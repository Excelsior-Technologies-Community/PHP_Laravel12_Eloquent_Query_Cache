@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>🔧 Cache Status</h2>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill">
        ← Back
    </a>
</div>

<div class="alert alert-success d-flex align-items-center gap-2">
    <i class="bi bi-check-circle-fill"></i>
    <span>Cache system is <strong>active</strong> and serving responses.</span>
</div>

<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-hdd-network fs-4 text-primary"></i>
                    <h6 class="mb-0 text-muted">Cache Driver</h6>
                </div>
                <h3 class="mb-0">{{ ucfirst($data['cache_driver']) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-clock-history fs-4 text-warning"></i>
                    <h6 class="mb-0 text-muted">Cache TTL</h6>
                </div>
                <h3 class="mb-0">{{ $data['cache_ttl'] }}s</h3>
                <small class="text-muted">per cached query</small>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-tag fs-4 text-info"></i>
                    <h6 class="mb-0 text-muted">Cache Version</h6>
                </div>
                <h3 class="mb-0">v{{ $data['cache_version'] }}</h3>
                <small class="text-muted">namespace for invalidation</small>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-layers fs-4 text-success"></i>
                    <h6 class="mb-0 text-muted">Tag Support</h6>
                </div>
                @if($data['taggable'])
                    <span class="badge text-bg-success fs-6">Enabled (Redis / Memcached)</span>
                @else
                    <span class="badge text-bg-secondary fs-6">Versioning (File / Database)</span>
                @endif
                <p class="text-muted mt-2 mb-0 small">
                    Global invalidation is handled via the cache version, so it works on every driver.
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-box-seam fs-4 text-primary"></i>
                    <h6 class="mb-0 text-muted">Storage Type</h6>
                </div>
                <h5 class="mb-0">{{ $data['cached_items'] }}</h5>
            </div>
        </div>
    </div>

</div>

<div class="d-flex gap-2">
    <a href="{{ route('products.clearCache') }}" class="btn btn-danger">
        <i class="bi bi-trash"></i> Clear Cache
    </a>
    <a href="{{ route('products.cacheKeys') }}" class="btn btn-outline-primary">
        <i class="bi bi-key"></i> View Cached Keys
    </a>
    <a href="{{ route('products.analytics') }}" class="btn btn-outline-success">
        <i class="bi bi-graph-up"></i> Analytics
    </a>
</div>

@endsection
