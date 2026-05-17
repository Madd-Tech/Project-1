<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $categories = Category::orderBy('id')->limit(5)->get(['id', 'name']);

        // Append censored_name to each review
        $reviews = $product->reviews->map(function ($review) {
            return array_merge($review->toArray(), [
                'censored_name' => $review->censored_name,
            ]);
        });

        return Inertia::render('ProductDetail', [
            'product'            => array_merge($product->toArray(), ['reviews' => $reviews]),
            'averageRating'      => $averageRating,
            'totalReviews'       => $totalReviews,
            'ratingDistribution' => $ratingDistribution,
            'categories'         => $categories,
        ]);
    }

    public function storeReview(Request $request, string $slug)
    {
        // Must be logged in as customer
        if (!Auth::guard('customer')->check()) {
            $redirectToReviewSection = route('product.show', ['slug' => $slug]) . '#reviews';
            return redirect()->route('customer.auth', ['redirect' => $redirectToReviewSection])
                ->with('error', 'Login terlebih dahulu untuk memberikan ulasan.');
        }

        $customer = Auth::guard('customer')->user();
        $product  = Product::where('slug', $slug)->where('status', 'active')->firstOrFail();

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        ProductReview::create([
            'product_id'    => $product->id,
            'customer_id'   => $customer->id,
            'reviewer_name' => $customer->name,
            'rating'        => $validated['rating'],
            'comment'       => $validated['comment'],
            'is_verified'   => true,
        ]);

        return back()->with('success', 'Ulasan berhasil ditambahkan! Terima kasih.');
    }
}
