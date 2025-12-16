/**
 * Dashboard Customization
 * Drag-and-drop widget reordering and visibility toggles
 */

window.DashboardCustomize = {
    /**
     * Initialize dashboard customization
     * @param {string} containerId - Dashboard container ID
     * @param {object} options - Options
     */
    init(containerId, options = {}) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const defaultOptions = {
            savePreferences: true,
            ...options
        };

        // Load saved widget order
        if (defaultOptions.savePreferences) {
            this.loadWidgetPreferences(container);
        }

        // Initialize drag and drop if SortableJS is available
        if (typeof Sortable !== 'undefined') {
            this.initDragAndDrop(container);
        }

        // Initialize visibility toggles
        this.initVisibilityToggles(container);
    },

    /**
     * Initialize drag and drop
     * @param {HTMLElement} container - Container element
     */
    initDragAndDrop(container) {
        const widgetContainer = container.querySelector('.dashboard-widgets');
        if (!widgetContainer) return;

        new Sortable(widgetContainer, {
            animation: 150,
            handle: '.widget-drag-handle',
            ghostClass: 'opacity-50',
            onEnd: () => {
                this.saveWidgetOrder(container);
            }
        });
    },

    /**
     * Initialize visibility toggles
     * @param {HTMLElement} container - Container element
     */
    initVisibilityToggles(container) {
        const widgets = container.querySelectorAll('[data-widget-id]');
        widgets.forEach(widget => {
            const toggle = widget.querySelector('.widget-visibility-toggle');
            if (toggle) {
                toggle.addEventListener('click', () => {
                    widget.style.display = widget.style.display === 'none' ? '' : 'none';
                    this.saveWidgetPreferences(container);
                });
            }
        });
    },

    /**
     * Save widget order
     * @param {HTMLElement} container - Container element
     */
    saveWidgetOrder(container) {
        const widgets = Array.from(container.querySelectorAll('[data-widget-id]'));
        const order = widgets.map(w => w.dataset.widgetId);
        
        localStorage.setItem('dashboard_widget_order', JSON.stringify(order));
    },

    /**
     * Load widget preferences
     * @param {HTMLElement} container - Container element
     */
    loadWidgetPreferences(container) {
        // Load order
        const savedOrder = localStorage.getItem('dashboard_widget_order');
        if (savedOrder) {
            try {
                const order = JSON.parse(savedOrder);
                const widgets = Array.from(container.querySelectorAll('[data-widget-id]'));
                const widgetMap = new Map(widgets.map(w => [w.dataset.widgetId, w]));
                
                order.forEach(widgetId => {
                    const widget = widgetMap.get(widgetId);
                    if (widget && widget.parentElement) {
                        widget.parentElement.appendChild(widget);
                    }
                });
            } catch (e) {
                console.error('Failed to load widget order:', e);
            }
        }

        // Load visibility
        const savedVisibility = localStorage.getItem('dashboard_widgets');
        if (savedVisibility) {
            try {
                const visibleWidgets = JSON.parse(savedVisibility);
                const widgets = container.querySelectorAll('[data-widget-id]');
                
                widgets.forEach(widget => {
                    if (!visibleWidgets.includes(widget.dataset.widgetId)) {
                        widget.style.display = 'none';
                    }
                });
            } catch (e) {
                console.error('Failed to load widget visibility:', e);
            }
        }
    },

    /**
     * Save widget preferences
     * @param {HTMLElement} container - Container element
     */
    saveWidgetPreferences(container) {
        const widgets = Array.from(container.querySelectorAll('[data-widget-id]'));
        const visibleWidgets = widgets
            .filter(w => w.style.display !== 'none')
            .map(w => w.dataset.widgetId);
        
        localStorage.setItem('dashboard_widgets', JSON.stringify(visibleWidgets));
        this.saveWidgetOrder(container);
    },

    /**
     * Reset dashboard to default
     * @param {string} containerId - Container ID
     */
    reset(containerId) {
        localStorage.removeItem('dashboard_widget_order');
        localStorage.removeItem('dashboard_widgets');
        location.reload();
    }
};

// Auto-initialize dashboards with data-customizable attribute
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-customizable="true"]').forEach(container => {
        DashboardCustomize.init(container.id || `dashboard_${Math.random().toString(36).substr(2, 9)}`);
    });
});

