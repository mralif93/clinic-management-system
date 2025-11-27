@extends('layouts.public')

@section('title', 'My Profile')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
                    <p class="text-gray-600 mt-1">View your personal information</p>
                </div>
                <a href="{{ route('patient.profile.edit') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class='bx bx-edit mr-2 text-base'></i>
                    Edit Profile
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <!-- Profile Header -->
                <div class="px-6 py-8 bg-gradient-to-r from-blue-500 to-indigo-600">
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-20 h-20 rounded-full bg-white flex items-center justify-center text-3xl font-bold text-blue-600 shadow-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="text-white">
                            <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                            <p class="text-blue-100">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="p-6 space-y-6">
                    <!-- Personal Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Full Name</label>
                                <p class="text-gray-900">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Email Address</label>
                                <p class="text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Phone Number</label>
                                <p class="text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Date of Birth</label>
                                <p class="text-gray-900">
                                    {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('M d, Y') : 'Not provided' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Gender</label>
                                <p class="text-gray-900">{{ $user->gender ? ucfirst($user->gender) : 'Not provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Member Since</label>
                                <p class="text-gray-900">{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Address</h3>
                        <p class="text-gray-900">{{ $user->address ?? 'No address provided' }}</p>
                    </div>

                    <!-- Account Information -->
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Account Type</label>
                                <p class="text-gray-900">{{ ucfirst($user->role) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Account Status</label>
                                <span
                                    class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection