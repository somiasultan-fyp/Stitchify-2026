<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<title>Manage Users - Stitchify Admin</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/common.css') }}" rel="stylesheet">
<link href="{{ asset('css/admin-users.css') }}" rel="stylesheet">
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

<div class="main-content">
  <div class="top-bar">
    <h2><i class="fas fa-users" style="color:#1976d2;margin-right:10px;"></i>Manage Users</h2>
    <span style="color:#777;font-size:14px;">
      <i class="fas fa-calendar-alt" style="margin-right:5px;"></i>{{ now()->format('D, d M Y') }}
    </span>
  </div>
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

  <div class="filter-card">
    <form method="GET" action="{{ route('admin.users') }}">
      <div class="filter-row">
        <div class="filter-group">
          <label>Search</label>
          <input type="text" name="search"
                 placeholder="Name or email..."
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
              No users found.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="pagination-wrap">
      {{ $users->withQueryString()->links() }}
    </div>
  </div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>