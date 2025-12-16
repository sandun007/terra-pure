@extends('wholesaler.layouts.app')
@push('title')
    Wholesale Dashboard
@endpush

@push('css')
    <!-- jQuery (needed for Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- CSRF Token Meta Tag (ADD THIS) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        .dashboard-container {
            padding: 30px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        /* Header Styling */
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .dashboard-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .header-title h1 {
            color: white;
            font-size: 32px;
            font-weight: 800;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-title p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            margin-bottom: 0;
        }

        .business-badge {
            margin-top: 15px;
        }

        .business-badge .badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 50px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        /* Statistics Grid */
        .wholesale-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
        }

        .wholesale-stat-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .wholesale-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .wholesale-stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .wholesale-stat-card:hover::before {
            transform: scaleX(1);
        }

        .wholesale-stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .wholesale-stat-title {
            font-size: 13px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .wholesale-stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            transition: all 0.3s ease;
        }

        .wholesale-stat-card:hover .wholesale-stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .icon-products {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .icon-orders {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(240, 147, 251, 0.3);
        }

        .icon-confirmed {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.3);
        }

        .icon-revenue {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(67, 233, 123, 0.3);
        }

        .wholesale-stat-value {
            font-size: 36px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .wholesale-stat-change {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .wholesale-stat-change i {
            font-size: 12px;
        }

        /* Dashboard Sections */
        .dashboard-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: none;
            transition: all 0.3s ease;
        }

        .dashboard-section:hover {
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f1f5f9;
        }

        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
        }

        .view-all-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .view-all-link:hover {
            color: #764ba2;
            gap: 8px;
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
            margin-top: 20px;
        }

        .chart-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .chart-btn {
            padding: 8px 16px;
            border: 2px solid #e2e8f0;
            background: white;
            color: #475569;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .chart-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }

        .chart-btn:hover:not(.active) {
            border-color: #667eea;
            color: #667eea;
        }

        /* Table Styling */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            padding: 15px;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            color: #475569;
            font-weight: 500;
        }

        .table .badge {
            padding: 6px 14px;
            font-weight: 600;
            font-size: 12px;
            border-radius: 50px;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .quick-action-btn {
            display: flex;
            align-items: center;
            padding: 18px 20px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            text-decoration: none;
            color: #475569;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: left 0.4s ease;
            z-index: 0;
        }

        .quick-action-btn:hover::before {
            left: 0;
        }

        .quick-action-btn:hover {
            color: white;
            border-color: transparent;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .quick-action-icon {
            margin-right: 12px;
            font-size: 20px;
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .quick-action-btn:hover .quick-action-icon {
            transform: scale(1.2);
        }

        .quick-action-btn span {
            position: relative;
            z-index: 1;
        }

        /* Stock Alerts */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 15px 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .alert:hover {
            transform: translateX(5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .alert-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }

        .alert i {
            font-size: 20px;
        }

        /* Storage Info Styles */
        .storage-info {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .storage-info h6 {
            color: #475569;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .storage-info p {
            color: #64748b;
            font-size: 12px;
            margin-bottom: 5px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 15px;
            }

            .dashboard-header {
                padding: 25px;
            }

            .header-title h1 {
                font-size: 24px;
            }

            .wholesale-stats-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .wholesale-stat-value {
                font-size: 28px;
            }

            .section-title {
                font-size: 18px;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .chart-container {
                height: 250px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        <!-- Storage Info Section (Optional - can be hidden) -->
        <div class="storage-info d-none" id="storageInfo">
            <h6><i class="fas fa-database me-2"></i> Storage Information</h6>
            <p>Wholesaler ID: <span id="wholesalerIdDisplay"></span></p>
            <p>Storage Path: <span id="storagePathDisplay"></span></p>
            <p>Disk Usage: <span id="diskUsageDisplay"></span></p>
        </div>

        <!-- Enhanced Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>👋 Welcome Back, {{ $wholesaler->name }}!</h1>
                    <p>Manage your wholesale business efficiently with real-time insights</p>
                </div>
                <div class="business-badge">
                    <span class="badge">🏢 {{ $wholesaler->business_name }}</span>
                </div>
            </div>
        </div>

        <!-- Enhanced Statistics Grid -->
        <div class="wholesale-stats-grid">
            <div class="wholesale-stat-card">
                <div class="wholesale-stat-header">
                    <div class="wholesale-stat-title">Total Products</div>
                    <div class="wholesale-stat-icon icon-products">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
                <div class="wholesale-stat-value">{{ $stats['total_products'] }}</div>
                <div class="wholesale-stat-change">
                    <i class="fas fa-chart-line"></i>
                    <span>Manage your product catalog</span>
                </div>
            </div>

            <div class="wholesale-stat-card">
                <div class="wholesale-stat-header">
                    <div class="wholesale-stat-title">Total Orders</div>
                    <div class="wholesale-stat-icon icon-orders">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="wholesale-stat-value">{{ $stats['total_orders'] }}</div>
                <div class="wholesale-stat-change">
                    <i class="fas fa-chart-line"></i>
                    <span>Track all customer orders</span>
                </div>
            </div>

            <div class="wholesale-stat-card">
                <div class="wholesale-stat-header">
                    <div class="wholesale-stat-title">Confirmed Orders</div>
                    <div class="wholesale-stat-icon icon-confirmed">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="wholesale-stat-value">{{ $stats['confirmed_orders'] }}</div>
                <div class="wholesale-stat-change">
                    <i class="fas fa-chart-line"></i>
                    <span>Ready for processing</span>
                </div>
            </div>

            <div class="wholesale-stat-card">
                <div class="wholesale-stat-header">
                    <div class="wholesale-stat-title">Total Revenue</div>
                    <div class="wholesale-stat-icon icon-revenue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="wholesale-stat-value">RS. {{ number_format($stats['total_revenue'], 2) }}</div>
                <div class="wholesale-stat-change">
                    <i class="fas fa-chart-line"></i>
                    <span>From completed orders</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Sales Analytics Chart -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3 class="section-title">Sales Analytics</h3>
                        <div class="chart-actions">
                            <button class="chart-btn active" data-period="monthly">Monthly</button>
                            <button class="chart-btn" data-period="quarterly">Quarterly</button>
                            <button class="chart-btn" data-period="yearly">Yearly</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Enhanced Recent Orders Section -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3 class="section-title">Recent Orders</h3>
                        <a href="{{ route('wholesaler.orders.index') }}" class="view-all-link">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
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
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>
                                        <i class="fas fa-user-circle" style="color: #667eea; margin-right: 8px;"></i>
                                        {{ $order->customer->name ?? 'N/A' }}
                                    </td>
                                    <td><strong>RS. {{ number_format($order->total_amount, 2) }}</strong></td>
                                    <td>
                                        <span class="badge
                                            @if($order->status == 'confirmed') bg-success
                                            @elseif($order->status == 'pending') bg-warning
                                            @elseif($order->status == 'cancelled') bg-danger
                                            @else bg-secondary @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="far fa-calendar-alt" style="color: #94a3b8; margin-right: 5px;"></i>
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted" style="padding: 40px;">
                                        <i class="fas fa-inbox"
                                           style="font-size: 48px; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                                        No orders found
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Enhanced Quick Actions Section -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3 class="section-title">Quick Actions</h3>
                    </div>

                    <div class="quick-actions">
                        <a href="{{ route('wholesaler.products.create') }}" class="quick-action-btn">
                            <i class="fas fa-plus-circle quick-action-icon"></i>
                            <span>Add New Product</span>
                        </a>
                        <a href="{{ route('wholesaler.orders.index') }}?status=pending" class="quick-action-btn">
                            <i class="fas fa-clock quick-action-icon"></i>
                            <span>Pending Orders</span>
                        </a>
                        <a href="{{ route('wholesaler.products.index') }}" class="quick-action-btn">
                            <i class="fas fa-box-open quick-action-icon"></i>
                            <span>Manage Products</span>
                        </a>
                        <a href="{{ route('wholesaler.profile') }}" class="quick-action-btn">
                            <i class="fas fa-user-cog quick-action-icon"></i>
                            <span>Update Profile</span>
                        </a>
                    </div>
                </div>

                <!-- Enhanced Stock Alerts -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3 class="section-title">Stock Alerts</h3>
                    </div>

                    @forelse($lowStockProducts as $product)
                        <div class="alert alert-warning d-flex align-items-center mb-3">
                            <i class="fas fa-exclamation-triangle me-3"></i>
                            <div>
                                <strong>{{ $product->name }}</strong>
                                <br>
                                <small>Only {{ $product->qty }} left in stock</small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center" style="padding: 30px;">
                            <i class="fas fa-check-circle"
                               style="font-size: 48px; color: #10b981; margin-bottom: 15px; display: block;"></i>
                            <p class="text-muted mb-0">All products are well stocked</p>
                        </div>
                    @endforelse
                </div>

                <!-- Additional Quick Links -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3 class="section-title">Business Management</h3>
                    </div>

                    <div class="quick-actions">
                        <a href="{{ route('wholesaler.payments.index') }}" class="quick-action-btn">
                            <i class="fas fa-store quick-action-icon"></i>
                            <span>My Shop</span>
                        </a>
                        <a href="{{ route('wholesaler.brands.index') }}" class="quick-action-btn">
                            <i class="fas fa-tags quick-action-icon"></i>
                            <span>Brands</span>
                        </a>
                        <a href="{{ route('wholesaler.categories.index') }}" class="quick-action-btn">
                            <i class="fas fa-layer-group quick-action-icon"></i>
                            <span>Categories</span>
                        </a>
                        <a href="{{ route('wholesaler.payments.index') }}" class="quick-action-btn">
                            <i class="fas fa-credit-card quick-action-icon"></i>
                            <span>Payments</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Storage script from controller -->
    {!! $storageScript ?? '' !!}
@endsection

@push('script')
    <!-- Inline WholesalerStorage JavaScript (instead of external file) -->
    <script>
        /**
         * Wholesaler Storage Utility - Inline version
         */
        class WholesalerStorage {
            // ... keep all the WholesalerStorage class code the same ...
        }

        // Create global instance
        window.WholesalerStorage = new WholesalerStorage();
    </script>

    <script>
        // Wait for DOM and all resources to load
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Feather Icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Add entrance animations
            const cards = document.querySelectorAll('.wholesale-stat-card, .dashboard-section');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Initialize Chart
            initializeSalesChart();

            // Chart period switcher
            const chartBtns = document.querySelectorAll('.chart-btn');
            chartBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    chartBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    updateChart(this.dataset.period);
                });
            });

            // Storage functionality
            setupStorageFeatures();
        });

        /**
         * Setup storage-related features
         */
        function setupStorageFeatures() {
            // Check if wholesaler data is loaded
            if (window.WholesalerStorage && window.WholesalerStorage.hasData()) {
                try {
                    const data = window.WholesalerStorage.getAllData();
                    console.log('Wholesaler data loaded from localStorage:', data);

                    // Display storage info (optional)
                    const wholesalerIdDisplay = document.getElementById('wholesalerIdDisplay');
                    const storagePathDisplay = document.getElementById('storagePathDisplay');
                    const storageInfo = document.getElementById('storageInfo');

                    if (wholesalerIdDisplay) {
                        wholesalerIdDisplay.textContent = data.wholesaler_id;
                    }
                    if (storagePathDisplay) {
                        storagePathDisplay.textContent = `wholesaler/${data.wholesaler_id}/`;
                    }
                    if (storageInfo) {
                        storageInfo.classList.remove('d-none');
                    }
                } catch (error) {
                    console.error('Error displaying storage info:', error);
                }
            }

            // Add logout handler to clear localStorage
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

        /**
         * Get storage info from server
         */
        async function getStorageInfo() {
            try {
                // Get CSRF token from meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    return;
                }

                const response = await fetch('/wholesaler/storage-info', {
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
                    // Update display with real storage info
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
            if (!ctx) {
                console.error('Chart canvas not found');
                return;
            }

            const ctx2d = ctx.getContext('2d');
            if (!ctx2d) {
                console.error('Could not get 2D context');
                return;
            }

            // FIXED: Use actual monthly sales data from controller
            @if(isset($monthlySales) && is_array($monthlySales) && count($monthlySales) > 0)
            const realData = @json($monthlySales);
            // Process real data for chart
            const labels = realData.map(item => item.month);
            const orders = realData.map(item => item.orders);
            const revenue = realData.map(item => item.revenue);

            const monthlyData = {
                labels: labels,
                orders: orders,
                revenue: revenue
            };
            @else
            // Use dummy data if no real data available
            const monthlyData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                orders: [12, 19, 15, 25, 22, 30, 28, 35, 32, 40, 38, 45],
                revenue: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000, 32000, 40000, 38000, 45000]
            };
            @endif

                try {
                salesChart = new Chart(ctx2d, {
                    type: 'bar',
                    data: {
                        labels: monthlyData.labels,
                        datasets: [
                            {
                                label: 'Orders',
                                data: monthlyData.orders,
                                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                                borderColor: 'rgba(102, 126, 234, 1)',
                                borderWidth: 2,
                                borderRadius: 8,
                                borderSkipped: false,
                            },
                            {
                                label: 'Revenue (RS)',
                                data: monthlyData.revenue,
                                backgroundColor: 'rgba(118, 75, 162, 0.8)',
                                borderColor: 'rgba(118, 75, 162, 1)',
                                borderWidth: 2,
                                borderRadius: 8,
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
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#64748b'
                                }
                            },
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Number of Orders',
                                    color: '#64748b'
                                },
                                grid: {
                                    color: 'rgba(100, 116, 139, 0.1)'
                                },
                                ticks: {
                                    color: '#64748b'
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Revenue (RS)',
                                    color: '#64748b'
                                },
                                grid: {
                                    drawOnChartArea: false,
                                },
                                ticks: {
                                    color: '#64748b',
                                    callback: function (value) {
                                        return 'RS. ' + value.toLocaleString();
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: '#475569',
                                    usePointStyle: true,
                                    padding: 20
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleColor: '#f1f5f9',
                                bodyColor: '#f1f5f9',
                                borderColor: '#334155',
                                borderWidth: 1,
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
                            duration: 1000,
                            easing: 'easeOutQuart'
                        }
                    }
                });
            } catch (error) {
                console.error('Error creating chart:', error);
            }
        }

        function updateChart(period) {
            if (!salesChart) {
                console.error('Chart not initialized');
                return;
            }

            // In a real application, you would fetch new data from the server based on the period
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
                default: // monthly
                    // If we have real data, use it for monthly view
                @if(isset($monthlySales) && is_array($monthlySales) && count($monthlySales) > 0)
                    const realData = @json($monthlySales);
                    const labels = realData.map(item => item.month);
                    const orders = realData.map(item => item.orders);
                    const revenue = realData.map(item => item.revenue);

                    newData = {
                        labels: labels,
                        orders: orders,
                        revenue: revenue
                    };
                    @else
                        newData = {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        orders: [12, 19, 15, 25, 22, 30, 28, 35, 32, 40, 38, 45],
                        revenue: [12000, 19000, 15000, 25000, 22000, 30000, 28000, 35000, 32000, 40000, 38000, 45000]
                    };
                @endif
            }

            try {
                salesChart.data.labels = newData.labels;
                salesChart.data.datasets[0].data = newData.orders;
                salesChart.data.datasets[1].data = newData.revenue;
                salesChart.update();
            } catch (error) {
                console.error('Error updating chart:', error);
            }
        }

        // Call storage info on page load
        window.addEventListener('load', function() {
            // Small delay to ensure everything is loaded
            setTimeout(getStorageInfo, 1000);
        });
    </script>
@endpush
