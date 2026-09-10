<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Payment Successful - Stitchify</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

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

        .success-wrapper {
            width: 480px;
            max-width: 95%;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            text-align: center;
        }

        /* Green top bar */
        .success-top {
            background: linear-gradient(135deg, #1B6B3A, #27ae60);
            padding: 40px 30px;
            color: white;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 36px;
        }
        .success-top h2 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .success-top p {
            opacity: 0.85;
            font-size: 14px;
            margin: 0;
        }

        /* Details */
        .success-body {
            padding: 30px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-row .label { color: var(--copyright-bg); }
        .detail-row .value { font-weight: 600; color: var(--primary-bg); }

        /* Amount highlight */
        .amount-highlight {
            background: #e8f5e9;
            border-radius: 10px;
            padding: 16px;
            margin: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .amount-highlight .label {
            color: #388e3c;
            font-weight: 600;
            font-size: 14px;
        }
        .amount-highlight .amount {
            color: #1B6B3A;
            font-size: 1.4rem;
            font-weight: 700;
        }

        /* What's next */
        .whats-next {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;
            text-align: left;
        }
        .whats-next h6 {
            color: var(--accent-color);
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .next-step {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: #555;
            margin-bottom: 8px;
        }
        .next-step:last-child { margin-bottom: 0; }
        .step-dot {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--accent-color);
            color: white;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Button */
        .btn-dashboard {
            display: block;
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }
        .btn-dashboard:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(27,42,74,0.3);
        }
    </style>
</head>
<body>

<div class="success-wrapper">

    <!---- Success Header ---->
    <div class="success-top">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        <h2>Payment Successful!</h2>
        <p>Your order has been confirmed and payment received</p>
    </div>

    <!---- Details ---->
    <div class="success-body">

        <!---- Order details ----->
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

        <!---- Amount -->
        <div class="amount-highlight">
            <span class="label">
                <i class="fas fa-receipt me-2"></i>Amount Paid
            </span>
            <span class="amount">Rs. {{ number_format($order->price) }}</span>
        </div>

        <!--- What's Next -->
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

        <!-- Dashboard Button --->
        <a href="/customer/dashboard" class="btn-dashboard">
            <i class="fas fa-th-large me-2"></i>
            Go to Dashboard
        </a>

    </div>
</div>

</body>
</html>