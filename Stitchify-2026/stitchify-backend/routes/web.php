<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TailorController;

Route::get('/', fn() => view('home'))->name('home');
Route::get('/tailors',                   [TailorController::class, 'index'])->name('tailors.index');
Route::get('/tailors/{id}',              [TailorController::class, 'show'])->name('tailors.show');

Route::get('/register',  [AuthController::class, 'showRegister'])->name('register.form');
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login',    [AuthController::class, 'login'])->name('login');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');


Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect('/admin/dashboard')
            ->with('success', 'Email verified!');
    } elseif ($user->role === 'tailor') {
        return redirect('/tailor/dashboard')
            ->with('success', 'Email verified!');
    } else {
        return redirect('/customer/dashboard')
            ->with('success', 'Email verified!');
    }

})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Verification email dobara bhej di gayi!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard',  [OrderController::class, 'myOrders']);
    Route::get('/customer/order-form', [OrderController::class, 'create']);
});

Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
});

Route::middleware(['auth', 'verified', 'role:tailor'])->group(function () {
    Route::get('/tailor/dashboard',          [TailorController::class, 'dashboard']);
    Route::post('/tailor/order/{id}/accept', [TailorController::class, 'acceptOrder']);
    Route::post('/tailor/order/{id}/status', [TailorController::class, 'updateStatus']);
    Route::post('/tailor/order/{id}/reject', [TailorController::class, 'rejectOrder']);
    Route::get('/tailor/order/{id}/detail',  [TailorController::class, 'orderDetail']);
    Route::get('/tailor/profile',            [TailorController::class, 'profile'])->name('tailor.profile');
    Route::post('/tailor/profile/update',    [TailorController::class, 'updateProfile'])->name('tailor.profile.update');
});


Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('admin.admindashboard'));
});