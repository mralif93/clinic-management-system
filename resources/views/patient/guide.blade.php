@extends('layouts.public')

@section('title', 'How to Use QR Code')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 md:mb-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
                    <i class='bx bx-qr text-blue-600 text-3xl'></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">How to Use Your QR Code</h1>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto px-4">A simple guide to using your appointment QR code at our clinic</p>
            </div>

            <!-- Step by Step Guide -->
            <div class="space-y-6">
                <!-- Step 1: Book Appointment -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                                <span class="text-xl font-bold text-yellow-600">1</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Book Your Appointment</h3>
                                <p class="text-gray-600 mt-2">
                                    Select your preferred doctor, service, date and time through our online booking system. 
                                    After submission, your appointment will be in <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-sm font-medium">Pending</span> status.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Wait for Confirmation -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-xl font-bold text-blue-600">2</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Wait for Confirmation</h3>
                                <p class="text-gray-600 mt-2">
                                    Our staff will review and confirm your appointment. Once confirmed, you will see a 
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-sm font-medium">QR Code</span> 
                                    on your appointment details page. Refresh the page to check your confirmation status.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: QR Code Appears -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                <span class="text-xl font-bold text-green-600">3</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Your QR Code</h3>
                                <p class="text-gray-600 mt-2">
                                    Once confirmed, your unique QR code will appear. You can:
                                </p>
                                <ul class="mt-3 space-y-2 text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <i class='bx bx-check text-green-500'></i>
                                        Show it on your phone screen
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <i class='bx bx-check text-green-500'></i>
                                        Screenshot for offline access
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <i class='bx bx-check text-green-500'></i>
                                        Note down your reference number
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- QR Code Example -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                            <div class="flex items-center justify-center gap-6">
                                <div class="text-center">
                                    <div id="example-qr" class="inline-block p-3 bg-white border-2 border-gray-200 rounded-lg"></div>
                                    <p class="mt-2 text-sm text-gray-500">Example QR Code</p>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm text-gray-600">Your QR code contains:</p>
                                    <ul class="mt-2 text-xs text-gray-500 space-y-1">
                                        <li>Reference Number</li>
                                        <li>Patient Name</li>
                                        <li>Doctor Name</li>
                                        <li>Service Type</li>
                                        <li>Date & Time</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Arrive at Clinic -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                                <span class="text-xl font-bold text-orange-600">4</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Arrive at the Clinic</h3>
                                <p class="text-gray-600 mt-2">
                                    When you arrive at our clinic:
                                </p>
                                <ol class="mt-3 space-y-3 text-gray-600">
                                    <li class="flex items-start gap-3">
                                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-xs flex items-center justify-center font-bold">a</span>
                                        <span>Go to the reception counter</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-xs flex items-center justify-center font-bold">b</span>
                                        <span>Show your QR code to the staff</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-orange-100 text-orange-600 text-xs flex items-center justify-center font-bold">c</span>
                                        <span>Staff will scan your QR code to check you in</span>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Doctor Acceptance -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-cyan-100 flex items-center justify-center">
                                <span class="text-xl font-bold text-cyan-600">5</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Doctor Acceptance</h3>
                                <p class="text-gray-600 mt-2">
                                    After check-in, your status changes to <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-sm font-medium">Arrived</span>. 
                                    The doctor will be notified and will accept you when ready.
                                </p>
                                <div class="mt-4 p-4 bg-cyan-50 rounded-xl border border-cyan-200">
                                    <div class="flex items-center gap-3">
                                        <i class='bx bx-bell-ring text-cyan-600 text-2xl'></i>
                                        <div>
                                            <p class="font-medium text-cyan-800">You'll be notified!</p>
                                            <p class="text-sm text-cyan-600">Staff will call you with your room number when the doctor accepts.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 6: Consultation -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                                <span class="text-xl font-bold text-purple-600">6</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900">Consultation & Payment</h3>
                                <p class="text-gray-600 mt-2">
                                    Proceed to the assigned room for your consultation. After consultation, 
                                    proceed to payment and you're all done!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Legend -->
            <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="font-semibold text-gray-900">Appointment Status Legend</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">Pending</span>
                            <span class="text-gray-600 text-sm">Waiting for confirmation</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">Scheduled</span>
                            <span class="text-gray-600 text-sm">Confirmed, awaiting your arrival</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-sm font-medium">Arrived</span>
                            <span class="text-gray-600 text-sm">Checked in, waiting for doctor</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 bg-cyan-100 text-cyan-700 rounded-full text-sm font-medium">With Doctor</span>
                            <span class="text-gray-600 text-sm">Doctor accepted, proceed to room</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-sm font-medium">In Consultation</span>
                            <span class="text-gray-600 text-sm">Currently with doctor</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">Completed</span>
                            <span class="text-gray-600 text-sm">Visit finished</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="mt-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 text-white">
                    <h3 class="font-semibold text-lg flex items-center gap-2">
                        <i class='bx bx-lightbulb text-yellow-300'></i>
                        Helpful Tips
                    </h3>
                    <ul class="mt-4 space-y-3">
                        <li class="flex items-start gap-3">
                            <i class='bx bx-check-circle text-green-300 mt-0.5'></i>
                            <span>Arrive 10-15 minutes before your appointment time</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class='bx bx-check-circle text-green-300 mt-0.5'></i>
                            <span>Take a screenshot of your QR code in case of poor internet connection</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class='bx bx-check-circle text-green-300 mt-0.5'></i>
                            <span>Bring a valid ID for verification</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class='bx bx-check-circle text-green-300 mt-0.5'></i>
                            <span>If you need to cancel, do so at least 24 hours in advance</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-8 text-center">
                <a href="{{ route('patient.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white font-medium rounded-xl hover:bg-gray-800 transition">
                    <i class='bx bx-arrow-back mr-2'></i>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var qrContainer = document.getElementById('example-qr');
            if (qrContainer) {
                QRCode.toCanvas(document.createElement('canvas'), 'EXAMPLE-TOKEN-123', {
                    width: 120,
                    margin: 1,
                    color: {
                        dark: '#1f2937',
                        light: '#ffffff'
                    }
                }, function(error, canvas) {
                    if (!error) {
                        qrContainer.appendChild(canvas);
                    }
                });
            }
        });
    </script>
    @endpush
@endsection
