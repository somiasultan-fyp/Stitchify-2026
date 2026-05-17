<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <title>Customer Dashboard - Stitchify</title>
  
  <!-- CSS -->
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

    /* Sidebar */
    .sidebar {
      position: fixed; top: 0; left: 0; height: 100vh; width: 260px;
      background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
      padding: 20px 0; box-shadow: 2px 0 10px rgba(0,0,0,0.1);
      overflow-y: auto; z-index: 1000;
    }
    .sidebar-logo {
      text-align: center; padding: 0 20px 20px;
      border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px;
    }
    .sidebar-logo img { width: 80px; height: 50px; border-radius: 50%; margin-bottom: 10px; }
    .sidebar-logo h3 { color: var(--text-white); font-size: 18px; font-weight: 600; margin: 0; }

    .user-info {
      padding: 15px 20px; background-color: rgba(255,255,255,0.1);
      margin: 0 15px 20px; border-radius: 10px;
    }
    .user-info h4 { color: var(--text-white); font-size: 16px; margin: 0 0 5px 0; }
    .user-info p { color: rgba(255,255,255,0.7); font-size: 13px; margin: 0; }

    .sidebar-menu { list-style: none; padding: 0 15px; }
    .sidebar-menu li { margin-bottom: 5px; }
    .sidebar-menu a {
      display: flex; align-items: center; padding: 12px 15px;
      color: rgba(255,255,255,0.8); text-decoration: none;
      border-radius: 8px; transition: all 0.3s ease; font-size: 15px;
      cursor: pointer; position: relative;
    }
    .sidebar-menu a::before {
      content: ''; position: absolute; left: 0; top: 50%;
      transform: translateY(-50%); width: 0; height: 70%;
      background-color: var(--text-white); border-radius: 0 4px 4px 0;
      transition: width 0.3s ease;
    }
    .sidebar-menu a.active::before { width: 4px; }
    .sidebar-menu a:hover, .sidebar-menu a.active {
      background-color: rgba(255,255,255,0.15);
      color: var(--text-white); padding-left: 20px;
    }
    .sidebar-menu a i { margin-right: 12px; width: 20px; text-align: center; }

    .logout-btn { margin-top: 20px; padding: 0 15px; }
    .logout-btn button {
      background-color: rgba(220,53,69,0.2); color: #ff6b6b;
      border: none; padding: 12px 15px; width: 100%;
      text-align: left; cursor: pointer; border-radius: 8px;
      font-size: 15px; display: flex; align-items: center;
      transition: all 0.3s ease;
    }
    .logout-btn button:hover { background-color: rgba(220,53,69,0.3); }
    .logout-btn button i { margin-right: 12px; width: 20px; text-align: center; }

    /* Main Content */
    .main-content { margin-left: 260px; padding: 20px; min-height: 100vh; }
    .top-bar {
      background-color: var(--text-white); padding: 20px 30px;
      border-radius: 15px; margin-bottom: 25px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    .top-bar h2 { color: var(--accent-color); font-size: 28px; font-weight: 700; margin: 0; }

    /* Stats Grid */
    .stats-grid {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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
    .stat-number { font-size: 32px; font-weight: 700; color: var(--accent-color); margin: 0; }
    .stat-label { color: var(--copyright-bg); font-size: 14px; margin-top: 5px; }

    /* Content Section */
    .content-section {
      background-color: var(--text-white); padding: 25px;
      border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      margin-bottom: 25px; scroll-margin-top: 20px;
    }
    .section-title {
      color: var(--accent-color); font-size: 24px; font-weight: 600;
      margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #f0f0f0;
    }
    .section-header {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 20px; flex-wrap: wrap; gap: 10px;
    }

    /* Order Card */
    .order-card {
      background-color: #f8f9fa; padding: 20px; border-radius: 12px;
      margin-bottom: 15px; border-left: 4px solid var(--accent-color);
      transition: all 0.3s ease;
    }
    .order-card:hover { box-shadow: 0 3px 15px rgba(0,0,0,0.1); }
    .order-header {
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 10px; flex-wrap: wrap; gap: 10px;
    }
    .order-id { font-weight: 600; color: var(--accent-color); font-size: 16px; }
    .order-status {
      padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
    }
    .status-pending { background-color: #fff3e0; color: #f57c00; }
    .status-accepted { background-color: #e3f2fd; color: #1976d2; }
    .status-in_progress { background-color: #fff3cd; color: #856404; }
    .status-ready { background-color: #d4edda; color: #155724; }
    .status-dispatched { background-color: #cce5ff; color: #004085; }
    .status-delivered { background-color: #d1ecf1; color: #0c5460; }
    .status-cancelled { background-color: #f8d7da; color: #721c24; }

    /* Payment Button */
    .payment-btn {
      padding: 6px 16px; background: linear-gradient(135deg, #1976d2, #1565c0);
      color: white; border: none; border-radius: 20px; font-size: 12px;
      font-weight: 600; cursor: pointer; transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(25,118,210,0.3);
      display: flex; align-items: center; gap: 5px;
    }
    .payment-btn:hover {
      background: linear-gradient(135deg, #1565c0, #0d47a1);
      transform: translateY(-2px); box-shadow: 0 4px 12px rgba(25,118,210,0.4);
    }
    .payment-btn:disabled {
      background: #ccc; cursor: not-allowed; transform: none; box-shadow: none;
    }

    .order-actions { display: flex; align-items: center; gap: 10px; }
    .order-details { color: var(--copyright-bg); font-size: 14px; line-height: 1.6; }
    .order-details strong { color: var(--primary-bg); }
    .order-price { font-weight: 600; color: var(--accent-color); }

    /* Empty State */
    .empty-state {
      text-align: center; padding: 40px 20px; color: var(--copyright-bg);
    }
    .empty-state i { font-size: 48px; margin-bottom: 15px; opacity: 0.5; }
    .empty-state .btn { margin-top: 15px; }

    /* Tailor Grid */
    .tailor-grid {
      display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
    }
    .tailor-card {
      background-color: #f8f9fa; padding: 20px; border-radius: 12px;
      text-align: center; transition: all 0.3s ease;
    }
    .tailor-card:hover {
      box-shadow: 0 5px 20px rgba(0,0,0,0.1); transform: translateY(-5px);
    }
    .tailor-avatar {
      width: 80px; height: 80px; border-radius: 50%;
      background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
      color: var(--text-white); display: flex; align-items: center;
      justify-content: center; font-size: 32px; margin: 0 auto 15px;
    }
    .tailor-name { color: var(--accent-color); font-size: 18px; font-weight: 600; margin-bottom: 5px; }
    .tailor-specialty { color: var(--copyright-bg); font-size: 14px; margin-bottom: 10px; }
    .tailor-rating { color: #f57c00; font-size: 14px; margin-bottom: 15px; }
    .tailor-slots {
      font-size: 12px; padding: 4px 10px; border-radius: 12px;
      display: inline-block; margin-bottom: 10px;
    }
    .slots-available { background-color: #d4edda; color: #155724; }
    .slots-full { background-color: #f8d7da; color: #721c24; }

    /* Alerts */
    .alert-fixed {
      position: fixed; top: 20px; right: 20px; z-index: 9999;
      min-width: 300px; max-width: 400px;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
      .sidebar.active { transform: translateX(0); }
      .main-content { margin-left: 0; }
      .menu-toggle {
        display: block; position: fixed; top: 15px; left: 15px;
        z-index: 1001; background: var(--accent-color); color: white;
        border: none; padding: 10px 15px; border-radius: 8px;
        font-size: 18px; cursor: pointer;
      }
      .order-header { flex-direction: column; align-items: flex-start; }
      .order-actions { width: 100%; justify-content: space-between; }
    }
    @media (min-width: 769px) {
      .menu-toggle { display: none; }
    }
  </style>
</head>
<body>

  {{-- ✅ Mobile Menu Toggle --}}
  <button class="menu-toggle" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </button>

  {{-- ✅ Alerts Section --}}
  @if(session('success'))
    <div class="alert alert-success alert-fixed alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-fixed alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- ✅ Sidebar --}}
  <div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
      <img src="{{ asset('images/logo.png') }}" alt="Stitchify Logo" onerror="this.src='https://via.placeholder.com/80x50?text=S'">
      <h3>Stitchify</h3>
    </div>

    <div class="user-info">
      <h4>{{ auth()->user()->name ?? 'Customer' }}</h4>
      <p><i class="fas fa-circle text-success me-1" style="font-size:8px;"></i> Online</p>
    </div>

    <ul class="sidebar-menu">
      <li><a href="#overview" data-section="overview" class="active"><i class="fas fa-th-large"></i> Dashboard</a></li>
      <li><a href="#my-orders" data-section="my-orders"><i class="fas fa-shopping-bag"></i> My Orders</a></li>
      <li><a href="#find-tailors" data-section="find-tailors"><i class="fas fa-cut"></i> Find Tailors</a></li>
      <li><a href="#order-history" data-section="order-history"><i class="fas fa-history"></i> Order History</a></li>
      <li><a href="#"><i class="fas fa-user"></i> My Profile</a></li>
      <li><a href="{{ route('customer.settings') ?? '#' }}"><i class="fas fa-cog"></i> Settings</a></li>
    </ul>

    <div class="logout-btn">
      <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </form>
    </div>
  </div>

  {{-- ✅ Main Content --}}
  <div class="main-content">
    
    {{-- Top Bar --}}
    <div class="top-bar" id="overview">
      <h2>Welcome back, {{ auth()->user()->name ?? 'Customer' }}! 👋</h2>
    </div>

    {{-- Stats Grid --}}
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-shopping-bag"></i></div>
        <h3 class="stat-number">{{ $stats['active_orders'] ?? 0 }}</h3>
        <p class="stat-label">Active Orders</p>
      </div>
      <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <h3 class="stat-number">{{ $stats['completed'] ?? 0 }}</h3>
        <p class="stat-label">Completed Orders</p>
      </div>
      <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
        <h3 class="stat-number">{{ $stats['pending'] ?? 0 }}</h3>
        <p class="stat-label">Pending Approval</p>
      </div>
      <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-bookmark"></i></div>
        <h3 class="stat-number">{{ $stats['total_orders'] ?? 0 }}</h3>
        <p class="stat-label">Total Orders</p>
      </div>
    </div>

    {{-- My Orders Section --}}
    <div class="content-section" id="my-orders">
      <div class="section-header">
        <h3 class="section-title mb-0">My Orders</h3>
        <a href="{{ route('customer.orders.create') }}" class="btn btn-primary btn-sm">
          <i class="fas fa-plus me-1"></i> New Order
        </a>
      </div>

      @if(isset($orders) && $orders->count() > 0)
        @foreach($orders as $order)
        <div class="order-card" data-order-id="{{ $order->id }}">
          <div class="order-header">
            <div class="order-id">#{{ $order->order_number }}</div>
            <div class="order-actions">
              {{-- Status Badge --}}
              <span class="order-status status-{{ str_replace('_', '-', $order->status) }}" 
                    id="status-badge-{{ $order->id }}">
                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
              </span>
              
              {{-- Payment Button (Only for pending/accepted unpaid orders) --}}
              @if(in_array($order->status, ['pending', 'accepted']) && $order->payment_status !== 'paid')
                <button class="payment-btn" 
                        onclick="initiatePayment({{ $order->id }}, '{{ $order->order_number }}', {{ $order->price ?? 0 }})"
                        {{ $order->status === 'pending' ? 'disabled title="Wait for tailor to accept"' : '' }}>
                  <i class="fas fa-credit-card"></i> 
                  {{ $order->status === 'pending' ? 'Waiting...' : 'Pay Rs. '.($order->price ?? 0) }}
                </button>
              @endif
            </div>
          </div>
          
          <div class="order-details">
            <p><strong>Tailor:</strong> {{ $order->tailor->user->name ?? 'N/A' }}</p>
            <p><strong>Item:</strong> {{ $order->dress_type }}</p>
            <p><strong>Fabric:</strong> {{ $order->fabric_details ?? 'Not specified' }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
            @if($order->expected_delivery_date)
              <p><strong>Expected Delivery:</strong> {{ $order->expected_delivery_date->format('M d, Y') }}</p>
            @endif
            @if($order->price)
              <p><strong>Price:</strong> <span class="order-price">Rs. {{ number_format($order->price) }}</span></p>
            @endif
          </div>
          
          <div class="mt-3">
            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
              View Details <i class="fas fa-arrow-right ms-1"></i>
            </a>
            @if($order->status === 'pending')
              <button class="btn btn-outline-danger btn-sm ms-2" 
                      onclick="confirmCancel({{ $order->id }})">
                Cancel Order
              </button>
            @endif
          </div>
        </div>
        @endforeach
        
        {{-- Pagination --}}
        @if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
          <div class="mt-3">
            {{ $orders->links('pagination::bootstrap-5') }}
          </div>
        @endif
        
      @else
        <div class="empty-state">
          <i class="fas fa-shopping-bag"></i>
          <h5>No orders yet</h5>
          <p class="mb-0">Start by placing your first order with a tailor!</p>
          <a href="{{ route('customer.orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Place Your First Order
          </a>
        </div>
      @endif
    </div>

    {{-- Find Tailors Section --}}
    <div class="content-section" id="find-tailors">
      <div class="section-header">
        <h3 class="section-title mb-0">Find Tailors</h3>
        <input type="text" class="form-control form-control-sm w-auto" 
               placeholder="Search tailors..." id="tailorSearch"
               onkeyup="filterTailors()">
      </div>

      <div class="tailor-grid" id="tailorGrid">
        @php
          $tailors = \App\Models\Tailor::with('user')
            ->where('status', 'approved')
            ->where('available_slots', '>', 0)
            ->take(6)
            ->get();
        @endphp
        
        @forelse($tailors as $tailor)
        <div class="tailor-card" data-tailor-name="{{ strtolower($tailor->user->name ?? '') }}">
          <div class="tailor-avatar">
            <i class="fas fa-user"></i>
          </div>
          <h4 class="tailor-name">{{ $tailor->user->name ?? 'Tailor' }}</h4>
          <p class="tailor-specialty">{{ $tailor->specialization ?? 'All Categories' }}</p>
          
          <span class="tailor-slots {{ $tailor->available_slots > 0 ? 'slots-available' : 'slots-full' }}">
            <i class="fas fa-clock me-1"></i>
            {{ $tailor->available_slots }} slot{{ $tailor->available_slots != 1 ? 's' : '' }} available
          </span>
          
          <div class="tailor-rating">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
            <span class="ms-1">4.5</span>
          </div>
          
          <a href="{{ route('customer.orders.create', ['tailor_id' => $tailor->id]) }}" 
             class="btn btn-primary btn-sm w-100"
             {{ !$tailor->hasAvailableSlot() ? 'disabled' : '' }}>
            {{ $tailor->hasAvailableSlot() ? 'Place Order' : 'Slots Full' }}
          </a>
        </div>
        @empty
        <div class="col-12 text-center py-4">
          <p class="text-muted mb-0">No tailors available right now. Check back later!</p>
        </div>
        @endforelse
      </div>
      
      <div class="text-center mt-3">
        <a href="{{ route('tailor.tailors.index') ?? '#' }}" class="btn btn-outline-secondary btn-sm">
          View All Tailors <i class="fas fa-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    {{-- Order History Section --}}
    <div class="content-section" id="order-history">
      <h3 class="section-title">Order History</h3>
      
      @php
        $historyOrders = isset($orders) ? 
          $orders->filter(fn($o) => in_array($o->status, ['delivered', 'cancelled'])) : 
          collect();
      @endphp
      
      @forelse($historyOrders->take(5) as $order)
      <div class="order-card">
        <div class="order-header">
          <div class="order-id">#{{ $order->order_number }}</div>
          <span class="order-status status-{{ str_replace('_', '-', $order->status) }}">
            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
          </span>
        </div>
        <div class="order-details">
          <p><strong>Tailor:</strong> {{ $order->tailor->user->name ?? 'N/A' }}</p>
          <p><strong>Item:</strong> {{ $order->dress_type }}</p>
          <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
          @if($order->status === 'delivered' && $order->actual_delivery_date)
            <p><strong>Delivered:</strong> {{ $order->actual_delivery_date->format('M d, Y') }}</p>
          @endif
        </div>
        <div class="mt-2">
          <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm">
            View Details
          </a>
          @if($order->status === 'delivered')
            <button class="btn btn-outline-success btn-sm ms-2" onclick="submitReview({{ $order->id }})">
              <i class="fas fa-star me-1"></i> Rate
            </button>
          @endif
        </div>
      </div>
      @empty
      <p class="text-muted">No completed orders yet.</p>
      @endforelse
    </div>

  </div> {{-- End Main Content --}}

  {{-- ✅ JavaScript --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
  <script>
    // CSRF Token for AJAX
    document.querySelectorAll('form').forEach(form => {
      if (!form.querySelector('input[name="_token"]')) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        if (token) {
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = '_token';
          input.value = token;
          form.appendChild(input);
        }
      }
    });

    // Mobile Sidebar Toggle
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('active');
    }

    // Close sidebar when clicking outside (mobile)
    document.addEventListener('click', function(e) {
      const sidebar = document.getElementById('sidebar');
      const toggle = document.querySelector('.menu-toggle');
      if (window.innerWidth <= 768 && 
          !sidebar.contains(e.target) && 
          !toggle.contains(e.target) && 
          sidebar.classList.contains('active')) {
        sidebar.classList.remove('active');
      }
    });

    // Smooth Scroll + Active Link Highlight
    document.querySelectorAll('.sidebar-menu a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          // Update active state
          document.querySelectorAll('.sidebar-menu a').forEach(a => a.classList.remove('active'));
          this.classList.add('active');
          // Close mobile sidebar
          if (window.innerWidth <= 768) {
            document.getElementById('sidebar').classList.remove('active');
          }
        }
      });
    });

    // Scroll Spy
    const sections = document.querySelectorAll('.content-section, .top-bar');
    const navLinks = document.querySelectorAll('.sidebar-menu a[data-section]');
    
    function updateActiveLink() {
      let current = '';
      sections.forEach(section => {
        const sectionTop = section.offsetTop - 100;
        if (window.scrollY >= sectionTop) {
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

    // ✅ Payment Handler (Placeholder - Integrate your gateway here)
    async function initiatePayment(orderId, orderNumber, amount) {
      console.log('Payment initiated:', { orderId, orderNumber, amount });
      
      // Show loading state
      const btn = event.target.closest('button');
      const originalText = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
      
      try {
        // TODO: Replace with your actual payment API call
        // Example: const response = await fetch('/api/payment/initiate', { ... });
        
        // Simulate API delay
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        // Show success (for demo)
        alert(`✅ Payment successful for Order #${orderNumber}\nAmount: Rs. ${amount}\n\n(In production, redirect to payment gateway)`);
        
        // TODO: Redirect to payment success page or update UI
        // window.location.href = `/customer/orders/${orderId}`;
        
      } catch (error) {
        console.error('Payment failed:', error);
        alert('❌ Payment failed. Please try again.');
      } finally {
        // Restore button
        btn.disabled = false;
        btn.innerHTML = originalText;
      }
    }

    // ✅ Cancel Order Confirmation
    function confirmCancel(orderId) {
      if (confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
        const reason = prompt('Optional: Reason for cancellation:');
        
        // Submit cancel request
        fetch(`/customer/orders/${orderId}/cancel`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
          },
          body: JSON.stringify({ cancel_reason: reason })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert('Order cancelled successfully.');
            location.reload();
          } else {
            alert('Failed to cancel order: ' + (data.message || 'Unknown error'));
          }
        })
        .catch(err => {
          console.error('Cancel error:', err);
          alert('Error cancelling order. Please try again.');
        });
      }
    }

    // ✅ Submit Review (Placeholder)
    function submitReview(orderId) {
      alert(`Review feature for Order #${orderId}\n\n(Integrate rating/review system here)`);
      // TODO: Open modal or redirect to review page
      // window.location.href = `/customer/orders/${orderId}/review`;
    }

    // ✅ Filter Tailors by Search
    function filterTailors() {
      const query = document.getElementById('tailorSearch').value.toLowerCase();
      const cards = document.querySelectorAll('#tailorGrid .tailor-card');
      
      cards.forEach(card => {
        const name = card.getAttribute('data-tailor-name') || '';
        card.style.display = name.includes(query) ? 'block' : 'none';
      });
    }

    // ✅ AJAX Live Status Update (Every 30 seconds)
    function fetchLiveStatus() {
      fetch('{{ route("customer.orders.live") }}')
        .then(res => res.json())
        .then(data => {
          if (data.success && data.orders) {
            data.orders.forEach(order => {
              // Update status badge
              const badge = document.getElementById(`status-badge-${order.id}`);
              if (badge) {
                badge.textContent = order.status_label || order.status.replace('_', ' ');
                badge.className = `order-status status-${order.status.replace('_', '-')}`;
              }
              
              // Update payment button if status changed to 'accepted'
              const payBtn = document.querySelector(`[onclick*="initiatePayment(${order.id}"]`);
              if (payBtn && order.status === 'accepted') {
                payBtn.disabled = false;
                payBtn.title = '';
                payBtn.innerHTML = `<i class="fas fa-credit-card"></i> Pay Rs. ${order.price || 0}`;
              }
            });
          }
        })
        .catch(err => console.error('Live status fetch error:', err));
    }
    
    // Start live updates if route exists
    @if(Route::has('customer.orders.live'))
      setInterval(fetchLiveStatus, 30000); // 30 seconds
      fetchLiveStatus(); // Initial fetch
    @endif

    // ✅ Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
      setTimeout(() => {
        document.querySelectorAll('.alert-fixed').forEach(alert => {
          const bsAlert = new bootstrap.Alert(alert);
          bsAlert.close();
        });
      }, 5000);
    });
  </script>
</body>
</html>