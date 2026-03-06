@extends('employee.layout.app')

@push('title')
    Pending Price Changes
@endpush

@push('css')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Toastr (optional but nice for success/error messages) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <style>
        .pending-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }

        .approval-card {
            background: white;
            border-radius: 12px;
            border-left: 4px solid #f59e0b;
            margin-bottom: 1rem;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }

        .approval-card:hover { box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }

        .approval-card.approved { border-left-color: #10b981; opacity: 0.85; }
        .approval-card.rejected { border-left-color: #ef4444; opacity: 0.85; }

        .price-tag { font-size: 1.25rem; font-weight: 700; }
        .price-old { color: #94a3b8; text-decoration: line-through; font-size: 1rem; }
        .price-new { color: #10b981; }

        .action-buttons { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        .btn-approve {
            background: #10b981;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            transition: all 0.25s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-approve:hover { background: #059669; transform: translateY(-1px); }

        .btn-reject {
            background: #ef4444;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            transition: all 0.25s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-reject:hover { background: #dc2626; transform: translateY(-1px); }

        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .mi {
            font-size: 18px;
            vertical-align: -4px;
            margin-right: 6px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="pending-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-2">
                        <span class="material-icons-round mi">pending_actions</span>
                        Pending Approvals
                    </h1>
                    <p class="mb-0 opacity-75">
                        <span class="material-icons-round mi">rule</span>
                        Review and approve price change requests
                    </p>
                </div>
                <div class="text-end">
                    <div class="bg-white bg-opacity-25 rounded-3 px-3 py-2">
                        <small>Pending</small>
                        <h3 class="mb-0">{{ $pendingChanges->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <h6 class="text-muted mb-1">Total Pending</h6>
                    <h3 class="mb-0">{{ $pendingChanges->total() }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h6 class="text-muted mb-1">Average Change</h6>
                    <h3 class="mb-0">{{ number_format($pendingChanges->avg('percentage_change') ?? 0, 2) }}%</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h6 class="text-muted mb-1">Total Impact</h6>
                    <h3 class="mb-0 {{ ($pendingChanges->sum('price_difference') ?? 0) > 0 ? 'text-success' : 'text-danger' }}">
                        Rs. {{ number_format($pendingChanges->sum('price_difference') ?? 0, 2) }}
                    </h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h6 class="text-muted mb-1">Oldest Pending</h6>
                    <h3 class="mb-0">{{ $pendingChanges->first()?->created_at->diffForHumans() ?? 'N/A' }}</h3>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form method="GET" action="{{ route('employee.wholesaler.price-change.pending') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">
                            <span class="material-icons-round mi">store</span>
                            Wholesaler
                        </label>
                        <select name="wholesaler_id" class="form-select">
                            <option value="all">All Wholesalers</option>
                            @foreach($wholesalers as $wholesaler)
                                <option value="{{ $wholesaler->id }}" {{ request('wholesaler_id') == $wholesaler->id ? 'selected' : '' }}>
                                    {{ $wholesaler->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            <span class="material-icons-round mi">badge</span>
                            Employee
                        </label>
                        <select name="employee_id" class="form-select">
                            <option value="all">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">
                            <span class="material-icons-round mi">event</span>
                            Date From
                        </label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">
                            <span class="material-icons-round mi">event</span>
                            Date To
                        </label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <span class="material-icons-round mi">filter_alt</span>
                            Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Pending Changes List -->
        @forelse($pendingChanges as $change)
            <div class="approval-card" id="change-{{ $change->id }}">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <small class="text-muted">
                            <span class="material-icons-round mi">schedule</span>
                            {{ $change->created_at->format('M d, Y h:i A') }}
                        </small>

                        <h6 class="mb-1">{{ $change->product->name ?? 'N/A' }}</h6>
                        <small class="text-muted">{{ $change->product->code ?? '' }}</small>
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
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <small class="text-muted">Old Price</small>
                                <div class="price-old">Rs. {{ number_format($change->old_final_price, 2) }}</div>
                            </div>

                            <span class="material-icons-round text-muted">arrow_right_alt</span>

                            <div>
                                <small class="text-muted">New Price</small>
                                <div class="price-new price-tag">Rs. {{ number_format($change->new_final_price, 2) }}</div>
                            </div>
                        </div>

                        @if($change->price_difference != 0)
                            <small class="{{ $change->price_difference > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $change->price_difference > 0 ? '+' : '' }}{{ number_format($change->price_difference, 2) }}
                                ({{ number_format($change->percentage_change, 2) }}%)
                            </small>
                        @endif
                    </div>

                    <div class="col-md-2">
                        <div class="action-buttons">

                            <!-- Approve button (SweetAlert confirm) -->
                            <form action="{{ route('employee.wholesaler.price-change.approve', $change->id) }}"
                                  method="POST"
                                  class="approve-form"
                                  style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-approve">
                                    <span class="material-icons-round">check_circle</span>
                                    Approve
                                </button>
                            </form>

                            <!-- Reject button (SweetAlert input + submit hidden form) -->
                            <button type="button" class="btn-reject" onclick="rejectWithSwal({{ $change->id }})">
                                <span class="material-icons-round">cancel</span>
                                Reject
                            </button>

                            <!-- Hidden reject form (submitted by JS) -->
                            <form action="{{ url('employee/wholesaler/price-change') }}/{{ $change->id }}/reject"
                                  method="POST"
                                  id="rejectForm-{{ $change->id }}"
                                  style="display:none;">
                                @csrf
                                <input type="hidden" name="rejection_reason" id="rejectReason-{{ $change->id }}">
                            </form>
                        </div>

                        @if($change->change_reason)
                            <small class="text-muted d-block mt-1">
                                <span class="material-icons-round mi">info</span>
                                Reason: {{ $change->change_reason }}
                            </small>
                        @endif
                    </div>
                </div>

                @if($change->notes)
                    <div class="mt-2 p-2 bg-light rounded">
                        <small>
                            <span class="material-icons-round mi text-muted">chat</span>
                            {{ $change->notes }}
                        </small>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-5">
                <span class="material-icons-round" style="font-size:64px;color:#16a34a;">task_alt</span>
                <h4 class="text-muted mt-3">No Pending Approvals</h4>
                <p class="text-muted">All price changes have been processed</p>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $pendingChanges->appends(request()->query())->links() }}
        </div>
    </div>
@endsection

@push('script')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastr (optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // SweetAlert confirm for approve
        document.querySelectorAll('.approve-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Approve this change?',
                    text: 'This will update the product price after approval.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Approve',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // SweetAlert reject with textarea input
        function rejectWithSwal(changeId) {
            Swal.fire({
                title: 'Reject this change?',
                input: 'textarea',
                inputLabel: 'Reason for Rejection',
                inputPlaceholder: 'Type rejection reason...',
                inputAttributes: { 'aria-label': 'Reason for rejection' },
                inputValidator: (value) => {
                    if (!value || value.trim().length < 3) {
                        return 'Please enter a valid reason (min 3 characters).';
                    }
                },
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Reject',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ef4444'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('rejectReason-' + changeId).value = result.value.trim();
                    document.getElementById('rejectForm-' + changeId).submit();
                }
            });
        }

        // Toastr messages (optional)
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
    </script>
@endpush
