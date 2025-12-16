@props([
    'showImage' => false,
    'showActions' => false
])

<div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
    @if($showImage)
        <!-- Image Skeleton -->
        <x-ui.skeleton variant="rect" width="100%" height="12rem" class="mb-4" />
    @endif
    
    <!-- Title Skeleton -->
    <x-ui.skeleton variant="text" width="70%" height="1.25rem" class="mb-3" />
    
    <!-- Content Lines -->
    <x-ui.skeleton variant="text" width="100%" class="mb-2" />
    <x-ui.skeleton variant="text" width="90%" class="mb-2" />
    <x-ui.skeleton variant="text" width="60%" class="mb-4" />
    
    @if($showActions)
        <!-- Actions Skeleton -->
        <div class="flex gap-2 mt-4">
            <x-ui.skeleton variant="rect" width="6rem" height="2rem" />
            <x-ui.skeleton variant="rect" width="6rem" height="2rem" />
        </div>
    @endif
</div>

