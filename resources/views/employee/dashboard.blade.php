@extends('employee.layout.app')

@push('title')
    Employee Dashboard
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

    <!-- CSRF Token Meta Tag -->
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 20px 60px rgba(16, 185, 129, 0.3);
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

        .employee-badge {
            margin-top: 15px;
        }

        .employee-badge .badge {
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
        .employee-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
        }

        .employee-stat-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: none;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .employee-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .employee-stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .employee-stat-card:hover::before {
            transform: scaleX(1);
        }

        .employee-stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .employee-stat-title {
            font-size: 13px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .employee-stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            transition: all 0.3s ease;
        }

        .employee-stat-card:hover .employee-stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .icon-wholesalers {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .icon-orders {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }

        .icon-products {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(139, 92, 246, 0.3);
        }

        .icon-revenue {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }

        .icon-exchanges {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
        }

        .icon-price-changes {
            background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(168, 85, 247, 0.3);
        }

        .employee-stat-value {
            font-size: 36px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .employee-stat-change {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .employee-stat-change i {
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 4px;
        }

        .view-all-link {
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .view-all-link:hover {
            color: #059669;
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-color: transparent;
        }

        .chart-btn:hover:not(.active) {
            border-color: #10b981;
            color: #10b981;
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
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

        /* Activity Timeline */
        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -0.5rem;
            top: 0;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: #10b981;
            border: 2px solid white;
        }

        .timeline-item.price::before {
            background: #8b5cf6;
        }

        .timeline-item.exchange::before {
            background: #f97316;
        }

        .timeline-date {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .timeline-title {
            font-weight: 600;
            color: #1e293b;
        }

        .timeline-desc {
            font-size: 0.9rem;
            color: #475569;
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

            .employee-stats-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .employee-stat-value {
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
        <!-- Enhanced Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <div class="header-title">
                    <h1>👋 Welcome, {{ $employee->name }}!</h1>
                    <p>Manage business operations efficiently with real-time insights</p>
                </div>
                <div class="employee-badge">
                    <span class="badge">👨‍💼 Employee Dashboard</span>
                </div>
            </div>
        </div>

        <!-- Enhanced Statistics Grid with New Metrics -->
        <div class="employee-stats-grid">
            <div class="employee-stat-card">
                <div class="employee-stat-header">
                    <div class="employee-stat-title">Wholesalers</div>
                    <div class="employee-stat-icon icon-wholesalers">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
                <div class="employee-stat-value">{{ $stats['total_wholesalers'] ?? 0 }}</div>
                <div class="employee-stat-change">
                    <i class="fas fa-chart-line"></i>
                    <span>Active wholesalers</span>
                </div>
            </div>

            <div class="employee-stat-card">
                <div class="employee-stat-header">
                    <div class="employee-stat-title">Total Orders</div>
                    <div class="employee-stat-icon icon-orders">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="employee-stat-value">{{ $stats['total_orders'] ?? 0 }}</div>
                <div class="employee-stat-change">
                    <i class="fas fa-chart-line"></i>
                    <span>Today: {{ $stats['today_orders'] ?? 0 }}</span>
                </div>
            </div>

            <div class="employee-stat-card">
                <div class="employee-stat-header">
                    <div class="employee-stat-title">Products</div>
                    <div class="employee-stat-icon icon-products">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
                <div class="employee-stat-value">{{ $stats['total_products'] ?? 0 }}</div>
                <div class="employee-stat-change">
                    <i class="fas fa-chart-line"></i>
                    <span>Active listings</span>
                </div>
            </div>

            <div class="employee-stat-card">
                <div class="employee-stat-header">
                    <div class="employee-stat-title">Total Revenue</div>
                    <div class="employee-stat-icon icon-revenue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="employee-stat-value">RS. {{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                <div class="employee-stat-change">
                    <i class="fas fa-chart-line"></i>
                    <span>Monthly revenue</span>
                </div>
            </div>

            <div class="employee-stat-card">
                <div class="employee-stat-header">
                    <div class="employee-stat-title">Price Changes</div>
                    <div class="employee-stat-icon icon-price-changes">
                        <i class="fas fa-tag"></i>
                    </div>
                </div>
                <div class="employee-stat-value">{{ $stats['total_price_changes'] ?? 0 }}</div>
                <div class="employee-stat-change">
                    <i class="fas fa-chart-line"></i>
                    <span>Total: {{ $stats['my_price_changes'] ?? 0 }} by you</span>
                </div>
            </div>

            <div class="employee-stat-card">
                <div class="employee-stat-header">
                    <div class="employee-stat-title">Exchanges</div>
                    <div class="employee-stat-icon icon-exchanges">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                </div>
                <div class="employee-stat-value">{{ $stats['total_exchanges'] ?? 0 }}</div>
                <div class="employee-stat-change">
                    <i class="fas fa-chart-line"></i>
                    <span>Pending: {{ $stats['pending_exchanges'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Sales Analytics Chart -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3 class="section-title">Business Analytics</h3>
                        <div class="chart-actions">
                            <button class="chart-btn active" data-period="daily">Daily</button>
                            <button class="chart-btn" data-period="weekly">Weekly</button>
                            <button class="chart-btn" data-period="monthly">Monthly</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="analyticsChart"></canvas>
                    </div>
                </div>

                <!-- Recent Price Changes -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3 class="section-title">Recent Price Changes</h3>
                        <a href="{{ route('employee.wholesaler.price-changes.index') }}" class="view-all-link">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Wholesaler</th>
                                <th>Old Price</th>
                                <th>New Price</th>
                                <th>Change</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentPriceChanges ?? [] as $change)
                                <tr>
                                    <td>
                                        <strong>{{ $change->product->name ?? 'N/A' }}</strong>
                                        <br><small class="text-muted">{{ $change->product->code ?? '' }}</small>
                                    </td>
                                    <td>{{ $change->wholesaler->business_name ?? 'N/A' }}</td>
                                    <td><span class="text-muted">Rs. {{ number_format($change->old_final_price, 2) }}</span></td>
                                    <td><strong class="text-primary">Rs. {{ number_format($change->new_final_price, 2) }}</strong></td>
                                    <td>
                                        <span class="badge {{ $change->price_difference > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $change->price_difference > 0 ? '+' : '' }}{{ number_format($change->price_difference, 2) }}
                                            ({{ number_format($change->percentage_change, 2) }}%)
                                        </span>
                                    </td>
                                    <td>
                                        <i class="far fa-calendar-alt" style="color: #94a3b8; margin-right: 5px;"></i>
                                        {{ $change->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted" style="padding: 40px;">
                                        <i class="fas fa-tag" style="font-size: 48px; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                                        No recent price changes
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Exchanges -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3 class="section-title">Recent Exchanges</h3>
                        <a href="{{ route('employee.wholesaler.exchanges.index') }}" class="view-all-link">
                            View All <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Source</th>
                                <th>Destination</th>
                                <th>Quantity</th>
                                <th>Value</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentExchanges ?? [] as $exchange)
                                <tr>
                                    <td>
                                        <small>{{ $exchange->sourceProduct->name ?? 'N/A' }}</small>
                                        <br><small class="text-muted">{{ $exchange->sourceWholesaler->business_name ?? '' }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $exchange->destinationProduct->name ?? 'N/A' }}</small>
                                        <br><small class="text-muted">{{ $exchange->destinationWholesaler->business_name ?? '' }}</small>
                                    </td>
                                    <td>{{ $exchange->exchange_quantity }}</td>
                                    <td>Rs. {{ number_format($exchange->destination_price_at_time, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{
                                            $exchange->status == 'completed' ? 'success' :
                                            ($exchange->status == 'pending' ? 'warning' :
                                            ($exchange->status == 'approved' ? 'info' : 'secondary'))
                                        }}">
                                            {{ ucfirst($exchange->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="far fa-calendar-alt" style="color: #94a3b8; margin-right: 5px;"></i>
                                        {{ $exchange->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted" style="padding: 40px;">
                                        <i class="fas fa-exchange-alt" style="font-size: 48px; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                                        No recent exchanges
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Quick Actions Section -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3 class="section-title">Quick Actions</h3>
                    </div>

                    <div class="quick-actions">
                        <a href="{{ route('employee.wholesaler.products.index') }}" class="quick-action-btn">
                            <i class="fas fa-box-open quick-action-icon"></i>
                            <span>Manage Products</span>
                        </a>
                        <a href="{{ route('employee.wholesaler.price-changes.create') }}" class="quick-action-btn">
                            <i class="fas fa-tag quick-action-icon"></i>
                            <span>Change Price</span>
                        </a>
                        <a href="{{ route('employee.wholesaler.price-changes.bulk.create') }}" class="quick-action-btn">
                            <i class="fas fa-layer-group quick-action-icon"></i>
                            <span>Bulk Price Update</span>
                        </a>
                        <a href="{{ route('employee.wholesaler.exchanges.create') }}" class="quick-action-btn">
                            <i class="fas fa-exchange-alt quick-action-icon"></i>
                            <span>New Exchange</span>
                        </a>

                        <a href="{{ route('employee.profile') }}" class="quick-action-btn">
                            <i class="fas fa-user-cog quick-action-icon"></i>
                            <span>Update Profile</span>
                        </a>
                    </div>
                </div>

                <!-- Pending Approvals -->
                @if(($stats['pending_price_changes'] ?? 0) > 0 || ($stats['pending_exchanges'] ?? 0) > 0)
                    <div class="dashboard-section">
                        <div class="section-header">
                            <h3 class="section-title">Pending Approvals</h3>
                        </div>

                        @if(($stats['pending_price_changes'] ?? 0) > 0)
                            <a href="{{ route('employee.wholesaler.price-changes.pending') }}" class="text-decoration-none">
                                <div class="alert alert-warning d-flex align-items-center mb-3">
                                    <i class="fas fa-clock me-3 fs-4"></i>
                                    <div>
                                        <strong>{{ $stats['pending_price_changes'] }} Price Change{{ $stats['pending_price_changes'] > 1 ? 's' : '' }}</strong>
                                        <br>
                                        <small>Awaiting approval</small>
                                    </div>
                                    <i class="fas fa-arrow-right ms-auto"></i>
                                </div>
                            </a>
                        @endif

                        @if(($stats['pending_exchanges'] ?? 0) > 0)
                            <a href="{{ route('employee.wholesaler.exchanges.index', ['status' => 'pending']) }}" class="text-decoration-none">
                                <div class="alert alert-warning d-flex align-items-center mb-3">
                                    <i class="fas fa-exchange-alt me-3 fs-4"></i>
                                    <div>
                                        <strong>{{ $stats['pending_exchanges'] }} Exchange{{ $stats['pending_exchanges'] > 1 ? 's' : '' }}</strong>
                                        <br>
                                        <small>Awaiting approval</small>
                                    </div>
                                    <i class="fas fa-arrow-right ms-auto"></i>
                                </div>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- Recent Wholesalers -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3 class="section-title">Recent Wholesalers</h3>
                        <a href="{{ route('employee.wholesaler.products.index') }}" class="view-all-link">
                            View All
                        </a>
                    </div>

                    @forelse($recentWholesalers ?? [] as $wholesaler)
                        <div class="d-flex align-items-center mb-3 p-3 bg-light rounded-3">
                            <i class="fas fa-store me-3 fs-4" style="color: #10b981;"></i>
                            <div>
                                <strong>{{ $wholesaler->business_name ?? $wholesaler->name }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ $wholesaler->products_count ?? 0 }} products •
                                    {{ $wholesaler->orders_count ?? 0 }} orders
                                </small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center" style="padding: 30px;">
                            <i class="fas fa-users" style="font-size: 48px; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                            <p class="text-muted mb-0">No wholesalers found</p>
                        </div>
                    @endforelse
                </div>

                <!-- Management Tools -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3 class="section-title">Management Tools</h3>
                    </div>

                    <div class="quick-actions">
{{--                        <a href="{{ route('seller.employee.seller.orders') }}" class="quick-action-btn">--}}
{{--                            <i class="fas fa-chart-line quick-action-icon"></i>--}}
{{--                            <span>Analytics</span>--}}
{{--                        </a>--}}
                        <a href="{{ route('employee.wholesaler.price-changes.history') }}" class="quick-action-btn">
                            <i class="fas fa-history quick-action-icon"></i>
                            <span>Price History</span>
                        </a>
{{--                        <a href="{{ route('employee.wholesaler.exchanges.history') }}" class="quick-action-btn">--}}
{{--                            <i class="fas fa-exchange-alt quick-action-icon"></i>--}}
{{--                            <span>Exchange History</span>--}}
{{--                        </a>--}}
                        <a href="{{ route('employee.wholesaler.products.export') }}" class="quick-action-btn">
                            <i class="fas fa-file-export quick-action-icon"></i>
                            <span>Export Products</span>
                        </a>
                        <a href="{{ route('employee.wholesaler.price-changes.export') }}" class="quick-action-btn">
                            <i class="fas fa-file-csv quick-action-icon"></i>
                            <span>Export Price Changes</span>
                        </a>
                        <a href="{{ route('employee.wholesaler.exchanges.export') }}" class="quick-action-btn">
                            <i class="fas fa-file-export quick-action-icon"></i>
                            <span>Export Exchanges</span>
                        </a>
                    </div>
                </div>

                <!-- Activity Timeline -->
                <div class="dashboard-section">
                    <div class="section-header">
                        <h3 class="section-title">Your Recent Activity</h3>
                    </div>

                    <div class="timeline">
                        @forelse($recentActivities ?? [] as $activity)
                            <div class="timeline-item {{ $activity->type }}">
                                <div class="timeline-date">
                                    <i class="far fa-clock me-1"></i> {{ $activity->created_at->diffForHumans() }}
                                </div>
                                <div class="timeline-title">{{ $activity->title }}</div>
                                <div class="timeline-desc">{{ $activity->description }}</div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No recent activity</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Wait for DOM to load
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Feather Icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Add entrance animations
            const cards = document.querySelectorAll('.employee-stat-card, .dashboard-section');
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
            initializeAnalyticsChart();

            // Chart period switcher
            const chartBtns = document.querySelectorAll('.chart-btn');
            chartBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    chartBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    updateChart(this.dataset.period);
                });
            });
        });

        let analyticsChart;

        function initializeAnalyticsChart() {
            const ctx = document.getElementById('analyticsChart');
            if (!ctx) {
                console.error('Chart canvas not found');
                return;
            }

            const ctx2d = ctx.getContext('2d');
            if (!ctx2d) {
                console.error('Could not get 2D context');
                return;
            }

            // Sample data - replace with actual data from controller
            const dailyData = {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                orders: [12, 19, 15, 25, 22, 30, 28],
                revenue: [12000, 19000, 15000, 25000, 22000, 30000, 28000]
            };

            try {
                analyticsChart = new Chart(ctx2d, {
                    type: 'line',
                    data: {
                        labels: dailyData.labels,
                        datasets: [
                            {
                                label: 'Orders',
                                data: dailyData.orders,
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderColor: 'rgba(16, 185, 129, 1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: 'white',
                                pointBorderColor: '#10b981',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointHoverRadius: 8
                            },
                            {
                                label: 'Revenue (RS)',
                                data: dailyData.revenue,
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: 'white',
                                pointBorderColor: '#3b82f6',
                                pointBorderWidth: 2,
                                pointRadius: 6,
                                pointHoverRadius: 8,
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
            if (!analyticsChart) {
                console.error('Chart not initialized');
                return;
            }

            // Fetch data from server
            fetch(`{{ route("employee.dashboard") }}?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    try {
                        analyticsChart.data.labels = data.labels;
                        analyticsChart.data.datasets[0].data = data.orders;
                        analyticsChart.data.datasets[1].data = data.revenue;
                        analyticsChart.update();
                    } catch (error) {
                        console.error('Error updating chart:', error);
                    }
                })
                .catch(error => {
                    console.error('Error fetching chart data:', error);

                    // Fallback to local data
                    let newData;
                    switch (period) {
                        case 'weekly':
                            newData = {
                                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                                orders: [100, 120, 150, 180],
                                revenue: [100000, 120000, 150000, 180000]
                            };
                            break;
                        case 'monthly':
                            newData = {
                                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                                orders: [180, 240, 320, 450, 500, 600, 550, 650, 700, 800, 850, 900],
                                revenue: [180000, 240000, 320000, 450000, 500000, 600000, 550000, 650000, 700000, 800000, 850000, 900000]
                            };
                            break;
                        default: // daily
                            newData = {
                                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                                orders: [12, 19, 15, 25, 22, 30, 28],
                                revenue: [12000, 19000, 15000, 25000, 22000, 30000, 28000]
                            };
                    }

                    analyticsChart.data.labels = newData.labels;
                    analyticsChart.data.datasets[0].data = newData.orders;
                    analyticsChart.data.datasets[1].data = newData.revenue;
                    analyticsChart.update();
                });
        }
    </script>
@endpush
