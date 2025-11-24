@extends('layouts.admin')

@section('title', 'Edit Staff')
@section('page-title', 'Edit Staff')

@section('content')
<div class="w-full">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class='bx bx-edit text-yellow-600 mr-3'></i>
                    Edit Staff
                </h2>
                <p class="text-sm text-gray-600 mt-1">Update staff information</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.staff.show', $staff->id) }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-show mr-2 text-base'></i>
                    View Details
                </a>
                <a href="{{ route('admin.staff.index') }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    <i class='bx bx-arrow-back mr-2 text-base'></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Staff Info Card -->
    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-lg shadow-sm border border-yellow-200 p-4 mb-6">
        <div class="flex items-center">
            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center shadow-md mr-4">
                <span class="text-white font-bold text-2xl">{{ strtoupper(substr($staff->first_name ?? 'S', 0, 1)) }}</span>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $staff->full_name ?? ($staff->first_name . ' ' . $staff->last_name) }}</h3>
                @if($staff->user)
                    <p class="text-sm text-gray-600">{{ $staff->user->email }}</p>
                @endif
                @if($staff->staff_id)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold mt-2 bg-yellow-100 text-yellow-800">
                        ID: {{ $staff->staff_id }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST" id="editStaffForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- User Account (Read-only) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-link mr-1 text-sm text-yellow-600'></i>
                        User Account
                    </label>
                    <div class="px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg">
                        @if($staff->user)
                            <span class="text-gray-900 font-medium">{{ $staff->user->name }} ({{ $staff->user->email }})</span>
                        @else
                            <span class="text-gray-500">No user account linked</span>
                        @endif
                    </div>
                </div>

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-user mr-1 text-sm text-yellow-600'></i>
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="first_name" 
                           name="first_name" 
                           value="{{ old('first_name', $staff->first_name) }}"
                           required
                           autofocus
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition @error('first_name') border-red-500 ring-red-200 @enderror"
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
                        <i class='bx bx-user mr-1 text-sm text-yellow-600'></i>
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="last_name" 
                           name="last_name" 
                           value="{{ old('last_name', $staff->last_name) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition @error('last_name') border-red-500 ring-red-200 @enderror"
                           placeholder="Enter last name">
                    @error('last_name')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class='bx bx-error-circle mr-1'></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-phone mr-1 text-sm text-yellow-600'></i>
                        Phone
                    </label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $staff->phone) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                           placeholder="Enter phone number">
                </div>

                <!-- Position -->
                <div>
                    <label for="position" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-briefcase mr-1 text-sm text-yellow-600'></i>
                        Position
                    </label>
                    <input type="text" 
                           id="position" 
                           name="position" 
                           value="{{ old('position', $staff->position) }}"
                           placeholder="e.g., Receptionist, Nurse, Administrator"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                </div>

                <!-- Department -->
                <div>
                    <label for="department" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-building mr-1 text-sm text-yellow-600'></i>
                        Department
                    </label>
                    <input type="text" 
                           id="department" 
                           name="department" 
                           value="{{ old('department', $staff->department) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                           placeholder="Enter department">
                </div>

                <!-- Hire Date -->
                <div>
                    <label for="hire_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-calendar mr-1 text-sm text-yellow-600'></i>
                        Hire Date
                    </label>
                    <input type="date" 
                           id="hire_date" 
                           name="hire_date" 
                           value="{{ old('hire_date', $staff->hire_date?->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class='bx bx-file-blank mr-1 text-sm text-yellow-600'></i>
                        Notes
                    </label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"
                              placeholder="Enter any additional notes">{{ old('notes', $staff->notes) }}</textarea>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('admin.staff.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                    <i class='bx bx-x mr-2 text-base'></i>
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 transition shadow-md hover:shadow-lg">
                    <i class='bx bx-save mr-2 text-base'></i>
                    Update Staff
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
