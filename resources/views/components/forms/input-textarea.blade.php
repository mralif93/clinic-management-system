@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => '',
    'rows' => 4,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'error' => null,
    'helpText' => null,
    'maxLength' => null,
    'showCounter' => false,
    'class' => '',
    'inputClass' => ''
])

@php
    $inputId = $name . '_' . uniqid();
    $hasError = $errors->has($name) || $error;
    $errorMessage = $error ?? ($errors->first($name));
    $currentValue = old($name, $value);
    $currentLength = mb_strlen($currentValue ?? '');
    
    $inputClasses = 'w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors resize-y ' .
                   ($hasError ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500') .
                   ($disabled || $readonly ? ' bg-gray-100 cursor-not-allowed' : ' bg-white') .
                   ' ' . $inputClass;
@endphp

<div class="{{ $class }}" x-data="{ length: {{ $currentLength }} }">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
            @if($showCounter && $maxLength)
                <span class="text-gray-500 font-normal ml-2">
                    (<span x-text="length"></span>/{{ $maxLength }})
                </span>
            @endif
        </label>
    @endif
    
    <textarea
        id="{{ $inputId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
        @if($maxLength) maxlength="{{ $maxLength }}" @endif
        @if($showCounter) x-on:input="length = $event.target.value.length" @endif
        class="{{ $inputClasses }}"
        aria-invalid="{{ $hasError ? 'true' : 'false' }}"
        aria-describedby="{{ $hasError ? $inputId . '_error' : ($helpText ? $inputId . '_help' : '') }}"
        {{ $attributes->except(['class']) }}
    >{{ $currentValue }}</textarea>
    
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

