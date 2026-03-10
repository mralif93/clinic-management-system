<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="theme-color" content="{{ $theme === 'emerald' ? '#047857' : '#b45309' }}">
    <title>{{ $name }} - Official Digital ID | {{ $clinicName }}</title>

    <!-- Google Fonts -->
    <link href="{{ asset('fonts/poppins.css') }}" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Hugeicons -->
    <link href='{{ asset("css/hugeicons.css") }}' rel='stylesheet'>

    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Poppins', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .gradient-bg-emerald {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #047857 70%, #10b981 100%);
        }

        .gradient-bg-amber {
            background: linear-gradient(135deg, #78350f 0%, #92400e 40%, #b45309 70%, #f59e0b 100%);
        }

        .card-hologram {
            background: conic-gradient(from 0deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.1));
            animation: spin 4s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Prevent elastic bouncing on iOS */
        html,
        body {
            overscroll-behavior-y: none;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col pt-8 pb-10 px-4 sm:px-6 md:justify-center md:items-center">

    <!-- Verification Badge (Top) -->
    <div class="max-w-md w-full mx-auto mb-6 text-center">
        @if($isExpired)
            <div
                class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 text-red-700 rounded-full font-bold text-sm shadow-sm ring-1 ring-red-200">
                <i class='hgi-stroke hgi-cancel-circle'></i> Identity Card Expired
            </div>
        @else
            <div
                class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-700 rounded-full font-bold text-sm shadow-sm ring-1 ring-green-200">
                <i class='hgi-stroke hgi-tick-double'></i> Verified Official Identity
            </div>
        @endif
        <p class="text-xs text-gray-500 mt-3 font-medium flex items-center justify-center gap-1.5">
            <i class='hgi-stroke hgi-shield-01 text-gray-400'></i> This real-time link confirms identity validity.
        </p>
    </div>

    <!-- Digital Card -->
    <div class="max-w-md w-full mx-auto relative group">
        <!-- Shadow glow effect -->
        <div
            class="absolute -inset-1 bg-gradient-to-r {{ $theme === 'emerald' ? 'from-emerald-600 to-teal-400' : 'from-amber-600 to-yellow-400' }} rounded-3xl blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200">
        </div>

        <div
            class="relative w-full aspect-[10/16] sm:aspect-[4/6] md:aspect-[1.586/1] md:h-64 rounded-2xl overflow-hidden shadow-2xl {{ $theme === 'emerald' ? 'gradient-bg-emerald' : 'gradient-bg-amber' }} flex flex-col">

            <!-- Decorative BG circles -->
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3 pointer-events-none">
            </div>
            <div
                class="absolute bottom-0 left-0 w-40 h-40 bg-black/10 rounded-full translate-y-1/2 -translate-x-1/4 pointer-events-none">
            </div>

            <!-- Card Header -->
            <div class="p-5 md:p-6 pb-2 flex items-center justify-between z-10 shrink-0">
                <div class="flex items-center gap-2.5">
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" class="w-10 h-10 object-contain drop-shadow-md">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shadow-inner">
                            <i class='hgi-stroke hgi-hospital-01 text-white text-xl'></i>
                        </div>
                    @endif
                    <span
                        class="text-white font-black text-sm tracking-widest uppercase leading-tight drop-shadow max-w-[140px] truncate">{{ $clinicName }}</span>
                </div>
                <span
                    class="px-2.5 py-1 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-black text-white tracking-widest uppercase shadow-inner border border-white/20">{{ $type }}</span>
            </div>

            <!-- Card Body / Photo -->
            <div class="p-5 md:p-6 flex-1 flex flex-col md:flex-row items-center md:items-start md:gap-6 z-10">
                <div
                    class="w-28 h-28 md:w-32 md:h-32 rounded-2xl overflow-hidden bg-white/10 flex items-center justify-center flex-shrink-0 shadow-xl border-[3px] border-white/40 backdrop-blur-sm relative z-20 mx-auto md:mx-0 mb-4 md:mb-0">
                    @if($photo)
                        <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-white text-4xl font-black">{{ strtoupper(substr($name, 0, 1)) }}</span>
                    @endif

                    @if($isExpired)
                        <div class="absolute inset-0 bg-red-900/60 backdrop-blur-[2px] flex items-center justify-center">
                            <div
                                class="bg-red-600 text-white text-xs font-black uppercase tracking-widest px-2 py-1 transform -rotate-12 border-2 border-red-400">
                                Expired</div>
                        </div>
                    @endif
                </div>

                <div class="flex-1 text-center md:text-left flex flex-col justify-center h-full min-w-0 w-full">
                    <p class="text-white font-black text-xl md:text-2xl leading-none drop-shadow-md break-words">
                        {{ $name }}</p>
                    <div
                        class="inline-block mt-1.5 md:mt-2 bg-white/15 px-2.5 py-1 rounded w-fit mx-auto md:mx-0 border border-white/10 backdrop-blur-sm">
                        <p
                            class="{{ $theme === 'emerald' ? 'text-emerald-100' : 'text-amber-100' }} text-xs md:text-sm font-bold tracking-wide uppercase">
                            {{ $title }}</p>
                    </div>
                    @if($department)
                        <p class="text-white/80 text-[11px] font-medium tracking-wider uppercase mt-3 drop-shadow-sm">
                    {{ $department }}</p>@endif
                    @if($location)
                        <p
                            class="text-white/90 text-xs md:text-sm mt-1 flex items-center justify-center md:justify-start gap-1">
                    <i class='hgi-stroke hgi-location-pin-01 text-[14px]'></i> {{ $location }}</p>@endif
                </div>
            </div>

            <!-- Card Footer -->
            <div
                class="p-5 md:p-6 pt-3 mt-auto flex items-end justify-between border-t border-white/10 relative z-10 shrink-0">
                <div class="text-left w-1/2">
                    <p class="text-white/50 text-[9px] font-bold uppercase tracking-widest mb-0.5">ID Number</p>
                    <p class="text-white font-black text-sm md:text-base font-mono drop-shadow-md tracking-wider">
                        {{ $id }}</p>
                </div>
                <div class="text-right w-1/2">
                    @if($expires_at)
                        <p class="text-white/50 text-[9px] font-bold uppercase tracking-widest mb-0.5">Valid Until</p>
                        <p
                            class="text-white font-bold text-xs md:text-sm {{ $isExpired ? 'text-red-300' : '' }} drop-shadow">
                            {{ $expires_at->format('d M Y') }}</p>
                    @endif
                </div>
            </div>

            <!-- Hologram line for realism -->
            <div
                class="absolute right-4 md:right-auto md:left-1/2 top-1/2 -translate-y-1/2 md:-translate-x-1/2 w-3 md:w-3/4 h-24 md:h-3 rounded-full opacity-30 card-hologram z-0 pointer-events-none">
            </div>
        </div>
    </div>

    <!-- Secure Footer Note -->
    <div class="max-w-md w-full mx-auto mt-8 text-center text-xs text-gray-400">
        <p>Issued by {{ $clinicName }}</p>
        <p class="mt-1">© {{ date('Y') }} All Rights Reserved.</p>
    </div>

</body>

</html>