<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Delivery;
use App\Models\Notification;
use App\Services\CommissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public static function createForOrder(Order $order): ?Delivery
    {
        if ($order->delivery_type !== 'home_delivery') {
            return null;
        }

        $existing = $order->delivery()->first();

        if ($existing) {
            return $existing;
        }

        $delivery = Delivery::create([
            'order_id'         => $order->id,
            'tracking_id'      => Delivery::generateTrackingId(),
            'type'             => 'home_delivery',
            'status'           => 'scheduled',
            'courier_name'     => 'Tailor Arranged Delivery',
            'pickup_address'   => $order->recipient_address ?? $order->customer->address ?? 'Customer Address',
            'delivery_address' => $order->tailor->address ?? 'Tailor Address',
            'estimated_date'   => $order->expected_delivery_date ?? now()->addDays(2),
        ]);

        $order->update([
            'tracking_id' => $delivery->tracking_id
        ]);

        return $delivery;
    }

    public function track(Order $order)
    {
        if ($order->customer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $delivery = $order->delivery;

        if (!$delivery) {
            return redirect('/customer/dashboard')
                ->with('error', 'No delivery record found for this order.');
        }

        return view('customer.tracking', compact('order', 'delivery'));
    }

    public function updateStatus(Request $request, Delivery $delivery)
    {
        $order = $delivery->order;

        $tailor = auth()->user()->tailor;
        if (
            auth()->user()->role !== 'tailor' ||
            !$tailor ||
            $order->tailor_id !== $tailor->id
        ) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'status' => 'required|in:scheduled,picked_up_from_customer,delivered_to_tailor,stitching_in_progress,picked_up_from_tailor,out_for_delivery,delivered',
            'notes'  => 'nullable|string|max:500',
        ]);

        $delivery->update([
            'status' => $request->status,
            'notes'  => $request->notes,
        ]);

        if ($request->status === 'delivered') {

            $order->update([
                'status'               => 'delivered',
                'actual_delivery_date' => now(),
            ]);

            CommissionService::releaseForOrder($order);
        }

        Notification::create([
            'user_id'    => $order->customer->user->id,
            'title'      => 'Delivery Update — ' . $delivery->tracking_id,
            'message'    => 'Your order status: ' . $delivery->status_label,
            'type'       => 'delivery',
            'action_url' => '/customer/track/' . $order->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Delivery status updated successfully.',
            'status'  => $delivery->status_label,
        ]);
    }

    public function getStatus(Order $order)
    {
        if ($order->customer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        $delivery = $order->delivery;

        if (!$delivery) {
            return response()->json([
                'found'   => false,
                'message' => 'No delivery record found.',
            ]);
        }

        return response()->json([
            'found'        => true,
            'tracking_id'  => $delivery->tracking_id,
            'status'       => $delivery->status,
            'status_label' => $delivery->status_label,
            'progress'     => $delivery->progress,
            'courier'      => $delivery->courier_name,
            'estimated'    => $delivery->estimated_date?->format('d M Y'),
        ]);
    }

    public function dashboard()
    {
        $deliveryBoy = Auth::user();

        $fabricPickups = Delivery::whereNull('delivery_boy_id')
            ->where('status', 'scheduled')
            ->whereHas('order', function ($query) use ($deliveryBoy) {
                $query->where('area', $deliveryBoy->area);
            })
            ->latest()
            ->get();

        $myDeliveries = Delivery::where('delivery_boy_id', $deliveryBoy->id)
            ->where('status', '!=', 'delivered')
            ->latest()
            ->get();

        $completedDeliveries = Delivery::where('delivery_boy_id', $deliveryBoy->id)
            ->where('status', 'delivered')
            ->latest()
            ->get();

        return view('delivery.dashboard', [
            'fabricPickups'       => $fabricPickups,
            'myDeliveries'        => $myDeliveries,
            'completedDeliveries' => $completedDeliveries,
        ]);
    }

    public function accept(Delivery $delivery)
    {
        if ($delivery->delivery_boy_id !== null) {
            return back()->with('error', 'This delivery has already been accepted by another delivery boy');
        }

        $delivery->update([
            'delivery_boy_id' => Auth::id(),
            'status'          => 'picked_up_from_customer',
        ]);

        return redirect()->route('delivery.order.show', $delivery)->with('success', 'Delivery accepted');
    }

    public function reject(Delivery $delivery)
    {
        return redirect()->route('delivery.dashboard')->with('success', 'Delivery skipped');
    }

    public function show(Delivery $delivery)
    {
        if ($delivery->delivery_boy_id !== Auth::id()) {
            abort(403);
        }

        return view('delivery.order-detail', [
            'delivery' => $delivery,
        ]);
    }

    public function updateBoyStatus(Request $request, Delivery $delivery)
    {
        if ($delivery->delivery_boy_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:delivered_to_tailor,picked_up_from_tailor,out_for_delivery,delivered',
        ]);

        $delivery->update([
            'status' => $request->status,
        ]);

        $order = $delivery->order;

        if ($request->status === 'delivered') {
            $order->update([
                'status'               => 'delivered',
                'actual_delivery_date' => now(),
            ]);

            CommissionService::releaseForOrder($order);
        }

        Notification::create([
            'user_id'    => $order->customer->user->id,
            'title'      => 'Delivery Update — ' . $delivery->tracking_id,
            'message'    => 'Your order status: ' . $delivery->status_label,
            'type'       => 'delivery',
            'action_url' => '/customer/track/' . $order->id,
        ]);

        return back()->with('success', 'Status updated');
    }
}