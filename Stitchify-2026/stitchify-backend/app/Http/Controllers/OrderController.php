<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Measurement;
use App\Models\Tailor;

class OrderController extends Controller
{
  
    public function create()
    {
        return view('customer.order-form');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
      
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User is not logged in.'
            ], 401);
        }

        $customer = $user->customer;

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer profile not found.'
            ], 400);
        }

        $tailor = Tailor::first();

        if (!$tailor) {
            return response()->json([
                'success' => false,
                'message' => 'No tailor found in the database. Please create a tailor record first.'
            ], 400);
        }

        $orderNumber = Order::generateOrderNumber();

        $order = Order::create([
            'order_number'         => $orderNumber,
            'customer_id'          => $customer->id,
            'tailor_id'            => $tailor->id,
            'dress_type'           => $request->dress_type ?? 'Not specified',
            'special_instructions' => $request->special_instructions ?? null,
            'fabric_provided_by'   => 'customer',
            'fabric_details'       => ($request->fabric_name ?? 'N/A') . ' - ' . ($request->fabric_color ?? 'N/A'),
            'delivery_type'        => 'home_delivery',
            'status'               => 'pending',
            'payment_status'       => 'unpaid',
        ]);

        if ($request->measurement_method === 'manual') {
            Measurement::create([
                'order_id'      => $order->id,
                'chest'         => $request->chest    ?? null,
                'waist'         => $request->waist    ?? null,
                'shirt_length'  => $request->length   ?? null,
                'shoulder'      => $request->shoulder ?? null,
                'sleeve_length' => $request->sleeve   ?? null,
                'neck'          => $request->neck     ?? null,
            ]);
        } else {
            Measurement::create([
                'order_id'         => $order->id,
                'additional_notes' => 'Appointment: ' . ($request->appointment_date ?? '') . ' at ' . ($request->appointment_time ?? ''),
            ]);
        }

        return response()->json([
            'success'      => true,
            'order_number' => $orderNumber,
            'message'      => 'Order placed successfully!',
        ]);
    }

    public function myOrders()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect('/login');
        }

        $customer = $user->customer;

        if (!$customer) {
            return redirect('/login')->with('error', 'Customer profile nahi mili.');
        }

        $orders = Order::where('customer_id', $customer->id)
                       ->latest()
                       ->get();

        return view('customer.customerdashboard', compact('orders'));
    }
}