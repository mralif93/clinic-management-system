@extends('layouts.doctor')

@section('title', 'Check In')
@section('page-title', 'Check In')

@push('styles')
<style>
    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 1; }
        100% { transform: scale(1.4); opacity: 0; }
    }
    .pulse-ring { animation: pulse-ring 1.5s infinite; }
    
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .float-animation { animation: float 3s ease-in-out infinite; }
</style>
@endpush

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 -mt-6 -mx-6 p-6">
    <div class="w-full max-w-md">
        <!-- Check-in Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header with Gradient -->
            <div class="bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 px-8 py-10 text-center relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute -top-10 -left-10 w-40 h-40 rounded-full bg-white"></div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 rounded-full bg-white"></div>
                </div>
                
                <!-- Clock Icon -->
                <div class="relative inline-flex items-center justify-center mb-4">
                    <div class="absolute w-24 h-24 bg-white/30 rounded-full pulse-ring"></div>
                    <div class="relative w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg float-animation">
                        <i class='bx bx-time-five text-5xl text-emerald-500'></i>
                    </div>
                </div>
                
                <h1 class="text-2xl font-bold text-white mb-1 relative">Good {{ now()->format('H') < 12 ? 'Morning' : (now()->format('H') < 17 ? 'Afternoon' : 'Evening') }}, Dr.!</h1>
                <p class="text-emerald-100 relative">Please check in to start your shift</p>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- User Info -->
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl p-5 mb-6 border border-emerald-100">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white text-xl font-bold shadow-lg">
                            {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}{{ strtoupper(substr($user->last_name ?? '', 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800">Dr. {{ $user->first_name ?? $user->name }} {{ $user->last_name ?? '' }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Current Time -->
                <div class="text-center mb-6">
                    <p class="text-sm text-gray-500 mb-1">Current Time</p>
                    <p class="text-4xl font-bold text-gray-800" id="currentTime">--:--:--</p>
                    <p class="text-sm text-gray-500 mt-1">{{ now()->format('l, F j, Y') }}</p>
                </div>

                <!-- Late Warning - Show only between 9:16 AM and 11:59 AM -->
                @php
                    $currentHour = now()->hour;
                    $currentMinute = now()->minute;
                    $isLate = ($currentHour == 9 && $currentMinute > 15) || ($currentHour >= 10 && $currentHour < 12);
                @endphp
                @if($isLate)
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class='bx bx-error-circle text-red-500 text-xl'></i>
                        </div>
                        <div>
                            <p class="font-semibold text-red-700">You are late!</p>
                            <p class="text-sm text-red-600">Work starts at 9:00 AM (Grace period until 9:15 AM)</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Check-in Button -->
                <form action="{{ route('doctor.check-in.store') }}" method="POST" id="checkInForm">
                    @csrf
                    <button type="submit" id="checkInBtn"
                        class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white rounded-2xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-3">
                        <i class='bx bx-log-in-circle text-2xl'></i>
                        CHECK IN NOW
                    </button>
                </form>

                <!-- Info Text -->
                <p class="text-center text-sm text-slate-400 mt-6">
                    <i class='bx bx-info-circle mr-1'></i>
                    By checking in, you confirm your attendance for today
                </p>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-white/60 hover:text-white text-sm transition-colors">
                    <i class='bx bx-log-out mr-1'></i> Logout instead
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', { hour12: false });
        document.getElementById('currentTime').textContent = timeStr;
    }
    updateTime();
    setInterval(updateTime, 1000);

    // Check-in confirmation
    document.getElementById('checkInForm').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Confirm Check In?',
            html: `<p class="text-gray-600">You are about to check in for today's shift.</p>
                   <p class="text-sm text-gray-500 mt-2">Time: <strong>${document.getElementById('currentTime').textContent}</strong></p>`,
            icon: 'question',
            iconColor: '#10b981',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bx bx-check mr-1"></i> Yes, Check In',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: { popup: 'rounded-2xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Checking In...',
                    html: '<div class="flex items-center justify-center gap-2"><i class="bx bx-loader-alt bx-spin text-2xl text-emerald-500"></i><span>Recording your attendance...</span></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false
                });
                setTimeout(() => this.submit(), 1500);
            }
        });
    });
</script>
@endpush

