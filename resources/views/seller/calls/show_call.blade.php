@extends('seller.layout.app')

@push('title')
    View Call Details
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #0f172a;
            --mid: #64748b;
            --border: #e2e8f0;
            --surface: #ffffff;
            --bg: #f8fafc;
            --mono: 'DM Mono', monospace;
            --sans: 'DM Sans', sans-serif;
            --radius-xl: 16px;
            --radius-lg: 12px;
            --radius-md: 8px;
            --shadow-sm: 0 2px 8px rgba(0,0,0,.07);
            --shadow-md: 0 6px 20px rgba(0,0,0,.09);
        }

        body { font-family: var(--sans); background: var(--bg); }

        .order-wrapper { padding: 32px; max-width: 1400px; margin: 0 auto; }
        @media (max-width: 768px) { .order-wrapper { padding: 16px; } }

        /* ── Page Header ── */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .page-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }
        .page-subtitle {
            font-size: 14px;
            color: var(--mid);
            margin: 4px 0 0 40px;
            font-family: var(--mono);
        }
        .header-actions { display: flex; gap: 12px; flex-wrap: wrap; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all .2s;
            font-family: var(--sans);
            text-decoration: none;
            white-space: nowrap;
        }
        .btn-primary   { background: var(--primary); color: #fff; }
        .btn-primary:hover   { background: var(--primary-hover); transform: translateY(-1px); color: #fff; }
        .btn-secondary { background: var(--dark); color: #fff; }
        .btn-secondary:hover { background: #1e293b; transform: translateY(-1px); color: #fff; }
        .btn-success   { background: var(--success); color: #fff; }
        .btn-success:hover   { background: #059669; transform: translateY(-1px); color: #fff; }
        .btn-warning   { background: var(--warning); color: #fff; }
        .btn-warning:hover   { background: #d97706; transform: translateY(-1px); color: #fff; }
        .btn-danger    { background: var(--danger); color: #fff; }
        .btn-danger:hover    { background: #dc2626; color: #fff; }
        .btn-outline {
            background: var(--surface);
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        .btn-outline:hover {
            background: var(--primary);
            color: #fff;
            transform: translateY(-1px);
        }

        /* ── Alerts ── */
        .alert { padding: 14px 18px; border-radius: var(--radius-md); margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #d1fae5; border: 1px solid #10b981; color: #065f46; }
        .alert-danger  { background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; }

        /* ── Form Container ── */
        .form-container {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 32px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
        }
        @media (max-width: 768px) { .form-container { padding: 20px; } }

        /* ── Section Header ── */
        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--border);
        }
        .section-header h5 {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
        }

        /* ── Info Grid ── */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        @media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; } }

        .info-item {
            background: var(--bg);
            border-radius: var(--radius-md);
            padding: 16px;
            border: 1px solid var(--border);
            transition: border-color .2s, box-shadow .2s;
        }
        .info-item:hover {
            border-color: #c7d2fe;
            box-shadow: var(--shadow-sm);
        }
        .info-item.full-width { grid-column: 1 / -1; }

        .info-label {
            font-size: 12px;
            color: var(--mid);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .info-label .material-icons-round { font-size: 15px; color: var(--primary); }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: var(--dark);
            line-height: 1.5;
        }
        .info-value a {
            color: var(--primary);
            text-decoration: none;
            transition: color .2s;
        }
        .info-value a:hover { color: var(--primary-hover); text-decoration: underline; }
        .info-value.muted { color: var(--mid); font-weight: 400; font-style: italic; }

        /* ── Call Number Badge ── */
        .call-number-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 999px;
            font-family: var(--mono);
            font-size: 14px;
            font-weight: 700;
        }

        /* ── Status Badges ── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 13px;
        }
        .status-badge .material-icons-round { font-size: 15px; }

        .status-pending     { background: #fef3c7; color: #92400e; }
        .status-conform     { background: #d1fae5; color: #065f46; }
        .status-reject      { background: #fee2e2; color: #991b1b; }
        .status-not-answer  { background: #ede9fe; color: #5b21b6; }
        .status-other       { background: #f1f5f9; color: #334155; }

        /* ── Price Info ── */
        .price-value { color: var(--danger); font-weight: 800; font-size: 16px; }
        .total-value { color: var(--success); font-weight: 800; font-size: 16px; }
        .qty-value   { color: var(--dark); font-family: var(--mono); font-size: 15px; font-weight: 700; }

        /* ── Product Section ── */
        .product-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 2px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 24px;
            margin-bottom: 24px;
        }
        .product-section-header {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── Form Footer ── */
        .form-footer {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            padding-top: 24px;
            border-top: 2px solid var(--border);
        }
        @media (max-width: 576px) {
            .form-footer { flex-direction: column-reverse; }
            .btn { width: 100%; justify-content: center; }
        }

        /* ── Remark Box ── */
        .remark-box {
            background: var(--bg);
            border-radius: var(--radius-md);
            padding: 16px;
            border: 1px solid var(--border);
            font-size: 14px;
            color: var(--dark);
            line-height: 1.7;
            min-height: 60px;
        }
        .remark-box.empty { color: var(--mid); font-style: italic; }

        /* ── Slide-in animation ── */
        @keyframes slideDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
        .info-item { animation: slideDown .25s ease-out both; }
        .info-item:nth-child(1)  { animation-delay: .04s; }
        .info-item:nth-child(2)  { animation-delay: .07s; }
        .info-item:nth-child(3)  { animation-delay: .10s; }
        .info-item:nth-child(4)  { animation-delay: .13s; }
        .info-item:nth-child(5)  { animation-delay: .16s; }
        .info-item:nth-child(6)  { animation-delay: .19s; }
        .info-item:nth-child(7)  { animation-delay: .22s; }
        .info-item:nth-child(8)  { animation-delay: .25s; }
        .info-item:nth-child(9)  { animation-delay: .28s; }
        .info-item:nth-child(10) { animation-delay: .31s; }
        .info-item:nth-child(11) { animation-delay: .34s; }
        .info-item:nth-child(12) { animation-delay: .37s; }
        .info-item:nth-child(13) { animation-delay: .40s; }
        .info-item:nth-child(14) { animation-delay: .43s; }
        .info-item:nth-child(15) { animation-delay: .46s; }
        .info-item:nth-child(16) { animation-delay: .49s; }
        .info-item:nth-child(17) { animation-delay: .52s; }
        .info-item:nth-child(18) { animation-delay: .55s; }
        .info-item:nth-child(19) { animation-delay: .58s; }
    </style>
@endpush

@section('content')
    <div class="order-wrapper">

        {{-- Page Header --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">
                    <span class="material-icons-round" style="color:var(--primary);font-size:30px">visibility</span>
                    View Call Details
                </h1>
                <p class="page-subtitle">Call #{{ $call->call_number ?? $call->order_code }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('call.edit', $call->id) }}" class="btn btn-warning">
                    <span class="material-icons-round" style="font-size:18px">edit</span>
                    Edit Call
                </a>
                <a href="{{ route('call.print', $call->id) }}" class="btn btn-secondary" target="_blank">
                    <span class="material-icons-round" style="font-size:18px">print</span>
                    Print
                </a>
                <a href="{{ route($call->status == 'Pending' ? 'call.index' : ($call->status == 'Conform' ? 'call.conform' : ($call->status == 'Reject' ? 'call.reject' : ($call->status == 'Not Answer' ? 'call.not_answer_call_list' : 'call.other_call_list')))) }}"
                   class="btn btn-outline">
                    <span class="material-icons-round" style="font-size:18px">arrow_back</span>
                    Back
                </a>
            </div>
        </div>

        {{-- Session Alerts --}}
        @if(session('error'))
            <div class="alert alert-danger">
                <span class="material-icons-round">error</span>
                <div>{{ session('error') }}</div>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">
                <span class="material-icons-round">check_circle</span>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        {{-- Product Information Section --}}
        <div class="product-section">
            <div class="product-section-header">
                <span class="material-icons-round" style="color:var(--primary)">inventory_2</span>
                Product Information
            </div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">tag</span>
                        Product Code
                    </div>
                    <div class="info-value">
                        <span class="call-number-badge">
                            <span class="material-icons-round" style="font-size:14px">qr_code</span>
                            {{ $call->item_code }}
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">inventory_2</span>
                        Product Name
                    </div>
                    <div class="info-value">{{ $call->item_name }}</div>
                </div>
            </div>
        </div>

        {{-- Main Details --}}
        <div class="form-container">
            <div class="section-header">
                <span class="material-icons-round" style="color:var(--primary)">info</span>
                <h5>Call Information</h5>
            </div>

            <div class="info-grid">

                {{-- Call Number --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">confirmation_number</span>
                        Call Number
                    </div>
                    <div class="info-value">
                        <span class="call-number-badge">
                            <span class="material-icons-round" style="font-size:14px">tag</span>
                            {{ $call->call_number ?? $call->order_code }}
                        </span>
                    </div>
                </div>

                {{-- Date --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">calendar_today</span>
                        Date
                    </div>
                    <div class="info-value">
                        {{ $call->date ? \Carbon\Carbon::parse($call->date)->format('M d, Y') : '-' }}
                    </div>
                </div>

                {{-- Customer Name --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">person</span>
                        Customer Name
                    </div>
                    <div class="info-value">{{ $call->customer_name }}</div>
                </div>

                {{-- Status --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">check_circle</span>
                        Status
                    </div>
                    <div class="info-value">
                        @php
                            $statusKey = strtolower(str_replace(' ', '_', $call->status));
                            $statusClass = match($statusKey) {
                                'pending'    => 'status-pending',
                                'conform'    => 'status-conform',
                                'reject'     => 'status-reject',
                                'not_answer' => 'status-not-answer',
                                default      => 'status-other'
                            };
                            $statusIcon = match($statusKey) {
                                'conform'    => 'check_circle',
                                'reject'     => 'cancel',
                                'not_answer' => 'phone_disabled',
                                'pending'    => 'schedule',
                                default      => 'more_horiz'
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            <span class="material-icons-round">{{ $statusIcon }}</span>
                            {{ $call->status }}
                        </span>
                    </div>
                </div>

                {{-- Phone 1 --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">phone</span>
                        Primary Phone
                    </div>
                    <div class="info-value">
                        <a href="tel:{{ $call->customer_phone_01 }}">{{ $call->customer_phone_01 }}</a>
                    </div>
                </div>

                {{-- Phone 2 --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">phone_in_talk</span>
                        Secondary Phone
                    </div>
                    <div class="info-value">
                        @if($call->customer_phone_02)
                            <a href="tel:{{ $call->customer_phone_02 }}">{{ $call->customer_phone_02 }}</a>
                        @else
                            <span class="muted">Not provided</span>
                        @endif
                    </div>
                </div>

                {{-- City --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">location_on</span>
                        City / District
                    </div>
                    <div class="info-value">{{ $call->city ?? '-' }}</div>
                </div>

                {{-- Address --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">home</span>
                        Delivery Address
                    </div>
                    <div class="info-value">{{ $call->customer_address ?? '-' }}</div>
                </div>

                {{-- Quantity --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">inventory</span>
                        Quantity
                    </div>
                    <div class="info-value">
                        <span class="qty-value">{{ $call->qty }} units</span>
                    </div>
                </div>

                {{-- Price --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">sell</span>
                        Unit Price
                    </div>
                    <div class="info-value">
                        <span class="price-value">Rs. {{ number_format($call->price, 2) }}</span>
                    </div>
                </div>

                {{-- Total --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">calculate</span>
                        Total Amount
                    </div>
                    <div class="info-value">
                        <span class="total-value">Rs. {{ number_format($call->total, 2) }}</span>
                    </div>
                </div>

                {{-- Platform --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">language</span>
                        Platform
                    </div>
                    <div class="info-value">
                        @php
                            $platformIcon = match(strtolower($call->platform ?? '')) {
                                'fb', 'facebook' => 'thumb_up',
                                'ig', 'instagram' => 'photo_camera',
                                'tiktok' => 'music_video',
                                'website' => 'web',
                                'phone' => 'phone_in_talk',
                                default => 'devices'
                            };
                        @endphp
                        <span style="display:inline-flex;align-items:center;gap:6px">
                            <span class="material-icons-round" style="font-size:16px;color:var(--primary)">{{ $platformIcon }}</span>
                            {{ ucfirst($call->platform ?? 'N/A') }}
                        </span>
                    </div>
                </div>

                {{-- Ad Name --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">campaign</span>
                        Ad Name
                    </div>
                    <div class="info-value">
                        @if($call->ad_name)
                            {{ $call->ad_name }}
                        @else
                            <span class="muted">Not specified</span>
                        @endif
                    </div>
                </div>

                {{-- Campaign Name --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">flag</span>
                        Campaign Name
                    </div>
                    <div class="info-value">
                        @if($call->campaign_name)
                            {{ $call->campaign_name }}
                        @else
                            <span class="muted">Not specified</span>
                        @endif
                    </div>
                </div>

                {{-- Lead Status --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">trending_up</span>
                        Lead Status
                    </div>
                    <div class="info-value">{{ $call->lead_status ?? '-' }}</div>
                </div>

                {{-- Status Date --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">event_available</span>
                        Status Date
                    </div>
                    <div class="info-value">
                        {{ $call->status_date ? \Carbon\Carbon::parse($call->status_date)->format('M d, Y h:i A') : '-' }}
                    </div>
                </div>

                {{-- Created At --}}
                <div class="info-item">
                    <div class="info-label">
                        <span class="material-icons-round">schedule</span>
                        Created At
                    </div>
                    <div class="info-value">
                        {{ $call->created_at ? \Carbon\Carbon::parse($call->created_at)->format('M d, Y h:i A') : '-' }}
                    </div>
                </div>

                {{-- Remark (Full Width) --}}
                <div class="info-item full-width">
                    <div class="info-label">
                        <span class="material-icons-round">sticky_note_2</span>
                        Remark / Notes
                    </div>
                    <div class="info-value">
                        <div class="remark-box @if(!$call->remark) empty @endif">
                            {{ $call->remark ?? 'No remarks added.' }}
                        </div>
                    </div>
                </div>

            </div>

            {{-- Form Actions --}}
            <div class="form-footer">
                <a href="{{ route($call->status == 'Pending' ? 'call.index' : ($call->status == 'Conform' ? 'call.conform' : ($call->status == 'Reject' ? 'call.reject' : ($call->status == 'Not Answer' ? 'call.not_answer_call_list' : 'call.other_call_list')))) }}"
                   class="btn btn-outline">
                    <span class="material-icons-round" style="font-size:18px">arrow_back</span>
                    Back to List
                </a>
                <a href="{{ route('call.print', $call->id) }}" class="btn btn-secondary" target="_blank">
                    <span class="material-icons-round" style="font-size:18px">print</span>
                    Print
                </a>
                <a href="{{ route('call.edit', $call->id) }}" class="btn btn-warning">
                    <span class="material-icons-round" style="font-size:18px">edit</span>
                    Edit Call
                </a>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
            @endif

            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
            @endif

            // Auto-dismiss alerts
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(el => {
                    el.style.transition = 'opacity .5s';
                    el.style.opacity    = '0';
                    setTimeout(() => el.remove(), 500);
                });
            }, 5000);
        });
    </script>
@endpush
