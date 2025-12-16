@props([
    'rows' => 5,
    'columns' => 4
])

<div class="w-full">
    <!-- Table Header Skeleton -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="grid gap-4 px-4 py-3" style="grid-template-columns: repeat({{ $columns }}, 1fr);">
            @for($i = 0; $i < $columns; $i++)
                <x-ui.skeleton variant="text" width="60%" height="0.75rem" />
            @endfor
        </div>
    </div>
    
    <!-- Table Rows Skeleton -->
    <div class="divide-y divide-gray-100">
        @for($row = 0; $row < $rows; $row++)
            <div class="grid gap-4 px-4 py-4" style="grid-template-columns: repeat({{ $columns }}, 1fr);">
                @for($col = 0; $col < $columns; $col++)
                    <x-ui.skeleton variant="text" width="{{ $col === 0 ? '80%' : '60%' }}" />
                @endfor
            </div>
        @endfor
    </div>
</div>

