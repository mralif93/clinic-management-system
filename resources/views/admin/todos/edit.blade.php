@extends('layouts.admin')

@section('title', 'Edit To-Do')
@section('page-title', 'Edit To-Do')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Edit To-Do</h2>
                <p class="text-sm text-gray-600 mt-1">Update task information</p>
            </div>

            <form action="{{ route('admin.todos.update', $todo->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title', $todo->title) }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $todo->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @foreach(\App\Models\Todo::getStatuses() as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $todo->status) == $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-semibold text-gray-700 mb-2">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" id="priority" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @foreach(\App\Models\Todo::getPriorities() as $key => $label)
                                <option value="{{ $key }}" {{ old('priority', $todo->priority) == $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-semibold text-gray-700 mb-2">Due Date</label>
                        <input type="date" name="due_date" id="due_date"
                            value="{{ old('due_date', $todo->due_date?->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <!-- Assigned To -->
                    <div>
                        <label for="assigned_to" class="block text-sm font-semibold text-gray-700 mb-2">Assign To</label>
                        <select name="assigned_to" id="assigned_to"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $todo->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Recurring Task Section -->
                <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                    <div class="flex items-center mb-4">
                        <input type="checkbox" name="is_recurring" id="is_recurring" value="1" 
                               {{ old('is_recurring', $todo->is_recurring) ? 'checked' : '' }}
                               onchange="toggleRecurrenceType()"
                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="is_recurring" class="ml-2 text-sm font-semibold text-gray-700">
                            <i class='bx bx-repeat mr-1'></i>Make this a recurring task
                        </label>
                    </div>
                    
                    <div id="recurrence_type_container" class="hidden">
                        <label for="recurrence_type" class="block text-sm font-semibold text-gray-700 mb-2">
                            Recurrence Type
                        </label>
                        <select name="recurrence_type" id="recurrence_type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select frequency</option>
                            <option value="daily" {{ old('recurrence_type', $todo->recurrence_type) == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('recurrence_type', $todo->recurrence_type) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('recurrence_type', $todo->recurrence_type) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                        <p class="mt-2 text-xs text-indigo-700">
                            <i class='bx bx-info-circle'></i> System will automatically create new instances of this task based on the selected frequency.
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.todos.index') }}"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-md hover:shadow-lg">
                        <i class='bx bx-save mr-2'></i>Update To-Do
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
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleRecurrenceType();
        });
    </script>
    @endpush
@endsection