@extends('employee.layout.app')

@push('title')
    Price History - {{ $product->name }}
@endpush

@push('css')
    <!-- DataTables + Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Toastr (optional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <style>
        .history-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .change-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .change-increase { background: #d1fae5; color: #065f46; }
        .change-decrease { background: #fee2e2; color: #991b1b; }

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }

        .stat-box {
            background: #f8fafc;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        /* DataTables buttons better look */
        .dt-buttons .dt-button {
            border-radius: 10px !important;
            border: 1px solid #e2e8f0 !important;
            background: #fff !important;
            padding: 8px 12px !important;
            margin-right: 6px !important;
        }
        .dt-buttons .dt-button:hover {
            background: #f1f5f9 !important;
        }

        .icon-inline {
            font-size: 18px;
            vertical-align: -4px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="history-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="mb-2">
                        <span class="material-icons-round icon-inline">history</span>
                        Price Change History
                    </h1>
                    <p class="mb-0 opacity-75">
                        <a href="{{ route('employee.wholesaler.products.show', $product->id) }}" class="text-white text-decoration-none">
                            <span class="material-icons-round icon-inline">arrow_back</span>
                            {{ $product->name }}
                        </a>
                    </p>
                </div>
                <div class="text-end">
                    <div class="bg-white bg-opacity-25 rounded-3 px-3 py-2">
                        <small>Product Code</small>
                        <h5 class="mb-0">{{ $product->code }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-label">Total Changes</div>
                    <div class="stat-value">{{ $priceChanges->total() }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-label">Average Change</div>
                    <div class="stat-value">{{ number_format($priceChanges->avg('percentage_change') ?? 0, 2) }}%</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-label">Net Change</div>
                    <div class="stat-value {{ ($priceChanges->sum('price_difference') ?? 0) > 0 ? 'text-success' : 'text-danger' }}">
                        Rs. {{ number_format($priceChanges->sum('price_difference') ?? 0, 2) }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-box">
                    <div class="stat-label">Last Change</div>
                    <div class="stat-value">{{ $priceChanges->first()?->created_at->diffForHumans() ?? 'Never' }}</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <form method="GET" action="{{ route('employee.wholesaler.products.price-history', $product->id) }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">
                            <span class="material-icons-round icon-inline">event</span>
                            Date From
                        </label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">
                            <span class="material-icons-round icon-inline">event</span>
                            Date To
                        </label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">
                            <span class="material-icons-round icon-inline">badge</span>
                            Employee
                        </label>
                        <select name="employee_id" class="form-select">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">
                            <span class="material-icons-round icon-inline">tune</span>
                            Change Type
                        </label>
                        <select name="change_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="manual" {{ request('change_type') == 'manual' ? 'selected' : '' }}>Manual</option>
                            <option value="bulk" {{ request('change_type') == 'bulk' ? 'selected' : '' }}>Bulk</option>
                            <option value="formula" {{ request('change_type') == 'formula' ? 'selected' : '' }}>Formula</option>
                        </select>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <span class="material-icons-round icon-inline">filter_alt</span>
                            Apply Filters
                        </button>

                        <button type="button" id="btnReset" class="btn btn-secondary">
                            <span class="material-icons-round icon-inline">restart_alt</span>
                            Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Price History Table -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-0">
                            <span class="material-icons-round icon-inline">list_alt</span>
                            History Records
                        </h5>
                        <small class="text-muted">Use search + export buttons if needed.</small>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="historyTable">
                        <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Employee</th>
                            <th>Old Price</th>
                            <th>New Price</th>
                            <th>Difference</th>
                            <th>Change %</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($priceChanges as $change)
                            <tr>
                                <td>
                                    {{ $change->created_at->format('M d, Y') }}
                                    <br><small class="text-muted">{{ $change->created_at->format('h:i A') }}</small>
                                </td>

                                <td>
                                    {{ $change->employee->name ?? 'N/A' }}
                                    @if($change->employee_id == Auth::guard('employee')->id())
                                        <br><span class="badge bg-primary">You</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="text-muted">Rs. {{ number_format($change->old_selling_price, 2) }}</span>
                                    @if($change->old_display_price != $change->old_selling_price)
                                        <br><small class="text-muted">Display: Rs. {{ number_format($change->old_display_price, 2) }}</small>
                                    @endif
                                </td>

                                <td>
                                    <strong>Rs. {{ number_format($change->new_selling_price, 2) }}</strong>
                                    @if($change->new_display_price != $change->new_selling_price)
                                        <br><small>Display: Rs. {{ number_format($change->new_display_price, 2) }}</small>
                                    @endif
                                </td>

                                <td>
                                    @if($change->price_difference > 0)
                                        <span class="badge change-increase">+Rs. {{ number_format($change->price_difference, 2) }}</span>
                                    @elseif($change->price_difference < 0)
                                        <span class="badge change-decrease">-Rs. {{ number_format(abs($change->price_difference), 2) }}</span>
                                    @else
                                        <span class="badge bg-secondary">No change</span>
                                    @endif
                                </td>

                                <td>
                                    @if($change->percentage_change > 0)
                                        <span class="text-success">+{{ number_format($change->percentage_change, 2) }}%</span>
                                    @elseif($change->percentage_change < 0)
                                        <span class="text-danger">{{ number_format($change->percentage_change, 2) }}%</span>
                                    @else
                                        0%
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-{{
                                        $change->change_type == 'manual' ? 'primary' :
                                        ($change->change_type == 'bulk' ? 'info' : 'secondary')
                                    }}">
                                        {{ ucfirst($change->change_type) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $change->change_reason ?? '-' }}
                                    @if($change->notes)
                                        <br>
                                        <small class="text-muted" title="{{ $change->notes }}">
                                            <span class="material-icons-round icon-inline">chat</span>
                                            Notes
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge bg-{{
                                        $change->status == 'approved' ? 'success' :
                                        ($change->status == 'pending' ? 'warning' :
                                        ($change->status == 'rejected' ? 'danger' : 'secondary'))
                                    }}">
                                        {{ ucfirst($change->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <span class="material-icons-round" style="font-size:48px;color:#94a3b8;">history</span>
                                    <h5 class="text-muted mt-2">No price changes found</h5>
                                    <p class="text-muted">This product has no price change history yet</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $priceChanges->firstItem() ?? 0 }} to {{ $priceChanges->lastItem() ?? 0 }}
                        of {{ $priceChanges->total() }} changes
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables + Buttons -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastr (optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function () {

            // DataTable (Laravel pagination ON, so keep paging OFF here)
            const dt = $('#historyTable').DataTable({
                paging: false,
                ordering: true,
                info: false,
                searching: true,
                order: [[0, 'desc']],
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copy', text: 'Copy' },
                    { extend: 'excel', text: 'Excel' },
                    { extend: 'pdf', text: 'PDF' },
                    { extend: 'print', text: 'Print' }
                ]
            });

            // SweetAlert Reset confirm
            $('#btnReset').on('click', function () {
                Swal.fire({
                    title: 'Reset filters?',
                    text: "This will remove all filter values.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, reset',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('employee.wholesaler.products.price-history', $product->id) }}";
                    }
                });
            });

            // Toastr messages from session (optional)
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
