/**
 * Lazy Loading for Images
 */

window.LazyLoad = {
    /**
     * Initialize lazy loading
     * @param {object} options - Options
     */
    init(options = {}) {
        const defaultOptions = {
            rootMargin: '50px',
            threshold: 0.01,
            ...options
        };

        // Use Intersection Observer if available
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadImage(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, defaultOptions);

            // Observe all lazy images
            document.querySelectorAll('img[data-src], img[loading="lazy"]').forEach(img => {
                observer.observe(img);
            });
        } else {
            // Fallback: load all images immediately
            document.querySelectorAll('img[data-src]').forEach(img => {
                this.loadImage(img);
            });
        }
    },

    /**
     * Load image
     * @param {HTMLElement} img - Image element
     */
    loadImage(img) {
        const src = img.dataset.src || img.src;
        if (!src) return;

        const image = new Image();
        image.onload = () => {
            img.src = src;
            img.classList.add('loaded');
            img.removeAttribute('data-src');
        };
        image.onerror = () => {
            img.classList.add('error');
            img.alt = 'Failed to load image';
        };
        image.src = src;
    }
};

// Auto-initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    LazyLoad.init();
});

