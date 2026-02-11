@extends('seller.layout.app')
@push('title')
    Seller Dashboard
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #2563eb;
            --secondary: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-success: linear-gradient(135deg, #6ee7b7 0%, #10b981 100%);
            --gradient-danger: linear-gradient(135deg, #fca5a5 0%, #ef4444 100%);
            --gradient-warning: linear-gradient(135deg, #fcd34d 0%, #f59e0b 100%);
            --gradient-info: linear-gradient(135deg, #93c5fd 0%, #3b82f6 100%);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.12);
            --shadow-xl: 0 12px 48px rgba(0, 0, 0, 0.15);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .dashboard-container {
            padding: 25px;
            min-height: 100vh;
        }

        /* Hero Header */
        .hero-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-xl);
            position: relative;
            overflow: hidden;
            animation: fadeInDown 0.6s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15), transparent);
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            50% {
                transform: translate(-20px, -20px) rotate(180deg);
            }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .hero-text h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 0.5rem;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .hero-text p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.1rem;
            margin: 0;
        }

        .hero-badges {
            display: flex;
            gap: 15px;
        }

        .hero-badge {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .hero-badge:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: translateY(-3px);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--shadow-lg);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease;
            animation-fill-mode: both;
        }

        .stat-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--stat-gradient);
            opacity: 0.05;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-xl);
            border-color: var(--stat-color);
        }

        .stat-card:hover::before {
            opacity: 0.1;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            background: var(--stat-gradient);
            color: white;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: rotate(360deg) scale(1.1);
        }

        .stat-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--gray-900);
            margin-bottom: 10px;
            line-height: 1;
        }

        .stat-value.gradient {
            background: var(--stat-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-change {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-500);
        }

        .stat-change.positive {
            color: var(--success);
        }

        .stat-change.negative {
            color: var(--danger);
        }

        /* Main Content Grid */
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }

        /* Content Card */
        .content-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--shadow-md);
            border: 2px solid var(--gray-200);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .content-card:hover {
            box-shadow: var(--shadow-xl);
            border-color: var(--primary);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--gray-100);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--gray-900);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-title::before {
            content: '';
            width: 5px;
            height: 24px;
            background: var(--gradient-primary);
            border-radius: 5px;
        }

        .view-all-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .view-all-link:hover {
            color: var(--primary-dark);
            gap: 12px;
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 320px;
            margin-top: 20px;
        }

        .chart-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .chart-btn {
            padding: 10px 20px;
            border: 2px solid var(--gray-200);
            background: white;
            color: var(--gray-600);
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .chart-btn.active {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .chart-btn:hover:not(.active) {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        /* Modern Table */
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .modern-table thead th {
            background: linear-gradient(135deg, var(--gray-50), var(--gray-100));
            color: var(--gray-700);
            font-weight: 800;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 20px;
            border: none;
            text-align: left;
        }

        .modern-table thead th:first-child {
            border-radius: 12px 0 0 12px;
        }

        .modern-table thead th:last-child {
            border-radius: 0 12px 12px 0;
        }

        .modern-table tbody tr {
            background: white;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .modern-table tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .modern-table tbody td {
            padding: 20px;
            vertical-align: middle;
            color: var(--gray-700);
            font-weight: 600;
            border-top: 1px solid var(--gray-100);
            border-bottom: 1px solid var(--gray-100);
        }

        .modern-table tbody td:first-child {
            border-left: 1px solid var(--gray-100);
            border-radius: 12px 0 0 12px;
        }

        .modern-table tbody td:last-child {
            border-right: 1px solid var(--gray-100);
            border-radius: 0 12px 12px 0;
        }

        /* Badge */
        .badge-modern {
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-pending {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(245, 158, 11, 0.25));
            color: #92400e;
            border: 2px solid rgba(245, 158, 11, 0.3);
        }

        .badge-processing {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(59, 130, 246, 0.25));
            color: #1e40af;
            border: 2px solid rgba(59, 130, 246, 0.3);
        }

        .badge-confirmed {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.25));
            color: #065f46;
            border: 2px solid rgba(16, 185, 129, 0.3);
        }

        .badge-cancelled {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.25));
            color: #991b1b;
            border: 2px solid rgba(239, 68, 68, 0.3);
        }

        /* Quick Actions */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .quick-action-btn {
            display: flex;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, var(--gray-50), white);
            border: 2px solid var(--gray-200);
            border-radius: 16px;
            text-decoration: none;
            color: var(--gray-700);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-weight: 700;
            position: relative;
            overflow: hidden;
        }

        .quick-action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-primary);
            transition: left 0.4s ease;
            z-index: 0;
        }

        .quick-action-btn:hover::before {
            left: 0;
        }

        .quick-action-btn:hover {
            color: white;
            border-color: transparent;
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .quick-action-icon {
            font-size: 1.5rem;
            margin-right: 12px;
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .quick-action-btn:hover .quick-action-icon {
            transform: scale(1.2) rotate(10deg);
        }

        .quick-action-btn span {
            position: relative;
            z-index: 1;
        }

        /* Stock Alert */
        .stock-alert {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.2));
            border: 2px solid rgba(245, 158, 11, 0.3);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stock-alert:hover {
            transform: translateX(5px);
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.2);
            border-color: var(--warning);
        }

        .stock-alert.critical {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.2));
            border-color: rgba(239, 68, 68, 0.3);
        }

        .stock-alert.critical:hover {
            border-color: var(--danger);
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.2);
        }

        .alert-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .stock-alert.critical .alert-header i {
            color: var(--danger);
        }

        .alert-header i {
            color: var(--warning);
            font-size: 1.5rem;
        }

        .alert-header strong {
            color: #92400e;
            font-size: 1rem;
        }

        .stock-alert.critical .alert-header strong {
            color: #991b1b;
        }

        .alert-body {
            font-size: 0.875rem;
            color: var(--gray-600);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Insights Grid */
        .insights-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 15px;
        }

        .insight-card {
            background: linear-gradient(135deg, var(--gray-50), white);
            border-radius: 16px;
            padding: 20px;
            border: 2px solid var(--gray-200);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .insight-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--insight-gradient);
        }

        .insight-card:hover {
            background: white;
            border-color: var(--insight-color);
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .insight-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .insight-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            background: var(--insight-gradient);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .insight-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--gray-600);
            text-transform: uppercase;
        }

        .insight-value {
            font-size: 2rem;
            font-weight: 900;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .insight-desc {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        /* Performance Metrics */
        .metric-item {
            background: var(--gray-50);
            border-radius: 16px;
            padding: 20px;
            border: 2px solid var(--gray-200);
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }

        .metric-item:hover {
            border-color: var(--primary);
            background: white;
            transform: translateY(-3px);
        }

        .metric-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .metric-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--gray-600);
        }

        .metric-value {
            font-size: 1rem;
            font-weight: 900;
            color: var(--gray-900);
        }

        .progress-bar-modern {
            height: 10px;
            background: var(--gray-200);
            border-radius: 5px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill-modern {
            height: 100%;
            background: var(--gradient-primary);
            border-radius: 5px;
            transition: width 1s ease;
            position: relative;
        }

        .progress-fill-modern::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }

        /* Product Item */
        .product-item {
            display: flex;
            align-items: center;
            padding: 20px;
            background: var(--gray-50);
            border-radius: 16px;
            border: 2px solid var(--gray-200);
            margin-bottom: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .product-item:hover {
            background: white;
            border-color: var(--primary);
            transform: translateX(8px);
            box-shadow: var(--shadow-md);
        }

        .product-rank {
            width: 40px;
            height: 40px;
            background: var(--gradient-primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 900;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid var(--gray-200);
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 5px;
        }

        .product-category {
            font-size: 0.875rem;
            color: var(--gray-500);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .product-stats {
            text-align: right;
        }

        .product-price {
            font-size: 1.125rem;
            font-weight: 900;
            color: var(--gray-900);
            margin-bottom: 5px;
        }

        .product-sales {
            font-size: 0.875rem;
            color: var(--success);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
            font-weight: 600;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 30px;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--gray-300);
            margin-bottom: 20px;
        }

        .empty-state p {
            color: var(--gray-500);
            font-size: 1rem;
            margin: 0;
        }

        /* Chart Legend */
        .chart-legend {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        /* Date Filter */
        .date-filter {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            border-radius: 12px;
            padding: 8px 16px;
            border: 2px solid var(--gray-200);
        }

        .date-filter input {
            border: none;
            outline: none;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            background: transparent;
        }

        .date-filter button {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .date-filter button:hover {
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 15px;
            }

            .hero-header {
                padding: 30px 20px;
            }

            .hero-content {
                flex-direction: column;
                gap: 20px;
            }

            .hero-text h1 {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions-grid {
                grid-template-columns: 1fr;
            }

            .insights-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .hero-badges {
                flex-direction: column;
                width: 100%;
            }

            .hero-badge {
                width: 100%;
                justify-content: center;
            }

            .insights-grid {
                grid-template-columns: 1fr;
            }

            .product-item {
                flex-direction: column;
                text-align: center;
            }

            .product-image {
                margin: 0 0 15px 0;
            }

            .product-stats {
                text-align: center;
                margin-top: 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        <!-- Hero Header -->
        <div class="hero-header">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>🚀 Welcome Back, {{ auth()->guard('seller')->user()->name ?? 'Seller' }}!</h1>
                    <p>Manage your store and track performance in real-time</p>
                </div>
                <div class="hero-badges">
                    <span class="hero-badge">
                        <i class="fas fa-store"></i>
                        <span>Seller ID: {{ auth()->guard('seller')->user()->code ?? 'N/A' }}</span>
                    </span>
                    <span class="hero-badge">
                        <i class="fas fa-star"></i>
                        <span>{{ $stats['avg_rating'] ?? '4.8' }}/5.0</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card" style="--stat-gradient: var(--gradient-primary); --stat-color: #667eea;">
                <div class="stat-header">
                    <div class="stat-title">Total Revenue</div>
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
                <div class="stat-value gradient">
                    RS. {{ number_format($stats['total_revenue'] ?? 0, 2) }}
                </div>
                <div class="stat-change {{ ($stats['revenue_change'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ ($stats['revenue_change'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ ($stats['revenue_change'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['revenue_change'] ?? 0 }}% this month</span>
                </div>
            </div>

            <div class="stat-card" style="--stat-gradient: var(--gradient-success); --stat-color: #10b981;">
                <div class="stat-header">
                    <div class="stat-title">Total Orders</div>
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['total_orders'] ?? 0 }}</div>
                <div class="stat-change {{ ($stats['order_change'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas fa-arrow-{{ ($stats['order_change'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ ($stats['order_change'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['order_change'] ?? 0 }}% this month</span>
                </div>
            </div>

            <div class="stat-card" style="--stat-gradient: var(--gradient-warning); --stat-color: #f59e0b;">
                <div class="stat-header">
                    <div class="stat-title">Pending Orders</div>
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['pending_orders'] ?? 0 }}</div>
                <div class="stat-change {{ ($stats['pending_orders'] ?? 0) > 0 ? 'negative' : 'positive' }}">
                    <i class="fas {{ ($stats['pending_orders'] ?? 0) > 0 ? 'fa-exclamation-circle' : 'fa-check-circle' }}"></i>
                    <span>{{ ($stats['pending_orders'] ?? 0) > 0 ? 'Needs attention' : 'All clear' }}</span>
                </div>
            </div>

            <div class="stat-card" style="--stat-gradient: var(--gradient-info); --stat-color: #3b82f6;">
                <div class="stat-header">
                    <div class="stat-title">Active Products</div>
                    <div class="stat-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['total_products'] ?? 0 }}</div>
                <div class="stat-change positive">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ $stats['active_products'] ?? 0 }} active</span>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="main-grid">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Sales Chart -->
                <div class="content-card">
                    <div class="card-header">
                        <h3 class="card-title">Sales Performance</h3>
                        <div class="chart-actions">
                            <button class="chart-btn active" data-period="monthly">Monthly</button>
                            <button class="chart-btn" data-period="weekly">Weekly</button>
                            <button class="chart-btn" data-period="yearly">Yearly</button>
                        </div>
                    </div>
                    <div class="date-filter">
                        <input type="date" id="startDate" value="{{ date('Y-m-01') }}">
                        <span>to</span>
                        <input type="date" id="endDate" value="{{ date('Y-m-d') }}">
                        <button onclick="updateChart()">Apply</button>
                    </div>
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background: #667eea;"></div>
                            <span>Revenue (RS)</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #f59e0b;"></div>
                            <span>Orders</span>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="content-card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Orders</h3>
{{--                        <a href="{{ route('seller.orders.index') }}" class="view-all-link">--}}
{{--                            View All <i class="fas fa-arrow-right"></i>--}}
{{--                        </a>--}}
                    </div>

                    @if(isset($recentOrders) && count($recentOrders) > 0)
                        <table class="modern-table">
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
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td><strong>#{{ $order->order_code ?? $order->id }}</strong></td>
                                    <td>
                                        <i class="fas fa-user-circle" style="color: var(--primary); margin-right: 8px;"></i>
                                        {{ $order->customer_name ?? ($order->customer->name ?? 'Customer') }}
                                    </td>
                                    <td><strong>RS. {{ number_format($order->total_amount ?? $order->amount ?? 0, 2) }}</strong></td>
                                    <td>
                                        <span class="badge-modern
                                            @if(in_array($order->status, ['pending', 'pending_payment'])) badge-pending
                                            @elseif(in_array($order->status, ['processing', 'confirmed'])) badge-processing
                                            @elseif($order->status == 'completed') badge-confirmed
                                            @else badge-cancelled @endif">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-shopping-bag"></i>
                            <p>No orders found</p>
                        </div>
                    @endif
                </div>

                <!-- Top Products -->
                <div class="content-card">
                    <div class="card-header">
                        <h3 class="card-title">Top Selling Products</h3>
                        <a href="{{ route('seller.product.index') }}" class="view-all-link">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    @if(isset($topProducts) && count($topProducts) > 0)
                        @foreach($topProducts as $product)
                            <a href="{{ route('seller.product.store', $product->id) }}" class="product-item">
                                <div class="product-rank">{{ $loop->iteration }}</div>
                                <img src="{{ asset('storage/' . $product->img) ?? 'https://via.placeholder.com/60' }}"
                                     class="product-image"
                                     alt="{{ $product->name }}"
                                     onerror="this.src='https://via.placeholder.com/60'">
                                <div class="product-info">
                                    <div class="product-name">{{ $product->name }}</div>
                                    <div class="product-category">
                                        <i class="fas fa-tag"></i>
                                        {{ $product->categoryModel->name ?? ($product->category ?? 'Uncategorized') }}
                                    </div>
                                </div>
                                <div class="product-stats">
                                    <div class="product-price">RS. {{ number_format($product->display_price ?? $product->price ?? 0, 2) }}</div>
                                    <div class="product-sales">
                                        <i class="fas fa-chart-line"></i>
                                        {{ $product->sales_count ?? $product->qty ?? 0 }} sold
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <p>No products data available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <!-- Quick Actions -->
                <div class="content-card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                    </div>
                    <div class="quick-actions-grid">
                        <a href="{{ route('seller.product.create') }}" class="quick-action-btn">
                            <i class="fas fa-plus-circle quick-action-icon"></i>
                            <span>Add Product</span>
                        </a>
                        <a href="{{ route('seller.product.index') }}" class="quick-action-btn">
                            <i class="fas fa-boxes quick-action-icon"></i>
                            <span>Manage Products</span>
                        </a>
{{--                        <a href="{{ route('seller.orders.index') }}" class="quick-action-btn">--}}
{{--                            <i class="fas fa-shopping-cart quick-action-icon"></i>--}}
{{--                            <span>View Orders</span>--}}
{{--                        </a>--}}
{{--                        <a href="{{ route('seller.profile') }}" class="quick-action-btn">--}}
{{--                            <i class="fas fa-user-cog quick-action-icon"></i>--}}
{{--                            <span>Profile Settings</span>--}}
{{--                        </a>--}}
                    </div>
                </div>

                <!-- Stock Alerts -->
                @if(isset($lowStockProducts) && count($lowStockProducts) > 0)
                    <div class="content-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-exclamation-triangle" style="color: var(--warning);"></i>
                                Stock Alerts
                            </h3>
                        </div>
                        @foreach($lowStockProducts as $product)
                            <a href="{{ route('seller.product.edit', $product->id) }}" class="stock-alert {{ ($product->qty <= 0) ? 'critical' : '' }}">
                                <div class="alert-header">
                                    <i class="fas {{ ($product->qty <= 0) ? 'fa-times-circle' : 'fa-exclamation-triangle' }}"></i>
                                    <strong>{{ $product->name }}</strong>
                                </div>
                                <div class="alert-body">
                                    <span>{{ ($product->qty <= 0) ? 'Out of Stock' : 'Low Stock' }}</span>
                                    <span><strong>{{ $product->qty ?? 0 }}</strong> units left</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Store Insights -->
                <div class="content-card">
                    <div class="card-header">
                        <h3 class="card-title">Store Insights</h3>
                    </div>
                    <div class="insights-grid">
                        <div class="insight-card" style="--insight-gradient: var(--gradient-danger); --insight-color: #ef4444;">
                            <div class="insight-header">
                                <div class="insight-icon">
                                    <i class="fas fa-bolt"></i>
                                </div>
                                <div class="insight-title">Conversion Rate</div>
                            </div>
                            <div class="insight-value">{{ $stats['conversion_rate'] ?? 0 }}%</div>
                            <p class="insight-desc">Orders/Views</p>
                        </div>

                        <div class="insight-card" style="--insight-gradient: var(--gradient-warning); --insight-color: #f59e0b;">
                            <div class="insight-header">
                                <div class="insight-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="insight-title">Growth</div>
                            </div>
                            <div class="insight-value">{{ ($stats['growth_rate'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['growth_rate'] ?? 0 }}%</div>
                            <p class="insight-desc">This month</p>
                        </div>

                        <div class="insight-card" style="--insight-gradient: var(--gradient-info); --insight-color: #3b82f6;">
                            <div class="insight-header">
                                <div class="insight-icon">
                                    <i class="fas fa-medal"></i>
                                </div>
                                <div class="insight-title">Avg. Rating</div>
                            </div>
                            <div class="insight-value">{{ $stats['avg_rating'] ?? '4.8' }}</div>
                            <p class="insight-desc">Out of 5.0</p>
                        </div>
                    </div>
                </div>

                <!-- Performance -->
                <div class="content-card">
                    <div class="card-header">
                        <h3 class="card-title">Performance</h3>
                    </div>
                    <div class="metric-item">
                        <div class="metric-header">
                            <h4 class="metric-title">Order Completion</h4>
                            <span class="metric-value">{{ $stats['completion_rate'] ?? 0 }}%</span>
                        </div>
                        <div class="progress-bar-modern">
                            <div class="progress-fill-modern" style="width: {{ $stats['completion_rate'] ?? 0 }}%"></div>
                        </div>
                    </div>

                    <div class="metric-item">
                        <div class="metric-header">
                            <h4 class="metric-title">Customer Satisfaction</h4>
                            <span class="metric-value">{{ $stats['satisfaction_rate'] ?? 0 }}%</span>
                        </div>
                        <div class="progress-bar-modern">
                            <div class="progress-fill-modern" style="width: {{ $stats['satisfaction_rate'] ?? 0 }}%"></div>
                        </div>
                    </div>

                    <div class="metric-item">
                        <div class="metric-header">
                            <h4 class="metric-title">Stock Health</h4>
                            <span class="metric-value">{{ $stats['stock_health'] ?? 0 }}%</span>
                        </div>
                        <div class="progress-bar-modern">
                            <div class="progress-fill-modern" style="width: {{ $stats['stock_health'] ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Laravel Session Messages with SweetAlert
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        @endif

        // Chart Initialization
        let salesChart = null;

        function initChart() {
            const ctx = document.getElementById('salesChart');
            if (!ctx) return;

            @if(isset($salesData) && count($salesData) > 0)
            const data = @json($salesData);
            const labels = data.map(item => item.label || item.date);
            const revenue = data.map(item => item.revenue || item.sales || 0);
            const orders = data.map(item => item.orders || item.count || 0);
            @else
            const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
            const revenue = [0, 0, 0, 0, 0, 0];
            const orders = [0, 0, 0, 0, 0, 0];
            @endif

            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(102, 126, 234, 0.3)');
            gradient.addColorStop(1, 'rgba(102, 126, 234, 0.05)');

            if (salesChart) {
                salesChart.destroy();
            }

            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue (RS)',
                        data: revenue,
                        borderColor: '#667eea',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#667eea',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }, {
                        label: 'Orders',
                        data: orders,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        yAxisID: 'y1',
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        y: {
                            position: 'left',
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                callback: value => 'RS. ' + value.toLocaleString(),
                                font: {
                                    weight: '600'
                                }
                            },
                            title: {
                                display: true,
                                text: 'Revenue (RS)',
                                font: {
                                    weight: '700',
                                    size: 12
                                }
                            }
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                font: {
                                    weight: '600'
                                }
                            },
                            title: {
                                display: true,
                                text: 'Orders',
                                font: {
                                    weight: '700',
                                    size: 12
                                }
                            }
                        },
                        x: {
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                font: {
                                    weight: '600'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    weight: '700'
                                },
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(30, 41, 59, 0.9)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#3b82f6',
                            borderWidth: 1,
                            padding: 12,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.datasetIndex === 0) {
                                        label += 'RS. ' + context.parsed.y.toLocaleString();
                                    } else {
                                        label += context.parsed.y + ' orders';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Update chart with date filter
        function updateChart() {
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();

            if (!startDate || !endDate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Please select date range',
                    text: 'Both start and end dates are required',
                    confirmButtonColor: '#3b82f6'
                });
                return;
            }

            // Show loading
            const loadingSwal = Swal.fire({
                title: 'Loading...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // AJAX request to get filtered data
            $.ajax({
                {{--url: '{{ route("seller.dashboard.chart") }}',--}}
                type: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    period: $('.chart-btn.active').data('period')
                },
                success: function(response) {
                    loadingSwal.close();

                    if (response.success) {
                        // Update chart with new data
                        if (salesChart) {
                            salesChart.data.labels = response.data.labels;
                            salesChart.data.datasets[0].data = response.data.revenue;
                            salesChart.data.datasets[1].data = response.data.orders;
                            salesChart.update();
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to load chart data',
                            confirmButtonColor: '#3b82f6'
                        });
                    }
                },
                error: function(xhr) {
                    loadingSwal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load chart data. Please try again.',
                        confirmButtonColor: '#3b82f6'
                    });
                }
            });
        }

        $(document).ready(function () {
            // Initialize chart
            initChart();

            // Chart Period Switcher
            $('.chart-btn').click(function () {
                $('.chart-btn').removeClass('active');
                $(this).addClass('active');
                updateChart();
            });

            // Animate Progress Bars
            $('.progress-fill-modern').each(function () {
                const width = $(this).css('width');
                $(this).css('width', '0');
                setTimeout(() => $(this).css('width', width), 300);
            });

            // Set default dates
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);

            $('#startDate').val(firstDay.toISOString().split('T')[0]);
            $('#endDate').val(today.toISOString().split('T')[0]);

            // Auto-refresh data every 5 minutes
            setInterval(function() {
                $.ajax({
                    url: '{{ route("seller.dashboard") }}',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            // Update stats
                            $('.stat-card:nth-child(1) .stat-value.gradient').text('RS. ' + parseFloat(response.stats.total_revenue).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                            $('.stat-card:nth-child(2) .stat-value').text(response.stats.total_orders);
                            $('.stat-card:nth-child(3) .stat-value').text(response.stats.pending_orders);
                            $('.stat-card:nth-child(4) .stat-value').text(response.stats.total_products);
                        }
                    }
                });
            }, 300000); // 5 minutes

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
