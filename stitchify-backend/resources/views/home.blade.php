<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <title>Stitchify - Online Tailoring Service</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
</head>
<body>

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
                    <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact Us</a></li>
                    <li class="nav-item ms-lg-3">
                        @auth
                            @if(auth()->user()->role === 'customer')
                                <a href="/customer/dashboard" class="btn btn-stitchify">Dashboard</a>
                            @elseif(auth()->user()->role === 'tailor')
                                <a href="/tailor/dashboard" class="btn btn-stitchify">Dashboard</a>
                            @elseif(auth()->user()->role === 'admin')
                                <a href="/admin/dashboard" class="btn btn-stitchify">Dashboard</a>
                            @endif
                        @else
                            <a href="/login" class="btn btn-stitchify">Login</a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section d-flex align-items-center"
    style="background: linear-gradient(rgba(33, 37, 41, 0.4), rgba(33, 37, 41, 0.4)), url({{asset('images/background.png')}}); background-size: cover; background-position: center; min-height: 500px;">
        <div class="container">
            <h1 class="display-3 fw-bold mb-4">Your Style, Our Stitch</h1>
            <p class="lead mb-5">Experience the future of online tailoring. Custom designs, expert tailors, and doorstep delivery.</p>
           <a href="/tailors" class="btn btn-stitchify btn-lg">Explore Now</a>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container text-center">
            <h2 class="mb-5" style="color: var(--primary-bg);">How it Works</h2>
            <div class="row g-4 justify-content-center">
                <div class="col-md-3">
                    <div class="step-circle">
                        <i class="fa-solid fa-user-plus fa-2x mb-2"></i>
                        <h5>Register</h5>
                    </div>
                    <p> Create your account with basic details to get started.</p>
                </div>
                <div class="col-md-3">
                    <div class="step-circle">
                        <i class="fa-solid fa-shopping-cart fa-2x mb-2"></i>
                        <h5>Place Order</h5>
                    </div>
                    <p>Select your preferred design, enter fabric details, and provide your measurements for perfect fit.</p>
                </div>
                <div class="col-md-3">
                    <div class="step-circle">
                        <i class="fa-solid fa-scissors fa-2x mb-2"></i>
                        <h5>Stitch in progress</h5>
                    </div>
                    <p>We will stitch the design of your choice.</p>
                </div>
                <div class="col-md-3">
                    <div class="step-circle">
                        <i class="fa-solid fa-truck fa-2x mb-2"></i>
                        <h5>Delivery</h5>
                    </div>
                    <p>Track your order and receive your outfit at your doorstep.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="categories" class="py-5" style="background-color: #f0f0f0;">
        <div class="container text-center">
            <h2 class="mb-5" style="color: var(--primary-bg);">Browse Categories</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Men's Wear</h5>
                            <a href="{{ route('tailors.category', 'men') }}" class="btn btn-stitchify">View Category</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Women's Wear</h5>
                            <a href="{{ route('tailors.category', 'women') }}" class="btn btn-stitchify">View Category</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Kids' Wear</h5>
                            <a href="{{ route('tailors.category', 'kids') }}" class="btn btn-stitchify">View Category</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
           
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 style="color: var(--primary-bg);">How to Measure?</h2>
                    <p>Watch our step-by-step guide to get the perfect measurements for your outfit.</p>
                    <button type="button" class="btn btn-stitchify mt-3" data-bs-toggle="modal" data-bs-target="#videoModal">
                        Watch Video
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="videoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-stitchify-dark">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white">How to Measure</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="ratio ratio-16x9">
                        <video controls>
                            <source src="{{ asset('video/measurements.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5" style="color: var(--primary-bg);">Professional Tailors</h2>
            <div class="row g-4">
                @forelse($topTailors as $tailor)
                @php
                    $averageRating = $tailor->reviews()->avg('rating') ?? 0;
                    $totalReviews = $tailor->reviews()->count();
                @endphp
                <div class="col-md-4">
                    <div class="card tailor-card h-100 p-3">
                        <div class="tailor-card-header text-center">
                            @if($tailor->user->profile_image)
                                <img src="{{ Storage::url($tailor->user->profile_image) }}"
                                     alt="{{ $tailor->user->name }}"
                                     class="tailor-avatar">
                            @else
                                <i class="fa-solid fa-user-tie fa-4x mb-3 text-white"></i>
                            @endif
                            
                            <div class="rating-badge">
                                <i class="fas fa-star"></i>
                                <span>{{ number_format($averageRating, 1) }}</span>
                                <small>({{ $totalReviews }})</small>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $tailor->user->name }}</h5>
                            <p class="tailor-category">
                                @if($tailor->specialization == 'all')
                                    All Categories
                                @elseif($tailor->specialization == 'men')
                                    Men's Wear
                                @elseif($tailor->specialization == 'women')
                                    Women's Wear
                                @elseif($tailor->specialization == 'kids')
                                    Kids' Wear
                                @else
                                    {{ ucfirst($tailor->specialization ?? 'General Tailoring') }}
                                @endif
                                </p>
                            <p class="card-text small">
                                <i class="fas fa-star me-1"></i>{{ $tailor->experience_years ?? 0 }} yrs experience
                                &nbsp;|&nbsp;
                                <i class="fas fa-check-circle me-1"></i>{{ $tailor->orders()->where('status','delivered')->count() }} completed
                            </p>
                            @if($tailor->city)
                                <p class="card-text small"><i class="fas fa-map-marker-alt me-1"></i>{{ $tailor->city }}</p>
                            @endif
                            <a href="{{ route('tailors.show', $tailor->id) }}" class="btn btn-stitchify">View Profile</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted">
                    <p>No tailors available right now.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5" style="color: var(--primary-bg);">Our Services</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="shadow rounded overflow-hidden">
                        <img src="{{ asset('images/stitching.jpeg') }}" alt="Stitching" class="service-img">
                        <div class="p-3 bg-stitchify-dark">
                            <h5 class="mb-0">Stitching</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="shadow rounded overflow-hidden">
                        <img src="{{ asset('images/design2.jpeg') }}" alt="Designing" class="service-img">
                        <div class="p-3 bg-stitchify-dark">
                            <h5 class="mb-0">Designing</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="shadow rounded overflow-hidden">
                        <img src="{{ asset('images/alteration.jpeg') }}" alt="Alteration" class="service-img">
                        <div class="p-3 bg-stitchify-dark">
                            <h5 class="mb-0">Alteration</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center mb-5" style="color: var(--primary-bg);">Customer Reviews</h2>

            @if($reviews->count() > 0)
                <div id="reviewsCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($reviews as $index => $review)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <div class="d-flex justify-content-center">
                                    <div class="review-card col-md-8 text-center">
                                        <div class="stars mb-3">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                        <p>"{{ $review->comment }}"</p>
                                        <h6 class="mt-3">- {{ $review->user->name }}</h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#reviewsCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#reviewsCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                    </button>
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-comment-slash fa-3x mb-3 d-block" style="opacity:0.3;"></i>
                    <p>No reviews yet. Be the first to share your experience!</p>
                </div>
            @endif
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container col-md-8">
            <h2 class="text-center mb-5" style="color: var(--primary-bg);">Frequently Asked Questions</h2>
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            How do I place an order?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Register on our website, select tailor of your category, fill the order form, and track your order.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            What are the delivery charges?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Delivery charges vary based on your location.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                            Do you pick my fabric from home?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, we will pick up the fabric from your location if you want to use the delivery service.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container pb-4">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4 class="text-white mb-3">Stitchify</h4>
                    <p class="text-muted text-white-50">Stitchify is your premium online tailoring partner. We bring the tailor shop to your doorstep with guaranteed quality and speed.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="text-white mb-3">Contact Info</h5>
                    <p class="text-white-50"><i class="fas fa-envelope me-2"></i> stitchify2026@gmail.com</p>
                    <p class="text-white-50"><i class="fas fa-phone me-2"></i> +92 3249788408</p>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            <div class="container">
                <small class="text-white">&copy; 2026 Stitchify. All Rights Reserved.</small>
            </div>
        </div>
    </footer>

    <button id="chatToggle"
        style="position:fixed; bottom:24px; right:24px; z-index:9999;
               width:56px; height:56px; border-radius:50%; border:none;
               background:linear-gradient(135deg,#1B2A4A,#212529);
               color:white; font-size:22px; cursor:pointer;
               box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
      <i class="fas fa-robot" id="chatIcon"></i>
    </button>

    <div id="chatWindow"
         style="display:none; position:fixed; bottom:90px; right:24px;
                z-index:9998; width:340px; height:480px;
                background:white; border-radius:16px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                flex-direction:column; overflow:hidden;">

      <div style="background:linear-gradient(135deg,#1B2A4A,#212529);
                  padding:16px; color:white; display:flex;
                  align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:10px">
          <div style="width:36px;height:36px;border-radius:50%;
                      background:rgba(255,255,255,0.2);
                      display:flex;align-items:center;justify-content:center">
            <i class="fas fa-robot"></i>
          </div>
          <div>
            <div style="font-weight:600; font-size:14px">Stitch</div>
            <div style="font-size:11px; opacity:0.8">Always here to help</div>
          </div>
        </div>
        <button onclick="toggleChat()"
                style="background:none;border:none;color:white;font-size:18px;cursor:pointer;">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <div id="chatMessages"
           style="flex:1; overflow-y:auto; padding:16px;
                  display:flex; flex-direction:column; gap:10px;
                  background:#f8f9fa;">
        <div style="display:flex; gap:8px; align-items:flex-start">
          <div style="width:28px;height:28px;border-radius:50%;
                      background:#1B2A4A;display:flex;align-items:center;
                      justify-content:center;flex-shrink:0">
            <i class="fas fa-robot text-white" style="font-size:12px"></i>
          </div>
          <div style="background:white; padding:10px 14px;
                      border-radius:0 12px 12px 12px;
                      font-size:13px; max-width:80%;
                      box-shadow:0 1px 3px rgba(0,0,0,0.1)">
            Hi! I'm Stitch, your Stitchify assistant. How can I help you today?
          </div>
        </div>
      </div>

      <div style="padding:12px; border-top:1px solid #e0e0e0;
                  background:white; display:flex; gap:8px">
        <input type="text" id="chatInput"
               placeholder="Type your message..."
               style="flex:1; border:2px solid #e0e0e0; border-radius:20px;
                      padding:8px 14px; font-size:13px; outline:none;"
               onkeypress="if(event.key==='Enter') sendMessage()">
        <button onclick="sendMessage()"
                style="width:38px;height:38px;border-radius:50%;
                       background:linear-gradient(135deg,#1B2A4A,#212529);
                       border:none;color:white;cursor:pointer;
                       display:flex;align-items:center;justify-content:center"
                id="sendBtn">
          <i class="fas fa-paper-plane" style="font-size:14px"></i>
        </button>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>