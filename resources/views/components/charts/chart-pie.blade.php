@props([
    'data' => [],
    'labels' => [],
    'title' => null,
    'height' => '300px',
    'colors' => ['#3b82f6', '#10b981', '#f59e0b', '#f97316', '#8b5cf6'],
    'chartId' => null
])

@php
    $chartId = $chartId ?? 'chart_' . uniqid();
    
    // Format data for Chart.js
    $formattedData = [
        'labels' => $labels,
        'datasets' => [[
            'data' => $data,
            'backgroundColor' => $colors,
            'borderWidth' => 2,
            'borderColor' => '#ffffff'
        ]]
    ];
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
        type: 'pie',
        data: @json($formattedData),
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            label += context.parsed + ' (' + percentage + '%)';
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush

