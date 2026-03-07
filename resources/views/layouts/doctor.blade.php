<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Doctor Dashboard - Clinic Management System')</title>

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

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontSize: {
                        'xs': '10px',
                        'sm': '11px',
                        'base': '12px',
                        'lg': '14px',
                        'xl': '16px',
                        '2xl': '20px',
                        '3xl': '24px',
                        '4xl': '30px',
                        '5xl': '36px',
                    },
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b'
                        },
                        success: {
                            50: '#f0fdf4',
                            50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 300: '#86efac',
                            400: '#4ade80', 500: '#22c55e', 600: '#16a34a', 700: '#15803d',
                            800: '#166534', 900: '#14532d',
                        },
                        'sidebar-dark': '#064e3b',
                        'sidebar-text': '#a7f3d0',
                    },
                    spacing: {
                        '1.25': '0.3125rem',
                        '1.5': '0.375rem',
                        '2.5': '0.625rem',
                    },
                    fontSize: {
                        'xs': ['0.625rem', { lineHeight: '0.875rem' }],
                        'sm': ['0.75rem', { lineHeight: '1rem' }],
                        'base': ['0.875rem', { lineHeight: '1.25rem' }],
                        'lg': ['1rem', { lineHeight: '1.5rem' }],
                        'xl': ['1.125rem', { lineHeight: '1.75rem' }],
                    },
                    borderRadius: {
                        'xs': '0.125rem',
                        'sm': '0.25rem',
                        'md': '0.375rem',
                        'lg': '0.5rem',
                        'xl': '0.75rem',
                    },
                    boxShadow: {
                        card: '0 1px 3px 0 rgb(0 0 0 / 0.08), 0 1px 2px -1px rgb(0 0 0 / 0.08)',
                        sidebar: '4px 0 6px -1px rgb(0 0 0 / 0.1)'
                    },
                    keyframes: {
                        'fadeIn': { from: { opacity: '0' }, to: { opacity: '1' } },
                        'slideInUp': { from: { transform: 'translateY(20px)', opacity: '0' }, to: { transform: 'translateY(0)', opacity: '1' } },
                        'shimmer': { '0%': { backgroundPosition: '-1000px 0' }, '100%': { backgroundPosition: '1000px 0' } }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.2s ease-in-out',
                        'slide-in-up': 'slideInUp 0.3s ease-out',
                        'shimmer': 'shimmer 2s infinite',
                    }
                }
            },
            plugins: [
                function ({ addBase, addComponents, addUtilities, theme }) {
                    addBase({
                        'input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="date"], input[type="time"], input[type="datetime-local"], input[type="tel"], input[type="url"], input[type="search"], input[type="file"], select, textarea': {
                            paddingTop: theme('spacing[1.5]'),
                            paddingBottom: theme('spacing[1.5]'),
                            paddingLeft: theme('spacing[2.5]'),
                            paddingRight: theme('spacing[2.5]'),
                            borderRadius: theme('borderRadius.md'),
                            lineHeight: theme('lineHeight.5'),
                        },
                        'input[type="checkbox"], input[type="radio"]': {
                            width: '0.625rem',
                            height: '0.625rem',
                        },
                        'label': {
                            marginBottom: theme('spacing[1]'),
                            display: 'block',
                            fontWeight: '500',
                        },
                        '*:focus-visible': {
                            outline: `2px solid ${theme('colors.emerald.600')}`,
                            outlineOffset: '2px',
                            borderRadius: theme('borderRadius.sm'),
                        }
                    });
                    addComponents({
                        '.btn': {
                            display: 'inline-flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            fontWeight: '500',
                            borderRadius: theme('borderRadius.md'),
                            transition: 'all 0.2s ease-in-out',
                            paddingTop: theme('spacing[1.5]'),
                            paddingBottom: theme('spacing[1.5]'),
                            paddingLeft: theme('spacing[4]'),
                            paddingRight: theme('spacing[4]'),
                            fontSize: theme('fontSize.sm'),
                            lineHeight: theme('lineHeight.5'),
                        },
                        '.btn-primary': { backgroundColor: theme('colors.emerald.600'), color: 'white', '&:hover': { backgroundColor: theme('colors.emerald.700') } },
                        '.rich-content p': { marginBottom: theme('spacing[2]') },
                        '.rich-content ul': { listStyleType: 'disc', paddingLeft: theme('spacing[6]'), margin: `${theme('spacing[2]')} 0` },
                        '.rich-content ol': { listStyleType: 'decimal', paddingLeft: theme('spacing[6]'), margin: `${theme('spacing[2]')} 0` },
                        '.rich-content blockquote': { borderLeft: `3px solid ${theme('colors.emerald.300')}`, paddingLeft: theme('spacing[4]'), color: theme('colors.emerald.500') },
                    });
                    addUtilities({
                        '.sr-only': { position: 'absolute', width: '1px', height: '1px', padding: '0', margin: '-1px', overflow: 'hidden', clip: 'rect(0, 0, 0, 0)', whiteSpace: 'nowrap', borderWidth: '0' },
                        '.skip-link': { position: 'absolute', top: '-40px', left: '0', background: theme('colors.emerald.600'), color: 'white', padding: '8px 16px', zIndex: '100', '&:focus': { top: '0' } },
                    });
                }
            ]
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Hugeicons CDN -->
    <link rel="stylesheet" href="https://cdn.hugeicons.com/font/hgi-stroke-rounded.css" />

    <!-- Animate.css CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>

<body class="bg-gray-50 font-sans text-base text-gray-900" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
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
            class="fixed lg:sticky top-0 left-0 z-50 h-screen w-64 bg-sidebar-dark text-white flex flex-col transition-transform duration-300 ease-in-out shadow-sidebar">

            <!-- Logo Section -->
            <div class="flex-shrink-0 p-5 border-b border-white/10">
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

                <div class="flex items-center gap-3">
                    @if($logoUrl)
                        <div class="w-10 h-10 rounded-xl bg-white/10 p-1.5 flex items-center justify-center">
                            <img src="{{ $logoUrl }}" alt="{{ $clinicName }}" class="max-h-full max-w-full object-contain">
                        </div>
                    @else
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                            <i class='bx bx-plus-circle text-xl text-white'></i>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h1 class="text-sm font-bold text-white truncate">{{ $clinicName }}</h1>
                        <p class="text-xs text-sidebar-text">Doctor Portal</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            @php
                $navBase = "relative overflow-hidden flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1 before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:w-[3px] before:bg-gradient-to-b before:from-emerald-500 before:to-emerald-400 before:rounded-r-sm before:transition-all before:duration-200 before:content-['']";
                $navActive = "bg-white/10 text-white before:h-[70%]";
                $navInactive = "text-sidebar-text hover:bg-white/5 hover:text-white before:h-0 hover:before:h-[60%]";
            @endphp
            <nav class="flex-1 overflow-y-auto py-4 px-3">
                <!-- Main Section -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-xs font-semibold text-sidebar-text uppercase tracking-wider">Main</p>

                    <!-- Dashboard -->
                    <a href="{{ route('doctor.dashboard') }}"
                        class="{{ $navBase }} {{ request()->routeIs('doctor.dashboard') ? $navActive : $navInactive }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.dashboard') ? 'bg-emerald-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bxs-dashboard text-lg {{ request()->routeIs('doctor.dashboard') ? 'text-emerald-400' : '' }}'></i>
                        </div>
                        <span>Dashboard</span>
                    </a>

                    <!-- Tasks -->
                    <a href="{{ route('doctor.todos.index') }}"
                        class="{{ $navBase }} {{ request()->routeIs('doctor.todos.*') ? $navActive : $navInactive }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.todos.*') ? 'bg-pink-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-task text-lg {{ request()->routeIs('doctor.todos.*') ? 'text-pink-400' : '' }}'></i>
                        </div>
                        <span>Tasks</span>
                    </a>
                </div>

                <!-- Clinical Section -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-xs font-semibold text-sidebar-text uppercase tracking-wider">Clinical</p>

                    <!-- Schedule -->
                    <a href="{{ route('doctor.schedule.index') }}"
                        class="{{ $navBase }} {{ request()->routeIs('doctor.schedule.*') ? $navActive : $navInactive }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.schedule.*') ? 'bg-teal-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-calendar text-lg {{ request()->routeIs('doctor.schedule.*') ? 'text-teal-400' : '' }}'></i>
                        </div>
                        <span>Schedule</span>
                    </a>

                    <!-- Appointments -->
                    <a href="{{ route('doctor.appointments.index') }}"
                        class="{{ $navBase }} {{ request()->routeIs('doctor.appointments.*') ? $navActive : $navInactive }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.appointments.*') ? 'bg-green-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-calendar-check text-lg {{ request()->routeIs('doctor.appointments.*') ? 'text-green-400' : '' }}'></i>
                        </div>
                        <span>Appointments</span>
                    </a>

                    <!-- Patients -->
                    <a href="{{ route('doctor.patients.index') }}"
                        class="{{ $navBase }} {{ request()->routeIs('doctor.patients.*') ? $navActive : $navInactive }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.patients.*') ? 'bg-blue-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-group text-lg {{ request()->routeIs('doctor.patients.*') ? 'text-blue-400' : '' }}'></i>
                        </div>
                        <span>Patients</span>
                    </a>

                    <!-- Referral Letters -->
                    <a href="{{ route('doctor.referral-letters.index') }}"
                        class="{{ $navBase }} {{ request()->routeIs('doctor.referral-letters.*') ? $navActive : $navInactive }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.referral-letters.*') ? 'bg-violet-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-transfer text-lg {{ request()->routeIs('doctor.referral-letters.*') ? 'text-violet-400' : '' }}'></i>
                        </div>
                        <span>Referral Letters</span>
                    </a>
                </div>

                <!-- HR Section -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-xs font-semibold text-sidebar-text uppercase tracking-wider">HR</p>

                    <!-- Attendance -->
                    <a href="{{ route('doctor.attendance.index') }}"
                        class="{{ $navBase }} {{ request()->routeIs('doctor.attendance.*') ? $navActive : $navInactive }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.attendance.*') ? 'bg-orange-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-time text-lg {{ request()->routeIs('doctor.attendance.*') ? 'text-orange-400' : '' }}'></i>
                        </div>
                        <span>Attendance</span>
                    </a>

                    <!-- Leave -->
                    <a href="{{ route('doctor.leaves.index') }}"
                        class="{{ $navBase }} {{ request()->routeIs('doctor.leaves.*') ? $navActive : $navInactive }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.leaves.*') ? 'bg-purple-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-calendar-minus text-lg {{ request()->routeIs('doctor.leaves.*') ? 'text-purple-400' : '' }}'></i>
                        </div>
                        <span>Leave</span>
                    </a>

                    <!-- Payslips -->
                    <a href="{{ route('doctor.payslips.index') }}"
                        class="{{ $navBase }} {{ request()->routeIs('doctor.payslips.*') ? $navActive : $navInactive }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.payslips.*') ? 'bg-cyan-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-wallet text-lg {{ request()->routeIs('doctor.payslips.*') ? 'text-cyan-400' : '' }}'></i>
                        </div>
                        <span>Payslips</span>
                    </a>

                    <!-- User Guide -->
                    <a href="{{ route('user-guide') }}"
                        class="{{ $navBase }} {{ request()->routeIs('user-guide') ? $navActive : $navInactive }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('user-guide') ? 'bg-blue-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-book-reader text-lg {{ request()->routeIs('user-guide') ? 'text-blue-400' : '' }}'></i>
                        </div>
                        <span>User Guide</span>
                    </a>
                </div>
            </nav>

            <!-- Footer Section -->
            <div class="flex-shrink-0 border-t border-white/10 p-3">
                <!-- Profile -->
                <a href="{{ route('doctor.profile.show') }}"
                    class="{{ $navBase }} {{ request()->routeIs('doctor.profile.*') ? $navActive : $navInactive }}">
                    <div
                        class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.profile.*') ? 'bg-slate-500/20' : 'bg-white/5' }} flex items-center justify-center">
                        <i
                            class='bx bx-user text-lg {{ request()->routeIs('doctor.profile.*') ? 'text-slate-400' : '' }}'></i>
                    </div>
                    <span>My Profile</span>
                </a>

                <!-- User Profile Section -->
                <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-white/5">
                    <div
                        class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">Dr. {{ Auth::user()->name }}</p>
                        <p class="text-xs text-sidebar-text truncate">{{ Auth::user()->doctor?->doctor_id ?? 'Doctor' }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen lg:ml-0">
            <!-- Header -->
            <header class="sticky top-0 z-30 bg-white border-b border-gray-100 shadow-sm">
                <div class="flex items-center justify-between px-3 sm:px-4 lg:px-6 h-14 sm:h-15 md:h-16">
                    <!-- Left Side - Mobile Menu & Breadcrumb -->
                    <div class="flex items-center gap-2 sm:gap-3 md:gap-4 min-w-0 flex-1">
                        <!-- Mobile Menu Toggle -->
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen"
                            class="lg:hidden p-1.5 sm:p-2 rounded-lg hover:bg-gray-100 transition-colors flex-shrink-0"
                            aria-label="Toggle sidebar">
                            <i class='bx bx-menu text-lg sm:text-xl text-gray-600'></i>
                        </button>

                        <!-- Page Title -->
                        <div class="min-w-0">
                            <h1 class="text-base sm:text-lg font-semibold text-gray-900 truncate">
                                @yield('page-title', 'Dashboard')</h1>
                        </div>
                    </div>

                    <!-- Right Side - Actions -->
                    <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0">
                        <!-- Global Search -->
                        <div class="hidden md:block w-48 lg:w-64 xl:w-80">
                            <x-search.global-search />
                        </div>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open"
                                class="flex items-center gap-1.5 sm:gap-2 p-1.5 sm:p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <div
                                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white text-xs sm:text-sm font-semibold flex-shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <i class='bx bx-chevron-down text-gray-500 hidden sm:block text-sm sm:text-base flex-shrink-0'
                                    :class="open ? 'rotate-180' : ''" style="transition: transform 0.2s;"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 sm:w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50"
                                style="display: none;">

                                <!-- User Info -->
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-800">Dr. {{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->email }}</p>
                                    @if(Auth::user()->doctor)
                                        <p class="text-xs text-emerald-600 mt-1">ID: {{ Auth::user()->doctor->doctor_id }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Menu Items -->
                                <div class="py-1">
                                    <a href="{{ route('doctor.profile.show') }}"
                                        class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class='bx bx-user text-lg text-gray-400'></i>
                                        <span>My Profile</span>
                                    </a>
                                </div>

                                <!-- Logout -->
                                <div class="border-t border-gray-100 pt-1">
                                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                            <i class='bx bx-log-out text-lg'></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
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
                ->whereDate('date', today())
                ->whereNotNull('clock_in_time')
                ->whereNull('clock_out_time')
                ->exists();
        @endphp

        document.querySelectorAll('.logout-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const formElement = this;

                @if($hasActiveAttendance)
                    // Doctor is clocked in - show options
                    Swal.fire({
                        title: 'Before you go...',
                        html: `
                            <div class="text-left py-2">
                                <p class="text-gray-600 mb-4">You are still clocked in. What would you like to do?</p>
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-3">
                                    <div class="flex items-center gap-2 text-amber-700">
                                        <i class='bx bx-time text-xl'></i>
                                        <span class="font-medium">You haven't clocked out yet</span>
                                    </div>
                                </div>
                            </div>
                        `,
                        icon: 'warning',
                        iconColor: '#f59e0b',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: '<i class="bx bx-log-out mr-1"></i> Clock Out & Logout',
                        denyButtonText: '<i class="bx bx-log-out mr-1"></i> Just Logout',
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
                                html: '<div class="flex items-center justify-center gap-2"><i class="bx bx-loader-alt bx-spin text-2xl text-green-500"></i><span>Recording your clock out time...</span></div>',
                                allowOutsideClick: false,
                                showConfirmButton: false
                            });

                            // Submit clock out via AJAX
                            fetch('{{ route("doctor.attendance.clock-out") }}', {
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
                                    html: '<div class="flex items-center justify-center gap-2"><i class="bx bx-loader-alt bx-spin text-2xl text-amber-500"></i><span>Goodbye!</span></div>',
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
                                html: '<div class="flex items-center justify-center gap-2"><i class="bx bx-loader-alt bx-spin text-2xl text-red-500"></i><span>Goodbye!</span></div>',
                                allowOutsideClick: false,
                                showConfirmButton: false
                            });
                            setTimeout(() => {
                                formElement.submit();
                            }, 1500);
                        }
                    });
                @else
                    // Doctor is not clocked in - simple logout confirmation
                    Swal.fire({
                        title: 'Logout?',
                        text: 'Are you sure you want to logout?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, logout',
                        cancelButtonText: 'Cancel',
                        customClass: { popup: 'rounded-2xl' }
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