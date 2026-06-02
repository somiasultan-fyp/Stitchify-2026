<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>{{ $tailor->name }} - Stitchify</title>
    
    <!-- Bootstrap & Font Awesome -->
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

        /* === Navbar (Home Page Exact Same) === */
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
            transition: all 0.3s ease;
        }
        .btn-stitchify:hover {
            background-color: #1a2c55;
            color: var(--text-white);
        }

        /* === Profile Section === */
        .profile-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            padding: 25px;
            margin: 30px 0;
        }

        .profile-image {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--accent-color);
        }

        .profile-name {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-bg);
            margin: 10px 0 5px;
        }

        .profile-category {
            display: inline-block;
            background: var(--accent-color);
            color: white;
            padding: 4px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
        }

        .detail-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child { border-bottom: none; }
        
        .detail-icon {
            width: 35px;
            height: 35px;
            background: #f0f3f8;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent-color);
            flex-shrink: 0;
        }
        .detail-label { font-size: 0.8rem; color: #888; }
        .detail-value { font-size: 0.95rem; color: #333; font-weight: 500; }

        .orders-count {
            background: #e8f4fd;
            color: var(--accent-color);
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 600;
            text-align: center;
            margin: 15px 0;
        }

        /* === Designs/Portfolio Grid === */
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--accent-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .designs-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        @media (max-width: 576px) {
            .designs-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .design-item {
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
        .design-item:hover {
            transform: scale(1.03);
            border-color: var(--accent-color);
        }
        .design-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .design-item i {
            font-size: 1.5rem;
            color: var(--accent-color);
            opacity: 0.6;
        }
        .design-item.add-new {
            background: #e8f4fd;
            border: 2px dashed var(--accent-color);
        }
        .design-item.add-new i {
            font-size: 1.2rem;
            color: var(--accent-color);
        }

        /* === Order Button === */
        .order-section {
            text-align: center;
            padding: 25px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            margin-bottom: 40px;
        }
        .btn-order {
            background: var(--accent-color);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .btn-order:hover {
            background: #1a2c55;
            color: white;
            transform: translateY(-2px);
        }

        .back-link {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin: 15px 0;
        }
        .back-link:hover { color: var(--primary-bg); }
    </style>
</head>
<body>

    <!-- === Navbar (Home Page Exact Same) === -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Stitchify Logo" height="55">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="/aboutus">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contactus">Contact Us</a></li>
                    <li class="nav-item ms-lg-3">
                        <a href="{{ route('login.form') }}" class="btn btn-stitchify">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- === Main Content === -->
    <div class="container" style="max-width: 700px;">
        
        <a href="/tailors" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Tailors
        </a>

        <!-- Profile Card -->
        <div class="profile-section text-center">
            <img src="{{ $tailor->profile_image ?? asset('images/default-avatar.png') }}" 
                 alt="{{ $tailor->name }}" 
                 class="profile-image">
            
            <div class="profile-name">{{ $tailor->name }}</div>
            
            <span class="profile-category">
                <i class="fas fa-tag me-1"></i>{{ $tailor->category ?? 'General Tailoring' }}
            </span>

            <div class="orders-count">
                <i class="fas fa-check-circle me-1"></i>
                {{ $tailor->completed_orders ?? 0 }} Orders Stitched
            </div>
        </div>

        <!-- Details Card -->
        <div class="profile-section">
            <h6 class="fw-bold mb-3" style="color: var(--accent-color);">
                <i class="fas fa-info-circle me-2"></i>Contact Details
            </h6>

            <div class="detail-row">
                <div class="detail-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="detail-label">Email</div>
                    <div class="detail-value">{{ $tailor->email }}</div>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-icon"><i class="fas fa-phone"></i></div>
                <div>
                    <div class="detail-label">Phone</div>
                    <div class="detail-value">{{ $tailor->phone ?? 'Not provided' }}</div>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <div class="detail-label">Address</div>
                    <div class="detail-value">{{ $tailor->address ?? 'Not provided' }}</div>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-icon"><i class="fas fa-layer-group"></i></div>
                <div>
                    <div class="detail-label">Slot Capacity</div>
                    <div class="detail-value">{{ $tailor->slot_capacity ?? 'N/A' }} Orders Max</div>
                </div>
            </div>
        </div>

        <!-- === ✨ DESIGNS/PORTFOLIO SECTION -->
        <div class="profile-section">
            <div class="section-title">
                <i class="fas fa-images"></i> Designs / Portfolio
            </div>
            
            <div class="designs-grid">
                @forelse($tailor->designs ?? [] as $design)
                    <div class="design-item">
                        <img src="{{ asset('storage/' . $design->image) }}" 
                             alt="Design" 
                             onclick="window.open(this.src, '_blank')">
                    </div>
                @empty
                    <!-- Placeholder Designs -->
                    <div class="design-item"><i class="fas fa-tshirt"></i></div>
                    <div class="design-item"><i class="fas fa-user-tie"></i></div>
                    <div class="design-item"><i class="fas fa-vest"></i></div>
                    <div class="design-item"><i class="fas fa-shirt"></i></div>
                @endforelse
                
                <!-- Add New Button (Tailor ke liye) -->
                @if(auth()->check() && auth()->id() === $tailor->user_id)
                    <div class="design-item add-new" title="Add Design">
                        <i class="fas fa-plus"></i>
                    </div>
                @endif
            </div>
            
            @if(empty($tailor->designs))
                <p class="text-muted small mt-3 mb-0 text-center">
                    <i class="fas fa-info-circle me-1"></i>More designs coming soon
                </p>
            @endif
        </div>

        <!-- Place Order Section (Bottom) -->
        <div class="order-section">
            <h6 class="fw-bold mb-2" style="color: var(--primary-bg);">
                <i class="fas fa-scissors me-2"></i>Ready to Order?
            </h6>
            <p class="text-muted small mb-3">Place your order with <strong>{{ $tailor->name }}</strong></p>

            @auth
                <a href="/order/{{ $tailor->id }}" class="btn-order">
                    <i class="fas fa-shopping-bag"></i> Place Order Now
                </a>
            @else
                <a href="/login?redirect=/order/{{ $tailor->id }}" class="btn-order">
                    <i class="fas fa-shopping-bag"></i> Place Order Now
                </a>
                <p class="text-muted small mt-3 mb-0">
                    <i class="fas fa-lock me-1"></i>Login required to place order
                </p>
            @endauth
        </div>

    </div>

    <!-- === Footer === -->
    <footer class="mt-auto" style="background: var(--primary-bg); color: white; padding: 30px 0 15px;">
        <div class="container text-center">
            <small class="text-white-50">&copy; 2026 Stitchify. All Rights Reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>