@props([
    'title' => null,
    'value' => null,
    'subtitle' => null,
    'icon' => null,
    'trend' => null, // 'up', 'down', null
    'trendValue' => null,
    'color' => 'blue', // blue, green, purple, amber, red
    'sparkline' => null, // Array of values for sparkline
    'class' => ''
])

@php
    $colors = [
        'blue' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600', 'light' => 'bg-blue-100'],
        'green' => ['bg' => 'bg-green-500', 'text' => 'text-green-600', 'light' => 'bg-green-100'],
        'purple' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-600', 'light' => 'bg-purple-100'],
        'amber' => ['bg' => 'bg-amber-500', 'text' => 'text-amber-600', 'light' => 'bg-amber-100'],
        'red' => ['bg' => 'bg-red-500', 'text' => 'text-red-600', 'light' => 'bg-red-100']
    ];
    
    $colorScheme = $colors[$color] ?? $colors['blue'];
@endphp

<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow {{ $class }}">
    <div class="flex items-center justify-between mb-4">
        <div class="flex-1">
            @if($title)
                <p class="text-sm font-medium text-gray-500 mb-1">{{ $title }}</p>
            @endif
            @if($value !== null)
                <p class="text-3xl font-bold text-gray-900">{{ $value }}</p>
            @endif
        </div>
        
        @if($icon)
            <div class="w-12 h-12 {{ $colorScheme['light'] }} rounded-xl flex items-center justify-center">
                <i class='{{ $icon }} {{ $colorScheme['text'] }} text-2xl'></i>
            </div>
        @endif
    </div>
    
    @if($trend && $trendValue)
        <div class="flex items-center gap-2 mt-4">
            @if($trend === 'up')
                <i class='bx bx-trending-up text-green-600'></i>
                <span class="text-sm font-medium text-green-600">+{{ $trendValue }}</span>
            @elseif($trend === 'down')
                <i class='bx bx-trending-down text-red-600'></i>
                <span class="text-sm font-medium text-red-600">-{{ $trendValue }}</span>
            @endif
            @if($subtitle)
                <span class="text-sm text-gray-500">{{ $subtitle }}</span>
            @endif
        </div>
    @elseif($subtitle)
        <p class="text-sm text-gray-500 mt-2">{{ $subtitle }}</p>
    @endif
    
    @if($sparkline && is_array($sparkline))
        <div class="mt-4 h-12">
            <canvas 
                class="sparkline-chart" 
                data-values="{{ json_encode($sparkline) }}"
                data-color="{{ $colorScheme['text'] }}"
            ></canvas>
        </div>
    @endif
</div>

