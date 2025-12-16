@extends('wholesaler.layouts.app')
@push('title')
    {{ $shop->shop_name ?? 'My Shop' }} | Wholesale Marketplace
@endpush

@push('css')
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <link href="https://unpkg.com/lucide-static@latest/font/lucide.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #8b5cf6;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1e293b;
            --darker: #0f172a;
            --light: #f8fafc;
            --lighter: #f1f5f9;
            --border: #e2e8f0;
            --border-dark: #cbd5e1;
            --surface: #ffffff;
            --surface-dark: #f1f5f9;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --transition-fast: 150ms ease;
            --transition-normal: 300ms ease;
            --transition-slow: 500ms ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--lighter);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--dark);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--darker);
        }

        /* ENHANCED SHOP HERO SECTION */
        .shop-hero {
            position: relative;
            background: linear-gradient(135deg,
            rgba(99, 102, 241, 0.95) 0%,
            rgba(139, 92, 246, 0.95) 50%,
            rgba(6, 182, 212, 0.9) 100%);
            padding: 5rem 0 3rem;
            margin-bottom: 3rem;
            overflow: hidden;
            min-height: 120px;
        }

        .shop-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            z-index: 1;
        }

        .shop-hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to top, var(--lighter), transparent);
            z-index: 2;
        }

        .hero-content {
            position: relative;
            z-index: 3;
        }

        /* Shop Logo with Enhanced Effects */
        .shop-logo-container {
            position: relative;
            width: 160px;
            height: 160px;
            margin: 0 auto 2rem;
        }

        .shop-logo-wrapper {
            width: 100%;
            height: 100%;
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.25),
                0 0 0 5px rgba(255, 255, 255, 0.1),
                0 0 0 10px rgba(255, 255, 255, 0.05);
            transition: all var(--transition-normal);
            animation: float 3s ease-in-out infinite;
        }

        .shop-logo-wrapper:hover {
            transform: scale(1.05) rotate(2deg);
            box-shadow:
                0 30px 60px rgba(0, 0, 0, 0.35),
                0 0 0 8px rgba(255, 255, 255, 0.15),
                0 0 0 15px rgba(255, 255, 255, 0.05);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .shop-logo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-normal);
        }

        .shop-logo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            font-size: 3.5rem;
        }

        .logo-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-lg);
            z-index: 4;
            animation: pulse 2s infinite;
        }

        /* Enhanced Shop Info Card */
        .shop-info-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: var(--radius-xl);
            padding: 2.5rem;
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 3;
            transform: translateY(60px);
            margin-top: -30px;
        }

        .shop-info-card::before {
            content: '';
            position: absolute;
            inset: 1px;
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.85) 100%);
            border-radius: calc(var(--radius-xl) - 1px);
            z-index: -1;
        }

        .shop-info-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg,
            var(--primary) 0%,
            var(--secondary) 50%,
            var(--accent) 100%);
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
        }

        .shop-title {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .shop-description {
            color: var(--dark);
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }

        .shop-stats {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid rgba(0, 0, 0, 0.05);
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--dark);
            opacity: 0.7;
            font-weight: 500;
        }

        .shop-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .meta-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--primary);
            transition: all var(--transition-fast);
        }

        .meta-badge:hover {
            background: rgba(99, 102, 241, 0.15);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .meta-badge i {
            font-size: 0.9rem;
        }

        .meta-badge.verified {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }

        .meta-badge.rating {
            background: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.2);
            color: var(--warning);
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .hero-btn {
            padding: 0.875rem 2rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all var(--transition-normal);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .hero-btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: var(--shadow-lg);
        }

        .hero-btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.3);
        }

        .hero-btn-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
            box-shadow: var(--shadow);
        }

        .hero-btn-secondary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        /* Floating Cart */
        .cart-floating {
            position: fixed;
            bottom: 2.5rem;
            right: 2.5rem;
            z-index: 9999;
        }

        .cart-floating-btn {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border: 3px solid white;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: var(--shadow-xl);
            transition: all var(--transition-normal);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .cart-floating-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 30%, rgba(255,255,255,0.2) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform var(--transition-slow);
        }

        .cart-floating-btn:hover {
            transform: scale(1.15) rotate(5deg);
            box-shadow: 0 30px 60px rgba(99, 102, 241, 0.4);
        }

        .cart-floating-btn:hover::before {
            transform: translateX(100%);
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
            font-size: 0.75rem;
            font-weight: 800;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid white;
            animation: pulse 2s infinite;
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }

        /* PRODUCT CARD */
        .product-card {
            width: 293px;
            height: 492px;
            background: var(--surface);
            border-radius: var(--radius-lg);
            transition: all var(--transition-normal) cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
        }

        .product-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .product-card:hover .card-img {
            transform: scale(1.12);
        }

        .product-card:hover::after {
            opacity: 1;
        }

        .product-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.03) 0%, transparent 100%);
            opacity: 0;
            transition: opacity var(--transition-normal);
            pointer-events: none;
        }

        .card-img-container {
            width: 100%;
            height: 130px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--lighter) 0%, var(--border) 100%);
            border-bottom: 1px solid var(--border);
        }

        .card-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 1.5rem;
            transition: transform 0.6s ease;
        }

        .card-badges {
            position: absolute;
            top: 10px;
            left: 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            z-index: 2;
        }

        .badge {
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            line-height: 1;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 4px;
            animation: badge-appear 0.4s ease-out;
        }

        @keyframes badge-appear {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .badge-new {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
        }

        .badge-hot {
            background: linear-gradient(135deg, var(--danger) 0%, #dc2626 100%);
            color: white;
        }

        .badge-discount {
            background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
            color: white;
        }

        .badge-wholesale {
            background: linear-gradient(135deg, var(--accent) 0%, #0891b2 100%);
            color: white;
        }

        .badge-premium {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .card-content {
            padding: 16px;
            height: calc(370px - 130px);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .card-category {
            font-size: 0.72rem;
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: flex;
            align-items: center;
            gap: 6px;
            opacity: 0.9;
        }

        .card-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--darker);
            margin-bottom: 8px;
            line-height: 1.4;
            height: 40px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            transition: color var(--transition-fast);
        }

        .card-title a {
            color: inherit;
            text-decoration: none;
        }

        .card-title a:hover {
            color: var(--primary);
        }

        .card-rating {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
        }

        .rating-stars {
            display: flex;
            gap: 2px;
        }

        .rating-count {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
        }

        .product-pricing {
            margin-bottom: 10px;
        }

        .price-original {
            font-size: 0.85rem;
            color: #9ca3af;
            text-decoration: line-through;
            display: inline-block;
            margin-right: 8px;
            font-weight: 500;
        }

        .price-display {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary);
            display: block;
            line-height: 1.2;
        }

        .profit-margin {
            font-size: 0.75rem;
            margin-top: 2px;
            font-weight: 600;
        }

        .card-stock {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            background: var(--lighter);
            padding: 6px 10px;
            border-radius: 6px;
        }

        .stock-indicator {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .stock-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            position: relative;
        }

        .stock-dot::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .stock-dot.in-stock {
            background: var(--success);
        }
        .stock-dot.in-stock::after {
            background: var(--success);
            opacity: 0.3;
        }

        .stock-dot.low-stock {
            background: var(--warning);
        }
        .stock-dot.low-stock::after {
            background: var(--warning);
            opacity: 0.3;
        }

        .stock-dot.out-stock {
            background: var(--danger);
        }
        .stock-dot.out-stock::after {
            background: var(--danger);
            opacity: 0.3;
        }

        .stock-text {
            font-size: 0.78rem;
            color: var(--dark);
            font-weight: 600;
        }

        .smart-quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            background: var(--lighter);
            padding: 6px;
            border-radius: 8px;
            border: 1px solid var(--border);
            transition: border-color var(--transition-fast);
        }

        .smart-quantity-controls:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .smart-quantity-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            background: white;
            color: var(--dark);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all var(--transition-fast);
            box-shadow: var(--shadow-sm);
            font-weight: 700;
            font-size: 1rem;
        }

        .smart-quantity-btn:hover:not(:disabled) {
            background: var(--primary);
            color: white;
            transform: translateY(-1px);
            box-shadow: var(--shadow);
        }

        .smart-quantity-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none !important;
        }

        .smart-quantity-input {
            width: 46px;
            height: 32px;
            text-align: center;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: white;
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--dark);
            transition: all var(--transition-fast);
            font-family: 'Inter', sans-serif;
        }

        .smart-quantity-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.15);
        }

        .total-price-display {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, transparent 100%);
            border-radius: 6px;
            padding: 6px 10px;
            margin-bottom: 8px;
            border: 1px solid rgba(99, 102, 241, 0.1);
        }

        .card-actions {
            margin-top: auto;
            display: flex;
            gap: 8px;
        }

        .btn-card {
            flex: 1;
            padding: 10px 12px;
            border: none;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-card-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: var(--shadow);
        }

        .btn-card-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
        }

        .btn-card-outline {
            background: transparent;
            border: 2px solid var(--border);
            color: var(--dark);
        }

        .btn-card-outline:hover {
            background: var(--lighter);
            border-color: var(--primary);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .btn-card:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        /* Enhanced Filter */
        .enhanced-filter {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 1.75rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 2rem;
            border: 1px solid var(--border);
            transition: all var(--transition-normal);
            position: sticky;
            top: 20px;
        }

        .enhanced-filter:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-lg);
        }

        .filter-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--darker);
            margin-bottom: 1.25rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .smart-search {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .smart-search input {
            width: 100%;
            padding: 12px 20px 12px 45px;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all var(--transition-fast);
            background: var(--lighter);
            font-family: 'Inter', sans-serif;
        }

        .smart-search input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            background: white;
        }

        .smart-search i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
        }

        .view-toggle-section {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
        }

        .view-toggle {
            display: flex;
            gap: 8px;
            background: var(--lighter);
            padding: 6px;
            border-radius: 10px;
            width: fit-content;
        }

        .view-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: var(--dark);
            cursor: pointer;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .view-btn:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
        }

        .view-btn.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 2rem;
            padding: 1rem 0;
        }

        .smart-notification {
            position: fixed;
            top: 25px;
            right: 25px;
            background: var(--surface);
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: var(--shadow-xl);
            z-index: 99999;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInRight 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            max-width: 380px;
            border-left: 5px solid var(--primary);
            border-top: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
        }

        .smart-notification.success {
            border-left-color: var(--success);
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, var(--surface) 100%);
        }

        .smart-notification.error {
            border-left-color: var(--danger);
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.05) 0%, var(--surface) 100%);
        }

        .smart-notification.warning {
            border-left-color: var(--warning);
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.05) 0%, var(--surface) 100%);
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%) translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateX(0) translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0) translateY(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%) translateY(-20px);
                opacity: 0;
            }
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 3rem;
        }

        .page-link {
            padding: 10px 16px;
            border: 2px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            color: var(--dark);
            text-decoration: none;
            font-weight: 700;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
        }

        .page-link:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .page-link.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border-color: var(--primary);
            box-shadow: var(--shadow);
        }

        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-normal);
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid var(--border);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--surface);
            border-radius: var(--radius-lg);
            border: 2px dashed var(--border);
            margin: 2rem 0;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: var(--border-dark);
            margin-bottom: 1.5rem;
            opacity: 0.6;
        }

        .quick-view-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            z-index: 99998;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            opacity: 0;
            transition: opacity var(--transition-normal);
        }

        .quick-view-modal.active {
            display: flex;
            opacity: 1;
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Responsive Design */
        @media (max-width: 1400px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 1.75rem;
            }
            .product-card {
                width: 200px;
                height: 360px;
            }
        }

        @media (max-width: 1200px) {
            .shop-logo-container {
                width: 140px;
                height: 140px;
            }
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
                gap: 1.5rem;
            }
            .product-card {
                width: 190px;
                height: 350px;
            }
        }

        @media (max-width: 992px) {
            .shop-hero {
                padding: 4rem 0 2rem;
            }
            .shop-info-card {
                padding: 2rem;
                transform: translateY(40px);
                margin-top: -20px;
            }
            .shop-title {
                font-size: 2rem;
            }
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 1.25rem;
            }
            .product-card {
                width: 100%;
                height: 340px;
            }
        }

        @media (max-width: 768px) {
            .shop-hero {
                padding: 3rem 0 1.5rem;
                margin-bottom: 2rem;
            }
            .shop-logo-container {
                width: 120px;
                height: 120px;
                margin-bottom: 1.5rem;
            }
            .shop-info-card {
                padding: 1.5rem;
                transform: translateY(30px);
                margin-top: -15px;
            }
            .shop-title {
                font-size: 1.75rem;
            }
            .shop-stats {
                gap: 1rem;
                margin-bottom: 1.5rem;
            }
            .stat-value {
                font-size: 1.5rem;
            }
            .cart-floating {
                bottom: 1.5rem;
                right: 1.5rem;
            }
            .cart-floating-btn {
                width: 60px;
                height: 60px;
                font-size: 1.25rem;
            }
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            .product-card {
                height: 320px;
            }
            .card-img-container {
                height: 120px;
            }
            .card-content {
                padding: 12px;
                height: calc(320px - 120px);
            }
        }

        @media (max-width: 576px) {
            .shop-hero {
                padding: 2.5rem 0 1rem;
            }
            .shop-info-card {
                padding: 1.25rem;
                transform: translateY(20px);
                margin-top: -10px;
            }
            .shop-title {
                font-size: 1.5rem;
            }
            .shop-description {
                font-size: 1rem;
            }
            .shop-stats {
                justify-content: center;
            }
            .hero-actions {
                flex-direction: column;
            }
            .hero-btn {
                width: 100%;
                justify-content: center;
            }
            .view-toggle {
                width: 100%;
                justify-content: center;
            }
            .view-btn {
                flex: 1;
                justify-content: center;
            }
            .products-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .product-card {
                height: 300px;
            }
            .card-img-container {
                height: 110px;
            }
            .card-content {
                height: calc(300px - 110px);
            }
            .smart-quantity-controls {
                padding: 4px;
                gap: 8px;
            }
            .smart-quantity-btn {
                width: 28px;
                height: 28px;
                font-size: 0.9rem;
            }
            .smart-quantity-input {
                width: 40px;
                height: 28px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 400px) {
            .shop-logo-container {
                width: 100px;
                height: 100px;
            }
            .logo-badge {
                font-size: 0.65rem;
                padding: 6px 10px;
                top: -8px;
                right: -8px;
            }
            .product-card {
                height: 280px;
            }
            .card-img-container {
                height: 100px;
            }
            .card-content {
                padding: 10px;
                height: calc(280px - 100px);
            }
            .price-display {
                font-size: 1.1rem;
            }
            .btn-card {
                padding: 8px 10px;
                font-size: 0.75rem;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <!-- Enhanced Shop Hero Section -->
    <div class="shop-hero">
        <div class="container">
            <div class="hero-content">
                <!-- Shop Logo with Enhanced Design -->
                <div class="shop-logo-container">
                    <div class="shop-logo-wrapper">
                        @if($shop->logo)
                            <img src="{{ asset('storage/' . $shop->logo) }}"
                                 alt="{{ $shop->shop_name }}"
                                 class="shop-logo">
                        @else
                            <div class="shop-logo-placeholder">
                                <i class="fas fa-store"></i>
                            </div>
                        @endif
                    </div>
                    @if($shop->is_verified)
                        <div class="logo-badge">
                            <i class="fas fa-check-circle me-1"></i>
                            Verified
                        </div>
                    @endif
                </div>

                <!-- Shop Info Card -->
                <div class="shop-info-card">
                    <h1 class="shop-title">{{ $shop->shop_name }}</h1>

                    @if($shop->description)
                        <p class="shop-description">{{ Str::limit($shop->description, 250) }}</p>
                    @endif

                    <!-- Shop Stats -->
                    <div class="shop-stats">
                        <div class="stat-item">
                            <span class="stat-value">{{ $products->total() }}</span>
                            <span class="stat-label">Products</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">4.8</span>
                            <span class="stat-label">Rating</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">{{ $shop->established_year ?? '2020' }}</span>
                            <span class="stat-label">Established</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">98%</span>
                            <span class="stat-label">Satisfaction</span>
                        </div>
                    </div>

                    <!-- Shop Meta Information -->
                    <div class="shop-meta">
                        <span class="meta-badge">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $shop->city }}, {{ $shop->country }}
                        </span>
                        <span class="meta-badge">
                            <i class="fas fa-phone-alt"></i>
                            {{ $shop->contact_phone }}
                        </span>
                        <span class="meta-badge verified">
                            <i class="fas fa-shield-alt"></i>
                            Trusted Seller
                        </span>
                        <span class="meta-badge rating">
                            <i class="fas fa-star"></i>
                            4.8 Rating
                        </span>
                        @if($shop->email)
                            <span class="meta-badge">
                                <i class="fas fa-envelope"></i>
                                {{ $shop->email }}
                            </span>
                        @endif
                    </div>

                    <!-- Hero Actions -->
                    <div class="hero-actions">
                        <button class="hero-btn hero-btn-primary" onclick="shareShop()">
                            <i class="fas fa-share-alt"></i>
                            Share This Shop
                        </button>
                        <button class="hero-btn hero-btn-secondary" onclick="contactShop()">
                            <i class="fas fa-comment-dots"></i>
                            Contact Seller
                        </button>
                        @if($shop->website)
                            <button class="hero-btn hero-btn-secondary" onclick="window.open('{{ $shop->website }}', '_blank')">
                                <i class="fas fa-globe"></i>
                                Visit Website
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <!-- View Toggle -->
        <div class="view-toggle-section">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="view-toggle">
                    <button class="view-btn {{ $viewType == 'grid' ? 'active' : '' }}" data-view="grid" onclick="changeView('grid')">
                        <i class="fas fa-th-large me-2"></i>
                        Grid View
                    </button>
                    <button class="view-btn {{ $viewType == 'list' ? 'active' : '' }}" data-view="list" onclick="changeView('list')">
                        <i class="fas fa-list me-2"></i>
                        List View
                    </button>
                    <button class="view-btn" data-view="compact" onclick="changeView('compact')">
                        <i class="fas fa-grip me-2"></i>
                        Compact
                    </button>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted">
                        <i class="fas fa-box me-2"></i>
                        <span id="productCount">{{ $products->total() }}</span> products found
                    </span>
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshProducts()">
                        <i class="fas fa-sync-alt me-2"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-lg-3 mb-4">
                <div class="enhanced-filter">
                    <h6 class="filter-title">
                        <i class="fas fa-filter me-2"></i>
                        Smart Filters
                    </h6>

                    <!-- Search -->
                    <div class="smart-search mb-4">
                        <i class="fas fa-search"></i>
                        <input type="text"
                               id="globalSearch"
                               placeholder="Search products..."
                               value="{{ request('search') }}"
                               oninput="debouncedSearch(this.value)">
                    </div>

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-3 d-flex justify-content-between">
                            <span>Categories</span>
                            <span class="badge bg-primary">{{ count($categories) }}</span>
                        </label>
                        <div class="filter-options">
                            <div class="form-check mb-2">
                                <input class="form-check-input"
                                       type="checkbox"
                                       id="category_all"
                                       {{ !request('category') ? 'checked' : '' }}
                                       onchange="toggleCategory('all')">
                                <label class="form-check-label d-flex align-items-center" for="category_all">
                                    <i class="fas fa-layer-group me-2 text-primary"></i>
                                    All Categories
                                </label>
                            </div>
                            @foreach($categories as $category)
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="category_{{ $category->id }}"
                                           value="{{ $category->id }}"
                                           {{ request('category') == $category->id ? 'checked' : '' }}
                                           onchange="toggleCategory({{ $category->id }})">
                                    <label class="form-check-label d-flex justify-content-between align-items-center" for="category_{{ $category->id }}">
                                        <span>
                                            <i class="fas fa-tag me-2" style="color: {{ $category->color ?? '#6366f1' }}"></i>
                                            {{ $category->name }}
                                        </span>
                                        <span class="badge bg-light text-dark">{{ $category->products_count ?? 0 }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-3">
                            <i class="fas fa-tags me-2"></i>
                            Price Range
                        </label>
                        <div class="price-slider mb-3">
                            <input type="range" class="form-range" id="priceRange" min="0" max="10000" value="{{ request('max_price', 5000) }}">
                            <div class="d-flex justify-content-between mt-2">
                                <span id="minPriceValue">Rs. 0</span>
                                <span id="maxPriceValue">Rs. 10,000</span>
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Min</span>
                                    <input type="number"
                                           id="minPrice"
                                           class="form-control"
                                           placeholder="0"
                                           value="{{ request('min_price', 0) }}"
                                           min="0">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Max</span>
                                    <input type="number"
                                           id="maxPrice"
                                           class="form-control"
                                           placeholder="10000"
                                           value="{{ request('max_price', 10000) }}"
                                           min="0">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100" onclick="applyPriceFilter()">
                            <i class="fas fa-check-circle me-2"></i>
                            Apply Price Filter
                        </button>
                    </div>

                    <!-- Stock Filter -->
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-3">
                            <i class="fas fa-boxes me-2"></i>
                            Stock Status
                        </label>
                        <select class="form-select" id="stockFilter" onchange="applyStockFilter()">
                            <option value="">All Stock</option>
                            <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="low_stock" {{ request('stock') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out_of_stock" {{ request('stock') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>

                    <!-- Clear Filters -->
                    @if(request()->hasAny(['category', 'min_price', 'max_price', 'search', 'stock', 'sort']))
                        <button class="btn btn-outline-danger w-100 mt-3" onclick="clearAllFilters()">
                            <i class="fas fa-times-circle me-2"></i>
                            Clear All Filters
                        </button>
                    @endif
                </div>
            </div>

            <!-- Products Section -->
            <div class="col-lg-9">
                <!-- Products Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <div>
                        <h3 class="h4 fw-bold mb-1">Featured Products</h3>
                        <p class="text-muted mb-0">
                            <i class="fas fa-eye me-1"></i>
                            Showing <strong>{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</strong>
                            of <strong>{{ $products->total() }}</strong> products
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-sort-amount-down"></i>
                            </span>
                            <select class="form-select" onchange="applySort(this.value)">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>
                                    <i class="fas fa-clock me-2"></i>Newest First
                                </option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                                    <i class="fas fa-sort-amount-down-alt me-2"></i>Price: Low to High
                                </option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                                    <i class="fas fa-sort-amount-down me-2"></i>Price: High to Low
                                </option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                    <i class="fas fa-fire me-2"></i>Most Popular
                                </option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>
                                    <i class="fas fa-star me-2"></i>Highest Rated
                                </option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                                    <i class="fas fa-sort-alpha-down me-2"></i>Name: A-Z
                                </option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                                    <i class="fas fa-sort-alpha-down-alt me-2"></i>Name: Z-A
                                </option>
                            </select>
                        </div>
                        <button class="btn btn-outline-primary btn-sm" onclick="toggleAdvancedSearch()">
                            <i class="fas fa-sliders-h me-2"></i>
                            Advanced
                        </button>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="products-grid" id="productsGrid">
                    @forelse($products as $product)
                        <div class="product-card" id="product-card-{{ $product->id }}" data-product-id="{{ $product->id }}">
                            <!-- Image -->
                            <div class="card-img-container">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="card-img"
                                         loading="lazy"
                                         onerror="this.onerror=null; this.src='https://via.placeholder.com/200x130/6366f1/ffffff?text=Product'">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="fas fa-box-open fa-3x text-primary" style="opacity: 0.5;"></i>
                                    </div>
                                @endif

                                <!-- Badges -->
                                <div class="card-badges">
                                    @if($product->created_at->diffInDays(now()) <= 7)
                                        <span class="badge badge-new">
                                            <i class="fas fa-certificate me-1"></i>
                                            NEW
                                        </span>
                                    @endif
                                    @if($product->is_featured)
                                        <span class="badge badge-hot">
                                            <i class="fas fa-fire me-1"></i>
                                            HOT
                                        </span>
                                    @endif
                                    @if($product->discount && $product->discount >= 15)
                                        <span class="badge badge-discount">
                                            <i class="fas fa-percentage me-1"></i>
                                            -{{ $product->discount }}%
                                        </span>
                                    @endif
                                    @if($product->wholesale_price < $product->display_price * 0.7)
                                        <span class="badge badge-wholesale">
                                            <i class="fas fa-store me-1"></i>
                                            WHOLESALE
                                        </span>
                                    @endif
                                    @if($product->is_premium)
                                        <span class="badge badge-premium">
                                            <i class="fas fa-crown me-1"></i>
                                            PREMIUM
                                        </span>
                                    @endif
                                </div>

                                <!-- Quick Actions -->
                                <div class="position-absolute top-0 end-0 p-2 d-flex flex-column gap-1">
                                    <button class="btn btn-sm btn-light rounded-circle" onclick="quickView({{ $product->id }})" title="Quick View">
                                        <i class="fas fa-eye fa-xs"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light rounded-circle" onclick="addToWishlist({{ $product->id }})" title="Add to Wishlist">
                                        <i class="fas fa-heart fa-xs"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light rounded-circle" onclick="window.location.href='{{ route('wholesaler.shop.product.show', $product->id) }}'" title="View Details">
                                        <i class="fas fa-info-circle fa-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="card-content">
                                <!-- Category -->
                                @if($product->category)
                                    <div class="card-category">
                                        <i class="fas fa-tag"></i>
                                        {{ $product->category->name }}
                                    </div>
                                @endif

                                <!-- Title -->
                                <h4 class="card-title">
                                    <a href="{{ route('wholesaler.shop.product.show', $product->id) }}">
                                        {{ Str::limit($product->name, 45) }}
                                    </a>
                                </h4>

                                <!-- Rating -->
                                <div class="card-rating">
                                    <div class="rating-stars">
                                        @php
                                            $avgRating = $product->average_rating ?? 0;
                                            $fullStars = floor($avgRating);
                                            $hasHalfStar = $avgRating - $fullStars >= 0.5;
                                        @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $fullStars)
                                                <i class="fas fa-star text-warning"></i>
                                            @elseif($i == $fullStars + 1 && $hasHalfStar)
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            @else
                                                <i class="far fa-star text-muted"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="rating-count">({{ $product->total_reviews ?? 0 }})</span>
                                </div>

                                <!-- Pricing -->
                                <div class="product-pricing">
                                    @if($product->discount > 0)
                                        <div class="d-flex align-items-baseline gap-2 mb-1">
                                            <span class="price-original">Rs. {{ number_format($product->selling_price, 2) }}</span>
                                            <span class="badge bg-danger">{{ $product->discount }}% OFF</span>
                                        </div>
                                    @endif
                                    <div class="price-display">
                                        Rs. {{ number_format($product->final_price, 2) }}
                                    </div>

                                    @if($product->profit_margin > 0)
                                        <div class="profit-margin small {{ $product->profit_margin > 20 ? 'text-success' : ($product->profit_margin > 10 ? 'text-warning' : 'text-info') }}">
                                            <i class="fas fa-chart-line me-1"></i>
                                            Margin: {{ number_format($product->profit_margin, 1) }}%
                                        </div>
                                    @endif
                                </div>

                                <!-- Stock -->
                                <div class="card-stock">
                                    <div class="stock-indicator">
                                        <div class="stock-dot {{ $product->qty > 50 ? 'in-stock' : ($product->qty > 10 ? 'low-stock' : 'out-stock') }}"></div>
                                        <span class="stock-text">{{ $product->qty }} in stock</span>
                                    </div>
                                    <span class="stock-text">
                                        <i class="fas fa-shopping-basket me-1"></i>
                                        Available: {{ $product->qty }}
                                    </span>
                                </div>

                                <!-- Smart Quantity Controls -->
                                <div class="smart-quantity-controls">
                                    <button class="smart-quantity-btn decrement-btn"
                                            data-product-id="{{ $product->id }}"
                                            onclick="decrementQuantity({{ $product->id }})"
                                            disabled>
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number"
                                           class="smart-quantity-input"
                                           value="0"
                                           min="0"
                                           max="{{ $product->qty }}"
                                           data-product-id="{{ $product->id }}"
                                           data-price="{{ $product->final_price }}"
                                           id="quantity-{{ $product->id }}"
                                           oninput="updateQuantityFromInput({{ $product->id }}, this.value)"
                                           onchange="calculateTotal({{ $product->id }})">
                                    <button class="smart-quantity-btn increment-btn"
                                            data-product-id="{{ $product->id }}"
                                            onclick="incrementQuantity({{ $product->id }})">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                                <!-- Total Price Display -->
                                <div class="total-price-display mb-2" id="total-price-{{ $product->id }}" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Total:</small>
                                        <span class="fw-bold text-primary" id="total-price-value-{{ $product->id }}">Rs. 0</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card-actions">
                                    <button class="btn-card btn-card-primary add-to-cart-btn"
                                            data-product-id="{{ $product->id }}"
                                            id="add-to-cart-{{ $product->id }}"
                                            onclick="addToCart({{ $product->id }})"
                                            disabled>
                                        <i class="fas fa-shopping-cart me-1"></i>
                                        Add to Cart
                                    </button>
                                    <button class="btn-card btn-card-outline buy-now-btn"
                                            data-product-id="{{ $product->id }}"
                                            id="buy-now-{{ $product->id }}"
                                            onclick="buyNow({{ $product->id }})"
                                            disabled>
                                        <i class="fas fa-bolt me-1"></i>
                                        Buy Now
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <h4 class="fw-bold mb-3">No Products Found</h4>
                            <p class="text-muted mb-4">Try adjusting your search or filter criteria</p>
                            <div class="d-flex gap-3 justify-content-center">
                                <button class="btn btn-primary px-4" onclick="clearAllFilters()">
                                    <i class="fas fa-redo me-2"></i>
                                    Reset Filters
                                </button>
                                <button class="btn btn-outline-primary px-4" onclick="window.location.reload()">
                                    <i class="fas fa-sync-alt me-2"></i>
                                    Refresh Page
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="pagination">
                        {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Floating Cart -->
    <div class="cart-floating">
        <button class="cart-floating-btn" onclick="window.location.href='{{ route('wholesaler.shop.cart') }}'">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-badge" id="cartCount">0</span>
        </button>
    </div>

    <!-- Quick View Modal -->
    <div class="quick-view-modal" id="quickViewModal">
        <div class="modal-content bg-white rounded-3 p-4" style="max-width: 800px; width: 90%;">
            <div class="modal-body" id="quickViewContent">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize everything
            updateCartCount();
            initializeProductQuantities();
            setupEventListeners();
            updatePriceSlider();
            initializeTooltips();
            checkSpecialOffers();

            // Add hover effect to shop logo
            const logoWrapper = document.querySelector('.shop-logo-wrapper');
            if (logoWrapper) {
                logoWrapper.addEventListener('mouseenter', function() {
                    const logo = this.querySelector('.shop-logo');
                    if (logo) {
                        logo.style.transform = 'scale(1.1)';
                    }
                });

                logoWrapper.addEventListener('mouseleave', function() {
                    const logo = this.querySelector('.shop-logo');
                    if (logo) {
                        logo.style.transform = 'scale(1)';
                    }
                });
            }
        });

        function setupEventListeners() {
            const priceSlider = document.getElementById('priceRange');
            if (priceSlider) {
                priceSlider.addEventListener('input', function() {
                    document.getElementById('maxPrice').value = this.value;
                    document.getElementById('maxPriceValue').textContent = 'Rs. ' + Number(this.value).toLocaleString();
                });
            }

            document.getElementById('quickViewModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeQuickView();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeQuickView();
                }
            });
        }

        function initializeTooltips() {
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        }

        function updatePriceSlider() {
            const minPrice = document.getElementById('minPrice');
            const maxPrice = document.getElementById('maxPrice');
            const priceSlider = document.getElementById('priceRange');

            if (priceSlider && maxPrice) {
                priceSlider.value = maxPrice.value;
                document.getElementById('maxPriceValue').textContent = 'Rs. ' + Number(maxPrice.value).toLocaleString();
                document.getElementById('minPriceValue').textContent = 'Rs. ' + Number(minPrice.value).toLocaleString();
            }
        }

        // Contact shop function
        function contactShop() {
            showNotification('info', 'Contact form will open shortly...');
            setTimeout(() => {
                // Here you can implement actual contact form opening
                alert('Contact the seller at: {{ $shop->email }}');
            }, 500);
        }

        // Initialize product quantities
        function initializeProductQuantities() {
            document.querySelectorAll('.smart-quantity-input').forEach(input => {
                const productId = input.dataset.productId;
                const quantity = parseInt(input.value) || 0;
                updateAddToCartButton(productId, quantity);
                updateBuyNowButton(productId, quantity);
            });
        }

        // Update Add to Cart button state
        function updateAddToCartButton(productId, quantity) {
            const addToCartBtn = document.getElementById(`add-to-cart-${productId}`);
            if (!addToCartBtn) return;

            // Enable button only if quantity is greater than 0
            if (quantity > 0) {
                addToCartBtn.disabled = false;
                addToCartBtn.classList.remove('disabled');
            } else {
                addToCartBtn.disabled = true;
                addToCartBtn.classList.add('disabled');
            }
        }

        // Update Buy Now button state
        function updateBuyNowButton(productId, quantity) {
            const buyNowBtn = document.getElementById(`buy-now-${productId}`);
            if (!buyNowBtn) return;

            // Enable button only if quantity is greater than 0
            if (quantity > 0) {
                buyNowBtn.disabled = false;
                buyNowBtn.classList.remove('disabled');
            } else {
                buyNowBtn.disabled = true;
                buyNowBtn.classList.add('disabled');
            }
        }

        // Calculate total price
        function calculateTotal(productId) {
            const quantityInput = document.getElementById(`quantity-${productId}`);
            const totalDisplay = document.getElementById(`total-price-${productId}`);
            const totalValueDisplay = document.getElementById(`total-price-value-${productId}`);

            if (!quantityInput || !totalDisplay || !totalValueDisplay) return;

            const quantity = parseInt(quantityInput.value) || 0;
            const price = parseFloat(quantityInput.dataset.price) || 0;
            const total = quantity * price;

            if (quantity > 0) {
                totalDisplay.style.display = 'block';
                totalValueDisplay.textContent = 'Rs. ' + total.toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            } else {
                totalDisplay.style.display = 'none';
            }
        }

        // Update quantity from input
        function updateQuantityFromInput(productId, value) {
            const quantityInput = document.getElementById(`quantity-${productId}`);
            const decrementBtn = document.querySelector(`.decrement-btn[data-product-id="${productId}"]`);
            const incrementBtn = document.querySelector(`.increment-btn[data-product-id="${productId}"]`);

            let quantity = parseInt(value) || 0;
            const maxQuantity = parseInt(quantityInput.max) || 0;

            // Validate input
            if (isNaN(quantity) || quantity < 0) quantity = 0;
            if (quantity > maxQuantity) {
                quantity = maxQuantity;
                showNotification('warning', `Maximum available quantity is ${maxQuantity}`);
            }

            // Update input value
            quantityInput.value = quantity;

            // Update button states
            if (decrementBtn) decrementBtn.disabled = quantity <= 0;
            if (incrementBtn) incrementBtn.disabled = quantity >= maxQuantity;

            // Update Add to Cart and Buy Now buttons
            updateAddToCartButton(productId, quantity);
            updateBuyNowButton(productId, quantity);

            // Calculate and update total price
            calculateTotal(productId);
        }

        // Increment quantity
        function incrementQuantity(productId) {
            const quantityInput = document.getElementById(`quantity-${productId}`);
            let currentValue = parseInt(quantityInput.value) || 0;
            const maxQuantity = parseInt(quantityInput.max) || 0;

            if (currentValue < maxQuantity) {
                currentValue++;
                updateQuantityFromInput(productId, currentValue);
            } else {
                showNotification('warning', 'Maximum quantity reached');
            }
        }

        // Decrement quantity
        function decrementQuantity(productId) {
            const quantityInput = document.getElementById(`quantity-${productId}`);
            let currentValue = parseInt(quantityInput.value) || 0;

            if (currentValue > 0) {
                currentValue--;
                updateQuantityFromInput(productId, currentValue);
            }
        }

        // Add to cart function with validation
        async function addToCart(productId) {
            const quantityInput = document.getElementById(`quantity-${productId}`);
            const quantity = parseInt(quantityInput.value) || 0;

            // Validate quantity
            if (quantity === 0) {
                showNotification('error', 'Please select quantity first');
                return;
            }

            // Validate stock availability
            const maxQuantity = parseInt(quantityInput.max) || 0;
            if (quantity > maxQuantity) {
                showNotification('error', `Only ${maxQuantity} items available in stock`);
                return;
            }

            const addToCartBtn = document.getElementById(`add-to-cart-${productId}`);
            const originalText = addToCartBtn.innerHTML;
            const originalState = addToCartBtn.disabled;

            // Show loading
            addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';
            addToCartBtn.disabled = true;

            // Add animation to product card
            const productCard = document.getElementById(`product-card-${productId}`);
            if (productCard) {
                productCard.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    productCard.style.transform = '';
                }, 300);
            }

            try {
                const response = await fetch('{{ route("wholesaler.shop.cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('success', data.message || 'Product added to cart successfully!');
                    updateCartCount();

                    // Reset quantity to 0 after successful add
                    updateQuantityFromInput(productId, 0);

                    // Show cart animation
                    showCartAnimation(productCard);
                } else {
                    showNotification('error', data.message || 'Failed to add to cart');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'An error occurred. Please try again.');
            } finally {
                addToCartBtn.innerHTML = originalText;
                addToCartBtn.disabled = originalState;
            }
        }

        // Buy Now function with validation
        async function buyNow(productId) {
            const quantityInput = document.getElementById(`quantity-${productId}`);
            const quantity = parseInt(quantityInput.value) || 0;

            // Validate quantity
            if (quantity === 0) {
                showNotification('error', 'Please select quantity first');
                return;
            }

            // Validate stock availability
            const maxQuantity = parseInt(quantityInput.max) || 0;
            if (quantity > maxQuantity) {
                showNotification('error', `Only ${maxQuantity} items available in stock`);
                return;
            }

            const buyNowBtn = document.getElementById(`buy-now-${productId}`);
            const originalText = buyNowBtn.innerHTML;
            const originalState = buyNowBtn.disabled;

            // Show loading
            buyNowBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
            buyNowBtn.disabled = true;

            try {
                const response = await fetch('{{ route("wholesaler.shop.buy.now") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('success', 'Redirecting to checkout...');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    showNotification('error', data.message || 'Failed to process buy now');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'An error occurred. Please try again.');
            } finally {
                buyNowBtn.innerHTML = originalText;
                buyNowBtn.disabled = originalState;
            }
        }

        function showCartAnimation(productCard) {
            const cartBtn = document.querySelector('.cart-floating-btn');
            if (!cartBtn || !productCard) return;

            const productRect = productCard.getBoundingClientRect();
            const cartRect = cartBtn.getBoundingClientRect();

            // Create flying item
            const flyingItem = document.createElement('div');
            flyingItem.style.cssText = `
                position: fixed;
                width: 20px;
                height: 20px;
                background: linear-gradient(135deg, var(--primary), var(--secondary));
                border-radius: 50%;
                z-index: 99999;
                pointer-events: none;
                left: ${productRect.left + productRect.width/2 - 10}px;
                top: ${productRect.top + productRect.height/2 - 10}px;
                animation: flyToCart 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            `;

            // Add keyframes
            if (!document.getElementById('cart-animation-styles')) {
                const style = document.createElement('style');
                style.id = 'cart-animation-styles';
                style.textContent = `
                    @keyframes flyToCart {
                        0% {
                            transform: scale(1) translate(0, 0);
                            opacity: 1;
                        }
                        100% {
                            transform: scale(0) translate(${cartRect.left - productRect.left + cartRect.width/2}px, ${cartRect.top - productRect.top + cartRect.height/2}px);
                            opacity: 0;
                        }
                    }
                `;
                document.head.appendChild(style);
            }

            document.body.appendChild(flyingItem);

            // Remove after animation
            setTimeout(() => {
                if (flyingItem.parentNode) {
                    flyingItem.parentNode.removeChild(flyingItem);
                }
            }, 800);

            // Animate cart badge
            const cartBadge = document.getElementById('cartCount');
            if (cartBadge) {
                cartBadge.style.transform = 'scale(1.3)';
                setTimeout(() => {
                    cartBadge.style.transform = '';
                }, 300);
            }
        }

        async function updateCartCount() {
            try {
                const response = await fetch('{{ route("wholesaler.shop.cart.count") }}');
                const data = await response.json();
                if (data.success) {
                    document.getElementById('cartCount').textContent = data.count;
                }
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        }

        // Quick View functionality
        async function quickView(productId) {
            showLoading(true);

            try {
                const response = await fetch(`/wholesaler/shop/products/${productId}/quick-view`);
                const data = await response.text();

                document.getElementById('quickViewContent').innerHTML = data;
                document.getElementById('quickViewModal').classList.add('active');

                // Initialize icons in modal
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('error', 'Failed to load product details');
            } finally {
                showLoading(false);
            }
        }

        function closeQuickView() {
            document.getElementById('quickViewModal').classList.remove('active');
        }

        // Filter functions
        function toggleCategory(categoryId) {
            const url = new URL(window.location.href);

            if (categoryId === 'all') {
                url.searchParams.delete('category');
                document.querySelectorAll('input[id^="category_"]').forEach(cb => {
                    if (cb.id !== 'category_all') cb.checked = false;
                });
            } else {
                url.searchParams.set('category', categoryId);
                document.getElementById('category_all').checked = false;
            }

            applyFilter(url);
        }

        function applyPriceFilter() {
            const minPrice = document.getElementById('minPrice').value;
            const maxPrice = document.getElementById('maxPrice').value;
            const url = new URL(window.location.href);

            if (minPrice) url.searchParams.set('min_price', minPrice);
            else url.searchParams.delete('min_price');

            if (maxPrice) url.searchParams.set('max_price', maxPrice);
            else url.searchParams.delete('max_price');

            applyFilter(url);
        }

        function applyStockFilter() {
            const stockFilter = document.getElementById('stockFilter').value;
            const url = new URL(window.location.href);

            if (stockFilter) {
                url.searchParams.set('stock', stockFilter);
            } else {
                url.searchParams.delete('stock');
            }

            applyFilter(url);
        }

        function applySort(sortBy) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sortBy);
            applyFilter(url);
        }

        // Search with debounce
        let searchTimeout;
        function debouncedSearch(query) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const url = new URL(window.location.href);
                if (query) {
                    url.searchParams.set('search', query);
                } else {
                    url.searchParams.delete('search');
                }
                applyFilter(url);
            }, 600);
        }

        function clearAllFilters() {
            const url = new URL('{{ route("wholesaler.shop.index") }}');
            applyFilter(url);
        }

        function changeView(view) {
            const url = new URL(window.location.href);
            url.searchParams.set('view', view);
            applyFilter(url);
        }

        function applyFilter(url) {
            showLoading(true);
            window.location.href = url.toString();
        }

        function showLoading(show) {
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) {
                if (show) {
                    loadingOverlay.classList.add('active');
                } else {
                    loadingOverlay.classList.remove('active');
                }
            }
        }

        // Share shop function
        function shareShop() {
            const shareUrl = window.location.href;
            const shopName = '{{ $shop->shop_name }}';
            const shopDesc = '{{ Str::limit($shop->description, 100) }}';

            if (navigator.share) {
                navigator.share({
                    title: shopName,
                    text: `Check out ${shopName} on Wholesale Marketplace: ${shopDesc}`,
                    url: shareUrl,
                }).catch(console.error);
            } else {
                // Fallback for browsers that don't support Web Share API
                navigator.clipboard.writeText(`${shopName} - ${shareUrl}`).then(() => {
                    showNotification('success', 'Shop link copied to clipboard!');
                }).catch(() => {
                    // Fallback for browsers that don't support clipboard API
                    const tempInput = document.createElement('input');
                    tempInput.value = shareUrl;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);
                    showNotification('success', 'Shop link copied to clipboard!');
                });
            }
        }

        function addToWishlist(productId) {
            showNotification('info', 'Added to wishlist');
            // Here you would typically make an API call to add to wishlist
        }

        function toggleAdvancedSearch() {
            showNotification('info', 'Advanced search coming soon!');
        }

        function refreshProducts() {
            window.location.reload();
        }

        function checkSpecialOffers() {
            const today = new Date();
            const hour = today.getHours();

            if (hour >= 9 && hour <= 17) {
                // Show notification only once per session
                if (!sessionStorage.getItem('specialOfferShown')) {
                    setTimeout(() => {
                        showNotification('info', '🔥 Special wholesale discounts available today! Limited time offer.');
                        sessionStorage.setItem('specialOfferShown', 'true');
                    }, 2000);
                }
            }
        }

        // Smart Notification System
        function showNotification(type, message) {
            // Remove existing notifications
            document.querySelectorAll('.smart-notification').forEach(n => n.remove());

            // Create notification
            const notification = document.createElement('div');
            notification.className = `smart-notification ${type}`;
            notification.innerHTML = `
                <i class="fas fa-${getNotificationIcon(type)} fa-lg me-2"></i>
                <div class="flex-grow-1">
                    <div class="fw-bold">${getNotificationTitle(type)}</div>
                    <div class="small">${message}</div>
                </div>
                <button class="btn btn-sm btn-outline-secondary" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.animation = 'slideOutRight 0.3s ease forwards';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                }
            }, 5000);
        }

        function getNotificationIcon(type) {
            const icons = {
                'success': 'check-circle',
                'error': 'exclamation-circle',
                'warning': 'exclamation-triangle',
                'info': 'info-circle'
            };
            return icons[type] || 'info-circle';
        }

        function getNotificationTitle(type) {
            const titles = {
                'success': 'Success',
                'error': 'Error',
                'warning': 'Warning',
                'info': 'Information'
            };
            return titles[type] || 'Notification';
        }
    </script>
@endpush
