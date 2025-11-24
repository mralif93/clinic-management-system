@extends('layouts.admin')

@section('title', 'Edit Patient')
@section('page-title', 'Edit Patient')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-edit text-blue-600 mr-3'></i>
                    Edit Patient
                </h2>
                <p class="text-sm text-gray-600 mt-1">Update patient information</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.patients.show', $patient->id) }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-show mr-2 text-base'></i>
                    View Details
                </a>
                <a href="{{ route('admin.patients.index') }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-arrow-back mr-2 text-base'></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Patient Info Card -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-sm border border-blue-200 p-4 mb-6">
        <div class="flex items-center">
            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md mr-4">
                <span class="text-white font-bold text-2xl">{{ strtoupper(substr($patient->first_name ?? 'P', 0, 1)) }}</span>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}</h3>
                <p class="text-sm text-gray-600">{{ $patient->email ?? 'No email' }}</p>
                @if($patient->patient_id)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold mt-2 bg-blue-100 text-blue-800">
                        ID: {{ $patient->patient_id }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST" id="editPatientForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Account -->
                <div class="md:col-span-2">
                    <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-link mr-1 text-sm text-blue-600'></i>
                        Link to User Account <span class="text-gray-500 text-xs font-normal">(Optional - must have patient role)</span>
                    </label>
                    <select id="user_id" 
                            name="user_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">No user account</option>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $patient->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @if($availableUsers->isEmpty() && !$patient->user_id)
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                            <i class='bx bx-info-circle mr-1'></i>
                            No available users with patient role.
                        </p>
                    @endif
                </div>

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-user mr-1 text-sm text-blue-600'></i>
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="first_name" 
                           name="first_name" 
                           value="{{ old('first_name', $patient->first_name) }}"
                           required
                           autofocus
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('first_name') border-red-500 ring-red-200 @enderror"
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
                        <i class='bx bx-user mr-1 text-sm text-blue-600'></i>
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="last_name" 
                           name="last_name" 
                           value="{{ old('last_name', $patient->last_name) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('last_name') border-red-500 ring-red-200 @enderror"
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
                        <i class='bx bx-envelope mr-1 text-sm text-blue-600'></i>
                        Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $patient->email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('email') border-red-500 ring-red-200 @enderror"
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
                        <i class='bx bx-phone mr-1 text-sm text-blue-600'></i>
                        Phone
                    </label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $patient->phone) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="Enter phone number">
                </div>

                <!-- Date of Birth -->
                <div>
                    <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-calendar mr-1 text-sm text-blue-600'></i>
                        Date of Birth
                    </label>
                    <input type="date" 
                           id="date_of_birth" 
                           name="date_of_birth" 
                           value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-male-female mr-1 text-sm text-blue-600'></i>
                        Gender
                    </label>
                    <select id="gender" 
                            name="gender" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-map mr-1 text-sm text-blue-600'></i>
                        Address
                    </label>
                    <textarea id="address" 
                              name="address" 
                              rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                              placeholder="Enter address">{{ old('address', $patient->address) }}</textarea>
                </div>

                <!-- Medical History -->
                <div class="md:col-span-2">
                    <label for="medical_history" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-file-blank mr-1 text-sm text-blue-600'></i>
                        Medical History
                    </label>
                    <textarea id="medical_history" 
                              name="medical_history" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                              placeholder="Enter medical history">{{ old('medical_history', $patient->medical_history) }}</textarea>
                </div>

                <!-- Allergies -->
                <div class="md:col-span-2">
                    <label for="allergies" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-error-circle mr-1 text-sm text-blue-600'></i>
                        Allergies
                    </label>
                    <textarea id="allergies" 
                              name="allergies" 
                              rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                              placeholder="Enter allergies">{{ old('allergies', $patient->allergies) }}</textarea>
                </div>

                <!-- Emergency Contact -->
                <div>
                    <label for="emergency_contact_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-user mr-1 text-sm text-blue-600'></i>
                        Emergency Contact Name
                    </label>
                    <input type="text" 
                           id="emergency_contact_name" 
                           name="emergency_contact_name" 
                           value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="Enter emergency contact name">
                </div>

                <div>
                    <label for="emergency_contact_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-phone mr-1 text-sm text-blue-600'></i>
                        Emergency Contact Phone
                    </label>
                    <input type="text" 
                           id="emergency_contact_phone" 
                           name="emergency_contact_phone" 
                           value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="Enter emergency contact phone">
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('admin.patients.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                    <i class='bx bx-x mr-2 text-base'></i>
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-md hover:shadow-lg">
                    <i class='bx bx-save mr-2 text-base'></i>
                    Update Patient
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
