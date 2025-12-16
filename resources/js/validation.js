/**
 * Client-Side Form Validation
 * Provides real-time validation feedback for forms
 */

window.FormValidation = {
    /**
     * Initialize validation for a form
     * @param {string|HTMLElement} formSelector - Form selector or element
     */
    init(formSelector) {
        const form = typeof formSelector === 'string' 
            ? document.querySelector(formSelector) 
            : formSelector;
        
        if (!form) return;

        // Validate on blur
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => {
                // Clear error if user starts typing
                if (input.classList.contains('border-red-500')) {
                    this.clearError(input);
                }
            });
        });

        // Validate on submit
        form.addEventListener('submit', (e) => {
            if (!this.validateForm(form)) {
                e.preventDefault();
                // Focus first invalid field
                const firstError = form.querySelector('.border-red-500');
                if (firstError) {
                    firstError.focus();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    },

    /**
     * Validate a single field
     * @param {HTMLElement} field - Input field element
     * @returns {boolean} - Is valid
     */
    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        const required = field.hasAttribute('required');
        const name = field.name;

        // Clear previous errors
        this.clearError(field);

        // Required validation
        if (required && !value) {
            this.showError(field, 'This field is required');
            return false;
        }

        // Skip other validations if empty and not required
        if (!value && !required) {
            return true;
        }

        // Email validation
        if (type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                this.showError(field, 'Please enter a valid email address');
                return false;
            }
        }

        // URL validation
        if (type === 'url' && value) {
            try {
                new URL(value);
            } catch {
                this.showError(field, 'Please enter a valid URL');
                return false;
            }
        }

        // Number validation
        if (type === 'number' && value) {
            const num = parseFloat(value);
            const min = field.getAttribute('min');
            const max = field.getAttribute('max');

            if (isNaN(num)) {
                this.showError(field, 'Please enter a valid number');
                return false;
            }

            if (min !== null && num < parseFloat(min)) {
                this.showError(field, `Value must be at least ${min}`);
                return false;
            }

            if (max !== null && num > parseFloat(max)) {
                this.showError(field, `Value must be at most ${max}`);
                return false;
            }
        }

        // Date validation
        if (type === 'date' && value) {
            const date = new Date(value);
            const min = field.getAttribute('min');
            const max = field.getAttribute('max');

            if (isNaN(date.getTime())) {
                this.showError(field, 'Please enter a valid date');
                return false;
            }

            if (min && value < min) {
                this.showError(field, `Date must be on or after ${min}`);
                return false;
            }

            if (max && value > max) {
                this.showError(field, `Date must be on or before ${max}`);
                return false;
            }
        }

        // Min length validation
        const minLength = field.getAttribute('minlength');
        if (minLength && value.length < parseInt(minLength)) {
            this.showError(field, `Must be at least ${minLength} characters`);
            return false;
        }

        // Max length validation
        const maxLength = field.getAttribute('maxlength');
        if (maxLength && value.length > parseInt(maxLength)) {
            this.showError(field, `Must be at most ${maxLength} characters`);
            return false;
        }

        // Pattern validation
        const pattern = field.getAttribute('pattern');
        if (pattern && value) {
            const regex = new RegExp(pattern);
            if (!regex.test(value)) {
                const title = field.getAttribute('title') || 'Please match the required format';
                this.showError(field, title);
                return false;
            }
        }

        // Show success indicator
        this.showSuccess(field);
        return true;
    },

    /**
     * Validate entire form
     * @param {HTMLElement} form - Form element
     * @returns {boolean} - Is valid
     */
    validateForm(form) {
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    },

    /**
     * Show error for a field
     * @param {HTMLElement} field - Input field
     * @param {string} message - Error message
     */
    showError(field, message) {
        field.classList.remove('border-gray-300', 'border-blue-500');
        field.classList.add('border-red-500', 'focus:ring-red-500');
        field.setAttribute('aria-invalid', 'true');

        // Remove existing error message
        this.clearError(field);

        // Create error message element
        const errorId = field.id + '_error';
        const errorDiv = document.createElement('p');
        errorDiv.id = errorId;
        errorDiv.className = 'mt-1 text-sm text-red-600 flex items-center gap-1';
        errorDiv.setAttribute('role', 'alert');
        errorDiv.innerHTML = `<i class='bx bx-error-circle'></i> ${message}`;

        // Insert after field or parent container
        const container = field.closest('.relative') || field.parentElement;
        container.insertAdjacentElement('afterend', errorDiv);

        // Update aria-describedby
        const describedBy = field.getAttribute('aria-describedby') || '';
        field.setAttribute('aria-describedby', describedBy ? `${describedBy} ${errorId}` : errorId);
    },

    /**
     * Clear error for a field
     * @param {HTMLElement} field - Input field
     */
    clearError(field) {
        field.classList.remove('border-red-500', 'focus:ring-red-500');
        field.classList.add('border-gray-300');
        field.setAttribute('aria-invalid', 'false');

        // Remove error message
        const errorId = field.id + '_error';
        const errorElement = document.getElementById(errorId);
        if (errorElement) {
            errorElement.remove();
        }

        // Update aria-describedby
        const describedBy = field.getAttribute('aria-describedby') || '';
        field.setAttribute('aria-describedby', describedBy.replace(errorId, '').trim());
    },

    /**
     * Show success indicator
     * @param {HTMLElement} field - Input field
     */
    showSuccess(field) {
        // Add success styling if needed
        field.classList.remove('border-red-500');
        if (!field.classList.contains('border-green-500')) {
            // Optional: add green border on success
            // field.classList.add('border-green-500');
        }
    }
};

// Auto-initialize forms with data-validate attribute
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form[data-validate]').forEach(form => {
        FormValidation.init(form);
    });
});

