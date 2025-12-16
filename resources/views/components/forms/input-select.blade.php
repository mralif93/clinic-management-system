@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => 'Select an option',
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
    $selectedValue = old($name, $value);
    
    $inputClasses = 'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors appearance-none bg-white ' .
                   ($hasError ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500') .
                   ($disabled ? ' bg-gray-100 cursor-not-allowed' : '') .
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
        <select
            id="{{ $inputId }}"
            name="{{ $name }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            class="{{ $inputClasses }}"
            aria-invalid="{{ $hasError ? 'true' : 'false' }}"
            aria-describedby="{{ $hasError ? $inputId . '_error' : ($helpText ? $inputId . '_help' : '') }}"
            {{ $attributes->except(['class']) }}
        >
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            
            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ $selectedValue == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
        
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <i class='bx bx-chevron-down {{ $hasError ? "text-red-500" : "text-gray-400" }}'></i>
        </div>
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

