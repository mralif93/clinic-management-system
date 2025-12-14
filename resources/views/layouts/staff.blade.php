<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Staff Dashboard - Clinic Management System')</title>

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
                    colors: {
                        primary: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f'
                        },
                        sidebar: {
                            dark: '#0f172a',
                            darker: '#0c1322',
                            light: '#1e293b',
                            text: '#94a3b8',
                            hover: '#334155'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        card: '0 1px 3px 0 rgb(0 0 0 / 0.08), 0 1px 2px -1px rgb(0 0 0 / 0.08)',
                        sidebar: '4px 0 6px -1px rgb(0 0 0 / 0.1)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Global Button Styles -->
    <link href="{{ asset('css/buttons.css') }}" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Sidebar Scrollbar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.3);
            border-radius: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.5);
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
        }

        /* Navigation item hover effect */
        .nav-item {
            position: relative;
            overflow: hidden;
        }
        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(to bottom, #f59e0b, #d97706);
            transform: scaleY(0);
            transition: transform 0.2s ease;
        }
        .nav-item:hover::before,
        .nav-item.active::before {
            transform: scaleY(1);
        }

        /* Global Input Sizing */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="date"],
        input[type="time"],
        input[type="datetime-local"],
        input[type="tel"],
        input[type="url"],
        input[type="search"],
        input[type="file"],
        select,
        textarea {
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }

        label {
            font-size: 0.875rem !important;
        }

        /* Rich content styles for WYSIWYG editor output */
        .rich-content p {
            margin-bottom: 0.5rem;
        }
        .rich-content p:last-child {
            margin-bottom: 0;
        }
        .rich-content ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin: 0.5rem 0;
        }
        .rich-content ol {
            list-style-type: decimal;
            padding-left: 1.5rem;
            margin: 0.5rem 0;
        }
        .rich-content li {
            margin-bottom: 0.25rem;
        }
        .rich-content strong, .rich-content b {
            font-weight: 600;
        }
        .rich-content em, .rich-content i:not([class]) {
            font-style: italic;
        }
        .rich-content u {
            text-decoration: underline;
        }
        .rich-content s {
            text-decoration: line-through;
        }
        .rich-content blockquote {
            border-left: 3px solid #d1d5db;
            padding-left: 1rem;
            margin: 0.5rem 0;
            color: #6b7280;
        }
        .rich-content pre {
            background: #1f2937;
            color: #f9fafb;
            padding: 0.75rem;
            border-radius: 0.375rem;
            overflow-x: auto;
            margin: 0.5rem 0;
        }
        .rich-content code {
            background: #f3f4f6;
            padding: 0.125rem 0.25rem;
            border-radius: 0.25rem;
            font-family: monospace;
            font-size: 0.875em;
        }
        .rich-content pre code {
            background: none;
            padding: 0;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 font-sans text-base text-gray-900" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
    <div class="min-h-screen flex">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="mobileSidebarOpen" x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" @click="mobileSidebarOpen = false"
            class="fixed inset-0 z-40 lg:hidden sidebar-overlay" style="display: none;"></div>

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
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                            <i class='bx bx-id-card text-xl text-white'></i>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h1 class="text-sm font-bold text-white truncate">{{ $clinicName }}</h1>
                        <p class="text-xs text-sidebar-text">Staff Portal</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 px-3">
                <!-- Main Section -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-xs font-semibold text-sidebar-text uppercase tracking-wider">Main</p>

                    <!-- Dashboard -->
                    <a href="{{ route('staff.dashboard') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('staff.dashboard') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.dashboard') ? 'bg-amber-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bxs-dashboard text-lg {{ request()->routeIs('staff.dashboard') ? 'text-amber-400' : '' }}'></i>
                        </div>
                        <span>Dashboard</span>
                    </a>

                    <!-- Patient Flow -->
                    <a href="{{ route('staff.patient-flow') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('staff.patient-flow*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.patient-flow*') ? 'bg-teal-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-transfer-alt text-lg {{ request()->routeIs('staff.patient-flow*') ? 'text-teal-400' : '' }}'></i>
                        </div>
                        <span>Patient Flow</span>
                    </a>

                    <!-- Tasks -->
                    <a href="{{ route('staff.todos.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('staff.todos.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.todos.*') ? 'bg-pink-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-task text-lg {{ request()->routeIs('staff.todos.*') ? 'text-pink-400' : '' }}'></i>
                        </div>
                        <span>Tasks</span>
                    </a>
                </div>

                <!-- Work Section -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-xs font-semibold text-sidebar-text uppercase tracking-wider">Work</p>

                    <!-- Attendance -->
                    <a href="{{ route('staff.attendance.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('staff.attendance.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.attendance.*') ? 'bg-green-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-time-five text-lg {{ request()->routeIs('staff.attendance.*') ? 'text-green-400' : '' }}'></i>
                        </div>
                        <span>Attendance</span>
                    </a>

                    <!-- Schedule -->
                    <a href="{{ route('staff.schedule.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('staff.schedule.index') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.schedule.index') ? 'bg-cyan-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-calendar-star text-lg {{ request()->routeIs('staff.schedule.index') ? 'text-cyan-400' : '' }}'></i>
                        </div>
                        <span>My Schedule</span>
                    </a>

                    <!-- Appointments -->
                    <a href="{{ route('staff.appointments.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('staff.appointments.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.appointments.*') ? 'bg-blue-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-calendar text-lg {{ request()->routeIs('staff.appointments.*') ? 'text-blue-400' : '' }}'></i>
                        </div>
                        <span>Appointments</span>
                    </a>
                </div>

                <!-- Management Section -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-xs font-semibold text-sidebar-text uppercase tracking-wider">Management</p>

                    <!-- Doctors -->
                    <a href="{{ route('staff.schedule.doctors') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('staff.schedule.doctors') || request()->routeIs('staff.schedule.view-doctor') || request()->routeIs('staff.doctors.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.schedule.doctors') || request()->routeIs('staff.schedule.view-doctor') || request()->routeIs('staff.doctors.*') ? 'bg-emerald-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-user-pin text-lg {{ request()->routeIs('staff.schedule.doctors') || request()->routeIs('staff.schedule.view-doctor') || request()->routeIs('staff.doctors.*') ? 'text-emerald-400' : '' }}'></i>
                        </div>
                        <span>Doctors</span>
                    </a>

                    <!-- Patients -->
                    <a href="{{ route('staff.patients.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('staff.patients.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.patients.*') ? 'bg-violet-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-group text-lg {{ request()->routeIs('staff.patients.*') ? 'text-violet-400' : '' }}'></i>
                        </div>
                        <span>Patients</span>
                    </a>
                </div>

                <!-- Personal Section -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-xs font-semibold text-sidebar-text uppercase tracking-wider">Personal</p>

                    <!-- Leave -->
                    <a href="{{ route('staff.leaves.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('staff.leaves.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.leaves.*') ? 'bg-orange-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-calendar-check text-lg {{ request()->routeIs('staff.leaves.*') ? 'text-orange-400' : '' }}'></i>
                        </div>
                        <span>Leave</span>
                    </a>

                    <!-- Payslips -->
                    <a href="{{ route('staff.payslips.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('staff.payslips.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.payslips.*') ? 'bg-teal-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-receipt text-lg {{ request()->routeIs('staff.payslips.*') ? 'text-teal-400' : '' }}'></i>
                        </div>
                        <span>Payslips</span>
                    </a>

                    <!-- Profile -->
                    <a href="{{ route('staff.profile.show') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('staff.profile.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.profile.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-user text-lg {{ request()->routeIs('staff.profile.*') ? 'text-indigo-400' : '' }}'></i>
                        </div>
                        <span>My Profile</span>
                    </a>

                    <!-- Reports -->
                    <a href="{{ route('staff.reports.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('staff.reports.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div class="w-8 h-8 rounded-lg {{ request()->routeIs('staff.reports.*') ? 'bg-rose-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-file text-lg {{ request()->routeIs('staff.reports.*') ? 'text-rose-400' : '' }}'></i>
                        </div>
                        <span>Reports</span>
                    </a>
                </div>
            </nav>

            <!-- User Section at Bottom -->
            <div class="flex-shrink-0 p-4 border-t border-white/10">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="w-full flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 transition-all">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white font-semibold text-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-sidebar-text truncate">
                                {{ Auth::user()->staff?->staff_id ?? 'Staff' }}
                            </p>
                        </div>
                        <i class='bx bx-chevron-up text-sidebar-text transition-transform duration-200' :class="{ 'rotate-180': open }"></i>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="absolute bottom-full left-0 right-0 mb-2 bg-sidebar-light rounded-lg shadow-lg border border-white/10 overflow-hidden"
                        style="display: none;">
                        <a href="{{ route('staff.profile.show') }}"
                            class="flex items-center gap-3 px-4 py-3 text-sm text-sidebar-text hover:bg-white/5 hover:text-white transition-all">
                            <i class='bx bx-user'></i>
                            <span>View Profile</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-400 hover:bg-red-500/10 transition-all">
                                <i class='bx bx-log-out'></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen lg:ml-0">
            <!-- Header -->
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200">
                <div class="flex items-center justify-between px-4 lg:px-6 py-3">
                    <!-- Left: Mobile Menu + Page Title -->
                    <div class="flex items-center gap-4">
                        <!-- Mobile Menu Button -->
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen"
                            class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                            <i class='bx bx-menu text-xl'></i>
                        </button>

                        <!-- Page Title -->
                        <div>
                            <h1 class="text-lg font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                        </div>
                    </div>

                    <!-- Right: User Info -->
                    <div class="flex items-center gap-3">
                        <!-- Staff Badge -->
                        @if(Auth::user()->staff)
                            <span class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                <i class='bx bx-id-card mr-1'></i>
                                {{ Auth::user()->staff?->staff_id ?? 'Staff' }}
                            </span>
                        @endif

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                <i class='bx bx-chevron-down text-gray-400 transition-transform duration-200' :class="{ 'rotate-180': open }"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50"
                                style="display: none;">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('staff.profile.show') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class='bx bx-user text-lg text-gray-400'></i>
                                    <span>My Profile</span>
                                </a>
                                <div class="border-t border-gray-100 mt-1 pt-1">
                                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
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
            <main class="flex-1 p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        // CSRF Token setup for AJAX requests
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };

        // Global SweetAlert Configuration
        window.showAlert = function (options) {
            const defaultOptions = {
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                width: '450px',
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            };
            return Swal.fire({ ...defaultOptions, ...options });
        };

        window.showSuccess = function (message, title = 'Success') {
            return showAlert({ icon: 'success', title: title, text: message });
        };

        window.showError = function (message, title = 'Error') {
            return showAlert({ icon: 'error', title: title, text: message });
        };

        window.showInfo = function (message, title = 'Info') {
            return showAlert({ icon: 'info', title: title, text: message });
        };

        window.showWarning = function (message, title = 'Warning') {
            return showAlert({ icon: 'warning', title: title, text: message });
        };

        // Show session messages as SweetAlert
        @if(session('success'))
            showSuccess('{{ session('success') }}');
        @endif

        @if(session('error'))
            showError('{{ session('error') }}');
        @endif

        @if(session('info'))
            showInfo('{{ session('info') }}');
        @endif

        @if(session('warning'))
            showWarning('{{ session('warning') }}');
        @endif

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
                // Staff is clocked in - show options
                Swal.fire({
                    title: 'Before you go...',
                    html: `
                        <div class="text-left py-2">
                            <p class="text-gray-600 mb-4">You are still clocked in. What would you like to do?</p>
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-3">
                                <div class="flex items-center gap-2 text-amber-700">
                                    <i class='bx bx-time-five text-xl'></i>
                                    <span class="font-medium">You haven't clocked out yet</span>
                                </div>
                            </div>
                        </div>
                    `,
                    icon: 'warning',
                    iconColor: '#f59e0b',
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: '<i class="bx bx-log-out-circle mr-1"></i> Clock Out & Logout',
                    denyButtonText: '<i class="bx bx-exit mr-1"></i> Just Logout',
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
                // Staff not clocked in or already clocked out - simple logout
                Swal.fire({
                    title: 'Logout?',
                    text: 'Are you sure you want to logout?',
                    icon: 'question',
                    iconColor: '#f59e0b',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="bx bx-log-out mr-1"></i> Yes, Logout',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-4 py-2',
                        cancelButton: 'rounded-xl px-4 py-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Logging out...',
                            html: '<div class="flex items-center justify-center gap-2"><i class="bx bx-loader-alt bx-spin text-2xl text-amber-500"></i><span>Goodbye!</span></div>',
                            allowOutsideClick: false,
                            showConfirmButton: false
                        });
                        setTimeout(() => {
                            formElement.submit();
                        }, 1500);
                    }
                });
                @endif
            });
        });
    </script>
</body>

</html>