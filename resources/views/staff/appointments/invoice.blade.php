@extends('layouts.staff')

@section('title', 'Appointment Invoice')
@section('page-title', 'Appointment Invoice')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('staff.appointments.show', $appointment->id) }}"
                            class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center hover:bg-white/30 transition">
                            <i class='bx bx-arrow-back text-white text-xl'></i>
                        </a>
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">Invoice</h1>
                            <p class="text-emerald-100 text-sm mt-1">INV-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button onclick="printInvoice()"
                            class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/30 transition border border-white/30">
                            <i class='bx bx-printer mr-2'></i> Print
                        </button>
                        <button onclick="downloadInvoice()"
                            class="inline-flex items-center px-4 py-2 bg-white text-emerald-600 font-medium rounded-xl hover:bg-emerald-50 transition shadow-lg">
                            <i class='bx bx-download mr-2'></i> Download
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            <div class="p-8">
                <x-invoice-template :appointment="$appointment" />
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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

        function printInvoice() {
            const element = document.getElementById('invoice-content');
            const opt = getPdfOptions('Invoice-INV-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}.pdf');

            html2pdf().set(opt).from(element).toPdf().get('pdf').then(function(pdf) {
                const blob = pdf.output('blob');
                const url = URL.createObjectURL(blob);
                const printWindow = window.open(url, '_blank');
                printWindow.onload = function() {
                    printWindow.print();
                };
            });
        }

        function downloadInvoice() {
            const element = document.getElementById('invoice-content');
            const opt = getPdfOptions('Invoice-INV-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}.pdf');
            html2pdf().set(opt).from(element).save();
        }
    </script>
    @endpush
@endsection

