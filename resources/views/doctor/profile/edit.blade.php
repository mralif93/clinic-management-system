@extends('layouts.doctor', ['hideLayoutTitle' => true])

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')
@section('hide-layout-title', true)

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div
            class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-6">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('doctor.profile.show') }}"
                        class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <i class='hgi-stroke hgi-arrow-left-01 text-white text-xl'></i>
                    </a>
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">Edit Profile</h1>
                        <p class="text-emerald-100 text-sm mt-1">Update your personal and professional information</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-sm text-white">
                        <i class='hgi-stroke hgi-user-circle mr-1'></i>
                        {{ Auth::user()->name }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <i class='hgi-stroke hgi-user text-emerald-500'></i> Profile Information
                </h3>
            </div>

            <form action="{{ route('doctor.profile.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">First Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $doctor->first_name) }}" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('first_name') border-red-500 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $doctor->last_name) }}" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('last_name') border-red-500 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $doctor->email) }}" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $doctor->phone) }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Professional Information -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Specialization</label>
                        <input type="text" name="specialization"
                            value="{{ old('specialization', $doctor->specialization) }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('specialization') border-red-500 @enderror">
                        @error('specialization')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Qualification</label>
                        <input type="text" name="qualification" value="{{ old('qualification', $doctor->qualification) }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('qualification') border-red-500 @enderror">
                        @error('qualification')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Type <span
                                class="text-red-500">*</span></label>
                        <select name="type" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('type') border-red-500 @enderror">
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
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Availability</label>
                        <div class="mt-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_available" value="1" {{ old('is_available', $doctor->is_available) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="ml-3 text-gray-700">Available for appointments</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Bio -->
                <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bio</label>
                    <textarea name="bio" rows="4"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('bio') border-red-500 @enderror">{{ old('bio', $doctor->bio) }}</textarea>
                    @error('bio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('doctor.profile.show') }}"
                        class="px-5 py-2.5 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 transition font-medium">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition shadow-sm">
                        <i class='hgi-stroke hgi-floppy-disk mr-2'></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection