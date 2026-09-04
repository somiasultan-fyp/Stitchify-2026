<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TailorDashboardController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\TailorController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\PasswordResetController;

Route::get('/', function () {
    $topTailors = \App\Models\Tailor::with('user')
        ->where('status', 'approved')
        ->orderByDesc('experience_years')
        ->take(3)
        ->get();
        $reviews = \App\Models\Review::with('customer.user')->latest()->take(5)->get();

    return view('home', compact('topTailors', 'reviews'));
})->name('home');
Route::get('/tailors', [TailorController::class, 'index'])->name('tailors.index');
Route::get('/tailors/{id}',[TailorController::class, 'show'])->name('tailors.show');
Route::get('/tailors/category/{category}', [TailorController::class, 'byCategory'])->name('tailors.category');

Route::get('/register',  [AuthController::class, 'showRegister'])->name('register.form');
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/aboutus', fn() => view('aboutus'))->name('about');
Route::get('/contactus', fn() => view('contactus'))->name('contact');
Route::get('/terms', fn() => view('terms'))->name('terms');
Route::get('/privacy', fn() => view('privacy'))->name('privacy');


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
    return back()->with('status', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth','verified','role:customer'])->group(function () {
    Route::get('/customer/dashboard',  [CustomerOrderController::class, 'myOrders'])->name('customer.dashboard');
    Route::get('/customer/order-form', [CustomerOrderController::class, 'showForm'])->name('customer.order.form');
    Route::post('/order/store', [CustomerOrderController::class, 'placeOrder'])->name('order.store');
    Route::get('/customer/order/{order}', [CustomerOrderController::class, 'showOrder'])->name('customer.order.show');
    Route::post('/customer/order/{order}/cancel', [CustomerOrderController::class, 'cancelOrder'])->name('customer.order.cancel');
    Route::get('/customer/live-status', [CustomerOrderController::class, 'liveStatus'])->name('customer.live.status');
    Route::get('/payment/{order}', [PaymentController::class, 'show']) ->name('payment.show');
    Route::post('/payment/{order}/process',[PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/{order}/success',[PaymentController::class, 'success'])->name('payment.success');
    Route::get('/customer/track/{order}',[DeliveryController::class, 'track'])->name('delivery.track');
    Route::get('/customer/track/{order}/status',[DeliveryController::class, 'getStatus'])->name('delivery.status');
});

Route::middleware(['auth', 'verified', 'role:tailor'])->group(function () {
    Route::get('/tailor/dashboard',          [TailorController::class, 'dashboard'])->name('tailor.dashboard');
    Route::post('/tailor/order/{id}/accept', [TailorController::class, 'acceptOrder'])->name('tailor.order.accept');
    Route::post('/tailor/order/{id}/status', [TailorController::class, 'updateStatus'])->name('tailor.order.status');
    Route::post('/tailor/order/{id}/reject', [TailorController::class, 'rejectOrder'])->name('tailor.order.reject');
    Route::get('/tailor/order/{id}/detail',  [TailorController::class, 'orderDetail'])->name('tailor.order.detail');
    Route::get('/tailor/profile',            [TailorController::class, 'profile'])->name('tailor.profile');
    Route::post('/tailor/profile/update',    [TailorController::class, 'updateProfile'])->name('tailor.profile.update');
    Route::post('/tailor/portfolio/upload',  [TailorController::class, 'uploadPortfolio'])->name('tailor.portfolio.upload');
    Route::delete('/tailor/portfolio/{id}/delete', [TailorController::class, 'deletePortfolio'])->name('tailor.portfolio.delete');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
   Route::get('/admin/dashboard', function() {
    $stats = [
        'total_users'      => \App\Models\User::count(),
        'total_orders'     => \App\Models\Order::count(),
        'total_tailors'    => \App\Models\Tailor::count(),
        'total_customers'  => \App\Models\Customer::count(),
        'completed_orders' => \App\Models\Order::where('status', 'completed')->count(),
        'pending_orders'   => \App\Models\Order::where('status', 'pending')->count(),
        'blocked_users'    => \App\Models\User::where('is_active', false)->count(),
    ];
    $users      = \App\Models\User::whereIn('role', ['customer', 'tailor'])->paginate(10);
    $orders     = \App\Models\Order::with(['customer.user', 'tailor.user'])->latest()->paginate(10);
    $complaints = \App\Models\Complaint::with('user')->latest()->get();

    return view('admin.admindashboard', compact('stats', 'users', 'orders', 'complaints'));})->name('admin.dashboard');
    Route::patch('/admin/users/{user}/toggle', [\App\Http\Controllers\Admin\AdminController::class, 'toggleUser'])->name('admin.users.toggle');
    Route::patch('/admin/complaints/{complaint}/respond', [\App\Http\Controllers\Admin\AdminController::class, 'respondComplaint'])->name('admin.complaints.respond');
    Route::patch('/admin/delivery/{delivery}/status',[DeliveryController::class, 'updateStatus'])->name('delivery.update');
    Route::patch('/admin/tailors/{user}/approve', [\App\Http\Controllers\Admin\AdminController::class, 'approveTailor'])->name('admin.tailors.approve');
});
Route::post('/chatbot', [ChatbotController::class, 'reply'])
     ->name('chatbot.reply');

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/latest', [App\Http\Controllers\NotificationController::class, 'latest'])->name('notifications.latest');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.count');
    Route::patch('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.readAll');
});