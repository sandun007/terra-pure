@extends('admin.app')
@push('title')
    Create Admin
@endpush

@push('css')
    <style>
        .phone-img {
            position: relative;
            display: inline-block;
            margin: 10px;
        }

        .phone-img img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 3px solid #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .phone-img a {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #ff4d4d;
            color: white;
            border-radius: 50%;
            padding: 5px;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .image-preview-wrapper {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            border: 2px dashed #dee2e6;
        }
    </style>
@endpush

@section('content')
    <div class="page-header justify-content-between">
        <div class="page-title">
            <h4 class="fw-bold mb-1">Create New Administrator</h4>
            <h6 class="text-muted">Fill in the details to add a new system admin</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
                <i data-feather="users" class="me-2"></i>
                View Admin List
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger mb-4 border-0 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold">General Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Admin Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Enter full name">
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="admin@example.com">
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+1234567890">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}" required placeholder="username">
                                @error('username') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Address</label>
                                <textarea name="address" class="form-control" rows="3" placeholder="Enter physical address">{{ old('address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold">Security</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control" required placeholder="Minimum 6 characters">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i data-feather="eye" style="width: 16px;"></i>
                                    </button>
                                </div>
                                @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold">Profile Picture</h5>
                    </div>
                    <div class="card-body">
                        <div class="image-preview-wrapper mb-3">
                            <div id="imagePreviewContainer">
                                <div class="text-muted py-4">
                                    <i data-feather="user" style="width: 64px; height: 64px; opacity: 0.2;"></i>
                                    <p class="mt-2 text-center">No image selected</p>
                                </div>
                            </div>
                        </div>

                        <div class="image-upload">
                            <label for="imageInput" class="btn btn-outline-primary w-100">
                                <i data-feather="upload" class="me-2"></i> Select Image
                            </label>
                            <input type="file" id="imageInput" name="img" style="display: none;" accept="image/*">
                        </div>
                        <p class="text-center text-muted small mt-2">Allowed: JPG, PNG, GIF (Max: 2MB)</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 py-2 mb-2 fw-bold">
                            Create Administrator
                        </button>
                        <a href="{{ route('admin.admins.index') }}" class="btn btn-light w-100 py-2">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('script')
<script>
    // Image Preview
    document.getElementById('imageInput').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('imagePreviewContainer');

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewContainer.innerHTML = `
                    <div class="phone-img">
                        <img src="${e.target.result}" alt="New Preview">
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }
    });

    // Toggle Password Visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            this.innerHTML = '<i data-feather="eye-off" style="width: 16px;"></i>';
        } else {
            passwordInput.type = 'password';
            this.innerHTML = '<i data-feather="eye" style="width: 16px;"></i>';
        }
        feather.replace();
    });

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
