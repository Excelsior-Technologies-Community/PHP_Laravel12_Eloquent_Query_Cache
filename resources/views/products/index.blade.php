@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Laravel Eloquent Query Cache</h2>

    <div>

        <a href="{{ route('products.create') }}"
           class="btn btn-primary">
            Add Product
        </a>

        <a href="{{ route('products.clearCache') }}"
           class="btn btn-danger">
            Clear Cache
        </a>

    </div>

</div>

@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif

<!-- Dashboard Cards -->

<div class="row mb-4">

    <div class="col-md-6">

        <div class="card bg-primary text-white shadow">
            <div class="card-body text-center">

                <h4>Total Products</h4>

                <h2>{{ $totalProducts }}</h2>

            </div>
        </div>

    </div>

    <div class="col-md-6">

        <div class="card bg-success text-white shadow">
            <div class="card-body text-center">

                <h4>Total Price</h4>

                <h2>
                    ₹ {{ number_format($totalPrice, 2) }}
                </h2>

            </div>
        </div>

    </div>

</div>

<!-- Search Form -->

<form method="GET"
      action="{{ route('products.index') }}"
      class="mb-4">

    <div class="row g-2">

        <div class="col-md-10">

            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Search Product..."
                   value="{{ request('search') }}">

        </div>

        <div class="col-md-2">

            <button class="btn btn-warning w-100">
                Search
            </button>

        </div>

    </div>

</form>

<!-- Product Table -->

<div class="card shadow border-0">

    <div class="card-body p-0">

        <table class="table table-dark table-hover table-bordered mb-0">

            <thead class="table-secondary text-dark">

                <tr>

                    <th width="80">ID</th>

                    <th>Name</th>

                    <th width="150">Price</th>

                    <th width="220">Created</th>

                    <th width="180">Action</th>

                </tr>

            </thead>

            <tbody>

                @forelse($products as $product)

                    <tr>

                        <td>
                            {{ $product->id }}
                        </td>

                        <td>
                            {{ $product->name }}
                        </td>

                        <td>
                            ₹ {{ number_format($product->price, 2) }}
                        </td>

                        <td>
                            {{ $product->created_at->format('d-m-Y h:i A') }}
                        </td>

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

                        <td colspan="5"
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
                        <span class="page-link">
                            Previous
                        </span>
                    </li>

                @else

                    <li class="page-item">

                        <a class="page-link"
                           href="{{ $products->previousPageUrl() }}">

                            Previous

                        </a>

                    </li>

                @endif

                {{-- Page Numbers --}}
                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)

                    @if ($page == $products->currentPage())

                        <li class="page-item active">

                            <span class="page-link">

                                {{ $page }}

                            </span>

                        </li>

                    @else

                        <li class="page-item">

                            <a class="page-link"
                               href="{{ $url }}">

                                {{ $page }}

                            </a>

                        </li>

                    @endif

                @endforeach

                {{-- Next Button --}}
                @if ($products->hasMorePages())

                    <li class="page-item">

                        <a class="page-link"
                           href="{{ $products->nextPageUrl() }}">

                            Next

                        </a>

                    </li>

                @else

                    <li class="page-item disabled">

                        <span class="page-link">
                            Next
                        </span>

                    </li>

                @endif

            </ul>

        </nav>

    </div>

@endif

@endsection