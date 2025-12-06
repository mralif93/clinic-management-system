@extends('layouts.staff')

@section('title', 'My Payslips')
@section('page-title', 'My Payslips')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-emerald-500 via-emerald-600 to-green-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <i class='bx bx-receipt text-3xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">My Payslips</h1>
                        <p class="text-emerald-100 text-sm mt-1">View and download your salary statements</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-sm">
                        <i class='bx bx-calendar mr-1'></i>
                        {{ now()->format('Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        @php
            $totalNet = $payslips->sum('net_salary');
            $paidCount = $payslips->where('status', 'paid')->count();
            $latestPayslip = $payslips->first();
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-file text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $payslips->total() }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-check-circle text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900">{{ $paidCount }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Paid</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-wallet text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">RM {{ number_format($totalNet, 0) }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">YTD Net</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center">
                        <i class='bx bx-calendar-check text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $latestPayslip ? $latestPayslip->pay_period : 'N/A' }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Latest</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filters -->
        <div class="bg-gradient-to-r from-emerald-50 via-green-50 to-emerald-50 rounded-2xl border border-emerald-100/50 p-5">
            <!-- Search Bar -->
            <div class="relative mb-4">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class='bx bx-search text-emerald-500 text-xl'></i>
                </div>
                <input type="text" id="searchInput" placeholder="Search payslips..."
                    class="w-full pl-12 pr-4 py-3 bg-white border-0 rounded-xl shadow-sm focus:ring-2 focus:ring-emerald-500 text-gray-700 placeholder-gray-400">
            </div>

            <!-- Filter Options -->
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-medium text-emerald-700 flex items-center gap-1.5">
                    <i class='bx bx-filter-alt'></i> Filters:
                </span>

                <select id="monthFilter" onchange="applyFilters()"
                    class="px-3 py-2 bg-white border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 text-sm text-gray-700 min-w-[130px]">
                    <option value="">All Months</option>
                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $index => $monthName)
                        <option value="{{ $index + 1 }}" {{ $month == ($index + 1) ? 'selected' : '' }}>{{ $monthName }}</option>
                    @endforeach
                </select>

                <select id="yearFilter" onchange="applyFilters()"
                    class="px-3 py-2 bg-white border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-emerald-500 text-sm text-gray-700 min-w-[100px]">
                    <option value="">All Years</option>
                    @foreach($years as $yr)
                        <option value="{{ $yr }}" {{ $year == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                    @endforeach
                </select>

                @if($month || $year)
                    <a href="{{ route('staff.payslips.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-white text-gray-600 text-sm font-medium rounded-lg shadow-sm hover:bg-gray-50 transition ml-auto">
                        <i class='bx bx-x mr-1'></i> Clear
                    </a>
                @endif
            </div>
        </div>

        <!-- Payslips Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                        <i class='bx bx-list-ul text-emerald-500'></i>
                        Payslip History
                    </h3>
                    <span class="text-sm text-gray-500">{{ $payslips->total() }} records</span>
                </div>
            </div>

            @if($payslips->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100" id="payslipsTable">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pay Period</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Basic Salary</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gross Salary</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Net Salary</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment Date</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50">
                            @foreach($payslips as $payslip)
                                <tr class="hover:bg-emerald-50/30 transition-colors duration-150 payslip-row">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-green-500 rounded-lg flex items-center justify-center">
                                                <i class='bx bx-calendar text-white text-lg'></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $payslip->pay_period }}</div>
                                                <div class="text-xs text-gray-500">Payslip #{{ $payslip->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        RM {{ number_format($payslip->basic_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        RM {{ number_format($payslip->gross_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-bold text-emerald-600">RM {{ number_format($payslip->net_salary, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusConfig = [
                                                'draft' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-edit'],
                                                'approved' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'icon' => 'bx-check'],
                                                'paid' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => 'bx-check-double'],
                                            ];
                                            $sConfig = $statusConfig[$payslip->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => 'bx-help-circle'];
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $sConfig['bg'] }} {{ $sConfig['text'] }}">
                                            <i class='bx {{ $sConfig['icon'] }}'></i>
                                            {{ ucfirst($payslip->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($payslip->payment_date)
                                            <div class="text-sm text-gray-700">{{ $payslip->payment_date->format('d M Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $payslip->payment_date->diffForHumans() }}</div>
                                        @else
                                            <span class="text-gray-400 text-sm">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end">
                                            <a href="{{ route('staff.payslips.show', $payslip->id) }}"
                                                class="w-8 h-8 flex items-center justify-center bg-emerald-500 text-white hover:bg-emerald-600 rounded-full transition shadow-sm hover:shadow" title="View">
                                                <i class='bx bx-show text-sm'></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="noResults" class="hidden p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-search-alt text-3xl text-gray-400'></i>
                    </div>
                    <p class="text-gray-500 font-medium">No payslips found matching your search</p>
                </div>

                @if($payslips->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $payslips->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-16">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class='bx bx-receipt text-3xl text-gray-400'></i>
                    </div>
                    <p class="text-gray-500 font-medium">No Payslips Available</p>
                    <p class="text-gray-400 text-sm mt-1">Your payslips will appear here once they are processed</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        function applyFilters() {
            const month = document.getElementById('monthFilter').value;
            const year = document.getElementById('yearFilter').value;
            const params = new URLSearchParams();

            if (month) params.append('month', month);
            if (year) params.append('year', year);

            window.location.href = '{{ route('staff.payslips.index') }}' + (params.toString() ? '?' + params.toString() : '');
        }

        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.payslip-row');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0);
            document.getElementById('payslipsTable').classList.toggle('hidden', visibleCount === 0);
        });
    </script>
    @endpush
@endsection

