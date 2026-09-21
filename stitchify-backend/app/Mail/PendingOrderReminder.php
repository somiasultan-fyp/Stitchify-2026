<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PendingOrderReminder extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public bool $slotsFull;

    public function __construct(Order $order, bool $slotsFull = false)
    {
        $this->order = $order;
        $this->slotsFull = $slotsFull;
    }

    public function build()
    {
        return $this->subject('Reminder: Order #' . $this->order->order_number . ' Awaiting Your Response')
            ->view('emails.pending-order-reminder');
    }
}