@extends('employee.layout.app')

@section('title', 'Update Product Price')
@section('subtitle', 'Update product selling price')

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .page-header-enhanced {
            background: var(--primary-gradient);
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            color: white;
            box-shadow: var(--card-shadow);
        }

        .page-header-enhanced h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .card-enhanced {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card-enhanced:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .product-img-enhanced {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 12px;
            border: 3px solid #e2e8f0;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .product-img-enhanced:hover {
            transform: scale(1.05);
            border-color: #667eea;
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
        }

        .form-control-enhanced {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control-enhanced:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .btn-enhanced-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-enhanced-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-enhanced-success {
            background: var(--success-gradient);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-enhanced-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            color: white;
        }

        .btn-enhanced-secondary {
            border: 2px solid #e2e8f0;
            background: white;
            color: #475569;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-enhanced-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .alert-enhanced {
            border: none;
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            box-shadow: var(--card-shadow);
            border-left: 4px solid;
        }

        .alert-info-enhanced {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-left-color: #3b82f6;
            color: #1e40af;
        }

        .alert-warning-enhanced {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left-color: #f59e0b;
            color: #92400e;
        }

        .price-display {
            font-size: 2rem;
            font-weight: 700;
            color: #f59e0b;
            text-align: center;
            margin: 1rem 0;
        }

        .badge-enhanced {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-category-enhanced {
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #4338ca;
            border: 1px solid #a5b4fc;
        }

        .status-badge-enhanced {
            padding: 6px 16px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .status-active-enhanced {
            background: var(--success-gradient);
            color: white;
        }

        .status-inactive-enhanced {
            background: var(--danger-gradient);
            color: white;
        }

        .price-change-preview {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 1rem;
            border-radius: 10px;
            margin-top: 1rem;
            border: 2px solid #bae6fd;
            display: none;
        }

        .price-change-preview.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .timeline-item {
            border-left: 3px solid #667eea;
            padding-left: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            left: -8px;
            top: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #667eea;
            border: 3px solid white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .timeline-date {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
        }

        .timeline-employee {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .price-change-details {
            background: #f8fafc;
            padding: 0.75rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .price-change-arrow {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .old-price {
            color: #94a3b8;
            text-decoration: line-through;
        }

        .new-price {
            color: #10b981;
            font-weight: 600;
        }

        .price-difference {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 12px;
        }

        .price-increase {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .price-decrease {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        {{-- CSRF Token for AJAX --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Enhanced Page Header --}}
        <div class="page-header-enhanced">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="d-flex align-items-center mb-2">
                        <i class="fas fa-edit me-3"></i>
                        Update Product Price
                    </h1>
                    <p class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Update selling price for product
                    </p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('employee.wholesaler.products') }}" class="btn btn-enhanced-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Products
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                @if($product)
                    <div class="card-enhanced mb-4">
                        <div class="card-body p-4">
                            @if(session('success'))
                                <div class="alert-enhanced alert-info-enhanced mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                                        <div>
                                            <h5 class="fw-bold mb-1">Price Updated Successfully!</h5>
                                            <p class="mb-0">{{ session('success') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert-enhanced alert-warning-enhanced mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle fa-2x me-3 text-danger"></i>
                                        <div>
                                            <h5 class="fw-bold mb-1">Update Failed!</h5>
                                            <p class="mb-0">{{ session('error') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-4">
                                <div class="col-md-4 text-center mb-3 mb-md-0">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}"
                                             class="product-img-enhanced mb-3">
                                    @else
                                        <div class="product-img-enhanced d-flex align-items-center justify-content-center bg-light mx-auto mb-3">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <h4 class="fw-bold mb-2">{{ $product->name }}</h4>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Code:</strong></p>
                                            <span class="badge-enhanced" style="background: #f1f5f9; color: #475569;">{{ $product->code }}</span>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Status:</strong></p>
                                            <span class="status-badge-enhanced {{ $product->status == 'active' ? 'status-active-enhanced' : 'status-inactive-enhanced' }}">
                                                <i class="fas {{ $product->status == 'active' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                                {{ ucfirst($product->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Wholesaler:</strong></p>
                                            <p class="fw-semibold text-dark">{{ $product->wholesaler->business_name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Category:</strong></p>
                                            <span class="badge-enhanced badge-category-enhanced">
                                                <i class="fas fa-tag me-1"></i>
                                                {{ $product->category->name ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="price-display" id="currentPriceDisplay">
                                        Current Price: Rs. {{ number_format($product->selling_price, 2) }}
                                    </div>
                                    <div class="mt-2 text-center">
                                        <small class="text-muted">
                                            Final Price after {{ $product->discount }}% discount:
                                            <strong>Rs. {{ number_format($product->final_price, 2) }}</strong>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <form id="priceUpdateForm" method="POST" action="{{ route('employee.wholesaler.price-update') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-dollar-sign me-1 text-warning"></i>
                                        New Selling Price <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light">Rs.</span>
                                        <input type="number" step="0.01" min="0.01" class="form-control form-control-enhanced"
                                               name="selling_price" id="sellingPriceInput" placeholder="0.00" required
                                               value="{{ old('selling_price', $product->selling_price) }}">
                                    </div>
                                    <small class="text-muted mt-1 d-block">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Enter the new selling price for this product
                                    </small>
                                    <div class="invalid-feedback" id="priceError"></div>
                                </div>

                                {{-- Price Change Preview --}}
                                <div class="price-change-preview" id="priceChangePreview">
                                    <h6 class="fw-bold mb-2">
                                        <i class="fas fa-chart-line me-1 text-info"></i>
                                        Price Change Preview
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <span class="text-muted">Old Price:</span>
                                                <span class="fw-bold text-danger" id="oldPricePreview">
                                                    Rs. {{ number_format($product->selling_price, 2) }}
                                                </span>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-muted">New Price:</span>
                                                <span class="fw-bold text-success" id="newPricePreview">
                                                    Rs. {{ number_format($product->selling_price, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <span class="text-muted">Change Amount:</span>
                                                <span class="fw-bold" id="changeAmountPreview">Rs. 0.00</span>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-muted">Percentage Change:</span>
                                                <span class="fw-bold" id="percentageChangePreview">0.00%</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <span class="text-muted">Final Price (after {{ $product->discount }}% discount):</span>
                                        <span class="fw-bold text-primary" id="finalPricePreview">
                                            Rs. {{ number_format($product->final_price, 2) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-comment-dots me-1 text-info"></i>
                                        Update Reason (Optional)
                                    </label>
                                    <textarea class="form-control form-control-enhanced" name="notes" id="notesInput" rows="3"
                                              placeholder="Reason for price update (e.g., Market price change, Promotion, Cost adjustment...)">{{ old('notes') }}</textarea>
                                    <small class="text-muted mt-1 d-block">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Provide a reason for the price update for record keeping
                                    </small>
                                </div>

                                <div class="alert-enhanced alert-warning-enhanced mb-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                        <div>
                                            <strong>Important Notice</strong>
                                            <p class="mb-0">Price changes will be recorded in the system audit log. This action cannot be undone.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('employee.wholesaler.products') }}" class="btn btn-enhanced-secondary">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-enhanced-success" id="saveChangesBtn">
                                        <i class="fas fa-save me-1"></i>Update Price
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Price History Section --}}
                    @if($priceHistory && $priceHistory->count() > 0)
                        <div class="card-enhanced">
                            <div class="card-header bg-light border-bottom p-3">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-history me-2 text-primary"></i>Recent Price History
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="timeline">
                                    @foreach($priceHistory as $history)
                                        <div class="timeline-item">
                                            <div class="timeline-date">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $history->created_at->format('M d, Y H:i') }}
                                                ({{ $history->created_at->diffForHumans() }})
                                            </div>
                                            <div class="timeline-employee">
                                                <i class="fas fa-user-circle me-1"></i>
                                                {{ $history->employee->name ?? 'Unknown Employee' }}
                                            </div>
                                            <div class="price-change-details">
                                                <div class="price-change-arrow">
                                                    <span class="old-price">
                                                        Rs. {{ number_format($history->old_selling_price, 2) }}
                                                    </span>
                                                    <i class="fas fa-arrow-right text-muted mx-2"></i>
                                                    <span class="new-price">
                                                        Rs. {{ number_format($history->new_selling_price, 2) }}
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="price-difference {{ $history->price_difference >= 0 ? 'price-increase' : 'price-decrease' }}">
                                                        <i class="fas {{ $history->price_difference >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} me-1"></i>
                                                        {{ $history->price_difference >= 0 ? '+' : '' }}Rs. {{ number_format(abs($history->price_difference), 2) }}
                                                        ({{ $history->percentage_change >= 0 ? '+' : '' }}{{ number_format($history->percentage_change, 2) }}%)
                                                    </span>
                                                    <small class="text-muted">
                                                        Final Price: Rs. {{ number_format($history->new_final_price, 2) }}
                                                    </small>
                                                </div>
                                            </div>
                                            @if($history->notes)
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-sticky-note me-1"></i>
                                                        {{ $history->notes }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                {{-- FIXED: Use count() instead of total() for Collections --}}
                                @if($priceHistory->count() >= 10)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('employee.wholesaler.product-view', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                            View Full History
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @else
                    <div class="card-enhanced">
                        <div class="card-body text-center p-5">
                            <i class="fas fa-exclamation-triangle fa-4x text-warning mb-4"></i>
                            <h4 class="mb-3">Product Not Found</h4>
                            <p class="text-muted mb-4">The product you're trying to update could not be found.</p>
                            <a href="{{ route('employee.wholesaler.products') }}" class="btn btn-enhanced-primary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Products
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            console.log('Price update page loaded');

            // Get product data
            const product = {
                id: {{ $product->id ?? 0 }},
                currentPrice: {{ $product->selling_price ?? 0 }},
                currentFinalPrice: {{ $product->final_price ?? 0 }},
                discount: {{ $product->discount ?? 0 }}
            };

            console.log('Product data:', product);

            // Set CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Calculate final price with discount
            function calculateFinalPrice(sellingPrice, discount) {
                if (discount > 0) {
                    return sellingPrice - (sellingPrice * (discount / 100));
                }
                return sellingPrice;
            }

            // Format currency
            function formatCurrency(amount) {
                return 'Rs. ' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
            }

            // Update price preview
            function updatePricePreview() {
                const newPrice = parseFloat($('#sellingPriceInput').val()) || product.currentPrice;
                const oldPrice = product.currentPrice;

                if (newPrice <= 0) {
                    $('#priceChangePreview').removeClass('show');
                    return;
                }

                const newFinalPrice = calculateFinalPrice(newPrice, product.discount);
                const oldFinalPrice = product.currentFinalPrice;
                const changeAmount = newFinalPrice - oldFinalPrice;
                const percentageChange = oldFinalPrice > 0 ? (changeAmount / oldFinalPrice) * 100 : 0;

                console.log('Preview calculations:', {
                    newPrice, oldPrice, newFinalPrice, oldFinalPrice,
                    changeAmount, percentageChange, discount: product.discount
                });

                // Update preview elements
                $('#oldPricePreview').text(formatCurrency(oldPrice));
                $('#newPricePreview').text(formatCurrency(newPrice));
                $('#changeAmountPreview').text((changeAmount >= 0 ? '+' : '') + formatCurrency(changeAmount));
                $('#percentageChangePreview').text((percentageChange >= 0 ? '+' : '') + percentageChange.toFixed(2) + '%');
                $('#finalPricePreview').text(formatCurrency(newFinalPrice));

                // Style based on change
                if (changeAmount > 0) {
                    $('#changeAmountPreview').removeClass('text-danger').addClass('text-success');
                    $('#percentageChangePreview').removeClass('text-danger').addClass('text-success');
                } else if (changeAmount < 0) {
                    $('#changeAmountPreview').removeClass('text-success').addClass('text-danger');
                    $('#percentageChangePreview').removeClass('text-success').addClass('text-danger');
                } else {
                    $('#changeAmountPreview').removeClass('text-success text-danger').addClass('text-muted');
                    $('#percentageChangePreview').removeClass('text-success text-danger').addClass('text-muted');
                }

                $('#priceChangePreview').addClass('show');
            }

            // Real-time price preview
            $('#sellingPriceInput').on('input', function() {
                updatePricePreview();
            });

            // Price update form submit
            $('#priceUpdateForm').on('submit', function (e) {
                e.preventDefault();
                e.stopPropagation();

                console.log('Price update form submitted');

                // Get form values
                const formData = $(this).serialize();
                console.log('Form data:', formData);

                // Validate required fields
                const sellingPrice = parseFloat($('#sellingPriceInput').val());
                if (!sellingPrice || sellingPrice <= 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Please enter a valid selling price (greater than 0)',
                        confirmButtonColor: '#ef4444'
                    });
                    return false;
                }

                // Disable save button and show loading
                const saveBtn = $('#saveChangesBtn');
                const originalText = saveBtn.html();
                saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Updating...');

                // Show loading overlay
                Swal.fire({
                    title: 'Updating Price...',
                    html: 'Please wait while we update the product price',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Send AJAX request
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function (response) {
                        console.log('Success response:', response);

                        if (response.success) {
                            // Close loading
                            Swal.close();

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                html: response.message + '<br><br>' +
                                    '<strong>New Price:</strong> ' + response.product.formatted_selling_price + '<br>' +
                                    '<strong>Final Price:</strong> ' + response.product.formatted_final_price,
                                showConfirmButton: true,
                                confirmButtonText: 'Continue',
                                confirmButtonColor: '#10b981'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Update current price display
                                    $('#currentPriceDisplay').html(
                                        'Current Price: ' + response.product.formatted_selling_price
                                    );

                                    // Update product data for preview
                                    product.currentPrice = parseFloat(response.product.selling_price);
                                    product.currentFinalPrice = parseFloat(response.product.final_price);

                                    // Update the input field with new price
                                    $('#sellingPriceInput').val(response.product.selling_price);

                                    // Update preview
                                    updatePricePreview();

                                    // Show success alert
                                    const successAlert = `
                                        <div class="alert-enhanced alert-info-enhanced mb-4">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                                                <div>
                                                    <h5 class="fw-bold mb-1">Price Updated Successfully!</h5>
                                                    <p class="mb-0">The price has been updated to ${response.product.formatted_selling_price}</p>
                                                </div>
                                            </div>
                                        </div>
                                    `;

                                    // Remove any existing alerts
                                    $('.alert-enhanced').remove();

                                    // Insert new alert at the top of card body
                                    $('.card-body').prepend(successAlert);

                                    // Scroll to top
                                    $('html, body').animate({
                                        scrollTop: 0
                                    }, 500);

                                    // Re-enable save button
                                    saveBtn.prop('disabled', false).html(originalText);
                                }
                            });
                        } else {
                            // Re-enable save button
                            saveBtn.prop('disabled', false).html(originalText);

                            // Close loading
                            Swal.close();

                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'Failed to update price',
                                confirmButtonColor: '#ef4444'
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });

                        // Re-enable save button
                        saveBtn.prop('disabled', false).html(originalText);

                        // Close loading
                        Swal.close();

                        let errorMessage = 'Failed to update price. Please try again.';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        } else if (xhr.status === 500) {
                            errorMessage = 'Server error. Please try again later.';
                        } else if (xhr.status === 422) {
                            errorMessage = 'Validation error. Please check your inputs.';
                        } else if (xhr.status === 419) {
                            errorMessage = 'Session expired. Please refresh the page.';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessage,
                            confirmButtonColor: '#ef4444'
                        });
                    }
                });

                return false;
            });

            // Initialize price preview
            updatePricePreview();

            // Auto-focus on price input
            $('#sellingPriceInput').focus();
        });
    </script>
@endpush
