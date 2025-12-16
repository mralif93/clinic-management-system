@props([
    'variant' => 'info', // success, warning, danger, info
    'dismissible' => false,
    'class' => ''
])

@php
    $variants = [
        'success' => [
            'bg' => 'bg-green-50',
            'border' => 'border-green-200',
            'text' => 'text-green-800',
            'icon' => 'bx-check-circle',
            'iconColor' => 'text-green-600'
        ],
        'warning' => [
            'bg' => 'bg-yellow-50',
            'border' => 'border-yellow-200',
            'text' => 'text-yellow-800',
            'icon' => 'bx-error',
            'iconColor' => 'text-yellow-600'
        ],
        'danger' => [
            'bg' => 'bg-red-50',
            'border' => 'border-red-200',
            'text' => 'text-red-800',
            'icon' => 'bx-error-circle',
            'iconColor' => 'text-red-600'
        ],
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-200',
            'text' => 'text-blue-800',
            'icon' => 'bx-info-circle',
            'iconColor' => 'text-blue-600'
        ]
    ];
    
    $style = $variants[$variant] ?? $variants['info'];
    $classes = 'rounded-lg border p-4 ' . $style['bg'] . ' ' . $style['border'] . ' ' . $class;
@endphp

<div class="{{ $classes }}" role="alert" x-data="{ show: true }" x-show="show" x-transition>
    <div class="flex items-start gap-3">
        <div class="flex-shrink-0">
            <i class='{{ $style['icon'] }} {{ $style['iconColor'] }} text-xl'></i>
        </div>
        
        <div class="flex-1 {{ $style['text'] }}">
            {{ $slot }}
        </div>
        
        @if($dismissible)
            <button 
                @click="show = false"
                class="flex-shrink-0 {{ $style['text'] }} hover:opacity-75 transition-opacity"
                aria-label="Dismiss alert"
            >
                <i class='bx bx-x text-xl'></i>
            </button>
        @endif
    </div>
</div>

