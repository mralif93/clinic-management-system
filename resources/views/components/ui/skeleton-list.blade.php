@props([
    'items' => 5,
    'showAvatar' => false,
    'showActions' => false
])

<div class="space-y-3">
    @for($i = 0; $i < $items; $i++)
        <div class="flex items-center gap-4 p-3 bg-white border border-gray-100 rounded-lg">
            @if($showAvatar)
                <x-ui.skeleton variant="circle" width="3rem" height="3rem" />
            @endif
            
            <div class="flex-1 space-y-2">
                <x-ui.skeleton variant="text" width="{{ $i % 2 === 0 ? '70%' : '85%' }}" />
                <x-ui.skeleton variant="text" width="50%" height="0.75rem" />
            </div>
            
            @if($showActions)
                <x-ui.skeleton variant="rect" width="2rem" height="2rem" />
            @endif
        </div>
    @endfor
</div>

