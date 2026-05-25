<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Tailor;
use App\Models\Customer;
use App\Models\Complaint;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ── Dashboard ─────────────────────────────────
    public function dashboard()
    {
        $stats = [
            // Total users
            'total_users'     => User::where('role', '!=', 'admin')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_tailors'   => User::where('role', 'tailor')->count(),

            // Orders
            'total_orders'    => Order::count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'active_orders'   => Order::whereIn('status',
                                    ['accepted', 'in_progress', 'ready'])->count(),
            'completed_orders'=> Order::where('status', 'delivered')->count(),

            // Revenue — advance payments
            'total_revenue'   => Order::where('payment_status', '!=', 'unpaid')
                                    ->sum('advance_paid'),

            // Complaints
            'open_complaints' => Complaint::where('status', 'open')->count(),

            // Blocked users
            'blocked_users'   => User::where('is_active', false)->count(),
        ];

        // Recent 5 orders
        $recentOrders = Order::with(['customer.user', 'tailor.user'])
            ->latest()
            ->take(5)
            ->get();

        // Recent 5 users
        $recentUsers = User::where('role', '!=', 'admin')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.admindashboard',
            compact('stats', 'recentOrders', 'recentUsers'));
    }

    // ── All Users ────────────────────────────────
    public function users()
    {
        // Filter by role
        $role = request('role', 'all');

        $query = User::where('role', '!=', 'admin');

        if ($role !== 'all') {
            $query->where('role', $role);
        }

        // Search by name or email
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->latest()->paginate(15);

        return view('admin.adminusers', compact('users', 'role'));
    }

    // ── Block / Unblock User ─────────────────────
    public function toggleUser(User $user)
    {
        // Admin ko block nahi kar sakte
        if ($user->role === 'admin') {
            return back()->with('error', 'Admin ko block nahi kar sakte.');
        }

        // Toggle — agar active hai toh inactive, warna active
        $user->update(['is_active' => !$user->is_active]);

        $action = $user->is_active ? 'unblocked' : 'blocked';

        return back()->with('success',
            "User successfully $action ho gaya.");
    }

    // ── All Orders ───────────────────────────────
    public function orders()
    {
        $status = request('status', 'all');

        $query = Order::with(['customer.user', 'tailor.user']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders', compact('orders', 'status'));
    }

    // ── Complaints ───────────────────────────────
    public function complaints()
    {
        $complaints = Complaint::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.complaints', compact('complaints'));
    }

    // ── Complaint Response ───────────────────────
    public function respondComplaint(Complaint $complaint, \Illuminate\Http\Request $request)
    {
        $request->validate([
            'admin_response' => 'required|string|max:1000',
            'status'         => 'required|in:in_review,resolved,closed',
        ]);

        $complaint->update([
            'admin_response' => $request->admin_response,
            'status'         => $request->status,
        ]);

        return back()->with('success', 'Response save ho gaya.');
    }
}
