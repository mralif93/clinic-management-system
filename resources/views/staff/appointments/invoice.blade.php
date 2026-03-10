@extends('layouts.staff')

@section('title', 'Appointment Invoice')
@section('page-title', 'Appointment Invoice')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-emerald-500 via-green-500 to-teal-500 rounded-2xl shadow-lg overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('staff.appointments.show', $appointment->id) }}"
                            class="shrink-0 w-12 h-12 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                            <i class='hgi-stroke hgi-arrow-left-01 text-white text-xl'></i>
                        </a>
                        <div class="text-white">
                            <h1 class="text-2xl font-bold">Invoice</h1>
                            <p class="text-emerald-100 text-sm mt-1">INV-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button onclick="printInvoice()"
                            class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white font-medium rounded-xl hover:bg-white/30 transition border border-white/30">
                            <i class='hgi-stroke hgi-printer mr-2'></i> Print
                        </button>
                        <button onclick="downloadInvoice()"
                            class="inline-flex items-center px-4 py-2 bg-white text-emerald-600 font-medium rounded-xl hover:bg-emerald-50 transition shadow-lg">
                            <i class='hgi-stroke hgi-download-04 mr-2'></i> Download
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

