@props([
    'actions' => []
])

@if(count($actions) > 0)
    <div class="quick-actions-menu" x-data="{ open: false }" @click.away="open = false">
        <button 
            @click="open = !open"
            class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
            aria-label="Quick actions"
        >
            <i class='bx bx-dots-horizontal text-xl'></i>
            <span class="hidden sm:inline">Quick Actions</span>
        </button>
        
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
            style="display: none;"
        >
            @foreach($actions as $action)
                <a href="{{ $action['url'] ?? '#' }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class='{{ $action['icon'] ?? 'bx-link' }} text-lg text-gray-400'></i>
                    <span>{{ $action['label'] ?? '' }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endif

