@extends('admin.app')
@push('title')
    Admin Profile
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

        .info-label {
            font-weight: 600;
            color: #888;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-weight: 500;
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        .btn-edit {
            background: var(--main-theme-color);
            color: #fff;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-edit:hover {
            background: #2a7def;
            transform: translateY(-2px);
            color: #fff;
            box-shadow: 0 5px 15px rgba(58, 141, 255, 0.3);
        }

        .status-text {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
            font-size: 0.9rem;
        }
    </style>
@endpush
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">My Profile</h4>
                <p>Your personal account information</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 text-white">Profile Overview</h5>
                    <div class="status-text {{ $isOnline ? 'text-white' : 'text-light' }}">
                        @if ($isOnline)
                            <span class="badge bg-success">Online</span>
                        @else
                            <span class="badge bg-secondary">Offline</span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-4 text-center border-end">
                            <div class="profile-img-wrap">
                                <img src="{{ $admin->img ? asset('storage/' . $admin->img) : asset('asset/img/default-avatar.png') }}"
                                    alt="Admin Profile">
                            </div>
                            <h4 class="mb-1">{{ $admin->name }}</h4>
                            <p class="text-primary fw-bold mb-3">Administrator</p>
                            <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary btn-sm mb-3">
                                <i class="ti ti-edit me-1"></i> Edit Profile
                            </a>
                            <p class="text-muted small">ID: #{{ $admin->id }}</p>
                        </div>
                        <div class="col-md-8 ps-md-5 pt-3">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="info-label">Full Name</div>
                                    <div class="info-value">{{ $admin->name }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-label">Username</div>
                                    <div class="info-value">@ {{ $admin->username }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value">{{ $admin->email }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-label">Phone Number</div>
                                    <div class="info-value">{{ $admin->phone ?? 'Not provided' }}</div>
                                </div>
                                <div class="col-12">
                                    <div class="info-label">Physical Address</div>
                                    <div class="info-value">{{ $admin->address ?? 'No address set' }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-label">Account Created</div>
                                    <div class="info-value">
                                        {{ $admin->created_at ? $admin->created_at->format('M d, Y') : 'N/A' }}</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="info-label">Last Updated</div>
                                    <div class="info-value">
                                        {{ $admin->updated_at ? $admin->updated_at->diffForHumans() : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
