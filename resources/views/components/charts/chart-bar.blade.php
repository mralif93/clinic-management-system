@props([
    'data' => [],
    'labels' => [],
    'title' => null,
    'height' => '300px',
    'colors' => ['#3b82f6', '#10b981', '#f59e0b'],
    'chartId' => null,
    'horizontal' => false
])

@php
    $chartId = $chartId ?? 'chart_' . uniqid();
@endphp

<div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
    @if($title)
        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $title }}</h3>
    @endif
    
    <canvas id="{{ $chartId }}" style="height: {{ $height }};"></canvas>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $chartId }}');
    if (!ctx) return;
    
    new Chart(ctx, {
        type: '{{ $horizontal ? "bar" : "bar" }}',
        data: {
            labels: @json($labels),
            datasets: @json($data)
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: '{{ $horizontal ? "y" : "x" }}',
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush

