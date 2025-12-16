/**
 * Animation Utilities
 * Helper functions for animations and micro-interactions
 */

window.Animations = {
    /**
     * Fade in element
     * @param {HTMLElement} element - Element to animate
     * @param {number} duration - Duration in ms
     */
    fadeIn(element, duration = 200) {
        element.style.opacity = '0';
        element.style.display = 'block';
        
        let start = null;
        const animate = (timestamp) => {
            if (!start) start = timestamp;
            const progress = (timestamp - start) / duration;
            
            if (progress < 1) {
                element.style.opacity = progress;
                requestAnimationFrame(animate);
            } else {
                element.style.opacity = '1';
            }
        };
        
        requestAnimationFrame(animate);
    },

    /**
     * Fade out element
     * @param {HTMLElement} element - Element to animate
     * @param {number} duration - Duration in ms
     */
    fadeOut(element, duration = 200) {
        let start = null;
        const startOpacity = parseFloat(getComputedStyle(element).opacity);
        
        const animate = (timestamp) => {
            if (!start) start = timestamp;
            const progress = (timestamp - start) / duration;
            
            if (progress < 1) {
                element.style.opacity = startOpacity * (1 - progress);
                requestAnimationFrame(animate);
            } else {
                element.style.opacity = '0';
                element.style.display = 'none';
            }
        };
        
        requestAnimationFrame(animate);
    },

    /**
     * Slide in element
     * @param {HTMLElement} element - Element to animate
     * @param {string} direction - Direction (up, down, left, right)
     * @param {number} duration - Duration in ms
     */
    slideIn(element, direction = 'up', duration = 300) {
        const directions = {
            up: { from: 'translateY(20px)', to: 'translateY(0)' },
            down: { from: 'translateY(-20px)', to: 'translateY(0)' },
            left: { from: 'translateX(-20px)', to: 'translateX(0)' },
            right: { from: 'translateX(20px)', to: 'translateX(0)' }
        };
        
        const dir = directions[direction] || directions.up;
        element.style.transform = dir.from;
        element.style.opacity = '0';
        element.style.display = 'block';
        
        let start = null;
        const animate = (timestamp) => {
            if (!start) start = timestamp;
            const progress = Math.min((timestamp - start) / duration, 1);
            
            element.style.transform = `translate(${
                dir.to.includes('X') ? (1 - progress) * -20 : 0
            }px, ${
                dir.to.includes('Y') ? (1 - progress) * 20 : 0
            }px)`;
            element.style.opacity = progress;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                element.style.transform = dir.to;
                element.style.opacity = '1';
            }
        };
        
        requestAnimationFrame(animate);
    },

    /**
     * Show success checkmark
     * @param {HTMLElement} element - Element to show checkmark on
     */
    showSuccess(element) {
        const checkmark = document.createElement('div');
        checkmark.className = 'checkmark-animation';
        checkmark.innerHTML = '<i class="bx bx-check-circle text-green-600 text-2xl"></i>';
        checkmark.style.position = 'absolute';
        checkmark.style.top = '50%';
        checkmark.style.left = '50%';
        checkmark.style.transform = 'translate(-50%, -50%)';
        
        element.style.position = 'relative';
        element.appendChild(checkmark);
        
        setTimeout(() => {
            checkmark.remove();
        }, 1000);
    },

    /**
     * Stagger animation for list items
     * @param {NodeList|Array} items - List of elements
     * @param {number} delay - Delay between items in ms
     */
    stagger(items, delay = 50) {
        Array.from(items).forEach((item, index) => {
            setTimeout(() => {
                item.classList.add('fade-in');
            }, index * delay);
        });
    }
};

// Respect reduced motion preference
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    // Disable animations
    Animations.fadeIn = (element) => { element.style.display = 'block'; };
    Animations.fadeOut = (element) => { element.style.display = 'none'; };
    Animations.slideIn = (element) => { element.style.display = 'block'; };
}

