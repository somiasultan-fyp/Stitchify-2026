<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">

@if(!auth()->check())
    <script>window.location.href = '/login';</script>
@endif

<title>Tailor Dashboard - Stitchify</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
:root {
  --primary-bg: #212529;
  --accent-color: #1b2a4a;
  --copyright-bg: #575a5b;
  --text-white: #ffffff;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html {
  scroll-behavior: smooth;
}

body {
  background-color: #f5f6fa;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  min-height: 100vh;
}

/* SIDEBAR */
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 260px;
  background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
  padding: 20px 0;
  box-shadow: 2px 0 10px rgba(0,0,0,0.1);
  overflow-y: auto;
  z-index: 1000;
}

.sidebar-logo {
  text-align: center;
  padding: 0 20px 20px;
  border-bottom: 1px solid rgba(255,255,255,0.1);
  margin-bottom: 20px;
}

.sidebar-logo img {
  width: 80px;
  height: 50px;
  border-radius: 50%;
  margin-bottom: 10px;
}

.sidebar-logo h3 {
  color: var(--text-white);
  font-size: 18px;
  font-weight: 600;
  margin: 0;
}

.user-info {
  padding: 15px 20px;
  background-color: rgba(255,255,255,0.1);
  margin: 0 15px 20px;
  border-radius: 10px;
}

.user-info h4 {
  color: var(--text-white);
  font-size: 16px;
  margin: 0 0 5px 0;
}

.user-info p {
  color: rgba(255,255,255,0.7);
  font-size: 13px;
  margin: 0;
}

.sidebar-menu {
  list-style: none;
  padding: 0 15px;
}

.sidebar-menu li {
  margin-bottom: 5px;
}

.sidebar-menu a {
  display: flex;
  align-items: center;
  padding: 12px 15px;
  color: rgba(255,255,255,0.8);
  text-decoration: none;
  border-radius: 8px;
  transition: all 0.3s ease;
  font-size: 15px;
  cursor: pointer;
  position: relative;
}

.sidebar-menu a::before {
  content: '';
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  width: 0;
  height: 70%;
  background-color: var(--text-white);
  border-radius: 0 4px 4px 0;
  transition: width 0.3s ease;
}

.sidebar-menu a.active::before {
  width: 4px;
}

.sidebar-menu a:hover,
.sidebar-menu a.active {
  background-color: rgba(255,255,255,0.15);
  color: var(--text-white);
  padding-left: 20px;
}

.sidebar-menu a i {
  margin-right: 12px;
  width: 20px;
  text-align: center;
}

.logout-btn {
  margin-top: 20px;
  padding: 0 15px;
}

/* MAIN CONTENT */
.main-content {
  margin-left: 260px;
  padding: 20px;
  min-height: 100vh;
}

.top-bar {
  background-color: var(--text-white);
  padding: 20px 30px;
  border-radius: 15px;
  margin-bottom: 25px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.top-bar h2 {
  color: var(--accent-color);
  font-size: 28px;
  font-weight: 700;
  margin: 0;
}

/* ALERTS */
.alert-success-custom {
  background: #e8f5e9;
  border-left: 4px solid #27ae60;
  color: #1e8449;
  padding: 12px 18px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-size: 14px;
  font-weight: 600;
}

.alert-error-custom {
  background: #fde8e8;
  border-left: 4px solid #c0392b;
  color: #c0392b;
  padding: 12px 18px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-size: 14px;
  font-weight: 600;
}

/* SLOT BLOCK */
.slot-block {
  background: var(--text-white);
  border-radius: 15px;
  padding: 22px 28px;
  margin-bottom: 25px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  border-left: 5px solid var(--accent-color);
}

.slot-block-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 14px;
}

.slot-block-title {
  font-size: 16px;
  font-weight: 700;
  color: var(--accent-color);
  display: flex;
  align-items: center;
  gap: 9px;
}

.slot-numbers {
  display: flex;
  gap: 24px;
}

.slot-pill {
  text-align: center;
  padding: 8px 18px;
  border-radius: 10px;
  font-size: 13px;
}

.slot-pill .num {
  font-size: 22px;
  font-weight: 700;
  display: block;
  line-height: 1.1;
}

.slot-pill.total {
  background: #E6F1FB;
  color: #0C447C;
}

.slot-pill.used {
  background: #fff3e0;
  color: #f57c00;
}

.slot-pill.free {
  background: #e8f5e9;
  color: #388e3c;
}

.slot-bar-wrap {
  background: #f0f0f0;
  border-radius: 30px;
  height: 12px;
  overflow: hidden;
  margin-bottom: 8px;
}

.slot-bar-fill {
  height: 100%;
  border-radius: 30px;
  transition: width 0.8s ease;
}

.slot-bar-label {
  font-size: 12px;
  color: var(--copyright-bg);
  text-align: right;
}

/* STATS */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px;
  margin-bottom: 25px;
}

.stat-card {
  background: linear-gradient(135deg, var(--text-white) 0%, #f8f9fa 100%);
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-left: 4px solid var(--accent-color);
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  margin-bottom: 15px;
}

.stat-icon.blue {
  background-color: #e3f2fd;
  color: #1976d2;
}

.stat-icon.green {
  background-color: #e8f5e9;
  color: #388e3c;
}

.stat-icon.orange {
  background-color: #fff3e0;
  color: #f57c00;
}

.stat-icon.purple {
  background-color: #f3e5f5;
  color: #7b1fa2;
}

.stat-number {
  font-size: 32px;
  font-weight: 700;
  color: var(--accent-color);
  margin: 0;
}

.stat-label {
  color: var(--copyright-bg);
  font-size: 14px;
  margin-top: 5px;
}

/* CONTENT SECTION */
.content-section {
  background-color: var(--text-white);
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  margin-bottom: 25px;
  scroll-margin-top: 20px;
}

.section-title {
  color: var(--accent-color);
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 20px;
  padding-bottom: 15px;
  border-bottom: 2px solid #f0f0f0;
}

/* ORDER CARD */
.order-card {
  background-color: #f8f9fa;
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 15px;
  border-left: 4px solid var(--accent-color);
  transition: all 0.3s ease;
}

.order-card:hover {
  box-shadow: 0 3px 15px rgba(0,0,0,0.1);
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 10px;
}

.order-id {
  font-weight: 600;
  color: var(--accent-color);
  font-size: 16px;
}

.order-status {
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.status-pending {
  background-color: #fff3e0;
  color: #f57c00;
}

.status-progress {
  background-color: #e3f2fd;
  color: #1976d2;
}

.status-completed {
  background-color: #e8f5e9;
  color: #388e3c;
}

.order-details {
  color: var(--copyright-bg);
  font-size: 14px;
  line-height: 1.6;
}

.order-details strong {
  color: var(--primary-bg);
}

.order-actions {
  margin-top: 15px;
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  align-items: center;
}

/* BUTTONS */
.btn-sm-custom {
  padding: 6px 15px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-accept {
  background-color: #388e3c;
  color: var(--text-white);
}

.btn-accept:hover {
  background-color: #2e7d32;
}

.btn-reject {
  background-color: #c62828;
  color: var(--text-white);
}

.btn-reject:hover {
  background-color: #b71c1c;
}

.btn-view {
  background-color: var(--accent-color);
  color: var(--text-white);
}

.btn-view:hover {
  background-color: var(--primary-bg);
}

.btn-complete {
  background-color: #1976d2;
  color: var(--text-white);
}

.btn-complete:hover {
  background-color: #1565c0;
}

/* DELIVERY UPDATE */
.delivery-update-box {
  width: 100%;
  margin-top: 10px;
  padding-top: 12px;
  border-top: 1px solid #e5e5e5;
}

.delivery-current {
  font-size: 12px;
  color: #666;
  margin-bottom: 7px;
}

.delivery-controls {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  align-items: center;
}

.delivery-select {
  border: 1px solid #ddd;
  border-radius: 6px;
  padding: 6px 10px;
  font-size: 13px;
  background: #fff;
  color: #333;
  min-width: 220px;
}

.btn-delivery {
  background-color: #1b2a4a;
  color: #fff;
}

.btn-delivery:hover {
  background-color: #212529;
}

/* INPUT */
.price-input {
  border: 1px solid #ddd;
  border-radius: 6px;
  padding: 5px 10px;
  font-size: 13px;
  width: 130px;
}

.days-input {
  border: 1px solid #ddd;
  border-radius: 6px;
  padding: 5px 10px;
  font-size: 13px;
  width: 80px;
}

/* REVIEWS */
.review-card {
  background-color: #f8f9fa;
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 15px;
}

.review-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.customer-name {
  font-weight: 600;
  color: var(--accent-color);
  font-size: 16px;
}

.review-rating {
  color: #f57c00;
  font-size: 14px;
}

.review-text {
  color: var(--copyright-bg);
  font-size: 14px;
  line-height: 1.6;
  margin-bottom: 10px;
}

.review-date {
  color: var(--copyright-bg);
  font-size: 12px;
  font-style: italic;
}

/* PERFORMANCE */
.performance-stat {
  display: flex;
  justify-content: space-between;
  margin-bottom: 15px;
  padding-bottom: 15px;
  border-bottom: 1px solid #f0f0f0;
}

.performance-stat:last-child {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}

.performance-label {
  color: var(--copyright-bg);
  font-size: 14px;
}

.performance-value {
  color: var(--accent-color);
  font-weight: 600;
  font-size: 14px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  .sidebar {
    transform: translateX(-100%);
  }

  .main-content {
    margin-left: 0;
  }

  .slot-numbers {
    gap: 10px;
  }

  .slot-pill .num {
    font-size: 18px;
  }

  .delivery-select {
    min-width: 100%;
  }
}
</style>
</head>

<body>

{{-- SIDEBAR --}}
<div class="sidebar">

  <div class="sidebar-logo">
    <img src="{{ asset('images/logo.png') }}" alt="Logo">
    <h3>Stitchify</h3>
  </div>

  <div class="user-info">
    <h4>{{ auth()->user()->name }}</h4>
    <p>Professional Tailor</p>
  </div>

  <ul class="sidebar-menu">
    <li>
      <a href="#overview" data-section="overview">
        <i class="fas fa-th-large"></i> Dashboard
      </a>
    </li>

    <li>
      <a href="#pending-orders" data-section="pending-orders">
        <i class="fas fa-hourglass-half"></i> Pending Orders
      </a>
    </li>

    <li>
      <a href="#active-orders" data-section="active-orders">
        <i class="fas fa-tasks"></i> Active Orders
      </a>
    </li>

    <li>
      <a href="#completed-orders" data-section="completed-orders">
        <i class="fas fa-check-double"></i> Completed
      </a>
    </li>

    <li>
      <a href="#performance" data-section="performance">
        <i class="fas fa-chart-line"></i> Performance
      </a>
    </li>

    <li>
      <a href="#reviews" data-section="reviews">
        <i class="fas fa-star"></i> Reviews
      </a>
    </li>
  </ul>

  <div class="logout-btn">
    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
      @csrf

      <button type="submit"
              style="background:none;border:none;padding:12px 15px;
                     color:#ff6b6b;width:100%;text-align:left;
                     cursor:pointer;border-radius:8px;font-size:15px;
                     display:flex;align-items:center;transition:all 0.3s ease;"
              onmouseover="this.style.backgroundColor='rgba(220,53,69,0.3)'"
              onmouseout="this.style.backgroundColor='transparent'">

        <i class="fas fa-sign-out-alt"
           style="margin-right:12px;width:20px;text-align:center;"></i>

        Logout
      </button>
    </form>
  </div>

</div>

{{-- MAIN CONTENT --}}
<div class="main-content">

  {{-- TOP BAR --}}
  <div class="top-bar" id="overview">
    <h2>Welcome, {{ auth()->user()->name }}!</h2>
  </div>

  {{-- SESSION ALERTS --}}
  @if(session('success'))
    <div class="alert-success-custom">
      <i class="fas fa-check-circle" style="margin-right:8px;"></i>
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="alert-error-custom">
      <i class="fas fa-exclamation-circle" style="margin-right:8px;"></i>
      {{ session('error') }}
    </div>
  @endif

  {{-- SLOT CAPACITY --}}
  @php
    $totalSlots = 20;
    $usedSlots  = $totalSlots - ($tailor->available_slots ?? 0);
    $freeSlots  = $tailor->available_slots ?? 0;
    $usedPct    = $totalSlots > 0 ? round(($usedSlots / $totalSlots) * 100) : 0;
  @endphp

  <div class="slot-block">

    <div class="slot-block-header">

      <div class="slot-block-title">
        <i class="fas fa-layer-group"></i>
        Slot Capacity
      </div>

      <div class="slot-numbers">

        <div class="slot-pill total">
          <span class="num">{{ $totalSlots }}</span>
          Total Slots
        </div>

        <div class="slot-pill used">
          <span class="num">{{ $usedSlots }}</span>
          In Use
        </div>

        <div class="slot-pill free">
          <span class="num">{{ $freeSlots }}</span>
          Available
        </div>

      </div>

    </div>

    <div class="slot-bar-wrap">
      <div class="slot-bar-fill"
           id="slotBarFill"
           style="width:{{ $usedPct }}%;
           background: {{ $usedPct < 60
             ? 'linear-gradient(90deg,#388e3c,#66bb6a)'
             : ($usedPct < 85
               ? 'linear-gradient(90deg,#f57c00,#ffb74d)'
               : 'linear-gradient(90deg,#c62828,#ef5350)') }};">
      </div>
    </div>

    <div class="slot-bar-label">
      {{ $usedPct }}% slots in use
    </div>

  </div>

  {{-- STATS --}}
  <div class="stats-grid">

    <div class="stat-card">
      <div class="stat-icon orange">
        <i class="fas fa-hourglass-half"></i>
      </div>

      <h3 class="stat-number">
        {{ $stats['pending'] }}
      </h3>

      <p class="stat-label">
        Pending Orders
      </p>
    </div>

    <div class="stat-card">
      <div class="stat-icon blue">
        <i class="fas fa-tasks"></i>
      </div>

      <h3 class="stat-number">
        {{ $stats['in_progress'] }}
      </h3>

      <p class="stat-label">
        In Progress
      </p>
    </div>

    <div class="stat-card">
      <div class="stat-icon green">
        <i class="fas fa-check-double"></i>
      </div>

      <h3 class="stat-number">
        {{ $stats['delivered'] }}
      </h3>

      <p class="stat-label">
        Completed Orders
      </p>
    </div>

    <div class="stat-card">
      <div class="stat-icon purple">
        <i class="fas fa-layer-group"></i>
      </div>

      <h3 class="stat-number">
        {{ $stats['slots_left'] }}
      </h3>

      <p class="stat-label">
        Available Slots
      </p>
    </div>

  </div>

  {{-- PENDING ORDERS --}}
  <div class="content-section" id="pending-orders">

    <h3 class="section-title">
      Pending Orders
    </h3>

    @forelse($pendingOrders as $order)

      <div class="order-card">

        <div class="order-header">

          <div class="order-id">
            #{{ $order->order_number }}
          </div>

          <span class="order-status status-pending">
            New Order
          </span>

        </div>

        <div class="order-details">

          <p>
            <strong>Customer:</strong>
            {{ $order->customer->user->name ?? 'N/A' }}
          </p>

          <p>
            <strong>Item:</strong>
            {{ $order->dress_type }}
          </p>

          <p>
            <strong>Order Date:</strong>
            {{ $order->created_at->format('M d, Y') }}
          </p>

        </div>

        <div class="order-actions">

          {{-- ACCEPT FORM --}}
          <form method="POST"
                action="{{ route('tailor.orders.accept', $order->id) }}"
                style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">

            @csrf
            @method('PATCH')

            <input type="number"
                   name="price"
                   placeholder="Price (PKR)"
                   class="price-input"
                   required>

            <input type="number"
                   name="delivery_days"
                   placeholder="Days"
                   class="days-input"
                   required>

            <button type="submit"
                    class="btn-sm-custom btn-accept">

              <i class="fas fa-check me-1"></i>
              Accept

            </button>

          </form>

          {{-- REJECT FORM --}}
          <form method="POST"
                action="{{ route('tailor.orders.reject', $order->id) }}"
                style="display:inline;"
                onsubmit="return confirm('Are you sure you want to reject this order?')">

            @csrf
            @method('PATCH')

            <input type="hidden"
                   name="rejection_reason"
                   value="Order rejected by tailor.">

            <button type="submit"
                    class="btn-sm-custom btn-reject">

              <i class="fas fa-times me-1"></i>
              Reject

            </button>

          </form>

        </div>

      </div>

    @empty

      <div class="text-center py-4" style="color:#aaa;">

        <i class="fas fa-hourglass-half fa-3x mb-3 d-block"></i>

        No pending orders.

      </div>

    @endforelse

  </div>

  {{-- ACTIVE ORDERS --}}
  <div class="content-section" id="active-orders">

    <h3 class="section-title">
      Active Orders
    </h3>

    @forelse($activeOrders as $order)

      @php

        $nextStatus = [
          'accepted'    => 'in_progress',
          'in_progress' => 'ready',
          'ready'       => 'dispatched',
          'dispatched'  => 'delivered',
        ][$order->status] ?? null;

        $btnLabels = [
          'in_progress' => [
            'icon' => 'fa-play',
            'label' => 'Start Progress'
          ],

          'ready' => [
            'icon' => 'fa-box',
            'label' => 'Mark Ready'
          ],

          'dispatched' => [
            'icon' => 'fa-truck',
            'label' => 'Mark Dispatched'
          ],

          'delivered' => [
            'icon' => 'fa-check-double',
            'label' => 'Mark Delivered'
          ],
        ];

      @endphp

      <div class="order-card">

        {{-- ORDER HEADER --}}
        <div class="order-header">

          <div class="order-id">
            #{{ $order->order_number }}
          </div>

          <span class="order-status status-progress">
            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
          </span>

        </div>

        {{-- ORDER DETAILS --}}
        <div class="order-details">

          <p>
            <strong>Customer:</strong>
            {{ $order->customer->user->name ?? 'N/A' }}
          </p>

          <p>
            <strong>Item:</strong>
            {{ $order->dress_type }}
          </p>

          <p>
            <strong>Order Date:</strong>
            {{ $order->created_at->format('M d, Y') }}
          </p>

          @if($order->expected_delivery_date)

            <p>
              <strong>Expected Delivery:</strong>
              {{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('M d, Y') }}
            </p>

          @endif

          @if($order->price)

            <p>
              <strong>Price:</strong>
              PKR {{ number_format($order->price) }}
            </p>

          @endif

          <p>
            <strong>Payment:</strong>

            <span class="badge bg-{{
              $order->payment_status === 'fully_paid'
                ? 'success'
                : ($order->payment_status === 'advance_paid'
                  ? 'warning'
                  : 'danger')
            }}">

              {{ ucfirst(str_replace('_', ' ', $order->payment_status ?? 'unpaid')) }}

            </span>
          </p>

        </div>

        {{-- ACTIONS --}}
        <div class="order-actions">

          {{-- ORDER STATUS UPDATE --}}
          @if($nextStatus)

            <form method="POST"
                  action="{{ route('tailor.orders.status', $order->id) }}"
                  style="display:inline;"
                  onsubmit="return confirm('Update order status to {{ ucfirst(str_replace('_', ' ', $nextStatus)) }}?')">

              @csrf
              @method('PATCH')

              <button type="submit"
                      class="btn-sm-custom btn-complete">

                <i class="fas {{ $btnLabels[$nextStatus]['icon'] ?? 'fa-arrow-right' }} me-1"></i>

                {{ $btnLabels[$nextStatus]['label'] ?? 'Update Status' }}

              </button>

            </form>

          @endif

          {{-- DELIVERY UPDATE --}}
          @if($order->delivery)

            <div class="delivery-update-box">

              <div class="delivery-current">

                <strong>Delivery Status:</strong>

                {{ ucfirst(str_replace('_', ' ', $order->delivery->status)) }}

              </div>

              <div class="delivery-controls">

                <select id="deliveryStatus-{{ $order->id }}"
                        class="delivery-select">

                  @foreach([
                    'scheduled',
                    'picked_up_from_customer',
                    'delivered_to_tailor',
                    'stitching_in_progress',
                    'picked_up_from_tailor',
                    'out_for_delivery',
                    'delivered'
                  ] as $deliveryStatus)

                    <option value="{{ $deliveryStatus }}"
                      {{ $order->delivery->status === $deliveryStatus ? 'selected' : '' }}>

                      {{ ucfirst(str_replace('_', ' ', $deliveryStatus)) }}

                    </option>

                  @endforeach

                </select>

                <button type="button"
                        class="btn-sm-custom btn-delivery"
                        onclick="updateTailorDelivery({{ $order->delivery->id }}, {{ $order->id }})">

                  <i class="fas fa-truck me-1"></i>
                  Update Delivery

                </button>

              </div>

            </div>

          @else

            <div style="width:100%;font-size:12px;color:#aaa;margin-top:8px;">

              <i class="fas fa-info-circle me-1"></i>
              No delivery record found for this order.

            </div>

          @endif

          {{-- VIEW DETAILS --}}
          <a href="{{ route('tailor.orders.show', $order->id) }}"
             class="btn-sm-custom btn-view">

            <i class="fas fa-eye me-1"></i>
            View Details

          </a>

        </div>

      </div>

    @empty

      <div class="text-center py-4" style="color:#aaa;">

        <i class="fas fa-tasks fa-3x mb-3 d-block"></i>

        No active orders.

      </div>

    @endforelse

  </div>

  {{-- COMPLETED ORDERS --}}
  <div class="content-section" id="completed-orders">

    <h3 class="section-title">
      Completed Orders
    </h3>

    @forelse($completedOrders as $order)

      <div class="order-card" style="border-left-color:#388e3c;">

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
            <strong>Customer:</strong>
            {{ $order->customer->user->name ?? 'N/A' }}
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

        </div>

        <div class="order-actions">

          <a href="{{ route('tailor.orders.show', $order->id) }}"
             class="btn-sm-custom btn-view">

            <i class="fas fa-eye me-1"></i>
            View Details

          </a>

        </div>

      </div>

    @empty

      <div class="text-center py-4" style="color:#aaa;">

        <i class="fas fa-check-double fa-3x mb-3 d-block"></i>

        No completed orders yet.

      </div>

    @endforelse

  </div>

  {{-- PERFORMANCE --}}
  <div class="content-section" id="performance">

    <h3 class="section-title">
      Performance Metrics
    </h3>

    <div class="performance-stat">
      <span class="performance-label">
        Total Pending Orders
      </span>

      <span class="performance-value">
        {{ $stats['pending'] }}
      </span>
    </div>

    <div class="performance-stat">
      <span class="performance-label">
        In Progress Orders
      </span>

      <span class="performance-value">
        {{ $stats['in_progress'] }}
      </span>
    </div>

    <div class="performance-stat">
      <span class="performance-label">
        Ready Orders
      </span>

      <span class="performance-value">
        {{ $stats['ready'] }}
      </span>
    </div>

    <div class="performance-stat">
      <span class="performance-label">
        Dispatched Orders
      </span>

      <span class="performance-value">
        {{ $stats['dispatched'] }}
      </span>
    </div>

    <div class="performance-stat">
      <span class="performance-label">
        Completed Orders
      </span>

      <span class="performance-value">
        {{ $stats['delivered'] }}
      </span>
    </div>

    <div class="performance-stat">
      <span class="performance-label">
        Available Slots
      </span>

      <span class="performance-value">
        {{ $stats['slots_left'] }}
      </span>
    </div>

  </div>

  {{-- REVIEWS --}}
  <div class="content-section" id="reviews">

    <h3 class="section-title">
      Recent Reviews
    </h3>

    <p class="text-muted text-center py-3">
      Reviews feature coming soon.
    </p>

  </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

<script>

/* SIDEBAR SMOOTH SCROLL */
document.querySelectorAll('.sidebar-menu a[href^="#"]').forEach(anchor => {

  anchor.addEventListener('click', function(e) {

    e.preventDefault();

    const target = document.querySelector(this.getAttribute('href'));

    if (target) {
      target.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }

  });

});


/* ACTIVE SIDEBAR LINK */
const sections = document.querySelectorAll('.content-section, .top-bar');
const navLinks = document.querySelectorAll('.sidebar-menu a[data-section]');

function updateActiveLink() {

  let current = '';

  sections.forEach(section => {

    if (window.scrollY >= section.offsetTop - 100) {
      current = section.getAttribute('id');
    }

  });

  navLinks.forEach(link => {

    link.classList.remove('active');

    if (link.getAttribute('data-section') === current) {
      link.classList.add('active');
    }

  });

}

window.addEventListener('scroll', updateActiveLink);

updateActiveLink();


/* TAILOR DELIVERY UPDATE */
async function updateTailorDelivery(deliveryId, orderId) {

  const statusElement =
    document.getElementById('deliveryStatus-' + orderId);

  if (!statusElement) {

    alert('Delivery status selector not found.');

    return;
  }

  const status = statusElement.value;

  const csrfElement =
    document.querySelector('meta[name="csrf-token"]');

  if (!csrfElement) {

    alert('CSRF token not found.');

    return;
  }

  const csrf = csrfElement.getAttribute('content');

  try {

    const response = await fetch(
      '/tailor/delivery/' + deliveryId + '/status',
      {
        method: 'PATCH',

        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json'
        },

        body: JSON.stringify({
          status: status
        })
      }
    );

    const data = await response.json();

    if (response.ok && data.success) {

      alert(
        'Delivery status updated successfully: ' +
        data.status
      );

      location.reload();

    } else {

      alert(
        data.message ||
        'Delivery status update failed. Please try again.'
      );

    }

  } catch (error) {

    console.error('Delivery update error:', error);

    alert(
      'Server error. Please try again.'
    );

  }

}

</script>

</body>
</html>
