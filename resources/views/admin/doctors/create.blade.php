@extends('layouts.admin')

@section('title', 'Create Doctor')
@section('page-title', 'Create New Doctor')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-user-plus text-green-600 mr-3'></i>
                    Create New Doctor
                </h2>
                <p class="text-sm text-gray-600 mt-1">Add a new doctor to the system</p>
            </div>
            <a href="{{ route('admin.doctors.index') }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                <i class='bx bx-arrow-back mr-2 text-base'></i>
                Back to List
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.doctors.store') }}" method="POST" id="createDoctorForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Account (Optional) -->
                <div class="md:col-span-2">
                    <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-link mr-1 text-sm text-green-600'></i>
                        Link to User Account <span class="text-gray-500 text-xs font-normal">(Optional - must have doctor role)</span>
                    </label>
                    <select id="user_id" 
                            name="user_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                        <option value="">No user account (create doctor only)</option>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @if($availableUsers->isEmpty())
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                            <i class='bx bx-info-circle mr-1'></i>
                            No available users with doctor role. Doctor can be created without user account.
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
                           value="{{ old('first_name') }}"
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
                           value="{{ old('last_name') }}"
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
                           value="{{ old('email') }}"
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
                           value="{{ old('phone') }}"
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
                           value="{{ old('specialization') }}"
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
                           value="{{ old('qualification') }}"
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
                        <option value="psychology" {{ old('type') == 'psychology' ? 'selected' : '' }}>Psychology</option>
                        <option value="homeopathy" {{ old('type') == 'homeopathy' ? 'selected' : '' }}>Homeopathy</option>
                        <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>General</option>
                    </select>
                    @error('type')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
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
                               {{ old('is_available', true) ? 'checked' : '' }}
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
                              placeholder="Enter doctor bio">{{ old('bio') }}</textarea>
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
                    Create Doctor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
