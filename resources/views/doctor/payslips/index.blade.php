@extends('layouts.doctor', ['hideLayoutTitle' => true])

@section('title', 'My Payslips')
@section('page-title', 'My Payslips')
@section('hide-layout-title', true)

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-6">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <i class='hgi-stroke hgi-invoice-01 text-2xl'></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">My Payslips</h1>
                        <p class="text-emerald-100 text-sm mt-1">View and download your payslips</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-sm">
                        <i class='hgi-stroke hgi-calendar-03 mr-1'></i>
                        {{ now()->format('l, M d') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <div class="flex flex-wrap gap-3 flex-1">
                    <!-- Month Filter -->
                    <select id="monthFilter" onchange="applyFilters()"
                        class="px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                        <option value="">All Months</option>
                        @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $index => $monthName)
                            <option value="{{ $index + 1 }}" {{ $month == ($index + 1) ? 'selected' : '' }}>{{ $monthName }}</option>
                        @endforeach
                    </select>
                    <!-- Year Filter -->
                    <select id="yearFilter" onchange="applyFilters()"
                        class="px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                        <option value="">All Years</option>
                        @foreach($years as $yr)
                            <option value="{{ $yr }}" {{ $year == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                        @endforeach
                    </select>
                    <!-- Search -->
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Search..."
                            class="w-full sm:w-48 pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                        <i class='hgi-stroke hgi-search-01 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                    </div>
                </div>
                @if($month || $year)
                    <a href="{{ route('doctor.payslips.index') }}"
                        class="inline-flex items-center px-4 py-2.5 border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition">
                        <i class='hgi-stroke hgi-cancel-circle mr-1'></i> Clear Filters
                    </a>
                @endif
            </div>
        </div>

        <!-- Payslips Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($payslips->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full" id="payslipsTable">
                        <thead class="bg-gray-50/80">
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
                        <tbody class="divide-y divide-gray-100">
                            @foreach($payslips as $payslip)
                                <tr class="hover:bg-gray-50/50 transition-colors payslip-row">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center">
                                                <i class='hgi-stroke hgi-calendar-03 text-emerald-600'></i>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900">{{ $payslip->pay_period }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        RM {{ number_format($payslip->basic_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        RM {{ number_format($payslip->gross_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-900">RM {{ number_format($payslip->net_salary, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payslip->status === 'paid')
                                            <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700">Paid</span>
                                        @elseif($payslip->status === 'approved')
                                            <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700">Approved</span>
                                        @else
                                            <span class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700">{{ ucfirst($payslip->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payslip->payment_date ? $payslip->payment_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('doctor.payslips.show', $payslip->id) }}"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors"
                                                title="View Payslip">
                                                <i class='hgi-stroke hgi-eye text-lg'></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- No Results Message -->
                <div id="noResults" class="hidden p-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class='hgi-stroke hgi-search-01 text-3xl text-gray-400'></i>
                    </div>
                    <p class="text-gray-500 font-medium">No payslips found matching your search.</p>
                </div>

                <!-- Pagination -->
                @if($payslips->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $payslips->links() }}
                    </div>
                @endif
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                        <i class='hgi-stroke hgi-invoice-01 text-3xl text-gray-400'></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Payslips Available</h3>
                    <p class="text-gray-500">Your payslips will appear here once they are processed.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function applyFilters() {
            const month = document.getElementById('monthFilter').value;
            const year = document.getElementById('yearFilter').value;
            const params = new URLSearchParams();

            if (month) params.append('month', month);
            if (year) params.append('year', year);

            window.location.href = '{{ route('doctor.payslips.index') }}' + (params.toString() ? '?' + params.toString() : '');
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
@endsection

