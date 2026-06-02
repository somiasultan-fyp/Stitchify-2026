<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Measurement;
use App\Models\Tailor;

class OrderController extends Controller
{
    /**
     * Display the order creation form.
     */
    public function create()
    {
        return view('customer.order-form');
    }

    /**
     * Store a newly created order in the database.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // 1. Check karein ke user logged in hai ya nahi
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User logged in nahi hai. Pehle login karein.'
            ], 401);
        }

        $customer = $user->customer;
        
        // 2. Check karein agar customer profile database me nahi mili
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer profile nahi mili. Pehle profile mukammal karein.'
            ], 400);
        }

        // 3. Check karein agar database me koi tailor hi nahi hai
        $tailor = Tailor::first();

        if (!$tailor) {
            return response()->json([
                'success' => false,
                'message' => 'Database me koi Tailor mojood nahi hai. Pehle ek tailor record create/seed karein.'
            ], 400);
        }

        // Unique order number generate karein
        $orderNumber = Order::generateOrderNumber();

        // 4. Order create karein
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

        // 5. Measurements handle karein
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

        // Success Response
        return response()->json([
            'success'      => true,
            'order_number' => $orderNumber,
            'message'      => 'Order placed successfully!',
        ]);
    }

    /**
     * Display the logged-in customer's dashboard with their orders.
     */
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