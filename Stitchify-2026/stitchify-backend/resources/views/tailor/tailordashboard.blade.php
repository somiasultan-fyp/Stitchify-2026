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

* { margin: 0; padding: 0; box-sizing: border-box; }
html { scroll-behavior: smooth; }

body {
  background-color: #f5f6fa;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  min-height: 100vh;
}

.sidebar {
  position: fixed;
  top: 0; left: 0;
  height: 100vh; width: 260px;
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
.sidebar-logo img { width: 80px; height: 50px; border-radius: 50%; margin-bottom: 10px; }
.sidebar-logo h3 { color: var(--text-white); font-size: 18px; font-weight: 600; margin: 0; }

.user-info {
  padding: 15px 20px;
  background-color: rgba(255,255,255,0.1);
  margin: 0 15px 20px;
  border-radius: 10px;
}
.user-info h4 { color: var(--text-white); font-size: 16px; margin: 0 0 5px 0; }
.user-info p  { color: rgba(255,255,255,0.7); font-size: 13px; margin: 0; }

.sidebar-menu { list-style: none; padding: 0 15px; }
.sidebar-menu li { margin-bottom: 5px; }
.sidebar-menu a {
  display: flex; align-items: center;
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
  content: ''; position: absolute; left: 0; top: 50%;
  transform: translateY(-50%);
  width: 0; height: 70%;
  background-color: var(--text-white);
  border-radius: 0 4px 4px 0;
  transition: width 0.3s ease;
}
.sidebar-menu a.active::before { width: 4px; }
.sidebar-menu a:hover, .sidebar-menu a.active {
  background-color: rgba(255,255,255,0.15);
  color: var(--text-white);
  padding-left: 20px;
}
.sidebar-menu a i { margin-right: 12px; width: 20px; text-align: center; }

.logout-btn { margin-top: 20px; padding: 0 15px; }

.main-content { margin-left: 260px; padding: 20px; min-height: 100vh; }

.top-bar {
  background-color: var(--text-white);
  padding: 20px 30px;
  border-radius: 15px;
  margin-bottom: 25px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.top-bar h2 { color: var(--accent-color); font-size: 28px; font-weight: 700; margin: 0; }

.slot-block {
  background: var(--text-white);
  border-radius: 15px;
  padding: 22px 28px;
  margin-bottom: 25px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  border-left: 5px solid var(--accent-color);
}
.slot-block-header {
  display: flex; justify-content: space-between;
  align-items: center; margin-bottom: 14px;
}
.slot-block-title {
  font-size: 16px; font-weight: 700;
  color: var(--accent-color);
  display: flex; align-items: center; gap: 9px;
}
.slot-block-title i { font-size: 18px; }
.slot-numbers { display: flex; gap: 24px; }
.slot-pill {
  text-align: center; padding: 8px 18px;
  border-radius: 10px; font-size: 13px;
}
.slot-pill .num {
  font-size: 22px; font-weight: 700;
  display: block; line-height: 1.1;
}
.slot-pill.total { background: #E6F1FB; color: #0C447C; }
.slot-pill.used  { background: #fff3e0; color: #f57c00; }
.slot-pill.free  { background: #e8f5e9; color: #388e3c; }
.slot-bar-wrap {
  background: #f0f0f0; border-radius: 30px;
  height: 12px; overflow: hidden; margin-bottom: 8px;
}
.slot-bar-fill { height: 100%; border-radius: 30px; transition: width 0.8s ease; }
.slot-bar-label { font-size: 12px; color: var(--copyright-bg); text-align: right; }

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px; margin-bottom: 25px;
}
.stat-card {
  background: linear-gradient(135deg, var(--text-white) 0%, #f8f9fa 100%);
  padding: 25px; border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-left: 4px solid var(--accent-color);
}
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
.stat-icon {
  width: 50px; height: 50px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 24px; margin-bottom: 15px;
}
.stat-icon.blue   { background-color: #e3f2fd; color: #1976d2; }
.stat-icon.green  { background-color: #e8f5e9; color: #388e3c; }
.stat-icon.orange { background-color: #fff3e0; color: #f57c00; }
.stat-icon.purple { background-color: #f3e5f5; color: #7b1fa2; }
.stat-number { font-size: 32px; font-weight: 700; color: var(--accent-color); margin: 0; }
.stat-label  { color: var(--copyright-bg); font-size: 14px; margin-top: 5px; }

.content-section {
  background-color: var(--text-white);
  padding: 25px; border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  margin-bottom: 25px; scroll-margin-top: 20px;
}
.section-title {
  color: var(--accent-color); font-size: 24px; font-weight: 600;
  margin-bottom: 20px; padding-bottom: 15px;
  border-bottom: 2px solid #f0f0f0;
}

.order-card {
  background-color: #f8f9fa; padding: 20px;
  border-radius: 12px; margin-bottom: 15px;
  border-left: 4px solid var(--accent-color);
  transition: all 0.3s ease;
}
.order-card:hover { box-shadow: 0 3px 15px rgba(0,0,0,0.1); }
.order-header {
  display: flex; justify-content: space-between;
  align-items: start; margin-bottom: 10px;
}
.order-id    { font-weight: 600; color: var(--accent-color); font-size: 16px; }
.order-status { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.status-pending  { background-color: #fff3e0; color: #f57c00; }
.status-progress { background-color: #e3f2fd; color: #1976d2; }
.status-ready    { background-color: #e8f5e9; color: #388e3c; }

.order-details { color: var(--copyright-bg); font-size: 14px; line-height: 1.6; }
.order-details strong { color: var(--primary-bg); }
.order-actions { margin-top: 15px; display: flex; gap: 10px; flex-wrap: wrap; }

.btn-sm-custom {
  padding: 6px 15px; border-radius: 6px;
  font-size: 13px; font-weight: 600;
  border: none; cursor: pointer; transition: all 0.3s ease;
}
.btn-accept   { background-color: #388e3c; color: var(--text-white); }
.btn-accept:hover   { background-color: #2e7d32; }
.btn-reject   { background-color: #dc3545; color: var(--text-white); }
.btn-reject:hover   { background-color: #c82333; }
.btn-view     { background-color: var(--accent-color); color: var(--text-white); }
.btn-view:hover     { background-color: var(--primary-bg); }
.btn-complete { background-color: #1976d2; color: var(--text-white); }
.btn-complete:hover { background-color: #1565c0; }

/* Modal */
.modal-header { background: linear-gradient(135deg, var(--accent-color), var(--primary-bg)); color: white; }
.modal-header .btn-close { filter: invert(1); }

.empty-state {
  text-align: center; padding: 40px 20px;
  color: var(--copyright-bg);
}
.empty-state i { font-size: 48px; margin-bottom: 15px; display: block; opacity: 0.4; }

/* Toast notification */
.toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }

.performance-stat {
  display: flex; justify-content: space-between;
  margin-bottom: 15px; padding-bottom: 15px;
  border-bottom: 1px solid #f0f0f0;
}
.performance-stat:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
.performance-label { color: var(--copyright-bg); font-size: 14px; }
.performance-value { color: var(--accent-color); font-weight: 600; font-size: 14px; }

.review-card { background-color: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 15px; }
.review-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
.customer-name { font-weight: 600; color: var(--accent-color); font-size: 16px; }
.review-rating { color: #f57c00; font-size: 14px; }
.review-text { color: var(--copyright-bg); font-size: 14px; line-height: 1.6; margin-bottom: 10px; }
.review-date { color: var(--copyright-bg); font-size: 12px; font-style: italic; }

@media (max-width: 768px) {
  .sidebar { transform: translateX(-100%); }
  .main-content { margin-left: 0; }
}
</style>
</head>
<body>
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
    <li><a href="#overview"       data-section="overview"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="#pending-orders" data-section="pending-orders"><i class="fas fa-hourglass-half"></i> Pending Orders <span class="badge bg-warning text-dark ms-1">{{ $stats['pending'] }}</span></a></li>
    <li><a href="#active-orders"  data-section="active-orders"><i class="fas fa-tasks"></i> Active Orders</a></li>
    <li><a href="#performance"    data-section="performance"><i class="fas fa-chart-line"></i> Performance</a></li>
    <li><a href="#reviews"        data-section="reviews"><i class="fas fa-star"></i> Reviews</a></li>
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
            {{ $order->expected_delivery_date->format('M d, Y') }}
            @if($order->expected_delivery_date->isPast())
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

  {{-- Performance --}}
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
      <p>Reviews Week 4 mein add honge</p>
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
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Current order IDs for modals
let currentAcceptId = null;
let currentRejectId = null;

// ── Toast helper ──────────────────────────
function showToast(msg, type = 'success') {
  const toast    = document.getElementById('mainToast');
  const toastMsg = document.getElementById('toastMsg');
  toastMsg.textContent = msg;
  toast.className = `toast align-items-center text-white border-0 bg-${type}`;
  new bootstrap.Toast(toast, { delay: 3000 }).show();
}

// ── Open Accept Modal ─────────────────────
function openAcceptModal(orderId, orderNum) {
  currentAcceptId = orderId;
  document.getElementById('acceptOrderNum').textContent = '#' + orderNum;
  document.getElementById('acceptPrice').value = '';
  document.getElementById('acceptDays').value  = '';
  new bootstrap.Modal(document.getElementById('acceptModal')).show();
}

// ── Confirm Accept ────────────────────────
async function confirmAccept() {
  const price = document.getElementById('acceptPrice').value;
  const days  = document.getElementById('acceptDays').value;

  if (!price || price < 1) {
    document.getElementById('acceptPrice').classList.add('is-invalid');
    return;
  }
  if (!days || days < 1) {
    document.getElementById('acceptDays').classList.add('is-invalid');
    return;
  }

  document.getElementById('acceptPrice').classList.remove('is-invalid');
  document.getElementById('acceptDays').classList.remove('is-invalid');

  const btn = document.getElementById('confirmAcceptBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Accepting...';

  try {
    const res = await fetch(`/tailor/order/${currentAcceptId}/accept`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
      },
      body: JSON.stringify({ price, delivery_days: days }),
    });

    const data = await res.json();

    if (data.success) {
      // Modal band karo
      bootstrap.Modal.getInstance(
        document.getElementById('acceptModal')
      ).hide();

      // Card remove karo — page reload ki zaroorat nahi
      const card = document.getElementById(`pending-card-${currentAcceptId}`);
      if (card) card.remove();

      showToast('Order accepted! Notification sent to customer.', 'success');

      // Stats update karo
      setTimeout(() => location.reload(), 1500);
    } else {
      showToast(data.message || 'something went wrong.', 'danger');
    }
  } catch (err) {
    showToast('Server error. Try again.', 'danger');
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-check me-1"></i> Accept Order';
  }
}

// ── Open Reject Modal ─────────────────────
function openRejectModal(orderId, orderNum) {
  currentRejectId = orderId;
  document.getElementById('rejectOrderNum').textContent = '#' + orderNum;
  document.getElementById('rejectReason').value = '';
  new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

// ── Confirm Reject ────────────────────────
async function confirmReject() {
  const reason = document.getElementById('rejectReason').value.trim();

  if (!reason) {
    document.getElementById('rejectReason').classList.add('is-invalid');
    return;
  }
  document.getElementById('rejectReason').classList.remove('is-invalid');

  try {
    const res = await fetch(`/tailor/order/${currentRejectId}/reject`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
      },
      body: JSON.stringify({ rejection_reason: reason }),
    });

    const data = await res.json();

    if (data.success) {
      bootstrap.Modal.getInstance(
        document.getElementById('rejectModal')
      ).hide();

      // Card remove karo
      const card = document.getElementById(`pending-card-${currentRejectId}`);
      if (card) card.remove();

      showToast('Order rejected. Notification sent to customer.', 'warning');
      setTimeout(() => location.reload(), 1500);
    } else {
      showToast(data.message || 'something went wrong.', 'danger');
    }
  } catch (err) {
    showToast('Server error. Try again.', 'danger');
  }
}

// ── Status Update ─────────────────────────
async function updateStatus(orderId, newStatus, btn) {
  const labels = {
    'in_progress' : 'Stitching started!',
    'ready'       : 'Order ready!',
    'dispatched'  : 'Order dispatched!',
  };

  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

  try {
    const res = await fetch(`/tailor/order/${orderId}/status`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': CSRF,
      },
      body: JSON.stringify({ status: newStatus }),
    });

    const data = await res.json();

    if (data.success) {
      showToast(labels[newStatus] || 'Status update ho gaya!', 'success');
      setTimeout(() => location.reload(), 1200);
    } else {
      showToast(data.message || 'something went wrong.', 'danger');
      btn.disabled = false;
    }
  } catch (err) {
    showToast('Server error. Try again.', 'danger');
    btn.disabled = false;
  }
}

// ── View Detail Modal ─────────────────────
async function viewDetail(orderId) {
  document.getElementById('detailBody').innerHTML = `
    <div class="text-center py-4">
      <div class="spinner-border text-primary"></div>
      <p class="mt-2 text-muted">Loading...</p>
    </div>`;

  new bootstrap.Modal(document.getElementById('detailModal')).show();

  try {
    const res  = await fetch(`/tailor/order/${orderId}/detail`);
    const data = await res.json();

    if (data.success) {
      const o = data.order;
      const m = o.measurement;

      document.getElementById('detailBody').innerHTML = `
        <div class="row g-3">
          <div class="col-md-6">
            <h6 class="fw-bold mb-3" style="color:var(--accent-color)">Order Info</h6>
            <table class="table table-borderless table-sm">
              <tr><th>Order #</th><td>${o.order_number}</td></tr>
              <tr><th>Customer</th><td>${o.customer_name}</td></tr>
              <tr><th>Phone</th><td>${o.customer_phone || '—'}</td></tr>
              <tr><th>Dress Type</th><td>${o.dress_type}</td></tr>
              <tr><th>Fabric Detail</th><td>${o.fabric_details || '—'}</td></tr>
              <tr><th>Delivery Type</th><td>${o.delivery_type}</td></tr>
              <tr><th>Special Note</th><td>${o.special_instructions || 'None'}</td></tr>
              <tr><th>Status</th><td><span class="badge bg-warning text-dark">${o.status}</span></td></tr>
              <tr><th>Price</th><td>${o.price ? 'Rs. ' + o.price : '—'}</td></tr>
              <tr><th>Expected</th><td>${o.expected_delivery_date || '—'}</td></tr>
              <tr><th>Date</th><td>${o.created_at}</td></tr>
            </table>
          </div>
          <div class="col-md-6">
            <h6 class="fw-bold mb-3" style="color:var(--accent-color)">Measurements (inches)</h6>
            ${m ? `
            <table class="table table-borderless table-sm">
              <tr><th>Chest</th><td>${m.chest || '—'}"</td></tr>
              <tr><th>Waist</th><td>${m.waist || '—'}"</td></tr>
              <tr><th>Hips</th><td>${m.hips || '—'}"</td></tr>
              <tr><th>Shoulder</th><td>${m.shoulder || '—'}"</td></tr>
              <tr><th>Sleeve Length</th><td>${m.sleeve_length || '—'}"</td></tr>
              <tr><th>Shirt Length</th><td>${m.shirt_length || '—'}"</td></tr>
              <tr><th>Trouser Length</th><td>${m.trouser_length || '—'}"</td></tr>
              <tr><th>Trouser Waist</th><td>${m.trouser_waist || '—'}"</td></tr>
              <tr><th>Neck</th><td>${m.neck || '—'}"</td></tr>
              ${m.additional_notes ? `<tr><th>Notes</th><td>${m.additional_notes}</td></tr>` : ''}
            </table>` : '<p class="text-muted">Measurements are not available.</p>'}
          </div>
        </div>`;
    }
  } catch (err) {
    document.getElementById('detailBody').innerHTML =
      '<p class="text-danger text-center">Detail could not be loaded.</p>';
  }
}

// ── Sidebar Scroll Spy ────────────────────
document.querySelectorAll('.sidebar-menu a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });
});

const sections = document.querySelectorAll('.content-section, .top-bar');
const navLinks = document.querySelectorAll('.sidebar-menu a[data-section]');

function updateActiveLink() {
  let current = '';
  sections.forEach(s => {
    if (window.scrollY >= (s.offsetTop - 100)) current = s.getAttribute('id');
  });
  navLinks.forEach(link => {
    link.classList.remove('active');
    if (link.getAttribute('data-section') === current) link.classList.add('active');
  });
}
window.addEventListener('scroll', updateActiveLink);
updateActiveLink();
</script>
</body>
</html>