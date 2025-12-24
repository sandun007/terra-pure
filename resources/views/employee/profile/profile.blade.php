@extends('employee.layout.app')
@push('title')
    My Profile
@endpush

@push('css')
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .profile-container {
            padding: 30px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        /* Header Styling */
        .profile-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 20px 60px rgba(16, 185, 129, 0.3);
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .header-title h1 {
            color: white;
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-title p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            margin-bottom: 0;
        }

        /* Profile Card */
        .profile-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .profile-card:hover {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .profile-sidebar {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
            position: relative;
        }

        .profile-avatar-wrapper {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 25px;
        }

        .profile-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .avatar-upload {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .avatar-upload:hover {
            transform: scale(1.1);
            background: #f8fafc;
        }

        .avatar-upload input {
            display: none;
        }

        .avatar-upload i {
            color: #10b981;
            font-size: 18px;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .profile-role {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 15px;
        }

        .profile-status {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .profile-content {
            padding: 40px;
        }

        /* Form Styling */
        .form-section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f5f9;
            position: relative;
        }

        .section-title::before {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control, .form-select {
            width: 100%;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            color: #1e293b;
            background: white;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            outline: none;
        }

        .form-control:disabled {
            background: #f8fafc;
            color: #94a3b8;
            cursor: not-allowed;
        }

        .form-hint {
            display: block;
            font-size: 12px;
            color: #94a3b8;
            margin-top: 6px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #f1f5f9;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: #10b981;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #64748b;
            font-weight: 600;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-container {
                padding: 15px;
            }

            .profile-header {
                padding: 25px;
            }

            .header-title h1 {
                font-size: 24px;
            }

            .profile-avatar-wrapper {
                width: 120px;
                height: 120px;
            }

            .profile-content {
                padding: 25px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="profile-container">
        <!-- Header -->
        <div class="profile-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>My Profile</h1>
                    <p>Manage your account information and settings</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <!-- Profile Sidebar -->
                <div class="profile-card">
                    <div class="profile-sidebar">
                        <div class="profile-avatar-wrapper">
                            @php
                                $employee = auth()->guard('employee')->user();
                                $avatarUrl = $employee && $employee->img ? asset('storage/' . $employee->img) : asset('asset/img/default-avatar.png');
                            @endphp
                            <img src="{{ $avatarUrl }}"
                                 alt="Profile"
                                 class="profile-avatar"
                                 id="profileAvatar"
                                 onerror="this.src='{{ asset('asset/img/default-avatar.png') }}'">
                            <label class="avatar-upload" for="avatarInput">
                                <i class="fas fa-camera"></i>
                                <input type="file"
                                       id="avatarInput"
                                       accept="image/*"
                                       data-url="{{ route('employee.profile-update') }}">
                            </label>
                        </div>

                        <div class="profile-name">{{ $employee->name ?? 'Employee' }}</div>
                        <div class="profile-role">{{ ucfirst($employee->role ?? 'Employee') }}</div>
                        <div class="profile-status">Employee Account</div>
                    </div>

                    <div class="profile-content">
                        <!-- Account Info -->
                        <div class="form-section mt-4">
                            <h5 class="section-title">Account Info</h5>
                            <div class="form-group">
                                <label class="form-label">Employee ID</label>
                                <input type="text" class="form-control" value="{{ $employee->employee_id ?? 'N/A' }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" value="{{ $employee->username ?? 'N/A' }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Account Type</label>
                                <input type="text" class="form-control" value="Employee" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <!-- Profile Form -->
                <div class="profile-card">
                    <div class="profile-content">
                        <form id="profileForm" action="{{ route('employee.profile-update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Personal Information -->
                            <div class="form-section">
                                <h5 class="section-title">Personal Information</h5>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label class="form-label">Full Name *</label>
                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               value="{{ $employee->name ?? '' }}"
                                               required>
                                        <span class="form-hint">Your full legal name</span>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Email Address *</label>
                                        <input type="email"
                                               name="email"
                                               class="form-control"
                                               value="{{ $employee->email ?? '' }}"
                                               required>
                                        <span class="form-hint">Primary contact email</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="form-section">
                                <h5 class="section-title">Contact Information</h5>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label class="form-label">Phone Number *</label>
                                        <input type="text"
                                               name="phone"
                                               class="form-control"
                                               value="{{ $employee->phone ?? '' }}"
                                               required>
                                        <span class="form-hint">Primary contact number</span>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Address</label>
                                        <textarea name="address"
                                                  class="form-control"
                                                  rows="3">{{ $employee->address ?? '' }}</textarea>
                                        <span class="form-hint">Your residential address</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Change (Optional) -->
                            <div class="form-section">
                                <h5 class="section-title">Change Password</h5>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label class="form-label">Current Password</label>
                                        <input type="password"
                                               name="current_password"
                                               class="form-control"
                                               autocomplete="current-password">
                                        <span class="form-hint">Enter your current password</span>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">New Password</label>
                                        <input type="password"
                                               name="new_password"
                                               class="form-control"
                                               autocomplete="new-password">
                                        <span class="form-hint">Minimum 8 characters</span>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password"
                                               name="new_password_confirmation"
                                               class="form-control"
                                               autocomplete="new-password">
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Image Upload -->
                            <div class="form-section">
                                <h5 class="section-title">Profile Image</h5>
                                <div class="form-group">
                                    <label class="form-label">Upload New Image</label>
                                    <div class="custom-file-upload">
                                        <input type="file"
                                               id="profileImage"
                                               name="img"
                                               accept="image/*"
                                               class="form-control">
                                        <span class="form-hint">Max file size: 2MB. Supported: JPG, PNG, GIF, WebP</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <button type="button" class="btn btn-secondary" onclick="window.location.reload()">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                <button type="submit" class="btn btn-primary" id="saveBtn">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('profileForm');
            const avatarInput = document.getElementById('avatarInput');
            const profileImage = document.getElementById('profileImage');
            const profileAvatar = document.getElementById('profileAvatar');
            const saveBtn = document.getElementById('saveBtn');

            // Handle avatar upload
            if (avatarInput) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        if (file.size > 2 * 1024 * 1024) {
                            showError('File size must be less than 2MB');
                            return;
                        }

                        // Preview image
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            profileAvatar.src = e.target.result;
                        };
                        reader.readAsDataURL(file);

                        // Upload via AJAX
                        uploadAvatar(file);
                    }
                });
            }

            // Handle profile image preview
            if (profileImage) {
                profileImage.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            console.log('Profile image selected:', file.name);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Handle form submission
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                    saveBtn.disabled = true;

                    const formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showSuccess(data.message);

                                // Update avatar if it was changed via form
                                if (formData.get('img')) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        profileAvatar.src = e.target.result;
                                    };
                                    reader.readAsDataURL(formData.get('img'));
                                }

                                // Reload page after 2 seconds to reflect changes
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                showError(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showError('An error occurred. Please try again.');
                        })
                        .finally(() => {
                            saveBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                            saveBtn.disabled = false;
                        });
                });
            }

            // Avatar upload function
            function uploadAvatar(file) {
                const formData = new FormData();
                formData.append('img', file);
                formData.append('_method', 'PATCH');

                fetch('{{ route("employee.profile-update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccess('Profile image updated successfully!');
                        } else {
                            showError(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showError('Failed to upload image');
                    });
            }

            // Success notification
            function showSuccess(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#f0fdf4',
                    iconColor: '#10b981'
                });
            }

            // Error notification
            function showError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#fef2f2',
                    iconColor: '#ef4444'
                });
            }

            // Add input validation styles
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '' && this.hasAttribute('required')) {
                        this.style.borderColor = '#ef4444';
                    } else {
                        this.style.borderColor = '#22c55e';
                        setTimeout(() => {
                            this.style.borderColor = '#e2e8f0';
                        }, 2000);
                    }
                });

                input.addEventListener('focus', function() {
                    this.style.borderColor = '#10b981';
                });
            });
        });
    </script>
@endpush
