<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\StockMovController;
use App\Http\Controllers\Admin\ReviewsController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\CustomerProfileController;

use App\Http\Controllers\ProductDetailController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

use App\Models\Product;
use App\Models\Category;

Route::get('/', function (\Illuminate\Http\Request $request) {
    $query = Product::with('category')->withAvg('reviews', 'rating')->withCount('reviews')->where('status', 'active')->latest();
    
    if ($request->has('search') && $request->search != '') {
        $query->where('name', 'like', '%' . $request->search . '%');
    }
    
    if ($request->has('category') && $request->category !== 'All') {
        $query->whereHas('category', function($q) use ($request) {
            $q->where('name', $request->category);
        });
    }

    $products = $query->paginate(4)->withQueryString();
    $categories = Category::where('is_featured', true)->withCount('products')->orderBy('featured_order')->get();

    $testimonials = \App\Models\ProductReview::with('product')
        ->where('rating', '>=', 4)
        ->inRandomOrder()
        ->limit(6)
        ->get();

    return Inertia::render('Home', [
        'products' => $products,
        'categories' => $categories,
        'testimonials' => $testimonials,
        'filters' => $request->only(['search', 'category'])
    ]);
});

Route::get('/products', function (\Illuminate\Http\Request $request) {
    $query = Product::with('category')->withAvg('reviews', 'rating')->withCount('reviews')->where('status', 'active')->latest();

    if ($request->has('search') && $request->search != '') {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->has('category') && $request->category !== 'All') {
        $query->whereHas('category', function($q) use ($request) {
            $q->where('name', $request->category);
        });
    }

    $products = $query->paginate(12)->withQueryString();
    $categories = Category::withCount('products')->get();

    return Inertia::render('Products', [
        'products'   => $products,
        'categories' => $categories,
        'filters'    => $request->only(['search', 'category'])
    ]);
});

// Product detail & reviews
Route::get('/products/{slug}', [ProductDetailController::class, 'show'])->name('product.show');
Route::post('/products/{slug}/review', [ProductDetailController::class, 'storeReview'])->name('product.review.store');

// Checkout & Orders
Route::middleware('auth:customer')->group(function () {
    Route::get('/checkout', function () {
        return Inertia::render('Checkout');
    })->name('checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{orderNumber}', [OrderController::class, 'success'])->name('checkout.success');
    Route::get('/customer/profile', [CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
    Route::put('/customer/profile/name', [CustomerProfileController::class, 'updateName'])->name('customer.profile.updateName');
    Route::put('/customer/profile/password', [CustomerProfileController::class, 'updatePassword'])->name('customer.profile.updatePassword');
    Route::delete('/customer/profile', [CustomerProfileController::class, 'destroy'])->name('customer.profile.destroy');
});

// Customer Auth
Route::get('/customer/auth', [CustomerAuthController::class, 'show'])->name('customer.auth');
Route::post('/customer/login', [CustomerAuthController::class, 'login'])->name('customer.login');
Route::post('/customer/register', [CustomerAuthController::class, 'register'])->name('customer.register');
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

// FAQ page
Route::get('/faq', function () {
    $footerCategories = Category::orderBy('id')->limit(5)->get(['id', 'name']);
    return Inertia::render('Faq', [
        'footerCategories' => $footerCategories,
    ]);
})->name('faq');



Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoriesController::class)->except(['create', 'show', 'edit']);
    Route::post('products/{product}', [ProductsController::class, 'update'])->name('products.update'); 
    Route::resource('products', ProductsController::class)->except(['create', 'show', 'edit', 'update']);
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::put('/stock/{product}', [StockController::class, 'update'])->name('stock.update');
    Route::get('/stockmov', [StockMovController::class, 'index'])->name('stockmov.index');
    Route::post('/categories/{category}/toggle-featured', [CategoriesController::class, 'toggleFeatured'])->name('categories.toggleFeatured');
    Route::get('/reviews', [ReviewsController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [ReviewsController::class, 'destroy'])->name('reviews.destroy');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
    
    // Customers
    Route::get('/customers', [CustomersController::class, 'index'])->name('customers.index');
    Route::delete('/customers/{customer}', [CustomersController::class, 'destroy'])->name('customers.destroy');
});
