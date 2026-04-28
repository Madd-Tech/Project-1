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
        $products = Product::select('id', 'name', 'stock', 'image')->get();

        return Inertia::render('Admin/StockMov', [
            'movements' => $movements,
            'allProducts' => $products,
            'filters' => $request->only(['sort_by', 'date_from', 'date_to']),
            'admin' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:produk masuk,produk keluar',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        
        StockMove::create($validated);

        if ($validated['type'] === 'produk masuk') {
            $product->increment('stock', $validated['quantity']);
        } else {
            $product->decrement('stock', $validated['quantity']);
        }

        return redirect()->back()->with('success', 'Stock movement recorded successfully.');
    }

}
