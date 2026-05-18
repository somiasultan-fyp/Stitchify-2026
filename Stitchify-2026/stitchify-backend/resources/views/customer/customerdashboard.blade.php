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
:root{
  --primary-bg:#212529;
  --accent-color:#1b2a4a;
  --copyright-bg:#575a5b;
  --text-white:#ffffff;
}

*{
  margin:0;
  padding:0;
  box-sizing:border-box;
}

html{
  scroll-behavior:smooth;
}

body{
  background:#f5f6fa;
  font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
  min-height:100vh;
}

/* ===== SIDEBAR ===== */

.sidebar{
  position:fixed;
  top:0;
  left:0;
  width:260px;
  height:100vh;
  background:linear-gradient(135deg,var(--accent-color),var(--primary-bg));
  padding:20px 0;
  box-shadow:2px 0 10px rgba(0,0,0,.1);
  overflow-y:auto;
  z-index:1000;
  display:flex;
  flex-direction:column;
}

.sidebar-logo{
  text-align:center;
  padding:0 20px 15px;
  border-bottom:1px solid rgba(255,255,255,.1);
  margin-bottom:15px;
}

.sidebar-logo img{
  width:80px;
  height:50px;
  object-fit:contain;
  margin-bottom:10px;
}

.sidebar-logo h3{
  color:#fff;
  font-size:18px;
  font-weight:600;
  margin:0;
}

.user-info{
  padding:15px;
  background:rgba(255,255,255,.1);
  margin:0 15px 15px;
  border-radius:10px;
}

.user-info h4{
  color:#fff;
  font-size:16px;
  margin:0 0 5px;
}

.user-info p{
  color:rgba(255,255,255,.75);
  font-size:13px;
  margin:0;
}

.sidebar-menu{
  list-style:none;
  padding:0 15px;
  flex:1;
}

.sidebar-menu li{
  margin-bottom:5px;
}

.sidebar-menu a{
  display:flex;
  align-items:center;
  padding:12px 15px;
  color:rgba(255,255,255,.8);
  text-decoration:none;
  border-radius:8px;
  transition:.3s;
  font-size:15px;
  position:relative;
}

.sidebar-menu a::before{
  content:'';
  position:absolute;
  left:0;
  top:50%;
  transform:translateY(-50%);
  width:0;
  height:70%;
  background:#fff;
  border-radius:0 4px 4px 0;
  transition:.3s;
}

.sidebar-menu a.active::before{
  width:4px;
}

.sidebar-menu a:hover,
.sidebar-menu a.active{
  background:rgba(255,255,255,.15);
  color:#fff;
  padding-left:20px;
}

.sidebar-menu a i{
  margin-right:12px;
  width:20px;
  text-align:center;
}

.sidebar-footer{
  padding:15px;
  border-top:1px solid rgba(255,255,255,.1);
}

.logout-link{
  display:flex;
  align-items:center;
  padding:12px 15px;
  background:rgba(220,53,69,.2);
  color:#ff6b6b !important;
  text-decoration:none;
  border-radius:8px;
  transition:.3s;
}

.logout-link:hover{
  background:rgba(220,53,69,.35);
}

.logout-link i{
  margin-right:10px;
}

/* ===== MAIN ===== */

.main-content{
  margin-left:260px;
  padding:20px;
  min-height:100vh;
}

.top-bar{
  background:#fff;
  padding:20px 30px;
  border-radius:15px;
  margin-bottom:25px;
  box-shadow:0 2px 10px rgba(0,0,0,.05);
  display:flex;
  justify-content:space-between;
  align-items:center;
}

.top-bar h2{
  color:var(--accent-color);
  font-size:28px;
  font-weight:700;
  margin:0;
}

/* ===== NOTIFICATION ===== */

.bell-wrapper{
  position:relative;
}

.bell-btn{
  border:none;
  background:none;
  font-size:20px;
  color:var(--accent-color);
  cursor:pointer;
  position:relative;
}

.bell-badge{
  position:absolute;
  top:-5px;
  right:-8px;
  background:#e53935;
  color:#fff;
  width:18px;
  height:18px;
  border-radius:50%;
  font-size:10px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:700;
}

.notif-dropdown{
  display:none;
  position:absolute;
  top:40px;
  right:0;
  width:300px;
  background:#fff;
  border-radius:12px;
  box-shadow:0 8px 25px rgba(0,0,0,.15);
  overflow:hidden;
  z-index:999;
}

.notif-dropdown.show{
  display:block;
}

.notif-header{
  background:var(--accent-color);
  color:#fff;
  padding:12px 15px;
  font-weight:600;
}

.notif-empty{
  padding:20px;
  text-align:center;
  color:#999;
}

/* ===== STATS ===== */

.stats-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:20px;
  margin-bottom:25px;
}

.stat-card{
  background:#fff;
  padding:25px;
  border-radius:15px;
  box-shadow:0 2px 10px rgba(0,0,0,.05);
  border-left:4px solid var(--accent-color);
  transition:.3s;
}

.stat-card:hover{
  transform:translateY(-5px);
}

.stat-icon{
  width:50px;
  height:50px;
  border-radius:12px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:24px;
  margin-bottom:15px;
}

.stat-icon.blue{
  background:#e3f2fd;
  color:#1976d2;
}

.stat-icon.orange{
  background:#fff3e0;
  color:#f57c00;
}

.stat-icon.green{
  background:#e8f5e9;
  color:#388e3c;
}

.stat-icon.purple{
  background:#f3e5f5;
  color:#7b1fa2;
}

.stat-number{
  font-size:32px;
  font-weight:700;
  color:var(--accent-color);
  margin:0;
}

.stat-label{
  color:var(--copyright-bg);
  margin-top:5px;
}

/* ===== CONTENT ===== */

.content-section{
  background:#fff;
  padding:25px;
  border-radius:15px;
  box-shadow:0 2px 10px rgba(0,0,0,.05);
  margin-bottom:25px;
}

.section-title{
  color:var(--accent-color);
  font-size:24px;
  font-weight:600;
  margin-bottom:20px;
  padding-bottom:15px;
  border-bottom:2px solid #f0f0f0;
}

/* ===== ORDERS ===== */

.order-card{
  background:#f8f9fa;
  padding:20px;
  border-radius:12px;
  margin-bottom:15px;
  border-left:4px solid var(--accent-color);
}

.order-header{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:12px;
}

.order-id{
  font-weight:700;
  color:var(--accent-color);
}

.order-status{
  padding:5px 12px;
  border-radius:20px;
  font-size:12px;
  font-weight:600;
}

.status-pending{
  background:#fff3e0;
  color:#f57c00;
}

.status-progress{
  background:#e3f2fd;
  color:#1976d2;
}

.status-completed{
  background:#e8f5e9;
  color:#388e3c;
}

.status-cancelled{
  background:#fce4ec;
  color:#c62828;
}

.order-details{
  color:#575a5b;
  line-height:1.7;
}

.order-details strong{
  color:#212529;
}

.waiting-badge{
  display:inline-block;
  margin-top:8px;
  padding:6px 12px;
  border-radius:20px;
  background:#fff8e1;
  color:#f57c00;
  font-size:12px;
  font-weight:600;
}

.pay-btn{
  margin-top:10px;
  padding:8px 20px;
  border:none;
  border-radius:8px;
  background:#388e3c;
  color:#fff;
  font-weight:600;
}

.pay-btn:hover{
  background:#2e7d32;
}

@media(max-width:768px){

  .sidebar{
    transform:translateX(-100%);
  }

  .main-content{
    margin-left:0;
  }

  .stats-grid{
    grid-template-columns:repeat(2,1fr);
  }

}
</style>
</head>

<body>

{{-- ===== SIDEBAR ===== --}}

<div class="sidebar">

  <div class="sidebar-logo">
    <img src="{{ asset('images/logo.png') }}" alt="Logo">
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
      <a href="#overview" class="active" data-section="overview">
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
    <a href="#"
       class="logout-link"
       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>

</div>

<form id="logout-form" action="/logout" method="POST" style="display:none;">
  @csrf
</form>

{{-- ===== MAIN ===== --}}

<div class="main-content">

  {{-- TOP BAR --}}

  <div class="top-bar" id="overview">

    <h2>Welcome, {{ auth()->user()->name }}!</h2>

    <div class="bell-wrapper">

      <button class="bell-btn" onclick="toggleNotif()">
        <i class="fas fa-bell"></i>
        <span class="bell-badge">0</span>
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

  {{-- STATS --}}

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

  {{-- MY ORDERS --}}

  <div class="content-section" id="my-orders">

    <h3 class="section-title">My Orders</h3>

    @php
      $activeOrders = $orders->whereIn('status',
      ['pending','accepted','in_progress','ready','dispatched']);
    @endphp

    @forelse($activeOrders as $order)

    <div class="order-card">

      <div class="order-header">

        <div class="order-id">
          #{{ $order->order_number }}
        </div>

        <span class="order-status
          @if($order->status === 'pending') status-pending
          @elseif($order->status === 'dispatched') status-completed
          @else status-progress
          @endif">

          {{ ucfirst(str_replace('_',' ',$order->status)) }}

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
          Tailor ke response ka intezaar hai...
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

          <button class="pay-btn">
            <i class="fas fa-credit-card me-1"></i>
            Pay Now
          </button>

          @endif

        @endif

      </div>

    </div>

    @empty

    <div class="text-center py-4">

      <i class="fas fa-shopping-bag fa-3x text-muted mb-3 d-block"></i>

      <p class="text-muted mb-3">
        Koi active order nahi hai.
      </p>

      <a href="/customer/order-form" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>
        Naya Order Place Karo
      </a>

    </div>

    @endforelse

  </div>

  {{-- ORDER HISTORY --}}

  <div class="content-section" id="order-history">

    <h3 class="section-title">Order History</h3>

    @php
      $historyOrders = $orders->whereIn('status',['delivered','cancelled']);
    @endphp

    @forelse($historyOrders as $order)

    <div class="order-card">

      <div class="order-header">

        <div class="order-id">
          #{{ $order->order_number }}
        </div>

        <span class="order-status
        {{ $order->status === 'delivered'
            ? 'status-completed'
            : 'status-cancelled' }}">

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

      </div>

    </div>

    @empty

    <p class="text-muted text-center py-3">
      Abhi koi completed order nahi hai.
    </p>

    @endforelse

  </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

<script>

function toggleNotif(){
  document.getElementById('notifDropdown').classList.toggle('show');
}

document.addEventListener('click',function(e){

  const wrapper=document.querySelector('.bell-wrapper');

  if(!wrapper.contains(e.target)){
    document.getElementById('notifDropdown').classList.remove('show');
  }

});

document.querySelectorAll('.sidebar-menu a[href^="#"]').forEach(anchor=>{

  anchor.addEventListener('click',function(e){

    e.preventDefault();

    const target=document.querySelector(this.getAttribute('href'));

    if(target){
      target.scrollIntoView({
        behavior:'smooth',
        block:'start'
      });
    }

  });

});

const sections=document.querySelectorAll('.content-section,.top-bar');
const navLinks=document.querySelectorAll('.sidebar-menu a[data-section]');

function updateActiveLink(){

  let current='';

  sections.forEach(section=>{

    if(window.scrollY >= section.offsetTop - 100){
      current=section.getAttribute('id');
    }

  });

  navLinks.forEach(link=>{

    link.classList.remove('active');

    if(link.getAttribute('data-section') === current){
      link.classList.add('active');
    }

  });

}

window.addEventListener('scroll',updateActiveLink);

updateActiveLink();

</script>

</body>
</html>