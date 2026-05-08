<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%');
        }

        return Inertia::render('Admin/Categories', [
            'categories' => $query->get(),
            'featuredCount' => Category::where('is_featured', true)->count(),
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
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }

    /**
     * Toggle featured status of a category.
     * Maximum 6 categories can be featured at a time.
     */
    public function toggleFeatured(Category $category)
    {
        if (!$category->is_featured) {
            // Check if we already have 6 featured categories
            $featuredCount = Category::where('is_featured', true)->count();
            if ($featuredCount >= 6) {
                return redirect()->back()->with('error', 'Maksimal 6 kategori yang bisa ditampilkan di halaman utama.');
            }

            // Set featured_order to next available
            $maxOrder = Category::where('is_featured', true)->max('featured_order') ?? 0;
            $category->update([
                'is_featured' => true,
                'featured_order' => $maxOrder + 1,
            ]);
        } else {
            $category->update([
                'is_featured' => false,
                'featured_order' => null,
            ]);
        }

        return redirect()->back()->with('success', 'Status tampil kategori berhasil diubah.');
    }
}
