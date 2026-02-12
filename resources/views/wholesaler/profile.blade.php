@extends('wholesaler.layouts.app')

@push('title')
    My Profile
@endpush

@push('css')
<style>
    * { font-family: 'Inter', sans-serif; }

    .profile-container {
        padding: 30px;
        background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
        min-height: 100vh;
    }

    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 30px;
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
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

    .header-content { position: relative; z-index: 1; }
    .header-title h1 {
        color: white;
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 10px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .header-title p { color: rgba(255,255,255,0.9); font-size: 16px; margin-bottom: 0; }

    .profile-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 50px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }
    .profile-card:hover { box-shadow: 0 20px 60px rgba(0,0,0,0.15); }

    .profile-sidebar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        border: 5px solid rgba(255,255,255,0.3);
        transition: all 0.3s ease;
        background: rgba(255,255,255,0.1);
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
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }

    .avatar-upload:hover { transform: scale(1.1); background: #f8fafc; }
    .avatar-upload input { display: none; }
    .avatar-upload i { color: #667eea; font-size: 18px; }

    .profile-name { font-size: 24px; font-weight: 800; margin-bottom: 5px; }
    .profile-business { font-size: 16px; opacity: 0.9; margin-bottom: 15px; }
    .profile-status {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        border: 2px solid rgba(255,255,255,0.3);
    }

    .profile-content { padding: 40px; }

    .form-section { margin-bottom: 40px; }
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
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .form-group { margin-bottom: 20px; }
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
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        outline: none;
    }

    .form-control:disabled {
        background: #f8fafc;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .form-hint { display: block; font-size: 12px; color: #94a3b8; margin-top: 6px; }

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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102,126,234,0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102,126,234,0.4);
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

    .stat-card:hover { transform: translateY(-5px); border-color: #667eea; }

    .stat-value {
        font-size: 32px;
        font-weight: 800;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 5px;
    }

    .stat-label { color: #64748b; font-weight: 600; font-size: 14px; }

    @media (max-width: 768px) {
        .profile-container { padding: 15px; }
        .profile-header { padding: 25px; }
        .header-title h1 { font-size: 24px; }
        .profile-avatar-wrapper { width: 120px; height: 120px; }
        .profile-content { padding: 25px; }
        .form-grid { grid-template-columns: 1fr; }
        .action-buttons { flex-direction: column; }
        .btn { width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="profile-container">

    <div class="profile-header">
        <div class="header-content">
            <div class="header-title">
                <h1>My Profile</h1>
                <p>Manage your account information and business details</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="profile-card">
                <div class="profile-sidebar">
                    <div class="profile-avatar-wrapper">
                        <img
                            src="{{ $wholesaler->profile_image_url }}"
                            alt="Profile"
                            class="profile-avatar"
                            id="profileAvatar"
                        >

                        <label class="avatar-upload" for="avatarInput" title="Change Photo">
                            <i class="fas fa-camera"></i>
                            <input
                                type="file"
                                id="avatarInput"
                                accept="image/*"
                                data-upload-url="{{ route('wholesaler.profile.avatar') }}"
                            >
                        </label>
                    </div>

                    <div class="profile-name">{{ $wholesaler->name }}</div>
                    <div class="profile-business">{{ $wholesaler->business_name }}</div>
                    <div class="profile-status">Wholesaler Account</div>
                </div>

                <div class="profile-content">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-value">{{ $stats['total_products'] }}</div>
                            <div class="stat-label">Products</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ $stats['total_orders'] }}</div>
                            <div class="stat-label">Orders</div>
                        </div>
                    </div>

                    <div class="form-section mt-4">
                        <h5 class="section-title">Account Info</h5>
                        <div class="form-group">
                            <label class="form-label">Account ID</label>
                            <input type="text" class="form-control" value="{{ $wholesaler->code }}" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="{{ $wholesaler->username }}" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Account Type</label>
                            <input type="text" class="form-control" value="{{ $wholesaler->type ?? 'Standard' }}" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="profile-card">
                <div class="profile-content">

                    <form id="profileForm"
                          action="{{ route('wholesaler.profile.update') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="form-section">
                            <h5 class="section-title">Personal Information</h5>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" name="name" class="form-control" value="{{ $wholesaler->name }}" required>
                                    <span class="form-hint">Your full legal name</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" name="email" class="form-control" value="{{ $wholesaler->email }}" required>
                                    <span class="form-hint">Primary contact email</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h5 class="section-title">Business Information</h5>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Business Name *</label>
                                    <input type="text" name="business_name" class="form-control" value="{{ $wholesaler->business_name }}" required>
                                    <span class="form-hint">Your registered business name</span>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Business Type</label>
                                    <select name="type" class="form-select">
                                        <option value="" {{ !$wholesaler->type ? 'selected' : '' }}>Select Type</option>
                                        <option value="distributor" {{ $wholesaler->type == 'distributor' ? 'selected' : '' }}>Distributor</option>
                                        <option value="manufacturer" {{ $wholesaler->type == 'manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                                        <option value="importer" {{ $wholesaler->type == 'importer' ? 'selected' : '' }}>Importer</option>
                                        <option value="wholesaler" {{ $wholesaler->type == 'wholesaler' ? 'selected' : '' }}>Wholesaler</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h5 class="section-title">Contact Information</h5>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $wholesaler->phone }}" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">WhatsApp Number</label>
                                    <input type="text" name="whatsapp" class="form-control" value="{{ $wholesaler->whatsapp }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h5 class="section-title">Address Information</h5>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Address *</label>
                                    <textarea name="address" class="form-control" rows="3" required>{{ $wholesaler->address }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">City *</label>
                                    <input type="text" name="city" class="form-control" value="{{ $wholesaler->city }}" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">District *</label>
                                    <input type="text" name="district" class="form-control" value="{{ $wholesaler->district }}" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Postal Code *</label>
                                    <input type="text" name="postal_code" class="form-control" value="{{ $wholesaler->postal_code }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h5 class="section-title">Profile Image (Optional)</h5>
                            <div class="form-group">
                                <label class="form-label">Upload New Image</label>
                                <input type="file" id="profileImage" name="img" accept="image/*" class="form-control">
                                <span class="form-hint">Max file size: 2MB. Supported: JPG, PNG, GIF, WebP</span>
                            </div>
                        </div>

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
    const profileAvatar = document.getElementById('profileAvatar');
    const saveBtn = document.getElementById('saveBtn');

    // =========================
    // 1) AVATAR UPLOAD (IMAGE ONLY)
    // =========================
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                showError('File size must be less than 2MB');
                avatarInput.value = '';
                return;
            }

            // Preview
            const reader = new FileReader();
            reader.onload = (ev) => profileAvatar.src = ev.target.result;
            reader.readAsDataURL(file);

            // Upload
            uploadAvatar(file);
        });
    }

    async function uploadAvatar(file) {
        const url = avatarInput.getAttribute('data-upload-url');

        const formData = new FormData();
        formData.append('img', file);

        try {
            const res = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const data = await res.json();

            if (!res.ok) {
                // if 422, show validation message
                showError(data.message || 'Avatar upload failed');
                return;
            }

            if (data.success) showSuccess(data.message || 'Profile image updated!');
            else showError(data.message || 'Avatar upload failed');

        } catch (err) {
            console.error(err);
            showError('Failed to upload image');
        }
    }

    // =========================
    // 2) FULL PROFILE SUBMIT
    // =========================
    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveBtn.disabled = true;

            const formData = new FormData(form);

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await res.json();

                if (!res.ok) {
                    // Laravel validation errors (422)
                    showError(data.message || 'Validation error');
                    return;
                }

                if (data.success) {
                    showSuccess(data.message || 'Profile updated!');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showError(data.message || 'Update failed');
                }

            } catch (err) {
                console.error(err);
                showError('An error occurred. Please try again.');
            } finally {
                saveBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                saveBtn.disabled = false;
            }
        });
    }

    // Alerts
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

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            background: '#fef2f2',
            iconColor: '#ef4444'
        });
    }
});
</script>
@endpush
