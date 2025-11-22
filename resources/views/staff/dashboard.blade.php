@extends('layouts.staff')

@section('title', 'Staff Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                <p class="text-yellow-100">Manage appointments and assist patients from here.</p>
                @if(Auth::user()->staff)
                    <p class="text-yellow-100 mt-2">ID: {{ Auth::user()->staff->staff_id }} | Position: {{ Auth::user()->staff->position ?? 'N/A' }}</p>
                @endif
            </div>
            <div class="text-6xl opacity-20">
                <i class='bx bx-group'></i>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Appointments -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Appointments</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalAppointments }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <i class='bx bx-calendar text-3xl text-blue-600'></i>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today's Appointments</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $todayAppointments }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <i class='bx bx-time text-3xl text-green-600'></i>
                </div>
            </div>
        </div>

        <!-- Upcoming -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Upcoming</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $upcomingAppointments }}</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <i class='bx bx-calendar-check text-3xl text-purple-600'></i>
                </div>
            </div>
        </div>

        <!-- Total Patients -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalPatients }}</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-full">
                    <i class='bx bx-user text-3xl text-yellow-600'></i>
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
                <a href="#" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-yellow-500 hover:bg-yellow-50 transition">
                    <i class='bx bx-calendar-plus text-3xl text-yellow-600 mb-2'></i>
                    <span class="text-sm font-medium text-gray-700">Book Appointment</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition">
                    <i class='bx bx-user text-3xl text-blue-600 mb-2'></i>
                    <span class="text-sm font-medium text-gray-700">Manage Patients</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition">
                    <i class='bx bx-user-circle text-3xl text-purple-600 mb-2'></i>
                    <span class="text-sm font-medium text-gray-700">My Profile</span>
                </a>
                <a href="#" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition">
                    <i class='bx bx-file text-3xl text-orange-600 mb-2'></i>
                    <span class="text-sm font-medium text-gray-700">Reports</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Today's Appointments</h3>
        </div>
        <div class="p-6">
            <p class="text-gray-500 text-center py-8">No appointments scheduled for today.</p>
        </div>
    </div>
</div>
@endsection

