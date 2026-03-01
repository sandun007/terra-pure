@extends('employee.layout.app')

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f5f7fb;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }

        /* Main Container */
        .dc-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px;
        }

        /* Header Section */
        .dc-header {
            margin-bottom: 28px;
        }

        .dc-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .dc-breadcrumb a {
            color: #6b7280;
            text-decoration: none;
        }

        .dc-breadcrumb a:hover {
            color: #2563eb;
        }

        .dc-header-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .dc-title-section {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .dc-title-section h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            letter-spacing: -0.3px;
        }

        .dc-badge {
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .dc-badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .dc-badge-packing {
            background: #dbeafe;
            color: #1e40af;
        }

        .dc-badge-packed {
            background: #ede9fe;
            color: #5b21b6;
        }

        .dc-badge-givedriver {
            background: #d1fae5;
            color: #065f46;
        }

        .dc-btn-back {
            padding: 8px 18px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            color: #475569;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .dc-btn-back:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        /* Status Tracker */
        .dc-tracker {
            background: white;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 28px;
            border: 1px solid #eef2f6;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .dc-steps {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .dc-step-item {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            min-width: 140px;
        }

        .dc-step-marker {
            width: 36px;
            height: 36px;
            border-radius: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            background: #f1f5f9;
            color: #64748b;
            border: 2px solid #e2e8f0;
        }

        .dc-step-marker.completed {
            background: #10b981;
            border-color: #10b981;
            color: white;
        }

        .dc-step-marker.active {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.15);
        }

        .dc-step-label {
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
        }

        .dc-step-label small {
            display: block;
            font-size: 11px;
            color: #94a3b8;
            font-weight: 400;
            margin-top: 2px;
        }

        .dc-step-connector {
            flex: 0 0 20px;
            height: 2px;
            background: #e2e8f0;
        }

        .dc-step-connector.completed {
            background: #10b981;
        }

        /* Grid Layout */
        .dc-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        .dc-grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 24px;
        }

        @media (max-width: 768px) {
            .dc-grid-2, .dc-grid-3 {
                grid-template-columns: 1fr;
            }
        }

        /* Cards */
        .dc-card {
            background: white;
            border-radius: 20px;
            border: 1px solid #eef2f6;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            transition: all 0.2s;
        }

        .dc-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.05);
        }

        .dc-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #f1f5f9;
            background: #ffffff;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .dc-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #475569;
        }

        .dc-card-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            flex: 1;
        }

        .dc-card-body {
            padding: 24px;
        }

        .dc-card-body.compact {
            padding: 20px;
        }

        /* Info Rows */
        .dc-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
        }

        .dc-info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .dc-info-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #94a3b8;
        }

        .dc-info-value {
            font-size: 15px;
            font-weight: 500;
            color: #1e293b;
            word-break: break-word;
        }

        .dc-info-value.mono {
            font-family: 'SF Mono', 'Menlo', monospace;
            font-size: 14px;
        }

        .dc-info-value.highlight {
            color: #3b82f6;
            font-weight: 600;
        }

        .dc-info-value.success {
            color: #10b981;
            font-weight: 600;
        }

        .dc-info-value.warning {
            color: #f59e0b;
        }

        .dc-info-value.danger {
            color: #ef4444;
        }

        /* Address Box */
        .dc-address-box {
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid #eef2f6;
        }

        .dc-address-line {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .dc-address-line:last-child {
            border-bottom: none;
        }

        .dc-address-label {
            width: 110px;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .dc-address-text {
            flex: 1;
            font-size: 14px;
            color: #1e293b;
        }

        /* Origin Tag */
        .dc-origin-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: #f1f5f9;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 500;
        }

        .dc-origin-tag.custom {
            background: #dbeafe;
            color: #1e40af;
        }

        .dc-origin-tag.default {
            background: #fef3c7;
            color: #92400e;
        }

        /* Product Card */
        .dc-product {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            padding: 16px;
            background: #f8fafc;
            border-radius: 16px;
            margin-bottom: 20px;
        }

        .dc-product-image {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: white;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            flex-shrink: 0;
        }

        .dc-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        .dc-product-details {
            flex: 1;
        }

        .dc-product-name {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .dc-product-sku {
            font-family: 'SF Mono', monospace;
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 12px;
        }

        .dc-product-tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .dc-tag {
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 600;
            background: white;
            border: 1px solid #e2e8f0;
            color: #475569;
        }

        .dc-tag.success {
            background: #d1fae5;
            border-color: #a7f3d0;
            color: #065f46;
        }

        .dc-tag.info {
            background: #dbeafe;
            border-color: #bfdbfe;
            color: #1e40af;
        }

        /* Price Box */
        .dc-price-box {
            background: #f8fafc;
            border-radius: 16px;
            padding: 20px;
        }

        .dc-price-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .dc-price-row:last-child {
            border-bottom: none;
        }

        .dc-price-row.total {
            margin-top: 8px;
            padding-top: 16px;
            border-top: 2px solid #e2e8f0;
            font-weight: 700;
        }

        .dc-price-label {
            font-size: 14px;
            color: #64748b;
        }

        .dc-price-amount {
            font-family: 'SF Mono', monospace;
            font-weight: 600;
            color: #1e293b;
        }

        .dc-price-amount.total {
            font-size: 20px;
            color: #10b981;
        }

        /* Waybill Hero */
        .dc-waybill {
            background: linear-gradient(135deg, #f0f9ff 0%, #e6f7f0 100%);
            border: 1px solid #bae6fd;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
        }

        .dc-waybill-icon {
            width: 56px;
            height: 56px;
            background: #0ea5e9;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            flex-shrink: 0;
        }

        .dc-waybill-content {
            flex: 1;
        }

        .dc-waybill-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0284c7;
            margin-bottom: 4px;
        }

        .dc-waybill-number {
            font-family: 'SF Mono', monospace;
            font-size: 24px;
            font-weight: 700;
            color: #0c4a6e;
            margin-bottom: 4px;
        }

        .dc-waybill-meta {
            font-size: 13px;
            color: #64748b;
        }

        /* Error Box */
        .dc-error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 16px;
            padding: 16px;
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .dc-error-icon {
            font-size: 20px;
        }

        .dc-error-message {
            font-size: 14px;
            color: #b91c1c;
            font-weight: 500;
            line-height: 1.5;
        }

        /* Delivery Info */
        .dc-delivery-info {
            border: 1px solid #eef2f6;
            border-radius: 16px;
            overflow: hidden;
        }

        .dc-delivery-row {
            display: flex;
            padding: 14px 16px;
            background: white;
            border-bottom: 1px solid #f1f5f9;
        }

        .dc-delivery-row:nth-child(even) {
            background: #fafbfc;
        }

        .dc-delivery-row:last-child {
            border-bottom: none;
        }

        .dc-delivery-label {
            width: 140px;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .dc-delivery-value {
            flex: 1;
            font-size: 14px;
            color: #1e293b;
        }

        /* Audio Player */
        .dc-audio-player {
            width: 100%;
            margin-top: 8px;
        }

        .dc-audio-player audio {
            width: 100%;
            border-radius: 30px;
        }

        /* Meta Section */
        .dc-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
        }

        /* Divider */
        .dc-divider {
            height: 1px;
            background: #eef2f6;
            margin: 24px 0;
        }
    </style>

    <div class="dc-container">
        <!-- Header -->
        <div class="dc-header">
            <div class="dc-breadcrumb">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                </svg>
                <a href="#">Dashboard</a>
                <span>/</span>
                <a href="{{ url()->previous() }}">Delivery Calls</a>
                <span>/</span>
                <span style="color:#1e293b;font-weight:500;">Call #{{ $call->id }}</span>
            </div>

            <div class="dc-header-main">
                <div class="dc-title-section">
                    <h1>Delivery Call #{{ $call->id }}</h1>
                    <span class="dc-badge dc-badge-{{ strtolower(str_replace(' ','-',$call->status)) }}">
                        <span>●</span>
                        {{ $call->status }}
                    </span>
                </div>
                <a href="{{ url()->previous() }}" class="dc-btn-back">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <!-- Status Tracker -->
        @php
            $flow = ['Pending','packing','packed','givedriver'];
            $flowLabels = ['Pending', 'Packing', 'Packed', 'Give to Driver'];
            $currentIndex = array_search($call->status, $flow);
            if ($currentIndex === false) $currentIndex = 0;
        @endphp

        <div class="dc-tracker">
            <div class="dc-steps">
                @foreach($flow as $index => $status)
                    <div class="dc-step-item">
                        <div class="dc-step-marker
                            @if($index < $currentIndex) completed
                            @elseif($index == $currentIndex) active
                            @endif">
                            @if($index < $currentIndex) ✓
                            @else {{ $index + 1 }}
                            @endif
                        </div>
                        <div class="dc-step-label">
                            {{ $flowLabels[$index] }}
                            @if($index == $currentIndex && $call->status_date)
                                <small>{{ optional($call->status_date)->format('M d, H:i') }}</small>
                            @endif
                        </div>
                    </div>
                    @if(!$loop->last)
                        <div class="dc-step-connector {{ $index < $currentIndex ? 'completed' : '' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Order & Customer Grid -->
        <div class="dc-grid-2">
            <!-- Order Info -->
            <div class="dc-card">
                <div class="dc-card-header">
                    <div class="dc-card-icon">📋</div>
                    <div class="dc-card-title">Order Information</div>
                </div>
                <div class="dc-card-body">
                    <div class="dc-info-grid">
                        <div class="dc-info-item">
                            <span class="dc-info-label">Call Number</span>
                            <span class="dc-info-value mono highlight">{{ $call->call_number }}</span>
                        </div>
                        <div class="dc-info-item">
                            <span class="dc-info-label">Order Code</span>
                            <span class="dc-info-value mono">{{ $call->order_code ?? '—' }}</span>
                        </div>
                        <div class="dc-info-item">
                            <span class="dc-info-label">Date</span>
                            <span class="dc-info-value">{{ optional($call->date)->format('M d, Y') ?? '—' }}</span>
                        </div>
                        <div class="dc-info-item">
                            <span class="dc-info-label">Platform</span>
                            <span class="dc-info-value">{{ $call->platform ?? '—' }}</span>
                        </div>
                        <div class="dc-info-item">
                            <span class="dc-info-label">Campaign</span>
                            <span class="dc-info-value">{{ $call->campaign_name ?? '—' }}</span>
                        </div>
                        <div class="dc-info-item">
                            <span class="dc-info-label">Ad Name</span>
                            <span class="dc-info-value">{{ $call->ad_name ?? '—' }}</span>
                        </div>
                        <div class="dc-info-item" style="grid-column:1/-1">
                            <span class="dc-info-label">Remark</span>
                            <span class="dc-info-value">{{ $call->remark ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="dc-card">
                <div class="dc-card-header">
                    <div class="dc-card-icon">👤</div>
                    <div class="dc-card-title">Customer Information</div>
                </div>
                <div class="dc-card-body">
                    <div class="dc-info-grid">
                        <div class="dc-info-item" style="grid-column:1/-1">
                            <span class="dc-info-label">Full Name</span>
                            <span class="dc-info-value" style="font-size:18px;font-weight:600;">{{ $call->customer_name }}</span>
                        </div>
                        <div class="dc-info-item">
                            <span class="dc-info-label">Phone 1</span>
                            <span class="dc-info-value mono">{{ $call->customer_phone_01 }}</span>
                        </div>
                        <div class="dc-info-item">
                            <span class="dc-info-label">Phone 2</span>
                            <span class="dc-info-value mono">{{ $call->customer_phone_02 ?? '—' }}</span>
                        </div>
                        <div class="dc-info-item" style="grid-column:1/-1">
                            <span class="dc-info-label">Address</span>
                            <span class="dc-info-value">{{ $call->customer_address ?? '—' }}</span>
                        </div>
                        <div class="dc-info-item">
                            <span class="dc-info-label">City</span>
                            <span class="dc-info-value">{{ $call->city ?? '—' }}</span>
                        </div>
                        <div class="dc-info-item">
                            <span class="dc-info-label">District</span>
                            <span class="dc-info-value">{{ $call->district ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delivery & Courier -->
        <div class="dc-card">
            <div class="dc-card-header">
                <div class="dc-card-icon">🚚</div>
                <div class="dc-card-title">Delivery & Courier</div>
                @if($call->curfox_waybill)
                    <span style="background:#10b981;color:white;padding:4px 12px;border-radius:30px;font-size:12px;font-weight:500;">Dispatched</span>
                @endif
            </div>
            <div class="dc-card-body">

                @if($call->curfox_waybill)
                    <div class="dc-waybill">
                        <div class="dc-waybill-icon">📦</div>
                        <div class="dc-waybill-content">
                            <div class="dc-waybill-label">Curfox Waybill</div>
                            <div class="dc-waybill-number">{{ $call->curfox_waybill }}</div>
                            @if($call->curfox_dispatched_at)
                                <div class="dc-waybill-meta">Dispatched: {{ optional($call->curfox_dispatched_at)->format('M d, Y H:i') }}</div>
                            @endif
                        </div>
                    </div>
                @endif

                @if($call->curfox_error)
                    <div class="dc-error-box">
                        <div class="dc-error-icon">⚠️</div>
                        <div class="dc-error-message">{{ $call->curfox_error }}</div>
                    </div>
                @endif

                <div class="dc-grid-2">
                    <!-- Destination -->
                    <div>
                        <h4 style="font-size:13px;color:#64748b;margin-bottom:12px;text-transform:uppercase;letter-spacing:0.5px;">Destination</h4>
                        <div class="dc-address-box">
                            <div class="dc-address-line">
                                <span class="dc-address-label">City</span>
                                <span class="dc-address-text">
                                    {{ $call->city ?? '—' }}
                                    @if($call->destination_city_id)
                                        <span style="background:#10b98110;color:#10b981;padding:2px 8px;border-radius:30px;font-size:11px;margin-left:8px;">ID: {{ $call->destination_city_id }}</span>
                                    @endif
                                </span>
                            </div>
                            <div class="dc-address-line">
                                <span class="dc-address-label">District</span>
                                <span class="dc-address-text">{{ $call->district ?? '—' }}</span>
                            </div>
                            <div class="dc-address-line">
                                <span class="dc-address-label">State</span>
                                <span class="dc-address-text">{{ $call->destination_state_name ?? '—' }}</span>
                            </div>
                            <div class="dc-address-line">
                                <span class="dc-address-label">Full Address</span>
                                <span class="dc-address-text">{{ $call->customer_address ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Origin -->
                    <div>
                        <h4 style="font-size:13px;color:#64748b;margin-bottom:12px;text-transform:uppercase;letter-spacing:0.5px;">Pickup Origin</h4>
                        @php
                            $hasCustomOrigin = $call->hasCustomOrigin();
                            $originCityName = $call->effectiveOriginCityName();
                            $originStateName = $call->effectiveOriginStateName();
                            $originCityId = $call->effectiveOriginCityId();
                        @endphp
                        <div class="dc-address-box">
                            <div class="dc-address-line">
                                <span class="dc-address-label">City</span>
                                <span class="dc-address-text">
                                    <span class="dc-origin-tag {{ $hasCustomOrigin ? 'custom' : 'default' }}">
                                        {{ $originCityName }}
                                        @if(!$hasCustomOrigin)
                                            (default)
                                        @endif
                                    </span>
                                </span>
                            </div>
                            <div class="dc-address-line">
                                <span class="dc-address-label">State</span>
                                <span class="dc-address-text">{{ $originStateName }}</span>
                            </div>
                            <div class="dc-address-line">
                                <span class="dc-address-label">City ID</span>
                                <span class="dc-address-text">
                                    @if($originCityId)
                                        <span class="dc-info-value mono highlight">{{ $originCityId }}</span>
                                    @else
                                        <span style="color:#94a3b8;">Not set</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dc-divider"></div>

                <div class="dc-grid-3">
                    <div class="dc-info-item">
                        <span class="dc-info-label">Waybill</span>
                        <span class="dc-info-value mono {{ $call->curfox_waybill ? 'success' : '' }}">
                            {{ $call->curfox_waybill ?? 'Not dispatched' }}
                        </span>
                    </div>
                    <div class="dc-info-item">
                        <span class="dc-info-label">Dispatched</span>
                        <span class="dc-info-value mono">
                            {{ optional($call->curfox_dispatched_at)->format('M d, H:i') ?? '—' }}
                        </span>
                    </div>
                    <div class="dc-info-item">
                        <span class="dc-info-label">COD Amount</span>
                        <span class="dc-info-value success" style="font-size:18px;">
                            Rs. {{ number_format(($call->price ?? 0) * ($call->qty ?? 1), 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product & Pricing Grid -->
        <div class="dc-grid-2" style="margin-top:24px;">
            <!-- Product -->
            <div class="dc-card">
                <div class="dc-card-header">
                    <div class="dc-card-icon">📦</div>
                    <div class="dc-card-title">Product Details</div>
                </div>
                <div class="dc-card-body">
                    @if($call->product)
                        @php $prod = $call->product; @endphp
                        <div class="dc-product">
                            <div class="dc-product-image">
                                @if($prod->image)
                                    <img src="{{ asset('storage/'.$prod->image) }}" alt="{{ $prod->name }}">
                                @else
                                    🛍️
                                @endif
                            </div>
                            <div class="dc-product-details">
                                <div class="dc-product-name">{{ $prod->name }}</div>
                                <div class="dc-product-sku">SKU: {{ $prod->sku ?? '—' }} | Code: {{ $prod->code }}</div>
                                <div class="dc-product-tags">
                                    <span class="dc-tag success">{{ $prod->status }}</span>
                                    @if($prod->type ?? false)
                                        <span class="dc-tag info">{{ $prod->type }}</span>
                                    @endif
                                    @if($prod->qty !== null)
                                        <span class="dc-tag">Stock: {{ $prod->qty }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="dc-info-grid">
                            <div class="dc-info-item">
                                <span class="dc-info-label">Display Price</span>
                                <span class="dc-info-value mono">Rs. {{ number_format($prod->display_price, 2) }}</span>
                            </div>
                            <div class="dc-info-item">
                                <span class="dc-info-label">Stock Price</span>
                                <span class="dc-info-value mono">Rs. {{ number_format($prod->stock_price, 2) }}</span>
                            </div>
                            @if($prod->packaging_cost ?? false)
                                <div class="dc-info-item">
                                    <span class="dc-info-label">Packaging</span>
                                    <span class="dc-info-value mono">Rs. {{ number_format($prod->packaging_cost, 2) }}</span>
                                </div>
                            @endif
                            @if($prod->discount ?? false)
                                <div class="dc-info-item">
                                    <span class="dc-info-label">Discount</span>
                                    <span class="dc-info-value warning">{{ $prod->discount }}%</span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="dc-info-grid">
                            <div class="dc-info-item" style="grid-column:1/-1">
                                <span class="dc-info-label">Item Name</span>
                                <span class="dc-info-value" style="font-size:16px;">{{ $call->item_name }}</span>
                            </div>
                            @if($call->item_code)
                                <div class="dc-info-item">
                                    <span class="dc-info-label">Item Code</span>
                                    <span class="dc-info-value mono">{{ $call->item_code }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pricing -->
            <div class="dc-card">
                <div class="dc-card-header">
                    <div class="dc-card-icon">💰</div>
                    <div class="dc-card-title">Pricing Breakdown</div>
                </div>
                <div class="dc-card-body">
                    @php
                        $unitPrice = $call->price ?? 0;
                        $qty = $call->qty ?? 1;
                        $total = $qty * $unitPrice;
                    @endphp

                    <div class="dc-price-box">
                        <div class="dc-price-row">
                            <span class="dc-price-label">Item</span>
                            <span class="dc-price-amount">{{ $call->item_name }}</span>
                        </div>
                        <div class="dc-price-row">
                            <span class="dc-price-label">Unit Price</span>
                            <span class="dc-price-amount">Rs. {{ number_format($unitPrice, 2) }}</span>
                        </div>
                        <div class="dc-price-row">
                            <span class="dc-price-label">Quantity</span>
                            <span class="dc-price-amount">{{ $qty }}</span>
                        </div>
                        <div class="dc-price-row total">
                            <span class="dc-price-label">Total COD</span>
                            <span class="dc-price-amount total">Rs. {{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    @if($call->product && $call->product->stock_price)
                        @php $gp = ($unitPrice - $call->product->stock_price) * $qty; @endphp
                        <div style="margin-top:16px;padding:12px;background:#f8fafc;border-radius:12px;text-align:center;">
                            <span style="font-size:13px;color:#64748b;">Gross Profit</span>
                            <div style="font-size:20px;font-weight:700;{{ $gp >= 0 ? 'color:#10b981;' : 'color:#ef4444;' }}">
                                Rs. {{ number_format($gp, 2) }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Call Recording (if any) -->
        @if($call->call_duration || $call->call_recording_url)
            <div class="dc-card" style="margin-top:24px;">
                <div class="dc-card-header">
                    <div class="dc-card-icon">📞</div>
                    <div class="dc-card-title">Call Recording</div>
                </div>
                <div class="dc-card-body">
                    <div class="dc-grid-2">
                        @if($call->call_duration)
                            <div class="dc-info-item">
                                <span class="dc-info-label">Duration</span>
                                <span class="dc-info-value">{{ $call->call_duration }}</span>
                            </div>
                        @endif
                        @if($call->call_recording_url)
                            <div class="dc-info-item" style="grid-column:1/-1;">
                                <span class="dc-info-label">Recording</span>
                                <div class="dc-audio-player">
                                    <audio controls>
                                        <source src="{{ $call->call_recording_url }}">
                                        Your browser does not support audio.
                                    </audio>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Metadata -->
        <div class="dc-card" style="margin-top:24px;">
            <div class="dc-card-header">
                <div class="dc-card-icon">🕐</div>
                <div class="dc-card-title">Record Metadata</div>
            </div>
            <div class="dc-card-body compact">
                <div class="dc-meta-grid">
                    <div class="dc-info-item">
                        <span class="dc-info-label">Seller ID</span>
                        <span class="dc-info-value mono">{{ $call->seller_id ?? '—' }}</span>
                    </div>
                    <div class="dc-info-item">
                        <span class="dc-info-label">Product ID</span>
                        <span class="dc-info-value mono">{{ $call->product_id ?? '—' }}</span>
                    </div>
                    <div class="dc-info-item">
                        <span class="dc-info-label">Download Status</span>
                        <span class="dc-info-value {{ $call->download_status ? 'success' : '' }}">
                            {{ $call->download_status ? 'Downloaded' : 'Pending' }}
                        </span>
                    </div>
                    <div class="dc-info-item">
                        <span class="dc-info-label">Created</span>
                        <span class="dc-info-value mono">{{ optional($call->created_at)->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="dc-info-item">
                        <span class="dc-info-label">Updated</span>
                        <span class="dc-info-value mono">{{ optional($call->updated_at)->format('M d, Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
