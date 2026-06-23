<!DOCTYPE html>
<html lang="en">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<title>Verify Email - Stitchify</title>
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

.verify-header {
  text-align: center;
  margin-bottom: 30px;
}

.verify-header h2 {
  color: var(--accent-color);
  font-weight: 700;
  font-size: 28px;
  margin-bottom: 8px;
}

.verify-header p {
  color: var(--copyright-bg);
  font-size: 14px;
}

/* Email icon circle */
.email-icon-circle {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 24px auto;
}

.email-icon-circle i {
  color: var(--text-white);
  font-size: 32px;
}

/* Email display box */
.email-box {
  background-color: #f8f9fa;
  border: 2px solid #e0e0e0;
  border-radius: 10px;
  padding: 12px 15px;
  text-align: center;
  color: var(--accent-color);
  font-weight: 600;
  font-size: 15px;
  margin-bottom: 24px;
  word-break: break-all;
}

/* Info text */
.info-text {
  color: var(--copyright-bg);
  font-size: 14px;
  text-align: center;
  margin-bottom: 24px;
  line-height: 1.6;
}

/* Primary button — same as login */
.btn-verify {
  background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
  color: var(--text-white);
  border: none;
  border-radius: 10px;
  padding: 14px;
  font-weight: 600;
  font-size: 16px;
  width: 100%;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  cursor: pointer;
}

.btn-verify:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(14, 24, 48, 0.4);
  color: var(--text-white);
}

.btn-verify:disabled {
  opacity: 0.7;
  transform: none;
  cursor: not-allowed;
}

/* Logout link — same as register link style */
.logout-link {
  text-align: center;
  margin-top: 25px;
  padding-top: 25px;
  border-top: 1px solid #e0e0e0;
  color: var(--copyright-bg);
  font-size: 14px;
}

.logout-link a {
  color: var(--accent-color);
  text-decoration: none;
  font-weight: 600;
}

.logout-link a:hover {
  text-decoration: underline;
}

/* Help text bottom */
.help-text {
  text-align: center;
  color: var(--copyright-bg);
  font-size: 12px;
  margin-top: 16px;
  padding: 0 10px;
}

/* Alert messages */
.alert-success-custom {
  background-color: #d1e7dd;
  border: 1px solid #a3cfbb;
  color: #0a3622;
  border-radius: 10px;
  padding: 12px 15px;
  font-size: 14px;
  margin-bottom: 20px;
  text-align: center;
}

.alert-info-custom {
  background-color: #cff4fc;
  border: 1px solid #9eeaf9;
  color: #055160;
  border-radius: 10px;
  padding: 12px 15px;
  font-size: 14px;
  margin-bottom: 20px;
  text-align: center;
}
</style>
</head>
<body>

<div class="login-wrapper">
  <div class="logo-container">
    <img src="{{ asset('images/logo.png') }}" alt="Stitchify" class="logo-image">
  </div>

  {{-- Bottom: Form area --}}
  <div class="form-container">

    {{-- Email icon --}}
    <div class="email-icon-circle">
      <i class="fas fa-envelope"></i>
    </div>

    {{-- Header --}}
    <div class="verify-header">
      <h2>Verify Your Email</h2>
      <p>A verification link has been sent to your email</p>
    </div>

    {{-- Success message — jab resend ho --}}
    @if(session('status') == 'verification-link-sent')
      <div class="alert-success-custom">
        <i class="fas fa-check-circle me-2"></i>
        Verification email has been resent successfully!
      </div>
    @endif

    {{-- Info message — jab register se aaye --}}
    @if(session('info'))
      <div class="alert-info-custom">
        <i class="fas fa-info-circle me-2"></i>
        {{ session('info') }}
      </div>
    @endif

    {{-- User ki email display --}}
    <div class="email-box">
      <i class="fas fa-at me-2" style="color: var(--copyright-bg)"></i>
      {{ auth()->user()->email }}
    </div>

    {{-- Instructions --}}
    <p class="info-text">
      Please check your inbox and click the verification link
      to activate your account.
    </p>

    {{-- Resend button --}}
    <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
      @csrf
      <button type="submit" class="btn-verify" id="resendBtn">
        <i class="fas fa-paper-plane me-2"></i> Resend Verification Email
      </button>
    </form>

    {{-- Logout --}}
    <div class="logout-link">
      Wrong account? 
      <a href="{{ route('login') }}" id="logoutLink">Logout</a>
    </div>

  </div>
</div>

{{-- Help text bahar card ke --}}
<div style="width:450px; max-width:95%; margin: 12px auto 0;">
  <p class="help-text">
    Didn't receive the email? Check your spam folder.
    If still not received, click "Resend Verification Email".
  </p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>
// Resend button — ek baar click ke baad disable ho jaaye
// taake user baar baar na kare
const resendForm = document.getElementById('resendForm');
const resendBtn  = document.getElementById('resendBtn');

resendForm.addEventListener('submit', function() {
  resendBtn.disabled = true;
  resendBtn.innerHTML = `
    <span class="spinner-border spinner-border-sm me-2"></span>
    Sending...
  `;

  // 60 second baad dobara enable ho
  setTimeout(() => {
    resendBtn.disabled = false;
    resendBtn.innerHTML = `
      <i class="fas fa-paper-plane me-2"></i> Resend Verification Email
    `;
  }, 60000);
});

// Logout link
document.getElementById('logoutLink').addEventListener('click', function(e) {
  e.preventDefault();

  fetch('/logout', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                              .getAttribute('content'),
      'Accept': 'application/json',
    },
  }).then(() => {
    window.location.href = '/login';
  }).catch(() => {
    window.location.href = '/login';
  });
});
</script>

</body>
</html>