<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>About Us - Stitchify</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/info-pages.css') }}">
</head>
<body>
  <header class="header">
    <div class="container">
      <div class="logo-section">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
        <h1 class="site-title">Stitchify</h1>
      </div>
      <nav>
        <ul class="nav-menu">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('about') }}" class="active">About Us</a></li>
          <li><a href="{{ route('contact') }}">Contact</a></li>
          <li><a href="{{ route('login.form') }}">Login</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="main-content">
    <div class="content-card">
      <h1 class="page-title">About Us</h1>
      <p class="page-subtitle">Crafting Excellence in Every Stitch</p>

      <p class="content-text">
        Welcome to our tailoring services platform, where tradition meets innovation. We are dedicated to connecting skilled tailors with customers who value quality craftsmanship and personalized service. Our platform brings together a community of experienced professionals who are passionate about creating perfectly fitted garments.
      </p>

      <h2 class="section-title">Our Story</h2>
      <p class="content-text">
        Founded with a vision to revolutionize the tailoring industry, we recognized the need for a platform that bridges the gap between talented tailors and customers seeking bespoke clothing solutions. What started as a simple idea has grown into a thriving community of artisans and fashion enthusiasts.
      </p>

      <p class="content-text">
        We believe that everyone deserves clothes that fit perfectly and reflect their personal style. Our mission is to make custom tailoring accessible, convenient, and reliable for everyone while empowering tailors to grow their businesses and showcase their craftsmanship.
      </p>

      <h2 class="section-title">What We Offer</h2>
      <div class="feature-grid">
        <div class="feature-box">
          <div class="feature-icon">
            <i class="fas fa-cut"></i>
          </div>
          <h3 class="feature-title">Expert Tailors</h3>
          <p class="feature-description">Connect with skilled tailors specializing in men's, women's, and children's clothing.</p>
        </div>

        <div class="feature-box">
          <div class="feature-icon">
            <i class="fas fa-ruler"></i>
          </div>
          <h3 class="feature-title">Custom Fitting</h3>
          <p class="feature-description">Get perfectly fitted garments tailored to your exact measurements and preferences.</p>
        </div>

        <div class="feature-box">
          <div class="feature-icon">
            <i class="fas fa-shipping-fast"></i>
          </div>
          <h3 class="feature-title">Fast Delivery</h3>
          <p class="feature-description">Reliable delivery service to ensure your garments reach you on time.</p>
        </div>

        <div class="feature-box">
          <div class="feature-icon">
            <i class="fas fa-star"></i>
          </div>
          <h3 class="feature-title">Quality Assured</h3>
          <p class="feature-description">We work only with verified tailors who maintain the highest standards of quality.</p>
        </div>
      </div>

      <h2 class="section-title">Our Values</h2>
      <p class="content-text">
        <strong>Quality:</strong> We are committed to maintaining the highest standards in craftsmanship and service delivery.
      </p>
      <p class="content-text">
        <strong>Trust:</strong> Building lasting relationships based on reliability, transparency, and integrity.
      </p>
      <p class="content-text">
        <strong>Innovation:</strong> Continuously improving our platform to provide the best experience for our community.
      </p>
      <p class="content-text">
        <strong>Community:</strong> Supporting and empowering tailors while serving customers with dedication and care.
      </p>

      <h2 class="section-title">Join Our Community</h2>
      <p class="content-text">
        Whether you're a customer looking for the perfect fit or a tailor seeking to expand your business, we invite you to join our growing community. Together, we're creating a new standard in personalized tailoring services.
      </p>
    </div>
  </main>

  <footer class="footer">
    <p>&copy; 2026 Stitchify. All rights reserved.</p>
    <p class="footer-links" style="margin-top: 10px;">
      <a href="{{ route('privacy') }}">Privacy Policy</a> |
      <a href="{{ route('terms') }}">Terms & Conditions</a>
    </p>
  </footer>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>