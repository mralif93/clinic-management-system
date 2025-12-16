/**
 * Table Sorting Functionality
 */

window.TableSort = {
    /**
     * Initialize sorting for a table
     * @param {string} tableId - Table ID
     * @param {object} options - Sorting options
     */
    init(tableId, options = {}) {
        const table = document.getElementById(tableId);
        if (!table) return;

        const defaultOptions = {
            defaultSort: null,
            defaultDirection: 'asc',
            preserveState: true,
            ...options
        };

        // Store options on table element
        table.dataset.sortDefault = defaultOptions.defaultSort || '';
        table.dataset.sortDirection = defaultOptions.defaultDirection;
        table.dataset.sortPreserveState = defaultOptions.preserveState;

        // Load saved state
        if (defaultOptions.preserveState) {
            const savedSort = localStorage.getItem(`table_sort_${tableId}`);
            if (savedSort) {
                const { key, direction } = JSON.parse(savedSort);
                this.sort(table, key, direction, false);
            }
        }
    },

    /**
     * Sort table by column
     * @param {HTMLElement|string} table - Table element or ID
     * @param {string} sortKey - Column key to sort by
     * @param {string} direction - Sort direction (asc/desc)
     * @param {boolean} saveState - Save state to localStorage
     */
    sort(table, sortKey, direction = null, saveState = true) {
        const tableElement = typeof table === 'string' 
            ? document.getElementById(table) 
            : table;
        
        if (!tableElement) return;

        const tbody = tableElement.querySelector('tbody');
        if (!tbody) return;

        // Get current sort state
        const currentSort = tableElement.dataset.currentSort || '';
        const currentDirection = tableElement.dataset.currentDirection || 'asc';

        // Determine new direction
        let newDirection = direction;
        if (!newDirection) {
            if (currentSort === sortKey) {
                newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
            } else {
                newDirection = 'asc';
            }
        }

        // Get rows
        const rows = Array.from(tbody.querySelectorAll('tr'));
        if (rows.length === 0) return;

        // Sort rows
        rows.sort((a, b) => {
            const aValue = this.getCellValue(a, sortKey);
            const bValue = this.getCellValue(b, sortKey);

            // Handle different data types
            const aNum = parseFloat(aValue);
            const bNum = parseFloat(bValue);
            const isNumeric = !isNaN(aNum) && !isNaN(bNum);

            let comparison = 0;
            if (isNumeric) {
                comparison = aNum - bNum;
            } else {
                comparison = String(aValue).localeCompare(String(bValue));
            }

            return newDirection === 'asc' ? comparison : -comparison;
        });

        // Reorder rows in DOM
        rows.forEach(row => tbody.appendChild(row));

        // Update sort indicators
        this.updateSortIndicators(tableElement, sortKey, newDirection);

        // Save state
        tableElement.dataset.currentSort = sortKey;
        tableElement.dataset.currentDirection = newDirection;

        if (saveState && tableElement.dataset.sortPreserveState !== 'false') {
            localStorage.setItem(`table_sort_${tableElement.id}`, JSON.stringify({
                key: sortKey,
                direction: newDirection
            }));
        }

        // Dispatch custom event
        tableElement.dispatchEvent(new CustomEvent('table:sorted', {
            detail: { sortKey, direction: newDirection }
        }));
    },

    /**
     * Get cell value for sorting
     * @param {HTMLElement} row - Table row
     * @param {string} sortKey - Column key
     * @returns {string|number}
     */
    getCellValue(row, sortKey) {
        // Try data attribute first
        const cell = row.querySelector(`[data-sort-key="${sortKey}"]`);
        if (cell) {
            const value = cell.dataset.sortValue || cell.textContent.trim();
            return value;
        }

        // Try column index
        const header = row.closest('table')?.querySelector('thead');
        if (header) {
            const headers = Array.from(header.querySelectorAll('th'));
            const index = headers.findIndex(th => {
                const key = th.dataset.sortKey || th.textContent.trim().toLowerCase().replace(/\s+/g, '_');
                return key === sortKey;
            });

            if (index >= 0) {
                const cells = row.querySelectorAll('td');
                if (cells[index]) {
                    return cells[index].textContent.trim();
                }
            }
        }

        return '';
    },

    /**
     * Update sort indicators in table header
     * @param {HTMLElement} table - Table element
     * @param {string} sortKey - Active sort key
     * @param {string} direction - Sort direction
     */
    updateSortIndicators(table, sortKey, direction) {
        const headers = table.querySelectorAll('thead th[data-sort-key]');
        headers.forEach(header => {
            const headerKey = header.dataset.sortKey;
            const iconContainer = header.querySelector('.flex.items-center');
            
            if (!iconContainer) return;

            // Remove existing sort icons
            const existingIcon = iconContainer.querySelector('.bx-sort, .bx-sort-up, .bx-sort-down');
            if (existingIcon) {
                existingIcon.remove();
            }

            // Add new icon
            if (headerKey === sortKey) {
                const icon = document.createElement('i');
                icon.className = direction === 'asc' 
                    ? 'bx bx-sort-up ml-1 text-blue-600' 
                    : 'bx bx-sort-down ml-1 text-blue-600';
                iconContainer.appendChild(icon);
            } else {
                const icon = document.createElement('i');
                icon.className = 'bx bx-sort ml-1 text-gray-400';
                iconContainer.appendChild(icon);
            }
        });
    }
};

// Global sort function for inline onclick handlers
window.sortTable = function(sortKey) {
    const table = document.querySelector('table[data-sortable="true"]');
    if (table) {
        TableSort.sort(table, sortKey);
    }
};

