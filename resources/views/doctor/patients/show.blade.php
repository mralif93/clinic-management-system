@extends('layouts.doctor', ['hideLayoutTitle' => true])

@section('title', 'Patient Details')
@section('page-title', 'Patient Details')
@section('hide-layout-title', true)

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div
            class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden mb-6">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div
                        class="shrink-0 w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-2xl font-bold shadow-inner border border-white/20 transform transition-transform hover:scale-105">
                        {{ strtoupper(substr($patient->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <a href="{{ route('doctor.patients.index') }}"
                            class="inline-flex items-center gap-1 text-emerald-100 hover:text-white text-sm mb-1 transition">
                            <i class='hgi-stroke hgi-arrow-left-01'></i> Back to Patients
                        </a>
                        <h1 class="text-2xl font-bold">{{ $patient->full_name }}</h1>
                        <span
                            class="inline-flex items-center gap-1 mt-1 px-3 py-1 bg-white/20 backdrop-blur rounded-full text-sm">
                            <i class='hgi-stroke hgi-identity-card'></i> {{ $patient->patient_id ?? 'N/A' }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('doctor.referral-letters.create', ['patient_id' => $patient->id]) }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold text-sm rounded-xl hover:bg-white/30 transition-all shadow-lg">
                        <i class='hgi-stroke hgi-arrow-data-transfer-horizontal'></i>
                        <span>New Referral Letter</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
            <div
                class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Total Appointments</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalAppointments }}</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <i class='hgi-stroke hgi-calendar-03 text-xl'></i>
                    </div>
                </div>
            </div>

            <div
                class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Completed</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $completedAppointments }}</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                        <i class='hgi-stroke hgi-checkmark-circle-02 text-xl'></i>
                    </div>
                </div>
            </div>

            <div
                class="group bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Upcoming</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $upcomingAppointments }}</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                        <i class='hgi-stroke hgi-clock-02 text-xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-user text-blue-500'></i> Basic Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-0 divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">First Name</label>
                            <p class="text-gray-900 font-medium">{{ $patient->first_name }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Last Name</label>
                            <p class="text-gray-900 font-medium">{{ $patient->last_name }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Date of Birth</label>
                            <p class="text-gray-900">
                                {{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') . ' (' . $patient->date_of_birth->age . ' years old)' : 'N/A' }}
                            </p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Gender</label>
                            <p class="text-gray-900">{{ $patient->gender ? ucfirst($patient->gender) : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-call text-emerald-500'></i> Contact Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-0 divide-y divide-gray-100">
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900">{{ $patient->email ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Phone</label>
                            <p class="text-gray-900">{{ $patient->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center justify-between py-3.5">
                            <label class="text-sm font-medium text-gray-500">Address</label>
                            <p class="text-gray-900">{{ $patient->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        @if($patient->medical_history || $patient->allergies)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @if($patient->medical_history)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                                <i class='hgi-stroke hgi-file-01 text-amber-500'></i> Medical History
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-700 leading-relaxed">{{ $patient->medical_history }}</p>
                        </div>
                    </div>
                @endif
                @if($patient->allergies)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                                <i class='hgi-stroke hgi-alert-circle text-red-500'></i> Allergies
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-700 leading-relaxed">{{ $patient->allergies }}</p>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Emergency Contact -->
        @if($patient->emergency_contact_name || $patient->emergency_contact_phone)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-call-02 text-red-500'></i> Emergency Contact
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center justify-between py-2">
                            <label class="text-sm font-medium text-gray-500">Contact Name</label>
                            <p class="text-gray-900 font-medium">{{ $patient->emergency_contact_name ?? 'N/A' }}</p>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <label class="text-sm font-medium text-gray-500">Contact Phone</label>
                            <p class="text-gray-900 font-medium">{{ $patient->emergency_contact_phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Appointments History -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    <i class='hgi-stroke hgi-time-schedule text-purple-500'></i> Appointment History
                </h3>
            </div>
            <div class="p-6">
                @php
                    $pendingApprovalAppointments = $patient->appointments->where('status', 'completed')->whereNull('record_approved_at');
                @endphp
                @if($pendingApprovalAppointments->count() > 0)
                    <div class="mb-5 p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class='hgi-stroke hgi-shield-01 text-white'></i>
                        </div>
                        <div>
                            <p class="font-semibold text-amber-800 text-sm">{{ $pendingApprovalAppointments->count() }}
                                record(s) awaiting your approval</p>
                            <p class="text-amber-700 text-xs mt-0.5">Completed appointments below are marked <span
                                    class="font-semibold">Approval Pending</span>. Click the Approve button on each to finalise
                                the medical record.</p>
                        </div>
                    </div>
                @endif
                @if($patient->appointments->count() > 0)
                    <div class="space-y-4">
                        @foreach($patient->appointments as $appointment)
                            <div
                                class="border border-gray-100 rounded-xl p-4 hover:bg-gray-50/50 hover:shadow-sm transition-all duration-200">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                                    <div class="flex items-start gap-4 flex-1">
                                        <div
                                            class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex flex-col items-center justify-center text-white flex-shrink-0">
                                            <span
                                                class="text-xs font-medium">{{ $appointment->appointment_date->format('M') }}</span>
                                            <span
                                                class="text-lg font-bold leading-none">{{ $appointment->appointment_date->format('d') }}</span>
                                        </div>
                                        <div class="flex-1 space-y-2">
                                            <h5 class="font-semibold text-gray-900">{{ $appointment->service->name ?? 'N/A' }}</h5>
                                            <p class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                            </p>

                                            @if($appointment->diagnosis)
                                                <div
                                                    class="text-sm text-gray-700 bg-blue-50 border-l-4 border-blue-400 px-3 py-2 rounded-r-lg">
                                                    <span class="font-semibold text-blue-800">Diagnosis:</span>
                                                    <span>{!! $appointment->diagnosis !!}</span>
                                                </div>
                                            @endif

                                            @if($appointment->prescription)
                                                <div
                                                    class="text-sm text-gray-700 bg-emerald-50 border-l-4 border-emerald-400 px-3 py-2 rounded-r-lg">
                                                    <span class="font-semibold text-emerald-800">Prescription:</span>
                                                    <span>{!! $appointment->prescription !!}</span>
                                                </div>
                                            @endif

                                            @if($appointment->notes)
                                                <div
                                                    class="text-sm text-gray-700 bg-amber-50 border-l-4 border-amber-400 px-3 py-2 rounded-r-lg">
                                                    <span class="font-semibold text-amber-800">Notes:</span>
                                                    <span>{!! $appointment->notes !!}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 sm:flex-col sm:items-end sm:min-w-[170px]">
                                        @php
                                            $statusColors = [
                                                'scheduled' => 'bg-blue-100 text-blue-700',
                                                'confirmed' => 'bg-emerald-100 text-emerald-700',
                                                'completed' => 'bg-purple-100 text-purple-700',
                                                'cancelled' => 'bg-red-100 text-red-700',
                                                'no_show' => 'bg-amber-100 text-amber-700',
                                            ];
                                            $statusColor = $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $statusColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                        </span>

                                        @if($appointment->status === 'completed')
                                            @if($appointment->record_approved_at)
                                                <div class="flex flex-col items-end">
                                                    <span
                                                        class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-100 text-emerald-700 flex items-center gap-1">
                                                        <i class='hgi-stroke hgi-checkmark-circle-02'></i> Record Approved
                                                    </span>
                                                    <p class="text-[11px] text-gray-500 text-right mt-1">
                                                        Dr. {{ $appointment->recordApprovedBy->name ?? '' }}<br>
                                                        {{ $appointment->record_approved_at->format('M d, Y h:i A') }}
                                                    </p>
                                                </div>
                                            @else
                                                <div class="flex flex-col items-end gap-1">
                                                    <span
                                                        class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-amber-100 text-amber-700 flex items-center gap-1">
                                                        <i class='hgi-stroke hgi-clock-02'></i> Approval Pending
                                                    </span>
                                                    <form class="approve-record-form"
                                                        action="{{ route('doctor.appointments.approve-record', $appointment->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="button"
                                                            class="approve-record-btn inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">
                                                            <i class='hgi-stroke hgi-checkmark-circle-02 mr-1'></i>
                                                            Approve Record
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endif

                                        <a href="{{ route('doctor.appointments.show', $appointment->id) }}"
                                            class="w-8 h-8 flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg transition">
                                            <i class='hgi-stroke hgi-eye text-lg'></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center py-12">
                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                            <i class='hgi-stroke hgi-calendar-03 text-3xl text-gray-400'></i>
                        </div>
                        <p class="text-gray-500 font-medium">No appointment history found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.approve-record-btn');
                if (!btn) return;

                const form = btn.closest('.approve-record-form');
                if (!form) return;

                Swal.fire({
                    title: 'Approve Medical Record?',
                    html: 'This confirms the <strong>diagnosis</strong>, <strong>prescription</strong>, and <strong>notes</strong> are accurate and finalised.',
                    icon: 'question',
                    iconColor: '#059669',
                    showCancelButton: true,
                    confirmButtonText: '<i class="hgi-stroke hgi-checkmark-circle-02"></i> Yes, Approve',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#6b7280',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-4 py-2 font-semibold',
                        cancelButton: 'rounded-xl px-4 py-2 font-semibold',
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush