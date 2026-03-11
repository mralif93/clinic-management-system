@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Banner -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-900 via-indigo-800 to-violet-900 text-white rounded-3xl shadow-2xl p-8 mb-8 border border-white/10 group">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-700"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

            <div class="relative flex flex-col md:flex-row items-center justify-between gap-8 text-center md:text-left">
                <div class="flex items-center gap-6">
                    <div class="shrink-0 w-16 h-16 md:w-20 md:h-20 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-inner border border-white/20 transform hover:scale-105 transition-all duration-300">
                        <i class='hgi-stroke hgi-hospital-01 text-4xl md:text-5xl text-white'></i>
                    </div>
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 backdrop-blur-md">
                            <i class='hgi-stroke hgi-sun-01 text-yellow-400 animate-pulse'></i>
                            <span class="text-[10px] font-bold tracking-wider uppercase opacity-90">{{ now()->format('l, F j, Y') }}</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                            Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-indigo-200">{{ Auth::user()->name }}!</span>
                        </h2>
                        <p class="text-sm text-indigo-100/80 max-w-md font-medium leading-relaxed">
                            Your clinic is performing excellently today. You have <span class="text-white font-bold">{{ $todayAppointments }} appointments</span> scheduled.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Patients -->
            <div
                class="group bg-white rounded-3xl shadow-sm hover:shadow-premium border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="p-2.5 rounded-2xl bg-primary-50 text-primary-600 inline-flex shadow-sm">
                            <i class='hgi-stroke hgi-user-group text-2xl'></i>
                        </div>
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.1em] text-gray-400 mb-1">Total Patients
                            </p>
                            <h4 class="text-3xl font-black text-gray-900">{{ number_format($totalPatients) }}</h4>
                        </div>
                        @if($newPatientsThisMonth > 0)
                            <div
                                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-success-50 text-success-600 text-[10px] font-bold">
                                <i class='hgi-stroke hgi-arrow-up-01'></i>
                                <span>+{{ $newPatientsThisMonth }} this month</span>
                            </div>
                        @else
                            <p class="text-[11px] text-gray-400 font-medium">Standard growth rate</p>
                        @endif
                    </div>
                    <div
                        class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-primary-50 transition-colors">
                        <i
                            class='hgi-stroke hgi-user-add-01 text-gray-300 group-hover:text-primary-400 transition-colors'></i>
                    </div>
                </div>
            </div>

            <!-- Total Appointments -->
            <div
                class="group bg-white rounded-3xl shadow-sm hover:shadow-premium border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="p-2.5 rounded-2xl bg-indigo-50 text-indigo-600 inline-flex shadow-sm">
                            <i class='hgi-stroke hgi-calendar-03 text-2xl'></i>
                        </div>
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.1em] text-gray-400 mb-1">Total Bookings
                            </p>
                            <h4 class="text-3xl font-black text-gray-900">{{ number_format($totalAppointments) }}</h4>
                        </div>
                        <div
                            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold">
                            <i class='hgi-stroke hgi-clock-01'></i>
                            <span>{{ $todayAppointments }} today</span>
                        </div>
                    </div>
                    <div
                        class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-indigo-50 transition-colors">
                        <i
                            class='hgi-stroke hgi-calendar-add-01 text-gray-300 group-hover:text-indigo-400 transition-colors'></i>
                    </div>
                </div>
            </div>

            <!-- Total Doctors -->
            <div
                class="group bg-white rounded-3xl shadow-sm hover:shadow-premium border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="p-2.5 rounded-2xl bg-violet-50 text-violet-600 inline-flex shadow-sm">
                            <i class='hgi-stroke hgi-doctor-01 text-2xl'></i>
                        </div>
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.1em] text-gray-400 mb-1">Active Doctors
                            </p>
                            <h4 class="text-3xl font-black text-gray-900">{{ number_format($totalDoctors) }}</h4>
                        </div>
                        <div
                            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-violet-50 text-violet-600 text-[10px] font-bold">
                            <i class='hgi-stroke hgi-checkmark-circle-01'></i>
                            <span>{{ $availableDoctors }} available</span>
                        </div>
                    </div>
                    <div
                        class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-violet-50 transition-colors">
                        <i
                            class='hgi-stroke hgi-stethoscope-02 text-gray-300 group-hover:text-violet-400 transition-colors'></i>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div
                class="group bg-white rounded-3xl shadow-sm hover:shadow-premium border border-gray-100 p-6 transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-start justify-between">
                    <div class="space-y-3">
                        <div class="p-2.5 rounded-2xl bg-emerald-50 text-emerald-600 inline-flex shadow-sm">
                            <i class='hgi-stroke hgi-bank text-2xl'></i>
                        </div>
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.1em] text-gray-400 mb-1">Monthly Revenue
                            </p>
                            <h4 class="text-3xl font-black text-gray-900">
                                {{ get_currency_symbol() }}{{ number_format($monthlyRevenue, 2) }}</h4>
                        </div>
                        @if($pendingPayments > 0)
                            <div
                                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold">
                                <i class='hgi-stroke hgi-timer-01'></i>
                                <span>{{ number_format($pendingPayments, 2) }} pending</span>
                            </div>
                        @else
                            <div
                                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-bold">
                                <i class='hgi-stroke hgi-checkmark-badge-01'></i>
                                <span>Cleared</span>
                            </div>
                        @endif
                    </div>
                    <div
                        class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-emerald-50 transition-colors">
                        <i
                            class='hgi-stroke hgi-chart-increase text-gray-300 group-hover:text-emerald-400 transition-colors'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Today's Appointments Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-premium transition-all">
                    <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gradient-to-br from-indigo-50/50 to-transparent">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-500 text-white flex items-center justify-center shadow-lg shadow-indigo-200">
                                 <i class='hgi-stroke hgi-calendar-03 text-xl'></i>
                            </div>
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Daily Schedule</h3>
                        </div>
                        <span class="text-2xl font-black text-indigo-600">{{ $todayAppointments }}</span>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-500 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full shadow-[0_0_8px_rgba(99,102,241,0.6)]"></span>
                                    Scheduled
                                </span>
                                <span class="text-xs font-black text-gray-900 bg-gray-50 px-2 py-1 rounded-lg">{{ $todayAppointmentsByStatus['scheduled'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-500 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full shadow-[0_0_8px_rgba(59,130,246,0.6)]"></span>
                                    Confirmed
                                </span>
                                <span class="text-xs font-black text-gray-900 bg-gray-50 px-2 py-1 rounded-lg">{{ $todayAppointmentsByStatus['confirmed'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-500 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-success-500 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                                    Completed
                                </span>
                                <span class="text-xs font-black text-gray-900 bg-gray-50 px-2 py-1 rounded-lg">{{ $todayAppointmentsByStatus['completed'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-50">
                            <a href="{{ route('admin.appointments.index') }}"
                                class="group/btn flex items-center justify-center gap-2 w-full py-3 rounded-2xl bg-indigo-50 text-indigo-600 text-xs font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">
                                <span>Manage Bookings</span>
                                <i class='hgi-stroke hgi-arrow-right-01 group-hover/btn:translate-x-1 transition-transform'></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Staff Attendance Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-premium transition-all">
                    <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gradient-to-br from-emerald-50/50 to-transparent">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-200">
                                 <i class='hgi-stroke hgi-user-check-01 text-xl'></i>
                            </div>
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Presence Hub</h3>
                        </div>
                        <span class="text-2xl font-black text-emerald-600">{{ $todayAttendance['present'] + $todayAttendance['late'] }}/{{ $totalStaffExpected }}</span>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-500 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
                                    Present Officers
                                </span>
                                <span class="text-xs font-black text-gray-900 bg-gray-50 px-2 py-1 rounded-lg">{{ $todayAttendance['present'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-500 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full shadow-[0_0_8px_rgba(245,158,11,0.6)]"></span>
                                    Delayed
                                </span>
                                <span class="text-xs font-black text-gray-900 bg-gray-50 px-2 py-1 rounded-lg">{{ $todayAttendance['late'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-500 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-violet-500 rounded-full shadow-[0_0_8px_rgba(139,92,246,0.6)]"></span>
                                    Out of Office
                                </span>
                                <span class="text-xs font-black text-gray-900 bg-gray-50 px-2 py-1 rounded-lg">{{ $todayAttendance['on_leave'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-50">
                            <a href="{{ route('admin.attendance.index') }}"
                                class="group/btn flex items-center justify-center gap-2 w-full py-3 rounded-2xl bg-emerald-50 text-emerald-600 text-xs font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all">
                                <span>Review Logs</span>
                                <i class='hgi-stroke hgi-arrow-right-01 group-hover/btn:translate-x-1 transition-transform'></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tasks & Alerts Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-premium transition-all">
                    <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gradient-to-br from-amber-50/50 to-transparent">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-amber-500 text-white flex items-center justify-center shadow-lg shadow-amber-200">
                                 <i class='hgi-stroke hgi-notification-03 text-xl'></i>
                            </div>
                            <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Alert Center</h3>
                        </div>
                        @if($overdueTodos > 0)
                            <span class="px-2 py-1 rounded-lg bg-danger-50 text-danger-600 text-[10px] font-black uppercase animate-pulse">Critical</span>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-500 flex items-center gap-2">
                                    <i class='hgi-stroke hgi-calendar-03 text-amber-500'></i>
                                    Pending Leave
                                </span>
                                <span class="text-xs font-black text-gray-900 bg-gray-50 px-2 py-1 rounded-lg">{{ $pendingLeaves->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-500 flex items-center gap-2">
                                    <i class='hgi-stroke hgi-task-01 text-blue-500'></i>
                                    In Progress
                                </span>
                                <span class="text-xs font-black text-gray-900 bg-gray-50 px-2 py-1 rounded-lg">{{ $pendingTodos->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-gray-500 flex items-center gap-2">
                                    <i class='hgi-stroke hgi-alert-circle text-danger-500'></i>
                                    Overdue
                                </span>
                                <span class="text-xs font-black text-danger-600 bg-danger-50 px-2 py-1 rounded-lg">{{ $overdueTodos }}</span>
                            </div>
                        </div>
                        <div class="mt-6 pt-6 border-t border-gray-50 flex gap-3">
                            <a href="{{ route('admin.leaves.index') }}"
                                class="flex-1 flex items-center justify-center py-3 rounded-2xl bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-widest hover:bg-amber-600 hover:text-white transition-all">
                                Leaves
                            </a>
                            <a href="{{ route('admin.todos.index') }}"
                                class="flex-1 flex items-center justify-center py-3 rounded-2xl bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all">
                                Tasks
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
                            <i class='hgi-stroke hgi-pie-chart text-indigo-600'></i>
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
                                <i class='hgi-stroke hgi-chart-increase text-green-600'></i>
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
                                <i class='hgi-stroke hgi-calendar-03 text-blue-600'></i>
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
                                <i class='hgi-stroke hgi-calendar-03 text-4xl text-gray-300 mb-2'></i>
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
                                <i class='hgi-stroke hgi-calendar-03 text-amber-600'></i>
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
                                <i class='hgi-stroke hgi-checkmark-circle-02 text-4xl text-green-300 mb-2'></i>
                                <p class="text-sm text-gray-500">No pending leave requests</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-premium transition-all">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gradient-to-br from-gray-50/50 to-transparent">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-gray-900 text-white flex items-center justify-center shadow-lg shadow-gray-200">
                             <i class='hgi-stroke hgi-activity-01 text-xl'></i>
                        </div>
                        <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Dynamic Activity Feed</h3>
                    </div>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Real-time Updates</span>
                </div>
                <div class="p-6">
                    @if($recentActivity->count() > 0)
                        <div class="relative">
                            <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-gray-50"></div>
                            <div class="space-y-6">
                                @foreach($recentActivity as $activity)
                                    <div class="relative flex items-center gap-5 pl-12 group/item">
                                        <div class="absolute left-2.5 w-5 h-5 rounded-full border-4 border-white flex items-center justify-center z-10 transition-transform group-hover/item:scale-125
                                                    {{ $activity['color'] === 'blue' ? 'bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]' : '' }}
                                                    {{ $activity['color'] === 'green' ? 'bg-success-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]' : '' }}
                                                    {{ $activity['color'] === 'purple' ? 'bg-violet-500 shadow-[0_0_10px_rgba(139,92,246,0.5)]' : '' }}
                                                ">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[13px] font-bold text-gray-900 group-hover/item:text-primary-600 transition-colors">{{ $activity['title'] ?? 'Activity' }}</p>
                                            <p class="text-[11px] text-gray-400 font-medium truncate mt-0.5">{{ $activity['description'] ?? 'N/A' }}</p>
                                        </div>
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="text-[10px] font-black text-gray-900 bg-gray-50 px-2 py-0.5 rounded-lg border border-gray-100 whitespace-nowrap">
                                                {{ ($activity['time'] && method_exists($activity['time'], 'diffForHumans')) ? $activity['time']->diffForHumans() : 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                                 <i class='hgi-stroke hgi-activity-01 text-4xl text-gray-200'></i>
                            </div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No recent snapshots</p>
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