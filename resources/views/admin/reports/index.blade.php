@extends('layouts.admin')

@section('title', 'Reports & Analytics')
@section('page-title', 'Reports & Analytics')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-slate-700 to-slate-800 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h1 class="text-2xl font-bold flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class='bx bx-bar-chart-alt text-2xl'></i>
                        </div>
                        Reports & Analytics
                    </h1>
                    <p class="mt-2 text-slate-300">Comprehensive overview of clinic performance</p>
                </div>
            </div>
        </div>

        <!-- Date Range Filter -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-2">
                    <i class='bx bx-calendar text-gray-500'></i>
                    <h3 class="font-semibold text-gray-700">Date Range</h3>
                </div>
            </div>
            <div class="p-5">
                <form method="GET" action="{{ route('admin.reports.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-600 mb-2">Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20 transition-all text-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-600 mb-2">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20 transition-all text-sm">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-700 text-white rounded-xl font-medium hover:bg-slate-800 transition-all text-sm">
                                <i class='bx bx-filter-alt'></i>
                                Apply
                            </button>
                            @if(request()->hasAny(['start_date', 'end_date']))
                                <a href="{{ route('admin.reports.index') }}"
                                    class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-all text-sm">
                                    <i class='bx bx-reset'></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Overview Stats -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Patients</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalPatients) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <i class='bx bx-user text-2xl text-blue-600'></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Doctors</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalDoctors) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <i class='bx bx-plus-circle text-2xl text-emerald-600'></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Services</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalServices) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                        <i class='bx bx-grid text-2xl text-purple-600'></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center">
                        <i class='bx bx-group text-2xl text-amber-600'></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Appointments</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalAppointments) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-cyan-50 flex items-center justify-center">
                        <i class='bx bx-calendar text-2xl text-cyan-600'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue & Appointments Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-100">Total Revenue</p>
                        <p class="text-2xl font-bold">
                            {{ get_setting('currency', '$') }}{{ number_format($totalRevenue, 2) }}</p>
                        <p class="text-xs text-green-200 mt-1">In selected period</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class='bx bx-dollar text-2xl'></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-100">Appointments</p>
                        <p class="text-2xl font-bold">{{ number_format($appointmentsInRange->count()) }}</p>
                        <p class="text-xs text-blue-200 mt-1">In selected period</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class='bx bx-calendar-check text-2xl'></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-emerald-100">Completed</p>
                        <p class="text-2xl font-bold">{{ number_format($completedAppointments) }}</p>
                        <p class="text-xs text-emerald-200 mt-1">Appointments</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class='bx bx-check-double text-2xl'></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl p-5 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-red-100">Cancelled</p>
                        <p class="text-2xl font-bold">{{ number_format($cancelledAppointments) }}</p>
                        <p class="text-xs text-red-200 mt-1">Appointments</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class='bx bx-x-circle text-2xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Appointments by Status -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-pie-chart-alt-2 text-gray-500'></i>
                        Appointments by Status
                    </h3>
                </div>
                <div class="p-5 space-y-4">
                    @php
                        $statusLabels = ['scheduled' => 'Scheduled', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'cancelled' => 'Cancelled', 'no_show' => 'No Show'];
                        $statusColors = ['scheduled' => 'bg-blue-500', 'confirmed' => 'bg-indigo-500', 'completed' => 'bg-green-500', 'cancelled' => 'bg-red-500', 'no_show' => 'bg-amber-500'];
                        $totalStatus = $appointmentsByStatus->sum();
                    @endphp
                    @foreach($statusLabels as $key => $label)
                        @php
                            $count = $appointmentsByStatus->get($key, 0);
                            $percentage = $totalStatus > 0 ? ($count / $totalStatus) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                                <span class="text-sm text-gray-600">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5">
                                <div class="{{ $statusColors[$key] ?? 'bg-blue-500' }} h-2.5 rounded-full transition-all"
                                    style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-trending-up text-gray-500'></i>
                        Monthly Revenue (Last 6 Months)
                    </h3>
                </div>
                <div class="p-5 space-y-3">
                    @php
                        $maxRevenue = $monthlyRevenue->count() > 0 && $monthlyRevenue->max() > 0 ? $monthlyRevenue->max() : 1;
                    @endphp
                    @foreach($monthlyRevenue as $month => $revenue)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-medium text-gray-700">{{ $month }}</span>
                                <span
                                    class="text-sm font-semibold text-gray-900">{{ get_setting('currency', '$') }}{{ number_format($revenue, 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3">
                                <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-3 rounded-full transition-all"
                                    style="width: {{ ($revenue / $maxRevenue) * 100 }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Patients Statistics -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-user text-gray-500'></i>
                        Patients Statistics
                    </h3>
                </div>
                <div class="p-5 space-y-4">
                    <div
                        class="flex justify-between items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
                        <span class="text-sm font-medium text-gray-700">New Patients (Period)</span>
                        <span class="text-xl font-bold text-blue-600">{{ number_format($newPatientsInRange) }}</span>
                    </div>
                    @if($patientsByGender->count() > 0)
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-3">By Gender</p>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($patientsByGender as $gender => $count)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                        <span class="text-sm text-gray-600 capitalize">{{ $gender ?? 'Not Specified' }}</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Doctors Statistics -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i class='bx bx-plus-circle text-gray-500'></i>
                        Doctors Statistics
                    </h3>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl text-center">
                            <p class="text-xs text-gray-600">Available</p>
                            <p class="text-2xl font-bold text-green-600">{{ $availableDoctors }}</p>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-amber-50 to-yellow-50 rounded-xl text-center">
                            <p class="text-xs text-gray-600">Unavailable</p>
                            <p class="text-2xl font-bold text-amber-600">{{ $unavailableDoctors }}</p>
                        </div>
                    </div>
                    @if($doctorsByType->count() > 0)
                        <div>
                            <p class="text-sm font-medium text-gray-700 mb-3">By Type</p>
                            <div class="space-y-2">
                                @foreach($doctorsByType as $type => $count)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                        <span class="text-sm text-gray-600 capitalize">{{ $type }}</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Services Statistics -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-grid text-gray-500'></i>
                    Services Statistics
                </h3>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-5 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl text-center">
                        <p class="text-sm text-gray-600">Active Services</p>
                        <p class="text-3xl font-bold text-green-600">{{ $activeServices }}</p>
                    </div>
                    <div class="p-5 bg-gradient-to-br from-amber-50 to-yellow-50 rounded-xl text-center">
                        <p class="text-sm text-gray-600">Inactive Services</p>
                        <p class="text-3xl font-bold text-amber-600">{{ $inactiveServices }}</p>
                    </div>
                    @if($servicesByType->count() > 0)
                        <div class="p-5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl">
                            <p class="text-sm text-gray-600 mb-3">By Type</p>
                            <div class="space-y-2">
                                @foreach($servicesByType as $type => $count)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600 capitalize">{{ $type }}</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @if($appointmentsByDoctor->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-trophy text-gray-500'></i>
                            Top Doctors by Appointments
                        </h3>
                    </div>
                    <div class="p-5 space-y-3">
                        @foreach($appointmentsByDoctor as $index => $item)
                            <div
                                class="flex justify-between items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $item->doctor->full_name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->doctor->specialization ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <span class="text-lg font-bold text-blue-600">{{ $item->count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($appointmentsByService->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                            <i class='bx bx-star text-gray-500'></i>
                            Top Services by Appointments
                        </h3>
                    </div>
                    <div class="p-5 space-y-3">
                        @foreach($appointmentsByService as $index => $item)
                            <div
                                class="flex justify-between items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $item->service->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500 capitalize">{{ $item->service->type ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <span class="text-lg font-bold text-purple-600">{{ $item->count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Daily Appointments -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-line-chart text-gray-500'></i>
                    Daily Appointments (Last 7 Days)
                </h3>
            </div>
            <div class="p-5 space-y-3">
                @php $maxDaily = $dailyAppointments->count() > 0 && $dailyAppointments->max() > 0 ? $dailyAppointments->max() : 1; @endphp
                @foreach($dailyAppointments as $day => $count)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $day }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $count }} appointments</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2.5 rounded-full transition-all"
                                style="width: {{ ($count / $maxDaily) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection