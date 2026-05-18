<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Customer Dashboard - Stitchify</title>
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

/* ===== SIDEBAR ===== */
.sidebar {
  position: fixed; top: 0; left: 0;
  height: 100vh; width: 260px;
  background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
  padding: 20px 0;
  box-shadow: 2px 0 10px rgba(0,0,0,0.1);
  overflow-y: auto; z-index: 1000;
  display: flex; flex-direction: column;
}
.sidebar-logo {
  text-align: center; padding: 0 20px 15px;
  border-bottom: 1px solid rgba(255,255,255,0.1);
  margin-bottom: 15px;
}
.sidebar-logo img {
  width: 70px; height: 70px;
  border-radius: 50%;
  object-fit: cover;
  margin-bottom: 8px;
  border: 2px solid rgba(255,255,255,0.2);
}
.sidebar-logo h3 { color: var(--text-white); font-size: 17px; font-weight: 600; margin: 0; }
.user-info {
  padding: 12px 15px;
  background-color: rgba(255,255,255,0.1);
  margin: 0 15px 15px; border-radius: 10px;
}
.user-info h4 { color: var(--text-white); font-size: 15px; margin: 0 0 3px 0; }
.user-info p  { color: rgba(255,255,255,0.7); font-size: 12px; margin: 0; }
.sidebar-menu { list-style: none; padding: 0 15px; flex: 1; }
.sidebar-menu li { margin-bottom: 4px; }
.sidebar-menu a {
  display: flex; align-items: center;
  padding: 11px 15px;
  color: rgba(255,255,255,0.8);
  text-decoration: none; border-radius: 8px;
  transition: all 0.3s ease; font-size: 14px;
  cursor: pointer; position: relative;
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
  color: var(--text-white); padding-left: 20px;
}
.sidebar-menu a i { margin-right: 10px; width: 18px; text-align: center; }

/* Logout bottom mein fix */
.sidebar-footer {
  padding: 15px;
  border-top: 1px solid rgba(255,255,255,0.1);
  margin-top: 10px;
}
.logout-link {
  display: flex; align-items: center;
  padding: 11px 15px;
  background-color: rgba(220,53,69,0.2);
  color: #ff6b6b !important;
  text-decoration: none; border-radius: 8px;
  font-size: 14px; cursor: pointer;
  transition: all 0.3s ease;
  border: none; width: 100%;
}
.logout-link:hover { background-color: rgba(220,53,69,0.35); color: #ff6b6b; }
.logout-link i { margin-right: 10px; width: 18px; text-align: center; }

/* ===== TOP BAR ===== */
.main-content { margin-left: 260px; padding: 20px; min-height: 100vh; }
.top-bar {
  background-color: var(--text-white);
  padding: 12px 25px;
  border-radius: 12px;
  margin-bottom: 20px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.top-bar h2 {
  color: var(--accent-color);
  font-size: 20px;
  font-weight: 700;
  margin: 0;
}

/* Bell notification */
.bell-wrapper { position: relative; display: inline-block; }
.bell-btn {
  background: none; border: none; cursor: pointer;
  color: var(--accent-color); font-size: 20px;
  padding: 6px 8px; border-radius: 8px;
  transition: all 0.3s ease; position: relative;
}
.bell-btn:hover { background-color: #f0f0f0; color: #1976d2; }
.bell-badge {
  position: absolute; top: 2px; right: 2px;
  background-color: #e53935;
  color: white; font-size: 10px; font-weight: 700;
  width: 17px; height: 17px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  border: 2px solid white;
}

/* Notification Dropdown */
.notif-dropdown {
  display: none;
  position: absolute; top: 42px; right: 0;
  width: 300px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  z-index: 9999;
  overflow: hidden;
}
.notif-dropdown.show { display: block; }
.notif-header {
  padding: 12px 16px;
  background: var(--accent-color);
  color: white; font-weight: 600; font-size: 14px;
}
.notif-item {
  padding: 12px 16px;
  border-bottom: 1px solid #f0f0f0;
  font-size: 13px; color: #444;
  display: flex; gap: 10px; align-items: flex-start;
}
.notif-item:last-child { border-bottom: none; }
.notif-item i { color: var(--accent-color); margin-top: 2px; }
.notif-empty {
  padding: 20px; text-align: center;
  color: #aaa; font-size: 13px;
}

/* ===== STATS ===== */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 15px;
  margin-bottom: 20px;
}
.stat-card {
  background: white; padding: 20px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  border-left: 4px solid var(--accent-color);
  transition: transform 0.3s ease;
}
.stat-card:hover { transform: translateY(-4px); }
.stat-icon {
  width: 45px; height: 45px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  font-size: 20px; margin-bottom: 12px;
}
.stat-icon.blue   { background-color: #e3f2fd; color: #1976d2; }
.stat-icon.green  { background-color: #e8f5e9; color: #388e3c; }
.stat-icon.orange { background-color: #fff3e0; color: #f57c00; }
.stat-icon.purple { background-color: #f3e5f5; color: #7b1fa2; }
.stat-number { font-size: 28px; font-weight: 700; color: var(--accent-color); margin: 0; }
.stat-label  { color: var(--copyright-bg); font-size: 13px; margin-top: 4px; }

/* ===== ORDER CARDS ===== */
.content-section {
  background-color: white; padding: 22px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  margin-bottom: 20px; scroll-margin-top: 20px;
}
.section-title {
  color: var(--accent-color); font-size: 20px; font-weight: 600;
  margin-bottom: 18px; padding-bottom: 12px;
  border-bottom: 2px solid #f0f0f0;
}
.order-card {
  background-color: #f8f9fa; padding: 18px;
  border-radius: 10px; margin-bottom: 12px;
  border-left: 4px solid var(--accent-color);
  transition: all 0.3s ease;
}
.order-card:hover { box-shadow: 0 3px 15px rgba(0,0,0,0.1); }
.order-header {
  display: flex; justify-content: space-between;
  align-items: center; margin-bottom: 10px;
}
.order-id { font-weight: 700; color: var(--accent-color); font-size: 15px; }
.order-status {
  padding: 4px 12px; border-radius: 20px;
  font-size: 12px; font-weight: 600;
}
.status-pending   { background-color: #fff3e0; color: #f57c00; }
.status-progress  { background-color: #e3f2fd; color: #1976d2; }
.status-completed { background-color: #e8f5e9; color: #388e3c; }
.status-cancelled { background-color: #fce4ec; color: #c62828; }
.order-details { color: var(--copyright-bg); font-size: 13px; line-height: 1.7; }
.order-details strong { color: var(--primary-bg); }

/* Pay Now button */
.pay-btn {
  margin-top: 10px; padding: 8px 20px;
  background-color: #388e3c; color: white;
  border: none; border-radius: 8px;
  font-weight: 600; cursor: pointer;
  font-size: 13px; transition: background 0.3s;
}
.pay-btn:hover { background-color: #2e7d32; }

/* Waiting badge */
.waiting-badge {
  display: inline-block;
  margin-top: 6px; padding: 5px 12px;
  background-color: #fff8e1;
  color: #f57c00; border-radius: 20px;
  font-size: 12px; font-weight: 600;
  border: 1px solid #ffe082;
}

@media (max-width: 768px) {
  .sidebar { transform: translateX(-100%); }
  .main-content { margin-left: 0; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>
</head>
<body>

{{-- ====== SIDEBAR ====== --}}
<div class="sidebar">

  <div class="sidebar-logo">
    <img src="{{ asset('images/logo.png') }}" alt="Stitchify Logo">
    <h3>Stitchify</h3>
  </div>

  <div class="user-info">
    <h4>{{ auth()->user()->name }}</h4>
    <p>Customer</p>
  </div>

  <ul class="sidebar-menu">
    <li>
      <a href="#overview" data-section="overview">
        <i class="fas fa-th-large"></i> Dashboard
      </a>
    </li>
    <li>
      <a href="#my-orders" data-section="my-orders">
        <i class="fas fa-shopping-bag"></i> My Orders
      </a>
    </li>
    <li>
      <a href="#order-history" data-section="order-history">
        <i class="fas fa-history"></i> Order History
      </a>
    </li>
    <li>
      <a href="/customer/order-form">
        <i class="fas fa-plus-circle"></i> New Order
      </a>
    </li>
  </ul>

  {{-- Logout sidebar ke bilkul neeche --}}
  <div class="sidebar-footer">
    <a class="logout-link"
       href="#"
       onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>

</div>

{{-- Logout Form --}}
<form id="logout-form" action="/logout" method="POST" style="display:none">
  @csrf
</form>

{{-- ====== MAIN CONTENT ====== --}}
<div class="main-content">

  {{-- ===== TOP BAR ===== --}}
  <div class="top-bar" id="overview">
    <h2>Welcome, {{ auth()->user()->name }}!</h2>

    {{-- Bell Notification --}}
    <div class="bell-wrapper">
      <button class="bell-btn" onclick="toggleNotif()" id="bellBtn">
        <i class="fas fa-bell"></i>
        <span class="bell-badge" id="bellBadge">0</span>
      </button>

      <div class="notif-dropdown" id="notifDropdown">
        <div class="notif-header">
          <i class="fas fa-bell me-2"></i> Notifications
        </div>
        <div id="notifList">
          <div class="notif-empty">
            <i class="fas fa-check-circle fa-2x mb-2 d-block" style="color:#ccc"></i>
            Koi nayi notification nahi
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== STATS CARDS ===== --}}
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-shopping-bag"></i></div>
      <h3 class="stat-number">
        {{ $orders->whereIn('status',['accepted','in_progress','ready','dispatched'])->count() }}
      </h3>
      <p class="stat-label">Active Orders</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
      <h3 class="stat-number">
        {{ $orders->where('status','pending')->count() }}
      </h3>
      <p class="stat-label">Pending Orders</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
      <h3 class="stat-number">
        {{ $orders->where('status','delivered')->count() }}
      </h3>
      <p class="stat-label">Completed Orders</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon purple"><i class="fas fa-list"></i></div>
      <h3 class="stat-number">{{ $orders->count() }}</h3>
      <p class="stat-label">Total Orders</p>
    </div>
  </div>

  {{-- ===== MY ORDERS ===== --}}
  <div class="content-section" id="my-orders">
    <h3 class="section-title">My Orders</h3>

    @php
      $activeOrders = $orders->whereIn('status',
        ['pending','accepted','in_progress','ready','dispatched']);
    @endphp

    @forelse($activeOrders as $order)
      <div class="order-card">
        <div class="order-header">
          <div class="order-id">#{{ $order->order_number }}</div>
          <span class="order-status
            @if($order->status === 'pending')        status-pending
            @elseif($order->status === 'dispatched') status-completed
            @else                                    status-progress
            @endif">
            {{ ucfirst(str_replace('_',' ',$order->status)) }}
          </span>
        </div>

        <div class="order-details">
          <p><strong>Tailor:</strong>
            {{ optional(optional($order->tailor)->user)->name ?? 'N/A' }}
          </p>
          <p><strong>Item:</strong> {{ $order->dress_type }}</p>
          <p><strong>Order Date:</strong>
            {{ $order->created_at->format('M d, Y') }}
          </p>

          @if($order->status === 'pending')
            {{-- Tailor ne abhi accept/reject nahi kiya --}}
            <span class="waiting-badge">
              <i class="fas fa-hourglass-half me-1"></i>
              Tailor ke response ka intezaar hai...
            </span>

          @else
            {{-- Tailor ne accept kar liya --}}
            @if($order->price)
              <p><strong>Price:</strong>
                PKR {{ number_format($order->price) }}
              </p>
            @endif

            @if($order->expected_delivery_date)
              <p><strong>Expected Delivery:</strong>
                {{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('M d, Y') }}
              </p>
            @endif

            {{-- Payment Section --}}
            @if($order->payment_status === 'unpaid')
              <p>
                <span class="badge bg-danger">Payment Pending</span>
              </p>
              {{-- Pay Now — active button, Phase 5 mein link lagega --}}
              <button class="pay-btn">
                <i class="fas fa-credit-card me-1"></i> Pay Now
              </button>

            @elseif($order->payment_status === 'advance_paid')
              <p>
                <span class="badge bg-warning text-dark">
                  <i class="fas fa-check me-1"></i> Advance Paid
                </span>
              </p>

            @elseif($order->payment_status === 'fully_paid')
              <p>
                <span class="badge bg-success">
                  <i class="fas fa-check-double me-1"></i> Fully Paid
                </span>
              </p>
            @endif

          @endif
        </div>
      </div>

    @empty
      <div class="text-center py-4">
        <i class="fas fa-shopping-bag fa-3x text-muted mb-3 d-block"></i>
        <p class="text-muted mb-3">Koi active order nahi hai.</p>
        <a href="/customer/order-form" class="btn btn-primary">
          <i class="fas fa-plus me-1"></i> Naya Order Place Karo
        </a>
      </div>
    @endforelse
  </div>

  {{-- ===== ORDER HISTORY ===== --}}
  <div class="content-section" id="order-history">
    <h3 class="section-title">Order History</h3>

    @php
      $historyOrders = $orders->whereIn('status',['delivered','cancelled']);
    @endphp

    @forelse($historyOrders as $order)
      <div class="order-card">
        <div class="order-header">
          <div class="order-id">#{{ $order->order_number }}</div>
          <span class="order-status
            {{ $order->status === 'delivered' ? 'status-completed' : 'status-cancelled' }}">
            {{ ucfirst($order->status) }}
          </span>
        </div>
        <div class="order-details">
          <p><strong>Tailor:</strong>
            {{ optional(optional($order->tailor)->user)->name ?? 'N/A' }}
          </p>
          <p><strong>Item:</strong> {{ $order->dress_type }}</p>
          <p><strong>Order Date:</strong>
            {{ $order->created_at->format('M d, Y') }}
          </p>
          @if($order->price)
            <p><strong>Price:</strong>
              PKR {{ number_format($order->price) }}
            </p>
          @endif
          @if($order->actual_delivery_date)
            <p><strong>Delivered On:</strong>
              {{ \Carbon\Carbon::parse($order->actual_delivery_date)->format('M d, Y') }}
            </p>
          @endif
        </div>
      </div>
    @empty
      <p class="text-muted text-center py-3">
        Abhi koi completed order nahi hai.
      </p>
    @endforelse
  </div>

</div>{{-- end main-content --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>

  // ===== BELL NOTIFICATION TOGGLE =====
  function toggleNotif() {
    const dd = document.getElementById('notifDropdown');
    dd.classList.toggle('show');
  }

  // Bell ke bahar click karne par band ho
  document.addEventListener('click', function(e) {
    const wrapper = document.querySelector('.bell-wrapper');
    if (!wrapper.contains(e.target)) {
      document.getElementById('notifDropdown').classList.remove('show');
    }
  });

  // Badge count — abhi 0, Phase 5/6 mein real count aayega
  const notifCount = 0;
  const badge = document.getElementById('bellBadge');
  if (notifCount > 0) {
    badge.textContent = notifCount;
    badge.style.display = 'flex';
  } else {
    badge.style.display = 'none';
  }

  // ===== SIDEBAR SMOOTH SCROLL =====
  document.querySelectorAll('.sidebar-menu a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  // ===== SCROLL SPY =====
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