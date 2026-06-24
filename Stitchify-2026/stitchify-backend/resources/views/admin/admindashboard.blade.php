<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<title>Admin Panel - Stitchify</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
:root {
  --primary-bg: #212529;
  --accent-color: #1b2a4a;
  --copyright-bg: #575a5b;
  --text-white: #ffffff;
  --danger: #dc3545;
  --success: #28a745;
  --warning: #ffc107;
  --info: #17a2b8;
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
.admin-badge {
  display: inline-block;
  background: linear-gradient(90deg, #c0392b, #e74c3c);
  color: white; font-size: 10px; font-weight: 700;
  letter-spacing: 1px; padding: 2px 8px;
  border-radius: 10px; margin-top: 4px;
}
.user-info {
  padding: 15px 20px;
  background-color: rgba(255,255,255,0.1);
  margin: 0 15px 20px; border-radius: 10px;
}
.user-info h4 { color: var(--text-white); font-size: 16px; margin: 0 0 4px; }
.user-info p  { color: rgba(255,255,255,0.7); font-size: 13px; margin: 0; }
.sidebar-menu { list-style: none; padding: 0 15px; }
.sidebar-menu li { margin-bottom: 5px; }
.sidebar-menu a {
  display: flex; align-items: center;
  padding: 12px 15px;
  color: rgba(255,255,255,0.8);
  text-decoration: none; border-radius: 8px;
  transition: all 0.3s ease; font-size: 15px;
  cursor: pointer; position: relative;
}
.sidebar-menu a::before {
  content: ''; position: absolute;
  left: 0; top: 50%; transform: translateY(-50%);
  width: 0; height: 70%;
  background-color: var(--text-white);
  border-radius: 0 4px 4px 0; transition: width 0.3s ease;
}
.sidebar-menu a.active::before { width: 4px; }
.sidebar-menu a:hover, .sidebar-menu a.active {
  background-color: rgba(255,255,255,0.15);
  color: var(--text-white); padding-left: 20px;
}
.sidebar-menu a i { margin-right: 12px; width: 20px; text-align: center; }
.logout-btn { margin-top: 20px; padding: 0 15px; }

.main-content { margin-left: 260px; padding: 20px; min-height: 100vh; }
.top-bar {
  background-color: var(--text-white);
  padding: 20px 30px; border-radius: 15px;
  margin-bottom: 25px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  display: flex; align-items: center; justify-content: space-between;
}
.top-bar h2 { color: var(--accent-color); font-size: 28px; font-weight: 700; margin: 0; }

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
.stat-icon.red    { background-color: #fde8e8; color: #c0392b; }
.stat-icon.teal   { background-color: #e0f7fa; color: #00796b; }
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
  display: flex; align-items: center; justify-content: space-between;
}

.admin-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.admin-table thead th {
  background-color: #f1f3f8; color: var(--accent-color);
  font-weight: 700; padding: 12px 15px;
  text-align: left; border-bottom: 2px solid #e0e0e0;
}
.admin-table tbody tr { border-bottom: 1px solid #f0f0f0; transition: background 0.2s; }
.admin-table tbody tr:hover { background-color: #f8f9fa; }
.admin-table td { padding: 12px 15px; color: #444; vertical-align: middle; }

.badge-status { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.badge-active    { background-color: #e8f5e9; color: #388e3c; }
.badge-blocked   { background-color: #fde8e8; color: #c0392b; }
.badge-pending   { background-color: #fff3e0; color: #f57c00; }
.badge-progress  { background-color: #e3f2fd; color: #1976d2; }
.badge-completed { background-color: #e8f5e9; color: #388e3c; }
.badge-rejected  { background-color: #fde8e8; color: #c0392b; }
.badge-customer  { background-color: #e3f2fd; color: #1565c0; }
.badge-tailor    { background-color: #f3e5f5; color: #6a1b9a; }

.btn-action {
  border: none; border-radius: 6px;
  padding: 5px 12px; font-size: 12px; font-weight: 600;
  cursor: pointer; transition: all 0.2s; margin-right: 4px;
}
.btn-block   { background: #fde8e8; color: #c0392b; }
.btn-block:hover   { background: #c0392b; color: white; }
.btn-unblock { background: #e8f5e9; color: #27ae60; }
.btn-unblock:hover { background: #27ae60; color: white; }
.btn-view    { background: #e3f2fd; color: #1565c0; }
.btn-view:hover    { background: #1565c0; color: white; }

.filter-row {
  display: flex; gap: 12px; margin-bottom: 18px;
  flex-wrap: wrap; align-items: center;
}
.filter-row input, .filter-row select {
  border: 1px solid #ddd; border-radius: 8px;
  padding: 8px 14px; font-size: 14px;
  outline: none; color: #444; background: #f8f9fa;
}
.filter-row input:focus, .filter-row select:focus {
  border-color: var(--accent-color);
}
.filter-row input { flex: 1; min-width: 180px; }

.report-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 18px;
}
.report-card {
  background: #f8f9fa; border-radius: 12px;
  padding: 20px; border-left: 4px solid var(--accent-color);
  transition: all 0.3s;
}
.report-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.09); transform: translateY(-3px); }
.report-card h5   { color: var(--accent-color); font-size: 16px; font-weight: 700; margin-bottom: 8px; }
.report-card p    { color: var(--copyright-bg); font-size: 13px; margin: 0 0 12px; }
.report-card .report-value { font-size: 28px; font-weight: 700; color: var(--accent-color); }
.report-card .report-sub   { font-size: 12px; color: #888; margin-top: 4px; }

.complaint-card {
  background: #f8f9fa; border-radius: 12px;
  padding: 18px 20px; margin-bottom: 14px;
  border-left: 4px solid #e67e22; transition: all 0.3s;
}
.complaint-card:hover { box-shadow: 0 3px 15px rgba(0,0,0,0.08); }
.complaint-card.resolved { border-left-color: #27ae60; opacity: 0.75; }
.complaint-header {
  display: flex; justify-content: space-between;
  align-items: center; margin-bottom: 8px;
}
.complaint-id   { font-weight: 700; color: var(--accent-color); font-size: 15px; }
.complaint-body { color: #555; font-size: 14px; line-height: 1.6; }
.complaint-meta { color: #999; font-size: 12px; margin-top: 6px; }

.modal-overlay {
  display: none; position: fixed;
  top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(0,0,0,0.45); z-index: 9999;
  justify-content: center; align-items: center;
}
.modal-overlay.show { display: flex; }
.modal-box {
  background: white; border-radius: 16px;
  padding: 30px; width: 420px; max-width: 95%;
  box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}
.modal-box h4   { color: var(--accent-color); margin-bottom: 12px; font-weight: 700; }
.modal-box p    { color: #555; font-size: 14px; margin-bottom: 20px; }
.modal-actions  { display: flex; gap: 10px; justify-content: flex-end; }
.modal-actions button { padding: 9px 22px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; }
.btn-confirm-block   { background: #c0392b; color: white; }
.btn-confirm-unblock { background: #27ae60; color: white; }
.btn-cancel-modal    { background: #f0f0f0; color: #555; }

.toast-container {
  position: fixed; bottom: 25px; right: 25px;
  z-index: 99999; display: flex; flex-direction: column; gap: 10px;
}
.toast-msg {
  background: var(--accent-color); color: white;
  padding: 13px 22px; border-radius: 10px;
  font-size: 14px; font-weight: 500;
  box-shadow: 0 4px 16px rgba(0,0,0,0.18);
  animation: slideIn 0.3s ease;
}
.toast-msg.success { background: #27ae60; }
.toast-msg.danger  { background: #c0392b; }
@keyframes slideIn {
  from { transform: translateX(60px); opacity: 0; }
  to   { transform: translateX(0); opacity: 1; }
}

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
    <div class="admin-badge">ADMIN PANEL</div>
  </div>

  <div class="user-info">
    <h4>{{ auth()->user()->name }}</h4>
    <p><i class="fas fa-shield-alt" style="margin-right:5px;color:#e74c3c;"></i>Administrator</p>
  </div>

  <ul class="sidebar-menu">
    <li><a href="#overview"     data-section="overview">    <i class="fas fa-th-large"></i>    Dashboard</a></li>
    <li><a href="#manage-users" data-section="manage-users"><i class="fas fa-users"></i>        Manage Users</a></li>
    <li><a href="#all-orders"   data-section="all-orders">  <i class="fas fa-shopping-bag"></i> All Orders</a></li>
    <li><a href="#reports"      data-section="reports">     <i class="fas fa-chart-bar"></i>    Reports</a></li>
    <li><a href="#complaints"   data-section="complaints">  <i class="fas fa-comments"></i>     Complaints</a></li>
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

  {{-- Top Bar --}}
  <div class="top-bar" id="overview">
    <h2>
      <i class="fas fa-shield-alt" style="color:#e74c3c;margin-right:10px;font-size:22px;"></i>
      Admin Dashboard
    </h2>
    <span style="color:#777;font-size:14px;">
      <i class="fas fa-calendar-alt" style="margin-right:5px;"></i>
      {{ now()->format('D, d M Y') }}
    </span>
  </div>

  {{-- ── STATS — Real Data ── --}}
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-users"></i></div>
      <h3 class="stat-number">{{ $stats['total_users'] }}</h3>
      <p class="stat-label">Total Users</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon purple"><i class="fas fa-cut"></i></div>
      <h3 class="stat-number">{{ $stats['total_tailors'] }}</h3>
      <p class="stat-label">Total Tailors</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon orange"><i class="fas fa-shopping-bag"></i></div>
      <h3 class="stat-number">{{ $stats['total_orders'] }}</h3>
      <p class="stat-label">Total Orders</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
      <h3 class="stat-number">{{ $stats['completed_orders'] }}</h3>
      <p class="stat-label">Completed Orders</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon red"><i class="fas fa-ban"></i></div>
      <h3 class="stat-number">{{ $stats['blocked_users'] }}</h3>
      <p class="stat-label">Blocked Accounts</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon teal"><i class="fas fa-clock"></i></div>
      <h3 class="stat-number">{{ $stats['pending_orders'] }}</h3>
      <p class="stat-label">Pending Orders</p>
    </div>
  </div>

  <div class="content-section" id="manage-users">
    <h3 class="section-title">Manage Users</h3>

    {{-- Search + Filter --}}
    <div class="filter-row">
      <input type="text" id="userSearch"
             placeholder="🔍 Search by name or email..."
             oninput="filterUsers()">
      <select id="roleFilter" onchange="filterUsers()">
        <option value="">All Roles</option>
        <option value="customer">Customer</option>
        <option value="tailor">Tailor</option>
      </select>
      <select id="statusFilter" onchange="filterUsers()">
        <option value="">All Status</option>
        <option value="1">Active</option>
        <option value="0">Blocked</option>
      </select>
    </div>

    <div style="overflow-x:auto;">
      <table class="admin-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Verified</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="usersBody">
          @forelse($users as $user)
          <tr class="user-row"
              data-name="{{ strtolower($user->name) }}"
              data-email="{{ strtolower($user->email) }}"
              data-role="{{ $user->role }}"
              data-active="{{ $user->is_active ? '1' : '0' }}">
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $user->name }}</strong></td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone ?? '—' }}</td>
            <td>
              <span class="badge-status {{ $user->role === 'customer' ? 'badge-customer' : 'badge-tailor' }}">
                {{ ucfirst($user->role) }}
              </span>
            </td>
            <td>
              @if($user->email_verified_at)
                <span style="color:#388e3c;font-size:13px;">
                  <i class="fas fa-check-circle"></i> Verified
                </span>
              @else
                <span style="color:#f57c00;font-size:13px;">
                  <i class="fas fa-clock"></i> Pending
                </span>
              @endif
            </td>
            <td>
              <span class="badge-status {{ $user->is_active ? 'badge-active' : 'badge-blocked' }}">
                {{ $user->is_active ? 'Active' : 'Blocked' }}
              </span>
            </td>
            <td>
              {{-- Block / Unblock --}}
              <form method="POST"
                    action="{{ route('admin.users.toggle', $user->id) }}"
                    style="display:inline;">
                @csrf @method('PATCH')
                @if($user->is_active)
                  <button type="submit"
                          class="btn-action btn-block"
                          onclick="return confirm('Block {{ $user->name }}?')">
                    <i class="fas fa-ban"></i> Block
                  </button>
                @else
                  <button type="submit"
                          class="btn-action btn-unblock"
                          onclick="return confirm('Unblock {{ $user->name }}?')">
                    <i class="fas fa-check"></i> Unblock
                  </button>
                @endif
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">
              Koi user nahi mila
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
      {{ $users->links() }}
    </div>
  </div>

  <div class="content-section" id="all-orders">
    <h3 class="section-title">Monitor All Orders</h3>

    <div class="filter-row">
      <input type="text" id="orderSearch"
             placeholder="🔍 Search by order # or customer..."
             oninput="filterOrders()">
      <select id="orderStatusFilter" onchange="filterOrders()">
        <option value="">All Statuses</option>
        <option value="pending">Pending</option>
        <option value="accepted">Accepted</option>
        <option value="in_progress">In Progress</option>
        <option value="ready">Ready</option>
        <option value="delivered">Delivered</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>

    <div style="overflow-x:auto;">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Tailor</th>
            <th>Dress Type</th>
            <th>Price</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="ordersBody">
          @forelse($orders as $order)
          @php
            $badgeClass = [
              'pending'     => 'badge-pending',
              'accepted'    => 'badge-progress',
              'in_progress' => 'badge-progress',
              'ready'       => 'badge-completed',
              'delivered'   => 'badge-completed',
              'cancelled'   => 'badge-rejected',
            ][$order->status] ?? 'badge-pending';
          @endphp
          <tr class="order-row"
              data-num="{{ strtolower($order->order_number) }}"
              data-customer="{{ strtolower($order->customer->user->name ?? '') }}"
              data-status="{{ $order->status }}">
            <td><strong>{{ $order->order_number }}</strong></td>
            <td>{{ $order->customer->user->name ?? '—' }}</td>
            <td>{{ $order->tailor->user->name ?? '—' }}</td>
            <td>{{ $order->dress_type }}</td>
            <td>{{ $order->price ? 'Rs. '.number_format($order->price) : '—' }}</td>
            <td>{{ $order->created_at->format('d M Y') }}</td>
            <td>
              <span class="badge-status {{ $badgeClass }}">
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">
              Koi order nahi hai abhi
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
      {{ $orders->links() }}
    </div>
  </div>

  <div class="content-section" id="reports">
    <h3 class="section-title">System Reports</h3>

    <div class="report-grid">
      <div class="report-card">
        <h5><i class="fas fa-shopping-bag" style="margin-right:7px;color:#1976d2;"></i>Total Orders</h5>
        <p>All orders placed since launch.</p>
        <div class="report-value">{{ $stats['total_orders'] }}</div>
        <div class="report-sub">{{ $stats['pending_orders'] }} pending</div>
      </div>
      <div class="report-card">
        <h5><i class="fas fa-check-circle" style="margin-right:7px;color:#388e3c;"></i>Completed</h5>
        <p>Successfully delivered orders.</p>
        <div class="report-value">{{ $stats['completed_orders'] }}</div>
        <div class="report-sub">
          @if($stats['total_orders'] > 0)
            {{ round(($stats['completed_orders'] / $stats['total_orders']) * 100) }}% completion rate
          @else
            0% completion rate
          @endif
        </div>
      </div>
      <div class="report-card">
        <h5><i class="fas fa-users" style="margin-right:7px;color:#7b1fa2;"></i>Total Users</h5>
        <p>Registered users on platform.</p>
        <div class="report-value">{{ $stats['total_users'] }}</div>
        <div class="report-sub">{{ $stats['total_tailors'] }} tailors, {{ $stats['total_customers'] }} customers</div>
      </div>
      <div class="report-card">
        <h5><i class="fas fa-ban" style="margin-right:7px;color:#c0392b;"></i>Blocked Accounts</h5>
        <p>Accounts blocked by admin.</p>
        <div class="report-value">{{ $stats['blocked_users'] }}</div>
        <div class="report-sub">Out of {{ $stats['total_users'] }} total users</div>
      </div>
    </div>
  </div>

  <div class="content-section" id="complaints">
    <h3 class="section-title">Complaints</h3>

    @forelse($complaints as $complaint)
    <div class="complaint-card {{ $complaint->status !== 'open' ? 'resolved' : '' }}">
      <div class="complaint-header">
        <div class="complaint-id">
          #{{ $complaint->id }} — {{ $complaint->user->name }}
        </div>
        <span class="badge-status {{ $complaint->status === 'open' ? 'badge-pending' : 'badge-completed' }}">
          {{ ucfirst($complaint->status) }}
        </span>
      </div>
      <div class="complaint-body">
        <strong>{{ $complaint->subject }}</strong><br>
        <span style="color:#777">{{ $complaint->message }}</span>
      </div>
      <div class="complaint-meta">
        <i class="fas fa-calendar-alt" style="margin-right:5px;"></i>
        {{ $complaint->created_at->format('d M Y') }}
      </div>

      {{-- Admin response agar pehle se hai --}}
      @if($complaint->admin_response)
        <div style="background:#e8f5e9;border-radius:8px;padding:10px;margin-top:10px;font-size:13px;">
          <strong style="color:#388e3c;">Admin Response:</strong>
          {{ $complaint->admin_response }}
        </div>
      @endif

      {{-- Respond form --}}
      @if($complaint->status === 'open')
      <form method="POST"
            action="{{ route('admin.complaints.respond', $complaint->id) }}"
            style="margin-top:12px;">
        @csrf @method('PATCH')
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <input type="text" name="admin_response"
                 placeholder="Type your response..."
                 style="flex:1;border:1px solid #ddd;border-radius:8px;
                        padding:8px 12px;font-size:13px;min-width:200px;"
                 required>
          <select name="status"
                  style="border:1px solid #ddd;border-radius:8px;
                         padding:8px 12px;font-size:13px;">
            <option value="in_review">In Review</option>
            <option value="resolved">Resolved</option>
            <option value="closed">Closed</option>
          </select>
          <button type="submit"
                  style="background:#27ae60;color:white;border:none;
                         border-radius:8px;padding:8px 16px;
                         font-size:13px;font-weight:600;cursor:pointer;">
            <i class="fas fa-reply me-1"></i> Respond
          </button>
        </div>
      </form>
      @endif
    </div>
    @empty
    <div class="text-center text-muted py-4">
      <i class="fas fa-check-circle fa-3x mb-3 d-block" style="opacity:0.2;"></i>
      <p>Koi complaint nahi hai abhi</p>
    </div>
    @endforelse
  </div>

</div>

<div class="toast-container" id="toastContainer"></div>

{{-- Success message --}}
@if(session('success'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    showToast('{{ session('success') }}', 'success');
  });
</script>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>

function filterUsers() {
  const q      = document.getElementById('userSearch').value.toLowerCase();
  const role   = document.getElementById('roleFilter').value;
  const status = document.getElementById('statusFilter').value;

  document.querySelectorAll('.user-row').forEach(row => {
    const name   = row.dataset.name;
    const email  = row.dataset.email;
    const rRole  = row.dataset.role;
    const active = row.dataset.active;

    const matchSearch = !q || name.includes(q) || email.includes(q);
    const matchRole   = !role   || rRole  === role;
    const matchStatus = !status || active === status;

    row.style.display = (matchSearch && matchRole && matchStatus) ? '' : 'none';
  });
}

function filterOrders() {
  const q      = document.getElementById('orderSearch').value.toLowerCase();
  const status = document.getElementById('orderStatusFilter').value;

  document.querySelectorAll('.order-row').forEach(row => {
    const num      = row.dataset.num;
    const customer = row.dataset.customer;
    const rStatus  = row.dataset.status;

    const matchSearch = !q      || num.includes(q) || customer.includes(q);
    const matchStatus = !status || rStatus === status;

    row.style.display = (matchSearch && matchStatus) ? '' : 'none';
  });
}

function showToast(msg, type = '') {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = `toast-msg ${type}`;
  toast.textContent = msg;
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 3500);
}

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
    if (window.scrollY >= s.offsetTop - 100) current = s.getAttribute('id');
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