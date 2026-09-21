<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #212529;">
    <h2>Order Awaiting Your Response</h2>
    <p>Hi {{ $order->tailor->user->name }},</p>
    <p>
        You have a pending order <strong>#{{ $order->order_number }}</strong> for
        <strong>{{ $order->dress_type }}</strong> that has been waiting for your
        response for over 24 hours.
    </p>
    @if($slotsFull)
    <p style="color:#f57c00;">
    <strong>Note:</strong> Your available slots are currently full. You can still
     reject this order if you're unable to take it. new slots will open once
     the month resets.
    </p>
    @endif
    <p>Please log in to your dashboard to accept or reject this order.</p>
    <p>
        <a href="{{ url('/tailor/dashboard') }}"
           style="background:#1b2a4a;color:white;padding:10px 20px;
                  text-decoration:none;border-radius:6px;">
            Go to Dashboard
        </a>
    </p>
</body>
</html>