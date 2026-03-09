@extends('wholesaler.layouts.app')

@push('title')
    Wholesale Dashboard
@endpush

@push('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --dx-primary: #667eea;
            --dx-primary-dark: #5a67d8;
            --dx-secondary: #764ba2;
            --dx-success: #10b981;
            --dx-success-light: #d1fae5;
            --dx-danger: #ef4444;
            --dx-danger-light: #fee2e2;
            --dx-warning: #f59e0b;
            --dx-warning-light: #fef3c7;
            --dx-info: #3b82f6;
            --dx-info-light: #dbeafe;
            --dx-purple-light: #ede9fe;
            --dx-bg: #f8fafc;
            --dx-card: #ffffff;
            --dx-border: #e2e8f0;
            --dx-text: #0f172a;
            --dx-text-soft: #475569;
            --dx-text-muted: #94a3b8;
            --dx-radius: 20px;
            --dx-radius-sm: 14px;
            --dx-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            --dx-shadow-hover: 0 18px 50px rgba(15, 23, 42, 0.14);
            --dx-transition: all .28s cubic-bezier(.4,0,.2,1);
        }

        * {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        .dashboard-shell {
            width: 100%;
            max-width: 1700px;
            margin: 0 auto;
            padding: 24px;
        }

        /* ===== HERO ===== */
        .dashboard-hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 55%, #8b5cf6 100%);
            border-radius: 28px;
            padding: 34px;
            margin-bottom: 24px;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.28);
        }

        .dashboard-hero::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -90px;
            width: 340px;
            height: 340px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, transparent 68%);
            pointer-events: none;
        }

        .dashboard-hero::after {
            content: '';
            position: absolute;
            left: -60px;
            bottom: -80px;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.09) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-inner {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            flex-wrap: wrap;
        }

        .hero-content {
            min-width: 0;
            flex: 1 1 420px;
        }

        .hero-title {
            color: #fff;
            font-size: clamp(24px, 3vw, 34px);
            font-weight: 900;
            letter-spacing: -0.6px;
            line-height: 1.15;
            margin-bottom: 10px;
            word-break: break-word;
        }

        .hero-subtitle {
            color: rgba(255,255,255,0.88);
            font-size: 15px;
            font-weight: 500;
            margin-bottom: 16px;
            max-width: 700px;
        }

        .hero-badges {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.18);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            backdrop-filter: blur(10px);
            white-space: nowrap;
        }

        .hero-badge i {
            font-size: 13px;
        }

        .hero-side {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .hero-mini-card {
            min-width: 135px;
            padding: 16px 18px;
            border-radius: 18px;
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.16);
            color: white;
            backdrop-filter: blur(12px);
        }

        .hero-mini-card small {
            display: block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.72);
            margin-bottom: 6px;
        }

        .hero-mini-card strong {
            display: block;
            font-size: 20px;
            font-weight: 800;
            line-height: 1.1;
        }

        /* ===== STATS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--dx-card);
            border: 1px solid var(--dx-border);
            border-radius: 22px;
            padding: 22px;
            box-shadow: var(--dx-shadow);
            position: relative;
            overflow: hidden;
            transition: var(--dx-transition);
            min-width: 0;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 4px;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .35s ease;
            background: var(--accent-color, var(--dx-primary));
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--dx-shadow-hover);
        }

        .stat-card:hover::after {
            transform: scaleX(1);
        }

        .stat-card.products  { --accent-color: #667eea; }
        .stat-card.orders    { --accent-color: #f43f5e; }
        .stat-card.confirmed { --accent-color: #06b6d4; }
        .stat-card.revenue   { --accent-color: #10b981; }

        .stat-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 16px;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--dx-text-muted);
            line-height: 1.4;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            flex-shrink: 0;
            transition: var(--dx-transition);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.08) rotate(4deg);
        }

        .stat-icon.products {
            color: #5b21b6;
            background: var(--dx-purple-light);
        }

        .stat-icon.orders {
            color: #e11d48;
            background: #ffe4e6;
        }

        .stat-icon.confirmed {
            color: #0ea5e9;
            background: #e0f2fe;
        }

        .stat-icon.revenue {
            color: #059669;
            background: var(--dx-success-light);
        }

        .stat-value {
            font-size: clamp(24px, 2.3vw, 34px);
            font-weight: 900;
            line-height: 1.1;
            color: var(--dx-text);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
            overflow-wrap: anywhere;
        }

        .stat-value.revenue-text {
            font-size: clamp(18px, 2vw, 24px);
        }

        .stat-foot {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--dx-text-soft);
            font-size: 12px;
            font-weight: 600;
            line-height: 1.5;
            flex-wrap: wrap;
        }

        .stat-foot i {
            font-size: 12px;
            color: var(--accent-color, var(--dx-primary));
        }

        /* ===== MAIN GRID ===== */
        .dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 22px;
            align-items: start;
        }

        .main-column,
        .side-column {
            min-width: 0;
        }

        /* ===== CARD ===== */
        .dashboard-card {
            background: var(--dx-card);
            border: 1px solid var(--dx-border);
            border-radius: 22px;
            box-shadow: var(--dx-shadow);
            overflow: hidden;
            margin-bottom: 22px;
            transition: var(--dx-transition);
        }

        .dashboard-card:hover {
            box-shadow: var(--dx-shadow-hover);
        }

        .card-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            padding: 24px 24px 0;
            margin-bottom: 18px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--dx-text);
            margin: 0;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .card-title::before {
            content: '';
            width: 4px;
            height: 22px;
            border-radius: 999px;
            background: linear-gradient(180deg, var(--dx-primary), var(--dx-secondary));
            flex-shrink: 0;
        }

        .card-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            color: var(--dx-primary);
            font-size: 13px;
            font-weight: 700;
            padding: 8px 12px;
            border-radius: 10px;
            background: #eef2ff;
            transition: var(--dx-transition);
            white-space: nowrap;
        }

        .card-link:hover {
            color: #fff;
            background: linear-gradient(135deg, var(--dx-primary), var(--dx-secondary));
        }

        /* ===== FILTER BUTTONS ===== */
        .chart-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .chart-btn {
            padding: 8px 14px;
            border: 1.5px solid var(--dx-border);
            background: #fff;
            color: var(--dx-text-soft);
            border-radius: 10px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            transition: var(--dx-transition);
        }

        .chart-btn.active {
            background: linear-gradient(135deg, var(--dx-primary), var(--dx-secondary));
            color: white;
            border-color: transparent;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.2);
        }

        .chart-btn:hover:not(.active) {
            color: var(--dx-primary);
            border-color: #c7d2fe;
            background: #f8faff;
        }

        /* ===== CHART ===== */
        .chart-wrap {
            padding: 0 24px 24px;
            height: 320px;
        }

        /* ===== TABLE ===== */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
        }

        .dashboard-table {
            width: 100%;
            min-width: 680px;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .dashboard-table thead th {
            background: #f8fafc;
            color: var(--dx-text-soft);
            font-weight: 800;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .06em;
            border: none;
            padding: 14px 16px;
            white-space: nowrap;
        }

        .dashboard-table tbody tr {
            transition: var(--dx-transition);
            border-bottom: 1px solid #f1f5f9;
        }

        .dashboard-table tbody tr:hover {
            background: #fafcff;
        }

        .dashboard-table tbody td {
            padding: 16px;
            vertical-align: middle;
            color: var(--dx-text-soft);
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
        }

        .dashboard-table tbody tr:last-child {
            border-bottom: none;
        }

        .order-id {
            font-weight: 800;
            color: var(--dx-text);
        }

        .customer-cell {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .customer-cell i {
            color: var(--dx-primary);
            font-size: 15px;
        }

        .amount-cell {
            font-weight: 800;
            color: var(--dx-text);
        }

        .date-cell {
            color: var(--dx-text-muted);
            font-size: 12px;
        }

        .empty-state {
            text-align: center;
            padding: 42px 18px;
            color: var(--dx-text-muted);
        }

        .empty-state i {
            display: block;
            font-size: 44px;
            color: #cbd5e1;
            margin-bottom: 12px;
        }

        .empty-state p {
            margin: 0;
            font-weight: 600;
            font-size: 13px;
        }

        /* ===== BADGES ===== */
        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .03em;
            white-space: nowrap;
        }

        .status-confirmed {
            background: #dcfce7;
            color: #15803d;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-default {
            background: #e2e8f0;
            color: #475569;
        }

        /* ===== QUICK ACTIONS ===== */
        .quick-actions {
            padding: 0 20px 20px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .quick-action-btn {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 66px;
            padding: 16px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border: 1.5px solid #e2e8f0;
            border-radius: 16px;
            text-decoration: none;
            color: var(--dx-text-soft);
            transition: var(--dx-transition);
            font-weight: 700;
            font-size: 13px;
        }

        .quick-action-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            left: -100%;
            background: linear-gradient(135deg, var(--dx-primary), var(--dx-secondary));
            transition: left .35s ease;
            z-index: 0;
        }

        .quick-action-btn:hover::before {
            left: 0;
        }

        .quick-action-btn:hover {
            transform: translateY(-3px);
            color: white;
            border-color: transparent;
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.25);
        }

        .quick-action-btn i,
        .quick-action-btn span {
            position: relative;
            z-index: 1;
        }

        .quick-action-btn i {
            font-size: 18px;
            min-width: 20px;
            text-align: center;
        }

        /* ===== ALERTS ===== */
        .alerts-wrap {
            padding: 0 20px 20px;
        }

        .stock-alert {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 16px;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
            border-left: 4px solid #f59e0b;
            box-shadow: 0 4px 14px rgba(0,0,0,0.05);
            transition: var(--dx-transition);
            margin-bottom: 12px;
        }

        .stock-alert:last-child {
            margin-bottom: 0;
        }

        .stock-alert:hover {
            transform: translateX(4px);
        }

        .stock-alert i {
            font-size: 20px;
            flex-shrink: 0;
        }

        .stock-alert strong {
            display: block;
            font-size: 13px;
            font-weight: 800;
            line-height: 1.4;
        }

        .stock-alert small {
            font-size: 12px;
            font-weight: 600;
            opacity: 0.85;
        }

        .ok-stock {
            text-align: center;
            padding: 36px 16px 24px;
            color: var(--dx-text-muted);
        }

        .ok-stock i {
            font-size: 46px;
            color: var(--dx-success);
            display: block;
            margin-bottom: 12px;
        }

        .ok-stock p {
            margin-bottom: 0;
            font-size: 13px;
            font-weight: 700;
        }

        /* ===== STORAGE INFO ===== */
        .storage-info {
            background: linear-gradient(135deg, #f8fafc, #eef2ff);
            border-radius: 16px;
            padding: 16px 18px;
            margin-bottom: 18px;
            border-left: 4px solid var(--dx-primary);
            box-shadow: var(--dx-shadow);
        }

        .storage-info h6 {
            color: var(--dx-text-soft);
            font-weight: 800;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .storage-info p {
            color: var(--dx-text-muted);
            font-size: 12px;
            margin-bottom: 5px;
            word-break: break-word;
        }

        .storage-info p:last-child {
            margin-bottom: 0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1400px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .side-column {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 18px;
            }

            .side-column .dashboard-card {
                margin-bottom: 0;
            }
        }

        @media (max-width: 991.98px) {
            .dashboard-shell {
                padding: 18px;
            }

            .dashboard-hero {
                padding: 26px;
            }

            .chart-wrap {
                height: 280px;
            }
        }

        @media (max-width: 767.98px) {
            .dashboard-shell {
                padding: 14px;
            }

            .dashboard-hero {
                padding: 22px 18px;
                border-radius: 22px;
                margin-bottom: 18px;
            }

            .hero-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .hero-side {
                width: 100%;
                justify-content: flex-start;
            }

            .hero-mini-card {
                min-width: 120px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
                margin-bottom: 18px;
            }

            .stat-card {
                padding: 18px;
                border-radius: 18px;
            }

            .dashboard-card {
                border-radius: 18px;
                margin-bottom: 16px;
            }

            .card-head {
                padding: 18px 16px 0;
                margin-bottom: 14px;
            }

            .chart-wrap {
                padding: 0 14px 16px;
                height: 230px;
            }

            .quick-actions {
                padding: 0 16px 16px;
                grid-template-columns: 1fr;
            }

            .alerts-wrap {
                padding: 0 16px 16px;
            }

            .side-column {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .dashboard-table {
                min-width: 620px;
            }

            .dashboard-table thead th,
            .dashboard-table tbody td {
                padding: 12px 12px;
            }
        }

        @media (max-width: 480px) {
            .hero-badge {
                width: 100%;
                justify-content: center;
            }

            .hero-side {
                flex-direction: column;
                width: 100%;
            }

            .hero-mini-card {
                width: 100%;
            }

            .chart-actions {
                width: 100%;
            }

            .chart-btn {
                flex: 1 1 auto;
                text-align: center;
            }

            .card-link {
                width: 100%;
                justify-content: center;
            }

            .stat-value.revenue-text {
                font-size: 18px;
            }
        }

        /* ===== ENTRY ANIMATION ===== */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dashboard-hero { animation: fadeUp .45s ease both; }
        .stats-grid { animation: fadeUp .45s .08s ease both; }
        .dashboard-grid { animation: fadeUp .45s .16s ease both; }
    </style>
@endpush

@section('content')
    <div class="dashboard-shell">

        {{-- Optional storage info --}}
        <div class="storage-info d-none" id="storageInfo">
            <h6><i class="fas fa-database me-2"></i>Storage Information</h6>
            <p>Wholesaler ID: <span id="wholesalerIdDisplay"></span></p>
            <p>Storage Path: <span id="storagePathDisplay"></span></p>
            <p>Disk Usage: <span id="diskUsageDisplay"></span></p>
        </div>

        {{-- Hero --}}
        <div class="dashboard-hero">
            <div class="hero-inner">
                <div class="hero-content">
                    <h1 class="hero-title">👋 Welcome Back, {{ $wholesaler->name }}!</h1>
                    <p class="hero-subtitle">Manage your wholesale business with real-time insights, faster actions, and a smarter dashboard experience.</p>

                    <div class="hero-badges">
                        <span class="hero-badge">
                            <i class="fas fa-building"></i>
                            {{ $wholesaler->business_name }}
                        </span>
                        <span class="hero-badge">
                            <i class="fas fa-chart-line"></i>
                            Wholesale Control Center
                        </span>
                    </div>
                </div>

                <div class="hero-side">
                    <div class="hero-mini-card">
                        <small>Pending Orders</small>
                        <strong>{{ $stats['pending_orders'] ?? 0 }}</strong>
                    </div>
                    <div class="hero-mini-card">
                        <small>Revenue</small>
                        <strong>RS. {{ number_format($stats['total_revenue'] ?? 0, 0) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="stats-grid">
            <div class="stat-card products">
                <div class="stat-top">
                    <div class="stat-label">Total Products</div>
                    <div class="stat-icon products">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($stats['total_products'] ?? 0) }}</div>
                <div class="stat-foot">
                    <i class="fas fa-box-open"></i>
                    <span>Manage your product catalog</span>
                </div>
            </div>

            <div class="stat-card orders">
                <div class="stat-top">
                    <div class="stat-label">Total Orders</div>
                    <div class="stat-icon orders">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($stats['total_orders'] ?? 0) }}</div>
                <div class="stat-foot">
                    <i class="fas fa-receipt"></i>
                    <span>Track all customer orders</span>
                </div>
            </div>

            <div class="stat-card confirmed">
                <div class="stat-top">
                    <div class="stat-label">Confirmed Orders</div>
                    <div class="stat-icon confirmed">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($stats['confirmed_orders'] ?? 0) }}</div>
                <div class="stat-foot">
                    <i class="fas fa-truck-loading"></i>
                    <span>Ready for processing</span>
                </div>
            </div>

            <div class="stat-card revenue">
                <div class="stat-top">
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-icon revenue">
                        <i class="fas fa-sack-dollar"></i>
                    </div>
                </div>
                <div class="stat-value revenue-text">RS. {{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                <div class="stat-foot">
                    <i class="fas fa-chart-line"></i>
                    <span>From completed orders</span>
                </div>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="dashboard-grid">
            <div class="main-column">

                {{-- Sales Analytics --}}
                <div class="dashboard-card">
                    <div class="card-head">
                        <h3 class="card-title">Sales Analytics</h3>

                        <div class="chart-actions">
                            <button class="chart-btn active" data-period="monthly">Monthly</button>
                            <button class="chart-btn" data-period="quarterly">Quarterly</button>
                            <button class="chart-btn" data-period="yearly">Yearly</button>
                        </div>
                    </div>

                    <div class="chart-wrap">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                {{-- Recent Orders --}}
                <div class="dashboard-card">
                    <div class="card-head">
                        <h3 class="card-title">Recent Orders</h3>
                        <a href="{{ route('wholesaler.order.new') }}" class="card-link">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="dashboard-table">
                            <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <span class="order-id">#{{ $order->id }}</span>
                                    </td>
                                    <td>
                                        <span class="customer-cell">
                                            <i class="fas fa-user-circle"></i>
                                            {{ $order->customer->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="amount-cell">RS. {{ number_format($order->total_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($order->status) {
                                                'confirmed' => 'status-confirmed',
                                                'pending' => 'status-pending',
                                                'cancelled' => 'status-cancelled',
                                                default => 'status-default',
                                            };
                                        @endphp
                                        <span class="status-badge {{ $statusClass }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="date-cell">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ $order->created_at->format('M d, Y') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox"></i>
                                            <p>No orders found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="side-column">

                {{-- Quick Actions --}}
                <div class="dashboard-card">
                    <div class="card-head">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>

                    <div class="quick-actions">
                        <a href="{{ route('wholesaler.products.create') }}" class="quick-action-btn">
                            <i class="fas fa-plus-circle"></i>
                            <span>Add New Product</span>
                        </a>

                        <a href="{{ route('wholesaler.order.new') }}?status=pending" class="quick-action-btn">
                            <i class="fas fa-clock"></i>
                            <span>Pending Orders</span>
                        </a>

                        <a href="{{ route('wholesaler.products.index') }}" class="quick-action-btn">
                            <i class="fas fa-box-open"></i>
                            <span>Manage Products</span>
                        </a>

                        <a href="{{ route('wholesaler.profile') }}" class="quick-action-btn">
                            <i class="fas fa-user-cog"></i>
                            <span>Update Profile</span>
                        </a>
                    </div>
                </div>

                {{-- Stock Alerts --}}
                <div class="dashboard-card">
                    <div class="card-head">
                        <h3 class="card-title">Stock Alerts</h3>
                    </div>

                    <div class="alerts-wrap">
                        @forelse($lowStockProducts as $product)
                            <div class="stock-alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>
                                    <strong>{{ $product->name }}</strong>
                                    <small>Only {{ $product->qty }} left in stock</small>
                                </div>
                            </div>
                        @empty
                            <div class="ok-stock">
                                <i class="fas fa-check-circle"></i>
                                <p>All products are well stocked</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Business Management --}}
                <div class="dashboard-card">
                    <div class="card-head">
                        <h3 class="card-title">Business Management</h3>
                    </div>

                    <div class="quick-actions">
                        <a href="{{ route('wholesaler.shop.index') }}" class="quick-action-btn">
                            <i class="fas fa-store"></i>
                            <span>My Shop</span>
                        </a>

                        <a href="{{ route('wholesaler.brands.index') }}" class="quick-action-btn">
                            <i class="fas fa-tags"></i>
                            <span>Brands</span>
                        </a>

                        <a href="{{ route('wholesaler.categories.index') }}" class="quick-action-btn">
                            <i class="fas fa-layer-group"></i>
                            <span>Categories</span>
                        </a>

                        <a href="{{ route('wholesaler.payments.index') }}" class="quick-action-btn">
                            <i class="fas fa-credit-card"></i>
                            <span>Payments</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! $storageScript ?? '' !!}
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <script>
        class WholesalerStorage {
            hasData() {
                try {
                    return !!localStorage.getItem('wholesaler_data');
                } catch (e) {
                    return false;
                }
            }

            getAllData() {
                try {
                    const raw = localStorage.getItem('wholesaler_data');
                    return raw ? JSON.parse(raw) : {};
                } catch (e) {
                    return {};
                }
            }

            clearData() {
                try {
                    localStorage.removeItem('wholesaler_data');
                } catch (e) {}
            }
        }

        window.WholesalerStorage = new WholesalerStorage();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            const cards = document.querySelectorAll('.stat-card, .dashboard-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(14px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.55s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 70);
            });

            initializeSalesChart();

            document.querySelectorAll('.chart-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.chart-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    updateChart(this.dataset.period);
                });
            });

            setupStorageFeatures();
        });

        function setupStorageFeatures() {
            if (window.WholesalerStorage && window.WholesalerStorage.hasData()) {
                try {
                    const data = window.WholesalerStorage.getAllData();

                    const wholesalerIdDisplay = document.getElementById('wholesalerIdDisplay');
                    const storagePathDisplay = document.getElementById('storagePathDisplay');
                    const storageInfo = document.getElementById('storageInfo');

                    if (wholesalerIdDisplay) {
                        wholesalerIdDisplay.textContent = data.wholesaler_id ?? '-';
                    }

                    if (storagePathDisplay) {
                        storagePathDisplay.textContent = `wholesaler/${data.wholesaler_id ?? '-'}/`;
                    }

                    if (storageInfo) {
                        storageInfo.classList.remove('d-none');
                    }
                } catch (error) {
                    console.error('Error displaying storage info:', error);
                }
            }

            document.querySelectorAll('.logout-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (window.WholesalerStorage) {
                        window.WholesalerStorage.clearData();
                    }
                    window.location.href = this.href;
                });
            });
        }

        async function getStorageInfo() {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) return;

                const response = await fetch('{{ route('wholesaler.storage.info') }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.content
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    const diskUsageDisplay = document.getElementById('diskUsageDisplay');
                    if (diskUsageDisplay) {
                        diskUsageDisplay.textContent =
                            `${data.disk_usage.total_mb} MB (${data.disk_usage.total_files} files)`;
                    }
                    return data;
                }
            } catch (error) {
                console.error('Error fetching storage info:', error);
            }
        }

        let salesChart;

        function initializeSalesChart() {
            const ctx = document.getElementById('salesChart');
            if (!ctx) return;

            const context = ctx.getContext('2d');
            if (!context) return;

            @if(isset($monthlySales) && is_array($monthlySales) && count($monthlySales) > 0)
            const realData = @json($monthlySales);
            const monthlyData = {
                labels: realData.map(item => item.month),
                orders: realData.map(item => item.orders),
                revenue: realData.map(item => item.revenue)
            };
            @else
            const monthlyData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                orders: [12, 19, 15, 25, 22, 30, 28, 35, 32, 40, 38, 45],
                revenue: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000, 32000, 40000, 38000, 45000]
            };
            @endif

                salesChart = new Chart(context, {
                type: 'bar',
                data: {
                    labels: monthlyData.labels,
                    datasets: [
                        {
                            label: 'Orders',
                            data: monthlyData.orders,
                            backgroundColor: 'rgba(102, 126, 234, 0.82)',
                            borderColor: 'rgba(102, 126, 234, 1)',
                            borderWidth: 1.5,
                            borderRadius: 10,
                            borderSkipped: false,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Revenue (RS)',
                            data: monthlyData.revenue,
                            backgroundColor: 'rgba(16, 185, 129, 0.80)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 1.5,
                            borderRadius: 10,
                            borderSkipped: false,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    layout: {
                        padding: {
                            top: 6
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    family: 'Inter',
                                    size: 11,
                                    weight: '600'
                                }
                            },
                            border: {
                                display: false
                            }
                        },
                        y: {
                            type: 'linear',
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Orders',
                                color: '#64748b',
                                font: {
                                    family: 'Inter',
                                    size: 12,
                                    weight: '700'
                                }
                            },
                            grid: {
                                color: 'rgba(100, 116, 139, 0.10)'
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    family: 'Inter',
                                    size: 11
                                }
                            },
                            border: {
                                display: false
                            }
                        },
                        y1: {
                            type: 'linear',
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Revenue (RS)',
                                color: '#64748b',
                                font: {
                                    family: 'Inter',
                                    size: 12,
                                    weight: '700'
                                }
                            },
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                color: '#64748b',
                                font: {
                                    family: 'Inter',
                                    size: 11
                                },
                                callback: function (value) {
                                    return 'RS. ' + value.toLocaleString();
                                }
                            },
                            border: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#475569',
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 18,
                                font: {
                                    family: 'Inter',
                                    size: 12,
                                    weight: '700'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.94)',
                            titleColor: '#f8fafc',
                            bodyColor: '#e2e8f0',
                            borderColor: 'rgba(255,255,255,0.08)',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 12,
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label.includes('Revenue')) {
                                        return label + ': RS. ' + context.parsed.y.toLocaleString();
                                    }
                                    return label + ': ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 900,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        function updateChart(period) {
            if (!salesChart) return;

            let newData;

            switch (period) {
                case 'quarterly':
                    newData = {
                        labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                        orders: [46, 77, 95, 123],
                        revenue: [46000, 77000, 95000, 123000]
                    };
                    break;

                case 'yearly':
                    newData = {
                        labels: ['2021', '2022', '2023', '2024'],
                        orders: [180, 240, 320, 450],
                        revenue: [180000, 240000, 320000, 450000]
                    };
                    break;

                default:
                @if(isset($monthlySales) && is_array($monthlySales) && count($monthlySales) > 0)
                    const realData = @json($monthlySales);
                    newData = {
                        labels: realData.map(item => item.month),
                        orders: realData.map(item => item.orders),
                        revenue: realData.map(item => item.revenue)
                    };
                    @else
                        newData = {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        orders: [12, 19, 15, 25, 22, 30, 28, 35, 32, 40, 38, 45],
                        revenue: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000, 32000, 40000, 38000, 45000]
                    };
                @endif
            }

            salesChart.data.labels = newData.labels;
            salesChart.data.datasets[0].data = newData.orders;
            salesChart.data.datasets[1].data = newData.revenue;
            salesChart.update();
        }

        window.addEventListener('load', function() {
            setTimeout(getStorageInfo, 800);
        });
    </script>
@endpush
