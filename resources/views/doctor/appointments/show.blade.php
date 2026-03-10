@extends('layouts.doctor')

@section('title', 'Appointment Details')
@section('page-title', 'Appointment Details')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <a href="{{ route('doctor.appointments.index') }}" class="inline-flex items-center gap-1 text-emerald-100 hover:text-white text-sm mb-2 transition">
                        <i class='hgi-stroke hgi-arrow-left-01'></i> Back to Appointments
                    </a>
                    <h1 class="text-2xl font-bold flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                            <i class='hgi-stroke hgi-calendar-download-02 text-xl'></i>
                        </div>
                        Appointment Details
                    </h1>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('doctor.appointments.invoice', $appointment->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur text-white font-semibold rounded-xl hover:bg-white/30 transition">
                        <i class='hgi-stroke hgi-invoice-01 mr-2'></i> Invoice
                    </a>
                    <a href="{{ route('doctor.referral-letters.create', ['appointment_id' => $appointment->id]) }}"
                        class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur text-white font-semibold rounded-xl hover:bg-white/30 transition">
                        <i class='hgi-stroke hgi-arrow-data-transfer-horizontal mr-2'></i> Referral Letter
                    </a>
                    <a href="{{ route('doctor.appointments.edit', $appointment->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-white text-emerald-600 font-semibold rounded-xl hover:bg-emerald-50 transition shadow-lg">
                        <i class='hgi-stroke hgi-pencil-edit-01 mr-2'></i> Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Patient Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-user text-blue-500'></i> Patient Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-0 divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Name</label>
                            <p class="text-gray-900 font-medium">{{ $appointment->patient->full_name }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Patient ID</label>
                            <p class="text-gray-900">{{ $appointment->patient->patient_id ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900">{{ $appointment->patient->email ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Phone</label>
                            <p class="text-gray-900">{{ $appointment->patient->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-calendar-03 text-emerald-500'></i> Appointment Details
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-0 divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Date</label>
                            <p class="text-gray-900 font-medium">{{ $appointment->appointment_date->format('M d, Y') }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Time</label>
                            <p class="text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Service</label>
                            <p class="text-gray-900">{{ $appointment->service->name ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            @php
                                $statusColors = [
                                    'scheduled' => 'bg-blue-100 text-blue-700',
                                    'confirmed' => 'bg-cyan-100 text-cyan-700',
                                    'in_progress' => 'bg-amber-100 text-amber-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    'no_show' => 'bg-gray-100 text-gray-700',
                                ];
                                $statusLabels = [
                                    'scheduled' => 'Scheduled',
                                    'confirmed' => 'Checked In',
                                    'in_progress' => 'In Consultation',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                    'no_show' => 'No Show',
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-lg {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$appointment->status] ?? ucfirst(str_replace('_', ' ', $appointment->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <i class='hgi-stroke hgi-credit-card text-purple-500'></i> Payment Information
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @if($appointment->fee)
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-sm text-gray-500 mb-1">Fee</p>
                            <p class="text-xl font-bold text-gray-900">{{ get_currency_symbol() }}{{ number_format($appointment->fee, 2) }}</p>
                        </div>
                    @endif
                    @if($appointment->discount_type && $appointment->discount_value > 0)
                        <div class="bg-red-50 rounded-xl p-4">
                            <p class="text-sm text-gray-500 mb-1">Discount</p>
                            <p class="text-xl font-bold text-red-600">-{{ get_currency_symbol() }}{{ number_format($appointment->discount_amount, 2) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $appointment->discount_display }}</p>
                        </div>
                        <div class="bg-emerald-50 rounded-xl p-4">
                            <p class="text-sm text-gray-500 mb-1">Final Amount</p>
                            <p class="text-xl font-bold text-emerald-600">{{ get_currency_symbol() }}{{ number_format($appointment->final_amount, 2) }}</p>
                        </div>
                    @endif
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500 mb-1">Payment Status</p>
                        @php
                            $paymentStatusColors = [
                                'unpaid' => 'bg-red-100 text-red-700',
                                'paid' => 'bg-emerald-100 text-emerald-700',
                                'partial' => 'bg-amber-100 text-amber-700',
                            ];
                        @endphp
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-lg {{ $paymentStatusColors[$appointment->payment_status ?? 'unpaid'] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($appointment->payment_status ?? 'Unpaid') }}
                        </span>
                    </div>
                    @if($appointment->payment_method)
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-sm text-gray-500 mb-1">Payment Method</p>
                            <p class="text-lg font-semibold text-gray-900">{{ \App\Models\Appointment::getPaymentMethods()[$appointment->payment_method] ?? ucfirst($appointment->payment_method) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Record Approval Card -->
        @if($appointment->status === 'completed')
        <div class="bg-white rounded-2xl shadow-sm border {{ $appointment->record_approved_at ? 'border-emerald-100' : 'border-amber-100' }} overflow-hidden">
            <div class="px-6 py-4 border-b {{ $appointment->record_approved_at ? 'border-emerald-100 bg-emerald-50/50' : 'border-amber-100 bg-amber-50/50' }}">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <i class='hgi-stroke {{ $appointment->record_approved_at ? "hgi-shield-01 text-emerald-500" : "hgi-shield-01 text-amber-500" }}'></i>
                    Medical Record Approval
                </h3>
            </div>
            <div class="p-6">
                @if($appointment->record_approved_at)
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                                <i class='hgi-stroke hgi-tick-double-01 text-emerald-600 text-xl'></i>
                            </div>
                            <div>
                                <p class="font-semibold text-emerald-700">Record Approved</p>
                                <p class="text-sm text-gray-500">
                                    Approved by <strong>Dr. {{ $appointment->recordApprovedBy->name ?? 'Unknown' }}</strong>
                                    on {{ $appointment->record_approved_at->format('d M Y, h:i A') }}
                                </p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-4 py-1.5 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-full">
                            <i class='hgi-stroke hgi-checkmark-circle-02'></i> Approved
                        </span>
                    </div>
                @else
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                                <i class='hgi-stroke hgi-clock-02 text-amber-600 text-xl'></i>
                            </div>
                            <div>
                                <p class="font-semibold text-amber-700">Approval Pending</p>
                                <p class="text-sm text-gray-500">This completed appointment's medical record has not yet been approved. Please review the diagnosis, prescription, and notes below, then approve.</p>
                            </div>
                        </div>
                        <form id="approveRecordForm" action="{{ route('doctor.appointments.approve-record', $appointment->id) }}" method="POST" class="flex-shrink-0">
                            @csrf
                            <button type="button" id="approveRecordBtn"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 active:scale-95 transition-all shadow-lg shadow-emerald-500/30">
                                <i class='hgi-stroke hgi-checkmark-circle-02 text-lg'></i>
                                Approve Record
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Medical Information -->
        @if($appointment->diagnosis || $appointment->prescription || $appointment->notes)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-first-aid-kit text-red-500'></i> Medical Information
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    @if($appointment->diagnosis)
                        <div>
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                <i class='hgi-stroke hgi-search-01 text-gray-400'></i> Diagnosis
                            </label>
                            <div class="text-gray-900 bg-gray-50 p-4 rounded-xl rich-content">{!! $appointment->diagnosis !!}</div>
                        </div>
                    @endif
                    @if($appointment->prescription)
                        <div>
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                <i class='hgi-stroke hgi-medicine-01 text-gray-400'></i> Prescription
                            </label>
                            <div class="text-gray-900 bg-gray-50 p-4 rounded-xl rich-content">{!! $appointment->prescription !!}</div>
                        </div>
                    @endif
                    @if($appointment->notes)
                        <div>
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2 mb-2">
                                <i class='hgi-stroke hgi-note-01 text-gray-400'></i> Notes
                            </label>
                            <div class="text-gray-900 bg-gray-50 p-4 rounded-xl rich-content">{!! $appointment->notes !!}</div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const approveBtn = document.getElementById('approveRecordBtn');
    if (!approveBtn) return;

    approveBtn.addEventListener('click', function () {
        Swal.fire({
            title: 'Approve Medical Record?',
            html: 'This confirms that the <strong>diagnosis</strong>, <strong>prescription</strong>, and <strong>notes</strong> are accurate and finalised.<br><br>This action cannot be undone once saved.',
            icon: 'question',
            iconColor: '#059669',
            showCancelButton: true,
            confirmButtonText: '<i class="hgi-stroke hgi-checkmark-circle-02"></i> Yes, Approve',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#059669',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
            focusConfirm: false,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-5 py-2.5 font-semibold',
                cancelButton: 'rounded-xl px-5 py-2.5 font-semibold',
            }
        }).then(function (result) {
            if (result.isConfirmed) {
                document.getElementById('approveRecordForm').submit();
            }
        });
    });
});
</script>
@endpush