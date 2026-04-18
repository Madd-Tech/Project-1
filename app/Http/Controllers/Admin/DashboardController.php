<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
 
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'admin' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'stats' => [
                'productCount' => Product::count(),
                'categoryCount' => Category::count(),
                'totalStock' => Product::sum('stock'),
                'ordersToday' => 0, //the placeholder
            ]
        ]);
    }
}
