@props(['appointment'])

@php
    $clinicLogo = get_setting('clinic_logo');
    $clinicName = get_setting('clinic_name', 'Clinic Management System');
    $clinicAddress = get_setting('clinic_address', '123 Medical Street, Health City');
    $clinicPhone = get_setting('clinic_phone', '+1 (555) 123-4567');
    $clinicEmail = get_setting('clinic_email', 'info@clinic.com');
    $currencySymbol = get_currency_symbol();
@endphp

<style>
    #invoice-content {
        width: 100%;
        max-width: 100%;
    }
    @media print {
        #invoice-content {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            font-size: 11px !important;
        }
        #invoice-content * {
            print-color-adjust: exact !important;
            -webkit-print-color-adjust: exact !important;
        }
        .no-print {
            display: none !important;
        }
    }
    @page {
        size: A4 portrait;
        margin: 8mm;
    }
</style>

<div id="invoice-content" class="bg-white overflow-hidden shadow-sm border border-gray-100 rounded-2xl print:rounded-none print:border-none print:shadow-none">
    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 px-5 py-4 text-white">
        <div class="flex justify-between items-start">
            <div class="flex items-center gap-3">
                <!-- Logo -->
                @if($clinicLogo)
                    @if(str_starts_with($clinicLogo, 'data:'))
                        <img src="{{ $clinicLogo }}" alt="{{ $clinicName }}" class="w-11 h-11 object-contain rounded-lg bg-white/20 p-1">
                    @else
                        <img src="{{ asset('storage/' . $clinicLogo) }}" alt="{{ $clinicName }}" class="w-11 h-11 object-contain rounded-lg bg-white/20 p-1">
                    @endif
                @else
                    <div class="w-11 h-11 bg-white/20 backdrop-blur rounded-lg flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($clinicName, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-base font-bold">{{ strtoupper($clinicName) }}</h1>
                    <p class="text-xs text-emerald-100">{{ $clinicAddress }}</p>
                    <p class="text-xs text-emerald-100">Tel: {{ $clinicPhone }} | Email: {{ $clinicEmail }}</p>
                </div>
            </div>
            <div class="text-right">
                <div class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur px-3 py-1.5 rounded-lg">
                    <i class='bx bx-receipt text-lg'></i>
                    <span class="text-base font-bold tracking-wide">INVOICE</span>
                </div>
                <p class="text-emerald-100 mt-1 text-xs font-medium">INV-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p class="text-emerald-200 text-xs">{{ $appointment->appointment_date->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <div class="p-4">

    <!-- Patient & Appointment Info -->
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div class="bg-gray-50 rounded-lg p-3">
            <h3 class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-2 flex items-center gap-1">
                <i class='bx bx-user text-xs'></i> Bill To
            </h3>
            <table class="w-full text-xs">
                <tr>
                    <td class="py-1 text-gray-500 w-24">Patient:</td>
                    <td class="py-1 font-semibold text-gray-900">{{ $appointment->patient->full_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-500">Patient ID:</td>
                    <td class="py-1 font-medium text-gray-900">PT-{{ str_pad($appointment->patient->id ?? 0, 4, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-500">Phone:</td>
                    <td class="py-1 text-gray-900">{{ $appointment->patient->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-500">Email:</td>
                    <td class="py-1 text-gray-900">{{ $appointment->patient->email ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        <div class="bg-gray-50 rounded-lg p-3">
            <h3 class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-2 flex items-center gap-1">
                <i class='bx bx-calendar-check text-xs'></i> Appointment Details
            </h3>
            <table class="w-full text-xs">
                <tr>
                    <td class="py-1 text-gray-500 w-24">Date:</td>
                    <td class="py-1 text-gray-900">{{ $appointment->appointment_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-500">Time:</td>
                    <td class="py-1 text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-500">Doctor:</td>
                    <td class="py-1 text-gray-900">{{ $appointment->doctor->full_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-500">Status:</td>
                    <td class="py-1">
                        @php
                            $statusColors = [
                                'scheduled' => 'bg-blue-100 text-blue-700',
                                'confirmed' => 'bg-green-100 text-green-700',
                                'completed' => 'bg-gray-100 text-gray-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                'no_show' => 'bg-yellow-100 text-yellow-700',
                            ];
                        @endphp
                        <span class="px-1.5 py-0.5 text-[10px] font-semibold rounded {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Service Details -->
    <div class="mb-4 border border-emerald-200 rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 px-3 py-2 flex items-center gap-1">
            <i class='bx bx-receipt text-sm text-white'></i>
            <h3 class="text-xs font-bold text-white">Service Details</h3>
        </div>
        <table class="w-full text-xs">
            <thead>
                <tr class="bg-emerald-50 border-b border-emerald-100">
                    <th class="py-2 px-3 text-left text-[10px] font-bold text-emerald-700 uppercase tracking-wider">Description</th>
                    <th class="py-2 px-3 text-right text-[10px] font-bold text-emerald-700 uppercase tracking-wider w-32">Amount ({{ $currencySymbol }})</th>
                </tr>
            </thead>
            <tbody class="text-xs">
                <tr class="border-b border-gray-100">
                    <td class="py-1.5 px-3 text-gray-800">
                        <div class="font-medium">{{ $appointment->service->name ?? 'Medical Consultation' }}</div>
                        @if($appointment->service && $appointment->service->description)
                            <div class="text-[10px] text-gray-500 mt-0.5">{{ Str::limit($appointment->service->description, 80) }}</div>
                        @endif
                    </td>
                    <td class="py-1.5 px-3 text-right font-semibold text-emerald-600">{{ number_format($appointment->fee ?? 0, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="border-b border-gray-100 bg-gray-50">
                    <td class="py-1.5 px-3 text-gray-600">Subtotal</td>
                    <td class="py-1.5 px-3 text-right font-medium text-gray-900">{{ number_format($appointment->fee ?? 0, 2) }}</td>
                </tr>
                @if($appointment->discount_type && $appointment->discount_value > 0)
                <tr class="border-b border-gray-100 bg-gray-50">
                    <td class="py-1.5 px-3 text-gray-600">
                        Discount
                        <span class="text-[10px] text-gray-500">
                            ({{ $appointment->discount_type === 'percentage' ? $appointment->discount_value . '%' : $currencySymbol . number_format($appointment->discount_value, 2) }})
                        </span>
                    </td>
                    <td class="py-1.5 px-3 text-right font-medium text-red-600">- {{ number_format($appointment->discount_amount, 2) }}</td>
                </tr>
                @endif
            </tfoot>
        </table>
    </div>

    <!-- Total Amount -->
    <div class="flex justify-end mb-4">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-4 py-2 rounded-lg min-w-[180px]">
            <p class="text-[9px] text-emerald-100 uppercase tracking-wider">Total Amount</p>
            <div class="text-xl font-bold flex items-start gap-0.5">
                <span class="text-xs mt-0.5">{{ $currencySymbol }}</span>
                <span>{{ number_format($appointment->final_amount, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Info -->
    <div class="mb-4 bg-gray-50 rounded-lg p-3">
        <div class="flex flex-wrap gap-4 text-xs">
            <div class="flex items-center gap-2">
                <span class="text-gray-500">Payment Status:</span>
                @php
                    $paymentStatusColors = [
                        'unpaid' => 'bg-red-100 text-red-700',
                        'paid' => 'bg-green-100 text-green-700',
                        'partial' => 'bg-yellow-100 text-yellow-700',
                    ];
                @endphp
                <span class="px-1.5 py-0.5 text-[10px] font-semibold rounded {{ $paymentStatusColors[$appointment->payment_status ?? 'unpaid'] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ ucfirst($appointment->payment_status ?? 'Unpaid') }}
                </span>
            </div>
            @if($appointment->payment_method)
            <div class="flex items-center gap-2">
                <span class="text-gray-500">Payment Method:</span>
                <span class="font-medium text-gray-900">{{ \App\Models\Appointment::getPaymentMethods()[$appointment->payment_method] ?? ucfirst($appointment->payment_method) }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Notes -->
    @if($appointment->notes)
    <div class="mb-4">
        <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1 flex items-center gap-1">
            <i class='bx bx-note text-xs'></i> Notes
        </h3>
        <div class="bg-gray-50 p-2 rounded-lg border border-gray-200">
            <p class="text-xs text-gray-700">{{ $appointment->notes }}</p>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="grid grid-cols-2 gap-4 text-gray-500 border-t border-gray-200 pt-3">
        <div>
            <p class="mb-2 text-[9px]">This is a computer-generated document. No signature is required.</p>
            <div class="border-t border-gray-300 pt-1 inline-block">
                <p class="font-bold text-gray-700 text-[10px]">{{ $clinicName }}</p>
                <p class="text-[9px]">Authorized Signatory</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-[9px]">Date Generated: <span class="font-medium text-gray-700">{{ now()->format('d M Y') }}</span></p>
            <p class="text-[9px]">Generated by: <span class="font-medium text-gray-700">{{ Auth::user()->name }}</span></p>
        </div>
    </div>
    </div>
</div>

