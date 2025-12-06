@extends('layouts.staff')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
    <div class="space-y-6">
        <!-- Profile Header Card -->
        <div class="bg-gradient-to-r from-violet-500 via-purple-500 to-indigo-600 rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-20 h-20 md:w-24 md:h-24 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border-2 border-white/30">
                            <span class="text-white font-bold text-3xl md:text-4xl">{{ strtoupper(substr($staff->first_name, 0, 1)) }}{{ strtoupper(substr($staff->last_name, 0, 1)) }}</span>
                        </div>
                        <div class="text-white">
                            <h1 class="text-2xl md:text-3xl font-bold">{{ $staff->full_name }}</h1>
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm">
                                    <i class='bx bx-id-card'></i>
                                    {{ $staff->staff_id ?? 'N/A' }}
                                </span>
                                @if($staff->position)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm">
                                        <i class='bx bx-briefcase'></i>
                                        {{ $staff->position }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('staff.profile.edit') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-white text-purple-600 font-semibold rounded-xl hover:bg-purple-50 transition shadow-lg hover:shadow-xl">
                        <i class='bx bx-edit mr-2'></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-briefcase text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $staff->position ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Position</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-building text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $staff->department ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Department</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-calendar-check text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $staff->hire_date ? $staff->hire_date->format('M Y') : 'N/A' }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Hired</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-time text-white text-xl'></i>
                    </div>
                    <div>
                        @if($staff->hire_date)
                            @php
                                $years = $staff->hire_date->diffInYears(now());
                                $months = $staff->hire_date->diffInMonths(now()) % 12;
                            @endphp
                            <p class="text-sm font-semibold text-gray-900">{{ $years > 0 ? $years . 'y ' : '' }}{{ $months }}m</p>
                        @else
                            <p class="text-sm font-semibold text-gray-900">N/A</p>
                        @endif
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Tenure</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Personal Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Contact Details -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-user text-violet-500'></i>
                            Personal Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class='bx bx-user text-violet-600'></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Full Name</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $staff->full_name }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class='bx bx-envelope text-blue-600'></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Email</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $staff->user->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class='bx bx-phone text-green-600'></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Phone</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $staff->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class='bx bx-calendar text-amber-600'></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Hire Date</p>
                                    <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $staff->hire_date ? $staff->hire_date->format('F d, Y') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        @if($staff->notes)
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class='bx bx-note text-gray-600'></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">Notes</p>
                                        <p class="text-sm text-gray-700 mt-1 bg-gray-50 rounded-lg p-3">{{ $staff->notes }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Change Password Section -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-lock-alt text-violet-500'></i>
                            Change Password
                        </h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('staff.profile.update-password') }}" method="POST" id="passwordForm">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Current Password</label>
                                    <div class="relative">
                                        <i class='bx bx-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                                        <input type="password" name="current_password" required
                                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                            placeholder="••••••••">
                                    </div>
                                    @error('current_password')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">New Password</label>
                                    <div class="relative">
                                        <i class='bx bx-lock-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                                        <input type="password" name="password" required
                                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                            placeholder="••••••••">
                                    </div>
                                    @error('password')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Confirm Password</label>
                                    <div class="relative">
                                        <i class='bx bx-check-shield absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                                        <input type="password" name="password_confirmation" required
                                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                                            placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end">
                                <button type="submit"
                                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-violet-500 to-purple-600 text-white font-medium rounded-xl hover:from-violet-600 hover:to-purple-700 transition shadow-sm hover:shadow">
                                    <i class='bx bx-lock mr-2'></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Account Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-shield-quarter text-violet-500'></i>
                            Account Status
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                <span class="text-sm font-medium text-green-700">Active</span>
                            </div>
                            <i class='bx bx-check-circle text-green-500 text-xl'></i>
                        </div>
                        <div class="text-center py-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-violet-400 to-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <span class="text-white font-bold text-xl">{{ strtoupper(substr($staff->first_name, 0, 1)) }}{{ strtoupper(substr($staff->last_name, 0, 1)) }}</span>
                            </div>
                            <p class="text-sm font-semibold text-gray-900">{{ $staff->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $staff->user->email ?? '' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-zap text-violet-500'></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="p-4 space-y-2">
                        <a href="{{ route('staff.profile.edit') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-violet-50 transition group">
                            <div class="w-10 h-10 bg-violet-100 rounded-lg flex items-center justify-center group-hover:bg-violet-200 transition">
                                <i class='bx bx-edit text-violet-600'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Edit Profile</p>
                                <p class="text-xs text-gray-500">Update your information</p>
                            </div>
                        </a>
                        <a href="{{ route('staff.attendance.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 transition group">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition">
                                <i class='bx bx-time-five text-blue-600'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">My Attendance</p>
                                <p class="text-xs text-gray-500">View attendance records</p>
                            </div>
                        </a>
                        <a href="{{ route('staff.leaves.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-amber-50 transition group">
                            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center group-hover:bg-amber-200 transition">
                                <i class='bx bx-calendar-event text-amber-600'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">My Leaves</p>
                                <p class="text-xs text-gray-500">Manage leave requests</p>
                            </div>
                        </a>
                        <a href="{{ route('staff.payslips.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-green-50 transition group">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition">
                                <i class='bx bx-receipt text-green-600'></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">My Payslips</p>
                                <p class="text-xs text-gray-500">View salary statements</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection