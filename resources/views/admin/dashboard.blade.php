@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Banner -->
        <div
            class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 text-white rounded-xl shadow-lg p-6">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute -right-5 -bottom-10 w-32 h-32 bg-indigo-400/20 rounded-full blur-xl"></div>
            <div class="relative flex items-center justify-between gap-4">
                <div class="space-y-2">
                    <p class="text-sm font-medium text-blue-100 flex items-center gap-2">
                        <i class='bx bx-sun text-yellow-300 animate-pulse'></i>
                        {{ now()->format('l, F j, Y') }}
                    </p>
                    <h3 class="text-2xl md:text-3xl font-bold">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="text-sm text-blue-100 max-w-md">Here's what's happening with your clinic today. Stay on top of
                        appointments, patients, and staff activities.</p>
                </div>
                <div class="hidden md:block">
                    <div class="text-8xl opacity-20">
                        <i class='bx bx-clinic'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Total Patients -->
            <div
                class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Patients</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalPatients) }}</p>
                        @if($newPatientsThisMonth > 0)
                            <p class="text-xs text-green-600 font-medium flex items-center gap-1">
                                <i class='bx bx-trending-up'></i>
                                +{{ $newPatientsThisMonth }} this month
                            </p>
                        @else
                            <p class="text-xs text-gray-400">No new patients this month</p>
                        @endif
                    </div>
                    <div
                        class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                        <i class='bx bx-user text-2xl'></i>
                    </div>
                </div>
            </div>

            <!-- Total Appointments -->
            <div
                class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Appointments</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalAppointments) }}</p>
                        <p class="text-xs text-blue-600 font-medium flex items-center gap-1">
                            <i class='bx bx-calendar'></i>
                            {{ $todayAppointments }} scheduled today
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white p-4 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                        <i class='bx bx-calendar-check text-2xl'></i>
                    </div>
                </div>
            </div>

            <!-- Total Doctors -->
            <div
                class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Doctors</p>
                        <p class="text-3xl font-bold text-gray-900">{{ number_format($totalDoctors) }}</p>
                        <p class="text-xs text-green-600 font-medium flex items-center gap-1">
                            <i class='bx bx-check-circle'></i>
                            {{ $availableDoctors }} available now
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-violet-500 to-violet-600 text-white p-4 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                        <i class='bx bx-plus-circle text-2xl'></i>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div
                class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Monthly Revenue</p>
                        <p class="text-3xl font-bold text-gray-900">
                            {{ get_currency_symbol() }}{{ number_format($monthlyRevenue, 2) }}</p>
                        @if($pendingPayments > 0)
                            <p class="text-xs text-amber-600 font-medium flex items-center gap-1">
                                <i class='bx bx-time'></i>
                                {{ get_currency_symbol() }}{{ number_format($pendingPayments, 2) }} pending
                            </p>
                        @else
                            <p class="text-xs text-green-600 font-medium">All payments collected</p>
                        @endif
                    </div>
                    <div
                        class="bg-gradient-to-br from-amber-500 to-orange-500 text-white p-4 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                        <i class='bx bx-money text-2xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Today's Appointments Card -->
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-calendar text-blue-600'></i>
                            Today's Appointments
                        </h3>
                        <span class="text-2xl font-bold text-blue-600">{{ $todayAppointments }}</span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                Scheduled
                            </span>
                            <span
                                class="text-sm font-semibold text-gray-800">{{ $todayAppointmentsByStatus['scheduled'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center gap-2">
                                <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                                Confirmed
                            </span>
                            <span
                                class="text-sm font-semibold text-gray-800">{{ $todayAppointmentsByStatus['confirmed'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Completed
                            </span>
                            <span
                                class="text-sm font-semibold text-gray-800">{{ $todayAppointmentsByStatus['completed'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center gap-2">
                                <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                Cancelled
                            </span>
                            <span
                                class="text-sm font-semibold text-gray-800">{{ $todayAppointmentsByStatus['cancelled'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.appointments.index') }}"
                            class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                            View all appointments
                            <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Staff Attendance Card -->
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-time text-green-600'></i>
                            Staff Attendance
                        </h3>
                        <span
                            class="text-2xl font-bold text-green-600">{{ $todayAttendance['present'] + $todayAttendance['late'] }}/{{ $totalStaffExpected }}</span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Present
                            </span>
                            <span class="text-sm font-semibold text-gray-800">{{ $todayAttendance['present'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center gap-2">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                Late
                            </span>
                            <span class="text-sm font-semibold text-gray-800">{{ $todayAttendance['late'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center gap-2">
                                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                                On Leave
                            </span>
                            <span class="text-sm font-semibold text-gray-800">{{ $todayAttendance['on_leave'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center gap-2">
                                <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                                Not Checked In
                            </span>
                            <span
                                class="text-sm font-semibold text-gray-800">{{ $todayAttendance['not_checked_in'] ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.attendance.index') }}"
                            class="text-sm text-green-600 hover:text-green-700 font-medium flex items-center gap-1">
                            View attendance
                            <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tasks & Alerts Card -->
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-amber-50 to-orange-50">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-task text-amber-600'></i>
                            Tasks & Alerts
                        </h3>
                        @if($overdueTodos > 0)
                            <span class="bg-red-100 text-red-700 text-xs font-semibold px-2 py-1 rounded-full">
                                {{ $overdueTodos }} overdue
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center gap-2">
                                <i class='bx bx-calendar-exclamation text-amber-500'></i>
                                Pending Leave Requests
                            </span>
                            <span
                                class="text-sm font-semibold bg-amber-100 text-amber-700 px-2 py-0.5 rounded">{{ $pendingLeaves->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center gap-2">
                                <i class='bx bx-list-check text-blue-500'></i>
                                Active Tasks
                            </span>
                            <span
                                class="text-sm font-semibold bg-blue-100 text-blue-700 px-2 py-0.5 rounded">{{ $pendingTodos->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 flex items-center gap-2">
                                <i class='bx bx-error-circle text-red-500'></i>
                                Overdue Tasks
                            </span>
                            <span
                                class="text-sm font-semibold bg-red-100 text-red-700 px-2 py-0.5 rounded">{{ $overdueTodos }}</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center gap-3">
                        <a href="{{ route('admin.leaves.index') }}"
                            class="text-sm text-amber-600 hover:text-amber-700 font-medium flex items-center gap-1">
                            Leaves
                            <i class='bx bx-right-arrow-alt'></i>
                        </a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('admin.todos.index') }}"
                            class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                            Tasks
                            <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
            <!-- Appointment Status Chart -->
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <i class='bx bx-pie-chart-alt-2 text-indigo-600'></i>
                        Appointment Status Overview
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center">
                        <div class="relative w-48 h-48">
                            @php
                                $total = array_sum($appointmentsByStatus);
                                $scheduled = $appointmentsByStatus['scheduled'] ?? 0;
                                $confirmed = $appointmentsByStatus['confirmed'] ?? 0;
                                $completed = $appointmentsByStatus['completed'] ?? 0;
                                $cancelled = $appointmentsByStatus['cancelled'] ?? 0;
                            @endphp

                            @if($total > 0)
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                    @php
                                        $offset = 0;
                                        $colors = ['#3b82f6', '#6366f1', '#22c55e', '#ef4444'];
                                        $values = [$scheduled, $confirmed, $completed, $cancelled];
                                    @endphp
                                    @foreach($values as $index => $value)
                                        @if($value > 0)
                                            @php
                                                $percentage = ($value / $total) * 100;
                                                $dashArray = $percentage * 2.51327;
                                                $dashOffset = -$offset * 2.51327;
                                            @endphp
                                            <circle cx="50" cy="50" r="40" fill="none" stroke="{{ $colors[$index] }}" stroke-width="20"
                                                stroke-dasharray="{{ $dashArray }} 251.327" stroke-dashoffset="{{ $dashOffset }}"
                                                class="transition-all duration-500" />
                                            @php $offset += $percentage; @endphp
                                        @endif
                                    @endforeach
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <p class="text-2xl font-bold text-gray-800">{{ $total }}</p>
                                        <p class="text-xs text-gray-500">Total</p>
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50 rounded-full">
                                    <p class="text-gray-400 text-sm">No data</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                            <span class="text-xs text-gray-600">Scheduled: {{ $scheduled }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-indigo-500 rounded-full"></span>
                            <span class="text-xs text-gray-600">Confirmed: {{ $confirmed }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                            <span class="text-xs text-gray-600">Completed: {{ $completed }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                            <span class="text-xs text-gray-600">Cancelled: {{ $cancelled }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-line-chart text-green-600'></i>
                            Revenue (Last 7 Days)
                        </h3>
                        <span
                            class="text-sm font-bold text-green-600">{{ get_currency_symbol() }}{{ number_format($todayRevenue, 2) }}
                            today</span>
                    </div>
                </div>
                <div class="p-6">
                    @php
                        $revenueValues = array_column($revenueData, 'revenue');
                        $maxRevenue = !empty($revenueValues) ? max($revenueValues) : 1;
                        if ($maxRevenue == 0) {
                            $maxRevenue = 1; // Prevent division by zero
                        }
                    @endphp
                    <div class="flex items-end justify-between gap-2 h-40">
                        @foreach($revenueData as $data)
                            <div class="flex-1 flex flex-col items-center gap-2">
                                <div class="w-full bg-gray-100 rounded-t-lg relative" style="height: 120px;">
                                    <div class="absolute bottom-0 w-full bg-gradient-to-t from-green-500 to-emerald-400 rounded-t-lg transition-all duration-500 hover:from-green-600 hover:to-emerald-500"
                                        style="height: {{ ($maxRevenue > 0 ? ($data['revenue'] / $maxRevenue) * 100 : 0) }}%;"
                                        title="{{ get_currency_symbol() }}{{ number_format($data['revenue'], 2) }}"></div>
                                </div>
                                <span class="text-xs text-gray-500 font-medium">{{ $data['day'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">This Month Total:</span>
                            <span
                                class="font-bold text-gray-800">{{ get_currency_symbol() }}{{ number_format($monthlyRevenue, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
            <!-- Upcoming Appointments -->
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-calendar-event text-blue-600'></i>
                            Upcoming Appointments
                        </h3>
                        <a href="{{ route('admin.appointments.index') }}"
                            class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                            View All
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    @if($upcomingAppointments->count() > 0)
                        <table class="w-full min-w-[600px] sm:min-w-0">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Patient</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Doctor</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date & Time
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($upcomingAppointments as $appointment)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3">
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ ($appointment->patient && $appointment->patient->full_name) ? $appointment->patient->full_name : 'N/A' }}</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-sm text-gray-600">Dr. {{ ($appointment->doctor && $appointment->doctor->full_name) ? $appointment->doctor->full_name : 'N/A' }}</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-sm text-gray-800">{{ $appointment->appointment_date ? $appointment->appointment_date->format('M d') : 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $appointment->appointment_time ? \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') : 'N/A' }}</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full
                                                        {{ $appointment->status === 'scheduled' ? 'bg-blue-100 text-blue-700' : '' }}
                                                        {{ $appointment->status === 'confirmed' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                                    ">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-8 text-center">
                            <i class='bx bx-calendar-minus text-4xl text-gray-300 mb-2'></i>
                            <p class="text-sm text-gray-500">No upcoming appointments</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pending Leave Requests -->
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <i class='bx bx-calendar-check text-amber-600'></i>
                            Pending Leave Requests
                        </h3>
                        <a href="{{ route('admin.leaves.index') }}"
                            class="text-xs text-amber-600 hover:text-amber-700 font-medium">
                            View All
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    @if($pendingLeaves->count() > 0)
                        <table class="w-full min-w-[600px] sm:min-w-0">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Employee</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Duration</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($pendingLeaves as $leave)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3">
                                            <p class="text-sm font-medium text-gray-800">{{ ($leave->user && $leave->user->name) ? $leave->user->name : 'N/A' }}</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $leave->type_color }}">
                                                {{ ucfirst($leave->leave_type) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-sm text-gray-800">
                                                {{ $leave->start_date ? $leave->start_date->format('M d') : 'N/A' }} -
                                                {{ $leave->end_date ? $leave->end_date->format('M d') : 'N/A' }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $leave->total_days ?? 0 }} day(s)</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.leaves.show', $leave) }}"
                                                class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                                                Review
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-8 text-center">
                            <i class='bx bx-check-circle text-4xl text-green-300 mb-2'></i>
                            <p class="text-sm text-gray-500">No pending leave requests</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                        <i class='bx bx-history text-gray-600'></i>
                        Recent Activity
                    </h3>
                    <span class="text-xs text-gray-500">Latest updates</span>
                </div>
            </div>
            <div class="p-4">
                @if($recentActivity->count() > 0)
                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                        <div class="space-y-4">
                            @foreach($recentActivity as $activity)
                                <div class="relative flex items-start gap-4 pl-10">
                                    <div class="absolute left-2 w-5 h-5 rounded-full flex items-center justify-center
                                                {{ $activity['color'] === 'blue' ? 'bg-blue-100 text-blue-600' : '' }}
                                                {{ $activity['color'] === 'green' ? 'bg-green-100 text-green-600' : '' }}
                                                {{ $activity['color'] === 'purple' ? 'bg-purple-100 text-purple-600' : '' }}
                                            ">
                                        <i class='bx {{ $activity['icon'] }} text-xs'></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800">{{ $activity['title'] ?? 'Activity' }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $activity['description'] ?? 'N/A' }}</p>
                                    </div>
                                    <span class="text-xs text-gray-400 whitespace-nowrap">
                                        {{ ($activity['time'] && method_exists($activity['time'], 'diffForHumans')) ? $activity['time']->diffForHumans() : 'N/A' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class='bx bx-history text-4xl text-gray-300 mb-2'></i>
                        <p class="text-sm text-gray-500">No recent activity to display</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .bg-grid-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
@endsection