<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CustomersController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount('orders')
            ->withSum('orders', 'total_amount')
            ->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $sortBy = $request->sort_by ?? 'newest';
        switch ($sortBy) {
            case 'oldest':
                $query->reorder()->oldest();
                break;
            case 'orders_high':
                $query->reorder()->orderBy('orders_count', 'desc');
                break;
            case 'amount_high':
                $query->reorder()->orderBy('orders_sum_total_amount', 'desc');
                break;
            default: // newest
                break;
        }

        $customers = $query->paginate(15)->withQueryString();

        $stats = [
            'total'        => Customer::count(),
            'with_orders'  => Customer::has('orders')->count(),
            'new_this_month' => Customer::whereMonth('created_at', now()->month)
                                         ->whereYear('created_at', now()->year)
                                         ->count(),
        ];

        return Inertia::render('Admin/Customers', [
            'customers' => $customers,
            'admin'     => [
                'name'  => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'filters' => $request->only(['search', 'sort_by']),
            'stats'   => $stats,
        ]);
    }

    public function destroy(Customer $customer)
    {
        $name = $customer->name;
        $customer->delete();
        return back()->with('success', "Pelanggan \"{$name}\" berhasil dihapus.");
    }
}
