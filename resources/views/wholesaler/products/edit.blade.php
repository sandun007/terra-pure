@extends('wholesaler.layouts.app')

@push('title')
    Edit Product - {{ $product->name }}
@endpush

@push('css')
    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

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
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* Page Header - Enhanced */
        .page-header {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: var(--gray-800);
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
            animation: slideInDown 0.5s ease-out;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.1) 0%, transparent 70%);
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
            background: radial-gradient(circle, rgba(245, 158, 11, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 1;
            opacity: 0.3;
        }

        .page-header-content {
            position: relative;
            z-index: 2;
        }

        /* Form Section Cards - Enhanced */
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
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .form-section-card:nth-child(1) { animation-delay: 0.1s; }
        .form-section-card:nth-child(2) { animation-delay: 0.2s; }
        .form-section-card:nth-child(3) { animation-delay: 0.3s; }

        .form-section-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--warning) 0%, var(--danger) 100%);
            opacity: 0;
            transition: var(--transition);
        }

        .form-section-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .form-section-card:hover::before {
            opacity: 1;
        }

        /* Section Header - Enhanced */
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
            background: linear-gradient(135deg, var(--warning-light) 0%, var(--light) 100%);
            border: 2px solid var(--warning);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
            transition: var(--transition);
        }

        .form-section-card:hover .icon-container {
            transform: rotate(5deg) scale(1.1);
        }

        .section-header .material-icons-outlined {
            font-size: 1.5rem;
            color: var(--warning);
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

        /* Current Image Display */
        .current-image-container {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--gray-100) 0%, var(--light) 100%);
            border-radius: var(--border-radius);
            border: 1px solid var(--gray-200);
            animation: fadeIn 0.5s ease-out;
        }

        .current-image-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-700);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .current-image-title .material-icons-outlined {
            color: var(--primary);
        }

        .current-image-wrapper {
            position: relative;
            display: inline-block;
        }

        .current-image {
            max-width: 200px;
            max-height: 200px;
            border-radius: var(--border-radius);
            border: 3px solid var(--primary-light);
            box-shadow: var(--shadow-lg);
            transition: var(--transition);
        }

        .current-image:hover {
            transform: scale(1.05);
            border-color: var(--primary);
        }

        /* Form Elements - Enhanced */
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
        }

        .form-label .material-icons-outlined {
            font-size: 1.2rem;
            color: var(--warning);
        }

        .required::after {
            content: " *";
            color: var(--danger);
            font-size: 1.2rem;
            line-height: 1;
        }

        .form-control, .form-select {
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius);
            padding: 0.875rem 1rem;
            font-size: 0.95rem;
            transition: var(--transition);
            background: var(--light);
            color: var(--gray-800);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--warning);
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
            outline: none;
        }

        .form-control:hover, .form-select:hover {
            border-color: var(--warning-light);
        }

        .form-control:readonly {
            background-color: var(--gray-100);
            cursor: not-allowed;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon .material-icons-outlined {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            pointer-events: none;
            z-index: 2;
            font-size: 1.2rem;
        }

        .input-with-icon input,
        .input-with-icon select,
        .input-with-icon textarea {
            padding-left: 3rem;
        }

        /* Info Text */
        .info-text {
            font-size: 0.85rem;
            color: var(--gray-500);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .info-text .material-icons-outlined {
            font-size: 1rem;
            color: var(--gray-400);
        }

        /* Enhanced Upload Area */
        .upload-container {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .upload-area {
            border: 2px dashed var(--warning);
            border-radius: var(--border-radius-lg);
            padding: 3rem 2rem;
            text-align: center;
            cursor: pointer;
            background: linear-gradient(135deg, var(--warning-light) 0%, var(--light) 100%);
            transition: var(--transition);
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .upload-area:hover {
            border-color: var(--danger);
            background: linear-gradient(135deg, var(--danger-light) 0%, var(--light) 100%);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .upload-area.drag-over {
            border-color: var(--success);
            background: linear-gradient(135deg, var(--success-light) 0%, var(--light) 100%);
            transform: scale(1.02);
        }

        .upload-area .upload-icon {
            font-size: 4rem;
            color: var(--warning);
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }

        .upload-area:hover .upload-icon {
            transform: translateY(-5px) scale(1.1);
            color: var(--danger);
        }

        /* Image Preview */
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: var(--border-radius);
            margin: 1rem auto;
            display: none;
            border: 3px solid var(--success);
            box-shadow: var(--shadow-lg);
            animation: fadeIn 0.5s ease-out;
        }

        .image-preview.show {
            display: block;
        }

        /* Progress Bar */
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
            background: linear-gradient(90deg, var(--warning) 0%, var(--danger) 100%);
            border-radius: 3px;
            width: 0%;
            transition: width 0.3s ease;
        }

        /* Enhanced Price Calculator */
        .price-calculator {
            background: linear-gradient(135deg, var(--light) 0%, var(--warning-light) 100%);
            border-radius: var(--border-radius);
            padding: 1.75rem;
            margin-top: 2rem;
            border: 1px solid var(--gray-200);
            position: relative;
            overflow: hidden;
            animation: pulse 2s infinite;
        }

        .price-calculator::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--warning) 0%, var(--danger) 100%);
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

        .price-label .material-icons-outlined {
            font-size: 1.2rem;
            color: var(--warning);
        }

        .price-value {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--gray-800);
        }

        .price-value.positive {
            color: var(--success);
        }

        .price-value.warning {
            color: var(--warning);
        }

        .price-value.negative {
            color: var(--danger);
        }

        /* Enhanced Buttons */
        .btn-action {
            padding: 1rem 2rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            font-size: 1rem;
            letter-spacing: 0.3px;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-action::after {
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

        .btn-action:hover::after {
            width: 300px;
            height: 300px;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
            color: var(--light);
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #fbbf24 0%, var(--warning) 100%);
        }

        .btn-outline-warning {
            border: 2px solid var(--warning);
            color: var(--warning);
            background: var(--light);
        }

        .btn-outline-warning:hover {
            background: var(--warning);
            color: var(--light);
        }

        .btn-outline-secondary {
            border: 2px solid var(--gray-300);
            color: var(--gray-700);
            background: var(--light);
        }

        .btn-outline-secondary:hover {
            border-color: var(--gray-400);
            background: var(--gray-100);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: var(--light);
        }

        /* Loading Overlay */
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
            border-top: 3px solid var(--warning);
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
            border-bottom: 2px solid var(--danger);
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

        /* Character Counter */
        .char-counter {
            text-align: right;
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
        }

        .char-counter.warning {
            color: var(--warning);
        }

        .char-counter.danger {
            color: var(--danger);
        }

        /* Alert Container */
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
            animation: slideInRight 0.4s ease-out;
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

        .alert-warning {
            border-left: 4px solid var(--warning);
            background: linear-gradient(135deg, var(--light) 0%, var(--warning-light) 100%);
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(245, 158, 11, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
            }
        }

        /* Floating Action Buttons */
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
            animation: bounce 2s infinite;
        }

        .floating-btn:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: var(--shadow-xl);
        }

        .floating-btn.warning {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
        }

        .floating-btn.danger {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        /* Validation Styles */
        .is-invalid {
            border-color: var(--danger) !important;
        }

        .is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important;
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .invalid-feedback .material-icons-outlined {
            font-size: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
            }

            .form-section-card {
                padding: 1.5rem;
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

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        /* Print Styles */
        @media print {
            .floating-actions,
            .btn-action {
                display: none !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <!-- Enhanced Page Header -->
        <div class="page-header">
            <div class="page-header-content">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div class="flex-grow-1">
                        <h4 class="fw-bold mb-3 d-flex align-items-center">
                            <span class="material-icons-outlined me-3" style="font-size: 2.5rem; color: var(--warning);">edit</span>
                            Edit Product
                        </h4>
                        <p class="mb-0 d-flex align-items-center text-muted">
                            <span class="material-icons-outlined me-2" style="font-size: 1.2rem; color: var(--warning);">inventory</span>
                            Update product information: <strong class="ms-1">{{ $product->name }}</strong>
                        </p>
                    </div>
                    <a href="{{ route('wholesaler.products.index') }}" class="btn btn-outline-warning btn-action">
                        <span class="material-icons-outlined me-2">arrow_back</span>
                        Back to Products
                    </a>
                </div>
            </div>
        </div>

        <!-- Alert Container for Dynamic Messages -->
        <div class="alert-container" id="alertContainer">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <span class="material-icons-outlined me-3" style="font-size: 2rem; color: var(--success);">check_circle</span>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Success!</h6>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <span class="material-icons-outlined me-3" style="font-size: 2rem; color: var(--danger);">error</span>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">Error!</h6>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <span class="material-icons-outlined me-3" style="font-size: 2rem; color: var(--danger);">warning</span>
                        <div>
                            <h6 class="mb-1">Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="spinner-container">
                <div class="spinner"></div>
                <div class="spinner-inner"></div>
            </div>
            <div class="loading-text fw-bold">Updating Product</div>
            <p class="text-muted mt-2">Please wait while we save your changes...</p>
        </div>

        <!-- Main Form -->
        <form id="productForm" action="{{ route('wholesaler.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Product Information Section -->
                    <div class="form-section-card">
                        <div class="section-header">
                            <div class="icon-container">
                                <span class="material-icons-outlined">info</span>
                            </div>
                            <div>
                                <h5 class="mb-1">Product Information</h5>
                                <p class="text-muted">Update basic details about your product</p>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label required">
                                        <span class="material-icons-outlined">qr_code</span>
                                        Product Code
                                    </label>
                                    <div class="input-with-icon">
                                        <span class="material-icons-outlined">fingerprint</span>
                                        <input type="text" class="form-control" value="{{ $product->code }}" readonly>
                                    </div>
                                    <div class="info-text">
                                        <span class="material-icons-outlined">lock</span>
                                        <span>Product code cannot be changed</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label required">
                                        <span class="material-icons-outlined">inventory</span>
                                        Product Name
                                    </label>
                                    <div class="input-with-icon">
                                        <span class="material-icons-outlined">badge</span>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name', $product->name) }}" placeholder="Enter product name" required
                                               data-required="true" id="productName">
                                    </div>
                                    @error('name')
                                    <div class="invalid-feedback">
                                        <span class="material-icons-outlined">error</span>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <span class="material-icons-outlined">barcode</span>
                                        Barcode
                                    </label>
                                    <div class="input-with-icon">
                                        <span class="material-icons-outlined">qr_code_scanner</span>
                                        <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror"
                                               value="{{ old('barcode', $product->barcode) }}" placeholder="Enter barcode">
                                    </div>
                                    @error('barcode')
                                    <div class="invalid-feedback">
                                        <span class="material-icons-outlined">error</span>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <span class="material-icons-outlined">branding_watermark</span>
                                        Brand
                                    </label>
                                    <div class="input-with-icon">
                                        <span class="material-icons-outlined">corporate_fare</span>
                                        <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                                            <option value="">Select Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('brand_id')
                                    <div class="invalid-feedback">
                                        <span class="material-icons-outlined">error</span>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <span class="material-icons-outlined">category</span>
                                        Category
                                    </label>
                                    <div class="input-with-icon">
                                        <span class="material-icons-outlined">layers</span>
                                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('category_id')
                                    <div class="invalid-feedback">
                                        <span class="material-icons-outlined">error</span>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label required">
                                        <span class="material-icons-outlined">toggle_on</span>
                                        Status
                                    </label>
                                    <div class="input-with-icon">
                                        <span class="material-icons-outlined">power_settings_new</span>
                                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                    @error('status')
                                    <div class="invalid-feedback">
                                        <span class="material-icons-outlined">error</span>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">
                                        <span class="material-icons-outlined">description</span>
                                        Description
                                        <span class="char-counter ms-2" id="descriptionCount">{{ strlen($product->description ?? '') }}/2000</span>
                                    </label>
                                    <div class="input-with-icon">
                                        <span class="material-icons-outlined">notes</span>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                                  rows="5" placeholder="Enter detailed product description..."
                                                  maxlength="2000" id="descriptionInput">{{ old('description', $product->description) }}</textarea>
                                    </div>
                                    @error('description')
                                    <div class="invalid-feedback">
                                        <span class="material-icons-outlined">error</span>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">
                                        <span class="material-icons-outlined">star</span>
                                        Key Features
                                        <span class="char-counter ms-2" id="featuresCount">{{ strlen($product->features ?? '') }}/2000</span>
                                    </label>
                                    <div class="input-with-icon">
                                        <span class="material-icons-outlined">format_list_bulleted</span>
                                        <textarea name="features" class="form-control @error('features') is-invalid @enderror"
                                                  rows="4" placeholder="Enter product key features (separate with commas)"
                                                  maxlength="2000" id="featuresInput">{{ old('features', $product->features) }}</textarea>
                                    </div>
                                    <div class="info-text">
                                        <span class="material-icons-outlined">tips_and_updates</span>
                                        <span>Separate features with commas for better readability</span>
                                    </div>
                                    @error('features')
                                    <div class="invalid-feedback">
                                        <span class="material-icons-outlined">error</span>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Image Section -->
                    <div class="form-section-card">
                        <div class="section-header">
                            <div class="icon-container">
                                <span class="material-icons-outlined">image</span>
                            </div>
                            <div>
                                <h5 class="mb-1">Product Media</h5>
                                <p class="text-muted">Update product image</p>
                            </div>
                        </div>

                        <!-- Current Image Display -->
                        @if($product->image)
                            <div class="current-image-container">
                                <div class="current-image-title">
                                    <span class="material-icons-outlined">photo_library</span>
                                    <span>Current Product Image</span>
                                </div>
                                <div class="d-flex flex-wrap align-items-start gap-4">
                                    <div class="current-image-wrapper">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="current-image">
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-2">
                                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">info</span>
                                            Current image will be replaced if you upload a new one.
                                        </p>
                                        <button type="button" class="btn btn-outline-danger" onclick="confirmImageRemoval()">
                                            <span class="material-icons-outlined me-1" style="font-size: 1rem;">delete</span>
                                            Remove Current Image
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="upload-container">
                            <div class="upload-area" id="uploadTrigger">
                                <span class="material-icons-outlined upload-icon">cloud_upload</span>
                                <h5 class="mt-3 mb-2 fw-bold">Drag & Drop New Image Here</h5>
                                <p class="text-muted mb-3">or click to browse files</p>
                                <p class="text-muted small">Supports JPG, PNG, GIF, WEBP - Max 2MB</p>
                                <input type="file" name="image" id="imageInput" class="d-none" accept="image/*">
                            </div>

                            <!-- Upload Progress -->
                            <div class="upload-progress" id="uploadProgress">
                                <div class="upload-progress-bar" id="uploadProgressBar"></div>
                            </div>

                            <!-- Image Preview -->
                            <img id="imagePreview" class="image-preview">

                            <div class="info-text mt-2">
                                <span class="material-icons-outlined">info</span>
                                <span>Leave empty to keep current image</span>
                            </div>

                            @error('image')
                            <div class="invalid-feedback d-block mt-3">
                                <span class="material-icons-outlined">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Pricing & Inventory Section -->
                    <div class="form-section-card">
                        <div class="section-header">
                            <div class="icon-container">
                                <span class="material-icons-outlined">payments</span>
                            </div>
                            <div>
                                <h5 class="mb-1">Pricing & Inventory</h5>
                                <p class="text-muted">Update pricing and manage stock</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">
                                <span class="material-icons-outlined">attach_money</span>
                                Cost Price (Rs.)
                            </label>
                            <div class="input-with-icon">
                                <span class="material-icons-outlined">price_change</span>
                                <input type="number" name="cost_price" id="costPrice"
                                       class="form-control @error('cost_price') is-invalid @enderror"
                                       step="0.01" min="0" value="{{ old('cost_price', $product->cost_price) }}"
                                       placeholder="0.00" required>
                            </div>
                            @error('cost_price')
                            <div class="invalid-feedback">
                                <span class="material-icons-outlined">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label required">
                                <span class="material-icons-outlined">price_check</span>
                                Selling Price (Rs.)
                            </label>
                            <div class="input-with-icon">
                                <span class="material-icons-outlined">sell</span>
                                <input type="number" name="selling_price" id="sellingPrice"
                                       class="form-control @error('selling_price') is-invalid @enderror"
                                       step="0.01" min="0" value="{{ old('selling_price', $product->selling_price) }}"
                                       placeholder="0.00" required>
                            </div>
                            @error('selling_price')
                            <div class="invalid-feedback">
                                <span class="material-icons-outlined">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <span class="material-icons-outlined">percent</span>
                                Discount (%)
                            </label>
                            <div class="input-with-icon">
                                <span class="material-icons-outlined">discount</span>
                                <input type="number" name="discount" id="discount"
                                       class="form-control @error('discount') is-invalid @enderror"
                                       step="0.01" min="0" max="100" value="{{ old('discount', $product->discount ?? 0) }}"
                                       placeholder="0">
                            </div>
                            @error('discount')
                            <div class="invalid-feedback">
                                <span class="material-icons-outlined">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <span class="material-icons-outlined">inventory</span>
                                Packaging Cost (Rs.)
                            </label>
                            <div class="input-with-icon">
                                <span class="material-icons-outlined">package</span>
                                <input type="number" name="packaging_cost" id="packagingCost"
                                       class="form-control @error('packaging_cost') is-invalid @enderror"
                                       step="0.01" min="0" value="{{ old('packaging_cost', $product->packaging_cost ?? 0) }}"
                                       placeholder="0.00">
                            </div>
                            @error('packaging_cost')
                            <div class="invalid-feedback">
                                <span class="material-icons-outlined">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label required">
                                <span class="material-icons-outlined">inventory_2</span>
                                Stock Quantity
                            </label>
                            <div class="input-with-icon">
                                <span class="material-icons-outlined">warehouse</span>
                                <input type="number" name="qty" id="quantity"
                                       class="form-control @error('qty') is-invalid @enderror"
                                       min="0" value="{{ old('qty', $product->qty) }}" placeholder="0" required>
                            </div>
                            @error('qty')
                            <div class="invalid-feedback">
                                <span class="material-icons-outlined">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label required">
                                <span class="material-icons-outlined">warning</span>
                                Minimum Stock Level
                            </label>
                            <div class="input-with-icon">
                                <span class="material-icons-outlined">notification_important</span>
                                <input type="number" name="min_stock_level"
                                       class="form-control @error('min_stock_level') is-invalid @enderror"
                                       min="1" value="{{ old('min_stock_level', $product->min_stock_level ?? 10) }}" placeholder="10" required>
                            </div>
                            <div class="info-text">
                                <span class="material-icons-outlined">notifications</span>
                                <span>Get notified when stock reaches this level</span>
                            </div>
                            @error('min_stock_level')
                            <div class="invalid-feedback">
                                <span class="material-icons-outlined">error</span>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- Price Summary Calculator -->
                        <div class="price-calculator">
                            <h6 class="mb-3 d-flex align-items-center">
                                <span class="material-icons-outlined me-2" style="color: var(--warning);">calculate</span>
                                Smart Price Summary
                            </h6>

                            <div class="price-row">
                                <span class="price-label">
                                    <span class="material-icons-outlined">summarize</span>
                                    Total Cost:
                                </span>
                                <span id="totalCostDisplay" class="price-value">Rs. 0.00</span>
                            </div>

                            <div class="price-row">
                                <span class="price-label">
                                    <span class="material-icons-outlined">price_check</span>
                                    Selling Price:
                                </span>
                                <span id="sellingPriceDisplay" class="price-value">Rs. 0.00</span>
                            </div>

                            <div class="price-row">
                                <span class="price-label">
                                    <span class="material-icons-outlined">percent</span>
                                    After Discount:
                                </span>
                                <span id="finalPriceDisplay" class="price-value positive">Rs. 0.00</span>
                            </div>

                            <div class="price-row">
                                <span class="price-label">
                                    <span class="material-icons-outlined">trending_up</span>
                                    Profit per Unit:
                                </span>
                                <span id="profitAmount" class="price-value">Rs. 0.00</span>
                            </div>

                            <div class="price-row">
                                <span class="price-label">
                                    <span class="material-icons-outlined">analytics</span>
                                    Total Profit:
                                </span>
                                <span id="totalProfitDisplay" class="price-value positive">Rs. 0.00</span>
                            </div>

                            <div class="price-row border-top mt-2 pt-2">
                                <span class="price-label">
                                    <span class="material-icons-outlined">analytics</span>
                                    Profit Margin:
                                </span>
                                <span id="profitMargin" class="price-value fw-bold">0.00%</span>
                            </div>

                            <div class="price-row">
                                <span class="price-label">
                                    <span class="material-icons-outlined">inventory_2</span>
                                    Stock Value:
                                </span>
                                <span id="stockValueDisplay" class="price-value">Rs. 0.00</span>
                            </div>

                            <div class="price-row">
                                <span class="price-label">
                                    <span class="material-icons-outlined">trending_up</span>
                                    Potential Revenue:
                                </span>
                                <span id="potentialValueDisplay" class="price-value positive">Rs. 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="form-section-card">
                        <div class="section-header">
                            <div class="icon-container">
                                <span class="material-icons-outlined">save</span>
                            </div>
                            <div>
                                <h5 class="mb-1">Review & Update</h5>
                                <p class="text-muted">Review changes and update product</p>
                            </div>
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-warning btn-action" id="submitBtn">
                                <span class="material-icons-outlined">save</span>
                                Update Product
                            </button>

                            <a href="{{ route('wholesaler.products.index') }}" class="btn btn-outline-secondary btn-action">
                                <span class="material-icons-outlined">cancel</span>
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Floating Action Buttons -->
    <div class="floating-actions">
        <button class="floating-btn warning" onclick="scrollToTop()" title="Scroll to Top">
            <span class="material-icons-outlined">vertical_align_top</span>
        </button>
        <button class="floating-btn danger" onclick="quickValidate()" title="Quick Validate">
            <span class="material-icons-outlined">fact_check</span>
        </button>
    </div>

    <!-- Hidden input for image removal flag -->
    <input type="hidden" name="remove_current_image" id="removeCurrentImage" value="0" form="productForm">
@endsection

@push('script')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========== DOM Elements ==========
            const uploadTrigger = document.getElementById('uploadTrigger');
            const imageInput = document.getElementById('imageInput');
            const imagePreview = document.getElementById('imagePreview');
            const form = document.getElementById('productForm');
            const submitBtn = document.getElementById('submitBtn');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const uploadProgress = document.getElementById('uploadProgress');
            const uploadProgressBar = document.getElementById('uploadProgressBar');
            const alertContainer = document.getElementById('alertContainer');
            const removeCurrentImage = document.getElementById('removeCurrentImage');

            // Price Calculation Elements
            const costPrice = document.getElementById('costPrice');
            const sellingPrice = document.getElementById('sellingPrice');
            const discount = document.getElementById('discount');
            const packagingCost = document.getElementById('packagingCost');
            const quantity = document.getElementById('quantity');

            // Character Counter Elements
            const descriptionInput = document.getElementById('descriptionInput');
            const featuresInput = document.getElementById('featuresInput');
            const descriptionCount = document.getElementById('descriptionCount');
            const featuresCount = document.getElementById('featuresCount');

            // ========== Initialize ==========
            updateCharacterCounts();
            updatePriceDisplay();
            initializeValidation();

            // ========== Character Counter Functions ==========
            function updateCharacterCounts() {
                if (descriptionInput && descriptionCount) {
                    const descLength = descriptionInput.value.length;
                    descriptionCount.textContent = `${descLength}/2000`;

                    if (descLength > 1800) {
                        descriptionCount.className = descLength >= 2000 ? 'char-counter danger' : 'char-counter warning';
                    } else {
                        descriptionCount.className = 'char-counter';
                    }
                }

                if (featuresInput && featuresCount) {
                    const featuresLength = featuresInput.value.length;
                    featuresCount.textContent = `${featuresLength}/2000`;

                    if (featuresLength > 1800) {
                        featuresCount.className = featuresLength >= 2000 ? 'char-counter danger' : 'char-counter warning';
                    } else {
                        featuresCount.className = 'char-counter';
                    }
                }
            }

            if (descriptionInput) {
                descriptionInput.addEventListener('input', updateCharacterCounts);
            }
            if (featuresInput) {
                featuresInput.addEventListener('input', updateCharacterCounts);
            }

            // ========== Image Upload with Progress ==========
            if (uploadTrigger && imageInput) {
                // Click to upload
                uploadTrigger.addEventListener('click', () => {
                    imageInput.click();
                });

                // Drag and drop
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
                        handleImageUpload(e.dataTransfer.files[0]);
                    }
                });

                // File input change
                imageInput.addEventListener('change', (e) => {
                    if (e.target.files.length > 0) {
                        handleImageUpload(e.target.files[0]);
                    }
                });
            }

            function handleImageUpload(file) {
                if (!file) return;

                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    showAlert('Please upload only JPG, PNG, GIF, or WEBP images.', 'error');
                    imageInput.value = '';
                    return;
                }

                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    showAlert('Image size must be less than 2MB.', 'error');
                    imageInput.value = '';
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
            }

            function createImagePreview(file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.add('show');

                    // Hide current image if exists
                    const currentImage = document.querySelector('.current-image');
                    if (currentImage) {
                        currentImage.style.opacity = '0.5';
                    }

                    showAlert('New image selected. It will be uploaded when you update the product.', 'success');
                };

                reader.readAsDataURL(file);
            }

            // ========== Image Removal Confirmation ==========
            window.confirmImageRemoval = function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Remove Current Image?',
                    html: `
                        <div class="text-start">
                            <p>This will remove the current product image.</p>
                            <p class="text-muted">You can upload a new one after removal.</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, remove it',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Set flag for image removal
                        removeCurrentImage.value = '1';

                        // Hide current image container
                        const currentContainer = document.querySelector('.current-image-container');
                        if (currentContainer) {
                            currentContainer.style.opacity = '0.5';
                            currentContainer.style.backgroundColor = '#fee2e2';
                        }

                        showAlert('Current image marked for removal. It will be removed when you update the product.', 'warning');
                    }
                });
            };

            // ========== Price Calculation ==========
            function updatePriceDisplay() {
                const cost = parseFloat(costPrice?.value) || 0;
                const selling = parseFloat(sellingPrice?.value) || 0;
                const disc = parseFloat(discount?.value) || 0;
                const packaging = parseFloat(packagingCost?.value) || 0;
                const qty = parseInt(quantity?.value) || 0;

                // Calculations
                const totalCostPerUnit = cost + packaging;
                const totalCost = totalCostPerUnit * qty;
                const discountAmount = selling * (disc / 100);
                const finalPricePerUnit = selling - discountAmount;
                const profitPerUnit = finalPricePerUnit - totalCostPerUnit;
                const totalProfit = profitPerUnit * qty;
                const marginPercent = totalCostPerUnit > 0 ? (profitPerUnit / totalCostPerUnit) * 100 : 0;
                const stockValue = cost * qty;
                const potentialValue = finalPricePerUnit * qty;

                // Update displays
                const displays = {
                    totalCostDisplay: totalCostPerUnit,
                    sellingPriceDisplay: selling,
                    finalPriceDisplay: finalPricePerUnit,
                    profitAmount: profitPerUnit,
                    totalProfitDisplay: totalProfit,
                    profitMargin: marginPercent,
                    stockValueDisplay: stockValue,
                    potentialValueDisplay: potentialValue
                };

                for (const [id, value] of Object.entries(displays)) {
                    const element = document.getElementById(id);
                    if (element) {
                        if (id === 'profitMargin') {
                            element.textContent = `${value.toFixed(2)}%`;
                        } else {
                            element.textContent = `Rs. ${value.toLocaleString('en-IN', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            })}`;
                        }
                    }
                }

                // Color code profit margin
                const profitMarginEl = document.getElementById('profitMargin');
                const profitAmountEl = document.getElementById('profitAmount');
                const totalProfitEl = document.getElementById('totalProfitDisplay');

                if (marginPercent > 30) {
                    [profitMarginEl, profitAmountEl, totalProfitEl].forEach(el => {
                        if (el) el.style.color = 'var(--success)';
                    });
                } else if (marginPercent > 10) {
                    [profitMarginEl, profitAmountEl, totalProfitEl].forEach(el => {
                        if (el) el.style.color = 'var(--warning)';
                    });
                } else if (marginPercent > 0) {
                    [profitMarginEl, profitAmountEl, totalProfitEl].forEach(el => {
                        if (el) el.style.color = 'var(--gray-500)';
                    });
                } else {
                    [profitMarginEl, profitAmountEl, totalProfitEl].forEach(el => {
                        if (el) el.style.color = 'var(--danger)';
                    });
                }
            }

            // Bind price calculation events
            [costPrice, sellingPrice, discount, packagingCost, quantity].forEach(input => {
                if (input) {
                    input.addEventListener('input', updatePriceDisplay);
                    input.addEventListener('change', updatePriceDisplay);
                }
            });

            // ========== Form Validation ==========
            function initializeValidation() {
                const requiredFields = form.querySelectorAll('[required]');

                requiredFields.forEach(field => {
                    field.addEventListener('blur', function() {
                        if (!this.value.trim()) {
                            this.classList.add('is-invalid');
                        } else {
                            this.classList.remove('is-invalid');
                        }
                    });
                });
            }

            function validateForm() {
                let isValid = true;
                const errors = [];

                // Clear all invalid classes
                form.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });

                // Check required fields
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value || !field.value.toString().trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');

                        const fieldName = field.name || 'Field';
                        errors.push(`${fieldName} is required`);
                    }
                });

                // Price validation
                const cost = parseFloat(costPrice?.value) || 0;
                const selling = parseFloat(sellingPrice?.value) || 0;

                if (selling < cost) {
                    isValid = false;
                    if (sellingPrice) sellingPrice.classList.add('is-invalid');
                    errors.push('Selling price cannot be lower than cost price');
                }

                const disc = parseFloat(discount?.value) || 0;
                if (disc > 100) {
                    isValid = false;
                    if (discount) discount.classList.add('is-invalid');
                    errors.push('Discount cannot exceed 100%');
                }

                return { isValid, errors };
            }

            // ========== Form Submission ==========
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const validation = validateForm();

                    if (!validation.isValid) {
                        // Show first error
                        if (validation.errors.length > 0) {
                            showAlert(validation.errors[0], 'error');
                        }

                        // Scroll to first invalid field
                        const firstInvalid = form.querySelector('.is-invalid');
                        if (firstInvalid) {
                            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        return;
                    }

                    // Show confirmation dialog
                    Swal.fire({
                        icon: 'question',
                        title: 'Update Product?',
                        html: `
                            <div class="text-start">
                                <p>Are you sure you want to update this product?</p>
                                <div class="bg-light p-3 rounded mt-3">
                                    <p class="mb-1"><strong>Product Name:</strong> ${document.querySelector('input[name="name"]')?.value || 'N/A'}</p>
                                    <p class="mb-1"><strong>Selling Price:</strong> Rs. ${sellingPrice?.value || '0'}</p>
                                    <p class="mb-0"><strong>Stock Quantity:</strong> ${quantity?.value || '0'}</p>
                                </div>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonColor: '#f59e0b',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, update it',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            loadingOverlay.style.display = 'flex';
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<span class="material-icons-outlined">hourglass_top</span> Updating...';

                            // Submit the form normally (not AJAX)
                            form.submit();
                        }
                    });
                });
            }

            // ========== Alert Function ==========
            function showAlert(message, type = 'info') {
                const alert = document.createElement('div');
                alert.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'warning'} alert-dismissible fade show`;

                let icon = 'info';
                let title = 'Info';

                if (type === 'error') {
                    icon = 'error';
                    title = 'Error!';
                } else if (type === 'success') {
                    icon = 'check_circle';
                    title = 'Success!';
                } else if (type === 'warning') {
                    icon = 'warning';
                    title = 'Warning!';
                }

                alert.innerHTML = `
                    <div class="d-flex align-items-center">
                        <span class="material-icons-outlined me-3" style="font-size: 2rem; color: var(--${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'warning'});">
                            ${icon}
                        </span>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${title}</h6>
                            <p class="mb-0">${message}</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                alertContainer.innerHTML = '';
                alertContainer.appendChild(alert);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 5000);
            }

            // ========== Keyboard Shortcuts ==========
            document.addEventListener('keydown', function(e) {
                // Don't trigger shortcuts when typing in inputs
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
                    return;
                }

                // Ctrl + S to save
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    submitBtn.click();
                }

                // Ctrl + Enter to quick validate
                if (e.ctrlKey && e.key === 'Enter') {
                    e.preventDefault();
                    quickValidate();
                }
            });

            // ========== Quick Validate Function ==========
            window.quickValidate = function() {
                const validation = validateForm();

                if (validation.isValid) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Validation Passed!',
                        text: 'All required fields are filled correctly.',
                        confirmButtonColor: '#f59e0b',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    showAlert(validation.errors[0], 'error');

                    // Scroll to first invalid field
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            };

            // ========== Scroll to Top Function ==========
            window.scrollToTop = function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };

            // ========== Auto-close existing alerts ==========
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    if (typeof bootstrap !== 'undefined') {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    } else {
                        alert.remove();
                    }
                });
            }, 5000);
        });
    </script>
@endpush
