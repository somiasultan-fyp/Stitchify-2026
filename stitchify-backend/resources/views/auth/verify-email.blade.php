<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <title>Verify Email - Stitchify</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
</head>
<body>

<div class="login-wrapper">
    <div class="logo-container">
        <img src="{{ asset('images/logo.png') }}" alt="Stitchify" class="logo-image">
    </div>

    <div class="form-container">
        <div class="email-icon-circle">
            <i class="fas fa-envelope"></i>
        </div>

        <div class="verify-header">
            <h2>Verify Your Email</h2>
            <p>A verification link has been sent to your email</p>
        </div>

        @if(session('status') == 'verification-link-sent')
            <div class="alert-success-custom">
                <i class="fas fa-check-circle me-2"></i>
                Verification email has been resent successfully!
            </div>
        @endif

        @if(session('info'))
            <div class="alert-info-custom">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
            </div>
        @endif

        <div class="email-box">
            <i class="fas fa-at me-2" style="color: var(--copyright-bg)"></i>
            {{ auth()->user()->email }}
        </div>

        <p class="info-text">
            Please check your inbox and click the verification link to activate your account.
        </p>

        <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
            @csrf
            <button type="submit" class="btn-verify" id="resendBtn">
                <i class="fas fa-paper-plane me-2"></i> Resend Verification Email
            </button>
        </form>

        <div class="logout-link">
            Wrong account? 
            <a href="{{ route('login') }}" id="logoutLink">Logout</a>
        </div>
    </div>
</div>

<div style="width:450px; max-width:95%; margin: 12px auto 0;">
    <p class="help-text">
        Did not receive the email? Check your spam folder. If still not received, click Resend Verification Email.
    </p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/verify-email.js') }}"></script>

</body>
</html>