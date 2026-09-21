<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tailor;
use App\Models\Notification;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TailorDashboardController extends Controller
{
    public function index()
    {
        $tailor = auth()->user()->tailor;

        $orders = Order::where('tailor_id', $tailor->id)
            ->with(['customer.user', 'delivery', 'measurement'])
            ->latest()
            ->get()
            ->groupBy('status');

        $reviews = \App\Models\Review::where('tailor_id', $tailor->id)
            ->with('customer.user')
            ->latest()
            ->get();    

        $stats = [
            'pending'         => $orders->get('pending', collect())->count(),
            'in_progress'     => $orders->get('in_progress', collect())->count(),
            'ready'           => $orders->get('ready', collect())->count(),
            'dispatched'      => $orders->get('dispatched', collect())->count(),
            'delivered'       => $orders->get('delivered', collect())->count(),
            'slots_left'      => $tailor->available_slots,
            'available_slots' => $tailor->available_slots,
            'max_slots'       => $tailor->max_slots ?? 20,
            'active'          => $orders->get('accepted', collect())->count()
                               + $orders->get('in_progress', collect())->count()
                               + $orders->get('ready', collect())->count()
                               + $orders->get('dispatched', collect())->count(),
            'completed'       => $orders->get('delivered', collect())->count(),
        ];

        $pendingOrders = $orders->get('pending', collect());

        $activeOrders = $orders->get('accepted', collect())
            ->merge($orders->get('in_progress', collect()))
            ->merge($orders->get('ready', collect()))
            ->merge($orders->get('dispatched', collect()));

        $completedOrders = $orders->get('delivered', collect());

        $completedCount = $orders->get('delivered', collect())->count();

        return view('tailor.tailordashboard', compact(
            'tailor',
            'stats',
            'pendingOrders',
            'activeOrders',
            'completedOrders',
            'completedCount',
            'reviews'
        ));
    }

    public function showOrder(Order $order, Request $request)
{
    $this->authorizeTailor($order);
    $order->load(['customer.user', 'measurement', 'delivery']);

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'order'   => array_merge($order->toArray(), [
            'design_image' => $order->design_image ? Storage::url($order->design_image) : null,
            ]),
        ]);
    }

    return view('tailor.order-detail', compact('order'));
}

    public function acceptOrder(Request $request, Order $order)
    {
        $this->authorizeTailor($order);

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This order cannot be accepted now.'
            ], 422);
        }

        $request->validate([
            'price'         => 'required|numeric|min:1',
            'delivery_days' => 'required|integer|min:1|max:60',
        ]);

        $tailor = auth()->user()->tailor;

        if ($tailor->available_slots <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'You have no available slots.'
            ], 422);
        }

        $order->update([
            'status'                 => 'accepted',
            'price'                  => $request->price,
            'delivery_days'          => $request->delivery_days,
            'expected_delivery_date' => Carbon::now()->addDays(
                (int) $request->delivery_days
            ),
            'accepted_at'            => now(),
        ]);

        $tailor->decrement('available_slots');

        Notification::create([
            'user_id' => $order->customer->user_id,
            'type'    => 'order_accepted',
            'title'   => 'Order Accepted',
            'message' => "Your order #{$order->id} has been accepted by tailor. Expected delivery: {$order->expected_delivery_date}",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order accepted successfully!'
        ]);
    }

    public function rejectOrder(Request $request, Order $order)
    {
        $this->authorizeTailor($order);

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This order cannot be rejected.'
            ], 422);
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $order->update([
            'status'           => 'cancelled',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at'      => now(),
        ]);

        Notification::create([
            'user_id' => $order->customer->user_id,
            'type'    => 'order_rejected',
            'title'   => 'Order Rejected',
            'message' => "Your order #{$order->id} has been rejected. Reason: {$request->rejection_reason}",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order has been rejected.'
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorizeTailor($order);

        $allowedTransitions = [
            'accepted'    => 'in_progress',
            'in_progress' => 'ready',
            'ready'       => 'dispatched',
            'dispatched'  => 'delivered',
        ];

        $nextStatus = $allowedTransitions[$order->status] ?? null;

        if (!$nextStatus) {
            return response()->json([
                'success' => false,
                'message' => 'Status update is not possible.'
            ], 422);
        }

        $order->update([
            'status' => $nextStatus
        ]);

        if ($nextStatus === 'delivered') {

            Notification::create([
                'user_id'  => $order->customer->user_id,
                'type'     => 'order_dispatched',
                'title'    => 'Order Dispatched',
                'message'  => "Good news! Your order #{$order->id} has been delivered successfully!",
                'order_id' => $order->id,
            ]);

            Notification::create([
                'user_id'  => $order->customer->user_id,
                'type'     => 'order_delivered',
                'title'    => 'Order Delivered',
                'message'  => "Your order #{$order->id} has been delivered. Please share your feedback!",
                'order_id' => $order->id,
            ]);
        }

        $statusLabels = [
            'in_progress' => 'In Progress',
            'ready'       => 'Ready',
            'dispatched'  => 'Dispatched',
            'delivered'   => 'Delivered',
        ];

        $label = $statusLabels[$nextStatus]
            ?? ucfirst(str_replace('_', ' ', $nextStatus));

        return response()->json([
            'success' => true,
            'message' => "Status updated to: {$label}"
        ]);
    }

    private function authorizeTailor(Order $order): void
    {
        if (
            !$order->tailor_id ||
            !auth()->user()->tailor ||
            $order->tailor_id !== auth()->user()->tailor->id
        ) {
            abort(403, 'Unauthorized access to this order.');
        }
    }
}