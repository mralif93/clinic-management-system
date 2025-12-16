/**
 * Skeleton Loader Management
 * Handles skeleton loading states and transitions
 */

window.SkeletonLoader = {
    /**
     * Show skeleton loader
     * @param {string} containerId - ID of container to show skeleton in
     * @param {string} type - Type of skeleton (table, card, list)
     * @param {object} options - Additional options
     */
    show(containerId, type = 'table', options = {}) {
        const container = document.getElementById(containerId);
        if (!container) return;

        // Store original content
        if (!container.dataset.originalContent) {
            container.dataset.originalContent = container.innerHTML;
        }

        // Show skeleton based on type
        let skeletonHTML = '';
        switch (type) {
            case 'table':
                skeletonHTML = this.getTableSkeleton(options.rows || 5, options.columns || 4);
                break;
            case 'card':
                skeletonHTML = this.getCardSkeleton(options.showImage, options.showActions);
                break;
            case 'list':
                skeletonHTML = this.getListSkeleton(options.items || 5, options.showAvatar, options.showActions);
                break;
            default:
                skeletonHTML = this.getTextSkeleton();
        }

        container.innerHTML = skeletonHTML;
        container.classList.add('skeleton-loading');
    },

    /**
     * Hide skeleton loader and restore content
     * @param {string} containerId - ID of container
     */
    hide(containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;

        if (container.dataset.originalContent) {
            container.innerHTML = container.dataset.originalContent;
            delete container.dataset.originalContent;
        }

        container.classList.remove('skeleton-loading');
    },

    /**
     * Get table skeleton HTML
     */
    getTableSkeleton(rows, columns) {
        let html = '<div class="w-full"><div class="bg-gray-50 border-b border-gray-200"><div class="grid gap-4 px-4 py-3" style="grid-template-columns: repeat(' + columns + ', 1fr);">';
        for (let i = 0; i < columns; i++) {
            html += '<div class="animate-pulse bg-gray-200 rounded h-3 w-3/5"></div>';
        }
        html += '</div></div><div class="divide-y divide-gray-100">';
        for (let row = 0; row < rows; row++) {
            html += '<div class="grid gap-4 px-4 py-4" style="grid-template-columns: repeat(' + columns + ', 1fr);">';
            for (let col = 0; col < columns; col++) {
                html += '<div class="animate-pulse bg-gray-200 rounded h-4 ' + (col === 0 ? 'w-4/5' : 'w-3/5') + '"></div>';
            }
            html += '</div>';
        }
        html += '</div></div>';
        return html;
    },

    /**
     * Get card skeleton HTML
     */
    getCardSkeleton(showImage = false, showActions = false) {
        let html = '<div class="bg-white border border-gray-100 rounded-lg shadow-sm p-6">';
        if (showImage) {
            html += '<div class="animate-pulse bg-gray-200 rounded h-48 mb-4"></div>';
        }
        html += '<div class="animate-pulse bg-gray-200 rounded h-5 w-3/4 mb-3"></div>';
        html += '<div class="animate-pulse bg-gray-200 rounded h-4 w-full mb-2"></div>';
        html += '<div class="animate-pulse bg-gray-200 rounded h-4 w-11/12 mb-2"></div>';
        html += '<div class="animate-pulse bg-gray-200 rounded h-4 w-3/5 mb-4"></div>';
        if (showActions) {
            html += '<div class="flex gap-2 mt-4"><div class="animate-pulse bg-gray-200 rounded h-8 w-24"></div><div class="animate-pulse bg-gray-200 rounded h-8 w-24"></div></div>';
        }
        html += '</div>';
        return html;
    },

    /**
     * Get list skeleton HTML
     */
    getListSkeleton(items = 5, showAvatar = false, showActions = false) {
        let html = '<div class="space-y-3">';
        for (let i = 0; i < items; i++) {
            html += '<div class="flex items-center gap-4 p-3 bg-white border border-gray-100 rounded-lg">';
            if (showAvatar) {
                html += '<div class="animate-pulse bg-gray-200 rounded-full w-12 h-12"></div>';
            }
            html += '<div class="flex-1 space-y-2"><div class="animate-pulse bg-gray-200 rounded h-4 ' + (i % 2 === 0 ? 'w-3/4' : 'w-5/6') + '"></div><div class="animate-pulse bg-gray-200 rounded h-3 w-1/2"></div></div>';
            if (showActions) {
                html += '<div class="animate-pulse bg-gray-200 rounded w-8 h-8"></div>';
            }
            html += '</div>';
        }
        html += '</div>';
        return html;
    },

    /**
     * Get text skeleton HTML
     */
    getTextSkeleton() {
        return '<div class="animate-pulse bg-gray-200 rounded h-4 w-full"></div>';
    }
};

// Alpine.js directive for skeleton loading
document.addEventListener('alpine:init', () => {
    Alpine.directive('skeleton', (el, { expression }, { evaluate }) => {
        const isLoading = evaluate(expression);
        if (isLoading) {
            el.classList.add('skeleton-loading');
        } else {
            el.classList.remove('skeleton-loading');
        }
    });
});

