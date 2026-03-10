@extends('layouts.staff')

@section('title', 'View Payslip')
@section('page-title', 'View Payslip')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('staff.payslips.index') }}"
                            class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                            <i class='hgi-stroke hgi-arrow-left-01 text-white text-xl'></i>
                        </a>
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">Payslip</h1>
                            <p class="text-emerald-100 text-sm mt-1">{{ $payroll->pay_period ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="printPayslip()"
                            class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/30 transition border border-white/30">
                            <i class='hgi-stroke hgi-printer mr-2'></i> Print
                        </button>
                        <button onclick="downloadPayslip()"
                            class="inline-flex items-center px-4 py-2 bg-white text-emerald-600 font-semibold rounded-xl hover:bg-emerald-50 transition shadow-lg">
                            <i class='hgi-stroke hgi-download-04 mr-2'></i> Download
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg flex items-center justify-center">
                        <i class='hgi-stroke hgi-money-bag-01 text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ get_currency_symbol() }}{{ number_format($payroll->net_salary ?? 0, 2) }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Net Pay</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <i class='hgi-stroke hgi-wallet-01 text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ get_currency_symbol() }}{{ number_format($payroll->basic_salary ?? 0, 2) }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Basic</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <i class='hgi-stroke hgi-plus-sign text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-green-600">+{{ get_currency_symbol() }}{{ number_format($payroll->total_allowances ?? 0, 2) }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Allowances</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-rose-600 rounded-lg flex items-center justify-center">
                        <i class='hgi-stroke hgi-minus-sign-circle text-white text-xl'></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-red-600">-{{ get_currency_symbol() }}{{ number_format($payroll->total_deductions ?? 0, 2) }}</p>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Deductions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payslip Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="p-6 md:p-8">
                @include('admin.payrolls.payslip_template', ['payroll' => $payroll])
            </div>
        </div>
    </div>

    <script src="{{ asset('js/html2pdf.min.js') }}"></script>
    <script>
        function getPdfOptions(filename) {
            return {
                margin: [5, 5, 5, 5],
                filename: filename,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    letterRendering: true,
                    logging: false
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                },
                pagebreak: { mode: 'avoid-all' }
            };
        }

        function printPayslip() {
            const element = document.getElementById('payslip-content');
            const opt = getPdfOptions('Payslip-{{ str_replace(' ', '-', $payroll->pay_period ?? 'N-A') }}.pdf');

            html2pdf().set(opt).from(element).toPdf().get('pdf').then(function(pdf) {
                const blob = pdf.output('blob');
                const url = URL.createObjectURL(blob);
                const printWindow = window.open(url, '_blank');
                printWindow.onload = function() {
                    printWindow.print();
                };
            });
        }

        function downloadPayslip() {
            const element = document.getElementById('payslip-content');
            const opt = getPdfOptions('Payslip-{{ str_replace(' ', '-', $payroll->pay_period ?? 'N-A') }}.pdf');
            html2pdf().set(opt).from(element).save();
        }
    </script>
@endsection

