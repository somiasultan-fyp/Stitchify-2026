<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <title>Customer Dashboard - Stitchify</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-dashboard.css') }}">
</head>
<body>

<div class="sidebar">
    <div class="sidebar-logo">
        <a href="/">
            <img src="{{ asset('images/logo.png') }}" alt="Stitchify Logo">
            <h3>Stitchify</h3>
        </a>
    </div>

    <div class="user-info">
        <h4>{{ auth()->user()->name }}</h4>
        <p>Customer</p>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="#overview" data-section="overview">
                <i class="fas fa-th-large"></i>
                Dashboard
            </a>
        </li>

        <li>
            <a href="#my-orders" data-section="my-orders">
                <i class="fas fa-shopping-bag"></i>
                My Orders
            </a>
        </li>

        <li>
            <a href="#order-history" data-section="order-history">
                <i class="fas fa-history"></i>
                Order History
            </a>
        </li>

        <li>
            <a href="/tailors">
                <i class="fas fa-plus-circle"></i>
                New Order
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a class="logout-link" href="#"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </div>
</div>

<form id="logout-form" action="/logout" method="POST" style="display:none">
    @csrf
</form>

<div class="main-content">

    <div class="top-bar" id="overview">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>

        <div class="bell-wrapper">
            <button type="button" class="bell-btn" id="bellBtn" onclick="toggleNotif()">
                <i class="fas fa-bell"></i>
                <span class="bell-badge" id="bellBadge">0</span>
            </button>

            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-dropdown-header">
                    <span>
                        <i class="fas fa-bell me-2"></i>
                        Notifications
                    </span>

                    <a href="#" onclick="markAllRead(event)">
                        Mark all read
                    </a>
                </div>

                <div id="notifList">
                    <div class="notif-empty">
                        <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                        No new notifications
                    </div>
                </div>

                <div class="notif-dropdown-footer">
                    <a href="/notifications">
                        View All Notifications
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-shopping-bag"></i>
            </div>

            <h3 class="stat-number">
                {{ $orders->whereIn('status', ['accepted', 'in_progress', 'ready', 'dispatched'])->count() }}
            </h3>

            <p class="stat-label">Active Orders</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>

            <h3 class="stat-number">
                {{ $orders->where('status', 'pending')->count() }}
            </h3>

            <p class="stat-label">Pending Orders</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>

            <h3 class="stat-number">
                {{ $orders->where('status', 'delivered')->count() }}
            </h3>

            <p class="stat-label">Completed Orders</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-list"></i>
            </div>

            <h3 class="stat-number">
                {{ $orders->count() }}
            </h3>

            <p class="stat-label">Total Orders</p>
        </div>
    </div>

    <div class="content-section" id="my-orders">
        <h3 class="section-title">My Orders</h3>

        @php
            $activeOrders = $orders->whereIn('status', [
                'pending',
                'accepted',
                'in_progress',
                'ready',
                'dispatched'
            ]);
        @endphp

        @forelse($activeOrders as $order)
            <div class="order-card">

                <div class="order-header">
                    <div class="order-id">
                        #{{ $order->order_number }}
                    </div>

                    <span class="order-status
                        @if($order->status === 'pending')
                            status-pending
                        @elseif($order->status === 'dispatched')
                            status-completed
                        @else
                            status-progress
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>

                <div class="order-details">
                    <p>
                        <strong>Tailor:</strong>
                        {{ optional(optional($order->tailor)->user)->name ?? 'N/A' }}
                    </p>

                    <p>
                        <strong>Item:</strong>
                        {{ $order->dress_type }}
                    </p>

                    <p>
                        <strong>Order Date:</strong>
                        {{ $order->created_at->format('M d, Y') }}
                    </p>

                    @if($order->status === 'pending')

                        <span class="waiting-badge">
                            <i class="fas fa-hourglass-half me-1"></i>
                            Awaiting response from tailor...
                        </span>

                    @else

                        @if($order->price)
                            <p>
                                <strong>Price:</strong>
                                PKR {{ number_format($order->price) }}
                            </p>
                        @endif

                        @if($order->expected_delivery_date)
                            <p>
                                <strong>Expected Delivery:</strong>
                                {{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('M d, Y') }}
                            </p>
                        @endif

                        @if($order->payment_status === 'unpaid')
                            <p>
                                <span class="badge bg-danger">
                                    Payment Pending
                                </span>
                            </p>

                            <a href="{{ route('payment.show', $order->id) }}" class="pay-btn">
                                <i class="fas fa-credit-card me-1"></i>
                                Pay Now
                            </a>

                        @elseif($order->payment_status === 'advance_paid')

                            <p>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-check me-1"></i>
                                    Advance Paid
                                </span>
                            </p>

                        @elseif($order->payment_status === 'fully_paid')

                            <p>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-double me-1"></i>
                                    Fully Paid
                                </span>
                            </p>
                        @endif

                        @if($order->delivery_type === 'home_delivery' && $order->tracking_id)
                            <a href="{{ route('delivery.track', $order->id) }}"
                               class="track-btn">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Track Order
                            </a>
                        @endif

                    @endif
                </div>
            </div>

        @empty

            <div class="empty-state">
                <i class="fas fa-shopping-bag"></i>

                <p>No active orders available.</p>

                <a href="/tailors" class="new-order-btn">
                    <i class="fas fa-plus me-1"></i>
                    Place New Order
                </a>
            </div>

        @endforelse
    </div>

    <div class="content-section" id="order-history">
        <h3 class="section-title">Order History</h3>

        @php
            $historyOrders = $orders->whereIn('status', ['delivered', 'cancelled']);
        @endphp

        @forelse($historyOrders as $order)

            <div class="order-card">

                <div class="order-header">
                    <div class="order-id">
                        #{{ $order->order_number }}
                    </div>

                    <span class="order-status
                        {{ $order->status === 'delivered' ? 'status-completed' : 'status-cancelled' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                <div class="order-details">

                    <p>
                        <strong>Tailor:</strong>
                        {{ optional(optional($order->tailor)->user)->name ?? 'N/A' }}
                    </p>

                    <p>
                        <strong>Item:</strong>
                        {{ $order->dress_type }}
                    </p>

                    <p>
                        <strong>Order Date:</strong>
                        {{ $order->created_at->format('M d, Y') }}
                    </p>

                    @if($order->price)
                        <p>
                            <strong>Price:</strong>
                            PKR {{ number_format($order->price) }}
                        </p>
                    @endif

                    @if($order->actual_delivery_date)
                        <p>
                            <strong>Delivered On:</strong>
                            {{ \Carbon\Carbon::parse($order->actual_delivery_date)->format('M d, Y') }}
                        </p>
                    @endif

                    @if($order->status === 'delivered')

                        @if(!$order->review)

                            <a href="/customer/review/{{ $order->id }}" class="review-btn">
                                <i class="fas fa-star me-1"></i>
                                Write Review
                            </a>

                        @else

                            <span class="reviewed-badge">
                                <i class="fas fa-check-circle me-1"></i>
                                Reviewed
                            </span>

                        @endif

                    @endif

                </div>
            </div>

        @empty

            <p class="text-muted text-center py-3">
                No completed order records found.
            </p>

        @endforelse
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/customer-dashboard.js') }}"></script>

</body>
</html>