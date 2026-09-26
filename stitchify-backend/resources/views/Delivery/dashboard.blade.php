<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <title>Delivery Dashboard - Stitchify</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/deliverydashboard.css') }}">
</head>
<body>

<button class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="/">
            <img src="{{ asset('images/logo.png') }}" alt="Stitchify Logo">
            <h3>Stitchify</h3>
        </a>
    </div>

    <div class="user-info">
        <h4>{{ auth()->user()->name }}</h4>
        <p>Delivery Boy</p>
        <p>Area: {{ auth()->user()->area }}</p>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="#overview" data-section="overview">
                <i class="fas fa-th-large"></i>
                Dashboard
            </a>
        </li>

        <li>
            <a href="#available-orders" data-section="available-orders">
                <i class="fas fa-box-open"></i>
                Available Orders
            </a>
        </li>

        <li>
            <a href="#my-deliveries" data-section="my-deliveries">
                <i class="fas fa-truck"></i>
                My Deliveries
            </a>
        </li>

        <li>
            <a href="#delivery-history" data-section="delivery-history">
                <i class="fas fa-history"></i>
                Delivery History
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

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-box-open"></i>
            </div>
            <h3 class="stat-number">{{ $availableOrders->count() }}</h3>
            <p class="stat-label">Available Orders</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-truck"></i>
            </div>
            <h3 class="stat-number">
                {{ $myOrders->whereIn('status', ['dispatched', 'on_the_way'])->count() }}
            </h3>
            <p class="stat-label">Active Deliveries</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3 class="stat-number">
                {{ $myOrders->where('status', 'delivered')->count() }}
            </h3>
            <p class="stat-label">Completed Deliveries</p>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-list"></i>
            </div>
            <h3 class="stat-number">{{ $myOrders->count() }}</h3>
            <p class="stat-label">Total Assigned</p>
        </div>
    </div>

    <div class="content-section" id="available-orders">
        <h3 class="section-title">Available Orders in Your Area</h3>

        @forelse ($availableOrders as $order)
            <div class="order-card">
                <div class="order-header">
                    <div class="order-id">
                        #{{ $order->order_number }}
                    </div>
                    <span class="order-status status-pending">
                        Ready
                    </span>
                </div>

                <div class="order-details">
                    <p>
                        <strong>Item:</strong>
                        {{ $order->dress_type }}
                    </p>

                    <p>
                        <strong>Tailor:</strong>
                        {{ optional(optional($order->tailor)->user)->name ?? 'N/A' }}
                    </p>

                    <p>
                        <strong>Area:</strong>
                        {{ $order->area }}
                    </p>

                    <div class="order-card-actions">
                        <form action="{{ route('delivery-boy.order.accept', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="accept-btn">
                                <i class="fas fa-check me-1"></i>
                                Accept
                            </button>
                        </form>

                        <form action="{{ route('delivery-boy.order.reject', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="reject-btn">
                                <i class="fas fa-times me-1"></i>
                                Reject
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>No available orders in your area right now.</p>
            </div>
        @endforelse
    </div>

    <div class="content-section" id="my-deliveries">
        <h3 class="section-title">My Deliveries</h3>

        @php
            $activeDeliveries = $myOrders->whereIn('status', ['dispatched', 'on_the_way']);
        @endphp

        @forelse ($activeDeliveries as $order)
            <div class="order-card">
                <div class="order-header">
                    <div class="order-id">
                        #{{ $order->order_number }}
                    </div>
                    <span class="order-status status-progress">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>

                <div class="order-details">
                    <p>
                        <strong>Item:</strong>
                        {{ $order->dress_type }}
                    </p>

                    <p>
                        <strong>Area:</strong>
                        {{ $order->area }}
                    </p>

                    @if($order->status === 'dispatched')
                        <form action="{{ route('delivery-boy.order.on-the-way', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="track-btn">
                                <i class="fas fa-route me-1"></i>
                                Mark On The Way
                            </button>
                        </form>
                    @elseif($order->status === 'on_the_way')
                        <form action="{{ route('delivery-boy.order.delivered', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="pay-btn">
                                <i class="fas fa-flag-checkered me-1"></i>
                                Mark Delivered
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-truck"></i>
                <p>No active deliveries right now.</p>
            </div>
        @endforelse
    </div>

    <div class="content-section" id="delivery-history">
        <h3 class="section-title">Delivery History</h3>

        @php
            $completedDeliveries = $myOrders->where('status', 'delivered');
        @endphp

        @forelse ($completedDeliveries as $order)
            <div class="order-card">
                <div class="order-header">
                    <div class="order-id">
                        #{{ $order->order_number }}
                    </div>
                    <span class="order-status status-completed">
                        Delivered
                    </span>
                </div>

                <div class="order-details">
                    <p>
                        <strong>Item:</strong>
                        {{ $order->dress_type }}
                    </p>

                    @if($order->actual_delivery_date)
                        <p>
                            <strong>Delivered On:</strong>
                            {{ \Carbon\Carbon::parse($order->actual_delivery_date)->format('M d, Y') }}
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted text-center py-3">
                No completed deliveries yet.
            </p>
        @endforelse
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/delivery-dashboard.js') }}"></script>

</body>
</html>