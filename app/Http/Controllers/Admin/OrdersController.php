<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['orderItems.product'])
            ->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_email', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('payment_status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return Inertia::render('Admin/Orders', [
            'orders'  => $orders,
            'filters' => $request->only(['search', 'status']),
            'admin'   => [
                'name'  => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,cancelled,expired',
        ]);

        $updateData = ['payment_status' => $validated['payment_status']];

        if ($validated['payment_status'] === 'paid' && !$order->paid_at) {
            $updateData['paid_at'] = now();
        }

        $order->update($updateData);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
