@props([
    'name',
    'label' => null,
    'value' => null,
    'min' => null,
    'max' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'helpText' => null,
    'class' => '',
    'inputClass' => ''
])

@php
    $inputId = $name . '_' . uniqid();
    $hasError = $errors->has($name) || $error;
    $errorMessage = $error ?? ($errors->first($name));
    
    $inputClasses = 'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors ' .
                   ($hasError ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500') .
                   ($disabled ? ' bg-gray-100 cursor-not-allowed' : ' bg-white') .
                   ' ' . $inputClass;
@endphp

<div class="{{ $class }}">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class='bx bx-calendar {{ $hasError ? "text-red-500" : "text-gray-400" }}'></i>
        </div>
        
        <input
            type="date"
            id="{{ $inputId }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            @if($min) min="{{ $min }}" @endif
            @if($max) max="{{ $max }}" @endif
            @if($required) required @endif
            @if($disabled) disabled @endif
            class="{{ $inputClasses }} pl-10"
            aria-invalid="{{ $hasError ? 'true' : 'false' }}"
            aria-describedby="{{ $hasError ? $inputId . '_error' : ($helpText ? $inputId . '_help' : '') }}"
            {{ $attributes->except(['class']) }}
        >
    </div>
    
    @if($helpText && !$hasError)
        <p id="{{ $inputId }}_help" class="mt-1 text-sm text-gray-500">{{ $helpText }}</p>
    @endif
    
    @if($hasError)
        <p id="{{ $inputId }}_error" class="mt-1 text-sm text-red-600 flex items-center gap-1" role="alert">
            <i class='bx bx-error-circle'></i>
            {{ $errorMessage }}
        </p>
    @endif
</div>

