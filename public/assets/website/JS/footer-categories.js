/**
 * Footer Categories Handler
 * Handles shopmore dropdown functionality
 */

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.shopmore').forEach(function(select) {
        select.addEventListener('change', function() {
            if (this.value) {
                if (typeof redirectWithLocation === 'function') {
                    redirectWithLocation(this.value);
                } else {
                    window.location.href = this.value;
                }
            }
        });
    });
});
