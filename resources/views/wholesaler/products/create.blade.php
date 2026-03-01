@extends('wholesaler.layouts.app')

@push('title')
    Create Product
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #4f46e5; --primary-light: #6366f1; --primary-lighter: #e0e7ff;
            --secondary: #7c3aed; --success: #10b981; --success-light: #d1fae5;
            --warning: #f59e0b; --danger: #ef4444; --danger-light: #fee2e2;
            --light: #ffffff; --light-gray: #f8fafc;
            --gray-100: #f1f5f9; --gray-200: #e2e8f0; --gray-300: #cbd5e1;
            --gray-400: #94a3b8; --gray-500: #64748b; --gray-600: #475569;
            --gray-700: #334155; --gray-800: #1e293b;
            --shadow-sm: 0 1px 3px rgba(0,0,0,.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,.05),0 2px 4px -1px rgba(0,0,0,.03);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,.05),0 4px 6px -2px rgba(0,0,0,.02);
            --shadow-xl: 0 20px 25px -5px rgba(0,0,0,.05),0 10px 10px -5px rgba(0,0,0,.02);
            --r: 12px; --r-lg: 16px; --tr: all .3s cubic-bezier(.4,0,.2,1);
        }
        body { background: var(--light-gray); color: var(--gray-800); font-family: 'Inter',system-ui,-apple-system,sans-serif; }

        /* ── Page Header ── */
        .page-header {
            background: linear-gradient(135deg,var(--light) 0%,var(--primary-lighter) 100%);
            border-radius: var(--r-lg); padding: 2rem; margin-bottom: 2rem;
            border: 1px solid var(--gray-200); box-shadow: var(--shadow-md);
            position: relative; overflow: hidden;
            animation: slideDown .5s ease-out;
        }
        .page-header::before {
            content:''; position:absolute; top:-50%; right:-10%;
            width:300px; height:300px;
            background:radial-gradient(circle,var(--primary-lighter) 0%,transparent 70%);
            border-radius:50%; opacity:.5;
        }
        .ph-content { position:relative; z-index:1; }

        /* ── Cards ── */
        .card-section {
            background: var(--light); border-radius: var(--r-lg);
            box-shadow: var(--shadow-md); padding: 2rem; margin-bottom: 2rem;
            border: 1px solid var(--gray-200); transition: var(--tr); position:relative; overflow:hidden;
        }
        .card-section::before {
            content:''; position:absolute; top:0; left:0; width:4px; height:100%;
            background:linear-gradient(to bottom,var(--primary),var(--secondary));
            opacity:0; transition: var(--tr);
        }
        .card-section:hover { box-shadow:var(--shadow-lg); transform:translateY(-2px); }
        .card-section:hover::before { opacity:1; }

        /* ── Section Header ── */
        .sec-hdr { display:flex; align-items:center; margin-bottom:1.75rem; padding-bottom:1.25rem; border-bottom:2px solid var(--gray-100); }
        .sec-icon {
            width:48px; height:48px; flex-shrink:0; margin-right:1rem;
            background:linear-gradient(135deg,var(--primary-lighter),var(--light));
            border:2px solid var(--primary-light); border-radius:var(--r);
            display:flex; align-items:center; justify-content:center; transition:var(--tr);
        }
        .card-section:hover .sec-icon { transform:rotate(5deg) scale(1.08); }
        .sec-icon .material-icons-outlined { font-size:1.5rem; color:var(--primary); }
        .sec-hdr h5 { font-weight:700; color:var(--gray-800); margin-bottom:.2rem; font-size:1.2rem; }
        .sec-hdr p  { color:var(--gray-600); font-size:.88rem; margin:0; }

        /* ── Form ── */
        .form-group { margin-bottom:1.6rem; }
        .form-label { font-weight:600; color:var(--gray-700); margin-bottom:.6rem; display:flex; align-items:center; gap:.4rem; font-size:.93rem; }
        .form-label .material-icons-outlined { font-size:1.15rem; color:var(--primary); }
        .required::after { content:" *"; color:var(--danger); font-size:1.1rem; line-height:1; }

        .form-control, .form-select {
            border:2px solid var(--gray-200); border-radius:var(--r);
            padding:.85rem 1rem; font-size:.94rem;
            transition:var(--tr); background:var(--light); color:var(--gray-800);
        }
        .form-control:focus, .form-select:focus { border-color:var(--primary); box-shadow:0 0 0 4px rgba(79,70,229,.1); outline:none; }
        .form-control:hover, .form-select:hover { border-color:var(--primary-light); }
        .form-control::placeholder { color:var(--gray-400); }

        .icon-input { position:relative; }
        .icon-input > .material-icons-outlined { position:absolute; left:1rem; top:50%; transform:translateY(-50%); color:var(--gray-400); pointer-events:none; z-index:2; font-size:1.15rem; }
        .icon-input.ta > .material-icons-outlined { top:1rem; transform:none; }
        .icon-input input, .icon-input select, .icon-input textarea { padding-left:3rem; }

        .info-txt { font-size:.83rem; color:var(--gray-500); margin-top:.45rem; display:flex; align-items:center; gap:.25rem; }
        .info-txt .material-icons-outlined { font-size:.95rem; color:var(--gray-400); }
        .char-ctr { font-size:.78rem; color:var(--gray-500); }
        .char-ctr.warn { color:var(--warning); } .char-ctr.bad { color:var(--danger); }

        .is-invalid { border-color:var(--danger)!important; }
        .is-invalid:focus { box-shadow:0 0 0 4px rgba(239,68,68,.1)!important; }
        .invalid-feedback { color:var(--danger); font-size:.85rem; margin-top:.35rem; display:flex; align-items:center; gap:.2rem; }
        .invalid-feedback .material-icons-outlined { font-size:.95rem; }

        /* ── Upload ── */
        .upload-zone {
            border:2px dashed var(--primary-light); border-radius:var(--r-lg);
            padding:2.5rem 2rem; text-align:center; cursor:pointer;
            background:linear-gradient(135deg,var(--light-gray),var(--light));
            transition:var(--tr); min-height:200px;
            display:flex; flex-direction:column; align-items:center; justify-content:center;
        }
        .upload-zone:hover, .upload-zone.over {
            border-color:var(--primary);
            background:linear-gradient(135deg,var(--primary-lighter),var(--light));
            transform:translateY(-2px); box-shadow:var(--shadow-lg);
        }
        .upload-zone .u-icon { font-size:3.5rem; color:var(--primary); margin-bottom:.75rem; transition:var(--tr); }
        .upload-zone:hover .u-icon { transform:translateY(-4px) scale(1.08); }

        .preview-wrap { position:relative; display:inline-block; margin-top:1.25rem; border-radius:var(--r); overflow:hidden; box-shadow:var(--shadow-md); border:2px solid var(--gray-200); max-width:220px; }
        .preview-wrap img { width:100%; height:160px; object-fit:cover; display:block; }
        .preview-remove {
            position:absolute; top:8px; right:8px;
            width:28px; height:28px; border-radius:50%;
            background:var(--light); border:none; cursor:pointer;
            display:flex; align-items:center; justify-content:center;
            box-shadow:var(--shadow-sm); transition:var(--tr);
        }
        .preview-remove:hover { background:var(--danger); color:#fff; }
        .preview-remove .material-icons-outlined { font-size:.9rem; }

        /* ── Price Calc ── */
        .price-calc {
            background:linear-gradient(135deg,var(--light),var(--primary-lighter));
            border-radius:var(--r); padding:1.4rem; margin-top:1.4rem;
            border:1px solid var(--gray-200); position:relative; overflow:hidden;
        }
        .price-calc::before { content:''; position:absolute; top:0; left:0; width:4px; height:100%; background:linear-gradient(to bottom,var(--primary),var(--secondary)); }
        .pc-row { display:flex; justify-content:space-between; align-items:center; padding:.6rem 0; border-bottom:1px solid var(--gray-200); }
        .pc-row:last-child { border-bottom:none; }
        .pc-label { display:flex; align-items:center; gap:.35rem; color:var(--gray-700); font-weight:500; font-size:.88rem; }
        .pc-label .material-icons-outlined { font-size:1.05rem; color:var(--primary); }
        .pc-val { font-weight:700; font-size:.95rem; color:var(--gray-800); }

        /* ── Buttons ── */
        .btn-xl {
            padding:.95rem 2rem; border-radius:var(--r); font-weight:600;
            display:inline-flex; align-items:center; justify-content:center; gap:.6rem;
            font-size:.95rem; transition:var(--tr); border:none; cursor:pointer;
        }
        .btn-xl:hover { transform:translateY(-2px); box-shadow:var(--shadow-lg); }
        .btn-main { background:linear-gradient(135deg,var(--primary),var(--secondary)); color:#fff; }
        .btn-main:hover { color:#fff; opacity:.93; }
        .btn-cancel { border:2px solid var(--gray-300); color:var(--gray-700); background:#fff; }
        .btn-cancel:hover { background:var(--gray-100); }
        .btn-back { border:2px solid var(--primary); color:var(--primary); background:#fff; }
        .btn-back:hover { background:var(--primary); color:#fff; }

        /* ── Loading ── */
        .loading-overlay {
            position:fixed; inset:0; background:rgba(255,255,255,.93);
            backdrop-filter:blur(4px);
            display:none; flex-direction:column; align-items:center; justify-content:center; z-index:99999;
        }
        .spin-wrap { position:relative; width:72px; height:72px; margin-bottom:1.25rem; }
        .spin-o { position:absolute; inset:0; border:3px solid var(--gray-200); border-top-color:var(--primary); border-radius:50%; animation:spin 1s linear infinite; }
        .spin-i { position:absolute; top:14px; left:14px; right:14px; bottom:14px; border:2px solid var(--gray-200); border-bottom-color:var(--secondary); border-radius:50%; animation:spinR 1.5s linear infinite; }

        /* ── Keyframes ── */
        @keyframes slideDown { from{transform:translateY(-30px);opacity:0} to{transform:translateY(0);opacity:1} }
        @keyframes spin  { to{transform:rotate(360deg)} }
        @keyframes spinR { to{transform:rotate(-360deg)} }

        @media (max-width:768px) {
            .page-header, .card-section { padding:1.25rem; }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="ph-content">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h4 class="fw-bold mb-2 d-flex align-items-center gap-2">
                            <span class="material-icons-outlined" style="font-size:2rem;color:var(--primary)">add_circle</span>
                            Create New Product
                        </h4>
                        <p class="mb-0 text-muted">Add a new product to your wholesaler inventory</p>
                    </div>
                    <a href="{{ route('wholesaler.products.index') }}" class="btn-xl btn-back">
                        <span class="material-icons-outlined">arrow_back</span> Back to Products
                    </a>
                </div>
            </div>
        </div>

        {{-- Server validation errors --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <div class="d-flex gap-2">
                    <span class="material-icons-outlined mt-1">error</span>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <span class="material-icons-outlined">check_circle</span>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Loading --}}
        <div class="loading-overlay" id="loadingOverlay">
            <div class="spin-wrap"><div class="spin-o"></div><div class="spin-i"></div></div>
            <div class="fw-bold fs-5">Creating Product…</div>
            <p class="text-muted mt-1 mb-0">Please wait, saving your product.</p>
        </div>

        {{-- ══════════════════════════════════════════════════════
             FORM — standard POST, NO AJAX
             enctype="multipart/form-data" required for file upload
             ══════════════════════════════════════════════════════ --}}
        <form id="productForm"
              action="{{ route('wholesaler.products.store') }}"
              method="POST"
              enctype="multipart/form-data"
              novalidate>
            @csrf

            <div class="row g-4">

                {{-- ╔══════════════════ LEFT ══════════════════╗ --}}
                <div class="col-lg-8">

                    {{-- ── Product Info ── --}}
                    <div class="card-section">
                        <div class="sec-hdr">
                            <div class="sec-icon"><span class="material-icons-outlined">info</span></div>
                            <div><h5>Product Information</h5><p>Basic details about your product</p></div>
                        </div>

                        <div class="row g-4">
                            {{-- Code --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label required"><span class="material-icons-outlined">qr_code</span>Product Code</label>
                                    <div class="icon-input">
                                        <span class="material-icons-outlined">fingerprint</span>
                                        <input type="text" name="code" class="form-control"
                                               value="{{ old('code', $productCode ?? $code ?? '') }}"
                                               readonly style="background:var(--gray-100)">
                                    </div>
                                    <div class="info-txt"><span class="material-icons-outlined">auto_awesome</span>Auto-generated unique identifier</div>
                                </div>
                            </div>

                            {{-- Name --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label required"><span class="material-icons-outlined">inventory</span>Product Name</label>
                                    <div class="icon-input">
                                        <span class="material-icons-outlined">badge</span>
                                        <input type="text" name="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name') }}" placeholder="Enter product name" required>
                                    </div>
                                    @error('name')<div class="invalid-feedback"><span class="material-icons-outlined">error</span>{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Barcode --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><span class="material-icons-outlined">qr_code_scanner</span>Barcode</label>
                                    <div class="icon-input">
                                        <span class="material-icons-outlined">tag</span>
                                        <input type="text" name="barcode"
                                               class="form-control @error('barcode') is-invalid @enderror"
                                               value="{{ old('barcode') }}" placeholder="Enter barcode">
                                    </div>
                                    @error('barcode')<div class="invalid-feedback"><span class="material-icons-outlined">error</span>{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Brand --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><span class="material-icons-outlined">branding_watermark</span>Brand</label>
                                    <div class="icon-input">
                                        <span class="material-icons-outlined">corporate_fare</span>
                                        <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                                            <option value="">Select Brand</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected':'' }}>{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('brand_id')<div class="invalid-feedback"><span class="material-icons-outlined">error</span>{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Category --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><span class="material-icons-outlined">category</span>Category</label>
                                    <div class="icon-input">
                                        <span class="material-icons-outlined">layers</span>
                                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected':'' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('category_id')<div class="invalid-feedback"><span class="material-icons-outlined">error</span>{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label required"><span class="material-icons-outlined">toggle_on</span>Status</label>
                                    <div class="icon-input">
                                        <span class="material-icons-outlined">power_settings_new</span>
                                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="active"   {{ old('status','active') == 'active'   ? 'selected':'' }}>Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected':'' }}>Inactive</option>
                                        </select>
                                    </div>
                                    @error('status')<div class="invalid-feedback"><span class="material-icons-outlined">error</span>{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label d-flex justify-content-between align-items-center">
                                        <span class="d-flex align-items-center gap-1"><span class="material-icons-outlined">description</span>Description</span>
                                        <span class="char-ctr" id="descCtr">0 / 2000</span>
                                    </label>
                                    <div class="icon-input ta">
                                        <span class="material-icons-outlined">notes</span>
                                        <textarea name="description" id="descTa"
                                                  class="form-control @error('description') is-invalid @enderror"
                                                  rows="5" maxlength="2000"
                                                  placeholder="Enter detailed product description…">{{ old('description') }}</textarea>
                                    </div>
                                    @error('description')<div class="invalid-feedback"><span class="material-icons-outlined">error</span>{{ $message }}</div>@enderror
                                </div>
                            </div>

                            {{-- Features --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label d-flex justify-content-between align-items-center">
                                        <span class="d-flex align-items-center gap-1"><span class="material-icons-outlined">star</span>Key Features</span>
                                        <span class="char-ctr" id="featCtr">0 / 2000</span>
                                    </label>
                                    <div class="icon-input ta">
                                        <span class="material-icons-outlined">format_list_bulleted</span>
                                        <textarea name="features" id="featTa"
                                                  class="form-control @error('features') is-invalid @enderror"
                                                  rows="4" maxlength="2000"
                                                  placeholder="Separate features with commas…">{{ old('features') }}</textarea>
                                    </div>
                                    <div class="info-txt"><span class="material-icons-outlined">tips_and_updates</span>Separate features with commas</div>
                                    @error('features')<div class="invalid-feedback"><span class="material-icons-outlined">error</span>{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Product Media ── --}}
                    <div class="card-section">
                        <div class="sec-hdr">
                            <div class="sec-icon"><span class="material-icons-outlined">image</span></div>
                            <div><h5>Product Media</h5><p>JPG · PNG · GIF · WEBP — max 2 MB</p></div>
                        </div>

                        <div class="upload-zone" id="uploadZone">
                            <span class="material-icons-outlined u-icon">cloud_upload</span>
                            <h6 class="fw-bold mb-1">Drag & Drop Image Here</h6>
                            <p class="text-muted small mb-0">or click to browse</p>
                            <input type="file" name="image" id="imageInput" class="d-none"
                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                        </div>

                        <div id="previewBox"></div>

                        @error('image')
                        <div class="invalid-feedback d-flex mt-2">
                            <span class="material-icons-outlined me-1">error</span>{{ $message }}
                        </div>
                        @enderror
                    </div>

                </div>{{-- /col-lg-8 --}}

                {{-- ╔══════════════════ RIGHT ══════════════════╗ --}}
                <div class="col-lg-4">

                    {{-- ── Pricing & Inventory ── --}}
                    <div class="card-section">
                        <div class="sec-hdr">
                            <div class="sec-icon"><span class="material-icons-outlined">payments</span></div>
                            <div><h5>Pricing & Inventory</h5><p>Set prices and stock levels</p></div>
                        </div>

                        {{-- Cost Price --}}
                        <div class="form-group">
                            <label class="form-label required"><span class="material-icons-outlined">attach_money</span>Cost Price (Rs.)</label>
                            <div class="icon-input">
                                <span class="material-icons-outlined">price_change</span>
                                <input type="number" name="cost_price" id="iCost"
                                       class="form-control @error('cost_price') is-invalid @enderror"
                                       step="0.01" min="0" value="{{ old('cost_price','') }}" placeholder="0.00" required>
                            </div>
                            @error('cost_price')<div class="invalid-feedback"><span class="material-icons-outlined">error</span>{{ $message }}</div>@enderror
                        </div>

                        {{-- Selling Price --}}
                        <div class="form-group">
                            <label class="form-label required"><span class="material-icons-outlined">price_check</span>Selling Price (Rs.)</label>
                            <div class="icon-input">
                                <span class="material-icons-outlined">sell</span>
                                <input type="number" name="selling_price" id="iSell"
                                       class="form-control @error('selling_price') is-invalid @enderror"
                                       step="0.01" min="0" value="{{ old('selling_price','') }}" placeholder="0.00" required>
                            </div>
                            @error('selling_price')<div class="invalid-feedback"><span class="material-icons-outlined">error</span>{{ $message }}</div>@enderror
                        </div>

                        {{-- Discount --}}
                        <div class="form-group">
                            <label class="form-label"><span class="material-icons-outlined">percent</span>Discount (%)</label>
                            <div class="icon-input">
                                <span class="material-icons-outlined">discount</span>
                                <input type="number" name="discount" id="iDisc"
                                       class="form-control @error('discount') is-invalid @enderror"
                                       step="0.01" min="0" max="100" value="{{ old('discount',0) }}" placeholder="0">
                            </div>
                            @error('discount')<div class="invalid-feedback"><span class="material-icons-outlined">error</span>{{ $message }}</div>@enderror
                        </div>

                        {{-- Packaging Cost --}}
                        <div class="form-group">
                            <label class="form-label"><span class="material-icons-outlined">inventory</span>Packaging Cost (Rs.)</label>
                            <div class="icon-input">
                                <span class="material-icons-outlined">package</span>
                                <input type="number" name="packaging_cost" id="iPack"
                                       class="form-control @error('packaging_cost') is-invalid @enderror"
                                       step="0.01" min="0" value="{{ old('packaging_cost',0) }}" placeholder="0.00">
                            </div>
                            @error('packaging_cost')<div class="invalid-feedback"><span class="material-icons-outlined">error</span>{{ $message }}</div>@enderror
                        </div>

                        {{-- Stock Qty --}}
                        <div class="form-group">
                            <label class="form-label required"><span class="material-icons-outlined">inventory_2</span>Stock Quantity</label>
                            <div class="icon-input">
                                <span class="material-icons-outlined">warehouse</span>
                                <input type="number" name="qty" id="iQty"
                                       class="form-control @error('qty') is-invalid @enderror"
                                       min="0" value="{{ old('qty','') }}" placeholder="0" required>
                            </div>
                            @error('qty')<div class="invalid-feedback"><span class="material-icons-outlined">error</span>{{ $message }}</div>@enderror
                        </div>

                        {{-- Min Stock Level --}}
                        <div class="form-group">
                            <label class="form-label required"><span class="material-icons-outlined">warning</span>Minimum Stock Level</label>
                            <div class="icon-input">
                                <span class="material-icons-outlined">notification_important</span>
                                <input type="number" name="min_stock_level"
                                       class="form-control @error('min_stock_level') is-invalid @enderror"
                                       min="0" value="{{ old('min_stock_level',0) }}" placeholder="0" required>
                            </div>
                            <div class="info-txt"><span class="material-icons-outlined">notifications</span>Alert level — set 0 to disable</div>
                            @error('min_stock_level')<div class="invalid-feedback"><span class="material-icons-outlined">error</span>{{ $message }}</div>@enderror
                        </div>

                        {{-- Price Calculator --}}
                        <div class="price-calc">
                            <h6 class="mb-3 d-flex align-items-center gap-2">
                                <span class="material-icons-outlined" style="color:var(--primary)">calculate</span>
                                Smart Price Summary
                            </h6>
                            <div class="pc-row"><span class="pc-label"><span class="material-icons-outlined">summarize</span>Total Cost</span><span class="pc-val" id="p-cost">Rs. 0.00</span></div>
                            <div class="pc-row"><span class="pc-label"><span class="material-icons-outlined">sell</span>Selling Price</span><span class="pc-val" id="p-sell">Rs. 0.00</span></div>
                            <div class="pc-row"><span class="pc-label"><span class="material-icons-outlined">percent</span>After Discount</span><span class="pc-val" id="p-final" style="color:var(--success)">Rs. 0.00</span></div>
                            <div class="pc-row"><span class="pc-label"><span class="material-icons-outlined">trending_up</span>Profit</span><span class="pc-val" id="p-profit">Rs. 0.00</span></div>
                            <div class="pc-row" style="border-top:1px solid var(--gray-200);margin-top:.2rem;padding-top:.4rem">
                                <span class="pc-label"><span class="material-icons-outlined">analytics</span>Profit Margin</span>
                                <span class="pc-val fw-bold" id="p-margin">0.00%</span>
                            </div>
                            <div class="pc-row"><span class="pc-label"><span class="material-icons-outlined">inventory_2</span>Stock Value</span><span class="pc-val" id="p-stockval">Rs. 0.00</span></div>
                        </div>
                    </div>

                    {{-- ── Submit Card ── --}}
                    <div class="card-section">
                        <div class="sec-hdr">
                            <div class="sec-icon"><span class="material-icons-outlined">rocket_launch</span></div>
                            <div><h5>Ready to Launch</h5><p>Review and create your product</p></div>
                        </div>
                        <div class="d-grid gap-3">
                            {{-- NOT type="submit" — JS will call form.submit() AFTER Swal confirm --}}
                            <button type="button" id="confirmBtn" class="btn-xl btn-main w-100">
                                <span class="material-icons-outlined">rocket_launch</span> Create Product
                            </button>
                            <a href="{{ route('wholesaler.products.index') }}" class="btn-xl btn-cancel w-100 text-decoration-none text-center">
                                <span class="material-icons-outlined">cancel</span> Cancel
                            </a>
                        </div>
                    </div>

                </div>{{-- /col-lg-4 --}}
            </div>
        </form>
    </div>

    {{-- Scroll to top --}}
    <button onclick="window.scrollTo({top:0,behavior:'smooth'})"
            style="position:fixed;bottom:2rem;right:2rem;width:50px;height:50px;border-radius:50%;border:none;background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;box-shadow:0 4px 15px rgba(79,70,229,.4);cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:999;transition:.2s"
            title="Scroll to top">
        <span class="material-icons-outlined">vertical_align_top</span>
    </button>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ── refs ──
            const form       = document.getElementById('productForm');
            const confirmBtn = document.getElementById('confirmBtn');
            const uploadZone = document.getElementById('uploadZone');
            const imageInput = document.getElementById('imageInput');
            const previewBox = document.getElementById('previewBox');
            const loading    = document.getElementById('loadingOverlay');

            const iCost  = document.getElementById('iCost');
            const iSell  = document.getElementById('iSell');
            const iDisc  = document.getElementById('iDisc');
            const iPack  = document.getElementById('iPack');
            const iQty   = document.getElementById('iQty');

            const descTa = document.getElementById('descTa');
            const featTa = document.getElementById('featTa');
            const descCtr= document.getElementById('descCtr');
            const featCtr= document.getElementById('featCtr');

            // ── character counters ──
            function counter(ta, el) {
                if (!ta || !el) return;
                const update = () => {
                    const n = ta.value.length;
                    el.textContent = `${n} / 2000`;
                    el.className = n >= 2000 ? 'char-ctr bad' : n > 1800 ? 'char-ctr warn' : 'char-ctr';
                };
                update();
                ta.addEventListener('input', update);
            }
            counter(descTa, descCtr);
            counter(featTa, featCtr);

            // ── image upload ──
            uploadZone.addEventListener('click', () => imageInput.click());
            uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.classList.add('over'); });
            uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('over'));
            uploadZone.addEventListener('drop', e => {
                e.preventDefault(); uploadZone.classList.remove('over');
                const file = e.dataTransfer.files[0];
                if (file) { assignFile(file); }
            });
            imageInput.addEventListener('change', () => {
                if (imageInput.files[0]) showPreview(imageInput.files[0]);
            });

            function assignFile(file) {
                // Transfer dropped file to the actual file input so the form POSTs it
                try {
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    imageInput.files = dt.files;
                } catch(e) {
                    // Fallback: DataTransfer not supported — just show preview; user must re-select
                }
                showPreview(file);
            }

            function showPreview(file) {
                const ok = ['image/jpeg','image/png','image/jpg','image/gif','image/webp'];
                if (!ok.includes(file.type)) { alert('Please upload JPG, PNG, GIF or WEBP only.'); imageInput.value=''; return; }
                if (file.size > 2*1024*1024) { alert('Image must be smaller than 2 MB.'); imageInput.value=''; return; }
                const reader = new FileReader();
                reader.onload = e => {
                    previewBox.innerHTML = `
                <div class="preview-wrap">
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="preview-remove" onclick="clearImg()" title="Remove">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>`;
                };
                reader.readAsDataURL(file);
            }

            window.clearImg = function() {
                imageInput.value = '';
                previewBox.innerHTML = '';
            };

            // ── price calculator ──
            function calcPrice() {
                const cost   = parseFloat(iCost?.value)  || 0;
                const sell   = parseFloat(iSell?.value)  || 0;
                const disc   = parseFloat(iDisc?.value)  || 0;
                const pack   = parseFloat(iPack?.value)  || 0;
                const qty    = parseInt(iQty?.value)     || 0;

                const totalCost  = cost + pack;
                const finalPrice = sell * (1 - disc / 100);
                const profit     = finalPrice - totalCost;
                const margin     = totalCost > 0 ? (profit / totalCost) * 100 : 0;
                const stockVal   = cost * qty;

                const rs = v => `Rs. ${v.toLocaleString('en-IN',{minimumFractionDigits:2,maximumFractionDigits:2})}`;

                document.getElementById('p-cost').textContent    = rs(totalCost);
                document.getElementById('p-sell').textContent    = rs(sell);
                document.getElementById('p-final').textContent   = rs(finalPrice);
                document.getElementById('p-profit').textContent  = rs(profit);
                document.getElementById('p-margin').textContent  = `${margin.toFixed(2)}%`;
                document.getElementById('p-stockval').textContent= rs(stockVal);

                const col = margin > 30 ? 'var(--success)' : margin > 10 ? 'var(--warning)' : margin > 0 ? 'var(--gray-500)' : 'var(--danger)';
                document.getElementById('p-profit').style.color = col;
                document.getElementById('p-margin').style.color = col;
            }
            [iCost, iSell, iDisc, iPack, iQty].forEach(el => el?.addEventListener('input', calcPrice));
            calcPrice();

            // ── client-side validation ──
            function validate() {
                let ok = true; const errs = [];
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                form.querySelectorAll('[required]').forEach(f => {
                    if (!f.value.toString().trim()) {
                        f.classList.add('is-invalid'); ok = false;
                        errs.push(f.getAttribute('placeholder') || f.name || 'Required field missing');
                    }
                });

                if (!imageInput.files.length && !previewBox.querySelector('img')) {
                    uploadZone.style.borderColor = 'var(--danger)'; ok = false;
                    errs.push('Product image is required');
                } else { uploadZone.style.borderColor = ''; }

                const cost = parseFloat(iCost?.value)||0, sell = parseFloat(iSell?.value)||0;
                if (sell > 0 && sell < cost) {
                    iSell?.classList.add('is-invalid'); ok = false;
                    errs.push('Selling price cannot be lower than cost price');
                }

                return { ok, errs };
            }

            // ── confirm → native submit ──
            confirmBtn.addEventListener('click', function () {
                const { ok, errs } = validate();

                if (!ok) {
                    Swal.fire({
                        icon: 'warning', title: 'Fix these errors first',
                        html: `<ul class="text-start mb-0 ps-3">${errs.map(e=>`<li>${e}</li>`).join('')}</ul>`,
                        confirmButtonColor: '#4f46e5'
                    });
                    form.querySelector('.is-invalid')?.scrollIntoView({ behavior:'smooth', block:'center' });
                    return;
                }

                const nameVal  = form.querySelector('input[name="name"]')?.value || 'N/A';
                const sellVal  = parseFloat(iSell?.value||0).toFixed(2);
                const qtyVal   = iQty?.value || '0';

                Swal.fire({
                    icon: 'question', title: 'Create this product?',
                    html: `
                <div class="text-start">
                    <div class="bg-light rounded p-3 mt-2">
                        <p class="mb-1"><strong>Name:</strong> ${nameVal}</p>
                        <p class="mb-1"><strong>Selling Price:</strong> Rs. ${sellVal}</p>
                        <p class="mb-0"><strong>Stock Qty:</strong> ${qtyVal}</p>
                    </div>
                </div>`,
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, create it',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then(result => {
                    if (result.isConfirmed) {
                        // Show loading spinner then do a plain native form POST — no AJAX, no JSON parsing
                        loading.style.display = 'flex';
                        form.submit();
                    }
                });
            });

            // Ctrl+S shortcut
            document.addEventListener('keydown', e => {
                if (e.ctrlKey && e.key === 's') { e.preventDefault(); confirmBtn.click(); }
            });

            // Auto-dismiss alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(a => a.classList.remove('show'));
            }, 5000);
        });
    </script>
@endpush
