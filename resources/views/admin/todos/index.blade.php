@extends('layouts.admin')

@section('title', 'To-Do List')
@section('page-title', 'To-Do List')

@section('content')
<div class="space-y-6">
    <!-- Page Header with Stats -->
    <div class="bg-gradient-to-r from-pink-600 to-rose-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-task text-2xl'></i>
                    </div>
                    To-Do List
                </h1>
                <p class="mt-2 text-pink-100">Manage your tasks and to-do items</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.todos.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-pink-600 rounded-xl font-semibold hover:bg-pink-50 transition-all shadow-lg shadow-pink-900/20">
                    <i class='bx bx-plus text-xl'></i>
                    Add New Task
                </a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            @php
                $totalTodos = $todos->total();
                $pendingCount = \App\Models\Todo::where('status', 'pending')->count();
                $inProgressCount = \App\Models\Todo::where('status', 'in_progress')->count();
                $completedCount = \App\Models\Todo::where('status', 'completed')->count();
            @endphp
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $totalTodos }}</p>
                <p class="text-sm text-pink-200">Total Tasks</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $pendingCount }}</p>
                <p class="text-sm text-pink-200">Pending</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $inProgressCount }}</p>
                <p class="text-sm text-pink-200">In Progress</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-xl p-4">
                <p class="text-2xl font-bold">{{ $completedCount }}</p>
                <p class="text-sm text-pink-200">Completed</p>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center gap-2">
                <i class='bx bx-filter-alt text-gray-500'></i>
                <h3 class="font-semibold text-gray-700">Filter Tasks</h3>
            </div>
        </div>
        <div class="p-5">
            <form method="GET" action="{{ route('admin.todos.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-600 mb-2">Search</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class='bx bx-search'></i>
                            </span>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Search tasks..."
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 transition-all text-sm">
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <select id="status" name="status" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 transition-all text-sm bg-white">
                            <option value="">All Statuses</option>
                            @foreach(\App\Models\Todo::getStatuses() as $key => $label)
                                <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Priority Filter -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-600 mb-2">Priority</label>
                        <select id="priority" name="priority" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 transition-all text-sm bg-white">
                            <option value="">All Priorities</option>
                            @foreach(\App\Models\Todo::getPriorities() as $key => $label)
                                <option value="{{ $key }}" {{ request('priority') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Assigned To -->
                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-600 mb-2">Assigned To</label>
                        <select id="assigned_to" name="assigned_to" 
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-500/20 transition-all text-sm bg-white">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-pink-600 text-white rounded-xl font-medium hover:bg-pink-700 transition-all text-sm">
                        <i class='bx bx-search'></i>
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'status', 'priority', 'assigned_to']))
                        <a href="{{ route('admin.todos.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                            <i class='bx bx-x'></i>
                            Clear
                        </a>
                    @endif

                    <!-- Show Deleted Toggle -->
                    <label class="inline-flex items-center gap-2 ml-auto cursor-pointer">
                        <input type="checkbox" name="trashed" value="1" {{ request('trashed') ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300 text-pink-600 focus:ring-pink-500"
                            onchange="this.form.submit()">
                        <span class="text-sm text-gray-600">Show deleted</span>
                    </label>
                </div>
            </form>
        </div>
    </div>

    <!-- Tasks Board View -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pending Column -->
        <div class="space-y-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                <h3 class="font-semibold text-gray-700">Pending</h3>
                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">{{ $todos->where('status', 'pending')->count() }}</span>
            </div>
            @foreach($todos->where('status', 'pending') as $todo)
                @include('admin.todos._task_card', ['todo' => $todo])
            @endforeach
        </div>

        <!-- In Progress Column -->
        <div class="space-y-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                <h3 class="font-semibold text-gray-700">In Progress</h3>
                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">{{ $todos->where('status', 'in_progress')->count() }}</span>
            </div>
            @foreach($todos->where('status', 'in_progress') as $todo)
                @include('admin.todos._task_card', ['todo' => $todo])
            @endforeach
        </div>

        <!-- Completed Column -->
        <div class="space-y-4">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                <h3 class="font-semibold text-gray-700">Completed</h3>
                <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">{{ $todos->where('status', 'completed')->count() }}</span>
            </div>
            @foreach($todos->where('status', 'completed') as $todo)
                @include('admin.todos._task_card', ['todo' => $todo])
            @endforeach
        </div>
    </div>

    <!-- Alternative List View for All Tasks -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-semibold text-gray-700 flex items-center gap-2">
                <i class='bx bx-list-ul'></i>
                All Tasks
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Task</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assigned To</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($todos as $todo)
                        <tr class="hover:bg-gray-50/50 transition-colors {{ $todo->trashed() ? 'bg-red-50/30' : '' }}">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $todo->title }}</div>
                                @if($todo->description)
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-1">{{ $todo->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusStyles = [
                                        'pending' => 'bg-amber-50 text-amber-700 ring-amber-500/20',
                                        'in_progress' => 'bg-blue-50 text-blue-700 ring-blue-500/20',
                                        'completed' => 'bg-green-50 text-green-700 ring-green-500/20',
                                        'cancelled' => 'bg-gray-50 text-gray-700 ring-gray-500/20',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold ring-1 ring-inset {{ $statusStyles[$todo->status] ?? $statusStyles['pending'] }}">
                                    {{ ucwords(str_replace('_', ' ', $todo->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $priorityStyles = [
                                        'low' => 'bg-gray-50 text-gray-600',
                                        'medium' => 'bg-blue-50 text-blue-600',
                                        'high' => 'bg-orange-50 text-orange-600',
                                        'urgent' => 'bg-red-50 text-red-600',
                                    ];
                                    $priorityIcons = [
                                        'low' => 'bx-chevrons-down',
                                        'medium' => 'bx-minus',
                                        'high' => 'bx-chevrons-up',
                                        'urgent' => 'bx-error',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $priorityStyles[$todo->priority] ?? $priorityStyles['medium'] }}">
                                    <i class='bx {{ $priorityIcons[$todo->priority] ?? 'bx-minus' }}'></i>
                                    {{ ucfirst($todo->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($todo->due_date)
                                    <div class="{{ $todo->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                        {{ $todo->due_date->format('M d, Y') }}
                                        @if($todo->isOverdue())
                                            <i class='bx bx-error-circle ml-1'></i>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">No due date</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($todo->assignedUser)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center text-white text-xs font-medium">
                                            {{ strtoupper(substr($todo->assignedUser->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm text-gray-900">{{ $todo->assignedUser->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end items-center gap-2">
                                    @if($todo->trashed())
                                        <button onclick="restoreTodo({{ $todo->id }}, '{{ addslashes($todo->title) }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-green-100 text-green-600 hover:bg-green-200 hover:scale-110 transition-all" title="Restore">
                                            <i class='bx bx-undo text-lg'></i>
                                        </button>
                                        <button onclick="forceDeleteTodo({{ $todo->id }}, '{{ addslashes($todo->title) }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete Permanently">
                                            <i class='bx bx-x-circle text-lg'></i>
                                        </button>
                                    @else
                                        <a href="{{ route('admin.todos.show', $todo->id) }}"
                                           class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:scale-110 transition-all" title="View">
                                            <i class='bx bx-show text-lg'></i>
                                        </a>
                                        <a href="{{ route('admin.todos.edit', $todo->id) }}"
                                           class="w-9 h-9 flex items-center justify-center rounded-full bg-amber-100 text-amber-600 hover:bg-amber-200 hover:scale-110 transition-all" title="Edit">
                                            <i class='bx bx-edit text-lg'></i>
                                        </a>
                                        <button onclick="deleteTodo({{ $todo->id }}, '{{ addslashes($todo->title) }}')"
                                            class="w-9 h-9 flex items-center justify-center rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:scale-110 transition-all" title="Delete">
                                            <i class='bx bx-trash text-lg'></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <i class='bx bx-task text-4xl text-gray-400'></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No tasks found</p>
                                    <p class="text-gray-400 text-sm mt-1">Create your first task to get started</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($todos->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $todos->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function deleteTodo(id, title) {
        Swal.fire({
            title: 'Delete Task?',
            html: `Are you sure you want to delete <strong>${title}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bx bx-trash mr-1"></i> Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/todos/${id}`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function restoreTodo(id, title) {
        Swal.fire({
            title: 'Restore Task?',
            html: `Are you sure you want to restore <strong>${title}</strong>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bx bx-undo mr-1"></i> Restore',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Restoring...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
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
            html: `<div class="text-left">
                <p>Are you sure you want to <strong class="text-red-600">permanently delete</strong> <strong>${title}</strong>?</p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                    <p class="text-sm text-red-700"><i class='bx bx-error-circle mr-1'></i> This cannot be undone!</p>
                </div>
            </div>`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Delete Permanently',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Deleting...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/todos/${id}/force-delete`;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection