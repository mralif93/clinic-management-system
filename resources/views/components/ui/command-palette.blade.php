@props([
    'shortcuts' => []
])

<div 
    x-data="{ open: false, query: '', selectedIndex: 0, shortcuts: @js($shortcuts) }"
    x-show="open"
    @command-palette:open.window="open = true; $nextTick(() => $refs.input.focus())"
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 hidden"
    style="display: none;"
    x-transition
>
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="open = false"></div>
    
    <!-- Palette -->
    <div class="fixed top-1/4 left-1/2 transform -translate-x-1/2 w-full max-w-2xl mx-4">
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            <!-- Search Input -->
            <div class="p-4 border-b border-gray-200">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class='bx bx-search text-gray-400'></i>
                    </div>
                    <input
                        x-ref="input"
                        type="text"
                        x-model="query"
                        placeholder="Type a command or search..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        @keydown.arrow-down.prevent="selectedIndex = Math.min(selectedIndex + 1, filteredShortcuts.length - 1)"
                        @keydown.arrow-up.prevent="selectedIndex = Math.max(selectedIndex - 1, 0)"
                        @keydown.enter.prevent="executeCommand(filteredShortcuts[selectedIndex])"
                    >
                </div>
            </div>
            
            <!-- Results -->
            <div class="max-h-96 overflow-y-auto">
                <template x-if="filteredShortcuts.length === 0">
                    <div class="p-8 text-center">
                        <i class='bx bx-search text-4xl text-gray-300 mb-2'></i>
                        <p class="text-sm text-gray-500">No commands found</p>
                    </div>
                </template>
                
                <template x-for="(shortcut, index) in filteredShortcuts" :key="index">
                    <button
                        @click="executeCommand(shortcut)"
                        :class="{ 'bg-blue-50': selectedIndex === index }"
                        class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors text-left border-b border-gray-100 last:border-b-0"
                    >
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i :class="shortcut.icon + ' text-gray-600 text-xl'"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900" x-text="shortcut.label"></p>
                                <p class="text-xs text-gray-500" x-text="shortcut.description"></p>
                            </div>
                        </div>
                        <kbd class="px-2 py-1 text-xs font-semibold text-gray-500 bg-gray-100 rounded" x-text="shortcut.key"></kbd>
                    </button>
                </template>
            </div>
            
            <!-- Footer -->
            <div class="p-3 border-t border-gray-200 bg-gray-50 flex items-center justify-between text-xs text-gray-500">
                <div class="flex items-center gap-4">
                    <span><kbd class="px-1.5 py-0.5 bg-white rounded">↑↓</kbd> Navigate</span>
                    <span><kbd class="px-1.5 py-0.5 bg-white rounded">Enter</kbd> Select</span>
                    <span><kbd class="px-1.5 py-0.5 bg-white rounded">Esc</kbd> Close</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Filter shortcuts based on query
    document.addEventListener('alpine:init', () => {
        Alpine.data('commandPalette', () => ({
            filteredShortcuts() {
                if (!this.query) {
                    return this.shortcuts;
                }
                const q = this.query.toLowerCase();
                return this.shortcuts.filter(s => 
                    s.label.toLowerCase().includes(q) || 
                    s.description.toLowerCase().includes(q)
                );
            },
            executeCommand(shortcut) {
                if (shortcut && shortcut.action) {
                    if (typeof shortcut.action === 'function') {
                        shortcut.action();
                    } else if (shortcut.url) {
                        window.location.href = shortcut.url;
                    }
                }
                this.open = false;
            }
        }));
    });
</script>

