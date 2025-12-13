<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | {{ get_setting('clinic_name', 'Clinic Management System') }}</title>

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

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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

    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 font-sans min-h-screen flex items-center justify-center px-4">
    <div class="max-w-2xl w-full text-center">
        <!-- Logo -->
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
            <div class="flex justify-center mb-8">
                <img src="{{ $logoUrl }}" 
                     alt="{{ get_setting('clinic_name', 'Clinic Management') }} Logo" 
                     class="h-16 md:h-20 w-auto object-contain">
            </div>
        @endif

        <!-- Error Code -->
        <div class="mb-6">
            <h1 class="text-9xl md:text-[12rem] font-bold text-blue-600 leading-none mb-4">404</h1>
            <div class="w-24 h-1 bg-blue-600 mx-auto rounded-full"></div>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Page Not Found</h2>
            <p class="text-lg text-gray-600 max-w-md mx-auto">
                Sorry, we couldn't find the page you're looking for. The page may have been moved, deleted, or doesn't exist.
            </p>
        </div>

        <!-- Icon -->
        <div class="mb-8 flex justify-center">
            <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center">
                <i class='bx bx-search-alt-2 text-5xl text-blue-600'></i>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ route('home') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition shadow-lg hover:shadow-xl">
                <i class='bx bx-home text-xl'></i>
                Go to Homepage
            </a>
            <button onclick="window.history.back()" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition shadow-md hover:shadow-lg">
                <i class='bx bx-arrow-back text-xl'></i>
                Go Back
            </button>
        </div>

        <!-- Helpful Links -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <p class="text-sm text-gray-500 mb-4">You might be looking for:</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('services.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition">
                    Our Services
                </a>
                <a href="{{ route('about') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition">
                    About Us
                </a>
                <a href="{{ route('team.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition">
                    Our Team
                </a>
                <a href="{{ route('packages.index') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition">
                    Packages
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center">
            <p class="text-xs text-gray-400">
                &copy; {{ date('Y') }} {{ get_setting('clinic_name', 'Clinic Management System') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
