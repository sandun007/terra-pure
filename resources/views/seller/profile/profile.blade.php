@extends('seller.layout.app')

@push('title')
    My Profile
@endpush

@push('css')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #4f46e5;
            --primary-light: #e0e7ff;
            --secondary-color: #10b981;
            --accent-color: #8b5cf6;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --text-light: #9ca3af;
            --bg-white: #ffffff;
            --bg-light: #f9fafb;
            --bg-lighter: #f3f4f6;
            --border-light: #e5e7eb;
            --border-medium: #d1d5db;
            --success-light: #d1fae5;
            --warning-light: #fef3c7;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.15);
            --gradient-primary: linear-gradient(135deg, #4f46e5 0%, #8b5cf6 100%);
            --gradient-light: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        }

        body {
            background: var(--bg-light);
        }

        .profile-container {
            padding: 24px;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            min-height: 100vh;
            position: relative;
        }

        /* Header Styling */
        .profile-header {
            background: var(--bg-white);
            border-radius: 20px;
            padding: 40px 32px;
            margin-bottom: 32px;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border-light);
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .profile-header::after {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 300px;
            height: 300px;
            background: var(--primary-light);
            border-radius: 50%;
            opacity: 0.6;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .header-title h1 {
            color: var(--text-primary);
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }

        .header-title p {
            color: var(--text-secondary);
            font-size: 16px;
            font-weight: 400;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }

        .header-icon {
            color: var(--primary-color);
            font-size: 20px;
        }

        /* Profile Card */
        .profile-card {
            background: var(--bg-white);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            margin-bottom: 24px;
            transition: all 0.3s ease;
            border: 1px solid var(--border-light);
        }

        .profile-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }

        .profile-sidebar {
            background: var(--gradient-light);
            padding: 36px 28px;
            text-align: center;
            color: var(--text-primary);
            position: relative;
            min-height: 360px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-bottom: 1px solid var(--border-light);
        }

        .profile-avatar-wrapper {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto 24px;
        }

        .profile-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--bg-white);
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .profile-avatar-wrapper:hover .profile-avatar {
            transform: scale(1.05);
            box-shadow: var(--shadow-lg);
        }

        .avatar-upload {
            position: absolute;
            bottom: 8px;
            right: 8px;
            width: 40px;
            height: 40px;
            background: var(--bg-white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
            border: 2px solid var(--primary-light);
        }

        .avatar-upload:hover {
            transform: scale(1.1);
            background: var(--primary-light);
            border-color: var(--primary-color);
        }

        .avatar-upload input {
            display: none;
        }

        .avatar-upload .material-symbols-rounded {
            color: var(--primary-color);
            font-size: 20px;
            font-variation-settings: 'FILL' 0;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-primary);
        }

        .profile-location {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-weight: 500;
        }

        .location-icon {
            color: var(--primary-color);
            font-size: 16px;
        }

        .profile-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-white);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            border: 2px solid var(--border-light);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .profile-status:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .profile-status.active {
            background: var(--success-light);
            border-color: var(--secondary-color);
            color: var(--secondary-color);
        }

        .profile-status.pending {
            background: var(--warning-light);
            border-color: #f59e0b;
            color: #d97706;
        }

        .status-icon {
            font-size: 16px;
            font-variation-settings: 'FILL' 1;
        }

        .profile-content {
            padding: 36px;
        }

        /* Form Styling */
        .form-section {
            margin-bottom: 36px;
            position: relative;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--border-light);
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .section-title .material-symbols-rounded {
            color: var(--primary-color);
            font-size: 20px;
            font-variation-settings: 'FILL' 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .form-label .material-symbols-rounded {
            font-size: 16px;
            color: var(--primary-color);
        }

        .form-control, .form-select {
            width: 100%;
            border: 1.5px solid var(--border-light);
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 15px;
            color: var(--text-primary);
            background: var(--bg-white);
            transition: all 0.3s ease;
            font-weight: 400;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-light);
            outline: none;
        }

        .form-control:disabled {
            background: var(--bg-lighter);
            color: var(--text-light);
            cursor: not-allowed;
            border-color: var(--border-light);
        }

        .form-hint {
            display: block;
            font-size: 12px;
            color: var(--text-light);
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            line-height: 1.4;
        }

        .form-hint .material-symbols-rounded {
            font-size: 14px;
            flex-shrink: 0;
        }

        /* Custom Select */
        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
            padding-right: 40px;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 16px;
            justify-content: flex-end;
            margin-top: 36px;
            padding-top: 28px;
            border-top: 2px solid var(--border-light);
        }

        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 120px;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            background: #4338ca;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: var(--bg-lighter);
            color: var(--text-secondary);
            border: 1.5px solid var(--border-medium);
        }

        .btn-secondary:hover {
            background: var(--border-light);
            color: var(--text-primary);
            transform: translateY(-2px);
        }

        .btn .material-symbols-rounded {
            font-size: 18px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }

        .stat-card {
            background: var(--bg-white);
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            border: 1.5px solid var(--border-light);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: var(--primary-color);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            font-size: 28px;
            margin-bottom: 12px;
            color: var(--primary-color);
            font-variation-settings: 'FILL' 0;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
            line-height: 1;
        }

        .stat-label {
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 12px;
            letter-spacing: 0.3px;
        }

        /* Floating Action Button */
        .fab-save {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 56px;
            height: 56px;
            background: var(--primary-color);
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-lg);
            cursor: pointer;
            z-index: 1000;
            transition: all 0.3s ease;
            border: none;
        }

        .fab-save:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-xl);
        }

        .fab-save .material-symbols-rounded {
            color: white;
            font-size: 24px;
            font-variation-settings: 'FILL' 0;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .profile-container {
                padding: 20px;
            }

            .profile-sidebar, .profile-content {
                padding: 28px 24px;
            }
        }

        @media (max-width: 992px) {
            .profile-avatar-wrapper {
                width: 120px;
                height: 120px;
            }

            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .profile-container {
                padding: 16px;
            }

            .profile-header {
                padding: 28px 20px;
                margin-bottom: 24px;
            }

            .header-title h1 {
                font-size: 24px;
            }

            .profile-content {
                padding: 24px 20px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 12px;
                margin-top: 28px;
            }

            .btn {
                width: 100%;
                padding: 12px 20px;
            }

            .fab-save {
                display: flex;
            }

            .header-action-buttons {
                display: none;
            }

            .form-section {
                margin-bottom: 28px;
            }
        }

        @media (max-width: 576px) {
            .profile-sidebar {
                padding: 24px 20px;
                min-height: 320px;
            }

            .profile-avatar-wrapper {
                width: 100px;
                height: 100px;
            }

            .profile-name {
                font-size: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .fab-save {
                bottom: 20px;
                right: 20px;
                width: 48px;
                height: 48px;
            }

            .profile-header::after {
                display: none;
            }
        }

        /* Success/Error States */
        .success-border {
            border-color: var(--secondary-color) !important;
            background-color: var(--success-light) !important;
        }

        .error-border {
            border-color: #dc2626 !important;
            background-color: #fef2f2 !important;
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-lighter);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-medium);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-light);
        }

        /* Focus Visible */
        .form-control:focus-visible,
        .form-select:focus-visible,
        .btn:focus-visible {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Animation Classes */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .slide-in {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Card Hover Effects */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        /* Input Groups */
        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group .form-control {
            padding-left: 40px;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            color: var(--text-light);
            font-size: 18px;
            pointer-events: none;
        }

        /* Custom Checkbox */
        .custom-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .custom-checkbox input {
            display: none;
        }

        .custom-checkbox .checkmark {
            width: 18px;
            height: 18px;
            border: 2px solid var(--border-light);
            border-radius: 4px;
            position: relative;
            transition: all 0.3s ease;
        }

        .custom-checkbox input:checked + .checkmark {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .custom-checkbox input:checked + .checkmark::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <div class="profile-container fade-in">
        <!-- Header -->
        <div class="profile-header hover-lift">
            <div class="header-content">
                <div class="header-title">
                    <div>
                        <h1>My Profile</h1>
                        <p>
                            <span class="material-symbols-rounded header-icon">person</span>
                            Manage your account information and business details
                        </p>
                    </div>
                    <div class="header-action-buttons">
                        <button class="btn btn-primary" onclick="document.getElementById('profileForm').submit()">
                            <span class="material-symbols-rounded">save</span>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <!-- Profile Sidebar -->
                <div class="profile-card hover-lift">
                    <div class="profile-sidebar">
                        <div class="profile-avatar-wrapper">
                            <img src="{{ $seller->profile_image_url }}"
                                 alt="Profile"
                                 class="profile-avatar"
                                 id="profileAvatar"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($seller->name) }}&background=4f46e5&color=fff&size=200'">
                            <label class="avatar-upload" for="avatarInput" title="Change profile picture">
                                <span class="material-symbols-rounded">photo_camera</span>
                                <input type="file"
                                       id="avatarInput"
                                       accept="image/*"
                                       data-url="{{ route('profile.update') }}">
                            </label>
                        </div>

                        <div class="profile-name">{{ $seller->name }}</div>
                        <div class="profile-location">
                            <span class="material-symbols-rounded location-icon">location_on</span>
                            <span>{{ $seller->city }}, {{ $seller->district }}</span>
                        </div>
                        <div class="profile-status {{ strtolower($seller->status ?? 'pending') }}">
                            <span class="material-symbols-rounded status-icon">
                                {{ $seller->status == 'active' ? 'check_circle' : 'pending' }}
                            </span>
                            <span>{{ ucfirst($seller->status ?? 'Pending') }}</span>
                        </div>
                    </div>

                    <div class="profile-content">
                        <!-- Quick Stats -->
                        <div class="stats-grid">
                            <div class="stat-card hover-lift">
                                <span class="material-symbols-rounded stat-icon">shopping_bag</span>
                                <div class="stat-value">{{ $stats['total_orders'] }}</div>
                                <div class="stat-label">Total Orders</div>
                            </div>
                            <div class="stat-card hover-lift">
                                <span class="material-symbols-rounded stat-icon">inventory_2</span>
                                <div class="stat-value">{{ $stats['total_products'] }}</div>
                                <div class="stat-label">Products</div>
                            </div>
                            <div class="stat-card hover-lift">
                                <span class="material-symbols-rounded stat-icon">monetization_on</span>
                                <div class="stat-value">Rs. {{ number_format($stats['total_sales'], 2) }}</div>
                                <div class="stat-label">Total Sales</div>
                            </div>
                        </div>

                        <!-- Account Info -->
                        <div class="form-section mt-4">
                            <h5 class="section-title">
                                <span class="material-symbols-rounded">badge</span>
                                Account Information
                            </h5>
                            <div class="form-group">
                                <label class="form-label">
                                    <span class="material-symbols-rounded">fingerprint</span>
                                    Seller ID
                                </label>
                                <div class="input-group">
                                    <span class="material-symbols-rounded input-icon">tag</span>
                                    <input type="text" class="form-control" value="{{ $seller->code }}" disabled>
                                </div>
                                <span class="form-hint">
                                    <span class="material-symbols-rounded">info</span>
                                    Unique seller identifier
                                </span>
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    <span class="material-symbols-rounded">person</span>
                                    Username
                                </label>
                                <div class="input-group">
                                    <span class="material-symbols-rounded input-icon">person</span>
                                    <input type="text" class="form-control" value="{{ $seller->username }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    <span class="material-symbols-rounded">category</span>
                                    Seller Type
                                </label>
                                <div class="input-group">
                                    <span class="material-symbols-rounded input-icon">store</span>
                                    <input type="text" class="form-control" value="{{ $seller->type ?? 'Standard' }}" disabled>
                                </div>
                            </div>
                            @if($seller->NIC_no)
                                <div class="form-group">
                                    <label class="form-label">
                                        <span class="material-symbols-rounded">id_card</span>
                                        NIC Number
                                    </label>
                                    <div class="input-group">
                                        <span class="material-symbols-rounded input-icon">badge</span>
                                        <input type="text" class="form-control" value="{{ $seller->NIC_no }}" disabled>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <!-- Profile Form -->
                <div class="profile-card hover-lift slide-in">
                    <div class="profile-content">
                        <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Personal Information -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <span class="material-symbols-rounded">person</span>
                                    Personal Information
                                </h5>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <span class="material-symbols-rounded">person</span>
                                            Full Name *
                                        </label>
                                        <div class="input-group">
                                            <span class="material-symbols-rounded input-icon">badge</span>
                                            <input type="text"
                                                   name="name"
                                                   class="form-control"
                                                   value="{{ old('name', $seller->name) }}"
                                                   required
                                                   placeholder="Enter your full name">
                                        </div>
                                        <span class="form-hint">
                                            <span class="material-symbols-rounded">info</span>
                                            Your full legal name
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            <span class="material-symbols-rounded">email</span>
                                            Email Address *
                                        </label>
                                        <div class="input-group">
                                            <span class="material-symbols-rounded input-icon">mail</span>
                                            <input type="email"
                                                   name="email"
                                                   class="form-control"
                                                   value="{{ old('email', $seller->email) }}"
                                                   required
                                                   placeholder="you@example.com">
                                        </div>
                                        <span class="form-hint">
                                            <span class="material-symbols-rounded">mail</span>
                                            Primary contact email
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            <span class="material-symbols-rounded">badge</span>
                                            NIC Number
                                        </label>
                                        <div class="input-group">
                                            <span class="material-symbols-rounded input-icon">id_card</span>
                                            <input type="text"
                                                   name="NIC_no"
                                                   class="form-control"
                                                   value="{{ old('NIC_no', $seller->NIC_no) }}"
                                                   placeholder="e.g., 123456789V">
                                        </div>
                                        <span class="form-hint">
                                            <span class="material-symbols-rounded">info</span>
                                            National Identity Card number
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            <span class="material-symbols-rounded">category</span>
                                            Seller Type
                                        </label>
                                        <select name="type" class="form-select">
                                            <option value="">Select Type</option>
                                            <option value="individual" {{ old('type', $seller->type) == 'individual' ? 'selected' : '' }}>Individual</option>
                                            <option value="distributor" {{ old('type', $seller->type) == 'distributor' ? 'selected' : '' }}>Distributor</option>
                                            <option value="retailer" {{ old('type', $seller->type) == 'retailer' ? 'selected' : '' }}>Retailer</option>
                                            <option value="agent" {{ old('type', $seller->type) == 'agent' ? 'selected' : '' }}>Agent</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <span class="material-symbols-rounded">contact_page</span>
                                    Contact Information
                                </h5>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <span class="material-symbols-rounded">call</span>
                                            Phone Number *
                                        </label>
                                        <div class="input-group">
                                            <span class="material-symbols-rounded input-icon">phone</span>
                                            <input type="tel"
                                                   name="phone"
                                                   class="form-control"
                                                   value="{{ old('phone', $seller->phone) }}"
                                                   required
                                                   placeholder="+94 77 123 4567">
                                        </div>
                                        <span class="form-hint">
                                            <span class="material-symbols-rounded">phone</span>
                                            Primary contact number
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            <span class="material-symbols-rounded">chat</span>
                                            WhatsApp Number
                                        </label>
                                        <div class="input-group">
                                            <span class="material-symbols-rounded input-icon">whatsapp</span>
                                            <input type="tel"
                                                   name="whatsapp"
                                                   class="form-control"
                                                   value="{{ old('whatsapp', $seller->whatsapp) }}"
                                                   placeholder="+94 77 123 4567">
                                        </div>
                                        <span class="form-hint">
                                            <span class="material-symbols-rounded">whatsapp</span>
                                            For business communication
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <span class="material-symbols-rounded">location_on</span>
                                    Location Information
                                </h5>
                                <div class="form-grid">
                                    <div class="form-group" style="grid-column: 1 / -1;">
                                        <label class="form-label">
                                            <span class="material-symbols-rounded">home</span>
                                            Address *
                                        </label>
                                        <div class="input-group">
                                            <span class="material-symbols-rounded input-icon">home</span>
                                            <textarea name="address"
                                                      class="form-control"
                                                      rows="3"
                                                      required
                                                      placeholder="Enter your complete address">{{ old('address', $seller->address) }}</textarea>
                                        </div>
                                        <span class="form-hint">
                                            <span class="material-symbols-rounded">location_city</span>
                                            Full street address
                                        </span>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            <span class="material-symbols-rounded">location_city</span>
                                            City *
                                        </label>
                                        <div class="input-group">
                                            <span class="material-symbols-rounded input-icon">apartment</span>
                                            <input type="text"
                                                   name="city"
                                                   class="form-control"
                                                   value="{{ old('city', $seller->city) }}"
                                                   required
                                                   placeholder="e.g., Colombo">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            <span class="material-symbols-rounded">map</span>
                                            District *
                                        </label>
                                        <div class="input-group">
                                            <span class="material-symbols-rounded input-icon">map</span>
                                            <input type="text"
                                                   name="district"
                                                   class="form-control"
                                                   value="{{ old('district', $seller->district) }}"
                                                   required
                                                   placeholder="e.g., Colombo">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            <span class="material-symbols-rounded">public</span>
                                            State/Province
                                        </label>
                                        <div class="input-group">
                                            <span class="material-symbols-rounded input-icon">public</span>
                                            <input type="text"
                                                   name="state"
                                                   class="form-control"
                                                   value="{{ old('state', $seller->state) }}"
                                                   placeholder="e.g., Western Province">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            <span class="material-symbols-rounded">flag</span>
                                            Country
                                        </label>
                                        <div class="input-group">
                                            <span class="material-symbols-rounded input-icon">flag</span>
                                            <input type="text"
                                                   name="country"
                                                   class="form-control"
                                                   value="{{ old('country', $seller->country ?? 'Sri Lanka') }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            <span class="material-symbols-rounded">local_post_office</span>
                                            Postal Code *
                                        </label>
                                        <div class="input-group">
                                            <span class="material-symbols-rounded input-icon">markunread_mailbox</span>
                                            <input type="text"
                                                   name="postal_code"
                                                   class="form-control"
                                                   value="{{ old('postal_code', $seller->postal_code) }}"
                                                   required
                                                   placeholder="e.g., 00100">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Image Upload -->
                            <div class="form-section">
                                <h5 class="section-title">
                                    <span class="material-symbols-rounded">image</span>
                                    Profile Image
                                </h5>
                                <div class="form-group">
                                    <label class="form-label">
                                        <span class="material-symbols-rounded">upload</span>
                                        Upload New Image
                                    </label>
                                    <div class="custom-file-upload">
                                        <input type="file"
                                               id="profileImage"
                                               name="img"
                                               accept="image/*"
                                               class="form-control">
                                        <span class="form-hint">
                                            <span class="material-symbols-rounded">warning</span>
                                            Max file size: 2MB. Supported: JPG, PNG, GIF, WebP
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <button type="button" class="btn btn-secondary" onclick="window.location.reload()">
                                    <span class="material-symbols-rounded">close</span>
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary" id="saveBtn">
                                    <span class="material-symbols-rounded">save</span>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Action Button for Mobile -->
    <button class="fab-save" id="mobileSaveBtn" onclick="document.getElementById('profileForm').submit()" title="Save Changes">
        <span class="material-symbols-rounded">save</span>
    </button>
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
            const mobileSaveBtn = document.getElementById('mobileSaveBtn');

            // Initialize animations
            document.querySelectorAll('.slide-in').forEach((el, i) => {
                el.style.animationDelay = `${i * 100}ms`;
            });

            // Handle avatar upload
            if (avatarInput) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        if (!validateImageFile(file)) return;

                        // Preview image
                        previewImage(file, profileAvatar, 'Profile image updated!');

                        // Upload via AJAX
                        uploadAvatar(file);
                    }
                });
            }

            // Handle profile image upload
            if (profileImage) {
                profileImage.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && !validateImageFile(file)) {
                        this.value = '';
                    }
                });
            }

            // Handle form submission
            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    if (!validateForm()) return;

                    const formData = new FormData(this);
                    await submitForm(form, formData, profileAvatar);
                });
            }

            // Form validation
            function validateForm() {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    field.classList.remove('error-border');

                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('error-border');

                        // Scroll to first error
                        if (isValid) {
                            field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }

                        showError(`${field.labels[0].textContent.replace('*', '').trim()} is required`);
                    }
                });

                // Validate email
                const emailField = form.querySelector('input[type="email"]');
                if (emailField && emailField.value && !isValidEmail(emailField.value)) {
                    isValid = false;
                    emailField.classList.add('error-border');
                    showError('Please enter a valid email address');
                }

                return isValid;
            }

            // Image file validation
            function validateImageFile(file) {
                const maxSize = 2 * 1024 * 1024; // 2MB
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

                if (file.size > maxSize) {
                    showError('File size must be less than 2MB');
                    return false;
                }

                if (!validTypes.includes(file.type)) {
                    showError('Please upload a valid image file (JPG, PNG, GIF, WebP)');
                    return false;
                }

                return true;
            }

            // Email validation
            function isValidEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            // Preview image
            function previewImage(file, element, successMessage) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    element.src = e.target.result;
                    if (successMessage) showSuccess(successMessage);
                };
                reader.readAsDataURL(file);
            }

            // Upload avatar via AJAX
            function uploadAvatar(file) {
                const formData = new FormData();
                formData.append('img', file);

                fetch('{{ route("profile.update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(handleResponse)
                    .catch(handleError);
            }

            // Submit form
            async function submitForm(form, formData, avatarElement) {
                showLoading('Saving changes...');

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await handleResponse(response);

                    if (data.success) {
                        showSuccess(data.message);

                        // Update avatar if changed
                        if (formData.get('img') && formData.get('img').size > 0) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                avatarElement.src = e.target.result;
                            };
                            reader.readAsDataURL(formData.get('img'));
                        }

                        // Reload after success
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showError(data.message || 'Failed to save changes');
                    }
                } catch (error) {
                    handleError(error);
                } finally {
                    hideLoading();
                }
            }

            // Handle API response
            async function handleResponse(response) {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            }

            // Handle errors
            function handleError(error) {
                console.error('Error:', error);
                showError('An error occurred. Please try again.');
            }

            // Loading state management
            function showLoading(message) {
                saveBtn.innerHTML = '<div class="loading-spinner"></div> Saving...';
                saveBtn.disabled = true;
                if (mobileSaveBtn) {
                    mobileSaveBtn.disabled = true;
                    mobileSaveBtn.innerHTML = '<div class="loading-spinner"></div>';
                }

                Swal.fire({
                    title: message,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => Swal.showLoading()
                });
            }

            function hideLoading() {
                saveBtn.innerHTML = '<span class="material-symbols-rounded">save</span> Save Changes';
                saveBtn.disabled = false;
                if (mobileSaveBtn) {
                    mobileSaveBtn.disabled = false;
                    mobileSaveBtn.innerHTML = '<span class="material-symbols-rounded">save</span>';
                }
                Swal.close();
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
                    background: '#d1fae5',
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
                    timer: 4000,
                    timerProgressBar: true,
                    background: '#fef2f2',
                    iconColor: '#dc2626'
                });
            }

            // Add input validation feedback
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.classList.add('error-border');
                    } else if (this.value.trim()) {
                        this.classList.remove('error-border');
                        this.classList.add('success-border');
                        setTimeout(() => this.classList.remove('success-border'), 2000);
                    }
                });

                input.addEventListener('focus', function() {
                    this.classList.remove('error-border', 'success-border');
                });
            });

            // Device detection
            function updateDeviceLayout() {
                const isMobile = window.innerWidth <= 768;
                document.body.classList.toggle('is-mobile', isMobile);
                document.body.classList.toggle('is-desktop', !isMobile);
            }

            // Initial detection and listener
            updateDeviceLayout();
            window.addEventListener('resize', debounce(updateDeviceLayout, 250));

            // Debounce function
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    form.submit();
                }

                if (e.key === 'Escape') {
                    window.location.reload();
                }
            });
        });
    </script>
@endpush
