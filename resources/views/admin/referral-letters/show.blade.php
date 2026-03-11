@extends('layouts.admin')
@section('title', 'Referral Letter — ' . $letter->referral_number)
@section('page-title', 'Referral Letter')

@section('content')
    <div class="space-y-5">

        <div
            class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-8">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

            <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div
                        class="shrink-0 w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        <i class='hgi-stroke hgi-file-attachment text-3xl'></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">{{ $letter->referral_number }}</h2>
                        <p class="text-blue-100 text-sm mt-1">
                            Patient: <span class="font-bold text-white">{{ $letter->patient->full_name ?? 'N/A' }}</span>
                            &bull; Doctor: <span class="font-bold text-white">Dr.
                                {{ $letter->doctor->full_name ?? 'N/A' }}</span>
                        </p>
                        <div class="mt-2">
                            @if($letter->isIssued())
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/30 border border-emerald-400/30 text-emerald-50">
                                    <i class='hgi-stroke hgi-checkmark-circle-02 mr-1'></i> Issued
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-500/30 border border-amber-400/30 text-amber-50">
                                    <i class='hgi-stroke hgi-pencil-edit-02 mr-1'></i> Draft
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                    <button onclick="printLetter()" id="printBtn"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-gray-900 rounded-xl font-semibold hover:bg-gray-50 transition-all shadow-lg border border-gray-100">
                        <i class='hgi-stroke hgi-printer'></i>
                        Print
                    </button>
                    <button onclick="downloadLetter()" id="downloadBtn"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all shadow-lg border border-blue-100">
                        <i class='hgi-stroke hgi-download-04'></i>
                        Download PDF
                    </button>
                    <form id="adminDeleteForm" action="{{ route('admin.referral-letters.destroy', $letter->id) }}"
                        method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" id="adminDeleteBtn"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-red-600 rounded-xl font-semibold hover:bg-red-50 transition-all shadow-lg border border-red-100">
                            <i class='hgi-stroke hgi-delete-01'></i>
                            Delete
                        </button>
                    </form>
                    <a href="{{ route('admin.referral-letters.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur text-white rounded-xl font-medium hover:bg-white/30 transition-all">
                        <i class='hgi-stroke hgi-arrow-left-01'></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        {{-- Letter card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-8">
                <x-referral-letter-template :letter="$letter" />
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/html2pdf.min.js') }}"></script>
    <script>
        function getPdfOptions() {
            return {
                margin: [8, 8, 8, 8],
                filename: 'ReferralLetter-{{ $letter->referral_number }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true, letterRendering: true, logging: false },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak: { mode: 'avoid-all' }
            };
        }
        function printLetter() {
            const el = document.getElementById('referral-letter-content');
            const btn = document.getElementById('printBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="hgi-stroke hgi-loading-02 bx-spin"></i> Preparing...';
            html2pdf().set(getPdfOptions()).from(el).toPdf().get('pdf').then(function (pdf) {
                const url = URL.createObjectURL(pdf.output('blob'));
                const w = window.open(url, '_blank');
                if (w) w.onload = () => w.print();
                btn.disabled = false;
                btn.innerHTML = '<i class="hgi-stroke hgi-printer"></i> Print';
            });
        }
        function downloadLetter() {
            const el = document.getElementById('referral-letter-content');
            const btn = document.getElementById('downloadBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="hgi-stroke hgi-loading-02 bx-spin"></i> Generating...';
            html2pdf().set(getPdfOptions()).from(el).save().then(function () {
                btn.disabled = false;
                btn.innerHTML = '<i class="hgi-stroke hgi-download-04"></i> Download PDF';
            });
        }
        document.getElementById('adminDeleteBtn')?.addEventListener('click', function () {
            Swal.fire({
                title: 'Delete Referral Letter?',
                text: 'This action is permanent and cannot be undone.',
                icon: 'warning',
                iconColor: '#dc2626',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl', cancelButton: 'rounded-xl' }
            }).then(result => {
                if (result.isConfirmed) document.getElementById('adminDeleteForm').submit();
            });
        });
    </script>
@endpush