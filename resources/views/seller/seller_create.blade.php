@extends('admin.app')
@push('title')
    Seller Create
@endpush
@push('css')
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #eef2ff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        .modern-card {
            background: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }

        .modern-card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .card-header-modern {
            background: linear-gradient(135deg, var(--primary-color), #3a56d4);
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            padding: 1rem 1.5rem;
            border: none;
        }

        .card-header-modern h5 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .card-header-modern svg {
            margin-right: 10px;
        }

        .form-section {
            padding: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .required-field::after {
            content: "*";
            color: var(--danger-color);
            margin-left: 4px;
        }

        .image-upload-modern {
            border: 2px dashed #cbd5e0;
            border-radius: var(--border-radius);
            padding: 2rem;
            text-align: center;
            transition: var(--transition);
            background: var(--light-color);
            cursor: pointer;
        }

        .image-upload-modern:hover {
            border-color: var(--primary-color);
            background: var(--primary-light);
        }

        .image-upload-modern svg {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .phone-img {
            position: relative;
            display: inline-block;
        }

        .phone-img img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .phone-img a {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .btn-modern {
            border-radius: var(--border-radius);
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary-modern {
            background: var(--primary-color);
            border: none;
        }

        .btn-primary-modern:hover {
            background: #3a56d4;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
        }

        .btn-secondary-modern {
            background: var(--secondary-color);
            border: none;
        }

        .btn-secondary-modern:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        .input-group-modern {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
        }

        .input-with-icon {
            padding-left: 2.5rem;
        }

        .progress-bar {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--primary-color);
            width: 0%;
            transition: width 0.3s ease;
        }
    </style>
@endpush
@section('content')
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Create Seller</h4>
                <p class="text-muted mb-0">Add a new seller to the system</p>
            </div>
            <div>
                <a href="{{route('seller.list')}}" class="btn btn-secondary-modern btn-modern">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list me-2">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                    View Seller List
                </a>
            </div>
        </div>
    </div>

    <form action="{{route('admin.seller.store')}}" class="add-product-form" method="post" enctype="multipart/form-data">
        @csrf
        <div class="add-product">
            <div class="modern-card">
                <div class="card-header-modern">
                    <h5>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Seller Information
                    </h5>
                </div>
                <div class="form-section">
                    <div class="grid-2">
                        <div class="mb-3">
                            <label class="form-label required-field">Seller Code</label>
                            <div class="input-group-modern">
                                <input type="text" name="code" class="form-control" required disabled value="{{$code}}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-field">Full Name</label>
                            <div class="input-group-modern">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </span>
                                <input type="text" name="name" class="form-control input-with-icon" placeholder="Full Name" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-field">NIC No</label>
                            <div class="input-group-modern">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                        <line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                </span>
                                <input type="text" name="nic_no" class="form-control input-with-icon" placeholder="NIC NO" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-field">Mobile No</label>
                            <div class="input-group-modern">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </span>
                                <input type="text" name="phone" class="form-control input-with-icon" placeholder="Mobile No 01" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-field">Whatsapp</label>
                            <div class="input-group-modern">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle">
                                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                    </svg>
                                </span>
                                <input type="text" name="whatsapp" class="form-control input-with-icon" placeholder="Whatsapp No" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-field">Email</label>
                            <div class="input-group-modern">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </span>
                                <input type="email" name="email" class="form-control input-with-icon" placeholder="Email" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-field">Address</label>
                            <div class="input-group-modern">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </span>
                                <input type="text" name="address" class="form-control input-with-icon" placeholder="Address" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-field">City</label>
                            <div class="input-group-modern">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                    </svg>
                                </span>
                                <input type="text" name="city" class="form-control input-with-icon" placeholder="City" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-field">Postal Code</label>
                            <div class="input-group-modern">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-hash">
                                        <line x1="4" y1="9" x2="20" y2="9"></line>
                                        <line x1="4" y1="15" x2="20" y2="15"></line>
                                        <line x1="10" y1="3" x2="8" y2="21"></line>
                                        <line x1="16" y1="3" x2="14" y2="21"></line>
                                    </svg>
                                </span>
                                <input type="text" name="postal_code" class="form-control input-with-icon" placeholder="Postal Code" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-field">Username</label>
                            <div class="input-group-modern">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </span>
                                <input type="text" name="username" class="form-control input-with-icon" placeholder="Username" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-field">Password</label>
                            <div class="input-group-modern">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </span>
                                <input type="password" name="password" class="form-control input-with-icon" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-field">Deposit Amount</label>
                            <div class="input-group-modern">
                                <span class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                </span>
                                <input type="number" name="deposit" class="form-control input-with-icon" placeholder="Deposit Amount" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-field">Seller Type</label>
                            <select class="form-control" name="type" required>
                                <option value="self_seller">Self Seller</option>
                                <option value="system_seller">System Seller</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-field">Referral Code</label>
                            <input type="text" name="ref_code" class="form-control" placeholder="Referral Code" value="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">District</label>
                            <select class="form-control" name="district" required>
                                <option value="">Select</option>
                                <option value="Ampara">Ampara</option>
                                <option value="Anuradhapura">Anuradhapura</option>
                                <option value="Badulla">Badulla</option>
                                <option value="Batticaloa">Batticaloa</option>
                                <option value="Colombo">Colombo</option>
                                <option value="Galle">Galle</option>
                                <option value="Gampaha">Gampaha</option>
                                <option value="Hambantota">Hambantota</option>
                                <option value="Jaffna">Jaffna</option>
                                <option value="Kalutara">Kalutara</option>
                                <option value="Kandy">Kandy</option>
                                <option value="Kegalle">Kegalle</option>
                                <option value="Kilinochchi">Kilinochchi</option>
                                <option value="Kurunegala">Kurunegala</option>
                                <option value="Mannar">Mannar</option>
                                <option value="Matale">Matale</option>
                                <option value="Matara">Matara</option>
                                <option value="Monaragala">Monaragala</option>
                                <option value="Mullaitivu">Mullaitivu</option>
                                <option value="Nuwara Eliya">Nuwara Eliya</option>
                                <option value="Polonnaruwa">Polonnaruwa</option>
                                <option value="Puttalam">Puttalam</option>
                                <option value="Ratnapura">Ratnapura</option>
                                <option value="Trincomalee">Trincomalee</option>
                                <option value="Vavuniya">Vavuniya</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modern-card">
                <div class="card-header-modern">
                    <h5>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        NIC Images
                    </h5>
                </div>
                <div class="form-section">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6 class="section-title">NIC Front</h6>
                            <div class="image-upload-modern" onclick="document.getElementById('nicFront').click()">
                                <input type="file" id="nicFront" name="nic_front" multiple required style="display: none;">
                                <div class="image-uploads text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>
                                    <h5 class="mt-2">Upload NIC Front</h5>
                                    <p class="text-muted mb-0">Click or drag files here</p>
                                </div>
                            </div>
                            <div id="nicFrontPreview" class="preview-container mt-3">
                                <!-- Dynamically generated previews -->
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <h6 class="section-title">NIC Back</h6>
                            <div class="image-upload-modern" onclick="document.getElementById('nicBack').click()">
                                <input type="file" id="nicBack" name="nic_back" multiple required style="display: none;">
                                <div class="image-uploads text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="16"></line>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>
                                    <h5 class="mt-2">Upload NIC Back</h5>
                                    <p class="text-muted mb-0">Click or drag files here</p>
                                </div>
                            </div>
                            <div id="nicBackPreview" class="preview-container mt-3">
                                <!-- Dynamically generated previews -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-end mt-4 mb-5">
            <button type="button" class="btn btn-secondary-modern btn-modern me-3">Cancel</button>
            <button type="submit" class="btn btn-primary-modern btn-modern">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save me-2">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                    <polyline points="17 21 17 13 7 13 7 21"></polyline>
                    <polyline points="7 3 7 8 15 8"></polyline>
                </svg>
                Save Seller
            </button>
        </div>
    </form>
@endsection
@push('script')
    <script>
        // Enhanced image preview functionality with drag and drop
        function setupImageUpload(inputId, previewId) {
            const input = document.getElementById(inputId);
            const previewContainer = document.getElementById(previewId);
            const dropZone = input.parentElement;

            // Click to upload
            input.addEventListener('change', handleFileSelect);

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
                dropZone.style.borderColor = '#4361ee';
                dropZone.style.backgroundColor = '#eef2ff';
            }

            function unhighlight() {
                dropZone.style.borderColor = '#cbd5e0';
                dropZone.style.backgroundColor = '#f8f9fa';
            }

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                input.files = files;
                handleFileSelect({ target: input });
            }

            function handleFileSelect(event) {
                const files = event.target.files;

                // Clear previous previews
                previewContainer.innerHTML = '';

                if (files.length === 0) return;

                // Show file count
                const fileCount = document.createElement('div');
                fileCount.className = 'text-center mb-2';
                fileCount.innerHTML = `<small class="text-muted">${files.length} file(s) selected</small>`;
                previewContainer.appendChild(fileCount);

                // Process each file
                for (const file of files) {
                    if (!file.type.startsWith('image/')) continue;

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const imgDiv = document.createElement('div');
                        imgDiv.className = 'phone-img';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Uploaded image';

                        const removeBtn = document.createElement('a');
                        removeBtn.href = 'javascript:void(0);';
                        removeBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        `;

                        removeBtn.onclick = () => {
                            imgDiv.remove();
                            // Update file input
                            const dt = new DataTransfer();
                            const currentFiles = input.files;

                            for (let i = 0; i < currentFiles.length; i++) {
                                if (currentFiles[i] !== file) {
                                    dt.items.add(currentFiles[i]);
                                }
                            }

                            input.files = dt.files;

                            // Update file count
                            const remainingFiles = input.files.length;
                            if (remainingFiles === 0) {
                                fileCount.remove();
                            } else {
                                fileCount.innerHTML = `<small class="text-muted">${remainingFiles} file(s) selected</small>`;
                            }
                        };

                        imgDiv.appendChild(img);
                        imgDiv.appendChild(removeBtn);
                        previewContainer.appendChild(imgDiv);
                    };
                    reader.readAsDataURL(file);
                }
            }
        }

        // Initialize both image upload sections
        document.addEventListener('DOMContentLoaded', function() {
            setupImageUpload('nicFront', 'nicFrontPreview');
            setupImageUpload('nicBack', 'nicBackPreview');
        });
    </script>
@endpush
