/**
 * User Onboarding & Tours
 */

window.Onboarding = {
    tours: new Map(),

    /**
     * Initialize tour
     * @param {string} tourId - Tour ID
     * @param {Array} steps - Tour steps
     * @param {object} options - Options
     */
    init(tourId, steps, options = {}) {
        const defaultOptions = {
            autoStart: false,
            showProgress: true,
            highlightElements: true,
            ...options
        };

        this.tours.set(tourId, {
            steps,
            currentStep: 0,
            options: defaultOptions
        });
    },

    /**
     * Start tour
     * @param {string} tourId - Tour ID
     */
    start(tourId) {
        const tour = this.tours.get(tourId);
        if (!tour) return;

        tour.currentStep = 0;
        this.showStep(tourId, 0);
    },

    /**
     * Show tour step
     * @param {string} tourId - Tour ID
     * @param {number} stepIndex - Step index
     */
    showStep(tourId, stepIndex) {
        const tour = this.tours.get(tourId);
        if (!tour) return;

        const step = tour.steps[stepIndex];
        if (!step) return;

        // Highlight element if specified
        if (step.element && tour.options.highlightElements) {
            this.highlightElement(step.element);
        }

        // Show tooltip/overlay
        this.showTooltip(step);
    },

    /**
     * Highlight element
     * @param {string} selector - Element selector
     */
    highlightElement(selector) {
        const element = document.querySelector(selector);
        if (!element) return;

        // Remove previous highlights
        document.querySelectorAll('.tour-highlight').forEach(el => {
            el.classList.remove('tour-highlight');
        });

        // Add highlight
        element.classList.add('tour-highlight');
        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
    },

    /**
     * Show tooltip
     * @param {object} step - Step configuration
     */
    showTooltip(step) {
        // Tooltip display is handled by the Blade component
        // This function can be extended for custom tooltip logic
    },

    /**
     * Complete tour
     * @param {string} tourId - Tour ID
     */
    complete(tourId) {
        // Mark tour as completed
        localStorage.setItem(`tour_completed_${tourId}`, 'true');
        
        // Remove highlights
        document.querySelectorAll('.tour-highlight').forEach(el => {
            el.classList.remove('tour-highlight');
        });
    },

    /**
     * Check if tour was completed
     * @param {string} tourId - Tour ID
     * @returns {boolean}
     */
    isCompleted(tourId) {
        return localStorage.getItem(`tour_completed_${tourId}`) === 'true';
    }
};

// Auto-start tours if not completed
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-tour]').forEach(element => {
        const tourId = element.dataset.tour;
        if (!Onboarding.isCompleted(tourId)) {
            // Tour will be initialized by the component
        }
    });
});

