<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Find a Tailor - Stitchify</title>

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

        /* ── Navbar — same as public profile ── */
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

        /* ── Hero Section ── */
        .page-hero {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
            padding: 50px 0 40px;
            color: white;
            text-align: center;
            margin-bottom: 40px;
        }
        .page-hero h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .page-hero p {
            font-size: 1rem;
            opacity: 0.8;
            margin-bottom: 25px;
        }

        /* ── Search Bar ── */
        .search-wrap {
            max-width: 500px;
            margin: 0 auto;
            position: relative;
        }
        .search-wrap input {
            width: 100%;
            padding: 14px 50px 14px 20px;
            border-radius: 30px;
            border: none;
            font-size: 15px;
            outline: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .search-wrap i {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--copyright-bg);
        }

        /* ── Results count ── */
        .results-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .results-count {
            font-size: 14px;
            color: var(--copyright-bg);
        }

        /* ── Tailor Card ── */
        .tailor-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 24px;
            border: 2px solid transparent;
        }
        .tailor-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            border-color: var(--accent-color);
        }

        /* Card top — gradient header */
        .card-top {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .tailor-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.4);
            flex-shrink: 0;
        }
        .card-top-info h5 {
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0 0 4px;
        }
        .specialization-badge {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-block;
        }

        .slot-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 6px;
        }
        .slot-available {
            background: rgba(56,142,60,0.25);
            color: #4caf50;
            border: 1px solid rgba(56,142,60,0.3);
        }
        .slot-full {
            background: rgba(220,53,69,0.25);
            color: #ef5350;
            border: 1px solid rgba(220,53,69,0.3);
        }

        .card-body-custom {
            padding: 18px 20px;
        }

        .stats-row {
            display: flex;
            gap: 0;
            margin-bottom: 16px;
            background: #f8f9fa;
            border-radius: 10px;
            overflow: hidden;
        }
        .stat-item {
            flex: 1;
            text-align: center;
            padding: 12px 8px;
            border-right: 1px solid #e0e0e0;
        }
        .stat-item:last-child { border-right: none; }
        .stat-num {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--accent-color);
            display: block;
        }
        .stat-lbl {
            font-size: 0.72rem;
            color: var(--copyright-bg);
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #f5f5f5;
            font-size: 13px;
            color: #555;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row i {
            color: var(--accent-color);
            width: 16px;
            text-align: center;
            flex-shrink: 0;
        }

        .btn-view-profile {
            display: block;
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            margin-top: 16px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .btn-view-profile:hover {
            color: white;
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(27,42,74,0.3);
        }
        .btn-view-profile.disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--copyright-bg);
        }
        .empty-state i {
            font-size: 4rem;
            opacity: 0.2;
            display: block;
            margin-bottom: 20px;
        }

        footer {
            background: var(--primary-bg);
            color: white;
            padding: 20px 0;
            margin-top: 40px;
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

        {{-- Search bar --}}
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

                {{-- Card Body --}}
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

                    {{-- View Profile Button --}}
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
<script>
function filterTailors() {
    const query         = document.getElementById('searchInput').value.toLowerCase().trim();
    const availableOnly = document.getElementById('availableOnly').checked;
    const items         = document.querySelectorAll('.tailor-item');

    let visibleCount = 0;

    items.forEach(item => {
        const name  = item.dataset.name;
        const city  = item.dataset.city;
        const spec  = item.dataset.spec;
        const slots = parseInt(item.dataset.slots);

        // Search match
        const matchSearch = !query ||
            name.includes(query) ||
            city.includes(query) ||
            spec.includes(query);

        // Available filter
        const matchAvailable = !availableOnly || slots > 0;

        if (matchSearch && matchAvailable) {
            item.style.display = 'block';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    // Results count update
    document.getElementById('resultsCount').textContent =
        visibleCount + ' tailor(s) found';

    // No results message
    document.getElementById('noResults').style.display =
        visibleCount === 0 ? 'block' : 'none';
}
</script>
</body>
</html>