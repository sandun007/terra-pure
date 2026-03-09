{{-- resources/views/employee/wholesaler/order-exchanges/create.blade.php --}}
@extends('employee.layout.app')

@push('title')
    Create Order Exchange
@endpush

@push('css')
    <!-- Google Material Icons (Round) -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --primary2: #4f46e5;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #06b6d4;
            --muted: #6b7280;
            --border: rgba(0, 0, 0, .08);
            --card: #ffffff;
            --shadow: 0 18px 45px rgba(0, 0, 0, .12);
            --shadow2: 0 10px 28px rgba(0, 0, 0, .10);
            --radius: 18px;
        }

        /* Hero Section */
        .hero {
            border-radius: 24px;
            padding: 22px;
            margin-bottom: 18px;
            background: radial-gradient(900px 260px at 10% 0%, rgba(245, 158, 11, .28), transparent 55%),
            radial-gradient(900px 260px at 90% 15%, rgba(16, 185, 129, .18), transparent 55%),
            linear-gradient(135deg, rgba(255, 255, 255, .88), rgba(255, 255, 255, .72));
            border: 1px solid rgba(255, 255, 255, .55);
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: -120px -120px auto auto;
            width: 260px;
            height: 260px;
            border-radius: 999px;
            background: rgba(245, 158, 11, .14);
            filter: blur(2px);
            transform: rotate(12deg);
        }

        .hero-title {
            font-weight: 950;
            margin: 0;
            letter-spacing: .2px;
        }

        .hero-sub {
            color: var(--muted);
            margin: 6px 0 0 0;
        }

        /* Steps */
        .steps {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 14px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 999px;
            border: 1px solid rgba(0, 0, 0, .06);
            background: rgba(255, 255, 255, .9);
            font-weight: 900;
            font-size: 13px;
            color: #111827;
        }

        .step .dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: rgba(0, 0, 0, .15);
        }

        .step.source .dot {
            background: rgba(99, 102, 241, .75);
        }

        .step.dest .dot {
            background: rgba(16, 185, 129, .75);
        }

        .step.summary .dot {
            background: rgba(245, 158, 11, .85);
        }

        /* Cards */
        .cardx {
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow2);
            overflow: hidden;
            height: 100%;
        }

        .cardx .cardx-body {
            padding: 18px;
        }

        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding-bottom: 12px;
            margin-bottom: 14px;
            border-bottom: 1px solid rgba(0, 0, 0, .06);
        }

        .section-title h5 {
            margin: 0;
            font-weight: 950;
            font-size: 16px;
        }

        .section-title small {
            color: var(--muted);
        }

        /* Badges */
        .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 950;
            font-size: 12px;
            border: 1px solid rgba(0, 0, 0, .06);
            background: rgba(255, 255, 255, .92);
            white-space: nowrap;
        }

        .pill.source {
            color: var(--primary2);
            background: rgba(99, 102, 241, .10);
        }

        .pill.dest {
            color: #065f46;
            background: rgba(16, 185, 129, .12);
        }

        .pill.warn {
            color: #92400e;
            background: rgba(245, 158, 11, .16);
        }

        /* Forms */
        .form-control,
        .form-select {
            border-radius: 14px !important;
            border: 1px solid rgba(0, 0, 0, .08) !important;
        }

        /* Product Search */
        .product-search {
            position: relative;
        }

        .results {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            max-height: 320px;
            overflow: auto;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, .10);
            border-radius: 14px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, .15);
            display: none;
            z-index: 1000;
        }

        .result-item {
            padding: 12px 12px;
            border-bottom: 1px solid rgba(0, 0, 0, .06);
            cursor: pointer;
            transition: .15s ease;
        }

        .result-item:hover {
            background: rgba(0, 0, 0, .03);
        }

        .result-item:last-child {
            border-bottom: 0;
        }

        .result-title {
            font-weight: 950;
            margin: 0;
        }

        .result-meta {
            color: var(--muted);
            font-size: 12px;
            margin-top: 2px;
        }

        /* Preview */
        .preview {
            margin-top: 12px;
            border-radius: 18px;
            border: 1px solid rgba(0, 0, 0, .06);
            background: linear-gradient(135deg, rgba(0, 0, 0, .02), rgba(255, 255, 255, .96));
            padding: 14px;
            display: none;
        }

        /* Center Arrow */
        .center-path {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 10px;
        }

        .swap {
            width: 70px;
            height: 70px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(245, 158, 11, .14);
            border: 1px solid rgba(0, 0, 0, .06);
            color: #92400e;
            box-shadow: var(--shadow2);
            font-size: 20px;
        }

        .swap small {
            display: block;
            font-size: 10px;
            color: var(--muted);
            font-weight: 900;
            letter-spacing: .4px;
            text-transform: uppercase;
            margin-top: 6px;
        }

        /* Summary */
        .summary {
            margin-top: 18px;
            border-radius: 22px;
            background: radial-gradient(900px 260px at 10% 0%, rgba(245, 158, 11, .22), transparent 55%),
            radial-gradient(900px 260px at 90% 10%, rgba(99, 102, 241, .18), transparent 55%),
            linear-gradient(135deg, #0f172a, #111827);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .10);
            box-shadow: var(--shadow);
            overflow: hidden;
            display: none;
        }

        .summary-body {
            padding: 18px;
        }

        .summary h6 {
            color: rgba(255, 255, 255, .65);
            font-weight: 900;
            font-size: 12px;
            text-transform: uppercase;
        }

        .summary .big {
            font-weight: 950;
            font-size: 22px;
            margin: 6px 0 0 0;
        }

        .summary .form-control,
        .summary .form-select {
            background: rgba(255, 255, 255, .12) !important;
            color: #fff !important;
            border: 1px solid rgba(255, 255, 255, .16) !important;
        }

        .summary .form-control::placeholder {
            color: rgba(255, 255, 255, .65);
        }

        .diff-up {
            color: #34d399;
            font-weight: 950;
        }

        .diff-down {
            color: #fb7185;
            font-weight: 950;
        }

        /* Sticky Actions */
        .sticky-actions {
            position: sticky;
            bottom: 12px;
            z-index: 30;
            margin-top: 16px;
        }

        .actions-bar {
            border-radius: 18px;
            padding: 12px;
            background: rgba(255, 255, 255, .92);
            border: 1px solid rgba(0, 0, 0, .08);
            box-shadow: var(--shadow2);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
            backdrop-filter: blur(10px);
        }

        .btnx {
            border-radius: 14px;
            padding: 10px 14px;
            font-weight: 950;
            border: 1px solid rgba(0, 0, 0, .08);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btnx-secondary {
            background: rgba(0, 0, 0, .04);
            color: #111827;
        }

        .btnx-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #111827;
            border-color: rgba(245, 158, 11, .35);
        }

        /* Google Icon */
        .gicon {
            font-family: 'Material Icons Round';
            font-weight: normal;
            font-style: normal;
            font-size: 20px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }

        /* Order Cards */
        .order-card {
            background: #f8fafc;
            border-radius: 14px;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .order-card:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .order-card.selected {
            border: 2px solid var(--primary);
            background: rgba(99, 102, 241, 0.05);
        }

        .order-number {
            font-weight: 950;
            color: var(--primary2);
        }

        /* Product Details Card */
        .product-detail-card {
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            border-radius: 16px;
            padding: 16px;
            margin-top: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .product-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .product-detail-row:last-child {
            border-bottom: none;
        }

        .product-detail-label {
            color: #64748b;
            font-weight: 600;
        }

        .product-detail-value {
            font-weight: 700;
            color: #1e293b;
        }

        /* Product ID Input Section */
        .product-id-section {
            background: #f1f5f9;
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .btn-fetch {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-fetch:hover {
            background: var(--primary2);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Hero Section -->
        <div class="hero">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                <div>
                    <h3 class="hero-title">Create Order Exchange</h3>
                    <p class="hero-sub">Exchange products between wholesaler orders with live value comparison.</p>

                    <div class="steps">
                        <div class="step source"><span class="dot"></span> 1) Select Source Order/Product</div>
                        <div class="step dest"><span class="dot"></span> 2) Select Destination Product</div>
                        <div class="step summary"><span class="dot"></span> 3) Confirm & Create</div>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <span class="pill warn"><span class="gicon">lightbulb</span> Tip: Enter Product ID to auto-fill</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('employee.wholesaler.order-exchanges.store') }}" id="exchangeForm" class="mt-4">
            @csrf

            <div class="row g-3 align-items-stretch">
                <!-- SOURCE SECTION -->
                <div class="col-lg-5">
                    <div class="cardx">
                        <div class="cardx-body">
                            <div class="section-title">
                                <h5><span class="gicon">arrow_forward</span> Source Product</h5>
                                <span class="pill source"><span class="gicon">logout</span> From</span>
                            </div>

                            <!-- Product ID Quick Fetch -->
                            <div class="product-id-section">
                                <label class="form-label fw-bold">Quick Fetch by Product ID</label>
                                <div class="input-group mb-3">
                                    <input type="number" id="sourceProductIdInput" class="form-control"
                                           placeholder="Enter Product ID (e.g., 123)" min="1">
                                    <button type="button" class="btn btn-primary" id="fetchSourceProductBtn">
                                        <span class="gicon">search</span> Fetch
                                    </button>
                                </div>
                                <small class="text-muted">Enter product ID and click Fetch to auto-load details</small>
                            </div>

                            <!-- Source Wholesaler Selection -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Source Wholesaler</label>
                                <select name="source_wholesaler_id" id="sourceWholesaler" class="form-select" required>
                                    <option value="">Select Wholesaler</option>
                                    @foreach($wholesalers as $wholesaler)
                                        <option value="{{ $wholesaler->id }}" {{ request('source_wholesaler_id') == $wholesaler->id ? 'selected' : '' }}>
                                            {{ $wholesaler->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Source Orders List -->
                            <div class="mb-3" id="sourceOrdersSection" style="display:none;">
                                <label class="form-label fw-bold">Select Source Order (Optional)</label>
                                <div id="sourceOrdersList" class="mb-2"></div>
                                <div class="text-center">
                                    <span class="badge bg-light text-dark">OR search product below</span>
                                </div>
                            </div>

                            <input type="hidden" name="source_order_id" id="sourceOrderId">
                            <input type="hidden" name="source_order_item_id" id="sourceOrderItemId">
                            <input type="hidden" name="source_product_id" id="sourceProductId" required>

                            <!-- Product Search -->
                            <div class="product-search mb-2">
                                <label class="form-label fw-bold">Search Source Product</label>
                                <input type="text" id="sourceSearch" class="form-control" placeholder="Search by name or code...">
                                <div class="results" id="sourceResults"></div>
                            </div>

                            <!-- Source Product Preview -->
                            <div class="preview" id="sourceProductPreview">
                                <div class="text-center text-muted py-3">
                                    <span class="gicon" style="font-size: 32px;">inventory</span>
                                    <p class="mt-2">No product selected yet</p>
                                </div>
                            </div>

                            <!-- Source Product Details Card (Shows after fetch) -->
                            <div id="sourceProductDetailsCard" class="product-detail-card" style="display: none;">
                                <h6 class="mb-3"><span class="gicon">inventory</span> Source Product Details</h6>
                                <div id="sourceProductDetails"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CENTER ARROW -->
                <div class="col-lg-2">
                    <div class="center-path">
                        <div class="swap" title="Exchange">
                            <div class="text-center">
                                <span class="gicon" style="font-size: 24px;">swap_horiz</span>
                                <small>Exchange</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DESTINATION SECTION -->
                <div class="col-lg-5">
                    <div class="cardx">
                        <div class="cardx-body">
                            <div class="section-title">
                                <h5><span class="gicon">arrow_back</span> Destination Product</h5>
                                <span class="pill dest"><span class="gicon">login</span> To</span>
                            </div>

                            <!-- Product ID Quick Fetch -->
                            <div class="product-id-section">
                                <label class="form-label fw-bold">Quick Fetch by Product ID</label>
                                <div class="input-group mb-3">
                                    <input type="number" id="destProductIdInput" class="form-control"
                                           placeholder="Enter Product ID (e.g., 456)" min="1">
                                    <button type="button" class="btn btn-primary" id="fetchDestProductBtn">
                                        <span class="gicon">search</span> Fetch
                                    </button>
                                </div>
                                <small class="text-muted">Enter product ID and click Fetch to auto-load details</small>
                            </div>

                            <!-- Destination Wholesaler Selection -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Destination Wholesaler</label>
                                <select name="destination_wholesaler_id" id="destinationWholesaler" class="form-select" required>
                                    <option value="">Select Wholesaler</option>
                                    @foreach($wholesalers as $wholesaler)
                                        <option value="{{ $wholesaler->id }}" {{ request('destination_wholesaler_id') == $wholesaler->id ? 'selected' : '' }}>
                                            {{ $wholesaler->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" name="destination_product_id" id="destinationProductId" required>

                            <!-- Product Search -->
                            <div class="product-search mb-2">
                                <label class="form-label fw-bold">Search Destination Product</label>
                                <input type="text" id="destinationSearch" class="form-control" placeholder="Search by name or code...">
                                <div class="results" id="destinationResults"></div>
                            </div>

                            <!-- Destination Product Preview -->
                            <div class="preview" id="destinationProductPreview">
                                <div class="text-center text-muted py-3">
                                    <span class="gicon" style="font-size: 32px;">inventory</span>
                                    <p class="mt-2">No product selected yet</p>
                                </div>
                            </div>

                            <!-- Destination Product Details Card (Shows after fetch) -->
                            <div id="destProductDetailsCard" class="product-detail-card" style="display: none;">
                                <h6 class="mb-3"><span class="gicon">inventory</span> Destination Product Details</h6>
                                <div id="destProductDetails"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EXCHANGE SUMMARY -->
            <div class="summary" id="exchangeSummary">
                <div class="summary-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                        <div>
                            <h4 class="mb-1" style="font-weight: 950;">
                                <span class="gicon">summarize</span> Exchange Summary
                            </h4>
                            <div class="hint">Enter quantity and review values before submitting.</div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="pill source" id="pillSourceName" style="display: none;"></span>
                            <span class="pill dest" id="pillDestName" style="display: none;"></span>
                        </div>
                    </div>

                    <div class="row g-3 align-items-end">
                        <div class="col-lg-3">
                            <h6>Exchange Number</h6>
                            <div class="big" id="exchangeNumber">Will be auto-generated</div>
                        </div>

                        <div class="col-lg-3">
                            <h6>Quantity to Exchange</h6>
                            <input type="number" name="exchange_quantity" id="exchangeQuantity" class="form-control form-control-lg"
                                   min="1" required placeholder="e.g. 5">
                        </div>

                        <div class="col-lg-3">
                            <h6>Source Value</h6>
                            <div class="big" id="sourceValue">Rs. 0.00</div>
                        </div>

                        <div class="col-lg-3">
                            <h6>Destination Value</h6>
                            <div class="big" id="destinationValue">Rs. 0.00</div>
                        </div>

                        <div class="col-lg-3">
                            <h6>Price Difference</h6>
                            <div class="big" id="priceDifference">Rs. 0.00</div>
                        </div>

                        <div class="col-lg-3">
                            <h6>Source Stock</h6>
                            <div class="big" id="sourceStock">0</div>
                        </div>

                        <div class="col-lg-3">
                            <h6>Destination Stock</h6>
                            <div class="big" id="destStock">0</div>
                        </div>
                    </div>

                    <hr style="border-color: rgba(255, 255, 255, .12);" class="my-3">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="text-white-50 fw-bold">Exchange Type</label>
                            <select name="exchange_type" class="form-select">
                                <option value="full">Full Exchange</option>
                                <option value="partial">Partial Exchange</option>
                                <option value="credit">Credit Exchange</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="text-white-50 fw-bold">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="">Select</option>
                                <option value="cash">Cash</option>
                                <option value="credit">Credit</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="text-white-50 fw-bold">Adjustment Amount</label>
                            <input type="number" name="adjustment_amount" class="form-control" step="0.01" value="0">
                        </div>

                        <div class="col-12">
                            <label class="text-white-50 fw-bold">Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check mt-1">
                                <input type="checkbox" name="require_approval" class="form-check-input" id="requireApproval" value="1">
                                <label class="form-check-label text-white" for="requireApproval">
                                    <strong>Require Approval</strong> — exchange needs approval before processing
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACTIONS -->
            <div class="sticky-actions">
                <div class="actions-bar">
                    <a href="{{ route('employee.wholesaler.order-exchanges.index') }}" class="btnx btnx-secondary">
                        <span class="gicon">close</span> Cancel
                    </a>
                    <button type="submit" class="btnx btnx-warning" id="submitBtn" disabled>
                        <span class="gicon">swap_horiz</span> Create Exchange
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let sourceProduct = null;
        let destinationProduct = null;
        let sourceProducts = [];
        let destinationProducts = [];
        let sourceOrders = [];

        // Escape HTML
        function esc(str) {
            return String(str ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", "&#039;");
        }

        // Format money
        function money(n) {
            const v = Number(n);
            return 'Rs. ' + (isFinite(v) ? v.toFixed(2) : '0.00');
        }

        // Show toast notification
        function toast(icon, title) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: title,
                showConfirmButton: false,
                timer: 2200,
                timerProgressBar: true
            });
        }

        // Show results dropdown
        function showResults(type, html) {
            $('#' + type + 'Results').html(html).show();
        }

        // Hide results dropdown
        function hideResults(type) {
            $('#' + type + 'Results').hide();
        }

        // Format product details HTML
        function formatProductDetails(product, type) {
            return `
                <div class="product-detail-row">
                    <span class="product-detail-label">Product ID:</span>
                    <span class="product-detail-value">#${product.id}</span>
                </div>
                <div class="product-detail-row">
                    <span class="product-detail-label">Name:</span>
                    <span class="product-detail-value">${esc(product.name)}</span>
                </div>
                <div class="product-detail-row">
                    <span class="product-detail-label">Code:</span>
                    <span class="product-detail-value">${esc(product.code || 'N/A')}</span>
                </div>
                <div class="product-detail-row">
                    <span class="product-detail-label">Price:</span>
                    <span class="product-detail-value">${money(product.price)}</span>
                </div>
                <div class="product-detail-row">
                    <span class="product-detail-label">Stock:</span>
                    <span class="product-detail-value">${product.quantity || 0}</span>
                </div>
                <div class="product-detail-row">
                    <span class="product-detail-label">Wholesaler:</span>
                    <span class="product-detail-value">${esc(product.wholesaler_name || 'N/A')}</span>
                </div>
            `;
        }

        // Set product preview
        function setPreview(type, product) {
            const $p = $('#' + type + 'ProductPreview');
            const $detailsCard = $('#' + (type === 'source' ? 'sourceProductDetailsCard' : 'destProductDetailsCard'));
            const $detailsDiv = $('#' + (type === 'source' ? 'sourceProductDetails' : 'destProductDetails'));

            const previewHtml = `
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <h6 style="font-weight:950;margin:0;">${esc(product.name)}</h6>
                        <div style="color:#6b7280;font-size:12px;margin-top:4px;">${esc(product.code || '')}</div>
                        <div class="mt-2 d-flex gap-2 flex-wrap">
                            <span class="pill ${type === 'source' ? 'source' : 'dest'}">
                                <span class="gicon">sell</span> ${money(product.price)}
                            </span>
                            <span class="pill warn">
                                <span class="gicon">inventory_2</span> Stock: ${esc(product.quantity)}
                            </span>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="pill ${type === 'source' ? 'source' : 'dest'}">
                            <span class="gicon">check_circle</span> Selected
                        </span>
                    </div>
                </div>
            `;

            $p.html(previewHtml).fadeIn(120);

            // Show and populate details card
            $detailsDiv.html(formatProductDetails(product, type));
            $detailsCard.fadeIn(120);

            // Update the hidden input with product ID
            $('#' + type + 'ProductId').val(product.id);

            // Update search input with product name
            $('#' + type + 'Search').val(product.name);
        }

        // Fetch products by wholesaler
        async function fetchProducts(wholesalerId) {
            try {
                const res = await $.ajax({
                    url: '{{ route("employee.wholesaler.order-exchanges.api.products") }}',
                    method: 'GET',
                    data: { wholesaler_id: wholesalerId }
                });
                return (res && res.success && Array.isArray(res.products)) ? res.products : [];
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to load products',
                    text: 'Please try again.'
                });
                return [];
            }
        }

        // Fetch orders by wholesaler
        async function fetchOrders(wholesalerId) {
            try {
                const res = await $.ajax({
                    url: '{{ route("employee.wholesaler.order-exchanges.api.orders") }}',
                    method: 'GET',
                    data: { wholesaler_id: wholesalerId }
                });
                return (res && res.success && Array.isArray(res.orders)) ? res.orders : [];
            } catch (e) {
                console.error('Error loading orders:', e);
                return [];
            }
        }

        // Fetch product by ID
        async function fetchProductById(productId) {
            try {
                const res = await $.ajax({
                    url: '{{ route("employee.wholesaler.order-exchanges.api.product-details") }}',
                    method: 'GET',
                    data: { product_id: productId }
                });

                if (!res || !res.success || !res.product) {
                    throw new Error('Product not found');
                }

                return res.product;
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Product Not Found',
                    text: 'No product found with ID: ' + productId
                });
                return null;
            }
        }

        // Render orders list
        function renderOrdersList(orders) {
            if (!orders || orders.length === 0) {
                $('#sourceOrdersList').html('<p class="text-muted">No orders found for this wholesaler.</p>');
                return;
            }

            let html = '';
            orders.forEach(order => {
                html += `
                    <div class="order-card" data-order-id="${order.id}" onclick="selectOrder(${order.id})">
                        <div class="d-flex justify-content-between">
                            <span class="order-number">${order.order_number || 'Order #' + order.id}</span>
                            <span class="badge bg-${order.status === 'pending' ? 'warning' : 'info'}">${order.status}</span>
                        </div>
                        <div class="small text-muted">Date: ${new Date(order.created_at).toLocaleDateString()}</div>
                        <div class="small">Items: ${order.items?.length || 0}</div>
                    </div>
                `;
            });
            $('#sourceOrdersList').html(html);
        }

        // Select order
        function selectOrder(orderId) {
            const order = sourceOrders.find(o => o.id == orderId);
            if (!order) return;

            $('#sourceOrderId').val(order.id);
            $('.order-card').removeClass('selected');
            $(`.order-card[data-order-id="${orderId}"]`).addClass('selected');
            toast('info', 'Order selected. Now select a product from this order.');
        }

        // Render search results
        function renderSearchList(type, list, query) {
            const q = query.toLowerCase();
            const filtered = list.filter(p =>
                (p.name || '').toLowerCase().includes(q) ||
                (p.code || '').toLowerCase().includes(q)
            ).slice(0, 25);

            if (filtered.length === 0) {
                showResults(type, `<div class="result-item text-muted">No products found</div>`);
                return;
            }

            let html = '';
            filtered.forEach(p => {
                html += `
                    <div class="result-item" data-id="${esc(p.id)}" data-type="${esc(type)}">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="result-title">${esc(p.name)}</div>
                                <div class="result-meta">${esc(p.code || '')}</div>
                            </div>
                            <div class="text-end">
                                <div class="pill ${type === 'source' ? 'source' : 'dest'}">
                                    <span class="gicon">sell</span> Rs. ${Number(p.price || p.selling_price || 0).toFixed(2)}
                                </div>
                                <div class="result-meta">
                                    <span class="gicon" style="font-size:16px;">inventory_2</span> Stock: ${esc(p.quantity)}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            showResults(type, html);
        }

        // Select product by ID
        async function selectProduct(productId, type) {
            try {
                const res = await $.ajax({
                    url: '{{ route("employee.wholesaler.order-exchanges.api.product-details") }}',
                    method: 'GET',
                    data: { product_id: productId }
                });

                if (!res || !res.success || !res.product) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Product not found',
                        text: 'No product found with ID: ' + productId
                    });
                    return;
                }

                const p = res.product;

                // Set wholesaler dropdown if not already set
                const wholesalerId = (type === 'source') ? p.wholesaler_id : p.wholesaler_id;
                if (type === 'source') {
                    $('#sourceWholesaler').val(wholesalerId).trigger('change');
                } else {
                    $('#destinationWholesaler').val(wholesalerId).trigger('change');
                }

                // Set preview and details
                setPreview(type, p);

                if (type === 'source') {
                    sourceProduct = p;
                    toast('success', 'Source product selected (ID: ' + productId + ')');
                } else {
                    destinationProduct = p;
                    toast('success', 'Destination product selected (ID: ' + productId + ')');
                }

                hideResults(type);
                enableSummaryIfReady();
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load product details: ' + e.message
                });
            }
        }

        // Enable summary when both products selected
        function enableSummaryIfReady() {
            if (sourceProduct && destinationProduct) {
                $('#exchangeSummary').slideDown(180);
                $('#submitBtn').prop('disabled', false);

                $('#pillSourceName').text(sourceProduct.name).show();
                $('#pillDestName').text(destinationProduct.name).show();

                updateExchangeSummary();
            }
        }

        // Update exchange summary
        function updateExchangeSummary() {
            if (!sourceProduct || !destinationProduct) return;

            const qty = Number($('#exchangeQuantity').val()) || 0;

            const sourceValue = Number(sourceProduct.price) * qty;
            const destValue = Number(destinationProduct.price) * qty;
            const diff = destValue - sourceValue;

            $('#sourceValue').text(money(sourceValue));
            $('#destinationValue').text(money(destValue));
            $('#sourceStock').text(sourceProduct.quantity || 0);
            $('#destStock').text(destinationProduct.quantity || 0);

            const $diff = $('#priceDifference');
            $diff.removeClass('diff-up diff-down');

            if (diff > 0) $diff.addClass('diff-up').text('+ ' + money(diff));
            else if (diff < 0) $diff.addClass('diff-down').text('- ' + money(Math.abs(diff)));
            else $diff.text(money(0));

            // Stock validation
            const available = Number(sourceProduct.quantity || 0);
            if (qty > 0 && qty > available) {
                $('#submitBtn').prop('disabled', true);
                Swal.fire({
                    icon: 'warning',
                    title: 'Quantity exceeds stock',
                    text: `Available stock: ${available}`
                });
            } else {
                $('#submitBtn').prop('disabled', false);
            }
        }

        $(document).ready(function () {
            // ===== SOURCE FETCH BY ID =====
            $('#fetchSourceProductBtn').on('click', async function () {
                const productId = $('#sourceProductIdInput').val();
                if (!productId) {
                    toast('warning', 'Please enter a product ID');
                    return;
                }
                await selectProduct(productId, 'source');
            });

            // Allow Enter key in source product ID input
            $('#sourceProductIdInput').on('keypress', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#fetchSourceProductBtn').click();
                }
            });

            // ===== DESTINATION FETCH BY ID =====
            $('#fetchDestProductBtn').on('click', async function () {
                const productId = $('#destProductIdInput').val();
                if (!productId) {
                    toast('warning', 'Please enter a product ID');
                    return;
                }
                await selectProduct(productId, 'dest');
            });

            // Allow Enter key in destination product ID input
            $('#destProductIdInput').on('keypress', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $('#fetchDestProductBtn').click();
                }
            });

            // ===== SOURCE WHOLESALER CHANGE =====
            $('#sourceWholesaler').on('change', async function () {
                const id = $(this).val();
                sourceProduct = null;
                $('#sourceProductId').val('');
                $('#sourceOrderId').val('');
                $('#sourceOrderItemId').val('');
                $('#sourceProductPreview').hide();
                $('#sourceProductDetailsCard').hide();
                $('#sourceSearch').val('');
                $('#sourceProductIdInput').val('');
                hideResults('source');
                $('#exchangeSummary').hide();
                $('#submitBtn').prop('disabled', true);
                $('#sourceOrdersSection').hide();

                if (!id) return;

                sourceProducts = await fetchProducts(id);
                sourceOrders = await fetchOrders(id);

                if (sourceOrders.length > 0) {
                    renderOrdersList(sourceOrders);
                    $('#sourceOrdersSection').show();
                }

                toast('info', 'Source wholesaler loaded');
            });

            // ===== DESTINATION WHOLESALER CHANGE =====
            $('#destinationWholesaler').on('change', async function () {
                const id = $(this).val();
                destinationProduct = null;
                $('#destinationProductId').val('');
                $('#destinationProductPreview').hide();
                $('#destProductDetailsCard').hide();
                $('#destinationSearch').val('');
                $('#destProductIdInput').val('');
                hideResults('destination');
                $('#exchangeSummary').hide();
                $('#submitBtn').prop('disabled', true);

                if (!id) return;
                destinationProducts = await fetchProducts(id);
                toast('info', 'Destination wholesaler loaded');
            });

            // ===== SEARCH WITH DEBOUNCE =====
            let t1, t2;

            $('#sourceSearch').on('keyup', function () {
                clearTimeout(t1);
                const q = $(this).val().trim();
                const wid = $('#sourceWholesaler').val();
                if (!wid) {
                    toast('warning', 'Select source wholesaler first');
                    return;
                }
                if (q.length < 2) {
                    hideResults('source');
                    return;
                }
                t1 = setTimeout(() => renderSearchList('source', sourceProducts, q), 220);
            });

            $('#destinationSearch').on('keyup', function () {
                clearTimeout(t2);
                const q = $(this).val().trim();
                const wid = $('#destinationWholesaler').val();
                if (!wid) {
                    toast('warning', 'Select destination wholesaler first');
                    return;
                }
                if (q.length < 2) {
                    hideResults('destination');
                    return;
                }
                t2 = setTimeout(() => renderSearchList('destination', destinationProducts, q), 220);
            });

            // ===== CLICK ON SEARCH RESULT =====
            $(document).on('click', '.result-item[data-id]', function () {
                const id = $(this).data('id');
                const type = $(this).data('type');
                selectProduct(id, type);
            });

            // ===== QUANTITY UPDATE =====
            $('#exchangeQuantity').on('input', updateExchangeSummary);

            // ===== CLICK OUTSIDE TO CLOSE DROPDOWNS =====
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.product-search').length) {
                    $('#sourceResults, #destinationResults').hide();
                }
            });

            // ===== CHECK FOR ORDER ID IN URL =====
            const urlParams = new URLSearchParams(window.location.search);
            const sourceOrderId = urlParams.get('order_id');
            if (sourceOrderId) {
                $('#sourceOrderId').val(sourceOrderId);
                toast('info', 'Order ID ' + sourceOrderId + ' pre-selected');
            }

            const sourceProductId = urlParams.get('product_id');
            if (sourceProductId) {
                $('#sourceProductIdInput').val(sourceProductId);
                setTimeout(() => {
                    $('#fetchSourceProductBtn').click();
                }, 500);
            }

            // ===== FORM SUBMIT CONFIRMATION =====
            $('#exchangeForm').on('submit', function (e) {
                e.preventDefault();

                if (!sourceProduct || !destinationProduct) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Select both products first'
                    });
                    return;
                }

                const qty = Number($('#exchangeQuantity').val()) || 0;
                if (qty <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Enter valid quantity'
                    });
                    return;
                }

                Swal.fire({
                    icon: 'question',
                    title: 'Create Exchange?',
                    html: `
                        <div style="text-align:left">
                            <b>Source:</b> ${esc(sourceProduct.name)} (ID: #${sourceProduct.id})<br>
                            <b>Destination:</b> ${esc(destinationProduct.name)} (ID: #${destinationProduct.id})<br>
                            <b>Quantity:</b> ${qty}<br>
                            <b>Source Value:</b> ${money(Number(sourceProduct.price) * qty)}<br>
                            <b>Destination Value:</b> ${money(Number(destinationProduct.price) * qty)}
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Create',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });

            // Check if products were pre-selected from session
            @if(session('source_product'))
            setTimeout(() => {
                selectProduct({{ session('source_product') }}, 'source');
            }, 1000);
            @endif

            @if(session('dest_product'))
            setTimeout(() => {
                selectProduct({{ session('dest_product') }}, 'dest');
            }, 1000);
            @endif
        });
    </script>
@endpush
