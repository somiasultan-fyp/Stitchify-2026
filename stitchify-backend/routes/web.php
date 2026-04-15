<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Pages
Route::get('/',         fn() => redirect('/login'));
Route::get('/login',    fn() => view('login'));
Route::get('/register', fn() => view('register'));

// Auth Actions
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login',    [AuthController::class, 'login'])->name('login');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// Protected pages — login hona zaruri hai
Route::middleware('auth')->group(function () {
    Route::get('/customer/dashboard', fn() => view('customer.dashboard'));
    Route::get('/tailor/dashboard',   fn() => view('tailor.dashboard'));
});