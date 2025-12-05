@extends('layouts.admin')

@section('title', 'Create Staff')
@section('page-title', 'Create New Staff')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-cyan-600 to-teal-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class='bx bx-user-plus text-2xl'></i>
                        </div>
                        Create New Staff
                    </h1>
                    <p class="mt-2 text-cyan-100">Add a new staff member to the system</p>
                </div>
                <a href="{{ route('admin.staff.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                    <i class='bx bx-arrow-back'></i>
                    Back to List
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.staff.store') }}" method="POST" id="createStaffForm">
                @csrf

                <!-- User Account Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-link text-cyan-600'></i>
                        Link to User Account
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Required - Staff must be linked to an existing user with staff
                        role</p>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <select id="user_id" name="user_id" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm bg-white @error('user_id') border-red-500 @enderror">
                        <option value="">Select User (must have staff role)</option>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                            {{ $message }}</p>
                    @enderror
                    @if($availableUsers->isEmpty())
                        <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                            <p class="text-sm text-amber-700 flex items-center gap-1">
                                <i class='bx bx-info-circle'></i>
                                No available users with staff role. Please create a user with staff role first.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Personal Information Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-user text-cyan-600'></i>
                        Personal Information
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                                autofocus
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm @error('first_name') border-red-500 @enderror"
                                placeholder="Enter first name">
                            @error('first_name')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm @error('last_name') border-red-500 @enderror"
                                placeholder="Enter last name">
                            @error('last_name')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm"
                                placeholder="Enter phone number">
                        </div>

                        <!-- Hire Date -->
                        <div>
                            <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-2">Hire Date</label>
                            <input type="date" id="hire_date" name="hire_date" value="{{ old('hire_date') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm">
                        </div>
                    </div>
                </div>

                <!-- Position & Department Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-briefcase text-cyan-600'></i>
                        Position & Department
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Position -->
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                            <input type="text" id="position" name="position" value="{{ old('position') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm"
                                placeholder="e.g., Receptionist, Nurse, Administrator">
                        </div>

                        <!-- Department -->
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <input type="text" id="department" name="department" value="{{ old('department') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm"
                                placeholder="Enter department">
                        </div>
                    </div>
                </div>

                <!-- Employment & Salary Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-wallet text-cyan-600'></i>
                        Employment & Salary
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Employment Type -->
                        <div>
                            <label for="employment_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Employment Type <span class="text-red-500">*</span>
                            </label>
                            <select id="employment_type" name="employment_type" required onchange="toggleSalaryFields()"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm bg-white">
                                <option value="full_time" {{ old('employment_type', 'full_time') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part_time" {{ old('employment_type') == 'part_time' ? 'selected' : '' }}>Part
                                    Time</option>
                            </select>
                        </div>

                        <!-- Basic Salary (Full Time) -->
                        <div id="basic_salary_field">
                            <label for="basic_salary" class="block text-sm font-medium text-gray-700 mb-2">
                                Basic Salary (RM) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">RM</span>
                                <input type="number" id="basic_salary" name="basic_salary" value="{{ old('basic_salary') }}"
                                    step="0.01" min="0"
                                    class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm @error('basic_salary') border-red-500 @enderror"
                                    placeholder="0.00">
                            </div>
                            @error('basic_salary')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hourly Rate (Part Time) -->
                        <div id="hourly_rate_field" style="display: none;">
                            <label for="hourly_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                Hourly Rate (RM/hour) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">RM</span>
                                <input type="number" id="hourly_rate" name="hourly_rate"
                                    value="{{ old('hourly_rate', '8.00') }}" step="0.01" min="0"
                                    class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm"
                                    placeholder="8.00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-file-blank text-cyan-600'></i>
                        Additional Notes
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <textarea id="notes" name="notes" rows="3"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all text-sm"
                        placeholder="Enter any additional notes...">{{ old('notes') }}</textarea>
                </div>

                <!-- Actions -->
                <div class="p-6 bg-gray-50/50 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.staff.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all text-sm">
                        <i class='bx bx-x'></i>
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-cyan-600 text-white rounded-xl font-semibold hover:bg-cyan-700 transition-all text-sm shadow-lg shadow-cyan-600/20">
                        <i class='bx bx-save'></i>
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

            document.addEventListener('DOMContentLoaded', function () {
                toggleSalaryFields();
            });
        </script>
    @endpush
@endsection