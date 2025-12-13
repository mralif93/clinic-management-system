<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Clinic Management System')</title>

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

    <!-- Global Button Styles -->
    <link href="{{ asset('css/buttons.css') }}" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
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

<body class="bg-gray-50 font-sans">
    <!-- Navigation Header -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                        <i class='bx bx-clinic text-3xl text-blue-600 mr-2'></i>
                        <span
                            class="text-xl font-bold text-gray-900">{{ get_setting('clinic_name', 'Clinic Management') }}</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    @php
                        $servicesPage = \App\Models\Page::where('type', 'services')->first();
                        $teamPage = \App\Models\Page::where('type', 'team')->first();
                        $packagesPage = \App\Models\Page::where('type', 'packages')->first();
                        $aboutPage = \App\Models\Page::where('type', 'about')->first();
                    @endphp
                    
                    @if(!$servicesPage || $servicesPage->is_published)
                        <a href="{{ route('services.index') }}"
                            class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition">
                            Services
                        </a>
                    @endif
                    
                    @if(!$aboutPage || $aboutPage->is_published)
                        <a href="{{ route('about') }}"
                            class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition">
                            About Us
                        </a>
                    @endif
                    
                    @if(!$teamPage || $teamPage->is_published)
                        <a href="{{ route('team.index') }}"
                            class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition">
                            Our Team
                        </a>
                    @endif
                    
                    @if(!$packagesPage || $packagesPage->is_published)
                        <a href="{{ route('packages.index') }}"
                            class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition">
                            Packages
                        </a>
                    @endif
                    @auth
                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open"
                                class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                                <div
                                    class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-sm shadow-md">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span
                                    class="text-sm font-medium text-gray-700 hidden md:block">{{ Auth::user()->name }}</span>
                                <i class='bx bx-chevron-down text-gray-500 transition-transform text-lg'
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
                                </div>

                                <!-- Dashboard Link -->
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class='bx bx-home-alt mr-3 text-lg'></i>
                                        <span>Dashboard</span>
                                    </a>
                                @elseif(Auth::user()->role === 'doctor')
                                    <a href="{{ route('doctor.dashboard') }}"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class='bx bx-home-alt mr-3 text-lg'></i>
                                        <span>Dashboard</span>
                                    </a>
                                @elseif(Auth::user()->role === 'staff')
                                    <a href="{{ route('staff.dashboard') }}"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class='bx bx-home-alt mr-3 text-lg'></i>
                                        <span>Dashboard</span>
                                    </a>
                                @else
                                    <a href="{{ route('patient.dashboard') }}"
                                        class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                        <i class='bx bx-home-alt mr-3 text-lg'></i>
                                        <span>Dashboard</span>
                                    </a>
                                @endif

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
                    @else
                        <a href="{{ route('login') }}"
                            class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

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