@props([
    'icon' => 'bx bx-inbox',
    'title' => 'No Data',
    'description' => 'There are no items to display.',
    'actionLabel' => null,
    'actionUrl' => null,
    'actionIcon' => 'bx bx-plus',
    'variant' => 'default' // default, error, no-results
])

@php
    $iconColors = [
        'default' => 'text-gray-400',
        'error' => 'text-red-400',
        'no-results' => 'text-blue-400'
    ];
    
    $iconColor = $iconColors[$variant] ?? $iconColors['default'];
@endphp

<div class="text-center py-12 px-4">
    <div class="flex justify-center mb-4">
        <div class="w-16 h-16 {{ $iconColor }} flex items-center justify-center">
            <i class='{{ $icon }} text-6xl'></i>
        </div>
    </div>
    
    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
    
    @if($description)
        <p class="text-gray-500 mb-6 max-w-md mx-auto">{{ $description }}</p>
    @endif
    
    @if($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
            <i class='{{ $actionIcon }}'></i>
            {{ $actionLabel }}
        </a>
    @endif
</div>

