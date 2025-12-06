@extends('layouts.staff')

@section('title', 'Patient Flow - Staff Dashboard')

@push('styles')
<style>
    .flow-card {
        transition: all 0.2s ease;
    }
    .flow-card:hover {
        transform: translateY(-2px);
    }
    .flow-column {
        min-width: 280px;
    }
    .pulse-dot {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    .waiting-urgent {
        animation: urgentPulse 1s infinite;
    }
    @keyframes urgentPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        50% { box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
    }
</style>
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 rounded-2xl p-6 shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="text-white">
                    <h1 class="text-2xl md:text-3xl font-bold flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class='bx bx-transfer-alt text-2xl'></i>
                        </div>
                        Patient Flow
                    </h1>
                    <p class="text-amber-100 mt-2 text-sm md:text-base">Real-time monitoring of patient journey from arrival to payment</p>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-white">
                        <div class="flex items-center gap-2">
                            <i class='bx bx-calendar text-lg'></i>
                            <span class="font-medium">{{ now()->format('l, d M Y') }}</span>
                        </div>
                    </div>
                    <button onclick="refreshData()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-amber-600 rounded-xl hover:bg-amber-50 transition-all font-semibold shadow-md hover:shadow-lg">
                        <i class='bx bx-refresh text-xl' id="refreshIcon"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <!-- Scheduled -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center">
                        <i class='bx bx-calendar text-slate-600 text-2xl'></i>
                    </div>
                    <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded-full">WAITING</span>
                </div>
                <p class="text-3xl font-bold text-gray-900" id="stat-scheduled">{{ $stats['scheduled'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Scheduled</p>
            </div>

            <!-- Checked In -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                        <i class='bx bx-user-check text-blue-600 text-2xl'></i>
                    </div>
                    <span class="text-xs font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded-full">ARRIVED</span>
                </div>
                <p class="text-3xl font-bold text-gray-900" id="stat-checked-in">{{ $stats['checked_in'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Checked In</p>
            </div>

            <!-- In Consultation -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-amber-200 rounded-xl flex items-center justify-center">
                        <i class='bx bx-user-voice text-amber-600 text-2xl'></i>
                    </div>
                    <span class="text-xs font-medium text-amber-600 bg-amber-100 px-2 py-1 rounded-full pulse-dot">ACTIVE</span>
                </div>
                <p class="text-3xl font-bold text-gray-900" id="stat-in-consultation">{{ $stats['in_consultation'] }}</p>
                <p class="text-sm text-gray-500 mt-1">In Consultation</p>
            </div>

            <!-- Completed -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                        <i class='bx bx-receipt text-purple-600 text-2xl'></i>
                    </div>
                    <span class="text-xs font-medium text-purple-600 bg-purple-100 px-2 py-1 rounded-full">BILLING</span>
                </div>
                <p class="text-3xl font-bold text-gray-900" id="stat-completed">{{ $stats['completed'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Pending Payment</p>
            </div>

            <!-- Paid -->
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                        <i class='bx bx-check-circle text-green-600 text-2xl'></i>
                    </div>
                    <span class="text-xs font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full">DONE</span>
                </div>
                <p class="text-3xl font-bold text-gray-900" id="stat-paid">{{ $stats['paid'] }}</p>
                <p class="text-sm text-gray-500 mt-1">Paid & Complete</p>
            </div>

            <!-- Total -->
            <div class="bg-gradient-to-br from-amber-500 via-orange-500 to-amber-600 rounded-2xl p-5 shadow-lg">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class='bx bx-group text-white text-2xl'></i>
                    </div>
                    <span class="text-xs font-medium text-white/90 bg-white/20 px-2 py-1 rounded-full">TODAY</span>
                </div>
                <p class="text-3xl font-bold text-white" id="stat-total">{{ $stats['total'] }}</p>
                <p class="text-sm text-amber-100 mt-1">Total Patients</p>
            </div>
        </div>

        <!-- Doctor Status -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                    <i class='bx bx-user-pin text-amber-500 text-xl'></i>
                    Doctor Availability
                </h3>
            </div>
            <div class="p-5">
                <div class="flex flex-wrap gap-3">
                    @forelse($doctors as $doctor)
                        <div class="inline-flex items-center gap-3 px-4 py-3 rounded-xl {{ $doctor['status'] === 'busy' ? 'bg-gradient-to-r from-red-50 to-orange-50 border border-red-200' : 'bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200' }} transition-all hover:shadow-md">
                            <div class="relative">
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-600">
                                    {{ substr($doctor['name'], 0, 1) }}
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 w-4 h-4 rounded-full border-2 border-white {{ $doctor['status'] === 'busy' ? 'bg-red-500 animate-pulse' : 'bg-green-500' }}"></span>
                            </div>
                            <div>
                                <p class="font-semibold text-sm {{ $doctor['status'] === 'busy' ? 'text-red-700' : 'text-green-700' }}">
                                    Dr. {{ $doctor['name'] }}
                                </p>
                                @if($doctor['current_patient'])
                                    <p class="text-xs text-red-600">With patient: {{ $doctor['current_patient'] }}</p>
                                @else
                                    <p class="text-xs text-green-600">Available</p>
                                @endif
                            </div>
                            <div class="ml-2 text-center">
                                <p class="text-lg font-bold {{ $doctor['status'] === 'busy' ? 'text-red-600' : 'text-green-600' }}">{{ $doctor['completed'] }}/{{ $doctor['upcoming'] + $doctor['completed'] }}</p>
                                <p class="text-xs text-gray-500">Patients</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No doctors available today</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Flow Progress Indicator -->
        <div class="hidden lg:block bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
            <div class="flex items-center justify-between px-8">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600">
                        <i class='bx bx-calendar text-xl'></i>
                    </div>
                    <span class="text-xs text-gray-500 mt-2">Scheduled</span>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-2 rounded relative">
                    <i class='bx bx-chevron-right absolute -right-1 top-1/2 -translate-y-1/2 text-gray-400'></i>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class='bx bx-user-check text-xl'></i>
                    </div>
                    <span class="text-xs text-gray-500 mt-2">Checked In</span>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-2 rounded relative">
                    <i class='bx bx-chevron-right absolute -right-1 top-1/2 -translate-y-1/2 text-gray-400'></i>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                        <i class='bx bx-user-voice text-xl'></i>
                    </div>
                    <span class="text-xs text-gray-500 mt-2">Consulting</span>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-2 rounded relative">
                    <i class='bx bx-chevron-right absolute -right-1 top-1/2 -translate-y-1/2 text-gray-400'></i>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600">
                        <i class='bx bx-receipt text-xl'></i>
                    </div>
                    <span class="text-xs text-gray-500 mt-2">Payment</span>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-2 rounded relative">
                    <i class='bx bx-chevron-right absolute -right-1 top-1/2 -translate-y-1/2 text-gray-400'></i>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                        <i class='bx bx-check-circle text-xl'></i>
                    </div>
                    <span class="text-xs text-gray-500 mt-2">Complete</span>
                </div>
            </div>
        </div>

        <!-- Kanban Board -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 overflow-x-auto pb-4">
            @include('staff.partials.flow-column', ['title' => 'Scheduled', 'subtitle' => 'Waiting to arrive', 'icon' => 'bx-calendar', 'color' => 'slate', 'appointments' => $scheduled, 'stage' => 'scheduled', 'nextAction' => 'check_in', 'nextLabel' => 'Check In', 'nextIcon' => 'bx-log-in-circle'])
            @include('staff.partials.flow-column', ['title' => 'Checked In', 'subtitle' => 'In waiting room', 'icon' => 'bx-user-check', 'color' => 'blue', 'appointments' => $checkedIn, 'stage' => 'checked_in', 'nextAction' => 'start_consultation', 'nextLabel' => 'Start Consult', 'nextIcon' => 'bx-play-circle'])
            @include('staff.partials.flow-column', ['title' => 'In Consultation', 'subtitle' => 'With doctor', 'icon' => 'bx-user-voice', 'color' => 'amber', 'appointments' => $inConsultation, 'stage' => 'in_consultation', 'nextAction' => 'complete', 'nextLabel' => 'Complete', 'nextIcon' => 'bx-check'])
            @include('staff.partials.flow-column', ['title' => 'Pending Payment', 'subtitle' => 'Ready to pay', 'icon' => 'bx-receipt', 'color' => 'purple', 'appointments' => $completed, 'stage' => 'completed', 'nextAction' => 'mark_paid', 'nextLabel' => 'Process Payment', 'nextIcon' => 'bx-credit-card'])
            @include('staff.partials.flow-column', ['title' => 'Completed', 'subtitle' => 'Paid & done', 'icon' => 'bx-check-circle', 'color' => 'green', 'appointments' => $paid, 'stage' => 'paid', 'nextAction' => null, 'nextLabel' => null, 'nextIcon' => null])
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closePaymentModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 px-6 py-5">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class='bx bx-credit-card text-white text-2xl'></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Process Payment</h3>
                            <p class="text-green-100 text-sm">Select payment method to complete</p>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="paymentAppointmentId">
                <input type="hidden" id="paymentAmount">

                <div class="px-6 py-6">
                    <!-- Payment Amount Display -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-6 text-center">
                        <p class="text-sm text-gray-500 mb-1">Amount to Pay</p>
                        <p class="text-3xl font-bold text-gray-900" id="paymentAmountDisplay">RM 0.00</p>
                    </div>

                    <!-- Payment Methods -->
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Select Payment Method</label>
                    <div class="grid grid-cols-2 gap-3" id="paymentMethods">
                        <button type="button" onclick="selectPaymentMethod('cash')" class="payment-method-btn group p-4 border-2 border-gray-200 rounded-xl hover:border-green-500 hover:bg-green-50 transition-all text-center" data-method="cash">
                            <div class="w-12 h-12 mx-auto bg-green-100 rounded-xl flex items-center justify-center mb-2 group-hover:bg-green-200 transition-colors">
                                <i class='bx bx-money text-green-600 text-2xl'></i>
                            </div>
                            <p class="font-semibold text-gray-700">Cash</p>
                            <p class="text-xs text-gray-400">Pay with cash</p>
                        </button>
                        <button type="button" onclick="selectPaymentMethod('card')" class="payment-method-btn group p-4 border-2 border-gray-200 rounded-xl hover:border-green-500 hover:bg-green-50 transition-all text-center" data-method="card">
                            <div class="w-12 h-12 mx-auto bg-blue-100 rounded-xl flex items-center justify-center mb-2 group-hover:bg-blue-200 transition-colors">
                                <i class='bx bx-credit-card text-blue-600 text-2xl'></i>
                            </div>
                            <p class="font-semibold text-gray-700">Card</p>
                            <p class="text-xs text-gray-400">Credit/Debit card</p>
                        </button>
                        <button type="button" onclick="selectPaymentMethod('online')" class="payment-method-btn group p-4 border-2 border-gray-200 rounded-xl hover:border-green-500 hover:bg-green-50 transition-all text-center" data-method="online">
                            <div class="w-12 h-12 mx-auto bg-purple-100 rounded-xl flex items-center justify-center mb-2 group-hover:bg-purple-200 transition-colors">
                                <i class='bx bx-transfer text-purple-600 text-2xl'></i>
                            </div>
                            <p class="font-semibold text-gray-700">Online</p>
                            <p class="text-xs text-gray-400">Bank transfer</p>
                        </button>
                        <button type="button" onclick="selectPaymentMethod('insurance')" class="payment-method-btn group p-4 border-2 border-gray-200 rounded-xl hover:border-green-500 hover:bg-green-50 transition-all text-center" data-method="insurance">
                            <div class="w-12 h-12 mx-auto bg-amber-100 rounded-xl flex items-center justify-center mb-2 group-hover:bg-amber-200 transition-colors">
                                <i class='bx bx-shield-plus text-amber-600 text-2xl'></i>
                            </div>
                            <p class="font-semibold text-gray-700">Insurance</p>
                            <p class="text-xs text-gray-400">Insurance claim</p>
                        </button>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button" onclick="closePaymentModal()" class="px-5 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="button" onclick="submitPayment()" class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-xl hover:from-green-600 hover:to-emerald-600 font-semibold transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                        <i class='bx bx-check-circle'></i>
                        Confirm Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let selectedPaymentMethod = 'cash';
    let autoRefreshInterval;

    // Start auto-refresh every 30 seconds
    function startAutoRefresh() {
        autoRefreshInterval = setInterval(refreshData, 30000);
    }

    // Stop auto-refresh
    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
    }

    // Refresh data via AJAX
    function refreshData() {
        const refreshIcon = document.getElementById('refreshIcon');
        refreshIcon.classList.add('animate-spin');

        fetch('{{ route("staff.patient-flow.data") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update stats
            document.getElementById('stat-scheduled').textContent = data.stats.scheduled;
            document.getElementById('stat-checked-in').textContent = data.stats.checked_in;
            document.getElementById('stat-in-consultation').textContent = data.stats.in_consultation;
            document.getElementById('stat-completed').textContent = data.stats.completed;
            document.getElementById('stat-paid').textContent = data.stats.paid;
            document.getElementById('stat-total').textContent = data.stats.total;

            refreshIcon.classList.remove('animate-spin');

            // Show toast
            Swal.fire({
                icon: 'success',
                title: 'Refreshed!',
                text: 'Data updated successfully',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        })
        .catch(error => {
            refreshIcon.classList.remove('animate-spin');
            console.error('Error:', error);
        });
    }

    // Get confirmation config based on action
    function getConfirmationConfig(action) {
        const configs = {
            // Forward actions
            'check_in': {
                title: 'Check In Patient?',
                text: 'Confirm that the patient has arrived at the clinic.',
                icon: 'question',
                confirmButtonText: '<i class="bx bx-log-in-circle mr-1"></i> Yes, Check In',
                confirmButtonColor: '#3b82f6',
                iconColor: '#3b82f6'
            },
            'start_consultation': {
                title: 'Start Consultation?',
                text: 'The patient will be moved to "In Consultation" status.',
                icon: 'question',
                confirmButtonText: '<i class="bx bx-play-circle mr-1"></i> Yes, Start',
                confirmButtonColor: '#f59e0b',
                iconColor: '#f59e0b'
            },
            'complete': {
                title: 'Complete Consultation?',
                text: 'Mark this consultation as completed. The patient will proceed to payment.',
                icon: 'question',
                confirmButtonText: '<i class="bx bx-check mr-1"></i> Yes, Complete',
                confirmButtonColor: '#8b5cf6',
                iconColor: '#8b5cf6'
            },
            // Revert actions
            'revert_to_scheduled': {
                title: 'Revert to Scheduled?',
                text: 'The patient will be moved back to "Scheduled" status. They will need to check in again.',
                icon: 'warning',
                confirmButtonText: '<i class="bx bx-undo mr-1"></i> Yes, Revert',
                confirmButtonColor: '#64748b',
                iconColor: '#f59e0b'
            },
            'revert_to_checked_in': {
                title: 'Revert to Checked In?',
                text: 'The patient will be moved back to "Checked In" status in the waiting room.',
                icon: 'warning',
                confirmButtonText: '<i class="bx bx-undo mr-1"></i> Yes, Revert',
                confirmButtonColor: '#3b82f6',
                iconColor: '#f59e0b'
            },
            'revert_to_in_consultation': {
                title: 'Revert to In Consultation?',
                text: 'The patient will be moved back to "In Consultation" status.',
                icon: 'warning',
                confirmButtonText: '<i class="bx bx-undo mr-1"></i> Yes, Revert',
                confirmButtonColor: '#f59e0b',
                iconColor: '#f59e0b'
            },
            'revert_to_completed': {
                title: 'Revert Payment?',
                text: 'The payment will be cancelled and the patient will be moved back to "Pending Payment" status.',
                icon: 'warning',
                confirmButtonText: '<i class="bx bx-undo mr-1"></i> Yes, Revert Payment',
                confirmButtonColor: '#8b5cf6',
                iconColor: '#ef4444'
            }
        };
        return configs[action] || {
            title: 'Confirm Action?',
            text: 'Are you sure you want to proceed?',
            icon: 'question',
            confirmButtonText: 'Yes, Proceed',
            confirmButtonColor: '#f59e0b',
            iconColor: '#f59e0b'
        };
    }

    // Update appointment status
    function updateStatus(appointmentId, action) {
        if (action === 'mark_paid') {
            openPaymentModal(appointmentId);
            return;
        }

        const config = getConfirmationConfig(action);

        Swal.fire({
            title: config.title,
            text: config.text,
            icon: config.icon,
            iconColor: config.iconColor,
            showCancelButton: true,
            confirmButtonColor: config.confirmButtonColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: config.confirmButtonText,
            cancelButtonText: '<i class="bx bx-x mr-1"></i> Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-5 py-2.5',
                cancelButton: 'rounded-xl px-5 py-2.5'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Processing...',
                    html: '<div class="flex items-center justify-center gap-2"><i class="bx bx-loader-alt bx-spin text-2xl text-amber-500"></i><span>Updating status...</span></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false
                });

                fetch(`{{ url('staff/patient-flow') }}/${appointmentId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ action: action })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-2xl'
                            }
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to update status',
                            customClass: {
                                popup: 'rounded-2xl'
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update status',
                        customClass: {
                            popup: 'rounded-2xl'
                        }
                    });
                });
            }
        });
    }

    // Payment modal functions
    function openPaymentModal(appointmentId, amount = 0) {
        document.getElementById('paymentAppointmentId').value = appointmentId;
        document.getElementById('paymentAmountDisplay').textContent = '{{ get_currency_symbol() }} ' + parseFloat(amount).toFixed(2);
        document.getElementById('paymentModal').classList.remove('hidden');
        selectPaymentMethod('cash');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    function selectPaymentMethod(method) {
        selectedPaymentMethod = method;
        document.querySelectorAll('.payment-method-btn').forEach(btn => {
            btn.classList.remove('border-green-500', 'bg-green-50', 'ring-2', 'ring-green-200');
            btn.classList.add('border-gray-200');
        });
        const selectedBtn = document.querySelector(`.payment-method-btn[data-method="${method}"]`);
        selectedBtn.classList.remove('border-gray-200');
        selectedBtn.classList.add('border-green-500', 'bg-green-50', 'ring-2', 'ring-green-200');
    }

    function submitPayment() {
        const appointmentId = document.getElementById('paymentAppointmentId').value;
        const amount = document.getElementById('paymentAmountDisplay').textContent;
        const methodLabels = {
            'cash': 'Cash',
            'card': 'Credit/Debit Card',
            'online': 'Online Transfer',
            'insurance': 'Insurance'
        };

        closePaymentModal();

        // Show confirmation
        Swal.fire({
            title: 'Confirm Payment?',
            html: `
                <div class="text-left py-2">
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-gray-600">Amount:</span>
                        <span class="font-bold text-lg text-green-600">${amount}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Payment Method:</span>
                        <span class="font-semibold">${methodLabels[selectedPaymentMethod]}</span>
                    </div>
                </div>
            `,
            icon: 'question',
            iconColor: '#10b981',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bx bx-check-circle mr-1"></i> Confirm Payment',
            cancelButtonText: '<i class="bx bx-x mr-1"></i> Cancel',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-5 py-2.5',
                cancelButton: 'rounded-xl px-5 py-2.5'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show processing
                Swal.fire({
                    title: 'Processing Payment...',
                    html: '<div class="flex items-center justify-center gap-2"><i class="bx bx-loader-alt bx-spin text-2xl text-green-500"></i><span>Recording payment...</span></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false
                });

                fetch(`{{ url('staff/patient-flow') }}/${appointmentId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'mark_paid',
                        payment_method: selectedPaymentMethod
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Payment Recorded!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'rounded-2xl'
                            }
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'Failed to record payment',
                            customClass: {
                                popup: 'rounded-2xl'
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to record payment',
                        customClass: {
                            popup: 'rounded-2xl'
                        }
                    });
                });
            }
        });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        startAutoRefresh();
    });

    // Stop auto-refresh when page is hidden
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
        }
    });
</script>
@endpush

