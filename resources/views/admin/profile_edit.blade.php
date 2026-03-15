@extends('admin.app')
@push('title')
    Edit Admin Profile
@endpush
@push('css')
    <style>
        .profile-img-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }

        .profile-img-wrap img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }



        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(45deg, var(--main-theme-color), #3a8dff);
            color: #fff;
            padding: 20px;
            border: none;
        }

        .form-label {
            font-weight: 600;
            color: #444;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--main-theme-color);
            box-shadow: 0 0 0 3px rgba(58, 141, 255, 0.1);
        }

        .btn-update {
            background: var(--main-theme-color);
            color: #fff;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            transition: all 0.3s;
        }

        .btn-update:hover {
            background: #2a7def;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(58, 141, 255, 0.3);
        }

        .btn-back {
            background: #6c757d;
            color: #fff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .btn-back:hover {
            background: #5a6268;
            color: #fff;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            user-select: none;
            z-index: 10;
        }
    </style>
@endpush
@section('content')
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold">Edit Profile</h4>
            <p>Modify your account information and security settings</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <a href="{{ route('admin.profile') }}" class="btn-back">
                <i class="ti ti-arrow-left"></i> Back to Profile
            </a>



            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 text-white">Update Information</h5>
                        <i class="ti ti-settings fs-3"></i>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-3 text-center border-end">
                                <div class="profile-img-wrap">
                                    <img id="profile-preview"
                                        src="{{ $admin->img ? asset('storage/' . $admin->img) : asset('asset/img/default-avatar.png') }}"
                                        alt="Admin Profile">
                                    <input type="file" id="img-input" name="img" class="d-none" accept="image/*">
                                </div>
                                <h5 class="mb-1">{{ $admin->name }}</h5>
                                <div class="mt-2">
                                    <label for="img-input" class="btn btn-outline-primary btn-sm">
                                        <i class="ti ti-upload me-1"></i> Upload New Photo
                                    </label>
                                </div>
                                @error('img')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-9 ps-md-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $admin->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Username</label>
                                        <input type="text" name="username"
                                            class="form-control @error('username') is-invalid @enderror"
                                            value="{{ old('username', $admin->username) }}" required>
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $admin->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $admin->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $admin->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12 mt-4 border-top pt-4">
                                        <h6 class="mb-3 text-primary">Update Password</h6>
                                        <p class="text-muted small mb-4">Leave password fields blank if you don't want to
                                            change it.</p>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">New Password</label>
                                                <div class="password-container">
                                                    <input type="password" name="password" id="new-password"
                                                        class="form-control @error('password') is-invalid @enderror">
                                                    <i class="ti ti-eye toggle-password" data-target="new-password"></i>
                                                </div>
                                                @error('password')
                                                    <div class="invalid-feedback d-block">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Confirm New Password</label>
                                                <div class="password-container">
                                                    <input type="password" name="password_confirmation"
                                                        id="confirm-password" class="form-control">
                                                    <i class="ti ti-eye toggle-password" data-target="confirm-password"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4 text-end">
                                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">Update
                                            Profile</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                showConfirmButton: true
            });
        @endif

        document.getElementById('img-input').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('profile-preview').setAttribute('src', event.target.result);
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // Password visibility toggle (Hold to show)
        document.querySelectorAll('.toggle-password').forEach(function(icon) {
            const targetId = icon.getAttribute('data-target');
            const input = document.getElementById(targetId);

            const showPassword = function() {
                input.type = 'text';
                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            };

            const hidePassword = function() {
                input.type = 'password';
                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            };

            // Mouse events
            icon.addEventListener('mousedown', showPassword);
            icon.addEventListener('mouseup', hidePassword);
            icon.addEventListener('mouseleave', hidePassword);

            // Touch events for mobile
            icon.addEventListener('touchstart', function(e) {
                e.preventDefault(); // Prevent default touch behavior like scrolling
                showPassword();
            });
            icon.addEventListener('touchend', hidePassword);
            icon.addEventListener('touchcancel', hidePassword);
        });
    </script>
@endpush
