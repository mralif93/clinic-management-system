@extends('layouts.staff')

@section('title', 'QR Scanner - Check In')

@push('styles')
<style>
    #scanner-container {
        position: relative;
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }
    #video-preview {
        width: 100%;
        border-radius: 1rem;
        background: #000;
    }
    #scanner-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 70%;
        height: 70%;
        border: 3px solid #3b82f6;
        border-radius: 1rem;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.3);
    }
    #scanner-overlay::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background: #3b82f6;
        animation: scan 2s linear infinite;
    }
    @keyframes scan {
        0% { top: 0; }
        50% { top: 100%; }
        100% { top: 0; }
    }
    .patient-card {
        animation: slideIn 0.3s ease-out;
    }
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-600 rounded-2xl p-6 shadow-lg">
            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="text-white">
                    <h1 class="text-2xl md:text-3xl font-bold flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <i class='hgi-stroke hgi-qr-code text-2xl'></i>
                        </div>
                        QR Check-In Scanner
                    </h1>
                    <p class="text-blue-100 mt-2">Scan patient QR code to check them in</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="toggleCamera()" id="toggleCameraBtn" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-blue-600 rounded-xl hover:bg-blue-50 transition-all font-semibold shadow-md">
                        <i class='hgi-stroke hgi-camera-01 text-xl'></i>
                        <span id="toggleCameraText">Start Scanner</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Scanner Section -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-camera-01 text-blue-500 text-xl'></i>
                        Scanner
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Video Preview -->
                    <div id="scanner-container" class="mb-6">
                        <video id="video-preview" autoplay playsinline class="hidden"></video>
                        <div id="scanner-overlay" class="hidden"></div>
                        <div id="scanner-placeholder" class="aspect-square bg-gray-100 rounded-xl flex flex-col items-center justify-center">
                            <i class='hgi-stroke hgi-qr-code text-6xl text-gray-300 mb-4'></i>
                            <p class="text-gray-500 text-center px-4">Click "Start Scanner" to begin scanning QR codes</p>
                        </div>
                    </div>

                    <!-- Manual Token Input -->
                    <div class="border-t border-gray-100 pt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Or enter reference number manually:
                        </label>
                        <div class="flex gap-3">
                            <input type="text" id="manual-token" placeholder="Enter 12-character code" maxlength="12"
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-mono text-center uppercase tracking-wider">
                            <button onclick="verifyManualToken()" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition">
                                Verify
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Result Section -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                        <i class='hgi-stroke hgi-user text-green-500 text-xl'></i>
                        Patient Information
                    </h3>
                </div>
                <div class="p-6" id="result-container">
                    <div id="empty-state" class="text-center py-12">
                        <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class='hgi-stroke hgi-qr-code text-gray-300 text-4xl'></i>
                        </div>
                        <p class="text-gray-500">Scan a QR code to see patient information</p>
                    </div>

                    <!-- Patient Card (Hidden by default) -->
                    <div id="patient-card" class="hidden">
                        <div class="patient-card">
                            <!-- Status Badge -->
                            <div id="status-badge" class="mb-4"></div>

                            <!-- Patient Info -->
                            <div class="flex items-center gap-4 mb-6">
                                <div id="patient-avatar" class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white text-xl font-bold"></div>
                                <div>
                                    <h4 id="patient-name" class="text-xl font-bold text-gray-900"></h4>
                                    <p id="patient-ic" class="text-sm text-gray-500"></p>
                                </div>
                            </div>

                            <!-- Appointment Details -->
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <i class='hgi-stroke hgi-user-md text-blue-500 text-xl'></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Doctor</p>
                                        <p id="doctor-name" class="font-semibold text-gray-900"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <i class='hgi-stroke hgi-plus-sign-medical text-green-500 text-xl'></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Service</p>
                                        <p id="service-name" class="font-semibold text-gray-900"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <i class='hgi-stroke hgi-clock-02 text-purple-500 text-xl'></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Time</p>
                                        <p id="appointment-time" class="font-semibold text-gray-900"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <div id="action-buttons"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Waiting Queue -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                    <i class='hgi-stroke hgi-user-check text-amber-500 text-xl'></i>
                    Waiting for Doctor Acceptance
                </h3>
                <button onclick="refreshWaitingQueue()" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    <i class='hgi-stroke hgi-refresh mr-1'></i> Refresh
                </button>
            </div>
            <div class="p-6" id="waiting-queue">
                <div id="waiting-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @php
                        $waitingAppointments = \App\Models\Appointment::with(['patient', 'doctor.user', 'service'])
                            ->where('status', 'arrived')
                            ->whereDate('appointment_date', today())
                            ->orderBy('arrived_at')
                            ->get();
                    @endphp
                    @forelse($waitingAppointments as $appt)
                        <div class="p-4 border border-amber-200 bg-amber-50 rounded-xl" data-appointment-id="{{ $appt->id }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-gray-900">{{ $appt->patient->name ?? 'Unknown' }}</span>
                                <span class="text-xs text-amber-600 bg-amber-100 px-2 py-1 rounded-full">
                                    <i class='hgi-stroke hgi-clock-02 mr-1'></i>{{ $appt->arrived_at?->diffForHumans() }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">Dr. {{ $appt->doctor->user->name ?? 'TBA' }}</p>
                            <p class="text-xs text-gray-500">{{ $appt->service->name ?? 'N/A' }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center col-span-full py-8">No patients waiting</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Note: Add your own notification sound file at public/sounds/notification.mp3 -->
@endsection

@push('scripts')
<script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
<script>
    let html5QrCode = null;
    let isScanning = false;
    let waitingPollInterval = null;

    function toggleCamera() {
        if (isScanning) {
            stopScanner();
        } else {
            startScanner();
        }
    }

    function startScanner() {
        const video = document.getElementById('video-preview');
        const overlay = document.getElementById('scanner-overlay');
        const placeholder = document.getElementById('scanner-placeholder');
        
        html5QrCode = new Html5Qrcode("video-preview");
        
        html5QrCode.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess,
            onScanFailure
        ).then(() => {
            isScanning = true;
            video.classList.remove('hidden');
            overlay.classList.remove('hidden');
            placeholder.classList.add('hidden');
            document.getElementById('toggleCameraText').textContent = 'Stop Scanner';
        }).catch(err => {
            console.error('Camera error:', err);
            Swal.fire({
                icon: 'error',
                title: 'Camera Error',
                text: 'Could not access camera. Please check permissions.',
            });
        });
    }

    function stopScanner() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                document.getElementById('video-preview').classList.add('hidden');
                document.getElementById('scanner-overlay').classList.add('hidden');
                document.getElementById('scanner-placeholder').classList.remove('hidden');
                document.getElementById('toggleCameraText').textContent = 'Start Scanner';
            });
        }
    }

    function onScanSuccess(decodedText) {
        try {
            const data = JSON.parse(decodedText);
            if (data.token) {
                verifyToken(data.token);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid QR Code',
                    text: 'This QR code is not a valid appointment code.',
                });
            }
        } catch (e) {
            if (decodedText.length === 12) {
                verifyToken(decodedText);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid QR Code',
                    text: 'Could not read appointment information from QR code.',
                });
            }
        }
    }

    function onScanFailure(error) {
        // Silent failure during scanning
    }

    function verifyManualToken() {
        const token = document.getElementById('manual-token').value.toUpperCase().trim();
        if (token.length !== 12) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Code',
                text: 'Please enter a valid 12-character reference code.',
            });
            return;
        }
        verifyToken(token);
    }

    function verifyToken(token) {
        Swal.fire({
            title: 'Verifying...',
            html: '<div class="flex items-center justify-center gap-2"><i class="hgi-stroke hgi-loading-02 bx-spin text-2xl text-blue-500"></i><span>Checking appointment...</span></div>',
            allowOutsideClick: false,
            showConfirmButton: false
        });

        fetch('{{ route("staff.qr-scanner.verify") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ token: token })
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            displayPatientInfo(data);
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to verify QR code. Please try again.',
            });
        });
    }

    function displayPatientInfo(data) {
        const emptyState = document.getElementById('empty-state');
        const patientCard = document.getElementById('patient-card');
        
        if (!data.success) {
            Swal.fire({
                icon: 'error',
                title: 'Verification Failed',
                text: data.message,
            });
            return;
        }

        const appt = data.appointment;
        
        emptyState.classList.add('hidden');
        patientCard.classList.remove('hidden');

        // Status Badge
        const statusBadge = document.getElementById('status-badge');
        if (data.already_arrived) {
            statusBadge.innerHTML = '<span class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 rounded-full font-medium"><i class="hgi-stroke hgi-clock-02 mr-2"></i>Already Arrived - Waiting for Doctor</span>';
        } else {
            statusBadge.innerHTML = '<span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-full font-medium"><i class="hgi-stroke hgi-checkmark-circle-02 mr-2"></i>Verified - Ready to Check In</span>';
        }

        // Patient Info
        document.getElementById('patient-avatar').textContent = getInitials(appt.patient?.name || 'Unknown');
        document.getElementById('patient-name').textContent = appt.patient?.name || 'Unknown Patient';
        document.getElementById('patient-ic').textContent = appt.patient?.ic_number || 'No IC';
        document.getElementById('doctor-name').textContent = 'Dr. ' + (appt.doctor?.user?.name || 'TBA');
        document.getElementById('service-name').textContent = appt.service?.name || 'N/A';
        document.getElementById('appointment-time').textContent = appt.appointment_time;

        // Action Buttons
        const actionButtons = document.getElementById('action-buttons');
        if (data.already_arrived) {
            actionButtons.innerHTML = `
                <div class="text-center text-amber-600">
                    <i class='hgi-stroke hgi-clock-02 text-2xl mb-2'></i>
                    <p>Patient is waiting for doctor to accept.</p>
                    <p class="text-sm">The system will notify you when the doctor accepts.</p>
                </div>
            `;
        } else {
            actionButtons.innerHTML = `
                <button onclick="checkInPatient('${appt.confirmation_token}')" class="w-full py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold rounded-xl hover:from-green-600 hover:to-emerald-600 transition shadow-md">
                    <i class='hgi-stroke hgi-checkmark-circle-02 mr-2'></i>Check In Patient
                </button>
            `;
        }
    }

    function checkInPatient(token) {
        Swal.fire({
            title: 'Checking In...',
            html: '<div class="flex items-center justify-center gap-2"><i class="hgi-stroke hgi-loading-02 bx-spin text-2xl text-green-500"></i><span>Processing...</span></div>',
            allowOutsideClick: false,
            showConfirmButton: false
        });

        fetch('{{ route("staff.qr-scanner.check-in") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ token: token })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Checked In!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    refreshWaitingQueue();
                    document.getElementById('empty-state').classList.remove('hidden');
                    document.getElementById('patient-card').classList.add('hidden');
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Check In Failed',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to check in patient. Please try again.',
            });
        });
    }

    function getInitials(name) {
        return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
    }

    function refreshWaitingQueue() {
        fetch('{{ route("staff.qr-scanner.waiting") }}')
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('waiting-list');
                if (data.appointments.length === 0) {
                    list.innerHTML = '<p class="text-gray-500 text-center col-span-full py-8">No patients waiting</p>';
                } else {
                    list.innerHTML = data.appointments.map(appt => `
                        <div class="p-4 border ${appt.is_accepted ? 'border-green-200 bg-green-50' : 'border-amber-200 bg-amber-50'} rounded-xl" data-appointment-id="${appt.id}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-gray-900">${appt.patient_name}</span>
                                <span class="text-xs ${appt.is_accepted ? 'text-green-600 bg-green-100' : 'text-amber-600 bg-amber-100'} px-2 py-1 rounded-full">
                                    ${appt.is_accepted ? '<i class="hgi-stroke hgi-checkmark-circle-02 mr-1"></i>Accepted' : '<i class="hgi-stroke hgi-clock-02 mr-1"></i>' + appt.wait_time}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600">Dr. ${appt.doctor_name}</p>
                            <p class="text-xs text-gray-500">${appt.service_name}</p>
                            ${appt.is_accepted ? `<p class="text-sm text-green-600 font-medium mt-2"><i class='hgi-stroke hgi-door-01 mr-1'></i>Room: ${appt.room_number || 'TBA'}</p>` : ''}
                        </div>
                    `).join('');

                    // Check for new acceptances and show alert
                    data.appointments.forEach(appt => {
                        if (appt.is_accepted && !localStorage.getItem('notified_' + appt.id)) {
                            showAcceptanceAlert(appt);
                            localStorage.setItem('notified_' + appt.id, 'true');
                        }
                    });
                }
            });
    }

    function showAcceptanceAlert(appt) {
        // Play sound
        const sound = document.getElementById('alert-sound');
        sound.play().catch(() => {});

        // Show alert
        Swal.fire({
            icon: 'success',
            title: 'Doctor Accepted Patient!',
            html: `
                <div class="text-left">
                    <p class="font-semibold text-lg">${appt.patient_name}</p>
                    <p class="text-gray-600">Dr. ${appt.doctor_name}</p>
                    <p class="text-green-600 font-bold mt-4 text-xl"><i class='hgi-stroke hgi-door-01 mr-2'></i>Room: ${appt.room_number || 'TBA'}</p>
                </div>
            `,
            confirmButtonText: 'Got it!',
            confirmButtonColor: '#10b981',
            customClass: {
                popup: 'rounded-2xl'
            }
        });
    }

    // Start polling for waiting queue updates
    function startWaitingPoll() {
        waitingPollInterval = setInterval(refreshWaitingQueue, 5000);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        startWaitingPoll();
    });

    // Clean up
    window.addEventListener('beforeunload', function() {
        if (waitingPollInterval) {
            clearInterval(waitingPollInterval);
        }
        stopScanner();
    });
</script>
@endpush
