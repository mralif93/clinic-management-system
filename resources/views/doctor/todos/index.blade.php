@extends('layouts.doctor')

@section('title', 'My Tasks')
@section('page-title', 'My Tasks')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative">
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-task text-xl'></i>
                    </div>
                    My Daily Tasks
                </h1>
                <p class="text-emerald-100 mt-2">View and manage your assigned tasks</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <form method="GET" action="{{ route('doctor.todos.index') }}" class="flex flex-col md:flex-row gap-4">
                <!-- Status Filter -->
                <select name="status"
                    class="px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    <option value="">All Statuses</option>
                    @foreach(\App\Models\Todo::getStatuses() as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                <!-- Priority Filter -->
                <select name="priority"
                    class="px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    <option value="">All Priorities</option>
                    @foreach(\App\Models\Todo::getPriorities() as $key => $label)
                        <option value="{{ $key }}" {{ request('priority') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition shadow-sm">
                        <i class='bx bx-filter-alt mr-2'></i> Filter
                    </button>
                    <a href="{{ route('doctor.todos.index') }}"
                        class="inline-flex items-center px-4 py-2.5 border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition">
                        <i class='bx bx-reset'></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Tasks Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Task</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Priority</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($todos as $todo)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class='bx bx-task text-emerald-600'></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $todo->title }}</div>
                                            @if($todo->description)
                                                <div class="text-sm text-gray-500 mt-0.5">{{ Str::limit($todo->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($todo->status === 'completed')
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700">Completed</span>
                                    @elseif($todo->status === 'in_progress')
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700">In Progress</span>
                                    @else
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($todo->priority === 'high')
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-100 text-red-700">High</span>
                                    @elseif($todo->priority === 'medium')
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700">Medium</span>
                                    @else
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700">Low</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($todo->due_date)
                                        <div class="flex items-center gap-2 {{ $todo->isOverdue() ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                            <div class="w-7 h-7 rounded-lg {{ $todo->isOverdue() ? 'bg-red-100' : 'bg-gray-100' }} flex items-center justify-center">
                                                <i class='bx {{ $todo->isOverdue() ? 'bx-error-circle text-red-600' : 'bx-calendar text-gray-600' }} text-sm'></i>
                                            </div>
                                            {{ $todo->due_date->format('M d, Y') }}
                                        </div>
                                    @else
                                        <span class="text-gray-400">No due date</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('doctor.todos.show', $todo->id) }}"
                                            class="w-8 h-8 rounded-lg inline-flex items-center justify-center bg-emerald-100 text-emerald-600 hover:bg-emerald-200 transition">
                                            <i class='bx bx-show text-base'></i>
                                        </a>
                                        @if($todo->status !== 'completed')
                                            <button onclick="updateStatus({{ $todo->id }}, '{{ $todo->title }}')"
                                                class="w-8 h-8 rounded-lg inline-flex items-center justify-center bg-blue-100 text-blue-600 hover:bg-blue-200 transition">
                                                <i class='bx bx-check text-base'></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                        <i class='bx bx-task text-3xl text-gray-400'></i>
                                    </div>
                                    <p class="text-gray-500 font-medium">No tasks assigned to you</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Update',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/doctor/todos/${id}/status`;
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

