@extends('seller.layout.app')

@push('title')
    Create Delivery Call
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <style>
        :root {
            --primary: #10b981;
            --primary-dark: #059669;
        }

        .form-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .form-header {
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e2e8f0;
        }

        .form-header h1 {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .form-label .required {
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(16,185,129,0.1);
        }

        .product-card {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .product-info {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            background: white;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .product-code {
            font-size: 12px;
            color: #64748b;
            font-family: monospace;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .form-actions {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 2px solid #e2e8f0;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="form-container">
            <div class="form-header">
                <h1>
                    <span class="material-icons-round" style="color: var(--primary);">add_circle</span>
                    Create New Delivery Call
                </h1>
                <p class="text-muted mt-2">Fill in the details to create a new delivery call</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('seller.delivery_calls.store') }}">
                @csrf

                <!-- Product Selection -->
                <div class="product-card">
                    <div class="product-info">
                        <div class="product-image">📦</div>
                        <div class="product-details">
                            <div class="product-name">Select Product</div>
                            <div class="product-code">Choose from your inventory</div>
                        </div>
                        <div style="min-width: 200px;">
                            <select name="product_id" id="productSelect" class="form-control" required>
                                <option value="">-- Select Product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}"
                                            data-code="{{ $product->code }}"
                                            data-name="{{ $product->name }}"
                                            data-price="{{ $product->display_price }}"
                                            data-stock="{{ $product->qty }}">
                                        {{ $product->name }} ({{ $product->code }}) - Rs. {{ number_format($product->display_price, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Call Information -->
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Call Number <span class="required">*</span></label>
                        <input type="text" name="call_number" class="form-control"
                               value="{{ $nextCallNumber }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Order Code</label>
                        <input type="text" name="order_code" class="form-control"
                               value="{{ old('order_code') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Item Code <span class="required">*</span></label>
                        <input type="text" name="item_code" id="itemCode" class="form-control"
                               value="{{ old('item_code') }}" readonly required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Item Name <span class="required">*</span></label>
                        <input type="text" name="item_name" id="itemName" class="form-control"
                               value="{{ old('item_name') }}" readonly required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Quantity <span class="required">*</span></label>
                        <input type="number" name="qty" id="qty" class="form-control"
                               value="{{ old('qty', 1) }}" min="1" required>
                        <small class="text-muted" id="stockInfo"></small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Unit Price (Rs.) <span class="required">*</span></label>
                        <input type="number" name="price" id="price" class="form-control"
                               value="{{ old('price') }}" step="0.01" readonly required>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Total (Rs.)</label>
                        <input type="number" name="total" id="total" class="form-control"
                               value="{{ old('total') }}" step="0.01" readonly>
                    </div>
                </div>

                <!-- Customer Information -->
                <h5 class="mt-4 mb-3 fw-bold">Customer Information</h5>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">Customer Name <span class="required">*</span></label>
                        <input type="text" name="customer_name" class="form-control"
                               value="{{ old('customer_name') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone 1 <span class="required">*</span></label>
                        <input type="text" name="customer_phone_01" class="form-control"
                               value="{{ old('customer_phone_01') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone 2</label>
                        <input type="text" name="customer_phone_02" class="form-control"
                               value="{{ old('customer_phone_02') }}">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Address <span class="required">*</span></label>
                        <textarea name="customer_address" class="form-control" rows="2" required>{{ old('customer_address') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">City <span class="required">*</span></label>
                        <input type="text" name="city" class="form-control"
                               value="{{ old('city') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">District <span class="required">*</span></label>
                        <input type="text" name="district" class="form-control"
                               value="{{ old('district') }}" required>
                    </div>
                </div>

                <!-- Additional Information -->
                <h5 class="mt-4 mb-3 fw-bold">Additional Information</h5>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Platform</label>
                        <select name="platform" class="form-control">
                            <option value="">Select Platform</option>
                            <option value="fb">Facebook</option>
                            <option value="ig">Instagram</option>
                            <option value="tiktok">TikTok</option>
                            <option value="website">Website</option>
                            <option value="phone">Phone</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ad Name</label>
                        <input type="text" name="ad_name" class="form-control" value="{{ old('ad_name') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Campaign Name</label>
                        <input type="text" name="campaign_name" class="form-control" value="{{ old('campaign_name') }}">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Remarks</label>
                        <textarea name="remark" class="form-control" rows="2">{{ old('remark') }}</textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('seller.delivery_calls.pending') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <span class="material-icons-round me-1">save</span>
                        Create Delivery Call
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('productSelect').addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const code = option.dataset.code;
            const name = option.dataset.name;
            const price = option.dataset.price;
            const stock = option.dataset.stock;

            document.getElementById('itemCode').value = code || '';
            document.getElementById('itemName').value = name || '';
            document.getElementById('price').value = price || '';
            document.getElementById('stockInfo').textContent = stock ? `Available: ${stock} units` : '';

            updateTotal();
        });

        document.getElementById('qty').addEventListener('input', updateTotal);

        function updateTotal() {
            const qty = parseFloat(document.getElementById('qty').value) || 0;
            const price = parseFloat(document.getElementById('price').value) || 0;
            document.getElementById('total').value = (qty * price).toFixed(2);
        }
    </script>
@endsection
