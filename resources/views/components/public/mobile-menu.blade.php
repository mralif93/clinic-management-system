@props([
    'items' => []
])

<div 
    x-data="{ 
        open: false,
        init() {
            // Close menu when page loads (in case it was open)
            this.open = false;
            
            // Close menu when navigating away
            window.addEventListener('popstate', () => {
                this.open = false;
            });
            
            // Close menu when clicking on any link
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a[href]');
                if (link && !link.closest('[x-data]')) {
                    this.open = false;
                }
            });
            
            // Watch for menu state changes and manage body scroll
            this.$watch('open', (value) => {
                if (value) {
                    document.body.classList.add('menu-open');
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.classList.remove('menu-open');
                    document.body.style.overflow = '';
                }
            });
        }
    }"
    class="lg:hidden"
    x-cloak
>
    <!-- Note: This menu is now used for both mobile AND tablet views (< 1024px) -->
    <!-- Mobile Menu Button -->
    <button 
        @click="open = !open"
        class="p-2.5 rounded-lg text-gray-600 hover:bg-gray-100 active:bg-gray-200 transition-colors min-w-[44px] min-h-[44px] flex items-center justify-center"
        aria-label="Toggle menu"
        aria-expanded="false"
        :aria-expanded="open"
    >
        <i class='bx bx-menu text-2xl' x-show="!open"></i>
        <i class='bx bx-x text-2xl' x-show="open" style="display: none;"></i>
    </button>
    
    <!-- Mobile Menu Overlay -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        @keydown.escape.window="open = false"
        class="mobile-menu-overlay"
        style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; width: 100vw !important; height: 100vh !important; background-color: rgba(0, 0, 0, 0.6) !important; backdrop-filter: blur(4px) !important; z-index: 99998 !important; display: none;"
        x-cloak
    ></div>
    
    <!-- Mobile Menu Panel -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="transform translate-x-full"
        x-transition:enter-end="transform translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="transform translate-x-0"
        x-transition:leave-end="transform translate-x-full"
        @click.away="open = false"
        class="mobile-menu-panel"
        style="position: fixed !important; top: 0 !important; right: 0 !important; bottom: 0 !important; width: 260px !important; height: 100vh !important; background-color: white !important; box-shadow: -4px 0 24px rgba(0, 0, 0, 0.15) !important; overflow-y: auto !important; z-index: 99999 !important; display: none;"
        x-cloak
    >
        <!-- Header Section -->
        <div class="sticky top-0 bg-white border-b border-gray-200 px-3 py-2 z-20">
            <div class="flex justify-between items-center">
                <h2 class="text-sm font-semibold text-gray-900">Menu</h2>
                <button 
                    @click="open = false"
                    class="p-1.5 rounded text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors min-w-[40px] min-h-[40px] flex items-center justify-center"
                    aria-label="Close menu"
                >
                    <i class='bx bx-x text-lg'></i>
                </button>
            </div>
        </div>
        
        <div class="p-3">
            <!-- Navigation Items -->
            <nav class="space-y-1">
                @foreach($items as $item)
                    @php
                        $isActive = request()->url() === ($item['url'] ?? '#');
                        $iconClass = isset($item['icon']) ? (str_starts_with($item['icon'], 'bx ') ? $item['icon'] : 'bx ' . $item['icon']) : 'bx bx-circle';
                    @endphp
                    <a 
                        href="{{ $item['url'] ?? '#' }}"
                        class="flex flex-row items-center gap-3 px-3 rounded-lg transition-colors {{ $isActive ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}"
                        style="height: 44px; display: flex !important; flex-direction: row !important; align-items: center !important;"
                        @click="open = false"
                    >
                        <i class='{{ $iconClass }} {{ $isActive ? 'text-blue-600' : 'text-gray-400' }}' style="font-size: 18px; line-height: 1; flex-shrink: 0; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;"></i>
                        <span class="text-sm" style="line-height: 1; display: flex; align-items: center;">{{ $item['label'] ?? '' }}</span>
                    </a>
                @endforeach
            </nav>
            
            <!-- Auth Section -->
            <div class="mt-2 pt-2 border-t border-gray-200">
                @auth
                    <!-- User Info -->
                    <div class="flex items-center gap-2 px-2 py-1.5 bg-gray-50 rounded mb-1">
                        <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold text-[10px] flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    
                    <a 
                        href="{{ route('patient.dashboard') }}"
                        class="flex items-center gap-2 px-3 py-2 text-gray-700 hover:bg-gray-50 transition-colors min-h-[40px]"
                        @click="open = false"
                    >
                        <i class='bx bx-home-alt text-base text-gray-400'></i>
                        <span class="text-sm">Dashboard</span>
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button 
                            type="submit"
                            class="flex items-center gap-2 w-full px-3 py-2 text-red-600 hover:bg-red-50 transition-colors min-h-[40px]"
                            @click="open = false"
                        >
                            <i class='bx bx-log-out text-base'></i>
                            <span class="text-sm">Logout</span>
                        </button>
                    </form>
                @else
                    <div class="space-y-1.5 px-2">
                        <a 
                            href="{{ route('login') }}"
                            class="flex items-center justify-center gap-1.5 w-full px-3 py-1.5 border border-gray-300 text-gray-700 rounded text-xs hover:bg-gray-50 transition-colors min-h-[36px]"
                            @click="open = false"
                        >
                            <i class='bx bx-log-in text-sm'></i>
                            <span>Login</span>
                        </a>
                        <a 
                            href="{{ route('register') }}"
                            class="flex items-center justify-center gap-1.5 w-full px-3 py-1.5 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 transition-colors min-h-[36px]"
                            @click="open = false"
                        >
                            <i class='bx bx-user-plus text-sm'></i>
                            <span>Get Started</span>
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

