@extends('layouts.admin')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')

@section('content')
<div class="space-y-6">
    <!-- Filters Section -->
    <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                        <i class='bx bx-calendar'></i> Start Date
                    </label>
                    <input type="date" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ $startDate }}"
                           class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                </div>
                
                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1">
                        <i class='bx bx-calendar-check'></i> End Date
                    </label>
                    <input type="date" 
                           id="end_date" 
                           name="end_date" 
                           value="{{ $endDate }}"
                           class="w-full rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 shadow-xs focus:outline-none focus:ring-2 focus:ring-primary-600 focus:ring-offset-2">
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap gap-3">
                <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:ring-2 focus:ring-primary-600 focus:ring-offset-2 shadow-sm">
                    <i class='bx bx-filter-alt text-base'></i>
                    Apply Filters
                </button>
                @if(request()->hasAny(['start_date', 'end_date']))
                    <a href="{{ route('admin.reports.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 shadow-sm">
                        <i class='bx bx-x text-base'></i>
                        Clear Filters
                    </a>
                @endif
            </div>
            
            <!-- Active Filters Indicator -->
            @if(request()->hasAny(['start_date', 'end_date']))
                <div class="pt-4 border-t border-gray-200">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-medium text-gray-700">Active Filters:</span>
                        @if(request('start_date'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-primary-50 text-primary-700">
                                <i class='bx bx-calendar mr-1'></i>
                                From: {{ \Carbon\Carbon::parse(request('start_date'))->format('M d, Y') }}
                                <a href="{{ route('admin.reports.index', array_merge(request()->except('start_date'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-primary-700">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                        @if(request('end_date'))
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-success-50 text-success-600">
                                <i class='bx bx-calendar-check mr-1'></i>
                                To: {{ \Carbon\Carbon::parse(request('end_date'))->format('M d, Y') }}
                                <a href="{{ route('admin.reports.index', array_merge(request()->except('end_date'), ['page' => 1])) }}" 
                                   class="ml-2 hover:text-success-600">
                                    <i class='bx bx-x'></i>
                                </a>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </form>
    </div>

    <!-- Overall Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalPatients) }}</p>
                </div>
                <div class="bg-primary-50 text-primary-700 p-4 rounded-full">
                    <i class='bx bx-user text-3xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Total Doctors</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalDoctors) }}</p>
                </div>
                <div class="bg-success-50 text-success-600 p-4 rounded-full">
                    <i class='bx bx-user-circle text-3xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Total Services</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalServices) }}</p>
                </div>
                <div class="bg-primary-50 text-primary-700 p-4 rounded-full">
                    <i class='bx bx-list-ul text-3xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="bg-yellow-50 text-yellow-700 p-4 rounded-full">
                    <i class='bx bx-group text-3xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Total Appointments</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalAppointments) }}</p>
                </div>
                <div class="bg-primary-50 text-primary-700 p-4 rounded-full">
                    <i class='bx bx-calendar text-3xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue & Appointments Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900">{{ get_setting('currency', '$') }}{{ number_format($totalRevenue, 2) }}</p>
                    <p class="text-xs text-gray-500">In selected period</p>
                </div>
                <div class="bg-success-50 text-success-600 p-4 rounded-full">
                    <i class='bx bx-dollar text-3xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Appointments</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($appointmentsInRange->count()) }}</p>
                    <p class="text-xs text-gray-500">In selected period</p>
                </div>
                <div class="bg-primary-50 text-primary-700 p-4 rounded-full">
                    <i class='bx bx-calendar text-3xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($completedAppointments) }}</p>
                    <p class="text-xs text-gray-500">Appointments</p>
                </div>
                    <div class="bg-success-50 text-success-600 p-4 rounded-full">
                        <i class='bx bx-check-circle text-3xl'></i>
                    </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="space-y-1">
                    <p class="text-sm font-medium text-gray-600">Cancelled</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($cancelledAppointments) }}</p>
                    <p class="text-xs text-gray-500">Appointments</p>
                </div>
                <div class="bg-red-50 text-red-700 p-4 rounded-full">
                    <i class='bx bx-x-circle text-3xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Appointments by Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Appointments by Status</h3>
            <div class="space-y-4">
                @php
                    $statusLabels = [
                        'scheduled' => 'Scheduled',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                        'no_show' => 'No Show'
                    ];
                    $statusColors = [
                        'scheduled' => 'bg-blue-500',
                        'confirmed' => 'bg-green-500',
                        'completed' => 'bg-gray-500',
                        'cancelled' => 'bg-red-500',
                        'no_show' => 'bg-yellow-500'
                    ];
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
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="{{ $statusColors[$key] ?? 'bg-blue-500' }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Revenue (Last 6 Months)</h3>
            <div class="space-y-3">
                @php
                    $maxRevenue = $monthlyRevenue->count() > 0 && $monthlyRevenue->max() > 0 ? $monthlyRevenue->max() : 1;
                @endphp
                @foreach($monthlyRevenue as $month => $revenue)
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $month }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ get_setting('currency', '$') }}{{ number_format($revenue, 2) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full" style="width: {{ ($revenue / $maxRevenue) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Patients Statistics -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Patients Statistics</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="text-sm font-medium text-gray-700">New Patients (Period)</span>
                    <span class="text-lg font-bold text-gray-900">{{ number_format($newPatientsInRange) }}</span>
                </div>
                @if($patientsByGender->count() > 0)
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">By Gender</p>
                    <div class="space-y-2">
                        @foreach($patientsByGender as $gender => $count)
                            <div class="flex justify-between items-center">
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
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Doctors Statistics</h3>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-green-50 rounded-lg">
                        <p class="text-xs text-gray-600">Available</p>
                        <p class="text-2xl font-bold text-green-600">{{ $availableDoctors }}</p>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-lg">
                        <p class="text-xs text-gray-600">Unavailable</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $unavailableDoctors }}</p>
                    </div>
                </div>
                @if($doctorsByType->count() > 0)
                <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">By Type</p>
                    <div class="space-y-2">
                        @foreach($doctorsByType as $type => $count)
                            <div class="flex justify-between items-center">
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
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Services Statistics</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600">Active Services</p>
                <p class="text-3xl font-bold text-green-600">{{ $activeServices }}</p>
            </div>
            <div class="p-4 bg-yellow-50 rounded-lg">
                <p class="text-sm text-gray-600">Inactive Services</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $inactiveServices }}</p>
            </div>
            @if($servicesByType->count() > 0)
            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">By Type</p>
                <div class="space-y-1">
                    @foreach($servicesByType as $type => $count)
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-600 capitalize">{{ $type }}</span>
                            <span class="text-xs font-semibold text-gray-900">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Top Performers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Doctors by Appointments -->
        @if($appointmentsByDoctor->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Doctors by Appointments</h3>
            <div class="space-y-3">
                @foreach($appointmentsByDoctor as $item)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->doctor->full_name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $item->doctor->specialization ?? 'N/A' }}</p>
                        </div>
                        <span class="text-lg font-bold text-blue-600">{{ $item->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Top Services by Appointments -->
        @if($appointmentsByService->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Services by Appointments</h3>
            <div class="space-y-3">
                @foreach($appointmentsByService as $item)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->service->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ $item->service->type ?? 'N/A' }}</p>
                        </div>
                        <span class="text-lg font-bold text-purple-600">{{ $item->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Daily Appointments (Last 7 Days) -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Appointments (Last 7 Days)</h3>
        <div class="space-y-3">
            @php
                $maxDaily = $dailyAppointments->count() > 0 && $dailyAppointments->max() > 0 ? $dailyAppointments->max() : 1;
            @endphp
            @foreach($dailyAppointments as $day => $count)
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">{{ $day }}</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $count }} appointments</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($count / $maxDaily) * 100 }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

