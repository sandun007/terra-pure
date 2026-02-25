@extends('seller.layout.app')

@push('title')
    Upload CSV Leads
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --primary   : #6366f1; --primary-d : #4f46e5; --primary-l : #eef2ff;
            --success   : #10b981; --success-l : #d1fae5;
            --danger    : #ef4444; --danger-l  : #fee2e2;
            --warning   : #f59e0b; --warning-l : #fef3c7;
            --gray      : #64748b; --info      : #3b82f6;
            --dark      : #0f172a; --mid       : #475569; --muted     : #94a3b8;
            --border    : #e2e8f0; --surface   : #ffffff; --surface2  : #f8fafc;
            --r-xl:16px; --r-lg:12px; --r-md:8px; --r-sm:6px;
            --shadow    : 0 4px 20px rgba(0,0,0,.07);
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        .csv-page{padding:28px 36px}
        @media(max-width:768px){.csv-page{padding:14px}}

        .ph{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;flex-wrap:wrap;gap:12px}
        .ph h1{font-size:21px;font-weight:800;color:var(--dark);display:flex;align-items:center;gap:10px}
        .ph-icon{width:38px;height:38px;border-radius:var(--r-lg);background:linear-gradient(135deg,#fbbf24,#f59e0b);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(245,158,11,.3);flex-shrink:0}
        .ph p{font-size:13px;color:var(--mid);margin-top:3px}

        .info-alert{display:flex;gap:12px;padding:13px 17px;background:rgba(59,130,246,.08);border-left:4px solid var(--info);border-radius:var(--r-lg);margin-bottom:18px;font-size:13px;font-weight:500;color:#1e40af;align-items:flex-start}

        .session-banner{display:none;align-items:center;justify-content:space-between;gap:12px;padding:14px 18px;background:#f0fdf4;border:1.5px solid #86efac;border-radius:var(--r-lg);margin-bottom:18px;flex-wrap:wrap}
        .session-banner.show{display:flex}
        .sb-left{display:flex;align-items:center;gap:12px}
        .sb-left i{color:var(--success);font-size:22px;flex-shrink:0}
        .sb-title{font-size:13px;font-weight:700;color:#14532d}
        .sb-sub{font-size:11px;color:#166534;margin-top:3px}
        .sb-actions{display:flex;gap:8px;flex-wrap:wrap}
        .btn-restore{padding:8px 16px;background:var(--success);color:#fff;border:none;border-radius:var(--r-md);font-size:13px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:background .2s}
        .btn-restore:hover{background:#059669}
        .btn-discard-s{padding:8px 16px;background:transparent;color:var(--danger);border:1.5px solid #fca5a5;border-radius:var(--r-md);font-size:13px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:all .2s}
        .btn-discard-s:hover{background:var(--danger-l)}

        .card{background:var(--surface);border:1px solid var(--border);border-radius:var(--r-xl);box-shadow:var(--shadow);margin-bottom:18px;overflow:hidden}
        .card-h{display:flex;align-items:center;gap:10px;padding:16px 22px;border-bottom:1px solid var(--border);background:var(--surface2)}
        .card-h h2{font-size:14px;font-weight:700;color:var(--dark)}
        .ci{width:30px;height:30px;border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;font-size:14px}
        .ci-y{background:var(--warning-l);color:var(--warning)}
        .ci-g{background:var(--success-l);color:var(--success)}
        .card-b{padding:22px}

        .dz{border:2.5px dashed var(--border);border-radius:var(--r-xl);padding:46px 24px;text-align:center;cursor:pointer;background:var(--surface2);transition:all .25s}
        .dz:hover,.dz.over{border-color:var(--primary);background:var(--primary-l)}
        .dz-ring{width:64px;height:64px;border-radius:50%;background:var(--surface);border:2px solid var(--border);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;transition:all .25s}
        .dz:hover .dz-ring,.dz.over .dz-ring{border-color:var(--primary);background:var(--primary-l);transform:scale(1.06)}
        .dz-ring i{font-size:28px;color:var(--muted);transition:color .25s}
        .dz:hover .dz-ring i,.dz.over .dz-ring i{color:var(--primary)}
        .dz-t{font-size:15px;font-weight:700;color:var(--dark);margin-bottom:5px}
        .dz-s{font-size:12px;color:var(--mid)}
        .dz-cta{display:inline-flex;align-items:center;gap:6px;margin-top:14px;padding:8px 18px;background:var(--primary);color:#fff;border-radius:var(--r-md);font-size:13px;font-weight:700}
        #fileInput{display:none}

        .file-bar{display:none;align-items:center;gap:14px;background:linear-gradient(135deg,var(--success),#059669);color:#fff;padding:12px 16px;border-radius:var(--r-lg);margin-top:14px}
        .file-bar.show{display:flex}
        .fb-ic{width:36px;height:36px;background:rgba(255,255,255,.2);border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .fb-meta{flex:1;min-width:0}
        .fb-name{font-weight:700;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .fb-size{font-size:11px;opacity:.8;margin-top:2px}
        .fb-rm{background:rgba(255,255,255,.2);border:none;color:#fff;padding:6px 12px;border-radius:var(--r-sm);font-size:12px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:4px}
        .fb-rm:hover{background:rgba(255,255,255,.3)}

        .product-selector{background:linear-gradient(135deg,var(--primary-l),#fff);border:1px solid #c7d2fe;border-radius:var(--r-lg);padding:20px;margin-bottom:20px}
        .product-selector-title{font-size:14px;font-weight:700;color:var(--dark);margin-bottom:15px;display:flex;align-items:center;gap:8px}
        .product-selector-title i{color:var(--primary)}
        .product-selector-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:15px;margin-bottom:15px}
        .product-card{background:white;border:2px solid var(--border);border-radius:var(--r-lg);padding:15px;cursor:pointer;transition:all .2s}
        .product-card:hover{border-color:var(--primary);transform:translateY(-2px);box-shadow:0 4px 12px rgba(99,102,241,.15)}
        .product-card.selected{border-color:var(--success);background:var(--success-l)}
        .product-card-code{font-size:14px;font-weight:700;color:var(--primary-d);margin-bottom:5px}
        .product-card-name{font-size:12px;color:var(--mid);margin-bottom:8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .product-card-meta{display:flex;justify-content:space-between;font-size:11px;color:var(--gray)}
        .product-card-price{font-weight:600;color:var(--success)}
        .product-card-stock{font-weight:600;color:var(--info)}
        .selected-product-info{background:var(--success-l);border:1px solid var(--success);border-radius:var(--r-lg);padding:15px;margin-top:15px;display:flex;align-items:center;gap:15px}
        .selected-product-icon{width:40px;height:40px;border-radius:50%;background:var(--success);color:white;display:flex;align-items:center;justify-content:center}
        .selected-product-details{flex:1}
        .selected-product-code{font-size:16px;font-weight:700;color:#065f46;margin-bottom:3px}
        .selected-product-meta{font-size:12px;color:var(--mid)}
        .loading-products{text-align:center;padding:30px;color:var(--mid)}
        .no-products-msg{text-align:center;padding:20px;color:var(--danger);background:var(--danger-l);border-radius:var(--r-lg);font-size:13px;font-weight:600}

        .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px}
        @media(max-width:768px){.stats-grid{grid-template-columns:repeat(2,1fr)}}
        .stat-box{background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-lg);padding:13px;position:relative;overflow:hidden}
        .stat-box::after{content:'';position:absolute;bottom:0;left:0;right:0;height:3px}
        .stat-box:nth-child(1)::after{background:var(--primary)}
        .stat-box:nth-child(2)::after{background:var(--success)}
        .stat-box:nth-child(3)::after{background:var(--danger)}
        .stat-box:nth-child(4)::after{background:var(--warning)}
        .stat-lbl{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:5px}
        .stat-val{font-size:24px;font-weight:800;color:var(--dark)}
        .sv-g{color:var(--success)}.sv-r{color:var(--danger)}.sv-b{color:var(--primary)}

        .cols-box{background:var(--primary-l);border:1px solid #c7d2fe;border-radius:var(--r-lg);padding:11px 15px;margin-bottom:14px;font-size:13px}
        .cols-box strong{color:var(--primary)}
        .cols-tags{display:flex;flex-wrap:wrap;gap:5px;margin-top:7px}
        .col-tag{padding:2px 8px;background:white;border:1px solid #c7d2fe;border-radius:999px;font-size:11px;font-weight:600;color:var(--primary)}

        .tbl-toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;flex-wrap:wrap;gap:8px}
        .search-wrap{position:relative}
        .search-wrap i{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:13px;pointer-events:none}
        .search-inp{padding:7px 12px 7px 30px;border:1.5px solid var(--border);border-radius:var(--r-md);font-size:13px;width:220px;transition:all .2s}
        .search-inp:focus{outline:none;border-color:var(--primary)}
        .per-page-sel{padding:6px 10px;border:1.5px solid var(--border);border-radius:var(--r-md);font-size:13px;cursor:pointer}

        .tbl-wrap{overflow-x:auto;border:1px solid var(--border);border-radius:var(--r-lg)}
        .preview-tbl{width:100%;border-collapse:collapse;min-width:1100px}
        .preview-tbl thead tr{background:var(--dark)}
        .preview-tbl th{padding:10px 11px;text-align:left;font-size:11px;font-weight:700;color:rgba(255,255,255,.85);white-space:nowrap;text-transform:uppercase;letter-spacing:.4px}
        .preview-tbl td{padding:9px 11px;border-bottom:1px solid var(--border);font-size:12px;color:var(--mid);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;vertical-align:middle}
        .preview-tbl tbody tr:hover td{background:var(--surface2)}
        .preview-tbl tbody tr:last-child td{border-bottom:none}
        .preview-tbl td.row-num{font-weight:700;color:var(--dark)}

        .badge-valid{display:inline-flex;align-items:center;gap:3px;padding:3px 8px;border-radius:999px;font-size:10px;font-weight:700}
        .bv-ok{background:var(--success-l);color:#065f46}
        .bv-bad{background:var(--danger-l);color:#7f1d1d}

        .status-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:999px;font-size:10px;font-weight:700;white-space:nowrap}
        .sb-p{background:var(--warning-l);color:#92400e}
        .sb-c{background:var(--success-l);color:#065f46}
        .sb-r{background:var(--danger-l);color:#7f1d1d}
        .sb-n{background:#f5f3ff;color:#3730a3}
        .sb-o{background:#f1f5f9;color:#334155}

        .ad-name-badge{background:var(--primary-l);color:var(--primary-d);padding:2px 6px;border-radius:var(--r-sm);font-size:10px;font-weight:600;display:inline-block;max-width:150px;overflow:hidden;text-overflow:ellipsis}

        .row-status-sel{width:118px;padding:4px 6px;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:11px;cursor:pointer;background:white;transition:all .2s}
        .row-status-sel:focus{outline:none;border-color:var(--primary)}
        .row-status-sel.saved{background:var(--success);color:white;border-color:var(--success)}

        .pag-row{display:flex;justify-content:space-between;align-items:center;padding:10px 2px;margin-top:6px;flex-wrap:wrap;gap:8px}
        .pag-info{font-size:12px;color:var(--mid)}
        .pag-btns{display:flex;gap:4px;flex-wrap:wrap}
        .pg-btn{padding:5px 10px;border:1px solid var(--border);border-radius:var(--r-sm);background:white;font-size:12px;cursor:pointer;transition:all .2s;min-width:32px;text-align:center}
        .pg-btn:hover:not(:disabled){border-color:var(--primary);color:var(--primary)}
        .pg-btn.active{background:var(--primary);color:white;border-color:var(--primary)}
        .pg-btn:disabled{opacity:.4;cursor:not-allowed}

        .prog-wrap{display:none;background:var(--surface2);border:1px solid var(--border);border-radius:var(--r-lg);padding:15px 20px;margin-bottom:16px}
        .prog-wrap.show{display:block}
        .prog-head{display:flex;justify-content:space-between;font-size:13px;font-weight:700;color:var(--dark);margin-bottom:10px}
        .prog-track{height:10px;background:var(--border);border-radius:999px;overflow:hidden}
        .prog-fill{height:100%;background:linear-gradient(90deg,var(--primary),var(--success));border-radius:999px;transition:width .3s ease;width:0%}

        .action-bar{display:flex;gap:12px;justify-content:flex-end;padding-top:18px;border-top:2px solid var(--border);flex-wrap:wrap}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:11px 22px;border-radius:var(--r-lg);font-size:14px;font-weight:700;border:none;cursor:pointer;transition:all .2s;text-decoration:none;font-family:inherit}
        .btn:disabled{opacity:.4;cursor:not-allowed;transform:none!important}
        .btn-dark{background:var(--dark);color:#fff}
        .btn-dark:hover{background:#1e293b;transform:translateY(-1px)}
        .btn-danger-o{background:transparent;color:var(--danger);border:1.5px solid #fca5a5}
        .btn-danger-o:not(:disabled):hover{background:var(--danger-l)}

        .status-filter-bar{display:flex;gap:8px;align-items:center;margin-bottom:15px;flex-wrap:wrap}
        .status-filter-btn{padding:6px 12px;border-radius:20px;font-size:11px;font-weight:600;cursor:pointer;border:1.5px solid transparent;transition:all .2s;background:white}
        .status-filter-btn.active{border-color:var(--primary);background:var(--primary-l);color:var(--primary-d)}
        .status-filter-btn.pending{color:#92400e;background:var(--warning-l)}
        .status-filter-btn.conform{color:#065f46;background:var(--success-l)}
        .status-filter-btn.reject{color:#7f1d1d;background:var(--danger-l)}
        .status-filter-btn.notanswer{color:#3730a3;background:#f5f3ff}
        .status-filter-btn.other{color:#334155;background:#f1f5f9}

        .table-upload-btn{background:var(--success);color:white;border:none;border-radius:var(--r-md);padding:8px 16px;font-size:13px;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:6px;transition:all .2s}
        .table-upload-btn:hover:not(:disabled){background:#059669;transform:translateY(-1px)}
        .table-upload-btn:disabled{opacity:.5;cursor:not-allowed}
        .upload-cell{text-align:center;min-width:100px}

        .token-status{display:none;align-items:center;gap:6px;font-size:11px;color:var(--success)}
        .token-status.show{display:flex}

        #previewSection{display:none}
        #previewSection.show{display:block}
    </style>
@endpush

@section('content')
    <div class="csv-page">

        <div class="ph">
            <div>
                <h1>
                    <div class="ph-icon"><i class="fas fa-file-csv" style="color:#fff;font-size:17px;"></i></div>
                    Upload CSV Leads
                </h1>
                <p>Import Facebook / Instagram Ads Manager leads — creates Call records in bulk</p>
            </div>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <span class="token-status" id="tokenStatus"><i class="fas fa-shield-alt"></i> Token refreshed</span>
                <a href="{{ route('call.index') }}" class="btn btn-dark" id="backBtn">
                    <i class="fas fa-arrow-left"></i> Back to Calls
                </a>
            </div>
        </div>

        <div class="session-banner" id="sessionBanner">
            <div class="sb-left">
                <i class="fas fa-history"></i>
                <div>
                    <div class="sb-title" id="sessionTitle">You have unsaved CSV data from your last visit</div>
                    <div class="sb-sub" id="sessionSub">Click Restore to continue where you left off</div>
                </div>
            </div>
            <div class="sb-actions">
                <button class="btn-restore" id="btnRestore"><i class="fas fa-undo"></i> Restore Data</button>
                <button class="btn-discard-s" id="btnDiscard"><i class="fas fa-trash"></i> Discard</button>
            </div>
        </div>

        <div class="info-alert">
            <i class="fas fa-info-circle" style="font-size:17px;flex-shrink:0;margin-top:1px;"></i>
            <div>Upload a CSV from Facebook / Instagram Ads Manager. Required columns:
                <strong>full name</strong> + <strong>phone</strong>. Select a product from your inventory below.
                Change status using the dropdown — it saves automatically. Click <strong>Upload Row</strong> to create a call.
            </div>
        </div>

        <!-- Step 1 -->
        <div class="card">
            <div class="card-h">
                <div class="ci ci-y"><i class="fas fa-cloud-upload-alt"></i></div>
                <h2>Step 1 — Select CSV File</h2>
            </div>
            <div class="card-b">
                <div class="dz" id="dropZone">
                    <div class="dz-ring"><i class="fas fa-file-csv"></i></div>
                    <div class="dz-t">Drop your CSV file here</div>
                    <div class="dz-s">Supports Facebook / Instagram lead export CSV files</div>
                    <div class="dz-cta"><i class="fas fa-folder-open"></i> Browse File</div>
                </div>
                <input type="file" id="fileInput" accept=".csv">
                <div class="file-bar" id="fileBar">
                    <div class="fb-ic"><i class="fas fa-file-alt" style="font-size:17px;"></i></div>
                    <div class="fb-meta">
                        <div class="fb-name" id="fileName">—</div>
                        <div class="fb-size" id="fileSize">—</div>
                    </div>
                    <button class="fb-rm" id="removeFileBtn"><i class="fas fa-times"></i> Remove</button>
                </div>
            </div>
        </div>

        <!-- Step 2: Product Selector -->
        <div class="product-selector">
            <div class="product-selector-title">
                <i class="fas fa-box"></i> Step 2 — Select Product
            </div>
            <div id="productLoader" class="loading-products">
                <i class="fas fa-spinner fa-spin"></i> Loading your products...
            </div>
            <div id="productGrid" class="product-selector-grid" style="display:none;"></div>
            <div id="noProductsMsg" class="no-products-msg" style="display:none;">
                <i class="fas fa-exclamation-triangle"></i>
                No active products found. Please add a product first.
            </div>
            <div id="selectedProductInfo" class="selected-product-info" style="display:none;">
                <div class="selected-product-icon"><i class="fas fa-check"></i></div>
                <div class="selected-product-details">
                    <div class="selected-product-code" id="selectedProductCode">-</div>
                    <div class="selected-product-meta">
                        <span id="selectedProductName">-</span> •
                        Price: Rs. <span id="selectedProductPrice">0</span> •
                        Stock: <span id="selectedProductStock">0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Preview table -->
        <div class="card" id="previewSection">
            <div class="card-h">
                <div class="ci ci-g"><i class="fas fa-table"></i></div>
                <h2>Step 3 — Preview & Upload Individual Leads</h2>
            </div>
            <div class="card-b">

                <div class="stats-grid">
                    <div class="stat-box"><div class="stat-lbl">Total Leads</div><div class="stat-val" id="stTotal">0</div></div>
                    <div class="stat-box"><div class="stat-lbl">Valid</div><div class="stat-val sv-g" id="stValid">0</div></div>
                    <div class="stat-box"><div class="stat-lbl">Invalid</div><div class="stat-val sv-r" id="stInvalid">0</div></div>
                    <div class="stat-box"><div class="stat-lbl">Success Rate</div><div class="stat-val sv-b" id="stRate">0%</div></div>
                </div>

                <div class="cols-box">
                    <strong><i class="fas fa-columns" style="margin-right:5px;"></i>CSV Columns Detected:</strong>
                    <div class="cols-tags" id="colTags"></div>
                </div>

                <div class="status-filter-bar">
                    <span style="font-size:12px;font-weight:600;color:var(--dark);">Filter by Status:</span>
                    <button class="status-filter-btn active" data-status="all">All</button>
                    <button class="status-filter-btn pending" data-status="Pending">Pending</button>
                    <button class="status-filter-btn conform" data-status="Conform">Confirm</button>
                    <button class="status-filter-btn reject" data-status="Reject">Reject</button>
                    <button class="status-filter-btn notanswer" data-status="Not Answer">Not Answer</button>
                    <button class="status-filter-btn other" data-status="Other">Other</button>
                </div>

                <div class="tbl-toolbar">
                    <div class="search-wrap">
                        <i class="fas fa-search"></i>
                        <input type="text" id="tblSearch" class="search-inp" placeholder="Search name / phone / city / ad name…">
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:12px;color:var(--mid);">Rows per page:</span>
                        <select id="perPage" class="per-page-sel">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="0">All</option>
                        </select>
                    </div>
                </div>

                <div class="tbl-wrap">
                    <table class="preview-tbl">
                        <thead>
                        <tr>
                            <th>#</th><th>Valid</th><th>Customer Name</th><th>Phone</th>
                            <th>Address</th><th>City</th><th>Platform</th><th>Ad Name</th>
                            <th>Campaign</th><th>Status</th><th>Change Status</th><th>Upload</th>
                        </tr>
                        </thead>
                        <tbody id="tblBody"></tbody>
                    </table>
                </div>

                <div class="pag-row">
                    <div class="pag-info" id="pagInfo">—</div>
                    <div class="pag-btns" id="pagBtns"></div>
                </div>
            </div>
        </div>

        <div class="prog-wrap" id="progWrap">
            <div class="prog-head">
                <span id="progTxt">Uploading…</span>
                <span id="progPct">0%</span>
            </div>
            <div class="prog-track"><div class="prog-fill" id="progFill"></div></div>
        </div>

        <div class="action-bar">
            <button class="btn btn-danger-o" id="clearBtn" disabled>
                <i class="fas fa-trash-alt"></i> Clear All Data
            </button>
        </div>

    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>
    <script>
        (function () {
            'use strict';

            /* ══════════════════════════════════════════════════
             *  CSRF TOKEN HELPERS
             * ══════════════════════════════════════════════════ */
            function getCsrfToken() {
                const m = document.querySelector('meta[name="csrf-token"]');
                return (m && m.getAttribute('content')) || '{{ csrf_token() }}';
            }
            function setCsrfToken(t) {
                if (!t) return;
                const m = document.querySelector('meta[name="csrf-token"]');
                if (m) m.setAttribute('content', t);
            }
            async function refreshCsrfToken() {
                try {
                    const r = await fetch('{{ route("call.csrf_refresh") }}', {
                        credentials: 'same-origin',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (r.ok) {
                        const d = await r.json();
                        if (d.token) {
                            setCsrfToken(d.token);
                            const el = $('tokenStatus');
                            if (el) { el.classList.add('show'); clearTimeout(el._t); el._t = setTimeout(() => el.classList.remove('show'), 2000); }
                        }
                    }
                } catch (e) { /* silent */ }
            }

            /* ══════════════════════════════════════════════════
             *  STATE
             * ══════════════════════════════════════════════════ */
            const SESS = 'csv_calls_v8';
            let leads = [], filtered = [], csvCols = [];
            let page = 1, perPage = 25, allowNav = false, currentStatusFilter = 'all';
            let selectedProduct = null, products = [];

            const STATUS_META = {
                p: { badge:'sb-p', icon:'fa-clock',        route:'{{ route("call.index") }}' },
                c: { badge:'sb-c', icon:'fa-check-circle', route:'{{ route("call.conform") }}' },
                r: { badge:'sb-r', icon:'fa-times-circle', route:'{{ route("call.reject") }}' },
                n: { badge:'sb-n', icon:'fa-phone-slash',  route:'{{ route("call.not_answer_call_list") }}' },
                o: { badge:'sb-o', icon:'fa-ellipsis-h',   route:'{{ route("call.other_call_list") }}' },
            };
            const STATUS_ROUTES = {
                'Pending'    : '{{ route("call.index") }}',
                'Conform'    : '{{ route("call.conform") }}',
                'Reject'     : '{{ route("call.reject") }}',
                'Not Answer' : '{{ route("call.not_answer_call_list") }}',
                'Other'      : '{{ route("call.other_call_list") }}',
            };

            function getStatusKey(v) {
                v = (v||'').toLowerCase().replace(/\s/g,'');
                if (v==='conform'||v==='confirm') return 'c';
                if (v==='reject') return 'r';
                if (v==='notanswer') return 'n';
                if (v==='other') return 'o';
                return 'p';
            }
            function $(id) { return document.getElementById(id); }
            function esc(s) {
                return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
            }

            /* ══════════════════════════════════════════════════
             *  PRODUCT LOADING  — uses dedicated /call/seller-products endpoint
             * ══════════════════════════════════════════════════ */
            async function loadProducts() {
                $('productLoader').style.display = 'block';
                $('productGrid').style.display   = 'none';
                $('noProductsMsg').style.display = 'none';

                try {
                    const res  = await fetch('{{ route("call.seller_products") }}', {
                        credentials: 'same-origin',
                        headers: { 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' }
                    });
                    const data = await res.json();

                    if (data.success && Array.isArray(data.products) && data.products.length) {
                        products = data.products;
                        renderProductGrid();
                    } else {
                        showNoProducts(data.message || 'No products found.');
                    }
                } catch (err) {
                    console.error('Product load error:', err);
                    showNoProducts('Failed to load products. Please refresh.');
                }
            }

            function showNoProducts(msg) {
                $('productLoader').style.display = 'none';
                $('productGrid').style.display   = 'none';
                $('noProductsMsg').style.display = 'block';
                $('noProductsMsg').innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + esc(msg);
            }

            function renderProductGrid() {
                $('productLoader').style.display = 'none';
                const grid = $('productGrid');
                grid.style.display = 'grid';

                grid.innerHTML = products.map(p => `
            <div class="product-card"
                 data-id="${p.id}"
                 data-code="${esc(p.code)}"
                 data-name="${esc(p.name)}"
                 data-price="${p.display_price}"
                 data-stock="${p.qty}">
                <div class="product-card-code">${esc(p.code)}</div>
                <div class="product-card-name" title="${esc(p.name)}">${esc(p.name)}</div>
                <div class="product-card-meta">
                    <span class="product-card-price">Rs. ${p.display_price}</span>
                    <span class="product-card-stock">Stock: ${p.qty}</span>
                </div>
            </div>`).join('');

                grid.querySelectorAll('.product-card').forEach(card => {
                    card.addEventListener('click', function() {
                        grid.querySelectorAll('.product-card').forEach(c => c.classList.remove('selected'));
                        this.classList.add('selected');
                        selectedProduct = {
                            id   : this.dataset.id,
                            code : this.dataset.code,
                            name : this.dataset.name,
                            price: this.dataset.price,
                            stock: this.dataset.stock,
                        };
                        showSelectedProduct(selectedProduct);
                        saveSession();
                    });
                });

                // Restore previously selected OR auto-select first
                if (selectedProduct) {
                    const card = grid.querySelector(`[data-code="${CSS.escape(selectedProduct.code)}"]`);
                    if (card) { card.classList.add('selected'); showSelectedProduct(selectedProduct); }
                    else { autoSelectFirst(grid); }
                } else {
                    autoSelectFirst(grid);
                }
            }

            function autoSelectFirst(grid) {
                const first = grid.querySelector('.product-card');
                if (first) first.click();
            }

            function showSelectedProduct(p) {
                $('selectedProductCode').textContent  = p.code;
                $('selectedProductName').textContent  = p.name;
                $('selectedProductPrice').textContent = p.price;
                $('selectedProductStock').textContent = p.stock;
                $('selectedProductInfo').style.display = 'flex';
            }

            /* ══════════════════════════════════════════════════
             *  SESSION
             * ══════════════════════════════════════════════════ */
            function saveSession() {
                if (!leads.length) return;
                try {
                    sessionStorage.setItem(SESS, JSON.stringify({
                        leads, csvCols, selectedProduct,
                        fileName: $('fileName').textContent,
                        fileSize: $('fileSize').textContent,
                        ts: Date.now()
                    }));
                } catch(e){}
            }
            function loadSession() { try { return JSON.parse(sessionStorage.getItem(SESS)); } catch(e){ return null; } }
            function clearSession() { sessionStorage.removeItem(SESS); }

            function checkRestore() {
                const saved = loadSession();
                if (!saved || !saved.leads || !saved.leads.length) return;
                const mins    = Math.round((Date.now()-(saved.ts||0))/60000);
                const timeAgo = mins<1?'just now':mins<60?mins+'m ago':Math.round(mins/60)+'h ago';
                const v       = saved.leads.filter(l=>l.valid).length;
                $('sessionTitle').textContent = saved.leads.length+' leads ('+v+' valid) saved '+timeAgo;
                $('sessionSub').textContent   = 'File: '+(saved.fileName||'—');
                $('sessionBanner').classList.add('show');
                $('btnRestore').onclick = () => doRestore(saved);
                $('btnDiscard').onclick = () => {
                    Swal.fire({ title:'Discard saved data?', text:'Your saved session will be permanently removed.',
                        icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444',
                        confirmButtonText:'<i class="fas fa-trash"></i> Yes, Discard' })
                        .then(r => { if(r.isConfirmed){ clearSession(); $('sessionBanner').classList.remove('show'); } });
                };
            }

            function doRestore(saved) {
                leads=saved.leads||[]; csvCols=saved.csvCols||[]; selectedProduct=saved.selectedProduct||null;
                leads.forEach(l=>{ l.valid=!!(l.name&&l.phone); });
                $('fileName').textContent=saved.fileName||'—'; $('fileSize').textContent=saved.fileSize||'—';
                $('fileBar').classList.add('show');
                page=1; doFilter(); renderStats(); renderCols();
                $('previewSection').classList.add('show'); $('clearBtn').disabled=false;
                $('sessionBanner').classList.remove('show');
                Swal.fire({ icon:'success', title:'Session Restored!',
                    html:'<p>📊 <b>'+leads.length+'</b> leads restored.</p>',
                    confirmButtonColor:'#6366f1', confirmButtonText:'Continue ›' });
            }

            /* ══════════════════════════════════════════════════
             *  LEAVE GUARD + AUTO-SAVE
             * ══════════════════════════════════════════════════ */
            function initLeaveGuard() {
                document.addEventListener('click', function(e) {
                    if (allowNav||!leads.length) return;
                    const link = e.target.closest('a[href]');
                    if (!link) return;
                    const href = link.getAttribute('href');
                    if (!href||href==='#'||href===''||link.target==='_blank') return;
                    e.preventDefault(); e.stopImmediatePropagation();
                    Swal.fire({ title:'⚠️ Leave this page?',
                        html:'<p style="font-size:14px;color:#475569;">You have <b>'+leads.length+' leads</b> loaded.</p>',
                        icon:'question', showCancelButton:true, showDenyButton:true,
                        confirmButtonColor:'#10b981', denyButtonColor:'#ef4444', cancelButtonColor:'#6366f1',
                        confirmButtonText:'<i class="fas fa-save"></i> Save &amp; Leave',
                        denyButtonText:'<i class="fas fa-trash"></i> Discard &amp; Leave',
                        cancelButtonText:'<i class="fas fa-arrow-left"></i> Stay', reverseButtons:true })
                        .then(r => {
                            if (r.isConfirmed) { saveSession(); allowNav=true; window.location.href=href; }
                            else if (r.isDenied) { clearSession(); allowNav=true; window.location.href=href; }
                        });
                }, true);
            }

            function initAutoSave() {
                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState==='visible') refreshCsrfToken();
                    if (document.visibilityState==='hidden' && leads.length) saveSession();
                });
                window.addEventListener('beforeunload', () => { if(leads.length&&!allowNav) saveSession(); });
                setInterval(() => { if(leads.length) saveSession(); }, 30000);
                setInterval(refreshCsrfToken, 10*60*1000);
                refreshCsrfToken();
            }

            /* ══════════════════════════════════════════════════
             *  FILE HANDLING
             * ══════════════════════════════════════════════════ */
            function initFileHandling() {
                const dz=$('dropZone'), fi=$('fileInput');
                dz.addEventListener('click', ()=>fi.click());
                dz.addEventListener('dragover', e=>{ e.preventDefault(); dz.classList.add('over'); });
                dz.addEventListener('dragleave', ()=>dz.classList.remove('over'));
                dz.addEventListener('drop', e=>{ e.preventDefault(); dz.classList.remove('over'); if(e.dataTransfer.files[0]) loadFile(e.dataTransfer.files[0]); });
                fi.addEventListener('change', e=>{ if(e.target.files[0]) loadFile(e.target.files[0]); });
                $('removeFileBtn').addEventListener('click', ()=>{
                    if(!leads.length){ fullReset(); return; }
                    Swal.fire({ title:'Remove file & clear data?', icon:'warning', showCancelButton:true,
                        confirmButtonColor:'#ef4444', confirmButtonText:'Yes, Remove' })
                        .then(r=>{ if(r.isConfirmed) fullReset(); });
                });
            }

            function loadFile(f) {
                if (!f.name.toLowerCase().endsWith('.csv')) {
                    Swal.fire({ icon:'error', title:'Invalid File', text:'Please select a .csv file.' }); return;
                }
                if (leads.length) {
                    Swal.fire({ title:'Replace current data?', text:'Loading a new file will clear all current leads.',
                        icon:'question', showCancelButton:true, confirmButtonText:'Yes, load new file' })
                        .then(r=>{ if(r.isConfirmed) doLoad(f); });
                } else { doLoad(f); }
            }

            function doLoad(f) {
                leads=[]; filtered=[]; csvCols=[]; page=1; currentStatusFilter='all';
                $('previewSection').classList.remove('show'); $('clearBtn').disabled=true;
                $('tblBody').innerHTML=''; $('fileName').textContent=f.name;
                $('fileSize').textContent=fmtBytes(f.size); $('fileBar').classList.add('show');
                parseCSV(f);
            }

            function fmtBytes(b) {
                if(b<1024) return b+' B'; if(b<1048576) return (b/1024).toFixed(1)+' KB';
                return (b/1048576).toFixed(2)+' MB';
            }

            function fullReset() {
                leads=[]; filtered=[]; csvCols=[]; page=1; perPage=25; currentStatusFilter='all'; selectedProduct=null;
                clearSession();
                const fi=$('fileInput'); if(fi) fi.value='';
                ['fileBar','previewSection','progWrap','sessionBanner'].forEach(id=>$( id).classList.remove('show'));
                $('clearBtn').disabled=true; $('tblSearch').value=''; $('perPage').value='25';
                $('tblBody').innerHTML=''; $('colTags').innerHTML=''; $('pagBtns').innerHTML='';
                $('pagInfo').textContent='—'; $('fileName').textContent='—'; $('fileSize').textContent='—';
                $('selectedProductInfo').style.display='none';
                ['stTotal','stValid','stInvalid'].forEach(id=>$(id).textContent='0'); $('stRate').textContent='0%';
                document.querySelectorAll('.status-filter-btn').forEach(b=>{ b.classList.remove('active'); if(b.dataset.status==='all') b.classList.add('active'); });
                document.querySelectorAll('.product-card').forEach(c=>c.classList.remove('selected'));
            }

            /* ══════════════════════════════════════════════════
             *  CSV PARSING
             * ══════════════════════════════════════════════════ */
            function parseCSV(f) {
                Swal.fire({ title:'Parsing CSV…', allowOutsideClick:false, didOpen:()=>Swal.showLoading() });
                Papa.parse(f, { header:true, encoding:'UTF-16LE', skipEmptyLines:true,
                    complete: r=>{ if(r.data&&r.data.length){ Swal.close(); processRows(r.data); } else parseUTF8(f); },
                    error: ()=>parseUTF8(f) });
            }
            function parseUTF8(f) {
                Papa.parse(f, { header:true, skipEmptyLines:true,
                    complete: r=>{ Swal.close(); if(!r.data||!r.data.length){ Swal.fire({ icon:'error', title:'Empty CSV', text:'File is empty or unreadable.' }); return; } processRows(r.data); },
                    error: e=>{ Swal.close(); Swal.fire({ icon:'error', title:'Parse Error', text:e.message }); } });
            }

            function getCol(row, ...keys) {
                const rowKeys=Object.keys(row);
                for(const k of keys) for(const rk of rowKeys) if(rk.toLowerCase().trim()===k.toLowerCase()){ const v=row[rk]; if(v&&v.toString().trim()) return v.toString().trim(); }
                return '';
            }
            function cleanPhone(p) { return p?p.replace(/^p:/i,'').replace(/\s/g,'').trim():''; }

            function processRows(data) {
                csvCols=Object.keys(data[0]||{});
                leads=data.map((row,i)=>{
                    const ph=cleanPhone(getCol(row,'phone','Phone','customer_phone'));
                    const wp=cleanPhone(getCol(row,'work phone number','work_phone_number'));
                    return { id:i,
                        name  : getCol(row,'full name','full_name','Full Name','customer_name','name'),
                        phone : ph||wp,
                        addr  : getCol(row,'street address','Street Address','address','customer_address'),
                        city  : getCol(row,'city','City','district'),
                        plat  : getCol(row,'platform','Platform')||'fb',
                        adName: getCol(row,'ad_name','ad name','Ad Name'),
                        camp  : getCol(row,'campaign_name','campaign name'),
                        ctime : getCol(row,'created_time','Created Time','created time'),
                        lstatus:getCol(row,'lead_status','lead status'),
                        status :'Pending', uploaded:false, valid:false };
                });
                leads.forEach(l=>{ l.valid=!!(l.name&&l.phone); });
                page=1; doFilter(); renderStats(); renderCols();
                $('previewSection').classList.add('show'); $('clearBtn').disabled=false; saveSession();
                const v=leads.filter(l=>l.valid).length;
                Swal.fire({ icon:'success', title:'CSV Loaded ✓',
                    html:'<div style="text-align:left;line-height:1.9;"><p>📊 <b>Total rows:</b> '+leads.length+'</p>'+
                        '<p>✅ <b>Valid leads:</b> '+v+'</p>'+
                        (leads.length-v>0?'<p>⚠️ <b>Invalid:</b> '+(leads.length-v)+'</p>':'')+
                        '<p>📋 <b>Columns:</b> '+csvCols.length+'</p></div>',
                    confirmButtonColor:'#6366f1', confirmButtonText:'View Preview ›' });
            }

            function renderStats() {
                const t=leads.length, v=leads.filter(l=>l.valid).length;
                $('stTotal').textContent=t; $('stValid').textContent=v;
                $('stInvalid').textContent=t-v; $('stRate').textContent=t?((v/t)*100).toFixed(1)+'%':'0%';
            }
            function renderCols() { $('colTags').innerHTML=csvCols.map(c=>'<span class="col-tag">'+esc(c)+'</span>').join(''); }

            /* ══════════════════════════════════════════════════
             *  STATUS FILTER
             * ══════════════════════════════════════════════════ */
            function initStatusFilter() {
                document.querySelectorAll('.status-filter-btn').forEach(btn=>{
                    btn.addEventListener('click', function(){ document.querySelectorAll('.status-filter-btn').forEach(b=>b.classList.remove('active')); this.classList.add('active'); currentStatusFilter=this.dataset.status; page=1; doFilter(); });
                });
            }
            function doFilter() {
                const q=($('tblSearch').value||'').toLowerCase().trim();
                filtered=leads.map((_,i)=>i).filter(i=>{
                    const l=leads[i];
                    if(currentStatusFilter!=='all'&&l.status!==currentStatusFilter) return false;
                    if(q){ return (l.name+' '+l.phone+' '+l.city+' '+l.plat+' '+l.adName+' '+l.camp).toLowerCase().includes(q); }
                    return true;
                });
                page=1; render();
            }

            /* ══════════════════════════════════════════════════
             *  UPLOAD A SINGLE LEAD
             * ══════════════════════════════════════════════════ */
            async function uploadLead(di) {
                if (!selectedProduct) {
                    Swal.fire({ icon:'error', title:'No Product Selected', text:'Please select a product from Step 2.' }); return;
                }
                const l=leads[di];
                if (!l.valid) {
                    Swal.fire({ icon:'warning', title:'Invalid Lead', text:'This lead has no name or phone.' }); return;
                }
                if (parseInt(selectedProduct.stock) < 1) {
                    Swal.fire({ icon:'error', title:'Out of Stock', text:'This product is out of stock.' }); return;
                }

                // ── Build payload — include product_id AND product_code ──────────
                const payload = {
                    product_code: selectedProduct.code,   // used for DB lookup
                    product_id  : selectedProduct.id,     // extra hint for server
                    leads: [{
                        customer_name   : l.name,
                        customer_phone  : l.phone,
                        customer_address: l.addr,
                        city            : l.city,
                        platform        : l.plat,
                        ad_name         : l.adName,
                        campaign_name   : l.camp,
                        created_time    : l.ctime,
                        lead_status     : l.lstatus,
                        call_status     : l.status || 'Pending',
                    }],
                };

                // Progress bar
                const pw=$('progWrap'), pf=$('progFill'), pt=$('progTxt'), pp=$('progPct');
                pw.classList.add('show'); pf.style.width='0%'; pt.textContent='Uploading…'; pp.textContent='0%';
                let fp=0;
                const tk=setInterval(()=>{ if(fp<70){ fp+=Math.random()*15; const p=Math.min(fp,70).toFixed(0); pf.style.width=p+'%'; pp.textContent=p+'%'; } },200);

                try {
                    const token = getCsrfToken();
                    const res = await fetch('{{ route("call.csv_insert") }}', {
                        method :'POST',
                        headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':token, 'X-Requested-With':'XMLHttpRequest' },
                        credentials:'same-origin',
                        body: JSON.stringify(payload),
                    });

                    clearInterval(tk);

                    // 419 → refresh token and retry once
                    if (res.status===419) {
                        pw.classList.remove('show');
                        await refreshCsrfToken();
                        const retry=await fetch('{{ route("call.csv_insert") }}', {
                            method:'POST',
                            headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':getCsrfToken(), 'X-Requested-With':'XMLHttpRequest' },
                            credentials:'same-origin', body:JSON.stringify(payload)
                        });
                        if (!retry.ok) throw new Error('Session expired. Please refresh the page and try again.');
                        return processUploadResponse(await retry.json(), di, l);
                    }

                    if (!res.ok) {
                        let msg = 'Server error ' + res.status;
                        try { const d=await res.json(); msg=d.message||msg; } catch(e){}
                        throw new Error(msg);
                    }

                    pf.style.width='100%'; pp.textContent='100%'; pt.textContent='Done!';
                    await new Promise(r=>setTimeout(r,400));
                    pw.classList.remove('show');

                    const data=await res.json();
                    if (data.csrf_token) setCsrfToken(data.csrf_token);
                    return processUploadResponse(data, di, l);

                } catch(err) {
                    clearInterval(tk);
                    pw.classList.remove('show');
                    console.error('Upload error:', err);
                    Swal.fire({ icon:'error', title:'Upload Failed', text: err.message||'An error occurred. Please try again.', confirmButtonColor:'#6366f1' });
                }
            }

            function processUploadResponse(data, di, l) {
                if (data.success) {
                    leads[di].uploaded=true; saveSession(); renderStats();
                    Swal.fire({ icon:'success', title:'Uploaded!', timer:1500, showConfirmButton:false });
                    setTimeout(()=>{ allowNav=true; window.location.href=STATUS_ROUTES[l.status]||'{{ route("call.index") }}'; }, 1500);
                } else {
                    throw new Error(data.message||'Upload failed');
                }
            }

            /* ══════════════════════════════════════════════════
             *  TABLE RENDER
             * ══════════════════════════════════════════════════ */
            function render() {
                const pp=perPage===0?Math.max(filtered.length,1):perPage;
                const tot=filtered.length, pages=Math.max(1,Math.ceil(tot/pp));
                if(page>pages) page=pages;
                const s0=(page-1)*pp, s1=perPage===0?tot:Math.min(s0+pp,tot);
                const slice=filtered.slice(s0,s1);
                const tbody=$('tblBody'); tbody.innerHTML='';

                if (tot===0) {
                    tbody.innerHTML='<tr><td colspan="12" style="text-align:center;padding:28px;color:var(--muted);"><i class="fas fa-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>No leads match the current filter.</td></tr>';
                } else {
                    slice.forEach((di,ri)=>{
                        const l=leads[di], num=s0+ri+1, dis=l.valid?'':'disabled';
                        const meta=STATUS_META[getStatusKey(l.status)]||STATUS_META.p;
                        const opts=['Pending','Conform','Reject','Not Answer','Other'].map(v=>'<option value="'+v+'"'+(l.status===v?' selected':'')+'>'+v+'</option>').join('');
                        const uploadBtn=l.uploaded
                            ?'<span style="color:var(--success);"><i class="fas fa-check-circle"></i> Uploaded</span>'
                            :'<button class="table-upload-btn upload-row-btn" data-di="'+di+'" '+dis+'><i class="fas fa-cloud-upload-alt"></i> Upload Row</button>';
                        const tr=document.createElement('tr'); tr.dataset.di=di;
                        tr.innerHTML=
                            '<td class="row-num">'+num+'</td>'+
                            '<td>'+(l.valid?'<span class="badge-valid bv-ok"><i class="fas fa-check"></i> Valid</span>':'<span class="badge-valid bv-bad"><i class="fas fa-times"></i> Invalid</span>')+'</td>'+
                            '<td style="font-weight:600;color:var(--dark);" title="'+esc(l.name)+'">'+(esc(l.name)||'—')+'</td>'+
                            '<td style="font-family:monospace;" title="'+esc(l.phone)+'">'+(esc(l.phone)||'—')+'</td>'+
                            '<td title="'+esc(l.addr)+'">'+(esc(l.addr)||'—')+'</td>'+
                            '<td>'+(esc(l.city)||'—')+'</td>'+
                            '<td>'+(esc(l.plat)||'—')+'</td>'+
                            '<td><span class="ad-name-badge" title="'+esc(l.adName)+'">'+(esc(l.adName)||'—')+'</span></td>'+
                            '<td title="'+esc(l.camp)+'">'+(esc(l.camp)||'—')+'</td>'+
                            '<td><span class="status-badge '+meta.badge+'" id="stbadge-'+di+'"><i class="fas '+meta.icon+'" style="font-size:9px;"></i> '+esc(l.status)+'</span></td>'+
                            '<td><select class="row-status-sel" data-di="'+di+'" '+dis+'>'+opts+'</select></td>'+
                            '<td class="upload-cell">'+uploadBtn+'</td>';
                        tbody.appendChild(tr);
                    });
                }

                tbody.querySelectorAll('.row-status-sel').forEach(sel=>{
                    sel.addEventListener('change', function(){
                        const di=parseInt(this.dataset.di), nv=this.value;
                        if(nv!==leads[di].status){
                            leads[di].status=nv;
                            const m=STATUS_META[getStatusKey(nv)]||STATUS_META.p;
                            const b=$('stbadge-'+di);
                            if(b){ b.className='status-badge '+m.badge; b.innerHTML='<i class="fas '+m.icon+'" style="font-size:9px;"></i> '+esc(nv); }
                            this.classList.add('saved'); setTimeout(()=>this.classList.remove('saved'),500);
                            saveSession();
                            if(currentStatusFilter!=='all'&&currentStatusFilter!==nv) setTimeout(()=>doFilter(),500);
                        }
                    });
                });
                tbody.querySelectorAll('.upload-row-btn').forEach(btn=>{
                    btn.addEventListener('click', function(){ uploadLead(parseInt(this.dataset.di)); });
                });
                renderPagination(pages,s0,s1,tot);
            }

            function renderPagination(pages,s0,s1,tot) {
                $('pagInfo').textContent=tot===0?'No rows':'Showing '+(s0+1)+'–'+s1+' of '+tot+' rows';
                const wrap=$('pagBtns'); wrap.innerHTML=''; if(pages<=1) return;
                function mkBtn(html,p,active,disabled){ const b=document.createElement('button'); b.className='pg-btn'+(active?' active':''); b.disabled=disabled; b.innerHTML=html; b.addEventListener('click',()=>{ page=p; render(); }); return b; }
                wrap.appendChild(mkBtn('<i class="fas fa-chevron-left"></i>',page-1,false,page===1));
                buildRange(page,pages).forEach((p,i,a)=>{ if(i>0&&p-a[i-1]>1){ const sp=document.createElement('button'); sp.className='pg-btn'; sp.disabled=true; sp.textContent='…'; wrap.appendChild(sp); } wrap.appendChild(mkBtn(p,p,p===page,false)); });
                wrap.appendChild(mkBtn('<i class="fas fa-chevron-right"></i>',page+1,false,page===pages));
            }
            function buildRange(cur,total) {
                if(total<=7) return Array.from({length:total},(_,i)=>i+1);
                const s=new Set([1,total,cur]); for(let d=-2;d<=2;d++){ const p=cur+d; if(p>0&&p<=total) s.add(p); } return Array.from(s).sort((a,b)=>a-b);
            }

            /* ══════════════════════════════════════════════════
             *  CLEAR
             * ══════════════════════════════════════════════════ */
            function initClear() {
                $('clearBtn').addEventListener('click', ()=>{
                    Swal.fire({ title:'Clear All Data?', html:'<p style="font-size:14px;color:#475569;">All <b>'+leads.length+'</b> leads will be removed.</p>',
                        icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', confirmButtonText:'<i class="fas fa-trash"></i> Yes, Clear All' })
                        .then(r=>{ if(r.isConfirmed){ fullReset(); Swal.fire({ icon:'success', title:'Cleared', timer:1200, showConfirmButton:false }); } });
                });
            }

            /* ══════════════════════════════════════════════════
             *  INIT
             * ══════════════════════════════════════════════════ */
            function init() {
                initFileHandling(); initClear(); initLeaveGuard(); initAutoSave(); initStatusFilter();
                loadProducts();
                $('tblSearch').addEventListener('input', doFilter);
                $('perPage').addEventListener('change', function(){ perPage=parseInt(this.value)||0; page=1; render(); });
                checkRestore();
            }

            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
@endpush
