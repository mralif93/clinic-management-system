/**
 * Keyboard Shortcuts System
 */

window.KeyboardShortcuts = {
    shortcuts: new Map(),
    commandPaletteOpen: false,

    /**
     * Register a keyboard shortcut
     * @param {string} key - Key combination (e.g., 'ctrl+k', 'alt+n')
     * @param {Function} callback - Callback function
     * @param {object} options - Options
     */
    register(key, callback, options = {}) {
        const shortcut = {
            key: this.parseKey(key),
            callback,
            description: options.description || '',
            preventDefault: options.preventDefault !== false,
            ...options
        };

        this.shortcuts.set(key, shortcut);
    },

    /**
     * Parse key combination
     * @param {string} keyString - Key string
     * @returns {object} - Parsed key object
     */
    parseKey(keyString) {
        const parts = keyString.toLowerCase().split('+');
        return {
            ctrl: parts.includes('ctrl') || parts.includes('cmd'),
            alt: parts.includes('alt'),
            shift: parts.includes('shift'),
            key: parts[parts.length - 1]
        };
    },

    /**
     * Check if key combination matches
     * @param {KeyboardEvent} event - Keyboard event
     * @param {object} keyObj - Key object
     * @returns {boolean}
     */
    matches(event, keyObj) {
        const ctrlKey = event.ctrlKey || event.metaKey; // Support Cmd on Mac
        return (
            (keyObj.ctrl === ctrlKey) &&
            (keyObj.alt === event.altKey) &&
            (keyObj.shift === event.shiftKey) &&
            (keyObj.key === event.key.toLowerCase())
        );
    },

    /**
     * Initialize keyboard shortcuts
     */
    init() {
        document.addEventListener('keydown', (e) => {
            // Check each registered shortcut
            for (const [keyString, shortcut] of this.shortcuts.entries()) {
                if (this.matches(e, shortcut.key)) {
                    if (shortcut.preventDefault) {
                        e.preventDefault();
                    }

                    // Check if shortcut should be active
                    if (shortcut.condition && !shortcut.condition()) {
                        continue;
                    }

                    shortcut.callback(e);
                    break;
                }
            }
        });

        // Register default shortcuts
        this.registerDefaults();
    },

    /**
     * Register default shortcuts
     */
    registerDefaults() {
        // Command palette (Ctrl/Cmd + K)
        this.register('ctrl+k', () => {
            this.openCommandPalette();
        }, {
            description: 'Open command palette'
        });

        // Quick search (Ctrl/Cmd + F)
        this.register('ctrl+f', () => {
            const searchInput = document.querySelector('[data-global-search="true"]');
            if (searchInput) {
                searchInput.focus();
            }
        }, {
            description: 'Focus search',
            preventDefault: false // Allow browser search to work
        });

        // New item shortcuts (context-aware)
        this.register('ctrl+n', () => {
            // Try to find "New" or "Create" button/link
            const newButton = document.querySelector('a[href*="/create"], button[onclick*="create"]');
            if (newButton) {
                newButton.click();
            }
        }, {
            description: 'Create new item'
        });

        // Escape to close modals/dropdowns
        this.register('escape', () => {
            // Close any open dropdowns/modals
            document.querySelectorAll('[x-show]').forEach(el => {
                if (window.Alpine && Alpine.$data(el)) {
                    // Close Alpine.js dropdowns
                    const data = Alpine.$data(el);
                    if (data.open !== undefined) {
                        data.open = false;
                    }
                }
            });
        }, {
            description: 'Close modals/dropdowns',
            key: this.parseKey('escape')
        });
    },

    /**
     * Open command palette
     */
    openCommandPalette() {
        if (this.commandPaletteOpen) {
            return;
        }

        this.commandPaletteOpen = true;
        // Command palette will be implemented as a separate component
        const event = new CustomEvent('command-palette:open');
        document.dispatchEvent(event);
    },

    /**
     * Get all registered shortcuts
     * @returns {Array} - Array of shortcuts
     */
    getAll() {
        return Array.from(this.shortcuts.entries()).map(([key, shortcut]) => ({
            key,
            description: shortcut.description,
            callback: shortcut.callback
        }));
    }
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    KeyboardShortcuts.init();
});

