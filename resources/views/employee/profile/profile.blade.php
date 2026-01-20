@extends('employee.layout.app')

@push('title')
    Employee Profile - {{ $employee->name }}
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #14b8a6;
            --accent: #f59e0b;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #3b82f6;
            --dark: #0f172a;
            --light: #f8fafc;
            --gray-50: #f9fafb;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .profile-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* Modern Header Card */
        .header-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
            animation: fadeInDown 0.6s ease;
        }

        .header-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--primary), var(--secondary), var(--accent));
            background-size: 200% 100%;
            animation: gradientShift 3s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .header-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .header-text h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .header-text p {
            color: var(--gray-600);
            font-size: 1.05rem;
        }

        /* Main Grid Layout */
        .profile-grid {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        /* Sidebar Card */
        .sidebar-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: slideInLeft 0.6s ease;
            height: fit-content;
            position: sticky;
            top: 2rem;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Profile Header Section */
        .profile-header-section {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 3rem 2rem 2rem;
            text-align: center;
            position: relative;
        }

        .profile-header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        /* Avatar Container */
        .avatar-wrapper {
            position: relative;
            width: 160px;
            height: 160px;
            margin: 0 auto 1.5rem;
            z-index: 2;
        }

        .avatar-wrapper::before {
            content: '';
            position: absolute;
            inset: -5px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.4));
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.6; }
            50% { transform: scale(1.05); opacity: 1; }
        }

        .profile-avatar {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            z-index: 1;
            transition: all 0.3s ease;
        }

        .avatar-wrapper:hover .profile-avatar {
            transform: scale(1.05);
        }

        .avatar-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 2;
            gap: 15px;
        }

        .avatar-wrapper:hover .avatar-overlay {
            opacity: 1;
        }

        .avatar-btn {
            width: 48px;
            height: 48px;
            background: white;
            border: none;
            border-radius: 50%;
            color: var(--primary);
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .avatar-btn:hover {
            transform: scale(1.15) rotate(10deg);
            background: var(--primary);
            color: white;
        }

        .avatar-btn.delete {
            color: var(--danger);
        }

        .avatar-btn.delete:hover {
            background: var(--danger);
            color: white;
        }

        /* Profile Name & Role */
        .profile-name {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.75rem;
            position: relative;
            z-index: 1;
        }

        .profile-badges {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .role-badge {
            padding: 0.5rem 1.25rem;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 30px;
            color: white;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-badge {
            padding: 0.5rem 1.25rem;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .status-badge.active {
            background: rgba(16, 185, 129, 0.2);
            color: #86efac;
            border: 2px solid rgba(16, 185, 129, 0.3);
        }

        .status-badge.inactive {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border: 2px solid rgba(239, 68, 68, 0.3);
        }

        .status-badge.active i {
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Profile Details */
        .profile-details {
            padding: 2rem;
        }

        .detail-group {
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: var(--gray-50);
            border-radius: 12px;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .detail-item:hover {
            background: white;
            border-left-color: var(--primary);
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .detail-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .detail-content {
            flex: 1;
            min-width: 0;
        }

        .detail-label {
            font-size: 0.8rem;
            color: var(--gray-500);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-size: 1rem;
            color: var(--gray-900);
            font-weight: 600;
            word-wrap: break-word;
        }

        /* QR Code Section */
        .qr-section {
            padding: 1.5rem;
            background: var(--gray-50);
            border-radius: 12px;
            text-align: center;
            margin-top: 1rem;
        }

        .qr-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .qr-text {
            font-size: 0.9rem;
            color: var(--gray-600);
            font-weight: 600;
        }

        /* Main Content Card */
        .content-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            animation: slideInRight 0.6s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Tabs Navigation */
        .tabs-nav {
            display: flex;
            gap: 1rem;
            margin-bottom: 2.5rem;
            border-bottom: 2px solid var(--gray-200);
            padding-bottom: 0;
        }

        .tab-btn {
            padding: 1rem 2rem;
            background: transparent;
            border: none;
            color: var(--gray-600);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
            border-radius: 12px 12px 0 0;
        }

        .tab-btn::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .tab-btn:hover {
            color: var(--primary);
            background: var(--gray-50);
        }

        .tab-btn.active {
            color: var(--primary);
            font-weight: 700;
        }

        .tab-btn.active::after {
            transform: scaleX(1);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Section Header */
        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .section-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        /* Form Styles */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-grid.single {
            grid-template-columns: 1fr;
        }

        .form-group {
            position: relative;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label .required {
            color: var(--danger);
            margin-left: 4px;
        }

        .form-control {
            width: 100%;
            padding: 0.95rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 1rem;
            color: var(--gray-900);
            background: white;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:hover {
            border-color: var(--gray-300);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
            background: rgba(239, 68, 68, 0.05);
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .form-control.is-valid {
            border-color: var(--success);
            background: rgba(16, 185, 129, 0.05);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .input-group {
            position: relative;
            display: flex;
        }

        .input-group .form-control {
            border-right: none;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group-btn {
            background: var(--gray-50);
            border: 2px solid var(--gray-200);
            border-left: none;
            padding: 0 1rem;
            border-radius: 0 12px 12px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--gray-500);
            transition: all 0.3s ease;
        }

        .input-group-btn:hover {
            background: var(--gray-100);
            color: var(--primary);
        }

        .error-message {
            display: block;
            color: var(--danger);
            font-size: 0.85rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        /* Password Strength */
        .password-strength {
            margin-top: 0.75rem;
        }

        .strength-bar {
            height: 6px;
            background: var(--gray-200);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: all 0.4s ease;
            border-radius: 3px;
        }

        .strength-fill.weak { width: 25%; background: linear-gradient(90deg, #ef4444, #dc2626); }
        .strength-fill.fair { width: 50%; background: linear-gradient(90deg, #f59e0b, #d97706); }
        .strength-fill.good { width: 75%; background: linear-gradient(90deg, #3b82f6, #2563eb); }
        .strength-fill.strong { width: 100%; background: linear-gradient(90deg, #10b981, #059669); }

        .strength-text {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--gray-600);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2.5rem;
            padding-top: 2rem;
            border-top: 2px solid var(--gray-200);
        }

        .btn {
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:active::before {
            width: 300px;
            height: 300px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.5);
        }

        .btn-secondary {
            background: var(--gray-100);
            color: var(--gray-700);
            border: 2px solid var(--gray-200);
        }

        .btn-secondary:hover {
            background: var(--gray-200);
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Stats Cards */
        .stats-section {
            margin-top: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 2rem 1.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 2px solid var(--gray-100);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            border-color: var(--primary);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(10deg);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--gray-600);
            font-weight: 600;
            font-size: 0.95rem;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }

            .sidebar-card {
                position: static;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem 0;
            }

            .header-card,
            .sidebar-card,
            .content-card {
                border-radius: 16px;
                padding: 2rem 1.5rem;
            }

            .profile-grid {
                gap: 1.5rem;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .header-text h1 {
                font-size: 1.5rem;
            }

            .tabs-nav {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .tab-btn {
                white-space: nowrap;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .profile-wrapper {
                padding: 0 1rem;
            }

            .avatar-wrapper {
                width: 120px;
                height: 120px;
            }

            .profile-name {
                font-size: 1.5rem;
            }

            .stat-value {
                font-size: 2rem;
            }
        }

        /* Utility Classes */
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

        ::selection {
            background: var(--primary);
            color: white;
        }

        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        }

        /* SweetAlert Custom Styling */
        .swal2-popup {
            border-radius: 20px !important;
            padding: 2rem !important;
        }

        .swal2-title {
            font-size: 1.75rem !important;
            font-weight: 700 !important;
        }

        .swal2-html-container {
            font-size: 1.05rem !important;
        }
    </style>
@endpush

@section('content')
    <div class="profile-wrapper">
        <!-- Header Card -->
        <div class="header-card">
            <div class="header-content">
                <div class="header-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="header-text">
                    <h1>Employee Profile</h1>
                    <p>Manage your personal information and account settings</p>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="profile-grid">
            <!-- Sidebar -->
            <div class="sidebar-card">
                <!-- Profile Header Section -->
                <div class="profile-header-section">
                    <!-- Avatar -->
                    <div class="avatar-wrapper">
                        <img src="{{ $employee->profile_image_url ?? asset('asset/img/default-avatar.png') }}"
                             alt="Profile"
                             class="profile-avatar"
                             id="profileAvatar"
                             onerror="this.src='{{ asset('asset/img/default-avatar.png') }}'">
                        <div class="avatar-overlay">
                            <button type="button" class="avatar-btn" id="uploadImageBtn" title="Upload photo">
                                <i class="fas fa-camera"></i>
                            </button>
                            @if(isset($employee->img) && $employee->img)
                                <button type="button" class="avatar-btn delete" id="deleteImageBtn" title="Remove photo">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                        </div>
                        <input type="file"
                               id="profileImageInput"
                               accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                               style="display: none;">
                    </div>

                    <!-- Name & Badges -->
                    <h2 class="profile-name" id="displayName">{{ $employee->name }}</h2>
                    <div class="profile-badges">
                        <span class="role-badge">{{ ucfirst($employee->role) }}</span>
                        <span class="status-badge {{ $employee->status }}">
                            <i class="fas fa-circle fa-xs"></i>
                            {{ ucfirst($employee->status) }}
                        </span>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="profile-details">
                    <div class="detail-group">
                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-id-badge"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Employee ID</div>
                                <div class="detail-value">{{ $employee->code }}</div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Username</div>
                                <div class="detail-value">{{ $employee->username }}</div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Email</div>
                                <div class="detail-value" id="displayEmail">{{ $employee->email }}</div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Phone</div>
                                <div class="detail-value" id="displayPhone">{{ $employee->phone }}</div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Last Login</div>
                                <div class="detail-value">
                                    @if($employee->last_login_at)
                                        {{ $employee->last_login_at->format('M d, Y H:i') }}
                                    @else
                                        Never
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Member Since</div>
                                <div class="detail-value">
                                    @if($employee->created_at)
                                        {{ $employee->created_at->format('M d, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QR Section -->
                    <div class="qr-section">
                        <div class="qr-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <div class="qr-text">Employee ID: {{ $employee->code }}</div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="content-card">
                <!-- Tabs Navigation -->
                <div class="tabs-nav">
                    <button class="tab-btn active" onclick="switchTab(event, 'profile')">
                        <i class="fas fa-user-edit"></i> Personal Info
                    </button>
                    <button class="tab-btn" onclick="switchTab(event, 'security')">
                        <i class="fas fa-lock"></i> Security
                    </button>
                    <button class="tab-btn" onclick="switchTab(event, 'stats')">
                        <i class="fas fa-chart-bar"></i> Statistics
                    </button>
                </div>

                <!-- Profile Tab -->
                <div id="profile" class="tab-content active">
                    <form id="profileForm" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf

                        <!-- Personal Information -->
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="section-title">Personal Information</div>
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
                        </div>

                        <!-- Address Information -->
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="section-title">Address Information</div>
                        </div>

                        <div class="form-grid single">
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
                        </div>

                        <div class="form-grid">
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

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Security Tab -->
                <div id="security" class="tab-content">
                    <form id="securityForm" method="POST" novalidate>
                        @csrf

                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="section-title">Change Password</div>
                        </div>

                        <div class="form-grid single">
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

                        <div class="action-buttons">
                            <button type="button" class="btn btn-secondary" onclick="resetPasswordForm()">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" id="securityBtn">
                                <i class="fas fa-key"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Statistics Tab -->
                <div id="stats" class="tab-content">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="section-title">Performance Statistics</div>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="stat-value">{{ $stats['total_price_changes'] ?? 0 }}</div>
                            <div class="stat-label">Total Price Changes</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-value">{{ $stats['this_month_changes'] ?? 0 }}</div>
                            <div class="stat-label">This Month</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <div class="stat-value">{{ $stats['products_updated'] ?? 0 }}</div>
                            <div class="stat-label">Products Updated</div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="stat-value">{{ $stats['total_orders_processed'] ?? 0 }}</div>
                            <div class="stat-label">Orders Processed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global variables
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
        const updateUrl = '{{ route("employee.profile.update") }}';
        const dataUrl = '{{ route("employee.profile.data") }}';
        const imageUploadUrl = '{{ route("employee.profile.image.upload") }}';
        const imageDeleteUrl = '{{ route("employee.profile.image.delete") }}';

        // Tab Switching
        function switchTab(event, tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        // Form Validation
        function validateForm() {
            let isValid = true;
            clearErrors();

            const requiredFields = ['name', 'email', 'phone', 'address', 'district', 'post_code'];
            requiredFields.forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (!input.value.trim()) {
                    markError(field, 'This field is required');
                    isValid = false;
                }
            });

            const email = document.querySelector('[name="email"]').value;
            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                markError('email', 'Please enter a valid email address');
                isValid = false;
            }

            const phone = document.querySelector('[name="phone"]').value;
            if (phone && !/^[\d\s\+\-\(\)]{10,}$/.test(phone)) {
                markError('phone', 'Please enter a valid phone number');
                isValid = false;
            }

            return isValid;
        }

        function clearErrors() {
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            document.querySelectorAll('.form-control').forEach(el => {
                el.classList.remove('is-invalid', 'is-valid');
            });
        }

        function markError(field, message) {
            const input = document.querySelector(`[name="${field}"]`);
            const errorEl = document.getElementById(field + 'Error');

            if (input) input.classList.add('is-invalid');
            if (errorEl) errorEl.textContent = message;
        }

        // Password Strength Checker
        function checkPasswordStrength(password) {
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            if (!password) {
                strengthFill.className = 'strength-fill';
                strengthText.textContent = '';
                return;
            }

            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            const levels = ['weak', 'weak', 'fair', 'good', 'strong'];
            const texts = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];

            strengthFill.className = 'strength-fill ' + levels[strength];
            strengthText.textContent = texts[strength];
        }

        // Toggle Password Visibility
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

        // Profile Image Upload Handler
        document.getElementById('uploadImageBtn').addEventListener('click', function() {
            document.getElementById('profileImageInput').click();
        });

        document.getElementById('profileImageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please upload a valid image file (JPEG, PNG, GIF, or WebP)',
                    confirmButtonColor: '#6366f1'
                });
                this.value = '';
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'Maximum file size is 2MB',
                    confirmButtonColor: '#6366f1'
                });
                this.value = '';
                return;
            }

            // Show uploading message
            Swal.fire({
                title: 'Uploading...',
                text: 'Please wait while we upload your image',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Upload the image
            const formData = new FormData();
            formData.append('img', file);

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
                    if (data.success) {
                        // Update the avatar image
                        const avatar = document.getElementById('profileAvatar');
                        avatar.src = data.image_url + '?t=' + new Date().getTime();

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Profile image updated successfully',
                            confirmButtonColor: '#6366f1',
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            // Reload to update delete button visibility
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Failed',
                            text: data.message || 'Failed to upload image. Please try again.',
                            confirmButtonColor: '#6366f1'
                        });
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Unable to connect to server. Please try again.',
                        confirmButtonColor: '#6366f1'
                    });
                })
                .finally(() => {
                    this.value = '';
                });
        });

        // Profile Image Delete Handler
        const deleteBtn = document.getElementById('deleteImageBtn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
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
                        // Show deleting message
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch(imageDeleteUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const avatar = document.getElementById('profileAvatar');
                                    avatar.src = (data.default_image || '{{ asset("asset/img/default-avatar.png") }}') + '?t=' + new Date().getTime();

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: data.message || 'Profile image removed successfully',
                                        confirmButtonColor: '#6366f1',
                                        timer: 2000,
                                        timerProgressBar: true
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Delete Failed',
                                        text: data.message || 'Failed to delete image',
                                        confirmButtonColor: '#6366f1'
                                    });
                                }
                            })
                            .catch(error => {
                                console.error('Delete error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Network Error',
                                    text: 'Unable to connect to server. Please try again.',
                                    confirmButtonColor: '#6366f1'
                                });
                            });
                    }
                });
            });
        }

        // Profile Form Submission (WITHOUT @method('PUT'))
        document.getElementById('profileForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!validateForm()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fix the errors in the form',
                    confirmButtonColor: '#6366f1'
                });
                return;
            }

            const saveBtn = document.getElementById('saveBtn');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            saveBtn.disabled = true;

            const formData = new FormData(this);

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
                    if (data.employee) {
                        Object.keys(data.employee).forEach(key => {
                            const input = document.querySelector(`[name="${key}"]`);
                            if (input && input.type !== 'file' && input.type !== 'password') {
                                input.value = data.employee[key] || '';
                            }
                        });

                        if (data.employee.profile_image_url) {
                            document.getElementById('profileAvatar').src = data.employee.profile_image_url + '?t=' + new Date().getTime();
                        }

                        document.getElementById('displayName').textContent = data.employee.name;
                        document.getElementById('displayEmail').textContent = data.employee.email;
                        document.getElementById('displayPhone').textContent = data.employee.phone;
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Profile updated successfully',
                        confirmButtonColor: '#6366f1',
                        timer: 2000,
                        timerProgressBar: true
                    });
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            markError(field, data.errors[field][0]);
                        });
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: data.message || 'Please fix the errors in the form',
                        confirmButtonColor: '#6366f1'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'Unable to connect to server. Please try again.',
                    confirmButtonColor: '#6366f1'
                });
            } finally {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }
        });

        // Security Form Submission (WITHOUT @method('PUT'))
        document.getElementById('securityForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            clearErrors();

            if (!currentPassword) {
                markError('current_password', 'Current password is required');
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please enter your current password',
                    confirmButtonColor: '#6366f1'
                });
                return;
            }

            if (newPassword.length < 8) {
                markError('password', 'Password must be at least 8 characters');
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Password must be at least 8 characters',
                    confirmButtonColor: '#6366f1'
                });
                return;
            }

            if (newPassword !== confirmPassword) {
                markError('password_confirmation', 'Passwords do not match');
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Passwords do not match',
                    confirmButtonColor: '#6366f1'
                });
                return;
            }

            const securityBtn = document.getElementById('securityBtn');
            const originalText = securityBtn.innerHTML;
            securityBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            securityBtn.disabled = true;

            const formData = new FormData(this);

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
                    document.getElementById('securityForm').reset();
                    document.getElementById('strengthFill').className = 'strength-fill';
                    document.getElementById('strengthText').textContent = '';

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Password updated successfully',
                        confirmButtonColor: '#6366f1',
                        timer: 2000,
                        timerProgressBar: true
                    });
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            markError(field, data.errors[field][0]);
                        });
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: data.message || 'Failed to update password',
                        confirmButtonColor: '#6366f1'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'Unable to connect to server. Please try again.',
                    confirmButtonColor: '#6366f1'
                });
            } finally {
                securityBtn.innerHTML = originalText;
                securityBtn.disabled = false;
            }
        });

        // Reset Form
        function resetForm() {
            Swal.fire({
                title: 'Reset Form?',
                text: 'All unsaved changes will be lost.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, reset',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Loading...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(dataUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const employee = data.employee;
                                const fields = ['name', 'email', 'phone', 'address', 'district', 'post_code'];
                                fields.forEach(field => {
                                    const input = document.querySelector(`[name="${field}"]`);
                                    if (input) input.value = employee[field] || '';
                                });
                                clearErrors();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Form Reset',
                                    text: 'Form has been reset to original values',
                                    confirmButtonColor: '#6366f1',
                                    timer: 2000,
                                    timerProgressBar: true
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to load data',
                                    confirmButtonColor: '#6366f1'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Reset error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to reset form',
                                confirmButtonColor: '#6366f1'
                            });
                        });
                }
            });
        }

        // Reset Password Form
        function resetPasswordForm() {
            document.getElementById('securityForm').reset();
            document.getElementById('strengthFill').className = 'strength-fill';
            document.getElementById('strengthText').textContent = '';
            clearErrors();
        }

        // Real-time Validation
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.form-control').forEach(input => {
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
                Swal.fire({
                    icon: 'info',
                    title: 'Welcome Back!',
                    text: 'Hello {{ $employee->name }}, manage your profile here.',
                    confirmButtonColor: '#6366f1',
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false
                });
            }, 1000);
        });
    </script>
@endpush
