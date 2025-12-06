@extends('layouts.doctor')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-3xl font-bold">
                        {{ strtoupper(substr($doctor->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ $doctor->full_name }}</h1>
                        <div class="flex flex-wrap items-center gap-2 mt-2">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-white/20 backdrop-blur rounded-full text-sm">
                                <i class='bx bx-id-card'></i> {{ $doctor->doctor_id ?? 'N/A' }}
                            </span>
                            @if($doctor->qualification)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-white/20 backdrop-blur rounded-full text-sm">
                                    <i class='bx bx-award'></i> {{ $doctor->qualification }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{ route('doctor.profile.edit') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-white/20 backdrop-blur text-white font-medium rounded-xl hover:bg-white/30 transition">
                    <i class='bx bx-edit mr-2'></i> Edit Profile
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Status & Type Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-badge-check text-emerald-500'></i> Status Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-0 divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Availability</label>
                            @if($doctor->is_available)
                                <span class="px-3 py-1 inline-flex items-center text-sm font-semibold rounded-lg bg-emerald-100 text-emerald-700">
                                    <i class='bx bx-check-circle mr-1'></i> Available
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex items-center text-sm font-semibold rounded-lg bg-amber-100 text-amber-700">
                                    <i class='bx bx-x-circle mr-1'></i> Unavailable
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Type</label>
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-lg bg-blue-100 text-blue-700">
                                {{ ucfirst($doctor->type) }}
                            </span>
                        </div>
                        @if($doctor->user)
                            <div class="flex items-center justify-between py-3.5">
                                <label class="text-sm font-medium text-gray-500">Email (Login)</label>
                                <p class="text-gray-900">{{ $doctor->user->email }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-phone text-blue-500'></i> Contact Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-0 divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900">{{ $doctor->email }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Phone</label>
                            <p class="text-gray-900">{{ $doctor->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Information Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-briefcase text-purple-500'></i> Professional Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-0 divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Specialization</label>
                            <p class="text-gray-900">{{ $doctor->specialization ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Qualification</label>
                            <p class="text-gray-900">{{ $doctor->qualification ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bio Card -->
            @if($doctor->bio)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-user text-amber-500'></i> Bio
                    </h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-700 leading-relaxed">{{ $doctor->bio }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Change Password Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-lock-alt text-red-500'></i> Change Password
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('doctor.profile.update-password') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                            <input type="password" name="current_password" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition shadow-sm">
                            <i class='bx bx-lock mr-2'></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection