@extends('employee.layout.app')

@push('title')
    Employee Dashboard
@endpush

@push('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #00c896;
            --primary-dark: #00a078;
            --primary-light: #e0fdf4;
            --primary-glow: rgba(0, 200, 150, 0.18);
            --accent: #ff6b35;
            --accent-light: #fff3ee;
            --purple: #7c3aed;
            --purple-light: #ede9fe;
            --blue: #2563eb;
            --blue-light: #dbeafe;
            --amber: #f59e0b;
            --amber-light: #fef3c7;
            --bg: #f1f5f9;
            --card: #ffffff;
            --card-border: rgba(15, 23, 42, 0.06);
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --radius: 18px;
            --radius-sm: 12px;
            --shadow: 0 6px 24px rgba(15, 23, 42, 0.07);
            --shadow-hover: 0 16px 40px rgba(15, 23, 42, 0.12);
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
        }

        .dash {
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            padding: 24px;
        }

        /* ===== HEADER ===== */
        .dash-header {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
            border-radius: var(--radius);
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.22);
        }

        .dash-header::before {
            content: '';
            position: absolute;
            top: -90px;
            right: -70px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 200, 150, 0.22) 0%, transparent 70%);
            pointer-events: none;
        }

        .dash-header::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: 8%;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.16) 0%, transparent 70%);
            pointer-events: none;
        }

        .header-inner {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            flex-wrap: wrap;
        }

        .header-left {
            min-width: 0;
            flex: 1 1 420px;
        }

        .header-left h1 {
            font-size: clamp(24px, 3vw, 34px);
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.6px;
            line-height: 1.15;
            margin-bottom: 8px;
            word-break: break-word;
        }

        .header-left p {
            color: rgba(255,255,255,0.68);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 0;
        }

        .header-time {
            font-family: 'DM Mono', monospace;
            font-size: 11px;
            color: rgba(255,255,255,0.42);
            margin-top: 12px;
            letter-spacing: 0.4px;
            line-height: 1.5;
        }

        .header-badges {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
            flex: 0 1 auto;
        }

        .h-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255,255,255,0.10);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.18);
            color: rgba(255,255,255,0.9);
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }

        .h-badge.primary {
            background: rgba(0,200,150,0.24);
            border-color: rgba(0,200,150,0.38);
            color: #6effd8;
        }

        /* ===== STATS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--card);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            padding: 22px 20px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            min-width: 0;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 3px;
            border-radius: 0 0 var(--radius) var(--radius);
            background: var(--bar-color, var(--primary));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .35s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .stat-card:hover::after {
            transform: scaleX(1);
        }

        .stat-card[data-color="green"]  { --bar-color: var(--primary); }
        .stat-card[data-color="amber"]  { --bar-color: var(--amber); }
        .stat-card[data-color="purple"] { --bar-color: var(--purple); }
        .stat-card[data-color="blue"]   { --bar-color: var(--blue); }
        .stat-card[data-color="orange"] { --bar-color: var(--accent); }
        .stat-card[data-color="violet"] { --bar-color: #a855f7; }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 16px;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-muted);
            line-height: 1.4;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .stat-icon.green  { background: var(--primary-light); color: var(--primary-dark); }
        .stat-icon.amber  { background: var(--amber-light); color: #b45309; }
        .stat-icon.purple { background: var(--purple-light); color: var(--purple); }
        .stat-icon.blue   { background: var(--blue-light); color: var(--blue); }
        .stat-icon.orange { background: var(--accent-light); color: var(--accent); }
        .stat-icon.violet { background: #f5f3ff; color: #7c3aed; }

        .stat-value {
            font-size: clamp(22px, 2vw, 28px);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 8px;
            color: var(--text-primary);
            font-variant-numeric: tabular-nums;
            overflow-wrap: anywhere;
        }

        .stat-sub {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            line-height: 1.5;
            flex-wrap: wrap;
        }

        .stat-sub .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ===== CONTENT GRID ===== */
        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 340px;
            gap: 22px;
            align-items: start;
        }

        .main-col,
        .sidebar-col {
            min-width: 0;
        }

        /* ===== GENERIC CARD ===== */
        .card {
            background: var(--card);
            border: 1px solid var(--card-border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 22px;
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: var(--shadow-hover);
        }

        .card-head {
            padding: 22px 24px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .card-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--text-primary);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .card-title-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }

        .card-title-icon.green  { background: var(--primary-light); color: var(--primary-dark); }
        .card-title-icon.amber  { background: var(--amber-light); color: #b45309; }
        .card-title-icon.blue   { background: var(--blue-light); color: var(--blue); }
        .card-title-icon.orange { background: var(--accent-light); color: var(--accent); }

        .view-all {
            font-size: 12px;
            font-weight: 700;
            color: var(--primary-dark);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: 9px;
            background: var(--primary-light);
            transition: var(--transition);
            white-space: nowrap;
        }

        .view-all:hover {
            background: var(--primary);
            color: white;
        }

        /* ===== PERIOD TABS ===== */
        .period-tabs {
            display: flex;
            gap: 6px;
            background: #f1f5f9;
            padding: 4px;
            border-radius: 10px;
            flex-wrap: wrap;
        }

        .period-tab {
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            border: none;
            background: transparent;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
        }

        .period-tab.active {
            background: var(--card);
            color: var(--primary-dark);
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
        }

        /* ===== CHART ===== */
        .chart-wrap {
            padding: 0 24px 24px;
            height: 300px;
        }

        /* ===== TABLE ===== */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
        }

        .data-table {
            width: 100%;
            min-width: 720px;
            border-collapse: collapse;
        }

        .data-table thead tr {
            background: #f8fafc;
        }

        .data-table th {
            padding: 11px 16px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--text-muted);
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
            white-space: nowrap;
        }

        .data-table td {
            padding: 14px 16px;
            font-size: 13px;
            color: var(--text-secondary);
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
            white-space: nowrap;
        }

        .data-table tbody tr {
            transition: var(--transition);
        }

        .data-table tbody tr:hover {
            background: #fafbfc;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .product-name {
            font-weight: 700;
            color: var(--text-primary);
            font-size: 13px;
            line-height: 1.4;
            white-space: normal;
        }

        .product-code {
            font-family: 'DM Mono', monospace;
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 2px;
            white-space: normal;
        }

        .price-new {
            font-weight: 700;
            color: var(--text-primary);
            font-family: 'DM Mono', monospace;
        }

        .price-old {
            font-family: 'DM Mono', monospace;
            color: var(--text-muted);
            text-decoration: line-through;
            font-size: 12px;
        }

        /* ===== BADGES ===== */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .badge-up        { background: #dcfce7; color: #15803d; }
        .badge-down      { background: #fee2e2; color: #dc2626; }
        .badge-pending   { background: var(--amber-light); color: #92400e; }
        .badge-completed { background: #dcfce7; color: #15803d; }
        .badge-approved  { background: var(--blue-light); color: #1e40af; }
        .badge-secondary { background: #f1f5f9; color: #475569; }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 44px 20px;
            color: var(--text-muted);
        }

        .empty-icon {
            font-size: 40px;
            margin-bottom: 12px;
            opacity: 0.3;
        }

        .empty-state p {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 0;
        }

        /* ===== ACTIONS ===== */
        .actions-list {
            padding: 0 20px 20px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 16px 10px;
            border-radius: var(--radius-sm);
            border: 1.5px solid #e8edf2;
            background: #fafbfc;
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            transition: var(--transition);
            min-height: 92px;
        }

        .action-btn:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px var(--primary-glow);
        }

        .action-btn i {
            font-size: 18px;
            transition: var(--transition);
        }

        .action-btn:hover i {
            transform: scale(1.12);
        }

        /* ===== ALERT CARD ===== */
        .alert-card {
            margin: 0 20px 12px;
            padding: 14px 16px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            border: 1.5px solid transparent;
            transition: var(--transition);
        }

        .alert-card.warn {
            background: var(--amber-light);
            border-color: rgba(245,158,11,0.22);
            color: #92400e;
        }

        .alert-card:hover {
            transform: translateX(3px);
            box-shadow: 0 4px 16px rgba(245,158,11,0.18);
        }

        .alert-card-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .alert-card-body {
            min-width: 0;
        }

        .alert-card-body strong {
            display: block;
            font-size: 13px;
            font-weight: 800;
            line-height: 1.4;
        }

        .alert-card-body small {
            font-size: 11px;
            opacity: 0.75;
        }

        .alert-card-arrow {
            margin-left: auto;
            opacity: 0.5;
            flex-shrink: 0;
        }

        /* ===== WHOLESALER LIST ===== */
        .wholesaler-list {
            padding: 0 20px 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .wholesaler-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: var(--radius-sm);
            background: #f8fafc;
            border: 1px solid transparent;
            transition: var(--transition);
        }

        .wholesaler-item:hover {
            background: var(--primary-light);
            border-color: rgba(0,200,150,0.18);
        }

        .wholesaler-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .wholesaler-info {
            min-width: 0;
        }

        .wholesaler-info strong {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.4;
            white-space: normal;
        }

        .wholesaler-meta {
            font-size: 11px;
            color: var(--text-muted);
            line-height: 1.5;
            white-space: normal;
        }

        /* ===== TIMELINE ===== */
        .timeline {
            padding: 0 20px 20px;
        }

        .tl-item {
            display: flex;
            gap: 14px;
            padding-bottom: 18px;
            position: relative;
        }

        .tl-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 14px;
            top: 32px;
            bottom: 0;
            width: 1.5px;
            background: #e8edf2;
        }

        .tl-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }

        .tl-dot.price    { background: #f5f3ff; color: var(--purple); }
        .tl-dot.exchange { background: var(--accent-light); color: var(--accent); }
        .tl-dot.order    { background: var(--blue-light); color: var(--blue); }

        .tl-content {
            flex: 1;
            min-width: 0;
            padding-top: 4px;
        }

        .tl-time {
            font-size: 11px;
            color: var(--text-muted);
            margin-bottom: 3px;
            line-height: 1.4;
        }

        .tl-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 2px;
            line-height: 1.4;
        }

        .tl-desc {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1400px) {
            .stats-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 1200px) {
            .content-grid {
                grid-template-columns: minmax(0, 1fr) 300px;
            }
        }

        @media (max-width: 991.98px) {
            .dash {
                padding: 18px;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .sidebar-col .card:last-child {
                margin-bottom: 0;
            }

            .chart-wrap {
                height: 270px;
            }
        }

        @media (max-width: 767.98px) {
            .dash {
                padding: 14px;
            }

            .dash-header {
                padding: 24px 20px;
                margin-bottom: 18px;
            }

            .header-inner {
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }

            .header-badges {
                justify-content: flex-start;
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
                margin-bottom: 18px;
            }

            .stat-card {
                padding: 18px 16px;
                border-radius: 16px;
            }

            .stat-value {
                font-size: 22px;
            }

            .card {
                margin-bottom: 16px;
                border-radius: 16px;
            }

            .card-head {
                padding: 18px 16px 0;
                gap: 12px;
            }

            .chart-wrap {
                padding: 0 14px 16px;
                height: 220px;
            }

            .actions-list {
                padding: 0 16px 16px;
                gap: 8px;
            }

            .alert-card,
            .wholesaler-list,
            .timeline {
                margin-left: 0;
                margin-right: 0;
            }

            .wholesaler-list,
            .timeline {
                padding: 0 16px 16px;
            }

            .alert-card {
                margin: 0 16px 12px;
            }

            .data-table {
                min-width: 640px;
            }

            .data-table th,
            .data-table td {
                padding: 10px 12px;
            }
        }

        @media (max-width: 575.98px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .actions-list {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .card-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .period-tabs {
                width: 100%;
            }

            .period-tab {
                flex: 1 1 auto;
                text-align: center;
            }

            .view-all {
                width: 100%;
                justify-content: center;
            }

            .chart-wrap {
                height: 210px;
            }

            .alert-card {
                padding: 12px 14px;
            }
        }

        @media (max-width: 420px) {
            .actions-list {
                grid-template-columns: 1fr;
            }

            .h-badge {
                width: 100%;
                justify-content: center;
            }
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .dash-header  { animation: fadeUp 0.4s ease both; }
        .stats-grid   { animation: fadeUp 0.4s 0.1s ease both; }
        .content-grid { animation: fadeUp 0.4s 0.2s ease both; }
    </style>
@endpush

@section('content')
    <div class="dash">

        {{-- ============ HEADER ============ --}}
        <div class="dash-header">
            <div class="header-inner">
                <div class="header-left">
                    <h1>👋 Welcome back, {{ $employee->name }}</h1>
                    <p>Here's what's happening with your business today.</p>
                    <div class="header-time" id="live-clock"></div>
                </div>

                <div class="header-badges">
                    <span class="h-badge primary"><i class="fas fa-circle" style="font-size:7px;"></i> Online</span>
                    <span class="h-badge"><i class="fas fa-user-tie"></i> Employee</span>
                </div>
            </div>
        </div>

        {{-- ============ STATS ============ --}}
        <div class="stats-grid">
            <div class="stat-card" data-color="green">
                <div class="stat-top">
                    <span class="stat-label">Wholesalers</span>
                    <div class="stat-icon green"><i class="fas fa-store"></i></div>
                </div>
                <div class="stat-value">{{ number_format($stats['total_wholesalers'] ?? 0) }}</div>
                <div class="stat-sub"><span class="dot" style="background:var(--primary)"></span> Active accounts</div>
            </div>

            <div class="stat-card" data-color="amber">
                <div class="stat-top">
                    <span class="stat-label">Total Orders</span>
                    <div class="stat-icon amber"><i class="fas fa-shopping-bag"></i></div>
                </div>
                <div class="stat-value">{{ number_format($stats['total_orders'] ?? 0) }}</div>
                <div class="stat-sub"><span class="dot" style="background:var(--amber)"></span> Today: {{ $stats['today_orders'] ?? 0 }}</div>
            </div>

            <div class="stat-card" data-color="purple">
                <div class="stat-top">
                    <span class="stat-label">Products</span>
                    <div class="stat-icon purple"><i class="fas fa-boxes"></i></div>
                </div>
                <div class="stat-value">{{ number_format($stats['total_products'] ?? 0) }}</div>
                <div class="stat-sub"><span class="dot" style="background:var(--purple)"></span> Active listings</div>
            </div>

            <div class="stat-card" data-color="blue">
                <div class="stat-top">
                    <span class="stat-label">Revenue</span>
                    <div class="stat-icon blue"><i class="fas fa-coins"></i></div>
                </div>
                <div class="stat-value" style="font-size: clamp(18px, 2vw, 22px);">Rs.{{ number_format($stats['total_revenue'] ?? 0, 0) }}</div>
                <div class="stat-sub"><span class="dot" style="background:var(--blue)"></span> Monthly total</div>
            </div>

            <div class="stat-card" data-color="violet">
                <div class="stat-top">
                    <span class="stat-label">Price Changes</span>
                    <div class="stat-icon violet"><i class="fas fa-tag"></i></div>
                </div>
                <div class="stat-value">{{ number_format($stats['total_price_changes'] ?? 0) }}</div>
                <div class="stat-sub"><span class="dot" style="background:#a855f7"></span> {{ $stats['my_price_changes'] ?? 0 }} by you</div>
            </div>

            <div class="stat-card" data-color="orange">
                <div class="stat-top">
                    <span class="stat-label">Exchanges</span>
                    <div class="stat-icon orange"><i class="fas fa-exchange-alt"></i></div>
                </div>
                <div class="stat-value">{{ number_format($stats['total_exchanges'] ?? 0) }}</div>
                <div class="stat-sub"><span class="dot" style="background:var(--accent)"></span> Pending: {{ $stats['pending_exchanges'] ?? 0 }}</div>
            </div>
        </div>

        {{-- ============ CONTENT GRID ============ --}}
        <div class="content-grid">

            {{-- ===== LEFT COLUMN ===== --}}
            <div class="main-col">

                {{-- Chart --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-title">
                            <div class="card-title-icon green"><i class="fas fa-chart-area"></i></div>
                            Business Analytics
                        </div>

                        <div class="period-tabs">
                            <button class="period-tab active" data-period="daily">Daily</button>
                            <button class="period-tab" data-period="weekly">Weekly</button>
                            <button class="period-tab" data-period="monthly">Monthly</button>
                        </div>
                    </div>

                    <div class="chart-wrap">
                        <canvas id="analyticsChart"></canvas>
                    </div>
                </div>

                {{-- Price Changes Table --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-title">
                            <div class="card-title-icon amber"><i class="fas fa-tag"></i></div>
                            Recent Price Changes
                        </div>

                        <a href="{{ route('employee.wholesaler.price-changes.index') }}" class="view-all">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Wholesaler</th>
                                <th>Old Price</th>
                                <th>New Price</th>
                                <th>Change</th>
                                <th>When</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentPriceChanges ?? [] as $change)
                                <tr>
                                    <td>
                                        <div class="product-name">{{ $change->product->name ?? 'N/A' }}</div>
                                        <div class="product-code">{{ $change->product->code ?? '—' }}</div>
                                    </td>
                                    <td style="font-size:13px;">{{ $change->wholesaler->business_name ?? 'N/A' }}</td>
                                    <td><span class="price-old">Rs.{{ number_format($change->old_final_price, 2) }}</span></td>
                                    <td><span class="price-new">Rs.{{ number_format($change->new_final_price, 2) }}</span></td>
                                    <td>
                                        <span class="badge {{ $change->price_difference > 0 ? 'badge-up' : 'badge-down' }}">
                                            <i class="fas fa-{{ $change->price_difference > 0 ? 'arrow-up' : 'arrow-down' }}" style="font-size:9px;"></i>
                                            {{ number_format(abs($change->percentage_change), 1) }}%
                                        </span>
                                    </td>
                                    <td style="font-size:12px;color:var(--text-muted);">{{ $change->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-icon"><i class="fas fa-tag"></i></div>
                                            <p>No recent price changes</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Exchanges Table --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-title">
                            <div class="card-title-icon orange"><i class="fas fa-exchange-alt"></i></div>
                            Recent Exchanges
                        </div>

                        <a href="{{ route('employee.wholesaler.exchanges.index') }}" class="view-all">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                            <tr>
                                <th>Source</th>
                                <th>Destination</th>
                                <th>Qty</th>
                                <th>Value</th>
                                <th>Status</th>
                                <th>When</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentExchanges ?? [] as $exchange)
                                <tr>
                                    <td>
                                        <div class="product-name" style="font-size:12px;">{{ $exchange->sourceProduct->name ?? 'N/A' }}</div>
                                        <div class="product-code">{{ $exchange->sourceWholesaler->business_name ?? '—' }}</div>
                                    </td>
                                    <td>
                                        <div class="product-name" style="font-size:12px;">{{ $exchange->destinationProduct->name ?? 'N/A' }}</div>
                                        <div class="product-code">{{ $exchange->destinationWholesaler->business_name ?? '—' }}</div>
                                    </td>
                                    <td style="font-family:'DM Mono',monospace;font-size:13px;font-weight:600;">{{ $exchange->exchange_quantity }}</td>
                                    <td style="font-family:'DM Mono',monospace;font-size:12px;">Rs.{{ number_format($exchange->destination_price_at_time, 2) }}</td>
                                    <td>
                                        @php
                                            $sc = match($exchange->status) {
                                                'completed' => 'badge-completed',
                                                'pending'   => 'badge-pending',
                                                'approved'  => 'badge-approved',
                                                default     => 'badge-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $sc }}">{{ ucfirst($exchange->status) }}</span>
                                    </td>
                                    <td style="font-size:12px;color:var(--text-muted);">{{ $exchange->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-icon"><i class="fas fa-exchange-alt"></i></div>
                                            <p>No recent exchanges</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ===== RIGHT COLUMN ===== --}}
            <div class="sidebar-col">

                {{-- Quick Actions --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-title">
                            <div class="card-title-icon blue"><i class="fas fa-bolt"></i></div>
                            Quick Actions
                        </div>
                    </div>

                    <div class="actions-list">
                        <a href="{{ route('employee.wholesaler.products.index') }}" class="action-btn">
                            <i class="fas fa-box-open"></i>
                            <span>Products</span>
                        </a>

                        <a href="{{ route('employee.wholesaler.price-changes.create') }}" class="action-btn">
                            <i class="fas fa-tag"></i>
                            <span>Change Price</span>
                        </a>

                        <a href="{{ route('employee.wholesaler.price-changes.bulk.create') }}" class="action-btn">
                            <i class="fas fa-layer-group"></i>
                            <span>Bulk Price</span>
                        </a>

                        <a href="{{ route('employee.wholesaler.exchanges.create') }}" class="action-btn">
                            <i class="fas fa-exchange-alt"></i>
                            <span>New Exchange</span>
                        </a>

                        <a href="{{ route('employee.wholesaler.price-changes.history') }}" class="action-btn">
                            <i class="fas fa-history"></i>
                            <span>Price History</span>
                        </a>

                        <a href="{{ route('employee.profile') }}" class="action-btn">
                            <i class="fas fa-user-cog"></i>
                            <span>Profile</span>
                        </a>
                    </div>
                </div>

                {{-- Pending Approvals --}}
                @if(($stats['pending_price_changes'] ?? 0) > 0 || ($stats['pending_exchanges'] ?? 0) > 0)
                    <div class="card">
                        <div class="card-head">
                            <div class="card-title">
                                <div class="card-title-icon amber"><i class="fas fa-clock"></i></div>
                                Pending Approvals
                            </div>
                        </div>

                        @if(($stats['pending_price_changes'] ?? 0) > 0)
                            <a href="{{ route('employee.wholesaler.price-changes.pending') }}" class="alert-card warn" style="margin-top:4px;">
                                <div class="alert-card-icon"><i class="fas fa-tag"></i></div>
                                <div class="alert-card-body">
                                    <strong>{{ $stats['pending_price_changes'] }} Price Change{{ $stats['pending_price_changes'] > 1 ? 's' : '' }}</strong>
                                    <small>Awaiting your review</small>
                                </div>
                                <div class="alert-card-arrow"><i class="fas fa-chevron-right"></i></div>
                            </a>
                        @endif

                        @if(($stats['pending_exchanges'] ?? 0) > 0)
                            <a href="{{ route('employee.wholesaler.exchanges.index', ['status' => 'pending']) }}" class="alert-card warn">
                                <div class="alert-card-icon"><i class="fas fa-exchange-alt"></i></div>
                                <div class="alert-card-body">
                                    <strong>{{ $stats['pending_exchanges'] }} Exchange{{ $stats['pending_exchanges'] > 1 ? 's' : '' }}</strong>
                                    <small>Awaiting your review</small>
                                </div>
                                <div class="alert-card-arrow"><i class="fas fa-chevron-right"></i></div>
                            </a>
                        @endif

                        <div style="height:8px;"></div>
                    </div>
                @endif

                {{-- Recent Wholesalers --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-title">
                            <div class="card-title-icon green"><i class="fas fa-store"></i></div>
                            Recent Wholesalers
                        </div>
                    </div>

                    <div class="wholesaler-list">
                        @forelse($recentWholesalers ?? [] as $w)
                            <div class="wholesaler-item">
                                <div class="wholesaler-avatar">{{ strtoupper(substr($w->business_name ?? $w->name, 0, 1)) }}</div>
                                <div class="wholesaler-info">
                                    <strong>{{ $w->business_name ?? $w->name }}</strong>
                                    <div class="wholesaler-meta">
                                        {{ $w->products_count ?? 0 }} products &bull; {{ $w->orders_count ?? 0 }} orders
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-store"></i></div>
                                <p>No wholesalers found</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Export Tools --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-title">
                            <div class="card-title-icon blue"><i class="fas fa-file-export"></i></div>
                            Export Tools
                        </div>
                    </div>

                    <div class="actions-list">
                        <a href="{{ route('employee.wholesaler.products.export') }}" class="action-btn">
                            <i class="fas fa-box"></i>
                            <span>Products</span>
                        </a>

                        <a href="{{ route('employee.wholesaler.price-changes.export') }}" class="action-btn">
                            <i class="fas fa-file-csv"></i>
                            <span>Prices</span>
                        </a>
{{--                        <a href="#" onclick="window.open('{{ route('', 0) }}'.replace('/0', ''), '_blank')" class="quick-action-btn">--}}
{{--                            <i class="fas fa-print quick-action-icon"></i>--}}
{{--                            <span>Print Labels</span>--}}
{{--                        </a>--}}

                        <a href="{{ route('employee.wholesaler.order-exchanges.export') }}" class="action-btn">
                            <i class="fas fa-exchange-alt"></i>
                            <span>Exchanges</span>
                        </a>

                        <a href="{{ route('employee.wholesaler.price-changes.stats') }}" class="action-btn">
                            <i class="fas fa-chart-pie"></i>
                            <span>Stats</span>
                        </a>
                    </div>
                </div>

                {{-- Activity Timeline --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-title">
                            <div class="card-title-icon orange"><i class="fas fa-stream"></i></div>
                            Your Activity
                        </div>
                    </div>

                    <div class="timeline">
                        @forelse($recentActivities ?? [] as $activity)
                            <div class="tl-item">
                                <div class="tl-dot {{ $activity->type ?? 'order' }}">
                                    @if(($activity->type ?? '') === 'price')
                                        <i class="fas fa-tag"></i>
                                    @elseif(($activity->type ?? '') === 'exchange')
                                        <i class="fas fa-exchange-alt"></i>
                                    @else
                                        <i class="fas fa-shopping-bag"></i>
                                    @endif
                                </div>

                                <div class="tl-content">
                                    <div class="tl-time"><i class="far fa-clock" style="margin-right:4px;"></i>{{ $activity->created_at->diffForHumans() }}</div>
                                    <div class="tl-title">{{ $activity->title }}</div>
                                    <div class="tl-desc">{{ $activity->description }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-stream"></i></div>
                                <p>No recent activity</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function clock() {
            const el = document.getElementById('live-clock');
            if (!el) return;

            const update = () => {
                const d = new Date();
                el.textContent =
                    d.toLocaleDateString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }) +
                    '  ·  ' +
                    d.toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
            };

            update();
            setInterval(update, 1000);
        })();

        let chart;

        function buildChart(labels, orders, revenue) {
            const ctx = document.getElementById('analyticsChart');
            if (!ctx) return;

            if (chart) chart.destroy();

            const gridColor = 'rgba(0,0,0,0.05)';
            const textColor = '#94a3b8';

            chart = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: 'Orders',
                            data: orders,
                            borderColor: '#00c896',
                            backgroundColor: 'rgba(0,200,150,0.08)',
                            borderWidth: 2.5,
                            tension: 0.45,
                            fill: true,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#00c896',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 7,
                        },
                        {
                            label: 'Revenue (Rs)',
                            data: revenue,
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37,99,235,0.06)',
                            borderWidth: 2.5,
                            tension: 0.45,
                            fill: true,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#2563eb',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 7,
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: textColor,
                                font: { family: 'Plus Jakarta Sans', size: 11 }
                            },
                            border: { display: false }
                        },
                        y: {
                            position: 'left',
                            grid: { color: gridColor, drawBorder: false },
                            ticks: {
                                color: textColor,
                                font: { size: 11 }
                            },
                            border: { display: false }
                        },
                        y1: {
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: {
                                color: textColor,
                                font: { size: 11 },
                                callback: v => 'Rs.' + (v >= 1000 ? Math.round(v / 1000) + 'k' : v)
                            },
                            border: { display: false }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#475569',
                                usePointStyle: true,
                                pointStyleWidth: 10,
                                padding: 20,
                                font: {
                                    family: 'Plus Jakarta Sans',
                                    size: 12,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#f1f5f9',
                            bodyColor: '#94a3b8',
                            borderColor: 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 10,
                            callbacks: {
                                label: ctx => ctx.dataset.label.includes('Revenue')
                                    ? ctx.dataset.label + ': Rs.' + ctx.parsed.y.toLocaleString()
                                    : ctx.dataset.label + ': ' + ctx.parsed.y
                            }
                        }
                    },
                    animation: {
                        duration: 600,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        const chartData = {
            daily: {
                labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
                orders: [12,19,15,25,22,30,28],
                revenue: [12000,19000,15000,25000,22000,30000,28000]
            },
            weekly: {
                labels: ['Week 1','Week 2','Week 3','Week 4'],
                orders: [100,120,150,180],
                revenue: [100000,120000,150000,180000]
            },
            monthly: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                orders: [180,240,320,450,500,600,550,650,700,800,850,900],
                revenue: [180000,240000,320000,450000,500000,600000,550000,650000,700000,800000,850000,900000]
            }
        };

        function loadPeriod(period) {
            fetch(`{{ route('employee.dashboard') }}?period=${period}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        buildChart(data.labels, data.orders, data.revenue);
                    } else {
                        const d = chartData[period];
                        buildChart(d.labels, d.orders, d.revenue);
                    }
                })
                .catch(() => {
                    const d = chartData[period] || chartData.daily;
                    buildChart(d.labels, d.orders, d.revenue);
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const d = chartData.daily;
            buildChart(d.labels, d.orders, d.revenue);

            document.querySelectorAll('.period-tab').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.period-tab').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    loadPeriod(this.dataset.period);
                });
            });
        });
    </script>
@endpush
