@extends('layouts.app')

@section('content')

<div class="top-bar">
    <h1>Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        + Add Product
    </a>
</div>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<table>
    <thead>
        <tr>
            <th width="80">ID</th>
            <th>Product Name</th>
            <th width="150">Price</th>
            <th width="200">Created At</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>₹ {{ number_format($product->price, 2) }}</td>
                <td>{{ $product->created_at->format('d-m-Y h:i A') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align:center">
                    No products found
                </td>
            </tr>
        @endforelse
    </tbody>
</table>



@endsection