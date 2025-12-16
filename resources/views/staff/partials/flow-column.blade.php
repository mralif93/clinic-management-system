@php
    $colorClasses = [
        'slate' => [
            'bg' => 'bg-slate-100',
            'text' => 'text-slate-600',
            'border' => 'border-slate-200',
            'header' => 'bg-gradient-to-r from-slate-50 to-slate-100',
            'gradient' => 'from-slate-500 to-slate-600',
            'light' => 'bg-slate-50',
            'ring' => 'ring-slate-200'
        ],
        'blue' => [
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-600',
            'border' => 'border-blue-200',
            'header' => 'bg-gradient-to-r from-blue-50 to-blue-100',
            'gradient' => 'from-blue-500 to-blue-600',
            'light' => 'bg-blue-50',
            'ring' => 'ring-blue-200'
        ],
        'amber' => [
            'bg' => 'bg-amber-100',
            'text' => 'text-amber-600',
            'border' => 'border-amber-200',
            'header' => 'bg-gradient-to-r from-amber-50 to-amber-100',
            'gradient' => 'from-amber-500 to-orange-500',
            'light' => 'bg-amber-50',
            'ring' => 'ring-amber-200'
        ],
        'purple' => [
            'bg' => 'bg-purple-100',
            'text' => 'text-purple-600',
            'border' => 'border-purple-200',
            'header' => 'bg-gradient-to-r from-purple-50 to-purple-100',
            'gradient' => 'from-purple-500 to-purple-600',
            'light' => 'bg-purple-50',
            'ring' => 'ring-purple-200'
        ],
        'green' => [
            'bg' => 'bg-green-100',
            'text' => 'text-green-600',
            'border' => 'border-green-200',
            'header' => 'bg-gradient-to-r from-green-50 to-emerald-100',
            'gradient' => 'from-green-500 to-emerald-500',
            'light' => 'bg-green-50',
            'ring' => 'ring-green-200'
        ],
    ];
    $colors = $colorClasses[$color] ?? $colorClasses['slate'];
@endphp

<div class="flow-column bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col min-h-[500px] overflow-hidden">
    <!-- Column Header -->
    <div class="{{ $colors['header'] }} px-4 py-4 border-b {{ $colors['border'] }}">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br {{ $colors['gradient'] }} rounded-xl flex items-center justify-center shadow-sm">
                    <i class='bx {{ $icon }} text-white text-xl'></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">{{ $title }}</h3>
                    <p class="text-xs text-gray-500">{{ $subtitle ?? '' }}</p>
                </div>
            </div>
            <div class="flex flex-col items-center">
                <span class="w-8 h-8 bg-white {{ $colors['text'] }} rounded-xl text-sm font-bold flex items-center justify-center shadow-sm border {{ $colors['border'] }}">
                    {{ $appointments->count() }}
                </span>
            </div>
        </div>
    </div>

    <!-- Column Body -->
    <div class="flex-1 p-3 space-y-3 overflow-y-auto max-h-[600px] custom-scrollbar bg-gray-50/50">
        @forelse($appointments as $appointment)
            @php
                $waitTime = null;
                if ($stage !== 'paid' && $appointment->appointment_time) {
                    $appointmentTime = \Carbon\Carbon::parse($appointment->appointment_date->format('Y-m-d') . ' ' . $appointment->appointment_time);
                    if (now()->gt($appointmentTime)) {
                        $waitTime = now()->diffInMinutes($appointmentTime);
                    }
                }
                $isUrgent = $waitTime && $waitTime > 30;
                $isWarning = $waitTime && $waitTime > 15 && $waitTime <= 30;
                $patientName = ($appointment->patient && $appointment->patient->name) ? $appointment->patient->name : 'Unknown Patient';
                $patientInitials = collect(explode(' ', $patientName))->map(fn($n) => substr($n, 0, 1))->take(2)->implode('');
            @endphp

            <div class="flow-card bg-white border-2 {{ $isUrgent ? 'border-red-300 waiting-urgent' : ($isWarning ? 'border-amber-300' : 'border-gray-100') }} rounded-xl p-4 hover:shadow-lg transition-all cursor-pointer group relative overflow-hidden">
                <!-- Urgent/Warning Badge -->
                @if($isUrgent)
                    <div class="absolute top-0 right-0 bg-red-500 text-white text-xs px-2 py-0.5 rounded-bl-lg font-medium">
                        <i class='bx bx-error-circle mr-1'></i>{{ $waitTime }}min wait
                    </div>
                @elseif($isWarning)
                    <div class="absolute top-0 right-0 bg-amber-500 text-white text-xs px-2 py-0.5 rounded-bl-lg font-medium">
                        <i class='bx bx-time mr-1'></i>{{ $waitTime }}min wait
                    </div>
                @endif

                <!-- Patient Info -->
                <div class="flex items-start gap-3 mb-3 {{ ($isUrgent || $isWarning) ? 'mt-4' : '' }}">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $colors['gradient'] }} flex items-center justify-center text-white font-bold text-sm shadow-sm flex-shrink-0">
                        {{ $patientInitials }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 truncate">{{ $patientName }}</p>
                        <p class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                            <i class='bx bx-id-card'></i>
                            {{ $appointment->patient->ic_number ?? 'No IC' }}
                        </p>
                    </div>
                </div>

                <!-- Appointment Details -->
                <div class="space-y-2 mb-3">
                    <!-- Time -->
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-7 h-7 rounded-lg {{ $colors['light'] }} flex items-center justify-center">
                            <i class='bx bx-time {{ $colors['text'] }} text-sm'></i>
                        </div>
                        <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</span>
                    </div>

                    <!-- Doctor -->
                    @if($appointment->doctor)
                        <div class="flex items-center gap-2 text-sm">
                            <div class="w-7 h-7 rounded-lg {{ $colors['light'] }} flex items-center justify-center">
                                <i class='bx bx-user {{ $colors['text'] }} text-sm'></i>
                            </div>
                            <span class="text-gray-600">Dr. {{ $appointment->doctor->user->name ?? 'TBA' }}</span>
                        </div>
                    @endif

                    <!-- Service -->
                    @if($appointment->service)
                        <div class="flex items-center gap-2 text-sm">
                            <div class="w-7 h-7 rounded-lg {{ $colors['light'] }} flex items-center justify-center">
                                <i class='bx bx-plus-circle {{ $colors['text'] }} text-sm'></i>
                            </div>
                            <span class="text-gray-600 truncate">{{ $appointment->service->name }}</span>
                        </div>
                    @endif
                </div>

                <!-- Amount (for payment/completed stage) -->
                @if($stage === 'completed' || $stage === 'paid')
                    <div class="flex items-center justify-between py-3 px-3 -mx-1 rounded-lg {{ $stage === 'paid' ? 'bg-green-50' : 'bg-purple-50' }} mb-3">
                        <span class="text-sm {{ $stage === 'paid' ? 'text-green-700' : 'text-purple-700' }}">
                            @if($stage === 'paid')
                                <i class='bx bx-check-circle mr-1'></i>Paid
                            @else
                                <i class='bx bx-receipt mr-1'></i>Amount Due
                            @endif
                        </span>
                        <span class="text-lg font-bold {{ $stage === 'paid' ? 'text-green-600' : 'text-purple-600' }}">
                            {{ get_currency_symbol() }}{{ number_format($appointment->final_amount ?? $appointment->fee ?? 0, 2) }}
                        </span>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-2">
                    @if($nextAction)
                        @php
                            $paymentAmount = $appointment->final_amount ?? $appointment->fee ?? 0;
                        @endphp
                        <!-- Forward Action Button -->
                        <button onclick="event.stopPropagation(); @if($nextAction === 'mark_paid') openPaymentModal({{ $appointment->id }}, {{ $paymentAmount }}); @else updateStatus({{ $appointment->id }}, '{{ $nextAction }}'); @endif"
                            class="w-full py-2.5 text-sm font-semibold rounded-xl transition-all flex items-center justify-center gap-2
                            @if($stage === 'completed')
                                bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white shadow-sm hover:shadow-md
                            @elseif($stage === 'scheduled')
                                bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white shadow-sm hover:shadow-md
                            @elseif($stage === 'checked_in')
                                bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white shadow-sm hover:shadow-md
                            @elseif($stage === 'in_consultation')
                                bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white shadow-sm hover:shadow-md
                            @else
                                bg-gray-100 hover:bg-gray-200 text-gray-700
                            @endif">
                            <i class='bx {{ $nextIcon ?? 'bx-right-arrow-alt' }}'></i>
                            {{ $nextLabel }}
                        </button>
                    @else
                        <!-- Completed - View Receipt -->
                        <a href="{{ route('staff.appointments.show', $appointment->id) }}"
                            class="w-full py-2.5 text-sm font-semibold rounded-xl transition-all flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700">
                            <i class='bx bx-receipt'></i>
                            View Details
                        </a>
                    @endif

                    <!-- Revert/Go Back Button -->
                    @php
                        $revertAction = null;
                        $revertLabel = null;
                        if ($stage === 'checked_in') {
                            $revertAction = 'revert_to_scheduled';
                            $revertLabel = 'Back to Scheduled';
                        } elseif ($stage === 'in_consultation') {
                            $revertAction = 'revert_to_checked_in';
                            $revertLabel = 'Back to Checked In';
                        } elseif ($stage === 'completed') {
                            $revertAction = 'revert_to_in_consultation';
                            $revertLabel = 'Back to Consultation';
                        } elseif ($stage === 'paid') {
                            $revertAction = 'revert_to_completed';
                            $revertLabel = 'Revert Payment';
                        }
                    @endphp

                    @if($revertAction)
                        <button onclick="event.stopPropagation(); updateStatus({{ $appointment->id }}, '{{ $revertAction }}')"
                            class="w-full py-2 text-xs font-medium rounded-lg transition-all flex items-center justify-center gap-1.5 border border-gray-200 text-gray-500 hover:bg-gray-100 hover:text-gray-700 hover:border-gray-300">
                            <i class='bx bx-undo'></i>
                            {{ $revertLabel }}
                        </button>
                    @endif
                </div>

                <!-- Quick View Link -->
                <a href="{{ route('staff.appointments.show', $appointment->id) }}"
                    class="block text-center text-xs text-gray-400 hover:text-amber-500 mt-3 transition-colors">
                    <i class='bx bx-link-external mr-1'></i>View Full Details
                </a>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <div class="w-16 h-16 rounded-full {{ $colors['light'] }} flex items-center justify-center mb-3">
                    <i class='bx bx-inbox {{ $colors['text'] }} text-3xl'></i>
                </div>
                <p class="text-sm font-medium text-gray-500">No patients</p>
                <p class="text-xs text-gray-400 mt-1">{{ $subtitle ?? 'Empty queue' }}</p>
            </div>
        @endforelse
    </div>
</div>

