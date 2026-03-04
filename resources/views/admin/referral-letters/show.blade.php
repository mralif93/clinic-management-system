@extends('layouts.admin')
@section('title', 'Referral Letter — ' . $letter->referral_number)
@section('page-title', 'Referral Letter')

@section('content')
    <div class="space-y-5">

        {{-- Header bar --}}
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.referral-letters.index') }}"
                    class="w-9 h-9 bg-white border border-gray-200 rounded-xl flex items-center justify-center shadow-sm hover:shadow-md transition text-gray-600">
                    <i class='bx bx-arrow-back text-lg'></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $letter->referral_number }}</h2>
                    <p class="text-sm text-gray-500">
                        Patient: <strong>{{ $letter->patient->full_name ?? 'N/A' }}</strong>
                        &bull; Doctor: <strong>Dr. {{ $letter->doctor->full_name ?? 'N/A' }}</strong>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                @if($letter->isIssued())
                    <span
                        class="px-3 py-1.5 text-xs font-semibold rounded-xl bg-emerald-100 text-emerald-700 flex items-center gap-1">
                        <i class='bx bx-check-circle'></i> Issued
                    </span>
                @else
                    <span
                        class="px-3 py-1.5 text-xs font-semibold rounded-xl bg-amber-100 text-amber-700 flex items-center gap-1">
                        <i class='bx bx-edit-alt'></i> Draft
                    </span>
                @endif

                <button onclick="printLetter()" id="printBtn"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition shadow-sm">
                    <i class='bx bx-printer'></i> Print
                </button>
                <button onclick="downloadLetter()" id="downloadBtn"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm">
                    <i class='bx bx-download'></i> Download PDF
                </button>

                {{-- Admin delete --}}
                <form id="adminDeleteForm" action="{{ route('admin.referral-letters.destroy', $letter->id) }}" method="POST"
                    class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" id="adminDeleteBtn"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-100 transition border border-red-200">
                        <i class='bx bx-trash'></i> Delete
                    </button>
                </form>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
            btn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Preparing...';
            html2pdf().set(getPdfOptions()).from(el).toPdf().get('pdf').then(function (pdf) {
                const url = URL.createObjectURL(pdf.output('blob'));
                const w = window.open(url, '_blank');
                if (w) w.onload = () => w.print();
                btn.disabled = false;
                btn.innerHTML = '<i class="bx bx-printer"></i> Print';
            });
        }
        function downloadLetter() {
            const el = document.getElementById('referral-letter-content');
            const btn = document.getElementById('downloadBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Generating...';
            html2pdf().set(getPdfOptions()).from(el).save().then(function () {
                btn.disabled = false;
                btn.innerHTML = '<i class="bx bx-download"></i> Download PDF';
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