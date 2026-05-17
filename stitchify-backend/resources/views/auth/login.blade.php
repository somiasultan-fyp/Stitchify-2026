<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
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
  background: #f8f9fa;
}
.login-wrapper {
  width: 450px;
  max-width: 95%;
  background-color: var(--text-white);
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
  overflow: hidden;
}
.logo-container {
  background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
  padding: 40px;
  text-align: center;
}
.logo-image {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: contain;
  background: #fff;
  padding: 10px;
}
.form-container { padding: 40px; }
.login-header { text-align: center; margin-bottom: 30px; }
.login-header h2 { color: var(--accent-color); font-weight: 700; font-size: 28px; margin-bottom: 8px; }
.login-header p { color: var(--copyright-bg); font-size: 14px; }
.form-label { color: var(--primary-bg); font-weight: 600; margin-bottom: 8px; font-size: 14px; }
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
  box-shadow: 0 0 0 0.25rem rgba(27, 42, 74, 0.15);
  background-color: #fff;
}
.form-control.is-invalid { border-color: #dc3545; }
.btn-login {
  background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 14px;
  font-weight: 600;
  font-size: 16px;
  width: 100%;
  transition: all 0.3s ease;
  margin-top: 10px;
  cursor: pointer;
}
.btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(27, 42, 74, 0.3); }
.btn-login:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
.password-toggle { position: relative; }
.password-toggle .toggle-icon {
  position: absolute;
  right: 15px;
  top: 70%;
  transform: translateY(-50%);
  cursor: pointer;
  color: var(--copyright-bg);
  opacity: 0.6;
  font-size: 1.1rem;
  display: none;
}
.password-toggle .toggle-icon.show { display: block; }
.password-toggle .toggle-icon:hover { opacity: 1; }
.error-text { color: #dc3545; font-size: 0.875rem; margin-top: 5px; display: block; }
.forgot-password { text-align: right; margin-top: 10px; margin-bottom: 20px; }
.forgot-password a { color: var(--copyright-bg); text-decoration: none; font-size: 14px; }
.forgot-password a:hover { color: var(--accent-color); text-decoration: underline; }
.register-link {
  text-align: center;
  margin-top: 25px;
  padding-top: 25px;
  border-top: 1px solid #e0e0e0;
  color: var(--copyright-bg);
  font-size: 14px;
}
.register-link a { color: var(--accent-color); text-decoration: none; font-weight: 600; }
.register-link a:hover { text-decoration: underline; }
.remember-me { display: flex; align-items: center; margin-top: 15px; }
.remember-me input[type="checkbox"] {
  width: 18px; height: 18px; margin-right: 8px; cursor: pointer; accent-color: var(--accent-color);
}
.remember-me label { color: var(--copyright-bg); font-size: 14px; cursor: pointer; margin: 0; }
.alert-danger {
  background-color: #ffe6e6;
  border: 1px solid #dc3545;
  color: #721c24;
  padding: 12px 15px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-size: 14px;
}
</style>
</head>
<body>
<div class="login-wrapper">
  <div class="logo-container">
    <img src="{{ asset('images/logo.png') }}" alt="Stitchify" class="logo-image" onerror="this.src='https://via.placeholder.com/100?text=S'">
  </div>

  <div class="form-container">
    <div class="login-header">
      <h2>Welcome Back</h2>
      <p>Login to your account</p>
    </div>

    <div id="loginError" class="alert-danger" style="display:none;"></div>

    <form id="loginForm">
      @csrf
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input id="email" name="email" class="form-control"
               placeholder="example@gmail.com" type="email"
               value="{{ old('email') }}" required autocomplete="email">
        <span id="emailError" class="error-text"></span>
      </div>
      <div class="mb-3 password-toggle">
        <label for="password" class="form-label">Password</label>
        <input id="password" name="password" class="form-control"
               placeholder="Enter your password" type="password"
               required autocomplete="current-password">
        <i id="togglePassword" class="fas fa-eye toggle-icon" role="button"></i>
        <span id="passwordError" class="error-text"></span>
      </div>
      <div class="d-flex justify-content-between align-items-center">
        <div class="remember-me">
          <input type="checkbox" name="remember" id="rememberMe">
          <label for="rememberMe">Remember me</label>
        </div>
        <div class="forgot-password">
          <a href="#" id="forgotPasswordLink">Forgot Password?</a>
        </div>
      </div>
      <button type="submit" class="btn-login" id="loginBtn">
        <i class="fas fa-sign-in-alt"></i> Login
      </button>
    </form>

    <div class="register-link">
      Don't have an account? <a href="{{ route('register.form') }}">Sign Up</a>
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
const loginBtn       = document.getElementById('loginBtn');
const loginForm      = document.getElementById('loginForm');
const errorDiv       = document.getElementById('loginError');

function validateEmail(email) {
  emailError.textContent = '';
  if (!email) return true;
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    emailError.textContent = 'Please enter a valid email address';
    return false;
  }
  return true;
}

emailInput.addEventListener('blur', () => {
  if (emailInput.value.trim()) validateEmail(emailInput.value.trim());
});
emailInput.addEventListener('input', () => {
  if (emailError.textContent) validateEmail(emailInput.value.trim());
});

passwordInput.addEventListener('input', () => {
  togglePassword.classList.toggle('show', passwordInput.value.length > 0);
  passwordError.textContent = '';
});

togglePassword.addEventListener('click', () => {
  const isPassword = passwordInput.type === 'password';
  passwordInput.type = isPassword ? 'text' : 'password';
  togglePassword.classList.toggle('fa-eye', !isPassword);
  togglePassword.classList.toggle('fa-eye-slash', isPassword);
});

document.getElementById('forgotPasswordLink')?.addEventListener('click', (e) => {
  e.preventDefault();
  alert('Password reset coming soon!');
});

loginForm.addEventListener('submit', async (e) => {
  e.preventDefault();

  const email    = emailInput.value.trim();
  const password = passwordInput.value;
  const token    = document.querySelector('input[name="_token"]').value;

  errorDiv.style.display = 'none';
  errorDiv.textContent   = '';

  if (!validateEmail(email)) return;
  if (!password) {
    passwordError.textContent = 'Password is required.';
    return;
  }

  loginBtn.disabled  = true;
  loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Logging in...';

  try {
    const response = await fetch('{{ route("login") }}', {
      method:  'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept':       'application/json',
        'X-CSRF-TOKEN': token,
      },
      body: JSON.stringify({ email, password }),
    });

    const result = await response.json();

    if (result.success) {
      loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Redirecting...';
      window.location.href = result.redirect;
    } else {
      errorDiv.textContent   = result.message || 'Email or password is incorrect.';
      errorDiv.style.display = 'block';
      loginBtn.disabled      = false;
      loginBtn.innerHTML     = '<i class="fas fa-sign-in-alt"></i> Login';
    }

  } catch (err) {
    console.error(err);
    errorDiv.textContent   = 'Something went wrong. Please try again.';
    errorDiv.style.display = 'block';
    loginBtn.disabled      = false;
    loginBtn.innerHTML     = '<i class="fas fa-sign-in-alt"></i> Login';
  }
});
</script>
</body>
</html>