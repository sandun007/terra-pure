@extends('seller.layout.app')

@push('title')
    Create New Order
@endpush

@push('styles')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
            --info-gradient: linear-gradient(135deg, #17ead9 0%, #6078ea 100%);
            --warning-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        }

        .border-dashed {
            border: 2px dashed #dee2e6 !important;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .border-dashed.highlight {
            border-color: #667eea !important;
            background-color: rgba(102, 126, 234, 0.05);
            transform: translateY(-2px);
        }

        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            border: none;
        }

        .stat-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
        }

        .card-section {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card-section:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        }

        .card-section .card-header {
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }

        .icon-wrapper {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
        }

        .file-upload-area {
            min-height: 200px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-area:hover {
            background-color: rgba(102, 126, 234, 0.02);
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }

        .btn-success {
            background: var(--success-gradient);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(11, 163, 96, 0.3);
        }

        .floating-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            max-width: 400px;
            animation: slideInRight 0.3s ease;
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

        .progress-bar {
            height: 4px;
            background: var(--primary-gradient);
            width: 0%;
            transition: width 0.3s ease;
        }

        /* Product Details Card Styles */
        .product-details-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
            animation: fadeIn 0.5s ease;
        }

        .product-image-container {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .product-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            padding: 20px;
        }

        .product-details-body {
            padding: 1.5rem;
        }

        .product-detail-row {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .product-detail-row:last-child {
            border-bottom: none;
        }

        .product-detail-label {
            min-width: 140px;
            font-weight: 600;
            color: #4b5563;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .product-detail-value {
            flex: 1;
            color: #1f2937;
            font-weight: 500;
        }

        .product-detail-value.price {
            color: #10b981;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .product-detail-value.stock {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .stock-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .stock-available {
            background: #d1fae5;
            color: #065f46;
        }

        .stock-low {
            background: #fef3c7;
            color: #92400e;
        }

        .stock-out {
            background: #fee2e2;
            color: #991b1b;
        }

        .product-description {
            margin-top: 15px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #4b5563;
        }

        .required {
            color: #dc2626;
            font-weight: bold;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .floating-alert {
                left: 20px;
                right: 20px;
                max-width: none;
            }

            .stat-card .card-body {
                padding: 1rem !important;
            }

            .product-detail-label {
                min-width: 120px;
                font-size: 0.9rem;
            }

            .product-image-container {
                height: 150px;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <!-- Floating Alert Messages -->
        <div id="floating-message-container" class="floating-alert"></div>

        <!-- Header Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h1 class="h3 mb-2 fw-bold text-dark">Create New Order</h1>
                        <p class="text-muted mb-0">Add new orders manually or upload bulk orders via Excel</p>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                            <span class="material-icons-round me-2" style="font-size: 1.1rem">person</span>
                            {{ auth()->user()->name }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Dashboard -->
        <div class="row g-4 mb-5">
            <!-- Available Balance -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="stat-card card border-0 shadow-lg rounded-4" style="background: var(--primary-gradient);">
                    <div class="card-body p-4 position-relative text-white">
                        <div class="position-absolute top-0 end-0 mt-3 me-3 opacity-25">
                            <span class="material-icons-round" style="font-size: 4rem">account_balance_wallet</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <span class="material-icons-round" style="font-size: 1.8rem">account_balance_wallet</span>
                            </div>
                            <div>
                                <p class="mb-1 opacity-85">Available Balance</p>
                                <h3 class="mb-0 fw-bold">
                                    Rs. {{ number_format($user->cal_deposit ?? $user->deposit ?? 0, 2) }}
                                </h3>
                                <small class="opacity-75">Updated just now</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cost Per Lead -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="stat-card card border-0 shadow-lg rounded-4" style="background: var(--info-gradient);">
                    <div class="card-body p-4 position-relative text-white">
                        <div class="position-absolute top-0 end-0 mt-3 me-3 opacity-25">
                            <span class="material-icons-round" style="font-size: 4rem">trending_up</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <span class="material-icons-round" style="font-size: 1.8rem">trending_up</span>
                            </div>
                            <div>
                                <p class="mb-1 opacity-85">Cost Per Lead</p>
                                <h3 class="mb-0 fw-bold">
                                    Rs. {{ number_format($user->cost ?? 0, 2) }}
                                </h3>
                                <small class="opacity-75">Current rate</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Upload Leads -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="stat-card card border-0 shadow-lg rounded-4" style="background: var(--warning-gradient);">
                    <div class="card-body p-4 position-relative text-white">
                        <div class="position-absolute top-0 end-0 mt-3 me-3 opacity-25">
                            <span class="material-icons-round" style="font-size: 4rem">group_add</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="icon-wrapper me-3">
                                <span class="material-icons-round" style="font-size: 1.8rem">group_add</span>
                            </div>
                            <div>
                                <p class="mb-1 opacity-85">Last Upload Leads</p>
                                <h3 class="mb-0 fw-bold">
                                    {{ $user->last_row_count ?? '0' }}
                                </h3>
                                <small class="opacity-75">Previous batch</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Form -->
        <form action="{{ route('seller.order.store') }}" method="post" enctype="multipart/form-data" id="orderForm" class="needs-validation" novalidate>
            @csrf

            <!-- Hidden fields for product data -->
            <input type="hidden" id="productId" name="product_id" value="{{ request()->get('product_id', '') }}">
            <input type="hidden" id="productNameHidden" name="product_name">
            <input type="hidden" id="productImageHidden" name="product_image">
            <input type="hidden" id="productCodeHidden" name="product_code">
            <input type="hidden" id="productPriceHidden" name="product_price">
            <input type="hidden" id="productStockHidden" name="product_stock">
            <input type="hidden" id="productDescriptionHidden" name="product_description">

            <div class="row g-4">
                <!-- Left Column: Product & Order Details -->
                <div class="col-lg-8">
                    <!-- Product Details Card -->
                    <div class="card card-section mb-4">
                        <div class="card-header bg-primary bg-opacity-10 border-bottom">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-wrapper bg-primary bg-opacity-10 text-primary me-3">
                                        <span class="material-icons-round">inventory_2</span>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold">Product Details</h5>
                                        <small class="text-muted">Search and select product from inventory</small>
                                    </div>
                                </div>
                                <span class="badge bg-primary px-3 py-2">
                                    <span class="material-icons-round me-1" style="font-size: 1.1rem">tag</span>
                                    Required (unless using Excel)
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Product Search -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">
                                        Product ID / Code
                                        <span class="required">*</span> (Required for single order)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons-round text-muted">search</span>
                                        </span>
                                        <input type="text" id="productIdInput" name="product_id_input"
                                               class="form-control"
                                               value="{{ request()->get('product_id') ?? old('product_id') }}"
                                               placeholder="Enter product ID or code"
                                               onkeypress="if(event.key === 'Enter') { event.preventDefault(); fetchProductDetails(); }">
                                        <button type="button" class="btn btn-primary" onclick="fetchProductDetails()">
                                            <span class="material-icons-round me-2">search</span>
                                            Fetch Product
                                        </button>
                                    </div>
                                    <small class="text-muted">Enter product ID or code from shop (not required for Excel upload)</small>
                                </div>
                            </div>

                            <!-- Product Preview Section -->
                            <div id="productPreview" class="d-none">
                                <div class="product-details-card">
                                    <!-- Product Image -->
                                    <div class="product-image-container">
                                        <img id="productImage" src="" alt="Product Image" class="product-image"
                                             onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%236b7280\'%3E%3Cpath d=\'M21 16.5c0 .38-.21.71-.53.88l-7.9 4.44c-.16.12-.36.18-.57.18s-.41-.06-.57-.18l-7.9-4.44A.991.991 0 013 16.5v-9c0-.38.21-.71.53-.88l7.9-4.44c.16-.12.36-.18.57-.18s.41.06.57.18l7.9 4.44c.32.17.53.5.53.88v9zM12 4.15L6.04 7.5 12 10.85l5.96-3.35L12 4.15zM5 15.91l6 3.38v-6.71L5 9.21v6.7zm14 0v-6.7l-6 3.37v6.71l6-3.38z\'/%3E%3C/svg%3E';">
                                    </div>

                                    <!-- Product Details -->
                                    <div class="product-details-body">
                                        <!-- Product Name -->
                                        <div class="product-detail-row">
                                            <div class="product-detail-label">
                                                <span class="material-icons-round" style="font-size: 1.2rem">inventory_2</span>
                                                Product Name:
                                            </div>
                                            <div class="product-detail-value" id="productName">-</div>
                                        </div>

                                        <!-- Product Code -->
                                        <div class="product-detail-row">
                                            <div class="product-detail-label">
                                                <span class="material-icons-round" style="font-size: 1.2rem">qr_code</span>
                                                Product Code:
                                            </div>
                                            <div class="product-detail-value" id="productCode">-</div>
                                        </div>

                                        <!-- Product Price -->
                                        <div class="product-detail-row">
                                            <div class="product-detail-label">
                                                <span class="material-icons-round" style="font-size: 1.2rem">attach_money</span>
                                                Unit Price:
                                            </div>
                                            <div class="product-detail-value price" id="productPrice">Rs. 0.00</div>
                                        </div>

                                        <!-- Stock Information -->
                                        <div class="product-detail-row">
                                            <div class="product-detail-label">
                                                <span class="material-icons-round" style="font-size: 1.2rem">warehouse</span>
                                                Stock Status:
                                            </div>
                                            <div class="product-detail-value stock" id="productStockContainer">
                                                <span id="productStock">0</span> units
                                                <span class="stock-badge" id="stockBadge"></span>
                                            </div>
                                        </div>

                                        <!-- Category -->
                                        <div class="product-detail-row">
                                            <div class="product-detail-label">
                                                <span class="material-icons-round" style="font-size: 1.2rem">category</span>
                                                Category:
                                            </div>
                                            <div class="product-detail-value" id="productCategory">-</div>
                                        </div>

                                        <!-- Description -->
                                        <div class="mt-4" id="productDescriptionContainer" style="display: none;">
                                            <div class="product-detail-label mb-2">
                                                <span class="material-icons-round" style="font-size: 1.2rem">description</span>
                                                Description:
                                            </div>
                                            <div class="product-description" id="productDescription">
                                                No description available
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Details Card -->
                    <div class="card card-section mb-4">
                        <div class="card-header bg-success bg-opacity-10 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-success bg-opacity-10 text-success me-3">
                                    <span class="material-icons-round">person_add</span>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">Customer Information</h5>
                                    <small class="text-muted">Enter customer details for delivery (Required for single order)</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Customer Name
                                        <span class="required">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons-round text-muted">person</span>
                                        </span>
                                        <input type="text" name="customer_name" class="form-control"
                                               placeholder="Enter full name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Phone Number
                                        <span class="required">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons-round text-muted">phone</span>
                                        </span>
                                        <input type="text" name="customer_phone" class="form-control"
                                               placeholder="+94 77 123 4567">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Delivery Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons-round text-muted">location_on</span>
                                        </span>
                                        <textarea name="customer_address" class="form-control"
                                                  rows="2" placeholder="Full delivery address"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">District / City</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <span class="material-icons-round text-muted">map</span>
                                        </span>
                                        <input type="text" name="district" class="form-control"
                                               placeholder="Enter district">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Platform</label>
                                    <select class="form-select" name="platform">
                                        <option value="">Select Platform</option>
                                        <option value="Facebook">Facebook</option>
                                        <option value="TikTok">TikTok</option>
                                        <option value="Instagram">Instagram</option>
                                        <option value="WhatsApp">WhatsApp</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Info & File Upload -->
                <div class="col-lg-4">
                    <!-- Order Information Card -->
                    <div class="card card-section mb-4">
                        <div class="card-header bg-info bg-opacity-10 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-info bg-opacity-10 text-info me-3">
                                    <span class="material-icons-round">receipt_long</span>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">Order Information</h5>
                                    <small class="text-muted">Order metadata & status</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Order Number</label>
                                <div class="alert alert-secondary py-2 mb-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <code class="fw-bold">{{ $code }}</code>
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                onclick="copyOrderNumber()">
                                            <span class="material-icons-round" style="font-size: 1.1rem">content_copy</span>
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">Auto-generated</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Order Date
                                    <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <span class="material-icons-round text-muted">calendar_today</span>
                                    </span>
                                    <input type="date" name="date" class="form-control"
                                           value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Call Status</label>
                                <select name="call_status" class="form-select">
                                    <option value="">Select Status</option>
                                    <option value="Conform" selected>Conform</option>
                                    <option value="Not Answer">Not Answer</option>
                                    <option value="Reject">Reject</option>
                                    <option value="Other">Other</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Remarks</label>
                                <textarea name="remark" class="form-control" rows="3"
                                          placeholder="Additional notes about this order..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Bulk Upload Card -->
                    <div class="card card-section">
                        <div class="card-header bg-warning bg-opacity-10 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-warning bg-opacity-10 text-warning me-3">
                                    <span class="material-icons-round">upload_file</span>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">Bulk Upload (Optional)</h5>
                                    <small class="text-muted">Upload multiple orders via Excel</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-info border-0 mb-4">
                                <div class="d-flex">
                                    <span class="material-icons-round text-info me-3">info</span>
                                    <div>
                                        <small>
                                            <strong>Note:</strong> Upload Excel for bulk orders OR fill the form for single order.
                                            Excel upload will override form data.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="file-upload-area border-dashed rounded-3 p-4 text-center position-relative"
                                 id="dropZone" onclick="document.getElementById('excelFile').click()">
                                <input type="file" name="excel" id="excelFile"
                                       class="form-control form-control-lg d-none"
                                       accept=".xlsx,.xls,.csv" onchange="handleFileSelect(event)">

                                <div id="uploadPlaceholder">
                                    <div class="mb-4">
                                        <span class="material-icons-round text-muted" style="font-size: 4rem">
                                            cloud_upload
                                        </span>
                                    </div>
                                    <h5 class="mb-2">Drag & drop Excel file</h5>
                                    <p class="text-muted mb-4">or click to browse</p>
                                    <button type="button" class="btn btn-outline-warning px-4">
                                        <span class="material-icons-round me-2">folder_open</span>
                                        Choose File
                                    </button>
                                    <p class="text-muted small mt-4 mb-0">
                                        Supports: .xlsx, .xls, .csv (Max 10MB)
                                    </p>
                                </div>

                                <div id="filePreview" class="d-none">
                                    <div class="alert alert-success border-0 mb-0">
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons-round text-success me-3">check_circle</span>
                                            <div class="flex-grow-1 text-start">
                                                <strong id="fileName"></strong>
                                                <div class="small text-muted" id="fileSize"></div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="event.stopPropagation(); clearFile();">
                                                <span class="material-icons-round">close</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="progress mt-2" style="height: 6px;">
                                        <div class="progress-bar bg-success" id="uploadProgress" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="#" class="text-decoration-none small d-flex align-items-center text-primary">
                                    <span class="material-icons-round me-2" style="font-size: 1.1rem">download</span>
                                    Download Excel template
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Submission -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-wrapper bg-primary bg-opacity-10 text-primary me-3">
                                            <span class="material-icons-round">info</span>
                                        </div>
                                        <div>
                                            <small class="text-muted">
                                                Fields marked with <span class="required">*</span> are required for single orders
                                            </small>
                                            <div class="small text-muted mt-1">
                                                Balance available: Rs. {{ number_format($user->cal_deposit ?? $user->deposit ?? 0, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex gap-3 justify-content-md-end">
                                        <button type="button" class="btn btn-outline-secondary px-4 py-3 rounded-pill" onclick="resetForm()">
                                            <span class="material-icons-round me-2">refresh</span>
                                            Reset
                                        </button>
                                        <button type="submit" id="submitBtn"
                                                class="btn btn-success px-5 py-3 rounded-pill shadow-sm">
                                            <span id="btnText">
                                                <span class="material-icons-round me-2">check_circle</span>
                                                Create Order
                                            </span>
                                            <span id="btnLoader" class="d-none">
                                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                                Processing...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Global variables
        let isExcelUploaded = false;

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-fetch product if ID exists in URL
            const urlParams = new URLSearchParams(window.location.search);
            const productId = urlParams.get('product_id');
            if (productId) {
                document.getElementById('productIdInput').value = productId;
                setTimeout(() => fetchProductDetails(), 100);
            }

            // Initialize drag & drop
            initDragAndDrop();

            // Show session messages as floating alerts
            showSessionMessages();
        });

        // Show session messages as floating alerts
        function showSessionMessages() {
            @if(session('success'))
            showFloatingAlert('success', '{{ session('success') }}');
            @endif
            @if(session('error'))
            showFloatingAlert('error', '{{ session('error') }}');
            @endif
            @if($errors->any())
            @foreach($errors->all() as $error)
            showFloatingAlert('error', '{{ $error }}');
            @endforeach
            @endif
        }

        // Floating alert system
        function showFloatingAlert(type, message) {
            const container = document.getElementById('floating-message-container');
            const icon = type === 'success' ? 'check_circle' : 'error';
            const color = type === 'success' ? 'success' : 'danger';

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${color} alert-dismissible fade show shadow-lg mb-2`;
            alertDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <span class="material-icons-round me-3">${icon}</span>
                    <div class="flex-grow-1">${message}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            container.appendChild(alertDiv);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 150);
            }, 5000);
        }

        // Reset form function
        function resetForm() {
            // Reset the entire form
            document.getElementById('orderForm').reset();

            // Hide product preview
            document.getElementById('productPreview').classList.add('d-none');
            document.getElementById('productIdInput').value = '';

            // Clear product details
            document.getElementById('productName').textContent = '-';
            document.getElementById('productCode').textContent = '-';
            document.getElementById('productPrice').textContent = 'Rs. 0.00';
            document.getElementById('productStock').textContent = '0';
            document.getElementById('productCategory').textContent = '-';
            document.getElementById('productImage').src = '';
            document.getElementById('stockBadge').textContent = '';
            document.getElementById('stockBadge').className = 'stock-badge';
            document.getElementById('productDescriptionContainer').style.display = 'none';
            document.getElementById('productDescription').textContent = 'No description available';

            // Clear hidden fields
            document.getElementById('productId').value = '';
            document.getElementById('productNameHidden').value = '';
            document.getElementById('productImageHidden').value = '';
            document.getElementById('productCodeHidden').value = '';
            document.getElementById('productPriceHidden').value = '';
            document.getElementById('productStockHidden').value = '';
            document.getElementById('productDescriptionHidden').value = '';

            // Clear file upload
            clearFile();

            // Reset Excel upload flag
            isExcelUploaded = false;

            showFloatingAlert('info', 'Form has been reset');
        }


            // Updated fetchProductDetails function with proper product code handling
            async function fetchProductDetails() {
            const productId = document.getElementById('productIdInput').value.trim();
            const productPreview = document.getElementById('productPreview');

            if (!productId) {
            showFloatingAlert('error', 'Please enter a product ID');
            document.getElementById('productIdInput').focus();
            return;
        }

            // Show loading state
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
            Fetching Product...
        `;

            try {
            // Fetch product details from shop endpoint
            const response = await fetch(`/seller/shop/product/${productId}/details`);

            if (!response.ok) {
            throw new Error('Product not found');
        }

            const data = await response.json();

            if (data.success) {
            // Update UI with product details
            document.getElementById('productName').textContent = data.product.name;
            // IMPORTANT: Product code will be in DX/ITM/XXXX format
            document.getElementById('productCode').textContent = data.product.code;
            document.getElementById('productPrice').textContent = 'Rs. ' + parseFloat(data.product.display_price).toFixed(2);
            document.getElementById('productStock').textContent = data.product.qty;
            document.getElementById('productCategory').textContent = data.product.category || 'Uncategorized';

            // Update product image
            const productImage = document.getElementById('productImage');
            if (data.product.image) {
            productImage.src = data.product.image;
            productImage.alt = data.product.name;
        }

            // Update stock badge
            const stockBadge = document.getElementById('stockBadge');
            if (data.product.qty === 0) {
            stockBadge.textContent = 'Out of Stock';
            stockBadge.className = 'stock-badge stock-out';
        } else if (data.product.qty <= 5) {
            stockBadge.textContent = 'Low Stock';
            stockBadge.className = 'stock-badge stock-low';
        } else {
            stockBadge.textContent = 'In Stock';
            stockBadge.className = 'stock-badge stock-available';
        }

            // Update description if available
            if (data.product.description && data.product.description.trim() !== '') {
            document.getElementById('productDescription').textContent = data.product.description;
            document.getElementById('productDescriptionContainer').style.display = 'block';
        } else {
            document.getElementById('productDescriptionContainer').style.display = 'none';
        }

            // Set hidden fields
            document.getElementById('productId').value = productId;
            document.getElementById('productNameHidden').value = data.product.name;
            document.getElementById('productImageHidden').value = data.product.image || '';
            // IMPORTANT: Store the product code in DX/ITM/XXXX format
            document.getElementById('productCodeHidden').value = data.product.code;
            document.getElementById('productPriceHidden').value = data.product.display_price;
            document.getElementById('productStockHidden').value = data.product.qty;
            document.getElementById('productDescriptionHidden').value = data.product.description || '';

            // Show product preview with animation
            productPreview.classList.remove('d-none');
            productPreview.style.animation = 'none';
            setTimeout(() => {
            productPreview.style.animation = 'fadeIn 0.5s ease';
        }, 10);

            // Show success message
            showFloatingAlert('success', `Product loaded: ${data.product.code} - ${data.product.name}`);

        } else {
            throw new Error(data.message || 'Product not found');
        }
        } catch (error) {
            console.error('Error fetching product:', error);
            productPreview.classList.add('d-none');
            showFloatingAlert('error', 'Product not found. Please check the product ID and try again.');
        } finally {
            // Restore button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
        }

        // Copy order number to clipboard
        function copyOrderNumber() {
            const orderNumber = "{{ $code }}";
            navigator.clipboard.writeText(orderNumber).then(() => {
                showFloatingAlert('success', 'Order number copied to clipboard!');
            }).catch(() => {
                showFloatingAlert('error', 'Failed to copy order number');
            });
        }

        // File upload functions
        function initDragAndDrop() {
            const dropZone = document.getElementById('dropZone');

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.add('highlight');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.classList.remove('highlight');
                }, false);
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                const files = e.dataTransfer.files;
                if (files.length) {
                    document.getElementById('excelFile').files = files;
                    handleFileSelect({ target: { files: files } });
                }
            });
        }

        function handleFileSelect(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validate file type
            const allowedExtensions = /(\.xlsx|\.xls|\.csv)$/i;
            if (!allowedExtensions.exec(file.name)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please upload only Excel files (.xlsx, .xls, .csv)',
                    confirmButtonText: 'OK'
                });
                clearFile();
                return;
            }

            // Validate file size (10MB max)
            const maxSize = 10 * 1024 * 1024;
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'Maximum file size is 10MB',
                    confirmButtonText: 'OK'
                });
                clearFile();
                return;
            }

            // Show file preview
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = formatFileSize(file.size);

            // Show upload progress (simulated)
            simulateUploadProgress();

            document.getElementById('uploadPlaceholder').classList.add('d-none');
            document.getElementById('filePreview').classList.remove('d-none');

            // Set Excel uploaded flag
            isExcelUploaded = true;
        }

        function simulateUploadProgress() {
            const progressBar = document.getElementById('uploadProgress');
            let width = 0;
            const interval = setInterval(() => {
                if (width >= 100) {
                    clearInterval(interval);
                    progressBar.style.width = '100%';
                } else {
                    width += 10;
                    progressBar.style.width = width + '%';
                }
            }, 100);
        }

        function clearFile() {
            document.getElementById('excelFile').value = '';
            document.getElementById('filePreview').classList.add('d-none');
            document.getElementById('uploadPlaceholder').classList.remove('d-none');
            document.getElementById('uploadProgress').style.width = '0%';
            isExcelUploaded = false;
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Form submission handling
        document.getElementById('orderForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');

            // Check if Excel file is uploaded
            const excelFile = document.getElementById('excelFile').files.length;
            const productPreview = document.getElementById('productPreview');

            // If no Excel file, validate single order requirements
            if (!excelFile) {
                // Check if product is selected
                if (productPreview.classList.contains('d-none')) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Product Required',
                        text: 'Please fetch product details before creating order, or upload an Excel file for bulk orders',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        document.getElementById('productIdInput').focus();
                    });
                    return;
                }

                // Validate product is in stock for single orders
                const productStock = parseInt(document.getElementById('productStockHidden').value) || 0;
                if (productStock === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Out of Stock',
                        text: 'Selected product is out of stock',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                document.addEventListener('DOMContentLoaded', function() {
                    // Auto-fetch product if ID exists in URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const productId = urlParams.get('product_id');

                    if (productId) {
                        document.getElementById('productIdInput').value = productId;
                        setTimeout(() => fetchProductDetails(), 100);
                    }
                });
                // Validate customer name and phone for single orders
                const customerName = document.querySelector('input[name="customer_name"]').value.trim();
                const customerPhone = document.querySelector('input[name="customer_phone"]').value.trim();

                if (!customerName || !customerPhone) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Customer Details Required',
                        text: 'Please fill in customer name and phone number',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
            }

            // Show loading state
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            btnLoader.classList.remove('d-none');
        });
    </script>
@endpush
