@props([
    'sortable' => false,
    'sortKey' => null,
    'currentSort' => null,
    'currentDirection' => 'asc',
    'class' => ''
])

@php
    $headerClasses = 'px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 bg-gray-50 ' . $class;
    
    if ($sortable) {
        $headerClasses .= ' cursor-pointer select-none hover:bg-gray-100 transition-colors';
    }
    
    $sortIcon = '';
    if ($sortable && $currentSort === $sortKey) {
        $sortIcon = $currentDirection === 'asc' 
            ? '<i class="hgi-stroke hgi-sorting-01 ml-1"></i>' 
            : '<i class="hgi-stroke hgi-sorting-01 ml-1"></i>';
    } elseif ($sortable) {
        $sortIcon = '<i class="hgi-stroke hgi-sorting-05 ml-1 text-gray-400"></i>';
    }
@endphp

<th 
    class="{{ $headerClasses }}"
    @if($sortable && $sortKey)
        onclick="window.sortTable('{{ $sortKey }}')"
        role="button"
        tabindex="0"
        aria-label="Sort by {{ $slot }}"
    @endif
    {{ $attributes }}
>
    <div class="flex items-center">
        {{ $slot }}
        {!! $sortIcon !!}
    </div>
</th>

