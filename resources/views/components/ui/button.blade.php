@props([
    'variant' => 'primary', // primary, secondary, success, warning, danger, outline-primary, outline-secondary
    'size' => 'md', // sm, md, lg
    'type' => 'button',
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'loading' => false,
    'disabled' => false,
    'class' => ''
])

@php
    $variants = [
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
        'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-gray-500',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
        'warning' => 'bg-yellow-600 text-white hover:bg-yellow-700 focus:ring-yellow-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'outline-primary' => 'bg-transparent text-blue-600 border-2 border-blue-600 hover:bg-blue-50 focus:ring-blue-500',
        'outline-secondary' => 'bg-transparent text-gray-700 border-2 border-gray-300 hover:bg-gray-50 focus:ring-gray-500'
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base'
    ];
    
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition-all duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
    $loadingClasses = $loading ? 'relative text-transparent pointer-events-none' : '';
    
    $classes = $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses . ' ' . $loadingClasses . ' ' . $class;
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
    @if($disabled || $loading) disabled @endif
>
    @if($loading)
        <span class="absolute inset-0 flex items-center justify-center">
            <i class='bx bx-loader-alt bx-spin'></i>
        </span>
    @endif
    
    @if($icon && $iconPosition === 'left' && !$loading)
        <i class='{{ $icon }} mr-2'></i>
    @endif
    
    <span>{{ $slot }}</span>
    
    @if($icon && $iconPosition === 'right' && !$loading)
        <i class='{{ $icon }} ml-2'></i>
    @endif
</button>

