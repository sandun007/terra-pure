@extends('employee.layout.app')

@push('title')
    Price Change Management
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Toastr (optional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <style>
        :root{
            --primary:#6366f1;
            --primary-dark:#4f46e5;
            --success:#10b981;
            --danger:#ef4444;
            --warning:#f59e0b;
            --info:#06b6d4;
            --card-bg:#ffffff;
            --border:#e5e7eb;
        }

        .mi{ font-size:18px; vertical-align:-4px; margin-right:8px; }

        /* Page header */
        .page-header{
            background: linear-gradient(135deg,#6366f1,#4f46e5);
            color:white;
            border-radius:18px;
            padding:24px;
            margin-bottom:20px;
            box-shadow:0 15px 30px rgba(0,0,0,0.15);
        }

        /* Stat cards */
        .stats-card{
            border-radius:16px;
            padding:20px;
            color:white;
            position:relative;
            overflow:hidden;
            box-shadow:0 10px 25px rgba(0,0,0,0.15);
        }

        .stats-card h2{ font-weight:800; }

        .stats-icon{
            position:absolute;
            right:15px;
            bottom:10px;
            font-size:4rem;
            opacity:0.2;
        }

        .bg-gradient-purple{ background:linear-gradient(135deg,#6366f1,#4f46e5); }
        .bg-gradient-warning{ background:linear-gradient(135deg,#f59e0b,#d97706); }
        .bg-gradient-success{ background:linear-gradient(135deg,#10b981,#059669); }
        .bg-gradient-info{ background:linear-gradient(135deg,#3b82f6,#2563eb); }

        /* Quick action cards */
        .quick-card{
            background:white;
            border-radius:14px;
            padding:18px;
            text-align:center;
            border:1px solid var(--border);
            transition:0.25s;
            cursor:pointer;
            text-decoration:none;
            color:inherit;
        }

        .quick-card:hover{
            transform:translateY(-4px);
            box-shadow:0 10px 20px rgba(0,0,0,0.12);
            border-color:var(--primary);
        }

        .quick-icon{
            width:50px;
            height:50px;
            border-radius:12px;
            display:flex;
            align-items:center;
            justify-content:center;
            margin:auto;
            margin-bottom:10px;
            background:#eef2ff;
            color:var(--primary);
            font-size:22px;
        }

        /* Filter box */
        .filter-card{
            background:white;
            padding:20px;
            border-radius:14px;
            border:1px solid var(--border);
            margin-bottom:20px;
            box-shadow:0 2px 6px rgba(0,0,0,0.05);
        }

        /* Table */
        .table-modern{
            background:white;
            border-radius:14px;
            overflow:hidden;
        }

        .table-modern th{
            background:#f9fafb;
            font-size:13px;
            font-weight:700;
            text-transform:uppercase;
            letter-spacing:0.5px;
        }

        .table-modern tbody tr:hover{ background:#f3f4f6; }

        /* badges */
        .badge-pending{ background:#fef3c7; color:#92400e; }
        .badge-approved{ background:#d1fae5; color:#065f46; }
        .badge-rejected{ background:#fee2e2; color:#991b1b; }

        /* price change */
        .price-up{ color:#10b981; font-weight:700; }
        .price-down{ color:#ef4444; font-weight:700; }

        /* empty */
        .empty-box{
            text-align:center;
            padding:60px;
            color:#9ca3af;
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid">

        <!-- HEADER -->
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1">
                    <span class="material-icons-round mi">swap_horiz</span>
                    Price Change Management
                </h3>
                <p class="mb-0 text-white-50">Manage and track wholesaler price updates</p>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('employee.wholesaler.price-changes.create') }}" class="btn btn-light">
                    <span class="material-icons-round mi">add</span>
                    New Change
                </a>

                <a href="{{ route('employee.wholesaler.price-changes.bulk.create') }}" class="btn btn-warning">
                    <span class="material-icons-round mi">layers</span>
                    Bulk Update
                </a>

                <a href="{{ route('employee.wholesaler.price-changes.export') }}" class="btn btn-dark export-btn">
                    <span class="material-icons-round mi">download</span>
                    Export
                </a>
            </div>
        </div>

        <!-- STATS -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stats-card bg-gradient-purple">
                    <h6>Total Changes</h6>
                    <h2>{{ $stats['total_changes'] }}</h2>
                    <small>{{ $stats['today_changes'] }} today</small>
                    <div class="stats-icon"><i class="fas fa-chart-line"></i></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stats-card bg-gradient-warning">
                    <h6>Pending</h6>
                    <h2>{{ $stats['pending_changes'] }}</h2>
                    <small>Awaiting approval</small>
                    <div class="stats-icon"><i class="fas fa-clock"></i></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stats-card bg-gradient-success">
                    <h6>Approved</h6>
                    <h2>{{ $stats['approved_changes'] }}</h2>
                    <small>Applied changes</small>
                    <div class="stats-icon"><i class="fas fa-check"></i></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stats-card bg-gradient-info">
                    <h6>Net Impact</h6>
                    <h2>Rs. {{ number_format($stats['total_increase'] - $stats['total_decrease'],2) }}</h2>
                    <small>↑ {{ number_format($stats['total_increase'],2) }} / ↓ {{ number_format($stats['total_decrease'],2) }}</small>
                    <div class="stats-icon"><i class="fas fa-dollar-sign"></i></div>
                </div>
            </div>
        </div>

        <!-- QUICK ACTIONS -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <a href="{{ route('employee.wholesaler.price-changes.pending') }}" class="text-decoration-none">
                    <div class="quick-card">
                        <div class="quick-icon">
                            <span class="material-icons-round">pending_actions</span>
                        </div>
                        <h6 class="mb-1">Pending Approvals</h6>
                        <span class="badge badge-pending">{{ $stats['pending_changes'] }}</span>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('employee.wholesaler.price-changes.history') }}" class="text-decoration-none">
                    <div class="quick-card">
                        <div class="quick-icon">
                            <span class="material-icons-round">history</span>
                        </div>
                        <h6 class="mb-0">History</h6>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('employee.wholesaler.products.index') }}" class="text-decoration-none">
                    <div class="quick-card">
                        <div class="quick-icon">
                            <span class="material-icons-round">inventory_2</span>
                        </div>
                        <h6 class="mb-0">Products</h6>
                    </div>
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('employee.wholesaler.price-changes.stats') }}" class="text-decoration-none">
                    <div class="quick-card">
                        <div class="quick-icon">
                            <span class="material-icons-round">analytics</span>
                        </div>
                        <h6 class="mb-0">Analytics</h6>
                    </div>
                </a>
            </div>
        </div>

        <!-- FILTER -->
        <div class="filter-card">
            <form method="GET" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Wholesaler</label>
                        <select name="wholesaler_id" class="form-select">
                            <option value="all">All</option>
                            @foreach($wholesalers as $w)
                                <option value="{{ $w->id }}" {{ request('wholesaler_id') == $w->id ? 'selected' : '' }}>
                                    {{ $w->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="all" {{ request('status','all')=='all'?'selected':'' }}>All</option>
                            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                            <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
                            <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Type</label>
                        <select name="change_type" class="form-select">
                            <option value="all" {{ request('change_type','all')=='all'?'selected':'' }}>All</option>
                            <option value="manual" {{ request('change_type')=='manual'?'selected':'' }}>Manual</option>
                            <option value="bulk" {{ request('change_type')=='bulk'?'selected':'' }}>Bulk</option>
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

                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <span class="material-icons-round">filter_alt</span>
                        </button>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-secondary" id="btnReset">
                            <span class="material-icons-round mi">restart_alt</span>
                            Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- TABLE -->
        <div class="card table-modern">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="priceChangesTable">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Wholesaler</th>
                            <th>Employee</th>
                            <th>Old</th>
                            <th>New</th>
                            <th>Change</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($priceChanges as $change)
                            <tr>
                                <td>
                                    {{ $change->created_at->format('M d') }}<br>
                                    <small class="text-muted">{{ $change->created_at->format('H:i') }}</small>
                                </td>

                                <td>
                                    <strong>{{ $change->product->name ?? '' }}</strong><br>
                                    <small>{{ $change->product->code ?? '' }}</small>
                                </td>

                                <td>{{ $change->wholesaler->business_name ?? '' }}</td>

                                <td>
                                    {{ $change->employee->name ?? '' }}
                                    @if(isset($employee) && $change->employee_id == $employee->id)
                                        <span class="badge bg-primary">You</span>
                                    @endif
                                </td>

                                <td class="text-muted">Rs. {{ number_format($change->old_final_price,2) }}</td>

                                <td><strong>Rs. {{ number_format($change->new_final_price,2) }}</strong></td>

                                <td>
                                    @if($change->price_difference > 0)
                                        <span class="price-up">+Rs {{ number_format($change->price_difference,2) }}</span>
                                    @elseif($change->price_difference < 0)
                                        <span class="price-down">-Rs {{ number_format(abs($change->price_difference),2) }}</span>
                                    @else
                                        <span class="text-muted">No Change</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-info">{{ ucfirst($change->change_type) }}</span>
                                </td>

                                <td>
                                    <span class="badge bg-{{
                                        $change->status=='approved'?'success':
                                        ($change->status=='pending'?'warning':'danger')
                                    }}">
                                        {{ ucfirst($change->status) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('employee.wholesaler.products.show',$change->product_id) }}"
                                           class="btn btn-sm btn-info view-btn"
                                           data-product="{{ $change->product->name ?? 'Product' }}">
                                            <span class="material-icons-round" style="font-size:16px;">visibility</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">
                                    <div class="empty-box">
                                        <span class="material-icons-round" style="font-size:56px;">sell</span>
                                        <h5 class="mt-2">No Price Changes Found</h5>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <div>
                        Showing {{ $priceChanges->firstItem() ?? 0 }} to {{ $priceChanges->lastItem() ?? 0 }}
                    </div>

                    <div>
                        {{ $priceChanges->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastr (optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function () {

            // DataTable: keep paging OFF because Laravel pagination is ON
            $('#priceChangesTable').DataTable({
                paging: false,
                ordering: true,
                info: false,
                searching: true,
                order: [[0, 'desc']]
            });

            // Reset confirm (SweetAlert)
            $('#btnReset').on('click', function () {
                Swal.fire({
                    title: 'Reset filters?',
                    text: 'This will remove all filter values.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, reset',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ url()->current() }}";
                    }
                });
            });

            // Export confirm (SweetAlert)
            document.querySelectorAll('.export-btn').forEach(btn => {
                btn.addEventListener('click', function(e){
                    e.preventDefault();
                    Swal.fire({
                        title: 'Export price changes?',
                        text: 'You will download the report file.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Download',
                        cancelButtonText: 'Cancel'
                    }).then((r)=>{
                        if(r.isConfirmed){
                            window.location.href = btn.getAttribute('href');
                        }
                    });
                });
            });

            // Optional: view button popup (nice UX)
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', function(e){
                    // If you want direct navigation WITHOUT popup, remove this whole block.
                    e.preventDefault();
                    const href = btn.getAttribute('href');
                    const product = btn.dataset.product || 'Product';

                    Swal.fire({
                        title: 'Open product details?',
                        text: `View details for: ${product}`,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Open',
                        cancelButtonText: 'Cancel'
                    }).then((r)=>{
                        if(r.isConfirmed){
                            window.location.href = href;
                        }
                    });
                });
            });

            // Toastr flash messages (optional)
            @if(session('success'))
            toastr.success(@json(session('success')));
            @endif
            @if(session('error'))
            toastr.error(@json(session('error')));
            @endif
            @if(session('warning'))
            toastr.warning(@json(session('warning')));
            @endif
            @if(session('info'))
            toastr.info(@json(session('info')));
            @endif
        });
    </script>
@endpush
