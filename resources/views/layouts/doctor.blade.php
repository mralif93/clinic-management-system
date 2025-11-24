<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Doctor Dashboard - Clinic Management System')</title>

    @php
        $logoPath = get_setting('clinic_logo');
        // Check if logo is base64 (for Vercel) or file path (for local)
        if ($logoPath && str_starts_with($logoPath, 'data:')) {
            $faviconUrl = $logoPath; // Base64 data URI
        } elseif ($logoPath) {
            $faviconUrl = asset('storage/' . $logoPath); // File path
        } else {
            $faviconUrl = 'data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22></text></svg>';
        }
    @endphp
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js CDN for dropdown functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Global Styles for Smaller Inputs and Buttons -->
    <style>
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
            @apply text-sm py-2 px-3;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }

        /* Global Button Sizing */
        button,
        .btn,
        a.btn {
            @apply text-sm py-2 px-4;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
        }

        /* Smaller icon buttons */
        button i,
        .btn i,
        a.btn i {
            font-size: 1rem !important;
        }

        /* Labels */
        label {
            @apply text-sm;
            font-size: 0.875rem !important;
        }

        /* Form groups spacing */
        .form-group,
        .space-y-4>div {
            margin-bottom: 0.75rem;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-green-600 to-green-700 shadow-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-white">Clinic Management</h1>
                <p class="text-sm text-green-100 mt-1">Doctor Portal</p>
            </div>
            <nav class="mt-6">
                <a href="{{ route('doctor.dashboard') }}"
                    class="flex items-center px-6 py-3 text-white hover:bg-green-700 transition {{ request()->routeIs('doctor.dashboard') ? 'bg-green-800 border-r-4 border-white' : '' }}">
                    <i class='bx bx-home-alt-2 mr-3 text-xl'></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('doctor.appointments.index') }}"
                    class="flex items-center px-6 py-3 text-white hover:bg-green-700 transition {{ request()->routeIs('doctor.appointments.*') ? 'bg-green-800 border-r-4 border-white' : '' }}">
                    <i class='bx bx-calendar mr-3 text-xl'></i>
                    <span>My Appointments</span>
                </a>
                <a href="{{ route('doctor.profile.show') }}"
                    class="flex items-center px-6 py-3 text-white hover:bg-green-700 transition {{ request()->routeIs('doctor.profile.*') ? 'bg-green-800 border-r-4 border-white' : '' }}">
                    <i class='bx bx-user mr-3 text-xl'></i>
                    <span>My Profile</span>
                </a>
                <a href="{{ route('doctor.schedule.index') }}"
                    class="flex items-center px-6 py-3 text-white hover:bg-green-700 transition {{ request()->routeIs('doctor.schedule.*') ? 'bg-green-800 border-r-4 border-white' : '' }}">
                    <i class='bx bx-time mr-3 text-xl'></i>
                    <span>Schedule</span>
                </a>
                <a href="{{ route('doctor.patients.index') }}"
                    class="flex items-center px-6 py-3 text-white hover:bg-green-700 transition {{ request()->routeIs('doctor.patients.*') ? 'bg-green-800 border-r-4 border-white' : '' }}">
                    <i class='bx bx-file-blank mr-3 text-xl'></i>
                    <span>Patients</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-9 h-9 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-semibold shadow-md">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="text-left hidden md:block">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                @if(Auth::user()->doctor)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ Auth::user()->doctor->doctor_id ?? 'N/A' }}
                                    </span>
                                @endif
                            </div>
                            <i class='bx bx-chevron-down text-gray-500 transition-transform'
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
                            style="display: none;">

                            <!-- User Info -->
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->email }}</p>
                                @if(Auth::user()->doctor)
                                    <p class="text-xs text-green-600 mt-1">ID: {{ Auth::user()->doctor->doctor_id }}</p>
                                @endif
                            </div>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class='bx bx-log-out mr-3 text-lg'></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
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

        // Logout confirmation with loading
        document.querySelectorAll('.logout-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to logout?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, logout',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Logging out...',
                            text: 'Please wait',
                            icon: 'info',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Wait 3 seconds then submit
                        setTimeout(() => {
                            form.submit();
                        }, 3000);
                    }
                });
            });
        });
    </script>
</body>

</html>