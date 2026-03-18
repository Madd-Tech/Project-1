<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\StockMovController;
use App\Http\Controllers\OrderController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

use App\Models\Product;
use App\Models\Category;

Route::get('/', function () {
    $products = Product::with('category')->where('status', 'active')->latest()->take(8)->get();
    $categories = Category::withCount('products')->take(6)->get();
    return Inertia::render('Home', [
        'products' => $products,
        'categories' => $categories
    ]);
});

Route::get('/products', function () {
    $products = Product::with('category')->where('status', 'active')->latest()->get();
    $categories = Category::withCount('products')->get();
    return Inertia::render('Products', [
        'products' => $products,
        'categories' => $categories
    ]);
});

// Order routes
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout', [OrderController::class, 'storeCart'])->name('checkout.store');
Route::get('/order/{product}', [OrderController::class, 'create'])->name('order.create');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
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
    Route::post('products/{product}', [ProductsController::class, 'update'])->name('products.update'); // Override put due to multipart/form-data
    Route::resource('products', ProductsController::class)->except(['create', 'show', 'edit', 'update']);
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::put('/stock/{product}', [StockController::class, 'update'])->name('stock.update');
    Route::get('/stockmov', [StockMovController::class, 'index'])->name('stockmov.index');
    Route::post('/stockmov', [StockMovController::class, 'store'])->name('stockmov.store');
});
