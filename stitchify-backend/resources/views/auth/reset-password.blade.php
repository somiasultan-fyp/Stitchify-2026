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
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('css/reset-password.css') }}" rel="stylesheet">
</head>
<body class="auth-page">

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/reset-password.js') }}"></script>

</body>
</html>