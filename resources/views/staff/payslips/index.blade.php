@extends('layouts.staff')

@section('title', 'My Payslips')
@section('page-title', 'My Payslips')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow">
            <!-- Header with Filters -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Payslip History</h3>
                        <p class="text-sm text-gray-500 mt-1">View and download your payslips</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- Month Filter -->
                        <select id="monthFilter" onchange="applyFilters()"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">All Months</option>
                            @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $index => $monthName)
                                <option value="{{ $index + 1 }}" {{ $month == ($index + 1) ? 'selected' : '' }}>{{ $monthName }}</option>
                            @endforeach
                        </select>
                        <!-- Year Filter -->
                        <select id="yearFilter" onchange="applyFilters()"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">All Years</option>
                            @foreach($years as $yr)
                                <option value="{{ $yr }}" {{ $year == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                        <!-- Search -->
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search..."
                                class="w-full sm:w-48 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                        </div>
                        @if($month || $year)
                            <a href="{{ route('staff.payslips.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                <i class='bx bx-x mr-1'></i> Clear
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if($payslips->count() > 0)
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="payslipsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pay Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Basic Salary</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gross Salary</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Salary</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Date</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($payslips as $payslip)
                                <tr class="hover:bg-gray-50 payslip-row">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $payslip->pay_period }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        RM {{ number_format($payslip->basic_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        RM {{ number_format($payslip->gross_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        RM {{ number_format($payslip->net_salary, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'approved' => 'bg-blue-100 text-blue-800',
                                                'paid' => 'bg-green-100 text-green-800',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$payslip->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($payslip->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $payslip->payment_date ? $payslip->payment_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('staff.payslips.show', $payslip->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-yellow-600 text-white text-sm rounded-lg hover:bg-yellow-700 transition">
                                            <i class='bx bx-show mr-1'></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- No Results Message -->
                <div id="noResults" class="hidden p-8 text-center">
                    <i class='bx bx-search-alt text-4xl text-gray-300 mb-2'></i>
                    <p class="text-gray-500">No payslips found matching your search.</p>
                </div>

                <!-- Pagination -->
                @if($payslips->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $payslips->links() }}
                    </div>
                @endif
            @else
                <div class="p-12 text-center">
                    <i class='bx bx-receipt text-6xl text-gray-300 mb-4'></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Payslips Available</h3>
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
@endsection

