@extends('seller.layout.app')

@push('title')
    Upload CSV Leads
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary:        #6366f1;
            --primary-hover:  #4f46e5;
            --primary-light:  #eef2ff;
            --primary-glow:   rgba(99,102,241,.15);
            --success:        #10b981;
            --success-light:  #d1fae5;
            --danger:         #ef4444;
            --danger-light:   #fee2e2;
            --warning:        #f59e0b;
            --warning-light:  #fef3c7;
            --info:           #3b82f6;
            --info-light:     #dbeafe;
            --purple:         #8b5cf6;
            --dark:           #0f172a;
            --mid:            #475569;
            --light:          #94a3b8;
            --border:         #e2e8f0;
            --surface:        #ffffff;
            --surface-2:      #f8fafc;
            --bg:             #fafbfc;
            --font-sans:      'Plus Jakarta Sans', sans-serif;
            --font-mono:      'JetBrains Mono', monospace;
            --radius-xl:      16px;
            --radius-lg:      12px;
            --radius-md:      8px;
            --shadow-sm:      0 2px 8px rgba(0,0,0,.05);
            --shadow-md:      0 6px 24px rgba(0,0,0,.07);
            --ease:           cubic-bezier(.4,0,.2,1);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: var(--font-sans);
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
            color: var(--dark);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        /* ── WRAPPER ── */
        .csv-wrapper {
            padding: 32px 40px;
            width: 100%;
            margin: 0 auto;
            min-height: 100vh;
        }
        @media (max-width: 768px) {
            .csv-wrapper { padding: 16px; }
        }

        /* ── PAGE HEADER ── */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .page-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0 0 4px;
            letter-spacing: -.5px;
        }
        .title-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-lg);
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(245,158,11,.3);
            flex-shrink: 0;
        }
        .page-sub {
            font-size: 13px;
            color: var(--mid);
            margin: 0;
        }

        /* ── CARD ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .card-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 22px 28px 0;
        }
        .card-body {
            padding: 20px 28px 28px;
        }
        @media (max-width: 640px) {
            .card-body { padding: 16px 16px 20px; }
            .card-head { padding: 18px 16px 0; }
        }
        .card-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }
        .card-icon {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .ci-yellow { background: var(--warning-light); color: var(--warning); }
        .ci-indigo { background: var(--primary-light);  color: var(--primary); }
        .ci-green  { background: var(--success-light);  color: var(--success); }

        /* ── ALERT ── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            border-radius: var(--radius-lg);
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            border-left: 4px solid transparent;
        }
        .alert-info {
            background: var(--info-light);
            border-left-color: var(--info);
            color: #1e40af;
        }
        .alert .material-icons-round {
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* ── DROP ZONE ── */
        .drop-zone {
            position: relative;
            border: 2.5px dashed var(--border);
            border-radius: var(--radius-xl);
            padding: 52px 24px;
            text-align: center;
            cursor: pointer;
            background: var(--surface-2);
            transition: border-color .25s var(--ease), background .25s var(--ease);
            overflow: hidden;
        }
        .drop-zone::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 50% 0%, var(--primary-glow) 0%, transparent 70%);
            opacity: 0;
            transition: opacity .3s;
        }
        .drop-zone:hover::before, .drop-zone.dragover::before {
            opacity: 1;
        }
        .drop-zone:hover, .drop-zone.dragover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .dz-icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--surface);
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            transition: all .25s var(--ease);
            box-shadow: var(--shadow-sm);
        }
        .drop-zone:hover .dz-icon-wrap, .drop-zone.dragover .dz-icon-wrap {
            border-color: var(--primary);
            background: var(--primary-light);
            transform: scale(1.06);
            box-shadow: 0 0 0 6px var(--primary-glow);
        }
        .dz-icon {
            font-size: 36px;
            color: var(--light);
            transition: color .25s;
        }
        .drop-zone:hover .dz-icon, .drop-zone.dragover .dz-icon {
            color: var(--primary);
        }

        .dz-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 6px;
        }
        .dz-sub   {
            font-size: 13px;
            color: var(--mid);
        }
        .dz-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 18px;
            padding: 9px 22px;
            background: var(--primary);
            color: #fff;
            border-radius: var(--radius-md);
            font-size: 13px;
            font-weight: 700;
            transition: background .2s;
        }
        .drop-zone:hover .dz-btn {
            background: var(--primary-hover);
        }

        #fileInput {
            display: none;
        }

        /* File bar */
        .file-bar {
            display: none;
            align-items: center;
            gap: 14px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            padding: 14px 18px;
            border-radius: var(--radius-lg);
            margin-top: 16px;
        }
        .file-bar.show {
            display: flex;
        }
        .file-bar-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,.2);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .file-meta {
            flex: 1;
            min-width: 0;
        }
        .file-name {
            font-weight: 700;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .file-size {
            font-size: 12px;
            opacity: .85;
            margin-top: 2px;
        }

        /* ── FORM GRID ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .required {
            color: var(--danger);
        }
        .input-wrap {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--light);
            font-size: 18px;
            pointer-events: none;
            transition: color .2s;
        }
        .input-wrap:focus-within .input-icon {
            color: var(--primary);
        }
        .form-control {
            width: 100%;
            padding: 12px 16px 12px 42px;
            border: 1.5px solid var(--border);
            border-radius: var(--radius-lg);
            font-family: var(--font-sans);
            font-size: 14px;
            font-weight: 500;
            color: var(--dark);
            background: var(--surface);
            transition: border-color .2s, box-shadow .2s;
            appearance: none;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-glow);
        }

        /* ── CALL STATUS SELECT ── */
        .status-wrap {
            position: relative;
        }
        .status-dot {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--border);
            pointer-events: none;
            z-index: 2;
            transition: background .2s, box-shadow .2s;
        }
        .status-wrap select.form-control {
            padding-left: 34px;
            padding-right: 42px;
            cursor: pointer;
        }
        .select-arrow {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--light);
            font-size: 20px;
            pointer-events: none;
        }

        /* dot states */
        .dot-pending  { background: #f59e0b !important; box-shadow: 0 0 0 3px rgba(245,158,11,.2); }
        .dot-confirm  { background: #10b981 !important; box-shadow: 0 0 0 3px rgba(16,185,129,.2); }
        .dot-reject   { background: #ef4444 !important; box-shadow: 0 0 0 3px rgba(239,68,68,.2); }
        .dot-noanswer { background: #8b5cf6 !important; box-shadow: 0 0 0 3px rgba(139,92,246,.2); }
        .dot-other    { background: #64748b !important; box-shadow: 0 0 0 3px rgba(100,116,139,.2); }

        /* ── REDIRECT BANNER ── */
        .redirect-banner {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            border-radius: var(--radius-lg);
            margin-top: 12px;
            border: 1.5px solid transparent;
            flex-wrap: wrap;
            animation: slideDown .22s var(--ease);
        }
        @keyframes slideDown {
            from { opacity:0; transform:translateY(-8px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .redirect-banner.show {
            display: flex;
        }

        .banner-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .banner-icon {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }
        .banner-text {
            font-size: 13px;
            font-weight: 700;
        }
        .banner-sub  {
            font-size: 11px;
            font-weight: 500;
            opacity: .65;
            margin-top: 1px;
        }
        .banner-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 14px;
            border-radius: var(--radius-md);
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all .2s;
            white-space: nowrap;
            border: none;
            cursor: pointer;
        }
        .banner-btn:hover {
            transform: translateY(-1px);
            opacity: .9;
        }

        /* banner variants */
        .banner-pending  { background:#fffbeb; border-color:#fcd34d; color:#92400e; }
        .banner-pending  .banner-icon { background:#fef3c7; color:#d97706; }
        .banner-pending  .banner-btn  { background:#f59e0b; color:#fff; }
        .banner-confirm  { background:#f0fdf4; border-color:#86efac; color:#14532d; }
        .banner-confirm  .banner-icon { background:#dcfce7; color:#16a34a; }
        .banner-confirm  .banner-btn  { background:#10b981; color:#fff; }
        .banner-reject   { background:#fff1f2; border-color:#fca5a5; color:#7f1d1d; }
        .banner-reject   .banner-icon { background:#fee2e2; color:#dc2626; }
        .banner-reject   .banner-btn  { background:#ef4444; color:#fff; }
        .banner-noanswer { background:#f5f3ff; border-color:#c4b5fd; color:#3730a3; }
        .banner-noanswer .banner-icon { background:#ede9fe; color:#7c3aed; }
        .banner-noanswer .banner-btn  { background:#8b5cf6; color:#fff; }
        .banner-other    { background:#f8fafc; border-color:#cbd5e1; color:#334155; }
        .banner-other    .banner-icon { background:#f1f5f9; color:#64748b; }
        .banner-other    .banner-btn  { background:#64748b; color:#fff; }

        /* ── STATS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4,1fr);
            gap: 14px;
            margin-bottom: 22px;
        }
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2,1fr); }
        }
        .stat-card {
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 16px;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 0 0 var(--radius-lg) var(--radius-lg);
        }
        .stat-card:nth-child(1)::after { background: var(--primary); }
        .stat-card:nth-child(2)::after { background: var(--success); }
        .stat-card:nth-child(3)::after { background: var(--danger);  }
        .stat-card:nth-child(4)::after { background: var(--warning); }
        .stat-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--light);
            margin-bottom: 6px;
        }
        .stat-value {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -1px;
            font-family: var(--font-mono);
            color: var(--dark);
        }
        .sv-green  { color: var(--success); }
        .sv-red    { color: var(--danger);  }
        .sv-indigo { color: var(--primary); }

        /* ── COLUMN TAGS ── */
        .col-box {
            background: var(--primary-light);
            border: 1px solid #c7d2fe;
            border-radius: var(--radius-lg);
            padding: 12px 16px;
            margin-bottom: 18px;
            font-size: 13px;
        }
        .col-box strong {
            color: var(--primary);
        }
        .col-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }
        .col-tag {
            padding: 3px 10px;
            background: white;
            border: 1px solid #c7d2fe;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            color: var(--primary);
            font-family: var(--font-mono);
        }

        /* ── TABLE ── */
        .tbl-wrap {
            overflow-x: auto;
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            margin-bottom: 10px;
        }
        .preview-table {
            width: 100%;
            border-collapse: collapse;
        }
        .preview-table thead tr {
            background: var(--dark);
        }
        .preview-table th {
            padding: 11px 12px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: rgba(255,255,255,.7);
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .preview-table td {
            padding: 9px 12px;
            border-bottom: 1px solid var(--border);
            font-size: 12px;
            max-width: 160px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: var(--mid);
        }
        .preview-table td:nth-child(3) {
            color: var(--dark);
            font-weight: 600;
        }
        .preview-table td:nth-child(4) {
            font-family: var(--font-mono);
            font-size: 11px;
        }
        .preview-table tr:hover td {
            background: var(--surface-2);
        }
        .preview-table tr:last-child td {
            border-bottom: none;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
        }
        .badge-ok  { background: var(--success-light); color: #065f46; }
        .badge-bad { background: var(--danger-light);  color: #991b1b; }
        .more-row td {
            text-align: center;
            color: var(--light);
            font-style: italic;
            font-size: 12px;
            padding: 14px;
            background: var(--surface-2);
        }

        /* ── PROGRESS ── */
        .progress-wrap {
            display: none;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 18px 20px;
            margin-bottom: 20px;
        }
        .progress-wrap.show {
            display: block;
        }
        .progress-head {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }
        .progress-track {
            height: 8px;
            background: var(--border);
            border-radius: 999px;
            overflow: hidden;
        }
        .progress-fill  {
            height: 100%;
            background: linear-gradient(90deg, var(--success), #34d399);
            border-radius: 999px;
            transition: width .3s ease;
        }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 22px;
            border-radius: var(--radius-lg);
            font-size: 14px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all .2s var(--ease);
            text-decoration: none;
            white-space: nowrap;
            font-family: var(--font-sans);
        }
        .btn:disabled {
            opacity: .45;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
        .btn .material-icons-round {
            font-size: 18px;
        }

        .btn-primary  {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 14px rgba(99,102,241,.3);
        }
        .btn-primary:not(:disabled):hover  {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }
        .btn-success  {
            background: var(--success);
            color: #fff;
            box-shadow: 0 4px 14px rgba(16,185,129,.3);
        }
        .btn-success:not(:disabled):hover  {
            background: #059669;
            transform: translateY(-1px);
        }
        .btn-dark     {
            background: var(--dark);
            color: #fff;
        }
        .btn-dark:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }
        .btn-outline-danger {
            background: transparent;
            color: var(--danger);
            border: 1.5px solid #fca5a5;
        }
        .btn-outline-danger:not(:disabled):hover {
            background: var(--danger-light);
            border-color: var(--danger);
        }

        /* ── ACTION BAR ── */
        .action-bar {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            padding-top: 24px;
            border-top: 2px solid var(--border);
            flex-wrap: wrap;
        }
        @media (max-width: 576px) {
            .action-bar { flex-direction: column-reverse; }
            .btn { width: 100%; }
        }

        /* ── PREVIEW SECTION ── */
        .preview-section {
            display: none;
        }
        .preview-section.show {
            display: block;
        }
    </style>
@endpush

@section('content')
    <div class="csv-wrapper">

        {{-- PAGE HEADER --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">
                    <div class="title-icon">
                        <span class="material-icons-round" style="color:#fff;font-size:24px">upload_file</span>
                    </div>
                    Upload CSV Leads
                </h1>
                <p class="page-sub">Import from Facebook / Instagram Ads Manager and create orders in bulk</p>
            </div>
            <a href="{{ route('seller.new-order') }}" class="btn btn-dark">
                <span class="material-icons-round">arrow_back</span>
                Back
            </a>
        </div>

        {{-- INSTRUCTION ALERT --}}
        <div class="alert alert-info">
            <span class="material-icons-round">info</span>
            <div>
                Upload a CSV exported from Facebook / Instagram Ads Manager.
                Required columns: <strong>full name</strong>, <strong>phone</strong>.
                Extra columns (address, city, platform, ad name, etc.) are imported automatically.
            </div>
        </div>

        {{-- ── SECTION 1: FILE UPLOAD ── --}}
        <div class="card">
            <div class="card-head">
                <div class="card-icon ci-yellow">
                    <span class="material-icons-round" style="font-size:20px">cloud_upload</span>
                </div>
                <h2 class="card-title">Select CSV File</h2>
            </div>
            <div class="card-body">
                <div class="drop-zone" id="dropZone">
                    <div class="dz-icon-wrap">
                        <span class="material-icons-round dz-icon">upload_file</span>
                    </div>
                    <div class="dz-title">Drop your CSV file here</div>
                    <div class="dz-sub">Supports Facebook / Instagram lead export CSV files</div>
                    <div class="dz-btn">
                        <span class="material-icons-round" style="font-size:16px">folder_open</span>
                        Browse File
                    </div>
                </div>
                <input type="file" id="fileInput" accept=".csv">

                <div class="file-bar" id="fileBar">
                    <div class="file-bar-icon">
                        <span class="material-icons-round" style="font-size:22px">description</span>
                    </div>
                    <div class="file-meta">
                        <div class="file-name" id="fileName">-</div>
                        <div class="file-size" id="fileSize">-</div>
                    </div>
                    <button class="btn btn-outline-danger" id="removeFileBtn" style="padding:8px 16px;font-size:13px;">
                        <span class="material-icons-round" style="font-size:16px">close</span>
                        Remove
                    </button>
                </div>
            </div>
        </div>

        {{-- ── SECTION 2: ORDER CONFIGURATION ── --}}
        <div class="card">
            <div class="card-head">
                <div class="card-icon ci-indigo">
                    <span class="material-icons-round" style="font-size:20px">tune</span>
                </div>
                <h2 class="card-title">Order Configuration</h2>
            </div>
            <div class="card-body">
                <div class="form-grid">

                    {{-- Product Code --}}
                    <div class="form-group">
                        <label class="form-label" for="productCode">
                            <span class="material-icons-round" style="font-size:15px;color:var(--primary)">inventory_2</span>
                            Product Code <span class="required">*</span>
                        </label>
                        <div class="input-wrap">
                            <input
                                type="text"
                                id="productCode"
                                class="form-control"
                                placeholder="e.g. DX/ITM/0014"
                                autocomplete="off"
                                required>
                            <span class="material-icons-round input-icon">tag</span>
                        </div>
                    </div>

                    {{-- Default Call Status --}}
                    <div class="form-group">
                        <label class="form-label" for="callStatus">
                            <span class="material-icons-round" style="font-size:15px;color:var(--primary)">phone_in_talk</span>
                            Default Call Status <span class="required">*</span>
                        </label>

                        <div class="status-wrap">
                            <span class="status-dot" id="statusDot"></span>
                            <select
                                id="callStatus"
                                class="form-control"
                                required
                                onchange="handleCallStatusChange(this.value)">
                                <option value="">— Select Call Status —</option>
                                <option value="Pending Call"
                                        data-route="{{ route('call.index') }}"
                                        data-color="pending"
                                        data-label="Pending Call List"
                                        data-icon="schedule">
                                    📋  Pending Call
                                </option>
                                <option value="Confirm Call"
                                        data-route="{{ route('call.conform') }}"
                                        data-color="confirm"
                                        data-label="Confirm Call List"
                                        data-icon="check_circle">
                                    ✅  Confirm Call
                                </option>
                                <option value="Reject Call"
                                        data-route="{{ route('call.reject') }}"
                                        data-color="reject"
                                        data-label="Reject Call List"
                                        data-icon="cancel">
                                    ❌  Reject Call
                                </option>
                                <option value="Not Answer"
                                        data-route="{{ route('call.not_answer_call_list') }}"
                                        data-color="noanswer"
                                        data-label="Not Answer List"
                                        data-icon="phone_missed">
                                    📵  Not Answer
                                </option>
                                <option value="Other Call"
                                        data-route="{{ route('call.other_call_list') }}"
                                        data-color="other"
                                        data-label="Other Call List"
                                        data-icon="more_horiz">
                                    🔄  Other Call
                                </option>
                            </select>
                            <span class="material-icons-round select-arrow">expand_more</span>
                        </div>

                        {{-- Live redirect banner --}}
                        <div class="redirect-banner" id="redirectBanner">
                            <div class="banner-left">
                                <div class="banner-icon">
                                    <span class="material-icons-round" id="bannerIconText">phone_in_talk</span>
                                </div>
                                <div>
                                    <div class="banner-text" id="bannerTitle">-</div>
                                    <div class="banner-sub">After upload you will be redirected here</div>
                                </div>
                            </div>
                            <a href="#" id="bannerViewBtn" target="_blank" class="banner-btn">
                                <span class="material-icons-round" style="font-size:14px">open_in_new</span>
                                View List
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── SECTION 3: PREVIEW ── --}}
        <div class="card preview-section" id="previewSection">
            <div class="card-head">
                <div class="card-icon ci-green">
                    <span class="material-icons-round" style="font-size:20px">table_chart</span>
                </div>
                <h2 class="card-title">Preview Leads Data</h2>
            </div>
            <div class="card-body">

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Total Leads</div>
                        <div class="stat-value" id="totalLeads">0</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Valid</div>
                        <div class="stat-value sv-green" id="validLeads">0</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Invalid</div>
                        <div class="stat-value sv-red" id="invalidLeads">0</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Success Rate</div>
                        <div class="stat-value sv-indigo" id="successRate">0%</div>
                    </div>
                </div>

                <div class="col-box">
                    <strong>CSV Columns Detected:</strong>
                    <div class="col-tags" id="colTags"></div>
                </div>

                <div class="tbl-wrap">
                    <table class="preview-table">
                        <thead id="tableHeader"></thead>
                        <tbody id="previewTableBody"></tbody>
                    </table>
                </div>

            </div>
        </div>

        {{-- PROGRESS BAR --}}
        <div class="progress-wrap" id="progressWrap">
            <div class="progress-head">
                <span id="progressText">Uploading leads…</span>
                <span id="progressPct">0%</span>
            </div>
            <div class="progress-track">
                <div class="progress-fill" id="progressFill" style="width:0%"></div>
            </div>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="action-bar">
            <button class="btn btn-outline-danger" id="clearBtn" disabled>
                <span class="material-icons-round">delete_sweep</span>
                Clear Data
            </button>
            <button class="btn btn-success" id="uploadBtn" disabled>
                <span class="material-icons-round">cloud_upload</span>
                Upload &amp; Create Orders
            </button>
        </div>

    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>
    <script>
        'use strict';

        // ════════════════════════════════════════════════
        // STATE
        // ════════════════════════════════════════════════
        let parsedLeads = [];
        let currentFile = null;
        let csvColumns  = [];

        // ════════════════════════════════════════════════
        // CALL STATUS COLOUR / ROUTE MAP
        // ════════════════════════════════════════════════
        const STATUS_META = {
            pending  : { dot: 'dot-pending',  banner: 'banner-pending',  icon: 'schedule',     label: 'Pending Call List'  },
            confirm  : { dot: 'dot-confirm',  banner: 'banner-confirm',  icon: 'check_circle', label: 'Confirm Call List'  },
            reject   : { dot: 'dot-reject',   banner: 'banner-reject',   icon: 'cancel',       label: 'Reject Call List'   },
            noanswer : { dot: 'dot-noanswer', banner: 'banner-noanswer', icon: 'phone_missed', label: 'Not Answer List'    },
            other    : { dot: 'dot-other',    banner: 'banner-other',    icon: 'more_horiz',   label: 'Other Call List'    },
        };

        function handleCallStatusChange(value) {
            const dot    = document.getElementById('statusDot');
            const banner = document.getElementById('redirectBanner');

            dot.className    = 'status-dot';
            banner.className = 'redirect-banner';

            if (!value) return;

            const sel  = document.getElementById('callStatus');
            const opt  = sel.querySelector(`option[value="${value.replace(/"/g,'&quot;')}"]`) ||
                [...sel.options].find(o => o.value === value);
            const color = opt?.dataset.color  || 'other';
            const route = opt?.dataset.route  || '#';
            const meta  = STATUS_META[color]  || STATUS_META.other;

            dot.classList.add(meta.dot);
            banner.classList.add('show', meta.banner);
            document.getElementById('bannerIconText').textContent = meta.icon;
            document.getElementById('bannerTitle').textContent    = meta.label;
            document.getElementById('bannerViewBtn').href         = route;
        }

        // ════════════════════════════════════════════════
        // FILE HANDLING
        // ════════════════════════════════════════════════
        const dropZone  = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const fileBar   = document.getElementById('fileBar');

        dropZone.addEventListener('click', () => fileInput.click());
        dropZone.addEventListener('dragover',  e => { e.preventDefault(); dropZone.classList.add('dragover'); });
        dropZone.addEventListener('dragleave', ()  => dropZone.classList.remove('dragover'));
        dropZone.addEventListener('drop', e => {
            e.preventDefault(); dropZone.classList.remove('dragover');
            if (e.dataTransfer.files[0]) handleFile(e.dataTransfer.files[0]);
        });
        fileInput.addEventListener('change', e => { if (e.target.files[0]) handleFile(e.target.files[0]); });
        document.getElementById('removeFileBtn').addEventListener('click', clearAll);

        function handleFile(file) {
            if (!file.name.toLowerCase().endsWith('.csv')) {
                Swal.fire({ icon:'error', title:'Invalid File', text:'Please upload a .csv file.', confirmButtonColor:'#6366f1' });
                return;
            }
            currentFile = file;
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = fmtBytes(file.size);
            fileBar.classList.add('show');
            parseCSV(file);
        }

        function fmtBytes(b) {
            if (b < 1024)    return b + ' B';
            if (b < 1048576) return (b/1024).toFixed(1) + ' KB';
            return (b/1048576).toFixed(2) + ' MB';
        }

        // ════════════════════════════════════════════════
        // CSV PARSING  (UTF-16LE → UTF-8 fallback)
        // ════════════════════════════════════════════════
        function parseCSV(file) {
            Swal.fire({ title:'Parsing CSV…', html:'Reading file…', allowOutsideClick:false, didOpen:() => Swal.showLoading() });

            Papa.parse(file, {
                header: true, encoding:'UTF-16LE', skipEmptyLines: true,
                complete(r) {
                    if (r.data?.length) { Swal.close(); csvColumns = Object.keys(r.data[0]); processCSVData(r.data); }
                    else parseUTF8(file);
                },
                error() { parseUTF8(file); }
            });
        }

        function parseUTF8(file) {
            Papa.parse(file, {
                header: true, skipEmptyLines: true,
                complete(r) {
                    Swal.close();
                    if (!r.data?.length) {
                        Swal.fire({ icon:'error', title:'Empty File', text:'The CSV appears empty or unreadable.', confirmButtonColor:'#6366f1' });
                        return;
                    }
                    csvColumns = Object.keys(r.data[0]);
                    processCSVData(r.data);
                },
                error(err) {
                    Swal.close();
                    Swal.fire({ icon:'error', title:'Parse Error', text:err.message, confirmButtonColor:'#6366f1' });
                }
            });
        }

        // ════════════════════════════════════════════════
        // PROCESS ROWS
        // ════════════════════════════════════════════════
        function colVal(row, ...keys) {
            for (const k of keys) {
                const found = Object.keys(row).find(rk => rk.toLowerCase() === k.toLowerCase());
                if (found && row[found]?.toString().trim()) return row[found].toString().trim();
            }
            return '';
        }

        function cleanPhone(p) { return p ? p.toString().replace(/^p:/i,'').trim() : ''; }

        function processCSVData(data) {
            parsedLeads = data.map(row => {
                const phone     = cleanPhone(colVal(row, 'phone', 'Phone', 'customer_phone'));
                const workPhone = cleanPhone(colVal(row, 'work phone number', 'work_phone_number'));
                const lead = {
                    original_data:    row,
                    customer_name:    colVal(row, 'full name', 'full_name', 'Full Name', 'customer_name', 'name'),
                    customer_phone:   phone || workPhone,
                    customer_address: colVal(row, 'street address', 'Street Address', 'address', 'customer_address'),
                    city:             colVal(row, 'city', 'City', 'district'),
                    platform:         colVal(row, 'platform', 'Platform') || 'fb',
                    ad_name:          colVal(row, 'ad_name', 'ad name', 'Ad Name'),
                    ad_id:            colVal(row, 'ad_id', 'ad id'),
                    campaign_name:    colVal(row, 'campaign_name', 'campaign name'),
                    created_time:     colVal(row, 'created_time', 'Created Time', 'created time'),
                    lead_status:      colVal(row, 'lead_status', 'lead status'),
                    valid: false, status: 'pending'
                };
                if (lead.customer_name && lead.customer_phone) { lead.valid = true; lead.status = 'valid'; }
                return lead;
            });
            displayPreview();
        }

        // ════════════════════════════════════════════════
        // PREVIEW
        // ════════════════════════════════════════════════
        function esc(s) {
            return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        function displayPreview() {
            const total       = parsedLeads.length;
            const valid       = parsedLeads.filter(l=>l.valid).length;
            const invalid     = total - valid;
            const rate        = total ? ((valid/total)*100).toFixed(1) : 0;

            document.getElementById('totalLeads').textContent   = total.toLocaleString();
            document.getElementById('validLeads').textContent   = valid.toLocaleString();
            document.getElementById('invalidLeads').textContent = invalid.toLocaleString();
            document.getElementById('successRate').textContent  = rate + '%';

            // Column tags
            document.getElementById('colTags').innerHTML =
                csvColumns.map(c => `<span class="col-tag">${esc(c)}</span>`).join('');

            // Table header
            const thead       = document.getElementById('tableHeader');
            thead.innerHTML   = '';
            const hr          = document.createElement('tr');
            const coreH       = ['#','Status','Customer Name','Phone','Address','City','Platform'];
            coreH.forEach(h  => { const th = document.createElement('th'); th.textContent = h; hr.appendChild(th); });

            const extraCols   = ['ad_name','created_time','campaign_name','lead_status'];
            const activeExtra = extraCols.filter(c =>
                csvColumns.some(k => k.toLowerCase()===c || k.toLowerCase()===c.replace('_',' '))
            );
            activeExtra.forEach(c => {
                const th = document.createElement('th');
                th.textContent = c.replace(/_/g,' ').toUpperCase();
                hr.appendChild(th);
            });
            thead.appendChild(hr);

            // Table body
            const tbody = document.getElementById('previewTableBody');
            tbody.innerHTML = '';
            const show  = Math.min(parsedLeads.length, 50);

            for (let i = 0; i < show; i++) {
                const l   = parsedLeads[i];
                const row = document.createElement('tr');
                const bdg = l.valid
                    ? '<span class="badge badge-ok">✓ Valid</span>'
                    : '<span class="badge badge-bad">✗ Invalid</span>';

                let html = `
            <td>${i+1}</td>
            <td>${bdg}</td>
            <td title="${esc(l.customer_name)}">${esc(l.customer_name)||'—'}</td>
            <td title="${esc(l.customer_phone)}">${esc(l.customer_phone)||'—'}</td>
            <td title="${esc(l.customer_address)}">${esc(l.customer_address)||'—'}</td>
            <td>${esc(l.city)||'—'}</td>
            <td>${esc(l.platform)||'—'}</td>`;

                activeExtra.forEach(c => {
                    const key = csvColumns.find(k => k.toLowerCase()===c || k.toLowerCase()===c.replace('_',' '));
                    const val = l[c] || (key ? l.original_data[key] : '') || '—';
                    html += `<td title="${esc(val)}">${esc(val)}</td>`;
                });

                row.innerHTML = html;
                tbody.appendChild(row);
            }

            if (parsedLeads.length > 50) {
                const row = document.createElement('tr');
                row.className = 'more-row';
                const cols = coreH.length + activeExtra.length;
                row.innerHTML = `<td colspan="${cols}">… and ${parsedLeads.length - 50} more rows (showing first 50)</td>`;
                tbody.appendChild(row);
            }

            document.getElementById('previewSection').classList.add('show');
            document.getElementById('clearBtn').disabled  = false;
            document.getElementById('uploadBtn').disabled = false;

            Swal.fire({
                icon: 'success',
                title: 'CSV Ready',
                html: `<div style="text-align:left;line-height:1.9;">
            <p>📊 <strong>Total:</strong> ${total.toLocaleString()}</p>
            <p>✅ <strong>Valid:</strong> ${valid.toLocaleString()}</p>
            ${invalid > 0 ? `<p>⚠️ <strong>Invalid (skipped):</strong> ${invalid.toLocaleString()}</p>` : ''}
            <p>📋 <strong>Columns:</strong> ${csvColumns.length}</p>
        </div>`,
                confirmButtonColor: '#6366f1',
                confirmButtonText: 'Continue ›'
            });
        }

        // ════════════════════════════════════════════════
        // UPLOAD
        // ════════════════════════════════════════════════
        document.getElementById('uploadBtn').addEventListener('click', async function () {

            const productCode = document.getElementById('productCode').value.trim();
            const callStatus  = document.getElementById('callStatus').value;

            if (!productCode) {
                Swal.fire({ icon:'error', title:'Product Code Required', text:'Please enter a product code.', confirmButtonColor:'#6366f1' });
                return;
            }
            if (!callStatus) {
                Swal.fire({ icon:'error', title:'Call Status Required', text:'Please select a default call status.', confirmButtonColor:'#6366f1' });
                return;
            }

            const validLeads = parsedLeads.filter(l => l.valid);
            if (!validLeads.length) {
                Swal.fire({ icon:'warning', title:'No Valid Leads', text:'No valid leads found. Ensure the CSV has "full name" and "phone" columns.', confirmButtonColor:'#6366f1' });
                return;
            }

            // Get redirect target from selected option
            const sel   = document.getElementById('callStatus');
            const opt   = [...sel.options].find(o => o.value === callStatus);
            const targetRoute = opt?.dataset.route || '{{ route("call.index") }}';
            const targetLabel = opt?.dataset.label || 'Call List';

            const conf = await Swal.fire({
                title: 'Confirm Upload',
                html: `<div style="text-align:left;line-height:2;">
            <p>📦 <strong>Product:</strong> ${esc(productCode)}</p>
            <p>📞 <strong>Call Status:</strong> ${esc(callStatus)}</p>
            <p>✅ <strong>Orders to create:</strong> ${validLeads.length.toLocaleString()}</p>
            <hr style="border-color:#e2e8f0;margin:12px 0;">
            <p style="font-size:13px;color:#64748b;">
                After upload you will be redirected to <strong>${esc(targetLabel)}</strong>.
            </p>
        </div>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981', cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Upload!', cancelButtonText: 'Cancel'
            });

            if (!conf.isConfirmed) return;

            // Show animated progress
            const progressWrap = document.getElementById('progressWrap');
            const progressFill = document.getElementById('progressFill');
            const progressText = document.getElementById('progressText');
            const progressPct  = document.getElementById('progressPct');

            progressWrap.classList.add('show');
            progressFill.style.width = '0%';
            progressText.textContent = `Uploading ${validLeads.length.toLocaleString()} leads…`;
            progressPct.textContent  = '0%';
            this.disabled = true;

            let fake = 0;
            const ticker = setInterval(() => {
                if (fake < 82) {
                    fake += Math.random() * 9;
                    const p = Math.min(fake, 82);
                    progressFill.style.width = p + '%';
                    progressPct.textContent  = Math.round(p) + '%';
                }
            }, 280);

            try {
                const response = await fetch('{{ route("seller.order.csv-insert") }}', {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':'{{ csrf_token() }}' },
                    body: JSON.stringify({
                        leads:        validLeads.map(l => ({ ...l, call_status: callStatus })),
                        product_code: productCode,
                        call_status:  callStatus
                    })
                });

                clearInterval(ticker);

                if (!response.ok) {
                    const e = await response.json().catch(() => ({}));
                    throw new Error(e.message || `Server error ${response.status}`);
                }

                const data = await response.json();

                progressFill.style.width = '100%';
                progressPct.textContent  = '100%';
                progressText.textContent = 'Upload complete!';

                await new Promise(r => setTimeout(r, 500));
                progressWrap.classList.remove('show');
                this.disabled = false;

                if (data.success) {
                    await Swal.fire({
                        icon: 'success',
                        title: 'Upload Complete! 🎉',
                        html: `<div style="text-align:left;line-height:2;">
                    <p>✅ <strong>Orders created:</strong> ${data.successCount}</p>
                    ${data.errorCount > 0 ? `<p>⚠️ <strong>Failed:</strong> ${data.errorCount}</p>` : ''}
                    <p style="margin-top:10px;font-size:13px;color:#64748b;">
                        Redirecting to <strong>${esc(targetLabel)}</strong>…
                    </p>
                </div>`,
                        confirmButtonColor: '#6366f1',
                        timer: 2800,
                        timerProgressBar: true
                    });

                    // ✅ Navigate to the correct call status page
                    window.location.href = targetRoute;

                } else {
                    throw new Error(data.message || 'Upload failed');
                }

            } catch (err) {
                clearInterval(ticker);
                progressWrap.classList.remove('show');
                this.disabled = false;

                Swal.fire({
                    icon: 'error',
                    title: 'Upload Failed',
                    html: `<p>${esc(err.message)}</p>`,
                    confirmButtonColor: '#6366f1'
                });
            }
        });

        // ════════════════════════════════════════════════
        // CLEAR ALL
        // ════════════════════════════════════════════════
        document.getElementById('clearBtn').addEventListener('click', () => {
            Swal.fire({
                title:'Clear All?', text:'All parsed leads will be removed.',
                icon:'warning', showCancelButton:true,
                confirmButtonColor:'#ef4444', cancelButtonColor:'#64748b',
                confirmButtonText:'Yes, Clear', cancelButtonText:'Cancel'
            }).then(r => { if (r.isConfirmed) clearAll(); });
        });

        function clearAll() {
            parsedLeads = []; currentFile = null; csvColumns = [];
            fileInput.value = '';
            document.getElementById('fileBar').classList.remove('show');
            document.getElementById('previewSection').classList.remove('show');
            document.getElementById('progressWrap').classList.remove('show');
            document.getElementById('clearBtn').disabled  = true;
            document.getElementById('uploadBtn').disabled = true;
            document.getElementById('productCode').value  = '';
            document.getElementById('callStatus').value   = '';
            document.getElementById('statusDot').className      = 'status-dot';
            document.getElementById('redirectBanner').className = 'redirect-banner';
        }
    </script>
@endpush
