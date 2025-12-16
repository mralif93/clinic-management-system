@props([
    'columns' => 2, // 1, 2, 3, 4
    'class' => ''
])

@php
    $gridClasses = match($columns) {
        1 => 'grid-cols-1',
        2 => 'grid-cols-1 md:grid-cols-2',
        3 => 'grid-cols-1 md:grid-cols-3',
        4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 md:grid-cols-2'
    };
@endphp

<div class="grid {{ $gridClasses }} gap-6 {{ $class }}">
    {{ $slot }}
</div>

