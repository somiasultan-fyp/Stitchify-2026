<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
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

/* ===== SIDEBAR ===== */
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
  color: white;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 1px;
  padding: 2px 8px;
  border-radius: 10px;
  margin-top: 4px;
}

.user-info {
  padding: 15px 20px;
  background-color: rgba(255,255,255,0.1);
  margin: 0 15px 20px;
  border-radius: 10px;
}
.user-info h4 { color: var(--text-white); font-size: 16px; margin: 0 0 4px 0; }
.user-info p { color: rgba(255,255,255,0.7); font-size: 13px; margin: 0; }

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
  content: ''; position: absolute;
  left: 0; top: 50%; transform: translateY(-50%);
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
.logout-btn a { background-color: rgba(220,53,69,0.2); color: #ff6b6b; }
.logout-btn a:hover { background-color: rgba(220,53,69,0.3); }

/* ===== MAIN ===== */
.main-content { margin-left: 260px; padding: 20px; min-height: 100vh; }

.top-bar {
  background-color: var(--text-white);
  padding: 20px 30px;
  border-radius: 15px;
  margin-bottom: 25px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  display: flex; align-items: center; justify-content: space-between;
}
.top-bar h2 { color: var(--accent-color); font-size: 28px; font-weight: 700; margin: 0; }
.top-bar-right { display: flex; align-items: center; gap: 15px; }
.top-bar-right .badge-notif {
  background: #e74c3c; color: white; border-radius: 50%;
  width: 22px; height: 22px; font-size: 11px;
  display: flex; align-items: center; justify-content: center;
  font-weight: 700;
}

/* ===== STATS ===== */
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
.stat-icon.blue { background-color: #e3f2fd; color: #1976d2; }
.stat-icon.green { background-color: #e8f5e9; color: #388e3c; }
.stat-icon.orange { background-color: #fff3e0; color: #f57c00; }
.stat-icon.purple { background-color: #f3e5f5; color: #7b1fa2; }
.stat-icon.red { background-color: #fde8e8; color: #c0392b; }
.stat-icon.teal { background-color: #e0f7fa; color: #00796b; }

.stat-number { font-size: 32px; font-weight: 700; color: var(--accent-color); margin: 0; }
.stat-label { color: var(--copyright-bg); font-size: 14px; margin-top: 5px; }

/* ===== SECTIONS ===== */
.content-section {
  background-color: var(--text-white);
  padding: 25px; border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  margin-bottom: 25px;
  scroll-margin-top: 20px;
}
.section-title {
  color: var(--accent-color); font-size: 24px; font-weight: 600;
  margin-bottom: 20px; padding-bottom: 15px;
  border-bottom: 2px solid #f0f0f0;
  display: flex; align-items: center; justify-content: space-between;
}
.section-title span { font-size: 15px; }

/* ===== TABLE ===== */
.admin-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.admin-table thead th {
  background-color: #f1f3f8;
  color: var(--accent-color);
  font-weight: 700;
  padding: 12px 15px;
  text-align: left;
  border-bottom: 2px solid #e0e0e0;
}
.admin-table tbody tr {
  border-bottom: 1px solid #f0f0f0;
  transition: background 0.2s;
}
.admin-table tbody tr:hover { background-color: #f8f9fa; }
.admin-table td { padding: 12px 15px; color: #444; vertical-align: middle; }

/* ===== BADGES ===== */
.badge-status {
  padding: 4px 12px; border-radius: 20px;
  font-size: 12px; font-weight: 600;
}
.badge-active { background-color: #e8f5e9; color: #388e3c; }
.badge-blocked { background-color: #fde8e8; color: #c0392b; }
.badge-pending { background-color: #fff3e0; color: #f57c00; }
.badge-progress { background-color: #e3f2fd; color: #1976d2; }
.badge-completed { background-color: #e8f5e9; color: #388e3c; }
.badge-rejected { background-color: #fde8e8; color: #c0392b; }
.badge-open { background-color: #fff3e0; color: #e67e22; }
.badge-resolved { background-color: #e8f5e9; color: #27ae60; }
.badge-customer { background-color: #e3f2fd; color: #1565c0; }
.badge-tailor { background-color: #f3e5f5; color: #6a1b9a; }

/* ===== BUTTONS ===== */
.btn-action {
  border: none; border-radius: 6px;
  padding: 5px 12px; font-size: 12px; font-weight: 600;
  cursor: pointer; transition: all 0.2s;
  margin-right: 4px;
}
.btn-block { background: #fde8e8; color: #c0392b; }
.btn-block:hover { background: #c0392b; color: white; }
.btn-unblock { background: #e8f5e9; color: #27ae60; }
.btn-unblock:hover { background: #27ae60; color: white; }
.btn-view { background: #e3f2fd; color: #1565c0; }
.btn-view:hover { background: #1565c0; color: white; }
.btn-resolve { background: #e8f5e9; color: #27ae60; }
.btn-resolve:hover { background: #27ae60; color: white; }
.btn-dismiss { background: #f1f1f1; color: #888; }
.btn-dismiss:hover { background: #888; color: white; }

/* ===== SEARCH / FILTER ===== */
.filter-row {
  display: flex; gap: 12px; margin-bottom: 18px; flex-wrap: wrap; align-items: center;
}
.filter-row input, .filter-row select {
  border: 1px solid #ddd; border-radius: 8px;
  padding: 8px 14px; font-size: 14px;
  outline: none; color: #444; background: #f8f9fa;
}
.filter-row input:focus, .filter-row select:focus { border-color: var(--accent-color); }
.filter-row input { flex: 1; min-width: 180px; }

/* ===== REPORT CARDS ===== */
.report-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 18px;
}
.report-card {
  background: #f8f9fa;
  border-radius: 12px;
  padding: 20px;
  border-left: 4px solid var(--accent-color);
  transition: all 0.3s;
}
.report-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.09); transform: translateY(-3px); }
.report-card h5 { color: var(--accent-color); font-size: 16px; font-weight: 700; margin-bottom: 8px; }
.report-card p { color: var(--copyright-bg); font-size: 13px; margin: 0 0 12px 0; }
.report-card .report-value { font-size: 28px; font-weight: 700; color: var(--accent-color); }
.report-card .report-sub { font-size: 12px; color: #888; margin-top: 4px; }

/* ===== COMPLAINTS ===== */
.complaint-card {
  background: #f8f9fa;
  border-radius: 12px;
  padding: 18px 20px;
  margin-bottom: 14px;
  border-left: 4px solid #e67e22;
  transition: all 0.3s;
}
.complaint-card:hover { box-shadow: 0 3px 15px rgba(0,0,0,0.08); }
.complaint-card.resolved { border-left-color: #27ae60; opacity: 0.75; }
.complaint-header {
  display: flex; justify-content: space-between; align-items: center;
  margin-bottom: 8px;
}
.complaint-id { font-weight: 700; color: var(--accent-color); font-size: 15px; }
.complaint-body { color: #555; font-size: 14px; line-height: 1.6; }
.complaint-meta { color: #999; font-size: 12px; margin-top: 6px; }
.complaint-actions { margin-top: 12px; display: flex; gap: 8px; }

/* ===== MODAL ===== */
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
.modal-box h4 { color: var(--accent-color); margin-bottom: 12px; font-weight: 700; }
.modal-box p { color: #555; font-size: 14px; margin-bottom: 20px; }
.modal-actions { display: flex; gap: 10px; justify-content: flex-end; }
.modal-actions button { padding: 9px 22px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; }
.btn-confirm-block { background: #c0392b; color: white; }
.btn-confirm-block:hover { background: #a93226; }
.btn-confirm-unblock { background: #27ae60; color: white; }
.btn-confirm-unblock:hover { background: #1e8449; }
.btn-cancel-modal { background: #f0f0f0; color: #555; }
.btn-cancel-modal:hover { background: #ddd; }

/* ===== TOAST ===== */
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
  to   { transform: translateX(0);    opacity: 1; }
}

@media (max-width: 768px) {
  .sidebar { transform: translateX(-100%); }
  .main-content { margin-left: 0; }
}
</style>
</head>
<body>

<!-- ===== SIDEBAR ===== -->
<div class="sidebar">
  <div class="sidebar-logo">
    <img src="{{ asset('images/logo.png') }}" alt="Logo">
    <h3>Stitchify</h3>
    <div class="admin-badge">ADMIN PANEL</div>
  </div>

  <div class="user-info">
    <h4>Super Admin</h4>
    <p><i class="fas fa-shield-alt" style="margin-right:5px;color:#e74c3c;"></i>Administrator</p>
  </div>

  <ul class="sidebar-menu">
    <li><a href="#overview"      data-section="overview">     <i class="fas fa-th-large"></i>    Dashboard</a></li>
    <li><a href="#manage-users"  data-section="manage-users"> <i class="fas fa-users"></i>        Manage Users</a></li>
    <li><a href="#all-orders"    data-section="all-orders">   <i class="fas fa-shopping-bag"></i> All Orders</a></li>
    <li><a href="#reports"       data-section="reports">      <i class="fas fa-chart-bar"></i>    System Reports</a></li>
    <li><a href="#complaints"    data-section="complaints">   <i class="fas fa-comments"></i>     Complaints &amp; Feedback</a></li>
    <li><a href="#security"      data-section="security">     <i class="fas fa-lock"></i>         Security</a></li>
  </ul>

  <div class="logout-btn">
    <form method="POST" action="/logout" style="margin:0;">
    @csrf
    <button type="submit" style="background:none;border:none;padding:12px 15px;color:#ff6b6b;width:100%;text-align:left;cursor:pointer;border-radius:8px;font-size:15px;display:flex;align-items:center;transition:all 0.3s ease;"
        onmouseover="this.style.backgroundColor='rgba(220,53,69,0.3)'"
        onmouseout="this.style.backgroundColor='transparent'">
        <i class="fas fa-sign-out-alt" style="margin-right:12px;width:20px;text-align:center;"></i> Logout
    </button>
      </form>
  </div>
</div>

<!-- ===== MAIN ===== -->
<div class="main-content">

  <!-- Top Bar -->
  <div class="top-bar" id="overview">
    <h2><i class="fas fa-shield-alt" style="color:#e74c3c;margin-right:10px;font-size:22px;"></i>Admin Dashboard</h2>
    <div class="top-bar-right">
      <span style="color:#777;font-size:14px;"><i class="fas fa-calendar-alt" style="margin-right:5px;"></i>
        <span id="current-date"></span>
      </span>
      <div style="position:relative;cursor:pointer;">
        <i class="fas fa-bell" style="font-size:20px;color:#555;"></i>
        <div class="badge-notif" style="position:absolute;top:-8px;right:-8px;">3</div>
      </div>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-users"></i></div>
      <h3 class="stat-number">248</h3>
      <p class="stat-label">Total Users</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon purple"><i class="fas fa-cut"></i></div>
      <h3 class="stat-number">64</h3>
      <p class="stat-label">Active Tailors</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon orange"><i class="fas fa-shopping-bag"></i></div>
      <h3 class="stat-number">137</h3>
      <p class="stat-label">Total Orders</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
      <h3 class="stat-number">98</h3>
      <p class="stat-label">Completed Orders</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon red"><i class="fas fa-ban"></i></div>
      <h3 class="stat-number">7</h3>
      <p class="stat-label">Blocked Accounts</p>
    </div>
    <div class="stat-card">
      <div class="stat-icon teal"><i class="fas fa-comment-dots"></i></div>
      <h3 class="stat-number">14</h3>
      <p class="stat-label">Open Complaints</p>
    </div>
  </div>

  <!-- Manage Users -->
  <div class="content-section" id="manage-users">
    <h3 class="section-title">
      Manage Users
      <span>
        <button class="btn-action btn-view" onclick="showToast('Users exported successfully!','success')">
          <i class="fas fa-download"></i> Export
        </button>
      </span>
    </h3>

    <div class="filter-row">
      <input type="text" id="userSearch" placeholder="🔍  Search by name or email..." oninput="filterUsers()">
      <select id="roleFilter" onchange="filterUsers()">
        <option value="">All Roles</option>
        <option value="Customer">Customer</option>
        <option value="Tailor">Tailor</option>
      </select>
      <select id="statusFilter" onchange="filterUsers()">
        <option value="">All Status</option>
        <option value="Active">Active</option>
        <option value="Blocked">Blocked</option>
      </select>
    </div>

    <div style="overflow-x:auto;">
      <table class="admin-table" id="usersTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Joined</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="usersBody">
          <!-- Filled by JS -->
        </tbody>
      </table>
    </div>
  </div>

  <!-- All Orders -->
  <div class="content-section" id="all-orders">
    <h3 class="section-title">Monitor All Orders</h3>

    <div class="filter-row">
      <input type="text" id="orderSearch" placeholder="🔍  Search by Order ID or tailor..." oninput="filterOrders()">
      <select id="orderStatusFilter" onchange="filterOrders()">
        <option value="">All Statuses</option>
        <option value="Pending">Pending</option>
        <option value="In Progress">In Progress</option>
        <option value="Completed">Completed</option>
        <option value="Rejected">Rejected</option>
      </select>
    </div>

    <div style="overflow-x:auto;">
      <table class="admin-table" id="ordersTable">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Tailor</th>
            <th>Item</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="ordersBody"></tbody>
      </table>
    </div>
  </div>

  <!-- Reports -->
  <div class="content-section" id="reports">
    <h3 class="section-title">System Reports</h3>

    <div class="report-grid">
      <div class="report-card">
        <h5><i class="fas fa-shopping-bag" style="margin-right:7px;color:#1976d2;"></i>Total Orders</h5>
        <p>All orders placed in the system since launch.</p>
        <div class="report-value">137</div>
        <div class="report-sub">↑ 18% this month</div>
      </div>
      <div class="report-card">
        <h5><i class="fas fa-rupee-sign" style="margin-right:7px;color:#388e3c;"></i>Total Revenue</h5>
        <p>Estimated revenue from completed orders.</p>
        <div class="report-value">Rs. 4,82,000</div>
        <div class="report-sub">↑ 12% vs last month</div>
      </div>
      <div class="report-card">
        <h5><i class="fas fa-user-plus" style="margin-right:7px;color:#7b1fa2;"></i>New Users (Month)</h5>
        <p>Users registered in the current month.</p>
        <div class="report-value">31</div>
        <div class="report-sub">↑ 7% vs last month</div>
      </div>
      <div class="report-card">
        <h5><i class="fas fa-star" style="margin-right:7px;color:#f57c00;"></i>Avg. Rating</h5>
        <p>Average tailor rating across all reviews.</p>
        <div class="report-value">4.3 / 5</div>
        <div class="report-sub">Based on 412 reviews</div>
      </div>
      <div class="report-card">
        <h5><i class="fas fa-times-circle" style="margin-right:7px;color:#c0392b;"></i>Order Rejection Rate</h5>
        <p>Orders rejected by tailors.</p>
        <div class="report-value">8.7%</div>
        <div class="report-sub">12 orders rejected</div>
      </div>
      <div class="report-card">
        <h5><i class="fas fa-truck" style="margin-right:7px;color:#00796b;"></i>Delivery Success Rate</h5>
        <p>Orders successfully delivered on time.</p>
        <div class="report-value">94.2%</div>
        <div class="report-sub">92 of 98 delivered</div>
      </div>
    </div>
  </div>

  <!-- Complaints & Feedback -->
  <div class="content-section" id="complaints">
    <h3 class="section-title">Complaints &amp; Feedback</h3>

    <div id="complaintsContainer"></div>
  </div>

  <!-- Security -->
  <div class="content-section" id="security">
    <h3 class="section-title">Security &amp; System Operations</h3>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;">

      <div class="report-card" style="border-left-color:#c0392b;">
        <h5><i class="fas fa-user-shield" style="margin-right:7px;color:#c0392b;"></i>Admin Login Activity</h5>
        <p>Last login: Today, 09:14 AM<br>IP: 192.168.1.10</p>
        <div style="font-size:13px;color:#27ae60;font-weight:600;"><i class="fas fa-check-circle"></i> Secure Session Active</div>
      </div>

      <div class="report-card" style="border-left-color:#1976d2;">
        <h5><i class="fas fa-database" style="margin-right:7px;color:#1976d2;"></i>Database Backup</h5>
        <p>Last backup: Yesterday, 11:59 PM<br>Status: Successful</p>
        <button class="btn-action btn-view" onclick="showToast('Backup initiated successfully!','success')">
          <i class="fas fa-sync-alt"></i> Run Backup Now
        </button>
      </div>

      <div class="report-card" style="border-left-color:#388e3c;">
        <h5><i class="fas fa-server" style="margin-right:7px;color:#388e3c;"></i>System Status</h5>
        <p>All services running normally.<br>Uptime: 99.9%</p>
        <div style="font-size:13px;color:#27ae60;font-weight:600;"><i class="fas fa-circle"></i> All Systems Operational</div>
      </div>

      <div class="report-card" style="border-left-color:#f57c00;">
        <h5><i class="fas fa-exclamation-triangle" style="margin-right:7px;color:#f57c00;"></i>Suspicious Activity</h5>
        <p>Failed login attempts (24h): <strong>3</strong><br>Flagged accounts: <strong>1</strong></p>
        <button class="btn-action btn-block" onclick="showToast('Security review initiated.','danger')">
          <i class="fas fa-search"></i> Review Now
        </button>
      </div>

      <div class="report-card" style="border-left-color:#7b1fa2;">
        <h5><i class="fas fa-key" style="margin-right:7px;color:#7b1fa2;"></i>Access Control</h5>
        <p>Active admin sessions: <strong>1</strong><br>Role permissions: Up to date</p>
        <button class="btn-action btn-view" onclick="showToast('Permissions refreshed.','success')">
          <i class="fas fa-sync"></i> Refresh Permissions
        </button>
      </div>

      <div class="report-card" style="border-left-color:#00796b;">
        <h5><i class="fas fa-file-alt" style="margin-right:7px;color:#00796b;"></i>Audit Logs</h5>
        <p>Total log entries today: <strong>214</strong><br>Last action: Block User</p>
        <button class="btn-action btn-view" onclick="showToast('Audit log downloaded.','success')">
          <i class="fas fa-download"></i> Download Logs
        </button>
      </div>

    </div>
  </div>

</div><!-- /main-content -->

<!-- ===== MODAL ===== -->
<div class="modal-overlay" id="confirmModal">
  <div class="modal-box">
    <h4 id="modalTitle">Confirm Action</h4>
    <p id="modalBody">Are you sure you want to perform this action?</p>
    <div class="modal-actions">
      <button class="btn-cancel-modal" onclick="closeModal()">Cancel</button>
      <button id="modalConfirmBtn" onclick="confirmAction()">Confirm</button>
    </div>
  </div>
</div>

<!-- ===== TOAST ===== -->
<div class="toast-container" id="toastContainer"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
// ===== DATA =====
const usersData = [
  { id:1, name:'Ali Hassan',    email:'ali@gmail.com',      role:'Customer', joined:'Jan 10, 2025', status:'Active'  },
  { id:2, name:'Fatima Noor',   email:'fatima@gmail.com',   role:'Customer', joined:'Jan 12, 2025', status:'Active'  },
  { id:3, name:'Ahmed Tailors', email:'ahmed@tailor.com',   role:'Tailor',   joined:'Dec 5, 2024',  status:'Active'  },
  { id:4, name:'Fashion Studio',email:'fashion@studio.com', role:'Tailor',   joined:'Nov 20, 2024', status:'Active'  },
  { id:5, name:'Usman Khan',    email:'usman@gmail.com',    role:'Customer', joined:'Feb 1, 2025',  status:'Blocked' },
  { id:6, name:'Elite Tailors', email:'elite@tailor.com',   role:'Tailor',   joined:'Oct 15, 2024', status:'Active'  },
  { id:7, name:'Sana Malik',    email:'sana@gmail.com',     role:'Customer', joined:'Mar 5, 2025',  status:'Active'  },
  { id:8, name:'Royal Tailors', email:'royal@tailor.com',   role:'Tailor',   joined:'Sep 10, 2024', status:'Blocked' },
];

const ordersData = [
  { id:'#ORD-001', customer:'Ali Hassan',   tailor:'Ahmed Tailors', item:'3-Piece Suit',    date:'Jan 15, 2026', status:'In Progress' },
  { id:'#ORD-002', customer:'Fatima Noor',  tailor:'Fashion Studio', item:'Bridal Lehenga', date:'Jan 16, 2026', status:'Pending'     },
  { id:'#ORD-003', customer:'Ali Hassan',   tailor:'Elite Tailors',  item:'Waistcoat',      date:'Jan 10, 2026', status:'Completed'   },
  { id:'#ORD-004', customer:'Sana Malik',   tailor:'Royal Tailors',  item:'Shalwar Kameez', date:'Dec 28, 2025', status:'Completed'   },
  { id:'#ORD-005', customer:'Usman Khan',   tailor:'Ahmed Tailors',  item:'Sherwani',       date:'Jan 5, 2026',  status:'Rejected'    },
  { id:'#ORD-006', customer:'Fatima Noor',  tailor:'Elite Tailors',  item:'Casual Shirt',   date:'Jan 18, 2026', status:'In Progress' },
];

const complaintsData = [
  { id:'#CMP-001', user:'Ali Hassan',   subject:'Tailor delayed order by 10 days',        date:'Jan 20, 2026', status:'Open'     },
  { id:'#CMP-002', user:'Sana Malik',   subject:'Payment deducted but order not placed',  date:'Jan 18, 2026', status:'Open'     },
  { id:'#CMP-003', user:'Fatima Noor',  subject:'Wrong fabric used for lehenga',           date:'Jan 15, 2026', status:'Open'     },
  { id:'#CMP-004', user:'Usman Khan',   subject:'Tailor was rude and unprofessional',     date:'Jan 10, 2026', status:'Resolved' },
  { id:'#CMP-005', user:'Ali Hassan',   subject:'Great experience! Quick delivery.',       date:'Jan 8, 2026',  status:'Resolved' },
];

// ===== RENDER USERS =====
function renderUsers(data) {
  const tbody = document.getElementById('usersBody');
  tbody.innerHTML = data.map(u => `
    <tr>
      <td>${u.id}</td>
      <td><strong>${u.name}</strong></td>
      <td>${u.email}</td>
      <td><span class="badge-status ${u.role==='Customer'?'badge-customer':'badge-tailor'}">${u.role}</span></td>
      <td>${u.joined}</td>
      <td><span class="badge-status ${u.status==='Active'?'badge-active':'badge-blocked'}">${u.status}</span></td>
      <td>
        <button class="btn-action btn-view" onclick="showToast('Viewing ${u.name} profile.','')"><i class="fas fa-eye"></i> View</button>
        ${u.status==='Active'
          ? `<button class="btn-action btn-block" onclick="openModal('block',${u.id},'${u.name}')"><i class="fas fa-ban"></i> Block</button>`
          : `<button class="btn-action btn-unblock" onclick="openModal('unblock',${u.id},'${u.name}')"><i class="fas fa-check"></i> Unblock</button>`
        }
      </td>
    </tr>
  `).join('');
}

function filterUsers() {
  const q    = document.getElementById('userSearch').value.toLowerCase();
  const role = document.getElementById('roleFilter').value;
  const stat = document.getElementById('statusFilter').value;
  const filtered = usersData.filter(u =>
    (u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)) &&
    (role ? u.role === role : true) &&
    (stat ? u.status === stat : true)
  );
  renderUsers(filtered);
}

// ===== RENDER ORDERS =====
function renderOrders(data) {
  const statusClass = {
    'Pending':'badge-pending','In Progress':'badge-progress',
    'Completed':'badge-completed','Rejected':'badge-rejected'
  };
  const tbody = document.getElementById('ordersBody');
  tbody.innerHTML = data.map(o => `
    <tr>
      <td><strong>${o.id}</strong></td>
      <td>${o.customer}</td>
      <td>${o.tailor}</td>
      <td>${o.item}</td>
      <td>${o.date}</td>
      <td><span class="badge-status ${statusClass[o.status]||''}">${o.status}</span></td>
      <td>
        <button class="btn-action btn-view" onclick="showToast('Viewing order ${o.id}','')"><i class="fas fa-eye"></i> View</button>
      </td>
    </tr>
  `).join('');
}

function filterOrders() {
  const q    = document.getElementById('orderSearch').value.toLowerCase();
  const stat = document.getElementById('orderStatusFilter').value;
  const filtered = ordersData.filter(o =>
    (o.id.toLowerCase().includes(q) || o.tailor.toLowerCase().includes(q) || o.customer.toLowerCase().includes(q)) &&
    (stat ? o.status === stat : true)
  );
  renderOrders(filtered);
}

// ===== RENDER COMPLAINTS =====
function renderComplaints() {
  const container = document.getElementById('complaintsContainer');
  container.innerHTML = complaintsData.map((c,i) => `
    <div class="complaint-card ${c.status==='Resolved'?'resolved':''}" id="cmp-${i}">
      <div class="complaint-header">
        <div class="complaint-id">${c.id} — ${c.user}</div>
        <span class="badge-status ${c.status==='Open'?'badge-open':'badge-resolved'}">${c.status}</span>
      </div>
      <div class="complaint-body">${c.subject}</div>
      <div class="complaint-meta"><i class="fas fa-calendar-alt" style="margin-right:5px;"></i>${c.date}</div>
      ${c.status==='Open' ? `
        <div class="complaint-actions">
          <button class="btn-action btn-resolve" onclick="resolveComplaint(${i})"><i class="fas fa-check"></i> Mark Resolved</button>
          <button class="btn-action btn-dismiss" onclick="showToast('Complaint dismissed.','')"><i class="fas fa-times"></i> Dismiss</button>
        </div>` : ''}
    </div>
  `).join('');
}

function resolveComplaint(idx) {
  complaintsData[idx].status = 'Resolved';
  renderComplaints();
  showToast('Complaint marked as resolved!', 'success');
}

// ===== MODAL =====
let pendingAction = null;
function openModal(action, userId, userName) {
  pendingAction = { action, userId, userName };
  const modal = document.getElementById('confirmModal');
  const title = document.getElementById('modalTitle');
  const body  = document.getElementById('modalBody');
  const btn   = document.getElementById('modalConfirmBtn');

  if (action === 'block') {
    title.textContent = 'Block User Account';
    body.textContent  = `Are you sure you want to block "${userName}"? They will not be able to login.`;
    btn.className = 'btn-confirm-block';
    btn.textContent = 'Yes, Block';
  } else {
    title.textContent = 'Unblock User Account';
    body.textContent  = `Are you sure you want to unblock "${userName}"? They will regain full access.`;
    btn.className = 'btn-confirm-unblock';
    btn.textContent = 'Yes, Unblock';
  }
  modal.classList.add('show');
}

function closeModal() {
  document.getElementById('confirmModal').classList.remove('show');
  pendingAction = null;
}

function confirmAction() {
  if (!pendingAction) return;
  const { action, userId, userName } = pendingAction;
  const user = usersData.find(u => u.id === userId);
  if (user) user.status = action === 'block' ? 'Blocked' : 'Active';
  closeModal();
  filterUsers();
  showToast(`${userName} has been ${action==='block'?'blocked':'unblocked'} successfully.`, action==='block'?'danger':'success');
}

// ===== TOAST =====
function showToast(msg, type='') {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = `toast-msg ${type}`;
  toast.textContent = msg;
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 3500);
}

// ===== SCROLL SPY =====
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
  sections.forEach(section => {
    if (window.scrollY >= section.offsetTop - 100) current = section.getAttribute('id');
  });
  navLinks.forEach(link => {
    link.classList.remove('active');
    if (link.getAttribute('data-section') === current) link.classList.add('active');
  });
}
window.addEventListener('scroll', updateActiveLink);
updateActiveLink();

// ===== DATE =====
document.getElementById('current-date').textContent = new Date().toLocaleDateString('en-US',{weekday:'short',year:'numeric',month:'short',day:'numeric'});

// ===== INIT =====
renderUsers(usersData);
renderOrders(ordersData);
renderComplaints();
</script>
</body>
</html>