<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<title>Registration - Stitchify</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/common.css') }}">
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body class="auth-page">
<div class="registration-wrapper auth-wrapper">
  <div class="logo-container">
    <img src="{{ asset('images/logo.png') }}" class="logo-image" alt="Stitchify">
  </div>

  <div class="form-step" id="step1Div">
    <div class="step-indicator">
      <div class="step-dot active"></div>
      <div class="step-dot"></div>
    </div>
    <div class="registration-header">
      <h2>Create Account</h2>
    </div>

    @if($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="mb-3">
      <label class="form-label">Full Name *</label>
      <input id="s1_name" class="form-control" placeholder="Enter your full name" type="text" value="{{ old('name') }}">
      <span id="nameError" class="error-text"></span>
    </div>
    <div class="mb-3">
      <label class="form-label">Email Address *</label>
      <input id="s1_email" class="form-control" placeholder="example@gmail.com" type="email" value="{{ old('email') }}">
      <span id="emailError" class="error-text"></span>
    </div>
    <div class="mb-3">
      <label class="form-label">Phone Number *</label>
      <input id="s1_phone" class="form-control" placeholder="+92 300 1234567" type="tel" value="{{ old('phone') }}">
      <span id="phoneError" class="error-text"></span>
    </div>
    <div class="mb-3">
       <label class="form-label">Password *</label>
      <div class="password-toggle">
        <input id="s1_password" class="form-control" placeholder="Min 8 chars with letters, numbers & special chars" type="password">
        <i id="togglePassword" class="fas fa-eye toggle-icon"></i>
      </div>
        <span id="pwError" class="error-text"></span>
    </div>

    <button class="btn btn-primary-custom" type="button" onclick="goToStep2()">
      <i class="fas fa-arrow-right"></i> Continue
    </button>

    <div class="login-link">
      Already have an account? <a href="{{ route('login.form') }}">Log In</a>
    </div>
  </div>

  <div class="form-step" id="step2Div" style="display:none;">
    <div class="step-indicator">
      <div class="step-dot"></div>
      <div class="step-dot active"></div>
    </div>
    <div class="registration-header">
      <h2>Complete Profile</h2>
      <p>Tell us more about yourself</p>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm">
      @csrf
      <input type="hidden" name="name" id="f_name">
      <input type="hidden" name="email" id="f_email">
      <input type="hidden" name="phone" id="f_phone">
      <input type="hidden" name="password" id="f_password">

      <div class="mb-3">
        <label class="form-label">I am a *</label>
        <select name="role" id="role" class="form-select" required onchange="toggleTailorFields()">
          <option value="">Select your role</option>
          <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
          <option value="tailor" {{ old('role') == 'tailor' ? 'selected' : '' }}>Tailor</option>
        </select>
        <span id="roleError" class="error-text"></span>
      </div>

      <div id="tailorFields" style="display:none;">
        <div class="mb-3">
          <label class="form-label">Shop Address *</label>
          <textarea name="address" id="s2_address" class="form-control" rows="2" placeholder="Enter your shop address">{{ old('address') }}</textarea>
          <span id="addressError" class="error-text"></span>
        </div>
        <div class="mb-3">
          <label class="form-label">Specialization *</label>
          <select name="category" id="s2_category" class="form-select">
            <option value="">Select specialization</option>
            <option value="men" {{ old('category') == 'men' ? 'selected' : '' }}>Men's Clothing</option>
            <option value="women" {{ old('category') == 'women' ? 'selected' : '' }}>Women's Clothing</option>
            <option value="child" {{ old('category') == 'child' ? 'selected' : '' }}>Children's Clothing</option>
            <option value="all" {{ old('category') == 'all' ? 'selected' : '' }}>All Categories</option>
          </select>
          <span id="categoryError" class="error-text"></span>
        </div>
        <div class="mb-3">
          <label class="form-label">Slot Capacity *</label>
          <input name="slot_capacity" id="s2_slot" class="form-control" type="number" min="1" max="100" placeholder="e.g. 10" value="{{ old('slot_capacity') }}">
          <span id="slotError" class="error-text"></span>
        </div>
      </div>

      <button type="button" class="btn btn-link text-secondary mb-2" onclick="goToStep1()">
        <i class="fas fa-arrow-left"></i> Back
      </button>
      <button type="submit" class="btn btn-primary-custom" id="registerSubmitBtn">
        <i class="fas fa-user-check"></i> Register
      </button>
    </form>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/register.js') }}"></script>
</body>
</html>