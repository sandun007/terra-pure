{{-- resources/views/wholesaler/calls/csv_upload.blade.php --}}
@extends('wholesaler.layouts.app')

@push('title')
    CSV Upload - Wholesaler Calls
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 25px 50px rgba(79,70,229,0.3);
        }
        .upload-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .upload-area {
            border: 3px dashed #e2e8f0;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            background: #f8fafc;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .upload-area:hover {
            border-color: #4f46e5;
            background: #eef2ff;
        }
        .upload-area i {
            font-size: 48px;
            color: #4f46e5;
            margin-bottom: 16px;
        }
        .preview-table {
            margin-top: 30px;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .btn-upload {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: white;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79,70,229,0.4);
            color: white;
        }
        .btn-upload:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .sample-csv {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 15px;
            font-family: monospace;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3 px-md-4 px-lg-5 py-4">
        <div class="page-header">
            <h4 class="mb-2"><i class="fas fa-upload me-3"></i>CSV Upload - Wholesaler Calls</h4>
            <p class="mb-0 opacity-75">Upload CSV file to bulk create calls for your products</p>
        </div>

        <div class="upload-card">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="fw-bold mb-3">Upload Instructions</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> CSV file must have headers</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Required columns: customer_name, customer_phone</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Optional: customer_address, city, platform, ad_name, campaign_name, call_status</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Max file size: 5MB</li>
                        </ul>

                        <div class="sample-csv">
                            <strong>Sample CSV format:</strong><br>
                            customer_name,customer_phone,customer_address,city,platform,ad_name<br>
                            John Doe,0712345678,Colombo 05,Colombo,fb,Summer Sale Ad
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Select Product</label>
                            <select id="productSelect" class="form-select" required>
                                <option value="">Choose a product...</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Default Call Status (if not specified in CSV)</label>
                            <select id="defaultStatus" class="form-select">
                                <option value="Pending">Pending</option>
                                <option value="Conform">Conform</option>
                                <option value="Reject">Reject</option>
                                <option value="Not Answer">Not Answer</option>
                                <option value="Other">Other</option>
                                <option value="WhatsApp">WhatsApp</option>
                            </select>
                        </div>

                        <div class="upload-area" id="uploadArea">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <h5>Drag & Drop CSV File Here</h5>
                            <p class="text-muted">or click to browse</p>
                            <input type="file" id="csvFile" accept=".csv" style="display: none;">
                            <button class="btn btn-outline-primary" id="browseBtn">Browse Files</button>
                        </div>

                        <div id="fileInfo" class="mt-3" style="display: none;">
                            <div class="alert alert-info">
                                <i class="fas fa-file-csv me-2"></i>
                                <span id="fileName"></span>
                                <button class="btn btn-sm btn-link float-end" id="removeFile">Remove</button>
                            </div>
                        </div>

                        <div id="previewSection" style="display: none;">
                            <h5 class="fw-bold mt-4 mb-3">Preview Data (First 5 rows)</h5>
                            <div class="table-responsive preview-table">
                                <table class="table table-sm mb-0" id="previewTable">
                                    <thead class="table-light">
                                    <tr id="previewHeader"></tr>
                                    </thead>
                                    <tbody id="previewBody"></tbody>
                                </table>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button class="btn-upload" id="uploadBtn" disabled>
                                <i class="fas fa-upload me-2"></i>Upload & Process
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <script>
        $(document).ready(function() {
            let parsedData = null;
            let fileName = '';

            // Load products
            $.ajax({
                url: '{{ route("wholesaler.call.wholesaler_products") }}',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        let options = '<option value="">Choose a product...</option>';
                        response.products.forEach(product => {
                            options += `<option value="${product.code}" data-id="${product.id}" data-price="${product.display_price}" data-name="${product.name}">${product.name} (${product.code}) - Rs. ${product.display_price} - Stock: ${product.qty}</option>`;
                        });
                        $('#productSelect').html(options);
                    }
                }
            });

            // Upload area click
            $('#uploadArea').on('click', function(e) {
                if (e.target.tagName !== 'BUTTON' && e.target.tagName !== 'I') {
                    $('#csvFile').click();
                }
            });

            $('#browseBtn').on('click', function() {
                $('#csvFile').click();
            });

            // File selection
            $('#csvFile').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    processFile(file);
                }
            });

            // Drag & drop
            $('#uploadArea').on('dragover', function(e) {
                e.preventDefault();
                $(this).css('border-color', '#4f46e5').css('background', '#eef2ff');
            });

            $('#uploadArea').on('dragleave', function(e) {
                e.preventDefault();
                $(this).css('border-color', '#e2e8f0').css('background', '#f8fafc');
            });

            $('#uploadArea').on('drop', function(e) {
                e.preventDefault();
                $(this).css('border-color', '#e2e8f0').css('background', '#f8fafc');
                const file = e.originalEvent.dataTransfer.files[0];
                if (file) {
                    processFile(file);
                }
            });

            function processFile(file) {
                if (file.type !== 'text/csv' && !file.name.endsWith('.csv')) {
                    Swal.fire('Error', 'Please upload a valid CSV file', 'error');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire('Error', 'File size must be less than 5MB', 'error');
                    return;
                }

                fileName = file.name;
                $('#fileName').text(file.name);
                $('#fileInfo').show();
                $('#uploadArea').hide();

                Papa.parse(file, {
                    header: true,
                    skipEmptyLines: true,
                    preview: 5,
                    complete: function(results) {
                        parsedData = results.data;
                        displayPreview(results);
                        checkUploadReady();
                    }
                });
            }

            $('#removeFile').on('click', function() {
                $('#csvFile').val('');
                $('#fileInfo').hide();
                $('#uploadArea').show();
                $('#previewSection').hide();
                parsedData = null;
                checkUploadReady();
            });

            function displayPreview(results) {
                const headers = results.meta.fields || [];
                const data = results.data;

                let headerHtml = '';
                headers.forEach(header => {
                    headerHtml += `<th>${header}</th>`;
                });
                $('#previewHeader').html(headerHtml);

                let bodyHtml = '';
                data.forEach(row => {
                    bodyHtml += '<tr>';
                    headers.forEach(header => {
                        bodyHtml += `<td>${row[header] || '-'}</td>`;
                    });
                    bodyHtml += '</tr>';
                });
                $('#previewBody').html(bodyHtml);
                $('#previewSection').show();
            }

            function checkUploadReady() {
                const productSelected = $('#productSelect').val();
                const hasData = parsedData && parsedData.length > 0;

                if (productSelected && hasData) {
                    $('#uploadBtn').prop('disabled', false);
                } else {
                    $('#uploadBtn').prop('disabled', true);
                }
            }

            $('#productSelect').on('change', checkUploadReady);

            $('#uploadBtn').on('click', function() {
                const productCode = $('#productSelect').val();
                const productName = $('#productSelect option:selected').data('name');
                const defaultStatus = $('#defaultStatus').val();

                if (!productCode) {
                    Swal.fire('Error', 'Please select a product', 'error');
                    return;
                }

                if (!parsedData) {
                    Swal.fire('Error', 'Please upload a CSV file', 'error');
                    return;
                }

                // Process full file for upload
                Papa.parse($('#csvFile')[0].files[0], {
                    header: true,
                    skipEmptyLines: true,
                    complete: function(results) {
                        const leads = results.data.filter(row => row.customer_name && row.customer_phone);

                        if (leads.length === 0) {
                            Swal.fire('Error', 'No valid leads found in CSV', 'error');
                            return;
                        }

                        Swal.fire({
                            title: 'Processing...',
                            text: `Uploading ${leads.length} leads`,
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: '{{ route("wholesaler.call.csv_insert") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                leads: leads,
                                product_code: productCode,
                                call_status: defaultStatus
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Store success message in sessionStorage
                                    sessionStorage.setItem('csv_upload_success', JSON.stringify({
                                        title: 'CSV Upload Successful!',
                                        sub: `${response.callCount} calls created successfully.`
                                    }));

                                    // Redirect to pending calls
                                    window.location.href = '{{ route("wholesaler.call.index") }}';
                                } else {
                                    Swal.fire('Error', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                let errorMsg = 'Error uploading CSV';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = xhr.responseJSON.message;
                                }
                                Swal.fire('Error', errorMsg, 'error');
                            }
                        });
                    }
                });
            });

            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
            @endif

            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: falsea
            });
            @endif
        });
    </script>
@endpush
