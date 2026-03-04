@extends('employee.layout.app')

@push('title')
    Product Exchange Management
@endpush

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Google Material Icons (Round) -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root{
            --primary:#6366f1;
            --primary2:#4f46e5;
            --success:#10b981;
            --warning:#f59e0b;
            --danger:#ef4444;
            --muted:#6b7280;
            --card:#ffffff;
            --border:rgba(0,0,0,.08);
            --shadow:0 18px 45px rgba(0,0,0,.12);
            --shadow2:0 10px 28px rgba(0,0,0,.10);
            --radius:18px;
        }

        /* google icon helper */
        .gicon{
            font-family: 'Material Icons Round';
            font-weight: normal;
            font-style: normal;
            font-size: 20px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            vertical-align: -4px;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }

        /* HERO */
        .hero{
            border-radius: 24px;
            padding: 22px;
            margin-bottom: 18px;
            background:
                radial-gradient(900px 260px at 10% 0%, rgba(245,158,11,.26), transparent 55%),
                radial-gradient(900px 260px at 90% 15%, rgba(99,102,241,.18), transparent 55%),
                linear-gradient(135deg, rgba(255,255,255,.88), rgba(255,255,255,.72));
            border: 1px solid rgba(255,255,255,.55);
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }
        .hero::after{
            content:"";
            position:absolute;
            inset:-120px -120px auto auto;
            width:260px;height:260px;border-radius:999px;
            background: rgba(245,158,11,.14);
            filter: blur(2px);
            transform: rotate(12deg);
        }
        .hero-title{ font-weight: 950; margin:0; letter-spacing:.2px; }
        .hero-sub{ color: var(--muted); margin:6px 0 0 0; }

        .hero-actions .btn{
            border-radius: 14px;
            font-weight: 900;
            padding: 10px 14px;
            border: 1px solid rgba(0,0,0,.08);
        }

        /* STATS */
        .stats{
            margin-top: 14px;
        }
        .stat-card{
            background: var(--card);
            border-radius: 18px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow2);
            padding: 14px;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after{
            content:"";
            position:absolute;
            right:-40px; top:-40px;
            width:120px; height:120px;
            border-radius: 999px;
            background: rgba(0,0,0,.03);
        }
        .stat-k{ color: var(--muted); font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing:.5px; }
        .stat-v{ font-weight: 950; font-size: 22px; margin: 6px 0 0 0; }
        .stat-ico{
            position:absolute;
            right: 12px; bottom: 12px;
            width: 40px; height: 40px;
            border-radius: 14px;
            display:flex; align-items:center; justify-content:center;
            background: rgba(0,0,0,.04);
            color: #111827;
        }
        .stat-ico.warning{ background: rgba(245,158,11,.16); color:#92400e; }
        .stat-ico.success{ background: rgba(16,185,129,.14); color:#065f46; }
        .stat-ico.primary{ background: rgba(99,102,241,.14); color: var(--primary2); }

        /* FILTERS */
        .filter-card{
            background: var(--card);
            border-radius: 18px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow2);
            padding: 16px;
            margin: 18px 0;
        }
        .filter-card .form-control, .filter-card .form-select{
            border-radius: 14px !important;
            border: 1px solid rgba(0,0,0,.08) !important;
        }

        /* LIST CARD */
        .xcard{
            background: var(--card);
            border-radius: 18px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow2);
            padding: 16px;
            margin-bottom: 12px;
            transition: .18s ease;
            position: relative;
            overflow: hidden;
        }
        .xcard:hover{
            transform: translateY(-2px);
            box-shadow: 0 14px 35px rgba(0,0,0,.14);
        }
        .xbar{
            position:absolute;
            left:0; top:0; bottom:0;
            width: 5px;
            background: rgba(245,158,11,.9);
        }
        .xbar.completed{ background: rgba(16,185,129,.95); }
        .xbar.pending{ background: rgba(245,158,11,.95); }
        .xbar.approved{ background: rgba(59,130,246,.95); }
        .xbar.cancelled{ background: rgba(239,68,68,.95); }

        .badge-soft{
            display:inline-flex;
            align-items:center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 900;
            font-size: 12px;
            border: 1px solid rgba(0,0,0,.06);
            background: rgba(0,0,0,.03);
            color: #111827;
            white-space: nowrap;
        }
        .badge-soft.store{ background: rgba(99,102,241,.12); color: var(--primary2); }
        .badge-soft.code{ background: rgba(0,0,0,.04); color:#334155; }
        .badge-soft.qty{ background: rgba(245,158,11,.16); color:#92400e; }
        .badge-soft.type{ background: rgba(6,182,212,.14); color:#0e7490; }

        .status-pill{
            display:inline-flex;
            align-items:center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: 999px;
            font-weight: 950;
            font-size: 12px;
            border: 1px solid rgba(0,0,0,.06);
        }
        .status-pill.pending{ background: rgba(245,158,11,.18); color:#92400e; }
        .status-pill.approved{ background: rgba(59,130,246,.18); color:#1e40af; }
        .status-pill.completed{ background: rgba(16,185,129,.16); color:#065f46; }
        .status-pill.cancelled{ background: rgba(239,68,68,.16); color:#991b1b; }

        .path{
            display:flex;
            align-items:center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .path .arrow{
            width: 42px;
            height: 42px;
            border-radius: 16px;
            background: rgba(0,0,0,.04);
            display:flex;
            align-items:center;
            justify-content:center;
            color:#475569;
        }

        .value-box{
            background: rgba(0,0,0,.03);
            border: 1px solid rgba(0,0,0,.06);
            border-radius: 16px;
            padding: 12px;
            height: 100%;
        }
        .value-box small{ color: var(--muted); font-weight: 800; text-transform: uppercase; letter-spacing:.4px; }
        .value-box p{ margin: 6px 0 0 0; font-weight: 950; }

        .note{
            margin-top: 10px;
            padding: 10px 12px;
            border-radius: 16px;
            background: rgba(0,0,0,.03);
            border: 1px solid rgba(0,0,0,.06);
            color:#334155;
        }

        .btnx{
            border-radius: 14px;
            font-weight: 950;
            padding: 9px 12px;
            border: 1px solid rgba(0,0,0,.08);
            display:inline-flex;
            align-items:center;
            gap: 8px;
        }
        .btnx-outline{
            background:#fff;
            color:#111827;
        }
        .btnx-approve{
            background: rgba(16,185,129,.14);
            color:#065f46;
            border-color: rgba(16,185,129,.30);
        }

        .empty{
            background: var(--card);
            border-radius: 18px;
            border: 1px dashed rgba(0,0,0,.20);
            padding: 32px;
            text-align:center;
            box-shadow: var(--shadow2);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <!-- HERO -->
        <div class="hero">
            <div class="d-flex justify-content-between align-items-start align-items-md-center flex-column flex-md-row gap-3">
                <div>
                    <h3 class="hero-title">Product Exchange Management</h3>
                    <p class="hero-sub">Manage and track product exchanges between wholesalers.</p>
                </div>

                <div class="hero-actions d-flex gap-2 flex-wrap">
                    <a href="{{ route('employee.wholesaler.exchanges.create') }}" class="btn btn-light">
                        <span class="gicon">add</span> New Exchange
                    </a>
                    <a href="{{ route('employee.wholesaler.exchanges.export') }}" class="btn btn-light">
                        <span class="gicon">download</span> Export
                    </a>
                </div>
            </div>

            <!-- STATS -->
            <div class="row g-3 stats">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-k">Total Exchanges</div>
                        <div class="stat-v">{{ $stats['total_exchanges'] }}</div>
                        <div class="stat-ico primary"><span class="gicon">sync_alt</span></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-k">Pending</div>
                        <div class="stat-v" style="color:#92400e;">{{ $stats['pending_exchanges'] }}</div>
                        <div class="stat-ico warning"><span class="gicon">schedule</span></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-k">Completed</div>
                        <div class="stat-v" style="color:#065f46;">{{ $stats['completed_exchanges'] }}</div>
                        <div class="stat-ico success"><span class="gicon">check_circle</span></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-k">Value Transferred</div>
                        <div class="stat-v">Rs. {{ number_format($stats['total_value_transferred'], 2) }}</div>
                        <div class="stat-ico warning"><span class="gicon">payments</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTERS -->
        <div class="filter-card">
            <form method="GET" action="{{ route('employee.wholesaler.exchanges.index') }}">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="all">All</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Source</label>
                        <select name="source_wholesaler_id" class="form-select">
                            <option value="all">All</option>
                            @foreach($wholesalers as $wholesaler)
                                <option value="{{ $wholesaler->id }}" {{ request('source_wholesaler_id') == $wholesaler->id ? 'selected' : '' }}>
                                    {{ $wholesaler->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Destination</label>
                        <select name="destination_wholesaler_id" class="form-select">
                            <option value="all">All</option>
                            @foreach($wholesalers as $wholesaler)
                                <option value="{{ $wholesaler->id }}" {{ request('destination_wholesaler_id') == $wholesaler->id ? 'selected' : '' }}>
                                    {{ $wholesaler->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Employee</label>
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
                        <label class="form-label fw-bold">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-bold">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold">Search</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="Search by product name or code..."
                               value="{{ request('search') }}">
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary" style="border-radius:14px;font-weight:950;">
                            <span class="gicon">filter_alt</span> Apply Filters
                        </button>
                        <a href="{{ route('employee.wholesaler.exchanges.index') }}" class="btn btn-secondary" style="border-radius:14px;font-weight:950;">
                            <span class="gicon">restart_alt</span> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- LIST -->
        @forelse($exchanges as $exchange)
            <div class="xcard">
                <div class="xbar {{ $exchange->status }}"></div>

                <div class="row g-3 align-items-start">
                    <!-- Path -->
                    <div class="col-lg-6">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                            <div class="path">
                                <div>
                                    <small class="text-muted fw-bold">Source</small>
                                    <div style="font-weight:950;">{{ $exchange->sourceProduct->name ?? 'N/A' }}</div>
                                    <div class="d-flex gap-2 flex-wrap mt-2">
                                        <span class="badge-soft code"><span class="gicon">qr_code_2</span> {{ $exchange->sourceProduct->code ?? '' }}</span>
                                        <span class="badge-soft store"><span class="gicon">store</span> {{ $exchange->sourceWholesaler->business_name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="arrow">
                                    <span class="gicon">trending_flat</span>
                                </div>

                                <div>
                                    <small class="text-muted fw-bold">Destination</small>
                                    <div style="font-weight:950;">{{ $exchange->destinationProduct->name ?? 'N/A' }}</div>
                                    <div class="d-flex gap-2 flex-wrap mt-2">
                                        <span class="badge-soft code"><span class="gicon">qr_code_2</span> {{ $exchange->destinationProduct->code ?? '' }}</span>
                                        <span class="badge-soft store"><span class="gicon">store</span> {{ $exchange->destinationWholesaler->business_name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                <span class="badge-soft qty"><span class="gicon">inventory_2</span> {{ $exchange->exchange_quantity }} units</span>
                                <span class="badge-soft type"><span class="gicon">category</span> {{ ucfirst($exchange->exchange_type) }}</span>
                            </div>
                        </div>

                        @if($exchange->notes)
                            <div class="note">
                                <span class="gicon">comment</span>
                                <span class="ms-1">{{ $exchange->notes }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Value -->
                    <div class="col-lg-3">
                        <div class="value-box">
                            <small>Value</small>
                            <p>Rs. {{ number_format($exchange->destination_price_at_time, 2) }}</p>
                        </div>
                        <div class="value-box mt-2">
                            <small>Difference</small>
                            <p class="{{ $exchange->price_difference > 0 ? 'text-success' : ($exchange->price_difference < 0 ? 'text-danger' : '') }}">
                                {{ $exchange->price_difference > 0 ? '+' : '' }}{{ number_format($exchange->price_difference, 2) }}
                            </p>
                        </div>
                    </div>

                    <!-- Meta + Actions -->
                    <div class="col-lg-3">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <div class="status-pill {{ $exchange->status }}">
                                <span class="gicon">
                                    @if($exchange->status=='completed') check_circle
                                    @elseif($exchange->status=='pending') schedule
                                    @elseif($exchange->status=='approved') verified
                                    @elseif($exchange->status=='cancelled') cancel
                                    @else info
                                    @endif
                                </span>
                                    {{ ucfirst($exchange->status) }}
                                </div>

                                <div class="mt-2">
                                    <small class="text-muted fw-bold">Date</small>
                                    <div style="font-weight:950;">{{ $exchange->created_at->format('M d, Y') }}</div>
                                    <small class="text-muted">{{ $exchange->created_at->format('h:i A') }}</small>
                                    @if($exchange->employee)
                                        <div class="mt-1">
                                            <small class="text-muted">by {{ $exchange->employee->name }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex gap-2 flex-wrap justify-content-end">
                                <a href="{{ route('employee.wholesaler.exchanges.show', $exchange->id) }}"
                                   class="btnx btnx-outline">
                                    <span class="gicon">visibility</span> View
                                </a>

                                @if($exchange->status == 'pending')
                                    <form action="{{ route('employee.wholesaler.exchanges.approve', $exchange->id) }}"
                                          method="POST"
                                          class="d-inline approve-form">
                                        @csrf
                                        <button type="submit" class="btnx btnx-approve">
                                            <span class="gicon">check</span> Approve
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="empty">
                <div style="font-size:46px; opacity:.8;">
                    <span class="gicon" style="font-size:52px;">swap_horiz</span>
                </div>
                <h4 class="mt-2 mb-1" style="font-weight:950;color:#334155;">No Exchanges Found</h4>
                <p class="text-muted mb-3">Start by creating a new product exchange.</p>
                <a href="{{ route('employee.wholesaler.exchanges.create') }}" class="btn btn-primary" style="border-radius:14px;font-weight:950;">
                    <span class="gicon">add</span> Create Exchange
                </a>
            </div>
        @endforelse

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $exchanges->appends(request()->query())->links() }}
        </div>

    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function toast(icon, title){
            Swal.fire({
                toast:true,
                position:'top-end',
                icon,
                title,
                showConfirmButton:false,
                timer:2200,
                timerProgressBar:true
            });
        }

        // Approve confirmation (SweetAlert)
        $(document).on('submit', '.approve-form', function(e){
            e.preventDefault();
            const form = this;

            Swal.fire({
                icon: 'question',
                title: 'Approve this exchange?',
                text: 'This action will approve the pending exchange.',
                showCancelButton: true,
                confirmButtonText: 'Yes, Approve',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if(result.isConfirmed){
                    toast('success','Approved successfully');
                    form.submit();
                }
            });
        });

        // Optional: show Laravel session flash via toast
        @if(session('success'))
        toast('success', @json(session('success')));
        @endif
        @if(session('error'))
        toast('error', @json(session('error')));
        @endif
    </script>
@endpush
