@extends('layouts.staff')

@section('title', 'Task Details')
@section('page-title', 'Task Details')

@php
    $priorityColors = [
        'low' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'bx-down-arrow-alt'],
        'medium' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'icon' => 'bx-minus'],
        'high' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => 'bx-up-arrow-alt'],
    ];
    $statusColors = [
        'pending' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-time'],
        'in_progress' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'bx-loader-circle'],
        'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'bx-check-circle'],
    ];
    $currentPriority = $priorityColors[$todo->priority] ?? $priorityColors['medium'];
    $currentStatus = $statusColors[$todo->status] ?? $statusColors['pending'];
@endphp

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-teal-500 via-cyan-500 to-blue-500 rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('staff.todos.index') }}"
                            class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-white/30 transition">
                            <i class='bx bx-arrow-back text-white text-xl'></i>
                        </a>
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">Task Details</h1>
                            <p class="text-teal-100 text-sm mt-1">View and manage your task</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1.5 {{ $currentPriority['bg'] }} {{ $currentPriority['text'] }} rounded-full text-sm font-semibold flex items-center gap-1">
                            <i class='bx {{ $currentPriority['icon'] }}'></i>
                            {{ ucfirst($todo->priority) }} Priority
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ $currentStatus['bg'] }} rounded-lg flex items-center justify-center">
                        <i class='bx {{ $currentStatus['icon'] }} {{ $currentStatus['text'] }} text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold {{ $currentStatus['text'] }}">{{ ucfirst(str_replace('_', ' ', $todo->status)) }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Status</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ $currentPriority['bg'] }} rounded-lg flex items-center justify-center">
                        <i class='bx {{ $currentPriority['icon'] }} {{ $currentPriority['text'] }} text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold {{ $currentPriority['text'] }}">{{ ucfirst($todo->priority) }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Priority</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 {{ $todo->due_date && $todo->isOverdue() ? 'bg-red-100' : 'bg-gradient-to-br from-teal-500 to-cyan-600' }} rounded-lg flex items-center justify-center">
                        <i class='bx bx-calendar {{ $todo->due_date && $todo->isOverdue() ? 'text-red-600' : 'text-white' }} text-xl'></i>
                    </div>
                    <div>
                        @if($todo->due_date)
                            <p class="text-sm font-semibold {{ $todo->isOverdue() ? 'text-red-600' : 'text-gray-900' }}">{{ $todo->due_date->format('M d, Y') }}</p>
                            @if($todo->isOverdue())
                                <p class="text-xs text-red-500 uppercase tracking-wide">Overdue!</p>
                            @else
                                <p class="text-xs text-gray-500 uppercase tracking-wide">Due Date</p>
                            @endif
                        @else
                            <p class="text-sm font-semibold text-gray-400">No due date</p>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">Due Date</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-user text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $todo->creator->name ?? 'System' }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Assigned By</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Task Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-task text-teal-500'></i>
                            Task Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ $todo->title }}</h2>
                        @if($todo->description)
                            <div class="bg-gray-50 p-4 rounded-xl">
                                <p class="text-sm text-gray-500 uppercase tracking-wide mb-2">Description</p>
                                <p class="text-gray-700">{{ $todo->description }}</p>
                            </div>
                        @else
                            <div class="bg-gray-50 p-4 rounded-xl text-center">
                                <i class='bx bx-info-circle text-gray-400 text-2xl'></i>
                                <p class="text-gray-400 text-sm mt-1">No description provided</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Update Status -->
                @if($todo->status !== 'completed')
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-refresh text-teal-500'></i>
                            Update Status
                        </h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('staff.todos.update-status', $todo->id) }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-end gap-4">
                            @csrf
                            @method('PUT')
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Change Status</label>
                                <div class="relative">
                                    <i class='bx bx-loader-circle absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                                    <select name="status" required
                                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 appearance-none bg-white">
                                        @foreach(\App\Models\Todo::getStatuses() as $key => $label)
                                            <option value="{{ $key }}" {{ $todo->status == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit"
                                class="px-6 py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-xl hover:from-teal-600 hover:to-cyan-700 transition shadow-lg shadow-teal-500/30 flex items-center justify-center gap-2">
                                <i class='bx bx-save'></i>
                                Update Status
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Task Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-info-circle text-teal-500'></i>
                            Details
                        </h3>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <i class='bx bx-calendar-plus text-gray-400'></i>
                            <div>
                                <p class="text-xs text-gray-500">Created</p>
                                <p class="text-sm font-medium text-gray-900">{{ $todo->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @if($todo->updated_at && $todo->updated_at != $todo->created_at)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                            <i class='bx bx-edit text-gray-400'></i>
                            <div>
                                <p class="text-xs text-gray-500">Last Updated</p>
                                <p class="text-sm font-medium text-gray-900">{{ $todo->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Completion Status -->
                @if($todo->status === 'completed')
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <i class='bx bx-check-circle text-3xl'></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg">Task Completed!</h4>
                            <p class="text-green-100 text-sm">Great job on finishing this task</p>
                        </div>
                    </div>
                </div>
                @elseif($todo->due_date && $todo->isOverdue())
                <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <i class='bx bx-error-circle text-3xl'></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg">Task Overdue!</h4>
                            <p class="text-red-100 text-sm">This task is past its due date</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection