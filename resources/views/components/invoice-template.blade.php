@props(['appointment'])

@php
    $clinicLogo = get_setting('clinic_logo');
    $clinicName = get_setting('clinic_name', 'Clinic Management System');
    $clinicAddress = get_setting('clinic_address', '123 Medical Street, Health City');
    $clinicPhone = get_setting('clinic_phone', '+1 (555) 123-4567');
    $clinicEmail = get_setting('clinic_email', 'info@clinic.com');
    $currencySymbol = get_currency_symbol();
@endphp

<div id="invoice-content" class="bg-white p-8 max-w-4xl mx-auto border border-gray-200">
    <!-- Header -->
    <div class="border-b-2 border-gray-800 pb-6 mb-6">
        <div class="flex justify-between items-start">
            <div class="flex items-center gap-4">
                <!-- Logo -->
                @if($clinicLogo)
                    @if(str_starts_with($clinicLogo, 'data:'))
                        <img src="{{ $clinicLogo }}" alt="{{ $clinicName }}" class="w-16 h-16 object-contain rounded-lg">
                    @else
                        <img src="{{ asset('storage/' . $clinicLogo) }}" alt="{{ $clinicName }}" class="w-16 h-16 object-contain rounded-lg">
                    @endif
                @else
                    <div class="w-16 h-16 bg-blue-600 rounded-lg flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($clinicName, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ strtoupper($clinicName) }}</h1>
                    <p class="text-sm text-gray-600">{{ $clinicAddress }}</p>
                    <p class="text-sm text-gray-600">Tel: {{ $clinicPhone }} | Email: {{ $clinicEmail }}</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-3xl font-bold text-gray-800 tracking-wide">INVOICE</h2>
                <p class="text-gray-600 mt-1 font-medium">INV-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p class="text-sm text-gray-500">{{ $appointment->appointment_date->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Patient & Appointment Info -->
    <div class="grid grid-cols-2 gap-8 mb-8">
        <div>
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Bill To</h3>
            <table class="w-full text-sm">
                <tr>
                    <td class="py-1 text-gray-600 w-28">Patient:</td>
                    <td class="py-1 font-semibold text-gray-900">{{ $appointment->patient->full_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Patient ID:</td>
                    <td class="py-1 font-medium text-gray-900">PT-{{ str_pad($appointment->patient->id ?? 0, 4, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Phone:</td>
                    <td class="py-1 text-gray-900">{{ $appointment->patient->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Email:</td>
                    <td class="py-1 text-gray-900">{{ $appointment->patient->email ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        <div>
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Appointment Details</h3>
            <table class="w-full text-sm">
                <tr>
                    <td class="py-1 text-gray-600 w-28">Date:</td>
                    <td class="py-1 text-gray-900">{{ $appointment->appointment_date->format('d M Y') }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Time:</td>
                    <td class="py-1 text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Doctor:</td>
                    <td class="py-1 text-gray-900">{{ $appointment->doctor->full_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-600">Status:</td>
                    <td class="py-1">
                        @php
                            $statusColors = [
                                'scheduled' => 'bg-blue-100 text-blue-800',
                                'confirmed' => 'bg-green-100 text-green-800',
                                'completed' => 'bg-gray-100 text-gray-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'no_show' => 'bg-yellow-100 text-yellow-800',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Service Details -->
    <div class="mb-8">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-50 border-y border-gray-200">
                    <th class="py-3 px-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="py-3 px-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Amount ({{ $currencySymbol }})</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                <tr class="border-b border-gray-100">
                    <td class="py-3 px-4 text-gray-800">
                        <div class="font-medium">{{ $appointment->service->name ?? 'Medical Consultation' }}</div>
                        @if($appointment->service && $appointment->service->description)
                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($appointment->service->description, 80) }}</div>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-right font-medium text-gray-900">{{ number_format($appointment->fee ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Totals -->
    <div class="mb-8">
        <div class="flex justify-end">
            <div class="w-72">
                <table class="w-full text-sm">
                    <tr class="border-b border-gray-100">
                        <td class="py-2 text-gray-600">Subtotal:</td>
                        <td class="py-2 text-right font-medium text-gray-900">{{ $currencySymbol }}{{ number_format($appointment->fee ?? 0, 2) }}</td>
                    </tr>
                    @if($appointment->discount_type && $appointment->discount_value > 0)
                    <tr class="border-b border-gray-100">
                        <td class="py-2 text-gray-600">
                            Discount
                            <span class="text-xs text-gray-500">
                                ({{ $appointment->discount_type === 'percentage' ? $appointment->discount_value . '%' : $currencySymbol . number_format($appointment->discount_value, 2) }})
                            </span>:
                        </td>
                        <td class="py-2 text-right font-medium text-red-600">- {{ $currencySymbol }}{{ number_format($appointment->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="bg-gray-900 text-white">
                        <td class="py-3 px-2 font-bold">TOTAL:</td>
                        <td class="py-3 px-2 text-right font-bold text-lg">{{ $currencySymbol }}{{ number_format($appointment->final_amount, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Payment Info -->
    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-600">Payment Status:</span>
                @php
                    $paymentStatusColors = [
                        'unpaid' => 'bg-red-100 text-red-800',
                        'paid' => 'bg-green-100 text-green-800',
                        'partial' => 'bg-yellow-100 text-yellow-800',
                    ];
                @endphp
                <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full {{ $paymentStatusColors[$appointment->payment_status ?? 'unpaid'] ?? 'bg-gray-100 text-gray-800' }}">
                    {{ ucfirst($appointment->payment_status ?? 'Unpaid') }}
                </span>
            </div>
            @if($appointment->payment_method)
            <div>
                <span class="text-gray-600">Payment Method:</span>
                <span class="ml-2 font-medium text-gray-900">{{ \App\Models\Appointment::getPaymentMethods()[$appointment->payment_method] ?? ucfirst($appointment->payment_method) }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Notes -->
    @if($appointment->notes)
    <div class="mb-8">
        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Notes</h3>
        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
            <p class="text-sm text-gray-700">{{ $appointment->notes }}</p>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="grid grid-cols-2 gap-12 text-sm text-gray-600 border-t border-gray-200 pt-8">
        <div>
            <p class="mb-12">This is a computer-generated document. No signature is required.</p>
            <p class="font-bold text-gray-900">{{ $clinicName }}</p>
            <p>Authorized Signatory</p>
        </div>
        <div class="text-right">
            <p class="mb-1">Date Generated: {{ now()->format('d M Y') }}</p>
            <p>Generated by: {{ Auth::user()->name }}</p>
        </div>
    </div>
</div>

