/**
 * Infinite Scroll
 * Load more content as user scrolls
 */

window.InfiniteScroll = {
    /**
     * Initialize infinite scroll
     * @param {string} containerId - Container ID
     * @param {object} options - Options
     */
    init(containerId, options = {}) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const defaultOptions = {
            threshold: 200, // Pixels from bottom to trigger load
            loading: false,
            hasMore: true,
            loadMoreUrl: null,
            loadMoreCallback: null,
            ...options
        };

        container.dataset.infiniteScrollThreshold = defaultOptions.threshold;
        container.dataset.infiniteScrollLoading = 'false';
        container.dataset.infiniteScrollHasMore = defaultOptions.hasMore ? 'true' : 'false';

        // Create loading indicator
        const loader = document.createElement('div');
        loader.className = 'infinite-scroll-loader hidden text-center py-4';
        loader.innerHTML = '<i class="bx bx-loader-alt bx-spin text-2xl text-gray-400"></i>';
        container.appendChild(loader);

        // Handle scroll
        let scrollTimer;
        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(() => {
                this.checkScroll(container, defaultOptions, loader);
            }, 100);
        });

        // Initial check
        this.checkScroll(container, defaultOptions, loader);
    },

    /**
     * Check if should load more
     * @param {HTMLElement} container - Container element
     * @param {object} options - Options
     * @param {HTMLElement} loader - Loader element
     */
    checkScroll(container, options, loader) {
        if (container.dataset.infiniteScrollLoading === 'true') return;
        if (container.dataset.infiniteScrollHasMore !== 'true') return;

        const threshold = parseInt(container.dataset.infiniteScrollThreshold) || 200;
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;

        if (scrollTop + windowHeight >= documentHeight - threshold) {
            this.loadMore(container, options, loader);
        }
    },

    /**
     * Load more content
     * @param {HTMLElement} container - Container element
     * @param {object} options - Options
     * @param {HTMLElement} loader - Loader element
     */
    async loadMore(container, options, loader) {
        container.dataset.infiniteScrollLoading = 'true';
        loader.classList.remove('hidden');

        try {
            if (options.loadMoreCallback) {
                const result = await options.loadMoreCallback();
                if (!result || !result.hasMore) {
                    container.dataset.infiniteScrollHasMore = 'false';
                    loader.innerHTML = '<p class="text-sm text-gray-500">No more items</p>';
                }
            } else if (options.loadMoreUrl) {
                const response = await fetch(options.loadMoreUrl);
                const html = await response.text();
                
                // Append new content
                const temp = document.createElement('div');
                temp.innerHTML = html;
                const newItems = temp.querySelectorAll('[data-item]');
                
                if (newItems.length === 0) {
                    container.dataset.infiniteScrollHasMore = 'false';
                    loader.innerHTML = '<p class="text-sm text-gray-500">No more items</p>';
                } else {
                    newItems.forEach(item => {
                        container.insertBefore(item, loader);
                    });
                }
            }
        } catch (error) {
            console.error('Failed to load more:', error);
            loader.innerHTML = '<p class="text-sm text-red-500">Failed to load more items</p>';
        } finally {
            container.dataset.infiniteScrollLoading = 'false';
            loader.classList.add('hidden');
        }
    }
};

// Auto-initialize containers with data-infinite-scroll attribute
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-infinite-scroll="true"]').forEach(container => {
        const url = container.dataset.infiniteScrollUrl;
        InfiniteScroll.init(container.id || `scroll_${Math.random().toString(36).substr(2, 9)}`, {
            loadMoreUrl: url
        });
    });
});

