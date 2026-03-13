@extends('layouts.doctor', ['hideLayoutTitle' => true])

@section('title', 'Referral Letter — ' . $letter->referral_number)
@section('page-title', 'Referral Letter')

@section('content')
    <div class="space-y-5">

        {{-- ── Header bar ── --}}
        <!-- Page Header -->
        <div
            class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-6">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="{{ route('doctor.referral-letters.index') }}"
                        class="inline-flex items-center gap-1 text-emerald-100 hover:text-white text-sm mb-2 transition">
                        <i class='hgi-stroke hgi-arrow-left-01'></i> Back to Referral Letters
                    </a>
                    <h1 class="text-2xl font-bold flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class='hgi-stroke hgi-file-01 text-xl'></i>
                        </div>
                        {{ $letter->referral_number }}
                    </h1>
                    <p class="text-emerald-100 mt-2">
                        For <strong>{{ $letter->patient->full_name ?? 'N/A' }}</strong>
                        &bull;
                        Referred to <strong>{{ $letter->referred_to_name }}</strong>
                        ({{ $letter->referred_to_specialty }})
                    </p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    {{-- Status badge --}}
                    @if($letter->isIssued())
                        <span
                            class="px-3 py-1.5 text-xs font-semibold rounded-xl bg-white/20 text-white backdrop-blur flex items-center gap-1 border border-white/20">
                            <i class='hgi-stroke hgi-checkmark-circle-02'></i> Issued
                        </span>
                    @else
                        <span
                            class="px-3 py-1.5 text-xs font-semibold rounded-xl bg-amber-400 text-amber-900 backdrop-blur flex items-center gap-1 border border-amber-300">
                            <i class='hgi-stroke hgi-pencil-edit-02'></i> Draft
                        </span>
                    @endif

                    {{-- Print / Download --}}
                    <button onclick="printLetter()" id="printBtn"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur text-white text-sm font-semibold rounded-xl hover:bg-white/30 transition border border-white/20">
                        <i class='hgi-stroke hgi-printer'></i> Print
                    </button>
                    <button onclick="downloadLetter()" id="downloadBtn"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur text-white text-sm font-semibold rounded-xl hover:bg-white/30 transition border border-white/20">
                        <i class='hgi-stroke hgi-download-04'></i> Download
                    </button>

                    @if($letter->isDraft())
                        <a href="{{ route('doctor.referral-letters.edit', $letter->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur text-white text-sm font-semibold rounded-xl hover:bg-white/30 transition border border-white/20">
                            <i class='hgi-stroke hgi-pencil-edit-01'></i> Edit
                        </a>

                        <form id="issueForm" action="{{ route('doctor.referral-letters.issue', $letter->id) }}" method="POST"
                            class="inline">
                            @csrf
                            <button type="button" id="issueBtn"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white text-sm font-semibold rounded-xl hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/30">
                                <i class='hgi-stroke hgi-checkmark-circle-02'></i> Issue
                            </button>
                        </form>

                        <form id="deleteForm" action="{{ route('doctor.referral-letters.destroy', $letter->id) }}" method="POST"
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" id="deleteBtn"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-red-500/80 backdrop-blur text-white text-sm font-semibold rounded-xl hover:bg-red-600 transition border border-red-500/30">
                                <i class='hgi-stroke hgi-delete-01'></i> Delete
                            </button>
                        </form>
                    @endif
                </div>
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
            btn.innerHTML = '<i class="hgi-stroke hgi-loading-02 animate-spin"></i> Preparing...';
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
            btn.innerHTML = '<i class="hgi-stroke hgi-loading-02 animate-spin"></i> Generating...';
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