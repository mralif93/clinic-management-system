@extends('layouts.admin')

@section('title', 'Create Staff')
@section('page-title', 'Create New Staff')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-user-plus text-yellow-600 mr-3'></i>
                    Create New Staff
                </h2>
                <p class="text-sm text-gray-600 mt-1">Add a new staff member to the system</p>
            </div>
            <a href="{{ route('admin.staff.index') }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <i class='bx bx-arrow-back mr-2 text-base'></i>
                Back to List
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.staff.store') }}" method="POST" id="createStaffForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Selection -->
                <div class="md:col-span-2">
                    <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-link mr-1 text-sm text-yellow-600'></i>
                        User Account <span class="text-red-500">*</span>
                        <span class="text-gray-500 text-xs font-normal">(Must have staff role)</span>
                    </label>
                    <select id="user_id" 
                            name="user_id" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition @error('user_id') border-red-500 ring-red-200 @enderror">
                        <option value="">Select User (must have staff role)</option>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
                    @if($availableUsers->isEmpty())
                        <p class="mt-2 text-xs text-yellow-600 flex items-center">
                            <i class='bx bx-info-circle mr-1'></i>
                            No available users with staff role. Please create a user with staff role first.
                        </p>
                    @endif
                </div>

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-user mr-1 text-sm text-yellow-600'></i>
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="first_name" 
                           name="first_name" 
                           value="{{ old('first_name') }}"
                           required
                           autofocus
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition @error('first_name') border-red-500 ring-red-200 @enderror"
                           placeholder="Enter first name">
                    @error('first_name')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-user mr-1 text-sm text-yellow-600'></i>
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="last_name" 
                           name="last_name" 
                           value="{{ old('last_name') }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition @error('last_name') border-red-500 ring-red-200 @enderror"
                           placeholder="Enter last name">
                    @error('last_name')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-phone mr-1 text-sm text-yellow-600'></i>
                        Phone
                    </label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                           placeholder="Enter phone number">
                </div>

                <!-- Position -->
                <div>
                    <label for="position" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-briefcase mr-1 text-sm text-yellow-600'></i>
                        Position
                    </label>
                    <input type="text" 
                           id="position" 
                           name="position" 
                           value="{{ old('position') }}"
                           placeholder="e.g., Receptionist, Nurse, Administrator"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-building mr-1 text-sm text-yellow-600'></i>
                        Department
                    </label>
                    <input type="text" 
                           id="department" 
                           name="department" 
                           value="{{ old('department') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                           placeholder="Enter department">
                </div>

                <!-- Hire Date -->
                <div>
                    <label for="hire_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-calendar mr-1 text-sm text-yellow-600'></i>
                        Hire Date
                    </label>
                    <input type="date"
                           id="hire_date"
                           name="hire_date"
                           value="{{ old('hire_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                </div>

                <!-- Employment Type -->
                <div>
                    <label for="employment_type" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-briefcase mr-1 text-sm text-yellow-600'></i>
                        Employment Type <span class="text-red-500">*</span>
                    </label>
                    <select id="employment_type"
                            name="employment_type"
                            required
                            onchange="toggleSalaryFields()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition @error('employment_type') border-red-500 ring-red-200 @enderror">
                        <option value="full_time" {{ old('employment_type', 'full_time') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part_time" {{ old('employment_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                    </select>
                    @error('employment_type')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Basic Salary (Full Time) -->
                <div id="basic_salary_field">
                    <label for="basic_salary" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-money mr-1 text-sm text-yellow-600'></i>
                        Basic Salary (RM) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           id="basic_salary"
                           name="basic_salary"
                           value="{{ old('basic_salary') }}"
                           step="0.01"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition @error('basic_salary') border-red-500 ring-red-200 @enderror"
                           placeholder="Enter basic salary">
                    @error('basic_salary')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Hourly Rate (Part Time) -->
                <div id="hourly_rate_field" style="display: none;">
                    <label for="hourly_rate" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-time mr-1 text-sm text-yellow-600'></i>
                        Hourly Rate (RM/hour) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           id="hourly_rate"
                           name="hourly_rate"
                           value="{{ old('hourly_rate', '8.00') }}"
                           step="0.01"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition @error('hourly_rate') border-red-500 ring-red-200 @enderror"
                           placeholder="Enter hourly rate">
                    @error('hourly_rate')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-file-blank mr-1 text-sm text-yellow-600'></i>
                        Notes
                    </label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                              placeholder="Enter any additional notes">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('admin.staff.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                    <i class='bx bx-x mr-2 text-base'></i>
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 transition shadow-md hover:shadow-lg">
                    <i class='bx bx-save mr-2 text-base'></i>
                    Create Staff
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleSalaryFields() {
    const employmentType = document.getElementById('employment_type').value;
    const basicSalaryField = document.getElementById('basic_salary_field');
    const hourlyRateField = document.getElementById('hourly_rate_field');
    const basicSalaryInput = document.getElementById('basic_salary');
    const hourlyRateInput = document.getElementById('hourly_rate');

    if (employmentType === 'full_time') {
        basicSalaryField.style.display = 'block';
        hourlyRateField.style.display = 'none';
        basicSalaryInput.required = true;
        hourlyRateInput.required = false;
    } else if (employmentType === 'part_time') {
        basicSalaryField.style.display = 'none';
        hourlyRateField.style.display = 'block';
        basicSalaryInput.required = false;
        hourlyRateInput.required = true;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleSalaryFields();
});
</script>
@endpush
@endsection
