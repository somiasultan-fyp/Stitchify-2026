<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <title>Order Details - Stitchify</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/order-details.css') }}">
</head>
<body>
    <div class="container">
        <a href="{{ route('tailor.dashboard') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-shopping-bag me-2"></i> Order #{{ $order->id }}
            </div>
            <div class="card-body">
                <div class="info-row">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge
                            @if($order->status == 'pending') status-pending
                            @elseif($order->status == 'in_progress') status-progress
                            @else status-delivered @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Customer Name:</div>
                    <div class="info-value">{{ $order->customer->user->name ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Customer Email:</div>
                    <div class="info-value">{{ $order->customer->user->email ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Customer Phone:</div>
                    <div class="info-value">{{ $order->customer->phone ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Dress Type:</div>
                    <div class="info-value">{{ $order->dress_type }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fabric Type:</div>
                    <div class="info-value">{{ $order->fabric_type ?? 'N/A' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Quantity:</div>
                    <div class="info-value">{{ $order->quantity ?? 1 }}</div>
                </div>
                @if($order->price)
                <div class="info-row">
                    <div class="info-label">Price:</div>
                    <div class="info-value"><strong>Rs. {{ number_format($order->price) }}</strong></div>
                </div>
                @endif
                @if($order->expected_delivery_date)
                <div class="info-row">
                    <div class="info-label">Expected Delivery:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('d M, Y') }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Order Placed:</div>
                    <div class="info-value">{{ $order->created_at->format('d M, Y h:i A') }}</div>
                </div>
                @if($order->special_instructions)
                <div class="info-row">
                    <div class="info-label">Special Instructions:</div>
                    <div class="info-value">{{ $order->special_instructions }}</div>
                </div>
                @endif
            </div>
        </div>

        @if($order->measurement)
        <div class="card">
            <div class="card-header">
                <i class="fas fa-ruler-combined me-2"></i> Measurements
            </div>
            <div class="card-body">
                <div class="measurement-grid">
                    @php
                        $measurements = is_string($order->measurements) ? json_decode($order->measurements, true) : $order->measurements;
                    @endphp
                    @if($measurements && is_array($measurements))
                        @foreach($measurements as $key => $value)
                            <div class="measurement-item">
                                <strong>{{ str_replace('_', ' ', ucfirst($key)) }}</strong><br>
                                {{ $value }} inches
                            </div>
                        @endforeach
                    @else
                        <p>No measurements available</p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="card-header">
                <i class="fas fa-history me-2"></i> Order Timeline
            </div>
            <div class="card-body">
                <div class="timeline">
                    @if($order->created_at)
                    <div class="timeline-item">
                        <div class="timeline-date">{{ $order->created_at->format('d M, Y h:i A') }}</div>
                        <div class="timeline-title">Order Placed</div>
                    </div>
                    @endif
                    @if($order->accepted_at)
                    <div class="timeline-item">
                        <div class="timeline-date">{{ \Carbon\Carbon::parse($order->accepted_at)->format('d M, Y h:i A') }}</div>
                        <div class="timeline-title">Order Accepted</div>
                    </div>
                    @endif
                    @if($order->status == 'delivered')
                    <div class="timeline-item">
                        <div class="timeline-date">{{ $order->updated_at->format('d M, Y h:i A') }}</div>
                        <div class="timeline-title">Order Delivered</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>