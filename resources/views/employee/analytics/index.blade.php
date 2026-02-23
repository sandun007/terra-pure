@extends('employee.layout.app')

@section('title', 'Analytics Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Analytics Dashboard</h1>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary" onclick="exportChart()">
                    <i class="fas fa-download me-1"></i> Export Chart
                </button>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Monthly Revenue Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>

        <div class="row g-4">
            <!-- Top Products -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Top Selling Products</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Code</th>
                                    <th>Quantity</th>
                                    <th>Revenue</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($topProducts as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->code }}</td>
                                        <td>{{ $product->total_quantity }}</td>
                                        <td>৳{{ number_format($product->total_revenue, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Wholesalers -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Top Wholesalers</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Wholesaler</th>
                                    <th>Email</th>
                                    <th>Orders</th>
                                    <th>Revenue</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($topWholesalers as $wholesaler)
                                    <tr>
                                        <td>{{ $wholesaler->name }}</td>
                                        <td>{{ $wholesaler->email }}</td>
                                        <td>{{ $wholesaler->total_orders }}</td>
                                        <td>৳{{ number_format($wholesaler->total_revenue, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Revenue Chart
            const ctx = document.getElementById('revenueChart').getContext('2d');

            const labels = {!! json_encode($monthlyRevenue->pluck('month')) !!};
            const data = {!! json_encode($monthlyRevenue->pluck('revenue')) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue (৳)',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return 'Revenue: ৳' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return '৳' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Export chart as image
            window.exportChart = function () {
                const canvas = document.getElementById('revenueChart');
                const link = document.createElement('a');
                link.download = 'revenue-chart-' + new Date().toISOString().split('T')[0] + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            };
        });
    </script>
@endsection
