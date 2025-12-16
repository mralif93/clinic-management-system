/**
 * Table Filtering Functionality
 */

window.TableFilter = {
    /**
     * Initialize filtering for a table
     * @param {string} tableId - Table ID
     * @param {object} options - Filter options
     */
    init(tableId, options = {}) {
        const table = document.getElementById(tableId);
        if (!table) return;

        const defaultOptions = {
            debounce: 300,
            highlightMatches: true,
            preserveState: true,
            ...options
        };

        table.dataset.filterDebounce = defaultOptions.debounce;
        table.dataset.filterHighlight = defaultOptions.highlightMatches;
        table.dataset.filterPreserveState = defaultOptions.preserveState;

        // Create filter input if not exists
        this.createFilterInput(table, defaultOptions);
    },

    /**
     * Create filter input element
     * @param {HTMLElement} table - Table element
     * @param {object} options - Options
     */
    createFilterInput(table, options) {
        // Check if filter input already exists
        if (table.previousElementSibling?.classList.contains('table-filter-container')) {
            return;
        }

        const container = document.createElement('div');
        container.className = 'table-filter-container mb-4 flex items-center gap-3';

        const input = document.createElement('input');
        input.type = 'text';
        input.placeholder = 'Filter table...';
        input.className = 'flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
        input.setAttribute('aria-label', 'Filter table');

        const clearButton = document.createElement('button');
        clearButton.type = 'button';
        clearButton.className = 'px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors';
        clearButton.innerHTML = '<i class="bx bx-x"></i> Clear';
        clearButton.style.display = 'none';

        container.appendChild(input);
        container.appendChild(clearButton);
        table.parentElement.insertBefore(container, table);

        // Filter on input
        let debounceTimer;
        input.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                this.filter(table, e.target.value);
                clearButton.style.display = e.target.value ? 'block' : 'none';
            }, options.debounce);
        });

        // Clear filter
        clearButton.addEventListener('click', () => {
            input.value = '';
            this.filter(table, '');
            clearButton.style.display = 'none';
        });
    },

    /**
     * Filter table rows
     * @param {HTMLElement|string} table - Table element or ID
     * @param {string} searchTerm - Search term
     */
    filter(table, searchTerm) {
        const tableElement = typeof table === 'string' 
            ? document.getElementById(table) 
            : table;
        
        if (!tableElement) return;

        const tbody = tableElement.querySelector('tbody');
        if (!tbody) return;

        const rows = tbody.querySelectorAll('tr');
        const term = searchTerm.toLowerCase().trim();
        const highlightMatches = tableElement.dataset.filterHighlight !== 'false';

        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matches = term === '' || text.includes(term);

            if (matches) {
                row.style.display = '';
                visibleCount++;

                // Highlight matches
                if (highlightMatches && term) {
                    this.highlightMatches(row, term);
                } else {
                    this.removeHighlights(row);
                }
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide empty state
        this.updateEmptyState(tableElement, visibleCount === 0, searchTerm);

        // Dispatch custom event
        tableElement.dispatchEvent(new CustomEvent('table:filtered', {
            detail: { searchTerm, visibleCount }
        }));
    },

    /**
     * Highlight matching text in row
     * @param {HTMLElement} row - Table row
     * @param {string} term - Search term
     */
    highlightMatches(row, term) {
        const cells = row.querySelectorAll('td');
        cells.forEach(cell => {
            const text = cell.textContent;
            const regex = new RegExp(`(${term})`, 'gi');
            const highlighted = text.replace(regex, '<mark class="bg-yellow-200">$1</mark>');
            cell.innerHTML = highlighted;
        });
    },

    /**
     * Remove highlights from row
     * @param {HTMLElement} row - Table row
     */
    removeHighlights(row) {
        const cells = row.querySelectorAll('td');
        cells.forEach(cell => {
            const marks = cell.querySelectorAll('mark');
            marks.forEach(mark => {
                mark.outerHTML = mark.textContent;
            });
        });
    },

    /**
     * Update empty state message
     * @param {HTMLElement} table - Table element
     * @param {boolean} isEmpty - Is empty
     * @param {string} searchTerm - Search term
     */
    updateEmptyState(table, isEmpty, searchTerm) {
        // Remove existing empty state
        const existing = table.parentElement.querySelector('.table-empty-state');
        if (existing) {
            existing.remove();
        }

        if (isEmpty && searchTerm) {
            const emptyState = document.createElement('div');
            emptyState.className = 'table-empty-state text-center py-8';
            emptyState.innerHTML = `
                <i class='bx bx-search text-4xl text-gray-300 mb-2'></i>
                <p class="text-gray-500">No results found for "${searchTerm}"</p>
            `;
            table.parentElement.insertBefore(emptyState, table.nextSibling);
        }
    }
};

// Auto-initialize tables with data-filterable attribute
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('table[data-filterable="true"]').forEach(table => {
        TableFilter.init(table.id || `table_${Math.random().toString(36).substr(2, 9)}`);
    });
});

