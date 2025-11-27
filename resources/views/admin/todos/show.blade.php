@extends('layouts.admin')

@section('title', 'To-Do Details')
@section('page-title', 'To-Do Details')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">To-Do Details</h3>
                <div class="flex gap-2">
                    <a href="{{ route('admin.todos.edit', $todo->id) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class='bx bx-edit mr-2'></i>Edit
                    </a>
                    <a href="{{ route('admin.todos.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Back to List
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">Title</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ $todo->title }}</p>
                    </div>

                    <!-- Description -->
                    @if($todo->description)
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Description</label>
                            <p class="mt-1 text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $todo->description }}</p>
                        </div>
                    @endif

                    <!-- Status -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Status</label>
                        <p class="mt-1">
                            <span
                                class="px-3 py-1 inline-flex text-sm font-semibold rounded-full border {{ $todo->status_color }}">
                                {{ ucfirst(str_replace('_', ' ', $todo->status)) }}
                            </span>
                        </p>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Priority</label>
                        <p class="mt-1">
                            <span
                                class="px-3 py-1 inline-flex text-sm font-semibold rounded-full border {{ $todo->priority_color }}">
                                {{ ucfirst($todo->priority) }}
                            </span>
                        </p>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Due Date</label>
                        <p class="mt-1 text-gray-900">
                            @if($todo->due_date)
                                <span class="{{ $todo->isOverdue() ? 'text-red-600 font-semibold' : '' }}">
                                    {{ $todo->due_date->format('M d, Y') }}
                                    @if($todo->isOverdue())
                                        <i class='bx bx-error-circle'></i> Overdue
                                    @endif
                                </span>
                            @else
                                <span class="text-gray-400">No due date</span>
                            @endif
                        </p>
                    </div>

                    <!-- Assigned To -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Assigned To</label>
                        <p class="mt-1 text-gray-900">{{ $todo->assignedUser ? $todo->assignedUser->name : 'Unassigned' }}
                        </p>
                    </div>

                    <!-- Created By -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Created By</label>
                        <p class="mt-1 text-gray-900">{{ $todo->creator->name }}</p>
                    </div>

                    <!-- Created At -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Created At</label>
                        <p class="mt-1 text-gray-900">{{ $todo->created_at->format('M d, Y h:i A') }}</p>
                    </div>

                    <!-- Recurring Task Info -->
                    @if($todo->is_recurring)
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Recurring Task</label>
                            <div class="mt-1 flex items-center gap-3">
                                <span
                                    class="px-3 py-1 inline-flex text-sm font-semibold rounded-full border bg-indigo-100 text-indigo-700 border-indigo-300">
                                    <i class='bx bx-repeat mr-1'></i> {{ ucfirst($todo->recurrence_type) }} Recurrence
                                </span>
                                @if($todo->last_generated_date)
                                    <span class="text-sm text-gray-600">
                                        Last generated: {{ $todo->last_generated_date->format('M d, Y') }}
                                    </span>
                                @endif
                            </div>
                            <p class="mt-2 text-xs text-indigo-700">
                                <i class='bx bx-info-circle'></i> This is a template task. New instances are automatically
                                created {{ $todo->recurrence_type }}.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection