@props([
    'responsive' => true,
    'hover' => true,
    'striped' => false,
    'class' => ''
])

@php
    $tableClasses = 'min-w-full divide-y divide-gray-200 ' . $class;
    $wrapperClasses = $responsive ? 'w-full overflow-x-auto' : 'w-full';
@endphp

<div class="{{ $wrapperClasses }}">
    <table class="{{ $tableClasses }}">
        {{ $slot }}
    </table>
</div>

