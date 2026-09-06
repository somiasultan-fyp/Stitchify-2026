<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Delivery;
use App\Models\Notification;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    //Delivery auto-create after payment
    public static function createAfterPayment(Order $order): void
    {
        if ($order->delivery_type !== 'home_delivery') {
            return;
        }

        if ($order->delivery()->exists()) {
            return;
        }

        $delivery = Delivery::create([
            'order_id'         => $order->id,
            'tracking_id'      => Delivery::generateTrackingId(),
            'type'             => 'home_delivery',
            'status'           => 'scheduled',
            'courier_name'     => 'Leopards Courier',
            'pickup_address'   => $order->customer->address ?? 'Customer Address',
            'delivery_address' => $order->tailor->address ?? 'Tailor Address',
            'estimated_date'   => now()->addDays(2),
        ]);

        $order->update(['tracking_id' => $delivery->tracking_id]);

        Notification::create([
            'user_id'    => $order->customer->user->id,
            'title'      => 'Delivery Scheduled!',
            'message'    => 'Your fabric pickup has been scheduled. Tracking ID: ' .
                           $delivery->tracking_id .
                           '. Our courier will contact you shortly.',
            'type'       => 'delivery',
            'action_url' => '/customer/dashboard',
        ]);

        Notification::create([
            'user_id'    => $order->tailor->user->id,
            'title'      => 'Fabric Coming Your Way!',
            'message'    => 'Fabric for order #' . $order->order_number .
                           ' will be delivered to you soon. Tracking: ' .
                           $delivery->tracking_id,
            'type'       => 'delivery',
            'action_url' => '/tailor/dashboard',
        ]);
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

    // Only the tailor assigned to this order can update delivery status
    if (auth()->user()->role !== 'tailor' ||
        !$authTailor = auth()->user()->tailor ||
        $order->tailor_id !== $authTailor->id) {
        abort(403, 'Unauthorized access.');
    }

    $request->validate([
        'status' => 'required|in:scheduled,picked_up_from_customer,
                     delivered_to_tailor,stitching_in_progress,
                     picked_up_from_tailor,out_for_delivery,delivered',
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

        $order->tailor->incrementSlot();
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
                'found'  => false,
                'message'=> 'No delivery record found.',
            ]);
        }

        return response()->json([
            'found'       => true,
            'tracking_id' => $delivery->tracking_id,
            'status'      => $delivery->status,
            'status_label'=> $delivery->status_label,
            'progress'    => $delivery->progress,
            'courier'     => $delivery->courier_name,
            'estimated'   => $delivery->estimated_date?->format('d M Y'),
        ]);
    }
}

