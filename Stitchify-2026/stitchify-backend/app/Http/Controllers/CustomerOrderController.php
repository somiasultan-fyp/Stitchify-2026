<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Tailor;
use App\Models\Customer;
use App\Models\Measurement;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomerOrderController extends Controller
{
    public function showForm(Request $request)
    {
        $tailor_id = $request->query('tailor_id');
        
        if (!$tailor_id) {
            return redirect()->route('customer.dashboard')->with('error', 'Please select a tailor first.');
        }
        
        $tailor = Tailor::with('user')->findOrFail($tailor_id);

        if (!$tailor->hasAvailableSlot() || $tailor->status !== 'approved') {
            return back()->with('error', 'slots are not available for this tailor. Please select another tailor.')->withInput();
        }

        return view('customer.order-form', compact('tailor'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'tailor_id' => 'required|exists:tailors,id',
            'dress_type' => 'required|string|max:100',
            'fabric_name' => 'required|string|max:100',
            'fabric_color' => 'required|string|max:50',
            'fabric_provided_by' => 'required|in:customer,tailor',
            'delivery_type' => 'required|in:pickup,home_delivery',
            'special_instructions' => 'nullable|string|max:1000',
            'design_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'measurement_method' => 'required|in:manual',
            'chest' => 'nullable|numeric|min:1|max:100',
            'waist' => 'nullable|numeric|min:1|max:100',
            'shoulder' => 'nullable|numeric|min:1|max:100',
            'sleeve_length' => 'nullable|numeric|min:1|max:100',
            'shirt_length' => 'nullable|numeric|min:1|max:100',
        ]);

        $tailor = Tailor::with('user')->findOrFail($request->tailor_id);

        if (!$tailor->hasAvailableSlot() || $tailor->status !== 'approved') {
            return back()->with('error', 'slots are not available for this tailor. Please select another tailor.')->withInput();
        }

        $customer = auth()->user()->customer;
        if (!$customer) {
            $customer = Customer::create(['user_id' => auth()->id()]);
        }

        $designImagePath = null;
        if ($request->hasFile('design_image')) {
            $designImagePath = $request->file('design_image')->store('designs', 'public');
        }

        try {
            DB::transaction(function () use ($request, $tailor, $customer, $designImagePath) {
                
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'customer_id' => $customer->id,
                    'tailor_id' => $tailor->id,
                    'dress_type' => $request->dress_type,
                    'fabric_details' => $request->fabric_name . ' - ' . $request->fabric_color,
                    'fabric_provided_by' => $request->fabric_provided_by,
                    'special_instructions' => $request->special_instructions,
                    'delivery_type' => $request->delivery_type,
                    'design_image' => $designImagePath,
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                ]);

                if ($request->measurement_method === 'manual') {
                    Measurement::create([
                        'order_id' => $order->id,
                        'chest' => $request->chest,
                        'waist' => $request->waist,
                        'hips' => $request->hips ?? null,
                        'shoulder' => $request->shoulder,
                        'sleeve_length' => $request->sleeve_length,
                        'shirt_length' => $request->shirt_length,
                        'trouser_length' => $request->trouser_length ?? null,
                        'trouser_waist' => $request->trouser_waist ?? null,
                        'neck' => $request->neck ?? null,
                        'additional_notes' => $request->special_instructions,
                    ]);
                } 

                Notification::create([
                    'user_id' => $tailor->user_id,
                    'type' => 'new_order',
                    'title' => 'New Order Received!',
                    'message' => auth()->user()->name . ' place a new order. order #' . $order->order_number,
                    'order_id' => $order->id,
                    'is_read' => false,
                ]);
            });

     return response()->json([
        'success'      => true,
        'order_number' => $order->order_number,
        'message'      => 'Order placed successfully!',
]);

        } catch (\Exception $e) {
            \Log::error('Order failed: ' . $e->getMessage());
            if ($designImagePath && Storage::disk('public')->exists($designImagePath)) {
                Storage::disk('public')->delete($designImagePath);
            }
       return response()->json([
        'success' => false,
        'message' => 'Order place karne mein error aaya.',
        ], 500);
        }
    }

    public function myOrders()
    {
        $customer = auth()->user()->customer;
        if (!$customer) {
            return redirect()->route('home')->with('error', 'Complete your profile to place an order.');
        }

        $orders = $customer->orders()
            ->with(['tailor.user', 'measurement'])
            ->latest()
            ->paginate(10);

        $stats = [
            'total_orders' => $customer->orders()->count(),
            'active_orders' => $customer->orders()->whereIn('status', ['accepted', 'in_progress', 'ready', 'dispatched'])->count(),
            'pending' => $customer->orders()->where('status', 'pending')->count(),
            'completed' => $customer->orders()->where('status', 'delivered')->count(),
        ];

        return view('customer.customerdashboard', compact('orders', 'stats'));
    }

    public function showOrder(Order $order)
    {
        if ($order->customer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        $order->load(['tailor.user', 'measurement', 'payments']);
        return view('customer.order-detail', compact('order'));
    }

    public function cancelOrder(Request $request, Order $order)
    {
        if ($order->customer->user_id !== auth()->id() || $order->status !== 'pending') {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        Notification::create([
            'user_id' => $order->tailor->user_id,
            'type' => 'order_cancelled',
            'title' => 'Order Cancelled',
            'message' => "Order #{$order->order_number} cancelled by customer.",
            'order_id' => $order->id,
        ]);

        return back()->with('success', 'Order cancelled.');
    }

    public function liveStatus()
    {
        $customer = auth()->user()->customer;
        if (!$customer) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $orders = $customer->orders()
            ->select('id', 'order_number', 'status', 'expected_delivery_date', 'payment_status')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders->map(fn($o) => [
                'id' => $o->id,
                'order_number' => $o->order_number,
                'status' => $o->status,
                'status_label' => ucfirst(str_replace('_', ' ', $o->status)),
                'expected_delivery_date' => $o->expected_delivery_date?->format('d M Y'),
            ])
        ]);
    }
}