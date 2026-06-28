<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Track Order - Stitchify</title>

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
            padding: 20px;
        }

        .page-wrapper {
            max-width: 680px;
            margin: 0 auto;
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
            margin-bottom: 20px;
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--primary-bg); }

        /* Header card */
        .header-card {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
            border-radius: 16px;
            padding: 24px 28px;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .header-card h4 {
            font-size: 1.1rem;
            opacity: 0.8;
            margin-bottom: 4px;
        }
        .tracking-id {
            font-size: 1.6rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 16px;
        }
        .order-meta {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .meta-item {
            font-size: 13px;
        }
        .meta-item .label { opacity: 0.7; display: block; }
        .meta-item .value { font-weight: 600; }

        /* Progress card */
        .progress-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }
        .card-title-custom {
            font-size: 1rem;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }

        /* Progress bar */
        .progress-wrap {
            background: #f0f0f0;
            border-radius: 30px;
            height: 10px;
            margin-bottom: 24px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 30px;
            background: linear-gradient(90deg, var(--accent-color), #4a7bc8);
            transition: width 0.8s ease;
        }

        /* Steps */
        .steps-list {
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        .step-item {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            position: relative;
        }
        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 17px;
            top: 36px;
            width: 2px;
            height: calc(100% - 10px);
            background: #e0e0e0;
        }
        .step-item.done::after { background: var(--accent-color); }

        .step-dot {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 14px;
            font-weight: 700;
            border: 2px solid #e0e0e0;
            background: white;
            color: #ccc;
            z-index: 1;
        }
        .step-dot.done {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }
        .step-dot.current {
            background: white;
            border-color: var(--accent-color);
            color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(27,42,74,0.1);
        }

        .step-content {
            padding-bottom: 24px;
            flex: 1;
        }
        .step-title {
            font-weight: 600;
            font-size: 14px;
            color: var(--primary-bg);
            margin-bottom: 2px;
        }
        .step-title.faded { color: #aaa; font-weight: 400; }
        .step-desc {
            font-size: 12px;
            color: var(--copyright-bg);
        }

        /* Info card */
        .info-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f5f5f5;
            font-size: 14px;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-row .label { color: var(--copyright-bg); }
        .detail-row .value { font-weight: 600; color: var(--primary-bg); }

        /* Pickup note */
        .pickup-note {
            background: #e8f5e9;
            border: 1.5px solid #a5d6a7;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
        }
        .pickup-note h6 {
            color: #1b5e20;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .pickup-note p {
            color: #2e7d32;
            font-size: 13px;
            margin: 0;
            line-height: 1.6;
        }

        /* Refresh button */
        .btn-refresh {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-refresh:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(27,42,74,0.3);
        }
    </style>
</head>
<body>

<div class="page-wrapper">

    <a href="/customer/dashboard" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

    {{-- Header --}}
    <div class="header-card">
        <h4>Tracking ID</h4>
        <div class="tracking-id">{{ $delivery->tracking_id }}</div>

        <div class="order-meta">
            <div class="meta-item">
                <span class="label">Order</span>
                <span class="value">#{{ $order->order_number }}</span>
            </div>
            <div class="meta-item">
                <span class="label">Tailor</span>
                <span class="value">{{ $order->tailor->user->name }}</span>
            </div>
            <div class="meta-item">
                <span class="label">Courier</span>
                <span class="value">{{ $delivery->courier_name }}</span>
            </div>
            @if($delivery->estimated_date)
            <div class="meta-item">
                <span class="label">Est. Delivery</span>
                <span class="value">{{ $delivery->estimated_date->format('d M Y') }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Pickup only note --}}
    @if($order->delivery_type === 'pickup')
    <div class="pickup-note">
        <h6><i class="fas fa-walking me-2"></i>Self Pickup Selected</h6>
        <p>
            You chose to handle fabric pickup and drop-off yourself.
            Please drop the fabric at the tailor's location and collect
            the finished garment when it's ready.
        </p>
    </div>
    @endif

    {{-- Progress --}}
    <div class="progress-card">
        <div class="card-title-custom">
            <i class="fas fa-map-marker-alt me-2"></i>
            Delivery Progress
        </div>

        {{-- Progress bar --}}
        <div class="progress-wrap">
            <div class="progress-fill"
                 id="progressBar"
                 style="width: {{ $delivery->progress }}%">
            </div>
        </div>
        <p style="text-align:right;font-size:12px;color:var(--copyright-bg);margin-bottom:20px;">
            {{ $delivery->progress }}% Complete
        </p>

        {{-- Steps --}}
        @php
            $steps = [
                ['key' => 'scheduled',               'title' => 'Delivery Scheduled',       'desc'  => 'Courier has been notified'],
                ['key' => 'picked_up_from_customer', 'title' => 'Fabric Picked Up',         'desc'  => 'Courier collected fabric from you'],
                ['key' => 'delivered_to_tailor',     'title' => 'Fabric at Tailor',         'desc'  => 'Fabric delivered to tailor'],
                ['key' => 'stitching_in_progress',   'title' => 'Stitching in Progress',    'desc'  => 'Tailor is working on your order'],
                ['key' => 'picked_up_from_tailor',   'title' => 'Order Picked Up',          'desc'  => 'Courier collected finished garment'],
                ['key' => 'out_for_delivery',        'title' => 'Out for Delivery',         'desc'  => 'On the way to you'],
                ['key' => 'delivered',               'title' => 'Delivered',                'desc'  => 'Order delivered successfully'],
            ];

            $statusOrder = array_column($steps, 'key');
            $currentIndex = array_search($delivery->status, $statusOrder);
        @endphp

        <div class="steps-list">
            @foreach($steps as $index => $step)
            @php
                $isDone    = $index < $currentIndex;
                $isCurrent = $index === $currentIndex;
            @endphp
            <div class="step-item {{ $isDone ? 'done' : '' }}">
                <div class="step-dot {{ $isDone ? 'done' : ($isCurrent ? 'current' : '') }}">
                    @if($isDone)
                        <i class="fas fa-check"></i>
                    @elseif($isCurrent)
                        <i class="fas fa-circle-dot"></i>
                    @else
                        {{ $index + 1 }}
                    @endif
                </div>
                <div class="step-content">
                    <div class="step-title {{ (!$isDone && !$isCurrent) ? 'faded' : '' }}">
                        {{ $step['title'] }}
                        @if($isCurrent)
                            <span style="background:#e3f2fd;color:#1565c0;
                                         font-size:11px;padding:2px 8px;
                                         border-radius:10px;margin-left:8px;
                                         font-weight:600;">
                                Current
                            </span>
                        @endif
                    </div>
                    <div class="step-desc">{{ $step['desc'] }}</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Refresh button --}}
        <div class="mt-4">
            <button class="btn-refresh" onclick="refreshStatus()">
                <i class="fas fa-sync-alt" id="refreshIcon"></i>
                Refresh Status
            </button>
            <span id="lastUpdated"
                  style="margin-left:12px;font-size:12px;color:var(--copyright-bg);">
                Last updated: Just now
            </span>
        </div>
    </div>

    {{-- Order Info --}}
    <div class="info-card">
        <div class="card-title-custom">
            <i class="fas fa-info-circle me-2"></i>
            Order Details
        </div>

        <div class="detail-row">
            <span class="label">Dress Type</span>
            <span class="value">{{ $order->dress_type }}</span>
        </div>
        <div class="detail-row">
            <span class="label">Delivery Type</span>
            <span class="value">
                {{ $order->delivery_type === 'home_delivery'
                    ? 'Home Delivery'
                    : 'Self Pickup' }}
            </span>
        </div>
        <div class="detail-row">
            <span class="label">Payment</span>
            <span class="value"
                  style="color:{{ $order->payment_status !== 'unpaid' ? '#388e3c' : '#f57c00' }}">
                {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
            </span>
        </div>
        @if($order->price)
        <div class="detail-row">
            <span class="label">Price</span>
            <span class="value">Rs. {{ number_format($order->price) }}</span>
        </div>
        @endif
    </div>

</div>

<script>
// Refresh status via AJAX
async function refreshStatus() {
    const icon = document.getElementById('refreshIcon');
    icon.classList.add('fa-spin');

    try {
        const res  = await fetch('{{ route("delivery.status", $order->id) }}');
        const data = await res.json();

        if (data.found) {
            // Update progress bar
            document.getElementById('progressBar').style.width =
                data.progress + '%';

            // Update last updated time
            document.getElementById('lastUpdated').textContent =
                'Last updated: ' + new Date().toLocaleTimeString();

            // Reload page if status changed
            if (data.status !== '{{ $delivery->status }}') {
                location.reload();
            }
        }
    } catch (err) {
        console.error('Status refresh failed:', err);
    } finally {
        icon.classList.remove('fa-spin');
    }
}

// Auto-refresh har 60 second mein
setInterval(refreshStatus, 60000);
</script>

</body>
</html>