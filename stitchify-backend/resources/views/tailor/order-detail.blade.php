<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Stitchify</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #1a1a2e;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
        .back-btn:hover {
            background: #16213e;
            color: white;
        }
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .card-header {
            background: #1a1a2e;
            color: white;
            padding: 15px 20px;
            font-size: 18px;
            font-weight: bold;
        }
        .card-body {
            padding: 20px;
        }
        .info-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
            color: #555;
        }
        .info-value {
            flex: 1;
            color: #333;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending { background: #fff3e0; color: #f57c00; }
        .status-progress { background: #e3f2fd; color: #1976d2; }
        .status-delivered { background: #e8f5e9; color: #388e3c; }
        .measurement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        .measurement-item {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }
        .timeline {
            padding-left: 20px;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 20px;
            margin-left: 20px;
            border-left: 2px solid #ddd;
            padding-left: 20px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #1a1a2e;
        }
        .timeline-date {
            font-size: 12px;
            color: #888;
            margin-bottom: 5px;
        }
        .timeline-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        @media (max-width: 768px) {
            .info-row {
                flex-direction: column;
            }
            .info-label {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('tailor.dashboard') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>

        <!-- Order Details Card -->
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

        <!-- Measurements Card -->
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

        <!-- Timeline Card -->
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