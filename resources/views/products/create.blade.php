@extends('layouts.app')

@section('content')

<div class="card border-0 shadow-sm">

    <div class="card-body">

        <h2 class="mb-4">Add Product</h2>

        <form action="{{ route('products.store') }}"
              method="POST">

            @csrf

            <div class="mb-3">

                <label>Name</label>

                <input type="text"
                       name="name"
                       class="form-control">

            </div>

            <div class="mb-3">

                <label>Category</label>

                <select name="category_id" class="form-control">
                    <option value="">No Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

            </div>

            <div class="mb-3">

                <label>Price</label>

                <input type="number"
                       step="0.01"
                       name="price"
                       class="form-control">

            </div>

            <button class="btn btn-success">
                Save Product
            </button>

        </form>

    </div>

</div>

@endsection