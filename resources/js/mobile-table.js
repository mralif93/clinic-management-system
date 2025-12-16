/**
 * Mobile Table Responsive Behavior
 * Converts tables to card layout on mobile devices
 */

window.MobileTable = {
    /**
     * Initialize mobile table conversion
     * @param {string} tableId - Table ID
     * @param {object} options - Options
     */
    init(tableId, options = {}) {
        const table = document.getElementById(tableId);
        if (!table) return;

        const defaultOptions = {
            breakpoint: 768, // md breakpoint
            cardComponent: null, // Custom card component function
            ...options
        };

        // Check if already initialized
        if (table.dataset.mobileTableInitialized === 'true') {
            return;
        }

        table.dataset.mobileTableInitialized = 'true';
        table.dataset.mobileTableBreakpoint = defaultOptions.breakpoint;
        table.dataset.mobileTableCardComponent = defaultOptions.cardComponent || '';

        // Create mobile view container
        const mobileContainer = document.createElement('div');
        mobileContainer.className = 'mobile-table-view hidden md:hidden';
        mobileContainer.id = `${tableId}_mobile`;
        table.parentElement.insertBefore(mobileContainer, table);

        // Handle resize
        this.handleResize(table, mobileContainer, defaultOptions);

        // Initial conversion
        if (window.innerWidth < defaultOptions.breakpoint) {
            this.convertToCards(table, mobileContainer, defaultOptions);
        }

        // Listen for resize events
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                this.handleResize(table, mobileContainer, defaultOptions);
            }, 250);
        });
    },

    /**
     * Handle window resize
     * @param {HTMLElement} table - Table element
     * @param {HTMLElement} mobileContainer - Mobile container
     * @param {object} options - Options
     */
    handleResize(table, mobileContainer, options) {
        const breakpoint = parseInt(table.dataset.mobileTableBreakpoint) || options.breakpoint;
        
        if (window.innerWidth < breakpoint) {
            table.classList.add('hidden');
            mobileContainer.classList.remove('hidden');
            if (mobileContainer.children.length === 0) {
                this.convertToCards(table, mobileContainer, options);
            }
        } else {
            table.classList.remove('hidden');
            mobileContainer.classList.add('hidden');
        }
    },

    /**
     * Convert table rows to cards
     * @param {HTMLElement} table - Table element
     * @param {HTMLElement} container - Container for cards
     * @param {object} options - Options
     */
    convertToCards(table, container, options) {
        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        const headers = Array.from(table.querySelectorAll('thead th'));
        const rows = tbody.querySelectorAll('tr');

        container.innerHTML = '';

        rows.forEach(row => {
            if (row.style.display === 'none') return; // Skip filtered rows

            const cells = row.querySelectorAll('td');
            const card = document.createElement('div');
            card.className = 'bg-white border border-gray-200 rounded-lg p-4 shadow-sm mb-4';

            // Extract data from row
            const rowData = {};
            headers.forEach((header, index) => {
                const key = header.dataset.key || header.textContent.trim().toLowerCase().replace(/\s+/g, '_');
                rowData[key] = cells[index] ? cells[index].textContent.trim() : '';
            });

            // Build card HTML
            let cardHTML = '<div class="space-y-2">';
            headers.forEach((header, index) => {
                const key = header.dataset.key || header.textContent.trim().toLowerCase().replace(/\s+/g, '_');
                const value = rowData[key];
                const cell = cells[index];

                if (cell && value) {
                    cardHTML += `
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-gray-500">${header.textContent.trim()}:</span>
                            <span class="text-sm text-gray-900 text-right flex-1 ml-4">${cell.innerHTML}</span>
                        </div>
                    `;
                }
            });
            cardHTML += '</div>';

            // Add actions if present
            const actions = row.querySelector('[data-actions]');
            if (actions) {
                cardHTML += `<div class="mt-4 pt-4 border-t border-gray-100">${actions.innerHTML}</div>`;
            }

            card.innerHTML = cardHTML;
            container.appendChild(card);
        });
    },

    /**
     * Refresh mobile view (call after table updates)
     * @param {string} tableId - Table ID
     */
    refresh(tableId) {
        const table = document.getElementById(tableId);
        if (!table) return;

        const mobileContainer = document.getElementById(`${tableId}_mobile`);
        if (!mobileContainer) return;

        const breakpoint = parseInt(table.dataset.mobileTableBreakpoint) || 768;
        if (window.innerWidth < breakpoint) {
            const options = {
                breakpoint: breakpoint,
                cardComponent: table.dataset.mobileTableCardComponent
            };
            this.convertToCards(table, mobileContainer, options);
        }
    }
};

// Auto-initialize tables with data-mobile-table attribute
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('table[data-mobile-table="true"]').forEach(table => {
        const tableId = table.id || `table_${Math.random().toString(36).substr(2, 9)}`;
        if (!table.id) {
            table.id = tableId;
        }
        MobileTable.init(tableId);
    });
});

