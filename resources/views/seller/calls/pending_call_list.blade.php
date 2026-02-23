@extends('seller.layout.app')

@push('title')
    Call List
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-900: #111827;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%);
            color: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(59, 130, 246, 0.3);
        }

        .page-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        .page-title h4 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
            position: relative;
            z-index: 1;
        }

        .page-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
            z-index: 1;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            position: relative;
            z-index: 1;
        }

        .action-btn {
            width: 50px;
            height: 50px;
            border-radius: 16px;
            background: rgba(255,255,255,0.2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .action-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .nav-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .nav-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background: rgba(255,255,255,0.2);
            color: white;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            background: rgba(255,255,255,0.3);
        }

        .main-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
            background: white;
            margin-bottom: 2rem;
        }

        .card-header-custom {
            background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-200) 100%);
            padding: 1.5rem 2rem;
            border: none;
            border-radius: 24px 24px 0 0;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            color: var(--gray-900);
        }

        .table-custom {
            margin: 0;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-custom thead {
            background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-200) 100%);
        }

        .table-custom thead th {
            padding: 1.5rem 1.25rem;
            text-align: left;
            font-weight: 700;
            color: var(--gray-700);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-custom tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid var(--gray-200);
        }

        .table-custom tbody tr:hover {
            background: linear-gradient(135deg, rgba(59,130,246,0.05) 0%, rgba(59,130,246,0.02) 100%);
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .table-custom tbody td {
            padding: 1.25rem;
            vertical-align: middle;
            border-color: var(--gray-200);
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-transform: capitalize;
        }

        .status-confirm {
            background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(16,185,129,0.05));
            color: var(--success);
            border: 1px solid rgba(16,185,129,0.2);
        }

        .status-pending {
            background: rgba(243,244,246,0.7);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .status-reject {
            background: linear-gradient(135deg, rgba(239,68,68,0.1), rgba(239,68,68,0.05));
            color: var(--danger);
            border: 1px solid rgba(239,68,68,0.2);
        }

        .status-not-answer {
            background: linear-gradient(135deg, rgba(245,158,11,0.1), rgba(245,158,11,0.05));
            color: var(--warning);
            border: 1px solid rgba(245,158,11,0.2);
        }

        .status-whatsapp {
            background: linear-gradient(135deg, rgba(59,130,246,0.1), rgba(59,130,246,0.05));
            color: var(--primary);
            border: 1px solid rgba(59,130,246,0.2);
        }

        .order-no {
            background: linear-gradient(135deg, var(--primary), #1d4ed8);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }

        .product-code-badge {
            background: rgba(59,130,246,0.1);
            color: var(--primary);
            padding: 6px 12px;
            border-radius: 20px;
            font-family: 'Monaco', monospace;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .view-btn {
            background: linear-gradient(135deg, var(--primary), #1d4ed8);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59,130,246,0.3);
        }

        .view-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59,130,246,0.4);
            color: white;
            text-decoration: none;
        }

        .date-badge {
            background: var(--gray-100);
            color: var(--gray-700);
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .nav-buttons {
                flex-direction: column;
            }
            .nav-btn {
                justify-content: center;
                width: 100%;
            }
            .table-custom tbody td {
                padding: 1rem 0.75rem;
                font-size: 0.9rem;
            }
            .page-header {
                padding: 1.5rem;
            }
        }

        .table-container {
            overflow-x: auto;
            border-radius: 0 0 24px 24px;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h4 class="mb-2">
                    <i class="fas fa-headset me-3"></i>Call List
                </h4>
                <p class="page-subtitle mb-0">Manage all your call activities ({{ count($orders) }} total)</p>
            </div>
            <div class="header-actions">
                <a href="#" id="refresh-btn" class="action-btn" title="Refresh">
                    <i class="fas fa-rotate-right"></i>
                </a>
                <a href="#" id="toggle-nav" class="action-btn" title="Toggle Navigation">
                    <i class="fas fa-chevron-down"></i>
                </a>
            </div>
        </div>

        <div class="nav-buttons" id="navContainer">
            <a href="{{route('call.index')}}" class="nav-btn">
                <i class="fas fa-clock"></i> Pending Calls
            </a>
            <a href="{{route('call.conform')}}" class="nav-btn">
                <i class="fas fa-check-circle"></i> Conform
            </a>
            <a href="{{route('call.reject')}}" class="nav-btn">
                <i class="fas fa-times-circle"></i> Reject Calls
            </a>
            <a href="{{route('call.not_answer_call_list')}}" class="nav-btn">
                <i class="fas fa-phone-slash"></i> Not Answered
            </a>
            <a href="{{route('call.other_call_list')}}" class="nav-btn">
                <i class="fas fa-ellipsis-h"></i> Other
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card main-card">
                <div class="card-header-custom">
                    <h5 class="card-title">
                        <i class="fas fa-list me-2 text-primary"></i>
                        Orders List
                        <span class="badge bg-primary ms-2">{{ count($orders) }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table-custom">
                            <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-1"></i>Order No</th>
                                <th><i class="fas fa-barcode me-1"></i>Product Code</th>
                                <th><i class="fas fa-box me-1"></i>Product Name</th>
                                <th><i class="fas fa-calendar me-1"></i>Date</th>
                                <th><i class="fas fa-user me-1"></i>Customer</th>
                                <th><i class="fas fa-tag me-1"></i>Status</th>
                                <th><i class="fas fa-cogs me-1"></i>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($orders as $order)
                                @php
                                    $status = strtolower($order->status);
                                    $class = match($status) {
                                        'reject' => 'status-reject',
                                        'conform', 'confirm' => 'status-confirm',
                                        'pending' => 'status-pending',
                                        'not answer', 'not anser' => 'status-not-answer',
                                        'whatsapp' => 'status-whatsapp',
                                        default => 'status-pending',
                                    };

                                    $iconClass = match($status) {
                                        'reject' => 'fas fa-times-circle',
                                        'conform', 'confirm' => 'fas fa-check-circle',
                                        'pending' => 'fas fa-clock',
                                        'not answer', 'not anser' => 'fas fa-phone-slash',
                                        'whatsapp' => 'fab fa-whatsapp',
                                        default => 'fas fa-question-circle'
                                    };
                                @endphp
                                <tr>
                                    <td><span class="order-no">#{{ $order->order_no }}</span></td>
                                    <td><span class="product-code-badge">{{ $order->item_code }}</span></td>
                                    <td>
                                        <strong class="d-block text-dark">{{ Str::limit($order->item_name, 35) }}</strong>
                                        @if(strlen($order->item_name) > 35)
                                            <small class="text-muted">...</small>
                                        @endif
                                    </td>
                                    <td>
                                            <span class="date-badge">
                                                {{ \Carbon\Carbon::parse($order->date)->format('M j, Y') }}
                                            </span>
                                    </td>
                                    <td><strong class="text-primary">{{ $order->customer_name }}</strong></td>
                                    <td>
                                            <span class="status-badge {{ $class }}">
                                                <i class="{{ $iconClass }}"></i>
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('call.view', $order->id) }}" class="view-btn">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                        <h5 class="text-muted mb-1">No Call Records</h5>
                                        <p class="text-muted">No orders found in this list</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle navigation
            $('#toggle-nav').on('click', function(e) {
                e.preventDefault();
                $('#navContainer').slideToggle(300);
                const $icon = $(this).find('i');
                $icon.toggleClass('fa-chevron-down fa-chevron-up');
            });

            // Refresh button with animation
            $('#refresh-btn').on('click', function(e) {
                e.preventDefault();
                const $icon = $(this).find('i');
                $icon.removeClass('fa-rotate-right').addClass('fa-spinner fa-spin');

                setTimeout(() => {
                    $icon.removeClass('fa-spinner fa-spin').addClass('fa-rotate-right');
                    // Simulate page refresh effect
                    $('tbody tr').fadeOut(100).fadeIn(300);
                }, 1000);
            });

            // Responsive navigation
            function handleResponsiveNav() {
                if ($(window).width() < 768) {
                    $('#navContainer').slideUp(300);
                    $('#toggle-nav').find('i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
                }
            }

            $(window).on('resize', handleResponsiveNav);

            // Initial check
            if ($(window).width() < 768) {
                handleResponsiveNav();
            }

            // Smooth table row animations on load
            $('tbody tr').each(function(index) {
                $(this).delay(50 * index).animate({ opacity: 1, transform: 'translateY(0)' }, 500);
            });
        });
    </script>
@endpush
