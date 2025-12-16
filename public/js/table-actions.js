/**
 * Table Actions - Row Selection, Bulk Actions, Export
 */

window.TableActions = {
    /**
     * Initialize table actions
     * @param {string} tableId - Table ID
     * @param {object} options - Options
     */
    init(tableId, options = {}) {
        const table = document.getElementById(tableId);
        if (!table) return;

        const defaultOptions = {
            selectable: true,
            bulkActions: true,
            exportEnabled: true,
            exportFormats: ['csv', 'pdf'],
            ...options
        };

        table.dataset.tableSelectable = defaultOptions.selectable;
        table.dataset.tableBulkActions = defaultOptions.bulkActions;
        table.dataset.tableExportEnabled = defaultOptions.exportEnabled;

        if (defaultOptions.selectable) {
            this.initRowSelection(table);
        }

        if (defaultOptions.bulkActions) {
            this.initBulkActions(table);
        }

        if (defaultOptions.exportEnabled) {
            this.initExport(table, defaultOptions.exportFormats);
        }
    },

    /**
     * Initialize row selection
     * @param {HTMLElement} table - Table element
     */
    initRowSelection(table) {
        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        // Add select all checkbox to header
        const thead = table.querySelector('thead');
        if (thead) {
            const firstRow = thead.querySelector('tr');
            if (firstRow && !firstRow.querySelector('th input[type="checkbox"]')) {
                const selectAllTh = document.createElement('th');
                selectAllTh.className = 'px-4 py-3 w-12';
                selectAllTh.innerHTML = `
                    <input type="checkbox" 
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                           onchange="TableActions.toggleSelectAll(this)">
                `;
                firstRow.insertBefore(selectAllTh, firstRow.firstChild);
            }
        }

        // Add checkbox to each row
        tbody.querySelectorAll('tr').forEach((row, index) => {
            if (!row.querySelector('td input[type="checkbox"]')) {
                const checkboxTd = document.createElement('td');
                checkboxTd.className = 'px-4 py-3 w-12';
                checkboxTd.innerHTML = `
                    <input type="checkbox" 
                           class="row-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                           value="${row.dataset.id || index}"
                           onchange="TableActions.updateBulkActions()">
                `;
                row.insertBefore(checkboxTd, row.firstChild);
            }
        });
    },

    /**
     * Toggle select all
     * @param {HTMLElement} checkbox - Select all checkbox
     */
    toggleSelectAll(checkbox) {
        const table = checkbox.closest('table');
        const checkboxes = table.querySelectorAll('tbody .row-checkbox');
        
        checkboxes.forEach(cb => {
            cb.checked = checkbox.checked;
        });

        this.updateBulkActions();
    },

    /**
     * Get selected rows
     * @param {HTMLElement} table - Table element
     * @returns {Array} - Array of selected row IDs
     */
    getSelectedRows(table) {
        const checkboxes = table.querySelectorAll('tbody .row-checkbox:checked');
        return Array.from(checkboxes).map(cb => cb.value);
    },

    /**
     * Update bulk actions visibility
     */
    updateBulkActions() {
        const tables = document.querySelectorAll('table[data-table-selectable="true"]');
        
        tables.forEach(table => {
            const selectedCount = this.getSelectedRows(table).length;
            const bulkActionsContainer = table.parentElement.querySelector('.bulk-actions-container');
            
            if (bulkActionsContainer) {
                if (selectedCount > 0) {
                    bulkActionsContainer.classList.remove('hidden');
                    bulkActionsContainer.querySelector('.selected-count').textContent = selectedCount;
                } else {
                    bulkActionsContainer.classList.add('hidden');
                }
            }
        });
    },

    /**
     * Initialize bulk actions
     * @param {HTMLElement} table - Table element
     */
    initBulkActions(table) {
        // Create bulk actions container if not exists
        let container = table.parentElement.querySelector('.bulk-actions-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'bulk-actions-container hidden mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg';
            container.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-blue-900">
                        <span class="selected-count">0</span> item(s) selected
                    </span>
                    <div class="flex gap-2">
                        <button onclick="TableActions.bulkDelete()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">
                            Delete Selected
                        </button>
                        <button onclick="TableActions.clearSelection()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">
                            Clear
                        </button>
                    </div>
                </div>
            `;
            table.parentElement.insertBefore(container, table);
        }
    },

    /**
     * Clear selection
     */
    clearSelection() {
        document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
            cb.checked = false;
        });
        document.querySelectorAll('thead input[type="checkbox"]').forEach(cb => {
            cb.checked = false;
        });
        this.updateBulkActions();
    },

    /**
     * Bulk delete action
     */
    bulkDelete() {
        const tables = document.querySelectorAll('table[data-table-selectable="true"]');
        
        tables.forEach(table => {
            const selectedIds = this.getSelectedRows(table);
            if (selectedIds.length > 0) {
                Swal.fire({
                    title: 'Delete Selected Items?',
                    text: `Are you sure you want to delete ${selectedIds.length} item(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete them'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Dispatch custom event for handling deletion
                        table.dispatchEvent(new CustomEvent('table:bulk-delete', {
                            detail: { ids: selectedIds }
                        }));
                    }
                });
            }
        });
    },

    /**
     * Initialize export functionality
     * @param {HTMLElement} table - Table element
     * @param {Array} formats - Export formats
     */
    initExport(table, formats) {
        // Create export button if not exists
        const container = table.parentElement;
        let exportButton = container.querySelector('.table-export-button');
        
        if (!exportButton && formats.length > 0) {
            exportButton = document.createElement('button');
            exportButton.className = 'table-export-button mb-4 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm flex items-center gap-2';
            exportButton.innerHTML = '<i class="bx bx-download"></i> Export';
            
            if (formats.length === 1) {
                exportButton.onclick = () => this.exportTable(table, formats[0]);
            } else {
                // Create dropdown
                exportButton.onclick = () => this.showExportMenu(table, formats);
            }
            
            container.insertBefore(exportButton, table);
        }
    },

    /**
     * Export table
     * @param {HTMLElement} table - Table element
     * @param {string} format - Export format (csv, pdf)
     */
    exportTable(table, format) {
        if (format === 'csv') {
            this.exportToCSV(table);
        } else if (format === 'pdf') {
            this.exportToPDF(table);
        }
    },

    /**
     * Export to CSV
     * @param {HTMLElement} table - Table element
     */
    exportToCSV(table) {
        const headers = Array.from(table.querySelectorAll('thead th'))
            .map(th => th.textContent.trim())
            .filter(text => text && !th.querySelector('input[type="checkbox"]'));
        
        const rows = Array.from(table.querySelectorAll('tbody tr'))
            .filter(tr => tr.style.display !== 'none')
            .map(tr => {
                return Array.from(tr.querySelectorAll('td'))
                    .filter(td => !td.querySelector('input[type="checkbox"]'))
                    .map(td => {
                        const text = td.textContent.trim().replace(/"/g, '""');
                        return `"${text}"`;
                    });
            });

        const csv = [
            headers.map(h => `"${h}"`).join(','),
            ...rows.map(r => r.join(','))
        ].join('\n');

        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `table_export_${new Date().getTime()}.csv`;
        a.click();
        window.URL.revokeObjectURL(url);
    },

    /**
     * Export to PDF (requires external library)
     * @param {HTMLElement} table - Table element
     */
    exportToPDF(table) {
        // This would require a PDF library like jsPDF
        Swal.fire({
            icon: 'info',
            title: 'PDF Export',
            text: 'PDF export requires additional library setup. Please use CSV export for now.'
        });
    },

    /**
     * Show export menu
     * @param {HTMLElement} table - Table element
     * @param {Array} formats - Available formats
     */
    showExportMenu(table, formats) {
        const options = formats.map(f => ({
            text: f.toUpperCase(),
            value: f
        }));

        Swal.fire({
            title: 'Export Format',
            input: 'select',
            inputOptions: Object.fromEntries(options.map(o => [o.value, o.text])),
            showCancelButton: true,
            confirmButtonText: 'Export',
            inputValidator: (value) => {
                if (!value) {
                    return 'Please select a format';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                this.exportTable(table, result.value);
            }
        });
    }
};

// Auto-initialize tables with data-table-actions attribute
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('table[data-table-actions="true"]').forEach(table => {
        TableActions.init(table.id || `table_${Math.random().toString(36).substr(2, 9)}`);
    });
});

