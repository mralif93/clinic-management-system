@extends('layouts.doctor')

@section('title', 'Appointment Invoice')
@section('page-title', 'Appointment Invoice')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
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
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class='bx bx-printer'></i> Print
                </button>
                <button onclick="downloadInvoice()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
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
        function printInvoice() {
            const content = document.getElementById('invoice-content');
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Invoice - INV-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</title>
                    <script src="https://cdn.tailwindcss.com"><\/script>
                    <style>
                        @media print {
                            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                        }
                    </style>
                </head>
                <body class="p-8">
                    ${content.outerHTML}
                    <script>
                        window.onload = function() {
                            setTimeout(function() {
                                window.print();
                                window.close();
                            }, 500);
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        function downloadInvoice() {
            const element = document.getElementById('invoice-content');
            const opt = {
                margin: 10,
                filename: 'Invoice-INV-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
    @endpush
@endsection

