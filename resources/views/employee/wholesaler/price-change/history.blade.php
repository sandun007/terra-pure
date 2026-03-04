@extends('employee.layout.app')

@push('title')
    Price Change History
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .history-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .timeline {
            position: relative;
            padding: 1rem 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 1rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e2e8f0;
        }

        .timeline-item {
            position: relative;
            padding-left: 3rem;
            padding-bottom: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: #667eea;
            border: 2px solid white;
            z-index: 1;
        }

        .timeline-item.increase::before {
            background: #10b981;
        }

        .timeline-item.decrease::before {
            background: #ef4444;
        }

        .timeline-date {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .timeline-content {
            background: #f8fafc;
            border-radius: 10px;
            padding: 1rem;
            border: 1px solid #e2e8f0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.25rem;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.9rem;
        }

        .export-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 100;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="history-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">Price Change History</h1>
                    <p class="mb-0 opacity-75">Complete history of all price changes</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('employee.wholesaler.price-change.export') }}?{{ http_build_query(request()->query()) }}"
                       class="btn btn-light">
                        <i class="fas fa-download me-2"></i>Export Current View
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $priceChanges->total() }}</div>
                <div class="stat-label">Total Changes</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $priceChanges->where('price_difference', '>', 0)->count() }}</div>
                <div class="stat-label">Price Increases</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $priceChanges->where('price_difference', '<', 0)->count() }}</div>
                <div class="stat-label">Price Decreases</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">Rs. {{ number_format($priceChanges->sum('price_difference'), 2) }}</div>
                <div class="stat-label">Net Impact</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form method="GET" action="{{ route('employee.wholesaler.price-change.history') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Wholesaler</label>
                        <select name="wholesaler_id" class="form-select">
                            <option value="all">All</option>
                            @foreach($wholesalers as $wholesaler)
                                <option value="{{ $wholesaler->id }}" {{ request('wholesaler_id') == $wholesaler->id ? 'selected' : '' }}>
                                    {{ $wholesaler->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Employee</label>
                        <select name="employee_id" class="form-select">
                            <option value="all">All</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Product</label>
                        <select name="product_id" class="form-select">
                            <option value="all">All</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="all">All</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Apply Filters
                        </button>
                        <a href="{{ route('employee.wholesaler.price-change.history') }}" class="btn btn-secondary">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Timeline/Table View Toggle -->
        <div class="mb-3">
            <div class="btn-group">
                <button class="btn btn-outline-primary active" onclick="toggleView('timeline')" id="timelineBtn">
                    <i class="fas fa-stream me-2"></i>Timeline View
                </button>
                <button class="btn btn-outline-primary" onclick="toggleView('table')" id="tableBtn">
                    <i class="fas fa-table me-2"></i>Table View
                </button>
            </div>
        </div>

        <!-- Timeline View -->
        <div id="timelineView">
            <div class="timeline">
                @forelse($priceChanges as $change)
                    <div class="timeline-item {{ $change->price_difference > 0 ? 'increase' : ($change->price_difference < 0 ? 'decrease' : '') }}">
                        <div class="timeline-date">
                            <i class="far fa-calendar me-1"></i> {{ $change->created_at->format('M d, Y h:i A') }}
                            <span class="badge bg-{{
                        $change->status == 'approved' ? 'success' :
                        ($change->status == 'pending' ? 'warning' :
                        ($change->status == 'rejected' ? 'danger' : 'secondary'))
                    }} ms-2">{{ ucfirst($change->status) }}</span>
                        </div>
                        <div class="timeline-content">
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>{{ $change->product->name ?? 'N/A' }}</strong>
                                    <br><small class="text-muted">{{ $change->product->code ?? '' }}</small>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Wholesaler</small>
                                    <p class="mb-0">{{ $change->wholesaler->business_name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Employee</small>
                                    <p class="mb-0">{{ $change->employee->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Price Change</small>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted">Rs. {{ number_format($change->old_final_price, 2) }}</span>
                                        <i class="fas fa-arrow-right"></i>
                                        <span class="fw-bold text-primary">Rs. {{ number_format($change->new_final_price, 2) }}</span>
                                    </div>
                                    <small class="{{ $change->price_difference > 0 ? 'text-success' : ($change->price_difference < 0 ? 'text-danger' : '') }}">
                                        {{ $change->price_difference > 0 ? '+' : '' }}{{ number_format($change->price_difference, 2) }}
                                        ({{ number_format($change->percentage_change, 2) }}%)
                                    </small>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Type</small>
                                    <p class="mb-0">{{ ucfirst($change->change_type) }}</p>
                                    @if($change->change_reason)
                                        <small class="text-muted" title="{{ $change->change_reason }}">{{ Str::limit($change->change_reason, 20) }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="fas fa-history fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No Price Changes Found</h4>
                        <p class="text-muted">Try adjusting your filters</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Table View (Hidden by default) -->
        <div id="tableView" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="historyTable">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Wholesaler</th>
                                <th>Employee</th>
                                <th>Old Price</th>
                                <th>New Price</th>
                                <th>Change</th>
                                <th>%</th>
                                <th>Type</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($priceChanges as $change)
                                <tr>
                                    <td>{{ $change->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        {{ $change->product->name ?? 'N/A' }}
                                        <br><small>{{ $change->product->code ?? '' }}</small>
                                    </td>
                                    <td>{{ $change->wholesaler->business_name ?? 'N/A' }}</td>
                                    <td>{{ $change->employee->name ?? 'N/A' }}</td>
                                    <td>Rs. {{ number_format($change->old_final_price, 2) }}</td>
                                    <td>Rs. {{ number_format($change->new_final_price, 2) }}</td>
                                    <td class="{{ $change->price_difference > 0 ? 'text-success' : ($change->price_difference < 0 ? 'text-danger' : '') }}">
                                        {{ $change->price_difference > 0 ? '+' : '' }}{{ number_format($change->price_difference, 2) }}
                                    </td>
                                    <td class="{{ $change->percentage_change > 0 ? 'text-success' : ($change->percentage_change < 0 ? 'text-danger' : '') }}">
                                        {{ $change->percentage_change > 0 ? '+' : '' }}{{ number_format($change->percentage_change, 2) }}%
                                    </td>
                                    <td>{{ ucfirst($change->change_type) }}</td>
                                    <td>
                                    <span class="badge bg-{{
                                        $change->status == 'approved' ? 'success' :
                                        ($change->status == 'pending' ? 'warning' : 'danger')
                                    }}">
                                        {{ ucfirst($change->status) }}
                                    </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $priceChanges->appends(request()->query())->links() }}
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        function toggleView(view) {
            const timelineView = document.getElementById('timelineView');
            const tableView = document.getElementById('tableView');
            const timelineBtn = document.getElementById('timelineBtn');
            const tableBtn = document.getElementById('tableBtn');

            if (view === 'timeline') {
                timelineView.style.display = 'block';
                tableView.style.display = 'none';
                timelineBtn.classList.add('active');
                tableBtn.classList.remove('active');
            } else {
                timelineView.style.display = 'none';
                tableView.style.display = 'block';
                tableBtn.classList.add('active');
                timelineBtn.classList.remove('active');

                // Initialize DataTable if not already initialized
                if (!$.fn.DataTable.isDataTable('#historyTable')) {
                    $('#historyTable').DataTable({
                        paging: false,
                        ordering: true,
                        info: false,
                        order: [[0, 'desc']]
                    });
                }
            }
        }
    </script>
@endpush
