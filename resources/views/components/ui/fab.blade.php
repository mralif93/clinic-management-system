@props([
    'icon' => 'bx-plus',
    'label' => null,
    'position' => 'bottom-right', // bottom-right, bottom-left, top-right, top-left
    'actions' => [],
    'class' => ''
])

@php
    $positions = [
        'bottom-right' => 'bottom-6 right-6',
        'bottom-left' => 'bottom-6 left-6',
        'top-right' => 'top-6 right-6',
        'top-left' => 'top-6 left-6'
    ];
    
    $positionClass = $positions[$position] ?? $positions['bottom-right'];
@endphp

<div class="fixed {{ $positionClass }} z-40 {{ $class }}" 
     x-data="{ open: false }"
     @click.away="open = false">
    
    @if(count($actions) > 0)
        <!-- Action Buttons -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="mb-4 space-y-3">
            @foreach($actions as $action)
                <a href="{{ $action['url'] ?? '#' }}" 
                   class="flex items-center gap-3 px-4 py-3 bg-white rounded-lg shadow-lg hover:shadow-xl transition-all group">
                    <div class="w-10 h-10 bg-{{ $action['color'] ?? 'blue' }}-100 rounded-lg flex items-center justify-center group-hover:bg-{{ $action['color'] ?? 'blue' }}-200 transition-colors">
                        <i class='{{ $action['icon'] ?? 'bx-link' }} text-{{ $action['color'] ?? 'blue' }}-600 text-xl'></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700 whitespace-nowrap">{{ $action['label'] ?? '' }}</span>
                </a>
            @endforeach
        </div>
    @endif
    
    <!-- Main FAB Button -->
    <button 
        @click="open = !open"
        class="w-14 h-14 bg-blue-600 text-white rounded-full shadow-lg hover:shadow-xl hover:bg-blue-700 transition-all flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        aria-label="{{ $label ?? 'Quick actions' }}"
    >
        <i class='{{ $icon }} text-2xl transition-transform' :class="{ 'rotate-45': open && count($actions) > 0 }"></i>
    </button>
</div>

