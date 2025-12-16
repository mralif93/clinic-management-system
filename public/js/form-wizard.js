/**
 * Form Wizard Management
 * Handles multi-step form navigation and state persistence
 */

window.FormWizard = {
    /**
     * Initialize wizard
     * @param {string} wizardId - Wizard container ID
     * @param {object} options - Options
     */
    init(wizardId, options = {}) {
        const wizard = document.getElementById(wizardId);
        if (!wizard) return;

        const defaultOptions = {
            persistState: true,
            validateOnStepChange: true,
            ...options
        };

        wizard.dataset.wizardPersistState = defaultOptions.persistState;
        wizard.dataset.wizardValidateOnChange = defaultOptions.validateOnStepChange;

        // Load saved state
        if (defaultOptions.persistState) {
            const savedState = localStorage.getItem(`wizard_state_${wizardId}`);
            if (savedState) {
                try {
                    const state = JSON.parse(savedState);
                    this.restoreState(wizard, state);
                } catch (e) {
                    console.error('Failed to restore wizard state:', e);
                }
            }
        }

        // Save state on input changes
        if (defaultOptions.persistState) {
            wizard.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('change', () => {
                    this.saveState(wizard);
                });
            });
        }
    },

    /**
     * Save wizard state
     * @param {HTMLElement} wizard - Wizard element
     */
    saveState(wizard) {
        const wizardId = wizard.id;
        const currentStep = wizard.querySelector('[x-data]')?.__x?.$data?.currentStep || 1;
        
        const formData = {};
        wizard.querySelectorAll('input, select, textarea').forEach(field => {
            if (field.name) {
                formData[field.name] = field.value;
            }
        });

        const state = {
            currentStep,
            formData
        };

        localStorage.setItem(`wizard_state_${wizardId}`, JSON.stringify(state));
    },

    /**
     * Restore wizard state
     * @param {HTMLElement} wizard - Wizard element
     * @param {object} state - Saved state
     */
    restoreState(wizard, state) {
        // Restore form data
        if (state.formData) {
            Object.entries(state.formData).forEach(([name, value]) => {
                const field = wizard.querySelector(`[name="${name}"]`);
                if (field) {
                    field.value = value;
                }
            });
        }

        // Restore step (if Alpine.js is available)
        if (state.currentStep && window.Alpine) {
            const alpineData = wizard.querySelector('[x-data]')?.__x?.$data;
            if (alpineData) {
                alpineData.currentStep = state.currentStep;
            }
        }
    },

    /**
     * Clear wizard state
     * @param {string} wizardId - Wizard ID
     */
    clearState(wizardId) {
        localStorage.removeItem(`wizard_state_${wizardId}`);
    },

    /**
     * Validate current step
     * @param {HTMLElement} wizard - Wizard element
     * @param {number} stepNumber - Step number
     * @returns {boolean} - Is valid
     */
    validateStep(wizard, stepNumber) {
        const step = wizard.querySelector(`[x-show="currentStep === ${stepNumber}"]`);
        if (!step) return true;

        const requiredFields = step.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
                
                // Show error message
                if (!field.nextElementSibling?.classList.contains('error-message')) {
                    const error = document.createElement('p');
                    error.className = 'error-message mt-1 text-sm text-red-600';
                    error.textContent = 'This field is required';
                    field.parentElement.appendChild(error);
                }
            } else {
                field.classList.remove('border-red-500');
                const error = field.parentElement.querySelector('.error-message');
                if (error) {
                    error.remove();
                }
            }
        });

        return isValid;
    }
};

