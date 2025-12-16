@props([
    'count' => 0,
    'max' => 99,
    'showZero' => false,
    'variant' => 'danger', // danger, warning, info
    'class' => ''
])

@php
    $variants = [
        'danger' => 'bg-red-500',
        'warning' => 'bg-yellow-500',
        'info' => 'bg-blue-500'
    ];
    
    $displayCount = $count > $max ? $max . '+' : $count;
    $show = ($count > 0 || $showZero);
@endphp

@if($show)
    <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 text-xs font-bold text-white rounded-full {{ $variants[$variant] ?? $variants['danger'] }} {{ $class }}">
        {{ $displayCount }}
    </span>
@endif

