@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>📈 Cache Analytics Dashboard</h2>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill">
        ← Back
    </a>
</div>

<div class="row mb-4">

    <div class="col-md-3">
        <div class="card bg-success text-white shadow border-0">
            <div class="card-body text-center">
                <h5>Cache Hits</h5>
                <h2>{{ $hits }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-danger text-white shadow border-0">
            <div class="card-body text-center">
                <h5>Cache Misses</h5>
                <h2>{{ $misses }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-primary text-white shadow border-0">
            <div class="card-body text-center">
                <h5>Total Requests</h5>
                <h2>{{ $total }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card bg-warning text-white shadow border-0">
            <div class="card-body text-center">
                <h5>Avg Miss Time</h5>
                <h2>{{ number_format($avgDuration, 4) }}s</h2>
            </div>
        </div>
    </div>

</div>

<div class="row mb-4">

    <!-- Hit / Miss Doughnut -->
    <div class="col-md-5">
        <div class="card shadow border-0 h-100">
            <div class="card-body text-center">
                <h5 class="mb-3 text-start">Hit vs Miss Rate</h5>
                <div style="position:relative; max-width:280px; margin:auto;">
                    <canvas id="cacheChart" height="260"></canvas>
                </div>
                <div class="d-flex justify-content-center gap-4 mt-3">
                    <div>
                        <span class="badge rounded-pill text-bg-success">Hits {{ $hits }}</span>
                    </div>
                    <div>
                        <span class="badge rounded-pill text-bg-danger">Misses {{ $misses }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hits vs Misses Bar -->
    <div class="col-md-7">
        <div class="card shadow border-0 h-100">
            <div class="card-body">
                <h5 class="mb-3">Hits vs Misses</h5>
                <canvas id="barChart" height="200"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- Slow Queries -->
<div class="card shadow border-0">
    <div class="card-body">
        <h5 class="mb-3">🐢 Slowest Miss Queries (execution time)</h5>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Query</th>
                        <th width="200">Duration (s)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($slowQueries as $index => $log)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $log->query ?: '(empty)' }}</td>
                            <td>{{ number_format($log->duration, 4) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-3 text-muted">No slow queries recorded</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // Center text plugin for doughnut
    const centerText = {
        id: 'centerText',
        afterDraw(chart) {
            if (chart.config.type !== 'doughnut') return;
            const { ctx, chartArea: { width, height } } = chart;
            ctx.save();
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillStyle = '#0f172a';
            ctx.font = '700 30px Poppins, sans-serif';
            ctx.fillText('{{ $hitRate }}%', width / 2, height / 2 - 8);
            ctx.fillStyle = '#64748b';
            ctx.font = '500 13px Poppins, sans-serif';
            ctx.fillText('Hit Rate', width / 2, height / 2 + 18);
            ctx.restore();
        }
    };

    const doughnut = document.getElementById('cacheChart');
    if (doughnut) {
        new Chart(doughnut.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Hits', 'Misses'],
                datasets: [{
                    data: [{{ $hits }}, {{ $misses }}],
                    backgroundColor: ['#16a34a', '#dc2626'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                cutout: '68%',
                responsive: true,
                plugins: {
                    legend: { display: false }
                }
            },
            plugins: [centerText]
        });
    }

    const bar = document.getElementById('barChart');
    if (bar) {
        new Chart(bar.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Hits', 'Misses'],
                datasets: [{
                    label: 'Requests',
                    data: [{{ $hits }}, {{ $misses }}],
                    backgroundColor: ['#16a34a', '#dc2626'],
                    borderRadius: 10,
                    maxBarThickness: 70
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ' ' + ctx.parsed.y + ' requests'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#64748b' },
                        grid: { color: '#eef2f7' }
                    },
                    x: {
                        ticks: { color: '#475569', font: { weight: '600' } },
                        grid: { display: false }
                    }
                }
            }
        });
    }
</script>
@endpush
