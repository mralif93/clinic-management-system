@props([
    'title' => null,
    'subtitle' => null,
    'badges' => [],
    'actions' => null,
    'class' => ''
])

<div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm {{ $class }}">
    <div class="flex items-start justify-between gap-4 mb-3">
        <div class="flex-1 min-w-0">
            @if($title)
                <h3 class="text-base font-semibold text-gray-900 truncate">{{ $title }}</h3>
            @endif
            @if($subtitle)
                <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
        
        @if($badges)
            <div class="flex flex-wrap gap-2 flex-shrink-0">
                @foreach($badges as $badge)
                    <x-ui.badge :variant="$badge['variant'] ?? 'neutral'" :size="'sm'">
                        {{ $badge['label'] }}
                    </x-ui.badge>
                @endforeach
            </div>
        @endif
    </div>
    
    <div class="space-y-2">
        {{ $slot }}
    </div>
    
    @if($actions)
        <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
            {{ $actions }}
        </div>
    @endif
</div>

