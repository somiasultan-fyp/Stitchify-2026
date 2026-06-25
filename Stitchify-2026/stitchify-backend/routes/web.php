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


Route::get('/', fn() => view('home'))->name('home');
Route::get('/tailors',                   [TailorController::class, 'index'])->name('tailors.index');
Route::get('/tailors/{id}',              [TailorController::class, 'show'])->name('tailors.show');

Route::get('/register',  [AuthController::class, 'showRegister'])->name('register.form');
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


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

Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard',  [CustomerOrderController::class, 'myOrders'])->name('customer.dashboard');
    Route::get('/customer/order-form', [CustomerOrderController::class, 'create'])->name('customer.order.form');
    Route::post('/order/store', [CustomerOrderController::class, 'store'])->name('order.store');
});

Route::middleware(['auth', 'verified', 'role:tailor'])->group(function () {
    Route::get('/tailor/dashboard',          [TailorController::class, 'dashboard'])->name('tailor.dashboard');
    Route::post('/tailor/order/{id}/accept', [TailorController::class, 'acceptOrder'])->name('tailor.order.accept');
    Route::post('/tailor/order/{id}/status', [TailorController::class, 'updateStatus'])->name('tailor.order.status');
    Route::post('/tailor/order/{id}/reject', [TailorController::class, 'rejectOrder'])->name('tailor.order.reject');
    Route::get('/tailor/order/{id}/detail',  [TailorController::class, 'orderDetail'])->name('tailor.order.detail');
    Route::get('/tailor/profile',            [TailorController::class, 'profile'])->name('tailor.profile');
    Route::post('/tailor/profile/update',    [TailorController::class, 'updateProfile'])->name('tailor.profile.update');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
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
});
Route::post('/chatbot', [ChatbotController::class, 'reply'])
     ->name('chatbot.reply');
// Customer aur Tailor dono ke liye — auth ke andar
// Route::middleware(['auth', 'verified'])->group(function () {
//     // Sab notifications dekho
//     Route::get('/notifications',
//         [NotificationController::class, 'index'])->name('notifications.index');

//     // Ek notification read mark karo
//     Route::patch('/notifications/{notification}/read',
//         [NotificationController::class, 'markRead'])->name('notifications.read');

//     // Sab read mark karo
//     Route::patch('/notifications/read-all',
//         [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

//     // Unread count — AJAX ke liye
//     Route::get('/notifications/unread-count',
//         [NotificationController::class, 'unreadCount'])->name('notifications.count');
// });

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications/latest', [App\Http\Controllers\NotificationController::class, 'latest'])->name('notifications.latest');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.count');
    Route::patch('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.readAll');
});