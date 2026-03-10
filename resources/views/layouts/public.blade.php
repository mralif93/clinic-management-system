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

<body class="theme-public bg-gray-50 font-sans" x-data="{ mobileMenuOpen: false }"
    :class="{ 'overflow-hidden': mobileMenuOpen }">
    <x-ui.skip-nav />
    <!-- Header -->
    <header
        class="bg-white/95 backdrop-blur-md border-b border-gray-200/80 shadow-sm sticky top-0 transition-all duration-300"
        style="z-index: 40 !important;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-18">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        @php
                            $logoPath = get_setting('clinic_logo');
                            if ($logoPath && str_starts_with($logoPath, 'data:')) {
                                $logoUrl = $logoPath;
                            } elseif ($logoPath) {
                                $logoUrl = asset('storage/' . $logoPath);
                            } else {
                                $logoUrl = null;
                            }
                        @endphp
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ get_setting('clinic_name', 'Clinic Management') }} Logo"
                                class="h-8 w-auto">
                        @else
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                <i class='hgi-stroke hgi-hospital-01 text-white text-xl'></i>
                            </div>
                        @endif
                        <span class="text-xl font-bold text-gray-900 hidden sm:block">
                            {{ get_setting('clinic_name', 'Clinic Management') }}
                        </span>
                    </a>
                </div>

                <!-- Navigation (Desktop) -->
                <nav class="hidden lg:flex items-center space-x-8">
                    @php
                        $servicesPage = \App\Models\Page::where('type', 'services')->first();
                        $teamPage = \App\Models\Page::where('type', 'team')->first();
                        $packagesPage = \App\Models\Page::where('type', 'packages')->first();
                        $aboutPage = \App\Models\Page::where('type', 'about')->first();
                    @endphp

                    @if(!$servicesPage || $servicesPage->is_published)
                        <a href="{{ route('services.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                            Services
                        </a>
                    @endif

                    @if(!$aboutPage || $aboutPage->is_published)
                        <a href="{{ route('about') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                            About
                        </a>
                    @endif

                    @if(!$teamPage || $teamPage->is_published)
                        <a href="{{ route('team.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                            Team
                        </a>
                    @endif

                    @if(!$packagesPage || $packagesPage->is_published)
                        <a href="{{ route('packages.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                            Packages
                        </a>
                    @endif

                    <a href="{{ route('how-it-works') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                        How It Works
                    </a>
                </nav>

                <!-- Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Hamburger Menu Button (Mobile) -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden p-2 text-gray-600 hover:text-gray-900 focus:outline-none rounded-lg hover:bg-gray-100">
                        <i class='hgi-stroke hgi-menu-01 text-2xl' x-show="!mobileMenuOpen"></i>
                        <i class='hgi-stroke hgi-cancel-circle text-2xl' x-show="mobileMenuOpen" x-cloak></i>
                    </button>

                    <!-- Desktop Actions -->
                    <div class="hidden lg:flex items-center space-x-4">
                        @auth
                            <!-- User Menu -->
                            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open"
                                    class="flex items-center space-x-2 text-gray-700 hover:text-blue-600">
                                    <div
                                        class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-sm font-semibold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium">{{ explode(' ', Auth::user()->name)[0] }}</span>
                                    <i class='hgi-stroke hgi-arrow-down-01'></i>
                                </button>

                                <!-- Dropdown -->
                                <div x-show="open" x-cloak x-transition
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1"
                                    style="display: none;">

                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                    </div>

                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Dashboard
                                        </a>
                                    @elseif(Auth::user()->role === 'doctor')
                                        <a href="{{ route('doctor.dashboard') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Dashboard
                                        </a>
                                    @elseif(Auth::user()->role === 'staff')
                                        <a href="{{ route('staff.dashboard') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Dashboard
                                        </a>
                                    @else
                                        <a href="{{ route('patient.dashboard') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Dashboard
                                        </a>
                                    @endif

                                    <a href="{{ route('user-guide') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2">
                                        <i class='hgi-stroke hgi-book-open-02'></i> User Guide
                                    </a>

                                    <div class="border-t border-gray-100">
                                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                                            @csrf
                                            <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Guest Buttons -->
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                Get Started
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Component -->
    <x-public.mobile-menu />

    <main id="main-content" class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <x-public.footer />

    <!-- Splide.js JS -->
    <script src="{{ asset('js/splide.min.js') }}"></script>

    @stack('scripts')

    <!-- UI Enhancement Scripts -->
    <script src="{{ asset('js/skeleton.js') }}"></script>
    <script src="{{ asset('js/lazy-load.js') }}"></script>
    <script src="{{ asset('js/animations.js') }}"></script>
    <script src="{{ asset('js/image-optimizer.js') }}"></script>

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
            showSuccess(@json(session('success')));
        @endif

        @if(session('error'))
            showError(@json(session('error')));
        @endif

        @if(session('info'))
            showInfo(@json(session('info')));
        @endif

        @if(session('warning'))
            showWarning(@json(session('warning')));
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