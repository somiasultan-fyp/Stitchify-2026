<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', fn() => redirect('/login'));

// Auth Pages
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login.form');

// Auth Actions
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login',    [AuthController::class, 'login'])->name('login');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// Protected pages
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', fn() => view('customer.customerdashboard'));
});

Route::middleware(['auth', 'role:tailor'])->group(function () {
    Route::get('/tailor/dashboard', fn() => view('tailor.tailordashboard'));
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('admin.admindashboard'));
});