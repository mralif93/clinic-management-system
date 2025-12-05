@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-700 text-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between gap-4">
            <div class="space-y-1">
                <p class="text-sm font-medium text-primary-100">Welcome back</p>
                <h3 class="text-2xl font-bold">Hello, {{ Auth::user()->name }}!</h3>
                <p class="text-sm text-primary-100">Here’s what’s happening with your clinic today.</p>
            </div>
            <div class="text-6xl opacity-20">
                <i class='bx bx-clinic'></i>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-900">0</p>
                </div>
                <div class="bg-primary-50 text-primary-700 p-4 rounded-full">
                    <i class='bx bx-user text-3xl'></i>
                </div>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Appointments</p>
                    <p class="text-3xl font-bold text-gray-900">0</p>
                </div>
                <div class="bg-success-50 text-success-600 p-4 rounded-full">
                    <i class='bx bx-calendar text-3xl'></i>
                </div>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Doctors</p>
                    <p class="text-3xl font-bold text-gray-900">0</p>
                </div>
                <div class="bg-primary-50 text-primary-700 p-4 rounded-full">
                    <i class='bx bx-user-circle text-3xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white border border-gray-100 rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
            <span class="text-xs font-medium text-gray-500">Last 7 days</span>
        </div>
        <div class="p-6">
            <p class="text-gray-500 text-center py-8">No recent activity to display.</p>
        </div>
    </div>
</div>
@endsection

