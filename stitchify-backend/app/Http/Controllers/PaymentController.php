<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Notification;
use App\Services\CommissionService;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function show(Order $order)
    {
        if ($order->customer->user_id != auth()->id()) {
            abort(403);
        }

        if (in_array($order->status, ['pending', 'cancelled'])) {
            return redirect('/customer/dashboard')
                ->with('error', 'Order has not been accepted yet.');
        }

        if ($order->status === 'accepted') {
            return redirect('/customer/dashboard')
                ->with('error', 'Payment opens after the tailor confirms that your fabric has been received.');
        }

        if ($order->payment_status !== 'unpaid') {
            return redirect('/customer/dashboard')
                ->with('error', 'Payment has already been made.');
        }

        $stripeKey = env('STRIPE_KEY');

        return view('customer.payment',
            compact('order', 'stripeKey'));
    }

    public function process(Request $request, Order $order)
    {
        if ($order->customer->user_id !== auth()->id()) {
            abort(403);
        }

        if (in_array($order->status, ['pending', 'accepted', 'cancelled'])) {
            return response()->json([
                'success' => false,
                'message' => 'Payment is not available for this order yet.',
            ], 422);
        }

        if ($order->payment_status !== 'unpaid') {
            return response()->json([
                'success' => false,
                'message' => 'Payment has already been made.',
            ], 422);
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
                $payment = Payment::create([
                    'order_id'          => $order->id,
                    'stripe_payment_id' => $paymentIntent->id,
                    'amount'            => $order->price,
                    'currency'          => 'pkr',
                    'status'            => 'completed',
                    'payment_type'      => 'advance',
                ]);

                $split = CommissionService::split((float) $order->price);

                $payment->forceFill([
                    'commission_rate'   => $split['rate'],
                    'commission_amount' => $split['commission'],
                    'tailor_amount'     => $split['tailor'],
                    'payout_status'     => 'held',
                ])->save();

                $order->update([
                    'payment_status' => 'advance_paid',
                    'advance_paid'   => $order->price,
                ]);

                Notification::create([
                    'user_id'    => $order->tailor->user->id,
                    'title'      => 'Payment Received!',
                    'message'    => 'Order #' .
                                   $order->order_number .
                                   ' has been paid by the customer. You can start stitching now.',
                    'type'       => 'payment',
                    'order_id'   => $order->id,
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