@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Cache Analytics Dashboard</h2>
</div>

<div class="row">

    <div class="col-md-6">
        <div class="card bg-success text-white shadow">
            <div class="card-body text-center">
                <h4>Cache Hits</h4>
                <h1>{{ $hits }}</h1>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card bg-danger text-white shadow">
            <div class="card-body text-center">
                <h4>Cache Misses</h4>
                <h1>{{ $misses }}</h1>
            </div>
        </div>
    </div>

</div>

@endsection