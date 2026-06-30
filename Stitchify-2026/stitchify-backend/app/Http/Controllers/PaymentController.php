<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Notification;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Http\Controllers\DeliveryController;

class PaymentController extends Controller
{ 
    public function show(Order $order)
    {
        if ($order->customer->user_id != auth()->id()) {
            abort(403);
        }

        if ($order->status !== 'accepted') {
            return redirect('/customer/dashboard')
                ->with('error', 'Order has not been accepted yet.');
        }

        
        if ($order->payment_status !== 'unpaid') {
            return redirect('/customer/dashboard')
                ->with('error', 'Payment has already been made.');
        }

        $stripeKey = env('STRIPE_KEY');

        return view('customer.payment',
            compact('order', 'stripeKey'));
    }

    //Payment Process 
    public function process(Request $request, Order $order)
    {
        if ($order->customer->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'payment_method_id' => 'required|string',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $amount = (int) ($order->price * 100);

            $paymentIntent = PaymentIntent::create([
                'amount'              => $amount,
                'currency'            => 'pkr',
                'payment_method'      => $request->payment_method_id,
                'confirmation_method' => 'manual',
                'confirm'             => true,
                'return_url'          => route('payment.success', $order->id),
            ]);

            if ($paymentIntent->status === 'succeeded') { 
                Payment::create([
                    'order_id'          => $order->id,
                    'stripe_payment_id' => $paymentIntent->id,
                    'amount'            => $order->price,
                    'currency'          => 'pkr',
                    'status'            => 'completed',
                    'payment_type'      => 'advance',
                ]);

                $order->update([
                    'payment_status' => 'advance_paid',
                    'advance_paid'   => $order->price,
                ]);

                DeliveryController::createAfterPayment($order);

                $order->update([
                    'payment_status' => 'advance_paid',
                    'advance_paid'   => $order->price,
                ]);

                Notification::create([
                    'user_id'    => $order->tailor->user->id,
                    'title'      => 'Payment Received!',
                    'message'    => 'Customer order #' .
                                   $order->order_number .
                                   ' has been paid by the customer. Please start stitching.',
                    'type'       => 'payment',
                    'action_url' => '/tailor/dashboard',
                ]);

                return response()->json([
                    'success'  => true,
                    'redirect' => route('payment.success', $order->id),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment failed. Please try again.',
            ], 400);

        } catch (\Stripe\Exception\CardException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }
 
    public function success(Order $order)
    {
        if ($order->customer->user_id !== auth()->id()) {
            abort(403);
        }

        return view('customer.payment-success', compact('order'));
    }
}
