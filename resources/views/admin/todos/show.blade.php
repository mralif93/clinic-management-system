@extends('layouts.admin')

@section('title', 'To-Do Details')
@section('page-title', 'To-Do Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center border-2 border-white/30">
                        <i class='bx bx-task text-4xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ Str::limit($todo->title, 50) }}</h1>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $todo->status_color }} bg-white/20 backdrop-blur">
                                {{ ucfirst(str_replace('_', ' ', $todo->status)) }}
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $todo->priority_color }} bg-white/20 backdrop-blur">
                                {{ ucfirst($todo->priority) }} Priority
                            </span>
                            @if($todo->is_recurring)
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-indigo-400/30">
                                    <i class='bx bx-repeat mr-1'></i> {{ ucfirst($todo->recurrence_type) }}
                                </span>
                            @endif
                            @if($todo->isOverdue())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-red-500/30">
                                    <i class='bx bx-error-circle mr-1'></i> Overdue
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.todos.edit', $todo->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-amber-600 rounded-xl font-semibold hover:bg-amber-50 transition-all shadow-lg">
                        <i class='bx bx-edit'></i>
                        Edit To-Do
                    </a>
                    <a href="{{ route('admin.todos.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-arrow-back'></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                        <i class='bx bx-flag text-2xl text-amber-600'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium mt-1 {{ $todo->status_color }}">
                            {{ ucfirst(str_replace('_', ' ', $todo->status)) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <i class='bx bx-trophy text-2xl text-blue-600'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Priority</p>
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium mt-1 {{ $todo->priority_color }}">
                            {{ ucfirst($todo->priority) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-xl {{ $todo->isOverdue() ? 'bg-red-50' : 'bg-green-50' }} flex items-center justify-center">
                        <i class='bx bx-calendar text-2xl {{ $todo->isOverdue() ? 'text-red-600' : 'text-green-600' }}'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Due Date</p>
                        @if($todo->due_date)
                            <p class="text-lg font-bold {{ $todo->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $todo->due_date->format('M d') }}
                            </p>
                        @else
                            <p class="text-lg font-bold text-gray-400">No date</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                        <i class='bx bx-user text-2xl text-purple-600'></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Assigned To</p>
                        <p class="text-lg font-bold text-gray-900">
                            {{ $todo->assignedUser ? Str::limit($todo->assignedUser->name, 12) : 'Unassigned' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Task Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-info-circle text-amber-600'></i>
                        Task Details
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500 block mb-2">Title</span>
                            <p class="text-lg font-semibold text-gray-900">{{ $todo->title }}</p>
                        </div>
                        @if($todo->description)
                            <div class="py-3 border-b border-gray-100">
                                <span class="text-sm text-gray-500 block mb-2">Description</span>
                                <p class="text-sm text-gray-900 bg-gray-50 rounded-xl p-4 whitespace-pre-wrap">
                                    {{ $todo->description }}</p>
                            </div>
                        @endif
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm text-gray-500">Created By</span>
                            <span class="text-sm font-medium text-gray-900">{{ $todo->creator->name }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-sm text-gray-500">Created At</span>
                            <span
                                class="text-sm font-medium text-gray-900">{{ $todo->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recurring Info / Additional Details -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-repeat text-amber-600'></i>
                        Recurring Settings
                    </h3>
                </div>
                <div class="p-6">
                    @if($todo->is_recurring)
                        <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-xl">
                            <div class="flex items-center gap-3 mb-3">
                                <i class='bx bx-repeat text-2xl text-indigo-600'></i>
                                <span class="text-lg font-semibold text-indigo-700">{{ ucfirst($todo->recurrence_type) }}
                                    Recurrence</span>
                            </div>
                            @if($todo->last_generated_date)
                                <p class="text-sm text-indigo-600 mb-2">
                                    <i class='bx bx-calendar-check mr-1'></i>
                                    Last generated: {{ $todo->last_generated_date->format('M d, Y') }}
                                </p>
                            @endif
                            <p class="text-xs text-indigo-700 mt-3 flex items-center gap-1">
                                <i class='bx bx-info-circle'></i>
                                This is a template task. New instances are automatically created {{ $todo->recurrence_type }}.
                            </p>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                                <i class='bx bx-calendar-minus text-3xl text-gray-400'></i>
                            </div>
                            <p class="text-gray-500">This is not a recurring task</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="bg-white rounded-2xl shadow-sm border-2 border-red-200 overflow-hidden">
            <div class="p-6 border-b border-red-100 bg-red-50/50">
                <h3 class="text-lg font-semibold text-red-900 flex items-center gap-2">
                    <i class='bx bx-error-circle text-red-600'></i>
                    Danger Zone
                </h3>
            </div>
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Delete this task</p>
                        <p class="text-sm text-gray-500">This will permanently delete the task.</p>
                    </div>
                    <form action="{{ route('admin.todos.destroy', $todo->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this task?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all text-sm shadow-lg shadow-red-600/20">
                            <i class='bx bx-trash'></i>
                            Delete Task
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection