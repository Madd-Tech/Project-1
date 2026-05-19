<?php

namespace App\Http\Middleware;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Auth;

class HandleInertiaRequests extends Middleware
{

    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $customer = Auth::guard('customer')->user();
        $customerOrders = $customer
            ? Order::query()
                ->where('customer_id', $customer->id)
                ->latest('id')
                ->limit(10)
                ->get(['order_number', 'status', 'total_amount', 'created_at'])
            : collect();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'name'  => $request->user()->name,
                    'email' => $request->user()->email,
                    'role'  => $request->user()->role,
                ] : null,
                'customer' => $customer ? [
                    'id'      => $customer->id,
                    'name'    => $customer->name,
                    'email'   => $customer->email,
                    'phone'   => $customer->phone,
                    'address' => $customer->address,
                    'checkout_status' => $customerOrders->first() ? [
                        'order_number' => $customerOrders->first()->order_number,
                        'status'       => $customerOrders->first()->status,
                    ] : null,
                    'orders' => $customerOrders->map(fn ($order) => [
                        'order_number' => $order->order_number,
                        'status'       => $order->status,
                        'total_amount' => $order->total_amount,
                        'created_at'   => optional($order->created_at)->toISOString(),
                    ])->values(),
                ] : null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
            ],
        ];
    }
}
