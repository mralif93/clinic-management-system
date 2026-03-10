@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-6 text-white  mb-6 shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
    <div class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
        <i class='hgi-stroke hgi-clock-01 text-2xl'></i>
    </div>
    <div>
        <h2 class="text-2xl font-bold">Leave Management</h2>
        <p class="text-emerald-100 text-sm mt-1">Select a month to view leave requests</p>
    </div>
</div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.leaves.trash') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='hgi-stroke hgi-delete-02 text-lg'></i>
                        Trash
                    </a>
                    <a href="{{ route('admin.leaves.create') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-emerald-600 rounded-xl font-semibold hover:bg-emerald-50 transition-all shadow-lg shadow-emerald-900/20">
                        <i class='hgi-stroke hgi-plus-sign text-xl'></i>
                        Apply Leave
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

                    <a href="{{ route('admin.leaves.by-month', ['year' => $month->year, 'month' => $month->month]) }}"
                        class="block group">
                        <div
                            class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border border-transparent hover:border-blue-200 {{ $isCurrentMonth ? 'ring-2 ring-blue-500 ring-offset-2' : '' }}">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div
                                        class="bg-purple-100 p-3 rounded-full group-hover:bg-purple-600 transition-colors duration-300">
                                        <i
                                            class='hgi-stroke hgi-calendar-01 text-2xl text-purple-600 group-hover:text-white transition-colors duration-300'></i>
                                    </div>
                                    @if($isCurrentMonth)
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Current</span>
                                    @endif
                                </div>

                                <h3 class="text-xl font-bold text-gray-800 mb-1 group-hover:text-purple-600 transition-colors">
                                    {{ $monthName }}
                                </h3>
                                <p class="text-sm text-gray-500 mb-4">{{ $month->total_leaves }} Requests</p>

                                <div class="flex items-center justify-between text-sm border-t border-gray-100 pt-4">
                                    <div class="flex gap-2">
                                        <span
                                            class="flex items-center gap-1 text-xs text-yellow-600 bg-yellow-100 px-2 py-0.5 rounded-full"
                                            title="Pending">
                                            <i class='hgi-stroke hgi-clock-01'></i> {{ $month->pending_count }}
                                        </span>
                                        <span
                                            class="flex items-center gap-1 text-xs text-green-600 bg-green-100 px-2 py-0.5 rounded-full"
                                            title="Approved">
                                            <i class='hgi-stroke hgi-checkmark-circle-02'></i> {{ $month->approved_count }}
                                        </span>
                                        <span
                                            class="flex items-center gap-1 text-xs text-red-600 bg-red-100 px-2 py-0.5 rounded-full"
                                            title="Rejected">
                                            <i class='hgi-stroke hgi-cancel-circle text-xs'></i> {{ $month->rejected_count }}
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
                    <i class='hgi-stroke hgi-calendar-minus-01 text-4xl text-gray-400'></i>
                </div>
                <h3 class="text-xl font-medium text-gray-900">No Leave Records</h3>
                <p class="text-gray-500 mt-2 mb-6">No leave requests found.</p>
                <a href="{{ route('admin.leaves.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class='hgi-stroke hgi-plus-sign mr-2 text-xl'></i>
                    Apply Leave
                </a>
            </div>
        @endif
    </div>
@endsection