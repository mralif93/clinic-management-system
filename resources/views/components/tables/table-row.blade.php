@props([
    'selectable' => false,
    'selected' => false,
    'hover' => true,
    'class' => ''
])

@php
    $rowClasses = ($hover ? 'hover:bg-gray-50 ' : '') . 
                  ($selected ? 'bg-blue-50 ' : '') . 
                  'transition-colors ' . $class;
@endphp

<tr 
    class="{{ $rowClasses }}"
    @if($selectable)
        onclick="this.classList.toggle('bg-blue-50'); this.querySelector('input[type=checkbox]')?.click();"
        role="button"
        tabindex="0"
    @endif
    {{ $attributes }}
>
    {{ $slot }}
</tr>

