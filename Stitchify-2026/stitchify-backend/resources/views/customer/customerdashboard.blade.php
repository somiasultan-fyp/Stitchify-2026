<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Customer Dashboard – Stitchify</title>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    :root {
      --navy:      #1b2a4a;
      --navy-dark: #141f38;
      --white:     #ffffff;
      --bg:        #f0f2f8;
      --muted:     #6c757d;
      --border:    #e4e8f0;
      --blue:      #1976d2;
      --green:     #2e7d32;
      --amber:     #f57c00;
      --purple:    #6a1b9a;
      --red:       #c62828;
      --cyan:      #00838f;
      --sidebar-w: 220px;
    }
    *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
    html { scroll-behavior:smooth; }
    body {
      font-family:'Segoe UI', system-ui, sans-serif;
      background:var(--bg); min-height:100vh; color:#2c3e50;
    }

    /* ─── SIDEBAR ─────────────────────────────── */
    .sidebar {
      position:fixed; inset:0 auto 0 0; width:var(--sidebar-w);
      background:linear-gradient(180deg, var(--navy) 0%, var(--navy-dark) 100%);
      display:flex; flex-direction:column;
      z-index:1000; overflow-y:auto;
      transition:transform .3s ease;
    }
    .sb-logo {
      display:flex; flex-direction:column; align-items:center;
      padding:24px 16px 18px;
      border-bottom:1px solid rgba(255,255,255,.08);
    }
    .sb-logo-img {
      width:56px; height:56px; border-radius:50%;
      background:rgba(255,255,255,.12);
      display:flex; align-items:center; justify-content:center;
      overflow:hidden; margin-bottom:10px;
    }
    .sb-logo-img img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
    .sb-logo-img i   { font-size:24px; color:rgba(255,255,255,.8); }
    .sb-logo-name    { color:#fff; font-size:17px; font-weight:700; letter-spacing:.5px; }

    .sb-user {
      margin:14px 12px 6px;
      background:rgba(255,255,255,.09);
      border-radius:10px; padding:11px 14px;
    }
    .sb-user-name { color:#fff; font-size:14px; font-weight:600; margin-bottom:2px; }
    .sb-user-role { color:rgba(255,255,255,.55); font-size:12px; }

    .sb-nav { list-style:none; padding:8px 10px; flex:1; }
    .sb-nav li { margin-bottom:2px; }
    .sb-nav a {
      display:flex; align-items:center; gap:11px;
      padding:11px 13px; border-radius:8px;
      color:rgba(255,255,255,.72); text-decoration:none;
      font-size:14px; font-weight:500; transition:all .22s ease;
    }
    .sb-nav a i { width:18px; text-align:center; font-size:14px; }
    .sb-nav a:hover { background:rgba(255,255,255,.11); color:#fff; }
    .sb-nav a.active {
      background:rgba(255,255,255,.15); color:#fff;
      border-left:3px solid #fff; padding-left:10px;
    }

    .sb-logout { padding:10px 10px 14px; border-top:1px solid rgba(255,255,255,.07); }
    .sb-logout form button {
      width:100%; display:flex; align-items:center; gap:11px;
      background:rgba(220,53,69,.18); color:#ff7070;
      border:none; padding:10px 13px; border-radius:8px;
      font-size:14px; cursor:pointer; transition:background .22s ease;
    }
    .sb-logout form button:hover { background:rgba(220,53,69,.30); }
    .sb-logout form button i { width:18px; text-align:center; }

    /* ─── MAIN ────────────────────────────────── */
    .main { margin-left:var(--sidebar-w); padding:22px 24px; min-height:100vh; }

    /* Welcome bar */
    .welcome-bar {
      background:#fff; border-radius:14px; padding:18px 24px;
      margin-bottom:20px;
      display:flex; align-items:center; justify-content:space-between;
      box-shadow:0 2px 10px rgba(27,42,74,.06);
    }
    .welcome-bar h2 { color:var(--navy); font-size:22px; font-weight:700; }

    /* Bell */
    .notif-wrap { position:relative; }
    .notif-bell-btn {
      background:none; border:none; cursor:pointer;
      font-size:20px; color:var(--navy); padding:6px 8px;
      border-radius:8px; position:relative; transition:background .2s;
    }
    .notif-bell-btn:hover { background:var(--bg); }
    .notif-dot {
      position:absolute; top:4px; right:4px;
      width:9px; height:9px; border-radius:50%;
      background:#e53935; border:2px solid #fff;
    }

    /* Notif dropdown */
    .notif-panel {
      display:none; position:absolute; right:0; top:calc(100% + 8px);
      width:320px; background:#fff; border-radius:14px;
      box-shadow:0 8px 32px rgba(27,42,74,.14); z-index:2000; overflow:hidden;
    }
    .notif-panel.open { display:block; }
    .notif-panel-head {
      padding:14px 18px; font-weight:700; font-size:14px; color:var(--navy);
      display:flex; align-items:center; justify-content:space-between;
      border-bottom:1px solid var(--border);
    }
    .mark-all-btn {
      font-size:12px; color:var(--blue); cursor:pointer;
      font-weight:500; background:none; border:none; padding:0;
    }
    .notif-list { max-height:320px; overflow-y:auto; }
    .notif-item {
      padding:13px 18px; border-bottom:1px solid #f5f6fa;
      transition:background .18s;
    }
    .notif-item.unread { background:#f0f4ff; }
    .notif-item-msg { font-size:13px; color:#333; line-height:1.45; margin-bottom:4px; }
    .notif-item-time { font-size:11px; color:#aaa; }
    .n-dot {
      display:inline-block; width:7px; height:7px; border-radius:50%;
      background:var(--blue); margin-right:6px; vertical-align:middle;
    }
    .notif-empty { padding:30px 18px; text-align:center; color:#bbb; font-size:13px; }
    .notif-empty i { font-size:32px; display:block; margin-bottom:8px; color:#ddd; }

    /* ─── STATS (always 4-col, never wrap) ─────── */
    .stats-row {
      display:grid;
      grid-template-columns:repeat(4,1fr);
      gap:16px; margin-bottom:20px;
    }
    .stat-card {
      background:#fff; border-radius:14px; padding:20px 18px;
      box-shadow:0 2px 10px rgba(27,42,74,.06);
      border-top:4px solid var(--c);
      transition:transform .22s, box-shadow .22s;
    }
    .stat-card:hover { transform:translateY(-3px); box-shadow:0 6px 20px rgba(27,42,74,.10); }
    .stat-icon {
      width:42px; height:42px; border-radius:10px;
      display:flex; align-items:center; justify-content:center;
      font-size:18px; margin-bottom:12px;
    }
    .stat-num { font-size:28px; font-weight:800; color:var(--navy); margin-bottom:3px; }
    .stat-lbl { font-size:12.5px; color:var(--muted); font-weight:500; }

    /* ─── SECTION CARD ────────────────────────── */
    .section-card {
      background:#fff; border-radius:14px; padding:22px 24px;
      box-shadow:0 2px 10px rgba(27,42,74,.06);
      margin-bottom:20px; scroll-margin-top:20px;
    }
    .section-head {
      display:flex; align-items:center; justify-content:space-between;
      margin-bottom:18px; padding-bottom:14px; border-bottom:2px solid var(--border);
    }
    .section-title { font-size:18px; font-weight:700; color:var(--navy); }

    /* ─── ORDER CARD ──────────────────────────── */
    .order-card {
      border:1.5px solid var(--border);
      border-left:4px solid var(--navy);
      border-radius:12px; padding:16px 18px;
      margin-bottom:14px; background:#fafbff;
      transition:box-shadow .2s, border-left-color .3s;
    }
    .order-card:hover { box-shadow:0 4px 16px rgba(27,42,74,.08); }
    .order-card.s-pending           { border-left-color:var(--amber); }
    .order-card.s-accepted          { border-left-color:var(--blue); }
    .order-card.s-payment-confirmed { border-left-color:var(--green); }
    .order-card.s-cutting           { border-left-color:#e91e63; }
    .order-card.s-stitching         { border-left-color:var(--purple); }
    .order-card.s-ready             { border-left-color:var(--green); }
    .order-card.s-dispatched        { border-left-color:var(--cyan); }
    .order-card.s-delivered         { border-left-color:#388e3c; }
    .order-card.s-rejected          { border-left-color:var(--red); }
    .order-card.s-cancelled         { border-left-color:#9e9e9e; }

    .order-top {
      display:flex; align-items:center; justify-content:space-between;
      flex-wrap:wrap; gap:8px; margin-bottom:12px;
    }
    .order-id { font-weight:700; font-size:15px; color:var(--navy); }
    .order-right { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }

    /* Status pills */
    .s-pill { padding:4px 12px; border-radius:20px; font-size:12px; font-weight:600; white-space:nowrap; }
    .pill-pending           { background:#fff3e0; color:var(--amber); }
    .pill-accepted          { background:#e3f2fd; color:var(--blue); }
    .pill-payment-confirmed { background:#e8f5e9; color:var(--green); }
    .pill-cutting           { background:#fce4ec; color:#c2185b; }
    .pill-stitching         { background:#f3e5f5; color:#7b1fa2; }
    .pill-ready             { background:#d4edda; color:#155724; }
    .pill-dispatched        { background:#e0f7fa; color:#006064; }
    .pill-delivered         { background:#d1ecf1; color:#0c5460; }
    .pill-rejected          { background:#fde8e8; color:var(--red); }
    .pill-cancelled         { background:#e9ecef; color:#495057; }

    /* Wait spinner pill */
    .wait-pill {
      display:inline-flex; align-items:center; gap:6px;
      background:#fff3e0; color:var(--amber);
      border:1px solid #ffe0b2; border-radius:20px;
      padding:5px 13px; font-size:12px; font-weight:600;
    }
    .spin { animation:spin 1.6s linear infinite; }
    @keyframes spin { to{transform:rotate(360deg);} }

    /* Pay button */
    .pay-btn {
      display:inline-flex; align-items:center; gap:6px;
      background:linear-gradient(135deg,#1976d2,#1565c0);
      color:#fff; border:none; border-radius:20px;
      padding:6px 16px; font-size:12px; font-weight:700;
      cursor:pointer; text-decoration:none;
      box-shadow:0 2px 8px rgba(25,118,210,.30);
      transition:all .22s ease;
    }
    .pay-btn:hover {
      background:linear-gradient(135deg,#1565c0,#0d47a1);
      transform:translateY(-1px); color:#fff;
      box-shadow:0 4px 14px rgba(25,118,210,.40);
    }

    /* Track button */
    .track-btn {
      display:inline-flex; align-items:center; gap:5px;
      background:linear-gradient(135deg,#00838f,#006064);
      color:#fff; border:none; border-radius:20px;
      padding:6px 14px; font-size:12px; font-weight:600;
      cursor:pointer; text-decoration:none; transition:opacity .2s;
    }
    .track-btn:hover { opacity:.88; color:#fff; }

    .order-meta { font-size:13.5px; color:#555; line-height:1.75; }
    .order-meta strong { color:#2c3e50; }
    .price-val { font-weight:700; color:var(--navy); font-size:14px; }

    /* Progress steps */
    .prog-wrap { display:flex; align-items:flex-start; margin:14px 0 6px; overflow-x:auto; }
    .p-step  { display:flex; flex-direction:column; align-items:center; flex-shrink:0; }
    .p-dot {
      width:26px; height:26px; border-radius:50%;
      display:flex; align-items:center; justify-content:center;
      font-size:10px; font-weight:700;
      border:2px solid var(--border); background:#f0f2f8; color:#aaa;
      transition:all .3s;
    }
    .p-dot.done   { background:var(--green); border-color:var(--green); color:#fff; }
    .p-dot.active { background:var(--blue);  border-color:var(--blue);  color:#fff;
                    box-shadow:0 0 0 4px rgba(25,118,210,.15); }
    .p-lbl {
      font-size:9.5px; color:#bbb; margin-top:4px; white-space:nowrap; text-align:center;
    }
    .p-lbl.done   { color:var(--green); }
    .p-lbl.active { color:var(--blue); font-weight:600; }
    .p-line {
      height:2px; width:28px; background:var(--border); flex-shrink:0;
      margin:13px 2px 0; transition:background .3s;
    }
    .p-line.done { background:var(--green); }

    .order-actions-row { display:flex; gap:8px; margin-top:14px; flex-wrap:wrap; }

    /* Empty state */
    .empty-state { text-align:center; padding:36px 20px; color:#bbb; }
    .empty-state i { font-size:44px; display:block; margin-bottom:10px; }
    .empty-state p { font-size:13px; color:#aaa; margin-bottom:14px; }

    /* Toast */
    .toast-box { position:fixed; top:18px; right:18px; z-index:9999; min-width:280px; max-width:380px; }

    /* Mobile toggle */
    .mob-toggle {
      display:none; position:fixed; top:12px; left:12px; z-index:1001;
      background:var(--navy); color:#fff; border:none;
      padding:10px 14px; border-radius:8px; font-size:16px; cursor:pointer;
    }

    /* ─── RESPONSIVE ──────────────────────────── */
    @media(max-width:1100px) { .stats-row { grid-template-columns:repeat(2,1fr); } }
    @media(max-width:768px) {
      .sidebar { transform:translateX(-100%); }
      .sidebar.open { transform:translateX(0); }
      .main { margin-left:0; padding:14px; }
      .mob-toggle { display:block; }
      .welcome-bar { padding:14px 16px; }
      .welcome-bar h2 { font-size:17px; }
      .stats-row { grid-template-columns:repeat(2,1fr); gap:12px; }
      .section-card { padding:16px; }
    }
  </style>
</head>
<body>

<button class="mob-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>

@if(session('success'))
<div class="toast-box">
  <div class="alert alert-success alert-dismissible fade show mb-0">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
@endif
@if(session('error'))
<div class="toast-box">
  <div class="alert alert-danger alert-dismissible fade show mb-0">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
@endif

{{-- ═══════════ SIDEBAR ═══════════ --}}
<aside class="sidebar" id="sidebar">
  <div class="sb-logo">
    <div class="sb-logo-img">
      <img src="{{ asset('images/logo.png') }}" alt="Stitchify"
           onerror="this.style.display='none';this.parentElement.innerHTML='<i class=\'fas fa-cut\'></i>'">
    </div>
    <div class="sb-logo-name">Stitchify</div>
  </div>

  <div class="sb-user">
    <div class="sb-user-name">{{ auth()->user()->name ?? 'Customer' }}</div>
    <div class="sb-user-role">Customer</div>
  </div>

  <ul class="sb-nav">
    <li><a href="#overview"     data-sec="overview"     class="active"><i class="fas fa-th-large"></i> Dashboard</a></li>
    <li><a href="#my-orders"    data-sec="my-orders"            ><i class="fas fa-shopping-bag"></i> My Orders</a></li>
    <li><a href="#order-history" data-sec="order-history"       ><i class="fas fa-history"></i> Order History</a></li>
    <li><a href="{{ route('customer.orders.create') }}"         ><i class="fas fa-plus-circle"></i> New Order</a></li>
  </ul>

  <div class="sb-logout">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </form>
  </div>
</aside>

{{-- ═══════════ MAIN ═══════════ --}}
<main class="main">

  {{-- Welcome + Bell --}}
  <div class="welcome-bar" id="overview">
    <h2>Welcome, {{ auth()->user()->name ?? 'Customer' }}! 👋</h2>

    <div class="notif-wrap">
      <button class="notif-bell-btn" id="bellBtn" onclick="toggleNotif(event)">
        <i class="fas fa-bell"></i>
        @php $unreadCount = isset($notifications) ? $notifications->where('is_read',false)->count() : 0; @endphp
        @if($unreadCount > 0)<span class="notif-dot" id="bellDot"></span>@endif
      </button>

      <div class="notif-panel" id="notifPanel">
        <div class="notif-panel-head">
          <span><i class="fas fa-bell me-2" style="color:var(--blue)"></i>Notifications</span>
          @if($unreadCount > 0)
            <button class="mark-all-btn" onclick="markAllRead()">Mark all read</button>
          @endif
        </div>
        <div class="notif-list" id="notifList">
          @if(isset($notifications) && $notifications->count() > 0)
            @foreach($notifications->take(10) as $notif)
            <div class="notif-item {{ $notif->is_read ? '' : 'unread' }}" id="notif-{{ $notif->id }}">
              <div class="notif-item-msg">
                @if(!$notif->is_read)<span class="n-dot"></span>@endif
                {{ $notif->message }}
              </div>
              <div class="notif-item-time"><i class="fas fa-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}</div>
            </div>
            @endforeach
          @else
            <div class="notif-empty" id="notifEmpty">
              <i class="fas fa-check-circle"></i>No new notifications
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- ═══ STATS — 4 columns, always one row ═══ --}}
  <div class="stats-row">
    <div class="stat-card" style="--c:var(--blue)">
      <div class="stat-icon" style="background:rgba(25,118,210,.12);color:var(--blue)"><i class="fas fa-shopping-bag"></i></div>
      <div class="stat-num">{{ $stats['active_orders'] ?? 0 }}</div>
      <div class="stat-lbl">Active Orders</div>
    </div>
    <div class="stat-card" style="--c:var(--amber)">
      <div class="stat-icon" style="background:rgba(245,124,0,.12);color:var(--amber)"><i class="fas fa-clock"></i></div>
      <div class="stat-num">{{ $stats['pending'] ?? 0 }}</div>
      <div class="stat-lbl">Pending Orders</div>
    </div>
    <div class="stat-card" style="--c:var(--green)">
      <div class="stat-icon" style="background:rgba(46,125,50,.12);color:var(--green)"><i class="fas fa-check-circle"></i></div>
      <div class="stat-num">{{ $stats['completed'] ?? 0 }}</div>
      <div class="stat-lbl">Completed Orders</div>
    </div>
    <div class="stat-card" style="--c:var(--navy)">
      <div class="stat-icon" style="background:rgba(27,42,74,.12);color:var(--navy)"><i class="fas fa-bookmark"></i></div>
      <div class="stat-num">{{ $stats['total_orders'] ?? 0 }}</div>
      <div class="stat-lbl">Total Orders</div>
    </div>
  </div>

  {{-- ═══ MY ORDERS ═══ --}}
  <div class="section-card" id="my-orders">
    <div class="section-head">
      <div class="section-title">My Orders</div>
      <a href="{{ route('customer.orders.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i>New Order
      </a>
    </div>

    @php
      $activeOrders = isset($orders)
        ? $orders->filter(fn($o) => !in_array($o->status, ['delivered','cancelled','rejected']))
        : collect();

      $stepKeys = ['pending','accepted','payment_confirmed','cutting','stitching','ready','dispatched','delivered'];
      $stepLabels = [
        'pending'=>'Pending','accepted'=>'Accepted','payment_confirmed'=>'Paid',
        'cutting'=>'Cutting','stitching'=>'Stitching','ready'=>'Ready',
        'dispatched'=>'Shipped','delivered'=>'Delivered'
      ];
      $stepIcons = [
        'pending'=>'fa-clock','accepted'=>'fa-thumbs-up','payment_confirmed'=>'fa-credit-card',
        'cutting'=>'fa-cut','stitching'=>'fa-tshirt','ready'=>'fa-box',
        'dispatched'=>'fa-truck','delivered'=>'fa-home'
      ];
    @endphp

    @if($activeOrders->count() > 0)
      @foreach($activeOrders as $order)
      @php
        $st     = $order->status;
        $stSlug = str_replace('_','-',$st);
        $isPaid = ($order->payment_status ?? '') === 'paid';
        $curIdx = array_search($st, $stepKeys);
      @endphp
      <div class="order-card s-{{ $stSlug }}" id="oc-{{ $order->id }}">

        {{-- Top --}}
        <div class="order-top">
          <div class="order-id">#{{ $order->order_number }}</div>
          <div class="order-right" id="or-{{ $order->id }}">

            {{-- Status pill --}}
            <span class="s-pill pill-{{ $stSlug }}" id="spill-{{ $order->id }}">
              {{ ucfirst(str_replace('_',' ',$st)) }}
            </span>

            {{-- Action based on status --}}
            @if($st === 'pending')
              <span class="wait-pill" id="waitpill-{{ $order->id }}">
                <i class="fas fa-spinner spin"></i>Waiting for tailor's response...
              </span>
            @elseif($st === 'accepted' && !$isPaid)
              <a href="{{ route('customer.payment.show', $order->id) }}"
                 class="pay-btn" id="paybtn-{{ $order->id }}">
                <i class="fas fa-credit-card"></i>Pay Rs.{{ number_format($order->price ?? 0) }}
              </a>
            @elseif(in_array($st, ['dispatched','ready']) && ($order->tracking_id ?? false))
              <a href="{{ route('customer.orders.track', $order->id) }}" class="track-btn">
                <i class="fas fa-map-marker-alt"></i>Track
              </a>
            @endif

          </div>
        </div>

        {{-- Meta --}}
        <div class="order-meta">
          <p><strong>Tailor:</strong> {{ $order->tailor->user->name ?? 'N/A' }}</p>
          <p><strong>Item:</strong> {{ $order->dress_type ?? '—' }}</p>
          @if($order->fabric_details)
            <p><strong>Fabric:</strong> {{ $order->fabric_details }}</p>
          @endif
          <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
          @if($order->price)
            <p><strong>Price:</strong> <span class="price-val">Rs.{{ number_format($order->price) }}</span></p>
          @endif
        </div>

        {{-- Progress bar --}}
        @if(!in_array($st, ['rejected','cancelled']))
        <div class="prog-wrap" id="psteps-{{ $order->id }}">
          @foreach($stepKeys as $i => $key)
            @php
              $done = $curIdx !== false && $i < $curIdx;
              $act  = $curIdx !== false && $i === $curIdx;
            @endphp
            <div class="p-step">
              <div class="p-dot {{ $done ? 'done' : ($act ? 'active' : '') }}">
                @if($done)
                  <i class="fas fa-check"></i>
                @else
                  <i class="fas {{ $stepIcons[$key] }}"></i>
                @endif
              </div>
              <div class="p-lbl {{ $done ? 'done' : ($act ? 'active' : '') }}">{{ $stepLabels[$key] }}</div>
            </div>
            @if($i < count($stepKeys)-1)
              <div class="p-line {{ $done ? 'done' : '' }}"></div>
            @endif
          @endforeach
        </div>
        @endif

        {{-- Actions --}}
        <div class="order-actions-row">
          <a href="{{ route('customer.orders.show', $order->id) }}"
             class="btn btn-outline-primary btn-sm">
            View Details <i class="fas fa-arrow-right ms-1"></i>
          </a>
          @if($st === 'pending')
            <button class="btn btn-outline-danger btn-sm"
                    onclick="cancelOrder({{ $order->id }}, '{{ $order->order_number }}')">
              <i class="fas fa-times me-1"></i>Cancel
            </button>
          @endif
        </div>
      </div>
      @endforeach

    @else
      <div class="empty-state">
        <i class="fas fa-shopping-bag" style="color:#ddd"></i>
        <p>No active orders yet.</p>
        <a href="{{ route('customer.orders.create') }}" class="btn btn-primary btn-sm">
          <i class="fas fa-plus me-1"></i>Place Your First Order
        </a>
      </div>
    @endif
  </div>

  {{-- ═══ ORDER HISTORY ═══ --}}
  <div class="section-card" id="order-history">
    <div class="section-head">
      <div class="section-title">Order History</div>
    </div>

    @php
      $histOrders = isset($orders)
        ? $orders->filter(fn($o) => in_array($o->status, ['delivered','cancelled','rejected']))
        : collect();
    @endphp

    @forelse($histOrders as $order)
    <div class="order-card" style="border-left-color:#9e9e9e">
      <div class="order-top">
        <div class="order-id">#{{ $order->order_number }}</div>
        <span class="s-pill pill-{{ str_replace('_','-',$order->status) }}">
          {{ ucfirst(str_replace('_',' ',$order->status)) }}
        </span>
      </div>
      <div class="order-meta">
        <p><strong>Tailor:</strong> {{ $order->tailor->user->name ?? 'N/A' }}</p>
        <p><strong>Item:</strong> {{ $order->dress_type ?? '—' }}</p>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
        @if($order->status === 'delivered' && ($order->actual_delivery_date ?? false))
          <p><strong>Delivered:</strong> {{ $order->actual_delivery_date->format('M d, Y') }}</p>
        @endif
      </div>
      <div class="order-actions-row">
        <a href="{{ route('customer.orders.show', $order->id) }}"
           class="btn btn-outline-secondary btn-sm">View Details</a>
        @if($order->status === 'delivered')
          <a href="{{ route('customer.orders.review', $order->id) }}"
             class="btn btn-outline-success btn-sm">
            <i class="fas fa-star me-1"></i>Rate Tailor
          </a>
        @endif
      </div>
    </div>
    @empty
    <div class="empty-state">
      <i class="fas fa-history" style="color:#ddd"></i>
      <p>No completed orders yet.</p>
    </div>
    @endforelse
  </div>

</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

/* ── Sidebar ──────────────────────────────────────── */
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
}
document.addEventListener('click', e => {
  const sb = document.getElementById('sidebar');
  const tog = document.querySelector('.mob-toggle');
  if (window.innerWidth <= 768 && sb.classList.contains('open')
      && !sb.contains(e.target) && tog && !tog.contains(e.target))
    sb.classList.remove('open');
});

/* Smooth scroll */
document.querySelectorAll('.sb-nav a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    e.preventDefault();
    const t = document.querySelector(a.getAttribute('href'));
    if (t) t.scrollIntoView({ behavior:'smooth', block:'start' });
    if (window.innerWidth <= 768)
      document.getElementById('sidebar').classList.remove('open');
  });
});

/* Scroll spy */
const secs = ['overview','my-orders','order-history'];
const navAs = document.querySelectorAll('.sb-nav a[data-sec]');
function spy() {
  let cur = '';
  secs.forEach(id => {
    const el = document.getElementById(id);
    if (el && window.scrollY >= el.offsetTop - 130) cur = id;
  });
  navAs.forEach(a => a.classList.toggle('active', a.dataset.sec === cur));
}
window.addEventListener('scroll', spy);
spy();

/* ── Notification panel ───────────────────────────── */
function toggleNotif(e) {
  e.stopPropagation();
  document.getElementById('notifPanel').classList.toggle('open');
}
document.addEventListener('click', e => {
  const p = document.getElementById('notifPanel');
  const b = document.getElementById('bellBtn');
  if (p && b && !p.contains(e.target) && !b.contains(e.target))
    p.classList.remove('open');
});

function markAllRead() {
  fetch('{{ route("customer.notifications.markAllRead") }}', {
    method:'POST',
    headers:{ 'X-CSRF-TOKEN':CSRF, 'Content-Type':'application/json' }
  })
  .then(r => r.json())
  .then(d => {
    if (!d.success) return;
    document.querySelectorAll('.notif-item.unread').forEach(el => {
      el.classList.remove('unread');
      const dot = el.querySelector('.n-dot');
      if (dot) dot.remove();
    });
    const bellDot = document.getElementById('bellDot');
    if (bellDot) bellDot.remove();
    const btn = document.querySelector('.mark-all-btn');
    if (btn) btn.remove();
  })
  .catch(() => {});
}

/* ── Cancel order ─────────────────────────────────── */
function cancelOrder(id, num) {
  if (!confirm(`Cancel order #${num}?\nThis cannot be undone.`)) return;
  const reason = prompt('Reason for cancellation (optional):') || '';
  fetch(`/customer/orders/${id}/cancel`, {
    method:'PATCH',
    headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF },
    body:JSON.stringify({ cancel_reason:reason })
  })
  .then(r => r.json())
  .then(d => {
    if (d.success) { showToast('Order cancelled.','success'); setTimeout(()=>location.reload(),1200); }
    else showToast(d.message || 'Could not cancel.','danger');
  })
  .catch(() => showToast('Network error.','danger'));
}

/* ── Toast ────────────────────────────────────────── */
function showToast(msg, type='success') {
  document.querySelectorAll('.toast-box.dyn').forEach(x=>x.remove());
  const d = document.createElement('div');
  d.className = 'toast-box dyn';
  d.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show mb-0">
    ${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
  document.body.appendChild(d);
  setTimeout(() => { try{new bootstrap.Alert(d.querySelector('.alert')).close();}catch(e){d.remove();} }, 4500);
}
document.addEventListener('DOMContentLoaded', () => {
  setTimeout(() => {
    document.querySelectorAll('.toast-box:not(.dyn) .alert').forEach(el => {
      try{new bootstrap.Alert(el).close();}catch(e){el.closest('.toast-box')?.remove();}
    });
  }, 5000);
});

/* ── Live polling every 30s ───────────────────────── */
function pollAll() {

  /* 1) Order status */
  @if(Route::has('customer.orders.live'))
  fetch('{{ route("customer.orders.live") }}')
    .then(r => r.json())
    .then(data => {
      if (!data.success || !data.orders) return;
      data.orders.forEach(order => {
        const slug = order.status.replace(/_/g,'-');

        /* Status pill */
        const pill = document.getElementById(`spill-${order.id}`);
        if (pill) {
          pill.textContent = (order.status_label || order.status).replace(/_/g,' ');
          pill.className   = `s-pill pill-${slug}`;
        }

        /* Order card border */
        const oc = document.getElementById(`oc-${order.id}`);
        if (oc) oc.className = `order-card s-${slug}`;

        /* Swap wait-pill → pay button when accepted */
        if (order.status === 'accepted' && order.payment_status !== 'paid') {
          const wp = document.getElementById(`waitpill-${order.id}`);
          if (wp) wp.style.display = 'none';
          if (!document.getElementById(`paybtn-${order.id}`)) {
            const right = document.getElementById(`or-${order.id}`);
            if (right) {
              const a = document.createElement('a');
              a.id        = `paybtn-${order.id}`;
              a.href      = `/customer/payment/${order.id}`;
              a.className = 'pay-btn';
              a.innerHTML = `<i class="fas fa-credit-card"></i> Pay Rs.${Number(order.price||0).toLocaleString()}`;
              right.appendChild(a);
            }
          }
        }
      });
    }).catch(()=>{});
  @endif

  /* 2) Notifications */
  @if(Route::has('customer.notifications.fetch'))
  fetch('{{ route("customer.notifications.fetch") }}')
    .then(r => r.json())
    .then(data => {
      if (!data.notifications) return;
      const list = document.getElementById('notifList');
      if (!list) return;
      let added = 0;
      data.notifications.forEach(n => {
        if (document.getElementById(`notif-${n.id}`)) return;
        added++;
        const div = document.createElement('div');
        div.id        = `notif-${n.id}`;
        div.className = `notif-item${n.is_read?'':' unread'}`;
        div.innerHTML = `<div class="notif-item-msg">${n.is_read?'':'<span class="n-dot"></span>'}${n.message}</div>
          <div class="notif-item-time"><i class="fas fa-clock me-1"></i>${n.time_ago}</div>`;
        list.insertBefore(div, list.firstChild);
      });
      if (added > 0) {
        /* Remove empty state */
        const empty = document.getElementById('notifEmpty');
        if (empty) empty.remove();
        /* Show red dot on bell */
        if (!document.getElementById('bellDot')) {
          const b = document.getElementById('bellBtn');
          if (b) {
            const dot = document.createElement('span');
            dot.id = 'bellDot'; dot.className = 'notif-dot';
            b.appendChild(dot);
          }
        }
      }
    }).catch(()=>{});
  @endif
}

setInterval(pollAll, 30000);
pollAll();
</script>
</body>
</html>