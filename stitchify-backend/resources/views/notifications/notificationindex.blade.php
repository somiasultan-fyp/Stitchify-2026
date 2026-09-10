<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<title>Notifications - Stitchify</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
</head>
<body>

<div class="page-wrapper">

  @php
    $backUrl = match(auth()->user()->role) {
        'tailor'   => '/tailor/dashboard',
        'customer' => '/customer/dashboard',
        'admin'    => '/admin/dashboard',
        default    => '/',
    };
  @endphp

  <a href="{{ $backUrl }}" class="back-link">
    <i class="fas fa-arrow-left"></i> Back to Dashboard
  </a>

  <div class="page-header">
    <h2>Notifications</h2>
    <form method="POST" action="{{ route('notifications.readAll') }}">
      @csrf
      @method('PATCH')
      <button type="submit" class="btn-mark-all">
        <i class="fas fa-check-double me-1"></i> Mark All Read
      </button>
    </form>
  </div>

  <div class="notification-list">
    @forelse($notifications as $notification)
      @php
        $iconClass = match($notification->type) {
            'order'    => 'order fas fa-shopping-bag',
            'payment'  => 'payment fas fa-credit-card',
            'delivery' => 'delivery fas fa-truck',
            default    => 'default fas fa-bell',
        };
      @endphp
      <a href="{{ $notification->action_url ?? '#' }}"
         class="notification-card {{ $notification->is_read ? '' : 'unread' }}">
        <div class="notification-icon {{ explode(' ', $iconClass)[0] }}">
          <i class="{{ substr($iconClass, strpos($iconClass, ' ') + 1) }}"></i>
        </div>
        <div class="notification-body">
          <div class="notification-title">{{ $notification->title }}</div>
          <div class="notification-message">{{ $notification->message }}</div>
          <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
        </div>
      </a>
    @empty
      <div class="empty-state">
        <i class="fas fa-bell-slash"></i>
        <p>You have no notifications yet.</p>
      </div>
    @endforelse
  </div>

  <div class="pagination-wrap">
    {{ $notifications->links() }}
  </div>
</div>

</body>
</html>