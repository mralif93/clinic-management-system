@extends('layouts.admin')

@php
    /** @var \Illuminate\Database\Eloquent\Collection $months */
@endphp

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div
            class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div
                        class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <i class='hgi-stroke hgi-calendar-03 text-2xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Appointment Management</h1>
                        <p class="text-indigo-100 text-sm mt-1">Select a month to view and manage scheduled appointments</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.appointments.trash') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold rounded-xl hover:bg-white/30 transition-all shadow-lg hover:shadow-xl">
                        <i class='hgi-stroke hgi-delete-01'></i>
                        Trash
                    </a>
                    <a href="{{ route('admin.appointments.create') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold rounded-xl hover:bg-white/30 transition-all shadow-lg hover:shadow-xl">
                        <i class='hgi-stroke hgi-plus-sign'></i>
                        Schedule Appointment
                    </a>
                </div>
            </div>
        </div>

        <!-- Months Grid -->
        @if($months->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($months as $month)
                    @php
                        $date = \Carbon\Carbon::createFromDate($month->year, $month->month, 1);
                        $monthName = $date->format('F Y');
                        $isCurrentMonth = $month->year == now()->year && $month->month == now()->month;
                    @endphp

                    <a href="{{ route('admin.appointments.by-month', ['year' => $month->year, 'month' => $month->month]) }}"
                        class="block group">
                        <div
                            class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border border-transparent hover:border-blue-200 {{ $isCurrentMonth ? 'ring-2 ring-blue-500 ring-offset-2' : '' }}">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div
                                        class="bg-indigo-100 p-3 rounded-full group-hover:bg-indigo-600 transition-colors duration-300">
                                        <i
                                            class='hgi-stroke hgi-calendar-03 text-2xl text-indigo-600 group-hover:text-white transition-colors duration-300'></i>
                                    </div>
                                    @if($isCurrentMonth)
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Current</span>
                                    @endif
                                </div>

                                <h3 class="text-xl font-bold text-gray-800 mb-1 group-hover:text-indigo-600 transition-colors">
                                    {{ $monthName }}
                                </h3>
                                <p class="text-sm text-gray-500 mb-4">{{ $month->total_appointments }} Appointments</p>

                                <div class="flex items-center justify-between text-sm border-t border-gray-100 pt-4">
                                    <div class="flex gap-2 w-full justify-between">
                                        <span
                                            class="flex items-center gap-1 text-xs text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full"
                                            title="Scheduled">
                                            <i class='hgi-stroke hgi-clock-02'></i> {{ $month->scheduled_count }}
                                        </span>
                                        <span
                                            class="flex items-center gap-1 text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded-full"
                                            title="Confirmed">
                                            <i class='hgi-stroke hgi-checkmark-circle-02'></i> {{ $month->confirmed_count }}
                                        </span>
                                        <span
                                            class="flex items-center gap-1 text-xs text-gray-600 bg-gray-100 px-2 py-0.5 rounded-full"
                                            title="Completed">
                                            <i class='hgi-stroke hgi-tick-double-01'></i> {{ $month->completed_count }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-xl shadow-md">
                <div class="bg-gray-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                    <i class='hgi-stroke hgi-calendar-03 text-4xl text-gray-400'></i>
                </div>
                <h3 class="text-xl font-medium text-gray-900">No Appointments</h3>
                <p class="text-gray-500 mt-2 mb-6">No appointments scheduled yet.</p>
                <a href="{{ route('admin.appointments.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class='hgi-stroke hgi-plus-sign mr-2 text-xl'></i>
                    Schedule Appointment
                </a>
            </div>
        @endif
    </div>
@endsection