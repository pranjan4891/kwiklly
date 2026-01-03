/**
 * Auto Redirect Handler
 * Handles auto-redirecting APP_URL with location parameters
 */

// Function to auto-redirect APP_URL with location parameters
function autoRedirectAppUrlWithLocation() {
    const currentUrl = new URL(window.location.href);
    const appUrl = window.APP_URL || window.location.origin;

    // Check if we're on the main APP_URL (home route without query params)
    if (currentUrl.href === appUrl || currentUrl.href === appUrl + '/' ||
        (currentUrl.pathname === '/' && currentUrl.search === '')) {

        // Only redirect if we have a saved location
        let savedLocation = localStorage.getItem("userLocation");
        if (savedLocation) {
            try {
                let loc = JSON.parse(savedLocation);
                if (loc.lat && loc.lng) {
                    // Use redirectWithLocation function
                    if (typeof redirectWithLocation === 'function') {
                        redirectWithLocation(appUrl);
                    }
                }
            } catch (e) {
                console.error("Invalid saved location:", e);
            }
        }
    }
}

// Execute on page load
window.addEventListener('DOMContentLoaded', function() {
    // Add a small delay to let other page initialization finish
    setTimeout(function() {
        autoRedirectAppUrlWithLocation();
    }, 100);
});
