@extends('layouts.staff')

@section('title', 'Appointment Invoice')
@section('page-title', 'Appointment Invoice')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('staff.appointments.show', $appointment->id) }}"
                    class="bg-white p-2 rounded-full shadow-sm hover:shadow-md transition-shadow text-gray-600">
                    <i class='bx bx-arrow-back text-2xl'></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Invoice</h1>
                    <p class="text-gray-600 mt-1">INV-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            <div class="flex gap-2">
                <button onclick="printInvoice()"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class='bx bx-printer'></i> Print
                </button>
                <button onclick="downloadInvoice()"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class='bx bx-download'></i> Download
                </button>
            </div>
        </div>

        <!-- Invoice Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
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

