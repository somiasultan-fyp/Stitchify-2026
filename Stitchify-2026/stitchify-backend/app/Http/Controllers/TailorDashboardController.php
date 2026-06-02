<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tailor;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TailorDashboardController extends Controller
{
    public function index()
    {
        $tailor = auth()->user()->tailor;

        $orders = Order::where('tailor_id', $tailor->id)
            ->with(['customer.user'])
            ->latest()
            ->get()
            ->groupBy('status');

        $stats = [
            'pending'     => $orders->get('pending', collect())->count(),
            'in_progress' => $orders->get('in_progress', collect())->count(),
            'ready'       => $orders->get('ready', collect())->count(),
            'dispatched'  => $orders->get('dispatched', collect())->count(),
            'delivered'   => $orders->get('delivered', collect())->count(),
            'slots_left'  => $tailor->available_slots,
        ];

        $pendingOrders   = $orders->get('pending', collect());
        $activeOrders    = $orders->get('in_progress', collect())
                            ->merge($orders->get('ready', collect()))
                            ->merge($orders->get('dispatched', collect()));
        $completedOrders = $orders->get('delivered', collect());

        return view('tailor.tailordashboard', compact(
            'tailor', 'stats', 'pendingOrders', 'activeOrders', 'completedOrders'
        ));
    }

    public function showOrder(Order $order)
    {
        $this->authorizeTailor($order);
        $order->load(['customer.user', 'measurement']);
        return view('tailor.order-detail', compact('order'));
    }

    public function acceptOrder(Request $request, Order $order)
    {
        $this->authorizeTailor($order);

        if ($order->status !== 'pending') {
            return back()->with('error', 'This order cannot be accepted now.');
        }

        $request->validate([
            'price'         => 'required|numeric|min:1',
            'delivery_days' => 'required|integer|min:1|max:60',
        ]);

        $tailor = auth()->user()->tailor;

        if ($tailor->available_slots <= 0) {
            return back()->with('error', 'You have no available slots.');
        }

        $order->update([
            'status'                 => 'accepted',
            'price'                  => $request->price,
            'delivery_days'          => $request->delivery_days,
            'expected_delivery_date' => Carbon::now()->addDays((int)$request->delivery_days),
            'accepted_at'            => now(),
        ]);

        $tailor->decrement('available_slots');

        // Create notification for customer
        Notification::create([
            'user_id' => $order->customer->user_id,
            'type' => 'order_accepted',
            'title' => 'Order Accepted',
            'message' => "Your order #{$order->id} has been accepted by tailor. Expected delivery: {$order->expected_delivery_date}",
            // 'order_id' => $order->id,
        ]);

        return back()->with('success', 'Order accepted successfully!');
    }

    public function rejectOrder(Request $request, Order $order)
    {
        $this->authorizeTailor($order);

        if ($order->status !== 'pending') {
            return back()->with('error', 'This order cannot be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $order->update([
            'status'           => 'cancelled',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at'      => now(),
        ]);

        // Create notification for customer
        Notification::create([
            'user_id' => $order->customer->user_id,
            'type' => 'order_rejected',
            'title' => 'Order Rejected',
            'message' => "Your order #{$order->id} has been rejected. Reason: {$request->rejection_reason}",
            // 'order_id' => $order->id,
        ]);

        return back()->with('success', 'Order has been rejected.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorizeTailor($order);

        $allowedTransitions = [
            'in_progress' => 'ready',
            'ready'       => 'dispatched',
            'dispatched'  => 'delivered',
        ];

        $nextStatus = $allowedTransitions[$order->status] ?? null;

        if (!$nextStatus) {
            return back()->with('error', 'Status update is not possible.');
        }

        $order->update(['status' => $nextStatus]);

        // Return slot when order is dispatched
        if ($nextStatus === 'delivered') {
            auth()->user()->tailor->increment('available_slots');
            
            Notification::create([
                'user_id' => $order->customer->user_id,
                'type' => 'order_dispatched',
                'title' => 'Order Dispatched',
                'message' => "Good news! Your order #{$order->id} has been delivered successfully!",
                'order_id' => $order->id,
            ]);
        }

        if ($nextStatus === 'delivered') {
            Notification::create([
                'user_id' => $order->customer->user_id,
                'type' => 'order_delivered',
                'title' => 'Order Delivered',
                'message' => "Your order #{$order->id} has been delivered. Please share your feedback!",
                'order_id' => $order->id,
            ]);
        }

        $statusLabels = [
            'ready' => 'Ready',
            'dispatched' => 'Dispatched',
            'delivered' => 'Delivered'
        ];
        
        $label = $statusLabels[$nextStatus] ?? ucfirst(str_replace('_', ' ', $nextStatus));

        return back()->with('success', "Status updated to: {$label}");
    }

    private function authorizeTailor(Order $order): void
    {
        if ($order->tailor_id !== auth()->user()->tailor->id) {
            abort(403, 'Unauthorized access to this order.');
        }
    }
}