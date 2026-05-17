<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockMove;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:100',
            'customer_phone'   => 'required|string|max:20',
            'customer_address' => 'required|string|max:500',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'payment_method'   => 'required|in:transfer,cod,datang_ke_toko',
            'notes'            => 'nullable|string|max:500',
            'items'            => 'required|array|min:1',
            'items.*.id'       => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $order = DB::transaction(function () use ($validated) {
                $totalAmount = 0;
                $orderItems = [];

                // Validate stock and build items
                foreach ($validated['items'] as $item) {
                    $product = Product::findOrFail($item['id']);

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi. Tersedia: {$product->stock}");
                    }

                    $subtotal = $product->price * $item['quantity'];
                    $totalAmount += $subtotal;

                    $orderItems[] = [
                        'product_id'   => $product->id,
                        'product_name' => $product->name,
                        'price'        => $product->price,
                        'quantity'     => $item['quantity'],
                        'subtotal'     => $subtotal,
                    ];

                    // Reduce stock
                    $product->decrement('stock', $item['quantity']);
                }

                // Create order
                $order = Order::create([
                    'order_number'     => Order::generateOrderNumber(),
                    'customer_id'      => Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null,
                    'customer_name'    => $validated['customer_name'],
                    'customer_phone'   => $validated['customer_phone'],
                    'customer_address' => $validated['customer_address'],
                    'latitude'         => $validated['latitude'] ?? null,
                    'longitude'        => $validated['longitude'] ?? null,
                    'payment_method'   => $validated['payment_method'],
                    'total_amount'     => $totalAmount,
                    'notes'            => $validated['notes'] ?? null,
                    'status'           => 'pending',
                ]);

                // Create order items
                foreach ($orderItems as $item) {
                    $order->items()->create($item);
                }

                // Record stock movements for sold products
                foreach ($orderItems as $item) {
                    StockMove::create([
                        'product_id' => $item['product_id'],
                        'type' => 'produk keluar',
                        'quantity' => $item['quantity'],
                        'reference' => 'Produk terjual - ' . $order->order_number,
                    ]);
                }

                return $order;
            });

            return redirect('/checkout/success/' . $order->order_number);
        } catch (\Exception $e) {
            return back()->withErrors(['order' => $e->getMessage()]);
        }
    }

    public function success(string $orderNumber)
    {
        $order = Order::with('items.product')->where('order_number', $orderNumber)->firstOrFail();

        return Inertia::render('OrderSuccess', [
            'order' => $order,
        ]);
    }
}
