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
    public function dashboard()
    {
        $stats = [
            'total_users'     => User::where('role', '!=', 'admin')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_tailors'   => User::where('role', 'tailor')->count(),
            'total_orders'    => Order::count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'active_orders'   => Order::whereIn('status',['accepted', 'in_progress', 'ready'])->count(),
            'completed_orders'=> Order::where('status', 'delivered')->count(),
            'total_revenue'   => Order::where('payment_status', '!=', 'unpaid')->sum('advance_paid'),
            'open_complaints' => Complaint::where('status', 'open')->count(),
            'blocked_users'   => User::where('is_active', false)->count(),
        ];

        $users = User::where('role', '!=', 'admin')->with('tailor')->latest()->paginate(15);
        $orders = Order::with(['customer.user', 'tailor.user'])->latest()->paginate(15);
        $complaints = Complaint::with('user')->latest()->get();

        $recentOrders = Order::with(['customer.user', 'tailor.user'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::where('role', '!=', 'admin')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.admindashboard',
            compact('stats','users', 'orders', 'complaints' , 'recentOrders' , 'recentUsers'));
    }

    public function users()
    {
        $role = request('role', 'all');

        $query = User::where('role', '!=', 'admin');

        if ($role !== 'all') {
            $query->where('role', $role);
        }

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

    public function toggleUser(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Admin accounts cannot be blocked.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $action = $user->is_active ? 'unblocked' : 'blocked';

        return back()->with('success',
            "User successfully $action.");
    }

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

    public function complaints()
    {
        $complaints = Complaint::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.complaints', compact('complaints'));
    }

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

        return back()->with('success', 'Response is submitted successfully.');
    }
    public function approveTailor(User $user)
    {
    if ($user->role !== 'tailor' || !$user->tailor) {
        return back()->with('error', 'This user is not a tailor.');
    }

    $user->tailor->update(['status' => 'approved']);

    return back()->with('success', "{$user->name} has been approved and can now receive orders.");
    }
}
