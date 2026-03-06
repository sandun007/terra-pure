@extends('employee.layout.app')

@push('title')
    {{ $product ? 'Update Price - ' . $product->name : 'Create Price Change' }}
@endpush

@push('css')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Toastr (optional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <style>
        :root{
            --primary:#6366f1;
            --primary2:#4f46e5;
            --success:#10b981;
            --danger:#ef4444;
            --warning:#f59e0b;
            --info:#06b6d4;
            --muted:#6b7280;
            --border: rgba(0,0,0,.08);
            --card: #ffffff;
            --shadow: 0 18px 45px rgba(0,0,0,.12);
            --shadow2: 0 10px 28px rgba(0,0,0,.10);
            --radius: 18px;
        }

        .mi{ font-size:18px; vertical-align:-4px; margin-right:8px; }

        /* HERO */
        .hero {
            border-radius: 24px;
            padding: 22px;
            background:
                radial-gradient(900px 260px at 10% 0%, rgba(99,102,241,.28), transparent 55%),
                radial-gradient(900px 260px at 90% 15%, rgba(6,182,212,.18), transparent 55%),
                linear-gradient(135deg, rgba(255,255,255,.85), rgba(255,255,255,.70));
            border: 1px solid rgba(255,255,255,.55);
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
            overflow: hidden;
            position: relative;
        }
        .hero::after{
            content:"";
            position:absolute;
            inset:-120px -120px auto auto;
            width:260px;
            height:260px;
            border-radius:999px;
            background: rgba(99,102,241,.14);
            filter: blur(2px);
            transform: rotate(12deg);
        }
        .hero-title{
            font-weight: 900;
            margin:0;
            letter-spacing: .2px;
        }
        .hero-sub{
            color: var(--muted);
            margin:6px 0 0 0;
        }

        /* Breadcrumb */
        .breadcrumb{
            background: transparent;
            margin-bottom: 12px;
        }
        .breadcrumb a{ text-decoration:none; }

        /* Cards */
        .cardx{
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow2);
            overflow: hidden;
        }
        .cardx .cardx-body{ padding: 18px; }

        .section-title{
            display:flex;
            align-items:center;
            justify-content: space-between;
            gap: 10px;
            padding-bottom: 12px;
            margin-bottom: 14px;
            border-bottom: 1px solid rgba(0,0,0,.06);
        }
        .section-title h5{
            margin:0;
            font-weight: 900;
            font-size: 16px;
        }
        .section-title small{ color: var(--muted); }

        /* Info rows */
        .info-grid{
            display:grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }
        .info-box{
            border: 1px solid rgba(0,0,0,.06);
            border-radius: 16px;
            padding: 12px;
            background: linear-gradient(135deg, rgba(99,102,241,.07), rgba(255,255,255,.90));
        }
        .info-label{
            color: var(--muted);
            font-weight: 800;
            font-size: 12px;
            letter-spacing: .2px;
            text-transform: uppercase;
        }
        .info-value{
            font-weight: 900;
            margin-top: 4px;
            font-size: 16px;
        }
        .info-value.primary{ color: var(--primary2); }
        .info-value.success{ color: var(--success); }
        .info-value.danger{ color: var(--danger); }

        /* Inputs */
        .form-control, .form-select, .input-group-text{
            border-radius: 14px !important;
            border: 1px solid rgba(0,0,0,.08) !important;
        }
        .input-group .form-control{ border-left: 0 !important; }
        .input-group .input-group-text{
            background: rgba(99,102,241,.08);
            font-weight: 900;
        }

        /* Live compare */
        .compare{
            display:flex;
            gap: 14px;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, rgba(16,185,129,.07), rgba(255,255,255,.95));
            border: 1px solid rgba(0,0,0,.06);
            border-radius: 18px;
            padding: 14px;
            margin-top: 16px;
            flex-wrap: wrap;
        }
        .price-card{
            min-width: 220px;
            flex: 1;
            padding: 12px;
            border-radius: 16px;
            background: rgba(255,255,255,.9);
            border: 1px solid rgba(0,0,0,.06);
        }
        .price-card .lbl{
            font-weight: 800;
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
        }
        .price-card .val{
            font-weight: 950;
            font-size: 22px;
            margin-top: 4px;
        }
        .price-card .val.old{
            color:#94a3b8;
            text-decoration: line-through;
        }
        .price-card .val.new{
            color: var(--success);
        }
        .arrow{
            width: 46px;
            height: 46px;
            border-radius: 16px;
            display:flex;
            align-items:center;
            justify-content:center;
            background: rgba(99,102,241,.10);
            border: 1px solid rgba(0,0,0,.06);
            color: var(--primary2);
            font-size: 18px;
        }

        /* Calculation preview */
        .calc{
            margin-top: 12px;
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(99,102,241,.08), rgba(255,255,255,.95));
            border: 1px solid rgba(0,0,0,.06);
            padding: 14px;
        }
        .calc-grid{
            display:grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }
        .calc-item{
            padding: 12px;
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,.06);
            background: rgba(255,255,255,.92);
        }
        .calc-item small{
            color: var(--primary2);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .2px;
            font-size: 12px;
        }
        .calc-item h6{
            margin: 6px 0 0 0;
            font-weight: 950;
            font-size: 16px;
        }

        /* Product picker */
        .picker-hint{
            color: var(--muted);
            font-size: 13px;
            margin: 0;
        }
        .product-card{
            border: 1px solid rgba(0,0,0,.08);
            border-radius: 18px;
            padding: 14px;
            background: linear-gradient(135deg, rgba(99,102,241,.05), rgba(255,255,255,.95));
            transition: all .2s ease;
            height: 100%;
        }
        .product-card:hover{
            transform: translateY(-2px);
            box-shadow: 0 16px 30px rgba(0,0,0,.10);
            border-color: rgba(99,102,241,.35);
        }
        .product-card h6{ margin: 0; font-weight: 900; }
        .product-card .meta{ color: var(--muted); font-size: 12px; margin-top: 4px; }
        .pill{
            display:inline-flex;
            align-items:center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 900;
            font-size: 12px;
            border: 1px solid rgba(0,0,0,.06);
            background: rgba(255,255,255,.9);
        }
        .pill.primary{ color: var(--primary2); background: rgba(99,102,241,.10); }
        .pill.info{ color: #075985; background: rgba(6,182,212,.12); }
        .pill.stock-ok{ color:#065f46; background: rgba(16,185,129,.14); }
        .pill.stock-low{ color:#92400e; background: rgba(245,158,11,.18); }
        .pill.stock-out{ color:#7f1d1d; background: rgba(239,68,68,.14); }

        /* Sticky actions */
        .sticky-actions{
            position: sticky;
            bottom: 12px;
            z-index: 30;
            margin-top: 14px;
        }
        .actions-bar{
            border-radius: 18px;
            padding: 12px;
            background: rgba(255,255,255,.92);
            border: 1px solid rgba(0,0,0,.08);
            box-shadow: var(--shadow2);
            display:flex;
            justify-content:flex-end;
            gap: 10px;
            flex-wrap: wrap;
            backdrop-filter: blur(10px);
        }
        .btnx{
            border-radius: 14px;
            padding: 10px 14px;
            font-weight: 900;
            border: 1px solid rgba(0,0,0,.08);
            display:inline-flex;
            align-items:center;
            gap:8px;
        }
        .btnx-primary{
            background: linear-gradient(135deg, var(--primary), var(--primary2));
            color: #fff;
            border-color: rgba(99,102,241,.35);
        }
        .btnx-secondary{
            background: rgba(0,0,0,.04);
            color: #111827;
        }

        /* Responsive */
        @media (max-width: 992px){
            .info-grid{ grid-template-columns: 1fr; }
            .calc-grid{ grid-template-columns: 1fr; }
            .arrow{ width: 100%; }
            .price-card{ min-width: 100%; }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employee.wholesaler.price-changes.index') }}">Price Changes</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product ? 'Update Price' : 'Create Price Change' }}</li>
            </ol>
        </nav>

        <!-- HERO -->
        <div class="hero mb-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                <div>
                    <h3 class="hero-title">
                        <span class="material-icons-round mi">{{ $product ? 'sell' : 'add_circle' }}</span>
                        {{ $product ? 'Update Price' : 'Create Price Change' }}
                    </h3>
                    <p class="hero-sub">
                        {{ $product ? 'Review current price and apply a new price with live preview.' : 'Select a wholesaler and product to start.' }}
                    </p>
                </div>

                @if($product)
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="pill primary"><span class="material-icons-round">barcode</span> {{ $product->code }}</span>
                        <span class="pill info"><span class="material-icons-round">store</span> {{ $product->wholesaler->business_name ?? 'N/A' }}</span>
                        @php
                            $qty = $product->qty ?? 0;
                            $min = $product->min_stock_level ?? 0;
                            $stockClass = $qty <= 0 ? 'stock-out' : ($qty <= $min ? 'stock-low' : 'stock-ok');
                        @endphp
                        <span class="pill {{ $stockClass }}"><span class="material-icons-round">inventory_2</span> Stock: {{ $qty }}</span>
                    </div>
                @endif
            </div>
        </div>

        @if(!$product)
            <!-- PRODUCT PICKER -->
            <div class="cardx mb-4">
                <div class="cardx-body">
                    <div class="section-title">
                        <div>
                            <h5><span class="material-icons-round mi text-primary">search</span>Select Product</h5>
                            <small>Pick wholesaler first, then search product by name or code.</small>
                        </div>
                        <p class="picker-hint mb-0">Tip: type at least <b>2 characters</b>.</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Wholesaler</label>
                            <select id="wholesalerSelect" class="form-select">
                                <option value="">Select Wholesaler</option>
                                @foreach($wholesalers as $wholesaler)
                                    <option value="{{ $wholesaler->id }}">{{ $wholesaler->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Search Product</label>
                            <input type="text" id="productSearch" class="form-control" placeholder="Search by name or code...">
                        </div>

                        <div class="col-12">
                            <div id="loader" class="text-center py-3 text-muted" style="display:none;">
                                <span class="material-icons-round mi">autorenew</span>Loading products...
                            </div>

                            <div id="productsList" class="row g-3 mt-1">
                                <div class="col-12 text-center py-4 text-muted">
                                    <span class="material-icons-round" style="font-size:44px;">inventory_2</span>
                                    <div class="fw-bold mt-2">No products loaded</div>
                                    <small>Select a wholesaler and start searching.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endif

        @if($product)
            <!-- CURRENT PRICE INFO -->
            <div class="cardx mb-4">
                <div class="cardx-body">
                    <div class="section-title">
                        <h5><span class="material-icons-round mi text-primary">info</span>Current Price Information</h5>
                        <small>Current values from product record.</small>
                    </div>

                    <div class="info-grid">
                        <div class="info-box">
                            <div class="info-label">Selling Price</div>
                            <div class="info-value">Rs. {{ number_format($product->selling_price, 2) }}</div>
                        </div>

                        <div class="info-box">
                            <div class="info-label">Display Price</div>
                            <div class="info-value">Rs. {{ number_format($product->display_price ?? $product->selling_price, 2) }}</div>
                        </div>

                        <div class="info-box">
                            <div class="info-label">Discount</div>
                            <div class="info-value">{{ $product->discount ?? 0 }}%</div>
                        </div>

                        <div class="info-box">
                            <div class="info-label">Final Price</div>
                            <div class="info-value primary">Rs. {{ number_format($product->final_price, 2) }}</div>
                        </div>

                        <div class="info-box">
                            <div class="info-label">Cost Price</div>
                            <div class="info-value">Rs. {{ number_format($product->cost_price ?? 0, 2) }}</div>
                        </div>

                        @php $profit = ($product->final_price - ($product->cost_price ?? 0)); @endphp
                        <div class="info-box">
                            <div class="info-label">Profit Margin</div>
                            <div class="info-value {{ $profit >= 0 ? 'success' : 'danger' }}">
                                Rs. {{ number_format($profit, 2) }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- FORM -->
            <form method="POST" action="{{ route('employee.wholesaler.price-changes.store') }}" id="priceChangeForm">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <!-- NEW PRICE -->
                <div class="cardx mb-4">
                    <div class="cardx-body">
                        <div class="section-title">
                            <h5><span class="material-icons-round mi text-primary">sell</span>New Price Details</h5>
                            <small>Live preview updates as you type.</small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">New Selling Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs.</span>
                                    <input type="number"
                                           name="new_selling_price"
                                           id="newSellingPrice"
                                           class="form-control"
                                           step="0.01"
                                           min="0"
                                           value="{{ old('new_selling_price', $product->selling_price) }}"
                                           required>
                                </div>
                                <small class="text-muted">Actual wholesaler selling price.</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Display Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rs.</span>
                                    <input type="number"
                                           name="new_display_price"
                                           id="newDisplayPrice"
                                           class="form-control"
                                           step="0.01"
                                           min="0"
                                           value="{{ old('new_display_price', $product->display_price ?? $product->selling_price) }}">
                                </div>
                                <small class="text-muted">If empty, it will use selling price.</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Discount (%)</label>
                                <div class="input-group">
                                    <input type="number"
                                           name="new_discount"
                                           id="newDiscount"
                                           class="form-control"
                                           step="0.1"
                                           min="0"
                                           max="100"
                                           value="{{ old('new_discount', $product->discount ?? 0) }}">
                                    <span class="input-group-text">%</span>
                                </div>
                                <small class="text-muted">Optional discount applied on display price.</small>
                            </div>
                        </div>

                        <!-- LIVE PREVIEW -->
                        <div class="compare">
                            <div class="price-card">
                                <div class="lbl">Current Final Price</div>
                                <div class="val old" id="currentPriceDisplay">Rs. {{ number_format($product->final_price, 2) }}</div>
                            </div>

                            <div class="arrow"><span class="material-icons-round">arrow_forward</span></div>

                            <div class="price-card">
                                <div class="lbl">New Final Price</div>
                                <div class="val new" id="newPriceDisplay">Rs. {{ number_format($product->final_price, 2) }}</div>
                            </div>
                        </div>

                        <!-- CALC -->
                        <div class="calc" id="calculationPreview">
                            <div class="calc-grid">
                                <div class="calc-item">
                                    <small>Price Difference</small>
                                    <h6 id="priceDifference">Rs. 0.00</h6>
                                </div>
                                <div class="calc-item">
                                    <small>Percentage Change</small>
                                    <h6 id="percentageChange">0%</h6>
                                </div>
                                <div class="calc-item">
                                    <small>New Profit</small>
                                    <h6 id="newProfit">Rs. {{ number_format($product->final_price - ($product->cost_price ?? 0), 2) }}</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- OPTIONS -->
                <div class="cardx mb-4">
                    <div class="cardx-body">
                        <div class="section-title">
                            <h5><span class="material-icons-round mi text-primary">settings</span>Additional Options</h5>
                            <small>Reason, notes, and approval requirement.</small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Change Reason</label>
                                <select name="change_reason" class="form-select">
                                    <option value="">Select Reason</option>
                                    <option value="price_adjustment">Price Adjustment</option>
                                    <option value="market_trend">Market Trend</option>
                                    <option value="competitor_price">Competitor Price</option>
                                    <option value="cost_increase">Cost Increase</option>
                                    <option value="cost_decrease">Cost Decrease</option>
                                    <option value="promotion">Promotion</option>
                                    <option value="clearance">Clearance</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Notes</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="require_approval" class="form-check-input" id="requireApproval" value="1">
                                    <label class="form-check-label" for="requireApproval">
                                        <strong>Require Approval</strong> — this change must be approved before applying.
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- STICKY ACTIONS -->
                <div class="sticky-actions">
                    <div class="actions-bar">
                        <a href="{{ route('employee.wholesaler.price-changes.index') }}" class="btnx btnx-secondary cancel-btn">
                            <span class="material-icons-round">close</span>
                            Cancel
                        </a>
                        <button type="submit" class="btnx btnx-primary" id="btnSave">
                            <span class="material-icons-round">save</span>
                            Save Price Change
                        </button>
                    </div>
                </div>

            </form>
        @endif

    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastr (optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @if($product)
        <script>
            // Safe numeric values
            const oldFinalPrice = Number({{ $product->final_price ?? 0 }});
            const costPrice     = Number({{ $product->cost_price ?? 0 }});

            function money(n){
                const v = (isFinite(n) ? n : 0);
                return 'Rs. ' + v.toFixed(2);
            }

            function calculateChanges(){
                const selling = Number($('#newSellingPrice').val()) || 0;
                let display   = Number($('#newDisplayPrice').val());
                const disc    = Number($('#newDiscount').val()) || 0;

                // if display empty/invalid => use selling
                if(!isFinite(display) || display <= 0) display = selling;

                // final after discount
                let newFinal = display;
                if(disc > 0){
                    newFinal = display * (1 - (disc/100));
                }

                $('#newPriceDisplay').text(money(newFinal));

                const diff = newFinal - oldFinalPrice;
                const percent = (oldFinalPrice > 0) ? (diff/oldFinalPrice * 100) : 0;

                const diffText = (diff > 0 ? '+Rs. ' : (diff < 0 ? '-Rs. ' : 'Rs. ')) + Math.abs(diff).toFixed(2);
                $('#priceDifference').text(diffText)
                    .removeClass('text-success text-danger text-muted')
                    .addClass(diff > 0 ? 'text-success' : (diff < 0 ? 'text-danger' : 'text-muted'));

                const pctText = (diff > 0 ? '+' : '') + percent.toFixed(2) + '%';
                $('#percentageChange').text(pctText)
                    .removeClass('text-success text-danger text-muted')
                    .addClass(diff > 0 ? 'text-success' : (diff < 0 ? 'text-danger' : 'text-muted'));

                const profit = newFinal - costPrice;
                $('#newProfit').text(money(profit))
                    .removeClass('text-success text-danger')
                    .addClass(profit >= 0 ? 'text-success' : 'text-danger');
            }

            $(document).ready(function(){
                $('#newSellingPrice, #newDisplayPrice, #newDiscount').on('input', calculateChanges);
                calculateChanges();

                // SweetAlert confirm on SAVE
                $('#priceChangeForm').on('submit', function(e){
                    e.preventDefault();

                    const newFinalText = $('#newPriceDisplay').text();
                    const diffText = $('#priceDifference').text();
                    const pctText  = $('#percentageChange').text();

                    Swal.fire({
                        title: 'Confirm save price change?',
                        html: `
                            <div style="text-align:left">
                                <div><b>Current Final:</b> {{ 'Rs. '.number_format($product->final_price,2) }}</div>
                                <div><b>New Final:</b> ${newFinalText}</div>
                                <div><b>Difference:</b> ${diffText} (${pctText})</div>
                                <div class="mt-2 text-muted" style="font-size:12px;">
                                    If "Require Approval" is checked, this will go to pending approvals.
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Save',
                        cancelButtonText: 'Cancel'
                    }).then((r)=>{
                        if(r.isConfirmed){
                            this.submit();
                        }
                    });
                });

                // SweetAlert confirm on CANCEL
                $('.cancel-btn').on('click', function(e){
                    e.preventDefault();
                    const href = this.getAttribute('href');

                    Swal.fire({
                        title: 'Cancel and leave this page?',
                        text: 'Any unsaved changes will be lost.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Leave',
                        cancelButtonText: 'Stay'
                    }).then((r)=>{
                        if(r.isConfirmed){
                            window.location.href = href;
                        }
                    });
                });

                // Toastr flash (optional)
                @if(session('success'))
                toastr.success(@json(session('success')));
                @endif
                @if(session('error'))
                toastr.error(@json(session('error')));
                @endif
            });
        </script>
    @endif

    @if(!$product)
        <script>
            $(document).ready(function(){
                const $wh = $('#wholesalerSelect');
                const $q  = $('#productSearch');
                const $list = $('#productsList');
                const $loader = $('#loader');

                function escapeHtml(str){
                    return String(str ?? '')
                        .replaceAll('&','&amp;')
                        .replaceAll('<','&lt;')
                        .replaceAll('>','&gt;')
                        .replaceAll('"','&quot;')
                        .replaceAll("'","&#039;");
                }

                function renderEmpty(msg){
                    $list.html(`
                        <div class="col-12 text-center py-4 text-muted">
                            <span class="material-icons-round" style="font-size:44px;">inventory_2</span>
                            <div class="fw-bold mt-2">${escapeHtml(msg)}</div>
                            <small>Try another keyword.</small>
                        </div>
                    `);
                }

                function loadProducts(){
                    const wholesalerId = $wh.val();
                    const search = ($q.val() || '').trim();

                    if(!wholesalerId){
                        renderEmpty('Please select a wholesaler');
                        return;
                    }
                    if(search.length < 2){
                        renderEmpty('Type at least 2 characters to search');
                        return;
                    }

                    $loader.show();

                    $.ajax({
                        url: '{{ route("employee.wholesaler.exchanges.api.products") }}',
                        method: 'GET',
                        data: { wholesaler_id: wholesalerId },
                        success: function(res){
                            $loader.hide();

                            if(!res || !res.success || !Array.isArray(res.products)){
                                renderEmpty('No products found');
                                return;
                            }

                            const keyword = search.toLowerCase();
                            const filtered = res.products.filter(p =>
                                (p.name || '').toLowerCase().includes(keyword) ||
                                (p.code || '').toLowerCase().includes(keyword)
                            );

                            if(filtered.length === 0){
                                renderEmpty('No matching products');
                                return;
                            }

                            let html = '';
                            filtered.forEach(p => {
                                const qty = Number(p.qty || 0);
                                const min = Number(p.min_stock_level || 0);
                                let stockClass = 'stock-ok';
                                if(qty <= 0) stockClass = 'stock-out';
                                else if(qty <= min) stockClass = 'stock-low';

                                html += `
                                    <div class="col-md-4">
                                        <a class="text-decoration-none"
                                           href="{{ url('employee/wholesaler/price-change/create') }}?product_id=${encodeURIComponent(p.id)}"
                                           onclick="return confirmPick('${escapeHtml(p.name)}')">
                                            <div class="product-card">
                                                <h6>${escapeHtml(p.name)}</h6>
                                                <div class="meta">${escapeHtml(p.code)}</div>

                                                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                                                    <span class="pill primary">
                                                        <span class="material-icons-round">sell</span>
                                                        Rs. ${Number(p.selling_price || 0).toFixed(2)}
                                                    </span>
                                                    <span class="pill ${stockClass}">
                                                        <span class="material-icons-round">inventory_2</span>
                                                        Stock: ${qty}
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                `;
                            });

                            $list.html(html);
                        },
                        error: function(){
                            $loader.hide();
                            renderEmpty('Error loading products');
                        }
                    });
                }

                window.confirmPick = function(productName){
                    // Block default navigation and use SweetAlert
                    event.preventDefault();
                    const href = event.currentTarget.getAttribute('href');

                    Swal.fire({
                        title: 'Select this product?',
                        text: productName,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Select',
                        cancelButtonText: 'Cancel'
                    }).then((r)=>{
                        if(r.isConfirmed){
                            window.location.href = href;
                        }
                    });
                    return false;
                };

                $wh.on('change', loadProducts);
                $q.on('keyup', function(){
                    clearTimeout(window.__pc_t);
                    window.__pc_t = setTimeout(loadProducts, 250);
                });
            });
        </script>
    @endif
@endpush
