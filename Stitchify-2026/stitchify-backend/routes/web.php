<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TailorDashboardController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\TailorController;
use App\Http\Controllers\OrderController;

// ─────────────────────────────────────────
// PUBLIC ROUTES
// ─────────────────────────────────────────
Route::get('/', fn() => view('home'))->name('home');

// ─────────────────────────────────────────
// AUTH ROUTES
// ─────────────────────────────────────────
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register.form');
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login',    [AuthController::class, 'login'])->name('login');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// ─────────────────────────────────────────
// EMAIL VERIFICATION ROUTES
// ─────────────────────────────────────────
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    $user = auth()->user();
    return match($user->role) {
        'admin'    => redirect('/admin/dashboard')->with('success', 'Email verify ho gayi!'),
        'tailor'   => redirect('/tailor/dashboard')->with('success', 'Email verify ho gayi!'),
        default    => redirect('/customer/dashboard')->with('success', 'Email verify ho gayi!'),
    };
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Verification email dobara bhej di gayi!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ─────────────────────────────────────────
// CUSTOMER ROUTES
// ─────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerOrderController::class, 'myOrders'])->name('customer.dashboard');
    Route::get('/order/place',        [CustomerOrderController::class, 'showForm'])->name('order.form');
    Route::post('/order/place',       [CustomerOrderController::class, 'placeOrder'])->name('order.place');
    Route::post('/customer/place-order', [CustomerOrderController::class, 'placeOrder'])->name('customer.place.order');
});

// ─────────────────────────────────────────
// TAILOR ROUTES
// ─────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:tailor'])->prefix('tailor')->name('tailor.')->group(function () {
    Route::get('/dashboard',              [TailorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders/{order}',         [TailorDashboardController::class, 'showOrder'])->name('orders.show');
    Route::patch('/orders/{order}/accept',[TailorDashboardController::class, 'acceptOrder'])->name('orders.accept');
    Route::patch('/orders/{order}/reject',[TailorDashboardController::class, 'rejectOrder'])->name('orders.reject');
    Route::patch('/orders/{order}/status',[TailorDashboardController::class, 'updateStatus'])->name('orders.status');
    Route::get('/tailors',                [TailorController::class, 'index'])->name('tailors.index');
    Route::get('/tailors/{id}',           [TailorController::class, 'show'])->name('tailors.show');
});

// ─────────────────────────────────────────
// ADMIN ROUTES
// ─────────────────────────────────────────
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('admin.admindashboard'));
});