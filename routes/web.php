<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\StockController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return Inertia::render('Home');
});

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
});
