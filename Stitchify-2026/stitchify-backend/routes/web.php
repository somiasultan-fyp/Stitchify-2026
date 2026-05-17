<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TailorDashboardController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\TailorController;

// ─────────────────────────────────────────
// PUBLIC ROUTES
// ─────────────────────────────────────────
Route::get('/', fn() => view('home'))->name('home');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/contact', fn() => view('contact'))->name('contact');
Route::get('/terms', fn() => view('terms'))->name('terms');

// ─────────────────────────────────────────
// AUTH ROUTES
// ─────────────────────────────────────────
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─────────────────────────────────────────
// EMAIL VERIFICATION
// ─────────────────────────────────────────
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    $user = auth()->user();
    return match($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'tailor' => redirect()->route('tailor.dashboard'),
        default => redirect()->route('customer.dashboard'),
    };
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ─────────────────────────────────────────
// CUSTOMER ROUTES ✅ FIXED
// ─────────────────────────────────────────
Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        
        Route::get('/dashboard', [CustomerOrderController::class, 'myOrders'])->name('dashboard');
        
        // ✅ Order Routes - Yeh 404 fix karenge
        Route::get('/orders', [CustomerOrderController::class, 'myOrders'])->name('orders.index');
        Route::get('/orders/create', [CustomerOrderController::class, 'showForm'])->name('orders.create');
        Route::post('/orders', [CustomerOrderController::class, 'placeOrder'])->name('orders.store');
        Route::get('/orders/{order}', [CustomerOrderController::class, 'showOrder'])->name('orders.show');
        
        // Actions
        Route::patch('/orders/{order}/cancel', [CustomerOrderController::class, 'cancelOrder'])->name('orders.cancel');
        Route::get('/orders/live-status', [CustomerOrderController::class, 'liveStatus'])->name('orders.live');
});

// ─────────────────────────────────────────
// TAILOR ROUTES
// ─────────────────────────────────────────
Route::middleware(['auth', 'role:tailor'])
    ->prefix('tailor')
    ->name('tailor.')
    ->group(function () {
        
        Route::get('/dashboard', [TailorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [TailorDashboardController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [TailorDashboardController::class, 'showOrder'])->name('orders.show');
        Route::patch('/orders/{order}/accept', [TailorDashboardController::class, 'acceptOrder'])->name('orders.accept');
        Route::patch('/orders/{order}/reject', [TailorDashboardController::class, 'rejectOrder'])->name('orders.reject');
        Route::patch('/orders/{order}/status', [TailorDashboardController::class, 'updateStatus'])->name('orders.status');
});

// ─────────────────────────────────────────
// ADMIN ROUTES
// ─────────────────────────────────────────
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('admin.admindashboard'))->name('dashboard');
});

// ─────────────────────────────────────────
// FALLBACK
// ─────────────────────────────────────────
Route::fallback(fn() => response()->view('errors.404', [], 404));