@extends('layouts.public')

@section('title', 'How It Works')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8 md:mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
                    <i class='hgi-stroke hgi-book-open-01 text-blue-600 text-3xl'></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">How It Works</h1>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto px-4">A simple guide to booking and checking in at our clinic</p>
            </div>

            <!-- Steps -->
            <div class="space-y-4">
                <!-- Step 1 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <span class="font-bold text-blue-600">1</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Register & Book</h3>
                            <p class="text-gray-600 text-sm">Create an account, select your preferred doctor, choose a service, and pick your preferred date & time.</p>
                        </div>
                        <i class='hgi-stroke hgi-calendar-03 text-gray-300 text-2xl'></i>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                            <span class="font-bold text-yellow-600">2</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Wait for Confirmation</h3>
                            <p class="text-gray-600 text-sm">Our staff will review and confirm your appointment. You'll receive a unique QR code once confirmed.</p>
                        </div>
                        <i class='hgi-stroke hgi-clock-02 text-gray-300 text-2xl'></i>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                            <span class="font-bold text-green-600">3</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Get Your QR Code</h3>
                            <p class="text-gray-600 text-sm">View your confirmed appointment with QR code. Take a screenshot for easy access.</p>
                        </div>
                        <i class='hgi-stroke hgi-qr-code text-gray-300 text-2xl'></i>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                            <span class="font-bold text-orange-600">4</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Arrive & Check In</h3>
                            <p class="text-gray-600 text-sm">Show your QR code at the reception. Staff will scan it to check you in.</p>
                        </div>
                        <i class='hgi-stroke hgi-searching text-gray-300 text-2xl'></i>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                            <span class="font-bold text-purple-600">5</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">See the Doctor</h3>
                            <p class="text-gray-600 text-sm">Doctor will accept you when ready. Staff will call you to the consultation room.</p>
                        </div>
                        <i class='hgi-stroke hgi-user-check-01 text-gray-300 text-2xl'></i>
                    </div>
                </div>

                <!-- Step 6 -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                            <span class="font-bold text-teal-600">6</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Complete Visit</h3>
                            <p class="text-gray-600 text-sm">After consultation, proceed to payment. Your visit is complete!</p>
                        </div>
                        <i class='hgi-stroke hgi-checkmark-circle-02 text-gray-300 text-2xl'></i>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="mt-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl  p-6 text-white text-center shadow-lg relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <h3 class="text-xl font-bold mb-2">Ready to Get Started?</h3>
                <p class="text-blue-100 mb-4">Book your first appointment today!</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('register') }}" class="px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition">
                        <i class='hgi-stroke hgi-user-add-01 mr-2'></i>Register
                    </a>
                    <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-700 text-white font-semibold rounded-lg hover:bg-blue-800 transition">
                        <i class='hgi-stroke hgi-login-01 mr-2'></i>Login
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
