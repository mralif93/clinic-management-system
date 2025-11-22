@extends('layouts.staff')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')

@section('content')
<div class="space-y-6">
    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('staff.reports.index') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" 
                       id="start_date" 
                       name="start_date" 
                       value="{{ $startDate->format('Y-m-d') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" 
                       id="end_date" 
                       name="end_date" 
                       value="{{ $endDate->format('Y-m-d') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            </div>
            <button type="submit" class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition">
                <i class='bx bx-filter mr-2'></i> Filter
            </button>
            <a href="{{ route('staff.reports.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                Reset
            </a>
        </form>
    </div>

    <!-- Overall Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalPatients) }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <i class='bx bx-user text-3xl text-blue-600'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Doctors</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalDoctors) }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <i class='bx bx-user-circle text-3xl text-green-600'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Services</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalServices) }}</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <i class='bx bx-list-ul text-3xl text-purple-600'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Appointments</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalAppointments) }}</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-full">
                    <i class='bx bx-calendar text-3xl text-yellow-600'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Scheduled</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($scheduledAppointments) }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <i class='bx bx-time text-3xl text-blue-600'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Confirmed</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($confirmedAppointments) }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <i class='bx bx-check-circle text-3xl text-green-600'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($completedAppointments) }}</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <i class='bx bx-check text-3xl text-purple-600'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Cancelled</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($cancelledAppointments) }}</p>
                </div>
                <div class="bg-red-100 p-4 rounded-full">
                    <i class='bx bx-x text-3xl text-red-600'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today's</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($todayAppointments) }}</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-full">
                    <i class='bx bx-calendar-check text-3xl text-yellow-600'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Revenue (Completed Appointments)</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ get_currency_symbol() }}{{ number_format($revenue, 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">Period: {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}</p>
            </div>
            <div class="bg-green-100 p-4 rounded-full">
                <i class='bx bx-dollar text-3xl text-green-600'></i>
            </div>
        </div>
    </div>
</div>
@endsection

