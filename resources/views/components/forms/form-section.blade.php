@props([
    'title' => null,
    'description' => null,
    'collapsible' => false,
    'defaultOpen' => true,
    'class' => ''
])

@php
    $sectionId = 'section_' . uniqid();
@endphp

<div class="bg-white border border-gray-200 rounded-lg shadow-sm {{ $class }}" 
     @if($collapsible) x-data="{ open: {{ $defaultOpen ? 'true' : 'false' }} }" @endif>
    @if($title)
        <div class="px-6 py-4 border-b border-gray-200 {{ $collapsible ? 'cursor-pointer' : '' }}"
             @if($collapsible) @click="open = !open" @endif>
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                    @if($description)
                        <p class="mt-1 text-sm text-gray-500">{{ $description }}</p>
                    @endif
                </div>
                @if($collapsible)
                    <i class='bx bx-chevron-down text-gray-400 transition-transform duration-200' 
                       :class="{ 'rotate-180': open }"></i>
                @endif
            </div>
        </div>
    @endif
    
    <div class="p-6" @if($collapsible) x-show="open" x-transition @endif>
        {{ $slot }}
    </div>
</div>

