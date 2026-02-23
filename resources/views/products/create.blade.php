@extends('layouts.app')

@section('content')

<h1>Add Product</h1>

<form method="POST" action="{{ route('products.store') }}">
    @csrf

    <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="name" value="{{ old('name') }}">
        @error('name')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Price</label>
        <input type="text" name="price" value="{{ old('price') }}">
        @error('price')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Save Product</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
</form>

@endsection