@extends('employee.layout.app')

@push('title')
    Update Price - {{ $product->name }}
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        .price-header {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border-left: 4px solid var(--primary);
        }

        .product-info-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
        }

        .current-price-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .price-label {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .price-value {
            font-size: 28px;
            font-weight: 700;
        }

        .price-old {
            text-decoration: line-through;
            opacity: 0.7;
            font-size: 14px;
        }

        .form-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .form-title {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        .price-preview {
            background: #f3f4f6;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .preview-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .preview-row:last-child {
            border-bottom: none;
        }

        .preview-label {
            color: #6b7280;
        }

        .preview-value {
            font-weight: 600;
            color: #1f2937;
        }

        .preview-value.success { color: var(--success); }
        .preview-value.warning { color: var(--warning); }
        .preview-value.danger { color: var(--danger); }

        .btn-submit {
            background: var(--primary);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background: #4338ca;
            transform: translateY(-2px);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Price Header -->
        <div class="price-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-2">Update Product Price</h4>
                    <p class="text-muted mb-0">Request a price change for this product (requires approval)</p>
                </div>
                <a href="{{ route('employee.seller.products.show', $product->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Product
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <!-- Product Info -->
                <div class="product-info-card">
                    <div class="text-center mb-3">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image mb-3">
                        <h5>{{ $product->name }}</h5>
                        <p class="text-muted mb-1">{{ $product->code }}</p>
                        <p class="text-muted">{{ $product->seller->name ?? 'N/A' }}</p>
                    </div>

                    <div class="current-price-box">
                        <div class="price-label">Current Display Price</div>
                        <div class="price-value">Rs. {{ number_format($product->display_price, 2) }}</div>
                        @if($product->discount > 0)
                            <div class="price-old">Final: Rs. {{ number_format($product->final_price, 2) }}</div>
                        @endif
                    </div>

                    <div class="mt-3">
                        <table class="table table-sm">
                            <tr>
                                <td>Stock Price:</td>
                                <td class="fw-semibold">Rs. {{ number_format($product->stock_price, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Discount:</td>
                                <td class="fw-semibold">{{ $product->discount ?? 0 }}%</td>
                            </tr>
                            <tr>
                                <td>Packaging Cost:</td>
                                <td class="fw-semibold">Rs. {{ number_format($product->packaging_cost ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Current Stock:</td>
                                <td class="fw-semibold">{{ $product->qty }} units</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Price Update Form -->
                <div class="form-section">
                    <div class="form-title">
                        <i class="fas fa-tag me-2"></i>Request Price Change
                    </div>

                    <form action="{{ route('employee.seller.products.price-update', $product->id) }}" method="POST" id="priceForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Display Price (Rs.)</label>
                                <input type="number" name="display_price" id="displayPrice" class="form-control"
                                       step="0.01" min="0" value="{{ old('display_price', $product->display_price) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stock Price (Rs.)</label>
                                <input type="number" name="stock_price" id="stockPrice" class="form-control"
                                       step="0.01" min="0" value="{{ old('stock_price', $product->stock_price) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount (%)</label>
                                <input type="number" name="discount" id="discount" class="form-control"
                                       step="0.01" min="0" max="100" value="{{ old('discount', $product->discount ?? 0) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Packaging Cost (Rs.)</label>
                                <input type="number" name="packaging_cost" id="packagingCost" class="form-control"
                                       step="0.01" min="0" value="{{ old('packaging_cost', $product->packaging_cost ?? 0) }}">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Change Reason <span class="text-danger">*</span></label>
                                <textarea name="change_reason" class="form-control" rows="3"
                                          placeholder="Why are you updating this price?" required></textarea>
                            </div>
                        </div>

                        <!-- Price Preview -->
                        <div class="price-preview" id="pricePreview">
                            <h6 class="mb-3">Price Preview</h6>

                            <div class="preview-row">
                                <span class="preview-label">Display Price:</span>
                                <span class="preview-value" id="previewDisplay">Rs. {{ number_format($product->display_price, 2) }}</span>
                            </div>

                            <div class="preview-row">
                                <span class="preview-label">Stock Price:</span>
                                <span class="preview-value" id="previewStock">Rs. {{ number_format($product->stock_price, 2) }}</span>
                            </div>

                            <div class="preview-row">
                                <span class="preview-label">Discount:</span>
                                <span class="preview-value" id="previewDiscount">{{ $product->discount ?? 0 }}%</span>
                            </div>

                            <div class="preview-row">
                                <span class="preview-label">Final Price:</span>
                                <span class="preview-value success" id="previewFinal">Rs. {{ number_format($product->final_price, 2) }}</span>
                            </div>

                            <div class="preview-row">
                                <span class="preview-label">Price Difference:</span>
                                <span class="preview-value" id="previewDifference">Rs. 0.00</span>
                            </div>

                            <div class="preview-row">
                                <span class="preview-label">Change Percentage:</span>
                                <span class="preview-value" id="previewPercentage">0%</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-paper-plane me-2"></i>Submit for Approval
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Important Notes -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Note:</strong> Price changes will require approval from a manager before they are applied to the product.
                    The seller will be notified once the price change is approved.
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Real-time price calculation
            function updatePricePreview() {
                const displayPrice = parseFloat($('#displayPrice').val()) || 0;
                const stockPrice = parseFloat($('#stockPrice').val()) || 0;
                const discount = parseFloat($('#discount').val()) || 0;
                const packagingCost = parseFloat($('#packagingCost').val()) || 0;

                // Calculate final price
                const finalPrice = displayPrice * (1 - discount / 100);

                // Calculate difference from original
                const originalDisplay = {{ $product->display_price }};
                const difference = displayPrice - originalDisplay;
                const percentageChange = originalDisplay > 0 ? (difference / originalDisplay) * 100 : 0;

                // Update preview
                $('#previewDisplay').text('Rs. ' + displayPrice.toFixed(2));
                $('#previewStock').text('Rs. ' + stockPrice.toFixed(2));
                $('#previewDiscount').text(discount.toFixed(2) + '%');
                $('#previewFinal').text('Rs. ' + finalPrice.toFixed(2));

                $('#previewDifference').text('Rs. ' + difference.toFixed(2))
                    .removeClass('success warning danger')
                    .addClass(difference > 0 ? 'success' : (difference < 0 ? 'danger' : ''));

                $('#previewPercentage').text((percentageChange > 0 ? '+' : '') + percentageChange.toFixed(2) + '%')
                    .removeClass('success warning danger')
                    .addClass(percentageChange > 0 ? 'success' : (percentageChange < 0 ? 'danger' : ''));
            }

            $('#displayPrice, #stockPrice, #discount, #packagingCost').on('input', updatePricePreview);
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
            showConfirmButton: false
        });
        @endif
    </script>
@endpush
