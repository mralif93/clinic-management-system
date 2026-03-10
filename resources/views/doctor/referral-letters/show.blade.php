@extends('layouts.doctor')

@section('title', 'Referral Letter — ' . $letter->referral_number)
@section('page-title', 'Referral Letter')

@section('content')
    <div class="space-y-5">

        {{-- ── Header bar ── --}}
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('doctor.referral-letters.index') }}"
                    class="w-9 h-9 bg-white border border-gray-200 rounded-xl flex items-center justify-center shadow-sm hover:shadow-md transition text-gray-600">
                    <i class='hgi-stroke hgi-arrow-left-01 text-lg'></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $letter->referral_number }}</h2>
                    <p class="text-sm text-gray-500">
                        For <strong>{{ $letter->patient->full_name ?? 'N/A' }}</strong>
                        &bull;
                        Referred to <strong>{{ $letter->referred_to_name }}</strong>
                        ({{ $letter->referred_to_specialty }})
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                {{-- Status badge --}}
                @if($letter->isIssued())
                    <span
                        class="px-3 py-1.5 text-xs font-semibold rounded-xl bg-emerald-100 text-emerald-700 flex items-center gap-1">
                        <i class='hgi-stroke hgi-checkmark-circle-02'></i> Issued
                    </span>
                @else
                    <span
                        class="px-3 py-1.5 text-xs font-semibold rounded-xl bg-amber-100 text-amber-700 flex items-center gap-1">
                        <i class='hgi-stroke hgi-pencil-edit-02'></i> Draft
                    </span>
                @endif

                {{-- Print / Download --}}
                <button onclick="printLetter()" id="printBtn"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-700 text-white text-sm font-semibold rounded-xl hover:bg-gray-800 transition shadow-sm">
                    <i class='hgi-stroke hgi-printer'></i> Print
                </button>
                <button onclick="downloadLetter()" id="downloadBtn"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm">
                    <i class='hgi-stroke hgi-download-04'></i> Download PDF
                </button>

                {{-- Edit (draft only) --}}
                @if($letter->isDraft())
                    <a href="{{ route('doctor.referral-letters.edit', $letter->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-semibold rounded-xl hover:bg-gray-50 transition shadow-sm">
                        <i class='hgi-stroke hgi-pencil-edit-01'></i> Edit
                    </a>

                    {{-- Issue button --}}
                    <form id="issueForm" action="{{ route('doctor.referral-letters.issue', $letter->id) }}" method="POST"
                        class="inline">
                        @csrf
                        <button type="button" id="issueBtn"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition shadow-sm shadow-emerald-500/30">
                            <i class='hgi-stroke hgi-checkmark-circle-02'></i> Issue Letter
                        </button>
                    </form>

                    {{-- Delete button --}}
                    <form id="deleteForm" action="{{ route('doctor.referral-letters.destroy', $letter->id) }}" method="POST"
                        class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" id="deleteBtn"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-100 transition border border-red-200">
                            <i class='hgi-stroke hgi-delete-01'></i> Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- ── Letter card ── --}}
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

        @if($letter->isDraft())
            // Issue button
            document.getElementById('issueBtn')?.addEventListener('click', function () {
                Swal.fire({
                    title: 'Issue Referral Letter?',
                    html: 'Once issued, this letter will be <strong>locked</strong> and can no longer be edited.',
                    icon: 'question',
                    iconColor: '#059669',
                    showCancelButton: true,
                    confirmButtonText: '<i class="hgi-stroke hgi-checkmark-circle-02"></i> Yes, Issue',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#6b7280',
                    reverseButtons: true,
                    customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl', cancelButton: 'rounded-xl' }
                }).then(result => {
                    if (result.isConfirmed) document.getElementById('issueForm').submit();
                });
            });

            // Delete button
            document.getElementById('deleteBtn')?.addEventListener('click', function () {
                Swal.fire({
                    title: 'Delete Draft Letter?',
                    text: 'This action cannot be undone.',
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
                    if (result.isConfirmed) document.getElementById('deleteForm').submit();
                });
            });
        @endif
    </script>
@endpush