@extends('layouts.doctor')

@section('title', 'View Payslip')
@section('page-title', 'View Payslip')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <a href="{{ route('doctor.payslips.index') }}" class="inline-flex items-center gap-1 text-emerald-100 hover:text-white text-sm mb-2 transition">
                        <i class='bx bx-arrow-back'></i> Back to Payslips
                    </a>
                    <h1 class="text-2xl font-bold flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class='bx bx-receipt text-xl'></i>
                        </div>
                        Payslip
                    </h1>
                    <p class="text-emerald-100 mt-2">{{ $payroll->pay_period ?? 'N/A' }}</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="printPayslip()"
                        class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur text-white font-medium rounded-xl hover:bg-white/30 transition">
                        <i class='bx bx-printer mr-2'></i> Print
                    </button>
                    <button onclick="downloadPayslip()"
                        class="inline-flex items-center px-4 py-2 bg-white text-emerald-700 font-medium rounded-xl hover:bg-emerald-50 transition shadow-sm">
                        <i class='bx bx-download mr-2'></i> Download
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
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
                // Open PDF in new window for printing
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

