@extends('layouts.admin')

@section('title', 'Create To-Do')
@section('page-title', 'Create To-Do')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <i class='bx bx-plus-circle text-2xl'></i>
                    </div>
                    Create New To-Do
                </h1>
                <p class="mt-2 text-amber-100">Add a new task to your to-do list</p>
            </div>
            <a href="{{ route('admin.todos.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                <i class='bx bx-arrow-back'></i>
                Back to List
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.todos.store') }}" method="POST">
            @csrf

            <!-- Task Details Section -->
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-task text-amber-600'></i>
                    Task Details
                </h3>
            </div>
            <div class="p-6 border-b border-gray-100">
                <div class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required autofocus
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all text-sm @error('title') border-red-500 @enderror"
                            placeholder="Enter task title">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all text-sm"
                            placeholder="Enter task description...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Status & Priority Section -->
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-flag text-amber-600'></i>
                    Status & Priority
                </h3>
            </div>
            <div class="p-6 border-b border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all text-sm bg-white">
                            @foreach(\App\Models\Todo::getStatuses() as $key => $label)
                                <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select id="priority" name="priority" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all text-sm bg-white">
                            @foreach(\App\Models\Todo::getPriorities() as $key => $label)
                                <option value="{{ $key }}" {{ old('priority', 'medium') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Assignment Section -->
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-user-check text-amber-600'></i>
                    Assignment
                </h3>
            </div>
            <div class="p-6 border-b border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                        <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all text-sm">
                    </div>

                    <!-- Assigned To -->
                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                        <select id="assigned_to" name="assigned_to"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 transition-all text-sm bg-white">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Recurring Section -->
            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-repeat text-amber-600'></i>
                    Recurring Options
                </h3>
            </div>
            <div class="p-6 border-b border-gray-100">
                <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-xl">
                    <label class="inline-flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" id="is_recurring" name="is_recurring" value="1" {{ old('is_recurring') ? 'checked' : '' }}
                            onchange="toggleRecurrenceType()"
                            class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="text-sm font-medium text-gray-700 flex items-center gap-1">
                            <i class='bx bx-repeat'></i> Make this a recurring task
                        </span>
                    </label>
                    
                    <div id="recurrence_type_container" class="hidden mt-4">
                        <label for="recurrence_type" class="block text-sm font-medium text-gray-700 mb-2">Recurrence Type</label>
                        <select id="recurrence_type" name="recurrence_type"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all text-sm bg-white">
                            <option value="">Select frequency</option>
                            <option value="daily" {{ old('recurrence_type') == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('recurrence_type') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('recurrence_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                        <p class="mt-2 text-xs text-indigo-700 flex items-center gap-1">
                            <i class='bx bx-info-circle'></i> System will automatically create new instances of this task based on the selected frequency.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="p-6 bg-gray-50/50 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('admin.todos.index') }}" 
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all text-sm">
                    <i class='bx bx-x'></i>
                    Cancel
                </a>
                <button type="submit" 
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-amber-600 text-white rounded-xl font-semibold hover:bg-amber-700 transition-all text-sm shadow-lg shadow-amber-600/20">
                    <i class='bx bx-save'></i>
                    Create To-Do
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleRecurrenceType() {
    const checkbox = document.getElementById('is_recurring');
    const container = document.getElementById('recurrence_type_container');
    const select = document.getElementById('recurrence_type');
    
    if (checkbox.checked) {
        container.classList.remove('hidden');
        select.required = true;
    } else {
        container.classList.add('hidden');
        select.required = false;
        select.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleRecurrenceType();
});
</script>
@endpush
@endsection