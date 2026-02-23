@extends('seller.layout.app')

@push('title')
    Create Product
@endpush

@push('css')
    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --primary-lighter: #e0e7ff;
            --secondary: #7c3aed;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --info: #3b82f6;
            --light: #ffffff;
            --light-gray: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --border-radius-lg: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background-color: var(--light-gray);
            color: var(--gray-800);
        }

        /* Page Header - Light Theme */
        .page-header {
            background: linear-gradient(135deg, var(--light) 0%, var(--primary-lighter) 100%);
            color: var(--gray-800);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, var(--primary-lighter) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 1;
            opacity: 0.5;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, var(--primary-lighter) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 1;
            opacity: 0.3;
        }

        .page-header-content {
            position: relative;
            z-index: 2;
        }

        /* Form Section Cards - Light Theme */
        .form-section-card {
            background: var(--light);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-md);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .form-section-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary) 0%, var(--secondary) 100%);
            opacity: 0;
            transition: var(--transition);
        }

        .form-section-card:hover::before {
            opacity: 1;
        }

        .form-section-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .form-section-card.active {
            box-shadow: var(--shadow-xl);
            border-color: var(--primary);
        }

        /* Section Header - Light Theme */
        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.25rem;
            border-bottom: 2px solid var(--gray-100);
            position: relative;
        }

        .section-header .icon-container {
            width: 48px;
            height: 48px;
            background: var(--light);
            border: 2px solid var(--primary-lighter);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
            transition: var(--transition);
        }

        .form-section-card:hover .icon-container {
            background: linear-gradient(135deg, var(--primary-lighter) 0%, var(--light) 100%);
            border-color: var(--primary);
        }

        .section-header .material-icons {
            font-size: 1.5rem;
            color: var(--primary);
        }

        .section-header h5 {
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 0.25rem;
            font-size: 1.25rem;
        }

        .section-header p {
            color: var(--gray-600);
            font-size: 0.9rem;
            margin: 0;
        }

        /* Progress Indicator - Light Theme */
        .progress-indicator {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 3rem;
            position: relative;
        }

        .progress-indicator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gray-200);
            transform: translateY(-50%);
            z-index: 1;
        }

        .progress-step {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light);
            border: 2px solid var(--gray-300);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .progress-step.active .step-circle {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-color: var(--primary);
            color: var(--light);
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.2);
        }

        .progress-step.completed .step-circle {
            background: linear-gradient(135deg, var(--success) 0%, #34d399 100%);
            border-color: var(--success);
            color: var(--light);
        }

        .step-label {
            font-weight: 600;
            color: var(--gray-600);
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .progress-step.active .step-label {
            color: var(--primary);
            font-weight: 700;
        }

        /* Form Elements - Light Theme */
        .form-group {
            margin-bottom: 1.75rem;
            position: relative;
        }

        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .form-label .material-icons {
            font-size: 1.1rem;
            color: var(--primary);
        }

        .required::after {
            content: " *";
            color: var(--danger);
            margin-left: 2px;
        }

        .form-control, .form-select {
            border: 1.5px solid var(--gray-300);
            border-radius: var(--border-radius);
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            transition: var(--transition);
            background: var(--light);
            color: var(--gray-800);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .form-control:hover, .form-select:hover {
            border-color: var(--primary-light);
        }

        .form-control::placeholder {
            color: var(--gray-400);
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon .material-icons {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            pointer-events: none;
            z-index: 2;
        }

        .input-with-icon input, .input-with-icon select {
            padding-left: 3rem;
        }

        /* Info Text */
        .info-text {
            font-size: 0.85rem;
            color: var(--gray-500);
            margin-top: 0.5rem;
        }

        .info-text .material-icons {
            font-size: 1rem;
            color: var(--gray-400);
        }

        /* Enhanced Upload Area - Light Theme */
        .upload-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .upload-area {
            border: 2px dashed var(--gray-300);
            border-radius: var(--border-radius-lg);
            padding: 3rem 2rem;
            text-align: center;
            cursor: pointer;
            background: var(--light);
            transition: var(--transition);
            min-height: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .upload-area:hover {
            border-color: var(--primary);
            background: var(--primary-lighter);
        }

        .upload-area.drag-over {
            border-color: var(--success);
            background: var(--success-light);
            transform: scale(1.01);
        }

        .upload-area .upload-icon {
            font-size: 4rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
            transition: var(--transition);
        }

        .upload-area:hover .upload-icon {
            transform: translateY(-5px);
            color: var(--primary);
        }

        /* Enhanced Image Preview - Light Theme */
        #imagePreviewContainer {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .preview-card {
            position: relative;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            border: 2px solid var(--gray-200);
            aspect-ratio: 1/1;
        }

        .preview-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .preview-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .preview-card:hover img {
            transform: scale(1.05);
        }

        .remove-image {
            position: absolute;
            top: 12px;
            right: 12px;
            background: var(--light);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            z-index: 10;
            opacity: 0;
            transform: translateY(-10px);
        }

        .preview-card:hover .remove-image {
            opacity: 1;
            transform: translateY(0);
        }

        .remove-image:hover {
            background: var(--danger);
            color: var(--light);
            transform: scale(1.1);
        }

        /* Enhanced Price Calculator - Light Theme */
        .price-calculator {
            background: linear-gradient(135deg, var(--light) 0%, var(--primary-lighter) 100%);
            border-radius: var(--border-radius);
            padding: 1.75rem;
            margin-top: 2rem;
            border: 1px solid var(--gray-200);
            position: relative;
            overflow: hidden;
        }

        .price-calculator::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary) 0%, var(--secondary) 100%);
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--gray-200);
        }

        .price-row:last-child {
            border-bottom: none;
        }

        .price-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-700);
            font-weight: 500;
        }

        .price-label .material-icons {
            font-size: 1.2rem;
            color: var(--primary);
        }

        .price-value {
            font-weight: 600;
            font-size: 1rem;
            color: var(--gray-800);
        }

        /* Enhanced Buttons - Light Theme */
        .btn-action {
            padding: 1rem 2rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            border: 1.5px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: var(--light);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--secondary) 100%);
        }

        .btn-outline-primary {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--light);
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: var(--light);
        }

        .btn-outline-light {
            border-color: var(--gray-300);
            color: var(--gray-700);
            background: var(--light);
        }

        .btn-outline-light:hover {
            border-color: var(--gray-400);
            background: var(--gray-100);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #34d399 100%);
            color: var(--light);
            border: none;
        }

        /* Floating Action Buttons - Light Theme */
        .floating-actions {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            z-index: 1000;
        }

        .floating-btn {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
            cursor: pointer;
            border: none;
            color: var(--light);
        }

        .floating-btn:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: var(--shadow-xl);
        }

        .floating-btn.primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        }

        .floating-btn.success {
            background: linear-gradient(135deg, var(--success) 0%, #34d399 100%);
        }

        /* Enhanced Alert Container - Light Theme */
        .alert-container {
            position: fixed;
            top: 2rem;
            right: 2rem;
            z-index: 9999;
            min-width: 350px;
        }

        .alert {
            border-radius: var(--border-radius);
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-lg);
            padding: 1.25rem 1.5rem;
            animation: slideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--light);
        }

        .alert-success {
            border-left: 4px solid var(--success);
            background: linear-gradient(135deg, var(--light) 0%, var(--success-light) 100%);
        }

        .alert-danger {
            border-left: 4px solid var(--danger);
            background: linear-gradient(135deg, var(--light) 0%, var(--danger-light) 100%);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Loading Overlay - Light Theme */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            display: none;
        }

        .spinner-container {
            position: relative;
            width: 80px;
            height: 80px;
            margin-bottom: 2rem;
        }

        .spinner {
            width: 100%;
            height: 100%;
            border: 3px solid var(--gray-200);
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .spinner-inner {
            position: absolute;
            top: 15px;
            left: 15px;
            width: 50px;
            height: 50px;
            border: 2px solid var(--gray-200);
            border-bottom: 2px solid var(--secondary);
            border-radius: 50%;
            animation: spinReverse 1.5s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes spinReverse {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(-360deg); }
        }

        .loading-text {
            color: var(--gray-800);
            font-size: 1.25rem;
            font-weight: 600;
            letter-spacing: 1px;
        }

        /* Progress Bar - Light Theme */
        .upload-progress {
            width: 100%;
            height: 6px;
            background: var(--gray-200);
            border-radius: 3px;
            margin-top: 1rem;
            overflow: hidden;
            display: none;
        }

        .upload-progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 3px;
            width: 0%;
            transition: width 0.3s ease;
        }

        /* Form Validation Indicators */
        .validation-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            display: none;
        }

        .is-valid ~ .validation-icon.valid {
            display: block;
            color: var(--success);
        }

        .is-invalid ~ .validation-icon.invalid {
            display: block;
            color: var(--danger);
        }

        .char-count {
            text-align: right;
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
        }

        .char-count.warning {
            color: var(--warning);
        }

        .char-count.danger {
            color: var(--danger);
        }

        /* Review Summary - Light Theme */
        .review-summary {
            background: var(--light);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            border: 1px solid var(--gray-200);
        }

        .list-group-item {
            background: transparent;
            border: none;
        }

        .list-group-item .material-icons {
            font-size: 1.5rem;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .form-section-card {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .progress-indicator {
                overflow-x: auto;
                padding-bottom: 1rem;
            }

            .progress-step {
                min-width: 80px;
            }

            .floating-actions {
                bottom: 1rem;
                right: 1rem;
            }

            .floating-btn {
                width: 48px;
                height: 48px;
            }

            .alert-container {
                left: 1rem;
                right: 1rem;
                min-width: unset;
            }
        }

        /* Animation for section reveal */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-section-card {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        /* Staggered animation delay */
        .form-section-card:nth-child(1) { animation-delay: 0.1s; }
        .form-section-card:nth-child(2) { animation-delay: 0.2s; }
        .form-section-card:nth-child(3) { animation-delay: 0.3s; }
        .form-section-card:nth-child(4) { animation-delay: 0.4s; }

        /* Invalid State Styling */
        .is-invalid {
            border-color: var(--danger) !important;
        }

        .is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Enhanced Page Header - Light Theme -->
        <div class="page-header">
            <div class="page-header-content">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h4 class="fw-bold mb-3 d-flex align-items-center">
                            <span class="material-icons me-3" style="font-size: 2rem; color: var(--primary);">add_circle_outline</span>
                            Create New Product
                        </h4>
                        <p class="mb-0 opacity-90 d-flex align-items-center" style="max-width: 600px; color: var(--gray-600);">
                            <span class="material-icons me-2" style="font-size: 1.2rem; color: var(--primary);">rocket_launch</span>
                            Add a stunning new product to your inventory with our smart creation wizard
                        </p>
                    </div>
                    <a href="{{ route('seller.product.index') }}" class="btn btn-outline-primary btn-action">
                        <span class="material-icons me-2">arrow_back</span>
                        Back to Products
                    </a>
                </div>

                <!-- Progress Indicator - Light Theme -->
                <div class="progress-indicator mt-4">
                    <div class="progress-step active" data-step="1">
                        <div class="step-circle">
                            <span class="material-icons">info</span>
                        </div>
                        <span class="step-label">Basic Info</span>
                    </div>
                    <div class="progress-step" data-step="2">
                        <div class="step-circle">
                            <span class="material-icons">image</span>
                        </div>
                        <span class="step-label">Media</span>
                    </div>
                    <div class="progress-step" data-step="3">
                        <div class="step-circle">
                            <span class="material-icons">payments</span>
                        </div>
                        <span class="step-label">Pricing</span>
                    </div>
                    <div class="progress-step" data-step="4">
                        <div class="step-circle">
                            <span class="material-icons">inventory_2</span>
                        </div>
                        <span class="step-label">Inventory</span>
                    </div>
                    <div class="progress-step" data-step="5">
                        <div class="step-circle">
                            <span class="material-icons">check_circle</span>
                        </div>
                        <span class="step-label">Review</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages - Light Theme -->
        <div class="alert-container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <span class="material-icons me-3" style="font-size: 2rem; color: var(--success);">check_circle</span>
                    <div class="flex-grow-1">
                        <h6 class="mb-1" style="color: var(--gray-800);">Success!</h6>
                        <p class="mb-0" style="color: var(--gray-700);">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <span class="material-icons me-3" style="font-size: 2rem; color: var(--danger);">error</span>
                    <div class="flex-grow-1">
                        <h6 class="mb-1" style="color: var(--gray-800);">Error!</h6>
                        <p class="mb-0" style="color: var(--gray-700);">{{ session('error') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- Loading Overlay - Light Theme -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="spinner-container">
                <div class="spinner"></div>
                <div class="spinner-inner"></div>
            </div>
            <div class="loading-text">Creating Your Product</div>
        </div>

        <!-- Main Form -->
        <form id="productForm" action="{{ route('seller.product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Basic Information - Light Theme -->
                    <div class="form-section-card active" data-step="1">
                        <div class="section-header">
                            <div class="icon-container">
                                <span class="material-icons">info</span>
                            </div>
                            <div>
                                <h5 class="mb-1">Product Information</h5>
                                <p>Enter basic details about your product</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label required">
                                    <span class="material-icons">qr_code</span>
                                    Product Code
                                </label>
                                <div class="input-with-icon">
                                    <span class="material-icons">fingerprint</span>
                                    <input type="text" name="code" class="form-control"
                                           value="{{ $code ?? old('code') }}" readonly required
                                           style="background-color: var(--gray-100);">
                                </div>
                                <div class="info-text mt-2 d-flex align-items-center">
                                    <span class="material-icons me-1" style="font-size: 1rem;">auto_awesome</span>
                                    <span>Auto-generated unique identifier</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label required">
                                    <span class="material-icons">inventory_2</span>
                                    Product Name
                                </label>
                                <div class="input-with-icon">
                                    <span class="material-icons">badge</span>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" placeholder="Enter product name" required>
                                </div>
                                @error('name')
                                <div class="invalid-feedback d-flex align-items-center mt-2">
                                    <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">
                                    <span class="material-icons">barcode</span>
                                    Barcode
                                </label>
                                <div class="input-with-icon">
                                    <span class="material-icons">qr_code_scanner</span>
                                    <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror"
                                           value="{{ old('barcode') }}" placeholder="Enter barcode">
                                </div>
                                @error('barcode')
                                <div class="invalid-feedback d-flex align-items-center mt-2">
                                    <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label required">
                                    <span class="material-icons">category</span>
                                    Product Type
                                </label>
                                <div class="input-with-icon">
                                    <span class="material-icons">category</span>
                                    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="">Select Type</option>
                                        <option value="physical" {{ old('type') == 'physical' ? 'selected' : '' }}>Physical Product</option>
                                        <option value="digital" {{ old('type') == 'digital' ? 'selected' : '' }}>Digital Product</option>
                                    </select>
                                </div>
                                @error('type')
                                <div class="invalid-feedback d-flex align-items-center mt-2">
                                    <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">
                                    <span class="material-icons">category</span>
                                    Category
                                </label>
                                <div class="input-with-icon">
                                    <span class="material-icons">layers</span>
                                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_id')
                                <div class="invalid-feedback d-flex align-items-center mt-2">
                                    <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">
                                    <span class="material-icons">branding_watermark</span>
                                    Brand
                                </label>
                                <div class="input-with-icon">
                                    <span class="material-icons">corporate_fare</span>
                                    <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('brand_id')
                                <div class="invalid-feedback d-flex align-items-center mt-2">
                                    <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-12 mb-4">
                                <label class="form-label">
                                    <span class="material-icons">description</span>
                                    Description
                                    <span class="char-count ms-2" id="descriptionCount">0/2000</span>
                                </label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                          rows="5" placeholder="Enter detailed product description..."
                                          maxlength="2000" id="descriptionInput">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback d-flex align-items-center mt-2">
                                    <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-12 mb-4">
                                <label class="form-label">
                                    <span class="material-icons">star</span>
                                    Key Features
                                    <span class="char-count ms-2" id="featuresCount">0/2000</span>
                                </label>
                                <textarea name="features" class="form-control @error('features') is-invalid @enderror"
                                          rows="4" placeholder="Enter product key features (separate with commas)"
                                          maxlength="2000" id="featuresInput">{{ old('features') }}</textarea>
                                <div class="info-text mt-2 d-flex align-items-center">
                                    <span class="material-icons me-1" style="font-size: 1rem;">tips_and_updates</span>
                                    <span>Separate features with commas for better readability</span>
                                </div>
                                @error('features')
                                <div class="invalid-feedback d-flex align-items-center mt-2">
                                    <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Product Image - Light Theme -->
                    <div class="form-section-card" data-step="2">
                        <div class="section-header">
                            <div class="icon-container">
                                <span class="material-icons">image</span>
                            </div>
                            <div>
                                <h5 class="mb-1">Product Media</h5>
                                <p>Upload high-quality images of your product</p>
                            </div>
                        </div>

                        <div class="upload-container">
                            <div class="upload-area" id="uploadTrigger">
                                <span class="material-icons upload-icon">cloud_upload</span>
                                <h4 class="mt-3 mb-2" style="color: var(--gray-800);">Drag & Drop Images Here</h4>
                                <p class="text-muted mb-3">or click to browse files</p>
                                <p class="text-muted small">Supports JPG, PNG, GIF, WEBP - Max 2MB per image</p>
                                <input type="file" name="img" id="imageInput" class="d-none" accept="image/*" required>
                            </div>

                            <!-- Upload Progress -->
                            <div class="upload-progress" id="uploadProgress">
                                <div class="upload-progress-bar" id="uploadProgressBar"></div>
                            </div>

                            <div id="imagePreviewContainer"></div>

                            @error('img')
                            <div class="invalid-feedback d-block mt-3 d-flex align-items-center">
                                <span class="material-icons me-2" style="font-size: 1rem;">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Pricing Information - Light Theme -->
                    <div class="form-section-card" data-step="3">
                        <div class="section-header">
                            <div class="icon-container">
                                <span class="material-icons">payments</span>
                            </div>
                            <div>
                                <h5 class="mb-1">Pricing Strategy</h5>
                                <p>Set competitive pricing for your product</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label required">
                                <span class="material-icons">attach_money</span>
                                Stock Price (Rs.)
                            </label>
                            <div class="input-with-icon">
                                <span class="material-icons">price_change</span>
                                <input type="number" name="stock_price" id="stockPrice"
                                       class="form-control @error('stock_price') is-invalid @enderror"
                                       step="0.01" min="0" value="{{ old('stock_price') }}" placeholder="0.00" required>
                            </div>
                            @error('stock_price')
                            <div class="invalid-feedback d-flex align-items-center mt-2">
                                <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label required">
                                <span class="material-icons">price_check</span>
                                Display Price (Rs.)
                            </label>
                            <div class="input-with-icon">
                                <span class="material-icons">sell</span>
                                <input type="number" name="display_price" id="displayPrice"
                                       class="form-control @error('display_price') is-invalid @enderror"
                                       step="0.01" min="0" value="{{ old('display_price') }}" placeholder="0.00" required>
                            </div>
                            @error('display_price')
                            <div class="invalid-feedback d-flex align-items-center mt-2">
                                <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                <span class="material-icons">percent</span>
                                Discount (%)
                            </label>
                            <div class="input-with-icon">
                                <span class="material-icons">discount</span>
                                <input type="number" name="discount" id="discount"
                                       class="form-control @error('discount') is-invalid @enderror"
                                       step="0.01" min="0" max="100" value="{{ old('discount', 0) }}" placeholder="0">
                            </div>
                            @error('discount')
                            <div class="invalid-feedback d-flex align-items-center mt-2">
                                <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                <span class="material-icons">inventory</span>
                                Packaging Cost (Rs.)
                            </label>
                            <div class="input-with-icon">
                                <span class="material-icons">package</span>
                                <input type="number" name="packaging_cost" id="packagingCost"
                                       class="form-control @error('packaging_cost') is-invalid @enderror"
                                       step="0.01" min="0" value="{{ old('packaging_cost', 0) }}" placeholder="0.00">
                            </div>
                            @error('packaging_cost')
                            <div class="invalid-feedback d-flex align-items-center mt-2">
                                <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Enhanced Price Summary - Light Theme -->
                        <div class="price-calculator">
                            <h6 class="mb-3 d-flex align-items-center" style="color: var(--gray-800);">
                                <span class="material-icons me-2" style="color: var(--primary);">calculate</span>
                                Smart Price Summary
                            </h6>
                            <div class="price-row">
                                <span class="price-label">
                                    <span class="material-icons me-1" style="color: var(--primary);">summarize</span>
                                    Total Cost:
                                </span>
                                <span id="totalCostDisplay" class="price-value">Rs. 0.00</span>
                            </div>
                            <div class="price-row">
                                <span class="price-label">
                                    <span class="material-icons me-1" style="color: var(--primary);">savings</span>
                                    After Discount:
                                </span>
                                <span id="finalPriceDisplay" class="price-value fw-bold" style="color: var(--success);">Rs. 0.00</span>
                            </div>
                            <div class="price-row">
                                <span class="price-label">
                                    <span class="material-icons me-1" style="color: var(--primary);">trending_up</span>
                                    Profit Margin:
                                </span>
                                <span id="profitMargin" class="price-value">0.00%</span>
                            </div>
                            <div class="price-row mt-3 pt-3 border-top">
                                <span class="price-label">
                                    <span class="material-icons me-1" style="color: var(--primary);">insights</span>
                                    Profit/Loss:
                                </span>
                                <span id="profitAmount" class="price-value" style="color: var(--success);">Rs. 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Information - Light Theme -->
                    <div class="form-section-card" data-step="4">
                        <div class="section-header">
                            <div class="icon-container">
                                <span class="material-icons">inventory_2</span>
                            </div>
                            <div>
                                <h5 class="mb-1">Inventory Management</h5>
                                <p>Manage stock levels and alerts</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label required">
                                <span class="material-icons">numbers</span>
                                Stock Quantity
                            </label>
                            <div class="input-with-icon">
                                <span class="material-icons">warehouse</span>
                                <input type="number" name="qty" id="quantity"
                                       class="form-control @error('qty') is-invalid @enderror"
                                       min="0" value="{{ old('qty') }}" placeholder="0" required>
                            </div>
                            @error('qty')
                            <div class="invalid-feedback d-flex align-items-center mt-2">
                                <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label required">
                                <span class="material-icons">warning</span>
                                Low Stock Alert
                            </label>
                            <div class="input-with-icon">
                                <span class="material-icons">notification_important</span>
                                <input type="number" name="min_qty"
                                       class="form-control @error('min_qty') is-invalid @enderror"
                                       min="1" value="{{ old('min_qty', 1) }}" placeholder="1" required>
                            </div>
                            <div class="info-text mt-2 d-flex align-items-center">
                                <span class="material-icons me-1" style="font-size: 1rem;">notifications</span>
                                <span>Get notified when stock reaches this level</span>
                            </div>
                            @error('min_qty')
                            <div class="invalid-feedback d-flex align-items-center mt-2">
                                <span class="material-icons me-1" style="font-size: 1rem;">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Enhanced Stock Value - Light Theme -->
                        <div class="price-calculator">
                            <h6 class="mb-3 d-flex align-items-center" style="color: var(--gray-800);">
                                <span class="material-icons me-2" style="color: var(--primary);">assessment</span>
                                Stock Valuation
                            </h6>
                            <div class="price-row">
                                <span class="price-label">
                                    <span class="material-icons me-1" style="color: var(--primary);">inventory_2</span>
                                    Current Stock Value:
                                </span>
                                <span id="stockValueDisplay" class="price-value">Rs. 0.00</span>
                            </div>
                            <div class="price-row">
                                <span class="price-label">
                                    <span class="material-icons me-1" style="color: var(--primary);">trending_up</span>
                                    Potential Sales Value:
                                </span>
                                <span id="potentialValueDisplay" class="price-value fw-bold" style="color: var(--success);">Rs. 0.00</span>
                            </div>
                            <div class="price-row mt-3 pt-3 border-top">
                                <span class="price-label">
                                    <span class="material-icons me-1" style="color: var(--primary);">diamond</span>
                                    Net Potential Profit:
                                </span>
                                <span id="netProfitDisplay" class="price-value" style="color: var(--success);">Rs. 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Review Section - Light Theme -->
                    <div class="form-section-card" data-step="5">
                        <div class="section-header">
                            <div class="icon-container">
                                <span class="material-icons">check_circle</span>
                            </div>
                            <div>
                                <h5 class="mb-1">Review & Submit</h5>
                                <p>Finalize and create your product</p>
                            </div>
                        </div>

                        <div class="review-summary mb-4">
                            <h6 class="mb-3 d-flex align-items-center" style="color: var(--gray-800);">
                                <span class="material-icons me-2" style="color: var(--primary);">visibility</span>
                                Product Summary
                            </h6>
                            <div class="list-group">
                                <div class="list-group-item border-0 px-0 py-2 d-flex align-items-center">
                                    <span class="material-icons me-3" style="color: var(--success);">check_circle</span>
                                    <div>
                                        <small class="text-muted">Product Code</small>
                                        <p class="mb-0 fw-bold" style="color: var(--gray-800);" id="reviewCode">{{ $code ?? '--' }}</p>
                                    </div>
                                </div>
                                <div class="list-group-item border-0 px-0 py-2 d-flex align-items-center">
                                    <span class="material-icons me-3" style="color: var(--primary);">inventory_2</span>
                                    <div>
                                        <small class="text-muted">Product Name</small>
                                        <p class="mb-0 fw-bold" style="color: var(--gray-800);" id="reviewName">--</p>
                                    </div>
                                </div>
                                <div class="list-group-item border-0 px-0 py-2 d-flex align-items-center">
                                    <span class="material-icons me-3" style="color: var(--warning);">payments</span>
                                    <div>
                                        <small class="text-muted">Final Price</small>
                                        <p class="mb-0 fw-bold" style="color: var(--gray-800);" id="reviewPrice">Rs. 0.00</p>
                                    </div>
                                </div>
                                <div class="list-group-item border-0 px-0 py-2 d-flex align-items-center">
                                    <span class="material-icons me-3" style="color: var(--info);">inventory</span>
                                    <div>
                                        <small class="text-muted">Initial Stock</small>
                                        <p class="mb-0 fw-bold" style="color: var(--gray-800);" id="reviewStock">0 units</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-action" id="submitBtn">
                                <span class="material-icons">rocket_launch</span>
                                Launch Product
                            </button>
                            <a href="{{ route('seller.product.index') }}" class="btn btn-outline-light btn-action">
                                <span class="material-icons">cancel</span>
                                Cancel Creation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Floating Action Buttons - Light Theme -->
    <div class="floating-actions">
        <button class="floating-btn primary" onclick="scrollToTop()" title="Scroll to Top">
            <span class="material-icons">vertical_align_top</span>
        </button>
        <button class="floating-btn success" onclick="validateAndNavigate()" title="Quick Validate">
            <span class="material-icons">fact_check</span>
        </button>
    </div>
@endsection

@push('script')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const uploadTrigger = document.getElementById('uploadTrigger');
            const imageInput = document.getElementById('imageInput');
            const previewContainer = document.getElementById('imagePreviewContainer');
            const form = document.getElementById('productForm');
            const submitBtn = document.getElementById('submitBtn');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const uploadProgress = document.getElementById('uploadProgress');
            const uploadProgressBar = document.getElementById('uploadProgressBar');

            // Progress Steps
            const progressSteps = document.querySelectorAll('.progress-step');
            const formSections = document.querySelectorAll('.form-section-card');

            // Price Calculation Elements
            const stockPrice = document.getElementById('stockPrice');
            const displayPrice = document.getElementById('displayPrice');
            const discount = document.getElementById('discount');
            const packagingCost = document.getElementById('packagingCost');
            const quantity = document.getElementById('quantity');

            // Review Elements
            const reviewName = document.getElementById('reviewName');
            const reviewPrice = document.getElementById('reviewPrice');
            const reviewStock = document.getElementById('reviewStock');

            // Character Counters
            const descriptionInput = document.getElementById('descriptionInput');
            const featuresInput = document.getElementById('featuresInput');
            const descriptionCount = document.getElementById('descriptionCount');
            const featuresCount = document.getElementById('featuresCount');

            // Initialize
            updateCharacterCounts();
            initializeProgressTracking();

            // Character Count Functions
            function updateCharacterCounts() {
                if (descriptionInput) {
                    const descLength = descriptionInput.value.length;
                    descriptionCount.textContent = `${descLength}/2000`;
                    descriptionCount.style.color = descLength > 1800 ?
                        (descLength >= 2000 ? 'var(--danger)' : 'var(--warning)') :
                        'var(--gray-500)';
                }

                if (featuresInput) {
                    const featuresLength = featuresInput.value.length;
                    featuresCount.textContent = `${featuresLength}/2000`;
                    featuresCount.style.color = featuresLength > 1800 ?
                        (featuresLength >= 2000 ? 'var(--danger)' : 'var(--warning)') :
                        'var(--gray-500)';
                }
            }

            // Initialize character count listeners
            if (descriptionInput) {
                descriptionInput.addEventListener('input', updateCharacterCounts);
            }
            if (featuresInput) {
                featuresInput.addEventListener('input', updateCharacterCounts);
            }

            // Progress Tracking
            function initializeProgressTracking() {
                formSections.forEach(section => {
                    const inputs = section.querySelectorAll('input, select, textarea');
                    inputs.forEach(input => {
                        input.addEventListener('input', () => {
                            validateSection(section);
                            updateReviewSummary();
                        });
                        input.addEventListener('change', () => {
                            validateSection(section);
                            updateReviewSummary();
                        });
                    });
                });
            }

            function validateSection(section) {
                const inputs = section.querySelectorAll('[required]');
                let isValid = true;

                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                    }
                });

                const step = section.dataset.step;
                const progressStep = document.querySelector(`.progress-step[data-step="${step}"]`);

                if (isValid) {
                    progressStep.classList.add('completed');
                    progressStep.classList.remove('active');

                    // Activate next step
                    const nextStep = parseInt(step) + 1;
                    const nextProgressStep = document.querySelector(`.progress-step[data-step="${nextStep}"]`);
                    if (nextProgressStep) {
                        nextProgressStep.classList.add('active');
                    }
                } else {
                    progressStep.classList.remove('completed');
                }
            }

            // Review Summary Update
            function updateReviewSummary() {
                // Update name
                const nameInput = document.querySelector('input[name="name"]');
                if (nameInput && nameInput.value) {
                    reviewName.textContent = nameInput.value;
                }

                // Update price
                updatePriceDisplay();
                reviewPrice.textContent = document.getElementById('finalPriceDisplay').textContent;

                // Update stock
                if (quantity && quantity.value) {
                    reviewStock.textContent = `${quantity.value} units`;
                }
            }

            // Enhanced Image Upload with Progress
            if (uploadTrigger && imageInput) {
                uploadTrigger.addEventListener('click', () => {
                    imageInput.click();
                });

                // Enhanced drag and drop
                uploadTrigger.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    uploadTrigger.classList.add('drag-over');
                });

                uploadTrigger.addEventListener('dragleave', () => {
                    uploadTrigger.classList.remove('drag-over');
                });

                uploadTrigger.addEventListener('drop', (e) => {
                    e.preventDefault();
                    uploadTrigger.classList.remove('drag-over');

                    if (e.dataTransfer.files.length > 0) {
                        imageInput.files = e.dataTransfer.files;
                        simulateUploadProgress(e.dataTransfer.files[0]);
                    }
                });

                imageInput.addEventListener('change', (e) => {
                    if (e.target.files.length > 0) {
                        simulateUploadProgress(e.target.files[0]);
                    }
                });
            }

            function simulateUploadProgress(file) {
                if (!file) return;

                // Validate file
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Please upload only JPG, PNG, GIF, or WEBP images.',
                        confirmButtonColor: '#4f46e5'
                    });
                    imageInput.value = '';
                    uploadTrigger.classList.remove('is-invalid');
                    return;
                }

                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Image size must be less than 2MB.',
                        confirmButtonColor: '#4f46e5'
                    });
                    imageInput.value = '';
                    uploadTrigger.classList.remove('is-invalid');
                    return;
                }

                // Show progress bar
                uploadProgress.style.display = 'block';
                uploadProgressBar.style.width = '0%';

                // Simulate upload progress
                let progress = 0;
                const interval = setInterval(() => {
                    progress += 5;
                    uploadProgressBar.style.width = `${progress}%`;

                    if (progress >= 100) {
                        clearInterval(interval);
                        setTimeout(() => {
                            uploadProgress.style.display = 'none';
                            createImagePreview(file);
                        }, 300);
                    }
                }, 50);

                // Update progress step
                const imageStep = document.querySelector('.progress-step[data-step="2"]');
                imageStep.classList.add('completed');
            }

            function createImagePreview(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.innerHTML = `
                        <div class="preview-card">
                            <img src="${e.target.result}" alt="Product Image Preview">
                            <div class="remove-image" onclick="removeImagePreview()">
                                <span class="material-icons">close</span>
                            </div>
                        </div>
                    `;
                    uploadTrigger.classList.remove('is-invalid');
                };
                reader.readAsDataURL(file);
            }

            // Global function for image removal
            window.removeImagePreview = function() {
                previewContainer.innerHTML = '';
                imageInput.value = '';
                uploadTrigger.classList.remove('is-invalid');

                // Update progress step
                const imageStep = document.querySelector('.progress-step[data-step="2"]');
                imageStep.classList.remove('completed');
            };

            // Enhanced Price Calculation
            function updatePriceDisplay() {
                const stockPriceVal = parseFloat(stockPrice.value) || 0;
                const displayPriceVal = parseFloat(displayPrice.value) || 0;
                const discountVal = parseFloat(discount.value) || 0;
                const packagingCostVal = parseFloat(packagingCost.value) || 0;
                const quantityVal = parseInt(quantity.value) || 0;

                // Calculate values
                const totalCost = stockPriceVal + packagingCostVal;
                const discountAmount = displayPriceVal * (discountVal / 100);
                const finalPrice = displayPriceVal - discountAmount;
                const profit = finalPrice - totalCost;
                const marginPercent = totalCost > 0 ? (profit / totalCost) * 100 : 0;
                const stockValue = stockPriceVal * quantityVal;
                const potentialValue = finalPrice * quantityVal;
                const netProfit = profit * quantityVal;

                // Update displays
                document.getElementById('totalCostDisplay').textContent = `Rs. ${totalCost.toFixed(2)}`;
                document.getElementById('finalPriceDisplay').textContent = `Rs. ${finalPrice.toFixed(2)}`;
                document.getElementById('profitMargin').textContent = `${marginPercent.toFixed(2)}%`;
                document.getElementById('profitAmount').textContent = `Rs. ${profit.toFixed(2)}`;
                document.getElementById('stockValueDisplay').textContent = `Rs. ${stockValue.toFixed(2)}`;
                document.getElementById('potentialValueDisplay').textContent = `Rs. ${potentialValue.toFixed(2)}`;
                document.getElementById('netProfitDisplay').textContent = `Rs. ${netProfit.toFixed(2)}`;

                // Color code profit margin
                const profitMarginEl = document.getElementById('profitMargin');
                const profitAmountEl = document.getElementById('profitAmount');
                const netProfitEl = document.getElementById('netProfitDisplay');

                if (marginPercent > 30) {
                    profitMarginEl.style.color = 'var(--success)';
                    profitAmountEl.style.color = 'var(--success)';
                    netProfitEl.style.color = 'var(--success)';
                } else if (marginPercent > 10) {
                    profitMarginEl.style.color = 'var(--warning)';
                    profitAmountEl.style.color = 'var(--warning)';
                    netProfitEl.style.color = 'var(--warning)';
                } else if (marginPercent > 0) {
                    profitMarginEl.style.color = 'var(--gray-500)';
                    profitAmountEl.style.color = 'var(--gray-500)';
                    netProfitEl.style.color = 'var(--gray-500)';
                } else {
                    profitMarginEl.style.color = 'var(--danger)';
                    profitAmountEl.style.color = 'var(--danger)';
                    netProfitEl.style.color = 'var(--danger)';
                }
            }

            // Bind price calculation events
            if (stockPrice && displayPrice && discount && packagingCost && quantity) {
                [stockPrice, displayPrice, discount, packagingCost, quantity].forEach(input => {
                    input.addEventListener('input', updatePriceDisplay);
                    input.addEventListener('change', updatePriceDisplay);
                });
                updatePriceDisplay();
            }

            // Form Validation
            function validateForm() {
                let isValid = true;

                // Clear invalid classes
                form.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                // Check required fields
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value || !field.value.toString().trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');

                        if (field.name === 'img') {
                            uploadTrigger.classList.add('is-invalid');
                        }
                    }
                });

                // Check image
                if (!imageInput.files.length) {
                    isValid = false;
                    uploadTrigger.classList.add('is-invalid');
                    Swal.fire({
                        icon: 'error',
                        title: 'Image Required',
                        text: 'Please upload a product image.',
                        confirmButtonColor: '#4f46e5'
                    });
                }

                // Price validation
                const stockPriceVal = parseFloat(stockPrice.value) || 0;
                const displayPriceVal = parseFloat(displayPrice.value) || 0;
                if (displayPriceVal < stockPriceVal) {
                    isValid = false;
                    displayPrice.classList.add('is-invalid');
                    Swal.fire({
                        icon: 'error',
                        title: 'Price Error',
                        text: 'Display price cannot be lower than stock price.',
                        confirmButtonColor: '#4f46e5'
                    });
                }

                return isValid;
            }

            // Enhanced Form Submission
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (!validateForm()) {
                        return false;
                    }

                    // Show loading with enhanced animation
                    loadingOverlay.style.display = 'flex';
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="material-icons">hourglass_top</span> Processing...';

                    // Create FormData
                    const formData = new FormData(form);

                    // Send AJAX request
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => response.json().then(data => ({ status: response.ok, data })))
                        .then(({ status, data }) => {
                            if (status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Product Created!',
                                    html: `
                                    <div class="text-center">
                                        <span class="material-icons" style="font-size: 4rem; color: var(--success);">rocket_launch</span>
                                        <h4 class="mt-3">${data.message}</h4>
                                        <p class="text-muted">Your product is now live in your inventory.</p>
                                    </div>
                                `,
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    window.location.href = data.redirect;
                                });
                            } else {
                                throw data;
                            }
                        })
                        .catch(error => {
                            let errorMessage = 'An error occurred. Please try again.';

                            if (error.errors) {
                                const errorList = Object.values(error.errors).flat().join('<br>');
                                errorMessage = `<div class="text-start">${errorList}</div>`;
                            } else if (error.message) {
                                errorMessage = error.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Creation Failed',
                                html: errorMessage,
                                confirmButtonColor: '#4f46e5'
                            });
                        })
                        .finally(() => {
                            loadingOverlay.style.display = 'none';
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<span class="material-icons">rocket_launch</span> Launch Product';
                        });
                });
            }

            // Auto-close alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Real-time validation
            form.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                    validateSection(this.closest('.form-section-card'));
                });
            });

            // Price validation
            if (stockPrice && displayPrice) {
                stockPrice.addEventListener('change', function() {
                    const stockVal = parseFloat(this.value) || 0;
                    const displayVal = parseFloat(displayPrice.value) || 0;
                    if (displayVal < stockVal) {
                        displayPrice.classList.add('is-invalid');
                    }
                });

                displayPrice.addEventListener('change', function() {
                    const displayVal = parseFloat(this.value) || 0;
                    const stockVal = parseFloat(stockPrice.value) || 0;
                    if (displayVal < stockVal) {
                        this.classList.add('is-invalid');
                    }
                });
            }

            // Floating Action Functions
            window.scrollToTop = function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };

            window.validateAndNavigate = function() {
                if (validateForm()) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Validation Passed!',
                        text: 'All required fields are filled correctly.',
                        confirmButtonColor: '#4f46e5'
                    });
                } else {
                    // Scroll to first invalid field
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            };

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl + S to save
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    submitBtn.click();
                }

                // Ctrl + Enter to quick validate
                if (e.ctrlKey && e.key === 'Enter') {
                    e.preventDefault();
                    validateAndNavigate();
                }
            });

            // Add scroll animations for sections
            let lastScrollTop = 0;
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                formSections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.offsetHeight;

                    if (scrollTop > sectionTop - window.innerHeight * 0.7 &&
                        scrollTop < sectionTop + sectionHeight) {
                        section.classList.add('active');
                    } else {
                        section.classList.remove('active');
                    }
                });

                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            }, false);

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
