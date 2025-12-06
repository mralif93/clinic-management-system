@extends('layouts.doctor')

@section('title', 'Task Details')
@section('page-title', 'Task Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative">
                <a href="{{ route('doctor.todos.index') }}" class="inline-flex items-center gap-1 text-emerald-100 hover:text-white text-sm mb-2 transition">
                    <i class='bx bx-arrow-back'></i> Back to My Tasks
                </a>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-task text-xl'></i>
                    </div>
                    Task Details
                </h1>
            </div>
        </div>

        <!-- Task Details Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-detail text-emerald-500'></i> Task Information
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2 block">Task Title</label>
                        <p class="text-lg font-bold text-gray-900">{{ $todo->title }}</p>
                    </div>

                    <!-- Description -->
                    @if($todo->description)
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2 block">Description</label>
                            <p class="text-gray-700 bg-gray-50 p-4 rounded-xl leading-relaxed">{{ $todo->description }}</p>
                        </div>
                    @endif

                    <!-- Status -->
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2 block">Status</label>
                        @if($todo->status === 'completed')
                            <span class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-emerald-100 text-emerald-700">Completed</span>
                        @elseif($todo->status === 'in_progress')
                            <span class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-blue-100 text-blue-700">In Progress</span>
                        @else
                            <span class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-amber-100 text-amber-700">Pending</span>
                        @endif
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2 block">Priority</label>
                        @if($todo->priority === 'high')
                            <span class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-red-100 text-red-700">High</span>
                        @elseif($todo->priority === 'medium')
                            <span class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-amber-100 text-amber-700">Medium</span>
                        @else
                            <span class="px-3 py-1.5 rounded-lg text-sm font-semibold bg-gray-100 text-gray-700">Low</span>
                        @endif
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2 block">Due Date</label>
                        @if($todo->due_date)
                            <div class="flex items-center gap-2 {{ $todo->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                                <div class="w-8 h-8 rounded-lg {{ $todo->isOverdue() ? 'bg-red-100' : 'bg-emerald-100' }} flex items-center justify-center">
                                    <i class='bx {{ $todo->isOverdue() ? 'bx-error-circle text-red-600' : 'bx-calendar text-emerald-600' }}'></i>
                                </div>
                                <span class="font-medium">{{ $todo->due_date->format('M d, Y') }}</span>
                                @if($todo->isOverdue())
                                    <span class="text-sm font-semibold">(Overdue)</span>
                                @endif
                            </div>
                        @else
                            <span class="text-gray-400">No due date</span>
                        @endif
                    </div>

                    <!-- Created By -->
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2 block">Assigned By</label>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                <i class='bx bx-user text-gray-600'></i>
                            </div>
                            <span class="font-medium text-gray-900">{{ $todo->creator->name }}</span>
                        </div>
                    </div>

                    <!-- Created At -->
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-2 block">Created At</label>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center">
                                <i class='bx bx-time text-gray-600'></i>
                            </div>
                            <span class="font-medium text-gray-900">{{ $todo->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Update Status -->
                @if($todo->status !== 'completed')
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h4 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <i class='bx bx-edit text-emerald-500'></i> Update Status
                        </h4>
                        <form action="{{ route('doctor.todos.update-status', $todo->id) }}" method="POST"
                            class="flex flex-col sm:flex-row items-stretch sm:items-end gap-4">
                            @csrf
                            @method('PUT')
                            <div class="flex-1">
                                <select name="status" required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                                    @foreach(\App\Models\Todo::getStatuses() as $key => $label)
                                        <option value="{{ $key }}" {{ $todo->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                class="inline-flex items-center justify-center px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition shadow-sm">
                                <i class='bx bx-save mr-2'></i> Update Status
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

