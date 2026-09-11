<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="status-url" content="{{ route('delivery.status', $order->id) }}">
    <meta name="current-status" content="{{ $delivery->status }}">
    <title>Track Order-Stitchify</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tracking.css') }}" rel="stylesheet">
</head>
<body>

<div class="page-wrapper">

    <a href="/customer/dashboard" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

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

    <div class="progress-card">
        <div class="card-title-custom">
            <i class="fas fa-map-marker-alt me-2"></i>
            Delivery Progress
        </div>

        <div class="progress-wrap">
            <div class="progress-fill"
                 id="progressBar"
                 style="width: {{ $delivery->progress }}%">
            </div>
        </div>
        <p style="text-align:right;font-size:12px;color:var(--copyright-bg);margin-bottom:20px;">
            {{ $delivery->progress }}% Complete
        </p>

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

<script src="{{ asset('js/tracking.js') }}"></script>
</body>
</html>