// Global SweetAlert Configuration
// All alerts will appear at top right and auto-dismiss after 3 seconds

window.showAlert = function(options) {
    const defaultOptions = {
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        toast: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    };

    return Swal.fire({ ...defaultOptions, ...options });
};

// Helper functions for common alert types
window.showSuccess = function(message, title = 'Success') {
    return showAlert({
        icon: 'success',
        title: title,
        text: message,
    });
};

window.showError = function(message, title = 'Error') {
    return showAlert({
        icon: 'error',
        title: title,
        text: message,
    });
};

window.showInfo = function(message, title = 'Info') {
    return showAlert({
        icon: 'info',
        title: title,
        text: message,
    });
};

window.showWarning = function(message, title = 'Warning') {
    return showAlert({
        icon: 'warning',
        title: title,
        text: message,
    });
};

