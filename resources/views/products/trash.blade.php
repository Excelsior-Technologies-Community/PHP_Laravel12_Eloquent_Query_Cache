@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>🗑 Trash (Soft Deleted)</h2>
    <a href="{{ route('products.index') }}" class="btn btn-outline-light rounded-pill">
        ← Back
    </a>
</div>

<div class="card shadow border-0">
    <div class="card-body p-0">
        <table class="table table-hover table-bordered mb-0">
            <thead class="table-secondary text-dark">
                <tr>
                    <th width="60">ID</th>
                    <th>Name</th>
                    <th width="150">Price</th>
                    <th width="200">Deleted At</th>
                    <th width="240">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>₹ {{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->deleted_at->format('d-m-Y h:i A') }}</td>
                        <td>
                            <form action="{{ route('products.restore', $product->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success">Restore</button>
                            </form>

                            <form action="{{ route('products.forceDelete', $product->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Permanently delete?')">
                                    Force Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">Trash is empty</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
@endif

@endsection
