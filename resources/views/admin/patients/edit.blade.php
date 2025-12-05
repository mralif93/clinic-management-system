@extends('layouts.admin')

@section('title', 'Edit Patient')
@section('page-title', 'Edit Patient')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-rose-600 to-pink-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="w-16 h-16 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center text-white font-bold text-2xl">
                        {{ strtoupper(substr($patient->first_name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">
                            {{ $patient->full_name ?? ($patient->first_name . ' ' . $patient->last_name) }}</h1>
                        <p class="text-rose-100">{{ $patient->email ?? 'No email' }}</p>
                        @if($patient->patient_id)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium bg-white/20 mt-1">
                                ID: {{ $patient->patient_id }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.patients.show', $patient->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-show'></i>
                        View Details
                    </a>
                    <a href="{{ route('admin.patients.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='bx bx-arrow-back'></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST" id="editPatientForm">
                @csrf
                @method('PUT')

                <!-- User Account Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-link text-rose-600'></i>
                        Link to User Account
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <select id="user_id" name="user_id"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm bg-white">
                        <option value="">No user account</option>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $patient->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Personal Information Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-user text-rose-600'></i>
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
                                value="{{ old('first_name', $patient->first_name) }}" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm @error('first_name') border-red-500 @enderror"
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
                            <input type="text" id="last_name" name="last_name"
                                value="{{ old('last_name', $patient->last_name) }}" required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm @error('last_name') border-red-500 @enderror"
                                placeholder="Enter last name">
                            @error('last_name')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $patient->email) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm @error('email') border-red-500 @enderror"
                                placeholder="Enter email address">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><i class='bx bx-error-circle'></i>
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $patient->phone) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm"
                                placeholder="Enter phone number">
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of
                                Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth"
                                value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm">
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                            <select id="gender" name="gender"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm bg-white">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male
                                </option>
                                <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>
                                    Female</option>
                                <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea id="address" name="address" rows="2"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm"
                                placeholder="Enter full address">{{ old('address', $patient->address) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Medical Information Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-heart text-rose-600'></i>
                        Medical Information
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Medical History -->
                        <div>
                            <label for="medical_history" class="block text-sm font-medium text-gray-700 mb-2">Medical
                                History</label>
                            <textarea id="medical_history" name="medical_history" rows="3"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm"
                                placeholder="Enter any relevant medical history...">{{ old('medical_history', $patient->medical_history) }}</textarea>
                        </div>

                        <!-- Allergies -->
                        <div>
                            <label for="allergies" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="flex items-center gap-2">
                                    Allergies
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700">Important</span>
                                </span>
                            </label>
                            <textarea id="allergies" name="allergies" rows="2"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm"
                                placeholder="List any known allergies...">{{ old('allergies', $patient->allergies) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact Section -->
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-phone text-rose-600'></i>
                        Emergency Contact
                    </h3>
                </div>
                <div class="p-6 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Emergency Contact Name -->
                        <div>
                            <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">Contact
                                Name</label>
                            <input type="text" id="emergency_contact_name" name="emergency_contact_name"
                                value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm"
                                placeholder="Enter emergency contact name">
                        </div>

                        <!-- Emergency Contact Phone -->
                        <div>
                            <label for="emergency_contact_phone"
                                class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                            <input type="text" id="emergency_contact_phone" name="emergency_contact_phone"
                                value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 transition-all text-sm"
                                placeholder="Enter emergency contact phone">
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="p-6 bg-gray-50/50 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('admin.patients.index') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition-all text-sm">
                        <i class='bx bx-x'></i>
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-rose-600 text-white rounded-xl font-semibold hover:bg-rose-700 transition-all text-sm shadow-lg shadow-rose-600/20">
                        <i class='bx bx-save'></i>
                        Update Patient
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection