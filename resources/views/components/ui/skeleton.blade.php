@props([
    'variant' => 'text', // text, circle, rect
    'width' => null,
    'height' => null,
    'class' => ''
])

@php
    $classes = 'animate-pulse bg-gray-200 rounded ' . $class;
    
    if ($variant === 'circle') {
        $classes .= ' rounded-full';
        $width = $width ?? $height ?? '1rem';
        $height = $height ?? $width ?? '1rem';
    } elseif ($variant === 'rect') {
        $width = $width ?? '100%';
        $height = $height ?? '1rem';
    } else {
        $width = $width ?? '100%';
        $height = $height ?? '0.875rem';
    }
    
    $style = '';
    if ($width) $style .= 'width: ' . $width . '; ';
    if ($height) $style .= 'height: ' . $height . '; ';
@endphp

<div class="{{ $classes }}" style="{{ $style }}"></div>

