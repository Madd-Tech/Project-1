<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\StockMove;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items.product')->latest();

        // Search by order number, customer name, or phone
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method != '') {
            $query->where('payment_method', $request->payment_method);
        }

        // Sort
        $sortBy = $request->sort_by ?? 'newest';
        switch ($sortBy) {
            case 'oldest':
                $query->reorder()->oldest();
                break;
            case 'amount_high':
                $query->reorder()->orderBy('total_amount', 'desc');
                break;
            case 'amount_low':
                $query->reorder()->orderBy('total_amount', 'asc');
                break;
            default: // newest
                break;
        }

        $orders = $query->paginate(10)->withQueryString();

        // Stats
        $stats = [
            'total'      => Order::count(),
            'pending'    => Order::where('status', 'pending')->count(),
            'confirmed'  => Order::where('status', 'confirmed')->count(),
            'completed'  => Order::where('status', 'completed')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        return Inertia::render('Admin/Orders', [
            'orders'  => $orders,
            'admin'   => [
                'name'  => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'filters' => $request->only(['search', 'status', 'payment_method', 'sort_by']),
            'stats'   => $stats,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,completed,cancelled',
        ]);

        if (str_contains((string) $order->notes, '[Cancelled by customer]')) {
            return back()->withErrors([
                'status' => "Status pesanan {$order->order_number} tidak dapat diubah karena dibatalkan oleh customer.",
            ]);
        }

        // If cancelling, restore stock
        if ($validated['status'] === 'cancelled' && $order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                    StockMove::create([
                        'product_id' => $item->product_id,
                        'type' => 'produk masuk',
                        'quantity' => $item->quantity,
                        'reference' => 'Pembatalan pesanan - ' . $order->order_number,
                    ]);
                }
            }
        }

        // If un-cancelling (reverting from cancelled), reduce stock again
        if ($order->status === 'cancelled' && $validated['status'] !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity);
                    StockMove::create([
                        'product_id' => $item->product_id,
                        'type' => 'produk keluar',
                        'quantity' => $item->quantity,
                        'reference' => 'Pesanan diaktifkan kembali - ' . $order->order_number,
                    ]);
                }
            }
        }

        $order->update(['status' => $validated['status']]);

        return back()->with('success', "Status pesanan {$order->order_number} berhasil diubah menjadi {$validated['status']}.");
    }

    public function destroy(Order $order)
    {
        $orderNumber = $order->order_number;

        // Restore stock if not already cancelled
        if ($order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                    StockMove::create([
                        'product_id' => $item->product_id,
                        'type' => 'produk masuk',
                        'quantity' => $item->quantity,
                        'reference' => 'Pesanan dihapus - ' . $order->order_number,
                    ]);
                }
            }
        }

        $order->delete();

        return back()->with('success', "Pesanan {$orderNumber} berhasil dihapus.");
    }
}
