<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Staff Portal - Clinic Management System')</title>

    @php
        $logoPath = get_setting('clinic_logo');
        if ($logoPath && str_starts_with($logoPath, 'data:')) {
            $faviconUrl = $logoPath;
        } elseif ($logoPath) {
            $faviconUrl = asset('storage/' . $logoPath);
        } else {
            $faviconUrl = 'data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22></text></svg>';
        }
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

    <!-- Google Fonts - Poppins (Local) -->
    <link href="{{ asset('fonts/poppins.css') }}" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js & Plugins -->
    <script defer src="{{ asset('js/collapse.min.js') }}"></script>
    <script defer src="{{ asset('js/alpine.min.js') }}"></script>

    <!-- Hugeicons -->
    <link href='{{ asset("css/hugeicons.css") }}' rel='stylesheet'>

    <!-- SweetAlert2 -->
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>

    @stack('styles')
</head>

<body class="theme-staff bg-gray-50 font-sans text-base text-gray-900"
    x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
    <x-ui.skip-nav />
    <div class="min-h-screen flex">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="mobileSidebarOpen" x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="mobileSidebarOpen = false"
            class="fixed inset-0 z-40 lg:hidden bg-black/50 backdrop-blur-sm" style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:sticky top-0 left-0 z-50 h-screen w-64 bg-sidebar-dark text-white flex flex-col transition-all duration-300 ease-in-out shadow-sidebar overflow-hidden lg:overflow-visible border-r border-white/5">

            <!-- Logo Section -->
            <div class="flex-shrink-0 p-6 border-b border-white/5">
                @php
                    $logoPath = get_setting('clinic_logo');
                    if ($logoPath && str_starts_with($logoPath, 'data:')) {
                        $logoUrl = $logoPath;
                    } elseif ($logoPath) {
                        $logoUrl = asset('storage/' . $logoPath);
                    } else {
                        $logoUrl = null;
                    }
                    $clinicName = get_setting('clinic_name', 'Clinic Management');
                @endphp

                <div class="flex items-center gap-4">
                    @if($logoUrl)
                        <div
                            class="w-10 h-10 rounded-2xl bg-white/5 p-2 flex items-center justify-center border border-white/10 shadow-lg shrink-0">
                            <img src="{{ $logoUrl }}" alt="{{ $clinicName }}"
                                class="max-h-full max-w-full object-contain filter brightness-110">
                        </div>
                    @else
                        <div
                            class="w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/20 shrink-0">
                            <i class='hgi-stroke hgi-hospital text-xl text-white'></i>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                        <h1 class="text-[12px] font-black text-white leading-tight uppercase tracking-wide break-words">
                            {{ $clinicName }}
                        </h1>
                        <p
                            class="text-[9px] text-amber-400/60 font-bold tracking-widest uppercase mt-0.5 whitespace-nowrap">
                            Staff Portal</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            @php
                $navBase = "group relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-[13px] font-bold transition-all mb-1";
                $navActive = "bg-amber-500/10 text-amber-400 shadow-[inset_0_0_20px_-10px_rgba(59,104,245,0.4)] border border-amber-500/20";
                $navInactive = "text-sidebar-text hover:bg-white/5 hover:text-white border border-transparent";

                $navActiveIconBg = "bg-amber-500";
                $navInactiveIconBg = "bg-white/5";
                $navActiveIconColor = "text-white";
                $navInactiveIconColor = "text-amber-400/70 group-hover:text-amber-400";
            @endphp
            <nav class="flex-1 overflow-y-auto py-6 px-4 custom-scrollbar">
                <!-- Main -->
                <div class="mb-4"
                    x-data="{ open: {{ request()->routeIs('staff.dashboard') || request()->routeIs('staff.patient-flow') || request()->routeIs('staff.qr-scanner') || request()->routeIs('staff.todos.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-[11px] font-black text-white/40 uppercase tracking-widest hover:bg-white/5 hover:text-white/70 transition-all group">
                        <span class="flex items-center gap-2">
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-amber-500/30 group-hover:bg-amber-500 transition-colors"></span>
                            Main
                        </span>
                        <i class='hgi-stroke hgi-arrow-down-01 text-[14px] transition-transform duration-300'
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-collapse>
                        <a href="{{ route('staff.dashboard') }}"
                            class="{{ $navBase }} {{ request()->routeIs('staff.dashboard') ? $navActive : $navInactive }}">
                            <div
                                class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.dashboard') ? $navActiveIconBg : $navInactiveIconBg }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i
                                    class='hgi-stroke hgi-dashboard-square-01 text-lg {{ request()->routeIs('staff.dashboard') ? $navActiveIconColor : $navInactiveIconColor }}'></i>
                            </div>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('staff.patient-flow') }}"
                            class="{{ $navBase }} {{ request()->routeIs('staff.patient-flow') ? $navActive : $navInactive }}">
                            <div
                                class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.patient-flow') ? $navActiveIconBg : $navInactiveIconBg }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i
                                    class='hgi-stroke hgi-arrow-data-transfer-horizontal text-lg {{ request()->routeIs('staff.patient-flow') ? $navActiveIconColor : $navInactiveIconColor }}'></i>
                            </div>
                            <span>Patient Flow</span>
                        </a>
                        <a href="{{ route('staff.qr-scanner') }}"
                            class="{{ $navBase }} {{ request()->routeIs('staff.qr-scanner') ? $navActive : $navInactive }}">
                            <div
                                class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.qr-scanner') ? $navActiveIconBg : $navInactiveIconBg }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i
                                    class='hgi-stroke hgi-qr-code text-lg {{ request()->routeIs('staff.qr-scanner') ? $navActiveIconColor : $navInactiveIconColor }}'></i>
                            </div>
                            <span>QR Scanner</span>
                        </a>
                        <a href="{{ route('staff.todos.index') }}"
                            class="{{ $navBase }} {{ request()->routeIs('staff.todos.*') ? $navActive : $navInactive }}">
                            <div
                                class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.todos.*') ? $navActiveIconBg : $navInactiveIconBg }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i
                                    class='hgi-stroke hgi-task-01 text-lg {{ request()->routeIs('staff.todos.*') ? $navActiveIconColor : $navInactiveIconColor }}'></i>
                            </div>
                            <span>Tasks</span>
                        </a>
                    </div>
                </div>
                <!-- Work -->
                <div class="mb-4"
                    x-data="{ open: {{ request()->routeIs('staff.attendance.*') || request()->routeIs('staff.schedule.*') || request()->routeIs('staff.appointments.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-[11px] font-black text-white/40 uppercase tracking-widest hover:bg-white/5 hover:text-white/70 transition-all group">
                        <span class="flex items-center gap-2">
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-amber-500/30 group-hover:bg-amber-500 transition-colors"></span>
                            Work
                        </span>
                        <i class='hgi-stroke hgi-arrow-down-01 text-[14px] transition-transform duration-300'
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-collapse>
                        <a href="{{ route('staff.attendance.index') }}"
                            class="{{ $navBase }} {{ request()->routeIs('staff.attendance.*') ? $navActive : $navInactive }}">
                            <div
                                class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.attendance.*') ? $navActiveIconBg : $navInactiveIconBg }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i
                                    class='hgi-stroke hgi-clock-02 text-lg {{ request()->routeIs('staff.attendance.*') ? $navActiveIconColor : $navInactiveIconColor }}'></i>
                            </div>
                            <span>Attendance</span>
                        </a>
                        <a href="{{ route('staff.schedule.index') }}"
                            class="{{ $navBase }} {{ request()->routeIs('staff.schedule.index.*') || request()->routeIs('staff.schedule.index') ? $navActive : $navInactive }}">
                            <div
                                class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.schedule.index.*') || request()->routeIs('staff.schedule.index') ? $navActiveIconBg : $navInactiveIconBg }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i
                                    class='hgi-stroke hgi-time-schedule text-lg {{ request()->routeIs('staff.schedule.index.*') || request()->routeIs('staff.schedule.index') ? $navActiveIconColor : $navInactiveIconColor }}'></i>
                            </div>
                            <span>My Schedule</span>
                        </a>
                        <a href="{{ route('staff.appointments.index') }}"
                            class="{{ $navBase }} {{ request()->routeIs('staff.appointments.*') ? $navActive : $navInactive }}">
                            <div
                                class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.appointments.*') ? $navActiveIconBg : $navInactiveIconBg }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i
                                    class='hgi-stroke hgi-calendar-03 text-lg {{ request()->routeIs('staff.appointments.*') ? $navActiveIconColor : $navInactiveIconColor }}'></i>
                            </div>
                            <span>Appointments</span>
                        </a>
                    </div>
                </div>

                <!-- Management -->
                <div class="mb-4"
                    x-data="{ open: {{ request()->routeIs('staff.schedule.doctors.*') || request()->routeIs('staff.patients.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-[11px] font-black text-white/40 uppercase tracking-widest hover:bg-white/5 hover:text-white/70 transition-all group">
                        <span class="flex items-center gap-2">
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-amber-500/30 group-hover:bg-amber-500 transition-colors"></span>
                            Management
                        </span>
                        <i class='hgi-stroke hgi-arrow-down-01 text-[14px] transition-transform duration-300'
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-collapse>
                        <a href="{{ route('staff.schedule.doctors') }}"
                            class="{{ $navBase }} {{ request()->routeIs('staff.schedule.doctors.*') ? $navActive : $navInactive }}">
                            <div
                                class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.schedule.doctors.*') ? $navActiveIconBg : $navInactiveIconBg }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i
                                    class='hgi-stroke hgi-doctor-01 text-lg {{ request()->routeIs('staff.schedule.doctors.*') ? $navActiveIconColor : $navInactiveIconColor }}'></i>
                            </div>
                            <span>Doctors</span>
                        </a>
                        <a href="{{ route('staff.patients.index') }}"
                            class="{{ $navBase }} {{ request()->routeIs('staff.patients.*') ? $navActive : $navInactive }}">
                            <div
                                class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.patients.*') ? $navActiveIconBg : $navInactiveIconBg }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i
                                    class='hgi-stroke hgi-patient text-lg {{ request()->routeIs('staff.patients.*') ? $navActiveIconColor : $navInactiveIconColor }}'></i>
                            </div>
                            <span>Patients</span>
                        </a>
                    </div>
                </div>
                <!-- Personal -->
                <div class="mb-4"
                    x-data="{ open: {{ request()->routeIs('staff.leaves.*') || request()->routeIs('staff.payslips.*') || request()->routeIs('staff.reports.*') || request()->routeIs('staff.digital-card.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-[11px] font-black text-white/40 uppercase tracking-widest hover:bg-white/5 hover:text-white/70 transition-all group">
                        <span class="flex items-center gap-2">
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-amber-500/30 group-hover:bg-amber-500 transition-colors"></span>
                            Personal
                        </span>
                        <i class='hgi-stroke hgi-arrow-down-01 text-[14px] transition-transform duration-300'
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-collapse>
                        <a href="{{ route('staff.leaves.index') }}"
                            class="{{ $navBase }} {{ request()->routeIs('staff.leaves.*') ? $navActive : $navInactive }}">
                            <div
                                class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.leaves.*') ? $navActiveIconBg : $navInactiveIconBg }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i
                                    class='hgi-stroke hgi-clock-01 text-lg {{ request()->routeIs('staff.leaves.*') ? $navActiveIconColor : $navInactiveIconColor }}'></i>
                            </div>
                            <span>Leave</span>
                        </a>
                        <a href="{{ route('staff.payslips.index') }}"
                            class="{{ $navBase }} {{ request()->routeIs('staff.payslips.*') ? $navActive : $navInactive }}">
                            <div
                                class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.payslips.*') ? $navActiveIconBg : $navInactiveIconBg }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i
                                    class='hgi-stroke hgi-invoice text-lg {{ request()->routeIs('staff.payslips.*') ? $navActiveIconColor : $navInactiveIconColor }}'></i>
                            </div>
                            <span>Payslips</span>
                        </a>
                        <a href="{{ route('staff.reports.index') }}"
                            class="{{ $navBase }} {{ request()->routeIs('staff.reports.*') ? $navActive : $navInactive }}">
                            <div
                                class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.reports.*') ? $navActiveIconBg : $navInactiveIconBg }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i
                                    class='hgi-stroke hgi-file-01 text-lg {{ request()->routeIs('staff.reports.*') ? $navActiveIconColor : $navInactiveIconColor }}'></i>
                            </div>
                            <span>Reports</span>
                        </a>
                        <a href="{{ route('staff.digital-card.show') }}"
                            class="{{ $navBase }} {{ request()->routeIs('staff.digital-card.*') ? $navActive : $navInactive }}">
                            <div
                                class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.digital-card.*') ? $navActiveIconBg : $navInactiveIconBg }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i
                                    class='hgi-stroke hgi-identity-card text-lg {{ request()->routeIs('staff.digital-card.*') ? $navActiveIconColor : $navInactiveIconColor }}'></i>
                            </div>
                            <span>Digital Card</span>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Footer Section -->
            <div class="flex-shrink-0 border-t border-white/5 p-4">
                <a href="{{ route('user-guide') }}"
                    class="{{ $navBase }} bg-white/5 text-white/50 hover:bg-white/10 hover:text-white">
                    <div
                        class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class='hgi-stroke hgi-book-open-01 text-lg opacity-70'></i>
                    </div>
                    <span>System Guide</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen min-w-0 transition-all duration-300">
            <!-- Header -->
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-100/50 shadow-sm w-full">
                <div class="flex items-center justify-between px-3 sm:px-4 lg:px-6 h-14 sm:h-15 md:h-16 w-full">
                    <!-- Left Side - Mobile Menu & Breadcrumb -->
                    <div class="flex items-center gap-2 sm:gap-3 md:gap-4 min-w-0">
                        <!-- Mobile Menu Toggle -->
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen"
                            class="lg:hidden p-1.5 sm:p-2 rounded-xl hover:bg-gray-100 transition-colors flex-shrink-0"
                            aria-label="Toggle sidebar">
                            <i class='hgi-stroke hgi-menu-01 text-lg sm:text-xl text-gray-600'></i>
                        </button>

                        <!-- Page Title -->
                        <div class="min-w-0">
                            <h1 class="text-base sm:text-lg font-bold text-gray-900 tracking-tight truncate">
                                @yield('page-title', 'Dashboard')
                            </h1>
                        </div>
                    </div>

                    <!-- Right Side - Actions -->
                    <div class="flex items-center gap-1.5 sm:gap-4 flex-shrink-0">
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open"
                                class="flex items-center gap-2 p-1 rounded-full hover:bg-white hover:shadow-premium transition-all border border-transparent hover:border-gray-100 group">
                                <div class="flex items-center gap-2.5 px-2 py-1">
                                    <div
                                        class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-white text-xs font-black shadow-md group-hover:scale-105 transition-transform">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="w-px h-4 bg-gray-200"></div>
                                    <span
                                        class="text-xs font-bold text-gray-700 group-hover:text-amber-600 transition-colors truncate max-w-[100px]">
                                        {{ Auth::user()->name }}
                                    </span>
                                </div>
                                <i class='hgi-stroke hgi-arrow-down-01 text-gray-400 text-[10px] mr-2 transition-transform duration-300'
                                    :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                                class="absolute right-0 mt-3 w-56 sm:w-60 bg-white rounded-[2rem] shadow-premium border border-gray-100 overflow-hidden z-50 pointer-events-auto"
                                style="display: none;" @click.away="open = false">

                                <!-- Compact Premium User Profile Header -->
                                <div
                                    class="relative p-4 overflow-hidden bg-gradient-to-br from-amber-900 via-amber-800 to-indigo-900 text-white">
                                    <div class="absolute -right-2 -top-4 w-16 h-16 bg-white/5 rounded-full blur-xl">
                                    </div>

                                    <div class="relative flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-sm font-black text-white shadow-lg">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[13px] font-black truncate leading-tight">
                                                {{ Auth::user()->name }}
                                            </p>
                                            <div class="mt-1 flex items-center gap-1.5">
                                                <div
                                                    class="px-1.5 py-0.5 rounded-md bg-white/10 backdrop-blur-md border border-white/10 text-[8px] font-black uppercase tracking-widest text-white/90">
                                                    {{ Auth::user()->staff ? Auth::user()->staff->staff_id : ucfirst('staff') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Compact Menu Items -->
                                <div class="p-2 bg-white">
                                    <div class="space-y-0.5">
                                        <a href="{{ route('staff.profile.show') }}"
                                            class="flex items-center justify-between group px-3 py-2 rounded-2xl hover:bg-amber-50 transition-all duration-300">
                                            <div class="flex items-center gap-2.5">
                                                <div
                                                    class="p-1.5 rounded-xl bg-amber-50 text-amber-600 group-hover:bg-white transition-colors shadow-sm border border-transparent group-hover:border-amber-100">
                                                    <i class='hgi-stroke hgi-user-circle text-base'></i>
                                                </div>
                                                <span
                                                    class="text-xs font-bold text-gray-700 group-hover:text-amber-700">Account</span>
                                            </div>
                                            <i
                                                class='hgi-stroke hgi-arrow-right-01 text-gray-300 group-hover:text-amber-400 opacity-0 group-hover:opacity-100 -translate-x-1 group-hover:translate-x-0 transition-all text-[10px]'></i>
                                        </a>
                                    </div>

                                    <!-- Compact Logout -->
                                    <div class="mt-1.5 pt-1.5 border-t border-gray-50">
                                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                                            @csrf
                                            <button type="submit"
                                                class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50 rounded-2xl transition-all text-left group">
                                                <div
                                                    class="p-1.5 rounded-xl bg-red-50 group-hover:bg-white transition-colors shadow-sm border border-transparent group-hover:border-red-100">
                                                    <i class='hgi-stroke hgi-logout-01 text-base'></i>
                                                </div>
                                                <span>Sign Out</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main id="main-content" class="flex-1 p-4 lg:p-6">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="py-4 px-6 border-t border-gray-100 bg-white">
                <p class="text-center text-xs text-gray-500">
                    &copy; {{ date('Y') }} {{ get_setting('clinic_name', 'Clinic Management System') }}. All rights
                    reserved.
                </p>
            </footer>
        </div>
    </div>

    @stack('scripts')

    <!-- UI Enhancement Scripts -->
    <script src="{{ asset('js/skeleton.js') }}"></script>
    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('js/input-masks.js') }}"></script>
    <script src="{{ asset('js/table-sort.js') }}"></script>
    <script src="{{ asset('js/table-filter.js') }}"></script>
    <script src="{{ asset('js/mobile-table.js') }}"></script>
    <script src="{{ asset('js/table-actions.js') }}"></script>
    <script src="{{ asset('js/form-wizard.js') }}"></script>
    <script src="{{ asset('js/search.js') }}"></script>
    <script src="{{ asset('js/keyboard-shortcuts.js') }}"></script>
    <script src="{{ asset('js/charts.js') }}"></script>
    <script src="{{ asset('js/dashboard-customize.js') }}"></script>
    <script src="{{ asset('js/realtime.js') }}"></script>
    <script src="{{ asset('js/touch-interactions.js') }}"></script>
    <script src="{{ asset('js/lazy-load.js') }}"></script>
    <script src="{{ asset('js/infinite-scroll.js') }}"></script>
    <script src="{{ asset('js/animations.js') }}"></script>
    <script src="{{ asset('js/onboarding.js') }}"></script>
    <script src="{{ asset('js/image-optimizer.js') }}"></script>

    <script>
        // CSRF Token setup for AJAX requests
        window.Laravel = { csrfToken: '{{ csrf_token() }}' };

        // Global SweetAlert Configuration
        window.showAlert = function (options) {
            const defaultOptions = {
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                heightAuto: true,
                width: '400px',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            };
            return Swal.fire({ ...defaultOptions, ...options });
        };

        window.showSuccess = (message, title = 'Success') => showAlert({ icon: 'success', title, text: message });
        window.showError = (message, title = 'Error') => showAlert({ icon: 'error', title, text: message });
        window.showInfo = (message, title = 'Info') => showAlert({ icon: 'info', title, text: message });
        window.showWarning = (message, title = 'Warning') => showAlert({ icon: 'warning', title, text: message });

            // Show session messages
            @if(session('success')) showSuccess('{{ session('success') }}'); @endif
        @if(session('error')) showError('{{ session('error') }}'); @endif
        @if(session('info')) showInfo('{{ session('info') }}'); @endif
        @if(session('warning')) showWarning('{{ session('warning') }}'); @endif


        // Logout confirmation with clock-out option
            @php
                $hasActiveAttendance = \App\Models\Attendance::where('user_id', auth()->id())
                    ->whereDate('date', \Carbon\Carbon::today()->toDateString())
                    ->whereNotNull('clock_in_time')
                    ->whereNull('clock_out_time')
                    ->exists();
            @endphp

        document.querySelectorAll('.logout-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const formElement = this;

                @if($hasActiveAttendance)
                    Swal.fire({
                        title: 'Before you go...',
                        html: `
                            <div class="text-left py-2">
                                <p class="text-gray-600 mb-4">You are still clocked in. What would you like to do?</p>
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-3">
                                    <div class="flex items-center gap-2 text-amber-700">
                                        <i class='hgi-stroke hgi-clock-02 text-xl'></i>
                                        <span class="font-medium">You haven't clocked out yet</span>
                                    </div>
                                </div>
                            </div>
                        `,
                        icon: 'warning',
                        iconColor: '#f59e0b',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: '<i class="hgi-stroke hgi-logout-01 mr-1"></i> Clock Out & Logout',
                        denyButtonText: '<i class="hgi-stroke hgi-logout-01 mr-1"></i> Just Logout',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#10b981',
                        denyButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-xl px-4 py-2',
                            denyButton: 'rounded-xl px-4 py-2',
                            cancelButton: 'rounded-xl px-4 py-2'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Clock out first, then logout
                            Swal.fire({
                                title: 'Clocking Out...',
                                html: '<div class="flex items-center justify-center gap-2"><i class="hgi-stroke hgi-loading-02 animate-spin text-2xl text-green-500"></i><span>Recording your clock out time...</span></div>',
                                allowOutsideClick: false,
                                showConfirmButton: false
                            });

                            // Submit clock out via AJAX
                            fetch('{{ route("staff.attendance.clock-out") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            }).then(response => {
                                // Now logout
                                Swal.fire({
                                    title: 'Logging out...',
                                    html: '<div class="flex items-center justify-center gap-2"><i class="hgi-stroke hgi-loading-02 animate-spin text-2xl text-amber-500"></i><span>Goodbye!</span></div>',
                                    allowOutsideClick: false,
                                    showConfirmButton: false
                                });
                                setTimeout(() => {
                                    formElement.submit();
                                }, 1500);
                            }).catch(error => {
                                console.error('Clock out error:', error);
                                // Still logout even if clock out fails
                                formElement.submit();
                            });
                        } else if (result.isDenied) {
                            // Just logout without clocking out
                            Swal.fire({
                                title: 'Logging out...',
                                html: '<div class="flex items-center justify-center gap-2"><i class="hgi-stroke hgi-loading-02 animate-spin text-2xl text-red-500"></i><span>Goodbye!</span></div>',
                                allowOutsideClick: false,
                                showConfirmButton: false
                            });
                            setTimeout(() => {
                                formElement.submit();
                            }, 1500);
                        }
                    });
                @else
                    Swal.fire({
                        title: 'Logout?',
                        text: 'Are you sure you want to logout?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, logout',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Logging out...',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                didOpen: () => Swal.showLoading()
                            });
                            setTimeout(() => formElement.submit(), 1000);
                        }
                    });
                @endif
            });
        });
    </script>
</body>

</html>
