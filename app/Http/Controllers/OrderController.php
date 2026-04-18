<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Single-product order page.
     */
    public function create(Product $product)
    {
        $product->load('category');

        return Inertia::render('Order', [
            'product' => $product,
        ]);
    }

    /**
     * Cart / multi-product checkout page.
     */
    public function checkout()
    {
        return Inertia::render('Checkout');
    }

    /**
     * Midtrans payment notification webhook (kept in case it is still needed).
     * This is now a no-op since ordering is done via WhatsApp.
     */
    public function notification(Request $request)
    {
        return response()->json(['message' => 'OK']);
    }

    /**
     * Order status page (kept for backward compatibility with existing links).
     */
    public function status(Order $order)
    {
        $order->load(['product.category', 'orderItems.product']);

        return Inertia::render('OrderStatus', [
            'order' => $order,
        ]);
    }
}
