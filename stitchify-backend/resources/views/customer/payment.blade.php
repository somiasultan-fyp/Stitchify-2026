<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="stripe-key" content="{{ $stripeKey }}">
    <meta name="order-id" content="{{ $order->id }}">
    <meta name="order-price" content="{{ number_format($order->price) }}">
    <meta name="process-url" content="{{ route('payment.process', $order->id) }}">
    <title>Payment-Stitchify</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('css/payment.css') }}" rel="stylesheet">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>

<div class="payment-wrapper">
    <a href="/customer/dashboard" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

    <div class="order-summary">
        <h5>Order Payment</h5>
        <h2>#{{ $order->order_number }}</h2>

        <div class="order-detail-row">
            <span class="label">Tailor</span>
            <span class="value">{{ $order->tailor->user->name }}</span>
        </div>
        <div class="order-detail-row">
            <span class="label">Dress Type</span>
            <span class="value">{{ $order->dress_type }}</span>
        </div>
        <div class="order-detail-row">
            <span class="label">Expected Delivery</span>
            <span class="value">
                {{ $order->expected_delivery_date
                    ? \Carbon\Carbon::parse($order->expected_delivery_date)->format('d M Y')
                    : '—' }}
            </span>
        </div>

        <div class="amount-row">
            <span class="label">Amount to Pay</span>
            <span class="amount">Rs. {{ number_format($order->price) }}</span>
        </div>
    </div>

    <div class="payment-form-card">
        <div class="form-title">
            <i class="fas fa-credit-card"></i>
            Card Details
        </div>

        <div class="test-info">
            <strong><i class="fas fa-info-circle me-1"></i> Test Mode</strong>
            Test card: <strong>4242 4242 4242 4242</strong><br>
            Expiry: Any future date &nbsp;|&nbsp; CVV: Any 3 digits
        </div>

        <div id="cardError" class="card-error"></div>

        <div class="card-element-wrap" id="cardElementWrap">
            <div id="cardElement"></div>
        </div>

        <button id="payBtn" class="btn-pay" onclick="processPayment()">
            <i class="fas fa-lock"></i>
            Pay Rs. {{ number_format($order->price) }}
        </button>

        <div class="security-badge">
            <i class="fas fa-shield-alt"></i>
            Secured by Stripe — Your card info is safe
        </div>
    </div>
</div>

<script src="{{ asset('js/payment.js') }}"></script>
</body>
</html>