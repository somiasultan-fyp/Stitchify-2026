const urlParams  = new URLSearchParams(window.location.search);
const redirectTo = urlParams.get('redirect') || null;
const loginForm      = document.getElementById('loginForm');
const emailInput     = document.getElementById('email');
const passwordInput  = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');
const emailError     = document.getElementById('emailError');
const passwordError  = document.getElementById('passwordError');

function validateEmail(email) {
  emailError.textContent = '';
  emailInput.classList.remove('is-invalid');

  if (!email) {
    emailError.textContent = 'Email address is required.';
    emailInput.classList.add('is-invalid');
    return false;
  }
  if (!email.includes('@')) {
    emailError.textContent = 'Email must contain @ symbol';
    emailInput.classList.add('is-invalid');
    return false;
  }
  const parts = email.split('@');
  if (parts.length !== 2 || !parts[0] || !parts[1]) {
    emailError.textContent = 'Invalid email format. Use: example@domain.com';
    emailInput.classList.add('is-invalid');
    return false;
  }
  if (!parts[1].includes('.')) {
    emailError.textContent = 'Email must contain a domain (e.g., gmail.com)';
    emailInput.classList.add('is-invalid');
    return false;
  }
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    emailError.textContent = 'Please enter a valid email address';
    emailInput.classList.add('is-invalid');
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
  const value = passwordInput.value;
  if (value.length > 0) {
    togglePassword.classList.add('show');
  } else {
    togglePassword.classList.remove('show');
    passwordInput.setAttribute('type', 'password');
    togglePassword.classList.remove('fa-eye-slash');
    togglePassword.classList.add('fa-eye');
  }
  passwordError.textContent = '';
  passwordInput.classList.remove('is-invalid');
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

loginForm.addEventListener('submit', async (e) => {
  e.preventDefault();

  const email     = document.getElementById('email').value.trim();
  const password  = document.getElementById('password').value;
  const errorDiv  = document.getElementById('loginError');
  const submitBtn = loginForm.querySelector('button[type="submit"]');

  errorDiv.textContent = '';
  errorDiv.style.display = 'none';

  const isEmailValid = validateEmail(email);

  let isPasswordValid = true;
  if (!password) {
    passwordError.textContent = 'Password is required.';
    passwordInput.classList.add('is-invalid');
    isPasswordValid = false;
  }

  if (!isEmailValid || !isPasswordValid) {
    const firstInvalid = document.querySelector('.is-invalid');
    if (firstInvalid) firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
    return;
  }

  submitBtn.disabled = true;
  submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Logging in...';

  try {
    const response = await fetch('/login', {
      method:  'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept':       'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
      body: JSON.stringify({ email, password, redirect: redirectTo }),
    });

    const result = await response.json();

    if (result.success) {
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Redirecting...';
      window.location.href = result.redirect;
    } else {
      errorDiv.textContent   = result.message || 'Invalid credentials.';
      errorDiv.style.display = 'block';
      submitBtn.disabled     = false;
      submitBtn.innerHTML    = 'Login';
    }

  } catch (err) {
    errorDiv.textContent   = 'Something went wrong. Please try again.';
    errorDiv.style.display = 'block';
    submitBtn.disabled     = false;
    submitBtn.innerHTML    = 'Login';
  }
});

const forgotSubmitBtn  = document.getElementById('forgotSubmitBtn');
const forgotEmailInput = document.getElementById('forgotEmail');
const forgotSuccessBox = document.getElementById('forgotSuccessBox');
const forgotErrorBox   = document.getElementById('forgotErrorBox');

forgotSubmitBtn.addEventListener('click', async () => {
  const email = forgotEmailInput.value.trim();

  forgotSuccessBox.style.display = 'none';
  forgotErrorBox.style.display   = 'none';
  forgotEmailInput.classList.remove('is-invalid');

  if (!email || !email.includes('@')) {
    forgotErrorBox.textContent = 'Please enter a valid email address.';
    forgotErrorBox.style.display = 'block';
    forgotEmailInput.classList.add('is-invalid');
    return;
  }

  forgotSubmitBtn.disabled = true;
  forgotSubmitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Sending...';

  try {
    const response = await fetch('/forgot-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
      body: JSON.stringify({ email }),
    });

    const result = await response.json();

    if (result.success) {
      forgotSuccessBox.textContent = result.message;
      forgotSuccessBox.style.display = 'block';
      forgotEmailInput.value = '';
    } else {
      forgotErrorBox.textContent = result.message || 'Unable to send reset link.';
      forgotErrorBox.style.display = 'block';
    }
  } catch (err) {
    forgotErrorBox.textContent = 'Something went wrong. Please try again.';
    forgotErrorBox.style.display = 'block';
  } finally {
    forgotSubmitBtn.disabled = false;
    forgotSubmitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Reset Link';
  }
});