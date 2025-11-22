@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                <p class="text-blue-100">Here's what's happening with your clinic today.</p>
            </div>
            <div class="text-6xl opacity-20">
                <i class='bx bx-clinic'></i>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <i class='bx bx-user text-3xl text-blue-600'></i>
                </div>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Appointments</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <i class='bx bx-calendar text-3xl text-green-600'></i>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Doctors</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">0</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <i class='bx bx-user-circle text-3xl text-purple-600'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
        </div>
        <div class="p-6">
            <p class="text-gray-500 text-center py-8">No recent activity to display.</p>
        </div>
    </div>
</div>
@endsection

