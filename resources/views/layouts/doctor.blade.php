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
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d'
                        },
                        warning: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            500: '#f59e0b',
                            600: '#d97706'
                        },
                        danger: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            500: '#ef4444',
                            600: '#dc2626'
                        },
                        sidebar: {
                            dark: '#064e3b',
                            darker: '#053b2c',
                            light: '#065f46',
                            text: '#a7f3d0',
                            hover: '#047857'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        card: '0 1px 3px 0 rgb(0 0 0 / 0.08), 0 1px 2px -1px rgb(0 0 0 / 0.08)',
                        sidebar: '4px 0 6px -1px rgb(0 0 0 / 0.1)'
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
        /* Scrollbar Styling */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #047857;
            border-radius: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }

        /* Navigation Item Animations */
        .nav-item {
            position: relative;
            transition: all 0.2s ease;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: linear-gradient(180deg, #10b981, #34d399);
            border-radius: 0 4px 4px 0;
            transition: height 0.2s ease;
        }

        .nav-item:hover::before,
        .nav-item.active::before {
            height: 60%;
        }

        .nav-item.active::before {
            height: 70%;
        }

        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
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

        button,
        .btn,
        a.btn {
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
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center">
                            <i class='bx bx-plus-medical text-xl text-white'></i>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h1 class="text-sm font-bold text-white truncate">{{ $clinicName }}</h1>
                        <p class="text-xs text-sidebar-text">Doctor Portal</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 px-3">
                <!-- Main Section -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-xs font-semibold text-sidebar-text uppercase tracking-wider">Main</p>

                    <!-- Dashboard -->
                    <a href="{{ route('doctor.dashboard') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('doctor.dashboard') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.dashboard') ? 'bg-emerald-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bxs-dashboard text-lg {{ request()->routeIs('doctor.dashboard') ? 'text-emerald-400' : '' }}'></i>
                        </div>
                        <span>Dashboard</span>
                    </a>

                    <!-- Tasks -->
                    <a href="{{ route('doctor.todos.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('doctor.todos.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.todos.*') ? 'bg-pink-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-task text-lg {{ request()->routeIs('doctor.todos.*') ? 'text-pink-400' : '' }}'></i>
                        </div>
                        <span>Tasks</span>
                    </a>
                </div>

                <!-- Clinical Section -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-xs font-semibold text-sidebar-text uppercase tracking-wider">Clinical</p>

                    <!-- Schedule -->
                    <a href="{{ route('doctor.schedule.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('doctor.schedule.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.schedule.*') ? 'bg-teal-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-calendar-star text-lg {{ request()->routeIs('doctor.schedule.*') ? 'text-teal-400' : '' }}'></i>
                        </div>
                        <span>Schedule</span>
                    </a>

                    <!-- Appointments -->
                    <a href="{{ route('doctor.appointments.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('doctor.appointments.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.appointments.*') ? 'bg-green-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-calendar-check text-lg {{ request()->routeIs('doctor.appointments.*') ? 'text-green-400' : '' }}'></i>
                        </div>
                        <span>Appointments</span>
                    </a>

                    <!-- Patients -->
                    <a href="{{ route('doctor.patients.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('doctor.patients.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.patients.*') ? 'bg-blue-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-group text-lg {{ request()->routeIs('doctor.patients.*') ? 'text-blue-400' : '' }}'></i>
                        </div>
                        <span>Patients</span>
                    </a>
                </div>

                <!-- HR Section -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-xs font-semibold text-sidebar-text uppercase tracking-wider">HR</p>

                    <!-- Attendance -->
                    <a href="{{ route('doctor.attendance.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('doctor.attendance.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.attendance.*') ? 'bg-orange-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-time-five text-lg {{ request()->routeIs('doctor.attendance.*') ? 'text-orange-400' : '' }}'></i>
                        </div>
                        <span>Attendance</span>
                    </a>

                    <!-- Leave -->
                    <a href="{{ route('doctor.leaves.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('doctor.leaves.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.leaves.*') ? 'bg-purple-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-calendar-x text-lg {{ request()->routeIs('doctor.leaves.*') ? 'text-purple-400' : '' }}'></i>
                        </div>
                        <span>Leave</span>
                    </a>

                    <!-- Payslips -->
                    <a href="{{ route('doctor.payslips.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('doctor.payslips.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.payslips.*') ? 'bg-cyan-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i class='bx bx-wallet text-lg {{ request()->routeIs('doctor.payslips.*') ? 'text-cyan-400' : '' }}'></i>
                        </div>
                        <span>Payslips</span>
                    </a>
                </div>
            </nav>

            <!-- Footer Section -->
            <div class="flex-shrink-0 border-t border-white/10 p-3">
                <!-- Profile -->
                <a href="{{ route('doctor.profile.show') }}"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-2
                    {{ request()->routeIs('doctor.profile.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                    <div
                        class="w-8 h-8 rounded-lg {{ request()->routeIs('doctor.profile.*') ? 'bg-slate-500/20' : 'bg-white/5' }} flex items-center justify-center">
                        <i class='bx bx-user text-lg {{ request()->routeIs('doctor.profile.*') ? 'text-slate-400' : '' }}'></i>
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
                        <p class="text-xs text-sidebar-text truncate">{{ Auth::user()->doctor?->doctor_id ?? 'Doctor' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen lg:ml-0">
            <!-- Header -->
            <header class="sticky top-0 z-30 bg-white border-b border-gray-100 shadow-sm">
                <div class="flex items-center justify-between px-4 lg:px-6 h-16">
                    <!-- Left Side - Mobile Menu & Breadcrumb -->
                    <div class="flex items-center gap-4">
                        <!-- Mobile Menu Toggle -->
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen"
                            class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class='bx bx-menu text-xl text-gray-600'></i>
                        </button>

                        <!-- Page Title -->
                        <div>
                            <h1 class="text-lg font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                        </div>
                    </div>

                    <!-- Right Side - Actions -->
                    <div class="flex items-center gap-2">
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open"
                                class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <div
                                    class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <i class='bx bx-chevron-down text-gray-500 hidden sm:block'
                                    :class="open ? 'rotate-180' : ''" style="transition: transform 0.2s;"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50"
                                style="display: none;">

                                <!-- User Info -->
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-800">Dr. {{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->email }}</p>
                                    @if(Auth::user()->doctor)
                                        <p class="text-xs text-emerald-600 mt-1">ID: {{ Auth::user()->doctor->doctor_id }}</p>
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
            <main class="flex-1 p-4 lg:p-6">
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