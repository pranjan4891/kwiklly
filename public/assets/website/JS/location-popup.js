/**
 * Location Popup Handler
 * Handles opening and closing of location selection popup
 */

// DOM elements - will be initialized when DOM is ready
let popup, overlay, trigger, headerLocationDesktop, headerLocationMobile, selectedLocationEl;

// Initialize DOM elements when ready
function initPopupElements() {
    popup = document.getElementById("addpopPopup");
    overlay = document.getElementById("addpopOverlay");
    trigger = document.querySelector(".location-boxs");
    headerLocationDesktop = document.querySelector(".location-text");
    headerLocationMobile = document.querySelector(".locations-text");
    selectedLocationEl = document.getElementById("selected-location");
    
    // Set up event listeners only if elements exist
    if (overlay) {
        overlay.addEventListener("click", closeAddpop);
    }
    if (popup) {
        popup.addEventListener("click", (e) => e.stopPropagation());
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPopupElements);
} else {
    // DOM already loaded, initialize immediately
    setTimeout(initPopupElements, 100);
}

// --- Popup Handling Functions (Make globally accessible immediately) ---
function toggleAddpop(e){
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    console.log("toggleAddpop called");
    
    // Get elements if not already cached
    if (!popup) popup = document.getElementById("addpopPopup");
    if (!overlay) overlay = document.getElementById("addpopOverlay");
    if (!trigger) trigger = document.querySelector(".location-boxs");
    
    console.log("Popup elements:", {
        popup: !!popup, 
        overlay: !!overlay, 
        trigger: !!trigger,
        popupId: popup ? popup.id : 'not found',
        overlayId: overlay ? overlay.id : 'not found'
    });
    
    if (!popup || !overlay) {
        console.error("Popup elements not found", {
            popup: !!popup, 
            overlay: !!overlay,
            popupElement: document.getElementById("addpopPopup"),
            overlayElement: document.getElementById("addpopOverlay")
        });
        return false;
    }
    
    const isMobile = window.innerWidth <= 768;
    console.log("Is mobile:", isMobile);

    if (isMobile) {
        // Save current scroll position
        const scrollY = window.scrollY;
        document.body.style.position = 'fixed';
        document.body.style.top = `-${scrollY}px`;
        document.body.style.width = '100%';

        popup.classList.add("addpop-mobile-active");
        overlay.classList.add("addpop-visible");
        console.log("Mobile popup opened");
    } else {
        // Desktop positioning - use .location-box for desktop
        let desktopTrigger = document.querySelector(".location-box");
        if (desktopTrigger) {
            const rect = desktopTrigger.getBoundingClientRect();
            popup.style.left = rect.left + "px";
            popup.style.top = (rect.bottom + window.scrollY + 5) + "px";
            console.log("Desktop popup positioned at:", rect.left, rect.bottom);
        } else {
            // Fallback positioning
            popup.style.left = "50%";
            popup.style.top = "50%";
            popup.style.transform = "translate(-50%, -50%)";
            console.log("Using fallback positioning");
        }
        popup.classList.add("addpop-desktop-active");
        overlay.classList.add("addpop-visible");
        console.log("Desktop popup opened", {
            popupClasses: popup.className,
            overlayClasses: overlay.className,
            popupStyle: {
                left: popup.style.left,
                top: popup.style.top,
                display: window.getComputedStyle(popup).display
            }
        });
    }
    document.body.style.overflow = 'hidden';
    
    // Ensure popup overflow is visible for autocomplete
    if (popup) {
        popup.style.overflow = 'visible';
    }
    
    // Clear any stale Places dropdown from a previous visit (empty box used to leave a visible bottom border)
    setTimeout(function () {
        if (popup.classList.contains('addpop-desktop-active') || popup.classList.contains('addpop-mobile-active')) {
            hideGooglePlacesDropdown();
        }
    }, 100);
    
    // Also check periodically while popup is open to ensure suggestions show
    const popupOpenCheckInterval = setInterval(function() {
        if (!popup.classList.contains('addpop-desktop-active') && 
            !popup.classList.contains('addpop-mobile-active')) {
            // Popup closed, stop checking
            clearInterval(popupOpenCheckInterval);
            return;
        }
        
        if (typeof ensurePacContainerVisible === 'function') {
            ensurePacContainerVisible();
        }
    }, 200);
    
    // Force show the appropriate input based on screen size
    let inputDesktop = document.getElementById("autocomplete");
    let inputMobile = document.getElementById("autocomplete-mobile");
    
    if (isMobile && inputMobile) {
        inputMobile.style.display = 'block';
        inputMobile.style.visibility = 'visible';
        inputMobile.style.opacity = '1';
        // Hide desktop input
        if (inputDesktop) {
            inputDesktop.style.display = 'none';
        }
    } else if (!isMobile && inputDesktop) {
        inputDesktop.style.display = 'block';
        inputDesktop.style.visibility = 'visible';
        inputDesktop.style.opacity = '1';
        // Hide mobile input
        if (inputMobile) {
            inputMobile.style.display = 'none';
        }
        console.log("Desktop input made visible:", {
            display: inputDesktop.style.display,
            visibility: inputDesktop.style.visibility,
            computedDisplay: window.getComputedStyle(inputDesktop).display
        });
    }
    
    // Reinitialize autocomplete when popup opens - multiple attempts
    // First attempt - immediate
    setTimeout(function() {
        console.log("Reinitializing autocomplete after popup open (attempt 1)");
        if (typeof initializeAutocompleteFields === 'function') {
            initializeAutocompleteFields();
        } else if (typeof initAutocompleteFieldsNow === 'function') {
            initAutocompleteFieldsNow();
        }
        // Show Google attribution when popup is open
        const pacContainers = document.querySelectorAll('.pac-container');
        pacContainers.forEach(function(container) {
            if (typeof showGoogleAttribution === 'function') {
                showGoogleAttribution(container);
            }
        });
    }, 100);
    
    // Second attempt - after popup animation
    setTimeout(function() {
        console.log("Reinitializing autocomplete after popup open (attempt 2)");
        if (typeof initializeAutocompleteFields === 'function') {
            initializeAutocompleteFields();
        } else if (typeof initAutocompleteFieldsNow === 'function') {
            initAutocompleteFieldsNow();
        }
        // Show Google attribution when popup is open
        const pacContainers = document.querySelectorAll('.pac-container');
        pacContainers.forEach(function(container) {
            if (typeof showGoogleAttribution === 'function') {
                showGoogleAttribution(container);
            }
        });
    }, 400);
    
    // Third attempt - final check
    setTimeout(function() {
        console.log("Reinitializing autocomplete after popup open (attempt 3)");
        if (typeof initializeAutocompleteFields === 'function') {
            initializeAutocompleteFields();
        } else if (typeof initAutocompleteFieldsNow === 'function') {
            initAutocompleteFieldsNow();
        }
        
        // Show Google attribution when popup is open
        const pacContainers = document.querySelectorAll('.pac-container');
        pacContainers.forEach(function(container) {
            if (typeof showGoogleAttribution === 'function') {
                showGoogleAttribution(container);
            }
        });
        
        // Also ensure input is focused and ready
        if (!isMobile && inputDesktop) {
            inputDesktop.focus();
            console.log("Desktop input focused");
        } else if (isMobile && inputMobile) {
            inputMobile.focus();
            console.log("Mobile input focused");
        }
    }, 800);
    
    // Continuous check for attribution while popup is open
    const popupElement = document.getElementById("addpopPopup");
    if (popupElement) {
        const attributionCheckInterval = setInterval(function() {
            if (!popupElement.classList.contains('addpop-desktop-active') && 
                !popupElement.classList.contains('addpop-mobile-active')) {
                // Popup closed, stop checking
                clearInterval(attributionCheckInterval);
                return;
            }
            
            const pacContainers = document.querySelectorAll('.pac-container');
            pacContainers.forEach(function(container) {
                // Show attribution when popup is open
                if (typeof showGoogleAttribution === 'function') {
                    showGoogleAttribution(container);
                }
            });
        }, 200);
    }
}

/** Collapse Google Places dropdown so empty/stale boxes do not overlap #selected-location */
function hideGooglePlacesDropdown() {
    document.querySelectorAll('.pac-container').forEach(function (container) {
        container.classList.remove('pac-container-visible');
        container.style.setProperty('display', 'none', 'important');
        container.style.setProperty('visibility', 'hidden', 'important');
        container.style.setProperty('opacity', '0', 'important');
        container.style.setProperty('height', '0', 'important');
        container.style.setProperty('max-height', '0', 'important');
        container.style.setProperty('width', '0', 'important');
        container.style.overflow = 'hidden';
        if (typeof hideGoogleAttribution === 'function') {
            hideGoogleAttribution(container);
        }
    });
}
window.hideGooglePlacesDropdown = hideGooglePlacesDropdown;

function closeAddpop(){
    // Get elements if not already cached
    if (!popup) popup = document.getElementById("addpopPopup");
    if (!overlay) overlay = document.getElementById("addpopOverlay");
    
    if (!popup || !overlay) {
        return;
    }
    
    const isMobile = window.innerWidth <= 768;

    popup.classList.remove("addpop-mobile-active", "addpop-desktop-active");
    overlay.classList.remove("addpop-visible");

    if (isMobile) {
        // Restore scroll position
        const scrollY = document.body.style.top;
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        if (scrollY) {
            window.scrollTo(0, parseInt(scrollY || '0') * -1);
        }
    }

    document.body.style.overflow = 'auto';
    
    hideGooglePlacesDropdown();

    console.log("Popup closed, hiding pac-containers and attribution");
}

// Make popup functions globally accessible IMMEDIATELY
window.toggleAddpop = toggleAddpop;
window.closeAddpop = closeAddpop;
