<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Tailor;
use App\Models\User; 
use App\Models\Notification;

class TailorController extends Controller
{
    public function index()
{
    $tailors = Tailor::with('user')
                     ->where('available_slots', '>', 0)
                     ->get();

    return view('tailors.index', compact('tailors'));
}
    // Single tailor profile
    public function show($id)
{
    // Tailor ID fetch
    $tailor = Tailor::where('id', $id)
                    ->with(['user', 'portfolios'])
                    ->firstOrFail();

    return view('tailor.publicprofilepage', compact('tailor'));

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

        // Stats for dashboard cards
        $stats = [
            'pending'         => $pendingOrders->count(),
            'active'          => $activeOrders->count(),
            'completed'       => $completedCount,
            'available_slots' => $tailor->available_slots,
            'max_slots'       => $tailor->max_slots,
        ];

        return view('tailor.tailordashboard', compact(
            'tailor',
            'pendingOrders',
            'activeOrders',
            'completedCount',
            'stats'
        ));
    }

    public function acceptOrder(Request $request, $orderId)
    {
        $request->validate([
            'price'         => 'required|numeric|min:1',
            'delivery_days' => 'required|integer|min:1|max:60',
        ]);

        $tailor = Auth::user()->tailor;
        $order  = Order::where('id', $orderId)
                       ->where('tailor_id', $tailor->id)
                       ->where('status', 'pending')
                       ->firstOrFail();

        $order->update(['status' => 'accepted', 
        'price' => $request->price,
        'expected_delivery_date' => now()->addDays((int) $request->delivery_days)
        ]);

        $tailor->decrementSlot();

        // notification to customer
        Notification::create([
            'user_id' => $order->customer->user->id,
            'title'   => 'Order Accepted!',
            'message' => 'Tailor accept the order no. #' . $order->order_number .
                         'Price: Rs. ' . $request->price .
                         '. Expected delivery: ' .
                         now()->addDays((int) $request->delivery_days)->format('d M Y') .
                         '. You can pay now.',
            'type'       => 'order',
            'action_url' => '/customer/dashboard',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order accepted successfully!',
        ]);
    }
    // Order Rejection
    public function rejectOrder(Request $request, $orderId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
 
        $tailor = Auth::user()->tailor;
        $order  = Order::where('id', $orderId)
                       ->where('tailor_id', $tailor->id)
                       ->where('status', 'pending')
                       ->with('customer.user')
                       ->firstOrFail();
 
        // Order cancel
        $order->update([
            'status' => 'cancelled',
        ]);
 
        Notification::create([
            'user_id' => $order->customer->user->id,
            'title'   => 'Order Rejected',
            'message' => 'Sorry, your order #' . $order->order_number .
                         ' has been rejected. Reason: ' . $request->rejection_reason,
            'type'       => 'order',
            'action_url' => '/customer/dashboard',
        ]);
 
        return response()->json([
            'success' => true,
            'message' => 'Order rejected.',
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
        $newStatus = $request->status;

        $order->update(['status' => $request->status]);

        if (in_array($request->status, ['delivered', 'cancelled'])
            && !in_array($oldStatus, ['delivered', 'cancelled'])) {
            $tailor->incrementSlot();
        }
        if ($newStatus === 'delivered') {
            $order->update(['actual_delivery_date' => now()]);
        }

        $messages = [
            'in_progress' => 'Stitching in progress!',
            'ready'       => 'Your order is ready!',
            'dispatched'  => 'Your order has been dispatched!',
            'delivered'   => 'Your order has been delivered!',
            'cancelled'   => 'Your order has been cancelled.',
        ];

        if (isset($messages[$newStatus])) {
            Notification::create([
                'user_id'    => $order->customer->user->id,
                'title'      => 'Order Update — #' . $order->order_number,
                'message'    => $messages[$newStatus],
                'type'       => 'order',
                'action_url' => '/customer/dashboard',
            ]);
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
                'delivery_type'        => $order->delivery_type,
                'price'                => $order->price,
                'status'               => $order->status,
                'expected_delivery_date' => $order->expected_delivery_date
                ? \Carbon\Carbon::parse($order->expected_delivery_date)->format('M d, Y')
                : null,
                'created_at'           => $order->created_at->format('M d, Y'),
                'measurement'          => $order->measurement,
            ]
        ]);
    }
public function profile()
{
    $user   = Auth::user();
    $tailor = $user->tailor;

    return view('tailor.profile', compact('user', 'tailor'));
}
public function updateProfile(Request $request)
{
    $user   = Auth::user();
    $tailor = $user->tailor;

    $request->validate([
        'name'           => 'required|string|max:255',
        'phone'          => 'required|string|max:20',
        'address'        => 'required|string|max:500',
        'bio'            => 'nullable|string|max:1000',
        'city'         => 'nullable|string|max:100',
        'address'      => 'nullable|string|max:500',
        'experience_years' => 'nullable|integer|min:0',
        'specialization'   => 'nullable|string|max:255',
        'base_price'       => 'nullable|numeric|min:0',
        'max_slots'        => 'nullable|integer|min:0',
    ]);

    $user->update([
        'name'  => $request->name,
        'phone' => $request->phone,
    ]);

    $tailor->update([
        'address'         => $request->address,
        'bio'              => $request->bio,
        'city'             => $request->city,
        'address'          => $request->address,
        'experience_years' => $request->experience_years,
        'specialization'   => $request->specialization,
        'base_price'       => $request->base_price,
        'max_slots'        => $request->max_slots,
    ]);

    return redirect()->route('tailor.profile')->with('success', 'Profile updated successfully!');
}
}