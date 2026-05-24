<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Registration - Stitchify</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
:root { --primary-bg: #212529; --accent-color: #1B2A4A; --copyright-bg: #575a5b; --text-white: #ffffff; }
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; background-color: #f8f9fa; }
.registration-wrapper { width: 500px; max-width: 95%; background-color: var(--text-white); border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.5); overflow: hidden; }
.logo-container { background: linear-gradient(135deg, var(--accent-color), var(--primary-bg)); padding: 30px; text-align: center; border-bottom: 3px solid var(--copyright-bg); }
.logo-image { width: 120px; height: 120px; margin: 0 auto; display: block; object-fit: contain; border-radius: 50%; padding: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
.form-step { padding: 40px; }
.registration-header { text-align: center; margin-bottom: 30px; }
.registration-header h2 { color: var(--accent-color); font-weight: 700; font-size: 28px; margin-bottom: 8px; }
.registration-header p { color: var(--copyright-bg); font-size: 14px; }
.form-label { color: var(--primary-bg); font-weight: 600; margin-bottom: 8px; font-size: 14px; }
.form-control, .form-select { border: 2px solid #e0e0e0; border-radius: 10px; padding: 12px 15px; color: var(--primary-bg); background-color: #f8f9fa; font-size: 15px; }
.form-control:focus, .form-select:focus { border-color: var(--accent-color); box-shadow: 0 0 0 0.25rem rgba(14,24,48,0.15); background-color: var(--text-white); }
.btn-custom { background: linear-gradient(135deg, var(--accent-color), var(--primary-bg)); color: var(--text-white); border: none; border-radius: 10px; padding: 14px; font-weight: 600; font-size: 16px; width: 100%; margin-top: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
.btn-custom:hover { opacity: 0.9; color: var(--text-white); }
.login-link { text-align: center; margin-top: 20px; color: var(--copyright-bg); font-size: 14px; }
.login-link a { color: var(--accent-color); text-decoration: none; font-weight: 600; }
.password-toggle { position: relative; }
.toggle-icon { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: var(--copyright-bg); opacity: 0.6; }
.step-indicator { display: flex; justify-content: center; gap: 10px; margin-bottom: 20px; }
.step-dot { width: 12px; height: 12px; border-radius: 50%; background-color: #e0e0e0; transition: all 0.3s; }
.step-dot.active { background-color: var(--accent-color); width: 30px; border-radius: 6px; }
</style>
</head>
<body>
<div class="registration-wrapper">
  <div class="logo-container">
    <img src="{{ asset('images/logo.png') }}" class="logo-image" alt="Stitchify">
  </div>

  {{-- Step 1 --}}
  <div class="form-step" id="step1Div">
    <div class="step-indicator">
      <div class="step-dot active"></div>
      <div class="step-dot"></div>
    </div>
    <div class="registration-header">
      <h2>Create Account</h2>
      <p>Join our tailoring community</p>
    </div>

    @if($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="mb-3">
      <label class="form-label">Full Name *</label>
      <input id="s1_name" class="form-control" placeholder="Enter your full name" type="text" value="{{ old('name') }}">
    </div>
    <div class="mb-3">
      <label class="form-label">Email Address *</label>
      <input id="s1_email" class="form-control" placeholder="example@gmail.com" type="email" value="{{ old('email') }}">
      <span id="passwordError" class="text-danger small"></span>
    </div>
    <div class="mb-3">
      <label class="form-label">Phone Number *</label>
      <input id="s1_phone" class="form-control" placeholder="+92 300 1234567" type="tel" value="{{ old('phone') }}">
    </div>
    <div class="mb-3 password-toggle">
      <label class="form-label">Password *</label>
      <input id="s1_password" class="form-control" placeholder="Min 8 chars with letters, numbers & special chars" type="password">
      <i id="togglePassword" class="fas fa-eye toggle-icon"></i>
      <span id="pwError" class="text-danger small"></span>
    </div>

    <button class="btn btn-custom" type="button" onclick="goToStep2()">
      <i class="fas fa-arrow-right"></i> Continue
    </button>

    <div class="login-link">
      Already have an account? <a href="{{ route('login.form') }}">Log In</a>
    </div>
  </div>

  {{-- Step 2 --}}
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
      </div>

      <div id="tailorFields" style="display:none;">
        <div class="mb-3">
          <label class="form-label">Shop Address *</label>
          <textarea name="address" class="form-control" rows="2" placeholder="Enter your shop address">{{ old('address') }}</textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Specialization *</label>
          <select name="category" class="form-select">
            <option value="">Select specialization</option>
            <option value="men" {{ old('category') == 'men' ? 'selected' : '' }}>Men's Clothing</option>
            <option value="women" {{ old('category') == 'women' ? 'selected' : '' }}>Women's Clothing</option>
            <option value="child" {{ old('category') == 'child' ? 'selected' : '' }}>Children's Clothing</option>
            <option value="all" {{ old('category') == 'all' ? 'selected' : '' }}>All Categories</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Slot Capacity *</label>
          <input name="slot_capacity" class="form-control" type="number" min="1" max="100" placeholder="e.g. 10" value="{{ old('slot_capacity') }}">
        </div>
      </div>

      <button type="button" class="btn btn-link text-secondary mb-2" onclick="goToStep1()">
        <i class="fas fa-arrow-left"></i> Back
      </button>
      <button type="submit" class="btn btn-custom">
        <i class="fas fa-user-check"></i> Register
      </button>
    </form>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*()_+{}:"<>?~]).{8,}$/;

function goToStep2() {
  const name     = document.getElementById('s1_name').value.trim();
  const email    = document.getElementById('s1_email').value.trim();
  const phone    = document.getElementById('s1_phone').value.trim();
  const password = document.getElementById('s1_password').value;

  if (!name)  { alert('Name is required.'); return; }
  if (!email || !email.includes('@')) { alert('Valid email is required.'); return; }
  if (!phone) { alert('Phone is required.'); return; }
  if (!passwordRegex.test(password)) {
    document.getElementById('pwError').textContent = 'Password must be at least 8 characters with letters, numbers & special chars (!@#$%^&*)';
    return;
  }
  document.getElementById('pwError').textContent = '';

  document.getElementById('f_name').value     = name;
  document.getElementById('f_email').value    = email;
  document.getElementById('f_phone').value    = phone;
  document.getElementById('f_password').value = password;

  document.getElementById('step1Div').style.display = 'none';
  document.getElementById('step2Div').style.display = 'block';
}

function goToStep1() {
  document.getElementById('step2Div').style.display = 'none';
  document.getElementById('step1Div').style.display = 'block';
}

function toggleTailorFields() {
  const role = document.getElementById('role').value;
  document.getElementById('tailorFields').style.display = role === 'tailor' ? 'block' : 'none';
}

const toggle = document.getElementById('togglePassword');
const pwd    = document.getElementById('s1_password');
toggle.addEventListener('click', () => {
  if (pwd.type === 'password') { pwd.type = 'text'; toggle.classList.replace('fa-eye','fa-eye-slash'); }
  else { pwd.type = 'password'; toggle.classList.replace('fa-eye-slash','fa-eye'); }
});
</script>
</body>
</html>