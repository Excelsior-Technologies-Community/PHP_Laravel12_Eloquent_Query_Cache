@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>⬆ Bulk Import Products (CSV)</h2>
    <a href="{{ route('products.index') }}" class="btn btn-outline-light rounded-pill">
        ← Back
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">

        <p class="text-muted">
            CSV format:
            <code>name,price,category</code>
            (category is optional and created automatically if missing)
        </p>

        <form action="{{ route('products.importStore') }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <label>CSV File</label>
                <input type="file"
                       name="file"
                       accept=".csv,.txt"
                       class="form-control"
                       required>
            </div>

            <button class="btn btn-success">Import</button>

        </form>

    </div>
</div>

@endsection
