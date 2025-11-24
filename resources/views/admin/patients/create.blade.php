@extends('layouts.admin')

@section('title', 'Create Patient')
@section('page-title', 'Create New Patient')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-user-plus text-blue-600 mr-3'></i>
                    Create New Patient
                </h2>
                <p class="text-sm text-gray-600 mt-1">Add a new patient to the system</p>
            </div>
            <a href="{{ route('admin.patients.index') }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <i class='bx bx-arrow-back mr-2 text-base'></i>
                Back to List
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.patients.store') }}" method="POST" id="createPatientForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Account (Optional) -->
                <div class="md:col-span-2">
                    <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-link mr-1 text-sm text-blue-600'></i>
                        Link to User Account <span class="text-gray-500 text-xs font-normal">(Optional - must have patient role)</span>
                    </label>
                    <select id="user_id" 
                            name="user_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">No user account (create patient only)</option>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @if($availableUsers->isEmpty())
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                            <i class='bx bx-info-circle mr-1'></i>
                            No available users with patient role. Patient can be created without user account.
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
                           value="{{ old('first_name') }}"
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
                           value="{{ old('last_name') }}"
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
                           value="{{ old('email') }}"
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
                           value="{{ old('phone') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('phone') border-red-500 ring-red-200 @enderror"
                           placeholder="Enter phone number">
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
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
                           value="{{ old('date_of_birth') }}"
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
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
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
                              placeholder="Enter address">{{ old('address') }}</textarea>
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
                              placeholder="Enter medical history">{{ old('medical_history') }}</textarea>
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
                              placeholder="Enter allergies">{{ old('allergies') }}</textarea>
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
                           value="{{ old('emergency_contact_name') }}"
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
                           value="{{ old('emergency_contact_phone') }}"
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
                    Create Patient
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
