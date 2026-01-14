@extends('employee.layout.app')

@push('title')
    Employee Profile - {{ $employee->name }}
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        :root {
            --primary-color: #10b981;
            --primary-dark: #059669;
            --secondary-color: #3b82f6;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --success-color: #10b981;
            --info-color: #3b82f6;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --border-radius: 16px;
            --box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            --box-shadow-hover: 0 20px 60px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #f8fafc 100%);
            min-height: 100vh;
        }

        /* Header */
        .profile-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: var(--border-radius);
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--box-shadow);
            position: relative;
            overflow: hidden;
            animation: fadeInDown 0.6s ease;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }

        .header-title h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 400;
        }

        /* Main Container */
        .profile-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Profile Card */
        .profile-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            margin-bottom: 2rem;
            animation: slideUp 0.6s ease;
        }

        .profile-card:hover {
            box-shadow: var(--box-shadow-hover);
            transform: translateY(-5px);
        }

        /* Sidebar */
        .profile-sidebar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 3rem 2rem;
            text-align: center;
            color: white;
            position: relative;
            min-height: 100%;
        }

        .avatar-container {
            position: relative;
            width: 160px;
            height: 160px;
            margin: 0 auto 2rem;
        }

        .profile-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid rgba(255, 255, 255, 0.2);
            transition: var(--transition);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .avatar-overlay {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: var(--transition);
        }

        .avatar-container:hover .avatar-overlay {
            opacity: 1;
        }

        .avatar-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .avatar-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .avatar-btn:hover {
            transform: scale(1.1);
            background: var(--gray-100);
        }

        .avatar-btn.delete-btn {
            color: var(--danger-color);
        }

        .profile-info {
            margin-top: 2rem;
        }

        .profile-name {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .profile-role {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .role-badge {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-admin { background: rgba(239, 68, 68, 0.2); color: #fecaca; }
        .role-manager { background: rgba(245, 158, 11, 0.2); color: #fde68a; }
        .role-staff { background: rgba(16, 185, 129, 0.2); color: #a7f3d0; }

        .profile-status {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.6rem 1.5rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            border: 2px solid rgba(255, 255, 255, 0.3);
            margin-top: 1rem;
        }

        .status-active { color: #86efac; }
        .status-inactive { color: #fca5a5; }

        /* Content Area */
        .profile-content {
            padding: 2.5rem;
        }

        /* Section Headers */
        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--gray-100);
            position: relative;
        }

        .section-header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .section-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .section-subtitle {
            color: var(--gray-500);
            font-size: 0.95rem;
            margin-top: 0.25rem;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--gray-600);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label .required {
            color: var(--danger-color);
            margin-left: 4px;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 0.9rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 1rem;
            color: var(--dark-color);
            background: white;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
            background: linear-gradient(0deg, rgba(239, 68, 68, 0.05), rgba(239, 68, 68, 0.05)), #ffffff;
        }

        .form-control.is-valid {
            border-color: var(--success-color);
            background: linear-gradient(0deg, rgba(16, 185, 129, 0.05), rgba(16, 185, 129, 0.05)), #ffffff;
        }

        .input-group {
            position: relative;
            display: flex;
        }

        .input-group .form-control {
            flex: 1;
            border-right: none;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group-btn {
            background: var(--gray-100);
            border: 2px solid var(--gray-200);
            border-left: none;
            padding: 0 1rem;
            border-radius: 0 12px 12px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--gray-500);
            transition: var(--transition);
        }

        .input-group-btn:hover {
            background: var(--gray-200);
            color: var(--dark-color);
        }

        .form-hint {
            display: block;
            font-size: 0.85rem;
            color: var(--gray-500);
            margin-top: 0.5rem;
        }

        .error-message {
            display: block;
            font-size: 0.85rem;
            color: var(--danger-color);
            margin-top: 0.5rem;
        }

        /* Document Upload */
        .document-section {
            background: var(--gray-100);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .document-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .document-card {
            background: white;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: var(--transition);
        }

        .document-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.1);
        }

        .document-preview {
            width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 1rem;
            background: var(--gray-100);
            padding: 1rem;
        }

        .document-placeholder {
            width: 100%;
            height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: var(--gray-100);
            border-radius: 8px;
            margin-bottom: 1rem;
            color: var(--gray-400);
        }

        .document-placeholder i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .document-label {
            font-weight: 600;
            color: var(--gray-600);
            margin-bottom: 1rem;
            display: block;
        }

        .document-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        /* Stats Cards */
        .stats-section {
            margin-top: 3rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            border: 2px solid var(--gray-100);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
            box-shadow: var(--box-shadow);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--gray-500);
            font-weight: 600;
            font-size: 0.95rem;
        }

        /* Action Buttons */
        .action-section {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 2px solid var(--gray-100);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
        }

        .btn {
            padding: 0.9rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-width: 140px;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 5px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-600);
            border: 2px solid var(--gray-200);
        }

        .btn-secondary:hover:not(:disabled) {
            background: var(--gray-200);
            color: var(--dark-color);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-outline:hover:not(:disabled) {
            background: var(--primary-color);
            color: white;
        }

        .btn-sm {
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
            min-width: auto;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid var(--gray-200);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Toast Container */
        .toast-container {
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 9998;
        }

        .toast {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: var(--box-shadow);
            border-left: 4px solid;
            animation: slideInRight 0.3s ease;
            min-width: 320px;
            max-width: 400px;
        }

        .toast-success { border-left-color: var(--success-color); }
        .toast-error { border-left-color: var(--danger-color); }
        .toast-warning { border-left-color: var(--warning-color); }
        .toast-info { border-left-color: var(--info-color); }

        .toast-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 0.75rem;
        }

        .toast-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        .toast-icon.success { background: var(--success-color); }
        .toast-icon.error { background: var(--danger-color); }
        .toast-icon.warning { background: var(--warning-color); }
        .toast-icon.info { background: var(--info-color); }

        .toast-title {
            font-weight: 600;
            color: var(--dark-color);
            flex: 1;
        }

        .toast-close {
            background: none;
            border: none;
            color: var(--gray-400);
            cursor: pointer;
            font-size: 1.2rem;
            transition: var(--transition);
        }

        .toast-close:hover {
            color: var(--danger-color);
        }

        .toast-message {
            color: var(--gray-600);
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 992px) {
            .profile-header {
                padding: 2rem 1rem;
            }

            .header-title h1 {
                font-size: 2rem;
            }

            .avatar-container {
                width: 140px;
                height: 140px;
            }

            .profile-content {
                padding: 2rem;
            }

            .document-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .profile-sidebar {
                padding: 2rem 1rem;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .action-section {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .toast {
                min-width: 280px;
                right: 10px;
                left: 10px;
            }
        }

        @media (max-width: 576px) {
            .profile-container {
                padding: 0 0.5rem;
            }

            .profile-header {
                border-radius: 12px;
                margin-bottom: 1rem;
            }

            .profile-card {
                border-radius: 12px;
            }

            .profile-content {
                padding: 1.5rem;
            }

            .document-section {
                padding: 1.5rem;
            }
        }

        /* Additional Styles */
        .password-strength {
            margin-top: 0.5rem;
        }

        .strength-bar {
            height: 4px;
            background: var(--gray-200);
            border-radius: 2px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            background: var(--danger-color);
            transition: width 0.3s ease;
        }

        .strength-fill.weak { width: 25%; background: var(--danger-color); }
        .strength-fill.fair { width: 50%; background: var(--warning-color); }
        .strength-fill.good { width: 75%; background: var(--info-color); }
        .strength-fill.strong { width: 100%; background: var(--success-color); }

        .strength-text {
            font-size: 0.85rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
        }

        .last-updated {
            font-size: 0.85rem;
            color: var(--gray-400);
            text-align: right;
            margin-top: 1rem;
            font-style: italic;
        }

        .account-details {
            background: var(--gray-100);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--gray-200);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--gray-600);
        }

        .detail-value {
            color: var(--dark-color);
            font-weight: 500;
        }

        .qr-code {
            text-align: center;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            border: 2px solid var(--gray-200);
            margin-top: 2rem;
        }

        .qr-code img {
            max-width: 150px;
            height: auto;
        }
    </style>
@endpush

@section('content')
    <div class="profile-container">
        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loading-spinner"></div>
        </div>

        <!-- Toast Container -->
        <div class="toast-container" id="toastContainer"></div>

        <!-- Header -->
        <div class="profile-header">
            <div class="header-content">
                <div class="header-title">
                    <h1><i class="fas fa-user-circle"></i> Employee Profile</h1>
                    <p class="header-subtitle">Manage your account information and preferences</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Column -->
            <div class="col-lg-4 mb-4">
                <div class="profile-card h-100">
                    <div class="profile-sidebar">
                        <!-- Avatar -->
                        <div class="avatar-container">
                            <img src="{{ $employee->profile_image_url }}"
                                 alt="Profile"
                                 class="profile-avatar"
                                 id="profileAvatar">
                            <div class="avatar-overlay">
                                <div class="avatar-actions">
                                    <button class="avatar-btn" onclick="document.getElementById('profileImageInput').click()"
                                            title="Upload new photo">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                    @if($employee->img)
                                        <button class="avatar-btn delete-btn" onclick="deleteProfileImage()"
                                                title="Remove photo">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <input type="file"
                                   id="profileImageInput"
                                   accept="image/*"
                                   style="display: none;"
                                   onchange="uploadProfileImage(this)">
                        </div>

                        <!-- Employee Info -->
                        <div class="profile-info">
                            <h2 class="profile-name">{{ $employee->name }}</h2>
                            <div class="profile-role">
                                <span>{{ ucfirst($employee->role) }}</span>
                                <span class="role-badge role-{{ $employee->role }}">
                                    {{ $employee->role }}
                                </span>
                            </div>
                            <div class="profile-status status-{{ $employee->status }}">
                                <i class="fas fa-circle fa-xs"></i> {{ ucfirst($employee->status) }}
                            </div>
                        </div>

                        <!-- Account Details -->
                        <div class="account-details mt-4">
                            <div class="detail-item">
                                <span class="detail-label">Employee ID:</span>
                                <span class="detail-value">{{ $employee->code }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Username:</span>
                                <span class="detail-value">{{ $employee->username }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Last Login:</span>
                                <span class="detail-value">
                                    @if($employee->last_login_at)
                                        {{ $employee->last_login_at->format('M d, Y H:i') }}
                                    @else
                                        Never
                                    @endif
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Member Since:</span>
                                <span class="detail-value">{{ $employee->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <!-- QR Code (Optional) -->
                        <div class="qr-code">
                            <div class="mb-2">
                                <i class="fas fa-qrcode fa-2x text-muted"></i>
                            </div>
                            <small class="text-muted">Employee ID: {{ $employee->code }}</small>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="stats-section">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Performance</h3>
                            <p class="section-subtitle">Your activity statistics</p>
                        </div>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-value">{{ $stats['total_price_changes'] }}</div>
                            <div class="stat-label">Price Changes</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-value">{{ $stats['this_month_changes'] }}</div>
                            <div class="stat-label">This Month</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="stat-value">{{ $stats['products_updated'] }}</div>
                            <div class="stat-label">Products Updated</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="stat-value">{{ $stats['total_orders_processed'] }}</div>
                            <div class="stat-label">Total Orders</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Column -->
            <div class="col-lg-8">
                <div class="profile-card">
                    <div class="profile-content">
                        <form id="profileForm" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')

                            <!-- Personal Information -->
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Personal Information</h3>
                                    <p class="section-subtitle">Update your personal details</p>
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">
                                        Full Name <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="name"
                                           class="form-control"
                                           value="{{ old('name', $employee->name) }}"
                                           required
                                           placeholder="Enter your full name">
                                    <div class="error-message" id="nameError"></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Email Address <span class="required">*</span>
                                    </label>
                                    <input type="email"
                                           name="email"
                                           class="form-control"
                                           value="{{ old('email', $employee->email) }}"
                                           required
                                           placeholder="your.email@example.com">
                                    <div class="error-message" id="emailError"></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        NIC Number <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="nic_no"
                                           class="form-control"
                                           value="{{ old('nic_no', $employee->nic_no) }}"
                                           required
                                           placeholder="123456789V">
                                    <div class="error-message" id="nic_noError"></div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Contact Information</h3>
                                    <p class="section-subtitle">Update your contact details</p>
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">
                                        Phone Number <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="phone"
                                           class="form-control"
                                           value="{{ old('phone', $employee->phone) }}"
                                           required
                                           placeholder="+94 77 123 4567">
                                    <div class="error-message" id="phoneError"></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Mobile Number <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="mobile_no"
                                           class="form-control"
                                           value="{{ old('mobile_no', $employee->mobile_no) }}"
                                           required
                                           placeholder="+94 77 123 4567">
                                    <div class="error-message" id="mobile_noError"></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">WhatsApp Number</label>
                                    <input type="text"
                                           name="whatsapp_no"
                                           class="form-control"
                                           value="{{ old('whatsapp_no', $employee->whatsapp_no) }}"
                                           placeholder="+94 77 123 4567">
                                    <div class="error-message" id="whatsapp_noError"></div>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Address Information</h3>
                                    <p class="section-subtitle">Update your residential address</p>
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">
                                        Address <span class="required">*</span>
                                    </label>
                                    <textarea name="address"
                                              class="form-control"
                                              rows="3"
                                              required
                                              placeholder="Enter your full address">{{ old('address', $employee->address) }}</textarea>
                                    <div class="error-message" id="addressError"></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        District <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="district"
                                           class="form-control"
                                           value="{{ old('district', $employee->district) }}"
                                           required
                                           placeholder="Colombo">
                                    <div class="error-message" id="districtError"></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Postal Code <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           name="post_code"
                                           class="form-control"
                                           value="{{ old('post_code', $employee->post_code) }}"
                                           required
                                           placeholder="10100">
                                    <div class="error-message" id="post_codeError"></div>
                                </div>
                            </div>

                            <!-- Document Upload -->
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-file-upload"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Document Upload</h3>
                                    <p class="section-subtitle">Upload required documents</p>
                                </div>
                            </div>

                            <div class="document-section">
                                <div class="document-grid">
                                    <!-- NIC Front -->
                                    <div class="document-card">
                                        <div class="document-label">
                                            <i class="fas fa-id-card me-2"></i> NIC Front Image
                                        </div>
                                        @if($employee->nic_front)
                                            <img src="{{ $employee->nic_front_url }}"
                                                 alt="NIC Front"
                                                 class="document-preview"
                                                 id="nicFrontPreview">
                                        @else
                                            <div class="document-placeholder" id="nicFrontPlaceholder">
                                                <i class="fas fa-id-card"></i>
                                                <div>No Image Uploaded</div>
                                            </div>
                                        @endif
                                        <div class="document-actions">
                                            <button type="button"
                                                    class="btn btn-outline btn-sm"
                                                    onclick="document.getElementById('nicFrontInput').click()">
                                                <i class="fas fa-upload me-2"></i> Upload
                                            </button>
                                            @if($employee->nic_front)
                                                <button type="button"
                                                        class="btn btn-secondary btn-sm"
                                                        onclick="clearDocument('nicFront')">
                                                    <i class="fas fa-times me-2"></i> Clear
                                                </button>
                                            @endif
                                        </div>
                                        <input type="file"
                                               name="nic_front"
                                               id="nicFrontInput"
                                               accept="image/*"
                                               class="d-none"
                                               onchange="previewDocument(this, 'nicFront')">
                                    </div>

                                    <!-- NIC Back -->
                                    <div class="document-card">
                                        <div class="document-label">
                                            <i class="fas fa-id-card me-2"></i> NIC Back Image
                                        </div>
                                        @if($employee->nic_back)
                                            <img src="{{ $employee->nic_back_url }}"
                                                 alt="NIC Back"
                                                 class="document-preview"
                                                 id="nicBackPreview">
                                        @else
                                            <div class="document-placeholder" id="nicBackPlaceholder">
                                                <i class="fas fa-id-card"></i>
                                                <div>No Image Uploaded</div>
                                            </div>
                                        @endif
                                        <div class="document-actions">
                                            <button type="button"
                                                    class="btn btn-outline btn-sm"
                                                    onclick="document.getElementById('nicBackInput').click()">
                                                <i class="fas fa-upload me-2"></i> Upload
                                            </button>
                                            @if($employee->nic_back)
                                                <button type="button"
                                                        class="btn btn-secondary btn-sm"
                                                        onclick="clearDocument('nicBack')">
                                                    <i class="fas fa-times me-2"></i> Clear
                                                </button>
                                            @endif
                                        </div>
                                        <input type="file"
                                               name="nic_back"
                                               id="nicBackInput"
                                               accept="image/*"
                                               class="d-none"
                                               onchange="previewDocument(this, 'nicBack')">
                                    </div>
                                </div>
                                <div class="form-hint text-center mt-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Supported formats: JPG, PNG. Maximum size: 2MB per file
                                </div>
                            </div>

                            <!-- Password Change -->
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Change Password</h3>
                                    <p class="section-subtitle">Update your password (optional)</p>
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Current Password</label>
                                    <div class="input-group">
                                        <input type="password"
                                               name="current_password"
                                               class="form-control"
                                               id="currentPassword"
                                               placeholder="Enter current password"
                                               autocomplete="current-password">
                                        <button type="button"
                                                class="input-group-btn"
                                                onclick="togglePassword('currentPassword')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="error-message" id="current_passwordError"></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">New Password</label>
                                    <div class="input-group">
                                        <input type="password"
                                               name="password"
                                               class="form-control"
                                               id="newPassword"
                                               placeholder="Enter new password"
                                               autocomplete="new-password"
                                               oninput="checkPasswordStrength(this.value)">
                                        <button type="button"
                                                class="input-group-btn"
                                                onclick="togglePassword('newPassword')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="password-strength">
                                        <div class="strength-bar">
                                            <div class="strength-fill" id="strengthFill"></div>
                                        </div>
                                        <div class="strength-text" id="strengthText"></div>
                                    </div>
                                    <div class="error-message" id="passwordError"></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password"
                                               name="password_confirmation"
                                               class="form-control"
                                               id="confirmPassword"
                                               placeholder="Confirm new password"
                                               autocomplete="new-password">
                                        <button type="button"
                                                class="input-group-btn"
                                                onclick="togglePassword('confirmPassword')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="error-message" id="password_confirmationError"></div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-section">
                                <button type="button"
                                        class="btn btn-secondary"
                                        onclick="resetForm()">
                                    <i class="fas fa-redo me-2"></i> Reset
                                </button>
                                <button type="submit"
                                        class="btn btn-primary"
                                        id="saveBtn">
                                    <i class="fas fa-save me-2"></i> Save Changes
                                </button>
                            </div>

                            <div class="last-updated">
                                Last updated: {{ $employee->updated_at->diffForHumans() }}
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
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // Global variables
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        const updateUrl = '{{ route("employee.profile.update") }}';
        const dataUrl = '{{ route("employee.profile.data") }}';
        const imageUploadUrl = '{{ route("employee.profile.image.upload") }}';
        const imageDeleteUrl = '{{ route("employee.profile.image.delete") }}';

        // Toast system
        function showToast(type, title, message, duration = 5000) {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();

            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div class="toast-header">
                    <div class="toast-icon ${type}">
                        <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : type === 'warning' ? 'exclamation' : 'info'}"></i>
                    </div>
                    <div class="toast-title">${title}</div>
                    <button type="button" class="toast-close" onclick="document.getElementById('${toastId}').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="toast-message">${message}</div>
            `;

            toastContainer.appendChild(toast);

            // Auto remove
            setTimeout(() => {
                const toastEl = document.getElementById(toastId);
                if (toastEl) {
                    toastEl.style.animation = 'slideInRight 0.3s ease reverse';
                    setTimeout(() => toastEl.remove(), 300);
                }
            }, duration);
        }

        // Loading control
        function showLoading() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').style.display = 'none';
        }

        // Form validation
        function validateForm() {
            let isValid = true;
            clearErrors();

            // Required fields validation
            const requiredFields = ['name', 'email', 'phone', 'mobile_no', 'address', 'district', 'post_code', 'nic_no'];
            requiredFields.forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (!input.value.trim()) {
                    markError(field, 'This field is required');
                    isValid = false;
                }
            });

            // Email validation
            const email = document.querySelector('[name="email"]').value;
            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                markError('email', 'Please enter a valid email address');
                isValid = false;
            }

            // Password validation
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (newPassword || confirmPassword) {
                if (!currentPassword) {
                    markError('current_password', 'Current password is required to change password');
                    isValid = false;
                }

                if (newPassword.length < 8) {
                    markError('password', 'Password must be at least 8 characters');
                    isValid = false;
                }

                if (newPassword !== confirmPassword) {
                    markError('password_confirmation', 'Passwords do not match');
                    isValid = false;
                }
            }

            return isValid;
        }

        function clearErrors() {
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
        }

        function markError(field, message) {
            const input = document.querySelector(`[name="${field}"]`);
            const errorEl = document.getElementById(field + 'Error');

            if (input) {
                input.classList.add('is-invalid');
            }
            if (errorEl) {
                errorEl.textContent = message;
            }
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;
            let text = '';
            let className = '';

            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            switch(strength) {
                case 0:
                case 1:
                    text = 'Very Weak';
                    className = 'weak';
                    break;
                case 2:
                    text = 'Weak';
                    className = 'weak';
                    break;
                case 3:
                    text = 'Fair';
                    className = 'fair';
                    break;
                case 4:
                    text = 'Good';
                    className = 'good';
                    break;
                case 5:
                    text = 'Strong';
                    className = 'strong';
                    break;
            }

            strengthFill.className = 'strength-fill ' + className;
            strengthText.textContent = text;
        }

        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.parentElement.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        }

        // Document preview
        function previewDocument(input, type) {
            const file = input.files[0];
            if (!file) return;

            // Validate file
            if (file.size > 2 * 1024 * 1024) {
                showToast('error', 'File too large', 'Maximum file size is 2MB');
                input.value = '';
                return;
            }

            if (!file.type.match('image.*')) {
                showToast('error', 'Invalid file', 'Please select an image file');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const previewId = type + 'Preview';
                const placeholderId = type + 'Placeholder';

                let preview = document.getElementById(previewId);
                const placeholder = document.getElementById(placeholderId);

                if (!preview) {
                    preview = document.createElement('img');
                    preview.id = previewId;
                    preview.className = 'document-preview';
                    placeholder.parentNode.insertBefore(preview, placeholder);
                }

                preview.src = e.target.result;
                preview.style.display = 'block';

                if (placeholder) {
                    placeholder.style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
        }

        function clearDocument(type) {
            const input = document.getElementById(type + 'Input');
            const preview = document.getElementById(type + 'Preview');
            const placeholder = document.getElementById(type + 'Placeholder');

            if (input) input.value = '';
            if (preview) preview.style.display = 'none';
            if (placeholder) placeholder.style.display = 'flex';
        }

        // Profile image upload
        function uploadProfileImage(input) {
            const file = input.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                showToast('error', 'File too large', 'Maximum file size is 2MB');
                return;
            }

            const formData = new FormData();
            formData.append('img', file);

            showLoading();

            fetch(imageUploadUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        document.getElementById('profileAvatar').src = data.image_url;
                        showToast('success', 'Success', 'Profile image updated successfully');
                    } else {
                        showToast('error', 'Error', data.message || 'Failed to upload image');
                    }
                })
                .catch(error => {
                    hideLoading();
                    showToast('error', 'Error', 'Network error. Please try again.');
                    console.error('Error:', error);
                });
        }

        // Delete profile image
        function deleteProfileImage() {
            Swal.fire({
                title: 'Delete Profile Image?',
                text: 'Are you sure you want to remove your profile picture?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();

                    fetch(imageDeleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            hideLoading();
                            if (data.success) {
                                document.getElementById('profileAvatar').src = data.default_image;
                                showToast('success', 'Success', 'Profile image removed successfully');
                            } else {
                                showToast('error', 'Error', data.message || 'Failed to delete image');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            showToast('error', 'Error', 'Network error. Please try again.');
                            console.error('Error:', error);
                        });
                }
            });
        }

        // Form submission
        document.getElementById('profileForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!validateForm()) {
                showToast('error', 'Validation Error', 'Please fix the errors in the form');
                return;
            }

            const saveBtn = document.getElementById('saveBtn');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving...';
            saveBtn.disabled = true;

            const formData = new FormData(this);

            showLoading();

            try {
                const response = await fetch(updateUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showToast('success', 'Success', data.message);

                    // Update form fields with returned data
                    if (data.employee) {
                        Object.keys(data.employee).forEach(key => {
                            const input = document.querySelector(`[name="${key}"]`);
                            if (input && input.type !== 'file' && input.type !== 'password') {
                                input.value = data.employee[key];
                            }
                        });

                        // Update profile image if available
                        if (data.employee.profile_image_url) {
                            document.getElementById('profileAvatar').src = data.employee.profile_image_url;
                        }

                        // Update displayed name
                        document.querySelector('.profile-name').textContent = data.employee.name;
                    }

                    // Clear password fields
                    document.getElementById('currentPassword').value = '';
                    document.getElementById('newPassword').value = '';
                    document.getElementById('confirmPassword').value = '';

                    // Clear password strength indicator
                    document.getElementById('strengthFill').className = 'strength-fill';
                    document.getElementById('strengthText').textContent = '';

                } else {
                    // Display validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            markError(field, data.errors[field][0]);
                        });
                        showToast('error', 'Validation Error', 'Please fix the errors in the form');
                    } else {
                        showToast('error', 'Error', data.message || 'An error occurred');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('error', 'Network Error', 'Unable to connect to server. Please try again.');
            } finally {
                hideLoading();
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }
        });

        // Reset form
        function resetForm() {
            Swal.fire({
                title: 'Reset Form?',
                text: 'All unsaved changes will be lost.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, reset',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();

                    fetch(dataUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            hideLoading();
                            if (data.success) {
                                const employee = data.employee;

                                // Update form fields
                                const fields = ['name', 'email', 'phone', 'mobile_no', 'whatsapp_no',
                                    'address', 'district', 'post_code', 'nic_no'];
                                fields.forEach(field => {
                                    const input = document.querySelector(`[name="${field}"]`);
                                    if (input) input.value = employee[field] || '';
                                });

                                // Clear password fields
                                document.getElementById('currentPassword').value = '';
                                document.getElementById('newPassword').value = '';
                                document.getElementById('confirmPassword').value = '';

                                // Clear errors
                                clearErrors();

                                // Clear password strength
                                document.getElementById('strengthFill').className = 'strength-fill';
                                document.getElementById('strengthText').textContent = '';

                                showToast('success', 'Form Reset', 'Form has been reset to original values');
                            } else {
                                showToast('error', 'Error', data.message || 'Failed to load data');
                            }
                        })
                        .catch(error => {
                            hideLoading();
                            console.error('Error:', error);
                            showToast('error', 'Error', 'Failed to reset form. Please try again.');
                        });
                }
            });
        }

        // Real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            // Add input validation on blur
            document.querySelectorAll('.form-control').forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });

                input.addEventListener('input', function() {
                    if (this.classList.contains('is-invalid')) {
                        this.classList.remove('is-invalid');
                        const errorId = this.name + 'Error';
                        const errorEl = document.getElementById(errorId);
                        if (errorEl) errorEl.textContent = '';
                    }
                });
            });

            // Welcome message
            setTimeout(() => {
                showToast('info', 'Welcome back!', `Hello ${'{{ $employee->name }}'}, you can update your profile here.`);
            }, 1000);
        });

        function validateField(input) {
            const value = input.value.trim();
            const name = input.name;

            // Clear previous error
            input.classList.remove('is-invalid');
            const errorEl = document.getElementById(name + 'Error');
            if (errorEl) errorEl.textContent = '';

            // Required validation
            if (!value && ['name', 'email', 'phone', 'mobile_no', 'address', 'district', 'post_code', 'nic_no'].includes(name)) {
                markError(name, 'This field is required');
                return false;
            }

            // Email validation
            if (name === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                markError(name, 'Please enter a valid email address');
                return false;
            }

            return true;
        }

        // Auto-save draft (optional feature)
        let autoSaveTimeout;
        function autoSaveDraft() {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                const formData = new FormData(document.getElementById('profileForm'));
                // Remove files from auto-save
                formData.delete('img');
                formData.delete('nic_front');
                formData.delete('nic_back');

                // Save to localStorage
                const draft = {};
                formData.forEach((value, key) => {
                    if (key !== '_token' && key !== '_method') {
                        draft[key] = value;
                    }
                });

                localStorage.setItem('employeeProfileDraft', JSON.stringify(draft));
                console.log('Draft auto-saved');
            }, 2000);
        }

        // Load draft on page load
        window.addEventListener('load', function() {
            const draft = localStorage.getItem('employeeProfileDraft');
            if (draft) {
                const data = JSON.parse(draft);
                Object.keys(data).forEach(key => {
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input && !input.value) {
                        input.value = data[key];
                    }
                });
                localStorage.removeItem('employeeProfileDraft');
            }
        });

        // Add auto-save listeners
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', autoSaveDraft);
        });
    </script>
@endpush
