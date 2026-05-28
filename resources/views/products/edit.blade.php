@extends('layouts.app')

@section('content')

<div class="card bg-dark border-secondary">

    <div class="card-body">

        <h2 class="mb-4">Edit Product</h2>

        <form action="{{ route('products.update', $product->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="mb-3">

                <label>Name</label>

                <input type="text"
                       name="name"
                       value="{{ $product->name }}"
                       class="form-control">

            </div>

            <div class="mb-3">

                <label>Price</label>

                <input type="number"
                       step="0.01"
                       name="price"
                       value="{{ $product->price }}"
                       class="form-control">

            </div>

            <button class="btn btn-primary">
                Update Product
            </button>

        </form>

    </div>

</div>

@endsection