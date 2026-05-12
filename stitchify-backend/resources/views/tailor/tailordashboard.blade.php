<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
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
body {
  background-color: #f5f6fa;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Sidebar */
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
.sidebar-logo h3 { color: var(--text-white); font-size: 18px; font-weight: 600; margin: 0; }

.user-info {
  padding: 15px 20px;
  background-color: rgba(255,255,255,0.1);
  margin: 0 15px 20px;
  border-radius: 10px;
}
.user-info h4 { color: var(--text-white); font-size: 16px; margin: 0 0 5px 0; }
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
}
.sidebar-menu a:hover {
  background-color: rgba(255,255,255,0.15);
  color: var(--text-white);
}
.sidebar-menu a i { margin-right: 12px; width: 20px; text-align: center; }

.logout-btn { margin-top: 20px; padding: 0 15px; }
.logout-btn a {
  background-color: rgba(220,53,69,0.2);
  color: #ff6b6b;
  text-decoration: none;
  display: flex;
  align-items: center;
  padding: 12px 15px;
  border-radius: 8px;
}
.logout-btn a:hover { background-color: rgba(220,53,69,0.3); }

/* Main Content */
.main-content { margin-left: 260px; padding: 20px; min-height: 100vh; }

.top-bar {
  background-color: var(--text-white);
  padding: 20px 30px;
  border-radius: 15px;
  margin-bottom: 25px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.top-bar h2 { color: var(--accent-color); font-size: 28px; font-weight: 700; margin: 0; }

/* Stats Cards */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px;
  margin-bottom: 25px;
}

.stat-card {
  background: linear-gradient(135deg, var(--text-white) 0%, #f8f9fa 100%);
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
  transition: transform 0.3s ease;
  border-left: 4px solid var(--accent-color);
  text-align: center;
}
.stat-card:hover { transform: translateY(-5px); }

.stat-icon {
  width: 50px; height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  margin: 0 auto 15px auto;
}
.stat-icon.orange { background-color: #fff3e0; color: #f57c00; }
.stat-icon.blue { background-color: #e3f2fd; color: #1976d2; }
.stat-icon.green { background-color: #e8f5e9; color: #388e3c; }
.stat-icon.purple { background-color: #f3e5f5; color: #7b1fa2; }

.stat-number { font-size: 32px; font-weight: 700; color: var(--accent-color); margin: 10px 0; }
.stat-label { color: var(--copyright-bg); font-size: 14px; }

/* Content Sections */
.content-section {
  background-color: var(--text-white);
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
  padding-bottom: 15px;
  border-bottom: 2px solid #f0f0f0;
}

/* Order Cards */
.order-card {
  background-color: #f8f9fa;
  padding: 20px;
  border-radius: 12px;
  margin-bottom: 15px;
  border-left: 4px solid var(--accent-color);
}
.order-card:hover { box-shadow: 0 3px 15px rgba(0,0,0,0.1); }

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: start;
  margin-bottom: 10px;
}
.order-id { font-weight: 600; color: var(--accent-color); font-size: 16px; }
.order-status { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
.status-pending { background-color: #fff3e0; color: #f57c00; }
.status-progress { background-color: #e3f2fd; color: #1976d2; }
.status-delivered { background-color: #e8f5e9; color: #388e3c; }

.order-details { color: var(--copyright-bg); font-size: 14px; line-height: 1.6; }
.order-details strong { color: var(--primary-bg); }

.order-actions { margin-top: 15px; display: flex; gap: 10px; flex-wrap: wrap; }

/* Buttons */
.btn-sm-custom {
  padding: 8px 18px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-block;
}
.btn-accept { background-color: #28a745; color: white; }
.btn-accept:hover { background-color: #218838; color: white; }
.btn-reject { background-color: #dc3545; color: white; }
.btn-reject:hover { background-color: #c82333; color: white; }
.btn-view { background-color: var(--accent-color); color: white; }
.btn-view:hover { background-color: var(--primary-bg); color: white; }
.btn-progress { background-color: #007bff; color: white; }
.btn-progress:hover { background-color: #0069d9; color: white; }

/* Forms */
.form-group { margin-bottom: 12px; }
.form-group label { font-size: 13px; font-weight: 600; color: var(--primary-bg); margin-bottom: 5px; display: block; }
.form-control-sm-custom {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 13px;
}
textarea.form-control-sm-custom { resize: vertical; }

/* Alerts */
.alert-custom {
  padding: 12px 20px;
  border-radius: 8px;
  margin-bottom: 20px;
}
.alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

@media (max-width: 768px) {
  .sidebar { transform: translateX(-100%); }
  .main-content { margin-left: 0; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-logo">
        <h3>🧵 Stitchify</h3>
    </div>
    <div class="user-info">
        <h4>{{ auth()->user()->name ?? 'Tailor' }}</h4>
        <p>Professional Tailor</p>
    </div>
    <ul class="sidebar-menu">
        <li><a href="#overview"><i class="fas fa-th-large"></i> Dashboard</a></li>
        <li><a href="#pending-orders"><i class="fas fa-hourglass-half"></i> Pending Orders</a></li>
        <li><a href="#active-orders"><i class="fas fa-tasks"></i> Active Orders</a></li>
        <li><a href="#completed-orders"><i class="fas fa-check-circle"></i> Completed Orders</a></li>
    </ul>
    <div class="logout-btn">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>

<div class="main-content">
    <div class="top-bar" id="overview">
        <h2>Welcome Back, {{ auth()->user()->name ?? 'Tailor' }}! 👋</h2>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert-custom alert-success">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert-custom alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-number">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending Orders</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-tasks"></i></div>
            <div class="stat-number">{{ $stats['in_progress'] }}</div>
            <div class="stat-label">In Progress</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-double"></i></div>
            <div class="stat-number">{{ $stats['delivered'] }}</div>
            <div class="stat-label">Delivered</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-layer-group"></i></div>
            <div class="stat-number">{{ $stats['slots_left'] }}</div>
            <div class="stat-label">Slots Available</div>
        </div>
    </div>

    {{-- Pending Orders --}}
    @if($pendingOrders->isNotEmpty())
    <div class="content-section" id="pending-orders">
        <h3 class="section-title"><i class="fas fa-hourglass-half me-2"></i> Pending Orders</h3>
        @foreach($pendingOrders as $order)
        <div class="order-card">
            <div class="order-header">
                <div class="order-id">Order #{{ $order->id }}</div>
                <span class="order-status status-pending">Pending</span>
            </div>
            <div class="order-details">
                <p><strong>Customer:</strong> {{ $order->customer->user->name ?? 'N/A' }}</p>
                <p><strong>Dress:</strong> {{ $order->dress_type }}</p>
                <p><strong>Placed:</strong> {{ $order->created_at->diffForHumans() }}</p>
            </div>
            <div class="order-actions">
                <a href="{{ route('tailor.orders.show', $order) }}" class="btn-sm-custom btn-view">View Details</a>
            </div>

            <div class="row mt-3">
                {{-- Accept Form --}}
                <div class="col-md-6 mb-3">
                    <div class="bg-light p-3 rounded border border-success">
                        <p class="fw-bold text-success mb-2"><i class="fas fa-check-circle"></i> Accept Order</p>
                        <form action="{{ route('tailor.orders.accept', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label>Price (Rs.)</label>
                                <input type="number" name="price" placeholder="Enter price" required class="form-control-sm-custom">
                            </div>
                            <div class="form-group">
                                <label>Delivery Days</label>
                                <input type="number" name="delivery_days" placeholder="Enter delivery days" required class="form-control-sm-custom">
                            </div>
                            <button type="submit" class="btn-sm-custom btn-accept w-100 mt-2">Accept Order</button>
                        </form>
                    </div>
                </div>

                {{-- Reject Form --}}
                <div class="col-md-6 mb-3">
                    <div class="bg-light p-3 rounded border border-danger">
                        <p class="fw-bold text-danger mb-2"><i class="fas fa-times-circle"></i> Reject Order</p>
                        <form action="{{ route('tailor.orders.reject', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label>Rejection Reason</label>
                                <textarea name="rejection_reason" placeholder="Write rejection reason..." required class="form-control-sm-custom" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn-sm-custom btn-reject w-100 mt-2" onclick="return confirm('Are you sure you want to reject this order?')">Reject Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Active Orders --}}
    @if($activeOrders->isNotEmpty())
    <div class="content-section" id="active-orders">
        <h3 class="section-title"><i class="fas fa-tasks me-2"></i> Active Orders</h3>
        @foreach($activeOrders as $order)
        <div class="order-card">
            <div class="order-header">
                <div class="order-id">Order #{{ $order->id }} — {{ $order->customer->user->name ?? 'N/A' }}</div>
                <span class="order-status status-progress">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
            </div>
            <div class="order-details">
                <p><strong>Delivery:</strong> {{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('d M, Y') }}</p>
            </div>
            <div class="order-actions">
                <form action="{{ route('tailor.orders.status', $order) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    @php
                        $nextLabels = [
                            'in_progress' => 'Mark as Ready',
                            'ready'       => 'Mark as Dispatched',
                            'dispatched'  => 'Mark as Delivered',
                        ];
                        $label = $nextLabels[$order->status] ?? null;
                    @endphp
                    @if($label)
                        <button type="submit" class="btn-sm-custom btn-progress">{{ $label }}</button>
                    @endif
                </form>
                <a href="{{ route('tailor.orders.show', $order) }}" class="btn-sm-custom btn-view">View Details</a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Completed Orders --}}
    @if($completedOrders->isNotEmpty())
    <div class="content-section" id="completed-orders">
        <h3 class="section-title"><i class="fas fa-check-circle me-2"></i> Completed Orders</h3>
        @foreach($completedOrders as $order)
        <div class="order-card">
            <div class="order-header">
                <div class="order-id">Order #{{ $order->id }} — {{ $order->customer->user->name ?? 'N/A' }}</div>
                <span class="order-status status-delivered">Delivered</span>
            </div>
            <div class="order-details">
                <p><strong>Amount:</strong> Rs. {{ number_format($order->price) }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
    setInterval(() => location.reload(), 30000);

    document.querySelectorAll('.sidebar-menu a').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth' });
        });
    });
</script>
</body>
</html>
