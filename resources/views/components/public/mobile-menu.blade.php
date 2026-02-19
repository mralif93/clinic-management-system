@php
    $servicesPage = \App\Models\Page::where('type', 'services')->first();
    $teamPage = \App\Models\Page::where('type', 'team')->first();
    $packagesPage = \App\Models\Page::where('type', 'packages')->first();
    $aboutPage = \App\Models\Page::where('type', 'about')->first();
@endphp

<div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-full" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-full"
    class="fixed inset-0 z-50 bg-white lg:hidden flex flex-col h-[100dvh]" x-cloak>
    <!-- Header -->
    <div class="flex items-center justify-between px-4 h-16 border-b border-gray-100 shrink-0">
        <div class="flex items-center space-x-3">
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
                <img src="{{ $logoUrl }}" alt="Logo" class="h-8 w-auto">
            @else
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class='bx bx-clinic text-white text-xl'></i>
                </div>
            @endif
            <span class="text-xl font-bold text-gray-900">Menu</span>
        </div>
        <button @click="mobileMenuOpen = false"
            class="p-2 -mr-2 text-gray-500 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 rounded-lg">
            <span class="sr-only">Close menu</span>
            <i class='bx bx-x text-3xl'></i>
        </button>
    </div>

    <!-- Scrollable Content -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-8">
        <!-- Navigation -->
        <nav class="flex flex-col space-y-4">
            @if(!$servicesPage || $servicesPage->is_published)
                <a href="{{ route('services.index') }}"
                    class="flex items-center space-x-3 text-lg font-medium text-gray-900 hover:text-blue-600 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class='bx bx-plus-medical text-xl text-gray-400'></i>
                    <span>Services</span>
                </a>
            @endif

            @if(!$aboutPage || $aboutPage->is_published)
                <a href="{{ route('about') }}"
                    class="flex items-center space-x-3 text-lg font-medium text-gray-900 hover:text-blue-600 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class='bx bx-info-circle text-xl text-gray-400'></i>
                    <span>About</span>
                </a>
            @endif

            @if(!$teamPage || $teamPage->is_published)
                <a href="{{ route('team.index') }}"
                    class="flex items-center space-x-3 text-lg font-medium text-gray-900 hover:text-blue-600 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class='bx bx-user-voice text-xl text-gray-400'></i>
                    <span>Team</span>
                </a>
            @endif

            @if(!$packagesPage || $packagesPage->is_published)
                <a href="{{ route('packages.index') }}"
                    class="flex items-center space-x-3 text-lg font-medium text-gray-900 hover:text-blue-600 p-2 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class='bx bx-package text-xl text-gray-400'></i>
                    <span>Packages</span>
                </a>
            @endif
        </nav>

        <!-- Divider -->
        <div class="border-t border-gray-100"></div>

        <!-- Auth Actions -->
        <div class="space-y-4">
            @auth
                <div class="flex items-center space-x-3 p-2 rounded-lg bg-gray-50">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3">
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Dashboard
                        </a>
                    @elseif(Auth::user()->role === 'doctor')
                        <a href="{{ route('doctor.dashboard') }}"
                            class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Dashboard
                        </a>
                    @elseif(Auth::user()->role === 'staff')
                        <a href="{{ route('staff.dashboard') }}"
                            class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('patient.dashboard') }}"
                            class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Dashboard
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center px-4 py-3 border border-red-200 text-red-600 font-medium rounded-lg hover:bg-red-50 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('login') }}"
                        class="flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="flex items-center justify-center px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Get Started
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>