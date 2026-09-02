<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
@if(!auth()->check())
    <script>window.location.href = '/login';</script>
@endif
<title>Tailor Dashboard - Stitchify</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/tailor-dashboard.css') }}">
</head>
<body>
<div class="sidebar">
  @php
    $defaultAvatarSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" fill="#1B2A4A"/><circle cx="50" cy="38" r="18" fill="#ffffff"/><path d="M50 60c-22 0-34 12-34 26v14h68V86c0-14-12-26-34-26z" fill="#ffffff"/></svg>';
    $defaultAvatarUri = 'data:image/svg+xml;base64,' . base64_encode($defaultAvatarSvg);
  @endphp
  <div class="sidebar-logo">
    <a href="/" style="text-decoration:none;">
      <img src="{{ asset('images/logo.png') }}" alt="Stitchify Logo">
      <h3 style="color:white;">Stitchify</h3>
    </a>
  </div>

  <div class="user-info">
    <img src="{{ auth()->user()->profile_image ? Storage::url(auth()->user()->profile_image) : $defaultAvatarUri }}"
         alt="{{ auth()->user()->name }}"
         class="user-info-photo">
    <div>
      <h4>{{ auth()->user()->name }}</h4>
      <p>Professional Tailor</p>
    </div>
  </div>

  <ul class="sidebar-menu">
    <li><a href="#overview"       data-section="overview"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="#pending-orders" data-section="pending-orders"><i class="fas fa-hourglass-half"></i> Pending Orders <span class="badge bg-warning text-dark ms-1">{{ $stats['pending'] }}</span></a></li>
    <li><a href="#active-orders"  data-section="active-orders"><i class="fas fa-tasks"></i> Active Orders</a></li>
    <li><a href="#performance"    data-section="performance"><i class="fas fa-chart-line"></i> Performance</a></li>
    <li><a href="#reviews"        data-section="reviews"><i class="fas fa-star"></i> Reviews</a></li>
    <li><a href="{{ route('tailor.profile') }}"><i class="fas fa-user"></i> My Profile</a></li>
  </ul>

  <div class="logout-btn">
    <form method="POST" action="/logout" style="margin:0;">
      @csrf
      <button type="submit"
              style="background:none;border:none;padding:12px 15px;
                     color:#ff6b6b;width:100%;text-align:left;
                     cursor:pointer;border-radius:8px;font-size:15px;
                     display:flex;align-items:center;transition:all 0.3s ease;"
              onmouseover="this.style.backgroundColor='rgba(220,53,69,0.3)'"
              onmouseout="this.style.backgroundColor='transparent'">
        <i class="fas fa-sign-out-alt" style="margin-right:12px;width:20px;text-align:center;"></i>
        Logout
      </button>
    </form>
  </div>
</div>

<div class="main-content">

  <div class="top-bar" id="overview">
    <h2>Welcome, {{ auth()->user()->name }}!</h2>

    <div class="bell-wrapper">
      <button class="bell-btn" onclick="toggleNotif()">
        <i class="fas fa-bell"></i>
        <span id="bellBadge" class="bell-badge">0</span>
      </button>

      <div id="notifDropdown" class="notif-dropdown">
        <div class="notif-dropdown-header">
          <span><i class="fas fa-bell me-2"></i> Notifications</span>
          <a href="#" onclick="markAllRead(event)">Mark all read</a>
        </div>
        <div id="notifList">
          <div class="notif-empty">
            <i class="fas fa-check-circle fa-2x mb-2 d-block" style="color:#ccc"></i>
            No new notifications
          </div>
        </div>
        <div class="notif-dropdown-footer">
          <a href="{{ route('notifications.index') }}">View All Notifications</a>
        </div>
      </div>
    </div>
  </div>

  <div class="toast-container">
    <div id="mainToast" class="toast align-items-center border-0" role="alert">
      <div class="d-flex">
        <div class="toast-body fw-bold" id="toastMsg"></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto"
                data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  <div class="slot-block">
    <div class="slot-block-header">
      <div class="slot-block-title">
        <i class="fas fa-layer-group"></i> Slot Capacity
      </div>
      <div class="slot-numbers">
        <div class="slot-pill total">
          <span class="num">{{ $stats['max_slots'] }}</span>
          Total Slots
        </div>
        <div class="slot-pill used">
          <span class="num">{{ $stats['max_slots'] - $stats['available_slots'] }}</span>
          In Use
        </div>
        <div class="slot-pill free">
          <span class="num">{{ $stats['available_slots'] }}</span>
          Available
        </div>
      </div>
    </div>
    <div class="slot-bar-wrap">
      @php
        $usedPct = $stats['max_slots'] > 0
          ? round((($stats['max_slots'] - $stats['available_slots']) / $stats['max_slots']) * 100)
          : 0;
      @endphp
      <div class="slot-bar-fill" id="slotBarFill"
           style="width: {{ $usedPct }}%;
                  background: {{ $usedPct < 60
                    ? 'linear-gradient(90deg,#388e3c,#66bb6a)'
                    : ($usedPct < 85
                      ? 'linear-gradient(90deg,#f57c00,#ffb74d)'
                      : 'linear-gradient(90deg,#c62828,#ef5350)') }}">
      </div>
    </div>
    <div class="slot-bar-label">{{ $usedPct }}% slots in use</div>
  </div>

  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon orange"><i class="fas fa-hourglass-half"></i></div>
      <h3 class="stat-number">{{ $stats['pending'] }}</h3>
      <p class="stat-label">Pending Orders</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-tasks"></i></div>
      <h3 class="stat-number">{{ $stats['active'] }}</h3>
      <p class="stat-label">In Progress</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-check-double"></i></div>
      <h3 class="stat-number">{{ $stats['completed'] }}</h3>
      <p class="stat-label">Completed Orders</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon purple"><i class="fas fa-layer-group"></i></div>
      <h3 class="stat-number">{{ $stats['available_slots'] }}</h3>
      <p class="stat-label">Available Slots</p>
    </div>
  </div>

  <div class="content-section" id="pending-orders">
    <h3 class="section-title">
      Pending Orders
      @if($pendingOrders->count() > 0)
        <span class="badge bg-warning text-dark ms-2" style="font-size:14px">
          {{ $pendingOrders->count() }}
        </span>
      @endif
    </h3>

    @forelse($pendingOrders as $order)
    <div class="order-card" id="pending-card-{{ $order->id }}">
      <div class="order-header">
        <div class="order-id">#{{ $order->order_number }}</div>
        <span class="order-status status-pending">New Order</span>
      </div>
      <div class="order-details">
        <p><strong>Customer:</strong> {{ $order->customer->user->name }}</p>
        <p><strong>Phone:</strong> {{ $order->customer->user->phone ?? '—' }}</p>
        <p><strong>Item:</strong> {{ $order->dress_type }}</p>
        @if($order->special_instructions)
          <p><strong>Note:</strong> {{ $order->special_instructions }}</p>
        @endif
        <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
      </div>
      <div class="order-actions">
        <button class="btn-sm-custom btn-accept"
                onclick="openAcceptModal({{ $order->id }}, '{{ $order->order_number }}')">
          <i class="fas fa-check me-1"></i> Accept Order
        </button>

        <button class="btn-sm-custom btn-reject"
                onclick="openRejectModal({{ $order->id }}, '{{ $order->order_number }}')">
          <i class="fas fa-times me-1"></i> Reject
        </button>

        <button class="btn-sm-custom btn-view"
                onclick="viewDetail({{ $order->id }})">
          <i class="fas fa-eye me-1"></i> View Details
        </button>
      </div>
    </div>
    @empty
    <div class="empty-state">
      <i class="fas fa-inbox"></i>
      <p>No pending orders at the moment.</p>
    </div>
    @endforelse
  </div>

  <div class="content-section" id="active-orders">
    <h3 class="section-title">Active Orders</h3>

    @forelse($activeOrders as $order)
    <div class="order-card" id="active-card-{{ $order->id }}">
      <div class="order-header">
        <div class="order-id">#{{ $order->order_number }}</div>
        <span class="order-status
          {{ $order->status === 'ready' ? 'status-ready' : 'status-progress' }}">
          {{ ucfirst(str_replace('_', ' ', $order->status)) }}
        </span>
      </div>
      <div class="order-details">
        <p><strong>Customer:</strong> {{ $order->customer->user->name }}</p>
        <p><strong>Item:</strong> {{ $order->dress_type }}</p>
        <p><strong>Price:</strong> Rs. {{ number_format($order->price) }}</p>
        @if($order->expected_delivery_date)
          <p><strong>Expected Delivery:</strong>
            {{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('M d, Y') }}
           @if(\Carbon\Carbon::parse($order->expected_delivery_date)->isPast())
              <span class="badge bg-danger ms-1">Overdue!</span>
            @endif
          </p>
        @endif
        <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
      </div>
      <div class="order-actions">

        @if($order->status === 'accepted')
          <button class="btn-sm-custom btn-complete"
                  onclick="updateStatus({{ $order->id }}, 'in_progress', this)">
            <i class="fas fa-cut me-1"></i> Start Stitching
          </button>
        @elseif($order->status === 'in_progress')
          <button class="btn-sm-custom btn-complete"
                  onclick="updateStatus({{ $order->id }}, 'ready', this)">
            <i class="fas fa-check me-1"></i> Mark Ready
          </button>
        @elseif($order->status === 'ready')
          <button class="btn-sm-custom btn-complete"
                  onclick="updateStatus({{ $order->id }}, 'dispatched', this)">
            <i class="fas fa-truck me-1"></i> Mark Dispatched
          </button>
        @endif

        <button class="btn-sm-custom btn-view"
                onclick="viewDetail({{ $order->id }})">
          <i class="fas fa-eye me-1"></i> View Details
        </button>
      </div>
    </div>
    @empty
    <div class="empty-state">
      <i class="fas fa-tasks"></i>
      <p>No active orders at the moment.</p>
    </div>
    @endforelse
  </div>

  <div class="content-section" id="performance">
    <h3 class="section-title">Performance Metrics</h3>
    <div class="performance-stat">
      <span class="performance-label">Total Orders Completed</span>
      <span class="performance-value">{{ $completedCount }} orders</span>
    </div>
    <div class="performance-stat">
      <span class="performance-label">Available Slots</span>
      <span class="performance-value">{{ $stats['available_slots'] }} / {{ $stats['max_slots'] }}</span>
    </div>
    <div class="performance-stat">
      <span class="performance-label">Active Orders</span>
      <span class="performance-value">{{ $stats['active'] }}</span>
    </div>
  </div>

  <div class="content-section" id="reviews">
    <h3 class="section-title">Recent Reviews</h3>
    <div class="empty-state">
      <i class="fas fa-star"></i>
      <p>Reviews will be added in a future update.</p>
    </div>
  </div>

</div>

<div class="modal fade" id="acceptModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Accept Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-3">
          Order: <strong id="acceptOrderNum"></strong>
        </p>
        <div class="mb-3">
          <label class="form-label fw-bold">Price (Rs.) *</label>
          <input type="number" id="acceptPrice" class="form-control"
                 placeholder="e.g. 2500" min="1" required>
          <div class="invalid-feedback">Price is required</div>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold">Delivery Days *</label>
          <input type="number" id="acceptDays" class="form-control"
                 placeholder="e.g. 7" min="1" max="60" required>
          <div class="invalid-feedback">Delivery days are required</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"
                data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success"
                id="confirmAcceptBtn" onclick="confirmAccept()">
          <i class="fas fa-check me-1"></i> Accept Order
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reject Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-3">
          Order: <strong id="rejectOrderNum"></strong>
        </p>
        <div class="mb-3">
          <label class="form-label fw-bold">Rejection Reason *</label>
          <textarea id="rejectReason" class="form-control" rows="3"
                    placeholder="Why are you rejecting this order?"></textarea>
          <div class="invalid-feedback">Reason is required</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"
                data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger"
                onclick="confirmReject()">
          <i class="fas fa-times me-1"></i> Reject Order
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Order Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="detailBody">
        <div class="text-center py-4">
          <div class="spinner-border text-primary"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/tailor-dashboard.js') }}"></script>
</body>
</html>