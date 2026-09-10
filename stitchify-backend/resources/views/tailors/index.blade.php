<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <title>Find a Tailor - Stitchify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tailors-index.css') }}" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Stitchify" height="55">
        </a>
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact Us</a></li>
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

<div class="page-hero">
    <div class="container">
        <h1><i class="fas fa-scissors me-2"></i>Find Your Tailor</h1>
        <p>Browse skilled tailors and place your order in minutes</p>

        <div class="search-wrap">
            <input type="text"
                   id="searchInput"
                   placeholder="Search by name, city, or specialization..."
                   oninput="filterTailors()">
            <i class="fas fa-search"></i>
        </div>
    </div>
</div>

<div class="container pb-5">

    <div class="results-bar">
        <span class="results-count" id="resultsCount">
            {{ $tailors->count() }} tailor(s) found
        </span>
     
        <div>
            <input type="checkbox" id="availableOnly"
                   class="form-check-input me-1"
                   onchange="filterTailors()">
            <label for="availableOnly"
                   class="form-check-label"
                   style="font-size:14px;color:var(--copyright-bg)">
                Available only
            </label>
        </div>
    </div>

    <div class="row g-4" id="tailorGrid">

        @forelse($tailors as $tailor)
        <div class="col-md-6 col-lg-4 tailor-item"
             data-name="{{ strtolower($tailor->user->name) }}"
             data-city="{{ strtolower($tailor->city ?? '') }}"
             data-spec="{{ strtolower($tailor->specialization ?? '') }}"
             data-slots="{{ $tailor->available_slots }}">

            <div class="tailor-card h-100">

                <div class="card-top">
                    <img src="{{ $tailor->user->profile_image
                                ? Storage::url($tailor->user->profile_image)
                                : asset('images/default-avatar.png') }}"
                         alt="{{ $tailor->user->name }}"
                         class="tailor-avatar">

                    <div class="card-top-info">
                        <h5>{{ $tailor->user->name }}</h5>
                        <span class="specialization-badge">
                            <i class="fas fa-tag me-1"></i>
                            {{ $tailor->specialization ?? 'General Tailoring' }}
                        </span>
                        <br>
                        @if($tailor->available_slots > 0)
                            <span class="slot-badge slot-available">
                                <i class="fas fa-check-circle"></i>
                                Available
                            </span>
                        @else
                            <span class="slot-badge slot-full">
                                <i class="fas fa-times-circle"></i>
                                Full
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body-custom">

                    {{-- Stats --}}
                    <div class="stats-row">
                        <div class="stat-item">
                            <span class="stat-num">
                                {{ $tailor->experience_years ?? 0 }}
                            </span>
                            <span class="stat-lbl">Years Exp.</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-num">
                                {{ $tailor->orders()
                                          ->where('status','delivered')
                                          ->count() }}
                            </span>
                            <span class="stat-lbl">Completed</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-num">
                                {{ $tailor->available_slots }}/{{ $tailor->max_slots }}
                            </span>
                            <span class="stat-lbl">Slots</span>
                        </div>
                    </div>

                    @if($tailor->city)
                    <div class="info-row">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $tailor->city }}
                        @if($tailor->address) — {{ $tailor->address }} @endif
                    </div>
                    @endif

                    @if($tailor->base_price)
                    <div class="info-row">
                        <i class="fas fa-tag"></i>
                        Starting from Rs. {{ number_format($tailor->base_price) }}
                    </div>
                    @endif

                    @if($tailor->user->phone)
                    <div class="info-row">
                        <i class="fas fa-phone"></i>
                        {{ $tailor->user->phone }}
                    </div>
                    @endif

                    @if($tailor->bio)
                    <div class="info-row">
                        <i class="fas fa-align-left"></i>
                        {{ Str::limit($tailor->bio, 60) }}
                    </div>
                    @endif

                    <a href="/tailors/{{ $tailor->id }}"
                       class="btn-view-profile">
                        <i class="fas fa-user me-2"></i> View Profile & Order
                    </a>

                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="fas fa-user-slash"></i>
                <h5>No tailors available right now</h5>
                <p class="text-muted">Please check back later</p>
            </div>
        </div>
        @endforelse

    </div>

    <div id="noResults" class="empty-state" style="display:none;">
        <i class="fas fa-search"></i>
        <h5>No tailors found</h5>
        <p class="text-muted">Try a different search term</p>
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
<script src="{{ asset('js/tailors-index.js') }}"></script>
</body>
</html>