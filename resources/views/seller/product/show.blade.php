@extends('seller.layout.app')

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
            /* Enhanced Google Material Design 3 Colors */
            --primary: #1a73e8;
            --primary-light: #4285f4;
            --primary-dark: #0d47a1;
            --secondary: #34a853;
            --secondary-light: #57bb8a;
            --warning: #fbbc05;
            --warning-light: #fdd663;
            --danger: #ea4335;
            --danger-light: #f28b82;
            --info: #5c6bc0;
            --surface: #ffffff;
            --surface-1: #f8f9fa;
            --surface-2: #f1f3f4;
            --surface-3: #e8eaed;
            --on-surface: #202124;
            --on-surface-variant: #5f6368;
            --outline: #dadce0;

            /* Typography Scale */
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

            /* Spacing */
            --space-xs: 4px;
            --space-sm: 8px;
            --space-md: 16px;
            --space-lg: 24px;
            --space-xl: 32px;
            --space-2xl: 48px;

            /* Border Radius */
            --radius-xs: 4px;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --radius-2xl: 28px;
            --radius-full: 9999px;

            /* Elevation */
            --elevation-1: 0 1px 3px 1px rgba(0, 0, 0, 0.15), 0 1px 2px 0 rgba(0, 0, 0, 0.30);
            --elevation-2: 0 2px 6px 2px rgba(0, 0, 0, 0.15), 0 1px 2px 0 rgba(0, 0, 0, 0.30);
            --elevation-3: 0 4px 8px 3px rgba(0, 0, 0, 0.15), 0 1px 3px 0 rgba(0, 0, 0, 0.30);
            --elevation-4: 0 6px 10px 4px rgba(0, 0, 0, 0.15), 0 2px 3px 0 rgba(0, 0, 0, 0.30);

            /* Transitions */
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 350ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Google Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--on-surface);
            line-height: 1.6;
            font-size: var(--body-medium);
        }

        /* Material Icons */
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .icon-filled .material-symbols-outlined {
            font-variation-settings: 'FILL' 1;
        }

        /* Main Container */
        .main-container {
            width: 100%;
            min-height: 100vh;
            padding: var(--space-md);
            max-width: 1440px;
            margin: 0 auto;
        }

        /* Loading Overlay */
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
            transition: var(--transition-base);
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

        /* Back Navigation */
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
            cursor: pointer;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--elevation-2);
            color: var(--primary);
            border-color: var(--primary-light);
            background: rgba(26, 115, 232, 0.04);
        }

        /* Product Header */
        .product-header {
            background: var(--surface);
            border-radius: var(--radius-xl);
            padding: var(--space-xl);
            margin-bottom: var(--space-lg);
            box-shadow: var(--elevation-2);
            border: 1px solid var(--outline);
            position: relative;
            overflow: hidden;
        }

        .product-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: var(--space-lg);
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
            background: var(--surface-2);
            border-radius: var(--radius-full);
            color: var(--on-surface-variant);
            font-size: var(--label-small);
            border: 1px solid var(--outline);
            transition: all var(--transition-fast);
        }

        .meta-chip:hover {
            background: var(--surface-3);
            color: var(--on-surface);
        }

        .product-title {
            font-size: var(--headline-large);
            font-weight: 600;
            color: var(--on-surface);
            margin-bottom: var(--space-xs);
            line-height: 1.3;
        }

        .product-subtitle {
            color: var(--on-surface-variant);
            font-size: var(--body-large);
            margin-bottom: var(--space-lg);
            line-height: 1.6;
        }

        .header-actions {
            display: flex;
            gap: var(--space-sm);
            flex-shrink: 0;
        }

        /* Status Badge */
        .status-badge {
            position: absolute;
            top: var(--space-lg);
            right: var(--space-xl);
            display: inline-flex;
            align-items: center;
            gap: var(--space-xs);
            padding: var(--space-xs) var(--space-sm);
            border-radius: var(--radius-full);
            font-size: var(--label-small);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 2px solid;
        }

        .status-badge.active {
            background: rgba(52, 168, 83, 0.12);
            color: var(--secondary);
            border-color: rgba(52, 168, 83, 0.24);
        }

        .status-badge.inactive {
            background: rgba(234, 67, 53, 0.12);
            color: var(--danger);
            border-color: rgba(234, 67, 53, 0.24);
        }

        /* Action Button Component */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-sm);
            padding: var(--space-sm) var(--space-md);
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
        }

        .action-btn::after {
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

        .action-btn:focus:not(:active)::after {
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

        .action-btn.primary {
            background: var(--primary);
            color: white;
            box-shadow: var(--elevation-1);
        }

        .action-btn.primary:hover {
            background: var(--primary-dark);
            box-shadow: var(--elevation-2);
            transform: translateY(-2px);
        }

        .action-btn.secondary {
            background: var(--surface);
            color: var(--on-surface-variant);
            border: 1px solid var(--outline);
        }

        .action-btn.secondary:hover {
            background: var(--surface-2);
            color: var(--on-surface);
            border-color: var(--on-surface-variant);
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

        /* Product Content Grid */
        .product-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--space-lg);
            margin-bottom: var(--space-lg);
        }

        /* Image Gallery */
        .image-section {
            background: var(--surface);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--elevation-2);
            border: 1px solid var(--outline);
        }

        .main-image-container {
            position: relative;
            height: 400px;
            overflow: hidden;
            background: var(--surface-1);
        }

        .main-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform var(--transition-slow);
            padding: var(--space-lg);
        }

        .main-image:hover {
            transform: scale(1.02);
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
        }

        .image-action-btn:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: var(--elevation-2);
        }

        .thumbnail-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: var(--space-xs);
            padding: var(--space-md);
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
        }

        .thumbnail-item.active {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.2);
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

        /* Details Section */
        .details-section {
            display: flex;
            flex-direction: column;
            gap: var(--space-lg);
        }

        /* Pricing Card */
        .pricing-card {
            background: var(--surface);
            border-radius: var(--radius-xl);
            padding: var(--space-lg);
            box-shadow: var(--elevation-2);
            border: 1px solid var(--outline);
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
        }

        .discount-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--space-xs);
            padding: var(--space-xs) var(--space-sm);
            background: linear-gradient(135deg, var(--danger) 0%, #d23f31 100%);
            color: white;
            border-radius: var(--radius-full);
            font-size: var(--label-medium);
            font-weight: 600;
        }

        .price-display {
            margin-bottom: var(--space-lg);
        }

        .original-price {
            font-size: var(--body-large);
            color: var(--on-surface-variant);
            text-decoration: line-through;
            margin-bottom: var(--space-xs);
        }

        .current-price {
            font-size: var(--headline-medium);
            font-weight: 700;
            color: var(--on-surface);
            display: flex;
            align-items: baseline;
            gap: var(--space-xs);
        }

        .currency {
            font-size: var(--body-large);
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

        .metric-value.negative {
            color: var(--danger);
        }

        .metric-value.neutral {
            color: var(--warning);
        }

        /* Stock Card */
        .stock-card {
            background: var(--surface);
            border-radius: var(--radius-xl);
            padding: var(--space-lg);
            box-shadow: var(--elevation-2);
            border: 1px solid var(--outline);
        }

        .stock-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--space-xs);
            padding: var(--space-xs) var(--space-sm);
            border-radius: var(--radius-full);
            font-size: var(--label-medium);
            font-weight: 600;
            border: 2px solid;
        }

        .stock-badge.in-stock {
            background: rgba(52, 168, 83, 0.12);
            color: var(--secondary);
            border-color: rgba(52, 168, 83, 0.24);
        }

        .stock-badge.low-stock {
            background: rgba(251, 188, 5, 0.12);
            color: var(--warning);
            border-color: rgba(251, 188, 5, 0.24);
        }

        .stock-badge.out-stock {
            background: rgba(234, 67, 53, 0.12);
            color: var(--danger);
            border-color: rgba(234, 67, 53, 0.24);
        }

        .stock-progress {
            margin: var(--space-lg) 0;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: var(--space-xs);
        }

        .progress-label {
            font-size: var(--label-medium);
            color: var(--on-surface-variant);
        }

        .progress-value {
            font-size: var(--label-medium);
            font-weight: 600;
            color: var(--on-surface);
        }

        .progress-track {
            height: 8px;
            background: var(--surface-3);
            border-radius: var(--radius-full);
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: var(--radius-full);
            transition: width var(--transition-slow) cubic-bezier(0.4, 0, 0.2, 1);
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
        }

        .detail-label {
            font-size: var(--label-small);
            color: var(--on-surface-variant);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: var(--body-medium);
            font-weight: 500;
            color: var(--on-surface);
        }

        /* Quick Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--space-md);
            margin-bottom: var(--space-lg);
        }

        .stat-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: var(--space-md);
            box-shadow: var(--elevation-1);
            border: 1px solid var(--outline);
            transition: all var(--transition-base);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--elevation-2);
            border-color: var(--primary-light);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: var(--space-sm);
            background: var(--surface-2);
            color: var(--primary);
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
        }

        /* Tabs Navigation */
        .tabs-container {
            background: var(--surface);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--elevation-2);
            border: 1px solid var(--outline);
            margin-bottom: var(--space-lg);
        }

        .tabs-header {
            display: flex;
            background: var(--surface-1);
            border-bottom: 1px solid var(--outline);
            overflow-x: auto;
            scrollbar-width: none;
        }

        .tabs-header::-webkit-scrollbar {
            display: none;
        }

        .tab-btn {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            padding: var(--space-md) var(--space-lg);
            background: none;
            border: none;
            font-family: 'Google Sans', sans-serif;
            font-size: var(--body-medium);
            font-weight: 500;
            color: var(--on-surface-variant);
            cursor: pointer;
            position: relative;
            transition: all var(--transition-base);
            white-space: nowrap;
        }

        .tab-btn::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary);
            transform: scaleX(0);
            transition: transform var(--transition-base);
        }

        .tab-btn:hover {
            color: var(--on-surface);
            background: var(--surface-2);
        }

        .tab-btn.active {
            color: var(--primary);
        }

        .tab-btn.active::after {
            transform: scaleX(1);
        }

        .tab-content {
            padding: var(--space-xl);
        }

        .tab-pane {
            display: none;
            animation: fadeIn var(--transition-slow);
        }

        .tab-pane.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Description Content */
        .description-content {
            color: var(--on-surface);
            line-height: 1.8;
        }

        .description-content h3 {
            font-size: var(--title-large);
            font-weight: 600;
            margin: var(--space-lg) 0 var(--space-md);
            color: var(--on-surface);
        }

        .description-content p {
            margin-bottom: var(--space-md);
        }

        .description-content ul, .description-content ol {
            margin-left: var(--space-lg);
            margin-bottom: var(--space-md);
        }

        .description-content li {
            margin-bottom: var(--space-xs);
        }

        /* Specifications Grid */
        .specs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--space-md);
        }

        .spec-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--space-sm) var(--space-md);
            background: var(--surface-1);
            border-radius: var(--radius-md);
            border: 1px solid var(--outline);
            transition: all var(--transition-fast);
        }

        .spec-item:hover {
            background: var(--surface-2);
            border-color: var(--primary-light);
        }

        .spec-label {
            font-weight: 500;
            color: var(--on-surface-variant);
            font-size: var(--body-small);
        }

        .spec-value {
            font-weight: 600;
            color: var(--on-surface);
            font-size: var(--body-medium);
        }

        /* Related Products */
        .related-products {
            margin-bottom: var(--space-lg);
        }

        .section-title {
            font-size: var(--headline-small);
            font-weight: 600;
            margin-bottom: var(--space-md);
            color: var(--on-surface);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: var(--space-md);
        }

        .product-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--elevation-1);
            border: 1px solid var(--outline);
            transition: all var(--transition-base);
            text-decoration: none;
            color: inherit;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--elevation-3);
            border-color: var(--primary-light);
        }

        .product-image {
            height: 180px;
            overflow: hidden;
            background: var(--surface-1);
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow);
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-info {
            padding: var(--space-md);
        }

        .product-name {
            font-size: var(--body-medium);
            font-weight: 600;
            color: var(--on-surface);
            margin-bottom: var(--space-xs);
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: var(--title-medium);
            font-weight: 700;
            color: var(--primary);
            margin-bottom: var(--space-xs);
        }

        .product-meta {
            display: flex;
            gap: var(--space-sm);
            font-size: var(--label-small);
            color: var(--on-surface-variant);
        }

        /* Floating Action Button */
        .fab-container {
            position: fixed;
            bottom: var(--space-lg);
            right: var(--space-lg);
            z-index: 100;
        }

        .fab {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-full);
            background: var(--primary);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--elevation-3);
            transition: all var(--transition-base);
            position: relative;
        }

        .fab:hover {
            transform: translateY(-4px) scale(1.1);
            box-shadow: var(--elevation-4);
            background: var(--primary-dark);
        }

        .fab-menu {
            position: absolute;
            bottom: 68px;
            right: 0;
            display: none;
            flex-direction: column;
            gap: var(--space-xs);
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: var(--space-xs);
            box-shadow: var(--elevation-3);
            border: 1px solid var(--outline);
            min-width: 180px;
        }

        .fab:hover .fab-menu {
            display: flex;
        }

        .fab-item {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            padding: var(--space-sm) var(--space-md);
            background: none;
            border: none;
            border-radius: var(--radius-md);
            color: var(--on-surface);
            font-family: 'Google Sans', sans-serif;
            font-size: var(--body-medium);
            cursor: pointer;
            transition: all var(--transition-fast);
            text-align: left;
        }

        .fab-item:hover {
            background: var(--surface-2);
            color: var(--primary);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .product-grid {
                grid-template-columns: 1fr;
            }

            .main-image-container {
                height: 350px;
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
                font-size: var(--headline-small);
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .price-metrics {
                grid-template-columns: 1fr;
                gap: var(--space-sm);
            }

            .specs-grid {
                grid-template-columns: 1fr;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }

            .fab-container {
                bottom: var(--space-md);
                right: var(--space-md);
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stock-details {
                grid-template-columns: 1fr;
            }

            .thumbnail-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .header-actions {
                width: 100%;
            }

            .action-btn {
                flex: 1;
                min-width: 0;
            }
        }

        /* Print Styles */
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

            .product-header,
            .pricing-card,
            .stock-card,
            .stat-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
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
            <a href="{{ route('seller.product.index') }}" class="back-btn">
                <span class="material-symbols-outlined">arrow_back</span>
                Back to Products
            </a>
        </div>

        <!-- Product Header -->
        <div class="product-header" data-aos="fade-up">
            <!-- Status Badge -->
            <span class="status-badge {{ $product->status }}">
                <span class="material-symbols-outlined">
                    {{ $product->status === 'active' ? 'check_circle' : 'block' }}
                </span>
                {{ ucfirst($product->status) }}
            </span>

            <div class="header-content">
                <div style="flex: 1;">
                    <div class="product-meta">
                        <span class="meta-chip">
                            <span class="material-symbols-outlined">category</span>
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </span>
                        <span class="meta-chip">
                            <span class="material-symbols-outlined">qr_code</span>
                            {{ $product->code }}
                        </span>
                        <span class="meta-chip">
                            <span class="material-symbols-outlined">calendar_month</span>
                            {{ $product->created_at->format('M d, Y') }}
                        </span>
                    </div>

                    <h1 class="product-title">{{ $product->name }}</h1>
                    <p class="product-subtitle">{{ $product->short_description ?? 'No description available' }}</p>
                </div>

                <div class="header-actions">
                    <a href="{{ route('seller.product.edit', $product->id) }}" class="action-btn primary">
                        <span class="material-symbols-outlined">edit</span>
                        Edit
                    </a>
                    <button class="action-btn secondary" onclick="printProduct()">
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
                    <img id="mainImage"
                         src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-product.png') }}"
                         alt="{{ $product->name }}"
                         class="main-image"
                         onerror="this.src='{{ asset('images/default-product.png') }}'">

                    <div class="image-actions">
                        <button class="image-action-btn" onclick="zoomImage()" title="Zoom">
                            <span class="material-symbols-outlined">zoom_in</span>
                        </button>
                        <button class="image-action-btn" onclick="downloadImage()" title="Download">
                            <span class="material-symbols-outlined">download</span>
                        </button>
                    </div>
                </div>

                <div class="thumbnail-grid">
                    @php
                        $images = [$product->image];
                        if ($product->additional_images) {
                            $additional = json_decode($product->additional_images, true);
                            $images = array_merge($images, $additional ?? []);
                        }
                        $images = array_filter($images);
                    @endphp

                    @foreach($images as $index => $image)
                        <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}"
                             data-image="{{ asset('storage/' . $image) }}"
                             onclick="changeImage(this)">
                            <img src="{{ asset('storage/' . $image) }}"
                                 alt="{{ $product->name }} - Image {{ $index + 1 }}"
                                 onerror="this.src='{{ asset('images/default-product.png') }}'">
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Details Section -->
            <div class="details-section" data-aos="fade-left">
                <!-- Pricing Card -->
                <div class="pricing-card">
                    <div class="card-header">
                        <h3 class="card-title">Pricing Details</h3>
                        @if($product->discount > 0)
                            <span class="discount-badge">
                                <span class="material-symbols-outlined">local_offer</span>
                                {{ number_format($product->discount, 0) }}% OFF
                            </span>
                        @endif
                    </div>

                    @php
                        $originalPrice = $product->display_price;
                        $costPrice = $product->cost_price ?? 0;

                        // Calculate final price with discount
                        if ($product->discount > 0) {
                            $finalPrice = $originalPrice - ($originalPrice * $product->discount / 100);
                        } else {
                            $finalPrice = $originalPrice;
                        }

                        // Calculate profit and margin
                        $profit = $finalPrice - $costPrice;
                        $marginPercentage = $costPrice > 0 ? ($profit / $costPrice) * 100 : 0;
                    @endphp

                    <div class="price-display">
                        @if($product->discount > 0)
                            <div class="original-price">
                                <span class="currency">Rs.</span> {{ number_format($originalPrice, 2) }}
                            </div>
                        @endif
                        <div class="current-price">
                            <span class="currency">Rs.</span> {{ number_format($finalPrice, 2) }}
                        </div>
                    </div>

                    <div class="price-metrics">
                        <div class="metric-item">
                            <span class="metric-label">Cost Price</span>
                            <span class="metric-value">
                                Rs. {{ number_format($costPrice, 2) }}
                            </span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Profit</span>
                            <span class="metric-value {{ $profit >= 0 ? 'positive' : 'negative' }}">
                                {{ $profit >= 0 ? '+' : '' }}Rs. {{ number_format($profit, 2) }}
                            </span>
                        </div>
                        <div class="metric-item">
                            <span class="metric-label">Margin</span>
                            <span class="metric-value {{ $marginPercentage >= 0 ? 'positive' : 'negative' }}">
                                {{ number_format($marginPercentage, 2) }}%
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Stock Card -->
                <div class="stock-card">
                    <div class="card-header">
                        <h3 class="card-title">Stock Information</h3>
                        @php
                            $qty = $product->qty ?? 0;
                            $minQty = $product->min_qty ?? 0;
                            $maxQty = $product->max_qty ?? max(100, $qty);

                            $stockStatus = 'in-stock';
                            $stockText = 'In Stock';

                            if ($qty <= 0) {
                                $stockStatus = 'out-stock';
                                $stockText = 'Out of Stock';
                            } elseif ($qty <= $minQty) {
                                $stockStatus = 'low-stock';
                                $stockText = 'Low Stock';
                            }

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
                            <span class="progress-label">Current Stock Level</span>
                            <span class="progress-value">{{ $qty }} / {{ $maxQty }}</span>
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
                            <span class="detail-label">Minimum Qty</span>
                            <span class="detail-value">{{ $minQty }} units</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Maximum Qty</span>
                            <span class="detail-value">{{ $maxQty }} units</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Reorder Level</span>
                            <span class="detail-value">{{ $minQty }} units</span>
                        </div>
                    </div>
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
                <div class="stat-label">Average Rating</div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="tabs-container" data-aos="fade-up">
            <div class="tabs-header">
                <button class="tab-btn active" data-tab="description">
                    <span class="material-symbols-outlined">description</span>
                    Description
                </button>
                <button class="tab-btn" data-tab="specifications">
                    <span class="material-symbols-outlined">tune</span>
                    Specifications
                </button>
                <button class="tab-btn" data-tab="reviews">
                    <span class="material-symbols-outlined">reviews</span>
                    Reviews
                </button>
                <button class="tab-btn" data-tab="activity">
                    <span class="material-symbols-outlined">history</span>
                    Activity
                </button>
                <button class="tab-btn" data-tab="analytics">
                    <span class="material-symbols-outlined">analytics</span>
                    Analytics
                </button>
            </div>

            <div class="tab-content">
                <!-- Description Tab -->
                <div class="tab-pane active" id="description">
                    <div class="description-content">
                        @if($product->description)
                            {!! $product->description !!}
                        @else
                            <div style="text-align: center; padding: 40px; color: var(--on-surface-variant);">
                                <span class="material-symbols-outlined" style="font-size: 48px; margin-bottom: 16px;">
                                    description
                                </span>
                                <p>No description available for this product.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Specifications Tab -->
                <div class="tab-pane" id="specifications">
                    <div class="specs-grid">
                        @php
                            $specs = [
                                'Brand' => $product->brand->name ?? 'Not specified',
                                'Weight' => $product->weight ? $product->weight . ' kg' : 'Not specified',
                                'Dimensions' => ($product->length && $product->width && $product->height)
                                    ? $product->length . '×' . $product->width . '×' . $product->height . ' cm'
                                    : 'Not specified',
                                'Material' => $product->material ?? 'Not specified',
                                'Color' => $product->color ?? 'Not specified',
                                'Warranty' => $product->warranty ?? 'No warranty',
                                'Origin' => $product->origin_country ?? 'Not specified',
                                'Manufacturer' => $product->manufacturer ?? 'Not specified',
                            ];

                            // Add custom specifications from JSON field
                            if ($product->specifications) {
                                $customSpecs = json_decode($product->specifications, true);
                                if (is_array($customSpecs)) {
                                    $specs = array_merge($specs, $customSpecs);
                                }
                            }
                        @endphp

                        @foreach($specs as $key => $value)
                            <div class="spec-item">
                                <span class="spec-label">{{ $key }}</span>
                                <span class="spec-value">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane" id="reviews">
                    @php
                        $reviews = [
                            ['rating' => 5, 'count' => 65, 'percentage' => 65],
                            ['rating' => 4, 'count' => 20, 'percentage' => 20],
                            ['rating' => 3, 'count' => 10, 'percentage' => 10],
                            ['rating' => 2, 'count' => 3, 'percentage' => 3],
                            ['rating' => 1, 'count' => 2, 'percentage' => 2],
                        ];
                        $averageRating = 4.5;
                        $totalReviews = array_sum(array_column($reviews, 'count'));
                    @endphp

                    <div style="max-width: 600px; margin: 0 auto;">
                        <div style="display: flex; align-items: center; gap: 32px; margin-bottom: 32px;">
                            <div style="text-align: center;">
                                <div style="font-size: 48px; font-weight: 700; color: var(--on-surface);">
                                    {{ number_format($averageRating, 1) }}
                                </div>
                                <div style="color: var(--warning); font-size: 20px; margin: 4px 0;">
                                    ★★★★★
                                </div>
                                <div style="color: var(--on-surface-variant); font-size: 14px;">
                                    {{ $totalReviews }} reviews
                                </div>
                            </div>
                            <div style="flex: 1;">
                                @foreach($reviews as $review)
                                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                        <div style="width: 60px; font-size: 14px; color: var(--on-surface-variant);">
                                            {{ $review['rating'] }} stars
                                        </div>
                                        <div style="flex: 1; height: 8px; background: var(--surface-3); border-radius: 4px; overflow: hidden;">
                                            <div style="width: {{ $review['percentage'] }}%; height: 100%; background: var(--warning); border-radius: 4px;"></div>
                                        </div>
                                        <div style="width: 40px; font-size: 14px; color: var(--on-surface-variant); text-align: right;">
                                            {{ $review['count'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Tab -->
                <div class="tab-pane" id="activity">
                    <div style="max-width: 600px;">
                        @php
                            $activities = [
                                ['time' => 'Today, 10:30 AM', 'action' => 'Price updated to Rs. ' . number_format($finalPrice, 2)],
                                ['time' => 'Yesterday, 3:45 PM', 'action' => 'Stock level adjusted to ' . $qty . ' units'],
                                ['time' => '2 days ago', 'action' => 'Product status changed to ' . $product->status],
                                ['time' => '1 week ago', 'action' => 'Discount applied: ' . $product->discount . '%'],
                                ['time' => $product->created_at->format('M d, Y'), 'action' => 'Product added to inventory'],
                            ];
                        @endphp

                        @foreach($activities as $activity)
                            <div style="display: flex; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--outline);">
                                <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--surface-2); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                    <span class="material-symbols-outlined">history</span>
                                </div>
                                <div style="flex: 1;">
                                    <div style="font-weight: 500; color: var(--on-surface); margin-bottom: 4px;">
                                        {{ $activity['action'] }}
                                    </div>
                                    <div style="font-size: 12px; color: var(--on-surface-variant);">
                                        {{ $activity['time'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Analytics Tab -->
                <div class="tab-pane" id="analytics">
                    <div style="max-width: 800px;">
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px;">
                            <div style="background: var(--surface-1); border-radius: 12px; padding: 16px; text-align: center;">
                                <div style="font-size: 24px; font-weight: 600; color: var(--on-surface);">24</div>
                                <div style="font-size: 12px; color: var(--on-surface-variant); text-transform: uppercase; letter-spacing: 0.5px;">Views Today</div>
                            </div>
                            <div style="background: var(--surface-1); border-radius: 12px; padding: 16px; text-align: center;">
                                <div style="font-size: 24px; font-weight: 600; color: var(--on-surface);">12</div>
                                <div style="font-size: 12px; color: var(--on-surface-variant); text-transform: uppercase; letter-spacing: 0.5px;">Sales This Month</div>
                            </div>
                            <div style="background: var(--surface-1); border-radius: 12px; padding: 16px; text-align: center;">
                                <div style="font-size: 24px; font-weight: 600; color: var(--on-surface);">78%</div>
                                <div style="font-size: 12px; color: var(--on-surface-variant); text-transform: uppercase; letter-spacing: 0.5px;">Conversion Rate</div>
                            </div>
                        </div>

                        <div style="background: var(--surface-1); border-radius: 12px; padding: 24px; margin-bottom: 16px;">
                            <div style="font-weight: 600; color: var(--on-surface); margin-bottom: 16px;">Performance Metrics</div>
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                                <div>
                                    <div style="font-size: 12px; color: var(--on-surface-variant); margin-bottom: 4px;">Inventory Turnover</div>
                                    <div style="font-weight: 600; color: var(--on-surface);">2.5x</div>
                                </div>
                                <div>
                                    <div style="font-size: 12px; color: var(--on-surface-variant); margin-bottom: 4px;">Stock Out Days</div>
                                    <div style="font-weight: 600; color: var(--on-surface);">0 days</div>
                                </div>
                                <div>
                                    <div style="font-size: 12px; color: var(--on-surface-variant); margin-bottom: 4px;">Average Sale/Day</div>
                                    <div style="font-weight: 600; color: var(--on-surface);">0.4 units</div>
                                </div>
                                <div>
                                    <div style="font-size: 12px; color: var(--on-surface-variant); margin-bottom: 4px;">Return Rate</div>
                                    <div style="font-weight: 600; color: var(--on-surface);">2.5%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="related-products" data-aos="fade-up">
                <h2 class="section-title">
                    <span class="material-symbols-outlined">widgets</span>
                    Related Products
                </h2>
                <div class="products-grid">
                    @foreach($relatedProducts->take(4) as $relatedProduct)
                        <a href="{{ route('seller.product.show', $relatedProduct->id) }}" class="product-card">
                            <div class="product-image">
                                <img src="{{ $relatedProduct->image ? asset('storage/' . $relatedProduct->image) : asset('images/default-product.png') }}"
                                     alt="{{ $relatedProduct->name }}"
                                     onerror="this.src='{{ asset('images/default-product.png') }}'">
                            </div>
                            <div class="product-info">
                                <h4 class="product-name">{{ $relatedProduct->name }}</h4>
                                <div class="product-price">Rs. {{ number_format($relatedProduct->display_price, 2) }}</div>
                                <div class="product-meta">
                                    <span>{{ $relatedProduct->category->name ?? 'Uncategorized' }}</span>
                                    <span>Stock: {{ $relatedProduct->qty }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Floating Action Button -->
    <div class="fab-container">
        <button class="fab" id="fabButton">
            <span class="material-symbols-outlined">more_vert</span>
        </button>
        <div class="fab-menu">
            <button class="fab-item" onclick="downloadProductInfo()">
                <span class="material-symbols-outlined">download</span>
                Download Info
            </button>
            <button class="fab-item" onclick="shareProduct()">
                <span class="material-symbols-outlined">share</span>
                Share Product
            </button>
            <button class="fab-item" onclick="duplicateProduct()">
                <span class="material-symbols-outlined">content_copy</span>
                Duplicate
            </button>
            <button class="fab-item danger" onclick="confirmDelete()">
                <span class="material-symbols-outlined">delete</span>
                Delete Product
            </button>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS animations
            AOS.init({
                duration: 400,
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
                closeMethod: 'slideUp',
                closeDuration: 300,
                closeEasing: 'swing',
                newestOnTop: true
            };

            // Check for success/error messages
            @if(session('success'))
            toastr.success('{{ session("success") }}', 'Success');
            @endif

            @if(session('error'))
            toastr.error('{{ session("error") }}', 'Error');
            @endif

            @if(session('info'))
            toastr.info('{{ session("info") }}', 'Info');
            @endif

            // Initialize image gallery
            initImageGallery();

            // Initialize tabs
            initTabs();

            // Initialize Fancybox for image zoom
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

            // Initialize real-time updates
            initRealTimeUpdates();

            // Initialize keyboard shortcuts
            initKeyboardShortcuts();

            // Initialize analytics chart
            initAnalyticsChart();

            // Update view count
            updateViewCount();

            // Show welcome message if first visit
            if (!localStorage.getItem('productPageVisited')) {
                setTimeout(() => {
                    toastr.info('👋 Welcome to Product Details! Use FAB button for quick actions.', 'Tip');
                    localStorage.setItem('productPageVisited', 'true');
                }, 1000);
            }
        });

        // Image Gallery Functions
        function initImageGallery() {
            const mainImage = document.getElementById('mainImage');
            const thumbnails = document.querySelectorAll('.thumbnail-item');
            let currentIndex = 0;

            function updateMainImage(src) {
                if (mainImage) {
                    // Add fade out effect
                    mainImage.style.opacity = '0';
                    mainImage.style.transform = 'scale(0.95)';

                    setTimeout(() => {
                        mainImage.src = src;
                        mainImage.style.opacity = '1';
                        mainImage.style.transform = 'scale(1)';
                    }, 200);
                }
            }

            function updateActiveThumbnail(index) {
                thumbnails.forEach((thumb, i) => {
                    thumb.classList.toggle('active', i === index);
                });
            }

            // Thumbnail click event
            thumbnails.forEach((thumb, index) => {
                thumb.addEventListener('click', function() {
                    const imageSrc = this.getAttribute('data-image');
                    updateMainImage(imageSrc);
                    updateActiveThumbnail(index);
                    currentIndex = index;
                });
            });

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft' && currentIndex > 0) {
                    const prevThumb = thumbnails[currentIndex - 1];
                    updateMainImage(prevThumb.getAttribute('data-image'));
                    updateActiveThumbnail(currentIndex - 1);
                    currentIndex--;
                } else if (e.key === 'ArrowRight' && currentIndex < thumbnails.length - 1) {
                    const nextThumb = thumbnails[currentIndex + 1];
                    updateMainImage(nextThumb.getAttribute('data-image'));
                    updateActiveThumbnail(currentIndex + 1);
                    currentIndex++;
                }
            });

            // Auto-rotate images (optional)
            /* setInterval(() => {
                if (thumbnails.length > 1) {
                    currentIndex = (currentIndex + 1) % thumbnails.length;
                    const nextThumb = thumbnails[currentIndex];
                    updateMainImage(nextThumb.getAttribute('data-image'));
                    updateActiveThumbnail(currentIndex);
                }
            }, 5000); */
        }

        // Tab Functions
        function initTabs() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');

            function switchTab(tabId) {
                // Update active tab button
                tabBtns.forEach(btn => {
                    btn.classList.toggle('active', btn.getAttribute('data-tab') === tabId);
                });

                // Show active tab pane
                tabPanes.forEach(pane => {
                    pane.classList.toggle('active', pane.id === tabId);
                });

                // Load chart for analytics tab
                if (tabId === 'analytics') {
                    setTimeout(initAnalyticsChart, 300);
                }
            }

            // Tab button click event
            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    switchTab(tabId);

                    // Add animation effect
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });

            // Hash-based navigation
            if (window.location.hash) {
                const tabId = window.location.hash.substring(1);
                if (document.getElementById(tabId)) {
                    switchTab(tabId);
                }
            }
        }

        // Analytics Chart
        function initAnalyticsChart() {
            const canvas = document.createElement('canvas');
            canvas.id = 'analyticsChart';
            canvas.style.maxWidth = '100%';
            canvas.style.height = '300px';

            const analyticsTab = document.getElementById('analytics');
            if (analyticsTab && !analyticsTab.querySelector('canvas')) {
                analyticsTab.appendChild(canvas);

                const ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'Sales',
                            data: [12, 19, 8, 15, 22, 18],
                            borderColor: '#1a73e8',
                            backgroundColor: 'rgba(26, 115, 232, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'Views',
                            data: [45, 52, 38, 65, 72, 60],
                            borderColor: '#34a853',
                            backgroundColor: 'rgba(52, 168, 83, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#202124',
                                    font: {
                                        family: 'Google Sans, sans-serif',
                                        size: 12
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    color: '#5f6368',
                                    font: {
                                        family: 'Google Sans, sans-serif',
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                ticks: {
                                    color: '#5f6368',
                                    font: {
                                        family: 'Google Sans, sans-serif',
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Image Actions
        function zoomImage() {
            const mainImage = document.getElementById('mainImage');
            if (mainImage) {
                Fancybox.show([{
                    src: mainImage.src,
                    type: 'image'
                }]);
            }
        }

        function downloadImage() {
            const mainImage = document.getElementById('mainImage');
            if (mainImage) {
                const link = document.createElement('a');
                link.href = mainImage.src;
                link.download = '{{ Str::slug($product->name) }}.jpg';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                toastr.success('Image download started', 'Success');
            }
        }

        function changeImage(element) {
            const mainImage = document.getElementById('mainImage');
            const imageSrc = element.getAttribute('data-image');

            if (mainImage && imageSrc) {
                // Add loading effect
                showLoading();

                // Preload image
                const img = new Image();
                img.onload = function() {
                    mainImage.src = imageSrc;

                    // Update active thumbnail
                    document.querySelectorAll('.thumbnail-item').forEach(thumb => {
                        thumb.classList.remove('active');
                    });
                    element.classList.add('active');

                    hideLoading();
                };
                img.src = imageSrc;
            }
        }

        // Delete Product with confirmation
        function confirmDelete() {
            Swal.fire({
                title: 'Delete Product?',
                html: `
                    <div style="text-align: left; margin: 20px 0;">
                        <p style="margin-bottom: 10px;">Are you sure you want to delete:</p>
                        <div style="background: var(--surface-1); padding: 12px; border-radius: 8px; margin: 10px 0;">
                            <div style="font-weight: 600; color: var(--on-surface);">"{{ addslashes($product->name) }}"</div>
                            <div style="font-size: 12px; color: var(--on-surface-variant); margin-top: 4px;">SKU: {{ $product->code }}</div>
                        </div>
                        <div style="color: var(--danger); font-size: 13px; margin-top: 15px;">
                            <span class="material-symbols-outlined" style="vertical-align: middle; font-size: 16px;">warning</span>
                            This action cannot be undone and will permanently delete all product data.
                        </div>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea4335',
                cancelButtonColor: '#5f6368',
                confirmButtonText: '<span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 6px;">delete</span> Delete Product',
                cancelButtonText: '<span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 6px;">cancel</span> Cancel',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                    try {
                        const response = await fetch('{{ route("seller.product.destroy", $product->id) }}', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            const error = await response.json();
                            throw new Error(error.message || 'Failed to delete product');
                        }

                        return await response.json();
                    } catch (error) {
                        Swal.showValidationMessage(`Delete failed: ${error.message}`);
                        return false;
                    }
                },
                allowOutsideClick: () => !Swal.isLoading(),
                backdrop: true,
                background: 'var(--surface)',
                customClass: {
                    popup: 'sweet-popup',
                    title: 'sweet-title',
                    htmlContainer: 'sweet-html',
                    confirmButton: 'sweet-confirm',
                    cancelButton: 'sweet-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    toastr.success(result.value?.message || 'Product deleted successfully', 'Success');

                    // Redirect after delay
                    setTimeout(() => {
                        window.location.href = '{{ route("seller.product.index") }}';
                    }, 1500);
                }
            });
        }

        // Print Product Details
        function printProduct() {
            // Add print styles
            const style = document.createElement('style');
            style.textContent = `
                @media print {
                    body * {
                        visibility: hidden;
                    }
                    .main-container, .main-container * {
                        visibility: visible;
                    }
                    .main-container {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        padding: 0;
                    }
                    .fab-container,
                    .header-actions,
                    .image-actions,
                    .back-btn,
                    .tabs-header {
                        display: none !important;
                    }
                    .product-header {
                        box-shadow: none;
                        border: 1px solid #ddd;
                    }
                    .pricing-card,
                    .stock-card,
                    .stat-card,
                    .image-section,
                    .tabs-container {
                        box-shadow: none;
                        border: 1px solid #ddd;
                        page-break-inside: avoid;
                    }
                }
            `;
            document.head.appendChild(style);

            // Trigger print
            window.print();

            // Remove print styles after printing
            setTimeout(() => {
                document.head.removeChild(style);
            }, 100);

            toastr.info('Print dialog opened', 'Print');
        }

        // Share Product
        function shareProduct() {
            const shareData = {
                title: '{{ $product->name }}',
                text: 'Check out this product: {{ $product->name }}',
                url: window.location.href
            };

            if (navigator.share && navigator.canShare(shareData)) {
                navigator.share(shareData)
                    .then(() => toastr.success('Product shared successfully', 'Shared'))
                    .catch(error => {
                        if (error.name !== 'AbortError') {
                            copyToClipboard(window.location.href);
                        }
                    });
            } else {
                copyToClipboard(window.location.href);
            }
        }

        // Copy to Clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text)
                .then(() => toastr.success('Link copied to clipboard', 'Copied'))
                .catch(() => {
                    // Fallback for older browsers
                    const textarea = document.createElement('textarea');
                    textarea.value = text;
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                    toastr.success('Link copied to clipboard', 'Copied');
                });
        }

        // Download Product Info
        function downloadProductInfo() {
            showLoading();

            // Create product info text
            const productInfo = `
PRODUCT INFORMATION
===================

Name: {{ $product->name }}
            SKU: {{ $product->code }}
            Category: {{ $product->category->name ?? 'Uncategorized' }}
            Brand: {{ $product->brand->name ?? 'Not specified' }}
            Status: {{ ucfirst($product->status) }}

            PRICING
            =======
            Original Price: Rs. {{ number_format($product->display_price, 2) }}
            @if($product->discount > 0)
            Discount: {{ $product->discount }}%
Final Price: Rs. {{ number_format($finalPrice, 2) }}
            @endif
            Cost Price: Rs. {{ number_format($product->cost_price ?? 0, 2) }}
            Profit: Rs. {{ number_format($profit, 2) }}
            Margin: {{ number_format($marginPercentage, 2) }}%

STOCK INFORMATION
=================
Current Stock: {{ $qty }} units
Minimum Qty: {{ $minQty }} units
Maximum Qty: {{ $maxQty }} units
Stock Status: {{ $stockText }}

            DESCRIPTION
            ===========
{{ strip_tags($product->description ?? 'No description') }}

            Generated on: ${new Date().toLocaleString()}
            `.trim();

            // Create and trigger download
            const blob = new Blob([productInfo], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = '{{ Str::slug($product->name) }}-info.txt';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);

            hideLoading();
            toastr.success('Product info downloaded', 'Success');
        }

        // Duplicate Product
        function duplicateProduct() {
            Swal.fire({
                title: 'Duplicate Product?',
                text: 'This will create a copy of this product with the same details.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1a73e8',
                cancelButtonColor: '#5f6368',
                confirmButtonText: '<span class="material-symbols-outlined" style="vertical-align: middle; margin-right: 6px;">content_copy</span> Duplicate',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                    try {
                        {{--const response = await fetch('{{ route("seller.product.duplicate", $product->id) }}', {--}}
                            {{--    method: 'POST',--}}
                            {{--    headers: {--}}
                            {{--        'X-CSRF-TOKEN': '{{ csrf_token() }}',--}}
                            {{--        'Content-Type': 'application/json'--}}
                            {{--    }--}}
                            {{--});--}}

                        if (!response.ok) {
                            throw new Error('Failed to duplicate product');
                        }

                        return await response.json();
                    } catch (error) {
                        Swal.showValidationMessage(`Duplicate failed: ${error.message}`);
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    toastr.success('Product duplicated successfully', 'Success');
                    // Redirect to the new product
                    if (result.value && result.value.redirect_url) {
                        setTimeout(() => {
                            window.location.href = result.value.redirect_url;
                        }, 1500);
                    }
                }
            });
        }

        // Real-time Updates
        function initRealTimeUpdates() {
            // Simulate real-time stock updates
            setInterval(() => {
                updateStockBadge();
            }, 30000); // Update every 30 seconds
        }

        function updateStockBadge() {
            // This would typically fetch from an API
            // For demo, we'll simulate small random changes
            const stockBadge = document.querySelector('.stock-badge');
            const progressFill = document.querySelector('.progress-fill');

            if (stockBadge && progressFill) {
                // Small animation to show update
                stockBadge.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    stockBadge.style.transform = '';
                }, 300);
            }
        }

        // Update View Count
        function updateViewCount() {
            // Simulate view count update
            const viewCountElement = document.querySelector('.stat-card:nth-child(1) .stat-value');
            if (viewCountElement) {
                const currentViews = parseInt(viewCountElement.textContent.replace(/,/g, ''));
                viewCountElement.textContent = (currentViews + 1).toLocaleString();
            }
        }

        // Keyboard Shortcuts
        function initKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                // Don't trigger shortcuts when user is typing
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

                // Ctrl/Cmd + P - Print
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    printProduct();
                }

                // Ctrl/Cmd + E - Edit
                if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                    e.preventDefault();
                    window.location.href = '{{ route("seller.product.edit", $product->id) }}';
                }

                // Ctrl/Cmd + D - Delete (with confirmation)
                if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                    e.preventDefault();
                    confirmDelete();
                }

                // Ctrl/Cmd + S - Share
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    shareProduct();
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
        }

        // Loading Functions
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }

        // Add ripple effect to buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.action-btn, .image-action-btn, .tab-btn')) {
                const btn = e.target.closest('.action-btn, .image-action-btn, .tab-btn');
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
        }

        // Add CSS for ripple effect
        const rippleStyle = document.createElement('style');
        rippleStyle.textContent = `
            .ripple {
                position: absolute;
                border-radius: 50%;
                background-color: rgba(255, 255, 255, 0.7);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
            }

            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }

            .action-btn, .image-action-btn, .tab-btn {
                position: relative;
                overflow: hidden;
            }
        `;
        document.head.appendChild(rippleStyle);
    </script>
@endpush
