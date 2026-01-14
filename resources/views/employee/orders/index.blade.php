@extends('employee.layout.app')

@push('title')
    Wholesaler Orders
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Wholesaler Orders</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Filter Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('employee.wholesaler.orders') }}"
                                       class="btn btn-outline-primary {{ request()->routeIs('employee.wholesaler.orders') && !request('status') ? 'active' : '' }}">
                                        All Orders
                                    </a>
                                    <a href="{{ route('employee.wholesaler.orders') }}?status=pending"
                                       class="btn btn-outline-warning {{ request('status') == 'pending' ? 'active' : '' }}">
                                        Pending
                                    </a>
                                    <a href="{{ route('employee.wholesaler.orders') }}?status=processing"
                                       class="btn btn-outline-info {{ request('status') == 'processing' ? 'active' : '' }}">
                                        Processing
                                    </a>
                                    <a href="{{ route('employee.wholesaler.orders') }}?status=shipped"
                                       class="btn btn-outline-success {{ request('status') == 'shipped' ? 'active' : '' }}">
                                        Shipped
                                    </a>
                                    <a href="{{ route('employee.wholesaler.orders') }}?status=completed"
                                       class="btn btn-outline-success {{ request('status') == 'completed' ? 'active' : '' }}">
                                        Completed
                                    </a>
                                    <a href="{{ route('employee.wholesaler.orders') }}?status=cancelled"
                                       class="btn btn-outline-danger {{ request('status') == 'cancelled' ? 'active' : '' }}">
                                        Cancelled
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Orders Table -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Wholesaler</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>#{{ $order->order_number }}</strong>
                                        </td>
                                        <td>
                                            {{ $order->wholesaler->shop_name ?? 'N/A' }}
                                            <br>
                                            <small class="text-muted">{{ $order->wholesaler->name ?? '' }}</small>
                                        </td>
                                        <td>
                                            {{ $order->customer_name }}
                                            <br>
                                            <small class="text-muted">{{ $order->customer_email }}</small>
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>{{ $order->items->count() }} items</td>
                                        <td>Rs. {{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'info')) }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('employee.wholesaler.order-view', $order->id) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-box-open fa-2x mb-2"></i>
                                                <h5>No orders found</h5>
                                                <p>No wholesaler orders available at the moment.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($orders->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
