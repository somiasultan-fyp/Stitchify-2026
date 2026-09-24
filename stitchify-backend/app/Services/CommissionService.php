<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Notification;

class CommissionService
{
    public static function rate(): float
    {
        return (float) config('stitchify.commission_rate', 4);
    }

    public static function split(float $amount): array
    {
        $rate = self::rate();
        $commission = round($amount * $rate / 100, 2);

        return [
            'rate'       => $rate,
            'commission' => $commission,
            'tailor'     => round($amount - $commission, 2),
        ];
    }

    public static function releaseForOrder(Order $order): void
    {
        $payments = Payment::where('order_id', $order->id)
            ->where('status', 'completed')
            ->where('payout_status', 'held')
            ->get();

        if ($payments->isEmpty()) {
            return;
        }

        foreach ($payments as $payment) {
            $payment->forceFill([
                'payout_status' => 'payable',
                'released_at'   => now(),
            ])->save();
        }

        $tailorAmount = (float) $payments->sum('tailor_amount');
        $commission   = (float) $payments->sum('commission_amount');

        Notification::create([
            'user_id'  => $order->tailor->user_id,
            'type'     => 'payment',
            'title'    => 'Payment Released',
            'message'  => 'Order #' . $order->order_number . ' has been delivered. Rs. ' .
                          number_format($tailorAmount, 2) .
                          ' is now payable to you after the platform commission of Rs. ' .
                          number_format($commission, 2) . '.',
            'order_id' => $order->id,
        ]);
    }
}