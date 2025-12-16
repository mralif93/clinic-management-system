/**
 * Touch Interactions
 * Swipe gestures, long-press, pull-to-refresh
 */

window.TouchInteractions = {
    /**
     * Initialize swipe gestures
     * @param {HTMLElement} element - Element to add swipe to
     * @param {object} callbacks - Callback functions
     */
    initSwipe(element, callbacks = {}) {
        let startX = 0;
        let startY = 0;
        let distX = 0;
        let distY = 0;
        let threshold = 50; // Minimum distance for swipe
        let restraint = 100; // Maximum perpendicular distance
        let allowedTime = 300; // Maximum time for swipe
        let startTime = 0;

        element.addEventListener('touchstart', (e) => {
            const touch = e.touches[0];
            startX = touch.clientX;
            startY = touch.clientY;
            startTime = new Date().getTime();
        });

        element.addEventListener('touchend', (e) => {
            const touch = e.changedTouches[0];
            distX = touch.clientX - startX;
            distY = touch.clientY - startY;
            const elapsedTime = new Date().getTime() - startTime;

            if (elapsedTime <= allowedTime) {
                if (Math.abs(distX) >= threshold && Math.abs(distY) <= restraint) {
                    // Horizontal swipe
                    if (distX > 0 && callbacks.onSwipeRight) {
                        callbacks.onSwipeRight(e);
                    } else if (distX < 0 && callbacks.onSwipeLeft) {
                        callbacks.onSwipeLeft(e);
                    }
                } else if (Math.abs(distY) >= threshold && Math.abs(distX) <= restraint) {
                    // Vertical swipe
                    if (distY > 0 && callbacks.onSwipeDown) {
                        callbacks.onSwipeDown(e);
                    } else if (distY < 0 && callbacks.onSwipeUp) {
                        callbacks.onSwipeUp(e);
                    }
                }
            }
        });
    },

    /**
     * Initialize long-press
     * @param {HTMLElement} element - Element
     * @param {Function} callback - Callback function
     * @param {number} duration - Long-press duration in ms
     */
    initLongPress(element, callback, duration = 500) {
        let pressTimer = null;

        element.addEventListener('touchstart', (e) => {
            pressTimer = setTimeout(() => {
                callback(e);
            }, duration);
        });

        element.addEventListener('touchend', () => {
            clearTimeout(pressTimer);
        });

        element.addEventListener('touchmove', () => {
            clearTimeout(pressTimer);
        });
    },

    /**
     * Initialize pull-to-refresh
     * @param {HTMLElement} container - Container element
     * @param {Function} callback - Refresh callback
     */
    initPullToRefresh(container, callback) {
        let startY = 0;
        let currentY = 0;
        let pulling = false;
        let threshold = 80;

        // Create indicator
        const indicator = document.createElement('div');
        indicator.className = 'pull-to-refresh-indicator';
        indicator.innerHTML = '<i class="bx bx-refresh"></i> <span>Pull to refresh</span>';
        container.insertBefore(indicator, container.firstChild);

        container.addEventListener('touchstart', (e) => {
            if (window.scrollY === 0) {
                startY = e.touches[0].clientY;
                pulling = true;
            }
        });

        container.addEventListener('touchmove', (e) => {
            if (!pulling) return;

            currentY = e.touches[0].clientY;
            const pullDistance = currentY - startY;

            if (pullDistance > 0 && window.scrollY === 0) {
                e.preventDefault();
                const progress = Math.min(pullDistance / threshold, 1);
                
                if (progress >= 1) {
                    indicator.classList.add('active');
                    indicator.innerHTML = '<i class="bx bx-refresh bx-spin"></i> <span>Release to refresh</span>';
                } else {
                    indicator.classList.remove('active');
                    indicator.innerHTML = '<i class="bx bx-refresh"></i> <span>Pull to refresh</span>';
                }
            }
        });

        container.addEventListener('touchend', (e) => {
            if (!pulling) return;

            const pullDistance = currentY - startY;
            pulling = false;

            if (pullDistance >= threshold) {
                indicator.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> <span>Refreshing...</span>';
                callback().then(() => {
                    indicator.classList.remove('active');
                    indicator.innerHTML = '<i class="bx bx-refresh"></i> <span>Pull to refresh</span>';
                });
            } else {
                indicator.classList.remove('active');
            }
        });
    }
};

// Auto-initialize swipeable elements
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-swipeable="true"]').forEach(element => {
        const callbacks = {
            onSwipeLeft: () => {
                const action = element.dataset.swipeAction;
                if (action) {
                    eval(action); // Execute action (should be a function call)
                }
            }
        };
        TouchInteractions.initSwipe(element, callbacks);
    });

    // Initialize long-press
    document.querySelectorAll('[data-long-press]').forEach(element => {
        const callback = element.dataset.longPressCallback;
        if (callback) {
            TouchInteractions.initLongPress(element, () => {
                eval(callback);
            });
        }
    });
});

