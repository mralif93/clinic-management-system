/**
 * Image Optimization
 * Lazy loading, responsive images, format optimization
 */

window.ImageOptimizer = {
    /**
     * Initialize image optimization
     * @param {object} options - Options
     */
    init(options = {}) {
        const defaultOptions = {
            lazyLoad: true,
            responsive: true,
            webpSupport: this.supportsWebP(),
            ...options
        };

        if (defaultOptions.lazyLoad) {
            this.initLazyLoading();
        }

        if (defaultOptions.responsive) {
            this.initResponsiveImages();
        }
    },

    /**
     * Check WebP support
     * @returns {boolean}
     */
    supportsWebP() {
        const canvas = document.createElement('canvas');
        canvas.width = 1;
        canvas.height = 1;
        return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
    },

    /**
     * Initialize lazy loading
     */
    initLazyLoading() {
        // Use native lazy loading if supported
        if ('loading' in HTMLImageElement.prototype) {
            document.querySelectorAll('img[data-src]').forEach(img => {
                img.loading = 'lazy';
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            });
        } else {
            // Fallback to Intersection Observer
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                observer.observe(img);
            });
        }
    },

    /**
     * Initialize responsive images
     */
    initResponsiveImages() {
        document.querySelectorAll('img[data-responsive]').forEach(img => {
            const srcset = img.dataset.srcset;
            const sizes = img.dataset.sizes || '100vw';
            
            if (srcset) {
                img.srcset = srcset;
                img.sizes = sizes;
            }
        });
    },

    /**
     * Convert image to WebP if supported
     * @param {string} src - Image source
     * @returns {string} - Optimized source
     */
    getOptimizedSrc(src) {
        if (this.supportsWebP() && !src.includes('.webp')) {
            // In a real implementation, this would convert the image server-side
            // For now, return original src
            return src;
        }
        return src;
    }
};

// Auto-initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    ImageOptimizer.init();
});

