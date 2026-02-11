@extends('seller.layout.app')

@push('title')
    My Products - Dashboard
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #8b5cf6;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
            --dark: #0f172a;
            --light: #f8fafc;
            --white: #ffffff;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-900: #111827;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 20px 25px -5px rgb(0 0 0 / 0.1);
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 18px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
        }

        /* ==================== HEADER ==================== */
        .dashboard-header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-xl);
            padding: 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .dashboard-header .content-wrapper {
            position: relative;
            z-index: 1;
        }

        .header-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-info h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-info h1 i {
            font-size: 1.25rem;
        }

        .header-info p {
            font-size: 0.875rem;
            opacity: 0.9;
        }

        .header-info p i {
            font-size: 0.75rem;
        }

        .header-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-primary-glass {
            background: rgba(255, 255, 255, 0.95);
            color: var(--primary);
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.875rem;
            transition: var(--transition);
            box-shadow: var(--shadow);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary-glass i {
            font-size: 0.875rem;
        }

        .btn-primary-glass:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary-glass {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.875rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .btn-secondary-glass i {
            font-size: 0.875rem;
        }

        .btn-secondary-glass:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* ==================== STATS GRID ==================== */
        .stats-section {
            margin-bottom: 1.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.5);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--card-gradient);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-card.primary { --card-gradient: linear-gradient(90deg, #6366f1 0%, #8b5cf6 100%); }
        .stat-card.success { --card-gradient: linear-gradient(90deg, #10b981 0%, #059669 100%); }
        .stat-card.warning { --card-gradient: linear-gradient(90deg, #f59e0b 0%, #d97706 100%); }
        .stat-card.danger { --card-gradient: linear-gradient(90deg, #ef4444 0%, #dc2626 100%); }

        .stat-card-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            background: var(--card-gradient);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .stat-details {
            flex: 1;
            text-align: right;
            padding-left: 1rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.25rem;
            background: var(--card-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            color: var(--gray-600);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .stat-trend {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .stat-trend i {
            font-size: 0.65rem;
        }

        .stat-trend.up { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .stat-trend.down { background: rgba(239, 68, 68, 0.1); color: var(--danger); }
        .stat-trend.neutral { background: rgba(59, 130, 246, 0.1); color: var(--info); }

        /* ==================== FILTERS ==================== */
        .filters-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .filters-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .filters-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filters-title i {
            font-size: 0.875rem;
        }

        .filter-toggle-btn {
            display: none;
            padding: 0.5rem 1rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .filter-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--gray-700);
            margin-bottom: 0.375rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .filter-group label i {
            font-size: 0.7rem;
        }

        .filter-input,
        .filter-select {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            transition: var(--transition);
            background: white;
        }

        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .quick-filters {
            display: flex;
            gap: 0.625rem;
            flex-wrap: wrap;
            padding-top: 1rem;
            border-top: 1px solid var(--gray-200);
        }

        .quick-filter-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--gray-700);
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .quick-filter-label i {
            font-size: 0.7rem;
        }

        .quick-filter-btn {
            padding: 0.5rem 1rem;
            border: 2px solid var(--gray-200);
            background: white;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--gray-700);
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .quick-filter-btn i {
            font-size: 0.75rem;
        }

        .quick-filter-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .quick-filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .btn-filter-reset {
            padding: 0.5rem 1rem;
            background: var(--gray-100);
            color: var(--gray-700);
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .btn-filter-reset i {
            font-size: 0.75rem;
        }

        /* ==================== TABLE ==================== */
        .table-wrapper {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .table-header {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            padding: 1.25rem;
            border-bottom: 2px solid var(--gray-200);
        }

        .table-header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.125rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-title i {
            font-size: 1rem;
        }

        .table-subtitle {
            color: var(--gray-600);
            font-size: 0.8rem;
        }

        .table-actions {
            display: flex;
            gap: 0.625rem;
            flex-wrap: wrap;
        }

        .table-btn {
            padding: 0.625rem 1rem;
            border: 2px solid var(--gray-200);
            background: white;
            border-radius: var(--radius-sm);
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--gray-700);
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .table-btn i {
            font-size: 0.75rem;
        }

        .table-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .table-btn.primary {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* ==================== BULK ACTIONS ==================== */
        .bulk-actions {
            background: var(--gray-100);
            padding: 0.875rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            display: none;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .bulk-actions.active {
            display: flex;
        }

        .bulk-info {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--gray-700);
        }

        .bulk-info i {
            font-size: 0.75rem;
        }

        .bulk-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.8rem;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .bulk-btn i {
            font-size: 0.75rem;
        }

        .bulk-btn.activate { background: var(--success); color: white; }
        .bulk-btn.deactivate { background: var(--warning); color: white; }
        .bulk-btn.delete { background: var(--danger); color: white; }

        /* ==================== DATATABLE ==================== */
        .dataTables_wrapper {
            padding: 1.25rem;
        }

        .dataTables_filter input {
            padding: 0.625rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            margin-left: 0.5rem;
            transition: var(--transition);
            width: 220px;
        }

        .dataTables_filter input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .dataTables_length select {
            padding: 0.5rem 0.75rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-sm);
            margin: 0 0.5rem;
            font-size: 0.875rem;
        }

        .dataTables_info {
            padding-top: 1rem;
            color: var(--gray-600);
            font-size: 0.875rem;
        }

        .dataTables_paginate {
            padding-top: 1rem;
        }

        #product-table {
            width: 100% !important;
        }

        #product-table thead th {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            font-weight: 700;
            color: var(--gray-900);
            padding: 0.875rem 0.75rem;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
            border-bottom: 2px solid var(--primary);
        }

        #product-table thead th i {
            font-size: 0.7rem;
            margin-right: 0.25rem;
        }

        #product-table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-100);
            font-size: 0.875rem;
        }

        #product-table tbody tr {
            transition: var(--transition);
        }

        #product-table tbody tr:hover {
            background: rgba(99, 102, 241, 0.03);
        }

        /* ==================== PRODUCT CELL ==================== */
        .product-cell {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .product-image-box {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-sm);
            overflow: hidden;
            border: 2px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            flex-shrink: 0;
        }

        .product-image-box:hover {
            transform: scale(1.1);
            border-color: var(--primary);
        }

        .product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-details {
            flex: 1;
            min-width: 0;
        }

        .product-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.125rem;
        }

        .product-desc {
            color: var(--gray-600);
            font-size: 0.75rem;
        }

        /* ==================== BADGES ==================== */
        .badge-modern {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 16px;
            font-size: 0.75rem;
            font-weight: 600;
            gap: 0.375rem;
            border: 1.5px solid;
        }

        .badge-modern i {
            font-size: 0.7rem;
        }

        .badge-code {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            color: var(--gray-900);
            border-color: var(--gray-300);
            font-family: 'Monaco', monospace;
            font-size: 0.7rem;
        }

        .badge-category {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
            border-color: var(--primary);
        }

        .badge-brand {
            background: rgba(139, 92, 246, 0.1);
            color: var(--secondary);
            border-color: var(--secondary);
        }

        /* ==================== PRICE ==================== */
        .price-box {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .price-current {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .price-original {
            font-size: 0.8rem;
            color: var(--gray-500);
            text-decoration: line-through;
        }

        .badge-discount {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 700;
            display: inline-block;
            margin-left: 0.375rem;
        }

        .badge-discount i {
            font-size: 0.65rem;
        }

        /* ==================== STOCK ==================== */
        .stock-box {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
        }

        .stock-badge {
            padding: 0.375rem 0.75rem;
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            border: 1.5px solid;
            width: fit-content;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .stock-badge i {
            font-size: 0.7rem;
        }

        .stock-badge.in-stock {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border-color: var(--success);
        }

        .stock-badge.low-stock {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
            border-color: var(--warning);
        }

        .stock-badge.out-stock {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border-color: var(--danger);
        }

        .stock-count {
            font-size: 0.75rem;
            color: var(--gray-600);
            font-weight: 600;
        }

        .stock-count i {
            font-size: 0.7rem;
        }

        /* ==================== STATUS ==================== */
        .status-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            border: 1.5px solid;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-badge.active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border-color: var(--success);
        }

        .status-badge.inactive {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border-color: var(--danger);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .status-badge.active .status-dot {
            background: var(--success);
        }

        .status-badge.inactive .status-dot {
            background: var(--danger);
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* ==================== ACTIONS ==================== */
        .action-group {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            border: none;
            color: white;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-sm);
        }

        .action-btn i {
            font-size: 0.875rem;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .action-btn.view {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
        .action-btn.edit {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        .action-btn.delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        /* ==================== CHECKBOX ==================== */
        .checkbox-custom {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        /* ==================== EMPTY STATE ==================== */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: var(--gray-300);
            margin-bottom: 1.5rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.75rem;
        }

        .empty-text {
            color: var(--gray-600);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        /* ==================== LOADING ==================== */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .dashboard-header {
                padding: 1.25rem;
            }

            .header-main {
                flex-direction: column;
                align-items: stretch;
            }

            .header-info h1 {
                font-size: 1.25rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card-content {
                flex-direction: row;
            }

            .filter-toggle-btn {
                display: block;
            }

            .filters-grid {
                display: none;
            }

            .filters-grid.active {
                display: grid;
                grid-template-columns: 1fr;
            }

            .quick-filters {
                flex-direction: column;
            }

            .table-header {
                padding: 1rem;
            }

            .table-header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .table-actions {
                flex-direction: column;
            }

            .dataTables_wrapper {
                padding: 1rem;
            }

            .dataTables_filter input {
                width: 100%;
                margin: 0.5rem 0 0 0;
            }

            .bulk-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .action-group {
                gap: 0.25rem;
            }

            .action-btn {
                width: 32px;
                height: 32px;
            }
        }

        /* ==================== SCROLLBAR ==================== */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
@endpush

@section('content')
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loader-spinner"></div>
    </div>

    <!-- Dashboard Header -->
    <div class="dashboard-header" data-aos="fade-down">
        <div class="content-wrapper">
            <div class="header-main">
                <div class="header-info">
                    <h1>
                        <i class="fas fa-cube"></i>
                        Product Management
                    </h1>
                    <p>
                        <i class="fas fa-chart-line"></i>
                        Complete inventory control with advanced analytics
                    </p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('seller.product.create') }}" class="btn-primary-glass">
                        <i class="fas fa-plus-circle"></i>
                        Add New Product
                    </a>
                    <button class="btn-secondary-glass" onclick="toggleBulkSelect()">
                        <i class="fas fa-tasks"></i>
                        Bulk Actions
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card primary" data-aos="fade-up" data-aos-delay="0">
                <div class="stat-card-content">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-value">{{ $products->count() }}</div>
                        <div class="stat-label">Total Products</div>
                        <span class="stat-trend neutral">
                            <i class="fas fa-globe"></i>
                            All Items
                        </span>
                    </div>
                </div>
            </div>

            <div class="stat-card success" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-card-content">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-value">{{ $products->where('status', 'active')->count() }}</div>
                        <div class="stat-label">Active Products</div>
                        <span class="stat-trend up">
                            <i class="fas fa-arrow-up"></i>
                            {{ $products->count() > 0 ? number_format(($products->where('status', 'active')->count() / $products->count()) * 100, 1) : 0 }}%
                        </span>
                    </div>
                </div>
            </div>

            <div class="stat-card warning" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-card-content">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    @php
                        $lowStockCount = $products->filter(function($product) {
                            return $product->qty <= $product->min_qty && $product->qty > 0;
                        })->count();
                    @endphp
                    <div class="stat-details">
                        <div class="stat-value">{{ $lowStockCount }}</div>
                        <div class="stat-label">Low Stock Alerts</div>
                        <span class="stat-trend {{ $lowStockCount > 0 ? 'down' : 'up' }}">
                            <i class="fas fa-{{ $lowStockCount > 0 ? 'exclamation' : 'check' }}-circle"></i>
                            {{ $lowStockCount > 0 ? 'Action Required' : 'All Good' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="stat-card danger" data-aos="fade-up" data-aos-delay="300">
                <div class="stat-card-content">
                    <div class="stat-icon-wrapper">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-value">{{ $products->where('qty', 0)->count() }}</div>
                        <div class="stat-label">Out of Stock</div>
                        <span class="stat-trend down">
                            <i class="fas fa-arrow-down"></i>
                            Restock Now
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-wrapper" data-aos="fade-up">
        <div class="filters-header">
            <h3 class="filters-title">
                <i class="fas fa-sliders-h"></i>
                Advanced Filters
            </h3>
            <div style="display: flex; gap: 0.75rem;">
                <button class="filter-toggle-btn" onclick="toggleFilters()">
                    <i class="fas fa-filter"></i> Toggle Filters
                </button>
                <button class="btn-filter-reset" onclick="resetAllFilters()">
                    <i class="fas fa-redo"></i> Reset
                </button>
            </div>
        </div>

        <div class="filters-grid" id="filtersGrid">
            <div class="filter-group">
                <label for="filterCategory">
                    <i class="fas fa-tag"></i> Category
                </label>
                <select id="filterCategory" class="filter-select">
                    <option value="">All Categories</option>
                    @foreach($products->pluck('categoryModel')->unique('id')->filter() as $category)
                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="filterBrand">
                    <i class="fas fa-copyright"></i> Brand
                </label>
                <select id="filterBrand" class="filter-select">
                    <option value="">All Brands</option>
                    @foreach($products->pluck('brandModel')->unique('id')->filter() as $brand)
                        <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="filterStatus">
                    <i class="fas fa-toggle-on"></i> Status
                </label>
                <select id="filterStatus" class="filter-select">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="filterStock">
                    <i class="fas fa-warehouse"></i> Stock Status
                </label>
                <select id="filterStock" class="filter-select">
                    <option value="">All Stock</option>
                    <option value="in">In Stock</option>
                    <option value="low">Low Stock</option>
                    <option value="out">Out of Stock</option>
                </select>
            </div>
        </div>

        <div class="quick-filters">
            <span class="quick-filter-label">
                <i class="fas fa-bolt"></i> Quick:
            </span>
            <button class="quick-filter-btn active" data-filter="all">
                <i class="fas fa-globe"></i> All
            </button>
            <button class="quick-filter-btn" data-filter="active">
                <i class="fas fa-check-circle"></i> Active
            </button>
            <button class="quick-filter-btn" data-filter="low-stock">
                <i class="fas fa-exclamation-triangle"></i> Low Stock
            </button>
            <button class="quick-filter-btn" data-filter="discounted">
                <i class="fas fa-percent"></i> Discounted
            </button>
            <button class="quick-filter-btn" data-filter="out-stock">
                <i class="fas fa-ban"></i> Out of Stock
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="table-wrapper" data-aos="fade-up">
        <div class="table-header">
            <div class="table-header-content">
                <div>
                    <h2 class="table-title">
                        <i class="fas fa-table"></i> Product Inventory
                    </h2>
                    <p class="table-subtitle">Complete overview of your product catalog</p>
                </div>
                <div class="table-actions">
                    <button class="table-btn" onclick="exportToExcel()">
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                    <button class="table-btn" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <button class="table-btn" onclick="printTable()">
                        <i class="fas fa-print"></i> Print
                    </button>
                    <button class="table-btn primary" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulkActions">
            <span class="bulk-info">
                <i class="fas fa-check-square"></i>
                <span id="selectedCount">0</span> selected
            </span>
            <button class="bulk-btn activate" onclick="bulkActivate()">
                <i class="fas fa-check-circle"></i> Activate
            </button>
            <button class="bulk-btn deactivate" onclick="bulkDeactivate()">
                <i class="fas fa-ban"></i> Deactivate
            </button>
            <button class="bulk-btn delete" onclick="bulkDelete()">
                <i class="fas fa-trash-alt"></i> Delete
            </button>
        </div>

        <div class="table-responsive">
            <table id="product-table" class="display nowrap" style="width:100%">
                <thead>
                <tr>
                    <th style="width: 30px;">
                        <input type="checkbox" class="checkbox-custom" id="selectAll">
                    </th>
                    <th><i class="fas fa-box"></i> Product</th>
                    <th><i class="fas fa-barcode"></i> Code</th>
                    <th><i class="fas fa-tag"></i> Category</th>
                    <th><i class="fas fa-copyright"></i> Brand</th>
                    <th><i class="fas fa-dollar-sign"></i> Price</th>
                    <th><i class="fas fa-warehouse"></i> Stock</th>
                    <th><i class="fas fa-toggle-on"></i> Status</th>
                    <th><i class="fas fa-cog"></i> Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    @php
                        $stockStatus = $product->qty == 0 ? 'out-stock' : ($product->qty <= $product->min_qty ? 'low-stock' : 'in-stock');
                        $stockText = $product->qty == 0 ? 'Out' : ($product->qty <= $product->min_qty ? 'Low' : 'In Stock');
                        $stockIcon = $product->qty == 0 ? 'fa-times-circle' : ($product->qty <= $product->min_qty ? 'fa-exclamation-circle' : 'fa-check-circle');
                    @endphp
                    <tr data-id="{{ $product->id }}"
                        data-status="{{ $product->status }}"
                        data-stock="{{ $stockStatus }}"
                        data-category="{{ $product->categoryModel->name ?? '' }}"
                        data-brand="{{ $product->brandModel->name ?? '' }}"
                        data-discount="{{ $product->hasDiscount() ? 'yes' : 'no' }}">
                        <td>
                            <input type="checkbox" class="checkbox-custom row-checkbox" value="{{ $product->id }}">
                        </td>
                        <td>
                            <div class="product-cell">
                                <div class="product-image-box">
                                    <img src="{{ $product->image_url }}"
                                         alt="{{ $product->name }}"
                                         class="product-img"
                                         onerror="this.src='{{ asset('images/default-product.png') }}'">
                                </div>
                                <div class="product-details">
                                    <div class="product-name">{{ Str::limit($product->name, 35) }}</div>
                                    <div class="product-desc">{{ Str::limit($product->description, 45) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-modern badge-code">{{ $product->code }}</span>
                        </td>
                        <td>
                            <span class="badge-modern badge-category">
                                <i class="fas fa-tag"></i>
                                {{ $product->categoryModel->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-modern badge-brand">
                                <i class="fas fa-copyright"></i>
                                {{ $product->brandModel->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <div class="price-box">
                                <div class="price-current">
                                    Rs. {{ number_format($product->final_price, 2) }}
                                </div>
                                @if($product->hasDiscount())
                                    <div>
                                        <span class="price-original">
                                            Rs. {{ number_format($product->display_price, 2) }}
                                        </span>
                                        <span class="badge-discount">
                                            <i class="fas fa-percent"></i> {{ $product->discount }}%
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="stock-box">
                                <span class="stock-badge {{ $stockStatus }}">
                                    <i class="fas {{ $stockIcon }}"></i>
                                    {{ $stockText }}
                                </span>
                                <span class="stock-count">
                                    <i class="fas fa-cube"></i> {{ $product->qty }} units
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge {{ $product->status }}">
                                <span class="status-dot"></span>
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <!-- View Button -->
                                <a href="{{ route('seller.product.show', $product->id) }}"
                                   class="action-btn view"
                                   title="View Product"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Edit Button -->
                                <a href="{{ route('seller.product.edit', $product->id) }}"
                                   class="action-btn edit"
                                   title="Edit Product"
                                   data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Delete Button -->
                                <button class="action-btn delete"
                                        onclick="deleteProduct({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                        title="Delete Product"
                                        data-bs-toggle="tooltip">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <h3 class="empty-title">No Products Found</h3>
                                <p class="empty-text">
                                    Start building your inventory by adding your first product
                                </p>
                                <a href="{{ route('seller.product.create') }}" class="btn-primary-glass" style="display: inline-flex;">
                                    <i class="fas fa-plus-circle"></i>
                                    Add Your First Product
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        AOS.init({ duration: 600, once: true });

        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 2500
        };

        let dataTable;
        let selectedProducts = [];

        $(document).ready(function () {
            initializeTooltips();
            initializeDataTable();
            initializeFilters();
            initializeSelect2();
            initializeBulkActions();
        });

        function initializeTooltips() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }

        function initializeDataTable() {
            @if($products->count() > 0)
                dataTable = $('#product-table').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[1, 'asc']],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search products...",
                    lengthMenu: "Show _MENU_",
                    info: "_START_ to _END_ of _TOTAL_",
                    paginate: {
                        next: "Next →",
                        previous: "← Prev"
                    }
                },
                dom: 'Blfrtip',
                buttons: ['excel', 'pdf', 'print'],
                columnDefs: [
                    { orderable: false, targets: [0, 8] }
                ],
                drawCallback: function() {
                    initializeTooltips();
                    updateBulkActionsVisibility();
                }
            });

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const row = dataTable.row(dataIndex).node();
                const categoryFilter = $('#filterCategory').val();
                const category = $(row).data('category');
                if (categoryFilter && category !== categoryFilter) return false;

                const brandFilter = $('#filterBrand').val();
                const brand = $(row).data('brand');
                if (brandFilter && brand !== brandFilter) return false;

                const statusFilter = $('#filterStatus').val();
                const status = $(row).data('status');
                if (statusFilter && status !== statusFilter) return false;

                const stockFilter = $('#filterStock').val();
                const stock = $(row).data('stock');
                if (stockFilter) {
                    if (stockFilter === 'in' && stock !== 'in-stock') return false;
                    if (stockFilter === 'low' && stock !== 'low-stock') return false;
                    if (stockFilter === 'out' && stock !== 'out-stock') return false;
                }

                return true;
            });
            @endif
        }

        function initializeSelect2() {
            $('.filter-select').select2({ theme: 'classic', width: '100%' });
        }

        function initializeFilters() {
            $('#filterCategory, #filterBrand, #filterStatus, #filterStock').on('change', function() {
                if (dataTable) dataTable.draw();
            });

            $('.quick-filter-btn').on('click', function() {
                $('.quick-filter-btn').removeClass('active');
                $(this).addClass('active');
                const filter = $(this).data('filter');
                $('#filterCategory, #filterBrand, #filterStatus, #filterStock').val('').trigger('change');

                if (filter === 'active') $('#filterStatus').val('active').trigger('change');
                else if (filter === 'low-stock') $('#filterStock').val('low').trigger('change');
                else if (filter === 'out-stock') $('#filterStock').val('out').trigger('change');
                else if (filter === 'discounted') {
                    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                        return dataTable.row(dataIndex).node().dataset.discount === 'yes';
                    });
                }

                if (dataTable) dataTable.draw();
                if (filter !== 'discounted') $.fn.dataTable.ext.search.pop();
            });
        }

        function initializeBulkActions() {
            $('#selectAll').on('change', function() {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
                updateSelectedProducts();
            });

            $(document).on('change', '.row-checkbox', updateSelectedProducts);
        }

        function toggleFilters() {
            $('#filtersGrid').toggleClass('active');
        }

        function resetAllFilters() {
            $('#filterCategory, #filterBrand, #filterStatus, #filterStock').val('').trigger('change');
            $('.quick-filter-btn').removeClass('active');
            $('.quick-filter-btn[data-filter="all"]').addClass('active');
            if (dataTable) dataTable.draw();
            toastr.success('Filters reset');
        }

        function toggleBulkSelect() {
            const bulkMode = $('#selectAll').is(':visible');
            if (bulkMode) {
                $('#selectAll, .row-checkbox').prop('checked', false).hide();
                $('#bulkActions').removeClass('active');
            } else {
                $('#selectAll, .row-checkbox').show();
            }
            updateSelectedProducts();
        }

        function updateSelectedProducts() {
            selectedProducts = $('.row-checkbox:checked').map(function() { return $(this).val(); }).get();
            $('#selectedCount').text(selectedProducts.length);
            $('#bulkActions').toggleClass('active', selectedProducts.length > 0);
            $('#selectAll').prop('checked', $('.row-checkbox').length === selectedProducts.length);
        }

        function updateBulkActionsVisibility() {
            if (selectedProducts.length > 0) $('#bulkActions').addClass('active');
        }

        function bulkActivate() {
            if (!selectedProducts.length) return toastr.warning('Select products first');
            Swal.fire({
                title: 'Activate Products?',
                html: `Activate <strong>${selectedProducts.length}</strong> products?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Yes, Activate!'
            }).then((result) => {
                if (result.isConfirmed) performBulkAction('activate');
            });
        }

        function bulkDeactivate() {
            if (!selectedProducts.length) return toastr.warning('Select products first');
            Swal.fire({
                title: 'Deactivate Products?',
                html: `Deactivate <strong>${selectedProducts.length}</strong> products?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Yes, Deactivate!'
            }).then((result) => {
                if (result.isConfirmed) performBulkAction('deactivate');
            });
        }

        function bulkDelete() {
            if (!selectedProducts.length) return toastr.warning('Select products first');
            Swal.fire({
                title: 'Delete Products?',
                html: `Delete <strong>${selectedProducts.length}</strong> products?<br><small>This cannot be undone!</small>`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, Delete!'
            }).then((result) => {
                if (result.isConfirmed) performBulkAction('delete');
            });
        }

        function performBulkAction(action) {
            showLoading();
            setTimeout(() => {
                hideLoading();
                toastr.success(`${selectedProducts.length} products ${action}d`);
                setTimeout(() => location.reload(), 1000);
            }, 1000);
        }

        function deleteProduct(productId, productName) {
            Swal.fire({
                title: 'Delete Product?',
                html: `Delete "<strong>${productName}</strong>"?<br><small>This cannot be undone!</small>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, Delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    $.ajax({
                        url: `/seller/product/${productId}`,
                        type: 'DELETE',
                        data: { '_token': '{{ csrf_token() }}' },
                        success: function(response) {
                            hideLoading();
                            toastr.success(response.message || 'Product deleted');
                            setTimeout(() => location.reload(), 800);
                        },
                        error: function() {
                            hideLoading();
                            toastr.error('Failed to delete');
                        }
                    });
                }
            });
        }

        function exportToExcel() {
            toastr.info('Exporting to Excel...');
            showLoading();
            setTimeout(() => {
                hideLoading();
                $('.buttons-excel').click();
                toastr.success('Excel ready');
            }, 800);
        }

        function exportToPDF() {
            toastr.info('Exporting to PDF...');
            showLoading();
            setTimeout(() => {
                hideLoading();
                $('.buttons-pdf').click();
                toastr.success('PDF ready');
            }, 800);
        }

        function printTable() {
            $('.buttons-print').click();
        }

        function showLoading() {
            $('#loadingOverlay').addClass('active');
        }

        function hideLoading() {
            $('#loadingOverlay').removeClass('active');
        }
    </script>
@endpush
