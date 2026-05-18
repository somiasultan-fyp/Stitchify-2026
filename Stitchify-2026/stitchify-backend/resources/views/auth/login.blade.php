<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Login - Stitchify</title>

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

.login-wrapper {
  width: 450px;
  max-width: 95%;
  background-color: var(--text-white);
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
  overflow: hidden;
}

.logo-container {
  background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
  padding: 40px;
  text-align: center;
  border-bottom: 3px solid var(--copyright-bg);
}

.logo-image {
  width: 100px;
  height: 100px;
  margin: 0 auto;
  display: block;
  object-fit: contain;
  border-radius: 50%;
  padding: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.form-container {
  padding: 40px;
}

.login-header {
  text-align: center;
  margin-bottom: 30px;
}

.login-header h2 {
  color: var(--accent-color);
  font-weight: 700;
  font-size: 28px;
  margin-bottom: 8px;
}

.login-header p {
  color: var(--copyright-bg);
  font-size: 14px;
}

.form-label {
  color: var(--primary-bg);
  font-weight: 600;
  margin-bottom: 8px;
  font-size: 14px;
}

.form-control {
  border: 2px solid #e0e0e0;
  border-radius: 10px;
  padding: 12px 15px;
  color: var(--primary-bg);
  background-color: #f8f9fa;
  transition: all 0.3s ease;
  font-size: 15px;
}

.form-control:focus {
  border-color: var(--accent-color);
  box-shadow: 0 0 0 0.25rem rgba(14, 24, 48, 0.15);
  background-color: var(--text-white);
}

.btn-login {
  background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
  color: var(--text-white);
  border: none;
  border-radius: 10px;
  padding: 14px;
  font-weight: 600;
  font-size: 16px;
  width: 100%;
  transition: all 0.3s ease;
  margin-top: 10px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.btn-login:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(14, 24, 48, 0.4);
}

.password-toggle {
  position: relative;
}

.password-toggle .toggle-icon {
  position: absolute;
  right: 15px;
  top: 70%;
  transform: translateY(-50%);
  cursor: pointer;
  color: var(--copyright-bg);
  opacity: 0.6;
  display: none;
  font-size: 1.1rem;
}

.password-toggle .toggle-icon.show {
  display: block;
}

.error-text {
  color: #dc3545;
  font-size: 0.875rem;
  margin-top: 5px;
  display: block;
}

.register-link {
  text-align: center;
  margin-top: 25px;
  padding-top: 25px;
  border-top: 1px solid #e0e0e0;
  color: var(--copyright-bg);
  font-size: 14px;
}

.register-link a {
  color: var(--accent-color);
  text-decoration: none;
  font-weight: 600;
}

.remember-me {
  display: flex;
  align-items: center;
  margin-top: 15px;
}

.remember-me input[type="checkbox"] {
  width: 18px;
  height: 18px;
  margin-right: 8px;
  cursor: pointer;
  accent-color: var(--accent-color);
}
</style>
</head>

<body>

<div class="login-wrapper">

  <div class="logo-container">
    <img src="{{ asset('images/logo.png') }}" alt="Stitchify" class="logo-image">
  </div>

  <div class="form-container">

    <div class="login-header">
      <h2>Welcome Back</h2>
      <p>Login to your account</p>
    </div>

    @if(session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="loginForm">

      @csrf

      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>

        <input
          id="email"
          name="email"
          class="form-control"
          placeholder="example@gmail.com"
          type="email"
          required
        >

        <span id="emailError" class="error-text"></span>
      </div>

      <div class="mb-3 password-toggle">

        <label for="password" class="form-label">Password</label>

        <input
          id="password"
          name="password"
          class="form-control"
          placeholder="Enter your password"
          type="password"
          required
        >

        <i
          id="togglePassword"
          class="fas fa-eye toggle-icon"
        ></i>

        <span id="passwordError" class="error-text"></span>
      </div>

      <div class="remember-me mb-3">
        <input type="checkbox" id="rememberMe" name="remember">
        <label for="rememberMe">Remember me</label>
      </div>

      <button type="submit" class="btn-login">
        <i class="fas fa-sign-in-alt"></i>
        Login
      </button>

    </form>

    <div class="register-link">
      Don't have an account?
      <a href="{{ route('register') }}">Sign Up</a>
    </div>

  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

<script>

const emailInput     = document.getElementById('email');
const passwordInput  = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');
const emailError     = document.getElementById('emailError');
const passwordError  = document.getElementById('passwordError');

function validateEmail(email) {

  emailError.textContent = '';

  if (!email.includes('@')) {
    emailError.textContent = 'Invalid email format';
    return false;
  }

  return true;
}

emailInput.addEventListener('blur', () => {
  if (emailInput.value.trim()) {
    validateEmail(emailInput.value.trim());
  }
});

passwordInput.addEventListener('input', () => {

  const value = passwordInput.value;

  if (value.length > 0) {
    togglePassword.classList.add('show');
  } else {
    togglePassword.classList.remove('show');
  }
});

togglePassword.addEventListener('click', () => {

  const currentType = passwordInput.getAttribute('type');

  if (currentType === 'password') {
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