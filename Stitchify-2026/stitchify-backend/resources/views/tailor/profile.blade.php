<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>My Profile - Stitchify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-bg: #212529;
            --accent-color: #1B2A4A;
            --copyright-bg: #575a5b;
            --text-white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            background-color: #f5f6fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh; width: 260px;
            background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar-logo {
            text-align: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .sidebar-logo img {
            width: 80px; height: 50px;
            border-radius: 50%; margin-bottom: 10px;
        }
        .sidebar-logo h3 {
            color: var(--text-white);
            font-size: 18px; font-weight: 600; margin: 0;
        }
        .user-info {
            padding: 15px 20px;
            background-color: rgba(255,255,255,0.1);
            margin: 0 15px 20px;
            border-radius: 10px;
        }
        .user-info h4 { color: var(--text-white); font-size: 16px; margin: 0 0 5px; }
        .user-info p  { color: rgba(255,255,255,0.7); font-size: 13px; margin: 0; }

        .sidebar-menu { list-style: none; padding: 0 15px; }
        .sidebar-menu li { margin-bottom: 5px; }
        .sidebar-menu a {
            display: flex; align-items: center;
            padding: 12px 15px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 15px;
            position: relative;
        }
        .sidebar-menu a::before {
            content: ''; position: absolute; left: 0; top: 50%;
            transform: translateY(-50%);
            width: 0; height: 70%;
            background-color: var(--text-white);
            border-radius: 0 4px 4px 0;
            transition: width 0.3s ease;
        }
        .sidebar-menu a.active::before { width: 4px; }
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255,255,255,0.15);
            color: var(--text-white);
            padding-left: 20px;
        }
        .sidebar-menu a i { margin-right: 12px; width: 20px; text-align: center; }

        .logout-btn { margin-top: 20px; padding: 0 15px; }

        /* ── Main Content ── */
        .main-content {
            margin-left: 260px;
            padding: 20px;
            min-height: 100vh;
        }

        .top-bar {
            background-color: var(--text-white);
            padding: 20px 30px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .top-bar h2 {
            color: var(--accent-color);
            font-size: 26px; font-weight: 700; margin: 0;
        }

        .avatar-card {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .avatar-img {
            width: 100px; height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.4);
            margin-bottom: 15px;
        }
        .avatar-name {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .avatar-role {
            font-size: 0.85rem;
            opacity: 0.8;
            margin-bottom: 15px;
        }
        .avatar-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
        }
        .avatar-stat .num {
            font-size: 1.2rem;
            font-weight: 700;
            display: block;
        }
        .avatar-stat .lbl {
            font-size: 0.75rem;
            opacity: 0.75;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }
        .form-card-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label {
            font-weight: 600;
            font-size: 14px;
            color: var(--primary-bg);
            margin-bottom: 6px;
        }
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(27,42,74,0.1);
        }
        textarea.form-control { resize: vertical; min-height: 100px; }

        .btn-save {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-bg));
            color: white;
            border: none;
            padding: 12px 35px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(27,42,74,0.3);
        }

        .alert-success-custom {
            background: #d1e7dd;
            border: 1px solid #a3cfbb;
            color: #0a3622;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <h3>Stitchify</h3>
    </div>

    <div class="user-info">
        <h4>{{ auth()->user()->name }}</h4>
        <p>Professional Tailor</p>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="/tailor/dashboard">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="/tailor/dashboard#pending-orders">
                <i class="fas fa-hourglass-half"></i> Pending Orders
            </a>
        </li>
        <li>
            <a href="/tailor/dashboard#active-orders">
                <i class="fas fa-tasks"></i> Active Orders
            </a>
        </li>
        <li>
            <a href="{{ route('tailor.profile') }}" class="active">
                <i class="fas fa-user"></i> My Profile
            </a>
        </li>
    </ul>

    <div class="logout-btn">
        <form method="POST" action="/logout" style="margin:0;">
            @csrf
            <button type="submit"
                    style="background:none;border:none;padding:12px 15px;
                           color:#ff6b6b;width:100%;text-align:left;
                           cursor:pointer;border-radius:8px;font-size:15px;
                           display:flex;align-items:center;transition:all 0.3s ease;"
                    onmouseover="this.style.backgroundColor='rgba(220,53,69,0.3)'"
                    onmouseout="this.style.backgroundColor='transparent'">
                <i class="fas fa-sign-out-alt"
                   style="margin-right:12px;width:20px;text-align:center;"></i>
                Logout
            </button>
        </form>
    </div>
</div>

<div class="main-content">

    <div class="top-bar">
        <h2>My Profile</h2>
        <a href="/tailor/dashboard" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success-custom">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">

        <div class="col-md-4">
            <div class="avatar-card">
                <img src="{{ $user->profile_image
                            ? Storage::url($user->profile_image)
                            : asset('images/default-avatar.png') }}"
                     alt="{{ $user->name }}"
                     class="avatar-img">
                <div class="avatar-name">{{ $user->name }}</div>
                <div class="avatar-role">
                    {{ $tailor->specialization ?? 'Professional Tailor' }}
                </div>
                <div class="avatar-stats">
                    <div class="avatar-stat">
                        <span class="num">
                            {{ $tailor->orders()->where('status','delivered')->count() }}
                        </span>
                        <span class="lbl">Completed</span>
                    </div>
                    <div class="avatar-stat">
                        <span class="num">{{ $tailor->available_slots }}</span>
                        <span class="lbl">Slots Left</span>
                    </div>
                    <div class="avatar-stat">
                        <span class="num">{{ $tailor->experience_years ?? 0 }}</span>
                        <span class="lbl">Years Exp.</span>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-title">
                    <i class="fas fa-info-circle"></i> Account Info
                </div>
                <div class="mb-2">
                    <small class="text-muted">Email</small>
                    <p class="mb-0 fw-bold" style="font-size:14px">
                        {{ $user->email }}
                    </p>
                </div>
                <hr>
                <div class="mb-2">
                    <small class="text-muted">Member Since</small>
                    <p class="mb-0 fw-bold" style="font-size:14px">
                        {{ $user->created_at->format('M Y') }}
                    </p>
                </div>
                <hr>
                <div>
                    <small class="text-muted">Account Status</small>
                    <p class="mb-0">
                        <span class="badge bg-success">Active</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <form method="POST"
                  action="{{ route('tailor.profile.update') }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="form-card">
                    <div class="form-card-title">
                        <i class="fas fa-user"></i> Personal Information
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone"
                                   class="form-control"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="e.g. 03001234567">
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="form-card-title">
                        <i class="fas fa-store"></i> Shop Information
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Shop Name</label>
                            <input type="text" name="shop_name"
                                   class="form-control"
                                   value="{{ old('shop_name', $tailor->shop_name) }}"
                                   placeholder="e.g. Ali Tailors">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Specialization</label>
                            <input type="text" name="specialization"
                                   class="form-control"
                                   value="{{ old('specialization', $tailor->specialization) }}"
                                   placeholder="e.g. Bridal, Casual, Formal">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" name="city"
                                   class="form-control"
                                   value="{{ old('city', $tailor->city) }}"
                                   placeholder="e.g. Lahore">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Experience (Years)</label>
                            <input type="number" name="experience_years"
                                   class="form-control"
                                   value="{{ old('experience_years', $tailor->experience_years) }}"
                                   min="0" placeholder="e.g. 5">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Base Price (Rs.)</label>
                            <input type="number" name="base_price"
                                   class="form-control"
                                   value="{{ old('base_price', $tailor->base_price) }}"
                                   min="0" placeholder="e.g. 1500">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Max Slots</label>
                            <input type="number" name="max_slots"
                                   class="form-control"
                                   value="{{ old('max_slots', $tailor->max_slots) }}"
                                   min="1" max="50">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <input type="text" name="address"
                                   class="form-control"
                                   value="{{ old('address', $tailor->address) }}"
                                   placeholder="Full shop address">
                        </div>
                        <div class="col-12">
                            <label class="form-label">About / Bio</label>
                            <textarea name="bio"
                                      class="form-control"
                                      placeholder="Tell customers about yourself and your work...">{{ old('bio', $tailor->bio) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>