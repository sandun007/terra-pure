@extends('seller.layout.app')
@push('title')
    New Order
@endpush

@section('content')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Google Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <!-- Session Messages -->
        <div id="message-container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                    <span class="material-icons-round me-3">check_circle</span>
                    <div class="flex-grow-1">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                    <span class="material-icons-round me-3">error</span>
                    <div class="flex-grow-1">{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-5">
            <!-- Available Balance -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 bg-gradient-primary text-white overflow-hidden">
                    <div class="card-body p-4 position-relative">
                        <div class="position-absolute top-0 end-0 mt-3 me-3 opacity-25">
                            <span class="material-icons-round" style="font-size: 3rem">account_balance_wallet</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                                <span class="material-icons-round" style="font-size: 2rem">account_balance_wallet</span>
                            </div>
                            <div>
                                <p class="mb-1 opacity-75">Available Balance</p>
                                <h3 class="mb-0 fw-bold">
                                    ${{ number_format($user->cal_deposit ?? $user->deposit, 2) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Updated Cost -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 bg-gradient-info text-white overflow-hidden">
                    <div class="card-body p-4 position-relative">
                        <div class="position-absolute top-0 end-0 mt-3 me-3 opacity-25">
                            <span class="material-icons-round" style="font-size: 3rem">trending_up</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                                <span class="material-icons-round" style="font-size: 2rem">trending_up</span>
                            </div>
                            <div>
                                <p class="mb-1 opacity-75">Cost Per Lead</p>
                                <h3 class="mb-0 fw-bold">
                                    ${{ number_format($user->cost, 2) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Upload Leads -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 bg-gradient-warning text-white overflow-hidden">
                    <div class="card-body p-4 position-relative">
                        <div class="position-absolute top-0 end-0 mt-3 me-3 opacity-25">
                            <span class="material-icons-round" style="font-size: 3rem">group_add</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 rounded-circle p-3 me-3">
                                <span class="material-icons-round" style="font-size: 2rem">group_add</span>
                            </div>
                            <div>
                                <p class="mb-1 opacity-75">Last Upload Leads</p>
                                <h3 class="mb-0 fw-bold">
                                    {{ $user->last_row_count ?? '0' }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Order Form -->
        <form action="{{ route('seller.order.store') }}" method="post" enctype="multipart/form-data" id="orderForm">
            @csrf
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Card Header -->
                <div class="card-header bg-gradient-dark text-white py-4 px-4 px-md-5">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <h2 class="h4 mb-0 d-flex align-items-center">
                                <span class="material-icons-round me-3">add_shopping_cart</span> Create New Order
                            </h2>
                        </div>
                        <div class="col-12 col-md-6 text-md-end">
                            <div class="d-flex align-items-center justify-content-md-end">
                                <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                                    <span class="material-icons-round">person</span>
                                </div>
                                <div>
                                    <p class="mb-0 opacity-75">Seller</p>
                                    <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4">
                        <!-- File No -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <label class="form-label fw-semibold mb-2">
                                <span class="material-icons-round me-2 text-primary">tag</span>File No
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <span class="material-icons-round text-primary">receipt</span>
                                </span>
                                <input type="text" name="order_id" class="form-control form-control-lg"
                                       value="{{ $code }}" readonly>
                            </div>
                        </div>

                        <!-- Seller ID -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <label class="form-label fw-semibold mb-2">
                                <span class="material-icons-round me-2 text-primary">badge</span>Seller ID
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <span class="material-icons-round text-primary">assignment_ind</span>
                                </span>
                                <input type="text" name="seller_id" class="form-control form-control-lg"
                                       value="{{ auth()->user()->code }}" readonly>
                            </div>
                        </div>

                        <!-- Order Date -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <label class="form-label fw-semibold mb-2">
                                <span class="material-icons-round me-2 text-primary">event</span>Order Date
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <span class="material-icons-round text-primary">calendar_today</span>
                                </span>
                                <input type="date" name="date" class="form-control form-control-lg"
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <!-- Social Media Platform -->
                        <div class="col-12 col-md-6 col-lg-4">
                            <label class="form-label fw-semibold mb-2">
                                <span class="material-icons-round me-2 text-primary">public</span>Social Media Platform
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <span class="material-icons-round text-primary">share</span>
                                </span>
                                <select class="form-select form-select-lg" name="platform" required>
                                    <option value="" selected disabled>Select Platform</option>
                                    <option value="Facebook">
                                        <span class="material-icons-round me-2">facebook</span> Facebook
                                    </option>
                                    <option value="TikTok">
                                        <span class="material-icons-round me-2">music_note</span> TikTok
                                    </option>
                                    <option value="Instagram">
                                        <span class="material-icons-round me-2">photo_camera</span> Instagram
                                    </option>
                                    <option value="WhatsApp">
                                        <span class="material-icons-round me-2">chat</span> WhatsApp
                                    </option>
                                    <option value="Other">
                                        <span class="material-icons-round me-2">more_horiz</span> Other
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Upload Excel File -->
                        <div class="col-12 col-md-8">
                            <label class="form-label fw-semibold mb-2">
                                <span class="material-icons-round me-2 text-primary">upload_file</span>Upload Excel File
                            </label>
                            <div class="file-upload-area border-dashed rounded-3 p-4 text-center position-relative"
                                 id="dropZone">
                                <input type="file" name="excel" id="excelFile"
                                       class="form-control form-control-lg d-none"
                                       accept=".xlsx,.xls,.csv" required>

                                <div class="upload-placeholder" id="uploadPlaceholder">
                                    <span class="material-icons-round text-muted mb-3" style="font-size: 3rem">
                                        cloud_upload
                                    </span>
                                    <h5 class="mb-2">Drag & drop your file here</h5>
                                    <p class="text-muted mb-3">or click to browse</p>
                                    <button type="button" class="btn btn-primary px-4" onclick="document.getElementById('excelFile').click()">
                                        <span class="material-icons-round me-2">folder_open</span>Browse Files
                                    </button>
                                    <p class="text-muted small mt-3 mb-0">
                                        Supported formats: .xlsx, .xls, .csv (Max: 10MB)
                                    </p>
                                </div>

                                <div id="filePreview" class="d-none">
                                    <div class="alert alert-success d-flex align-items-center mb-0">
                                        <span class="material-icons-round me-3" style="font-size: 2rem">description</span>
                                        <div class="flex-grow-1">
                                            <strong id="fileName"></strong>
                                            <div id="fileSize" class="small"></div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger ms-3"
                                                onclick="clearFile()">
                                            <span class="material-icons-round">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Excel Format Guide -->
                        <div class="col-12">
                            <div class="alert alert-info border-0 rounded-3 bg-light-info">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="material-icons-round text-info me-3" style="font-size: 2rem">
                                        description
                                    </span>
                                    <h5 class="mb-0 fw-semibold">Excel File Format Requirements</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-white bg-opacity-50 mb-3">
                                            <div class="card-body">
                                                <h6 class="fw-semibold text-primary mb-3">
                                                    <span class="material-icons-round me-2 text-success">check_circle</span>
                                                    Required Columns
                                                </h6>
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-success me-2">1</span>
                                                    <code class="bg-light px-2 py-1 rounded">customer_name</code>
                                                    <span class="ms-2 small text-muted">Customer Name</span>
                                                </div>
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-success me-2">2</span>
                                                    <code class="bg-light px-2 py-1 rounded">customer_phone</code>
                                                    <span class="ms-2 small text-muted">Phone Number</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-success me-2">3</span>
                                                    <code class="bg-light px-2 py-1 rounded">total_amount</code>
                                                    <span class="ms-2 small text-muted">Total Amount</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-0 bg-white bg-opacity-50">
                                            <div class="card-body">
                                                <h6 class="fw-semibold text-primary mb-3">
                                                    <span class="material-icons-round me-2 text-secondary">info</span>
                                                    Optional Columns
                                                </h6>
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-secondary me-2">1</span>
                                                    <code class="bg-light px-2 py-1 rounded">customer_address</code>
                                                    <span class="ms-2 small text-muted">Address</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-secondary me-2">2</span>
                                                    <code class="bg-light px-2 py-1 rounded">district</code>
                                                    <span class="ms-2 small text-muted">District</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ asset('templates/sample-order-template.xlsx') }}"
                                       class="btn btn-info px-4" download>
                                        <span class="material-icons-round me-2">download</span>Download Sample Template
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Cost Estimate (Dynamic) -->
                        <div class="col-12">
                            <div class="card border-primary border-1 bg-light-primary">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="fw-semibold text-primary mb-1">
                                                <span class="material-icons-round me-2">calculate</span>
                                                Cost Estimate
                                            </h6>
                                            <p class="text-muted small mb-0">
                                                Estimated cost will be calculated based on number of leads in your file
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-md-end">
                                            <div class="cost-display">
                                                <span class="text-primary fw-bold">Cost per lead: ${{ number_format($user->cost, 2) }}</span>
                                                <div id="estimatedCost" class="h5 text-primary fw-bold mt-1">$0.00</div>
                                                <small class="text-muted" id="leadCount">0 leads</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-light py-4 px-4 px-md-5">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <span class="material-icons-round text-primary">info</span>
                                </div>
                                <small class="text-muted">
                                    Make sure all required columns are present in your Excel file
                                </small>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 text-md-end">
                            <button type="submit" id="submitBtn"
                                    class="btn btn-lg btn-success px-5 py-3 rounded-pill shadow-sm">
                                <span id="btnText">
                                    <span class="material-icons-round me-2">publish</span>Upload Order
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
        </form>
    </div>
@endsection

@push('script')
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const excelFile = document.getElementById('excelFile');
            const filePreview = document.getElementById('filePreview');
            const uploadPlaceholder = document.getElementById('uploadPlaceholder');
            const dropZone = document.getElementById('dropZone');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            const orderForm = document.getElementById('orderForm');
            const estimatedCost = document.getElementById('estimatedCost');
            const leadCount = document.getElementById('leadCount');
            const costPerLead = {{ $user->cost }};

            // File upload handler
            excelFile.addEventListener('change', handleFileSelect);

            // Drag and drop functionality
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                dropZone.classList.add('highlight');
            }

            function unhighlight() {
                dropZone.classList.remove('highlight');
            }

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                excelFile.files = files;
                handleFileSelect({ target: { files: files } });
            }

            function handleFileSelect(e) {
                const file = e.target.files[0];
                if (!file) return;

                // Validate file type
                const allowedExtensions = /(\.xlsx|\.xls|\.csv)$/i;
                if (!allowedExtensions.exec(file.name)) {
                    showError('Invalid File Type', 'Please upload only .xlsx, .xls, or .csv files.');
                    clearFile();
                    return;
                }

                // Validate file size (10MB)
                const maxSize = 10 * 1024 * 1024;
                if (file.size > maxSize) {
                    showError('File Too Large', 'File size should not exceed 10MB.');
                    clearFile();
                    return;
                }

                // Show file preview
                showFilePreview(file);

                // Estimate cost (this is a mock function - you might want to parse the Excel file)
                estimateCost(file);
            }

            function showFilePreview(file) {
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);

                uploadPlaceholder.classList.add('d-none');
                filePreview.classList.remove('d-none');
                dropZone.classList.remove('border-dashed');
            }

            function clearFile() {
                excelFile.value = '';
                filePreview.classList.add('d-none');
                uploadPlaceholder.classList.remove('d-none');
                dropZone.classList.add('border-dashed');
                estimatedCost.textContent = '$0.00';
                leadCount.textContent = '0 leads';
            }

            function estimateCost(file) {
                // This is a mock estimation
                // In a real scenario, you would parse the Excel file to count rows
                const estimatedLeads = Math.floor(file.size / 100); // Mock calculation
                const totalCost = estimatedLeads * costPerLead;

                estimatedCost.textContent = `$${totalCost.toFixed(2)}`;
                leadCount.textContent = `${estimatedLeads} leads (estimated)`;
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function showError(title, text) {
                Swal.fire({
                    icon: 'error',
                    title: title,
                    text: text,
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            }

            // Form submission handler
            orderForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (!excelFile.files.length) {
                    showError('No File Selected', 'Please select an Excel file to upload.');
                    return;
                }

                // Validate required fields
                const platform = document.querySelector('select[name="platform"]');
                if (!platform.value) {
                    showError('Platform Required', 'Please select a social media platform.');
                    platform.focus();
                    return;
                }

                // Show confirmation dialog
                const result = await Swal.fire({
                    title: 'Confirm Order Upload',
                    html: `
                        <div class="text-start">
                            <p>Are you sure you want to upload this order?</p>
                            <div class="alert alert-light mt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>File:</span>
                                    <strong>${excelFile.files[0].name}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Platform:</span>
                                    <strong>${platform.options[platform.selectedIndex].text}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Date:</span>
                                    <strong>${document.querySelector('input[name="date"]').value}</strong>
                                </div>
                            </div>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, Upload Now',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-primary px-4',
                        cancelButton: 'btn btn-outline-secondary px-4'
                    },
                    reverseButtons: true
                });

                if (result.isConfirmed) {
                    // Show loading state
                    btnText.classList.add('d-none');
                    btnLoader.classList.remove('d-none');
                    submitBtn.disabled = true;

                    // Submit the form
                    this.submit();
                }
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Platform select validation styling
            const platformSelect = document.querySelector('select[name="platform"]');
            platformSelect.addEventListener('change', function() {
                if (this.value) {
                    this.classList.add('is-valid');
                    this.classList.remove('is-invalid');
                } else {
                    this.classList.remove('is-valid');
                }
            });

            // Date validation - cannot select future dates
            const dateInput = document.querySelector('input[name="date"]');
            dateInput.max = new Date().toISOString().split('T')[0];
        });
    </script>

    <style>
        /* Custom Styles */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        }

        .bg-gradient-primary {
            background: var(--primary-gradient) !important;
        }

        .bg-gradient-info {
            background: var(--info-gradient) !important;
        }

        .bg-gradient-warning {
            background: var(--warning-gradient) !important;
        }

        .bg-gradient-dark {
            background: linear-gradient(135deg, #2c3e50 0%, #4a6491 100%) !important;
        }

        .bg-light-primary {
            background-color: rgba(102, 126, 234, 0.1) !important;
        }

        .bg-light-info {
            background-color: rgba(79, 172, 254, 0.1) !important;
        }

        .border-dashed {
            border: 2px dashed #dee2e6 !important;
            border-radius: 12px;
        }

        .border-dashed.highlight {
            border-color: #667eea !important;
            background-color: rgba(102, 126, 234, 0.05);
            animation: pulse 2s infinite;
        }

        .form-control-lg, .form-select-lg {
            border-radius: 12px;
            border: 1.5px solid #e9ecef;
            transition: all 0.3s ease;
            padding: 0.75rem 1rem;
        }

        .form-control-lg:focus, .form-select-lg:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            border: 1.5px solid #e9ecef;
            border-right: none;
            background-color: #f8f9fa;
        }

        .rounded-pill {
            min-width: 200px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .upload-placeholder {
            cursor: pointer;
        }

        .upload-placeholder:hover {
            opacity: 0.8;
        }

        .material-icons-round {
            vertical-align: middle;
        }

        /* Animation for file upload zone */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
            100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Badge styling */
        .badge {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-header .row {
                flex-direction: column;
                text-align: center;
            }

            .card-footer .row {
                flex-direction: column;
                text-align: center;
            }

            .btn-lg {
                width: 100%;
                max-width: 300px;
            }

            .input-group {
                flex-direction: column;
            }

            .input-group-text {
                border-radius: 12px 12px 0 0;
                border-bottom: none;
                width: 100%;
                justify-content: center;
            }

            .form-control-lg, .form-select-lg {
                border-radius: 0 0 12px 12px;
                width: 100%;
            }

            .upload-placeholder h5 {
                font-size: 1.1rem;
            }

            .upload-placeholder p {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            .card-body {
                padding: 1.5rem !important;
            }

            .btn-lg {
                padding: 0.75rem 1.5rem !important;
                font-size: 1rem;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #667eea, #764ba2);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #5a6fd8, #69438f);
        }
    </style>
@endpush
