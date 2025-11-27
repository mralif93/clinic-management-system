@extends('layouts.doctor')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@section('content')
    <div class="w-full">
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Edit Profile</h3>
                <a href="{{ route('doctor.profile.show') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>

            <!-- Edit Form -->
            <form action="{{ route('doctor.profile.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $doctor->first_name) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $doctor->last_name) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email <span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $doctor->email) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $doctor->phone) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Professional Information -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Specialization</label>
                        <input type="text" name="specialization"
                            value="{{ old('specialization', $doctor->specialization) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('specialization') border-red-500 @enderror">
                        @error('specialization')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Qualification</label>
                        <input type="text" name="qualification" value="{{ old('qualification', $doctor->qualification) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('qualification') border-red-500 @enderror">
                        @error('qualification')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type <span
                                class="text-red-500">*</span></label>
                        <select name="type" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('type') border-red-500 @enderror">
                            <option value="psychology" {{ old('type', $doctor->type) == 'psychology' ? 'selected' : '' }}>
                                Psychology</option>
                            <option value="homeopathy" {{ old('type', $doctor->type) == 'homeopathy' ? 'selected' : '' }}>
                                Homeopathy</option>
                            <option value="general" {{ old('type', $doctor->type) == 'general' ? 'selected' : '' }}>General
                            </option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
                        <div class="mt-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_available" value="1" {{ old('is_available', $doctor->is_available) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-gray-700">Available for appointments</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Bio -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                    <textarea name="bio" rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('bio') border-red-500 @enderror">{{ old('bio', $doctor->bio) }}</textarea>
                    @error('bio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition">
                        <i class='bx bx-save mr-2'></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection