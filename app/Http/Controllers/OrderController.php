<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Show the order/checkout page for a single product (legacy).
     */
    public function create(Product $product)
    {
        $product->load('category');

        return Inertia::render('Order', [
            'product' => $product,
        ]);
    }

    /**
     * Show the cart checkout page (multi-product).
     */
    public function checkout()
    {
        return Inertia::render('Checkout');
    }

    /**
     * Store the order and create Midtrans payment (single product - legacy).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:bank_transfer,qris',
            'payment_bank' => 'nullable|required_if:payment_method,bank_transfer|in:bca,bni,bri,mandiri,permata',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Stok tidak mencukupi.']);
        }

        $quantity = $validated['quantity'];
        $unitPrice = $product->price;
        $subtotal = $unitPrice * $quantity;
        $tax = $subtotal * 0.11;
        $totalAmount = $subtotal + $tax;

        $orderNumber = Order::generateOrderNumber();

        $order = DB::transaction(function () use ($validated, $product, $quantity, $unitPrice, $subtotal, $tax, $totalAmount, $orderNumber) {
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'payment_bank' => $validated['payment_bank'] ?? null,
                'midtrans_order_id' => $orderNumber,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
            ]);

            return $order;
        });

        return $this->processPayment($order, $validated);
    }

    /**
     * Store multi-product order from cart and create Midtrans payment.
     */
    public function storeCart(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'payment_method' => 'required|in:bank_transfer,qris,gopay',
            'payment_bank' => 'nullable|required_if:payment_method,bank_transfer|in:bca,bni,bri,mandiri,permata',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Validate stock for all items
        $products = Product::whereIn('id', collect($validated['items'])->pluck('id'))->get()->keyBy('id');

        foreach ($validated['items'] as $item) {
            $product = $products->get($item['id']);
            if (!$product) {
                return back()->withErrors(['items' => "Produk tidak ditemukan."]);
            }
            if ($product->stock < $item['quantity']) {
                return back()->withErrors(['items' => "Stok {$product->name} tidak mencukupi. Sisa: {$product->stock}"]);
            }
        }

        $orderNumber = Order::generateOrderNumber();
        $subtotal = 0;

        $order = DB::transaction(function () use ($validated, $products, $orderNumber, &$subtotal) {
            $orderItems = [];

            foreach ($validated['items'] as $item) {
                $product = $products->get($item['id']);
                $itemSubtotal = $product->price * $item['quantity'];
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $tax = $subtotal * 0.11;
            $totalAmount = $subtotal + $tax;

            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'payment_bank' => $validated['payment_bank'] ?? null,
                'midtrans_order_id' => $orderNumber,
            ]);

            foreach ($orderItems as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }

            return $order;
        });

        return $this->processPayment($order, $validated);
    }

    /**
     * Process payment with Midtrans.
     */
    private function processPayment(Order $order, array $validated)
    {
        $order->load('orderItems.product');

        $payload = $this->buildMidtransPayload($order, $validated);

        try {
            $response = Http::withBasicAuth(config('midtrans.server_key'), '')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post(config('midtrans.api_url') . '/charge', $payload);

            $body = $response->json();

            if ($response->successful() && isset($body['transaction_id'])) {
                $updateData = [
                    'midtrans_transaction_id' => $body['transaction_id'],
                    'midtrans_response' => $body,
                ];

                if ($validated['payment_method'] === 'bank_transfer') {
                    $vaNumber = $this->extractVaNumber($body, $validated['payment_bank'] ?? '');
                    $updateData['va_number'] = $vaNumber;
                } elseif ($validated['payment_method'] === 'qris') {
                    $updateData['qr_url'] = $body['actions'][0]['url'] ?? null;
                } elseif ($validated['payment_method'] === 'gopay') {
                    if (isset($body['actions'])) {
                        foreach ($body['actions'] as $action) {
                            if ($action['name'] === 'generate-qr-code') {
                                $updateData['qr_url'] = $action['url'];
                                break;
                            }
                        }
                    }
                }

                if (isset($body['expiry_time'])) {
                    $updateData['payment_expired_at'] = $body['expiry_time'];
                }

                $order->update($updateData);

                return redirect()->route('order.status', $order->id);
            } else {
                Log::error('Midtrans Charge Error', ['response' => $body]);
                $order->update([
                    'payment_status' => 'failed',
                    'midtrans_response' => $body,
                ]);

                return back()->withErrors(['payment' => $body['status_message'] ?? 'Pembayaran gagal. Silakan coba lagi.']);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans Exception', ['message' => $e->getMessage()]);
            $order->update(['payment_status' => 'failed']);

            return back()->withErrors(['payment' => 'Terjadi kesalahan saat memproses pembayaran.']);
        }
    }

    /**
     * Show the order status / payment instructions page.
     */
    public function status(Order $order)
    {
        $order->load(['product.category', 'orderItems.product']);

        return Inertia::render('OrderStatus', [
            'order' => $order,
        ]);
    }

    /**
     * Handle Midtrans webhook/notification.
     */
    public function notification(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans Notification', $payload);

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? 'accept';

        if (!$orderId) {
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $order = Order::where('midtrans_order_id', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $serverKey = config('midtrans.server_key');
        $signatureKey = hash('sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            $serverKey
        );

        if ($signatureKey !== ($payload['signature_key'] ?? '')) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept') {
                $order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'midtrans_response' => $payload,
                ]);

                // Decrease stock for all order items
                $order->load('orderItems');
                foreach ($order->orderItems as $item) {
                    Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
                }
            }
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $order->update([
                'payment_status' => $transactionStatus === 'expire' ? 'expired' : 'cancelled',
                'midtrans_response' => $payload,
            ]);
        } elseif ($transactionStatus === 'pending') {
            $order->update([
                'payment_status' => 'pending',
                'midtrans_response' => $payload,
            ]);
        }

        return response()->json(['message' => 'Notification processed']);
    }

    /**
     * Build the Midtrans charge payload (supports multi-item).
     */
    private function buildMidtransPayload(Order $order, array $validated): array
    {
        $itemDetails = [];

        if ($order->orderItems->count() > 0) {
            foreach ($order->orderItems as $orderItem) {
                $itemDetails[] = [
                    'id' => (string) $orderItem->product_id,
                    'price' => (int) $orderItem->unit_price,
                    'quantity' => $orderItem->quantity,
                    'name' => substr($orderItem->product->name, 0, 50),
                ];
            }
        } elseif ($order->product_id) {
            // Legacy single product
            $itemDetails[] = [
                'id' => (string) $order->product_id,
                'price' => (int) $order->unit_price,
                'quantity' => $order->quantity,
                'name' => substr($order->product->name ?? 'Product', 0, 50),
            ];
        }

        $itemDetails[] = [
            'id' => 'TAX',
            'price' => (int) $order->tax,
            'quantity' => 1,
            'name' => 'PPN 11%',
        ];

        $payload = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $validated['customer_name'],
                'email' => $validated['customer_email'],
                'phone' => $validated['customer_phone'],
                'billing_address' => [
                    'address' => $validated['customer_address'],
                ],
            ],
            'item_details' => $itemDetails,
        ];

        if ($validated['payment_method'] === 'bank_transfer') {
            $bank = $validated['payment_bank'];

            if ($bank === 'permata') {
                $payload['payment_type'] = 'permata';
            } else {
                $payload['payment_type'] = 'bank_transfer';
                $payload['bank_transfer'] = [
                    'bank' => $bank,
                ];
            }
        } elseif ($validated['payment_method'] === 'qris') {
            $payload['payment_type'] = 'qris';
        } elseif ($validated['payment_method'] === 'gopay') {
            $payload['payment_type'] = 'gopay';
        }

        return $payload;
    }

    /**
     * Extract VA number from Midtrans response.
     */
    private function extractVaNumber(array $response, string $bank): ?string
    {
        if ($bank === 'permata') {
            return $response['permata_va_number'] ?? null;
        }

        if (isset($response['va_numbers']) && count($response['va_numbers']) > 0) {
            return $response['va_numbers'][0]['va_number'] ?? null;
        }

        return null;
    }
}
