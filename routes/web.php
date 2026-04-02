<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VendorAccountController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PaymentController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/vendor/register', [VendorAccountController::class, 'showRegister'])->name('vendor.register');
Route::post('/vendor/register', [VendorAccountController::class, 'register'])->name('vendor.register.store');

// Customer routes (guest allowed)
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/vendor/{vendor}', [CustomerController::class, 'viewVendor'])->name('vendor-detail');
    Route::get('/cart', [CustomerController::class, 'cart'])->name('cart');
    Route::post('/cart/add', [CustomerController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/{menuId}', [CustomerController::class, 'updateCartItem'])->name('cart.update');
    Route::delete('/cart/{menuId}', [CustomerController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/clear', [CustomerController::class, 'clearCart'])->name('cart.clear');
    Route::get('/orders', [CustomerController::class, 'myOrders'])->name('my-orders');
    Route::get('/order/{pesanan}', [CustomerController::class, 'orderDetail'])->name('order-detail');
    Route::post('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
    Route::get('/payment/{pesanan}', [PaymentController::class, 'show'])->name('payment');
    Route::post('/payment/{pesanan}', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment-status/{pesanan}', [PaymentController::class, 'virtualAccountInfo'])->name('payment-status');
    Route::post('/payment/retry/{pesanan}', [PaymentController::class, 'retry'])->name('payment.retry');
});

// Protected routes
Route::middleware('auth')->group(function () {

    Route::prefix('admin')->name('admin.')->middleware('check.admin')->group(function () {
        Route::get('/vendors', [VendorAccountController::class, 'index'])->name('vendors.index');
        Route::post('/vendors', [VendorAccountController::class, 'store'])->name('vendors.store');
    });

    // Vendor routes
    Route::prefix('vendor')->name('vendor.')->middleware('check.vendor')->group(function () {
        Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
        Route::get('/menu', [VendorController::class, 'menuList'])->name('menu-list');
        Route::get('/menu/create', [VendorController::class, 'createMenu'])->name('create-menu');
        Route::post('/menu', [VendorController::class, 'storeMenu'])->name('store-menu');
        Route::get('/menu/{menu}/edit', [VendorController::class, 'editMenu'])->name('edit-menu');
        Route::put('/menu/{menu}', [VendorController::class, 'updateMenu'])->name('update-menu');
        Route::delete('/menu/{menu}', [VendorController::class, 'deleteMenu'])->name('delete-menu');
        
        Route::get('/orders', [VendorController::class, 'orders'])->name('orders');
        Route::get('/order/{pesanan}', [VendorController::class, 'orderDetail'])->name('order-detail');
        Route::put('/order/{pesanan}/status', [VendorController::class, 'updateOrderStatus'])->name('update-order-status');
        Route::post('/order/{pesanan}/confirm-payment', [PaymentController::class, 'confirmPayment'])->name('confirm-payment');
        
        Route::get('/sales', [VendorController::class, 'sales'])->name('sales');
    });
});

// Payment gateway webhook (tidak perlu auth)
Route::post('/webhook/payment', [PaymentController::class, 'webhook'])->name('webhook.payment');

