@extends('wholesaler.layouts.app')

@push('title')
    {{ $product->name }} - Product Details
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Google+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --secondary: #10b981;
            --secondary-light: #34d399;
            --warning: #f59e0b;
            --warning-light: #fbbf24;
            --danger: #ef4444;
            --danger-light: #f87171;
            --info: #6366f1;
            --surface: #ffffff;
            --surface-1: #f8fafc;
            --surface-2: #f1f5f9;
            --surface-3: #e2e8f0;
            --on-surface: #0f172a;
            --on-surface-variant: #475569;
            --outline: #cbd5e1;

            --display-large: 57px;
            --display-medium: 45px;
            --display-small: 36px;
            --headline-large: 32px;
            --headline-medium: 28px;
            --headline-small: 24px;
            --title-large: 22px;
            --title-medium: 16px;
            --title-small: 14px;
            --label-large: 14px;
            --label-medium: 12px;
            --label-small: 11px;
            --body-large: 16px;
            --body-medium: 14px;
            --body-small: 12px;

            --space-xs: 4px;
            --space-sm: 8px;
            --space-md: 16px;
            --space-lg: 24px;
            --space-xl: 32px;
            --space-2xl: 48px;

            --radius-xs: 4px;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --radius-2xl: 28px;
            --radius-full: 9999px;

            --elevation-1: 0 1px 3px 1px rgba(0, 0, 0, 0.15), 0 1px 2px 0 rgba(0, 0, 0, 0.30);
            --elevation-2: 0 2px 6px 2px rgba(0, 0, 0, 0.15), 0 1px 2px 0 rgba(0, 0, 0, 0.30);
            --elevation-3: 0 4px 8px 3px rgba(0, 0, 0, 0.15), 0 1px 3px 0 rgba(0, 0, 0, 0.30);
            --elevation-4: 0 6px 10px 4px rgba(0, 0, 0, 0.15), 0 2px 3px 0 rgba(0, 0, 0, 0.30);

            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 350ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Google Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--on-surface);
            line-height: 1.6;
            font-size: var(--body-medium);
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .main-container {
            width: 100%;
            min-height: 100vh;
            padding: var(--space-md);
            max-width: 1440px;
            margin: 0 auto;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loader {
            width: 56px;
            height: 56px;
            border: 3px solid var(--surface-3);
            border-radius: var(--radius-full);
            border-top-color: var(--primary);
            animation: spin 1s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .back-nav {
            margin-bottom: var(--space-lg);
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: var(--space-sm);
            padding: var(--space-sm) var(--space-md);
            background: var(--surface);
            border-radius: var(--radius-lg);
            text-decoration: none;
            color: var(--on-surface-variant);
            font-weight: 500;
            font-size: var(--body-medium);
            box-shadow: var(--elevation-1);
            border: 1px solid var(--outline);
            transition: all var(--transition-base);
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--elevation-2);
            color: var(--primary);
            border-color: var(--primary-light);
            background: rgba(37, 99, 235, 0.04);
        }

        .product-header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-radius: var(--radius-xl);
            padding: var(--space-xl);
            margin-bottom: var(--space-lg);
            box-shadow: var(--elevation-2);
            position: relative;
            overflow: hidden;
            color: white;
        }

        .product-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            pointer-events: none;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: var(--space-lg);
            position: relative;
            z-index: 1;
        }

        .product-meta {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            margin-bottom: var(--space-md);
            flex-wrap: wrap;
        }

        .meta-chip {
            display: inline-flex;
            align-items: center;
            gap: var(--space-xs);
            padding: var(--space-xs) var(--space-sm);
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-full);
            color: white;
            font-size: var(--label-small);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .meta-chip:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .product-title {
            font-size: var(--headline-large);
            font-weight: 600;
            color: white;
            margin-bottom: var(--space-xs);
            line-height: 1.3;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .status-badge {
            position: absolute;
            top: var(--space-lg);
            right: var(--space-xl);
            display: inline-flex;
            align-items: center;
            gap: var(--space-xs);
            padding: var(--space-xs) var(--space-md);
            border-radius: var(--radius-full);
            font-size: var(--label-medium);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-sm);
            padding: var(--space-sm) var(--space-lg);
            border-radius: var(--radius-lg);
            font-weight: 500;
            font-size: var(--body-medium);
            text-decoration: none;
            transition: all var(--transition-base);
            border: none;
            cursor: pointer;
            min-height: 44px;
            font-family: 'Google Sans', sans-serif;
            letter-spacing: 0.25px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--elevation-1);
        }

        .action-btn.primary {
            background: white;
            color: var(--primary);
        }

        .action-btn.primary:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
            box-shadow: var(--elevation-2);
        }

        .action-btn.secondary {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .action-btn.secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .action-btn.danger {
            background: var(--surface);
            color: var(--danger);
            border: 1px solid var(--outline);
        }

        .action-btn.danger:hover {
            background: var(--danger);
            color: white;
            border-color: var(--danger);
        }

        .product-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--space-lg);
            margin-bottom: var(--space-lg);
        }

        .image-section {
            background: var(--surface);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--elevation-2);
            border: 1px solid var(--outline);
        }

        .main-image-container {
            position: relative;
            height: 450px;
            overflow: hidden;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .main-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform var(--transition-slow);
            padding: var(--space-xl);
        }

        .main-image:hover {
            transform: scale(1.05);
        }

        .image-actions {
            position: absolute;
            top: var(--space-md);
            right: var(--space-md);
            display: flex;
            gap: var(--space-xs);
            z-index: 10;
        }

        .image-action-btn {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-full);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid var(--outline);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--on-surface);
            cursor: pointer;
            transition: all var(--transition-base);
            box-shadow: var(--elevation-1);
        }

        .image-action-btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: var(--elevation-2);
            color: var(--primary);
        }

        .no-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--on-surface-variant);
        }

        .no-image-placeholder .material-symbols-outlined {
            font-size: 5rem;
            margin-bottom: var(--space-md);
            color: var(--outline);
        }

        .thumbnail-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--space-xs);
            padding: var(--space-sm);
            background: var(--surface-1);
            border-top: 1px solid var(--outline);
        }

        .thumbnail-item {
            aspect-ratio: 1;
            border-radius: var(--radius-sm);
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all var(--transition-base);
            position: relative;
            background: var(--surface);
        }

        .thumbnail-item.active {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        .thumbnail-item:hover {
            border-color: var(--primary-light);
            transform: translateY(-2px);
        }

        .thumbnail-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .details-section {
            display: flex;
            flex-direction: column;
            gap: var(--space-lg);
        }

        .pricing-card {
            background: linear-gradient(135deg, var(--surface) 0%, var(--surface-1) 100%);
            border-radius: var(--radius-xl);
            padding: var(--space-xl);
            box-shadow: var(--elevation-2);
            border: 1px solid var(--outline);
            position: relative;
            overflow: hidden;
        }

        .pricing-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 100%);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-lg);
        }

        .card-title {
            font-size: var(--title-large);
            font-weight: 600;
            color: var(--on-surface);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }

        .discount-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--space-xs);
            padding: var(--space-xs) var(--space-md);
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
            border-radius: var(--radius-full);
            font-size: var(--label-medium);
            font-weight: 600;
            box-shadow: var(--elevation-1);
        }

        .price-display {
            margin-bottom: var(--space-xl);
            text-align: center;
        }

        .original-price {
            font-size: var(--body-large);
            color: var(--on-surface-variant);
            text-decoration: line-through;
            margin-bottom: var(--space-xs);
        }

        .current-price {
            font-size: var(--display-small);
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: var(--space-xs);
            line-height: 1.2;
        }

        .currency {
            font-size: var(--title-large);
            color: var(--on-surface-variant);
        }

        .price-metrics {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--space-md);
            margin-top: var(--space-lg);
            padding-top: var(--space-lg);
            border-top: 1px solid var(--outline);
        }

        .metric-item {
            display: flex;
            flex-direction: column;
            gap: var(--space-xs);
            text-align: center;
        }

        .metric-label {
            font-size: var(--label-small);
            color: var(--on-surface-variant);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .metric-value {
            font-size: var(--title-medium);
            font-weight: 600;
            color: var(--on-surface);
        }

        .metric-value.positive {
            color: var(--secondary);
        }

        .stock-card {
            background: var(--surface);
            border-radius: var(--radius-xl);
            padding: var(--space-xl);
            box-shadow: var(--elevation-2);
            border: 1px solid var(--outline);
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--space-xs);
            padding: var(--space-xs) var(--space-md);
            border-radius: var(--radius-full);
            font-size: var(--label-medium);
            font-weight: 600;
        }

        .stock-badge.in-stock {
            background: rgba(16, 185, 129, 0.12);
            color: var(--secondary);
            border: 1px solid rgba(16, 185, 129, 0.24);
        }

        .stock-badge.low-stock {
            background: rgba(245, 158, 11, 0.12);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.24);
        }

        .stock-badge.out-stock {
            background: rgba(239, 68, 68, 0.12);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.24);
        }

        .stock-progress {
            margin: var(--space-xl) 0;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: var(--space-xs);
            font-size: var(--label-medium);
            color: var(--on-surface-variant);
        }

        .progress-track {
            height: 10px;
            background: var(--surface-3);
            border-radius: var(--radius-full);
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: var(--radius-full);
            transition: width var(--transition-slow);
        }

        .progress-fill.in-stock {
            background: linear-gradient(90deg, var(--secondary) 0%, var(--secondary-light) 100%);
        }

        .progress-fill.low-stock {
            background: linear-gradient(90deg, var(--warning) 0%, var(--warning-light) 100%);
        }

        .progress-fill.out-stock {
            background: linear-gradient(90deg, var(--danger) 0%, var(--danger-light) 100%);
        }

        .stock-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--space-md);
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: var(--space-xs);
            padding: var(--space-sm);
            background: var(--surface-1);
            border-radius: var(--radius-md);
            border: 1px solid var(--outline);
        }

        .detail-label {
            font-size: var(--label-small);
            color: var(--on-surface-variant);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: var(--body-large);
            font-weight: 600;
            color: var(--on-surface);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--space-md);
            margin-bottom: var(--space-lg);
        }

        .stat-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: var(--space-lg);
            box-shadow: var(--elevation-1);
            border: 1px solid var(--outline);
            transition: all var(--transition-base);
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--elevation-2);
            border-color: var(--primary-light);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--space-md);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            box-shadow: var(--elevation-1);
        }

        .stat-value {
            font-size: var(--headline-small);
            font-weight: 600;
            color: var(--on-surface);
            margin-bottom: var(--space-xs);
        }

        .stat-label {
            font-size: var(--label-medium);
            color: var(--on-surface-variant);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-card {
            background: var(--surface);
            border-radius: var(--radius-xl);
            padding: var(--space-xl);
            box-shadow: var(--elevation-2);
            border: 1px solid var(--outline);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--space-md) 0;
            border-bottom: 1px solid var(--outline);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            font-weight: 500;
            color: var(--on-surface-variant);
        }

        .info-value {
            font-weight: 600;
            color: var(--on-surface);
            background: var(--surface-2);
            padding: var(--space-xs) var(--space-md);
            border-radius: var(--radius-full);
        }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-list li {
            padding: var(--space-md) 0;
            border-bottom: 1px solid var(--outline);
            display: flex;
            align-items: center;
            gap: var(--space-md);
            color: var(--on-surface);
        }

        .feature-list li:last-child {
            border-bottom: none;
        }

        .feature-list li::before {
            content: "check_circle";
            font-family: 'Material Symbols Outlined';
            color: var(--secondary);
            font-size: 1.4rem;
        }

        .action-buttons {
            display: flex;
            gap: var(--space-md);
            flex-wrap: wrap;
        }

        .description-content {
            line-height: 1.8;
            color: var(--on-surface);
            white-space: pre-line;
            font-size: var(--body-large);
        }

        .fab-container {
            position: fixed;
            bottom: var(--space-xl);
            right: var(--space-xl);
            z-index: 100;
        }

        .fab {
            width: 60px;
            height: 60px;
            border-radius: var(--radius-full);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--elevation-3);
            transition: all var(--transition-base);
            font-size: 28px;
        }

        .fab:hover {
            transform: translateY(-4px) scale(1.1);
            box-shadow: var(--elevation-4);
        }

        .fab-menu {
            position: absolute;
            bottom: 70px;
            right: 0;
            display: none;
            flex-direction: column;
            gap: var(--space-xs);
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: var(--space-xs);
            box-shadow: var(--elevation-3);
            border: 1px solid var(--outline);
            min-width: 220px;
        }

        .fab:hover .fab-menu {
            display: flex;
        }

        .fab-item {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            padding: var(--space-md) var(--space-lg);
            background: none;
            border: none;
            border-radius: var(--radius-md);
            color: var(--on-surface);
            font-family: 'Google Sans', sans-serif;
            font-size: var(--body-medium);
            cursor: pointer;
            transition: all var(--transition-fast);
            width: 100%;
            text-align: left;
        }

        .fab-item:hover {
            background: var(--surface-2);
            color: var(--primary);
        }

        .fab-item.danger:hover {
            background: var(--danger);
            color: white;
        }

        @media (max-width: 1200px) {
            .product-grid {
                grid-template-columns: 1fr;
            }

            .main-image-container {
                height: 400px;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: var(--space-sm);
            }

            .product-header {
                padding: var(--space-lg);
            }

            .header-content {
                flex-direction: column;
            }

            .product-title {
                font-size: var(--headline-medium);
            }

            .status-badge {
                position: relative;
                top: 0;
                right: 0;
                margin-bottom: var(--space-md);
                display: inline-flex;
                width: fit-content;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .price-metrics {
                grid-template-columns: 1fr;
            }

            .stock-details {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .action-btn {
                width: 100%;
            }

            .main-image-container {
                height: 300px;
            }

            .thumbnail-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .product-meta {
                flex-direction: column;
                align-items: flex-start;
            }

            .thumbnail-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media print {
            .fab-container,
            .header-actions,
            .image-actions,
            .back-btn {
                display: none !important;
            }

            body {
                background: white;
            }

            .product-header {
                background: white;
                color: black;
            }

            .product-title,
            .meta-chip {
                color: black;
            }

            .meta-chip {
                background: #f1f5f9;
                color: #475569;
            }
        }

        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--surface-2);
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--outline);
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--on-surface-variant);
        }
    </style>
@endpush

@section('content')
    <div class="main-container">
        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay">
            <div class="loader"></div>
        </div>

        <!-- Back Navigation -->
        <div class="back-nav" data-aos="fade-down">
            <a href="{{ route('wholesaler.products.index') }}" class="back-btn">
                <span class="material-symbols-outlined">arrow_back</span>
                Back to Products
            </a>
        </div>

        <!-- Product Header -->
        <div class="product-header" data-aos="fade-up">
            <!-- Status Badge -->
            <span class="status-badge">
                <span class="material-symbols-outlined">
                    {{ $product->status === 'active' ? 'check_circle' : 'block' }}
                </span>
                {{ ucfirst($product->status) }}
            </span>

            <div class="header-content">
                <div style="flex: 1;">
                    <div class="product-meta">
                        <span class="meta-chip">
                            <span class="material-symbols-outlined">qr_code</span>
                            {{ $product->code }}
                        </span>
                        @if($product->barcode)
                            <span class="meta-chip">
                                <span class="material-symbols-outlined">qr_code_scanner</span>
                                {{ $product->barcode }}
                            </span>
                        @endif
                        <span class="meta-chip">
                            <span class="material-symbols-outlined">calendar_month</span>
                            {{ $product->created_at->format('M d, Y') }}
                        </span>
                    </div>

                    <h1 class="product-title">{{ $product->name }}</h1>
                </div>

                <div class="header-actions">
                    <a href="{{ route('wholesaler.products.edit', $product->id) }}" class="action-btn primary">
                        <span class="material-symbols-outlined">edit</span>
                        Edit
                    </a>
                    <button class="action-btn secondary" onclick="window.print()">
                        <span class="material-symbols-outlined">print</span>
                        Print
                    </button>
                </div>
            </div>
        </div>

        <!-- Product Content Grid -->
        <div class="product-grid">
            <!-- Image Gallery -->
            <div class="image-section" data-aos="fade-right">
                <div class="main-image-container">
                    @if($product->image)
                        <img id="mainImage"
                             src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="main-image"
                             onerror="this.src='{{ asset('images/default-product.png') }}'">
                    @else
                        <div class="no-image-placeholder">
                            <span class="material-symbols-outlined">image</span>
                            <p>No image available</p>
                        </div>
                    @endif

                    @if($product->image)
                        <div class="image-actions">
                            <button class="image-action-btn" onclick="zoomImage()" title="Zoom">
                                <span class="material-symbols-outlined">zoom_in</span>
                            </button>
                            <button class="image-action-btn" onclick="downloadImage()" title="Download">
                                <span class="material-symbols-outlined">download</span>
                            </button>
                        </div>
                    @endif
                </div>

                @if($product->image)
                    <div class="thumbnail-grid">
                        <div class="thumbnail-item active" data-image="{{ asset('storage/' . $product->image) }}" onclick="changeImage(this)">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        </div>
                        <!-- Add more thumbnails here if you have additional images -->
                    </div>
                @endif
            </div>

            <!-- Details Section -->
            <div class="details-section" data-aos="fade-left">
                <!-- Pricing Card -->
                <div class="pricing-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="material-symbols-outlined">payments</span>
                            Pricing Details
                        </h3>
                        @if($product->discount > 0)
                            <span class="discount-badge">
                                <span class="material-symbols-outlined">local_offer</span>
                                {{ number_format($product->discount, 0) }}% OFF
                            </span>
                        @endif
                    </div>

                    <div class="price-display">
                        @if($product->display_price != $product->final_price)
                            <div class="original-price">
                                Rs. {{ number_format($product->display_price, 2) }}
                            </div>
                        @endif
                        <div class="current-price">
                            <span class="currency">Rs.</span> {{ number_format($product->final_price, 2) }}
                        </div>
                    </div>

                    <div class="price-metrics">
                        <div class="metric-item">
                            <span class="metric-label">Display Price</span>
                            <span class="metric-value">
                                Rs. {{ number_format($product->display_price, 2) }}
                            </span>
                        </div>
                        @if($product->cost_price)
                            <div class="metric-item">
                                <span class="metric-label">Cost Price</span>
                                <span class="metric-value">
                                Rs. {{ number_format($product->cost_price, 2) }}
                            </span>
                            </div>
                        @endif
                        @if($product->profit_margin)
                            <div class="metric-item">
                                <span class="metric-label">Profit Margin</span>
                                <span class="metric-value positive">
                                {{ number_format($product->profit_margin, 2) }}%
                            </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Stock Card -->
                <div class="stock-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="material-symbols-outlined">inventory</span>
                            Stock Information
                        </h3>
                        @php
                            $qty = $product->qty ?? 0;
                            $stockStatus = $qty > 50 ? 'in-stock' : ($qty > 10 ? 'low-stock' : 'out-stock');
                            $stockText = $qty > 50 ? 'In Stock' : ($qty > 10 ? 'Low Stock' : 'Out of Stock');
                            $maxQty = max(100, $qty);
                            $percentage = $maxQty > 0 ? min(100, ($qty / $maxQty) * 100) : 0;
                        @endphp
                        <span class="stock-badge {{ $stockStatus }}">
                            <span class="material-symbols-outlined">
                                {{ $stockStatus === 'in-stock' ? 'inventory' : ($stockStatus === 'low-stock' ? 'warning' : 'cancel') }}
                            </span>
                            {{ $stockText }}
                        </span>
                    </div>

                    <div class="stock-progress">
                        <div class="progress-header">
                            <span>Current Stock Level</span>
                            <span class="fw-bold">{{ $qty }} / {{ $maxQty }}</span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill {{ $stockStatus }}" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>

                    <div class="stock-details">
                        <div class="detail-item">
                            <span class="detail-label">Current Stock</span>
                            <span class="detail-value">{{ $qty }} units</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Min Order Qty</span>
                            <span class="detail-value">{{ $product->min_order_quantity ?? 1 }} units</span>
                        </div>
                    </div>
                </div>

                <!-- Specifications Card -->
                <div class="pricing-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <span class="material-symbols-outlined">tune</span>
                            Specifications
                        </h3>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            <span class="material-symbols-outlined">category</span>
                            Category
                        </span>
                        <span class="info-value">{{ $product->category->name ?? 'N/A' }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            <span class="material-symbols-outlined">branding_watermark</span>
                            Brand
                        </span>
                        <span class="info-value">{{ $product->brand->name ?? 'N/A' }}</span>
                    </div>

                    @if($product->weight)
                        <div class="info-row">
                        <span class="info-label">
                            <span class="material-symbols-outlined">scale</span>
                            Weight
                        </span>
                            <span class="info-value">{{ $product->weight }} kg</span>
                        </div>
                    @endif

                    @if($product->dimensions)
                        <div class="info-row">
                        <span class="info-label">
                            <span class="material-symbols-outlined">straighten</span>
                            Dimensions
                        </span>
                            <span class="info-value">{{ $product->dimensions }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="stats-grid" data-aos="fade-up">
            <div class="stat-card">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">visibility</span>
                </div>
                <div class="stat-value">{{ number_format($product->views ?? 0) }}</div>
                <div class="stat-label">Total Views</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">shopping_cart</span>
                </div>
                <div class="stat-value">{{ number_format($product->sales_count ?? 0) }}</div>
                <div class="stat-label">Total Sales</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">star</span>
                </div>
                <div class="stat-value">{{ number_format($product->rating ?? 0, 1) }}</div>
                <div class="stat-label">Avg Rating</div>
            </div>
        </div>

        <!-- Product Features & Description -->
        <div class="product-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: var(--space-lg);">
            @if($product->description)
                <div class="info-card" data-aos="fade-right">
                    <h3 class="card-title mb-4">
                        <span class="material-symbols-outlined">description</span>
                        Product Description
                    </h3>
                    <div class="description-content">
                        {{ $product->description }}
                    </div>
                </div>
            @endif

            @if($product->features)
                <div class="info-card" data-aos="fade-left">
                    <h3 class="card-title mb-4">
                        <span class="material-symbols-outlined">star</span>
                        Product Features
                    </h3>
                    <ul class="feature-list">
                        @php
                            $features = array_filter(array_map('trim', explode(',', $product->features)));
                        @endphp
                        @foreach($features as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Additional Information -->
        <div class="info-card" data-aos="fade-up" style="margin-bottom: var(--space-lg);">
            <h3 class="card-title mb-4">
                <span class="material-symbols-outlined">info</span>
                Additional Information
            </h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="info-row">
                        <span class="info-label">
                            <span class="material-symbols-outlined">calendar_today</span>
                            Created
                        </span>
                        <span class="info-value">{{ $product->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-row">
                        <span class="info-label">
                            <span class="material-symbols-outlined">update</span>
                            Updated
                        </span>
                        <span class="info-value">{{ $product->updated_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-row">
                        <span class="info-label">
                            <span class="material-symbols-outlined">person</span>
                            Added By
                        </span>
                        <span class="info-value">{{ $product->wholesaler->name ?? 'System' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="info-card" data-aos="fade-up">
            <div class="action-buttons">
                <a href="{{ route('wholesaler.products.edit', $product->id) }}" class="action-btn primary">
                    <span class="material-symbols-outlined">edit</span>
                    Edit Product
                </a>
                <a href="{{ route('wholesaler.products.index') }}" class="action-btn secondary">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Back to Products
                </a>
                <button onclick="confirmDelete()" class="action-btn danger">
                    <span class="material-symbols-outlined">delete</span>
                    Delete Product
                </button>
            </div>
        </div>
    </div>

    <!-- Floating Action Button -->
    <div class="fab-container">
        <button class="fab" id="fabButton">
            <span class="material-symbols-outlined">more_vert</span>
        </button>
        <div class="fab-menu">
            <button class="fab-item" onclick="shareProduct()">
                <span class="material-symbols-outlined">share</span>
                Share Product
            </button>
            <button class="fab-item" onclick="copyProductLink()">
                <span class="material-symbols-outlined">link</span>
                Copy Link
            </button>
            <button class="fab-item" onclick="window.print()">
                <span class="material-symbols-outlined">print</span>
                Print Details
            </button>
            <button class="fab-item danger" onclick="confirmDelete()">
                <span class="material-symbols-outlined">delete</span>
                Delete Product
            </button>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="deleteForm" action="{{ route('wholesaler.products.destroy', $product->id) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 500,
                once: true,
                offset: 20,
                easing: 'ease-out-cubic'
            });

            // Initialize Toastr
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 3000,
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                preventDuplicates: true
            };

            // Initialize Fancybox
            Fancybox.bind('[data-fancybox]', {
                infinite: true,
                Thumbs: {
                    type: 'modern',
                },
                Toolbar: {
                    display: {
                        left: [],
                        middle: [],
                        right: ['close'],
                    },
                },
            });

            // Show success/error messages
            @if(session('success'))
            toastr.success('{{ session("success") }}', 'Success');
            @endif

            @if(session('error'))
            toastr.error('{{ session("error") }}', 'Error');
            @endif

            @if(session('info'))
            toastr.info('{{ session("info") }}', 'Information');
            @endif

            // Update view count
            updateViewCount();
        });

        // Image Functions
        function changeImage(element) {
            const mainImage = document.getElementById('mainImage');
            const imageSrc = element.getAttribute('data-image');

            if (mainImage && imageSrc) {
                showLoading();

                const img = new Image();
                img.onload = function() {
                    mainImage.src = imageSrc;
                    mainImage.style.opacity = '0';

                    setTimeout(() => {
                        mainImage.style.opacity = '1';
                    }, 100);

                    // Update active thumbnail
                    document.querySelectorAll('.thumbnail-item').forEach(thumb => {
                        thumb.classList.remove('active');
                    });
                    element.classList.add('active');

                    hideLoading();
                    toastr.info('Image updated', 'Gallery');
                };
                img.src = imageSrc;
            }
        }

        function zoomImage() {
            const mainImage = document.getElementById('mainImage');
            if (mainImage && mainImage.src) {
                Fancybox.show([{
                    src: mainImage.src,
                    type: 'image',
                    caption: '{{ $product->name }}'
                }]);
            }
        }

        function downloadImage() {
            const mainImage = document.getElementById('mainImage');
            if (mainImage && mainImage.src) {
                const link = document.createElement('a');
                link.href = mainImage.src;
                link.download = '{{ Str::slug($product->name) }}.jpg';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                toastr.success('Image download started', 'Success');
            }
        }

        // Share Functions
        function shareProduct() {
            const shareData = {
                title: '{{ $product->name }}',
                text: 'Check out this product: {{ $product->name }}',
                url: window.location.href
            };

            if (navigator.share) {
                navigator.share(shareData)
                    .then(() => toastr.success('Shared successfully', 'Share'))
                    .catch(error => {
                        if (error.name !== 'AbortError') {
                            copyProductLink();
                        }
                    });
            } else {
                copyProductLink();
            }
        }

        function copyProductLink() {
            navigator.clipboard.writeText(window.location.href)
                .then(() => toastr.success('Link copied to clipboard', 'Success'))
                .catch(() => {
                    // Fallback
                    const textarea = document.createElement('textarea');
                    textarea.value = window.location.href;
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                    toastr.success('Link copied to clipboard', 'Success');
                });
        }

        // Delete Confirmation
        function confirmDelete() {
            Swal.fire({
                title: 'Delete Product?',
                html: `
                    <div style="text-align: left;">
                        <p style="margin-bottom: 15px;">Are you sure you want to delete:</p>
                        <div style="background: #f1f5f9; padding: 15px; border-radius: 12px; margin: 15px 0; border-left: 4px solid #ef4444;">
                            <div style="font-weight: 600; font-size: 16px; color: #0f172a;">"{{ addslashes($product->name) }}"</div>
                            <div style="font-size: 13px; color: #64748b; margin-top: 5px;">SKU: {{ $product->code }}</div>
                        </div>
                        <p style="color: #ef4444; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                            <span class="material-symbols-outlined" style="font-size: 18px;">warning</span>
                            This action cannot be undone!
                        </p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                    try {
                        const form = document.getElementById('deleteForm');
                        const formData = new FormData(form);

                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (!response.ok) {
                            throw new Error('Failed to delete product');
                        }

                        return await response.json();
                    } catch (error) {
                        Swal.showValidationMessage(`Delete failed: ${error.message}`);
                        return false;
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    toastr.success('Product deleted successfully', 'Success');
                    setTimeout(() => {
                        window.location.href = '{{ route("wholesaler.products.index") }}';
                    }, 1500);
                }
            });
        }

        // Loading Functions
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }

        // Update View Count
        function updateViewCount() {
            const viewElement = document.querySelector('.stat-card:first-child .stat-value');
            if (viewElement) {
                const currentViews = parseInt(viewElement.textContent.replace(/,/g, '') || '0');
                viewElement.textContent = (currentViews + 1).toLocaleString();
            }
        }

        // Keyboard Shortcuts
        document.addEventListener('keydown', function(e) {
            // Don't trigger shortcuts when typing in inputs
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

            // Ctrl/Cmd + P - Print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
                toastr.info('Opening print dialog...', 'Print');
            }

            // Ctrl/Cmd + E - Edit
            if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                e.preventDefault();
                window.location.href = '{{ route("wholesaler.products.edit", $product->id) }}';
            }

            // Ctrl/Cmd + D - Delete (with confirmation)
            if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                confirmDelete();
            }

            // Escape - Close modals
            if (e.key === 'Escape') {
                if (document.querySelector('.swal2-show')) {
                    Swal.close();
                }
                if (Fancybox.getInstance()) {
                    Fancybox.close();
                }
            }
        });

        // Ripple effect for buttons
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.action-btn, .image-action-btn, .fab-item');
            if (btn) {
                createRipple(btn, e);
            }
        });

        function createRipple(element, event) {
            const circle = document.createElement("span");
            const diameter = Math.max(element.clientWidth, element.clientHeight);
            const radius = diameter / 2;

            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${event.clientX - element.getBoundingClientRect().left - radius}px`;
            circle.style.top = `${event.clientY - element.getBoundingClientRect().top - radius}px`;
            circle.classList.add("ripple");

            const ripple = element.getElementsByClassName("ripple")[0];
            if (ripple) {
                ripple.remove();
            }

            element.appendChild(circle);

            setTimeout(() => {
                circle.remove();
            }, 600);
        }

        // Add ripple style
        const style = document.createElement('style');
        style.textContent = `
            .ripple {
                position: absolute;
                border-radius: 50%;
                background-color: rgba(255, 255, 255, 0.7);
                transform: scale(0);
                animation: ripple-animation 0.6s ease-out;
                pointer-events: none;
                z-index: 1;
            }

            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }

            .action-btn, .image-action-btn, .fab-item {
                position: relative;
                overflow: hidden;
            }
        `;
        document.head.appendChild(style);
    </script>
@endpush
