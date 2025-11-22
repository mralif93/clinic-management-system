@extends('layouts.public')

@section('title', 'My Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Logout -->
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">Patient Dashboard</h1>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-2 text-sm text-white bg-red-500 rounded-lg hover:bg-red-600 transition">
                        <i class='bx bx-log-out mr-2'></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                <p class="text-blue-100">Manage your appointments and profile from here.</p>
            </div>
            <div class="text-6xl opacity-20">
                <i class='bx bx-user-circle'></i>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- My Appointments -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">My Appointments</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <i class='bx bx-calendar text-3xl text-blue-600'></i>
                </div>
            </div>
        </div>

        <!-- Upcoming -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Upcoming</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <i class='bx bx-time text-3xl text-green-600'></i>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <i class='bx bx-check-circle text-3xl text-purple-600'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="#" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                    <i class='bx bx-calendar-plus text-3xl text-blue-600 mb-2'></i>
                    <span class="text-sm font-medium text-gray-700">Book Appointment</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition">
                    <i class='bx bx-history text-3xl text-green-600 mb-2'></i>
                    <span class="text-sm font-medium text-gray-700">View History</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition">
                    <i class='bx bx-user text-3xl text-purple-600 mb-2'></i>
                    <span class="text-sm font-medium text-gray-700">My Profile</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition">
                    <i class='bx bx-file text-3xl text-orange-600 mb-2'></i>
                    <span class="text-sm font-medium text-gray-700">My Records</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Appointments</h3>
        </div>
        <div class="p-6">
            <p class="text-gray-500 text-center py-8">No appointments found. <a href="#" class="text-blue-600 hover:underline">Book your first appointment</a></p>
        </div>
    </div>
        </div>
    </div>
</div>
@endsection

