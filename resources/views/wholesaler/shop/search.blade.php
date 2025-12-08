@extends('wholesaler.layouts.app')
@push('title')
    Search Results - {{ $shop->shop_name ?? 'Shop' }}
@endpush
@push('css')
    <style>
        .search-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .search-results-info {
            color: #64748b;
        }

        .highlight {
            background: #fef3c7;
            padding: 2px 4px;
            border-radius: 4px;
        }

        .no-results {
            text-align: center;
            padding: 4rem 2rem;
        }

        .no-results-icon {
            font-size: 4rem;
            color: #cbd5e1;
            margin-bottom: 1.5rem;
        }

        .search-suggestions {
            margin-top: 2rem;
        }

        .suggestion-list {
            list-style: none;
            padding: 0;
        }

        .suggestion-list li {
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .suggestion-list li:last-child {
            border-bottom: none;
        }
    </style>
@endpush

@section('content')
    <div class="search-header">
        <div class="container">
            <h1 class="fw-bold mb-3">
                <i class="fas fa-search me-2"></i>
                Search Results
            </h1>

            <div class="search-results-info">
                @if(request('query'))
                    <p class="mb-0">
                        Found <strong>{{ $products->total() }}</strong> result(s) for
                        "<strong>{{ request('query') }}</strong>"
                    </p>
                @else
                    <p class="mb-0">Enter a search term to find products</p>
                @endif
            </div>
        </div>
    </div>

    <div class="container">
        @if($products->isEmpty())
            <div class="no-results">
                <div class="no-results-icon">
                    <i class="fas fa-search fa-4x"></i>
                </div>
                <h3 class="mb-3">No Results Found</h3>
                <p class="text-muted mb-4">
                    We couldn't find any products matching "{{ request('query') }}"
                </p>

                <div class="search-suggestions">
                    <h5 class="mb-3">Suggestions:</h5>
                    <ul class="suggestion-list">
                        <li>Check your spelling and try again</li>
                        <li>Try more general keywords</li>
                        <li>Browse through our categories</li>
                        <li>
                            <a href="{{ route('wholesaler.shop.index') }}" class="text-primary">
                                View all products
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @else
            <!-- Include the products grid from index page -->
            @include('wholesaler.shop.partials.products-grid', ['products' => $products])
        @endif
    </div>
@endsection
