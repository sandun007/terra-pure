@extends('seller.layout.app')
@push('title')
    Category Management
@endpush
@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-light: #e0e7ff;
            --secondary-color: #06b6d4;
            --secondary-light: #cffafe;
            --success-color: #10b981;
            --success-light: #d1fae5;
            --warning-color: #f59e0b;
            --warning-light: #fef3c7;
            --danger-color: #ef4444;
            --danger-light: #fee2e2;
            --info-color: #3b82f6;
            --info-light: #dbeafe;
            --light-color: #ffffff;
            --light-gray: #f8fafc;
            --gray-color: #94a3b8;
            --dark-gray: #64748b;
            --border-color: #e2e8f0;
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.08);
            --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.1);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --radius-full: 9999px;
            --transition-fast: 0.2s ease;
            --transition-normal: 0.3s ease;
            --transition-slow: 0.4s ease;
        }

        /* Light Theme Only */
        body {
            background-color: #f9fafb;
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* Page Header - Light Gradient */
        .page-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #6366f1 100%);
            color: white;
            border-radius: var(--radius-lg);
            padding: 2.5rem 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-lg);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .page-header-content {
            position: relative;
            z-index: 2;
        }

        .page-header h3 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.95;
            margin-bottom: 0;
        }

        .page-actions .btn {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: var(--radius-md);
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            color: white;
            transition: var(--transition-normal);
            position: relative;
            z-index: 2;
        }

        .page-actions .btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        /* Mobile Header */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.75rem 1.25rem;
                margin-bottom: 1.5rem;
                border-radius: var(--radius-md);
            }

            .page-header h3 {
                font-size: 1.5rem;
            }

            .page-header p {
                font-size: 0.95rem;
            }
        }

        /* Stats Cards - Light Theme */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 576px) {
            .stats-container {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        .stat-card {
            background: var(--light-color);
            border-radius: var(--radius-lg);
            padding: 1.75rem 1.5rem;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: var(--transition-normal);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.6s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-icon {
            width: 64px;
            height: 64px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: white;
            flex-shrink: 0;
            box-shadow: var(--shadow-md);
        }

        .stat-icon.total {
            background: linear-gradient(135deg, var(--primary-color), #6366f1);
        }

        .stat-icon.active {
            background: linear-gradient(135deg, var(--success-color), #34d399);
        }

        .stat-icon.inactive {
            background: linear-gradient(135deg, var(--danger-color), #f87171);
        }

        .stat-icon.products {
            background: linear-gradient(135deg, var(--warning-color), #fbbf24);
        }

        .stat-content {
            flex: 1;
            min-width: 0;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 0.375rem;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* View Toggle - Light */
        .view-toggle {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 2rem;
            background: var(--light-color);
            padding: 1rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        @media (max-width: 576px) {
            .view-toggle {
                gap: 0.5rem;
                padding: 0.75rem;
                flex-wrap: wrap;
            }
        }

        .view-btn {
            flex: 1;
            min-width: 130px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            background: var(--light-color);
            color: var(--text-secondary);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition-normal);
            font-size: 0.95rem;
        }

        .view-btn:hover:not(.active) {
            background: var(--light-gray);
            border-color: var(--primary-light);
            color: var(--primary-color);
        }

        .view-btn.active {
            background: linear-gradient(135deg, var(--primary-color), #6366f1);
            color: white;
            border-color: transparent;
            box-shadow: var(--shadow-md);
        }

        .view-btn i {
            font-size: 1.25rem;
        }

        /* Grid View - Light Cards */
        #gridView {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.75rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            #gridView {
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
                gap: 1.25rem;
            }
        }

        @media (max-width: 576px) {
            #gridView {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        .category-card {
            background: var(--light-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            transition: var(--transition-normal);
            overflow: hidden;
            height: 100%;
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .category-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .category-image-container {
            height: 180px;
            background: linear-gradient(135deg, var(--light-gray) 0%, #f1f5f9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            border-bottom: 1px solid var(--border-color);
        }

        .category-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 2rem;
            transition: var(--transition-slow);
        }

        .category-card:hover .category-image {
            transform: scale(1.08);
        }

        .no-image {
            font-size: 3.5rem;
            color: #cbd5e1;
            opacity: 0.7;
        }

        .card-body {
            padding: 1.75rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
            gap: 0.75rem;
        }

        .category-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            flex: 1;
            line-height: 1.4;
        }

        .category-code {
            background: var(--primary-light);
            color: var(--primary-color);
            padding: 0.375rem 1rem;
            border-radius: var(--radius-full);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .category-description {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .category-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }

        .status-badge {
            padding: 0.5rem 1.25rem;
            border-radius: var(--radius-full);
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            box-shadow: var(--shadow-sm);
        }

        .status-active {
            background: var(--success-light);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-inactive {
            background: var(--danger-light);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .product-count-badge {
            background: var(--info-light);
            color: var(--info-color);
            padding: 0.5rem 1.25rem;
            border-radius: var(--radius-full);
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid rgba(59, 130, 246, 0.2);
            white-space: nowrap;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
            margin-top: auto;
        }

        .btn-action {
            flex: 1;
            padding: 0.875rem 1rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: var(--transition-normal);
            border: 1px solid transparent;
            cursor: pointer;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
        }

        .btn-action::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .btn-action:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }

        .btn-view {
            background: var(--light-color);
            color: var(--info-color);
            border-color: var(--border-color);
        }

        .btn-view:hover {
            background: var(--info-color);
            color: white;
            border-color: var(--info-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .btn-edit {
            background: var(--light-color);
            color: var(--warning-color);
            border-color: var(--border-color);
        }

        .btn-edit:hover {
            background: var(--warning-color);
            color: white;
            border-color: var(--warning-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        }

        .btn-delete {
            background: var(--light-color);
            color: var(--danger-color);
            border-color: var(--border-color);
        }

        .btn-delete:hover {
            background: var(--danger-color);
            color: white;
            border-color: var(--danger-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        /* Table View - Light Theme */
        #tableView {
            display: none;
            margin-bottom: 2rem;
        }

        .card {
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            background: var(--light-color);
            border: 1px solid var(--border-color);
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #categories-table_wrapper {
            padding: 0;
        }

        #categories-table_filter {
            position: relative;
            margin-bottom: 1.25rem;
            padding: 0 1.5rem;
        }

        #categories-table_filter input {
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 0.875rem 1rem;
            padding-left: 3rem;
            width: 100%;
            max-width: 400px;
            transition: var(--transition-normal);
            background: var(--light-color);
            font-size: 0.95rem;
            color: var(--text-primary);
        }

        #categories-table_filter input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        #categories-table_filter:before {
            content: "search";
            font-family: 'Material Icons Outlined';
            position: absolute;
            left: 1.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
            z-index: 10;
            font-size: 1.5rem;
        }

        table.dataTable {
            width: 100% !important;
            margin: 0 !important;
            border-collapse: separate;
            border-spacing: 0;
        }

        table.dataTable thead th {
            background: var(--light-gray);
            color: var(--text-primary);
            font-weight: 700;
            font-size: 0.9rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table.dataTable tbody tr {
            background: var(--light-color);
            transition: var(--transition-fast);
        }

        table.dataTable tbody tr:hover {
            background: var(--light-gray);
        }

        table.dataTable tbody td {
            padding: 1.25rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        .table-category-image {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: var(--radius-md);
            background: var(--light-gray);
            padding: 0.5rem;
            border: 1px solid var(--border-color);
        }

        .table-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .table-actions .btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            transition: var(--transition-normal);
        }

        .table-actions .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .table-actions .btn-outline-primary:hover {
            background: var(--info-color);
            border-color: var(--info-color);
        }

        .table-actions .btn-outline-warning:hover {
            background: var(--warning-color);
            border-color: var(--warning-color);
        }

        .table-actions .btn-outline-danger:hover {
            background: var(--danger-color);
            border-color: var(--danger-color);
        }

        /* Empty State - Light */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--light-color);
            border-radius: var(--radius-lg);
            border: 2px dashed var(--border-color);
            margin: 2rem 0;
            box-shadow: var(--shadow-sm);
        }

        .empty-state-icon {
            font-size: 4rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 1.5rem;
            display: inline-block;
        }

        .empty-state h4 {
            color: var(--text-primary);
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            font-weight: 700;
        }

        .empty-state p {
            color: var(--text-secondary);
            margin-bottom: 2rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .empty-state .btn {
            padding: 0.875rem 2rem;
            font-weight: 600;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, var(--primary-color), #6366f1);
            border: none;
            color: white;
            transition: var(--transition-normal);
        }

        .empty-state .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Alerts - Light */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
            border-left: 4px solid transparent;
            background: var(--light-color);
        }

        .alert-success {
            background: var(--success-light);
            color: var(--success-color);
            border-left-color: var(--success-color);
        }

        .alert-danger {
            background: var(--danger-light);
            color: var(--danger-color);
            border-left-color: var(--danger-color);
        }

        .alert .btn-close {
            padding: 1rem;
        }

        /* Loading Overlay - Light */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(4px);
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 3px solid var(--light-gray);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* FAB Button - Light */
        .fab {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
            display: none;
        }

        @media (max-width: 768px) {
            .fab {
                display: block;
            }
        }

        .fab-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), #6366f1);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: var(--shadow-xl);
            cursor: pointer;
            transition: var(--transition-normal);
            position: relative;
            overflow: hidden;
        }

        .fab-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .fab-btn:hover::before {
            opacity: 1;
        }

        .fab-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 30px rgba(79, 70, 229, 0.3);
        }

        /* Mobile Menu - Light */
        .mobile-actions-menu {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 1000;
            display: none;
        }

        @media (max-width: 768px) {
            .mobile-actions-menu {
                display: block;
            }
        }

        .mobile-actions-btn {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), #6366f1);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: var(--shadow-lg);
            cursor: pointer;
            transition: var(--transition-normal);
        }

        .mobile-actions-btn:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-xl);
        }

        /* Pull to Refresh - Light */
        .pull-to-refresh {
            text-align: center;
            padding: 1rem;
            color: var(--text-secondary);
            background: var(--light-color);
            border-bottom: 1px solid var(--border-color);
            display: none;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .pull-to-refresh {
                display: block;
            }
        }

        /* Touch-friendly Improvements */
        @media (hover: none) and (pointer: coarse) {
            .btn-action,
            .view-btn,
            .table-actions .btn {
                min-height: 44px;
                min-width: 44px;
            }

            .category-card:active {
                transform: scale(0.98);
                box-shadow: var(--shadow-md);
            }

            input,
            select,
            textarea,
            button {
                font-size: 16px !important;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-gray);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* Animation Classes */
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-in {
            animation: slideIn 0.4s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Badge Animation */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        /* Focus Styles */
        *:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        *:focus:not(.focus-visible) {
            outline: none;
        }

        /* Print Styles */
        @media print {
            .page-actions,
            .view-toggle,
            .table-actions,
            .action-buttons,
            .fab,
            .mobile-actions-menu,
            .pull-to-refresh {
                display: none !important;
            }

            .page-header {
                background: white !important;
                color: var(--text-primary) !important;
                box-shadow: none !important;
                border: 1px solid var(--border-color) !important;
            }

            .stat-card,
            .category-card,
            .card {
                box-shadow: none !important;
                border: 1px solid var(--border-color) !important;
                break-inside: avoid;
            }

            body {
                background: white !important;
                color: black !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-header fade-in">
        <div class="page-header-content">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div class="flex-grow-1">
                    <h3 class="d-flex align-items-center gap-2">
                        <i class="fas fa-boxes"></i>
                        <span>Category Management</span>
                    </h3>
                    <p class="d-flex align-items-center gap-2 mb-0">
                        <i class="fas fa-layer-group"></i>
                        <span>Organize products with {{ $count }} categories</span>
                    </p>
                </div>
                <div class="page-actions">
                    <a href="{{ route('seller.category.create') }}"
                       class="btn d-flex align-items-center gap-2">
                        <i class="fas fa-plus-circle"></i>
                        <span class="d-none d-md-inline">New Category</span>
                        <span class="d-md-none">Add</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay" style="display: none;">
        <div class="spinner"></div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-container fade-in">
        @php
            $activeCount = $categories->where('status', 'active')->count();
            $inactiveCount = $categories->where('status', 'inactive')->count();
            $totalProducts = 0;
            foreach($categories as $category) {
                $totalProducts += \App\Models\SellerProduct::where('category_id', $category->id)
                    ->where('seller_id', Auth::guard('seller')->id())
                    ->count();
            }
        @endphp

        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $count }}</div>
                <div class="stat-label">Total Categories</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon active">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $activeCount }}</div>
                <div class="stat-label">Active Categories</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon inactive">
                <i class="fas fa-pause-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $inactiveCount }}</div>
                <div class="stat-label">Inactive Categories</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon products">
                <i class="fas fa-cube"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalProducts }}</div>
                <div class="stat-label">Total Products</div>
            </div>
        </div>
    </div>

    <!-- View Toggle -->
    <div class="view-toggle fade-in">
        <button class="view-btn active" id="gridViewBtn" aria-label="Switch to grid view">
            <i class="fas fa-th-large"></i>
            <span>Grid View</span>
        </button>
        <button class="view-btn" id="tableViewBtn" aria-label="Switch to table view">
            <i class="fas fa-table"></i>
            <span>Table View</span>
        </button>
        <button class="view-btn d-md-none" id="filterBtn" aria-label="Filter categories">
            <i class="fas fa-filter"></i>
            <span>Filter</span>
        </button>
    </div>

    <!-- Pull to Refresh Indicator -->
    <div class="pull-to-refresh" id="pullToRefresh">
        <i class="fas fa-arrow-down me-2"></i> Pull down to refresh
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($categories->isEmpty())
        <div class="empty-state fade-in">
            <i class="fas fa-boxes empty-state-icon"></i>
            <h4>No Categories Found</h4>
            <p>Start organizing your products by creating your first category. Categories help you manage your products efficiently.</p>
            <a href="{{ route('seller.category.create') }}"
               class="btn d-inline-flex align-items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                Create First Category
            </a>
        </div>
    @else
        <!-- Grid View -->
        <div id="gridView" class="fade-in">
            @foreach($categories as $category)
                <div class="category-card" data-category-id="{{ $category->id }}"
                     data-category-status="{{ $category->status }}">
                    <div class="category-image-container">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}"
                                 alt="{{ $category->name }}"
                                 class="category-image"
                                 loading="lazy"
                                 onerror="this.src='{{ asset('images/default-category.png') }}'; this.onerror=null;">
                        @else
                            <i class="fas fa-boxes no-image"></i>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="category-header">
                            <h5 class="category-title" title="{{ $category->name }}">
                                {{ $category->name }}
                            </h5>
                            <span class="category-code">{{ $category->code }}</span>
                        </div>

                        @if($category->description)
                            <p class="category-description" title="{{ $category->description }}">
                                {{ Str::limit($category->description, 100) }}
                            </p>
                        @endif

                        <div class="category-footer">
                            <span class="product-count-badge">
                                <i class="fas fa-cube"></i>
                                {{ \App\Models\SellerProduct::where('category_id', $category->id)->where('seller_id', Auth::guard('seller')->id())->count() }}
                            </span>
                            <span class="status-badge {{ $category->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                <i class="fas {{ $category->status == 'active' ? 'fa-check-circle' : 'fa-pause-circle' }}"></i>
                                {{ ucfirst($category->status) }}
                            </span>
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('seller.category.show', $category->id) }}"
                               class="btn-action btn-view"
                               aria-label="View {{ $category->name }}"
                               title="View Details">
                                <i class="fas fa-eye"></i>
                                <span class="d-none d-md-inline">View</span>
                            </a>
                            <a href="{{ route('seller.category.edit', $category->id) }}"
                               class="btn-action btn-edit"
                               aria-label="Edit {{ $category->name }}"
                               title="Edit Category">
                                <i class="fas fa-edit"></i>
                                <span class="d-none d-md-inline">Edit</span>
                            </a>
                            <button type="button"
                                    class="btn-action btn-delete delete-category"
                                    data-id="{{ $category->id }}"
                                    data-name="{{ $category->name }}"
                                    aria-label="Delete {{ $category->name }}"
                                    title="Delete Category">
                                <i class="fas fa-trash"></i>
                                <span class="d-none d-md-inline">Delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Table View -->
        <div id="tableView" class="fade-in">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="categories-table" class="display table table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th width="80">Image</th>
                                <th>Category Name</th>
                                <th width="100">Code</th>
                                <th width="100">Products</th>
                                <th width="100">Status</th>
                                <th width="120">Created</th>
                                <th width="140" class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
                                <tr data-category-id="{{ $category->id }}" data-category-status="{{ $category->status }}">
                                    <td>
                                        @if($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}"
                                                 alt="{{ $category->name }}"
                                                 class="table-category-image"
                                                 loading="lazy"
                                                 onerror="this.src='{{ asset('images/default-category.png') }}'; this.onerror=null;">
                                        @else
                                            <div class="table-category-image d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-boxes text-gray"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <strong class="mb-1">{{ $category->name }}</strong>
                                            @if($category->description)
                                                <small class="text-muted text-truncate" style="max-width: 200px;">
                                                    {{ Str::limit($category->description, 40) }}
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <code class="bg-light px-2 py-1 rounded">{{ $category->code }}</code>
                                    </td>
                                    <td>
                                        <span class="product-count-badge">
                                            {{ \App\Models\SellerProduct::where('category_id', $category->id)->where('seller_id', Auth::guard('seller')->id())->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $category->status == 'active' ? 'status-active' : 'status-inactive' }}">
                                            {{ ucfirst($category->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>{{ $category->created_at->format('M d, Y') }}</span>
                                            <small class="text-muted">{{ $category->created_at->format('h:i A') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="{{ route('seller.category.show', $category->id) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               aria-label="View {{ $category->name }}"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('seller.category.edit', $category->id) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               aria-label="Edit {{ $category->name }}"
                                               title="Edit Category">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-category"
                                                    data-id="{{ $category->id }}"
                                                    data-name="{{ $category->name }}"
                                                    aria-label="Delete {{ $category->name }}"
                                                    title="Delete Category">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Mobile Floating Action Button -->
    <div class="fab">
        <button class="fab-btn" id="fabBtn" aria-label="Quick actions">
            <i class="fas fa-plus"></i>
        </button>
    </div>

    <!-- Mobile Actions Menu -->
    <div class="mobile-actions-menu" id="mobileActionsMenu" style="display: none;">
        <button class="mobile-actions-btn" id="mobileMenuBtn" aria-label="Open actions menu">
            <i class="fas fa-ellipsis-v"></i>
        </button>
    </div>

    <!-- CSRF Token Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    <script>
        $(document).ready(function () {
            // Initialize variables
            let dataTable = null;
            let isMobile = window.innerWidth <= 768;
            let touchStartY = 0;
            let isRefreshing = false;

            // View toggle functionality
            $('#gridViewBtn').on('click', function() {
                $(this).addClass('active');
                $('#tableViewBtn').removeClass('active');
                $('#gridView').fadeIn(300);
                $('#tableView').hide();
                localStorage.setItem('categoryView', 'grid');

                // Update mobile menu
                updateMobileMenu();
            });

            $('#tableViewBtn').on('click', function() {
                $(this).addClass('active');
                $('#gridViewBtn').removeClass('active');
                $('#tableView').fadeIn(300);
                $('#gridView').hide();
                localStorage.setItem('categoryView', 'table');

                // Initialize DataTable if not already initialized
                if (dataTable === null) {
                    initializeDataTable();
                }

                // Update mobile menu
                updateMobileMenu();
            });

            // Load saved view preference
            const savedView = localStorage.getItem('categoryView') || 'grid';
            if (savedView === 'table') {
                $('#tableViewBtn').click();
            } else {
                $('#gridViewBtn').click();
            }

            // Initialize DataTable
            function initializeDataTable() {
                dataTable = $('#categories-table').DataTable({
                    responsive: true,
                    language: {
                        search: "",
                        searchPlaceholder: "Search categories...",
                        lengthMenu: "Show _MENU_ categories",
                        info: "Showing _START_ to _END_ of _TOTAL_ categories",
                        infoEmpty: "No categories available",
                        zeroRecords: "No matching categories found",
                        infoFiltered: "(filtered from _MAX_ total categories)",
                        paginate: {
                            first: '<i class="fas fa-angle-double-left"></i>',
                            previous: '<i class="fas fa-angle-left"></i>',
                            next: '<i class="fas fa-angle-right"></i>',
                            last: '<i class="fas fa-angle-double-right"></i>'
                        }
                    },
                    columnDefs: [
                        { orderable: false, targets: [0, 6] },
                        { className: "align-middle", targets: "_all" }
                    ],
                    order: [[1, 'asc']],
                    pageLength: 10,
                    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                    dom: '<"row"<"col-md-6"l><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                    initComplete: function() {
                        $('.dataTables_filter input').addClass('form-control');
                        $('.dataTables_length select').addClass('form-select');

                        // Mobile adjustments
                        if (isMobile) {
                            $('.dataTables_length select').addClass('form-select-sm');
                            $('.dataTables_filter input').addClass('form-control-sm');
                        }
                    }
                });
            }

            // Get CSRF token from meta tag
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Enhanced delete confirmation
            $(document).on('click', '.delete-category', function() {
                const categoryId = $(this).data('id');
                const categoryName = $(this).data('name');
                const deleteUrl = "{{ route('seller.category.destroy', ':id') }}".replace(':id', categoryId);
                const $deleteBtn = $(this);
                const isMobileView = window.innerWidth <= 768;

                // Add loading state
                $deleteBtn.prop('disabled', true).addClass('loading');
                const originalContent = $deleteBtn.html();
                $deleteBtn.html('<i class="fas fa-spinner fa-spin"></i>');

                // Show confirmation dialog
                Swal.fire({
                    title: isMobileView ? 'Delete?' : 'Delete Category?',
                    html: isMobileView
                        ? `<div class="text-center p-3">
                              <div class="mb-4">
                                  <i class="fas fa-trash fa-3x text-danger"></i>
                              </div>
                              <h5 class="mb-2 text-dark">${categoryName}</h5>
                              <p class="text-muted mb-0">This action cannot be undone</p>
                           </div>`
                        : `<div class="text-center p-4">
                              <div class="mb-4">
                                  <i class="fas fa-trash fa-4x text-danger"></i>
                              </div>
                              <h4 class="mb-3 text-dark">"${categoryName}"</h4>
                              <p class="text-muted mb-0">This action will permanently delete the category and cannot be undone.</p>
                           </div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: isMobileView ? 'Delete' : 'Yes, Delete It',
                    cancelButtonText: 'Cancel',
                    showLoaderOnConfirm: true,
                    reverseButtons: !isMobileView,
                    customClass: {
                        confirmButton: isMobileView ? 'btn-lg' : '',
                        cancelButton: isMobileView ? 'btn-lg' : '',
                        popup: isMobileView ? 'swal2-popup-mobile' : ''
                    },
                    preConfirm: () => {
                        return $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: csrfToken
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    return response;
                                } else {
                                    throw new Error(response.message || 'Delete failed');
                                }
                            },
                            error: function(xhr) {
                                let errorMessage = 'Request failed';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.statusText) {
                                    errorMessage = xhr.statusText;
                                }
                                throw new Error(errorMessage);
                            }
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Success animation
                        confetti({
                            particleCount: isMobileView ? 50 : 100,
                            spread: 70,
                            origin: { y: 0.6 },
                            colors: ['#4f46e5', '#6366f1', '#818cf8']
                        });

                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Category has been deleted successfully.',
                            icon: 'success',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            background: '#f8fafc',
                            color: '#1e293b',
                            didClose: () => {
                                // Show loading overlay
                                $('#loadingOverlay').fadeIn(200, function() {
                                    location.reload();
                                });
                            }
                        });
                    } else {
                        // Reset button state
                        $deleteBtn.prop('disabled', false).removeClass('loading');
                        $deleteBtn.html(originalContent);
                    }
                });
            });

            // Image error handling
            $('.category-image, .table-category-image').on('error', function() {
                const $this = $(this);
                $this.attr('src', '{{ asset("images/default-category.png") }}');
                $this.on('error', null); // Prevent infinite loop
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                $('.alert').alert('close');
            }, 5000);

            // Mobile menu functionality
            function updateMobileMenu() {
                const isTableView = $('#tableView').is(':visible');
                if (isMobile && isTableView) {
                    $('#mobileActionsMenu').fadeIn(300);
                } else {
                    $('#mobileActionsMenu').hide();
                }
            }

            // FAB button functionality
            $('#fabBtn').on('click', function() {
                Swal.fire({
                    title: 'Quick Actions',
                    showCloseButton: true,
                    showCancelButton: false,
                    showConfirmButton: false,
                    background: '#ffffff',
                    color: '#1e293b',
                    html: `
                        <div class="d-grid gap-3 p-2">
                            <a href="{{ route('seller.category.create') }}" class="btn btn-primary btn-lg py-3 d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-plus-circle fs-4"></i>
                                <span class="fs-5">New Category</span>
                            </a>
                            <button class="btn btn-outline-primary btn-lg py-3 d-flex align-items-center justify-content-center gap-2" id="switchViewBtn">
                                <i class="fas fa-exchange-alt fs-4"></i>
                                <span class="fs-5">Switch View</span>
                            </button>
                            <button class="btn btn-outline-success btn-lg py-3 d-flex align-items-center justify-content-center gap-2" id="refreshBtn">
                                <i class="fas fa-sync-alt fs-4"></i>
                                <span class="fs-5">Refresh</span>
                            </button>
                        </div>
                    `,
                    willOpen: () => {
                        $('#switchViewBtn').on('click', function() {
                            Swal.close();
                            if ($('#gridViewBtn').hasClass('active')) {
                                $('#tableViewBtn').click();
                            } else {
                                $('#gridViewBtn').click();
                            }
                        });

                        $('#refreshBtn').on('click', function() {
                            Swal.close();
                            $('#loadingOverlay').fadeIn(200);
                            setTimeout(() => {
                                location.reload();
                            }, 500);
                        });
                    }
                });
            });

            // Pull to refresh functionality for mobile
            let startY = 0;
            let currentY = 0;
            let pulling = false;

            document.addEventListener('touchstart', function(e) {
                if (window.scrollY === 0) {
                    startY = e.touches[0].pageY;
                    pulling = true;
                }
            }, { passive: true });

            document.addEventListener('touchmove', function(e) {
                if (!pulling) return;

                currentY = e.touches[0].pageY;
                const pullDistance = currentY - startY;

                if (pullDistance > 0) {
                    e.preventDefault();
                    $('#pullToRefresh').show().css({
                        transform: `translateY(${Math.min(pullDistance, 100)}px)`,
                        opacity: Math.min(pullDistance / 100, 1)
                    });
                }
            }, { passive: false });

            document.addEventListener('touchend', function(e) {
                if (!pulling) return;

                const pullDistance = currentY - startY;
                if (pullDistance > 100 && !isRefreshing) {
                    isRefreshing = true;
                    $('#pullToRefresh').html('<i class="fas fa-spinner fa-spin me-2"></i> Refreshing...');

                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    $('#pullToRefresh').hide().css({
                        transform: 'translateY(0)',
                        opacity: 0
                    });
                }

                pulling = false;
                startY = 0;
                currentY = 0;
            });

            // Filter button for mobile
            $('#filterBtn').on('click', function() {
                Swal.fire({
                    title: 'Filter Categories',
                    html: `
                        <div class="text-start">
                            <div class="mb-4">
                                <label class="form-label fw-bold mb-2">Status</label>
                                <select class="form-select form-select-lg" id="filterStatus">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold mb-2">Sort By</label>
                                <select class="form-select form-select-lg" id="filterSort">
                                    <option value="name">Name (A-Z)</option>
                                    <option value="newest">Newest First</option>
                                    <option value="oldest">Oldest First</option>
                                    <option value="products">Most Products</option>
                                </select>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Apply Filters',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#4f46e5',
                    background: '#ffffff',
                    color: '#1e293b',
                    preConfirm: () => {
                        const status = $('#filterStatus').val();
                        const sort = $('#filterSort').val();

                        // Apply filters
                        applyFilters(status, sort);
                    }
                });
            });

            // Filter function
            function applyFilters(status, sort) {
                $('.category-card, #categories-table tbody tr').show();

                if (status) {
                    $('.category-card').each(function() {
                        if ($(this).data('category-status') !== status) {
                            $(this).hide();
                        }
                    });

                    $('#categories-table tbody tr').each(function() {
                        if ($(this).data('category-status') !== status) {
                            $(this).hide();
                        }
                    });
                }
            }

            // Mobile menu button
            $('#mobileMenuBtn').on('click', function() {
                // Show quick actions menu
                $('#fabBtn').click();
            });

            // Keyboard shortcuts
            $(document).keydown(function(e) {
                // Ctrl/Cmd + F to focus search
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    if ($('#tableView').is(':visible')) {
                        $('#categories-table_filter input').focus();
                    }
                }

                // Escape to clear search
                if (e.key === 'Escape') {
                    if ($('#tableView').is(':visible')) {
                        $('#categories-table_filter input').val('').trigger('keyup');
                    }
                }

                // G for grid view, T for table view
                if (e.key === 'g' || e.key === 'G') {
                    e.preventDefault();
                    $('#gridViewBtn').click();
                }
                if (e.key === 't' || e.key === 'T') {
                    e.preventDefault();
                    $('#tableViewBtn').click();
                }
            });

            // Initialize tooltips for desktop
            if (!isMobile) {
                $('[title]').tooltip({
                    trigger: 'hover',
                    placement: 'top'
                });
            }

            // Handle window resize
            let resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    isMobile = window.innerWidth <= 768;
                    updateMobileMenu();
                }, 250);
            });

            // Lazy load images
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src) {
                                img.src = img.dataset.src;
                            }
                            imageObserver.unobserve(img);
                        }
                    });
                });

                $('img[data-src]').each(function() {
                    imageObserver.observe(this);
                });
            }

            // Add ripple effect to buttons
            $('.btn-action, .view-btn, .fab-btn').on('click', function(e) {
                const $btn = $(this);
                const x = e.pageX - $btn.offset().left;
                const y = e.pageY - $btn.offset().top;

                $btn.append(`<span class="ripple" style="left: ${x}px; top: ${y}px;"></span>`);

                setTimeout(() => {
                    $btn.find('.ripple').remove();
                }, 600);
            });

            // Add CSS for ripple effect
            $('head').append(`
                <style>
                    .ripple {
                        position: absolute;
                        border-radius: 50%;
                        background: rgba(255, 255, 255, 0.7);
                        transform: scale(0);
                        animation: ripple-animation 0.6s linear;
                    }

                    @keyframes ripple-animation {
                        to {
                            transform: scale(4);
                            opacity: 0;
                        }
                    }

                    .btn-action, .view-btn, .fab-btn {
                        position: relative;
                        overflow: hidden;
                    }
                </style>
            `);

            // Print functionality
            $(document).on('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    window.print();
                }
            });
        });
    </script>
@endpush
