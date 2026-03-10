@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-700 p-8 text-white shadow-lg">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="rounded-xl bg-white/20 p-2 backdrop-blur-sm border border-white/20">
                            <i class='hgi-stroke hgi-credit-card text-2xl text-white'></i>
                        </div>
                        <h1 class="text-3xl font-bold text-white">Payroll Management</h1>
                    </div>
                    <p class="text-indigo-100 max-w-xl">Select a month to view payslips.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.payrolls.trash') }}"
                        class="bg-white/10 hover:bg-white/20 border border-white/20 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 transition-all backdrop-blur-sm font-medium">
                        <i class='hgi-stroke hgi-delete-01 text-xl'></i>
                        Trash
                    </a>
                    <a href="{{ route('admin.payrolls.create') }}"
                        class="bg-white text-indigo-600 hover:bg-indigo-50 px-5 py-2.5 rounded-xl flex items-center gap-2 font-bold shadow-lg transition-all hover:scale-105 active:scale-95">
                        <i class='hgi-stroke hgi-plus-sign text-xl'></i>
                        Generate Payslip
                    </a>
                </div>
            </div>

            <!-- Decorative Background -->
            <div
                class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none">
            </div>
            <div
                class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none">
            </div>
        </div>

        <!-- Months Grid -->
        @if(is_countable($months) && count($months) > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($months as $month)
                    @php
                        $date = \Carbon\Carbon::createFromDate($month->year, $month->month, 1);
                        $monthName = $date->format('F Y');
                        $isCurrentMonth = $month->year == now()->year && $month->month == now()->month;
                    @endphp

                    <a href="{{ route('admin.payrolls.by-month', ['year' => $month->year, 'month' => $month->month]) }}"
                        class="block group">
                        <div
                            class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border border-transparent hover:border-blue-200 {{ $isCurrentMonth ? 'ring-2 ring-blue-500 ring-offset-2' : '' }}">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div
                                        class="bg-blue-100 p-3 rounded-full group-hover:bg-blue-600 transition-colors duration-300">
                                        <i
                                            class='hgi-stroke hgi-calendar-03 text-2xl text-blue-600 group-hover:text-white transition-colors duration-300'></i>
                                    </div>
                                    @if($isCurrentMonth)
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">Current</span>
                                    @endif
                                </div>

                                <h3 class="text-xl font-bold text-gray-800 mb-1 group-hover:text-blue-600 transition-colors">
                                    {{ $monthName }}</h3>
                                <p class="text-sm text-gray-500 mb-4">{{ $month->total_payrolls }} Payslips</p>

                                <div class="flex items-center justify-between text-sm border-t border-gray-100 pt-4">
                                    <div class="text-gray-600">
                                        <span class="block text-xs uppercase text-gray-400">Total Paid</span>
                                        <span class="font-semibold text-green-600">RM
                                            {{ number_format($month->total_amount, 2) }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-xs uppercase text-gray-400">Status</span>
                                        <div class="flex gap-2 mt-1">
                                            @if($month->pending_count > 0)
                                                <span class="w-2 h-2 rounded-full bg-yellow-400"
                                                    title="{{ $month->pending_count }} Pending"></span>
                                            @endif
                                            @if($month->paid_count > 0)
                                                <span class="w-2 h-2 rounded-full bg-green-500"
                                                    title="{{ $month->paid_count }} Paid"></span>
                                            @endif
                                        </div>
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
                    <i class='hgi-stroke hgi-calendar-03-minus text-4xl text-gray-400'></i>
                </div>
                <h3 class="text-xl font-medium text-gray-900">No Payroll Records</h3>
                <p class="text-gray-500 mt-2 mb-6">Start by generating your first payslip.</p>
                <a href="{{ route('admin.payrolls.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class='hgi-stroke hgi-plus-sign mr-2 text-xl'></i>
                    Generate Payslip
                </a>
            </div>
        @endif
    </div>
@endsection