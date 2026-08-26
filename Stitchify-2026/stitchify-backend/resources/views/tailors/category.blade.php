<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<title>{{ ucfirst($category) }}'s Wear Tailors - Stitchify</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
:root {
    --primary-bg: #212529;
    --accent-color: #1B2A4A;
    --text-white: #ffffff;
}
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
.navbar { background-color: var(--primary-bg) !important; }
.btn-stitchify {
    background-color: var(--accent-color); color: var(--text-white);
    border: none; padding: 10px 25px; transition: all 0.3s ease;
}
.btn-stitchify:hover { background-color: #1a2c55; color: var(--text-white); }
.page-header {
    background-color: var(--primary-bg);
    color: white; padding: 60px 0; text-align: center;
}
.tailor-card {
    background: white; border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    padding: 25px; text-align: center;
    transition: all 0.3s ease;
}
.tailor-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
.tailor-avatar {
    width: 80px; height: 80px; border-radius: 50%;
    background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
    color: white; display: flex; align-items: center;
    justify-content: center; font-size: 32px;
    margin: 0 auto 15px;
}
.tailor-name { color: var(--accent-color); font-size: 18px; font-weight: 700; margin-bottom: 5px; }
.tailor-specialty { color: #777; font-size: 14px; margin-bottom: 10px; }
.category-badge {
    display: inline-block; padding: 4px 12px;
    border-radius: 20px; font-size: 12px; font-weight: 600;
    background-color: #e3f2fd; color: #1565c0; margin-bottom: 15px;
}
.footer-copyright { background-color: #575a5b; padding: 15px 0; text-align: center; }
</style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/logo.png') }}" alt="Stitchify Logo" height="55">
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('about') }}">About Us</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="{{ route('contact') }}">Contact Us</a></li>
                <li class="nav-item ms-lg-3">
                    @auth
                        <a href="/{{ auth()->user()->role }}/dashboard" class="btn btn-stitchify">Dashboard</a>
                    @else
                        <a href="/login" class="btn btn-stitchify">Login</a>
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Page Header --}}
<div class="page-header">
    <div class="container">
        <h1 class="fw-bold">{{ ucfirst($category) }}'s Wear Tailors</h1>
        <p class="lead opacity-75 mb-4">Find the best tailors for {{ $category }}'s clothing</p>
        {{-- Category Filter Buttons --}}
        <a href="{{ route('tailors.category', 'men') }}"
           class="btn {{ $category === 'men' ? 'btn-light' : 'btn-outline-light' }} me-2">
            <i class="fas fa-male me-1"></i> Men's
        </a>
        <a href="{{ route('tailors.category', 'women') }}"
           class="btn {{ $category === 'women' ? 'btn-light' : 'btn-outline-light' }} me-2">
            <i class="fas fa-female me-1"></i> Women's
        </a>
        <a href="{{ route('tailors.category', 'kids') }}"
           class="btn {{ $category === 'kids' ? 'btn-light' : 'btn-outline-light' }}">
            <i class="fas fa-child me-1"></i> Kids'
        </a>
    </div>
</div>

{{-- Tailors List --}}
<section class="py-5">
    <div class="container">
        @if($tailors->count() > 0)
            <p class="text-muted mb-4">
                <strong>{{ $tailors->count() }}</strong> tailor(s) found for
                <strong>{{ ucfirst($category) }}'s Wear</strong>
            </p>
            <div class="row g-4">
                @foreach($tailors as $tailor)
                <div class="col-md-4">
                    <div class="tailor-card">
                        <div class="tailor-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <h5 class="tailor-name">{{ $tailor->user->name ?? 'N/A' }}</h5>
                        <p class="tailor-specialty">
                            {{ $tailor->specialization ?? 'General Tailoring' }}
                        </p>
                        <span class="category-badge">
                            @if($tailor->category === 'all')
                                <i class="fas fa-layer-group me-1"></i> All Categories
                            @else
                                {{ ucfirst($tailor->category) }}'s Wear
                            @endif
                        </span>
                        <br>
                        <a href="{{ route('customer.orders.create', ['tailor_id' => $tailor->id]) }}"
                           class="btn btn-stitchify btn-sm mt-2">
                            <i class="fas fa-plus me-1"></i> Place Order
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-cut fa-4x text-muted mb-4 d-block"></i>
                <h4 class="text-muted">No tailors available for {{ ucfirst($category) }}'s Wear</h4>
                <a href="/" class="btn btn-stitchify mt-3">
                    <i class="fas fa-home me-1"></i> Go Back Home
                </a>
            </div>
        @endif
    </div>
</section>

<div class="footer-copyright">
    <small class="text-white">&copy; 2026 Stitchify. All Rights Reserved.</small>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>