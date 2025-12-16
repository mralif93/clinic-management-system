@extends('layouts.staff')

@section('title', 'My Tasks')
@section('page-title', 'My Tasks')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-teal-500 via-teal-600 to-cyan-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class='bx bx-task text-3xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">My Daily Tasks</h1>
                        <p class="text-teal-100 text-sm mt-1">View and manage your assigned tasks</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-sm">
                        <i class='bx bx-calendar mr-1'></i>
                        {{ now()->format('l, M d') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        @php
            $pendingCount = $todos->where('status', 'pending')->count();
            $inProgressCount = $todos->where('status', 'in_progress')->count();
            $completedCount = $todos->where('status', 'completed')->count();
            $overdueCount = $todos->filter(fn($t) => $t->isOverdue())->count();
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-list-check text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $todos->total() }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-time text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $pendingCount }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Pending</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-loader-circle text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $inProgressCount }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">In Progress</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-check-circle text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $completedCount }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Completed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="bg-gradient-to-r from-teal-50 via-cyan-50 to-teal-50 rounded-2xl border border-teal-100/50 p-5">
            <form method="GET" action="{{ route('staff.todos.index') }}">
                <!-- Filter Options -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm font-medium text-teal-700 flex items-center gap-1.5">
                        <i class='bx bx-filter-alt'></i> Filters:
                    </span>

                    <select name="status"
                        class="px-3 py-2 bg-white border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 text-sm text-gray-700 min-w-[140px]">
                        <option value="">All Statuses</option>
                        @foreach(\App\Models\Todo::getStatuses() as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>

                    <select name="priority"
                        class="px-3 py-2 bg-white border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-teal-500 text-sm text-gray-700 min-w-[140px]">
                        <option value="">All Priorities</option>
                        @foreach(\App\Models\Todo::getPriorities() as $key => $label)
                            <option value="{{ $key }}" {{ request('priority') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>

                    <div class="flex items-center gap-2 ml-auto">
                        @if(request()->hasAny(['status', 'priority']))
                            <a href="{{ route('staff.todos.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white text-gray-600 text-sm font-medium rounded-lg shadow-sm hover:bg-gray-50 transition">
                                <i class='bx bx-x mr-1'></i> Clear
                            </a>
                        @endif
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-teal-500 to-cyan-500 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-teal-600 hover:to-cyan-600 transition-all">
                            <i class='bx bx-filter-alt mr-1.5'></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tasks Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class='bx bx-list-ul text-teal-500'></i>
                        Tasks List
                    </h3>
                    @if($overdueCount > 0)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                            <i class='bx bx-error-circle'></i>
                            {{ $overdueCount }} Overdue
                        </span>
                    @endif
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Task</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($todos as $todo)
                            <tr class="hover:bg-teal-50/30 transition-colors duration-150 {{ $todo->isOverdue() ? 'bg-red-50/50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-teal-400 to-cyan-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class='bx bx-task text-white text-sm'></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $todo->title }}</div>
                                            @if($todo->description)
                                                <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($todo->description, 60) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'icon' => 'bx-time'],
                                            'in_progress' => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-700', 'icon' => 'bx-loader-circle'],
                                            'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'bx-check-circle'],
                                        ];
                                        $sConfig = $statusConfig[$todo->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-help-circle'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $sConfig['bg'] }} {{ $sConfig['text'] }}">
                                        <i class='bx {{ $sConfig['icon'] }}'></i>
                                        {{ ucfirst(str_replace('_', ' ', $todo->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $priorityConfig = [
                                            'low' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-down-arrow-alt'],
                                            'medium' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'bx-minus'],
                                            'high' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'icon' => 'bx-up-arrow-alt'],
                                            'urgent' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => 'bx-error'],
                                        ];
                                        $pConfig = $priorityConfig[$todo->priority] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-minus'];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $pConfig['bg'] }} {{ $pConfig['text'] }}">
                                        <i class='bx {{ $pConfig['icon'] }}'></i>
                                        {{ ucfirst($todo->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($todo->due_date)
                                        <div class="{{ $todo->isOverdue() ? 'text-red-600' : 'text-gray-700' }}">
                                            <div class="text-sm font-medium flex items-center gap-1">
                                                @if($todo->isOverdue())
                                                    <i class='bx bx-error-circle text-red-500'></i>
                                                @endif
                                                {{ $todo->due_date->format('M d, Y') }}
                                            </div>
                                            <div class="text-xs {{ $todo->isOverdue() ? 'text-red-500' : 'text-gray-500' }}">
                                                {{ $todo->due_date->diffForHumans() }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">No due date</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-1.5">
                                        <a href="{{ route('staff.todos.show', $todo->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-blue-500 text-white hover:bg-blue-600 rounded-full transition shadow-sm hover:shadow" title="View">
                                            <i class='bx bx-show text-sm'></i>
                                        </a>
                                        @if($todo->status !== 'completed')
                                            <button onclick="updateStatus({{ $todo->id }}, '{{ addslashes($todo->title) }}')"
                                                class="w-8 h-8 flex items-center justify-center bg-green-500 text-white hover:bg-green-600 rounded-full transition shadow-sm hover:shadow" title="Update Status">
                                                <i class='bx bx-check text-sm'></i>
                                            </button>
                                        @else
                                            <span class="w-8 h-8 flex items-center justify-center bg-gray-200 text-gray-400 rounded-full">
                                                <i class='bx bx-check-double text-sm'></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class='bx bx-task text-3xl text-gray-400'></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">No tasks assigned to you</p>
                                        <p class="text-gray-400 text-sm mt-1">Tasks will appear here when assigned</p>
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
        function updateStatus(id, title) {
            Swal.fire({
                title: 'Update Task Status',
                html: `<p class="text-gray-600 mb-2">Change status for:</p><p class="font-semibold text-gray-800">${title}</p>`,
                input: 'select',
                inputOptions: {
                    'pending': '⏳ Pending',
                    'in_progress': '🔄 In Progress',
                    'completed': '✅ Completed'
                },
                inputPlaceholder: 'Select new status',
                showCancelButton: true,
                confirmButtonColor: '#14b8a6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="bx bx-check mr-1"></i> Update',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl',
                    cancelButton: 'rounded-xl',
                    input: 'rounded-xl'
                }
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