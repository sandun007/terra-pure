@extends('wholesaler.layouts.app')

@push('title')
    Product Inventory Management
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* ===== MODERN DESIGN SYSTEM ===== */
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #e0e7ff;
            --secondary: #8b5cf6;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --info: #3b82f6;
            --info-light: #dbeafe;
            --dark: #1e293b;
            --darker: #0f172a;
            --light: #f8fafc;
            --lighter: #ffffff;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: var(--gray-700);
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            background: var(--lighter);
            padding: 1.25rem 1.5rem;
            border-radius: var(--radius-lg);
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
        }

        .page-header h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-700);
            margin-bottom: 0.25rem;
        }

        .page-header .text-muted {
            font-size: 0.875rem;
            color: var(--gray-500) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.875rem;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-outline-secondary {
            border: 2px solid var(--gray-200);
            background: var(--lighter);
            color: var(--gray-600);
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.875rem;
            transition: var(--transition);
        }

        .btn-outline-secondary:hover {
            background: var(--gray-100);
            border-color: var(--gray-300);
        }

        /* ===== STATS CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--lighter);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-icon.primary {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            color: white;
        }

        .stat-icon.success {
            background: linear-gradient(135deg, var(--success-light) 0%, var(--success) 100%);
            color: white;
        }

        .stat-icon.warning {
            background: linear-gradient(135deg, var(--warning-light) 0%, var(--warning) 100%);
            color: white;
        }

        .stat-icon.info {
            background: linear-gradient(135deg, var(--info-light) 0%, var(--info) 100%);
            color: white;
        }

        .stat-content {
            flex: 1;
            min-width: 0;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-700);
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stat-trend {
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-top: 0.25rem;
            color: var(--gray-500);
        }

        /* ===== TABLE CONTAINER ===== */
        .table-container {
            background: var(--lighter);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        /* ===== PERFECTLY OPTIMIZED TABLE ===== */
        #product-table {
            width: 100% !important;
            margin: 0 !important;
            border-collapse: collapse;
        }

        #product-table thead th {
            background: var(--gray-100);
            color: var(--gray-600);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 0.75rem;
            border-bottom: 2px solid var(--gray-200);
            white-space: nowrap;
            text-align: left;
        }

        #product-table tbody td {
            padding: 0.875rem 0.75rem;
            border-bottom: 1px solid var(--gray-200);
            font-size: 0.875rem;
            color: var(--gray-600);
            vertical-align: middle;
        }

        #product-table tbody tr {
            transition: var(--transition);
        }

        #product-table tbody tr:hover {
            background: var(--gray-100);
        }

        /* Column Widths - Perfectly Balanced */
        #product-table thead th:nth-child(1) {
            width: 60px;
        }

        /* Image */
        #product-table thead th:nth-child(2) {
            width: 25%;
        }

        /* Product Details */
        #product-table thead th:nth-child(3) {
            width: 12%;
        }

        /* Category */
        #product-table thead th:nth-child(4) {
            width: 12%;
        }

        /* Brand */
        #product-table thead th:nth-child(5) {
            width: 12%;
        }

        /* Pricing */
        #product-table thead th:nth-child(6) {
            width: 8%;
        }

        /* Stock */
        #product-table thead th:nth-child(7) {
            width: 8%;
        }

        /* Min Order */
        #product-table thead th:nth-child(8) {
            width: 8%;
        }

        /* Status */
        #product-table thead th:nth-child(9) {
            width: 100px;
        }

        /* Actions */

        /* ===== PRODUCT IMAGE ===== */
        .product-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: var(--radius-sm);
            border: 2px solid var(--lighter);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            display: block;
        }

        .product-img:hover {
            transform: scale(2.5) translateY(-5px);
            box-shadow: var(--shadow-xl);
            z-index: 1000;
            border-color: var(--primary);
        }

        .product-img-placeholder {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--gray-200) 0%, var(--gray-300) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-500);
            font-size: 1rem;
        }

        /* ===== PRODUCT DETAILS ===== */
        .product-name {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
        }

        .product-code {
            font-size: 0.75rem;
            color: var(--gray-500);
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .product-code i {
            font-size: 0.7rem;
        }

        /* ===== BADGES ===== */
        .badge-modern {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
            border: 1px solid transparent;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .badge-modern i {
            font-size: 0.7rem;
        }

        .badge-light {
            background: var(--gray-100);
            color: var(--gray-600);
            border-color: var(--gray-200);
        }

        .badge-success {
            background: var(--success-light);
            color: var(--success);
            border-color: var(--success);
        }

        .badge-danger {
            background: var(--danger-light);
            color: var(--danger);
            border-color: var(--danger);
        }

        /* ===== PRICE DISPLAY ===== */
        .price-display {
            font-weight: 700;
            color: var(--primary);
            font-size: 0.875rem;
            white-space: nowrap;
        }

        .wholesale-price {
            font-size: 0.75rem;
            color: var(--gray-500);
            display: flex;
            align-items: center;
            gap: 0.25rem;
            white-space: nowrap;
        }

        .wholesale-price i {
            font-size: 0.7rem;
        }

        /* ===== STOCK INDICATOR ===== */
        .stock-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
            border: 1px solid transparent;
        }

        .stock-high {
            background: var(--success-light);
            color: var(--success);
            border-color: var(--success);
        }

        .stock-medium {
            background: var(--warning-light);
            color: var(--warning);
            border-color: var(--warning);
        }

        .stock-low {
            background: var(--danger-light);
            color: var(--danger);
            border-color: var(--danger);
        }

        /* ===== ACTION BUTTONS ===== */
        .action-buttons {
            display: flex;
            gap: 0.375rem;
            flex-wrap: nowrap;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            color: white;
            font-size: 0.875rem;
            transition: var(--transition);
            cursor: pointer;
            box-shadow: var(--shadow-sm);
            flex-shrink: 0;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-view {
            background: linear-gradient(135deg, var(--gray-600) 0%, var(--gray-700) 100%);
        }

        .btn-edit {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
        }

        .btn-delete {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
        }

        /* ===== DATA TABLES CUSTOMIZATION ===== */
        .dataTables_wrapper {
            padding: 1.25rem;
        }

        .dataTables_filter {
            margin-bottom: 1rem;
        }

        .dataTables_filter label {
            width: 100%;
            position: relative;
            display: block;
        }

        .dataTables_filter input {
            width: 100% !important;
            max-width: 300px;
            padding: 0.625rem 1rem 0.625rem 2.5rem !important;
            border: 2px solid var(--gray-200) !important;
            border-radius: var(--radius-md) !important;
            font-size: 0.875rem;
            transition: var(--transition);
            background: var(--lighter);
            height: 40px;
        }

        .dataTables_filter input:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px var(--primary-light) !important;
            outline: none;
        }

        .dataTables_filter::before {
            content: '\f002';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            z-index: 1;
            pointer-events: none;
            font-size: 0.875rem;
        }

        .dataTables_length {
            margin-bottom: 1rem;
        }

        .dataTables_length select {
            padding: 0.5rem 2rem 0.5rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-md);
            font-size: 0.875rem;
            background: var(--lighter);
            cursor: pointer;
            height: 40px;
        }

        .dataTables_info {
            font-size: 0.875rem;
            color: var(--gray-500);
            padding: 1rem 0;
        }

        .dataTables_paginate {
            display: flex;
            gap: 0.25rem;
            justify-content: flex-end;
            flex-wrap: wrap;
            padding: 1rem 0;
        }

        .dataTables_paginate .paginate_button {
            min-width: 36px;
            height: 36px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-sm) !important;
            border: 1px solid var(--gray-200) !important;
            background: var(--lighter) !important;
            color: var(--gray-600) !important;
            font-weight: 500;
            font-size: 0.875rem;
            transition: var(--transition);
            margin: 0 2px !important;
            padding: 0 !important;
        }

        .dataTables_paginate .paginate_button:hover {
            background: var(--gray-100) !important;
            border-color: var(--gray-300) !important;
            transform: translateY(-1px);
        }

        .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
            border-color: transparent !important;
            color: white !important;
        }

        /* ===== RESPONSIVE BREAKPOINTS ===== */

        /* Desktop Large (1200px+) */
        @media (min-width: 1200px) {
            #product-table thead th:nth-child(1) {
                width: 60px;
            }

            #product-table thead th:nth-child(2) {
                width: 25%;
            }

            #product-table thead th:nth-child(3) {
                width: 12%;
            }

            #product-table thead th:nth-child(4) {
                width: 12%;
            }

            #product-table thead th:nth-child(5) {
                width: 12%;
            }

            #product-table thead th:nth-child(6) {
                width: 8%;
            }

            #product-table thead th:nth-child(7) {
                width: 8%;
            }

            #product-table thead th:nth-child(8) {
                width: 8%;
            }

            #product-table thead th:nth-child(9) {
                width: 100px;
            }
        }

        /* Desktop (992px - 1199px) */
        @media (max-width: 1199px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            #product-table thead th:nth-child(4) {
                display: none;
            }

            /* Hide Brand */
            #product-table tbody td:nth-child(4) {
                display: none;
            }

            #product-table thead th:nth-child(2) {
                width: 30%;
            }

            #product-table thead th:nth-child(3) {
                width: 15%;
            }

            #product-table thead th:nth-child(5) {
                width: 15%;
            }
        }

        /* Tablet (768px - 991px) */
        @media (max-width: 991px) {
            .page-header {
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1.25rem;
            }

            .stat-value {
                font-size: 1.25rem;
            }

            #product-table thead th:nth-child(3),
            #product-table thead th:nth-child(4),
            #product-table thead th:nth-child(7) {
                display: none; /* Hide Category, Brand, Min Order */
            }

            #product-table tbody td:nth-child(3),
            #product-table tbody td:nth-child(4),
            #product-table tbody td:nth-child(7) {
                display: none;
            }

            #product-table thead th:nth-child(2) {
                width: 35%;
            }

            #product-table thead th:nth-child(5) {
                width: 15%;
            }

            #product-table thead th:nth-child(6) {
                width: 10%;
            }

            #product-table thead th:nth-child(8) {
                width: 10%;
            }

            .product-name {
                max-width: 150px;
            }
        }

        /* Mobile Landscape (576px - 767px) */
        @media (max-width: 767px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }

            .stat-card {
                padding: 0.75rem;
            }

            .stat-icon {
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }

            .stat-value {
                font-size: 1rem;
            }

            .stat-label {
                font-size: 0.7rem;
            }

            .stat-trend {
                font-size: 0.65rem;
            }

            #product-table thead th:nth-child(3),
            #product-table thead th:nth-child(4),
            #product-table thead th:nth-child(5),
            #product-table thead th:nth-child(7) {
                display: none; /* Hide Category, Brand, Pricing, Min Order */
            }

            #product-table tbody td:nth-child(3),
            #product-table tbody td:nth-child(4),
            #product-table tbody td:nth-child(5),
            #product-table tbody td:nth-child(7) {
                display: none;
            }

            #product-table thead th:nth-child(2) {
                width: 40%;
            }

            #product-table thead th:nth-child(6) {
                width: 12%;
            }

            #product-table thead th:nth-child(8) {
                width: 12%;
            }

            #product-table thead th:nth-child(9) {
                width: 80px;
            }

            .product-name {
                max-width: 120px;
                font-size: 0.8rem;
            }

            .product-code {
                font-size: 0.65rem;
            }

            .stock-indicator {
                padding: 0.2rem 0.5rem;
                font-size: 0.65rem;
            }

            .badge-modern {
                padding: 0.25rem 0.5rem;
                font-size: 0.65rem;
            }

            .action-buttons {
                gap: 0.25rem;
            }

            .btn-action {
                width: 28px;
                height: 28px;
                font-size: 0.75rem;
            }

            .dataTables_filter input {
                max-width: 100%;
                height: 36px;
                font-size: 0.8rem;
            }

            .dataTables_paginate {
                justify-content: center;
            }

            .dataTables_paginate .paginate_button {
                min-width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }
        }

        /* Mobile Portrait (<=575px) */
        @media (max-width: 575px) {
            .page-header h3 {
                font-size: 1.25rem;
            }

            .page-header .text-muted {
                font-size: 0.75rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                display: flex;
                align-items: center;
            }

            #product-table thead th:nth-child(6) {
                display: none;
            }

            /* Hide Stock */
            #product-table tbody td:nth-child(6) {
                display: none;
            }

            #product-table thead th:nth-child(2) {
                width: 50%;
            }

            #product-table thead th:nth-child(8) {
                width: 15%;
            }

            #product-table thead th:nth-child(9) {
                width: 70px;
            }

            .product-name {
                max-width: 100px;
                font-size: 0.75rem;
            }

            .product-code {
                font-size: 0.6rem;
            }

            .product-code i {
                display: none;
            }

            .status-badge i {
                display: none;
            }

            .badge-modern i {
                display: none;
            }

            .btn-action {
                width: 26px;
                height: 26px;
                font-size: 0.7rem;
            }

            .dataTables_length {
                display: none;
            }

            .dataTables_info {
                text-align: center;
                font-size: 0.75rem;
            }
        }

        /* Extra Small (<=400px) */
        @media (max-width: 400px) {
            .product-name {
                max-width: 80px;
            }

            #product-table thead th:nth-child(8) {
                width: 20%;
            }

            #product-table thead th:nth-child(9) {
                width: 60px;
            }

            .badge-modern {
                padding: 0.2rem 0.4rem;
                font-size: 0.6rem;
            }

            .btn-action {
                width: 24px;
                height: 24px;
                font-size: 0.65rem;
            }
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            background: var(--lighter);
            border-radius: var(--radius-lg);
            border: 2px dashed var(--gray-300);
            box-shadow: var(--shadow-md);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--gray-400);
            margin-bottom: 1rem;
        }

        .empty-state h4 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--gray-500);
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        /* ===== ALERTS ===== */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .alert-success {
            background: var(--success-light);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background: var(--danger-light);
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }

        /* ===== ANIMATIONS ===== */
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

        .page-header,
        .stats-grid,
        .table-container {
            animation: fadeIn 0.3s ease-out;
        }

        /* ===== CUSTOM SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-400);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-500);
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <h3 class="d-flex align-items-center gap-2">
                    <i class="fas fa-boxes" style="color: var(--primary);"></i>
                    Product Inventory
                </h3>
                <p class="text-muted d-flex align-items-center gap-2">
                    <i class="fas fa-list"></i>
                    <span>{{ $products->count() }} Products in Inventory</span>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('wholesaler.products.create') }}"
                   class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Product</span>
                </a>
                <button class="btn btn-outline-secondary d-flex align-items-center gap-2" id="exportBtn">
                    <i class="fas fa-download"></i>
                    <span class="d-none d-md-inline">Export</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Products</div>
                <div class="stat-value">{{ number_format($products->count()) }}</div>
                <div class="stat-trend">
                    <i class="fas fa-chart-line" style="color: var(--success);"></i>
                    <span>+12% this month</span>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Active</div>
                <div class="stat-value">{{ number_format($products->where('status', 'active')->count()) }}</div>
                <div class="stat-trend">
                    <i class="fas fa-circle" style="color: var(--success);"></i>
                    <span>{{ round(($products->where('status', 'active')->count() / max($products->count(), 1)) * 100) }}%</span>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Low Stock</div>
                <div class="stat-value">{{ number_format($products->where('qty', '<=', 10)->count()) }}</div>
                <div class="stat-trend">
                    <i class="fas fa-clock" style="color: var(--warning);"></i>
                    <span>Need reorder</span>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-rupee-sign"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Inventory Value</div>
                <div class="stat-value">
                    Rs. {{ number_format($products->sum(function($p) { return $p->qty * $p->display_price; }), 0) }}</div>
                <div class="stat-trend">
                    <i class="fas fa-chart-bar" style="color: var(--info);"></i>
                    <span>Total value</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Content -->
    @if($products->isEmpty())
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h4>No Products Found</h4>
            <p>Start building your inventory by adding your first product</p>
            <a href="{{ route('wholesaler.products.create') }}"
               class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                Add First Product
            </a>
        </div>
    @else
        <div class="table-container">
            <table id="product-table" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Details</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Pricing</th>
                    <th>Stock</th>
                    <th>Min Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="product-img"
                                     data-bs-toggle="tooltip"
                                     title="{{ $product->name }}">
                            @else
                                <div class="product-img-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="product-name">{{ $product->name }}</div>
                            <div class="product-code">
                                <i class="fas fa-barcode"></i>
                                <span>{{ $product->code }}</span>
                            </div>
                        </td>
                        <td>
                                <span class="badge-modern badge-light">
                                    <i class="fas fa-tag"></i>
                                    <span>{{ $product->category->name ?? 'N/A' }}</span>
                                </span>
                        </td>
                        <td>
                                <span class="badge-modern badge-light">
                                    <i class="fas fa-tags"></i>
                                    <span>{{ $product->brand->name ?? 'N/A' }}</span>
                                </span>
                        </td>
                        <td>
                            <div class="price-display">Rs. {{ number_format($product->display_price, 2) }}</div>
                            <div class="wholesale-price">
                                <i class="fas fa-store"></i>
                                <span>Rs. {{ number_format($product->wholesale_price, 2) }}</span>
                            </div>
                        </td>
                        <td>
                                <span class="stock-indicator {{
                                    $product->qty > 50 ? 'stock-high' :
                                    ($product->qty > 10 ? 'stock-medium' : 'stock-low')
                                }}">
                                    <i class="fas {{
                                        $product->qty > 50 ? 'fa-check-circle' :
                                        ($product->qty > 10 ? 'fa-exclamation-triangle' : 'fa-exclamation-circle')
                                    }}"></i>
                                    <span>{{ $product->qty }}</span>
                                </span>
                        </td>
                        <td>
                                <span class="badge-modern badge-light">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>{{ $product->min_order_quantity }}</span>
                                </span>
                        </td>
                        <td>
                                <span
                                    class="badge-modern {{ $product->status == 'active' ? 'badge-success' : 'badge-danger' }}">
                                    <i class="fas {{ $product->status == 'active' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    <span>{{ ucfirst($product->status) }}</span>
                                </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('wholesaler.products.show', $product->id) }}"
                                   class="btn-action btn-view"
                                   data-bs-toggle="tooltip"
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('wholesaler.products.edit', $product->id) }}"
                                   class="btn-action btn-edit"
                                   data-bs-toggle="tooltip"
                                   title="Edit Product">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                        class="btn-action btn-delete"
                                        onclick="confirmDelete({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                        data-bs-toggle="tooltip"
                                        title="Delete Product">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable with optimized settings
            const table = $('#product-table').DataTable({
                responsive: false, // We handle responsiveness manually with CSS
                language: {
                    search: "",
                    searchPlaceholder: "Search products...",
                    lengthMenu: "Show _MENU_",
                    info: "_START_ to _END_ of _TOTAL_",
                    infoEmpty: "0 to 0 of 0",
                    zeroRecords: "No matching products",
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        previous: '<i class="fas fa-angle-left"></i>',
                        next: '<i class="fas fa-angle-right"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>'
                    }
                },
                columnDefs: [
                    {orderable: false, targets: [0, 8]},
                    {className: "align-middle", targets: "_all"}
                ],
                order: [[1, 'asc']],
                pageLength: 10,
                lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                dom: '<"row g-2"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row g-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                drawCallback: function () {
                    initTooltips();
                }
            });

            // Initialize tooltips
            function initTooltips() {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl, {
                        trigger: 'hover',
                        placement: 'top'
                    });
                });
            }

            // Auto-hide alerts
            setTimeout(function () {
                $('.alert').fadeOut(300, function () {
                    $(this).alert('close');
                });
            }, 5000);

            // Export functionality
            $('#exportBtn').on('click', function () {
                Swal.fire({
                    title: 'Export Products',
                    text: 'Choose export format',
                    icon: 'question',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: '<i class="fas fa-file-csv"></i> CSV',
                    denyButtonText: '<i class="fas fa-file-excel"></i> Excel',
                    cancelButtonText: '<i class="fas fa-times"></i> Cancel',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-success me-2',
                        denyButton: 'btn btn-warning me-2',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Exporting...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                                setTimeout(() => {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Export Complete',
                                        text: 'CSV file downloaded',
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                }, 1500);
                            }
                        });
                    } else if (result.isDenied) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Coming Soon',
                            text: 'Excel export coming soon!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            });

            // Table row double-click to view
            $('#product-table tbody tr').on('dblclick', function () {
                const viewBtn = $(this).find('.btn-view');
                if (viewBtn.length) {
                    window.location.href = viewBtn.attr('href');
                }
            });

            // Keyboard shortcuts
            $(document).on('keydown', function (e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    $('.dataTables_filter input').focus();
                }
                if (e.key === 'Escape') {
                    $('.dataTables_filter input').val('').trigger('keyup');
                }
            });

            // Responsive adjustments
            let resizeTimer;
            $(window).on('resize', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function () {
                    table.columns.adjust();
                }, 250);
            });

            initTooltips();
        });

        // Delete confirmation with SweetAlert2
        function confirmDelete(productId, productName) {
            Swal.fire({
                title: 'Delete Product?',
                html: `
                    <div style="text-align: center">
                        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: var(--danger); margin-bottom: 1rem;"></i>
                        <h5 style="font-weight: 600;">${productName}</h5>
                        <p class="text-muted" style="font-size: 0.9rem;">This action cannot be undone.</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--danger)',
                cancelButtonColor: 'var(--gray-500)',
                confirmButtonText: '<i class="fas fa-trash"></i> Delete',
                cancelButtonText: '<i class="fas fa-times"></i> Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/wholesaler/products/${productId}`;

                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';

                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';

                        form.appendChild(csrfInput);
                        form.appendChild(methodInput);
                        document.body.appendChild(form);

                        form.submit();
                    });
                }
            });
        }
    </script>
@endpush
