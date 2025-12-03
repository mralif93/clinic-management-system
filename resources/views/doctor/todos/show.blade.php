@extends('layouts.doctor')

@section('title', 'Task Details')
@section('page-title', 'Task Details')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Task Details</h3>
                <a href="{{ route('doctor.todos.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Back to My Tasks
                </a>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500">Task Title</label>
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
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full border {{ $todo->status_color }}">
                                {{ ucfirst(str_replace('_', ' ', $todo->status)) }}
                            </span>
                        </p>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Priority</label>
                        <p class="mt-1">
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full border {{ $todo->priority_color }}">
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

                    <!-- Created By -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Assigned By</label>
                        <p class="mt-1 text-gray-900">{{ $todo->creator->name }}</p>
                    </div>

                    <!-- Created At -->
                    <div>
                        <label class="text-sm font-medium text-gray-500">Created At</label>
                        <p class="mt-1 text-gray-900">{{ $todo->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                <!-- Update Status -->
                @if($todo->status !== 'completed')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-4">Update Status</h4>
                        <form action="{{ route('doctor.todos.update-status', $todo->id) }}" method="POST"
                            class="flex items-end gap-4">
                            @csrf
                            @method('PUT')
                            <div class="flex-1">
                                <select name="status" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                                    @foreach(\App\Models\Todo::getStatuses() as $key => $label)
                                        <option value="{{ $key }}" {{ $todo->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                                <i class='bx bx-save mr-2'></i>Update Status
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

