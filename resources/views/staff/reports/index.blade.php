@extends('layouts.staff')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class='bx bx-bar-chart-alt-2 text-3xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Reports & Analytics</h1>
                        <p class="text-indigo-100 text-sm mt-1">View clinic statistics and performance metrics</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-sm">
                        <i class='bx bx-calendar mr-1'></i>
                        {{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-2 mb-4">
                <i class='bx bx-filter-alt text-indigo-500 text-xl'></i>
                <h3 class="font-semibold text-gray-700">Filter by Date Range</h3>
            </div>
            <form method="GET" action="{{ route('staff.reports.index') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[150px]">
                    <label for="start_date" class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label for="end_date" class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-medium rounded-xl hover:from-indigo-600 hover:to-purple-600 transition shadow-sm">
                    <i class='bx bx-filter-alt mr-2'></i> Apply Filter
                </button>
                <a href="{{ route('staff.reports.index') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">
                    <i class='bx bx-reset mr-2'></i> Reset
                </a>
            </form>
        </div>

        <!-- Overall Statistics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-user text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalPatients) }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Patients</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-user-circle text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalDoctors) }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Doctors</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-list-ul text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalServices) }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Services</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-calendar text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalAppointments) }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Appointments</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Statistics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class='bx bx-calendar-check text-indigo-500'></i>
                    Appointment Statistics
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 text-center">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <i class='bx bx-time text-white text-xl'></i>
                        </div>
                        <p class="text-2xl font-bold text-blue-700">{{ number_format($scheduledAppointments) }}</p>
                        <p class="text-xs text-blue-600 uppercase tracking-wide mt-1">Scheduled</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <i class='bx bx-check-circle text-white text-xl'></i>
                        </div>
                        <p class="text-2xl font-bold text-green-700">{{ number_format($confirmedAppointments) }}</p>
                        <p class="text-xs text-green-600 uppercase tracking-wide mt-1">Confirmed</p>
                    </div>
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 text-center">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <i class='bx bx-check-double text-white text-xl'></i>
                        </div>
                        <p class="text-2xl font-bold text-purple-700">{{ number_format($completedAppointments) }}</p>
                        <p class="text-xs text-purple-600 uppercase tracking-wide mt-1">Completed</p>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-4 text-center">
                        <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <i class='bx bx-x text-white text-xl'></i>
                        </div>
                        <p class="text-2xl font-bold text-red-700">{{ number_format($cancelledAppointments) }}</p>
                        <p class="text-xs text-red-600 uppercase tracking-wide mt-1">Cancelled</p>
                    </div>
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 text-center">
                        <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <i class='bx bx-calendar-check text-white text-xl'></i>
                        </div>
                        <p class="text-2xl font-bold text-amber-700">{{ number_format($todayAppointments) }}</p>
                        <p class="text-xs text-amber-600 uppercase tracking-wide mt-1">Today</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="bg-gradient-to-r from-emerald-500 via-green-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class='bx bx-dollar-circle text-4xl'></i>
                    </div>
                    <div>
                        <p class="text-emerald-100 text-sm font-medium">Total Revenue (Completed Appointments)</p>
                        <p class="text-4xl font-bold mt-1">{{ get_currency_symbol() }}{{ number_format($revenue, 2) }}</p>
                        <p class="text-emerald-100 text-sm mt-2 flex items-center gap-1">
                            <i class='bx bx-calendar'></i>
                            {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2">
                        <span class="text-sm">{{ $completedAppointments }} completed appointments</span>
                    </div>
                    @if($completedAppointments > 0)
                        <div class="text-emerald-100 text-sm">
                            Avg: {{ get_currency_symbol() }}{{ number_format($revenue / $completedAppointments, 2) }} per appointment
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection