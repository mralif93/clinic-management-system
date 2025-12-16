@props([
    'items' => []
])

@if(count($items) > 0)
    <nav aria-label="Breadcrumb" class="mb-4">
        <ol class="flex items-center space-x-2 text-sm">
            @foreach($items as $index => $item)
                <li class="flex items-center">
                    @if($index > 0)
                        <i class='bx bx-chevron-right text-gray-400 mx-2'></i>
                    @endif
                    
                    @if(isset($item['url']) && $index < count($items) - 1)
                        <a href="{{ $item['url'] }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                            {{ $item['label'] }}
                        </a>
                    @else
                        <span class="text-gray-900 font-medium" aria-current="page">
                            {{ $item['label'] }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif

