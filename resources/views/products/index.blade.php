@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 p-3 rounded-4"
     style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(10px);">

    <!-- LEFT SIDE: TITLE + SUBTITLE -->
    <div>
        <h2 class="mb-1 fw-bold text-dark">
            ⚡ Laravel Query Cache Dashboard
        </h2>
        <small style="color:#94a3b8;">
            Fast • Cached • Optimized Product System
        </small>
    </div>

    <!-- RIGHT SIDE: ACTION BUTTONS -->
    <div class="d-flex flex-wrap gap-2 justify-content-end">

        <a href="{{ route('products.create') }}"
           class="btn btn-primary btn-sm px-3 rounded-pill">
            ➕ Add
        </a>

        <a href="{{ route('products.import') }}"
           class="btn btn-secondary btn-sm px-3 rounded-pill">
            ⬆ Import
        </a>

        <a href="{{ route('categories.index') }}"
           class="btn btn-outline-light btn-sm px-3 rounded-pill">
            🏷 Categories
        </a>

        <a href="{{ route('products.trash') }}"
           class="btn btn-outline-warning btn-sm px-3 rounded-pill">
            🗑 Trash
        </a>

        <a href="{{ route('products.export') }}"
           class="btn btn-success btn-sm px-3 rounded-pill">
            ⬇ Export
        </a>

        <a href="{{ route('products.warmUp') }}"
           class="btn btn-warning btn-sm px-3 rounded-pill text-dark">
            ⚡ Warm
        </a>

        <a href="{{ route('products.cacheStatus') }}"
           class="btn btn-info btn-sm px-3 rounded-pill text-dark">
            📊 Status
        </a>

        <a href="{{ route('products.cacheKeys') }}"
           class="btn btn-outline-info btn-sm px-3 rounded-pill">
            🔑 Keys
        </a>

        <a href="{{ route('products.analytics') }}"
           class="btn btn-dark btn-sm px-3 rounded-pill">
            📈 Analytics
        </a>

        <a href="{{ route('products.clearCache') }}"
           class="btn btn-danger btn-sm px-3 rounded-pill">
            🧹 Clear
        </a>

    </div>

</div>

<!-- Dashboard Cards -->

<div class="row mb-4">

    <div class="col-md-3">
        <div class="card bg-primary text-white shadow">
            <div class="card-body text-center">
                <h4>Total Products</h4>
                <h2>{{ $totalProducts }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-success text-white shadow">
            <div class="card-body text-center">
                <h4>Total Price</h4>
                <h2>₹ {{ number_format($totalPrice, 2) }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-info text-white shadow">
            <div class="card-body text-center">
                <h4>Cache TTL</h4>
                <h2>{{ config('querycache.ttl') }}s</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-dark text-white shadow">
            <div class="card-body text-center">
                <h4>Cache Version</h4>
                <h2>v{{ \App\Services\ProductCacheService::version() }}</h2>
            </div>
        </div>
    </div>

</div>

<!-- Search + Filter Form -->

<form method="GET"
      action="{{ route('products.index') }}"
      class="mb-4 p-3 rounded-4"
      style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);">

    <div class="row g-2 align-items-end">

        <div class="col-md-3">
            <label>Search</label>
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Product name / price"
                   value="{{ request('search') }}">
        </div>

        <div class="col-md-3">
            <label>Category</label>
            <select name="category_id" class="form-control">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ $categoryId == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label>Min Price</label>
            <input type="number"
                   step="0.01"
                   name="min_price"
                   class="form-control"
                   placeholder="0"
                   value="{{ request('min_price') }}">
        </div>

        <div class="col-md-2">
            <label>Max Price</label>
            <input type="number"
                   step="0.01"
                   name="max_price"
                   class="form-control"
                   placeholder="9999"
                   value="{{ request('max_price') }}">
        </div>

        <div class="col-md-2">
            <label>Sort By</label>
            <select name="sort" class="form-control">
                <option value="id_asc" {{ $sort == 'id_asc' ? 'selected' : '' }}>Newest ID</option>
                <option value="id_desc" {{ $sort == 'id_desc' ? 'selected' : '' }}>Oldest ID</option>
                <option value="name_asc" {{ $sort == 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                <option value="name_desc" {{ $sort == 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Price Low</option>
                <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Price High</option>
            </select>
        </div>

        <div class="col-12 d-flex gap-2 mt-2">
            <button class="btn btn-warning">
                <i class="bi bi-funnel"></i> Apply Filters
            </button>
            <a href="{{ route('products.index') }}" class="btn btn-outline-light">
                Reset
            </a>
        </div>

    </div>

</form>

<!-- Product Table -->

<div class="card shadow border-0">

    <div class="card-body p-0">

        <table class="table table-hover table-bordered mb-0">

            <thead class="table-secondary text-dark">

                <tr>

                    <th width="60">ID</th>
                    <th>Name</th>
                    <th width="160">Category</th>
                    <th width="150">Price</th>
                    <th width="200">Created</th>
                    <th width="180">Action</th>

                </tr>

            </thead>

            <tbody>

                @forelse($products as $product)

                <tr>

                    <td>{{ $product->id }}</td>

                    <td>{{ $product->name }}</td>

                    <td>
                        @if($product->category)
                            <span class="badge bg-info text-dark">
                                {{ $product->category->name }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>

                    <td>₹ {{ number_format($product->price, 2) }}</td>

                    <td>{{ $product->created_at->format('d-m-Y h:i A') }}</td>

                    <td>

                        <a href="{{ route('products.edit', $product->id) }}"
                           class="btn btn-sm btn-info">
                            Edit
                        </a>

                        <form action="{{ route('products.destroy', $product->id) }}"
                              method="POST"
                              class="d-inline">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete Product?')">
                                Delete
                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6"
                        class="text-center py-4">
                        No Products Found
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<!-- Custom Pagination -->

@if ($products->hasPages())

<div class="d-flex justify-content-center mt-4">

    <nav>

        <ul class="pagination">

            {{-- Previous Button --}}
            @if ($products->onFirstPage())

            <li class="page-item disabled">
                <span class="page-link">Previous</span>
            </li>

            @else

            <li class="page-item">
                <a class="page-link" href="{{ $products->previousPageUrl() }}">Previous</a>
            </li>

            @endif

            {{-- Page Numbers --}}
            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)

            @if ($page == $products->currentPage())

            <li class="page-item active">
                <span class="page-link">{{ $page }}</span>
            </li>

            @else

            <li class="page-item">
                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
            </li>

            @endif

            @endforeach

            {{-- Next Button --}}
            @if ($products->hasMorePages())

            <li class="page-item">
                <a class="page-link" href="{{ $products->nextPageUrl() }}">Next</a>
            </li>

            @else

            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>

            @endif

        </ul>

    </nav>

</div>

@endif

@endsection
