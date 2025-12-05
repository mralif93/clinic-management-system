<div
    class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all {{ $todo->trashed() ? 'opacity-60' : '' }}">
    <div class="p-4">
        <!-- Header -->
        <div class="flex items-start justify-between gap-3 mb-3">
            <h4 class="font-medium text-gray-900 line-clamp-2">{{ $todo->title }}</h4>
            @php
                $priorityStyles = [
                    'low' => 'bg-gray-100 text-gray-600',
                    'medium' => 'bg-blue-100 text-blue-600',
                    'high' => 'bg-orange-100 text-orange-600',
                    'urgent' => 'bg-red-100 text-red-600',
                ];
            @endphp
            <span
                class="flex-shrink-0 px-2 py-0.5 rounded text-xs font-medium {{ $priorityStyles[$todo->priority] ?? $priorityStyles['medium'] }}">
                {{ ucfirst($todo->priority) }}
            </span>
        </div>

        <!-- Description -->
        @if($todo->description)
            <p class="text-sm text-gray-500 line-clamp-2 mb-3">{{ $todo->description }}</p>
        @endif

        <!-- Meta -->
        <div class="flex items-center justify-between text-xs text-gray-500">
            @if($todo->due_date)
                <div class="flex items-center gap-1 {{ $todo->isOverdue() ? 'text-red-600 font-medium' : '' }}">
                    <i class='bx bx-calendar'></i>
                    {{ $todo->due_date->format('M d') }}
                    @if($todo->isOverdue())
                        <i class='bx bx-error-circle'></i>
                    @endif
                </div>
            @else
                <span class="text-gray-400">No due date</span>
            @endif

            @if($todo->assignedUser)
                <div class="flex items-center gap-1">
                    <div
                        class="w-5 h-5 rounded-full bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center text-white text-[10px] font-medium">
                        {{ strtoupper(substr($todo->assignedUser->name, 0, 1)) }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="px-4 py-2 bg-gray-50 border-t border-gray-100 flex justify-end gap-1">
        @if($todo->trashed())
            <button onclick="restoreTodo({{ $todo->id }}, '{{ addslashes($todo->title) }}')"
                class="p-1.5 rounded text-green-600 hover:bg-green-100 transition-colors">
                <i class='bx bx-undo'></i>
            </button>
        @else
            <a href="{{ route('admin.todos.show', $todo->id) }}"
                class="p-1.5 rounded text-gray-500 hover:bg-gray-200 transition-colors">
                <i class='bx bx-show'></i>
            </a>
            <a href="{{ route('admin.todos.edit', $todo->id) }}"
                class="p-1.5 rounded text-gray-500 hover:bg-gray-200 transition-colors">
                <i class='bx bx-edit'></i>
            </a>
        @endif
    </div>
</div>