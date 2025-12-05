@extends('layouts.admin')

@section('title', 'To-Do List')
@section('page-title', 'To-Do List')

@section('content')
    <div class="space-y-6">
        <!-- Header with Create Button -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-1">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-task text-primary-600 text-2xl'></i>
                    To-Do List
                </h2>
                <p class="text-sm text-gray-600">Manage your tasks and to-do items</p>
            </div>
            <a href="{{ route('admin.todos.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 shadow-sm">
                <i class='bx bx-plus text-lg'></i>
                Add New To-Do
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <form method="GET" action="{{ route('admin.todos.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <i class='bx bx-search'></i> Search
                        </label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Search tasks..."
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for "status" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <i class='bx bx-info-circle'></i> Status
                        </label>
                        <select id="status" name="status"
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                            <option value="">All Statuses</option>
                            @foreach(\App\Models\Todo::getStatuses() as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Priority Filter -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <i class='bx bx-flag'></i> Priority
                        </label>
                        <select id="priority" name="priority"
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                            <option value="">All Priorities</option>
                            @foreach(\App\Models\Todo::getPriorities() as $key => $label)
                                <option value="{{ $key }}" {{ request('priority') == $key ? 'selected' : '' }}>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Assigned To Filter -->
                    <div class="md:col-span-2">
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                            <i class='bx bx-user'></i> Assigned To
                        </label>
                        <select id="assigned_to" name="assigned_to"
                            class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Show Trashed -->
                    <div class="flex items-end">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="trashed" value="1" {{ request('trashed') ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-2 focus:ring-primary-600">
                            <span class="ml-2 text-sm text-gray-700">Show deleted</span>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 mt-4">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 shadow-sm">
                        <i class='bx bx-filter-alt'></i>
                        Apply Filters
                    </button>
                    @if(request()->hasAny(['search', 'status', 'priority', 'assigned_to']))
                        <a href="{{ route('admin.todos.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-sm">
                            <i class='bx bx-x'></i>
                            Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- To-Do Table -->
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left tracking-wide">Title</th>
                        <th class="px-6 py-3 text-left tracking-wide">Status</th>
                        <th class="px-6 py-3 text-left tracking-wide">Priority</th>
                        <th class="px-6 py-3 text-left tracking-wide">Due Date</th>
                        <th class="px-6 py-3 text-left tracking-wide">Assigned To</th>
                        <th class="px-6 py-3 text-left tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($todos as $todo)
                        <tr class="hover:bg-gray-50 {{ $todo->trashed() ? 'bg-red-50' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $todo->title }}</div>
                                        @if($todo->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($todo->description, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full border {{ $todo->status_color }}">
                                    {{ ucfirst(str_replace('_', ' ', $todo->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full border {{ $todo->priority_color }}">
                                    {{ ucfirst($todo->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($todo->due_date)
                                    <div class="{{ $todo->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                                        {{ $todo->due_date->format('M d, Y') }}
                                        @if($todo->isOverdue())
                                            <i class='bx bx-error-circle'></i>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">No due date</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $todo->assignedUser ? $todo->assignedUser->name : 'Unassigned' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    @if($todo->trashed())
                                        <button onclick="restoreTodo({{ $todo->id }}, '{{ $todo->title }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-success-600 text-white hover:bg-success-700 focus:ring-2 focus:ring-success-600 focus:ring-offset-2 text-xs">
                                            <i class='bx bx-undo text-base'></i>
                                        </button>
                                        <button onclick="forceDeleteTodo({{ $todo->id }}, '{{ $todo->title }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-red-600 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-600 focus:ring-offset-2 text-xs">
                                            <i class='bx bx-x-circle text-base'></i>
                                        </button>
                                    @else
                                        <a href="{{ route('admin.todos.show', $todo->id) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 text-xs">
                                            <i class='bx bx-info-circle text-base'></i>
                                        </a>
                                        <a href="{{ route('admin.todos.edit', $todo->id) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 text-xs">
                                            <i class='bx bx-pencil text-base'></i>
                                        </a>
                                        <button onclick="deleteTodo({{ $todo->id }}, '{{ $todo->title }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded-full bg-red-600 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-600 focus:ring-offset-2 text-xs">
                                            <i class='bx bx-trash text-base'></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class='bx bx-task text-4xl mb-2'></i>
                                <p>No to-dos found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($todos->hasPages())
            <div class="mt-4">
                {{ $todos->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function deleteTodo(id, title) {
                Swal.fire({
                    title: 'Delete To-Do?',
                    html: `Are you sure you want to delete <strong>${title}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/todos/${id}`;
                        form.innerHTML = `
                                    @csrf
                                    @method('DELETE')
                                `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            function restoreTodo(id, title) {
                Swal.fire({
                    title: 'Restore To-Do?',
                    html: `Are you sure you want to restore <strong>${title}</strong>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Restore',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/todos/${id}/restore`;
                        form.innerHTML = `@csrf`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            function forceDeleteTodo(id, title) {
                Swal.fire({
                    title: 'Permanently Delete?',
                    html: `Are you sure you want to <strong class="text-red-600">permanently delete</strong> <strong>${title}</strong>?<br><br>This action cannot be undone!`,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Delete Permanently',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/todos/${id}/force-delete`;
                        form.innerHTML = `
                                    @csrf
                                    @method('DELETE')
                                `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        </script>
    @endpush
@endsection