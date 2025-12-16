@props([
    'size' => 'md', // sm, md, lg
    'text' => null,
    'overlay' => false,
    'class' => ''
])

@php
    $sizes = [
        'sm' => 'w-4 h-4',
        'md' => 'w-8 h-8',
        'lg' => 'w-12 h-12'
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    
    $overlayClasses = $overlay ? 'fixed inset-0 bg-white/80 backdrop-blur-sm z-50 flex items-center justify-center' : '';
@endphp

<div class="{{ $overlayClasses }} {{ $class }}" role="status" aria-label="Loading">
    <div class="flex flex-col items-center gap-3">
        <div class="{{ $sizeClass }} border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
        @if($text)
            <p class="text-sm text-gray-600">{{ $text }}</p>
        @endif
    </div>
    <span class="sr-only">Loading...</span>
</div>

