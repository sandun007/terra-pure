@extends('admin.app')
@push('title')
    Dashboard
@endpush
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --danger: #e63946;
            --dark: #1d3557;
            --light: #f8f9fa;
            --gray: #6c757d;
            --bg-white: #ffffff;
            --card-bg: #f8fafc;
            --text-dark: #1e293b;
            --border-light: #e2e8f0;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-light);
        }

        .header-title h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .header-title p {
            color: var(--gray);
            font-size: 14px;
        }

        .header-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-outline {
            background: transparent;
            color: var(--text-dark);
            border: 1px solid var(--border-light);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--success));
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .stat-title {
            font-size: 14px;
            color: var(--gray);
            font-weight: 500;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .icon-sales {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .icon-profit {
            background: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }

        .icon-commission {
            background: rgba(243, 114, 44, 0.1);
            color: #f3722c;
        }

        .icon-sellers {
            background: rgba(233, 196, 106, 0.1);
            color: #e9c46a;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--dark);
        }

        .stat-change {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
        }

        .change-positive {
            color: #2ecc71;
        }

        .change-negative {
            color: var(--danger);
        }

        .commission-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            color: var(--primary);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 5px 0;
        }

        .commission-toggle:hover {
            color: var(--secondary);
        }

        .commission-toggle i {
            transition: transform 0.3s ease;
        }

        .commission-toggle.active i {
            transform: rotate(180deg);
        }

        .commission-breakdown {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease, margin-top 0.3s ease;
        }

        .commission-breakdown.show {
            max-height: 200px;
            margin-top: 20px;
        }

        .commission-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid var(--border-light);
            text-align: center;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .commission-breakdown.show .commission-item {
            opacity: 1;
            transform: translateY(0);
        }

        .commission-item:nth-child(1) {
            transition-delay: 0.1s;
        }
        .commission-item:nth-child(2) {
            transition-delay: 0.2s;
        }
        .commission-item:nth-child(3) {
            transition-delay: 0.3s;
        }
        .commission-item:nth-child(4) {
            transition-delay: 0.4s;
        }

        .commission-value {
            font-size: 18px;
            font-weight: 700;
            margin: 5px 0;
        }

        .commission-label {
            font-size: 12px;
            color: var(--gray);
        }

        .charts-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }

        .chart-actions {
            display: flex;
            gap: 10px;
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        .tables-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .table-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
        }

        .view-all {
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px 15px;
            font-weight: 500;
            color: var(--gray);
            font-size: 14px;
            border-bottom: 1px solid var(--border-light);
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--border-light);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background: rgba(46, 204, 113, 0.1);
            color: #2ecc71;
        }

        .badge-warning {
            background: rgba(241, 196, 15, 0.1);
            color: #f1c40f;
        }

        .badge-danger {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
        }

        .seller-type {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .seller-type-btn {
            padding: 8px 16px;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
            color: var(--text-dark);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .seller-type-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        @media (max-width: 1200px) {
            .charts-container {
                grid-template-columns: 1fr;
            }

            .tables-container {
                grid-template-columns: 1fr;
            }

            .commission-breakdown {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .header-actions {
                width: 100%;
                justify-content: space-between;
            }

            .commission-breakdown {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
@section('content')
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div class="header-title">
                <h1>DreamX Dashboard</h1>
                <p>Track your multi-level marketing performance and commissions</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary"><i class="fas fa-sync"></i> Refresh Data</button>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-title">TOTAL SALES</div>
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
                    <div class="stat-title">TOTAL PROFIT</div>
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
                    <div class="stat-title">TOTAL COMMISSION</div>
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
                    <div class="stat-title">TOTAL SELLERS</div>
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
        </div>

        <div class="charts-container">
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Commission Distribution</div>
                    <div class="chart-actions">
                        <select class="btn btn-outline" style="padding: 8px 12px;">
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
                        <th>PRODUCT</th>
                        <th>PRICE</th>
                        <th>STOCK</th>
                        <th>STATUS</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Lenevo 3rd Generation</td>
                        <td>RS. 125,000</td>
                        <td>10</td>
                        <td><span class="badge badge-warning">LOW</span></td>
                    </tr>
                    <tr>
                        <td>Dell XPS 13</td>
                        <td>RS. 98,000</td>
                        <td>5</td>
                        <td><span class="badge badge-danger">CRITICAL</span></td>
                    </tr>
                    <tr>
                        <td>MacBook Pro 16"</td>
                        <td>RS. 220,000</td>
                        <td>8</td>
                        <td><span class="badge badge-warning">LOW</span></td>
                    </tr>
                    <tr>
                        <td>HP Spectre x360</td>
                        <td>RS. 112,000</td>
                        <td>3</td>
                        <td><span class="badge badge-danger">CRITICAL</span></td>
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
                        <th>ORDER NO</th>
                        <th>CUSTOMER</th>
                        <th>AMOUNT</th>
                        <th>STATUS</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>ORD/00001</td>
                        <td>Suranga</td>
                        <td>RS. 1,500</td>
                        <td><span class="badge badge-warning">PENDING</span></td>
                    </tr>
                    <tr>
                        <td>ORD/00015</td>
                        <td>Kamal</td>
                        <td>RS. 2,300</td>
                        <td><span class="badge badge-success">COMPLETED</span></td>
                    </tr>
                    <tr>
                        <td>ORD/00022</td>
                        <td>Nimal</td>
                        <td>RS. 4,500</td>
                        <td><span class="badge badge-warning">PENDING</span></td>
                    </tr>
                    <tr>
                        <td>ORD/00031</td>
                        <td>Sunil</td>
                        <td>RS. 3,200</td>
                        <td><span class="badge badge-success">COMPLETED</span></td>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Commission breakdown toggle functionality
            const commissionToggle = document.getElementById('commissionToggle');
            const commissionBreakdown = document.getElementById('commissionBreakdown');

            commissionToggle.addEventListener('click', function() {
                commissionBreakdown.classList.toggle('show');
                this.classList.toggle('active');

                const isExpanded = commissionBreakdown.classList.contains('show');
                this.querySelector('span').textContent = isExpanded
                    ? 'Hide Commission Breakdown'
                    : 'Show Commission Breakdown';
            });

            // Commission Distribution Chart
            const commissionCtx = document.getElementById('commissionChart').getContext('2d');
            const commissionChart = new Chart(commissionCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'Level 1 Commission',
                            data: [12000, 15000, 13000, 18000, 22000, 25000, 23000, 28000, 30000, 32000, 35000, 40000],
                            borderColor: '#4361ee',
                            backgroundColor: 'rgba(67, 97, 238, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Level 2 Commission',
                            data: [8000, 10000, 9000, 12000, 15000, 18000, 16000, 20000, 22000, 24000, 26000, 30000],
                            borderColor: '#4cc9f0',
                            backgroundColor: 'rgba(76, 201, 240, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Level 3 Commission',
                            data: [5000, 6000, 5500, 8000, 10000, 12000, 11000, 14000, 16000, 18000, 19000, 22000],
                            borderColor: '#f72585',
                            backgroundColor: 'rgba(247, 37, 133, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#1e293b',
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1e293b',
                            bodyColor: '#1e293b',
                            borderColor: '#4361ee',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                color: '#64748b'
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                color: '#64748b',
                                callback: function(value) {
                                    return 'RS. ' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Seller Distribution Chart
            const sellerCtx = document.getElementById('sellerChart').getContext('2d');
            const sellerChart = new Chart(sellerCtx, {
                type: 'doughnut',
                data: {
                    labels: ['System Sellers', 'Self Sellers'],
                    datasets: [{
                        data: [125000, 260656],
                        backgroundColor: [
                            'rgba(67, 97, 238, 0.8)',
                            'rgba(76, 201, 240, 0.8)'
                        ],
                        borderColor: [
                            'rgba(67, 97, 238, 1)',
                            'rgba(76, 201, 240, 1)'
                        ],
                        borderWidth: 2,
                        hoverOffset: 10
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
                                font: {
                                    size: 12
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#1e293b',
                            bodyColor: '#1e293b',
                            borderColor: '#4361ee',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value.toLocaleString()} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });

            // Seller type buttons functionality
            const sellerTypeBtns = document.querySelectorAll('.seller-type-btn');
            sellerTypeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    sellerTypeBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
@endpush
