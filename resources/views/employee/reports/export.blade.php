@extends('employee.layout.app')

@section('title', 'Export Reports')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Export Reports</h1>
        </div>

        <div class="row">
            <!-- Export Cards -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="fas fa-box fa-3x text-primary"></i>
                        </div>
                        <h4>Wholesaler Products</h4>
                        <p class="text-muted mb-4">Export all wholesaler products with prices and details</p>
                        <div class="btn-group w-100">
                            <button class="btn btn-outline-primary"
                                    onclick="exportReport('wholesaler_products', 'csv')">
                                <i class="fas fa-file-csv me-1"></i> CSV
                            </button>
                            <button class="btn btn-outline-success"
                                    onclick="exportReport('wholesaler_products', 'xlsx')">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="fas fa-shopping-cart fa-3x text-success"></i>
                        </div>
                        <h4>Wholesaler Orders</h4>
                        <p class="text-muted mb-4">Export all wholesaler orders with details</p>
                        <div class="btn-group w-100">
                            <button class="btn btn-outline-primary" onclick="exportReport('wholesaler_orders', 'csv')">
                                <i class="fas fa-file-csv me-1"></i> CSV
                            </button>
                            <button class="btn btn-outline-success" onclick="exportReport('wholesaler_orders', 'xlsx')">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="fas fa-truck fa-3x text-info"></i>
                        </div>
                        <h4>Seller Orders</h4>
                        <p class="text-muted mb-4">Export all seller orders with customer details</p>
                        <div class="btn-group w-100">
                            <button class="btn btn-outline-primary" onclick="exportReport('seller_orders', 'csv')">
                                <i class="fas fa-file-csv me-1"></i> CSV
                            </button>
                            <button class="btn btn-outline-success" onclick="exportReport('seller_orders', 'xlsx')">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Form -->
    <form id="exportForm" action="{{ route('employee.export.report') }}" method="GET" style="display: none;">
        <input type="hidden" name="type" id="exportType">
        <input type="hidden" name="format" id="exportFormat">
    </form>
@endsection

@section('scripts')
    <script>
        function exportReport(type, format) {
            $('#exportType').val(type);
            $('#exportFormat').val(format);
            $('#exportForm').submit();
        }
    </script>
@endsection
