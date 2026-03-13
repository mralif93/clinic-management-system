@extends('layouts.doctor', ['hideLayoutTitle' => true])

@section('title', 'View Payslip')
@section('page-title', 'View Payslip')
@section('hide-layout-title', true)

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div
            class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-6">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('doctor.payslips.index') }}"
                        class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <i class='hgi-stroke hgi-arrow-left-01 text-white text-xl'></i>
                    </a>
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">Payslip</h1>
                        <p class="text-emerald-100 text-sm mt-1">{{ $payroll->pay_period ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="printPayslip()"
                        class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur text-white font-medium rounded-xl hover:bg-white/30 transition border border-white/30">
                        <i class='hgi-stroke hgi-printer mr-2'></i> Print
                    </button>
                    <button onclick="downloadPayslip()"
                        class="inline-flex items-center px-4 py-2 bg-white text-emerald-700 font-medium rounded-xl hover:bg-emerald-50 transition shadow-lg">
                        <i class='hgi-stroke hgi-download-04 mr-2'></i> Download
                    </button>
                </div>
            </div>
        </div>

        <!-- Payslip Card -->
        <div class="bg-gray-50 rounded-2xl p-4 md:p-6">
            <div class="max-w-[210mm] mx-auto">
                @include('admin.payrolls.payslip_template', ['payroll' => $payroll])
            </div>
        </div>
    </div>

    <script src="{{ asset('js/html2pdf.min.js') }}"></script>
    <link href="{{ asset('css/hugeicons.css') }}" rel="stylesheet">
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

            html2pdf().set(opt).from(element).toPdf().get('pdf').then(function (pdf) {
                // Open PDF in new window for printing
                const blob = pdf.output('blob');
                const url = URL.createObjectURL(blob);
                const printWindow = window.open(url, '_blank');
                printWindow.onload = function () {
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