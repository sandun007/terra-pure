@extends('wholesaler.layouts.app')

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

        .bulk-bar{background:linear-gradient(135deg,var(--primary-l),#fff);border:1px solid #c7d2fe;border-radius:var(--r-lg);padding:16px 20px;margin-bottom:18px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}
        .bulk-bar-left{display:flex;align-items:center;gap:10px}
        .bulk-bar-left i{color:var(--primary);font-size:20px}
        .bulk-bar-title{font-size:14px;font-weight:700;color:var(--dark)}
        .bulk-bar-sub{font-size:11px;color:var(--mid);margin-top:2px}
        .btn-bulk-upload{padding:10px 22px;background:linear-gradient(135deg,var(--primary),var(--primary-d));color:#fff;border:none;border-radius:var(--r-md);font-size:13px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:8px;transition:all .2s;box-shadow:0 4px 12px rgba(99,102,241,.3)}
        .btn-bulk-upload:hover:not(:disabled){transform:translateY(-2px);box-shadow:0 6px 16px rgba(99,102,241,.4)}
        .btn-bulk-upload:disabled{opacity:.5;cursor:not-allowed;transform:none}

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

        .bulk-progress-wrap{display:none;background:white;border:1px solid var(--border);border-radius:var(--r-lg);padding:20px;margin-bottom:16px;box-shadow:var(--shadow)}
        .bulk-progress-wrap.show{display:block}
        .bulk-prog-title{font-size:14px;font-weight:700;color:var(--dark);margin-bottom:12px;display:flex;align-items:center;gap:8px}
        .bulk-prog-title i{color:var(--primary)}
        .bulk-prog-row{display:flex;justify-content:space-between;font-size:12px;color:var(--mid);margin-bottom:6px}
        .bulk-prog-track{height:14px;background:var(--border);border-radius:999px;overflow:hidden}
        .bulk-prog-fill{height:100%;background:linear-gradient(90deg,var(--primary),var(--success));border-radius:999px;transition:width .3s ease;width:0%}
        .bulk-prog-counts{display:flex;gap:16px;margin-top:10px;font-size:12px}
        .bpc-success{color:var(--success);font-weight:700}
        .bpc-error{color:var(--danger);font-weight:700}
        .bpc-remain{color:var(--mid);font-weight:600}
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
                <p>Import leads to create orders and calls in bulk</p>
            </div>
            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                <a href="{{ route('wholesaler.order.pending-order') }}" class="btn btn-dark" id="backBtn">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
            </div>
        </div>

        <div class="info-alert">
            <i class="fas fa-info-circle" style="font-size:17px;flex-shrink:0;margin-top:1px;"></i>
            <div>Upload a CSV file. Required columns:
                <strong>full name</strong> + <strong>phone</strong>.
                <strong>Important:</strong> Each lead will create BOTH an order (in Pending Orders) AND a call record (in Pending Calls).
                Use <strong>Upload Row</strong> for individual leads or <strong>Upload All Valid</strong> to send all at once.
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
                    <div class="dz-s">Supports CSV files with lead data</div>
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
                <h2>Step 3 — Preview & Upload Leads</h2>
            </div>
            <div class="card-b">

                <div class="stats-grid">
                    <div class="stat-box"><div class="stat-lbl">Total Leads</div><div class="stat-val" id="stTotal">0</div></div>
                    <div class="stat-box"><div class="stat-lbl">Valid</div><div class="stat-val sv-g" id="stValid">0</div></div>
                    <div class="stat-box"><div class="stat-lbl">Uploaded</div><div class="stat-val sv-b" id="stUploaded">0</div></div>
                    <div class="stat-box"><div class="stat-lbl">Remaining</div><div class="stat-val sv-r" id="stRemaining">0</div></div>
                </div>

                <!-- Bulk Upload Bar -->
                <div class="bulk-bar" id="bulkBar">
                    <div class="bulk-bar-left">
                        <i class="fas fa-rocket"></i>
                        <div>
                            <div class="bulk-bar-title">Bulk Upload All Valid Leads</div>
                            <div class="bulk-bar-sub" id="bulkBarSub">Select a product and load CSV to enable bulk upload</div>
                        </div>
                    </div>
                    <button class="btn-bulk-upload" id="btnBulkUpload" disabled>
                        <i class="fas fa-cloud-upload-alt"></i> Upload All Valid
                    </button>
                </div>

                <!-- Bulk Progress -->
                <div class="bulk-progress-wrap" id="bulkProgressWrap">
                    <div class="bulk-prog-title"><i class="fas fa-spinner fa-spin"></i> Uploading leads…</div>
                    <div class="bulk-prog-row">
                        <span id="bulkProgLabel">Processing…</span>
                        <span id="bulkProgPct">0%</span>
                    </div>
                    <div class="bulk-prog-track"><div class="bulk-prog-fill" id="bulkProgFill"></div></div>
                    <div class="bulk-prog-counts">
                        <span class="bpc-success"><i class="fas fa-check-circle"></i> <span id="bpcSuccess">0</span> uploaded</span>
                        <span class="bpc-error"><i class="fas fa-times-circle"></i> <span id="bpcError">0</span> failed</span>
                        <span class="bpc-remain"><i class="fas fa-hourglass-half"></i> <span id="bpcRemain">0</span> remaining</span>
                    </div>
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
                        <input type="text" id="tblSearch" class="search-inp" placeholder="Search name / phone / city…">
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
                            <th>Campaign</th><th>Lead Date</th><th>Status</th><th>Upload</th>
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
             *  STATE
             * ══════════════════════════════════════════════════ */
            const SESS = 'csv_calls_v10';
            let leads = [], filtered = [], csvCols = [];
            let page = 1, perPage = 25, allowNav = false, currentStatusFilter = 'all';
            let selectedProduct = null, products = [];
            let isBulkUploading = false;

            function $(id) { return document.getElementById(id); }
            function esc(s) {
                return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
            }

            /* ══════════════════════════════════════════════════
             *  PRODUCT LOADING - FIXED: Use correct API endpoint
             * ══════════════════════════════════════════════════ */
            async function loadProducts() {
                $('productLoader').style.display = 'block';
                $('productGrid').style.display   = 'none';
                $('noProductsMsg').style.display = 'none';

                try {
                    // FIXED: Use the correct route for wholesaler products
                    const res  = await fetch('{{ route("wholesaler.products.index") }}', {
                        credentials: 'same-origin',
                        headers: { 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' }
                    });
                    const data = await res.json();

                    if (data.success && Array.isArray(data.products) && data.products.length) {
                        products = data.products;
                        renderProductGrid();
                    } else if (Array.isArray(data)) {
                        // Handle case where API returns array directly
                        products = data;
                        renderProductGrid();
                    } else {
                        showNoProducts('No products found. Please add products first.');
                    }
                } catch (err) {
                    console.error('Product load error:', err);
                    showNoProducts('Failed to load products. Please refresh the page.');
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
                 data-price="${p.display_price || p.price}"
                 data-stock="${p.qty}">
                <div class="product-card-code">${esc(p.code)}</div>
                <div class="product-card-name" title="${esc(p.name)}">${esc(p.name)}</div>
                <div class="product-card-meta">
                    <span class="product-card-price">Rs. ${p.display_price || p.price}</span>
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
                        updateBulkBar();
                        saveSession();
                    });
                });

                // Auto-select first product if available
                const first = grid.querySelector('.product-card');
                if (first) first.click();
            }

            function showSelectedProduct(p) {
                $('selectedProductCode').textContent  = p.code;
                $('selectedProductName').textContent  = p.name;
                $('selectedProductPrice').textContent = p.price;
                $('selectedProductStock').textContent = p.stock;
                $('selectedProductInfo').style.display = 'flex';
                updateBulkBar();
            }

            /* ══════════════════════════════════════════════════
             *  BULK BAR
             * ══════════════════════════════════════════════════ */
            function updateBulkBar() {
                const validLeads = leads.filter(l => l.valid && !l.uploaded);
                const btn = $('btnBulkUpload');
                const sub = $('bulkBarSub');
                if (!selectedProduct || validLeads.length === 0) {
                    btn.disabled = true;
                    sub.textContent = selectedProduct
                        ? (leads.length === 0 ? 'Load a CSV file to enable bulk upload' : 'All valid leads already uploaded!')
                        : 'Select a product and load CSV to enable bulk upload';
                } else {
                    btn.disabled = false;
                    sub.textContent = `${validLeads.length} valid lead(s) ready to upload`;
                }
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
                sessionStorage.removeItem(SESS);
                const fi=$('fileInput'); if(fi) fi.value='';
                ['fileBar','previewSection','progWrap','bulkProgressWrap'].forEach(id=>$(id).classList.remove('show'));
                $('clearBtn').disabled=true; $('tblSearch').value=''; $('perPage').value='25';
                $('tblBody').innerHTML=''; $('colTags').innerHTML=''; $('pagBtns').innerHTML='';
                $('pagInfo').textContent='—'; $('fileName').textContent='—'; $('fileSize').textContent='—';
                $('selectedProductInfo').style.display='none';
                ['stTotal','stValid','stUploaded','stRemaining'].forEach(id=>$(id).textContent='0');
                document.querySelectorAll('.status-filter-btn').forEach(b=>{ b.classList.remove('active'); if(b.dataset.status==='all') b.classList.add('active'); });
                document.querySelectorAll('.product-card').forEach(c=>c.classList.remove('selected'));
                updateBulkBar();
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
                page=1; doFilter(); renderStats(); renderCols(); updateBulkBar();
                $('previewSection').classList.add('show'); $('clearBtn').disabled=false; saveSession();
                const v=leads.filter(l=>l.valid).length;
                Swal.fire({ icon:'success', title:'CSV Loaded ✓',
                    html:'<div style="text-align:left;line-height:1.9;"><p>📊 <b>Total rows:</b> '+leads.length+'</p>'+
                        '<p>✅ <b>Valid leads:</b> '+v+'</p>'+
                        (leads.length-v>0?'<p>⚠️ <b>Invalid:</b> '+(leads.length-v)+'</p>':'')+
                        '<p>📋 <b>Columns:</b> '+csvCols.length+'</p>'+
                        '<p>🚀 Use <b>Upload All Valid</b> to send all leads at once.</p></div>',
                    confirmButtonColor:'#6366f1', confirmButtonText:'View Preview ›' });
            }

            function renderStats() {
                const t=leads.length, v=leads.filter(l=>l.valid).length;
                const u=leads.filter(l=>l.uploaded).length;
                const rem=v-u;
                $('stTotal').textContent=t;
                $('stValid').textContent=v;
                $('stUploaded').textContent=u;
                $('stRemaining').textContent=Math.max(0,rem);
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
             *  UPLOAD FUNCTIONS - FIXED to create both orders and calls
             * ══════════════════════════════════════════════════ */
            function buildPayload(leadsArr) {
                return {
                    product_code: selectedProduct.code,
                    call_status: 'Pending',
                    leads: leadsArr.map(l => ({
                        customer_name   : l.name,
                        customer_phone  : l.phone,
                        customer_phone_02 : '',
                        customer_address: l.addr,
                        city            : l.city,
                        platform        : l.plat,
                        ad_name         : l.adName,
                        campaign_name   : l.camp,
                        created_time    : l.ctime,
                        lead_status     : l.lstatus,
                        call_status     : l.status || 'Pending',
                    }))
                };
            }

            async function uploadLead(di) {
                if (!selectedProduct) {
                    Swal.fire({ icon:'error', title:'No Product Selected', text:'Please select a product from Step 2.' }); return;
                }
                const l=leads[di];
                if (!l.valid) {
                    Swal.fire({ icon:'warning', title:'Invalid Lead', text:'This lead has no name or phone.' }); return;
                }

                const payload = buildPayload([l]);

                const pw=$('progWrap'), pf=$('progFill'), pt=$('progTxt'), pp=$('progPct');
                pw.classList.add('show'); pf.style.width='0%'; pt.textContent='Uploading…'; pp.textContent='0%';
                let fp=0;
                const tk=setInterval(()=>{ if(fp<70){ fp+=Math.random()*15; const p=Math.min(fp,70).toFixed(0); pf.style.width=p+'%'; pp.textContent=p+'%'; } },200);

                try {
                    const data = await doUploadRequest(payload);
                    clearInterval(tk);

                    pf.style.width='100%'; pp.textContent='100%'; pt.textContent='Done!';
                    await new Promise(r=>setTimeout(r,400));
                    pw.classList.remove('show');

                    if (data.success) {
                        leads[di].uploaded=true; saveSession(); renderStats(); updateBulkBar(); render();
                        Swal.fire({
                            icon:'success',
                            title:'Uploaded!',
                            html:'<p>Lead <strong>'+esc(l.name)+'</strong> has been created.</p><p>✅ Order and call record created successfully.</p>',
                            confirmButtonColor:'#10b981',
                            confirmButtonText:'<i class="fas fa-list"></i> View Pending Calls',
                            showCancelButton:true,
                            cancelButtonText:'Stay here'
                        }).then(r => {
                            if (r.isConfirmed) {
                                allowNav = true;
                                window.location.href = '{{ route("wholesaler.call.index") }}';
                            } else {
                                render();
                            }
                        });
                    } else {
                        throw new Error(data.message||'Upload failed');
                    }

                } catch(err) {
                    clearInterval(tk);
                    pw.classList.remove('show');
                    console.error('Upload error:', err);
                    Swal.fire({ icon:'error', title:'Upload Failed', text: err.message||'An error occurred. Please try again.', confirmButtonColor:'#6366f1' });
                }
            }

            async function doUploadRequest(payload) {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                const res = await fetch('{{ route("wholesaler.order.csv_insert") }}', {
                    method :'POST',
                    headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':token, 'X-Requested-With':'XMLHttpRequest' },
                    credentials:'same-origin',
                    body: JSON.stringify(payload),
                });
                if (!res.ok) {
                    let msg = 'Server error ' + res.status;
                    try { const d=await res.json(); msg=d.message||msg; } catch(e){}
                    throw new Error(msg);
                }
                return await res.json();
            }

            async function bulkUploadAll() {
                if (!selectedProduct) {
                    Swal.fire({ icon:'error', title:'No Product Selected', text:'Please select a product.' }); return;
                }
                const validLeads = leads.filter(l => l.valid && !l.uploaded);
                if (!validLeads.length) {
                    Swal.fire({ icon:'info', title:'Nothing to Upload', text:'All valid leads are already uploaded.' }); return;
                }

                const confirm = await Swal.fire({
                    icon: 'question',
                    title: 'Upload All Valid Leads?',
                    html: `<div style="text-align:left;line-height:2;">
                        <p>📦 <b>Product:</b> ${esc(selectedProduct.code)} — ${esc(selectedProduct.name)}</p>
                        <p>📋 <b>Leads to upload:</b> ${validLeads.length}</p>
                        <p class="text-info"><i class="fas fa-info-circle"></i> Each lead will create an order and a call record.</p>
                    </div>`,
                    showCancelButton: true,
                    confirmButtonColor: '#6366f1',
                    confirmButtonText: '<i class="fas fa-rocket"></i> Yes, Upload All',
                    cancelButtonText: 'Cancel'
                });
                if (!confirm.isConfirmed) return;

                isBulkUploading = true;
                $('btnBulkUpload').disabled = true;
                $('clearBtn').disabled = true;

                const bpw = $('bulkProgressWrap');
                bpw.classList.add('show');
                const bpf = $('bulkProgFill'), bpl = $('bulkProgLabel'), bpp = $('bulkProgPct');
                const bpSuccess = $('bpcSuccess'), bpError = $('bpcError'), bpRemain = $('bpcRemain');

                let successCount = 0, errorCount = 0;
                const BATCH_SIZE = 10;
                const batches = [];
                for (let i = 0; i < validLeads.length; i += BATCH_SIZE) {
                    batches.push(validLeads.slice(i, i + BATCH_SIZE));
                }

                let processed = 0;
                const total = validLeads.length;
                const errors = [];

                for (let bi = 0; bi < batches.length; bi++) {
                    const batch = batches[bi];
                    bpl.textContent = `Batch ${bi+1}/${batches.length} — uploading ${batch.length} leads…`;

                    try {
                        const payload = buildPayload(batch);
                        const data = await doUploadRequest(payload);

                        if (data.success) {
                            batch.forEach(l => { leads[l.id].uploaded = true; });
                            successCount += data.successCount || batch.length;
                            if (data.errorCount) errorCount += data.errorCount;
                            if (data.errors && data.errors.length) errors.push(...data.errors);
                        } else {
                            errorCount += batch.length;
                            errors.push(`Batch ${bi+1}: ${data.message || 'Failed'}`);
                        }
                    } catch(err) {
                        errorCount += batch.length;
                        errors.push(`Batch ${bi+1}: ${err.message}`);
                        console.error('Batch upload error:', err);
                    }

                    processed += batch.length;
                    const pct = Math.round((processed / total) * 100);
                    bpf.style.width = pct + '%';
                    bpp.textContent = pct + '%';
                    bpSuccess.textContent = successCount;
                    bpError.textContent = errorCount;
                    bpRemain.textContent = Math.max(0, total - processed);

                    saveSession();
                    renderStats();
                    render();

                    if (bi < batches.length - 1) {
                        await new Promise(r => setTimeout(r, 500));
                    }
                }

                isBulkUploading = false;
                bpf.style.width = '100%';
                bpl.textContent = 'Upload complete!';

                setTimeout(async () => {
                    bpw.classList.remove('show');

                    const resultHtml = `<div style="text-align:left;line-height:2;">
                        <p>✅ <b>${successCount}</b> lead(s) successfully uploaded</p>
                        ${errorCount > 0 ? `<p>❌ <b>${errorCount}</b> lead(s) failed</p>` : ''}
                        <p class="text-primary"><i class="fas fa-phone-alt"></i> Check your Pending Calls page</p>
                    </div>`;

                    const result = await Swal.fire({
                        icon: successCount > 0 ? 'success' : 'warning',
                        title: successCount > 0 ? 'Upload Complete! 🎉' : 'Upload Issues',
                        html: resultHtml,
                        confirmButtonColor: '#10b981',
                        confirmButtonText: '<i class="fas fa-phone-alt"></i> View Pending Calls',
                        showCancelButton: true,
                        cancelButtonText: 'Stay here'
                    });

                    if (result.isConfirmed) {
                        allowNav = true;
                        window.location.href = '{{ route("wholesaler.call.index") }}';
                    } else {
                        updateBulkBar();
                        $('clearBtn').disabled = false;
                    }
                }, 600);
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
                        const l=leads[di], num=s0+ri+1, dis=(l.valid&&!l.uploaded)?'':'disabled';
                        const opts=['Pending','Conform','Reject','Not Answer','Other'].map(v=>'<option value="'+v+'"'+(l.status===v?' selected':'')+'>'+v+'</option>').join('');
                        let uploadCell;
                        if (l.uploaded) {
                            uploadCell = '<span style="color:var(--success);font-size:12px;font-weight:700;"><i class="fas fa-check-circle"></i> Created</span>';
                        } else if (!l.valid) {
                            uploadCell = '<span style="color:var(--muted);font-size:11px;"><i class="fas fa-ban"></i> Invalid</span>';
                        } else {
                            uploadCell = '<button class="table-upload-btn upload-row-btn" data-di="'+di+'"><i class="fas fa-cloud-upload-alt"></i> Upload Row</button>';
                        }
                        const tr=document.createElement('tr');
                        if (l.uploaded) tr.style.opacity = '0.6';
                        tr.dataset.di=di;
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
                            '<td>'+(l.ctime?esc(l.ctime).substring(0,10):'—')+'</td>'+
                            '<td><span class="status-badge sb-'+l.status.toLowerCase()+'">'+esc(l.status)+'</span></td>'+
                            '<td class="upload-cell">'+uploadCell+'</td>';
                        tbody.appendChild(tr);
                    });
                }

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
                [1,2,3,4,5,6,7].filter(p=>p<=pages).forEach(p=>wrap.appendChild(mkBtn(p,p,p===page,false)));
                if(pages>7) wrap.appendChild(mkBtn('<i class="fas fa-chevron-right"></i>',page+1,false,page===pages));
            }

            /* ══════════════════════════════════════════════════
             *  CLEAR
             * ══════════════════════════════════════════════════ */
            function initClear() {
                $('clearBtn').addEventListener('click', ()=>{
                    if(isBulkUploading) return;
                    Swal.fire({ title:'Clear All Data?', html:'<p style="font-size:14px;color:#475569;">All <b>'+leads.length+'</b> leads will be removed.</p>',
                        icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', confirmButtonText:'<i class="fas fa-trash"></i> Yes, Clear All' })
                        .then(r=>{ if(r.isConfirmed){ fullReset(); Swal.fire({ icon:'success', title:'Cleared', timer:1200, showConfirmButton:false }); } });
                });
            }

            /* ══════════════════════════════════════════════════
             *  INIT
             * ══════════════════════════════════════════════════ */
            function init() {
                initFileHandling(); initClear(); initStatusFilter();
                loadProducts();
                $('tblSearch').addEventListener('input', doFilter);
                $('perPage').addEventListener('change', function(){ perPage=parseInt(this.value)||0; page=1; render(); });
                $('btnBulkUpload').addEventListener('click', () => {
                    if (!isBulkUploading) bulkUploadAll();
                });
            }

            document.addEventListener('DOMContentLoaded', init);
        })();
    </script>
@endpush
