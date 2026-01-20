@extends('employee.layout.app')
@push('title')
    Export Reports | Employee Dashboard
@endpush

@push('css')
    <style>
        .export-container {
            padding: 30px 0;
        }

        .export-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .export-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .export-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .icon-purple { background: linear-gradient(135deg, #a78bfa, #8b5cf6); color: white; }
        .icon-blue { background: linear-gradient(135deg, #60a5fa, #3b82f6); color: white; }
        .icon-green { background: linear-gradient(135deg, #34d399, #10b981); color: white; }
        .icon-orange { background: linear-gradient(135deg, #fb923c, #f97316); color: white; }

        .export-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .export-description {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 20px;
            min-height: 40px;
        }

        .export-options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-export {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-export-csv {
            background: #10b981;
            color: white;
        }

        .btn-export-csv:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        .btn-export-excel {
            background: #3b82f6;
            color: white;
        }

        .btn-export-excel:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        .btn-export-pdf {
            background: #ef4444;
            color: white;
        }

        .btn-export-pdf:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .date-filters {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .filter-title {
            font-size: 16px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 15px;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #64748b;
        }

        @media (max-width: 768px) {
            .export-options {
                flex-direction: column;
            }

            .btn-export {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="export-container">
        <div class="container">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0">📊 Export Reports</h1>
                        <a href="{{ route('employee.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                        </a>
                    </div>
                    <p class="text-muted mb-0">Generate and download various reports in different formats</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-value">{{ $stats['total_orders'] ?? 0 }}</div>
                    <div class="stat-label">Total Orders</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">{{ $stats['total_products'] ?? 0 }}</div>
                    <div class="stat-label">Total Products</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">Rs. {{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                    <div class="stat-label">Total Revenue</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">{{ $stats['price_changes'] ?? 0 }}</div>
                    <div class="stat-label">Price Changes</div>
                </div>
            </div>

            <!-- Date Filters -->
            <div class="date-filters">
                <div class="filter-title">📅 Filter by Date Range</div>
                <form id="exportFilterForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">From Date</label>
                            <input type="date" class="form-control" id="dateFrom" name="date_from">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">To Date</label>
                            <input type="date" class="form-control" id="dateTo" name="date_to">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" onclick="applyDateFilters()">
                                <i class="fas fa-filter me-2"></i> Apply Filters
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Export Cards -->
            <div class="row">
                <!-- Wholesaler Orders Report -->
                <div class="col-md-6 col-lg-4">
                    <div class="export-card">
                        <div class="export-icon icon-blue">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="export-title">Wholesaler Orders</div>
                        <div class="export-description">
                            Export all wholesaler orders with customer details, items, and payment information.
                        </div>
                        <div class="export-options">
                            <a href="{{ route('employee.export.report') }}?type=wholesaler_orders&format=csv"
                               class="btn-export btn-export-csv">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                            <a href="{{ route('employee.wholesaler_orders.export') }}"
                               class="btn-export btn-export-excel">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Seller Orders Report -->
                <div class="col-md-6 col-lg-4">
                    <div class="export-card">
                        <div class="export-icon icon-green">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="export-title">Seller Orders</div>
                        <div class="export-description">
                            Export all seller orders with product details, quantities, and order status.
                        </div>
                        <div class="export-options">
                            <a href="{{ route('employee.export.report') }}?type=seller_orders&format=csv"
                               class="btn-export btn-export-csv">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Price Changes Report -->
                <div class="col-md-6 col-lg-4">
                    <div class="export-card">
                        <div class="export-icon icon-purple">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="export-title">Price Changes History</div>
                        <div class="export-description">
                            Export complete history of all price changes made by employees.
                        </div>
                        <div class="export-options">
                            <a href="{{ route('price-changes.export') }}"
                               class="btn-export btn-export-csv">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Products Report -->
                <div class="col-md-6 col-lg-4">
                    <div class="export-card">
                        <div class="export-icon icon-orange">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="export-title">Products Catalog</div>
                        <div class="export-description">
                            Export complete product catalog with prices, categories, and stock information.
                        </div>
                        <div class="export-options">
                            <a href="{{ route('employee.export.report') }}?type=products&format=csv"
                               class="btn-export btn-export-csv">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Revenue Report -->
                <div class="col-md-6 col-lg-4">
                    <div class="export-card">
                        <div class="export-icon icon-green">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="export-title">Revenue Report</div>
                        <div class="export-description">
                            Export detailed revenue report with daily, weekly, and monthly breakdowns.
                        </div>
                        <div class="export-options">
                            <a href="{{ route('employee.export.report') }}?type=revenue&format=csv"
                               class="btn-export btn-export-csv">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Inventory Report -->
                <div class="col-md-6 col-lg-4">
                    <div class="export-card">
                        <div class="export-icon icon-blue">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="export-title">Inventory Report</div>
                        <div class="export-description">
                            Export inventory status including stock levels, low stock items, and reorder suggestions.
                        </div>
                        <div class="export-options">
                            <a href="{{ route('employee.export.report') }}?type=inventory&format=csv"
                               class="btn-export btn-export-csv">
                                <i class="fas fa-file-csv"></i> CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Export Section -->
            <div class="export-card mt-4">
                <h5 class="mb-3">🚀 Bulk Export Options</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll">
                            <label class="form-check-label fw-medium" for="selectAll">
                                Select All Reports
                            </label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <button type="button" class="btn btn-primary" onclick="bulkExport()">
                            <i class="fas fa-download me-2"></i> Download Selected Reports
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearSelection()">
                            <i class="fas fa-times me-2"></i> Clear Selection
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function applyDateFilters() {
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;

            if (!dateFrom || !dateTo) {
                alert('Please select both date ranges');
                return;
            }

            // Add date parameters to all export links
            document.querySelectorAll('.btn-export').forEach(link => {
                const url = new URL(link.href);
                url.searchParams.set('date_from', dateFrom);
                url.searchParams.set('date_to', dateTo);
                link.href = url.toString();
            });

            alert('Date filters applied to all exports');
        }

        function bulkExport() {
            const selectedReports = [];
            document.querySelectorAll('.form-check-input:checked').forEach(checkbox => {
                if (checkbox.id !== 'selectAll') {
                    selectedReports.push(checkbox.value);
                }
            });

            if (selectedReports.length === 0) {
                alert('Please select at least one report to export');
                return;
            }

            Swal.fire({
                title: 'Bulk Export',
                text: `You are about to export ${selectedReports.length} reports. This may take a few moments.`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Continue',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a zip file or multiple downloads
                    selectedReports.forEach(report => {
                        const link = document.createElement('a');
                        link.href = report;
                        link.target = '_blank';
                        link.click();
                    });
                }
            });
        }

        function clearSelection() {
            document.querySelectorAll('.form-check-input').forEach(checkbox => {
                checkbox.checked = false;
            });
        }

        // Select All functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.form-check-input').forEach(checkbox => {
                if (checkbox.id !== 'selectAll') {
                    checkbox.checked = isChecked;
                }
            });
        });

        // Add checkboxes to export cards
        document.addEventListener('DOMContentLoaded', function() {
            const exportCards = document.querySelectorAll('.export-card');
            exportCards.forEach((card, index) => {
                const links = card.querySelectorAll('.btn-export');
                if (links.length > 0) {
                    const firstLink = links[0].href;
                    const checkboxHtml = `
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox"
                                   value="${firstLink}"
                                   id="report${index}">
                            <label class="form-check-label small text-muted"
                                   for="report${index}">
                                Include in bulk export
                            </label>
                        </div>
                    `;
                    card.querySelector('.export-options').insertAdjacentHTML('afterend', checkboxHtml);
                }
            });
        });
    </script>
@endpush
