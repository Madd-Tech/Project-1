<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(5)->withQueryString();
        $categories = Category::orderBy('name', 'asc')->get();

        return Inertia::render('Admin/Product', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['search']),
            'admin' => [
                'name' => \Illuminate\Support\Facades\Auth::user()->name,
                'email' => \Illuminate\Support\Facades\Auth::user()->email,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['slug'] = Str::slug($validated['name']) . '-' . uniqid();

        Product::create($validated);

        return redirect()->back()->with('success', 'Product created successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
           
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        } else {
            unset($validated['image']);
        }

        if ($product->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']) . '-' . uniqid();
        }

        $product->update($validated);

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
