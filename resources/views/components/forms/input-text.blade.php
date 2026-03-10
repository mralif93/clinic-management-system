<!-- Component Version: 1.0.2-Consistent -->
@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'type' => 'text',
    'icon' => null,
    'iconPosition' => 'left',
    'error' => null,
    'helpText' => null,
    'class' => '',
    'inputClass' => '',
    'clearable' => false,
    'passwordToggle' => false
])

@php
    $inputId = $name . '_' . uniqid();
    $hasError = $errors->has($name) || $error;
    $errorMessage = $error ?? ($errors->first($name));
    
    // Determine internal state
    $isPassword = $type === 'password';
    $canToggle = $isPassword && $passwordToggle;
    
    // Normalize Icon Class
    $iconClass = $icon;
    if ($icon && str_starts_with($icon, 'hgi-')) {
        $iconClass = 'hgi hgi-stroke ' . $icon;
    }
    
    $inputClasses = 'w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 text-base ' . 
                   ($hasError ? 'border-red-500 focus:ring-red-500' : 'border-gray-200 focus:ring-blue-500 focus:border-blue-500 hover:border-gray-300') . 
                   ($disabled || $readonly ? ' bg-gray-50 cursor-not-allowed opacity-75' : ' bg-white px-3') . 
                   ($icon && $iconPosition === 'left' ? ' pl-12' : '') .
                   ($icon && $iconPosition === 'right' || $clearable || $canToggle ? ' pr-12' : '') .
                   ' ' . $inputClass;
@endphp

<div class="{{ $class }}" 
    x-data="{ 
        stateValue: '{{ old($name, $value) }}',
        statePassVisible: false,
        stateToggleActive: {{ $passwordToggle ? 'true' : 'false' }}
    }">
    @if($label)
        <label for="{{ $inputId }}" class="block text-base font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative group">
        @if($icon && $iconPosition === 'left')
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-200 group-focus-within:text-blue-500">
                <i class='{{ $iconClass }} {{ $hasError ? "text-red-500" : "text-gray-400" }} text-xl'></i>
            </div>
        @endif
        
        <input
            :type="stateToggleActive ? (statePassVisible ? 'text' : 'password') : '{{ $type }}'"
            id="{{ $inputId }}"
            name="{{ $name }}"
            x-model="stateValue"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            class="{{ $inputClasses }}"
            aria-invalid="{{ $hasError ? 'true' : 'false' }}"
            aria-describedby="{{ $hasError ? $inputId . '_error' : ($helpText ? $inputId . '_help' : '') }}"
            {{ $attributes->except(['class']) }}
        >
        
        <!-- Action Buttons (Right) -->
        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center gap-1.5">
            @if($clearable)
                <button 
                    type="button"
                    x-show="stateValue.length > 0"
                    x-cloak
                    @click="stateValue = ''; $nextTick(() => $el.closest('.relative').querySelector('input').focus())"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors p-1.5 rounded-full hover:bg-gray-100"
                    title="Clear field"
                >
                    <i class="hgi hgi-stroke hgi-cancel-01 text-xl"></i>
                </button>
            @endif

            @if($canToggle)
                <button 
                    type="button"
                    @click="statePassVisible = !statePassVisible"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors p-1.5 rounded-full hover:bg-gray-100"
                    :title="statePassVisible ? 'Hide password' : 'Show password'"
                >
                    <i :class="statePassVisible ? 'hgi hgi-stroke hgi-view-off-slash' : 'hgi hgi-stroke hgi-view'" class="text-xl"></i>
                </button>
            @endif

            @if($icon && $iconPosition === 'right' && !$clearable && !$canToggle)
                <i class='{{ $iconClass }} {{ $hasError ? "text-red-500" : "text-gray-400" }} text-lg'></i>
            @endif
        </div>
    </div>
    
    @if($helpText && !$hasError)
        <p id="{{ $inputId }}_help" class="mt-1.5 text-xs text-gray-500">{{ $helpText }}</p>
    @endif
    
    @if($hasError)
        <p id="{{ $inputId }}_error" class="mt-1.5 text-xs text-red-600 flex items-center gap-1 animate-fade-in" role="alert">
            <i class='hgi hgi-stroke hgi-alert-circle text-sm'></i>
            {{ $errorMessage }}
        </p>
    @endif
</div>
