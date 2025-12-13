<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - Clinic Management System')</title>

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
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a'
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
            background: #334155;
            border-radius: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #475569;
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
            background: linear-gradient(180deg, #3b82f6, #60a5fa);
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

        /* Pulse animation for notifications */
        @keyframes pulse-dot {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.8;
            }
        }

        .pulse-dot {
            animation: pulse-dot 2s infinite;
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
        .rich-content h1, .rich-content h2, .rich-content h3 {
            font-weight: 600;
            margin: 0.75rem 0 0.5rem 0;
        }
        .rich-content h1 { font-size: 1.5rem; }
        .rich-content h2 { font-size: 1.25rem; }
        .rich-content h3 { font-size: 1.125rem; }
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
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                            <i class='bx bx-plus-medical text-xl text-white'></i>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h1 class="text-sm font-bold text-white truncate">{{ $clinicName }}</h1>
                        <p class="text-xs text-sidebar-text">Admin Panel</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 px-3">
                <!-- Main Section -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-xs font-semibold text-sidebar-text uppercase tracking-wider">Main</p>

                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('admin.dashboard') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bxs-dashboard text-lg {{ request()->routeIs('admin.dashboard') ? 'text-blue-400' : '' }}'></i>
                        </div>
                        <span>Dashboard</span>
                    </a>
                </div>

                <!-- Management Section -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-xs font-semibold text-sidebar-text uppercase tracking-wider">Management</p>

                    <!-- User Management Dropdown -->
                    <div x-data="{ open: {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.staff.*') || request()->routeIs('admin.doctors.*') || request()->routeIs('admin.patients.*') ? 'true' : 'false' }} }"
                        class="mb-1">
                        <button @click="open = !open"
                            class="nav-item w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all
                            {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.staff.*') || request()->routeIs('admin.doctors.*') || request()->routeIs('admin.patients.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.staff.*') || request()->routeIs('admin.doctors.*') || request()->routeIs('admin.patients.*') ? 'bg-violet-500/20' : 'bg-white/5' }} flex items-center justify-center">
                                    <i
                                        class='bx bxs-user-account text-lg {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.staff.*') || request()->routeIs('admin.doctors.*') || request()->routeIs('admin.patients.*') ? 'text-violet-400' : '' }}'></i>
                                </div>
                                <span>Users</span>
                            </div>
                            <i class='bx bx-chevron-down text-lg transition-transform duration-200'
                                :class="{ 'rotate-180': open }"></i>
                        </button>

                        <div x-show="open" x-collapse class="mt-1 ml-4 border-l border-white/10 pl-4 space-y-1">
                            <a href="{{ route('admin.users.index') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all
                                {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                                <i class='bx bx-user-circle'></i>
                                <span>All Users</span>
                            </a>
                            <a href="{{ route('admin.staff.index') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all
                                {{ request()->routeIs('admin.staff.*') ? 'bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                                <i class='bx bx-id-card'></i>
                                <span>Staff</span>
                            </a>
                            <a href="{{ route('admin.doctors.index') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all
                                {{ request()->routeIs('admin.doctors.*') ? 'bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                                <i class='bx bx-plus-medical'></i>
                                <span>Doctors</span>
                            </a>
                            <a href="{{ route('admin.patients.index') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-all
                                {{ request()->routeIs('admin.patients.*') ? 'bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                                <i class='bx bx-user'></i>
                                <span>Patients</span>
                            </a>
                        </div>
                    </div>

                    <!-- Services -->
                    <a href="{{ route('admin.services.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('admin.services.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.services.*') ? 'bg-cyan-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-grid-alt text-lg {{ request()->routeIs('admin.services.*') ? 'text-cyan-400' : '' }}'></i>
                        </div>
                        <span>Services</span>
                    </a>

                    <!-- Packages -->
                    <a href="{{ route('admin.packages.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('admin.packages.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.packages.*') ? 'bg-purple-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-package text-lg {{ request()->routeIs('admin.packages.*') ? 'text-purple-400' : '' }}'></i>
                        </div>
                        <span>Packages</span>
                    </a>

                    <!-- Team -->
                    <a href="{{ route('admin.team.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('admin.team.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.team.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-group text-lg {{ request()->routeIs('admin.team.*') ? 'text-indigo-400' : '' }}'></i>
                        </div>
                        <span>Team</span>
                    </a>

                    <!-- Appointments -->
                    <a href="{{ route('admin.appointments.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('admin.appointments.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.appointments.*') ? 'bg-green-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-calendar-check text-lg {{ request()->routeIs('admin.appointments.*') ? 'text-green-400' : '' }}'></i>
                        </div>
                        <span>Appointments</span>
                    </a>

                    <!-- Schedules -->
                    <a href="{{ route('admin.schedules.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('admin.schedules.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.schedules.*') ? 'bg-teal-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-calendar text-lg {{ request()->routeIs('admin.schedules.*') ? 'text-teal-400' : '' }}'></i>
                        </div>
                        <span>Schedules</span>
                    </a>
                </div>

                <!-- HR Section -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-xs font-semibold text-sidebar-text uppercase tracking-wider">Human
                        Resources</p>

                    <!-- Attendance -->
                    <a href="{{ route('admin.attendance.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('admin.attendance.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.attendance.*') ? 'bg-orange-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-time-five text-lg {{ request()->routeIs('admin.attendance.*') ? 'text-orange-400' : '' }}'></i>
                        </div>
                        <span>Attendance</span>
                    </a>

                    <!-- Leave -->
                    <a href="{{ route('admin.leaves.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('admin.leaves.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.leaves.*') ? 'bg-purple-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-calendar-x text-lg {{ request()->routeIs('admin.leaves.*') ? 'text-purple-400' : '' }}'></i>
                        </div>
                        <span>Leave</span>
                    </a>

                    <!-- Payroll -->
                    <a href="{{ route('admin.payrolls.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('admin.payrolls.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.payrolls.*') ? 'bg-emerald-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-wallet text-lg {{ request()->routeIs('admin.payrolls.*') ? 'text-emerald-400' : '' }}'></i>
                        </div>
                        <span>Payroll</span>
                    </a>
                </div>

                <!-- Tools Section -->
                <div class="mb-6">
                    <p class="px-3 mb-2 text-xs font-semibold text-sidebar-text uppercase tracking-wider">Tools</p>

                    <!-- To-Do List -->
                    <a href="{{ route('admin.todos.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('admin.todos.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.todos.*') ? 'bg-pink-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-task text-lg {{ request()->routeIs('admin.todos.*') ? 'text-pink-400' : '' }}'></i>
                        </div>
                        <span>To-Do List</span>
                    </a>

                    <!-- Report -->
                    <a href="{{ route('admin.reports.index') }}"
                        class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-1
                        {{ request()->routeIs('admin.reports.*') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                        <div
                            class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-500/20' : 'bg-white/5' }} flex items-center justify-center">
                            <i
                                class='bx bx-bar-chart-alt-2 text-lg {{ request()->routeIs('admin.reports.*') ? 'text-indigo-400' : '' }}'></i>
                        </div>
                        <span>Reports</span>
                    </a>
                </div>
            </nav>

            <!-- Footer Section -->
            <div class="flex-shrink-0 border-t border-white/10 p-3">
                <!-- Settings -->
                <a href="{{ route('admin.settings.index') }}"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-2
                    {{ request()->routeIs('admin.settings.*') && request('tab') !== 'pages' ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                    <div
                        class="w-8 h-8 rounded-lg {{ request()->routeIs('admin.settings.*') && request('tab') !== 'pages' ? 'bg-slate-500/20' : 'bg-white/5' }} flex items-center justify-center">
                        <i
                            class='bx bx-cog text-lg {{ request()->routeIs('admin.settings.*') && request('tab') !== 'pages' ? 'text-slate-400' : '' }}'></i>
                    </div>
                    <span>Settings</span>
                </a>

                <!-- Pages -->
                <a href="{{ route('admin.pages.index') }}"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all mb-2
                    {{ (request()->routeIs('admin.pages.*')) || (request()->routeIs('admin.settings.*') && request('tab') === 'pages') ? 'active bg-white/10 text-white' : 'text-sidebar-text hover:bg-white/5 hover:text-white' }}">
                    <div
                        class="w-8 h-8 rounded-lg {{ (request()->routeIs('admin.pages.*')) || (request()->routeIs('admin.settings.*') && request('tab') === 'pages') ? 'bg-slate-500/20' : 'bg-white/5' }} flex items-center justify-center">
                        <i
                            class='bx bx-file text-lg {{ (request()->routeIs('admin.pages.*')) || (request()->routeIs('admin.settings.*') && request('tab') === 'pages') ? 'text-slate-400' : '' }}'></i>
                    </div>
                    <span>Pages</span>
                </a>

                <!-- User Profile Section -->
                <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-white/5">
                    <div
                        class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-sidebar-text truncate">{{ ucfirst(Auth::user()->role) }}</p>
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
                        <!-- Quick Actions -->
                        <a href="{{ route('admin.appointments.create') }}"
                            class="hidden sm:inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class='bx bx-plus'></i>
                            <span>New Appointment</span>
                        </a>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open"
                                class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                                <div
                                    class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-semibold">
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
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->email }}</p>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-1">
                                    <a href="{{ route('admin.settings.index') }}"
                                        class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class='bx bx-cog text-lg text-gray-400'></i>
                                        <span>Settings</span>
                                    </a>
                                    <a href="{{ route('admin.pages.index') }}"
                                        class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class='bx bx-file text-lg text-gray-400'></i>
                                        <span>Pages</span>
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

        // Logout confirmation
        document.querySelectorAll('.logout-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
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
                        setTimeout(() => form.submit(), 1000);
                    }
                });
            });
        });
    </script>
</body>

</html>