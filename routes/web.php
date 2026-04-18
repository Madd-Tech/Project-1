<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\StockMovController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\OrderController;
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
    $categories = Category::withCount('products')->take(6)->get();

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
    
    if ($request->has('category') && $request->category !== 'All') {
        $query->whereHas('category', function($q) use ($request) {
            $q->where('name', $request->category);
        });
    }
    
    $products = $query->paginate(4)->withQueryString();
    $categories = Category::withCount('products')->get();
    
    return Inertia::render('Products', [
        'products' => $products,
        'categories' => $categories,
        'filters' => $request->only(['category'])
    ]);
});

// Product detail & reviews
Route::get('/products/{slug}', [ProductDetailController::class, 'show'])->name('product.show');
Route::post('/products/{slug}/review', [ProductDetailController::class, 'storeReview'])->name('product.review.store');

// Order routes (checkout is now a client-side WhatsApp redirect)
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout.index');
Route::get('/order/{product}', [OrderController::class, 'create'])->name('order.create');
Route::get('/order/status/{order}', [OrderController::class, 'status'])->name('order.status');
Route::post('/midtrans/notification', [OrderController::class, 'notification'])->name('midtrans.notification');

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
    Route::post('/stockmov', [StockMovController::class, 'store'])->name('stockmov.store');
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status', [OrdersController::class, 'updateStatus'])->name('orders.updateStatus');
});
