@extends('employee.layout.app')

@section('title', 'Product Details')
@section('subtitle', $product->name)

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #667eea;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .page-header-enhanced {
            background: var(--primary-gradient);
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            color: white;
            box-shadow: var(--card-shadow);
        }

        .back-button-enhanced {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
        }

        .back-button-enhanced:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateX(-5px);
        }

        .card-enhanced {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card-enhanced:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .product-image-enhanced {
            width: 100%;
            height: 400px;
            object-fit: contain;
            border-radius: 12px;
            background: white;
            padding: 1rem;
            border: 2px solid #e2e8f0;
        }

        .info-group-enhanced {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
        }

        .info-label-enhanced {
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .info-value-enhanced {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .pricing-card-enhanced {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 1.5rem;
            border-radius: 15px;
            border: 2px solid #bae6fd;
            margin-top: 1rem;
        }

        .price-item-enhanced {
            text-align: center;
            padding: 1rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .price-label-enhanced {
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .price-value-enhanced {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0c4a6e;
        }

        .final-price-enhanced {
            font-size: 2rem;
            font-weight: 800;
            color: #10b981;
            text-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
        }

        .status-badge-enhanced {
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .status-active-enhanced {
            background: var(--success-gradient);
            color: white;
        }

        .status-inactive-enhanced {
            background: var(--danger-gradient);
            color: white;
        }

        .timeline-enhanced {
            position: relative;
            padding-left: 2rem;
        }

        .timeline-enhanced::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        }

        .timeline-item-enhanced {
            position: relative;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            background: white;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .timeline-item-enhanced:hover {
            border-color: #667eea;
            box-shadow: 0 4px 16px rgba(102, 126, 234, 0.15);
            transform: translateX(5px);
        }

        .timeline-dot-enhanced {
            position: absolute;
            left: -2.4rem;
            top: 1.5rem;
            width: 18px;
            height: 18px;
            background: white;
            border: 4px solid #667eea;
            border-radius: 50%;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            z-index: 1;
        }

        .timeline-time-enhanced {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .timeline-employee-enhanced {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .price-change-box-enhanced {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 0.75rem;
            border-left: 4px solid #667eea;
        }

        .price-arrow-enhanced {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
        }

        .old-price-enhanced {
            color: #94a3b8;
            text-decoration: line-through;
        }

        .new-price-enhanced {
            color: #667eea;
            font-size: 1.1rem;
        }

        .price-diff-badge-enhanced {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .price-increase-enhanced {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
            border: 1px solid #86efac;
        }

        .price-decrease-enhanced {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .notes-text-enhanced {
            font-size: 0.9rem;
            color: #475569;
            font-style: italic;
            padding: 0.75rem;
            background: white;
            border-radius: 8px;
            border-left: 3px solid #94a3b8;
        }

        .empty-state-enhanced {
            text-align: center;
            padding: 3rem 1rem;
            color: #94a3b8;
        }

        .empty-state-enhanced i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .timeline-container-enhanced {
            max-height: 600px;
            overflow-y: auto;
            padding-right: 1rem;
        }

        .timeline-container-enhanced::-webkit-scrollbar {
            width: 8px;
        }

        .timeline-container-enhanced::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .timeline-container-enhanced::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        .badge-enhanced {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .section-title-enhanced {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid #e2e8f0;
        }

        .section-title-enhanced i {
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .product-image-enhanced {
                height: 300px;
            }

            .pricing-card-enhanced {
                padding: 1rem;
            }

            .price-value-enhanced {
                font-size: 1.25rem;
            }

            .final-price-enhanced {
                font-size: 1.5rem;
            }

            .timeline-enhanced {
                padding-left: 1.5rem;
            }

            .timeline-dot-enhanced {
                left: -2rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        {{-- Page Header --}}
        <div class="page-header-enhanced">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="mb-2">
                        <i class="fas fa-box-open me-3"></i>
                        Product Details
                    </h1>
                    <p class="mb-0 opacity-90">
                        <i class="fas fa-barcode me-2"></i>
                        {{ $product->code }} @if($product->barcode)
                            | {{ $product->barcode }}
                        @endif
                    </p>
                </div>
                <div>
                    <a href="{{ route('employee.wholesaler.products') }}" class="back-button-enhanced">
                        <i class="fas fa-arrow-left"></i>
                        Back to Products
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Left Column - Product Info --}}
            <div class="col-lg-8">
                {{-- Product Image & Basic Info --}}
                <div class="card-enhanced mb-4">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-5">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="product-image-enhanced">
                                @else
                                    <div
                                        class="product-image-enhanced d-flex align-items-center justify-content-center bg-light">
                                        <i class="fas fa-image fa-5x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-7">
                                <h3 class="fw-bold mb-3">{{ $product->name }}</h3>

                                <div class="info-group-enhanced">
                                    <div class="info-label-enhanced">
                                        <i class="fas fa-store me-1"></i> Wholesaler
                                    </div>
                                    <div class="info-value-enhanced">
                                        {{ $product->wholesaler->business_name ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-group-enhanced">
                                            <div class="info-label-enhanced">
                                                <i class="fas fa-tag me-1"></i> Category
                                            </div>
                                            <div class="info-value-enhanced">
                                                {{ $product->category->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-group-enhanced">
                                            <div class="info-label-enhanced">
                                                <i class="fas fa-copyright me-1"></i> Brand
                                            </div>
                                            <div class="info-value-enhanced">
                                                {{ $product->brand->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-group-enhanced">
                                    <div class="info-label-enhanced">
                                        <i class="fas fa-toggle-on me-1"></i> Status
                                    </div>
                                    <div class="mt-2">
                                        <span
                                            class="status-badge-enhanced {{ $product->status == 'active' ? 'status-active-enhanced' : 'status-inactive-enhanced' }}">
                                            <i class="fas {{ $product->status == 'active' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                            {{ ucfirst($product->status) }}
                                        </span>
                                    </div>
                                </div>

                                @if($product->description)
                                    <div class="info-group-enhanced">
                                        <div class="info-label-enhanced">
                                            <i class="fas fa-align-left me-1"></i> Description
                                        </div>
                                        <div class="info-value-enhanced" style="font-size: 0.95rem;">
                                            {{ $product->description }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pricing Information --}}
                <div class="card-enhanced">
                    <div class="card-body p-4">
                        <h4 class="section-title-enhanced">
                            <i class="fas fa-dollar-sign"></i>
                            Pricing Information
                        </h4>

                        <div class="pricing-card-enhanced">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="price-item-enhanced">
                                        <div class="price-label-enhanced">
                                            <i class="fas fa-money-bill-wave me-1"></i>
                                            Cost Price
                                        </div>
                                        <div class="price-value-enhanced">
                                            Rs. {{ number_format($product->cost_price, 2) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="price-item-enhanced">
                                        <div class="price-label-enhanced">
                                            <i class="fas fa-tags me-1"></i>
                                            Selling Price
                                        </div>
                                        <div class="price-value-enhanced">
                                            Rs. {{ number_format($product->selling_price, 2) }}
                                        </div>
                                        @if($product->discount > 0)
                                            <div class="badge-enhanced mt-2"
                                                 style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #92400e; border: 1px solid #fbbf24;">
                                                <i class="fas fa-percent"></i>
                                                {{ $product->discount }}% OFF
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="price-item-enhanced" style="background: #ecfdf5;">
                                        <div class="price-label-enhanced" style="color: #059669;">
                                            <i class="fas fa-hand-holding-usd me-1"></i>
                                            Final Price
                                        </div>
                                        <div class="final-price-enhanced">
                                            Rs. {{ number_format($product->final_price, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($product->packaging_cost > 0)
                                <div class="mt-3 text-center">
                                    <div class="badge-enhanced"
                                         style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); color: #4338ca; border: 1px solid #a5b4fc;">
                                        <i class="fas fa-box me-1"></i>
                                        Packaging Cost: Rs. {{ number_format($product->packaging_cost, 2) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column - Price History --}}
            <div class="col-lg-4">
                <div class="card-enhanced">
                    <div class="card-body p-4">
                        <h4 class="section-title-enhanced">
                            <i class="fas fa-history"></i>
                            Price Change History
                        </h4>

                        <div class="timeline-container-enhanced">
                            @if($priceHistory->isEmpty())
                                <div class="empty-state-enhanced">
                                    <i class="fas fa-clock"></i>
                                    <h5 class="mt-3 mb-2">No Price Changes Yet</h5>
                                    <p class="text-muted small mb-0">Price history will appear here when changes are
                                        made</p>
                                </div>
                            @else
                                <div class="timeline-enhanced">
                                    @foreach($priceHistory as $change)
                                        <div class="timeline-item-enhanced">
                                            <div class="timeline-dot-enhanced"></div>

                                            <div class="timeline-time-enhanced">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $change->created_at->diffForHumans() }}
                                                <span
                                                    class="text-muted small">({{ $change->created_at->format('M d, Y H:i') }}
                                                    )</span>
                                            </div>

                                            <div class="timeline-employee-enhanced">
                                                <i class="fas fa-user-circle"></i>
                                                {{ $change->employee->name ?? 'Unknown' }}
                                            </div>

                                            <div class="price-change-box-enhanced">
                                                <div class="price-arrow-enhanced">
                                                    <span class="old-price-enhanced">
                                                        Rs. {{ number_format($change->old_selling_price, 2) }}
                                                    </span>
                                                    <i class="fas fa-arrow-right"></i>
                                                    <span class="new-price-enhanced">
                                                        Rs. {{ number_format($change->new_selling_price, 2) }}
                                                    </span>
                                                </div>

                                                <div class="mt-2">
                                                    <span
                                                        class="price-diff-badge-enhanced {{ $change->price_difference >= 0 ? 'price-increase-enhanced' : 'price-decrease-enhanced' }}">
                                                        <i class="fas {{ $change->price_difference >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                                                        Rs. {{ number_format(abs($change->price_difference), 2) }}
                                                        ({{ $change->price_difference >= 0 ? '+' : '' }}{{ number_format($change->percentage_change, 2) }}
                                                        %)
                                                    </span>
                                                </div>
                                            </div>

                                            @if($change->notes)
                                                <div class="notes-text-enhanced">
                                                    <i class="fas fa-sticky-note me-2"></i>
                                                    {{ $change->notes }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if($priceHistory->hasPages())
                            <div class="mt-3">
                                {{ $priceHistory->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Any additional JavaScript for this page
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Product details page loaded');
        });
    </script>
@endpush
