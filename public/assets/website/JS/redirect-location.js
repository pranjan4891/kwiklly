/**
 * Redirect with Location Handler
 * Handles redirecting to URLs with location parameters
 */

// --- Redirect helper ---
function redirectWithLocation(baseUrl) {
    let savedLocation = typeof getPreferredSavedLocationRaw === "function"
        ? getPreferredSavedLocationRaw()
        : localStorage.getItem("userLocation");

    if (savedLocation) {
        try {
            let loc = JSON.parse(savedLocation);

            if (loc.lat && loc.lng) {
                window.location.href = `${baseUrl}?latitude=${encodeURIComponent(loc.lat)}&longitude=${encodeURIComponent(loc.lng)}`;
                return false;
            }
        } catch (e) {
            console.error("Invalid saved location:", e);
        }
    }

    window.location.href = baseUrl;
    return false;
}

// Make redirectWithLocation globally accessible
window.redirectWithLocation = redirectWithLocation;
