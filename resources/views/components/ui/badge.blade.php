@props([
    'variant' => 'primary', // primary, success, warning, danger, info, neutral
    'size' => 'md', // sm, md
    'class' => ''
])

@php
    $variants = [
        'primary' => 'bg-blue-100 text-blue-700',
        'success' => 'bg-green-100 text-green-700',
        'warning' => 'bg-yellow-100 text-yellow-700',
        'danger' => 'bg-red-100 text-red-700',
        'info' => 'bg-cyan-100 text-cyan-700',
        'neutral' => 'bg-gray-100 text-gray-700'
    ];
    
    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm'
    ];
    
    $classes = 'inline-flex items-center font-medium rounded-full ' . 
               ($variants[$variant] ?? $variants['primary']) . ' ' . 
               ($sizes[$size] ?? $sizes['md']) . ' ' . 
               $class;
@endphp

<span class="{{ $classes }}">
    {{ $slot }}
</span>

