<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMove;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(10)->withQueryString();

        return Inertia::render('Admin/Stock', [
            'products' => $products,
            'filters' => $request->only(['search']),
            'admin' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type' => 'required|in:produk masuk,produk keluar',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string|max:255',
        ]);

        // Prevent stock going negative
        if ($validated['type'] === 'produk keluar' && $product->stock < $validated['quantity']) {
            return redirect()->back()->withErrors([
                'quantity' => 'Stok tidak mencukupi. Stok saat ini: ' . $product->stock,
            ]);
        }

        // Adjust product stock
        if ($validated['type'] === 'produk masuk') {
            $product->increment('stock', $validated['quantity']);
        } else {
            $product->decrement('stock', $validated['quantity']);
        }

        // Record stock movement
        StockMove::create([
            'product_id' => $product->id,
            'type' => $validated['type'],
            'quantity' => $validated['quantity'],
            'reference' => $validated['reference'] ?? null,
        ]);

        $label = $validated['type'] === 'produk masuk' ? 'ditambahkan' : 'dikurangi';
        return redirect()->back()->with('success', "Stok {$product->name} berhasil {$label} sebanyak {$validated['quantity']}.");
    }
}
