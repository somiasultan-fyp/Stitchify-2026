<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TailorDashboardController;
use App\Http\Controllers\CustomerOrderController;

Route::get('/', fn() => redirect('/login'));

// Auth Pages
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login.form');

// Auth Actions
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login',    [AuthController::class, 'login'])->name('login');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// Customer Dashboard
Route::middleware('auth')->group(function () {
    Route::get('/customer/dashboard', fn() => view('customer.customerdashboard'));
});

Route::post('/customer/place-order', [CustomerOrderController::class, 'placeOrder'])->name('customer.place.order')->middleware('auth');

// Tailor Dashboard
Route::middleware(['auth', 'role:tailor'])->prefix('tailor')->name('tailor.')->group(function () {
    Route::get('/dashboard', [TailorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders/{order}', [TailorDashboardController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{order}/accept', [TailorDashboardController::class, 'acceptOrder'])->name('orders.accept');
    Route::patch('/orders/{order}/reject', [TailorDashboardController::class, 'rejectOrder'])->name('orders.reject');
    Route::patch('/orders/{order}/status', [TailorDashboardController::class, 'updateStatus'])->name('orders.status');
});

// Order Form
Route::middleware('auth')->group(function () {
    Route::get('/order/place', [CustomerOrderController::class, 'showForm'])->name('order.form');
    Route::post('/order/place', [CustomerOrderController::class, 'placeOrder'])->name('order.place');
});