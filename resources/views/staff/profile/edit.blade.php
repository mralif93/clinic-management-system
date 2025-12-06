@extends('layouts.staff')

@section('title', 'Edit Profile')
@section('page-title', 'Edit Profile')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-violet-500 via-purple-500 to-indigo-600 rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <i class='bx bx-edit text-white text-2xl'></i>
                        </div>
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">Edit Profile</h1>
                            <p class="text-violet-100 text-sm mt-1">Update your personal information</p>
                        </div>
                    </div>
                    <a href="{{ route('staff.profile.show') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-white/20 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/30 transition border border-white/30">
                        <i class='bx bx-arrow-back mr-2'></i> Back to Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Edit Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('staff.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class='bx bx-user text-violet-500'></i>
                        Basic Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class='bx bx-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                                <input type="text" name="first_name" value="{{ old('first_name', $staff->first_name) }}" required
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('first_name') border-red-300 bg-red-50 @enderror"
                                    placeholder="Enter first name">
                            </div>
                            @error('first_name')
                                <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class='bx bx-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                                <input type="text" name="last_name" value="{{ old('last_name', $staff->last_name) }}" required
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('last_name') border-red-300 bg-red-50 @enderror"
                                    placeholder="Enter last name">
                            </div>
                            @error('last_name')
                                <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
                                Phone Number
                            </label>
                            <div class="relative">
                                <i class='bx bx-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                                <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('phone') border-red-300 bg-red-50 @enderror"
                                    placeholder="e.g., 012-345 6789">
                            </div>
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
                                Hire Date
                            </label>
                            <div class="relative">
                                <i class='bx bx-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                                <input type="date" name="hire_date"
                                    value="{{ old('hire_date', $staff->hire_date ? $staff->hire_date->format('Y-m-d') : '') }}"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('hire_date') border-red-300 bg-red-50 @enderror">
                            </div>
                            @error('hire_date')
                                <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Work Information Section -->
                <div class="px-6 py-4 border-b border-t border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class='bx bx-briefcase text-violet-500'></i>
                        Work Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
                                Position
                            </label>
                            <div class="relative">
                                <i class='bx bx-briefcase absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                                <input type="text" name="position" value="{{ old('position', $staff->position) }}"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('position') border-red-300 bg-red-50 @enderror"
                                    placeholder="e.g., Receptionist, Nurse">
                            </div>
                            @error('position')
                                <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">
                                Department
                            </label>
                            <div class="relative">
                                <i class='bx bx-building absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                                <input type="text" name="department" value="{{ old('department', $staff->department) }}"
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('department') border-red-300 bg-red-50 @enderror"
                                    placeholder="e.g., Admin, Medical">
                            </div>
                            @error('department')
                                <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                    <i class='bx bx-error-circle'></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="px-6 py-4 border-b border-t border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class='bx bx-note text-violet-500'></i>
                        Additional Notes
                    </h3>
                </div>
                <div class="p-6">
                    <div class="relative">
                        <textarea name="notes" rows="4"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('notes') border-red-300 bg-red-50 @enderror resize-none"
                            placeholder="Add any additional notes or comments...">{{ old('notes', $staff->notes) }}</textarea>
                    </div>
                    @error('notes')
                        <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                            <i class='bx bx-error-circle'></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3">
                    <a href="{{ route('staff.profile.show') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-100 transition">
                        <i class='bx bx-x mr-2'></i> Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 text-white font-medium rounded-xl hover:from-violet-600 hover:to-purple-700 transition shadow-sm hover:shadow">
                        <i class='bx bx-save mr-2'></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection