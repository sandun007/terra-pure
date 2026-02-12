@extends('seller.layout.app')
@push('title')
    Seller Dashboard
@endpush

@push('styles')
    <!-- Google Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #c7d2fe;
            --secondary: #8b5cf6;
            --success: #10b981;
            --success-dark: #059669;
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
            --gradient-success: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            --gradient-danger: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
            --gradient-warning: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            --gradient-info: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            --gradient-dark: linear-gradient(135deg, #4b5563 0%, #1f2937 100%);
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.12);
            --shadow-xl: 0 12px 48px rgba(0, 0, 0, 0.15);
            --shadow-primary: 0 4px 20px rgba(99, 102, 241, 0.2);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7ff 0%, #eef2ff 100%);
            min-height: 100vh;
        }

        .dashboard-container {
            padding: 20px;
            min-height: 100vh;
        }

        /* Modern Hero Header */
        .hero-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: var(--radius-xl);
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-primary);
            position: relative;
            overflow: hidden;
            animation: slideInDown 0.6s ease;
        }

        .hero-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15), transparent 70%);
            animation: float 8s ease-in-out infinite;
        }

        .hero-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1), transparent 70%);
            animation: floatReverse 10s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-30px, -30px) rotate(180deg); }
        }

        @keyframes floatReverse {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(30px, 30px) rotate(-180deg); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .hero-text h1 {
            color: white;
            font-size: 2.75rem;
            font-weight: 900;
            margin-bottom: 12px;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            background: linear-gradient(to right, #ffffff, #f3f4f6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero-text p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.125rem;
            margin: 0;
            max-width: 600px;
        }

        .hero-badges {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .hero-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 14px 28px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .hero-badge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .hero-badge:hover::before {
            left: 100%;
        }

        .hero-badge:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        /* Modern Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 30px;
            box-shadow: var(--shadow-lg);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s ease;
            animation-fill-mode: both;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-xl);
            border-color: var(--stat-color);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--stat-gradient);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }

        .stat-card:hover::after {
            transform: translateX(100%);
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
            transform: rotateY(180deg) scale(1.1);
        }

        .stat-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-value {
            font-size: 2.75rem;
            font-weight: 900;
            color: var(--gray-900);
            margin-bottom: 10px;
            line-height: 1;
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
            padding: 6px 12px;
            background: var(--gray-100);
            border-radius: 20px;
            width: fit-content;
            transition: all 0.3s ease;
        }

        .stat-change.positive {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .stat-change.negative {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .stat-card:hover .stat-change {
            transform: translateX(5px);
        }

        /* Modern Chart Container */
        .chart-container {
            position: relative;
            height: 350px;
            margin-top: 20px;
            background: white;
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .chart-container:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .chart-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .chart-tab {
            padding: 10px 24px;
            border: 2px solid var(--gray-200);
            background: white;
            color: var(--gray-600);
            border-radius: var(--radius-md);
            font-weight: 700;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .chart-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--gradient-primary);
            transform: translateY(100%);
            transition: transform 0.3s ease;
            z-index: 1;
        }

        .chart-tab.active::before {
            transform: translateY(0);
        }

        .chart-tab span {
            position: relative;
            z-index: 2;
        }

        .chart-tab.active {
            color: white;
            border-color: transparent;
            box-shadow: var(--shadow-primary);
        }

        .chart-tab:hover:not(.active) {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        /* Modern Table */
        .modern-table-container {
            background: white;
            border-radius: var(--radius-lg);
            padding: 25px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .modern-table-container:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .modern-table thead {
            background: linear-gradient(135deg, var(--gray-50), white);
        }

        .modern-table thead th {
            padding: 18px 20px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray-700);
            border-bottom: 2px solid var(--gray-200);
            text-align: left;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
            border-radius: var(--radius-sm);
        }

        .modern-table tbody tr:hover {
            background: var(--gray-50);
            transform: translateX(5px);
        }

        .modern-table tbody td {
            padding: 20px;
            border-bottom: 1px solid var(--gray-100);
            color: var(--gray-700);
            font-weight: 600;
        }

        /* Modern Badge */
        .badge-modern {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 2px solid;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .badge-modern i {
            font-size: 0.875rem;
        }

        .badge-pending {
            border-color: rgba(245, 158, 11, 0.3);
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.15), rgba(245, 158, 11, 0.25));
            color: #92400e;
        }

        .badge-processing {
            border-color: rgba(59, 130, 246, 0.3);
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15), rgba(59, 130, 246, 0.25));
            color: #1e40af;
        }

        .badge-delivered {
            border-color: rgba(16, 185, 129, 0.3);
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.25));
            color: #065f46;
        }

        .badge-cancelled {
            border-color: rgba(239, 68, 68, 0.3);
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.25));
            color: #991b1b;
        }

        /* Modern Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }

        .grid-column {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        /* Modern Card */
        .modern-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 30px;
            box-shadow: var(--shadow-md);
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .modern-card:hover {
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
            transform: translateY(-5px);
        }

        .modern-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gradient-primary);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .modern-card:hover::before {
            transform: scaleY(1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
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

        .card-title i {
            color: var(--primary);
        }

        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: var(--shadow-sm);
            border: 2px solid var(--gray-200);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .product-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: var(--radius-md);
            margin-bottom: 15px;
        }

        .product-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .product-name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-900);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 1.25rem;
            font-weight: 900;
            color: var(--primary);
        }

        /* Quick Actions */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .quick-action {
            background: linear-gradient(135deg, var(--gray-50), white);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            padding: 20px;
            text-decoration: none;
            color: var(--gray-700);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            text-align: center;
        }

        .quick-action:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
            transform: translateY(-3px);
            box-shadow: var(--shadow-primary);
        }

        .quick-action i {
            font-size: 2rem;
            color: var(--primary);
            transition: all 0.3s ease;
        }

        .quick-action:hover i {
            color: white;
            transform: scale(1.2);
        }

        .quick-action span {
            font-weight: 700;
            font-size: 0.875rem;
        }

        /* Stock Alert */
        .stock-alert {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.2));
            border: 2px solid rgba(245, 158, 11, 0.3);
            border-radius: var(--radius-lg);
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
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

        /* Performance Metrics */
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .metric-item {
            background: linear-gradient(135deg, var(--gray-50), white);
            border-radius: var(--radius-lg);
            padding: 20px;
            border: 2px solid var(--gray-200);
            transition: all 0.3s ease;
        }

        .metric-item:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 900;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .metric-label {
            font-size: 0.875rem;
            color: var(--gray-600);
            font-weight: 600;
        }

        /* Progress Bar */
        .progress-container {
            margin-top: 15px;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-600);
        }

        .progress-bar {
            height: 10px;
            background: var(--gray-200);
            border-radius: 5px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--gradient-primary);
            border-radius: 5px;
            transition: width 1s ease;
            position: relative;
        }

        .progress-fill::after {
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
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .dashboard-grid {
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

            .hero-text h1 {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions-grid {
                grid-template-columns: 1fr;
            }

            .product-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-delay-100 { animation-delay: 0.1s; }
        .animate-delay-200 { animation-delay: 0.2s; }
        .animate-delay-300 { animation-delay: 0.3s; }
        .animate-delay-400 { animation-delay: 0.4s; }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--gradient-danger);
            color: white;
            font-size: 0.75rem;
            font-weight: 900;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        <!-- Hero Header -->
        <div class="hero-header">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>🎯 Welcome Back, {{ auth()->guard('seller')->user()->name ?? 'Seller' }}!</h1>
                    <p>Your store dashboard - Monitor sales, manage orders, and track performance</p>
                </div>
                <div class="hero-badges">
                    <div class="hero-badge">
                        <span class="material-symbols-outlined">store</span>
                        <span>Store ID: {{ auth()->guard('seller')->user()->code ?? 'N/A' }}</span>
                    </div>
                    <div class="hero-badge" onclick="showPerformanceReport()">
                        <span class="material-symbols-outlined">trending_up</span>
                        <span>Performance: {{ $stats['performance_score'] ?? '85' }}%</span>
                    </div>
                    <div class="hero-badge" onclick="showRatingDetails()">
                        <span class="material-symbols-outlined">star</span>
                        <span>Rating: {{ $stats['avg_rating'] ?? '4.8' }}/5.0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card" style="--stat-gradient: var(--gradient-primary); --stat-color: #6366f1;"
                 onclick="showRevenueDetails()">
                <div class="stat-header">
                    <div class="stat-title">Total Revenue</div>
                    <div class="stat-icon">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                </div>
                <div class="stat-value">RS. {{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                <div class="stat-change {{ ($stats['revenue_change'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                    <span class="material-symbols-outlined">
                        {{ ($stats['revenue_change'] ?? 0) >= 0 ? 'trending_up' : 'trending_down' }}
                    </span>
                    <span>{{ ($stats['revenue_change'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['revenue_change'] ?? 0 }}% this month</span>
                </div>
            </div>

            <div class="stat-card" style="--stat-gradient: var(--gradient-success); --stat-color: #10b981;"
                 onclick="showOrdersDetails()">
                <div class="stat-header">
                    <div class="stat-title">Total Orders</div>
                    <div class="stat-icon">
                        <span class="material-symbols-outlined">shopping_cart</span>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($stats['total_orders'] ?? 0) }}</div>
                <div class="stat-change {{ ($stats['order_change'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                    <span class="material-symbols-outlined">
                        {{ ($stats['order_change'] ?? 0) >= 0 ? 'trending_up' : 'trending_down' }}
                    </span>
                    <span>{{ ($stats['order_change'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['order_change'] ?? 0 }}% this month</span>
                </div>
            </div>

            <div class="stat-card" style="--stat-gradient: var(--gradient-warning); --stat-color: #f59e0b;"
                 onclick="showPendingOrders()">
                <div class="stat-header">
                    <div class="stat-title">Pending Orders</div>
                    <div class="stat-icon">
                        <span class="material-symbols-outlined">pending_actions</span>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['pending_orders'] ?? 0 }}</div>
                <div class="stat-change {{ ($stats['pending_orders'] ?? 0) > 0 ? 'negative' : 'positive' }}">
                    <span class="material-symbols-outlined">
                        {{ ($stats['pending_orders'] ?? 0) > 0 ? 'warning' : 'check_circle' }}
                    </span>
                    <span>{{ ($stats['pending_orders'] ?? 0) > 0 ? 'Needs attention' : 'All clear' }}</span>
                </div>
                @if(($stats['pending_orders'] ?? 0) > 0)
                    <div class="notification-badge">{{ $stats['pending_orders'] ?? 0 }}</div>
                @endif
            </div>

            <div class="stat-card" style="--stat-gradient: var(--gradient-info); --stat-color: #3b82f6;"
                 onclick="showProductsOverview()">
                <div class="stat-header">
                    <div class="stat-title">Active Products</div>
                    <div class="stat-icon">
                        <span class="material-symbols-outlined">inventory_2</span>
                    </div>
                </div>
                <div class="stat-value">{{ $stats['total_products'] ?? 0 }}</div>
                <div class="stat-change positive">
                    <span class="material-symbols-outlined">check_circle</span>
                    <span>{{ $stats['active_products'] ?? 0 }} active</span>
                </div>
            </div>
        </div>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Left Column -->
            <div class="grid-column">
                <!-- Sales Chart -->
                <div class="modern-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="material-symbols-outlined">trending_up</span>
                            Sales Performance
                        </h3>
                        <div class="chart-tabs">
                            <button class="chart-tab active" data-period="weekly">
                                <span>Weekly</span>
                            </button>
                            <button class="chart-tab" data-period="monthly">
                                <span>Monthly</span>
                            </button>
                            <button class="chart-tab" data-period="yearly">
                                <span>Yearly</span>
                            </button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                    <div class="chart-legend" style="margin-top: 20px; display: flex; gap: 20px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 12px; height: 12px; background: #6366f1; border-radius: 50%;"></div>
                            <span style="font-size: 0.875rem; color: var(--gray-600);">Revenue</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 12px; height: 12px; background: #10b981; border-radius: 50%;"></div>
                            <span style="font-size: 0.875rem; color: var(--gray-600);">Orders</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="modern-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="material-symbols-outlined">receipt_long</span>
                            Recent Orders
                        </h3>
                        {{--                        <a href="{{ route('seller.orders.index') }}" style="--}}
                        {{--                            color: var(--primary);--}}
                        {{--                            text-decoration: none;--}}
                        {{--                            font-weight: 700;--}}
                        {{--                            font-size: 0.875rem;--}}
                        {{--                            display: flex;--}}
                        {{--                            align-items: center;--}}
                        {{--                            gap: 8px;--}}
                        {{--                        ">--}}
                        {{--                            View All <span class="material-symbols-outlined">arrow_forward</span>--}}
                        {{--                        </a>--}}
                    </div>

                    @if(isset($recentOrders) && count($recentOrders) > 0)
                        <div class="modern-table-container">
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
                                    <tr onclick="viewOrderDetails({{ $order->id }})">
                                        <td><strong>#{{ $order->order_code ?? $order->id }}</strong></td>
                                        <td>
                                                <span class="material-symbols-outlined" style="font-size: 1rem; color: var(--primary); margin-right: 8px;">
                                                    person
                                                </span>
                                            {{ $order->customer_name ?? ($order->customer->name ?? 'Customer') }}
                                        </td>
                                        <td><strong>RS. {{ number_format($order->total_amount ?? $order->amount ?? 0, 2) }}</strong></td>
                                        <td>
                                                <span class="badge-modern
                                                    @if(in_array($order->status, ['pending', 'pending_payment'])) badge-pending
                                                    @elseif(in_array($order->status, ['processing', 'confirmed'])) badge-processing
                                                    @elseif($order->status == 'completed') badge-delivered
                                                    @else badge-cancelled @endif">
                                                    <span class="material-symbols-outlined" style="font-size: 0.875rem;">
                                                        @if(in_array($order->status, ['pending', 'pending_payment'])) pending
                                                        @elseif(in_array($order->status, ['processing', 'confirmed'])) schedule
                                                        @elseif($order->status == 'completed') check_circle
                                                        @else cancel @endif
                                                    </span>
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <span class="material-symbols-outlined">shopping_bag</span>
                            <p>No orders found</p>
                        </div>
                    @endif
                </div>

                <!-- Top Products -->
                <div class="modern-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="material-symbols-outlined">local_fire_department</span>
                            Top Selling Products
                        </h3>
                        <a href="{{ route('seller.product.index') }}" style="
                            color: var(--primary);
                            text-decoration: none;
                            font-weight: 700;
                            font-size: 0.875rem;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                        ">
                            View All <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>

                    @if(isset($topProducts) && count($topProducts) > 0)
                        <div class="product-grid">
                            @foreach($topProducts as $product)
                                <div class="product-card" onclick="viewProduct({{ $product->id }})">
                                    <img src="{{ asset('storage/' . $product->img) ?? 'https://via.placeholder.com/300' }}"
                                         class="product-image"
                                         alt="{{ $product->name }}"
                                         onerror="this.src='https://via.placeholder.com/300'">
                                    <div class="product-info">
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div class="product-price">RS. {{ number_format($product->display_price ?? $product->price ?? 0, 2) }}</div>
                                            <div style="display: flex; align-items: center; gap: 4px; color: var(--success); font-weight: 700;">
                                                <span class="material-symbols-outlined" style="font-size: 1rem;">trending_up</span>
                                                <span>{{ $product->sales_count ?? $product->qty ?? 0 }} sold</span>
                                            </div>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 4px; color: var(--gray-500); font-size: 0.875rem;">
                                            <span class="material-symbols-outlined" style="font-size: 1rem;">category</span>
                                            <span>{{ $product->categoryModel->name ?? ($product->category ?? 'Uncategorized') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <span class="material-symbols-outlined">inventory_2</span>
                            <p>No products data available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column -->
            <div class="grid-column">
                <!-- Quick Actions -->
                <div class="modern-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="material-symbols-outlined">bolt</span>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="quick-actions-grid">
                        <a href="{{ route('seller.product.create') }}" class="quick-action">
                            <span class="material-symbols-outlined">add_circle</span>
                            <span>Add Product</span>
                        </a>
                        <a href="{{ route('seller.product.index') }}" class="quick-action">
                            <span class="material-symbols-outlined">inventory</span>
                            <span>Manage Products</span>
                        </a>
                                                <a href="{{ route('seller.new-order') }}" class="quick-action">
                                                    <span class="material-symbols-outlined">shopping_cart</span>
                                                    <span>View Orders</span>
                                                </a>
                        {{--                        <a href="{{ route('seller.reports') }}" class="quick-action">--}}
                        {{--                            <span class="material-symbols-outlined">analytics</span>--}}
                        {{--                            <span>View Reports</span>--}}
                        {{--                        </a>--}}
                    </div>
                </div>

                <!-- Stock Alerts -->
                @if(isset($lowStockProducts) && count($lowStockProducts) > 0)
                    <div class="modern-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <span class="material-symbols-outlined" style="color: var(--warning);">warning</span>
                                Stock Alerts
                            </h3>
                        </div>
                        @foreach($lowStockProducts as $product)
                            <div class="stock-alert {{ ($product->qty <= 0) ? 'critical' : '' }}"
                                 onclick="restockProduct({{ $product->id }})">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <span class="material-symbols-outlined">
                                            {{ ($product->qty <= 0) ? 'cancel' : 'warning' }}
                                        </span>
                                        <div>
                                            <strong>{{ $product->name }}</strong>
                                            <div style="font-size: 0.875rem; color: var(--gray-600); margin-top: 4px;">
                                                {{ ($product->qty <= 0) ? 'Out of Stock' : 'Low Stock' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div style="font-weight: 900; color: {{ ($product->qty <= 0) ? 'var(--danger)' : 'var(--warning)' }};">
                                        {{ $product->qty ?? 0 }} units
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Performance Metrics -->
                <div class="modern-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="material-symbols-outlined">speed</span>
                            Performance Metrics
                        </h3>
                    </div>
                    <div class="metrics-grid">
                        <div class="metric-item">
                            <div class="metric-value">{{ $stats['conversion_rate'] ?? 0 }}%</div>
                            <div class="metric-label">Conversion Rate</div>
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $stats['conversion_rate'] ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="metric-item">
                            <div class="metric-value">{{ $stats['satisfaction_rate'] ?? 0 }}%</div>
                            <div class="metric-label">Customer Satisfaction</div>
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $stats['satisfaction_rate'] ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="metric-item">
                            <div class="metric-value">{{ $stats['completion_rate'] ?? 0 }}%</div>
                            <div class="metric-label">Order Completion</div>
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $stats['completion_rate'] ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="metric-item">
                            <div class="metric-value">{{ $stats['growth_rate'] ?? 0 }}%</div>
                            <div class="metric-label">Monthly Growth</div>
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: min({{ $stats['growth_rate'] ?? 0 }}%, 100)"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Store Insights -->
                <div class="modern-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="material-symbols-outlined">insights</span>
                            Store Insights
                        </h3>
                    </div>
                    <div style="display: grid; gap: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: var(--gray-50); border-radius: var(--radius-md);">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span class="material-symbols-outlined" style="color: var(--primary);">visibility</span>
                                <div>
                                    <div style="font-weight: 700; font-size: 0.875rem;">Product Views</div>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">This month</div>
                                </div>
                            </div>
                            <div style="font-weight: 900; font-size: 1.25rem;">{{ number_format($stats['product_views'] ?? 0) }}</div>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: var(--gray-50); border-radius: var(--radius-md);">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span class="material-symbols-outlined" style="color: var(--success);">repeat</span>
                                <div>
                                    <div style="font-weight: 700; font-size: 0.875rem;">Repeat Customers</div>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">Loyal clients</div>
                                </div>
                            </div>
                            <div style="font-weight: 900; font-size: 1.25rem;">{{ $stats['repeat_customers'] ?? 0 }}</div>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: var(--gray-50); border-radius: var(--radius-md);">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span class="material-symbols-outlined" style="color: var(--info);">schedule</span>
                                <div>
                                    <div style="font-weight: 700; font-size: 0.875rem;">Avg. Processing Time</div>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">Order fulfillment</div>
                                </div>
                            </div>
                            <div style="font-weight: 900; font-size: 1.25rem;">{{ $stats['avg_processing_time'] ?? '24' }}h</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Welcome Notification
        @if(session('success'))
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                background: '#10b981',
                color: 'white',
                iconColor: 'white'
            });
        });
        @endif

        @if(session('error'))
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#6366f1',
                background: '#fef2f2',
                color: '#991b1b'
            });
        });
        @endif

        // Initialize Chart
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
            const labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            const revenue = [1500, 2300, 1800, 2500, 3200, 2800, 3500];
            const orders = [15, 23, 18, 25, 32, 28, 35];
            @endif

            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0.05)');

            if (salesChart) {
                salesChart.destroy();
            }

            salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Revenue (RS)',
                            data: revenue,
                            borderColor: '#6366f1',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#6366f1',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6
                        },
                        {
                            label: 'Orders',
                            data: orders,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(30, 41, 59, 0.95)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#6366f1',
                            borderWidth: 1,
                            padding: 12,
                            usePointStyle: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'RS. ' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }

        // Chart Tab Switching
        document.querySelectorAll('.chart-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.chart-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const period = this.dataset.period;
                updateChartData(period);
            });
        });

        function updateChartData(period) {
            Swal.fire({
                title: 'Loading Data...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate API call
            setTimeout(() => {
                Swal.close();

                // Update chart with new data based on period
                const newLabels = getLabelsForPeriod(period);
                const newRevenue = generateRandomData(newLabels.length, 1000, 5000);
                const newOrders = generateRandomData(newLabels.length, 10, 50);

                if (salesChart) {
                    salesChart.data.labels = newLabels;
                    salesChart.data.datasets[0].data = newRevenue;
                    salesChart.data.datasets[1].data = newOrders;
                    salesChart.update();
                }

                showSuccessToast(`Switched to ${period} view`);
            }, 1000);
        }

        function getLabelsForPeriod(period) {
            switch(period) {
                case 'weekly':
                    return ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                case 'monthly':
                    return ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                case 'yearly':
                    return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                default:
                    return ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            }
        }

        function generateRandomData(count, min, max) {
            return Array.from({length: count}, () =>
                Math.floor(Math.random() * (max - min + 1)) + min
            );
        }

        // Interactive Functions
        function showRevenueDetails() {
            Swal.fire({
                title: '📊 Revenue Details',
                html: `
                    <div style="text-align: left; margin: 20px 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Total Revenue:</span>
                            <strong>RS. {{ number_format($stats['total_revenue'] ?? 0, 2) }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>This Month:</span>
                            <strong>RS. {{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Last Month:</span>
                            <strong>RS. {{ number_format($stats['last_month_revenue'] ?? 0, 2) }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Growth:</span>
                            <strong style="color: {{ ($stats['revenue_change'] ?? 0) >= 0 ? '#10b981' : '#ef4444' }};">
                                {{ ($stats['revenue_change'] ?? 0) >= 0 ? '+' : '' }}{{ $stats['revenue_change'] ?? 0 }}%
                            </strong>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Close',
                confirmButtonColor: '#6366f1',
                width: 500
            });
        }

        function showOrdersDetails() {
            Swal.fire({
                title: '📦 Order Statistics',
                html: `
                    <div style="text-align: left; margin: 20px 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Total Orders:</span>
                            <strong>{{ $stats['total_orders'] ?? 0 }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Completed:</span>
                            <strong style="color: #10b981;">{{ $stats['completed_orders'] ?? 0 }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Pending:</span>
                            <strong style="color: #f59e0b;">{{ $stats['pending_orders'] ?? 0 }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Cancelled:</span>
                            <strong style="color: #ef4444;">{{ $stats['cancelled_orders'] ?? 0 }}</strong>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Close',
                confirmButtonColor: '#6366f1',
                width: 500
            });
        }

        function showPendingOrders() {
            Swal.fire({
                title: '⏳ Pending Orders',
                html: `
                    <div style="text-align: left; margin: 20px 0;">
                        <p>You have <strong>{{ $stats['pending_orders'] ?? 0 }}</strong> pending orders that need attention.</p>
                        <p style="color: var(--gray-600); font-size: 0.875rem; margin-top: 10px;">
                            <span class="material-symbols-outlined" style="font-size: 1rem; vertical-align: middle;">
                                info
                            </span>
                            Please process these orders to avoid delays.
                        </p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'View Orders',
                cancelButtonText: 'Close',
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('seller.new-order') }}?status=pending';
                }
            });
        }

        function showPerformanceReport() {
            const performance = {{ $stats['performance_score'] ?? 85 }};
            let message = '';
            let icon = 'success';

            if (performance >= 90) {
                message = 'Excellent performance! Keep up the great work!';
                icon = 'success';
            } else if (performance >= 75) {
                message = 'Good performance! There\'s room for improvement.';
                icon = 'info';
            } else {
                message = 'Performance needs improvement. Check your metrics.';
                icon = 'warning';
            }

            Swal.fire({
                title: '📈 Performance Report',
                html: `
                    <div style="text-align: center; margin: 20px 0;">
                        <div style="font-size: 3rem; font-weight: 900; color: #6366f1; margin-bottom: 20px;">
                            ${performance}%
                        </div>
                        <div style="margin: 20px 0;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>Order Completion:</span>
                                <strong>{{ $stats['completion_rate'] ?? 0 }}%</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>Customer Satisfaction:</span>
                                <strong>{{ $stats['satisfaction_rate'] ?? 0 }}%</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>Response Time:</span>
                                <strong>{{ $stats['avg_response_time'] ?? '2' }}h</strong>
                            </div>
                        </div>
                        <p style="color: var(--gray-600);">${message}</p>
                    </div>
                `,
                icon: icon,
                confirmButtonText: 'View Details',
                confirmButtonColor: '#6366f1',
                width: 500,
                showCancelButton: true,
                cancelButtonText: 'Close'
            });
        }

        function showRatingDetails() {
            Swal.fire({
                title: '⭐ Customer Ratings',
                html: `
                    <div style="text-align: left; margin: 20px 0;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                            <div style="font-size: 2.5rem; font-weight: 900; color: #f59e0b;">
                                {{ $stats['avg_rating'] ?? '4.8' }}
                </div>
                <div>
                    <div style="color: #f59e0b; font-size: 1.25rem;">
${generateStars({{ $stats['avg_rating'] ?? 4.8 }})}
                                </div>
                                <div style="color: var(--gray-600); font-size: 0.875rem;">
                                    Based on {{ $stats['total_reviews'] ?? 0 }} reviews
                                </div>
                            </div>
                        </div>

                        <div style="margin: 20px 0;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                <span>5 stars</span>
                                <div style="flex: 1; height: 8px; background: var(--gray-200); border-radius: 4px;">
                                    <div style="width: 70%; height: 100%; background: #f59e0b; border-radius: 4px;"></div>
                                </div>
                                <span>70%</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                <span>4 stars</span>
                                <div style="flex: 1; height: 8px; background: var(--gray-200); border-radius: 4px;">
                                    <div style="width: 20%; height: 100%; background: #fbbf24; border-radius: 4px;"></div>
                                </div>
                                <span>20%</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                <span>3 stars</span>
                                <div style="flex: 1; height: 8px; background: var(--gray-200); border-radius: 4px;">
                                    <div style="width: 7%; height: 100%; background: #fcd34d; border-radius: 4px;"></div>
                                </div>
                                <span>7%</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                <span>2 stars</span>
                                <div style="flex: 1; height: 8px; background: var(--gray-200); border-radius: 4px;">
                                    <div style="width: 2%; height: 100%; background: #fde68a; border-radius: 4px;"></div>
                                </div>
                                <span>2%</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span>1 star</span>
                                <div style="flex: 1; height: 8px; background: var(--gray-200); border-radius: 4px;">
                                    <div style="width: 1%; height: 100%; background: #fef3c7; border-radius: 4px;"></div>
                                </div>
                                <span>1%</span>
                            </div>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'View Reviews',
                confirmButtonColor: '#f59e0b',
                width: 500,
                showCancelButton: true,
                cancelButtonText: 'Close'
            });
        }

        function generateStars(rating) {
            let stars = '';
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 >= 0.5;

            for (let i = 0; i < fullStars; i++) {
                stars += '★';
            }

            if (hasHalfStar) {
                stars += '★';
            }

            return stars;
        }

        function viewOrderDetails(orderId) {
            Swal.fire({
                title: 'Loading order details...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simulate API call
            setTimeout(() => {
                Swal.fire({
                    title: 'Order #' + orderId,
                    html: `
                        <div style="text-align: left; margin: 20px 0;">
                            <p>Order details would be displayed here.</p>
                            <p>This is a simulation. In production, this would fetch real data.</p>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'Close',
                    confirmButtonColor: '#6366f1'
                });
            }, 1000);
        }

        function viewProduct(productId) {
            window.location.href = `/seller/products/${productId}`;
        }

        function restockProduct(productId) {
            Swal.fire({
                title: '🛒 Restock Product',
                html: `
                    <div style="text-align: left; margin: 20px 0;">
                        <p>How many units would you like to add?</p>
                        <input type="number" id="restockQuantity"
                               class="swal2-input"
                               placeholder="Enter quantity"
                               min="1"
                               value="10"
                               style="width: 80%;">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Restock',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#6366f1',
                preConfirm: () => {
                    const quantity = document.getElementById('restockQuantity').value;
                    if (!quantity || quantity < 1) {
                        Swal.showValidationMessage('Please enter a valid quantity');
                        return false;
                    }
                    return quantity;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Simulate API call
                    Swal.fire({
                        title: 'Restocking...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    setTimeout(() => {
                        Swal.fire({
                            title: 'Success!',
                            text: `Added ${result.value} units to stock`,
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#10b981'
                        });
                    }, 1500);
                }
            });
        }

        function showProductsOverview() {
            Swal.fire({
                title: '📦 Products Overview',
                html: `
                    <div style="text-align: left; margin: 20px 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Total Products:</span>
                            <strong>{{ $stats['total_products'] ?? 0 }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Active Products:</span>
                            <strong style="color: #10b981;">{{ $stats['active_products'] ?? 0 }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Out of Stock:</span>
                            <strong style="color: #ef4444;">{{ $stats['out_of_stock'] ?? 0 }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <span>Low Stock:</span>
                            <strong style="color: #f59e0b;">{{ $stats['low_stock'] ?? 0 }}</strong>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Manage Products',
                confirmButtonColor: '#6366f1',
                showCancelButton: true,
                cancelButtonText: 'Close'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('seller.product.index') }}';
                }
            });
        }

        // Auto-refresh dashboard every 5 minutes
        function refreshDashboard() {
            $.ajax({
                url: '{{ route("seller.dashboard") }}',
                method: 'GET',
                success: function(response) {
                    // Update stats dynamically
                    console.log('Dashboard refreshed');
                },
                error: function() {
                    console.error('Failed to refresh dashboard');
                }
            });
        }

        // Initialize dashboard
        $(document).ready(function() {
            initChart();

            // Start auto-refresh
            setInterval(refreshDashboard, 300000); // 5 minutes

            // Show welcome message
            setTimeout(() => {
                if (!localStorage.getItem('dashboardWelcomeShown')) {
                    showWelcomeMessage();
                    localStorage.setItem('dashboardWelcomeShown', 'true');
                }
            }, 1000);
        });

        function showWelcomeMessage() {
            const welcomeMessages = [
                "Welcome back! Your store is looking great! 🚀",
                "Ready to boost your sales today? 💰",
                "Check out your latest stats and insights! 📊",
                "Great job on maintaining your store! 👍"
            ];

            const randomMessage = welcomeMessages[Math.floor(Math.random() * welcomeMessages.length)];

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            Toast.fire({
                icon: 'success',
                title: randomMessage,
                background: '#6366f1',
                color: 'white',
                iconColor: 'white'
            });
        }

        function showSuccessToast(message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            Toast.fire({
                icon: 'success',
                title: message,
                background: '#10b981',
                color: 'white',
                iconColor: 'white'
            });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + R to refresh
            if (e.ctrlKey && e.key === 'r') {
                e.preventDefault();
                refreshDashboard();
                showSuccessToast('Dashboard refreshed!');
            }

            // Ctrl + N for new product
            if (e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                window.location.href = '{{ route('seller.product.create') }}';
            }
        });
    </script>
@endpush
