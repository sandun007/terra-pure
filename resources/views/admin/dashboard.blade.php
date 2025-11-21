@extends('admin.app')
@push('title')
    Dashboard
@endpush
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

@endpush
@section('content')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="header-title">
                <h1>DreamX Dashboard</h1>
                <p>Track your multi-level marketing performance and commissions</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" id="refreshBtn"><i class="fas fa-sync"></i> Refresh Data</button>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Sales</div>
                    <div class="stat-icon icon-sales">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
                <div class="stat-value">RS. 307,144</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> 12.5% from last month
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Profit</div>
                    <div class="stat-icon icon-profit">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="stat-value">RS. 43,850</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> 8.3% from last month
                </div>
                <div class="commission-toggle" id="commissionToggle">
                    <span>Show Commission Breakdown</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="commission-breakdown" id="commissionBreakdown">
                    <div class="commission-item">
                        <div class="commission-label">Level 1</div>
                        <div class="commission-value">RS. 15</div>
                        <div class="commission-label">Direct Referrer</div>
                    </div>
                    <div class="commission-item">
                        <div class="commission-label">Level 2</div>
                        <div class="commission-value">RS. 25</div>
                        <div class="commission-label">Indirect</div>
                    </div>
                    <div class="commission-item">
                        <div class="commission-label">Level 3</div>
                        <div class="commission-value">RS. 20</div>
                        <div class="commission-label">Indirect</div>
                    </div>
                    <div class="commission-item">
                        <div class="commission-label">Company</div>
                        <div class="commission-value">RS. 10</div>
                        <div class="commission-label">Profit</div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Commission</div>
                    <div class="stat-icon icon-commission">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>
                <div class="stat-value">RS. 40,000</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> 15.2% from last month
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Sellers</div>
                    <div class="stat-icon icon-sellers">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">385,656</div>
                <div class="seller-type">
                    <button class="seller-type-btn active">System: 125,000</button>
                    <button class="seller-type-btn">Self: 260,656</button>
                </div>
            </div>

            <!-- New Cards -->
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Customers</div>
                    <div class="stat-icon icon-customers">
                        <i class="fas fa-user-friends"></i>
                    </div>
                </div>
                <div class="stat-value">12,458</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> 5.7% from last month
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Total Suppliers</div>
                    <div class="stat-icon icon-suppliers">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
                <div class="stat-value">248</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> 3.2% from last month
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Purchase Invoices</div>
                    <div class="stat-icon icon-purchase">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                </div>
                <div class="stat-value">1,245</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> 8.1% from last month
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">Sales Invoices</div>
                    <div class="stat-icon icon-sales-invoice">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
                <div class="stat-value">3,567</div>
                <div class="stat-change change-positive">
                    <i class="fas fa-arrow-up"></i> 12.3% from last month
                </div>
            </div>
        </div>

        <div class="charts-container">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Commission Distribution</div>
                    <div class="chart-actions">
                        <select>
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option selected>Last 90 Days</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="commissionChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Seller Distribution</div>
                </div>
                <div class="chart-container">
                    <canvas id="sellerChart"></canvas>
                </div>
            </div>
        </div>

        <div class="tables-container">
            <div class="table-card">
                <div class="table-header">
                    <div class="table-title">Low Stock Products</div>
                    <a href="#" class="view-all">View All <i class="fas fa-chevron-right"></i></a>
                </div>
                <table>
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Lenevo 3rd Generation</td>
                        <td>RS. 125,000</td>
                        <td>10</td>
                        <td><span class="badge badge-warning">Low</span></td>
                    </tr>
                    <tr>
                        <td>Dell XPS 13</td>
                        <td>RS. 98,000</td>
                        <td>5</td>
                        <td><span class="badge badge-danger">Critical</span></td>
                    </tr>
                    <tr>
                        <td>MacBook Pro 16"</td>
                        <td>RS. 220,000</td>
                        <td>8</td>
                        <td><span class="badge badge-warning">Low</span></td>
                    </tr>
                    <tr>
                        <td>HP Spectre x360</td>
                        <td>RS. 112,000</td>
                        <td>3</td>
                        <td><span class="badge badge-danger">Critical</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-card">
                <div class="table-header">
                    <div class="table-title">Recent Return Orders</div>
                    <a href="#" class="view-all">View All <i class="fas fa-chevron-right"></i></a>
                </div>
                <table>
                    <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>ORD/00001</td>
                        <td>Suranga</td>
                        <td>RS. 1,500</td>
                        <td><span class="badge badge-warning">Pending</span></td>
                    </tr>
                    <tr>
                        <td>ORD/00015</td>
                        <td>Kamal</td>
                        <td>RS. 2,300</td>
                        <td><span class="badge badge-success">Completed</span></td>
                    </tr>
                    <tr>
                        <td>ORD/00022</td>
                        <td>Nimal</td>
                        <td>RS. 4,500</td>
                        <td><span class="badge badge-warning">Pending</span></td>
                    </tr>
                    <tr>
                        <td>ORD/00031</td>
                        <td>Sunil</td>
                        <td>RS. 3,200</td>
                        <td><span class="badge badge-success">Completed</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function() {
            'use strict';

            // Store chart instances
            let commissionChart = null;
            let sellerChart = null;

            // Chart configuration
            const commissionConfig = {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Level 1 Commission',
                        data: [12000, 15000, 13000, 18000, 22000, 25000, 23000, 28000, 30000, 32000, 35000, 40000],
                        borderColor: '#4361ee',
                        backgroundColor: 'rgba(67, 97, 238, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }, {
                        label: 'Level 2 Commission',
                        data: [8000, 10000, 9000, 12000, 15000, 18000, 16000, 20000, 22000, 24000, 26000, 30000],
                        borderColor: '#4cc9f0',
                        backgroundColor: 'rgba(76, 201, 240, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }, {
                        label: 'Level 3 Commission',
                        data: [5000, 6000, 5500, 8000, 10000, 12000, 11000, 14000, 16000, 18000, 19000, 22000],
                        borderColor: '#f72585',
                        backgroundColor: 'rgba(247, 37, 133, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#1e293b',
                                font: { size: 11, family: 'Inter' },
                                padding: 12,
                                usePointStyle: true
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(226, 232, 240, 0.5)', drawBorder: false },
                            ticks: { color: '#64748b', font: { size: 10 } }
                        },
                        y: {
                            grid: { color: 'rgba(226, 232, 240, 0.5)', drawBorder: false },
                            ticks: {
                                color: '#64748b',
                                font: { size: 10 },
                                callback: function(value) {
                                    return 'RS. ' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            };

            const sellerConfig = {
                type: 'doughnut',
                data: {
                    labels: ['System Sellers', 'Self Sellers'],
                    datasets: [{
                        data: [125000, 260656],
                        backgroundColor: ['rgba(67, 97, 238, 0.8)', 'rgba(76, 201, 240, 0.8)'],
                        borderColor: ['#ffffff', '#ffffff'],
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#1e293b',
                                font: { size: 11, family: 'Inter' },
                                padding: 15,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return label + ': ' + value.toLocaleString() + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    cutout: '65%'
                }
            };

            // Initialize charts
            function initCharts() {
                const commissionCanvas = document.getElementById('commissionChart');
                const sellerCanvas = document.getElementById('sellerChart');

                if (!commissionCanvas || !sellerCanvas) {
                    console.error('Canvas elements not found');
                    return;
                }

                // Destroy existing charts
                if (commissionChart) {
                    commissionChart.destroy();
                    commissionChart = null;
                }
                if (sellerChart) {
                    sellerChart.destroy();
                    sellerChart = null;
                }

                // Create new charts
                try {
                    const commissionCtx = commissionCanvas.getContext('2d');
                    const sellerCtx = sellerCanvas.getContext('2d');

                    commissionChart = new Chart(commissionCtx, commissionConfig);
                    sellerChart = new Chart(sellerCtx, sellerConfig);
                } catch (error) {
                    console.error('Error creating charts:', error);
                }
            }

            // Refresh data function
            function refreshData() {
                const refreshBtn = document.getElementById('refreshBtn');
                const icon = refreshBtn.querySelector('i');

                // Add spinning animation
                icon.style.animation = 'spin 1s linear infinite';
                refreshBtn.disabled = true;

                // Simulate data refresh
                setTimeout(function() {
                    // Reinitialize charts
                    initCharts();

                    // Remove animation
                    icon.style.animation = '';
                    refreshBtn.disabled = false;
                }, 1000);
            }

            // Initialize on DOM load
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize charts
                initCharts();

                // Refresh button handler
                const refreshBtn = document.getElementById('refreshBtn');
                if (refreshBtn) {
                    refreshBtn.addEventListener('click', refreshData);
                }

                // Commission toggle
                const commissionToggle = document.getElementById('commissionToggle');
                const commissionBreakdown = document.getElementById('commissionBreakdown');

                if (commissionToggle && commissionBreakdown) {
                    commissionToggle.addEventListener('click', function() {
                        commissionBreakdown.classList.toggle('show');
                        this.classList.toggle('active');
                        this.querySelector('span').textContent = commissionBreakdown.classList.contains('show')
                            ? 'Hide Commission Breakdown'
                            : 'Show Commission Breakdown';
                    });
                }

                // Seller type buttons
                document.querySelectorAll('.seller-type-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.seller-type-btn').forEach(function(b) {
                            b.classList.remove('active');
                        });
                        this.classList.add('active');
                    });
                });
            });

            // Reinitialize on visibility change
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    setTimeout(function() {
                        initCharts();
                    }, 100);
                }
            });

            // Add CSS for spin animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(style);
        })();
    </script>
@endpush
