/**
 * Hash Cleanup Handler
 * Removes Facebook OAuth hash from URL
 */

if (window.location.hash && window.location.hash === '#_=_') {
    if (window.history && history.replaceState) {
        // Removes #_=_ without reloading the page
        history.replaceState(null, null, window.location.href.split('#')[0]);
    } else {
        // Fallback for older browsers
        window.location.hash = '';
    }
}
