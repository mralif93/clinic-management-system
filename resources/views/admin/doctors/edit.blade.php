@extends('layouts.admin')

@section('title', 'Edit Doctor')
@section('page-title', 'Edit Doctor')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-edit text-green-600 mr-3'></i>
                    Edit Doctor
                </h2>
                <p class="text-sm text-gray-600 mt-1">Update doctor information</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.doctors.show', $doctor->id) }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-show mr-2 text-base'></i>
                    View Details
                </a>
                <a href="{{ route('admin.doctors.index') }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-arrow-back mr-2 text-base'></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Doctor Info Card -->
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg shadow-sm border border-green-200 p-4 mb-6">
        <div class="flex items-center">
            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center shadow-md mr-4">
                <span class="text-white font-bold text-2xl">{{ strtoupper(substr($doctor->first_name ?? 'D', 0, 1)) }}</span>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}</h3>
                <p class="text-sm text-gray-600">{{ $doctor->email }}</p>
                @if($doctor->doctor_id)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold mt-2 bg-green-100 text-green-800">
                        ID: {{ $doctor->doctor_id }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST" id="editDoctorForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Account -->
                <div class="md:col-span-2">
                    <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-link mr-1 text-sm text-green-600'></i>
                        Link to User Account <span class="text-gray-500 text-xs font-normal">(Optional - must have doctor role)</span>
                    </label>
                    <select id="user_id" 
                            name="user_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                        <option value="">No user account</option>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $doctor->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @if($availableUsers->isEmpty() && !$doctor->user_id)
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                            <i class='bx bx-info-circle mr-1'></i>
                            No available users with doctor role.
                        </p>
                    @endif
                </div>

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-user mr-1 text-sm text-green-600'></i>
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="first_name" 
                           name="first_name" 
                           value="{{ old('first_name', $doctor->first_name) }}"
                           required
                           autofocus
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition @error('first_name') border-red-500 ring-red-200 @enderror"
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
                        <i class='bx bx-user mr-1 text-sm text-green-600'></i>
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="last_name" 
                           name="last_name" 
                           value="{{ old('last_name', $doctor->last_name) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition @error('last_name') border-red-500 ring-red-200 @enderror"
                           placeholder="Enter last name">
                    @error('last_name')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-envelope mr-1 text-sm text-green-600'></i>
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $doctor->email) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition @error('email') border-red-500 ring-red-200 @enderror"
                           placeholder="Enter email address">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-phone mr-1 text-sm text-green-600'></i>
                        Phone
                    </label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $doctor->phone) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                           placeholder="Enter phone number">
                </div>

                <!-- Specialization -->
                <div>
                    <label for="specialization" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-briefcase mr-1 text-sm text-green-600'></i>
                        Specialization
                    </label>
                    <input type="text" 
                           id="specialization" 
                           name="specialization" 
                           value="{{ old('specialization', $doctor->specialization) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                           placeholder="Enter specialization">
                </div>

                <!-- Qualification -->
                <div>
                    <label for="qualification" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-award mr-1 text-sm text-green-600'></i>
                        Qualification
                    </label>
                    <input type="text" 
                           id="qualification" 
                           name="qualification" 
                           value="{{ old('qualification', $doctor->qualification) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                           placeholder="Enter qualification">
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-category mr-1 text-sm text-green-600'></i>
                        Type <span class="text-red-500">*</span>
                    </label>
                    <select id="type" 
                            name="type" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition @error('type') border-red-500 ring-red-200 @enderror">
                        <option value="">Select Type</option>
                        <option value="psychology" {{ old('type', $doctor->type) == 'psychology' ? 'selected' : '' }}>Psychology</option>
                        <option value="homeopathy" {{ old('type', $doctor->type) == 'homeopathy' ? 'selected' : '' }}>Homeopathy</option>
                        <option value="general" {{ old('type', $doctor->type) == 'general' ? 'selected' : '' }}>General</option>
                    </select>
                    @error('type')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Employment Type -->
                <div>
                    <label for="employment_type" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-briefcase mr-1 text-sm text-green-600'></i>
                        Employment Type <span class="text-red-500">*</span>
                    </label>
                    <select id="employment_type"
                            name="employment_type"
                            required
                            onchange="toggleDoctorSalaryFields()"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition @error('employment_type') border-red-500 ring-red-200 @enderror">
                        <option value="full_time" {{ old('employment_type', $doctor->user->employment_type ?? 'full_time') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                        <option value="locum" {{ old('employment_type', $doctor->user->employment_type) == 'locum' ? 'selected' : '' }}>Locum</option>
                    </select>
                    @error('employment_type')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Basic Salary (Full Time) -->
                <div id="doctor_basic_salary_field">
                    <label for="basic_salary" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-money mr-1 text-sm text-green-600'></i>
                        Basic Salary (RM) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           id="basic_salary"
                           name="basic_salary"
                           value="{{ old('basic_salary', $doctor->user->basic_salary) }}"
                           step="0.01"
                           min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition @error('basic_salary') border-red-500 ring-red-200 @enderror"
                           placeholder="Enter basic salary">
                    @error('basic_salary')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Commission Rate (Locum) -->
                <div id="commission_rate_field" style="display: none;">
                    <label for="commission_rate" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-percentage mr-1 text-sm text-green-600'></i>
                        Commission Rate (%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           id="commission_rate"
                           name="commission_rate"
                           value="{{ old('commission_rate', $doctor->commission_rate ?? '60.00') }}"
                           step="0.01"
                           min="0"
                           max="100"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition @error('commission_rate') border-red-500 ring-red-200 @enderror"
                           placeholder="Enter commission rate">
                    @error('commission_rate')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Percentage of appointment fees earned as salary</p>
                </div>

                <!-- Is Available -->
                <div>
                    <label for="is_available" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-check-circle mr-1 text-sm text-green-600'></i>
                        Availability
                    </label>
                    <div class="flex items-center mt-2">
                        <input type="checkbox"
                               id="is_available"
                               name="is_available"
                               value="1"
                               {{ old('is_available', $doctor->is_available) ? 'checked' : '' }}
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="is_available" class="ml-2 text-sm text-gray-700">Available for appointments</label>
                    </div>
                </div>

                <!-- Bio -->
                <div class="md:col-span-2">
                    <label for="bio" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-file-blank mr-1 text-sm text-green-600'></i>
                        Bio
                    </label>
                    <textarea id="bio" 
                              name="bio" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                              placeholder="Enter doctor bio">{{ old('bio', $doctor->bio) }}</textarea>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('admin.doctors.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                    <i class='bx bx-x mr-2 text-base'></i>
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition shadow-md hover:shadow-lg">
                    <i class='bx bx-save mr-2 text-base'></i>
                    Update Doctor
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleDoctorSalaryFields() {
    const employmentType = document.getElementById('employment_type').value;
    const basicSalaryField = document.getElementById('doctor_basic_salary_field');
    const commissionRateField = document.getElementById('commission_rate_field');
    const basicSalaryInput = document.getElementById('basic_salary');
    const commissionRateInput = document.getElementById('commission_rate');

    if (employmentType === 'full_time') {
        basicSalaryField.style.display = 'block';
        commissionRateField.style.display = 'none';
        basicSalaryInput.required = true;
        commissionRateInput.required = false;
    } else if (employmentType === 'locum') {
        basicSalaryField.style.display = 'none';
        commissionRateField.style.display = 'block';
        basicSalaryInput.required = false;
        commissionRateInput.required = true;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleDoctorSalaryFields();
});
</script>
@endpush
@endsection
