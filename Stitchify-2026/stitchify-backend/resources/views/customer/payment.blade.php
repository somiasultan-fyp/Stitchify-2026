<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment - Stitchify</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!----- Stripe JS ---->
    <script src="https://js.stripe.com/v3/"></script>

    <style>
        :root {
            --primary-bg: #212529;
            --accent-color: #1B2A4A;
            --copyright-bg: #575a5b;
            --text-white: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .payment-wrapper {
            width: 480px;
            max-width: 95%;
        }

        /* Order Summary Card */
        .order-summary {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
            border-radius: 16px 16px 0 0;
            padding: 24px 30px;
            color: white;
        }
        .order-summary h5 {
            font-size: 13px;
            opacity: 0.7;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .order-summary h2 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .order-detail-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            padding: 6px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .order-detail-row:last-child { border-bottom: none; }
        .order-detail-row .label { opacity: 0.7; }
        .order-detail-row .value { font-weight: 600; }
        .amount-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        .amount-row .label {
            font-size: 14px;
            opacity: 0.8;
        }
        .amount-row .amount {
            font-size: 1.8rem;
            font-weight: 700;
        }

        /* Payment Form Card */
        .payment-form-card {
            background: white;
            border-radius: 0 0 16px 16px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .form-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Stripe Card Element */
        .card-element-wrap {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 8px;
            transition: border-color 0.3s;
            background: #f8f9fa;
        }
        .card-element-wrap.focused {
            border-color: var(--accent-color);
            background: white;
        }

        .card-error {
            color: #dc3545;
            font-size: 13px;
            margin-bottom: 16px;
            min-height: 20px;
        }

        /* Test card info box */
        .test-info {
            background: #e8f4fd;
            border: 1px solid #bee3f8;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #1565c0;
        }
        .test-info strong { display: block; margin-bottom: 4px; }

        /* Pay Button */
        .btn-pay {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(27,42,74,0.3);
        }
        .btn-pay:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Security badge */
        .security-badge {
            text-align: center;
            margin-top: 16px;
            color: var(--copyright-bg);
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* Back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--accent-color);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 16px;
        }
        .back-link:hover { color: var(--primary-bg); }
    </style>
</head>
<body>

<div class="payment-wrapper">

    <!----- Back link ---->
    <a href="/customer/dashboard" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

    <!----- Order Summary ---->
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

    <!----- Payment Form ---->
    <div class="payment-form-card">

        <div class="form-title">
            <i class="fas fa-credit-card"></i>
            Card Details
        </div>

        <!---- Test card info — sandbox mein ---->
        <div class="test-info">
            <strong><i class="fas fa-info-circle me-1"></i> Test Mode</strong>
            Test card: <strong>4242 4242 4242 4242</strong><br>
            Expiry: Any future date &nbsp;|&nbsp; CVV: Any 3 digits
        </div>

        {{-- Error message --}}
        <div id="cardError" class="card-error"></div>

        {{-- Stripe Card Element --}}
        <div class="card-element-wrap" id="cardElementWrap">
            <div id="cardElement"></div>
        </div>

        {{-- Pay Button --}}
        <button id="payBtn" class="btn-pay" onclick="processPayment()">
            <i class="fas fa-lock"></i>
            Pay Rs. {{ number_format($order->price) }}
        </button>

        {{-- Security --}}
        <div class="security-badge">
            <i class="fas fa-shield-alt"></i>
            Secured by Stripe — Your card info is safe
        </div>

    </div>

</div>

<script>
//  Initialize Stripe
const stripe = Stripe('{{ $stripeKey }}');
const elements = stripe.elements();

// Make Card element 
const cardElement = elements.create('card', {
    style: {
        base: {
            fontSize: '16px',
            color: '#212529',
            fontFamily: "'Segoe UI', sans-serif",
            '::placeholder': { color: '#aab7c4' },
        },
        invalid: { color: '#dc3545' },
    }
});

// Mount Card element 
cardElement.mount('#cardElement');

// Focus/blur styling
cardElement.on('focus', () => {
    document.getElementById('cardElementWrap').classList.add('focused');
});
cardElement.on('blur', () => {
    document.getElementById('cardElementWrap').classList.remove('focused');
});

// Show Card errors in real-time
cardElement.on('change', (event) => {
    const errorDiv = document.getElementById('cardError');
    errorDiv.textContent = event.error ? event.error.message : '';
});

// Payment Process
async function processPayment() {
    const btn       = document.getElementById('payBtn');
    const errorDiv  = document.getElementById('cardError');
    const csrf      = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Disable Button
    btn.disabled = true;
    btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Processing...
    `;
    errorDiv.textContent = '';

    try {
        // Step 1:Make Payment method 
        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        });

        if (error) {
            errorDiv.textContent = error.message;
            btn.disabled = false;
            btn.innerHTML = `<i class="fas fa-lock"></i> Pay Rs. {{ number_format($order->price) }}`;
            return;
        }

        // Step 2: Shift to Server 
        const response = await fetch('{{ route("payment.process", $order->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                payment_method_id: paymentMethod.id,
            }),
        });

        const data = await response.json();

        if (data.success) {
            // Success — redirect 
            btn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Redirecting...
            `;
            window.location.href = data.redirect;
        } else {
            errorDiv.textContent = data.message || 'Payment failed. Please try again.';
            btn.disabled = false;
            btn.innerHTML = `<i class="fas fa-lock"></i> Pay Rs. {{ number_format($order->price) }}`;
        }

    } catch (err) {
        errorDiv.textContent = 'Something went wrong. Please try again.';
        btn.disabled = false;
        btn.innerHTML = `<i class="fas fa-lock"></i> Pay Rs. {{ number_format($order->price) }}`;
    }
}
</script>

</body>
</html>