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
<link href="{{ asset('css/common.css') }}" rel="stylesheet">
<link href="{{ asset('css/admin-dashboard.css') }}" rel="stylesheet">
</head>
<body>

<button class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<div class="sidebar" id="sidebar">
  <div class="sidebar-logo">
     <a href="/" style="text-decoration:none;">
        <img src="{{ asset('images/logo.png') }}" alt="Stitchify Logo">
        <h3 style="color:white;">Stitchify</h3>
    </a>
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
      <button type="submit" class="logout-link">
        <i class="fas fa-sign-out-alt"> </i>
        Logout
      </button>
    </form>
  </div>
</div>

<div class="main-content">

  <div class="top-bar" id="overview">
    <h2>
      <i class="fas fa-shield-alt"> </i>
      Admin Dashboard
    </h2>
    <div class="top-bar-actions">
      <span class="top-bar-date">
        <i class="fas fa-calendar-alt"> </i>
        {{ now()->format('D, d M Y') }}
      </span>
      <div class="notif-wrapper">
        <button onclick="toggleNotif()" class="notif-button-btn">
          <i class="fas fa-bell"></i>
          <span id="bellBadge" class="notif-bell-badge">0</span>
        </button>
        <div id="notifDropdown" class="notif-dropdown">
          <div class="notif-dropdown-header">
            <span><i class="fas fa-bell me-2"></i> Notifications</span>
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
  </div>

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
    <div class="filter-row">
      <input type="text" id="userSearch"
             placeholder="Search by name or email..."
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
    <div class="table-scroll">
      <table class="admin-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Verified</th>
            <th>Approval</th>
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
                <span class="verified-text">
                <i class="fas fa-check-circle"></i> Verified
                </span>
               @else
                <span class="unverified-text">
                <i class="fas fa-clock"></i> Pending
                </span>
               @endif
            </td>
            <td>
              @if($user->role === 'tailor' && $user->tailor)
              @if($user->tailor->status === 'approved')
                <span class="badge-status badge-active">Approved</span>
              @else
                <span class="badge-status badge-pending">Pending</span>
              @endif
              @else
                <span class="text-muted">-</span>
              @endif
            </td>
            <td>
              <span class="badge-status {{ $user->is_active ? 'badge-active' : 'badge-blocked' }}">
                {{ $user->is_active ? 'Active' : 'Blocked' }}
              </span>
            </td>
            
            <td>
              @if($user->role === 'tailor' && $user->tailor && $user->tailor->status === 'pending')
                <form method="POST"
                      action="{{ route('admin.tailors.approve', $user->id) }}"
                      style="display:inline;">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn-action btn-approve"
                          onclick="return confirm('Approve {{ $user->name }} as a tailor?')">
                    <i class="fas fa-check"></i> Approve
                  </button>
                </form>
              @endif
              <form method="POST"
                    action="{{ route('admin.users.toggle', $user->id) }}"
                    class="inline-form">
                @csrf @method('PATCH')
                @if($user->is_active)
                  <button type="submit" class="btn-action btn-block"
                          onclick="return confirm('Block {{ $user->name }}?')">
                    <i class="fas fa-ban"></i> Block
                  </button>
                @else
                  <button type="submit" class="btn-action btn-unblock"
                          onclick="return confirm('Unblock {{ $user->name }}?')">
                    <i class="fas fa-check"></i> Unblock
                  </button>
                @endif
              </form>
           </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">No users found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3">{{ $users->links() }}</div>
  </div>

  <div class="content-section" id="all-orders">
    <h3 class="section-title">Monitor All Orders</h3>
    <div class="filter-row">
      <input type="text" id="orderSearch"
             placeholder=" Search by order # or customer..."
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
    <div class="table-scroll">
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
            <th>Delivery</th>
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
            <td>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">No orders found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3">{{ $orders->links() }}</div>
  </div>

  <div class="content-section" id="reports">
    <h3 class="section-title">System Reports</h3>
    <div class="report-grid">
      <div class="report-card">
        <h5><i class="fas fa-shopping-bag report-icon-blue"></i>Total Orders</h5>
        <p>All orders placed since launch.</p>
        <div class="report-value">{{ $stats['total_orders'] }}</div>
        <div class="report-sub">{{ $stats['pending_orders'] }} pending</div>
      </div>
      <div class="report-card">
        <h5><i class="fas fa-check-circle report-icon-green" ></i>Completed</h5>
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
        <h5><i class="fas fa-users report-icon-purple"></i>Total Users</h5>
        <p>Registered users on platform.</p>
        <div class="report-value">{{ $stats['total_users'] }}</div>
        <div class="report-sub">{{ $stats['total_tailors'] }} tailors, {{ $stats['total_customers'] }} customers</div>
      </div>
      <div class="report-card">
        <h5><i class="fas fa-ban report-icon-red"></i>Blocked Accounts</h5>
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
        <span class="complaint-message">{{ $complaint->message }}</span>
      </div>
      <div class="complaint-meta">
        <i class="fas fa-calendar-alt" style="margin-right:5px;"></i>
        {{ $complaint->created_at->format('d M Y') }}
      </div>
      @if($complaint->admin_response)
        <div class="complaint-response">
          <strong>Admin Response:</strong>
          {{ $complaint->admin_response }}
        </div>
      @endif
      @if($complaint->status === 'open')
      <form method="POST"
            action="{{ route('admin.complaints.respond', $complaint->id) }}"
            class="complaint-form">
        @csrf @method('PATCH')
        <div class="complaint-form-row">
          <input type="text" name="admin_response"
                 placeholder="Type your response..."
                 class="complaint-input"
                 required>
          <select name="status" class="complaint-select">
            <option value="in_review">In Review</option>
            <option value="resolved">Resolved</option>
            <option value="closed">Closed</option>
          </select>
          <button type="submit" class="complaint-submit">
            <i class="fas fa-reply me-1"></i> Respond
          </button>
        </div>
      </form>
      @endif
    </div>
    @empty
    <div class="text-center text-muted py-4">
      <i class="fas fa-check-circle fa-3x mb-3 d-block complaints-empty-icon" ></i>
      <p>No complaints found</p>
    </div>
    @endforelse
  </div>

</div>

<div class="toast-container" id="toastContainer"></div>

@if(session('success'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    showToast('{{ session('success') }}', 'success');
  });
</script>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
</body>
</html>