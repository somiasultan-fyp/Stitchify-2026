<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<title>Reset Password - Stitchify</title>
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

.reset-wrapper {
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

.reset-header {
  text-align: center;
  margin-bottom: 30px;
}

.reset-header h2 {
  color: var(--accent-color);
  font-weight: 700;
  font-size: 28px;
  margin-bottom: 8px;
}

.reset-header p {
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
  font-size: 15px;
}

.form-control:focus {
  border-color: var(--accent-color);
  box-shadow: 0 0 0 0.25rem rgba(14, 24, 48, 0.15);
  background-color: var(--text-white);
}

.form-control.is-invalid {
  border-color: #dc3545;
}

.btn-reset {
  background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
  color: var(--text-white);
  border: none;
  border-radius: 10px;
  padding: 14px;
  font-weight: 600;
  font-size: 16px;
  width: 100%;
  margin-top: 10px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.error-text {
  color: #dc3545;
  font-size: 0.875rem;
  margin-top: 5px;
  display: block;
}
</style>
</head>
<body>
<div class="reset-wrapper">
  <div class="logo-container">
    <img src="{{ asset('images/logo.png') }}" alt="Stitchify" class="logo-image">
  </div>

  <div class="form-container">
    <div class="reset-header">
      <h2>Reset Password</h2>
      <p>Enter your new password below</p>
    </div>

    @if($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input name="email" class="form-control" type="email" value="{{ $email }}" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label">New Password</label>
        <input name="password" class="form-control" type="password" placeholder="Enter new password" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input name="password_confirmation" class="form-control" type="password" placeholder="Confirm new password" required>
      </div>

      <button type="submit" class="btn-reset">
        <i class="fas fa-check"></i> Reset Password
      </button>
    </form>
  </div>
</div>
</body>
</html>