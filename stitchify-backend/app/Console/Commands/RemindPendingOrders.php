<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Notification;
use App\Mail\PendingOrderReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class RemindPendingOrders extends Command
{
    protected $signature = 'orders:remind-pending';
    protected $description = 'Send reminder to tailors for orders pending over 24 hours';

    public function handle()
    {
        $orders = Order::where('status', 'pending')
            ->whereNull('reminder_sent_at')
            ->where('created_at', '<=', Carbon::now()->subDay())
            ->with('tailor.user')
            ->get();

        $count = 0;

        foreach ($orders as $order) {
            if (!$order->tailor || !$order->tailor->user) {
                continue;
            }
        $slotsFull = $order->tailor->available_slots <= 0;
        $message = $slotsFull
        ? "Order #{$order->order_number} has been pending for over 24 hours. Your slots are currently full you can still reject it if you cannot take it, or wait until new slots open."
        : "Order #{$order->order_number} has been pending for over 24 hours. Please accept or reject it.";    

            Notification::create([
                'user_id'    => $order->tailor->user_id,
                'type'       => 'order_reminder',
                'title'      => 'Order Awaiting Response',
                'message'    => "Order #{$order->order_number} has been pending for over 24 hours. Please accept or reject it.",
                'order_id'   => $order->id,
                'action_url' => '/tailor/dashboard',
            ]);

            try {
                Mail::to($order->tailor->user->email)
                    ->send(new PendingOrderReminder($order));
            } catch (\Throwable $e) {
                \Log::error('Reminder email failed', [
                    'order_id' => $order->id,
                    'error'    => $e->getMessage(),
                ]);
            }

            $order->update(['reminder_sent_at' => now()]);
            $count++;

            $this->info("Reminder sent for order #{$order->order_number}");
        }

        $this->info("Total reminders sent: {$count}");
        return Command::SUCCESS;
    }
}