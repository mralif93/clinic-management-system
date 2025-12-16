/**
 * Global Search Functionality
 */

window.GlobalSearch = {
    /**
     * Initialize search
     * @param {string} searchInputId - Search input ID
     * @param {object} options - Options
     */
    init(searchInputId, options = {}) {
        const input = document.getElementById(searchInputId);
        if (!input) return;

        const defaultOptions = {
            minLength: 2,
            debounce: 300,
            endpoint: '/search/autocomplete',
            ...options
        };

        let debounceTimer;
        let selectedIndex = -1;
        let results = [];

        // Create results container
        const resultsContainer = document.createElement('div');
        resultsContainer.className = 'search-results absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-lg max-h-96 overflow-y-auto hidden';
        resultsContainer.id = `${searchInputId}_results`;
        input.parentElement.appendChild(resultsContainer);

        // Handle input
        input.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            clearTimeout(debounceTimer);

            if (query.length < defaultOptions.minLength) {
                resultsContainer.classList.add('hidden');
                results = [];
                return;
            }

            debounceTimer = setTimeout(() => {
                this.performSearch(query, defaultOptions.endpoint)
                    .then(data => {
                        results = data.results || [];
                        this.displayResults(resultsContainer, results, selectedIndex);
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    });
            }, defaultOptions.debounce);
        });

        // Handle keyboard navigation
        input.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, results.length - 1);
                this.displayResults(resultsContainer, results, selectedIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                this.displayResults(resultsContainer, results, selectedIndex);
            } else if (e.key === 'Enter' && selectedIndex >= 0) {
                e.preventDefault();
                if (results[selectedIndex]) {
                    window.location.href = results[selectedIndex].url;
                }
            } else if (e.key === 'Escape') {
                resultsContainer.classList.add('hidden');
            }
        });

        // Hide results on click outside
        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });
    },

    /**
     * Perform search
     * @param {string} query - Search query
     * @param {string} endpoint - Search endpoint
     * @returns {Promise}
     */
    async performSearch(query, endpoint) {
        const response = await fetch(`${endpoint}?q=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Search failed');
        }

        return response.json();
    },

    /**
     * Display search results
     * @param {HTMLElement} container - Results container
     * @param {Array} results - Search results
     * @param {number} selectedIndex - Selected index
     */
    displayResults(container, results, selectedIndex) {
        if (results.length === 0) {
            container.innerHTML = `
                <div class="p-4 text-center">
                    <i class='bx bx-search text-4xl text-gray-300 mb-2'></i>
                    <p class="text-sm text-gray-500">No results found</p>
                </div>
            `;
            container.classList.remove('hidden');
            return;
        }

        container.innerHTML = results.map((result, index) => `
            <a href="${result.url}" 
               class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0 ${selectedIndex === index ? 'bg-blue-50' : ''}">
                <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i class='${result.icon} text-gray-600 text-xl'></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">${result.title}</p>
                    <p class="text-xs text-gray-500 truncate">${result.subtitle || ''}</p>
                </div>
                <i class='bx bx-chevron-right text-gray-400'></i>
            </a>
        `).join('');

        container.classList.remove('hidden');
    }
};

// Auto-initialize search inputs with data-global-search attribute
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-global-search="true"]').forEach(input => {
        GlobalSearch.init(input.id || `search_${Math.random().toString(36).substr(2, 9)}`);
    });
});

