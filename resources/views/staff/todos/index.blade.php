@extends('layouts.staff')

@section('title', 'My Tasks')
@section('page-title', 'My Tasks')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">My Daily Tasks</h2>
                <p class="text-sm text-gray-600 mt-1">View and manage your assigned tasks</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <form method="GET" action="{{ route('staff.todos.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Status Filter -->
                <div>
                    <select name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                        <option value="">All Statuses</option>
                        @foreach(\App\Models\Todo::getStatuses() as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Priority Filter -->
                <div>
                    <select name="priority"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
                        <option value="">All Priorities</option>
                        @foreach(\App\Models\Todo::getPriorities() as $key => $label)
                            <option value="{{ $key }}" {{ request('priority') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        <i class='bx bx-filter-alt'></i> Filter
                    </button>
                    <a href="{{ route('staff.todos.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <i class='bx bx-reset'></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Tasks Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($todos as $todo)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $todo->title }}</div>
                                    @if($todo->description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($todo->description, 50) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 inline-flex text-xs font-semibold rounded-full border {{ $todo->status_color }}">
                                    {{ ucfirst(str_replace('_', ' ', $todo->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 inline-flex text-xs font-semibold rounded-full border {{ $todo->priority_color }}">
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
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('staff.todos.show', $todo->id) }}"
                                        class="w-8 h-8 rounded-full inline-flex items-center justify-center bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition">
                                        <i class='bx bx-show text-base'></i>
                                    </a>
                                    @if($todo->status !== 'completed')
                                        <button onclick="updateStatus({{ $todo->id }}, '{{ $todo->title }}')"
                                            class="w-8 h-8 rounded-full inline-flex items-center justify-center bg-green-100 text-green-600 hover:bg-green-200 transition">
                                            <i class='bx bx-check text-base'></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class='bx bx-task text-4xl mb-2'></i>
                                <p>No tasks assigned to you</p>
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
            function updateStatus(id, title) {
                Swal.fire({
                    title: 'Update Task Status',
                    html: `Update status for <strong>${title}</strong>`,
                    input: 'select',
                    inputOptions: {
                        'pending': 'Pending',
                        'in_progress': 'In Progress',
                        'completed': 'Completed'
                    },
                    inputPlaceholder: 'Select status',
                    showCancelButton: true,
                    confirmButtonColor: '#eab308',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Update',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/staff/todos/${id}/status`;
                        form.innerHTML = `
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="${result.value}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        </script>
    @endpush
@endsection