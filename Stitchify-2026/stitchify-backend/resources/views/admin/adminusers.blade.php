<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<title>Manage Users - Stitchify Admin</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
:root {
  --primary-bg: #212529;
  --accent-color: #1b2a4a;
  --text-white: #ffffff;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { background-color: #f5f6fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

/* SIDEBAR */
.sidebar {
  position: fixed; top: 0; left: 0;
  height: 100vh; width: 260px;
  background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
  padding: 20px 0; overflow-y: auto; z-index: 1000;
}
.sidebar-logo { text-align: center; padding: 0 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px; }
.sidebar-logo img { width: 80px; height: 50px; border-radius: 50%; margin-bottom: 10px; }
.sidebar-logo h3 { color: var(--text-white); font-size: 18px; font-weight: 600; margin: 0; }
.admin-badge { display: inline-block; background: linear-gradient(90deg,#c0392b,#e74c3c); color: white; font-size: 10px; font-weight: 700; letter-spacing: 1px; padding: 2px 8px; border-radius: 10px; margin-top: 4px; }
.user-info { padding: 15px 20px; background-color: rgba(255,255,255,0.1); margin: 0 15px 20px; border-radius: 10px; }
.user-info h4 { color: var(--text-white); font-size: 16px; margin: 0 0 4px 0; }
.user-info p { color: rgba(255,255,255,0.7); font-size: 13px; margin: 0; }
.sidebar-menu { list-style: none; padding: 0 15px; }
.sidebar-menu li { margin-bottom: 5px; }
.sidebar-menu a { display: flex; align-items: center; padding: 12px 15px; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 8px; transition: all 0.3s; font-size: 15px; position: relative; }
.sidebar-menu a.active,
.sidebar-menu a:hover { background-color: rgba(255,255,255,0.15); color: white; padding-left: 20px; }
.sidebar-menu a i { margin-right: 12px; width: 20px; text-align: center; }
.logout-btn { margin-top: 20px; padding: 0 15px; }
.logout-btn button { background: none; border: none; padding: 12px 15px; color: #ff6b6b; width: 100%; text-align: left; cursor: pointer; border-radius: 8px; font-size: 15px; display: flex; align-items: center; transition: all 0.3s; }
.logout-btn button:hover { background-color: rgba(220,53,69,0.3); }
.logout-btn button i { margin-right: 12px; }

/* MAIN */
.main-content { margin-left: 260px; padding: 20px; min-height: 100vh; }
.top-bar { background: white; padding: 20px 30px; border-radius: 15px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: space-between; }
.top-bar h2 { color: var(--accent-color); font-size: 26px; font-weight: 700; margin: 0; }

/* ALERTS */
.alert-success-custom { background: #e8f5e9; border-left: 4px solid #27ae60; color: #1e8449; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 600; }
.alert-error-custom { background: #fde8e8; border-left: 4px solid #c0392b; color: #c0392b; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 600; }

/* FILTER */
.filter-card { background: white; padding: 20px 25px; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
.filter-row { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
.filter-group { flex: 1; min-width: 180px; }
.filter-group label { font-size: 13px; font-weight: 600; color: var(--accent-color); margin-bottom: 5px; display: block; }
.filter-group input,
.filter-group select { width: 100%; border: 1px solid #ddd; border-radius: 8px; padding: 9px 14px; font-size: 14px; outline: none; color: #444; background: #f8f9fa; }
.filter-group input:focus,
.filter-group select:focus { border-color: var(--accent-color); }

/* TABLE */
.table-card { background: white; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden; }
.table-card-header { padding: 18px 25px; border-bottom: 2px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between; }
.table-card-header h3 { color: var(--accent-color); font-size: 18px; font-weight: 700; margin: 0; }
.admin-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.admin-table thead th { background-color: #f1f3f8; color: var(--accent-color); font-weight: 700; padding: 12px 18px; text-align: left; border-bottom: 2px solid #e0e0e0; }
.admin-table tbody tr { border-bottom: 1px solid #f0f0f0; transition: background 0.2s; }
.admin-table tbody tr:hover { background-color: #f8f9fa; }
.admin-table td { padding: 13px 18px; color: #444; vertical-align: middle; }

/* BADGES */
.badge-status { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
.badge-customer   { background-color: #e3f2fd; color: #1565c0; }
.badge-tailor     { background-color: #f3e5f5; color: #6a1b9a; }
.badge-active     { background-color: #e8f5e9; color: #388e3c; }
.badge-blocked    { background-color: #fde8e8; color: #c0392b; }
.badge-verified   { background-color: #e8f5e9; color: #27ae60; }
.badge-unverified { background-color: #fff3e0; color: #f57c00; }

/* BUTTONS */
.btn-action { border: none; border-radius: 6px; padding: 6px 14px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
.btn-block   { background: #fde8e8; color: #c0392b; }
.btn-block:hover   { background: #c0392b; color: white; }
.btn-unblock { background: #e8f5e9; color: #27ae60; }
.btn-unblock:hover { background: #27ae60; color: white; }
.btn-filter { background: var(--accent-color); color: white; padding: 9px 20px; border-radius: 8px; border: none; font-size: 14px; font-weight: 600; cursor: pointer; }
.btn-filter:hover { background: #253e6e; }
.btn-reset { background: #f0f0f0; color: #555; padding: 9px 20px; border-radius: 8px; border: none; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; }
.btn-reset:hover { background: #ddd; }

/* PAGINATION */
.pagination-wrap { padding: 15px 20px; border-top: 1px solid #f0f0f0; }
.pagination .page-link { color: var(--accent-color); }
.pagination .page-item.active .page-link { background-color: var(--accent-color); border-color: var(--accent-color); }
</style>
</head>
<body>

{{-- SIDEBAR --}}
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
    <li><a href="{{ route('admin.dashboard') }}">            <i class="fas fa-th-large"></i>    Dashboard</a></li>
    <li><a href="{{ route('admin.users') }}" class="active"> <i class="fas fa-users"></i>        Manage Users</a></li>
    <li><a href="{{ route('admin.orders') }}">               <i class="fas fa-shopping-bag"></i> All Orders</a></li>
    <li><a href="{{ route('admin.complaints') }}">           <i class="fas fa-comments"></i>     Complaints</a></li>
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

{{-- MAIN --}}
<div class="main-content">

  {{-- Top Bar --}}
  <div class="top-bar">
    <h2><i class="fas fa-users" style="color:#1976d2;margin-right:10px;"></i>Manage Users</h2>
    <span style="color:#777;font-size:14px;">
      <i class="fas fa-calendar-alt" style="margin-right:5px;"></i>{{ now()->format('D, d M Y') }}
    </span>
  </div>

  {{-- Alerts --}}
  @if(session('success'))
    <div class="alert-success-custom">
      <i class="fas fa-check-circle" style="margin-right:8px;"></i>{{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="alert-error-custom">
      <i class="fas fa-exclamation-circle" style="margin-right:8px;"></i>{{ session('error') }}
    </div>
  @endif

  {{-- FILTER --}}
  <div class="filter-card">
    <form method="GET" action="{{ route('admin.users') }}">
      <div class="filter-row">
        <div class="filter-group">
          <label>Search</label>
          <input type="text" name="search"
                 placeholder="Name ya email..."
                 value="{{ request('search') }}">
        </div>
        <div class="filter-group" style="max-width:200px;">
          <label>Role</label>
          <select name="role">
            <option value="all"      {{ $role === 'all'      ? 'selected' : '' }}>All Roles</option>
            <option value="customer" {{ $role === 'customer' ? 'selected' : '' }}>Customer</option>
            <option value="tailor"   {{ $role === 'tailor'   ? 'selected' : '' }}>Tailor</option>
          </select>
        </div>
        <div style="display:flex;gap:8px;align-items:flex-end;">
          <button type="submit" class="btn-filter">
            <i class="fas fa-search" style="margin-right:5px;"></i>Filter
          </button>
          <a href="{{ route('admin.users') }}" class="btn-reset">
            <i class="fas fa-redo" style="margin-right:5px;"></i>Reset
          </a>
        </div>
      </div>
    </form>
  </div>

  {{-- TABLE --}}
  <div class="table-card">
    <div class="table-card-header">
      <h3>All Users</h3>
      <span style="color:#777;font-size:13px;">Total: {{ $users->total() }} users</span>
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
            <th>Email Verified</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td><strong>{{ $user->name }}</strong></td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone ?? '—' }}</td>
            <td>
              <span class="badge-status {{ $user->role === 'tailor' ? 'badge-tailor' : 'badge-customer' }}">
                {{ ucfirst($user->role) }}
              </span>
            </td>
            <td>
              @if($user->email_verified_at)
                <span class="badge-status badge-verified">
                  <i class="fas fa-check-circle"></i> Verified
                </span>
              @else
                <span class="badge-status badge-unverified">
                  <i class="fas fa-times-circle"></i> Not Verified
                </span>
              @endif
            </td>
            <td>
              <span class="badge-status {{ $user->is_active ? 'badge-active' : 'badge-blocked' }}">
                {{ $user->is_active ? 'Active' : 'Blocked' }}
              </span>
            </td>
            <td>
              @if(auth()->id() !== $user->id)
                <form method="POST"
                      action="{{ route('admin.users.toggle', $user->id) }}"
                      style="display:inline;"
                      onsubmit="return confirm('{{ $user->is_active ? 'Block' : 'Unblock' }} {{ addslashes($user->name) }}?')">
                  @csrf @method('PATCH')
                  <button type="submit"
                          class="btn-action {{ $user->is_active ? 'btn-block' : 'btn-unblock' }}">
                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                    {{ $user->is_active ? 'Block' : 'Unblock' }}
                  </button>
                </form>
              @else
                <span style="color:#aaa;font-size:12px;">You</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" style="text-align:center;color:#aaa;padding:40px;">
              <i class="fas fa-users" style="font-size:30px;margin-bottom:10px;display:block;"></i>
              Koi user nahi mila.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{-- Pagination --}}
    <div class="pagination-wrap">
      {{ $users->withQueryString()->links() }}
    </div>
  </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>