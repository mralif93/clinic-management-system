/**
 * Input Masks for Phone Numbers, IDs, etc.
 */

window.InputMasks = {
    /**
     * Apply phone number mask
     * @param {HTMLElement} input - Input element
     * @param {string} format - Format string (e.g., '+1 (XXX) XXX-XXXX')
     */
    phone(input, format = '+1 (XXX) XXX-XXXX') {
        input.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            let formatted = '';
            let valueIndex = 0;

            for (let i = 0; i < format.length && valueIndex < value.length; i++) {
                if (format[i] === 'X') {
                    formatted += value[valueIndex];
                    valueIndex++;
                } else {
                    formatted += format[i];
                }
            }

            e.target.value = formatted;
        });
    },

    /**
     * Apply ID mask (e.g., PAT-XXXXXX)
     * @param {HTMLElement} input - Input element
     * @param {string} prefix - Prefix (e.g., 'PAT-')
     */
    id(input, prefix = '') {
        input.addEventListener('input', (e) => {
            let value = e.target.value.replace(/[^0-9]/g, '');
            if (prefix) {
                value = prefix + value;
            }
            e.target.value = value;
        });
    },

    /**
     * Apply currency mask
     * @param {HTMLElement} input - Input element
     * @param {string} symbol - Currency symbol
     */
    currency(input, symbol = '$') {
        input.addEventListener('input', (e) => {
            let value = e.target.value.replace(/[^0-9.]/g, '');
            // Ensure only one decimal point
            const parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            // Limit to 2 decimal places
            if (parts[1] && parts[1].length > 2) {
                value = parts[0] + '.' + parts[1].substring(0, 2);
            }
            e.target.value = value ? symbol + value : '';
        });
    },

    /**
     * Apply date mask (MM/DD/YYYY)
     * @param {HTMLElement} input - Input element
     */
    date(input) {
        input.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            if (value.length >= 5) {
                value = value.substring(0, 5) + '/' + value.substring(5, 9);
            }
            e.target.value = value;
        });
    },

    /**
     * Initialize all masks on page
     */
    init() {
        // Phone number masks
        document.querySelectorAll('[data-mask="phone"]').forEach(input => {
            const format = input.dataset.maskFormat || '+1 (XXX) XXX-XXXX';
            this.phone(input, format);
        });

        // ID masks
        document.querySelectorAll('[data-mask="id"]').forEach(input => {
            const prefix = input.dataset.maskPrefix || '';
            this.id(input, prefix);
        });

        // Currency masks
        document.querySelectorAll('[data-mask="currency"]').forEach(input => {
            const symbol = input.dataset.maskSymbol || '$';
            this.currency(input, symbol);
        });

        // Date masks
        document.querySelectorAll('[data-mask="date"]').forEach(input => {
            this.date(input);
        });
    }
};

// Auto-initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    InputMasks.init();
});

