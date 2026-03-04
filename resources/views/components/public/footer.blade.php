<!-- Simple Footer -->
<footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-center md:text-left">
                <p class="text-xs text-gray-500">&copy; {{ date('Y') }} {{ get_setting('clinic_name', 'Clinic Management System') }}. All rights reserved.</p>
            </div>
            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('how-it-works') }}" class="text-gray-500 hover:text-blue-600">
                    How It Works
                </a>
                <a href="{{ route('services.index') }}" class="text-gray-500 hover:text-blue-600">
                    Services
                </a>
                <a href="{{ route('team.index') }}" class="text-gray-500 hover:text-blue-600">
                    Team
                </a>
                <a href="{{ route('about') }}" class="text-gray-500 hover:text-blue-600">
                    About
                </a>
            </div>
        </div>
    </div>
</footer>
