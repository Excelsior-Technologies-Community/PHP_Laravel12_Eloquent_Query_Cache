@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>🔑 Cached Keys Viewer</h2>
    <a href="{{ route('products.index') }}" class="btn btn-outline-light rounded-pill">
        ← Back
    </a>
</div>

<div class="alert alert-info">
    Store: <strong>{{ config('cache.default') }}</strong>
    &nbsp;|&nbsp; Tagged cache: <strong>{{ \App\Services\ProductCacheService::isTaggable() ? 'Yes' : 'No (versioning used)' }}</strong>
</div>

<div class="card shadow border-0">
    <div class="card-body p-0">
        <table class="table table-hover table-bordered mb-0">
            <thead class="table-secondary text-dark">
                <tr>
                    <th>Cache Key</th>
                    <th width="280">Expires At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($keys as $item)
                    <tr>
                        <td><code>{{ $item['key'] }}</code></td>
                        <td>{{ $item['expires_at'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center py-4">No cached keys found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
