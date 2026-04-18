<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductDetailController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with(['category', 'reviews' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }])->where('slug', $slug)->where('status', 'active')->firstOrFail();

        $averageRating = round($product->reviews()->avg('rating') ?? 0, 1);
        $totalReviews  = $product->reviews()->count();
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $product->reviews()->where('rating', $i)->count();
            $ratingDistribution[$i] = [
                'count'      => $count,
                'percentage' => $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0,
            ];
        }

        return Inertia::render('ProductDetail', [
            'product'            => $product,
            'averageRating'      => $averageRating,
            'totalReviews'       => $totalReviews,
            'ratingDistribution' => $ratingDistribution,
        ]);
    }

    public function storeReview(Request $request, string $slug)
    {
        $product = Product::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:100',
            'reviewer_email'=> 'nullable|email|max:150',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'required|string|min:10|max:1000',
        ]);

        $validated['product_id'] = $product->id;

        if (auth()->check()) {
            $validated['user_id']    = auth()->id();
            $validated['is_verified']= true;
        }

        ProductReview::create($validated);

        return back()->with('success', 'Ulasan berhasil ditambahkan! Terima kasih.');
    }
}
