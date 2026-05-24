<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $tailor->user->name }} - Stitchify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-bg: #212529;
            --accent-color: #1B2A4A;
            --copyright-bg: #575a5b;
            --text-white: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: var(--primary-bg) !important;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: var(--text-white) !important;
        }
        .btn-stitchify {
            background-color: var(--accent-color);
            color: var(--text-white);
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-weight: 600;
        }
        .btn-stitchify:hover {
            background-color: #142038;
            color: var(--text-white);
            transform: translateY(-1px);
        }

        .back-link {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin: 20px 0 10px;
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--primary-bg); }

        .profile-hero {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
            border-radius: 16px;
            padding: 30px;
            color: white;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.4);
            flex-shrink: 0;
        }
        .profile-hero-info h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 6px;
        }
        .profile-hero-info .badge-category {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-block;
            margin-bottom: 10px;
        }
        .profile-hero-stats {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .hero-stat {
            text-align: center;
        }
        .hero-stat .num {
            font-size: 1.3rem;
            font-weight: 700;
            display: block;
        }
        .hero-stat .lbl {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .slot-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 8px;
        }
        .slot-available {
            background: rgba(56,142,60,0.2);
            color: #4caf50;
            border: 1px solid rgba(56,142,60,0.3);
        }
        .slot-full {
            background: rgba(220,53,69,0.2);
            color: #ef5350;
            border: 1px solid rgba(220,53,69,0.3);
        }

        .info-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }
        .card-title-custom {
            font-size: 1rem;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
        }

        .detail-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid #f5f5f5;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-icon {
            width: 38px;
            height: 38px;
            background: #f0f3f8;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-color);
            flex-shrink: 0;
        }
        .detail-label { font-size: 0.78rem; color: #999; margin-bottom: 2px; }
        .detail-value { font-size: 0.95rem; color: #333; font-weight: 500; }

        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        @media (max-width: 576px) {
            .portfolio-grid { grid-template-columns: repeat(2, 1fr); }
        }
        .portfolio-item {
            aspect-ratio: 1;
            border-radius: 10px;
            overflow: hidden;
            background: #f0f3f8;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s;
            border: 2px solid transparent;
        }
        .portfolio-item:hover {
            transform: scale(1.03);
            border-color: var(--accent-color);
        }
        .portfolio-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .portfolio-item i {
            font-size: 2rem;
            color: var(--accent-color);
            opacity: 0.3;
        }
        .portfolio-caption {
            font-size: 0.75rem;
            color: #666;
            text-align: center;
            margin-top: 4px;
        }

        .order-card {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            margin-bottom: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .order-card h5 {
            color: white;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .order-card p {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        .btn-order {
            background: white;
            color: var(--accent-color);
            border: none;
            padding: 12px 40px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .btn-order:hover {
            background: #f0f3f8;
            color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .btn-order-disabled {
            background: rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.6);
            border: none;
            padding: 12px 40px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: not-allowed;
        }

        footer {
            background: var(--primary-bg);
            color: white;
            padding: 20px 0;
        }
    </style>
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
                        {{-- Role ke hisaab se dashboard --}}
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

<div class="container" style="max-width: 750px;">

    <a href="/tailors" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Tailors
    </a>

    <div class="profile-hero">
        <img src="{{ $tailor->user->profile_image
                    ? Storage::url($tailor->user->profile_image)
                    : asset('images/default-avatar.png') }}"
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
                         onclick="window.open('{{ Storage::url($item->image_path) }}', '_blank')">
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
                    {{-- Tailor ID pass karo order form mein --}}
                    <a href="/customer/order-form?tailor_id={{ $tailor->id }}"
                       class="btn-order">
                        <i class="fas fa-shopping-bag"></i> Place Order Now
                    </a>
                @else
                    <button class="btn-order-disabled" disabled>
                        <i class="fas fa-times-circle me-2"></i>
                        Tailor is Currently Full
                    </button>
                    <p class="mt-3 mb-0" style="color:rgba(255,255,255,0.6);font-size:0.85rem">
                        This tailor has no available slots right now.
                        Please check back later.
                    </p>
                @endif
            @elseif(auth()->user()->role === 'tailor')
                <p style="color:rgba(255,255,255,0.7)">
                    Tailors cannot place orders.
                </p>
            @endif
        @else
            <a href="/login?redirect=/tailors/{{ $tailor->id }}"
               class="btn-order">
                <i class="fas fa-sign-in-alt"></i> Login to Place Order
            </a>
            <p class="mt-3 mb-0" style="color:rgba(255,255,255,0.6);font-size:0.85rem">
                <i class="fas fa-lock me-1"></i>
                Login or register to place an order
            </p>
        @endauth
    </div>

</div>

<footer>
    <div class="container text-center">
        <small class="text-white-50">
            &copy; 2026 Stitchify. All Rights Reserved.
        </small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>