<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Payment Successful - Stitchify</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('css/payment-success.css') }}" rel="stylesheet">
</head>
<body>

<div class="success-wrapper">
    <div class="success-top">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h2>Payment Successful!</h2>
        <p>Your order has been confirmed and payment received</p>
    </div>

    <div class="success-body">
        <div class="detail-row">
            <span class="label">Order Number</span>
            <span class="value">#{{ $order->order_number }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Tailor</span>
            <span class="value">{{ $order->tailor->user->name }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Dress Type</span>
            <span class="value">{{ $order->dress_type }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Expected Delivery</span>
            <span class="value">
                {{ $order->expected_delivery_date
                    ? \Carbon\Carbon::parse($order->expected_delivery_date)->format('d M Y')
                    : '—' }}
            </span>
        </div>
        <div class="detail-row">
            <span class="label">Payment Status</span>
            <span class="value" style="color:#27ae60;">
                <i class="fas fa-check-circle me-1"></i> Paid
            </span>
        </div>

        <div class="amount-highlight">
            <span class="label">
                <i class="fas fa-receipt me-2"></i>Amount Paid
            </span>
            <span class="amount">Rs. {{ number_format($order->price) }}</span>
        </div>

        <div class="whats-next">
            <h6><i class="fas fa-list-check me-2"></i>What Happens Next?</h6>
            <div class="next-step">
                <div class="step-dot">1</div>
                <span>Tailor will start stitching your order</span>
            </div>
            <div class="next-step">
                <div class="step-dot">2</div>
                <span>You'll get updates as status changes</span>
            </div>
            <div class="next-step">
                <div class="step-dot">3</div>
                <span>Order will be delivered to your address</span>
            </div>
            <div class="next-step">
                <div class="step-dot">4</div>
                <span>Rate your tailor after delivery</span>
            </div>
        </div>

        <a href="/customer/dashboard" class="btn-dashboard">
            <i class="fas fa-th-large me-2"></i>
            Go to Dashboard
        </a>
    </div>
</div>

</body>
</html>