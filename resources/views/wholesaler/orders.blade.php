@extends('wholesaler.app')
@push('title')
    My Orders
@endpush
@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .page-title h4 {
            color: white;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .page-title h6 {
            color: rgba(255,255,255,0.9);
            font-weight: 500;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-body {
            padding: 30px;
        }

        .table thead th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
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

        .badge {
            padding: 8px 16px;
            font-weight: 600;
            font-size: 12px;
            border-radius: 50px;
        }

        .bg-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; }
        .bg-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important; }
        .bg-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important; }
        .bg-info { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important; }

        .btn-sm {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .order-filter {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .filter-badge {
            padding: 10px 20px;
            margin: 5px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .filter-badge.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="add-item d-flex justify-content-between align-items-center">
            <div class="page-title">
                <h4 class="fw-bold">Order Management</h4>
                <h6>Total Orders: {{ $orders->count() }}</h6>
            </div>
        </div>
    </div>

    <!-- Order Filters -->
    <div class="order-filter">
        <div class="row">
            <div class="col-12">
                <h6 class="mb-3">Filter by Status:</h6>
                <div class="d-flex flex-wrap">
                    <a href="{{ route('wholesaler.orders') }}" class="filter-badge {{ !request()->has('status') ? 'active' : 'bg-light text-dark' }}">
                        All Orders
                    </a>
                    <a href="{{ route('wholesaler.orders') }}?status=confirmed" class="filter-badge {{ request()->get('status') == 'confirmed' ? 'active' : 'bg-light text-dark' }}">
                        <i class="fas fa-check-circle me-2"></i>Confirmed
                    </a>
                    <a href="{{ route('wholesaler.orders') }}?status=pending" class="filter-badge {{ request()->get('status') == 'pending' ? 'active' : 'bg-light text-dark' }}">
                        <i class="fas fa-clock me-2"></i>Pending
                    </a>
                    <a href="{{ route('wholesaler.orders') }}?status=processing" class="filter-badge {{ request()->get('status') == 'processing' ? 'active' : 'bg-light text-dark' }}">
                        <i class="fas fa-cog me-2"></i>Processing
                    </a>
                    <a href="{{ route('wholesaler.orders') }}?status=completed" class="filter-badge {{ request()->get('status') == 'completed' ? 'active' : 'bg-light text-dark' }}">
                        <i class="fas fa-check me-2"></i>Completed
                    </a>
                    <a href="{{ route('wholesaler.orders') }}?status=cancelled" class="filter-badge {{ request()->get('status') == 'cancelled' ? 'active' : 'bg-light text-dark' }}">
                        <i class="fas fa-times me-2"></i>Cancelled
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="order-table" class="display table table-striped" style="width:100%">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Items</th>
                            <th>Status</th>
                            <th>Order Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>
                                    <i class="fas fa-user-circle me-2" style="color: #667eea;"></i>
                                    {{ $order->customer->name ?? 'N/A' }}
                                    @if($order->customer->phone ?? false)
                                        <br><small class="text-muted">{{ $order->customer->phone }}</small>
                                    @endif
                                </td>
                                <td><strong>RS. {{ number_format($order->total_amount, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ $order->orderItems->count() }} items</span>
                                </td>
                                <td>
                                    <span class="badge
                                        @if($order->status == 'confirmed') bg-success
                                        @elseif($order->status == 'pending') bg-warning
                                        @elseif($order->status == 'processing') bg-info
                                        @elseif($order->status == 'completed') bg-success
                                        @elseif($order->status == 'cancelled') bg-danger
                                        @else bg-secondary @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <i class="far fa-calendar-alt me-2 text-muted"></i>
                                    {{ $order->created_at->format('M d, Y') }}
                                    <br>
                                    <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('wholesaler.orders.details', $order->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal{{ $order->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>

                                    <!-- Status Update Modal -->
                                    <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Update Order Status</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('wholesaler.orders.status.update', $order->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Order Status</label>
                                                            <select name="status" class="form-select">
                                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update Status</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No orders found</h5>
                                    <p class="text-muted">When you receive orders, they will appear here</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#order-table').DataTable({
                "order": [[0, "desc"]],
                "language": {
                    "emptyTable": "No orders available",
                    "zeroRecords": "No matching orders found"
                }
            });
        });
    </script>
@endpush
