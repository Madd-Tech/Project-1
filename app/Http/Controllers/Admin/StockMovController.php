<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockMove;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class StockMovController extends Controller
{
   
    public function index(Request $request)
    {
        $query = StockMove::with('product');

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        switch ($request->input('sort_by', 'newest')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'a-z':
                $query->orderBy(
                    Product::select('name')
                        ->whereColumn('products.id', 'stock_moves.product_id')
                        ->limit(1),
                    'asc'
                );
                break;
            case 'z-a':
                $query->orderBy(
                    Product::select('name')
                        ->whereColumn('products.id', 'stock_moves.product_id')
                        ->limit(1),
                    'desc'
                );
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $movements = $query->paginate(10)->withQueryString();

        // Stats
        $stats = [
            'total'  => StockMove::count(),
            'masuk'  => StockMove::where('type', 'produk masuk')->count(),
            'keluar' => StockMove::where('type', 'produk keluar')->count(),
        ];

        return Inertia::render('Admin/StockMov', [
            'movements' => $movements,
            'stats' => $stats,
            'filters' => $request->only(['sort_by', 'date_from', 'date_to', 'type']),
            'admin' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ]);
    }
}
