@props([
    'items' => []
])

<nav class="bottom-nav mobile-only" aria-label="Bottom navigation">
    @foreach($items as $item)
        <a 
            href="{{ $item['url'] ?? '#' }}"
            class="bottom-nav-item {{ request()->url() === ($item['url'] ?? '#') ? 'active' : '' }}"
            aria-label="{{ $item['label'] ?? '' }}"
        >
            <i class='{{ $item['icon'] ?? 'bx-link' }}'></i>
            <span>{{ $item['label'] ?? '' }}</span>
        </a>
    @endforeach
</nav>

