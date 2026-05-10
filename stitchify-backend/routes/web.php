<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TailorController;

Route::get('/', fn() => view('home'));

// Auth Routes
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register.form');
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login',    [AuthController::class, 'login'])->name('login');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// Customer Routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard',  [OrderController::class, 'myOrders']);
    Route::get('/customer/order-form', [OrderController::class, 'create']);
    
});
Route::post('/order/store',        [OrderController::class, 'store']);
// Tailor Routes
Route::middleware(['auth', 'role:tailor'])->group(function () {
    Route::get('/tailor/dashboard',          [TailorController::class, 'dashboard']);
    Route::post('/tailor/order/{id}/accept', [TailorController::class, 'acceptOrder']);
    Route::post('/tailor/order/{id}/status', [TailorController::class, 'updateStatus']);
    Route::get('/tailor/order/{id}/detail',  [TailorController::class, 'orderDetail']);
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('admin.admindashboard'));
});