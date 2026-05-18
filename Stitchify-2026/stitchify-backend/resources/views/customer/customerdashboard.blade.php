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

/* ===== MOBILE TOGGLE ===== */

.menu-toggle {
  display: none;
}

/* ===== SIDEBAR ===== */

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
  display: flex;
  flex-direction: column;
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
  flex: 1;
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

/* ===== LOGOUT ===== */

.sidebar-footer {
  padding: 15px;
  border-top: 1px solid rgba(255,255,255,0.1);
}

.logout-link {
  display: flex;
  align-items: center;
  padding: 12px 15px;
  background-color: rgba(220,53,69,0.2);
  color: #ff6b6b !important;
  text-decoration: none;
  border-radius: 8px;
  font-size: 15px;
  transition: all 0.3s ease;
}

.logout-link:hover {
  background-color: rgba(220,53,69,0.35);
}

.logout-link i {
  margin-right: 12px;
}

/* ===== MAIN CONTENT ===== */

.main-content {
  margin-left: 260px;
  padding: 20px;
  min-height: 100vh;
}

/* ===== TOP BAR ===== */

.top-bar {
  background-color: white;
  padding: 20px 30px;
  border-radius: 15px;
  margin-bottom: 25px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.top-bar h2 {
  color: var(--accent-color);
  font-size: 28px;
  font-weight: 700;
  margin: 0;
}

/* ===== NOTIFICATION ===== */

.bell-wrapper {
  position: relative;
}

.bell-btn {
  background: none;
  border: none;
  cursor: pointer;
  color: var(--accent-color);
  font-size: 20px;
  padding: 6px 8px;
  border-radius: 8px;
  transition: all 0.3s ease;
  position: relative;
}

.bell-btn:hover {
  background-color: #f0f0f0;
}

.bell-badge {
  position: absolute;
  top: 2px;
  right: 2px;
  background-color: #e53935;
  color: white;
  font-size: 10px;
  font-weight: 700;
  width: 17px;
  height: 17px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
}

.notif-dropdown {
  display: none;
  position: absolute;
  top: 42px;
  right: 0;
  width: 300px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  z-index: 9999;
  overflow: hidden;
}

.notif-dropdown.show {
  display: block;
}

.notif-header {
  padding: 12px 16px;
  background: var(--accent-color);
  color: white;
  font-weight: 600;
  font-size: 14px;
}

.notif-item {
  padding: 12px 16px;
  border-bottom: 1px solid #f0f0f0;
  font-size: 13px;
}

.notif-empty {
  padding: 20px;
  text-align: center;
  color: #aaa;
  font-size: 13px;
}

/* ===== STATS ===== */

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4,1fr);
  gap: 20px;
  margin-bottom: 25px;
}

.stat-card {
  background: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  border-left: 4px solid var(--accent-color);
  transition: transform 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-5px);
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
}

.stat-label {
  color: var(--copyright-bg);
  font-size: 14px;
}

/* ===== CONTENT ===== */

.content-section {
  background-color: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  margin-bottom: 25px;
}

.section-title {
  color: var(--accent-color);
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 20px;
  border-bottom: 2px solid #f0f0f0;
  padding-bottom: 15px;
}

/* ===== ORDER CARD ===== */

.order-card {
  background-color: #f8f9fa;
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 15px;
  border-left: 4px solid var(--accent-color);
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.order-id {
  font-weight: 700;
  color: var(--accent-color);
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

.status-cancelled {
  background-color: #fce4ec;
  color: #c62828;
}

.order-details {
  color: var(--copyright-bg);
  font-size: 14px;
  line-height: 1.7;
}

.order-details strong {
  color: var(--primary-bg);
}

.waiting-badge {
  display: inline-block;
  margin-top: 6px;
  padding: 5px 12px;
  background-color: #fff8e1;
  color: #f57c00;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
}

.pay-btn {
  margin-top: 10px;
  padding: 8px 20px;
  background-color: #388e3c;
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
}

/* ===== RESPONSIVE ===== */

@media (max-width: 768px) {

  .sidebar {
    transform: translateX(-100%);
    transition: transform 0.3s ease;
  }

  .sidebar.active {
    transform: translateX(0);
  }

  .main-content {
    margin-left: 0;
  }

  .menu-toggle {
    display: block;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1001;
    background: var(--accent-color);
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 8px;
    font-size: 18px;
    cursor: pointer;
  }

  .stats-grid {
    grid-template-columns: repeat(2,1fr);
  }
}
</style>
</head>

<body>

<button class="menu-toggle" onclick="toggleSidebar()">
  <i class="fas fa-bars"></i>
</button>

<!-- SIDEBAR -->

<div class="sidebar" id="sidebar">

  <div class="sidebar-logo">
    <img src="{{ asset('images/logo.png') }}"
         alt="Stitchify Logo"
         onerror="this.src='https://via.placeholder.com/80x50?text=S'">
    <h3>Stitchify</h3>
  </div>

  <div class="user-info">
    <h4>{{ auth()->user()->name }}</h4>
    <p>
      <i class="fas fa-circle text-success me-1" style="font-size:8px;"></i>
      Online
    </p>
  </div>

  <ul class="sidebar-menu">

    <li>
      <a href="#overview" data-section="overview" class="active">
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

  <div class="sidebar-footer">
    <a class="logout-link"
       href="#"
       onclick="event.preventDefault();
       document.getElementById('logout-form').submit();">

      <i class="fas fa-sign-out-alt"></i>
      Logout
    </a>
  </div>

</div>

<form id="logout-form" action="/logout" method="POST" style="display:none">
  @csrf
</form>

<!-- MAIN CONTENT -->

<div class="main-content">

  <!-- TOP BAR -->

  <div class="top-bar" id="overview">

    <h2>
      Welcome, {{ auth()->user()->name }}!
    </h2>

    <div class="bell-wrapper">

      <button class="bell-btn" onclick="toggleNotif()">
        <i class="fas fa-bell"></i>
        <span class="bell-badge" id="bellBadge">0</span>
      </button>

      <div class="notif-dropdown" id="notifDropdown">

        <div class="notif-header">
          Notifications
        </div>

        <div class="notif-empty">
          Koi nayi notification nahi
        </div>

      </div>

    </div>

  </div>

  <!-- STATS -->

  <div class="stats-grid">

    <div class="stat-card">
      <div class="stat-icon blue">
        <i class="fas fa-shopping-bag"></i>
      </div>

      <h3 class="stat-number">
        {{ $orders->whereIn('status',['accepted','in_progress','ready','dispatched'])->count() }}
      </h3>

      <p class="stat-label">Active Orders</p>
    </div>

    <div class="stat-card">
      <div class="stat-icon orange">
        <i class="fas fa-clock"></i>
      </div>

      <h3 class="stat-number">
        {{ $orders->where('status','pending')->count() }}
      </h3>

      <p class="stat-label">Pending Orders</p>
    </div>

    <div class="stat-card">
      <div class="stat-icon green">
        <i class="fas fa-check-circle"></i>
      </div>

      <h3 class="stat-number">
        {{ $orders->where('status','delivered')->count() }}
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

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

<script>

function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('active');
}

document.addEventListener('click', function(e) {

  const sidebar = document.getElementById('sidebar');
  const toggle = document.querySelector('.menu-toggle');

  if (
    window.innerWidth <= 768 &&
    !sidebar.contains(e.target) &&
    !toggle.contains(e.target) &&
    sidebar.classList.contains('active')
  ) {
    sidebar.classList.remove('active');
  }
});

function toggleNotif() {
  document.getElementById('notifDropdown').classList.toggle('show');
}

document.addEventListener('click', function(e) {

  const wrapper = document.querySelector('.bell-wrapper');

  if (!wrapper.contains(e.target)) {
    document.getElementById('notifDropdown').classList.remove('show');
  }
});

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

</script>

</body>
</html>