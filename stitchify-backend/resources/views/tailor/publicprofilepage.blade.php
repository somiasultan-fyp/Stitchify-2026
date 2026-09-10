<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <title>{{ $tailor->user->name }} - Stitchify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tailor-public-profile.css') }}">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/logo.png') }}" alt="Stitchify" height="55">
        </a>
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="/aboutus">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="/contactus">Contact Us</a></li>
                <li class="nav-item ms-lg-3">
                    @auth
                        @if(auth()->user()->role === 'customer')
                            <a href="/customer/dashboard" class="btn-stitchify">Dashboard</a>
                        @elseif(auth()->user()->role === 'tailor')
                            <a href="/tailor/dashboard" class="btn-stitchify">Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login.form') }}" class="btn-stitchify">Login</a>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>
@php
    $defaultAvatarSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect width="100" height="100" fill="#1B2A4A"/><circle cx="50" cy="38" r="18" fill="#ffffff"/><path d="M50 60c-22 0-34 12-34 26v14h68V86c0-14-12-26-34-26z" fill="#ffffff"/></svg>';
    $defaultAvatarUri = 'data:image/svg+xml;base64,' . base64_encode($defaultAvatarSvg);
@endphp
<div class="container" style="max-width: 750px;">
    <a href="/tailors" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Tailors
    </a>

    <div class="profile-hero">
        <img src="{{ $tailor->user->profile_image ? Storage::url($tailor->user->profile_image) : $defaultAvatarUri }}"
             alt="{{ $tailor->user->name }}"
             class="profile-avatar">

        <div class="profile-hero-info">
            <h2>{{ $tailor->user->name }}</h2>

            <span class="badge-category">
                <i class="fas fa-tag me-1"></i>
                {{ $tailor->specialization ?? 'General Tailoring' }}
            </span>

            <div class="profile-hero-stats">
                <div class="hero-stat">
                    <span class="num">{{ $tailor->experience_years ?? 0 }}</span>
                    <span class="lbl">Years Exp.</span>
                </div>
                <div class="hero-stat">
                    <span class="num">
                        {{ $tailor->orders()->where('status','delivered')->count() }}
                    </span>
                    <span class="lbl">Completed</span>
                </div>
                <div class="hero-stat">
                    <span class="num">{{ $tailor->available_slots }}</span>
                    <span class="lbl">Slots Left</span>
                </div>
            </div>

            @if($tailor->available_slots > 0)
                <div class="slot-badge slot-available">
                    <i class="fas fa-check-circle"></i> Available for Orders
                </div>
            @else
                <div class="slot-badge slot-full">
                    <i class="fas fa-times-circle"></i> Currently Full
                </div>
            @endif
        </div>
    </div>

    <div class="info-card">
        <div class="card-title-custom">
            <i class="fas fa-info-circle"></i> Details
        </div>

        @if($tailor->address)
        <div class="detail-row">
            <div class="detail-icon"><i class="fas fa-store"></i></div>
            <div>
                <div class="detail-label">Address</div>
                <div class="detail-value">{{ $tailor->address }}</div>
            </div>
        </div>
        @endif

        <div class="detail-row">
            <div class="detail-icon"><i class="fas fa-envelope"></i></div>
            <div>
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $tailor->user->email }}</div>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-icon"><i class="fas fa-phone"></i></div>
            <div>
                <div class="detail-label">Phone</div>
                <div class="detail-value">
                    {{ $tailor->user->phone ?? 'Not provided' }}
                </div>
            </div>
        </div>

        <div class="detail-row">
            <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div>
                <div class="detail-label">City / Address</div>
                <div class="detail-value">
                    {{ $tailor->city ?? '' }}
                    {{ $tailor->city && $tailor->address ? ' — ' : '' }}
                    {{ $tailor->address ?? 'Not provided' }}
                </div>
            </div>
        </div>

        @if($tailor->base_price)
        <div class="detail-row">
            <div class="detail-icon"><i class="fas fa-tag"></i></div>
            <div>
                <div class="detail-label">Starting Price</div>
                <div class="detail-value">Rs. {{ number_format($tailor->base_price) }}</div>
            </div>
        </div>
        @endif

        <div class="detail-row">
            <div class="detail-icon"><i class="fas fa-layer-group"></i></div>
            <div>
                <div class="detail-label">Slot Capacity</div>
                <div class="detail-value">
                    {{ $tailor->available_slots }} / {{ $tailor->max_slots }} available
                </div>
            </div>
        </div>

        @if($tailor->bio)
        <div class="detail-row">
            <div class="detail-icon"><i class="fas fa-align-left"></i></div>
            <div>
                <div class="detail-label">About</div>
                <div class="detail-value">{{ $tailor->bio }}</div>
            </div>
        </div>
        @endif
    </div>

    <div class="info-card">
        <div class="card-title-custom">
            <i class="fas fa-images"></i> Portfolio
        </div>

        @php $portfolios = $tailor->portfolios; @endphp

        @if($portfolios->count() > 0)
            <div class="portfolio-grid">
                @foreach($portfolios as $item)
                <div>
                    <div class="portfolio-item"
                         data-image-url="{{ Storage::url($item->image_path) }}">
                        <img src="{{ Storage::url($item->image_path) }}"
                             alt="{{ $item->title ?? 'Portfolio' }}">
                    </div>
                    @if($item->title)
                        <p class="portfolio-caption">{{ $item->title }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4 text-muted">
                <i class="fas fa-images fa-3x mb-3 d-block" style="opacity:0.2"></i>
                <p class="mb-0 small">No portfolio images yet</p>
            </div>
        @endif
    </div>

        <div class="order-card">
        <h5><i class="fas fa-scissors me-2"></i>Ready to Order?</h5>
        <p>Place your order with <strong>{{ $tailor->user->name }}</strong></p>

        @auth
            @if(auth()->user()->role === 'customer')
                @if($tailor->available_slots > 0)
                    <a href="{{ route('customer.order.form') }}?tailor_id={{ $tailor->id }}" class="btn-order">
                        <i class="fas fa-shopping-bag"></i> Place Order Now
                    </a>
                @else
                    <button class="btn-order-disabled" disabled>
                        <i class="fas fa-times-circle me-2"></i>
                        Tailor is Currently Full
                    </button>
                    <p class="mt-3 mb-0" style="color:rgba(255,255,255,0.6);font-size:0.85rem">
                        This tailor has no available slots right now. Please check back later.
                    </p>
                @endif
            @elseif(auth()->user()->role === 'tailor')
                <p style="color:rgba(255,255,255,0.7)">Tailors cannot place orders.</p>
            @endif
        @else
            <a href="{{ route('customer.order.form') }}?tailor_id={{ $tailor->id }}" class="btn-order">
                <i class="fas fa-sign-in-alt"></i> Login to Place Order
            </a>
            <p class="mt-3 mb-0" style="color:rgba(255,255,255,0.6);font-size:0.85rem">
                <i class="fas fa-lock me-1"></i> Login or register to place an order
            </p>
        @endauth
    </div>

<footer>
    <div class="container text-center">
        <small class="text-white-50">
            &copy; 2026 Stitchify. All Rights Reserved.
        </small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/tailor-public-profile.js') }}"></script>
</body>
</html>