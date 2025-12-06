@extends('layouts.staff')

@section('title', 'Check In')

@push('styles')
<style>
    @keyframes pulse-ring {
        0% { transform: scale(0.8); opacity: 1; }
        100% { transform: scale(1.5); opacity: 0; }
    }
    .pulse-ring {
        animation: pulse-ring 1.5s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .float-animation {
        animation: float 3s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 -mt-6 -mx-6 p-6">
    <div class="w-full max-w-md">
        <!-- Check-in Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header with Gradient -->
            <div class="bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 px-8 py-10 text-center relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute -top-10 -left-10 w-40 h-40 rounded-full bg-white"></div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 rounded-full bg-white"></div>
                </div>
                
                <!-- Clock Icon -->
                <div class="relative inline-flex items-center justify-center mb-4">
                    <div class="absolute w-24 h-24 bg-white/30 rounded-full pulse-ring"></div>
                    <div class="relative w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg float-animation">
                        <i class='bx bx-time-five text-5xl text-amber-500'></i>
                    </div>
                </div>
                
                <h1 class="text-2xl font-bold text-white mb-1 relative">Good {{ now()->format('H') < 12 ? 'Morning' : (now()->format('H') < 17 ? 'Afternoon' : 'Evening') }}!</h1>
                <p class="text-amber-100 relative">Please check in to start your shift</p>
            </div>

            <!-- Body -->
            <div class="px-8 py-8">
                <!-- User Info -->
                <div class="flex items-center gap-4 mb-8 p-4 bg-slate-50 rounded-2xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-md">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">{{ $user->name }}</h3>
                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Current Time -->
                <div class="text-center mb-8">
                    <p class="text-sm text-slate-500 mb-2">Current Time</p>
                    <div class="text-4xl font-bold text-slate-800" id="currentTime">
                        {{ now()->format('h:i:s A') }}
                    </div>
                    <p class="text-slate-500 mt-2">
                        <i class='bx bx-calendar mr-1'></i>
                        {{ now()->format('l, F j, Y') }}
                    </p>
                </div>

                <!-- Late Warning -->
                @if(now()->hour >= 9 && now()->minute > 15)
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <i class='bx bx-error-circle text-red-500 text-xl'></i>
                        </div>
                        <div>
                            <p class="font-semibold text-red-700">You are late!</p>
                            <p class="text-sm text-red-600">Work starts at 9:00 AM</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Check-in Button -->
                <form action="{{ route('staff.check-in.store') }}" method="POST" id="checkInForm">
                    @csrf
                    <button type="submit" id="checkInBtn"
                        class="w-full py-4 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-2xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-3">
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

        <!-- Logout Link -->
        <div class="text-center mt-6">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-white transition-colors text-sm">
                    <i class='bx bx-log-out mr-1'></i>
                    Logout instead
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Update clock every second
    function updateClock() {
        const now = new Date();
        let hours = now.getHours();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12;
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('currentTime').textContent = `${hours}:${minutes}:${seconds} ${ampm}`;
    }
    setInterval(updateClock, 1000);

    // Check-in confirmation
    document.getElementById('checkInForm').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Confirm Check In?',
            html: `<p class="text-gray-600">You are about to check in for today's shift.</p>
                   <p class="text-sm text-gray-500 mt-2">Time: <strong>${document.getElementById('currentTime').textContent}</strong></p>`,
            icon: 'question',
            iconColor: '#f59e0b',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="bx bx-check mr-1"></i> Yes, Check In',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: { popup: 'rounded-2xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Checking In...',
                    html: '<i class="bx bx-loader-alt bx-spin text-3xl text-amber-500"></i>',
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                this.submit();
            }
        });
    });
</script>
@endpush

