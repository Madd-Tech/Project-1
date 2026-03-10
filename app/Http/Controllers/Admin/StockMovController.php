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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movements = StockMove::with('product')->latest()->paginate(10);
        $products = Product::select('id', 'name', 'stock', 'image')->get();

        return Inertia::render('Admin/StockMov', [
            'movements' => $movements,
            'allProducts' => $products,
            'admin' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
