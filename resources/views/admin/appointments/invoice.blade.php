@extends('layouts.admin')

@section('title', 'Appointment Invoice')
@section('page-title', 'Appointment Invoice')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div
            class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-8 print:hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.appointments.show', $appointment->id) }}"
                        class="inline-flex items-center justify-center w-11 h-11 bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-xl hover:bg-white/30 transition-all shadow-lg hover:shadow-xl">
                        <i class='hgi-stroke hgi-arrow-left-01 text-2xl'></i>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold">Appointment Invoice</h2>
                        <p class="text-blue-100 text-sm mt-1">INV-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="printInvoice()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold rounded-xl hover:bg-white/30 transition-all shadow-lg hover:shadow-xl">
                        <i class='hgi-stroke hgi-printer'></i>
                        Print
                    </button>
                    <button onclick="downloadInvoice()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold rounded-xl hover:bg-white/30 transition-all shadow-lg hover:shadow-xl">
                        <i class='hgi-stroke hgi-download-04'></i>
                        Download
                    </button>
                </div>
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

                html2pdf().set(opt).from(element).toPdf().get('pdf').then(function (pdf) {
                    const blob = pdf.output('blob');
                    const url = URL.createObjectURL(blob);
                    const printWindow = window.open(url, '_blank');
                    printWindow.onload = function () {
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