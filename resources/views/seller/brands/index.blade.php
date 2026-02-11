@extends('seller.layout.app')
@push('title')
    Brand Management
@endpush

@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #8b5cf6;
            --success: #10b981;
            --success-light: #34d399;
            --warning: #f59e0b;
            --warning-light: #fbbf24;
            --danger: #ef4444;
            --danger-light: #f87171;
            --info: #3b82f6;
            --dark: #1e293b;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --gradient-primary: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
            --gradient-danger: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
            --gradient-info: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            --gradient-dark: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            --shadow-2xl: 0 25px 50px -12px rgb(0 0 0 / 0.25);
            --shadow-glow: 0 0 40px rgba(99, 102, 241, 0.15);
            --radius-sm: 0.375rem;
            --radius: 0.5rem;
            --radius-md: 0.75rem;
            --radius-lg: 1rem;
            --radius-xl: 1.25rem;
            --radius-2xl: 1.5rem;
            --radius-3xl: 2rem;
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition: 200ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 300ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-bounce: 500ms cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* Page Header */
        .page-header {
            background: var(--gradient-primary);
            color: white;
            border-radius: var(--radius-2xl);
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-xl), var(--shadow-glow);
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 60%);
            animation: pulse-glow 4s ease-in-out infinite;
        }

        .page-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 40%;
            height: 150%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
            animation: pulse-glow 6s ease-in-out infinite reverse;
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.1); }
        }

        .page-header-content {
            position: relative;
            z-index: 1;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            letter-spacing: -0.025em;
        }

        .page-title .material-icons-outlined {
            font-size: 2.5rem;
            background: rgba(255,255,255,0.2);
            padding: 0.5rem;
            border-radius: var(--radius-lg);
            backdrop-filter: blur(10px);
        }

        .page-subtitle {
            opacity: 0.9;
            font-size: 1rem;
            font-weight: 400;
            max-width: 500px;
        }

        .header-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-header {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            border-radius: var(--radius-lg);
            font-weight: 600;
            font-size: 0.9rem;
            transition: all var(--transition);
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-header-primary {
            background: white;
            color: var(--primary);
            box-shadow: var(--shadow-lg);
        }

        .btn-header-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
            color: var(--primary-dark);
        }

        .btn-header-outline {
            background: rgba(255,255,255,0.15);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            backdrop-filter: blur(10px);
        }

        .btn-header-outline:hover {
            background: rgba(255,255,255,0.25);
            border-color: rgba(255,255,255,0.5);
            color: white;
            transform: translateY(-2px);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background: white;
            border-radius: var(--radius-xl);
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-100);
            transition: all var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .stat-card.success::before { background: var(--gradient-success); }
        .stat-card.warning::before { background: var(--gradient-warning); }
        .stat-card.info::before { background: var(--gradient-info); }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .stat-icon.primary { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
        .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .stat-icon.info { background: rgba(59, 130, 246, 0.1); color: var(--info); }

        .stat-icon .material-icons-outlined {
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--gray-900);
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--gray-500);
            font-weight: 500;
        }

        .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius);
            margin-top: 0.75rem;
        }

        .stat-change.positive {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .stat-change.negative {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        /* Search & Filter Section */
        .filter-section {
            background: white;
            border-radius: var(--radius-xl);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-100);
        }

        .search-wrapper {
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-xl);
            font-size: 1rem;
            transition: all var(--transition);
            background: var(--gray-50);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .search-input::placeholder {
            color: var(--gray-400);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            pointer-events: none;
        }

        .search-clear {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: var(--gray-200);
            border: none;
            width: 1.75rem;
            height: 1.75rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition);
            opacity: 0;
            visibility: hidden;
        }

        .search-clear.visible {
            opacity: 1;
            visibility: visible;
        }

        .search-clear:hover {
            background: var(--gray-300);
        }

        .search-clear .material-icons-outlined {
            font-size: 1rem;
            color: var(--gray-600);
        }

        /* Filter Pills */
        .filter-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .filter-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            background: var(--gray-100);
            border: 2px solid transparent;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-600);
            cursor: pointer;
            transition: all var(--transition);
            white-space: nowrap;
        }

        .filter-pill:hover {
            background: var(--gray-200);
            transform: translateY(-1px);
        }

        .filter-pill.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .filter-pill .material-icons-outlined {
            font-size: 1.125rem;
        }

        .filter-pill .count {
            background: rgba(0,0,0,0.1);
            padding: 0.125rem 0.5rem;
            border-radius: 50px;
            font-size: 0.75rem;
            margin-left: 0.25rem;
        }

        .filter-pill.active .count {
            background: rgba(255,255,255,0.2);
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--gray-200);
        }

        .quick-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            background: white;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-700);
            cursor: pointer;
            transition: all var(--transition);
        }

        .quick-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .quick-btn.success {
            background: var(--gradient-success);
            border-color: transparent;
            color: white;
        }

        .quick-btn.success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .quick-btn .material-icons-outlined {
            font-size: 1.125rem;
        }

        /* Sort Dropdown */
        .sort-dropdown {
            position: relative;
        }

        .sort-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 1.25rem;
            background: var(--gray-50);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-xl);
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--gray-700);
            cursor: pointer;
            transition: all var(--transition);
            width: 100%;
            justify-content: space-between;
        }

        .sort-toggle:hover {
            border-color: var(--primary);
        }

        .sort-menu {
            position: absolute;
            top: calc(100% + 0.5rem);
            left: 0;
            right: 0;
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--gray-200);
            padding: 0.5rem;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all var(--transition);
        }

        .sort-dropdown.open .sort-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .sort-option {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-lg);
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .sort-option:hover {
            background: var(--gray-100);
        }

        .sort-option.active {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        .sort-option .material-icons-outlined {
            font-size: 1.25rem;
            color: var(--gray-400);
        }

        .sort-option.active .material-icons-outlined {
            color: var(--primary);
        }

        /* Alerts */
        .alert-modern {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.25rem;
            border-radius: var(--radius-xl);
            margin-bottom: 1.5rem;
            border: none;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-modern.success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(52, 211, 153, 0.1) 100%);
            border-left: 4px solid var(--success);
        }

        .alert-modern.danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(248, 113, 113, 0.1) 100%);
            border-left: 4px solid var(--danger);
        }

        .alert-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .alert-modern.success .alert-icon {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }

        .alert-modern.danger .alert-icon {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger);
        }

        .alert-content h5 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--gray-900);
        }

        .alert-content p {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin: 0;
        }

        .alert-close {
            background: transparent;
            border: none;
            padding: 0.25rem;
            cursor: pointer;
            color: var(--gray-400);
            margin-left: auto;
            transition: color var(--transition);
        }

        .alert-close:hover {
            color: var(--gray-600);
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: var(--radius-2xl);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-100);
        }

        .table-header {
            padding: 1.25rem 1.5rem;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-title .count-badge {
            background: var(--primary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .table-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Desktop Table */
        .brands-table {
            width: 100%;
            border-collapse: collapse;
        }

        .brands-table thead {
            background: var(--gray-50);
        }

        .brands-table th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-500);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--gray-200);
            white-space: nowrap;
        }

        .brands-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .brands-table tbody tr {
            transition: all var(--transition);
        }

        .brands-table tbody tr:hover {
            background: var(--gray-50);
        }

        .brands-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Brand Cell */
        .brand-cell {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .brand-avatar {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: var(--radius-lg);
            overflow: hidden;
            flex-shrink: 0;
            background: var(--gray-100);
            border: 2px solid var(--gray-200);
            transition: all var(--transition);
        }

        .brands-table tbody tr:hover .brand-avatar {
            border-color: var(--primary);
            transform: scale(1.05);
        }

        .brand-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-avatar-placeholder {
            width: 100%;
            height: 100%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
        }

        .brand-info h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .brand-code {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            color: var(--gray-500);
            background: var(--gray-100);
            padding: 0.125rem 0.5rem;
            border-radius: var(--radius);
        }

        .brand-description {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
            line-height: 1.4;
            max-width: 250px;
        }

        /* Product Count Badge */
        .product-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 0.875rem;
            background: rgba(59, 130, 246, 0.1);
            border-radius: 50px;
            font-weight: 600;
            color: var(--info);
            font-size: 0.875rem;
        }

        .product-badge .material-icons-outlined {
            font-size: 1rem;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .status-badge.active {
            background: var(--gradient-success);
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .status-badge.inactive {
            background: var(--gray-200);
            color: var(--gray-600);
        }

        .status-badge .material-icons-outlined {
            font-size: 0.875rem;
        }

        /* Date Cell */
        .date-cell {
            font-size: 0.875rem;
            color: var(--gray-500);
        }

        .date-cell .time {
            font-size: 0.75rem;
            color: var(--gray-400);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: var(--radius-lg);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition);
            text-decoration: none;
        }

        .action-btn .material-icons-outlined {
            font-size: 1.25rem;
        }

        .action-btn.view {
            background: rgba(139, 92, 246, 0.1);
            color: var(--secondary);
        }

        .action-btn.view:hover {
            background: var(--secondary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        }

        .action-btn.edit {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .action-btn.edit:hover {
            background: var(--warning);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .action-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .action-btn.delete:hover {
            background: var(--danger);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* Mobile Card View */
        .mobile-cards {
            display: none;
            padding: 1rem;
            gap: 1rem;
            flex-direction: column;
        }

        .brand-card {
            background: white;
            border-radius: var(--radius-xl);
            padding: 1.25rem;
            border: 1px solid var(--gray-200);
            transition: all var(--transition);
        }

        .brand-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-lg);
        }

        .brand-card-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .brand-card-avatar {
            width: 4rem;
            height: 4rem;
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: var(--gray-100);
            border: 2px solid var(--gray-200);
            flex-shrink: 0;
        }

        .brand-card-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand-card-avatar-placeholder {
            width: 100%;
            height: 100%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .brand-card-title {
            flex: 1;
        }

        .brand-card-title h4 {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .brand-card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .brand-card-description {
            font-size: 0.875rem;
            color: var(--gray-500);
            line-height: 1.5;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--gray-100);
        }

        .brand-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-card-date {
            font-size: 0.75rem;
            color: var(--gray-400);
        }

        .brand-card-actions {
            display: flex;
            gap: 0.5rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
            border-radius: var(--radius-2xl);
            border: 2px dashed var(--gray-300);
            position: relative;
            overflow: hidden;
        }

        .empty-state::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, transparent 70%);
            transform: translate(-50%, -50%);
            animation: pulse-glow 4s ease-in-out infinite;
        }

        .empty-icon {
            position: relative;
            z-index: 1;
            width: 5rem;
            height: 5rem;
            margin: 0 auto 1.5rem;
            background: var(--gradient-primary);
            border-radius: var(--radius-2xl);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .empty-icon .material-icons-outlined {
            font-size: 2.5rem;
            color: white;
        }

        .empty-state h3 {
            position: relative;
            z-index: 1;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.75rem;
        }

        .empty-state p {
            position: relative;
            z-index: 1;
            color: var(--gray-500);
            font-size: 1rem;
            max-width: 400px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        .empty-actions {
            position: relative;
            z-index: 1;
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary-gradient {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all var(--transition);
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
            color: white;
        }

        .btn-outline-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            background: white;
            color: var(--primary);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all var(--transition);
            text-decoration: none;
        }

        .btn-outline-primary:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            color: var(--primary);
        }

        /* View Toggle */
        .view-toggle {
            display: flex;
            background: var(--gray-100);
            border-radius: var(--radius-lg);
            padding: 0.25rem;
        }

        .view-toggle-btn {
            padding: 0.5rem 0.75rem;
            border: none;
            background: transparent;
            border-radius: var(--radius);
            cursor: pointer;
            color: var(--gray-500);
            transition: all var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .view-toggle-btn.active {
            background: white;
            color: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        .view-toggle-btn:hover:not(.active) {
            color: var(--gray-700);
        }

        .view-toggle-btn .material-icons-outlined {
            font-size: 1.25rem;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .page-header {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .page-title .material-icons-outlined {
                font-size: 2rem;
            }

            .filter-section {
                padding: 1.25rem;
            }
        }

        @media (max-width: 768px) {
            .page-header {
                text-align: center;
            }

            .page-header-content > div:first-child {
                margin-bottom: 1.5rem;
            }

            .page-title {
                justify-content: center;
                font-size: 1.375rem;
            }

            .page-subtitle {
                margin: 0 auto;
            }

            .header-actions {
                justify-content: center;
                width: 100%;
            }

            .btn-header {
                flex: 1;
                justify-content: center;
                min-width: 140px;
            }

            .filter-pills {
                justify-content: flex-start;
                overflow-x: auto;
                padding-bottom: 0.5rem;
                margin: 1rem -0.5rem 0;
                padding: 0 0.5rem 0.5rem;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }

            .filter-pills::-webkit-scrollbar {
                display: none;
            }

            .quick-actions {
                flex-wrap: wrap;
            }

            .quick-btn {
                flex: 1;
                min-width: calc(50% - 0.375rem);
                justify-content: center;
                font-size: 0.8125rem;
                padding: 0.5rem 0.75rem;
            }

            /* Show mobile cards by default on mobile, hide table */
            .table-responsive {
                display: none;
            }

            .mobile-cards {
                display: flex;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .table-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }

        @media (min-width: 769px) {
            .table-responsive {
                display: block;
            }

            .mobile-cards {
                display: none !important;
            }
        }

        @media (max-width: 480px) {
            .page-header {
                padding: 1.25rem;
                border-radius: var(--radius-xl);
            }

            .page-title {
                font-size: 1.25rem;
                flex-direction: column;
                gap: 0.5rem;
            }

            .page-title .material-icons-outlined {
                font-size: 1.75rem;
            }

            .page-subtitle {
                font-size: 0.875rem;
            }

            .header-actions {
                flex-direction: column;
            }

            .btn-header {
                width: 100%;
            }

            .stat-card {
                padding: 1.25rem;
            }

            .stat-value {
                font-size: 1.75rem;
            }

            .filter-section {
                padding: 1rem;
            }

            .search-input {
                padding: 0.875rem 0.875rem 0.875rem 2.75rem;
                font-size: 0.9375rem;
            }

            .search-icon {
                left: 0.875rem;
            }

            .filter-pill {
                padding: 0.375rem 0.75rem;
                font-size: 0.8125rem;
            }

            .quick-btn {
                min-width: 100%;
            }

            .brand-card {
                padding: 1rem;
            }

            .brand-card-avatar {
                width: 3.5rem;
                height: 3.5rem;
            }

            .brand-card-title h4 {
                font-size: 1rem;
            }

            .empty-state {
                padding: 3rem 1.5rem;
            }

            .empty-icon {
                width: 4rem;
                height: 4rem;
            }

            .empty-icon .material-icons-outlined {
                font-size: 2rem;
            }

            .empty-state h3 {
                font-size: 1.25rem;
            }

            .empty-state p {
                font-size: 0.875rem;
            }

            .empty-actions {
                flex-direction: column;
            }

            .btn-primary-gradient,
            .btn-outline-primary {
                width: 100%;
                justify-content: center;
            }
        }

        /* Animations */
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

        .animate-fade-in {
            animation: fadeInUp 0.5s ease forwards;
        }

        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.2s; }
        .animate-delay-3 { animation-delay: 0.3s; }
        .animate-delay-4 { animation-delay: 0.4s; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header animate-fade-in">
        <div class="page-header-content">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-4">
                <div>
                    <h1 class="page-title">
                        <span class="material-icons-outlined">dashboard</span>
                        Brand Management
                    </h1>
                    <p class="page-subtitle">
                        Manage your product brands efficiently with advanced analytics and quick actions
                    </p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('seller.brands.create') }}" class="btn-header btn-header-primary">
                        <span class="material-icons-outlined">add_circle</span>
                        New Brand
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card animate-fade-in animate-delay-1">
            <div class="stat-icon primary">
                <span class="material-icons-outlined">storefront</span>
            </div>
            <div class="stat-value">{{ $brands->count() ?? 0 }}</div>
            <div class="stat-label">Total Brands</div>
        </div>

        <div class="stat-card success animate-fade-in animate-delay-2">
            <div class="stat-icon success">
                <span class="material-icons-outlined">check_circle</span>
            </div>
            <div class="stat-value">{{ $activeBrands ?? 0 }}</div>
            <div class="stat-label">Active Brands</div>
        </div>

        <div class="stat-card warning animate-fade-in animate-delay-3">
            <div class="stat-icon warning">
                <span class="material-icons-outlined">inventory_2</span>
            </div>
            <div class="stat-value">{{ $totalProducts ?? 0 }}</div>
            <div class="stat-label">Total Products</div>
        </div>

        <div class="stat-card info animate-fade-in animate-delay-4">
            <div class="stat-icon info">
                <span class="material-icons-outlined">trending_up</span>
            </div>
            <div class="stat-value">{{ $topBrands ?? 0 }}</div>
            <div class="stat-label">Top Brands</div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="filter-section animate-fade-in">
        <div class="row align-items-center filter-row" style="display: flex; gap: 1rem;">
            <div style="flex: 1; min-width: 0;">
                <div class="search-wrapper">
                    <span class="material-icons-outlined search-icon">search</span>
                    <input type="text"
                           class="search-input"
                           id="brandSearch"
                           placeholder="Search brands by name, code, or description..."
                           oninput="handleSearchInput(this)">
                    <button class="search-clear" id="searchClear" onclick="clearSearch()">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
            </div>
            <div style="flex-shrink: 0; min-width: 180px;">
                <div class="sort-dropdown" id="sortDropdown">
                    <button class="sort-toggle" onclick="toggleSortDropdown()">
                        <span class="d-flex align-items-center gap-2">
                            <span class="material-icons-outlined">sort</span>
                            <span id="currentSort">Sort By</span>
                        </span>
                        <span class="material-icons-outlined">expand_more</span>
                    </button>
                    <div class="sort-menu">
                        <div class="sort-option" onclick="sortBrands('name')">
                            <span class="material-icons-outlined">sort_by_alpha</span>
                            Name (A-Z)
                        </div>
                        <div class="sort-option" onclick="sortBrands('name_desc')">
                            <span class="material-icons-outlined">sort_by_alpha</span>
                            Name (Z-A)
                        </div>
                        <div class="sort-option" onclick="sortBrands('products')">
                            <span class="material-icons-outlined">inventory</span>
                            Most Products
                        </div>
                        <div class="sort-option" onclick="sortBrands('recent')">
                            <span class="material-icons-outlined">schedule</span>
                            Recently Added
                        </div>
                        <div class="sort-option" onclick="sortBrands('status')">
                            <span class="material-icons-outlined">toggle_on</span>
                            Status
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="filter-pills">
            <div class="filter-pill active" data-filter="all" onclick="filterByStatus('all', this)">
                <span class="material-icons-outlined">apps</span>
                All Brands
                <span class="count">{{ $brands->count() ?? 0 }}</span>
            </div>
            <div class="filter-pill" data-filter="active" onclick="filterByStatus('active', this)">
                <span class="material-icons-outlined" style="color: #10b981;">check_circle</span>
                Active
                <span class="count">{{ $activeBrands ?? 0 }}</span>
            </div>
            <div class="filter-pill" data-filter="inactive" onclick="filterByStatus('inactive', this)">
                <span class="material-icons-outlined" style="color: #6b7280;">cancel</span>
                Inactive
                <span class="count">{{ ($brands->count() ?? 0) - ($activeBrands ?? 0) }}</span>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('error'))
        <div class="alert-modern danger animate-fade-in" role="alert">
            <div class="alert-icon">
                <span class="material-icons-outlined">error_outline</span>
            </div>
            <div class="alert-content">
                <h5>Operation Failed</h5>
                <p>{{ session('error') }}</p>
            </div>
            <button class="alert-close" onclick="this.parentElement.remove()">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert-modern success animate-fade-in" role="alert">
            <div class="alert-icon">
                <span class="material-icons-outlined">check_circle_outline</span>
            </div>
            <div class="alert-content">
                <h5>Success!</h5>
                <p>{{ session('success') }}</p>
            </div>
            <button class="alert-close" onclick="this.parentElement.remove()">
                <span class="material-icons-outlined">close</span>
            </button>
        </div>
    @endif

    <!-- Brands List -->
    @if(isset($brands) && $brands->count() > 0)
        <div class="table-container animate-fade-in">
            <div class="table-header">
                <h5 class="table-title">
                    <span class="material-icons-outlined">list</span>
                    All Brands
                    <span class="count-badge">{{ $brands->count() }}</span>
                </h5>
                <div class="table-actions">
                    <div class="view-toggle d-none d-md-flex">
                        <button class="view-toggle-btn active" onclick="setView('table')" title="Table View">
                            <span class="material-icons-outlined">view_list</span>
                        </button>
                        <button class="view-toggle-btn" onclick="setView('grid')" title="Grid View">
                            <span class="material-icons-outlined">grid_view</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="table-responsive">
                <table class="brands-table" id="brandsTable">
                    <thead>
                    <tr>
                        <th>Brand</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($brands as $brand)
                        <tr data-status="{{ $brand->status }}" data-products="{{ $brand->products_count ?? 0 }}" data-id="{{ $brand->id }}">
                            <td>
                                <div class="brand-cell">
                                    <div class="brand-avatar">
                                        @if($brand->image)
                                            <img src="{{ asset('storage/' . $brand->image) }}"
                                                 alt="{{ $brand->name }}"
                                                 onerror="this.parentElement.innerHTML='<div class=\'brand-avatar-placeholder\'>{{ strtoupper(substr($brand->name, 0, 2)) }}</div>'">
                                        @else
                                            <div class="brand-avatar-placeholder">
                                                {{ strtoupper(substr($brand->name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="brand-info">
                                        <h4 class="brand-name">{{ $brand->name }}</h4>
                                        <span class="brand-code">
                                            <span class="material-icons-outlined" style="font-size: 0.75rem;">tag</span>
                                            {{ $brand->code ?? 'BR-' . str_pad($brand->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                        @if($brand->description)
                                            <p class="brand-description">{{ Str::limit($brand->description, 60) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="product-badge">
                                    <span class="material-icons-outlined">inventory_2</span>
                                    {{ $brand->products_count ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $brand->status == 'active' ? 'active' : 'inactive' }}">
                                    <span class="material-icons-outlined">
                                        {{ $brand->status == 'active' ? 'check_circle' : 'cancel' }}
                                    </span>
                                    {{ ucfirst($brand->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <div>{{ $brand->created_at->format('M d, Y') }}</div>
                                    <div class="time">{{ $brand->created_at->format('h:i A') }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('seller.brands.show', $brand->id) }}"
                                       class="action-btn view"
                                       title="View">
                                        <span class="material-icons-outlined">visibility</span>
                                    </a>
                                    <a href="{{ route('seller.brands.edit', $brand->id) }}"
                                       class="action-btn edit"
                                       title="Edit">
                                        <span class="material-icons-outlined">edit</span>
                                    </a>
                                    <button type="button"
                                            class="action-btn delete"
                                            onclick="deleteBrand({{ $brand->id }}, '{{ addslashes($brand->name) }}')"
                                            title="Delete">
                                        <span class="material-icons-outlined">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="mobile-cards" id="mobileCards">
                @foreach($brands as $brand)
                    <div class="brand-card" data-status="{{ $brand->status }}" data-products="{{ $brand->products_count ?? 0 }}" data-id="{{ $brand->id }}">
                        <div class="brand-card-header">
                            <div class="brand-card-avatar">
                                @if($brand->image)
                                    <img src="{{ asset('storage/' . $brand->image) }}"
                                         alt="{{ $brand->name }}"
                                         onerror="this.parentElement.innerHTML='<div class=\'brand-card-avatar-placeholder\'>{{ strtoupper(substr($brand->name, 0, 2)) }}</div>'">
                                @else
                                    <div class="brand-card-avatar-placeholder">
                                        {{ strtoupper(substr($brand->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="brand-card-title">
                                <h4 class="brand-name">{{ $brand->name }}</h4>
                                <span class="brand-code">
                                    <span class="material-icons-outlined" style="font-size: 0.75rem;">tag</span>
                                    {{ $brand->code ?? 'BR-' . str_pad($brand->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                        </div>
                        <div class="brand-card-meta">
                            <span class="product-badge">
                                <span class="material-icons-outlined">inventory_2</span>
                                {{ $brand->products_count ?? 0 }} products
                            </span>
                            <span class="status-badge {{ $brand->status == 'active' ? 'active' : 'inactive' }}">
                                <span class="material-icons-outlined">
                                    {{ $brand->status == 'active' ? 'check_circle' : 'cancel' }}
                                </span>
                                {{ ucfirst($brand->status) }}
                            </span>
                        </div>
                        @if($brand->description)
                            <p class="brand-card-description">{{ Str::limit($brand->description, 100) }}</p>
                        @endif
                        <div class="brand-card-footer">
                            <span class="brand-card-date">
                                <span class="material-icons-outlined" style="font-size: 0.875rem; vertical-align: middle;">calendar_today</span>
                                {{ $brand->created_at->format('M d, Y') }}
                            </span>
                            <div class="brand-card-actions">
                                <a href="{{ route('seller.brands.show', $brand->id) }}" class="action-btn view">
                                    <span class="material-icons-outlined">visibility</span>
                                </a>
                                <a href="{{ route('seller.brands.edit', $brand->id) }}" class="action-btn edit">
                                    <span class="material-icons-outlined">edit</span>
                                </a>
                                <button type="button" class="action-btn delete" onclick="deleteBrand({{ $brand->id }}, '{{ addslashes($brand->name) }}')">
                                    <span class="material-icons-outlined">delete</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="empty-state animate-fade-in">
            <div class="empty-icon">
                <span class="material-icons-outlined">branding_watermark</span>
            </div>
            <h3>No Brands Found</h3>
            <p>You haven't created any brands yet. Brands help organize your products and build brand recognition with your customers.</p>
            <div class="empty-actions">
                <a href="{{ route('seller.brands.create') }}" class="btn-primary-gradient">
                    <span class="material-icons-outlined">add_circle</span>
                    Create First Brand
                </a>
            </div>
        </div>
    @endif

    <!-- Hidden form for deletion -->
    <form id="delete-form" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Handle search input
        function handleSearchInput(input) {
            const clearBtn = document.getElementById('searchClear');
            if (input.value.length > 0) {
                clearBtn.classList.add('visible');
            } else {
                clearBtn.classList.remove('visible');
            }
            filterBrands();
        }

        // Filter brands by search
        function filterBrands() {
            const search = document.getElementById('brandSearch').value.toLowerCase();
            const tableRows = document.querySelectorAll('#brandsTable tbody tr');
            const cards = document.querySelectorAll('.brand-card');

            // Filter table rows
            tableRows.forEach(row => {
                const name = row.querySelector('.brand-name')?.textContent.toLowerCase() || '';
                const code = row.querySelector('.brand-code')?.textContent.toLowerCase() || '';
                const description = row.querySelector('.brand-description')?.textContent.toLowerCase() || '';

                if (name.includes(search) || code.includes(search) || description.includes(search)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Filter cards
            cards.forEach(card => {
                const name = card.querySelector('.brand-name')?.textContent.toLowerCase() || '';
                const code = card.querySelector('.brand-code')?.textContent.toLowerCase() || '';
                const description = card.querySelector('.brand-card-description')?.textContent.toLowerCase() || '';

                if (name.includes(search) || code.includes(search) || description.includes(search)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Clear search
        function clearSearch() {
            document.getElementById('brandSearch').value = '';
            document.getElementById('searchClear').classList.remove('visible');
            filterBrands();
        }

        // Toggle sort dropdown
        function toggleSortDropdown() {
            const dropdown = document.getElementById('sortDropdown');
            dropdown.classList.toggle('open');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('sortDropdown');
            if (dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.remove('open');
            }
        });

        // Filter by status
        function filterByStatus(status, element) {
            const tableRows = document.querySelectorAll('#brandsTable tbody tr');
            const cards = document.querySelectorAll('.brand-card');
            const filterPills = document.querySelectorAll('.filter-pill');

            filterPills.forEach(pill => pill.classList.remove('active'));
            element.classList.add('active');

            const filterElements = (elements) => {
                elements.forEach(el => {
                    const elStatus = el.getAttribute('data-status');
                    if (status === 'all' || elStatus === status) {
                        el.style.display = '';
                    } else {
                        el.style.display = 'none';
                    }
                });
            };

            filterElements(tableRows);
            filterElements(cards);
        }

        // Sort brands
        function sortBrands(criteria) {
            const tbody = document.querySelector('#brandsTable tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const cardsContainer = document.getElementById('mobileCards');
            const cards = Array.from(cardsContainer.querySelectorAll('.brand-card'));

            const sortLabels = {
                'name': 'Name (A-Z)',
                'name_desc': 'Name (Z-A)',
                'products': 'Most Products',
                'recent': 'Recently Added',
                'status': 'Status'
            };

            document.getElementById('currentSort').textContent = sortLabels[criteria] || 'Sort By';

            const sortFunction = (a, b) => {
                switch(criteria) {
                    case 'name':
                        return a.querySelector('.brand-name').textContent
                            .localeCompare(b.querySelector('.brand-name').textContent);
                    case 'name_desc':
                        return b.querySelector('.brand-name').textContent
                            .localeCompare(a.querySelector('.brand-name').textContent);
                    case 'products':
                        return parseInt(b.getAttribute('data-products')) - parseInt(a.getAttribute('data-products'));
                    case 'recent':
                        return parseInt(b.getAttribute('data-id')) - parseInt(a.getAttribute('data-id'));
                    case 'status':
                        return a.getAttribute('data-status').localeCompare(b.getAttribute('data-status'));
                    default:
                        return 0;
                }
            };

            rows.sort(sortFunction);
            cards.sort(sortFunction);

            rows.forEach(row => tbody.appendChild(row));
            cards.forEach(card => cardsContainer.appendChild(card));

            document.getElementById('sortDropdown').classList.remove('open');
        }

        // Set view type
        function setView(type) {
            const toggleBtns = document.querySelectorAll('.view-toggle-btn');
            const tableView = document.querySelector('.table-responsive');
            const cardView = document.getElementById('mobileCards');

            toggleBtns.forEach(btn => btn.classList.remove('active'));
            event.target.closest('.view-toggle-btn').classList.add('active');

            if (type === 'table') {
                tableView.style.display = '';
                cardView.style.display = 'none';
            } else {
                tableView.style.display = 'none';
                cardView.style.display = 'flex';
            }
        }

        // Delete brand with confirmation
        function deleteBrand(brandId, brandName) {
            Swal.fire({
                title: 'Delete Brand?',
                html: `
                    <div style="text-align: center; padding: 1rem 0;">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center;">
                            <span class="material-icons-outlined" style="font-size: 40px; color: #ef4444;">delete_forever</span>
                        </div>
                        <h4 style="font-size: 1.25rem; font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">${brandName}</h4>
                        <p style="color: #64748b; font-size: 0.9375rem;">This will permanently delete the brand and cannot be undone.</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<span class="material-icons-outlined" style="font-size: 1rem; vertical-align: middle; margin-right: 0.25rem;">delete</span> Delete Brand',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3',
                    confirmButton: 'rounded-2',
                    cancelButton: 'rounded-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form');
                    form.action = `/seller/brands/${brandId}`;
                    form.submit();
                }
            });
        }

        // Initialize page animations
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.animate-fade-in');
            animatedElements.forEach((el, index) => {
                el.style.opacity = '0';
                setTimeout(() => {
                    el.style.opacity = '1';
                }, index * 100);
            });

            // Auto-detect screen size and set appropriate view
            if (window.innerWidth <= 768) {
                // On mobile, show cards view
                const toggleBtns = document.querySelectorAll('.view-toggle-btn');
                const tableView = document.querySelector('.table-responsive');
                const cardView = document.getElementById('mobileCards');

                if (toggleBtns.length > 0) {
                    toggleBtns.forEach(btn => btn.classList.remove('active'));
                    document.querySelector('[onclick="setView(\'grid\')"]').classList.add('active');
                }

                if (tableView && cardView) {
                    tableView.style.display = 'none';
                    cardView.style.display = 'flex';
                }
            }
        });

        // Show success toast
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-3'
            }
        });
        @endif
    </script>
@endpush
