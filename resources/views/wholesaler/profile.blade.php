@extends('wholesaler.app')
@push('title')
    My Profile
@endpush
@push('css')
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-title h4 {
            color: white;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .profile-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px;
            text-align: center;
            color: white;
            position: relative;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid rgba(255,255,255,0.3);
            object-fit: cover;
            margin-bottom: 20px;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .profile-business {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 10px;
        }

        .profile-badge {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
        }

        .profile-body {
            padding: 40px;
        }

        .form-label {
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .profile-stats {
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
            border-color: #667eea;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .image-upload {
            position: relative;
            display: inline-block;
        }

        .image-upload input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .image-upload-label {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-upload-label:hover {
            transform: translateY(-2px);
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="page-title">
            <h4 class="fw-bold">My Profile</h4>
            <h6>Manage your account information</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="profile-card">
                <div class="profile-header">
                    @if($wholesaler->img)
                        <img src="{{ asset('storage/' . $wholesaler->img) }}" alt="Profile" class="profile-avatar">
                    @else
                        <div class="profile-avatar bg-white d-flex align-items-center justify-content-center mx-auto">
                            <i class="fas fa-user-tie fa-3x text-primary"></i>
                        </div>
                    @endif
                    <div class="profile-name">{{ $wholesaler->name }}</div>
                    <div class="profile-business">{{ $wholesaler->business_name }}</div>
                    <div class="profile-badge">Wholesaler Account</div>
                </div>
                <div class="profile-body">
                    <div class="profile-stats">
                        <div class="stat-card">
                            <div class="stat-value">{{ $totalProducts }}</div>
                            <div class="stat-label">Total Products</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">{{ $totalOrders }}</div>
                            <div class="stat-label">Total Orders</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="profile-card">
                <div class="profile-body">
                    <h5 class="mb-4">Profile Information</h5>
                    <form action="{{ route('wholesaler.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $wholesaler->name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ $wholesaler->email }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ $wholesaler->phone }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Business Name</label>
                                <input type="text" name="business_name" class="form-control" value="{{ $wholesaler->business_name }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Business Registration No</label>
                                <input type="text" name="business_reg_no" class="form-control" value="{{ $wholesaler->business_reg_no }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tax ID</label>
                                <input type="text" name="tax_id" class="form-control" value="{{ $wholesaler->tax_id }}">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="3" required>{{ $wholesaler->address }}</textarea>
                            </div>
                            <div class="col-12 mb-4">
                                <label class="form-label">Profile Image</label>
                                <div class="image-upload">
                                    <input type="file" name="img" accept="image/*">
                                    <div class="image-upload-label">
                                        <i class="fas fa-camera me-2"></i>Upload New Image
                                    </div>
                                </div>
                                @if($wholesaler->img)
                                    <small class="text-muted mt-2 d-block">Current image: {{ basename($wholesaler->img) }}</small>
                                @endif
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-save">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image preview functionality
            const imageUpload = document.querySelector('input[name="img"]');
            if (imageUpload) {
                imageUpload.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // You can add image preview functionality here
                            console.log('Image selected:', file.name);
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endpush
