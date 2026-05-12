<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tailor;
use App\Models\Measurement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerOrderController extends Controller
{
    // Order form dikhao
    public function showForm(Request $request)
    {
        $tailor_id = $request->query('tailor_id');
        $tailor = Tailor::with('user')->findOrFail($tailor_id);

        if (!$tailor->hasAvailableSlot()) {
            return back()->with('error', 'Is tailor ke slots full hain.');
        }

        return view('customer.order-form', compact('tailor'));
    }

    // Order submit karo
    public function placeOrder(Request $request)
    {
        $request->validate([
            'tailor_id'          => 'required|exists:tailors,id',
            'dress_type'         => 'required|string',
            'fabric_name'        => 'required|string',
            'fabric_color'       => 'required|string',
            'fabric_provided_by' => 'required|in:customer,tailor',
            'delivery_type'      => 'required|in:pickup,home_delivery',
            'special_instructions' => 'nullable|string',
        ]);

        $tailor = Tailor::findOrFail($request->tailor_id);

        // Slot check
        if (!$tailor->hasAvailableSlot()) {
            return back()->with('error', 'Is tailor ke slots full hain. Doosra tailor choose karein.');
        }

        $customer = auth()->user()->customer;

        // Order banao
        $order = Order::create([
            'order_number'         => Order::generateOrderNumber(),
            'customer_id'          => $customer->id,
            'tailor_id'            => $tailor->id,
            'dress_type'           => $request->dress_type,
            'fabric_details'       => $request->fabric_name . ' - ' . $request->fabric_color,
            'fabric_provided_by'   => $request->fabric_provided_by,
            'special_instructions' => $request->special_instructions,
            'delivery_type'        => $request->delivery_type,
            'status'               => 'pending',
            'payment_status'       => 'unpaid',
        ]);

        // Measurements save karo (agar manual bhari hain)
        if ($request->measurement_method === 'manual') {
            Measurement::create([
                'order_id'        => $order->id,
                'chest'           => $request->chest,
                'waist'           => $request->waist,
                'hips'            => $request->hips,
                'shoulder'        => $request->shoulder,
                'sleeve_length'   => $request->sleeve_length,
                'shirt_length'    => $request->shirt_length,
                'trouser_length'  => $request->trouser_length,
                'trouser_waist'   => $request->trouser_waist,
                'neck'            => $request->neck,
                'additional_notes'=> $request->special_instructions,
            ]);
        }

        return redirect()->route('customer.dashboard')
            ->with('success', 'Order place ho gaya! Tailor ka response ka intezaar karein.');
    }
}