<!DOCTYPE html>
<html lang="en">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Login - Stitchify</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
<div class="login-wrapper auth-wrapper">
  <div class="logo-container">
    <img src="{{ asset('images/logo.png') }}" alt="Stitchify" class="logo-image">
  </div>

  <div class="form-container">
    <div class="login-header">
      <h2>Welcome Back</h2>
      <p>Login to your account</p>
    </div>

    @if(session('success'))
      <div class="success-banner">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
      @csrf
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input id="email" class="form-control" placeholder="example@gmail.com" type="email" required>
        <span id="emailError" class="error-text"></span>
      </div>

      <div class="mb-3 password-toggle">
        <label for="password" class="form-label">Password</label>
        <input id="password" class="form-control" placeholder="Enter your password" type="password" required>
        <i id="togglePassword" class="fas fa-eye toggle-icon" title="Show/hide password" role="button" aria-label="Toggle password visibility"></i>
        <span id="passwordError" class="error-text"></span>
      </div>
      <div id="loginError" style="display:none; color:red; margin-bottom:10px;"></div>

      <div class="d-flex justify-content-between align-items-center">
        <div class="remember-me">
          <input type="checkbox" id="rememberMe">
          <label for="rememberMe">Remember me</label>
        </div>
        <div class="forgot-password">
          <a href="#" id="forgotPasswordLink" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot Password?</a>
        </div>
      </div>

      <button type="submit" class="btn-login btn-primary-custom">
        <i class="fas fa-sign-in-alt"></i> Login
      </button>
    </form>

    <div class="register-link">
      Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
    </div>
  </div>
</div>

<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-content-custom">
      <div class="modal-header modal-header-custom">
        <h5 class="modal-title">Reset Password</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body modal-body-custom">
        <p style="color: var(--copyright-bg); font-size: 14px;">Enter the email address associated with your account and we will send you a link to reset your password.</p>

        <div id="forgotSuccessBox" style="display:none;" class="success-banner"></div>
        <div id="forgotErrorBox" style="display:none;" class="error-text mb-2"></div>

        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input id="forgotEmail" class="form-control" type="email" placeholder="example@gmail.com">
        </div>

        <button type="button" class="btn-modal-submit" id="forgotSubmitBtn">
          <i class="fas fa-paper-plane"></i> Send Reset Link
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/login.js') }}"></script>
</body>
</html>