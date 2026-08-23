<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <title>My Profile - Stitchify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tailor-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body>

<div class="sidebar">
    <div class="sidebar-logo">
        <a href="/" style="text-decoration:none;">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h3 style="color:white;">Stitchify</h3>
        </a>
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
        <div class="success-banner">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">

        <div class="col-md-4">
            <div class="avatar-card">
                <div class="avatar-img-wrap">
                    <img src="{{ $user->profile_image
                                ? Storage::url($user->profile_image)
                                : asset('images/default-avatar.png') }}"
                         alt="{{ $user->name }}"
                         class="avatar-img"
                         id="avatarPreview">
                    <label for="profileImageInput" class="avatar-upload-btn">
                        <i class="fas fa-camera"></i>
                    </label>
                </div>
                <div class="avatar-name">{{ $user->name }}</div>
                <div class="avatar-role">
                    {{ $tailor->specialization ?? 'Professional Tailor' }}
                </div>
                <div class="avatar-stats">
                    <div class="avatar-stat">
                        <span class="num">
                            {{ $tailor->orders()->where('status', 'delivered')->count() }}
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

                <input type="file"
                       name="profile_image"
                       id="profileImageInput"
                       accept="image/*"
                       style="display:none;">

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
<script src="{{ asset('js/profile.js') }}"></script>
</body>
</html>