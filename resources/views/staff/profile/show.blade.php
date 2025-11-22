@extends('layouts.staff')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Profile Information</h3>
            <a href="{{ route('staff.profile.edit') }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                <i class='bx bx-edit mr-2'></i> Edit Profile
            </a>
        </div>

        <!-- Profile Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="h-20 w-20 rounded-full bg-yellow-100 flex items-center justify-center">
                            <span class="text-yellow-600 font-bold text-2xl">{{ strtoupper(substr($staff->first_name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $staff->full_name }}</h2>
                            <p class="text-yellow-600 font-semibold">{{ $staff->staff_id ?? 'N/A' }}</p>
                            @if($staff->position)
                                <p class="text-gray-600">{{ $staff->position }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Info -->
                <div class="space-y-4">
                    @if($staff->position)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Position</label>
                        <p class="mt-1 text-gray-900">{{ $staff->position }}</p>
                    </div>
                    @endif
                    @if($staff->department)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Department</label>
                        <p class="mt-1 text-gray-900">{{ $staff->department }}</p>
                    </div>
                    @endif
                    @if($staff->user)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email (Login)</label>
                        <p class="mt-1 text-gray-900">{{ $staff->user->email }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Phone</label>
                    <p class="mt-1 text-gray-900">{{ $staff->phone ?? 'N/A' }}</p>
                </div>
                @if($staff->hire_date)
                <div>
                    <label class="text-sm font-medium text-gray-500">Hire Date</label>
                    <p class="mt-1 text-gray-900">{{ $staff->hire_date->format('M d, Y') }}</p>
                </div>
                @endif
            </div>

            <!-- Notes -->
            @if($staff->notes)
            <div class="mt-8">
                <label class="text-sm font-medium text-gray-500">Notes</label>
                <p class="mt-1 text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $staff->notes }}</p>
            </div>
            @endif

            <!-- Change Password -->
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h4 class="font-semibold text-gray-900 mb-4">Change Password</h4>
                <form action="{{ route('staff.profile.update-password') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                            <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition">
                            <i class='bx bx-lock mr-2'></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

