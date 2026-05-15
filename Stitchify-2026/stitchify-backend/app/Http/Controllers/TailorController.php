<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Tailor;
use App\Models\User; 

class TailorController extends Controller
{
    public function index()
    {
        $tailors = User::where('role', 'tailor')
                       ->where('is_active', true)
                       ->get();

        return view('tailors.index', compact('tailors'));
    }

    // Single tailor profile
    public function show($id)
    {
        $tailor = User::where('id', $id)
                      ->where('role', 'tailor')
                      ->where('is_active', true)
                      ->firstOrFail();

        return view('tailors.show', compact('tailor'));
    }
    public function dashboard()
    {
        $user   = Auth::user();
        $tailor = $user->tailor;

        if (!$tailor) {
            return redirect('/login')->with('error', 'Tailor profile not found.');
        }

        $pendingOrders  = Order::where('tailor_id', $tailor->id)
                               ->where('status', 'pending')
                               ->with('customer.user')
                               ->latest()
                               ->get();

        $activeOrders   = Order::where('tailor_id', $tailor->id)
                               ->whereIn('status', ['accepted', 'in_progress'])
                               ->with('customer.user')
                               ->latest()
                               ->get();

        $completedCount = Order::where('tailor_id', $tailor->id)
                               ->where('status', 'delivered')
                               ->count();

        return view('tailor.tailordashboard', compact(
            'tailor',
            'pendingOrders',
            'activeOrders',
            'completedCount'
        ));
    }

    public function acceptOrder(Request $request, $orderId)
    {
        $tailor = Auth::user()->tailor;
        $order  = Order::where('id', $orderId)
                       ->where('tailor_id', $tailor->id)
                       ->firstOrFail();

        $order->update(['status' => 'accepted']);
        $tailor->decrementSlot();

        return response()->json([
            'success' => true,
            'message' => 'Order accepted successfully!',
        ]);
    }

    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:in_progress,ready,dispatched,delivered,cancelled'
        ]);

        $tailor    = Auth::user()->tailor;
        $order     = Order::where('id', $orderId)
                          ->where('tailor_id', $tailor->id)
                          ->firstOrFail();

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        if (in_array($request->status, ['delivered', 'cancelled'])
            && !in_array($oldStatus, ['delivered', 'cancelled'])) {
            $tailor->incrementSlot();
        }

        return response()->json([
            'success' => true,
            'message' => 'Order status updated!',
        ]);
    }

    public function orderDetail($orderId)
    {
        $tailor = Auth::user()->tailor;
        $order  = Order::where('id', $orderId)
                       ->where('tailor_id', $tailor->id)
                       ->with(['customer.user', 'measurement'])
                       ->firstOrFail();

        return response()->json([
            'success' => true,
            'order'   => [
                'id'                   => $order->id,
                'order_number'         => $order->order_number,
                'customer_name'        => $order->customer->user->name,
                'customer_phone'       => $order->customer->user->phone,
                'dress_type'           => $order->dress_type,
                'fabric_details'       => $order->fabric_details,
                'special_instructions' => $order->special_instructions,
                'status'               => $order->status,
                'created_at'           => $order->created_at->format('M d, Y'),
                'measurement'          => $order->measurement,
            ]
        ]);
    }
}
