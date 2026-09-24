<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tailor;
use App\Models\Notification;
use App\Models\Payment;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
                               + $orders->get('fabric_received', collect())->count()
                               + $orders->get('in_progress', collect())->count()
                               + $orders->get('ready', collect())->count()
                               + $orders->get('dispatched', collect())->count()
                               + $orders->get('on_the_way', collect())->count(),
            'completed'       => $orders->get('delivered', collect())->count(),
        ];

        $pendingOrders = $orders->get('pending', collect());

        $activeOrders = $orders->get('accepted', collect())
            ->merge($orders->get('fabric_received', collect()))
            ->merge($orders->get('in_progress', collect()))
            ->merge($orders->get('ready', collect()))
            ->merge($orders->get('dispatched', collect()))
            ->merge($orders->get('on_the_way', collect()));

        $completedOrders = $orders->get('delivered', collect());

        $completedCount = $orders->get('delivered', collect())->count();

        $tailorPayments = Payment::whereIn(
            'order_id',
            Order::where('tailor_id', $tailor->id)->select('id')
        )->where('status', 'completed');

        $earnings = [
            'held'    => (clone $tailorPayments)->where('payout_status', 'held')->sum('tailor_amount'),
            'payable' => (clone $tailorPayments)->where('payout_status', 'payable')->sum('tailor_amount'),
            'paid'    => (clone $tailorPayments)->where('payout_status', 'paid')->sum('tailor_amount'),
        ];

        return view('tailor.tailordashboard', compact(
            'tailor',
            'stats',
            'pendingOrders',
            'activeOrders',
            'completedOrders',
            'completedCount',
            'reviews',
            'earnings'
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

        DeliveryController::createForOrder($order);

        $expectedDate = Carbon::parse($order->expected_delivery_date)->format('d M Y');

        $acceptedMessage = $order->delivery_type === 'home_delivery'
            ? "Your order #{$order->order_number} has been accepted. Our delivery courier is on the way to pick up your fabric. Expected delivery: {$expectedDate}."
            : "Your order #{$order->order_number} has been accepted. Please hand over your fabric to the tailor. Expected delivery: {$expectedDate}.";

        Notification::create([
            'user_id'  => $order->customer->user_id,
            'type'     => 'order_accepted',
            'title'    => 'Order Accepted',
            'message'  => $acceptedMessage,
            'order_id' => $order->id,
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

        $isPickup = $order->delivery_type === 'pickup';

        $allowedTransitions = [
            'accepted'        => 'fabric_received',
            'fabric_received' => 'in_progress',
            'in_progress'     => 'ready',
            'ready'           => $isPickup ? 'delivered' : 'dispatched',
            'dispatched'      => 'on_the_way',
            'on_the_way'      => 'delivered',
        ];

        $nextStatus = $allowedTransitions[$order->status] ?? null;

        if (!$nextStatus) {
            return response()->json([
                'success' => false,
                'message' => 'Status update is not possible.'
            ], 422);
        }

        if ($nextStatus === 'in_progress' && $order->payment_status === 'unpaid') {
            return response()->json([
                'success' => false,
                'message' => 'Stitching can start only after the customer completes the payment.'
            ], 422);
        }

        $updates = ['status' => $nextStatus];

        if ($nextStatus === 'delivered') {
            $updates['actual_delivery_date'] = now();
        }

        $order->update($updates);

        if ($nextStatus === 'delivered') {
            CommissionService::releaseForOrder($order);
        }

        $delivery = DeliveryController::createForOrder($order);

        if ($delivery) {
            $deliveryStatusMap = [
                'fabric_received' => 'delivered_to_tailor',
                'in_progress'     => 'stitching_in_progress',
                'dispatched'      => 'picked_up_from_tailor',
                'on_the_way'      => 'out_for_delivery',
                'delivered'       => 'delivered',
            ];

            if (isset($deliveryStatusMap[$nextStatus])) {
                $delivery->update([
                    'status' => $deliveryStatusMap[$nextStatus],
                ]);
            }
        }

        $notice = $this->customerNotice($order, $nextStatus);

        if ($notice) {
            Notification::create([
                'user_id'  => $order->customer->user_id,
                'type'     => $notice['type'],
                'title'    => $notice['title'],
                'message'  => $notice['message'],
                'order_id' => $order->id,
            ]);
        }

        $statusLabels = [
            'fabric_received' => 'Fabric Received',
            'in_progress'     => 'In Progress',
            'ready'           => 'Ready',
            'dispatched'      => 'Dispatched',
            'on_the_way'      => 'On the Way',
            'delivered'       => 'Delivered',
        ];

        $label = $statusLabels[$nextStatus] ?? ucfirst(str_replace('_', ' ', $nextStatus));

        return response()->json([
            'success' => true,
            'message' => "Status updated to: {$label}",
            'status'  => $nextStatus,
        ]);
    }

    private function customerNotice(Order $order, string $status): ?array
    {
        $number = $order->order_number;
        $isPickup = $order->delivery_type === 'pickup';

        return match ($status) {
            'fabric_received' => [
                'type'    => 'order',
                'title'   => 'Fabric Received',
                'message' => "The tailor has received your fabric for order #{$number}. Please complete the payment so stitching can begin.",
            ],
            'in_progress' => [
                'type'    => 'order',
                'title'   => 'Stitching Started',
                'message' => "Stitching for your order #{$number} has started.",
            ],
            'ready' => [
                'type'    => 'order',
                'title'   => 'Order Ready',
                'message' => $isPickup
                    ? "Your order #{$number} is ready. Please pick up your order from the tailor."
                    : "Your order #{$number} is ready and will be dispatched soon.",
            ],
            'dispatched' => [
                'type'    => 'order_dispatched',
                'title'   => 'Order Dispatched',
                'message' => "Your order #{$number} has been dispatched.",
            ],
            'on_the_way' => [
                'type'    => 'order_dispatched',
                'title'   => 'Order On the Way',
                'message' => "Your order #{$number} is on the way to you.",
            ],
            'delivered' => [
                'type'    => 'order_delivered',
                'title'   => 'Order Delivered',
                'message' => "Your order #{$number} has been delivered. Please share your feedback!",
            ],
            default => null,
        };
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