@props(['letter'])

@php
    $clinicLogo = get_setting('clinic_logo');
    $clinicName = get_setting('clinic_name', 'Clinic Management System');
    $clinicAddress = get_setting('clinic_address', '123 Medical Street, Health City');
    $clinicPhone = get_setting('clinic_phone', '+1 (555) 123-4567');
    $clinicEmail = get_setting('clinic_email', 'info@clinic.com');

    $doctor = $letter->doctor;
    $patient = $letter->patient;

    $urgencyColors = [
        'routine' => ['bg' => '#d1fae5', 'text' => '#065f46', 'label' => 'Routine'],
        'urgent' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Urgent'],
        'emergency' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Emergency'],
    ];
    $urgency = $urgencyColors[$letter->urgency] ?? $urgencyColors['routine'];
@endphp

<style>
    #referral-letter-content {
        font-family: 'Georgia', 'Times New Roman', serif;
        color: #1a1a2e;
        line-height: 1.6;
    }

    #referral-letter-content * {
        box-sizing: border-box;
    }

    @media print {
        #referral-letter-content {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            font-size: 11pt !important;
        }

        #referral-letter-content * {
            print-color-adjust: exact !important;
            -webkit-print-color-adjust: exact !important;
        }

        .no-print {
            display: none !important;
        }
    }

    @page {
        size: A4 portrait;
        margin: 12mm;
    }
</style>

<div id="referral-letter-content" style="background:#fff; max-width:100%; padding: 32px 40px; border-radius: 8px;">

    {{-- =========================================================
    TOP: Clinic letterhead on LEFT, Logo on RIGHT
    (matches the sample layout)
    ========================================================= --}}
    <table style="width:100%; margin-bottom:28px;">
        <tr>
            <td style="vertical-align:top; padding-right:16px;">
                <p style="font-size:10pt; color:#374151; margin:0 0 2px; font-family: Arial, sans-serif;">
                    <strong style="font-size:11pt; color:#111827;">{{ $clinicName }}</strong>
                </p>
                <p style="font-size:9pt; color:#6b7280; margin:0; font-family:Arial,sans-serif; line-height:1.5;">
                    {!! nl2br(e($clinicAddress)) !!}<br>
                    Tel: {{ $clinicPhone }}<br>
                    {{ $clinicEmail }}
                </p>
            </td>
            <td style="text-align:right; vertical-align:top; width:100px;">
                @if($clinicLogo)
                    @if(str_starts_with($clinicLogo, 'data:'))
                        <img src="{{ $clinicLogo }}" alt="{{ $clinicName }}"
                            style="width:80px; height:80px; object-fit:contain; border-radius:8px; border:1px solid #e5e7eb;">
                    @else
                        <img src="{{ asset('storage/' . $clinicLogo) }}" alt="{{ $clinicName }}"
                            style="width:80px; height:80px; object-fit:contain; border-radius:8px; border:1px solid #e5e7eb;">
                    @endif
                @else
                    <div
                        style="width:80px; height:80px; background:linear-gradient(135deg,#059669,#0d9488); border-radius:8px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:26pt; font-weight:700; line-height:80px; text-align:center;">
                        {{ strtoupper(substr($clinicName, 0, 1)) }}
                    </div>
                @endif
            </td>
        </tr>
    </table>

    {{-- Divider --}}
    <hr style="border:none; border-top:2px solid #059669; margin-bottom:24px;">

    {{-- =========================================================
    TITLE
    ========================================================= --}}
    <div style="text-align:center; margin-bottom:24px;">
        <h2
            style="font-size:16pt; font-weight:700; letter-spacing:2px; text-transform:uppercase; text-decoration:underline; color:#111827; margin:0;">
            Referral Letter
        </h2>
        <p style="font-size:9pt; color:#6b7280; margin:4px 0 0; font-family:Arial,sans-serif;">
            Ref: <strong>{{ $letter->referral_number }}</strong>
            &nbsp;&bull;&nbsp;
            Date:
            <strong>{{ $letter->issued_at ? $letter->issued_at->format('d/m/Y') : $letter->created_at->format('d/m/Y') }}</strong>
            &nbsp;&bull;&nbsp;
            <span style="padding:2px 8px; border-radius:4px; font-size:8pt; font-weight:600;
                         background:{{ $urgency['bg'] }}; color:{{ $urgency['text'] }};">
                {{ $urgency['label'] }}
            </span>
        </p>
    </div>

    {{-- =========================================================
    TO / FROM / PATIENT INFO (2-column table)
    ========================================================= --}}
    <table style="width:100%; margin-bottom:20px; font-family:Arial,sans-serif; font-size:9.5pt;">
        <tr style="vertical-align:top;">
            {{-- Left: To + From --}}
            <td style="width:48%; padding-right:16px;">
                <p style="margin:0 0 4px;"><strong style="color:#059669;">Date:</strong>
                    {{ $letter->issued_at ? $letter->issued_at->format('d/m/Y') : $letter->created_at->format('d/m/Y') }}
                </p>
                <p style="margin:0 0 2px;"><strong style="color:#059669;">To:</strong> {{ $letter->referred_to_name }}
                </p>
                <p style="margin:0 0 2px; color:#374151;">{{ $letter->referred_to_specialty }}</p>
                <p style="margin:0 0 16px; color:#374151;">{{ $letter->referred_to_facility }}</p>

                <p style="margin:0 0 2px;"><strong style="color:#059669;">From:</strong> {{ $clinicName }}</p>
                <p style="margin:0; color:#374151; line-height:1.5;">{!! nl2br(e($clinicAddress)) !!}</p>
            </td>

            {{-- Right: Patient Info box --}}
            <td style="width:52%;">
                <div style="border:1px solid #d1fae5; border-radius:6px; padding:12px 14px; background:#f0fdf4;">
                    <p
                        style="margin:0 0 6px; font-weight:700; font-size:9pt; color:#065f46; text-transform:uppercase; letter-spacing:1px;">
                        Patient Information</p>
                    <table style="width:100%; font-size:9pt;">
                        <tr>
                            <td style="color:#6b7280; width:115px; padding:2px 0;">Patient Name:</td>
                            <td style="font-weight:600; color:#111827; padding:2px 0;">
                                {{ $patient->full_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="color:#6b7280; padding:2px 0;">Patient ID:</td>
                            <td style="color:#374151; padding:2px 0;">{{ $patient->patient_id ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="color:#6b7280; padding:2px 0;">IC / Passport:</td>
                            <td style="color:#374151; padding:2px 0;">{{ $patient->ic_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="color:#6b7280; padding:2px 0;">Date of Birth:</td>
                            <td style="color:#374151; padding:2px 0;">
                                {{ $patient->date_of_birth ? $patient->date_of_birth->format('d M Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="color:#6b7280; padding:2px 0;">Gender:</td>
                            <td style="color:#374151; padding:2px 0;">{{ ucfirst($patient->gender ?? 'N/A') }}</td>
                        </tr>
                        <tr>
                            <td style="color:#6b7280; padding:2px 0;">Contact:</td>
                            <td style="color:#374151; padding:2px 0;">{{ $patient->phone ?? 'N/A' }}</td>
                        </tr>
                        @if($patient->address)
                            <tr>
                                <td style="color:#6b7280; padding:2px 0; vertical-align:top;">Address:</td>
                                <td style="color:#374151; padding:2px 0;">{!! nl2br(e($patient->address)) !!}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </td>
        </tr>
    </table>

    {{-- =========================================================
    SALUTATION
    ========================================================= --}}
    <p style="font-family:Arial,sans-serif; font-size:10pt; margin:0 0 14px; color:#1f2937;">
        Dear {{ $letter->referred_to_name }},
    </p>

    {{-- =========================================================
    REASON / BODY
    ========================================================= --}}
    <div style="margin-bottom:18px;">
        <p
            style="font-family:Arial,sans-serif; font-size:10pt; margin:0 0 8px; font-weight:700; color:#059669; text-transform:uppercase; letter-spacing:0.5px; font-size:9pt;">
            Reason for Referral</p>
        <div
            style="background:#f9fafb; border-left:4px solid #059669; border-radius:0 4px 4px 0; padding:12px 16px; font-family:Arial,sans-serif; font-size:10pt; color:#1f2937; line-height:1.7;">
            {!! nl2br(e($letter->reason)) !!}
        </div>
    </div>

    {{-- =========================================================
    CLINICAL NOTES
    ========================================================= --}}
    @if($letter->clinical_notes)
        <div style="margin-bottom:18px;">
            <p
                style="font-family:Arial,sans-serif; font-size:9pt; margin:0 0 8px; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:0.5px;">
                Clinical Notes / History</p>
            <div
                style="background:#f9fafb; border-left:4px solid #9ca3af; border-radius:0 4px 4px 0; padding:12px 16px; font-family:Arial,sans-serif; font-size:10pt; color:#374151; line-height:1.7;">
                {!! nl2br(e($letter->clinical_notes)) !!}
            </div>
        </div>
    @endif

    {{-- =========================================================
    CLOSING + VALID UNTIL
    ========================================================= --}}
    <p style="font-family:Arial,sans-serif; font-size:10pt; color:#1f2937; margin:0 0 6px;">
        Thank you for your attention to this referral.
    </p>

    @if($letter->valid_until)
        <p style="font-family:Arial,sans-serif; font-size:9pt; color:#6b7280; margin:0 0 24px;">
            <em>This referral letter is valid until <strong>{{ $letter->valid_until->format('d M Y') }}</strong>.</em>
        </p>
    @else
        <div style="margin-bottom:24px;"></div>
    @endif

    {{-- =========================================================
    SIGNATURE
    ========================================================= --}}
    <p style="font-family:Arial,sans-serif; font-size:10pt; color:#1f2937; margin:0 0 40px;">Yours Sincerely,</p>

    <div style="border-top:1px solid #374151; display:inline-block; padding-top:8px; min-width:220px;">
        <p style="margin:0; font-weight:700; font-family:Arial,sans-serif; font-size:10pt; color:#111827;">
            Dr. {{ $doctor->full_name ?? 'N/A' }}
        </p>
        @if($doctor->specialization)
            <p style="margin:2px 0 0; font-size:9pt; color:#374151; font-family:Arial,sans-serif;">
                {{ $doctor->specialization }}
            </p>
        @endif
        @if($doctor->qualification)
            <p style="margin:2px 0 0; font-size:9pt; color:#374151; font-family:Arial,sans-serif;">
                {{ $doctor->qualification }}
            </p>
        @endif
        <p style="margin:2px 0 0; font-size:9pt; color:#374151; font-family:Arial,sans-serif;">
            {{ $clinicName }}
        </p>
    </div>

    {{-- Footer divider --}}
    <hr style="border:none; border-top:1px solid #e5e7eb; margin: 28px 0 8px;">
    <p style="font-family:Arial,sans-serif; font-size:8pt; color:#9ca3af; text-align:center; margin:0;">
        This is a computer-generated document from {{ $clinicName }}.
        &nbsp;|&nbsp; Generated on {{ now()->format('d M Y, h:i A') }}
        &nbsp;|&nbsp; Ref: {{ $letter->referral_number }}
    </p>
</div>