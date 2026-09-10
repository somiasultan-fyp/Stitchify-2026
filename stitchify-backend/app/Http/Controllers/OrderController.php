<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Measurement;
use App\Models\Tailor;
use App\Models\Notification;

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

        $tailor = Tailor::find($request->tailor_id);
        if (!$tailor) {
            return response()->json([
                'success' => false,
                'message' => 'Selected tailor not found.'
            ], 400);
        }

        if (!$tailor->hasAvailableSlot()) {
            return response()->json([
                'success' => false,
                'message' => 'This tailor has no available slots right now.'
            ], 400);
        }

        $orderNumber = Order::generateOrderNumber();

        $formName = $request->customer_name ?? $user->name;

        $order = Order::create([
            'order_number'         => $orderNumber,
            'customer_id'          => $customer->id,
            'tailor_id'            => $tailor->id,
            'recipient_name'       => $formName,
            'recipient_phone'      => $request->customer_phone ?? null,
            'recipient_address'    => $request->customer_address ?? null,
            'recipient_city'       => $request->customer_city ?? null,
            'dress_type'           => $request->dress_type ?? 'Not specified',
            'special_instructions' => $request->special_instructions ?? null,
            'fabric_provided_by'   => 'customer',
            'fabric_details'       => ($request->fabric_name ?? 'N/A') . ' - ' . ($request->fabric_color ?? 'N/A'),
            'delivery_type'        => $request->delivery_type ?? 'pickup',
            'status'               => 'pending',
            'payment_status'       => 'unpaid',
        ]);

        if ($request->has('design_images') && is_array($request->design_images)) {
        $imagePaths = [];
        
        foreach ($request->design_images as $index => $imageData) {
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $imageData = base64_decode($imageData);
                
                $filename = 'order_' . $orderNumber . '_' . $index . '_' . time() . '.png';
                $path = 'designs/' . $filename;
                
                \Storage::disk('public')->put($path, $imageData);
                $imagePaths[] = $path;
            }
        }
        
        if (!empty($imagePaths)) {
            $order->update([
                'design_image' => $imagePaths[0]
            ]);
        }
    }

        if ($request->measurement_method === 'manual') {
            Measurement::create([
                'order_id'         => $order->id,
                'chest'            => $request->chest          ?? null,
                'waist'            => $request->waist          ?? null,
                'hips'             => $request->hips           ?? null,
                'shoulder'         => $request->shoulder       ?? null,
                'sleeve_length'    => $request->sleeve_length  ?? null,
                'shirt_length'     => $request->shirt_length   ?? null,
                'trouser_length'   => $request->trouser_length ?? null,
                'trouser_waist'    => $request->trouser_waist  ?? null,
                'neck'             => $request->neck           ?? null,
                'additional_notes' => $request->additional_notes ?? null,
            ]);
        } else {
            Measurement::create([
                'order_id'         => $order->id,
                'additional_notes' => 'Appointment: ' .
                    ($request->appointment_date ?? '') .
                    ' at ' .
                    ($request->appointment_time ?? ''),
            ]);
        }

        $tailor->decrementSlot();

        Notification::create([
            'user_id'    => $tailor->user->id,
            'title'      => 'New Order Received!',
            'message'    => $formName . ' has placed order #' . $orderNumber .
                           ' for ' . ($request->dress_type ?? 'garment') . '.',
            'type'       => 'order',
            'action_url' => '/tailor/dashboard',
        ]);

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
            return redirect('/login')
                ->with('error', 'Customer profile not found.');
        }

        $orders = Order::where('customer_id', $customer->id)
                       ->with(['tailor.user', 'measurement'])
                       ->latest()
                       ->get();

        return view('customer.customerdashboard', compact('orders'));
    }
}