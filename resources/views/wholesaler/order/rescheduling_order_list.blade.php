@extends('wholesaler.layouts.app')

@push('title')
    Rescheduling Orders
@endpush

@push('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        /* ─── DESIGN TOKENS ─────────────────────────────────────────────── */
        :root {
            --ink:        #0a0e1a;
            --ink-2:      #1e2535;
            --ink-3:      #3a4460;
            --muted:      #7f8ea3;
            --muted-2:    #b0bbc9;
            --border:     #e4e9f0;
            --surface:    #ffffff;
            --bg:         #f3f5fb;
            --bg-2:       #eaecf7;

            --accent:     #4f46e5;
            --accent-glow:rgba(79,70,229,0.18);
            --accent-2:   #7c3aed;
            --cyan:       #06b6d4;
            --amber:      #f59e0b;
            --emerald:    #10b981;
            --rose:       #f43f5e;

            --radius-sm:  6px;
            --radius:     12px;
            --radius-lg:  20px;
            --radius-xl:  28px;

            --shadow-xs:  0 1px 3px rgba(0,0,0,0.06);
            --shadow-sm:  0 2px 8px rgba(0,0,0,0.08);
            --shadow:     0 4px 20px rgba(0,0,0,0.10);
            --shadow-lg:  0 12px 40px rgba(0,0,0,0.14);

            --transition: 0.22s cubic-bezier(0.4,0,0.2,1);
        }

        /* ─── RESET & BASE ───────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        /* ─── GRID WRAPPER ───────────────────────────────────────────────── */
        .rsc-wrapper {
            padding: 2rem;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* ─── HERO HEADER ────────────────────────────────────────────────── */
        .rsc-hero {
            position: relative;
            border-radius: var(--radius-xl);
            padding: 2.5rem 2.75rem;
            background: var(--ink-2);
            overflow: hidden;
            margin-bottom: 1.75rem;
            isolation: isolate;
        }

        /* mesh blobs */
        .rsc-hero::before,
        .rsc-hero::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            pointer-events: none;
            z-index: -1;
        }
        .rsc-hero::before {
            width: 340px; height: 340px;
            background: radial-gradient(circle, rgba(79,70,229,0.55) 0%, transparent 70%);
            top: -80px; right: 80px;
        }
        .rsc-hero::after {
            width: 260px; height: 260px;
            background: radial-gradient(circle, rgba(124,58,237,0.40) 0%, transparent 70%);
            bottom: -60px; right: -30px;
        }

        .rsc-hero-grid {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 1.5rem;
        }

        .rsc-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            margin-bottom: 0.6rem;
        }
        .rsc-eyebrow .dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--amber);
            animation: pulse-dot 2s infinite;
        }
        @keyframes pulse-dot {
            0%,100% { opacity:1; transform:scale(1); }
            50%      { opacity:0.5; transform:scale(1.4); }
        }

        .rsc-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(22px, 3vw, 32px);
            font-weight: 800;
            color: #fff;
            margin: 0 0 0.4rem;
            line-height: 1.2;
        }

        .rsc-subtitle {
            font-size: 14px;
            color: rgba(255,255,255,0.55);
            margin: 0;
        }

        .rsc-hero-count {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 4px;
        }

        .rsc-big-num {
            font-family: 'Syne', sans-serif;
            font-size: clamp(48px, 6vw, 72px);
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }

        .rsc-big-label {
            font-size: 12px;
            color: rgba(255,255,255,0.45);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }

        /* ─── STAT CARDS ─────────────────────────────────────────────────── */
        .rsc-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .stat-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 1.5rem 1.75rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-xs);
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: transform var(--transition), box-shadow var(--transition);
            cursor: default;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow);
        }

        .stat-icon-wrap {
            flex-shrink: 0;
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stat-icon-wrap .material-icons-round { font-size: 24px; }
        .stat-icon-wrap.blue   { background: rgba(79,70,229,0.10); color: var(--accent); }
        .stat-icon-wrap.amber  { background: rgba(245,158,11,0.12); color: var(--amber); }
        .stat-icon-wrap.emerald{ background: rgba(16,185,129,0.12); color: var(--emerald); }

        .stat-body { flex: 1; min-width: 0; }
        .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 4px;
        }
        .stat-value {
            font-family: 'Syne', sans-serif;
            font-size: 26px;
            font-weight: 800;
            color: var(--ink);
            line-height: 1.1;
        }
        .stat-sub {
            font-size: 11px;
            color: var(--muted-2);
            margin-top: 2px;
        }

        /* ─── TABS ───────────────────────────────────────────────────────── */
        .rsc-tabs {
            display: flex;
            gap: 4px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 5px;
            margin-bottom: 1.75rem;
            overflow-x: auto;
            white-space: nowrap;
            scrollbar-width: none;
            box-shadow: var(--shadow-xs);
        }
        .rsc-tabs::-webkit-scrollbar { display: none; }

        .rsc-tab {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: var(--radius);
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            text-decoration: none;
            transition: background var(--transition), color var(--transition);
            white-space: nowrap;
        }
        .rsc-tab:hover {
            background: var(--bg);
            color: var(--ink);
        }
        .rsc-tab.active {
            background: var(--ink-2);
            color: #fff;
            box-shadow: 0 2px 8px rgba(10,14,26,0.22);
        }
        .rsc-tab .material-icons-round { font-size: 17px; }
        .rsc-tab .tab-badge {
            background: var(--accent);
            color: #fff;
            border-radius: 100px;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 7px;
            line-height: 18px;
        }
        .rsc-tab.active .tab-badge { background: rgba(255,255,255,0.25); }

        /* ─── MAIN TABLE CARD ────────────────────────────────────────────── */
        .rsc-card {
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .rsc-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.4rem 1.75rem;
            border-bottom: 1px solid var(--border);
            gap: 1rem;
            flex-wrap: wrap;
        }

        .rsc-card-title {
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }
        .rsc-card-title .material-icons-round {
            font-size: 20px;
            color: var(--accent);
        }

        .rsc-header-actions { display: flex; gap: 8px; align-items: center; }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: transparent;
            color: var(--ink-3);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition);
            text-decoration: none;
        }
        .btn-ghost:hover { background: var(--bg); border-color: var(--ink-3); color: var(--ink); }
        .btn-ghost .material-icons-round { font-size: 16px; }

        .btn-accent {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: var(--radius);
            background: var(--accent);
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all var(--transition);
            text-decoration: none;
        }
        .btn-accent:hover { background: #4338ca; transform: translateY(-1px); box-shadow: 0 4px 14px var(--accent-glow); color: #fff; }
        .btn-accent .material-icons-round { font-size: 16px; }

        /* ─── DATATABLE WRAPPER ──────────────────────────────────────────── */
        .dt-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.75rem;
            gap: 1rem;
            flex-wrap: wrap;
            border-bottom: 1px solid var(--border);
        }

        .dt-controls-left, .dt-controls-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dt-search-wrap {
            position: relative;
        }
        .dt-search-wrap .material-icons-round {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: var(--muted);
            pointer-events: none;
        }
        .dt-search-wrap input {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 8px 12px 8px 36px;
            font-size: 13px;
            color: var(--ink);
            width: 220px;
            transition: all var(--transition);
            background: var(--bg);
            font-family: inherit;
        }
        .dt-search-wrap input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
            background: var(--surface);
        }

        .dt-length-select {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--muted);
        }
        .dt-length-select select {
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 6px 10px;
            font-size: 13px;
            background: var(--bg);
            cursor: pointer;
            font-family: inherit;
            color: var(--ink);
        }

        /* ─── TABLE ──────────────────────────────────────────────────────── */
        .rsc-table-wrap { overflow-x: auto; }

        table.rsc-table {
            width: 100% !important;
            border-collapse: collapse;
        }

        table.rsc-table thead tr {
            background: var(--bg);
        }
        table.rsc-table thead th {
            padding: 12px 16px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
            cursor: pointer;
            user-select: none;
        }
        table.rsc-table thead th:hover { color: var(--ink); }
        table.rsc-table thead th.sorting::after,
        table.rsc-table thead th.sorting_asc::after,
        table.rsc-table thead th.sorting_desc::after {
            font-family: 'Material Icons Round';
            font-size: 14px;
            margin-left: 4px;
            vertical-align: middle;
        }

        table.rsc-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            font-size: 13.5px;
        }
        table.rsc-table tbody tr:last-child td { border-bottom: none; }
        table.rsc-table tbody tr {
            transition: background var(--transition);
        }
        table.rsc-table tbody tr:hover {
            background: #f7f8ff;
        }

        /* ─── TABLE CELLS ────────────────────────────────────────────────── */

        /* Order Number */
        .order-id {
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: var(--accent);
            letter-spacing: 0.02em;
        }

        /* Product */
        .product-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .product-img {
            width: 42px; height: 42px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid var(--border);
            background: var(--bg);
            flex-shrink: 0;
        }
        .product-no-img {
            width: 42px; height: 42px;
            border-radius: 10px;
            background: var(--bg-2);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .product-no-img .material-icons-round { font-size: 20px; color: var(--muted-2); }
        .product-name {
            font-weight: 600;
            color: var(--ink);
            font-size: 13px;
            max-width: 160px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.3;
        }
        .product-sku {
            font-size: 11px;
            color: var(--muted-2);
            font-family: monospace;
            margin-top: 2px;
        }

        /* Customer */
        .customer-name { font-weight: 600; color: var(--ink); line-height: 1.3; }
        .customer-meta { font-size: 11.5px; color: var(--muted); margin-top: 2px; }

        /* Quantity pill */
        .qty-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px; height: 32px;
            border-radius: 8px;
            background: var(--bg-2);
            font-weight: 700;
            font-size: 14px;
            color: var(--ink-2);
            font-family: 'Syne', sans-serif;
        }

        /* Date cell */
        .date-primary { font-size: 13px; font-weight: 500; color: var(--ink); }
        .date-sub     { font-size: 11px; color: var(--muted-2); margin-top: 2px; }

        /* Reschedule date pill */
        .resched-date {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
            line-height: 1.3;
        }
        .date-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 5px;
            padding: 3px 10px;
            border-radius: 100px;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .date-tag .material-icons-round { font-size: 12px; }
        .date-tag.upcoming { background: rgba(6,182,212,0.12); color: #0891b2; }
        .date-tag.today    { background: rgba(245,158,11,0.15); color: #b45309; }
        .date-tag.overdue  { background: rgba(244,63,94,0.12);  color: #be123c; }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .status-badge::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
        }
        .status-badge.rescheduled {
            background: rgba(79,70,229,0.10);
            color: var(--accent);
        }
        .status-badge.rescheduled::before { background: var(--accent); }

        /* Amount */
        .amount-val {
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: var(--ink);
        }
        .amount-cur { font-size: 11px; color: var(--muted); font-weight: 500; }

        /* Action buttons */
        .action-wrap { display: flex; gap: 6px; }
        .act-btn {
            width: 34px; height: 34px;
            border-radius: 9px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition);
            font-size: 0;
        }
        .act-btn .material-icons-round { font-size: 17px; }
        .act-btn.view   { background: rgba(79,70,229,0.10); color: var(--accent); }
        .act-btn.edit   { background: rgba(245,158,11,0.12); color: var(--amber); }
        .act-btn:hover  { transform: translateY(-2px); box-shadow: var(--shadow); }
        .act-btn.view:hover { background: var(--accent); color: #fff; }
        .act-btn.edit:hover { background: var(--amber); color: #fff; }

        /* ─── DATATABLE PAGINATION ───────────────────────────────────────── */
        .dt-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.75rem;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 1rem;
        }
        .dt-info { font-size: 12.5px; color: var(--muted); }

        .dataTables_paginate .paginate_button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px; height: 32px;
            border-radius: 8px;
            border: 1px solid var(--border) !important;
            background: transparent !important;
            color: var(--ink-3) !important;
            font-size: 13px !important;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition);
            margin: 0 2px;
        }
        .dataTables_paginate .paginate_button:hover:not(.disabled) {
            background: var(--accent) !important;
            border-color: var(--accent) !important;
            color: #fff !important;
        }
        .dataTables_paginate .paginate_button.current {
            background: var(--ink-2) !important;
            border-color: var(--ink-2) !important;
            color: #fff !important;
        }
        .dataTables_paginate .paginate_button.disabled {
            opacity: 0.35 !important;
            cursor: not-allowed !important;
        }

        /* ─── EMPTY STATE ────────────────────────────────────────────────── */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 80px 20px;
            text-align: center;
        }
        .empty-icon-wrap {
            width: 90px; height: 90px;
            background: var(--bg-2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .empty-icon-wrap .material-icons-round { font-size: 40px; color: var(--muted-2); }
        .empty-state h5 {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 8px;
        }
        .empty-state p { color: var(--muted); font-size: 14px; max-width: 300px; }

        /* ─── MODAL ──────────────────────────────────────────────────────── */
        .modal-content {
            border: none;
            border-radius: var(--radius-xl);
            box-shadow: 0 20px 60px rgba(0,0,0,0.18);
            overflow: hidden;
        }
        .modal-header {
            background: var(--ink-2);
            padding: 1.4rem 1.75rem;
            border-bottom: none;
        }
        .modal-title {
            font-family: 'Syne', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }
        .modal-header .btn-close { filter: invert(1); opacity: 0.6; }
        .modal-header .btn-close:hover { opacity: 1; }

        .modal-body { padding: 1.75rem; }
        .modal-footer { padding: 1rem 1.75rem; border-top: 1px solid var(--border); gap: 8px; }

        .form-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 6px;
        }
        .form-control, .form-select {
            border: 1.5px solid var(--border);
            border-radius: var(--radius);
            padding: 10px 14px;
            font-size: 13.5px;
            color: var(--ink);
            font-family: inherit;
            transition: all var(--transition);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
            outline: none;
        }

        .btn-secondary {
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--ink-3);
            padding: 10px 20px;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all var(--transition);
        }
        .btn-secondary:hover { background: var(--border); }

        .btn-primary {
            background: var(--accent);
            border: none;
            color: #fff;
            padding: 10px 22px;
            border-radius: var(--radius);
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all var(--transition);
        }
        .btn-primary:hover { background: #4338ca; box-shadow: 0 4px 14px var(--accent-glow); }

        /* status color strips in modal select */
        .status-indicator {
            display: flex;
            gap: 6px;
            margin-top: 8px;
            flex-wrap: wrap;
        }
        .si-chip {
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 100px;
            font-weight: 600;
            border: 1px solid;
        }
        .si-chip.pending  { background:#fef9c3; color:#854d0e; border-color:#fde047; }
        .si-chip.complete { background:#d1fae5; color:#065f46; border-color:#6ee7b7; }
        .si-chip.return   { background:#fee2e2; color:#991b1b; border-color:#fca5a5; }
        .si-chip.resched  { background:#e0e7ff; color:#3730a3; border-color:#a5b4fc; }

        /* ─── RESPONSIVE ─────────────────────────────────────────────────── */
        @media (max-width: 991px) {
            .rsc-wrapper { padding: 1rem; }
            .rsc-stats   { grid-template-columns: repeat(2, 1fr); }
            .rsc-hero    { padding: 1.75rem; }
            .rsc-hero-grid { grid-template-columns: 1fr; }
            .rsc-hero-count { align-items: flex-start; flex-direction: row; align-items: center; gap: 10px; }
            .rsc-big-num { font-size: 36px; }
        }

        @media (max-width: 576px) {
            .rsc-stats { grid-template-columns: 1fr; }
            .dt-controls { flex-direction: column; align-items: flex-start; }
            .dt-search-wrap input { width: 180px; }
        }

        /* ─── ENTRY ANIMATION ────────────────────────────────────────────── */
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(16px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .rsc-hero   { animation: fadeUp 0.4s ease both; }
        .rsc-stats  { animation: fadeUp 0.4s 0.08s ease both; }
        .rsc-tabs   { animation: fadeUp 0.4s 0.14s ease both; }
        .rsc-card   { animation: fadeUp 0.4s 0.20s ease both; }

        /* DataTables default info/length — hide default, use custom */
        .dataTables_info,
        .dataTables_length,
        .dataTables_filter,
        .dataTables_paginate {
            display: none !important; /* We build our own controls */
        }
    </style>
@endpush

@section('content')
    <div class="rsc-wrapper">

        {{-- ── HERO HEADER ─────────────────────────────────────────────────── --}}
        <div class="rsc-hero">
            <div class="rsc-hero-grid">
                <div>
                    <div class="rsc-eyebrow">
                        <span class="dot"></span>
                        Delivery Management
                    </div>
                    <h1 class="rsc-title">Rescheduling Orders</h1>
                    <p class="rsc-subtitle">Track and manage all orders that require a new delivery date</p>
                </div>
                <div class="rsc-hero-count">
                    <span class="rsc-big-num">{{ str_pad($count, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="rsc-big-label">{{ $count === 1 ? 'Order' : 'Orders' }}</span>
                </div>
            </div>
        </div>

        {{-- ── STAT CARDS ──────────────────────────────────────────────────── --}}
        <div class="rsc-stats">
            <div class="stat-card">
                <div class="stat-icon-wrap blue">
                    <span class="material-icons-round">schedule</span>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Rescheduled</div>
                    <div class="stat-value">{{ $count }}</div>
                    <div class="stat-sub">Active reschedule requests</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-wrap amber">
                    <span class="material-icons-round">payments</span>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Pending Value</div>
                    <div class="stat-value">Rs. {{ number_format($orders->sum('total_amount'), 0) }}</div>
                    <div class="stat-sub">Total order amount</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon-wrap emerald">
                    <span class="material-icons-round">event_upcoming</span>
                </div>
                <div class="stat-body">
                    <div class="stat-label">Upcoming</div>
                    <div class="stat-value">{{ $upcomingCount ?? 0 }}</div>
                    <div class="stat-sub">Scheduled for future dates</div>
                </div>
            </div>
        </div>

        {{-- ── NAV TABS ─────────────────────────────────────────────────────── --}}
        <div class="rsc-tabs">
            <a href="{{ route('wholesaler.order.pending-order') }}" class="rsc-tab">
                <span class="material-icons-round">pending_actions</span>
                Pending
            </a>
            <a href="{{ route('wholesaler.order.complete-order') }}" class="rsc-tab">
                <span class="material-icons-round">check_circle</span>
                Completed
            </a>
            <a href="{{ route('wholesaler.order.return-order') }}" class="rsc-tab">
                <span class="material-icons-round">assignment_return</span>
                Returns
            </a>
            <a href="{{ route('wholesaler.order.rescheduling-order') }}" class="rsc-tab active">
                <span class="material-icons-round">schedule</span>
                Rescheduling
                @if($count > 0)
                    <span class="tab-badge">{{ $count }}</span>
                @endif
            </a>
        </div>

        {{-- ── TABLE CARD ───────────────────────────────────────────────────── --}}
        <div class="rsc-card">
            <div class="rsc-card-header">
                <h3 class="rsc-card-title">
                    <span class="material-icons-round">calendar_month</span>
                    Rescheduled Orders
                </h3>
                <div class="rsc-header-actions">
                    <button class="btn-ghost" onclick="window.location.reload()">
                        <span class="material-icons-round">refresh</span>
                        Refresh
                    </button>
                    <a href="{{ route('wholesaler.order.pending-order') }}" class="btn-accent">
                        <span class="material-icons-round">add_circle</span>
                        View All
                    </a>
                </div>
            </div>

            @if($count > 0)

                {{-- Custom DataTable Controls --}}
                <div class="dt-controls">
                    <div class="dt-controls-left">
                        <div class="dt-length-select">
                            Show
                            <select id="customLength">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            entries
                        </div>
                    </div>
                    <div class="dt-controls-right">
                        <div class="dt-search-wrap">
                            <span class="material-icons-round">search</span>
                            <input type="text" id="customSearch" placeholder="Search orders…">
                        </div>
                    </div>
                </div>

                <div class="rsc-table-wrap">
                    <table id="ordersTable" class="rsc-table table">
                        <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Qty</th>
                            <th>Order Date</th>
                            <th>Original Delivery</th>
                            <th>Rescheduled Date</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th style="width:90px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            @php
                                $rescheduleDate = $order->rescheduling_date ? \Carbon\Carbon::parse($order->rescheduling_date) : null;
                                $dateClass  = 'upcoming';
                                $dateLabel  = 'Upcoming';
                                $dateIcon   = 'event';
                                if ($rescheduleDate) {
                                    if ($rescheduleDate->isToday()) {
                                        $dateClass = 'today'; $dateLabel = 'Today'; $dateIcon = 'notifications_active';
                                    } elseif ($rescheduleDate->isPast()) {
                                        $dateClass = 'overdue'; $dateLabel = 'Overdue'; $dateIcon = 'warning';
                                    }
                                }
                            @endphp
                            <tr>
                                {{-- Order Number --}}
                                <td>
                                    <span class="order-id">{{ $order->order_number }}</span>
                                </td>

                                {{-- Product --}}
                                <td>
                                    <div class="product-cell">
                                        @if($order->product_image)
                                            <img src="{{ asset('storage/' . $order->product_image) }}"
                                                 alt="{{ $order->product_name }}"
                                                 class="product-img"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="product-no-img" style="display:none;">
                                                <span class="material-icons-round">inventory_2</span>
                                            </div>
                                        @else
                                            <div class="product-no-img">
                                                <span class="material-icons-round">inventory_2</span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="product-name" title="{{ $order->product_name }}">{{ $order->product_name }}</div>
                                            <div class="product-sku">{{ $order->product_code }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Customer --}}
                                <td>
                                    <div class="customer-name">{{ $order->customer_name }}</div>
                                    <div class="customer-meta">{{ $order->customer_phone }}</div>
                                    @if($order->district)
                                        <div class="customer-meta">
                                            <span class="material-icons-round" style="font-size:11px;vertical-align:middle;">location_on</span>
                                            {{ $order->district }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Qty --}}
                                <td><span class="qty-pill">{{ $order->quantity }}</span></td>

                                {{-- Order Date --}}
                                <td>
                                    <div class="date-primary">
                                        {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d M Y') : '—' }}
                                    </div>
                                    @if($order->order_date)
                                        <div class="date-sub">
                                            {{ \Carbon\Carbon::parse($order->order_date)->diffForHumans() }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Original Delivery --}}
                                <td>
                                    <div class="date-primary">
                                        {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') : '—' }}
                                    </div>
                                </td>

                                {{-- Rescheduled Date --}}
                                <td>
                                    @if($order->rescheduling_date)
                                        <div class="resched-date">
                                            {{ \Carbon\Carbon::parse($order->rescheduling_date)->format('d M Y') }}
                                        </div>
                                        <div>
                                        <span class="date-tag {{ $dateClass }}">
                                            <span class="material-icons-round">{{ $dateIcon }}</span>
                                            {{ $dateLabel }}
                                        </span>
                                        </div>
                                    @else
                                        <span style="color:var(--muted-2);">—</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td>
                                    <span class="status-badge rescheduled">Rescheduled</span>
                                </td>

                                {{-- Amount --}}
                                <td>
                                    <div class="amount-cur">LKR</div>
                                    <div class="amount-val">{{ number_format($order->total_amount, 2) }}</div>
                                </td>

                                {{-- Actions --}}
                                <td>
                                    <div class="action-wrap">
                                        <button class="act-btn view"
                                                onclick="viewOrder({{ $order->id }})"
                                                title="View Details">
                                            <span class="material-icons-round">visibility</span>
                                        </button>
                                        <button class="act-btn edit"
                                                onclick="editOrder({{ $order->id }})"
                                                title="Update Status">
                                            <span class="material-icons-round">edit</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Custom Footer / Pagination --}}
                <div class="dt-footer">
                    <div class="dt-info" id="customInfo"></div>
                    <div id="customPaginate"></div>
                </div>

            @else
                <div class="empty-state">
                    <div class="empty-icon-wrap">
                        <span class="material-icons-round">inbox</span>
                    </div>
                    <h5>No Rescheduled Orders</h5>
                    <p>There are no orders awaiting rescheduling at the moment.</p>
                    <a href="{{ route('wholesaler.order.pending-order') }}" class="btn-accent mt-3">
                        <span class="material-icons-round">pending_actions</span>
                        View Pending Orders
                    </a>
                </div>
            @endif
        </div>

    </div>

    {{-- ── UPDATE MODAL ─────────────────────────────────────────────────────── --}}
    <div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="material-icons-round">edit_calendar</span>
                        Update Order Status
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="updateForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="pending">Pending</option>
                                <option value="complete">Complete</option>
                                <option value="return">Return</option>
                                <option value="rescheduling" selected>Rescheduling</option>
                            </select>
                            <div class="status-indicator">
                                <span class="si-chip pending">Pending</span>
                                <span class="si-chip complete">Complete</span>
                                <span class="si-chip return">Return</span>
                                <span class="si-chip resched">Rescheduling</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Call Status</label>
                            <select name="call_status" class="form-select">
                                <option value="Pending Call">Pending Call</option>
                                <option value="Called">Called</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Not Answered">Not Answered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Rescheduling Date</label>
                            <input type="date" name="rescheduling_date" class="form-control"
                                   value="{{ date('Y-m-d') }}"
                                   min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Remark / Notes</label>
                            <textarea name="remark" class="form-control" rows="3"
                                      placeholder="Add notes about this reschedule…"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary">
                            <span class="material-icons-round" style="font-size:16px;vertical-align:middle;margin-right:4px;">save</span>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            @if($count > 0)

            /* ── Init DataTable ── */
            const dt = $('#ordersTable').DataTable({
                order: [[6, 'asc']],
                pageLength: 10,
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: [1, 9] }
                ],
                drawCallback: function () {
                    const api  = this.api();
                    const info = api.page.info();
                    const from = info.recordsDisplay === 0 ? 0 : info.start + 1;
                    const to   = Math.min(info.end, info.recordsDisplay);
                    const total = info.recordsTotal;

                    /* Info text */
                    $('#customInfo').text(
                        `Showing ${from}–${to} of ${info.recordsDisplay} orders` +
                        (info.recordsDisplay < total ? ` (filtered from ${total})` : '')
                    );

                    /* Pagination buttons */
                    buildPagination(api, info);
                }
            });

            /* ── Custom search ── */
            $('#customSearch').on('input', function () {
                dt.search(this.value).draw();
            });

            /* ── Custom length ── */
            $('#customLength').on('change', function () {
                dt.page.len(parseInt(this.value)).draw();
            });

            /* ── Build pagination ── */
            function buildPagination(api, info) {
                const $pg = $('#customPaginate').empty();
                if (info.pages <= 1) return;

                function btn(label, page, disabled, current) {
                    const $b = $('<button>')
                        .addClass('paginate_button' + (disabled ? ' disabled' : '') + (current ? ' current' : ''))
                        .html(label);
                    if (!disabled && !current) {
                        $b.on('click', function () { api.page(page).draw('page'); });
                    }
                    $pg.append($b);
                }

                /* Prev */
                btn('<span class="material-icons-round" style="font-size:15px;line-height:1;">chevron_left</span>',
                    info.page - 1, info.page === 0, false);

                /* Pages */
                const current = info.page;
                const total   = info.pages;
                let pages     = [];

                if (total <= 7) {
                    pages = [...Array(total).keys()];
                } else {
                    pages = [0];
                    if (current > 2) pages.push('...');
                    const start = Math.max(1, current - 1);
                    const end   = Math.min(total - 2, current + 1);
                    for (let i = start; i <= end; i++) pages.push(i);
                    if (current < total - 3) pages.push('...');
                    pages.push(total - 1);
                }

                pages.forEach(p => {
                    if (p === '...') {
                        $('<span>').css({ padding:'0 4px', color:'var(--muted-2)', lineHeight:'32px' }).text('…').appendTo($pg);
                    } else {
                        btn(p + 1, p, false, p === current);
                    }
                });

                /* Next */
                btn('<span class="material-icons-round" style="font-size:15px;line-height:1;">chevron_right</span>',
                    info.page + 1, info.page === total - 1, false);
            }

            @endif

            /* ── Flash alerts ── */
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(el => {
                    el.style.transition = 'opacity 0.4s';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 400);
                });
            }, 4000);
        });

        /* ── Order Actions ── */
        function viewOrder(id) {
            window.location.href = `/wholesaler/order/show/${id}`;
        }

        function editOrder(id) {
            $('#updateForm').attr('action', `/wholesaler/order/update/${id}`);
            const modal = new bootstrap.Modal(document.getElementById('updateModal'));
            modal.show();
        }

        /* ── SweetAlert2 Flash Messages ── */
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Done!',
            text: '{{ session('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
        });
        @endif
        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: '{{ session('error') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
        });
        @endif
    </script>
@endpush
