<!DOCTYPE html>
<html lang="en">

<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Registration - Stitchify</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>

:root {
  --primary-bg: #212529;
  --accent-color: #1B2A4A;
  --copyright-bg: #575a5b;
  --text-white: #ffffff;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.registration-wrapper {
  width: 500px;
  max-width: 95%;
  background-color: var(--text-white);
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.5);
  overflow: hidden;
}

.logo-container {
  background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
  padding: 30px;
  text-align: center;
  border-bottom: 3px solid var(--copyright-bg);
}

.logo-image {
  width: 120px;
  height: 120px;
  margin: 0 auto;
  display: block;
  object-fit: contain;
  border-radius: 50%;
  padding: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.form-container {
  background-color: var(--text-white);
  display: flex;
  width: 200%;
  transition: transform 0.5s ease;
}

.form-container.active {
  transform: translateX(-50%);
}

.form-step {
  width: 50%;
  padding: 40px;
  box-sizing: border-box;
}

.registration-header {
  text-align: center;
  margin-bottom: 30px;
}

.registration-header h2 {
  color: var(--accent-color);
  font-weight: 700;
  font-size: 28px;
  margin-bottom: 8px;
}

.registration-header p {
  color: var(--copyright-bg);
  font-size: 14px;
}

.form-label {
  color: var(--primary-bg);
  font-weight: 600;
  margin-bottom: 8px;
  font-size: 14px;
}

.form-control,
.form-select {
  border: 2px solid #e0e0e0;
  border-radius: 10px;
  padding: 12px 15px;
  font-size: 14px;
  background-color: #f8f9fa;
  transition: border-color 0.3s, box-shadow 0.3s;
}

.form-control:focus,
.form-select:focus {
  border-color: var(--accent-color);
  box-shadow: 0 0 0 0.25rem rgba(14,24,48,0.15);
  background-color: white;
}

.btn-custom {
  background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
  color: white;
  border: none;
  border-radius: 10px;
  padding: 14px;
  width: 100%;
  font-weight: 600;
  font-size: 14px;
  margin-top: 10px;
  transition: transform 0.3s ease;
}

.btn-custom:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.btn-nav {
  background-color: transparent;
  border: none;
  background: transparent;
  color: var(--accent-color);
  margin-bottom: 10px;
}

.password-toggle {
  position: relative;
}

.toggle-icon {
  position: absolute;
  right: 15px;
  top: 52%;
  cursor: pointer;
  color: gray;
}

.error-text {
  color: red;
  font-size: 0.85rem;
}

.step-indicator {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-bottom: 20px;
}

.step-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background-color: #ddd;
}

.step-dot.active {
  background-color: var(--accent-color);
  width: 30px;
  border-radius: 10px;
}

.login-link {
  text-align: center;
  margin-top: 20px;
}

.login-link a {
  color: var(--accent-color);
  text-decoration: none;
  font-weight: 600;
}

</style>
</head>

<body>

<div class="registration-wrapper">

  <div class="logo-container">
    <img src="{{ asset('images/logo.png') }}" class="logo-image">
  </div>

  <!-- SINGLE FORM -->
  <form method="POST" action="{{ route('register') }}" id="registerForm">

    @csrf

    <div class="form-container" id="formWrapper">

      <!-- STEP 1 -->
      <div class="form-step">

        <div class="step-indicator">
          <div class="step-dot active"></div>
          <div class="step-dot"></div>
        </div>

        <div class="registration-header">
          <h2>Create Account</h2>
          <p>Join our tailoring community</p>
        </div>
<form method="POST" action="{{ route('register') }}" id="step1Form" novalidate>
      @csrf
        <div class="mb-3">
          <label class="form-label">Full Name *</label>
          <input
            type="text"
            id="name"
            name="name"
            class="form-control"
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label">Email *</label>
          <input
            type="email"
            id="email"
            name="email"
            class="form-control"
            required
          >
          <span id="emailError" class="error-text"></span>
        </div>

        <div class="mb-3">
          <label class="form-label">Phone *</label>
          <input
            type="tel"
            id="phone"
            name="phone"
            class="form-control"
            required
          >
        </div>

        <div class="mb-3 password-toggle">

          <label class="form-label">Password *</label>

          <input
            type="password"
            id="password"
            name="password"
            class="form-control"
            required
          >

          <i
            id="togglePassword"
            class="fas fa-eye toggle-icon"
          ></i>

          <span id="passwordError" class="error-text"></span>

        </div>

        <button type="button" class="btn-custom" id="nextBtn">
          Continue
        </button>

        <div class="login-link">
          Already have an account?
          <a href="{{ route('login') }}">Login</a>
        </div>

      </div>

      <!-- STEP 2 -->
      <div class="form-step">

        <div class="step-indicator">
          <div class="step-dot"></div>
          <div class="step-dot active"></div>
        </div>

        <div class="registration-header">
          <h2>Complete Profile</h2>
          <p>Tell us more about yourself</p>
        </div>

        <div class="mb-3">

          <label class="form-label">Role *</label>

          <select
            id="role"
            name="role"
            class="form-select"
            required
          >
            <option value="">Select Role</option>
            <option value="customer">Customer</option>
            <option value="tailor">Tailor</option>
          </select>

        </div>

        <div id="tailorFields" style="display:none;">

          <div class="mb-3">
            <label class="form-label">Shop Address</label>

            <textarea
              id="address"
              name="address"
              class="form-control"
              rows="2"
            ></textarea>
          </div>

          <div class="mb-3">

            <label class="form-label">Specialization</label>

            <select
              id="category"
              name="category"
              class="form-select"
            >
              <option value="">Select Category</option>
              <option value="men">Men's Clothing</option>
              <option value="women">Women's Clothing</option>
              <option value="child">Children's Clothing</option>
              <option value="all">All Categories</option>
            </select>

          </div>

          <div class="mb-3">

            <label class="form-label">Slot Capacity</label>

            <input
              type="number"
              id="slot_capacity"
              name="slot_capacity"
              class="form-control"
              min="1"
            >

          </div>

        </div>

        <button type="button" class="btn-nav" id="backBtn">
          ← Back
        </button>

        <button type="submit" class="btn-custom">
          Register
        </button>

      </div>

    </div>

  </form>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>

const wrapper = document.getElementById('formWrapper');
const nextBtn = document.getElementById('nextBtn');
const backBtn = document.getElementById('backBtn');

const roleSelect = document.getElementById('role');
const tailorFields = document.getElementById('tailorFields');

const nameInput = document.getElementById('name');
const emailInput = document.getElementById('email');
const phoneInput = document.getElementById('phone');
const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');

const emailInput = document.getElementById('email');
const emailError = document.getElementById('emailError');

const passwordError = document.getElementById('passwordError');

const passwordRegex =
/^(?=.*[A-Za-z])(?=.*\\d)(?=.*[!@#$%^&*()_+{}:\"<>?~]).{8,}$/;

function validateEmail(email) {

  const regex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;

  if (!regex.test(email)) {
    emailError.textContent = 'Invalid Email';
    return false;
  }

  emailError.textContent = '';
  return true;
}

emailInput.addEventListener('blur', () => {
  validateEmail(emailInput.value.trim());
});

nextBtn.addEventListener('click', () => {

  let isValid = true;

  const name = document.getElementById('name').value.trim();
  const email = emailInput.value.trim();
  const phone = document.getElementById('phone').value.trim();
  const password = passwordInput.value.trim();

  if (!name) isValid = false;

  if (!validateEmail(email)) isValid = false;

  if (!phone) isValid = false;

  if (!passwordRegex.test(password)) {

    passwordError.textContent =
      'Password must contain letters, numbers and special characters';

    isValid = false;

  } else {
    passwordError.textContent = '';
  }

  if (isValid) {
    wrapper.classList.add('active');
  }

});

backBtn.addEventListener('click', () => {
  wrapper.classList.remove('active');
});

roleSelect.addEventListener('change', () => {

  if (roleSelect.value === 'tailor') {
    tailorFields.style.display = 'block';
  } else {
    tailorFields.style.display = 'none';
  }

});

togglePassword.addEventListener('click', () => {

  const type = passwordInput.getAttribute('type');

  if (type === 'password') {
    passwordInput.setAttribute('type', 'text');
    togglePassword.classList.remove('fa-eye');
    togglePassword.classList.add('fa-eye-slash');
  } else {
    passwordInput.setAttribute('type', 'password');
    togglePassword.classList.remove('fa-eye-slash');
    togglePassword.classList.add('fa-eye');
  }

});

</script>

</body>
</html>