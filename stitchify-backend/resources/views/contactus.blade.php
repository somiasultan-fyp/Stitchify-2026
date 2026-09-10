<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Contact Us - Stitchify</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/info-pages.css') }}">
</head>
<body>
  <header class="header">
    <div class="container">
      <div class="logo-section">
         <a href="{{ route('home') }}" class="logo-link">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
         </a>
     </div>
      <nav>
        <ul class="nav-menu">
          <li><a href="{{ route('home') }}">Home</a></li>
          <li><a href="{{ route('about') }}">About Us</a></li>
          <li><a href="{{ route('contact') }}" class="active">Contact</a></li>
          <li><a href="{{ route('login.form') }}">Login</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="main-content">
    <div class="content-card">
      <h1 class="page-title">Contact Us</h1>
      <p class="page-subtitle">We'd love to hear from you</p>

      <div class="feature-grid">
        <div class="feature-box">
          <div class="feature-icon">
            <i class="fas fa-map-marker-alt"></i>
          </div>
          <h3 class="feature-title">Visit Us</h3>
          <p class="feature-description">
             <br>
            Gujranwala, Punjab<br>
            Pakistan
          </p>
        </div>

        <div class="feature-box">
          <div class="feature-icon">
            <i class="fas fa-phone"></i>
          </div>
          <h3 class="feature-title">Call Us</h3>
          <p class="feature-description">
            <a href="tel:+923001234567">+92 3249788408</a><br>
            <a href="tel:+923001234568">+92 3356465001</a><br>
            Mon - Sat: 9 AM - 6 PM
          </p>
        </div>

        <div class="feature-box">
          <div class="feature-icon">
            <i class="fas fa-envelope"></i>
          </div>
          <h3 class="feature-title">Email Us</h3>
          <p class="feature-description">
            <a href="mailto:info@stitchify.biz">stitchify2026@gmail.com</a><br>
            24/7 Support Available
          </p>
        </div>
      </div>

      <div class="form-section">
        <h2 class="section-title">Send Us a Message</h2>

        <div id="contactSuccessBox" class="success-banner" style="display:none;"></div>

        <form id="contactForm" novalidate>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="fullName" class="form-label">Full Name *</label>
              <input type="text" class="form-control" id="fullName" placeholder="Enter your name" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="email" class="form-label">Email Address *</label>
              <input type="email" class="form-control" id="email" placeholder="your@email.com" required>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="phone" class="form-label">Phone Number *</label>
              <input type="tel" class="form-control" id="phone" placeholder="+92 300 1234567" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="subject" class="form-label">Subject *</label>
              <select class="form-select" id="subject" required>
                <option value="">Select a subject</option>
                <option value="general">General Inquiry</option>
                <option value="tailor">Tailor Registration</option>
                <option value="order">Order Related</option>
                <option value="technical">Technical Support</option>
                <option value="feedback">Feedback</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label for="message" class="form-label">Message *</label>
            <textarea class="form-control" id="message" placeholder="Write your message here..." required></textarea>
          </div>

          <button type="submit" class="btn-primary-custom">
            <i class="fas fa-paper-plane"></i> Send Message
          </button>
        </form>
      </div>
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
  <script src="{{ asset('js/contact.js') }}"></script>
</body>
</html>