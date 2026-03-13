@extends('layouts.admin')

@section('title', 'Edit Doctor')
@section('page-title', 'Edit Doctor')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div
            class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div
                        class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <i class='hgi-stroke hgi-pencil-edit-01 text-2xl'></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">Edit Doctor</h2>
                        <p class="text-green-100 text-sm mt-1">
                            {{ $doctor->full_name ?? ($doctor->first_name . ' ' . $doctor->last_name) }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.doctors.show', $doctor->id) }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold rounded-xl hover:bg-white/30 transition-all shadow-lg hover:shadow-xl">
                        <i class='hgi-stroke hgi-eye'></i>
                        View Details
                    </a>
                    <a href="{{ route('admin.doctors.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold rounded-xl hover:bg-white/30 transition-all shadow-lg hover:shadow-xl">
                        <i class='hgi-stroke hgi-arrow-left-01'></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST" id="editDoctorForm">
                @csrf
                @method('PUT')

                <!-- User Account Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-link-01 text-green-600'></i>
                        Link to User Account
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <select id="user_id" name="user_id"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm bg-white">
                        <option value="">No user account</option>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $doctor->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Personal Information Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-user text-green-600'></i>
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
                            <input type="text" id="first_name" name="first_name"
                                value="{{ old('first_name', $doctor->first_name) }}" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm @error('first_name') border-red-500 @enderror"
                                placeholder="Enter first name">
                            @error('first_name')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="last_name" name="last_name"
                                value="{{ old('last_name', $doctor->last_name) }}" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm @error('last_name') border-red-500 @enderror"
                                placeholder="Enter last name">
                            @error('last_name')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $doctor->email) }}" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm @error('email') border-red-500 @enderror"
                                placeholder="Enter email address">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $doctor->phone) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm"
                                placeholder="Enter phone number">
                        </div>
                    </div>
                </div>

                <!-- Professional Information Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-briefcase-01 text-green-600'></i>
                        Professional Information
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Specialization -->
                        <div>
                            <label for="specialization"
                                class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                            <input type="text" id="specialization" name="specialization"
                                value="{{ old('specialization', $doctor->specialization) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm"
                                placeholder="e.g., Cardiology, Pediatrics">
                        </div>

                        <!-- Qualification -->
                        <div>
                            <label for="qualification"
                                class="block text-sm font-medium text-gray-700 mb-2">Qualification</label>
                            <input type="text" id="qualification" name="qualification"
                                value="{{ old('qualification', $doctor->qualification) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm"
                                placeholder="e.g., MBBS, MD">
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                Type <span class="text-red-500">*</span>
                            </label>
                            <select id="type" name="type" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm bg-white @error('type') border-red-500 @enderror">
                                <option value="">Select Type</option>
                                <option value="psychology" {{ old('type', $doctor->type) == 'psychology' ? 'selected' : '' }}>
                                    Psychology</option>
                                <option value="homeopathy" {{ old('type', $doctor->type) == 'homeopathy' ? 'selected' : '' }}>
                                    Homeopathy</option>
                                <option value="general" {{ old('type', $doctor->type) == 'general' ? 'selected' : '' }}>
                                    General</option>
                            </select>
                            @error('type')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Availability -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
                            <label
                                class="inline-flex items-center gap-3 px-4 py-2.5 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all w-full">
                                <input type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', $doctor->is_available) ? 'checked' : '' }}
                                    class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="text-sm text-gray-700">Available for appointments</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Employment & Salary Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-wallet-01 text-green-600'></i>
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
                            <select id="employment_type" name="employment_type" required
                                onchange="toggleDoctorSalaryFields()"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm bg-white">
                                <option value="full_time" {{ old('employment_type', $doctor->user?->employment_type ?? 'full_time') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                <option value="locum" {{ old('employment_type', $doctor->user?->employment_type) == 'locum' ? 'selected' : '' }}>Locum</option>
                            </select>
                        </div>

                        <!-- Basic Salary (Full Time) -->
                        <div id="doctor_basic_salary_field">
                            <label for="basic_salary" class="block text-sm font-medium text-gray-700 mb-2">
                                Basic Salary (RM) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">RM</span>
                                <input type="number" id="basic_salary" name="basic_salary"
                                    value="{{ old('basic_salary', $doctor->user?->basic_salary ?? '') }}" step="0.01"
                                    min="0"
                                    class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm @error('basic_salary') border-red-500 @enderror"
                                    placeholder="0.00">
                            </div>
                            @error('basic_salary')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Commission Rate (Locum) -->
                        <div id="commission_rate_field" style="display: none;">
                            <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                Commission Rate (%) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="commission_rate" name="commission_rate"
                                    value="{{ old('commission_rate', $doctor->commission_rate ?? '60.00') }}" step="0.01"
                                    min="0" max="100"
                                    class="w-full pr-10 px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm @error('commission_rate') border-red-500 @enderror"
                                    placeholder="60.00">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">%</span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Percentage of appointment fees earned as salary</p>
                        </div>
                    </div>
                </div>

                <!-- LHDN Tax Information Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-file-check-01 text-green-600'></i>
                        LHDN Tax Information
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Information for PCB/MTD tax calculations</p>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Marital Status -->
                        <div>
                            <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-2">Marital
                                Status</label>
                            <select id="marital_status" name="marital_status"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm bg-white @error('marital_status') border-red-500 @enderror">
                                <option value="single" {{ old('marital_status', $doctor->user?->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('marital_status', $doctor->user?->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="married_spouse_working" {{ old('marital_status', $doctor->user?->marital_status) == 'married_spouse_working' ? 'selected' : '' }}>Married
                                    (Spouse Working)</option>
                                <option value="married_spouse_not_working" {{ old('marital_status', $doctor->user?->marital_status) == 'married_spouse_not_working' ? 'selected' : '' }}>
                                    Married (Spouse Not Working)</option>
                            </select>
                            @error('marital_status')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Number of Children -->
                        <div>
                            <label for="number_of_children" class="block text-sm font-medium text-gray-700 mb-2">Number of
                                Children</label>
                            <input type="number" id="number_of_children" name="number_of_children"
                                value="{{ old('number_of_children', $doctor->user?->number_of_children ?? 0) }}" min="0"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm @error('number_of_children') border-red-500 @enderror"
                                placeholder="0">
                            @error('number_of_children')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tax Number (Income Tax No) -->
                        <div class="md:col-span-2">
                            <label for="tax_number" class="block text-sm font-medium text-gray-700 mb-2">LHDN Tax
                                Number</label>
                            <input type="text" id="tax_number" name="tax_number"
                                value="{{ old('tax_number', $doctor->user?->tax_number) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm @error('tax_number') border-red-500 @enderror"
                                placeholder="e.g. SG 1234567890">
                            @error('tax_number')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i
                                        class='hgi-stroke hgi-alert-circle'></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Bio Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-file-01 text-green-600'></i>
                        Biography
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <textarea id="bio" name="bio" rows="4"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition-all text-sm"
                        placeholder="Enter a brief bio about the doctor...">{{ old('bio', $doctor->bio) }}</textarea>
                </div>

                <!-- Actions -->
                <div class="p-6 bg-gray-50/50 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.doctors.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all text-sm">
                        <i class='hgi-stroke hgi-cancel-circle'></i>
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition-all text-sm shadow-lg shadow-green-600/20">
                        <i class='hgi-stroke hgi-floppy-disk'></i>
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

            document.addEventListener('DOMContentLoaded', function () {
                toggleDoctorSalaryFields();
            });
        </script>
    @endpush
@endsection