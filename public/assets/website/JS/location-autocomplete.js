/**
 * Google Maps Autocomplete Handler
 * Handles Google Maps Places Autocomplete initialization for both desktop and mobile
 */

// --- Initialize Google Autocomplete ---
// Make this globally accessible IMMEDIATELY (before Google Maps API loads)
// Define initAutocomplete function and make it globally accessible
window.initAutocomplete = function() {
    console.log("initAutocomplete called by Google Maps API");
    
    // Wait for DOM to be ready and for initializeAutocompleteFields to be defined
    function tryInitialize() {
        if (typeof initializeAutocompleteFields === 'function') {
            console.log("Calling initializeAutocompleteFields");
            initializeAutocompleteFields();
        } else {
            console.log("initializeAutocompleteFields not yet defined, retrying...");
            setTimeout(tryInitialize, 100);
        }
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', tryInitialize);
    } else {
        tryInitialize();
    }
};

console.log("initAutocomplete function defined and globally accessible:", typeof window.initAutocomplete);

// Store autocomplete instances
let autocompleteDesktopInstance = null;
let autocompleteMobileInstance = null;

function initializeAutocompleteFields() {
    // Check if Google Maps API is loaded
    if (typeof google === 'undefined' || typeof google.maps === 'undefined' || typeof google.maps.places === 'undefined') {
        console.warn("Google Maps API not loaded yet. Retrying...");
        setTimeout(initializeAutocompleteFields, 500);
        return;
    }
    
    console.log("Google Maps API loaded, initializing autocomplete fields...");
    
    // Wait a bit for popup to be visible if it's opening
    setTimeout(function() {
        initAutocompleteFieldsNow();
    }, 100);
}

function initAutocompleteFieldsNow() {
    const isMobile = window.innerWidth <= 768;
    console.log("initAutocompleteFieldsNow called, isMobile:", isMobile);
    
    // Desktop autocomplete
    let inputDesktop = document.getElementById("autocomplete");
    if (inputDesktop && !isMobile) {
        // Ensure input is visible before initializing
        const computedStyle = window.getComputedStyle(inputDesktop);
        console.log("Desktop input computed style:", {
            display: computedStyle.display,
            visibility: computedStyle.visibility,
            opacity: computedStyle.opacity
        });
        
        if (computedStyle.display === 'none' || computedStyle.visibility === 'hidden') {
            console.log("Desktop input is hidden, making it visible");
            inputDesktop.style.display = 'block';
            inputDesktop.style.visibility = 'visible';
            inputDesktop.style.opacity = '1';
        }
        
        console.log("Initializing desktop autocomplete");
        try {
            // Destroy existing instance if any
            if (autocompleteDesktopInstance) {
                google.maps.event.clearInstanceListeners(autocompleteDesktopInstance);
            }
            
            autocompleteDesktopInstance = new google.maps.places.Autocomplete(inputDesktop, {
                types: ['geocode'],
                componentRestrictions: { country: 'in' },
                fields: ['geometry', 'formatted_address', 'name', 'address_components']
            });
            
            // Also listen for when user starts typing
            google.maps.event.addDomListener(inputDesktop, 'keydown', function() {
                setTimeout(function() {
                    ensurePacContainerVisible();
                }, 50);
            });
            
            // Store reference to input for later use
            if (autocompleteDesktopInstance) {
                autocompleteDesktopInstance.inputElement = inputDesktop;
                console.log("Desktop autocomplete instance created successfully");
            }
            
            autocompleteDesktopInstance.addListener("place_changed", function () {
                let place = autocompleteDesktopInstance.getPlace();
                console.log("Desktop place selected:", place);
                if (place.geometry && place.geometry.location) {
                    let lat = place.geometry.location.lat();
                    let lng = place.geometry.location.lng();
                    let fullAddress = place.formatted_address || place.name;
                    console.log("Updating location from desktop autocomplete:", lat, lng);
                    if (typeof updateLocation === 'function') {
                        updateLocation(fullAddress, place, lat, lng, false);
                    }
                }
                if (typeof window.hideGooglePlacesDropdown === 'function') {
                    window.hideGooglePlacesDropdown();
                }
            });
            
            // Ensure dropdown shows and suggestions are visible
            inputDesktop.addEventListener('input', function() {
                // Start periodic check when user types
                if (typeof startPeriodicCheck === 'function') {
                    startPeriodicCheck();
                }
                console.log("Desktop input event triggered, value:", this.value);
                const inputValue = this.value;
                
                // Check if popup is open
                const popup = document.getElementById("addpopPopup");
                const isPopupOpen = popup && (popup.classList.contains('addpop-desktop-active') || popup.classList.contains('addpop-mobile-active'));
                
                if (!isPopupOpen) {
                    console.log("Popup not open, skipping autocomplete visibility");
                    return;
                }
                
                console.log("Popup is open, ensuring suggestions are visible");
                
                // Multiple checks to ensure visibility - immediate and delayed
                // Immediate check
                setTimeout(function() {
                    // Find and show any pac-containers
                    let pacContainers = document.querySelectorAll('.pac-container');
                    console.log("Desktop input: Found", pacContainers.length, "pac-containers");
                    
                    pacContainers.forEach(function(container) {
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        container.style.setProperty('height', 'auto', 'important');
                        container.style.setProperty('max-height', '300px', 'important');
                        container.style.zIndex = '100000002';
                        container.style.backgroundColor = 'white';
                        container.style.overflowY = 'auto';
                        container.style.overflowX = 'hidden';
                        container.style.position = 'fixed';
                        container.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.15)';
                        container.style.borderRadius = '8px';
                        container.style.border = 'none';
                        
                        // Force show all pac-items (suggestions)
                        const items = container.querySelectorAll('.pac-item');
                        console.log("Desktop input: Found", items.length, "pac-items");
                        items.forEach(function(item) {
                            item.style.setProperty('display', 'block', 'important');
                            item.style.setProperty('visibility', 'visible', 'important');
                            item.style.setProperty('opacity', '1', 'important');
                            item.style.setProperty('text-indent', '0', 'important');
                            item.style.padding = '12px 16px';
                            item.style.cursor = 'pointer';
                            item.style.backgroundColor = '#ffffff';
                            item.style.transition = 'background-color 0.2s ease';
                            item.style.borderBottom = 'none';
                            
                            // Ensure all children are visible with proper styling
                            const children = item.querySelectorAll('*');
                            children.forEach(function(child) {
                                child.style.setProperty('text-indent', '0', 'important');
                                child.style.setProperty('display', 'block', 'important');
                                child.style.setProperty('visibility', 'visible', 'important');
                                child.style.setProperty('opacity', '1', 'important');
                                
                                // Style text elements properly
                                if (child.tagName === 'STRONG' || child.tagName === 'B') {
                                    child.style.fontWeight = '600';
                                    child.style.color = '#212529';
                                    child.style.fontSize = '15px';
                                } else if (child.tagName === 'SPAN' || child.tagName === 'DIV') {
                                    if (!child.classList.contains('pac-icon')) {
                                        child.style.color = '#6c757d';
                                        child.style.fontSize = '13px';
                                    }
                                }
                            });
                            
                            // Style location icons and add if missing
                            let icon = item.querySelector('.pac-icon, img');
                            if (!icon) {
                                // Create icon if it doesn't exist
                                icon = document.createElement('img');
                                icon.src = window.MARKER_IMAGE_URL || '/loc.png';
                                icon.className = 'pac-icon';
                                icon.alt = 'Location';
                                item.insertBefore(icon, item.firstChild);
                            }
                            icon.style.width = '24px';
                            icon.style.height = '24px';
                            icon.style.marginRight = '12px';
                            icon.style.opacity = '1';
                            icon.style.verticalAlign = 'middle';
                            icon.style.display = 'inline-block';
                            icon.style.objectFit = 'contain';
                            icon.style.flexShrink = '0';
                        });
                        console.log("Desktop input: Forced", items.length, "suggestions to be visible, container display:", window.getComputedStyle(container).display);
                        
                        // Show attribution
                        if (typeof showGoogleAttribution === 'function') {
                            showGoogleAttribution(container);
                        }
                    });
                    
                    ensurePacContainerVisible();
                    
                    // If still no pac-container and input has value, trigger autocomplete manually
                    if (inputValue && inputValue.length > 0 && document.querySelectorAll('.pac-container').length === 0) {
                        console.log("No pac-container found, triggering autocomplete manually");
                        if (autocompleteDesktopInstance) {
                            // Force autocomplete to show suggestions
                            google.maps.event.trigger(inputDesktop, 'focus');
                            google.maps.event.trigger(inputDesktop, 'keydown');
                        }
                    }
                }, 100);
                
                setTimeout(function() {
                    ensurePacContainerVisible();
                    let pacContainers = document.querySelectorAll('.pac-container');
                    pacContainers.forEach(function(container) {
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        
                        const items = container.querySelectorAll('.pac-item');
                        items.forEach(function(item) {
                            item.style.setProperty('display', 'block', 'important');
                            item.style.setProperty('visibility', 'visible', 'important');
                            item.style.setProperty('opacity', '1', 'important');
                        });
                    });
                }, 300);
                
                setTimeout(function() {
                    ensurePacContainerVisible();
                    let pacContainers = document.querySelectorAll('.pac-container');
                    pacContainers.forEach(function(container) {
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        
                        const items = container.querySelectorAll('.pac-item');
                        items.forEach(function(item) {
                            item.style.setProperty('display', 'block', 'important');
                            item.style.setProperty('visibility', 'visible', 'important');
                            item.style.setProperty('opacity', '1', 'important');
                        });
                    });
                }, 500);
            });
            
            // Also trigger on focus
            inputDesktop.addEventListener('focus', function() {
                console.log("Desktop focus event triggered");
                const popup = document.getElementById("addpopPopup");
                const isPopupOpen = popup && (popup.classList.contains('addpop-desktop-active') || popup.classList.contains('addpop-mobile-active'));
                
                if (!isPopupOpen) return;
                
                setTimeout(function() {
                    ensurePacContainerVisible();
                    let pacContainers = document.querySelectorAll('.pac-container');
                    pacContainers.forEach(function(container) {
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        
                        if (typeof showGoogleAttribution === 'function') {
                            showGoogleAttribution(container);
                        }
                    });
                }, 100);
                setTimeout(function() {
                    ensurePacContainerVisible();
                    let pacContainers = document.querySelectorAll('.pac-container');
                    pacContainers.forEach(function(container) {
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        
                        if (typeof showGoogleAttribution === 'function') {
                            showGoogleAttribution(container);
                        }
                    });
                }, 300);
            });
            
            // Trigger on keydown to catch typing
            inputDesktop.addEventListener('keydown', function(e) {
                console.log("Desktop keydown event:", e.key);
                const popup = document.getElementById("addpopPopup");
                const isPopupOpen = popup && (popup.classList.contains('addpop-desktop-active') || popup.classList.contains('addpop-mobile-active'));
                
                if (!isPopupOpen) return;
                
                setTimeout(function() {
                    ensurePacContainerVisible();
                    let pacContainers = document.querySelectorAll('.pac-container');
                    pacContainers.forEach(function(container) {
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        
                        if (typeof showGoogleAttribution === 'function') {
                            showGoogleAttribution(container);
                        }
                    });
                }, 50);
                setTimeout(function() {
                    ensurePacContainerVisible();
                    let pacContainers = document.querySelectorAll('.pac-container');
                    pacContainers.forEach(function(container) {
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        
                        if (typeof showGoogleAttribution === 'function') {
                            showGoogleAttribution(container);
                        }
                    });
                }, 150);
            });
            
            // Also listen for keyup
            inputDesktop.addEventListener('keyup', function() {
                const popup = document.getElementById("addpopPopup");
                const isPopupOpen = popup && (popup.classList.contains('addpop-desktop-active') || popup.classList.contains('addpop-mobile-active'));
                
                if (!isPopupOpen) return;
                
                setTimeout(function() {
                    ensurePacContainerVisible();
                    let pacContainers = document.querySelectorAll('.pac-container');
                    pacContainers.forEach(function(container) {
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        
                        if (typeof showGoogleAttribution === 'function') {
                            showGoogleAttribution(container);
                        }
                    });
                }, 100);
            });
        } catch (error) {
            console.error("Error initializing desktop autocomplete:", error);
            // Retry initialization after a delay
            setTimeout(function() {
                console.log("Retrying desktop autocomplete initialization");
                if (inputDesktop && !isMobile) {
                    try {
                        if (autocompleteDesktopInstance) {
                            google.maps.event.clearInstanceListeners(autocompleteDesktopInstance);
                        }
                        autocompleteDesktopInstance = new google.maps.places.Autocomplete(inputDesktop, {
                            types: ['geocode'],
                            componentRestrictions: { country: 'in' },
                            fields: ['geometry', 'formatted_address', 'name', 'address_components']
                        });
                        console.log("Desktop autocomplete retry successful");
                    } catch (retryError) {
                        console.error("Desktop autocomplete retry failed:", retryError);
                    }
                }
            }, 1000);
        }
    } else {
        if (isMobile) {
            console.log("Skipping desktop autocomplete (mobile view)");
        } else {
            console.warn("Desktop autocomplete input not found");
        }
    }

    // Mobile autocomplete
    let inputMobile = document.getElementById("autocomplete-mobile");
    if (inputMobile && isMobile) {
        // Ensure input is visible before initializing
        const computedStyle = window.getComputedStyle(inputMobile);
        if (computedStyle.display === 'none') {
            console.log("Mobile input is hidden, making it visible");
            inputMobile.style.display = 'block';
            inputMobile.style.visibility = 'visible';
        }
        
        console.log("Initializing mobile autocomplete");
        try {
            // Destroy existing instance if any
            if (autocompleteMobileInstance) {
                google.maps.event.clearInstanceListeners(autocompleteMobileInstance);
            }
            
            autocompleteMobileInstance = new google.maps.places.Autocomplete(inputMobile, {
                types: ['geocode'],
                componentRestrictions: { country: 'in' },
                fields: ['geometry', 'formatted_address', 'name', 'address_components']
            });
            
            // Also listen for when user starts typing
            google.maps.event.addDomListener(inputMobile, 'keydown', function() {
                setTimeout(function() {
                    ensurePacContainerVisible();
                }, 50);
            });
            
            autocompleteMobileInstance.addListener("place_changed", function () {
                let place = autocompleteMobileInstance.getPlace();
                console.log("Mobile place selected:", place);
                if (place.geometry && place.geometry.location) {
                    let lat = place.geometry.location.lat();
                    let lng = place.geometry.location.lng();
                    let fullAddress = place.formatted_address || place.name;
                    console.log("Updating location from mobile autocomplete:", lat, lng);
                    if (typeof updateLocation === 'function') {
                        updateLocation(fullAddress, place, lat, lng, false);
                    }
                }
                if (typeof window.hideGooglePlacesDropdown === 'function') {
                    window.hideGooglePlacesDropdown();
                }
            });
            
            // Force show dropdown on mobile when typing
            inputMobile.addEventListener('input', function() {
                // Start periodic check when user types
                if (typeof startPeriodicCheck === 'function') {
                    startPeriodicCheck();
                }
                console.log("Mobile input event triggered, value:", this.value);
                const inputValue = this.value;
                
                // Check if popup is open
                const popup = document.getElementById("addpopPopup");
                const isPopupOpen = popup && (popup.classList.contains('addpop-desktop-active') || popup.classList.contains('addpop-mobile-active'));
                
                if (!isPopupOpen) {
                    console.log("Popup not open, skipping autocomplete visibility");
                    return;
                }
                
                console.log("Mobile popup is open, ensuring suggestions are visible");
                
                // Multiple checks to ensure visibility - immediate and delayed
                // Immediate check
                setTimeout(function() {
                    // Find and show any pac-containers
                    let pacContainers = document.querySelectorAll('.pac-container');
                    console.log("Mobile: Found", pacContainers.length, "pac-containers");
                    
                    pacContainers.forEach(function(container) {
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        container.style.setProperty('height', 'auto', 'important');
                        container.style.setProperty('max-height', '300px', 'important');
                        container.style.zIndex = '100000002';
                        container.style.backgroundColor = 'white';
                        container.style.overflowY = 'auto';
                        container.style.overflowX = 'hidden';
                        container.style.position = 'fixed';
                        container.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.15)';
                        container.style.borderRadius = '8px';
                        container.style.border = 'none';
                        
                        // Force show all pac-items (suggestions) with improved styling
                        const items = container.querySelectorAll('.pac-item');
                        console.log("Mobile: Found", items.length, "pac-items");
                        items.forEach(function(item) {
                            item.style.setProperty('display', 'block', 'important');
                            item.style.setProperty('visibility', 'visible', 'important');
                            item.style.setProperty('opacity', '1', 'important');
                            item.style.setProperty('text-indent', '0', 'important');
                            item.style.padding = '12px 16px';
                            item.style.cursor = 'pointer';
                            item.style.backgroundColor = '#ffffff';
                            item.style.transition = 'background-color 0.2s ease';
                            item.style.borderBottom = 'none';
                            
                            // Ensure all children are visible with proper styling
                            const children = item.querySelectorAll('*');
                            children.forEach(function(child) {
                                child.style.setProperty('text-indent', '0', 'important');
                                child.style.setProperty('display', 'block', 'important');
                                child.style.setProperty('visibility', 'visible', 'important');
                                child.style.setProperty('opacity', '1', 'important');
                                
                                // Style text elements properly
                                if (child.tagName === 'STRONG' || child.tagName === 'B') {
                                    child.style.fontWeight = '600';
                                    child.style.color = '#212529';
                                    child.style.fontSize = '15px';
                                } else if (child.tagName === 'SPAN' || child.tagName === 'DIV') {
                                    if (!child.classList.contains('pac-icon')) {
                                        child.style.color = '#6c757d';
                                        child.style.fontSize = '13px';
                                    }
                                }
                            });
                            
                            // Style location icons and add if missing
                            let icon = item.querySelector('.pac-icon, img');
                            if (!icon) {
                                // Create icon if it doesn't exist
                                icon = document.createElement('img');
                                icon.src = window.MARKER_IMAGE_URL || '/loc.png';
                                icon.className = 'pac-icon';
                                icon.alt = 'Location';
                                item.insertBefore(icon, item.firstChild);
                            }
                            icon.style.width = '24px';
                            icon.style.height = '24px';
                            icon.style.marginRight = '12px';
                            icon.style.opacity = '1';
                            icon.style.verticalAlign = 'middle';
                            icon.style.display = 'inline-block';
                            icon.style.objectFit = 'contain';
                            icon.style.flexShrink = '0';
                        });
                        
                        // Show attribution at bottom
                        if (typeof showGoogleAttribution === 'function') {
                            showGoogleAttribution(container);
                        }
                    });
                    
                    ensurePacContainerVisible();
                }, 50);
                
                setTimeout(function() {
                    ensurePacContainerVisible();
                    let pacContainers = document.querySelectorAll('.pac-container');
                    console.log("Mobile input (100ms): Found", pacContainers.length, "pac-containers");
                    
                    pacContainers.forEach(function(container) {
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        
                        const items = container.querySelectorAll('.pac-item');
                        console.log("Mobile input (100ms): Found", items.length, "pac-items");
                        items.forEach(function(item) {
                            item.style.setProperty('display', 'block', 'important');
                            item.style.setProperty('visibility', 'visible', 'important');
                            item.style.setProperty('opacity', '1', 'important');
                            item.style.padding = '12px 16px';
                            item.style.backgroundColor = '#ffffff';
                            item.style.transition = 'background-color 0.2s ease';
                            item.style.borderBottom = 'none';
                            
                            // Style text elements
                            const strongElements = item.querySelectorAll('strong, b');
                            strongElements.forEach(function(el) {
                                el.style.fontWeight = '600';
                                el.style.color = '#212529';
                                el.style.fontSize = '15px';
                            });
                            
                            const textElements = item.querySelectorAll('span:not(.pac-icon), div:not(.pac-attribution)');
                            textElements.forEach(function(el) {
                                if (!el.closest('strong') && !el.closest('b')) {
                                    el.style.color = '#6c757d';
                                    el.style.fontSize = '13px';
                                }
                            });
                        });
                        
                        if (typeof showGoogleAttribution === 'function') {
                            showGoogleAttribution(container);
                        }
                    });
                }, 100);
                setTimeout(function() {
                    ensurePacContainerVisible();
                    let pacContainers = document.querySelectorAll('.pac-container');
                    console.log("Mobile input (300ms): Found", pacContainers.length, "pac-containers");
                    
                    pacContainers.forEach(function(container) {
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        
                        const items = container.querySelectorAll('.pac-item');
                        console.log("Mobile input (300ms): Found", items.length, "pac-items");
                        items.forEach(function(item) {
                            item.style.setProperty('display', 'block', 'important');
                            item.style.setProperty('visibility', 'visible', 'important');
                            item.style.setProperty('opacity', '1', 'important');
                            item.style.setProperty('white-space', 'normal', 'important');
                            item.style.padding = '12px 16px 12px 44px';
                            item.style.backgroundColor = '#ffffff';
                            item.style.transition = 'background-color 0.2s ease';
                            item.style.borderBottom = 'none';
                            item.style.position = 'relative';
                            
                            // Make all child elements inline
                            const children = item.querySelectorAll('*');
                            children.forEach(function(child) {
                                child.style.setProperty('display', 'inline', 'important');
                                child.style.setProperty('white-space', 'normal', 'important');
                                child.style.margin = '0';
                                child.style.padding = '0';
                                child.style.verticalAlign = 'baseline';
                                child.style.float = 'none';
                                child.style.clear = 'none';
                                child.style.width = 'auto';
                                child.style.height = 'auto';
                                child.style.lineHeight = 'inherit';
                            });
                            
                            // Convert all div elements to inline
                            const divs = item.querySelectorAll('div:not(.pac-icon):not(.pac-attribution)');
                            divs.forEach(function(div) {
                                div.style.setProperty('display', 'inline', 'important');
                                div.style.setProperty('white-space', 'normal', 'important');
                                if (div.textContent.trim() && div.previousSibling && div.previousSibling.textContent.trim()) {
                                    div.style.marginLeft = '4px';
                                }
                            });
                            
                            // Style text elements
                            const strongElements = item.querySelectorAll('strong, b');
                            strongElements.forEach(function(el) {
                                el.style.fontWeight = '600';
                                el.style.color = '#212529';
                                el.style.fontSize = '15px';
                                el.style.marginRight = '4px';
                            });
                            
                            const textElements = item.querySelectorAll('span:not(.pac-icon), div:not(.pac-attribution)');
                            textElements.forEach(function(el) {
                                if (!el.closest('strong') && !el.closest('b')) {
                                    el.style.color = '#6c757d';
                                    el.style.fontSize = '13px';
                                    el.style.marginLeft = '4px';
                                }
                            });
                            
                            // Handle icon - add if missing
                            let icon = item.querySelector('.pac-icon, img');
                            if (!icon) {
                                // Create icon if it doesn't exist
                                icon = document.createElement('img');
                                icon.src = window.MARKER_IMAGE_URL || '/loc.png';
                                icon.className = 'pac-icon';
                                icon.alt = 'Location';
                                item.insertBefore(icon, item.firstChild);
                            }
                            icon.style.setProperty('display', 'inline-block', 'important');
                            icon.style.width = '24px';
                            icon.style.height = '24px';
                            icon.style.marginRight = '12px';
                            icon.style.verticalAlign = 'middle';
                            icon.style.opacity = '1';
                            icon.style.objectFit = 'contain';
                            icon.style.flexShrink = '0';
                            
                            // Remove all <br> tags completely
                            const brTags = item.querySelectorAll('br');
                            brTags.forEach(function(br) {
                                br.style.setProperty('display', 'none', 'important');
                                br.style.setProperty('visibility', 'hidden', 'important');
                                br.style.setProperty('height', '0', 'important');
                                br.style.setProperty('line-height', '0', 'important');
                                br.style.setProperty('margin', '0', 'important');
                                br.style.setProperty('padding', '0', 'important');
                            });
                        });
                        
                        if (typeof showGoogleAttribution === 'function') {
                            showGoogleAttribution(container);
                        }
                    });
                }, 300);
                setTimeout(function() {
                    ensurePacContainerVisible();
                    let pacContainers = document.querySelectorAll('.pac-container');
                    console.log("Mobile input (500ms): Found", pacContainers.length, "pac-containers");
                    
                    pacContainers.forEach(function(container) {
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        
                        const items = container.querySelectorAll('.pac-item');
                        console.log("Mobile input (500ms): Found", items.length, "pac-items");
                        items.forEach(function(item) {
                            item.style.setProperty('display', 'block', 'important');
                            item.style.setProperty('visibility', 'visible', 'important');
                            item.style.setProperty('opacity', '1', 'important');
                            item.style.padding = '12px 16px';
                            item.style.backgroundColor = '#ffffff';
                            item.style.transition = 'background-color 0.2s ease';
                            item.style.borderBottom = 'none';
                            
                            // Style text elements
                            const strongElements = item.querySelectorAll('strong, b');
                            strongElements.forEach(function(el) {
                                el.style.fontWeight = '600';
                                el.style.color = '#212529';
                                el.style.fontSize = '15px';
                            });
                            
                            const textElements = item.querySelectorAll('span:not(.pac-icon), div:not(.pac-attribution)');
                            textElements.forEach(function(el) {
                                if (!el.closest('strong') && !el.closest('b')) {
                                    el.style.color = '#6c757d';
                                    el.style.fontSize = '13px';
                                }
                            });
                        });
                        
                        if (typeof showGoogleAttribution === 'function') {
                            showGoogleAttribution(container);
                        }
                    });
                }, 500);
            });
            
            // Also trigger on focus
            inputMobile.addEventListener('focus', function() {
                console.log("Mobile focus event triggered");
                const popup = document.getElementById("addpopPopup");
                const isPopupOpen = popup && (popup.classList.contains('addpop-desktop-active') || popup.classList.contains('addpop-mobile-active'));
                
                if (!isPopupOpen) return;
                
                setTimeout(function() {
                    ensurePacContainerVisible();
                    let pacContainers = document.querySelectorAll('.pac-container');
                    pacContainers.forEach(function(container) {
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        
                        if (typeof showGoogleAttribution === 'function') {
                            showGoogleAttribution(container);
                        }
                    });
                }, 200);
            });
            
            // Trigger on keydown to catch typing
            inputMobile.addEventListener('keydown', function() {
                const popup = document.getElementById("addpopPopup");
                const isPopupOpen = popup && (popup.classList.contains('addpop-desktop-active') || popup.classList.contains('addpop-mobile-active'));
                
                if (!isPopupOpen) return;
                
                setTimeout(function() {
                    ensurePacContainerVisible();
                    let pacContainers = document.querySelectorAll('.pac-container');
                    pacContainers.forEach(function(container) {
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        
                        const items = container.querySelectorAll('.pac-item');
                        items.forEach(function(item) {
                            item.style.setProperty('display', 'flex', 'important');
                            item.style.setProperty('visibility', 'visible', 'important');
                            item.style.setProperty('opacity', '1', 'important');
                            item.style.setProperty('align-items', 'center', 'important');
                            
                            // Add icon if missing
                            let icon = item.querySelector('.pac-icon, img');
                            if (!icon) {
                                icon = document.createElement('img');
                                icon.src = window.MARKER_IMAGE_URL || '/loc.png';
                                icon.className = 'pac-icon';
                                icon.alt = 'Location';
                                item.insertBefore(icon, item.firstChild);
                            }
                            icon.style.setProperty('display', 'inline-block', 'important');
                            icon.style.width = '24px';
                            icon.style.height = '24px';
                            icon.style.marginRight = '12px';
                            icon.style.verticalAlign = 'middle';
                            icon.style.opacity = '1';
                            icon.style.objectFit = 'contain';
                            icon.style.flexShrink = '0';
                        });
                        
                        if (typeof showGoogleAttribution === 'function') {
                            showGoogleAttribution(container);
                        }
                    });
                }, 100);
            });
        } catch (error) {
            console.error("Error initializing mobile autocomplete:", error);
        }
    } else {
        console.warn("Mobile autocomplete input not found");
    }
    
    // Set up MutationObserver to ensure pac-container stays visible
    setupPacContainerObserver();
    
    console.log("Autocomplete initialization complete");
}

// Function to ensure pac-container is visible
function ensurePacContainerVisible() {
    let pacContainers = document.querySelectorAll('.pac-container');
    
    console.log("ensurePacContainerVisible called, found", pacContainers.length, "pac-containers");
    
    if (pacContainers.length === 0) {
        console.log("No pac-container found yet in ensurePacContainerVisible");
        return;
    }

    const popupElement = document.getElementById("addpopPopup");
    const isPopupOpen = popupElement && (popupElement.classList.contains('addpop-desktop-active') || popupElement.classList.contains('addpop-mobile-active'));
    const inputDesktop = document.getElementById("autocomplete");
    const inputMobile = document.getElementById("autocomplete-mobile");
    const inputFocused = document.activeElement === inputDesktop || document.activeElement === inputMobile;

    pacContainers.forEach(function(pacContainer, index) {
        if (!pacContainer) {
            return;
        }
        console.log("Ensuring pac-container visibility for container", index + 1);

        const pacItems = pacContainer.querySelectorAll('.pac-item');

        if (!isPopupOpen || pacItems.length === 0 || !inputFocused) {
            pacContainer.classList.remove('pac-container-visible');
            pacContainer.style.setProperty('display', 'none', 'important');
            pacContainer.style.setProperty('visibility', 'hidden', 'important');
            pacContainer.style.setProperty('opacity', '0', 'important');
            pacContainer.style.setProperty('height', '0', 'important');
            pacContainer.style.setProperty('max-height', '0', 'important');
            pacContainer.style.setProperty('width', '0', 'important');
            pacContainer.style.overflow = 'hidden';
            if (typeof hideGoogleAttribution === 'function') {
                hideGoogleAttribution(pacContainer);
            }
            return;
        }

        let inputField = null;
        if (inputDesktop && window.getComputedStyle(inputDesktop).display !== 'none') {
            inputField = inputDesktop;
        } else if (inputMobile && window.getComputedStyle(inputMobile).display !== 'none') {
            inputField = inputMobile;
        }

        if (inputField) {
            let inputRect = inputField.getBoundingClientRect();
            pacContainer.style.position = 'fixed';

            const isMobile = window.innerWidth <= 768;
            const gap = isMobile ? 8 : 6;
            pacContainer.style.top = (inputRect.bottom + window.scrollY + gap) + 'px';

            pacContainer.style.left = inputRect.left + 'px';
            pacContainer.style.width = inputRect.width + 'px';
            console.log("Positioned pac-container at:", inputRect.left, inputRect.bottom + gap, isMobile ? '(mobile with gap)' : '(web with gap)');
        }

        let parentPopup = pacContainer.closest('.addpop-popup');
        if (parentPopup) {
            parentPopup.style.overflow = 'visible';
        }

        let actions = pacContainer.closest('.addpop-actions');
        if (actions) {
            actions.style.overflow = 'visible';
        }

        pacContainer.classList.add('pac-container-visible');
        pacContainer.style.setProperty('display', 'block', 'important');
        pacContainer.style.setProperty('visibility', 'visible', 'important');
        pacContainer.style.setProperty('opacity', '1', 'important');
        pacContainer.style.setProperty('height', 'auto', 'important');
        pacContainer.style.setProperty('max-height', '300px', 'important');
        pacContainer.style.zIndex = '100000002';
        pacContainer.style.backgroundColor = 'white';
        pacContainer.style.overflowY = 'auto';
        pacContainer.style.overflowX = 'hidden';
        pacContainer.style.position = 'fixed';
        pacContainer.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.15)';
        pacContainer.style.borderRadius = '8px';
        pacContainer.style.border = 'none';

        const computedDisplay = window.getComputedStyle(pacContainer).display;
        console.log("Pac-container shown, computed display:", computedDisplay);

        console.log("Found", pacItems.length, "pac-items in ensurePacContainerVisible");

        pacItems.forEach(function(item) {
                    item.style.setProperty('display', 'block', 'important');
                    item.style.setProperty('visibility', 'visible', 'important');
                    item.style.setProperty('opacity', '1', 'important');
                    item.style.setProperty('text-indent', '0', 'important');
                    item.style.setProperty('white-space', 'normal', 'important');
                    item.style.padding = '10px 15px 10px 44px';
                    item.style.cursor = 'pointer';
                    item.style.borderBottom = 'none';
                    item.style.color = '#333';
                    item.style.position = 'relative';
                    
                    // Ensure all child elements are visible and inline
                    const children = item.querySelectorAll('*');
                    children.forEach(function(child) {
                        child.style.setProperty('text-indent', '0', 'important');
                        child.style.setProperty('display', 'inline', 'important');
                        child.style.setProperty('visibility', 'visible', 'important');
                        child.style.setProperty('opacity', '1', 'important');
                        child.style.setProperty('white-space', 'normal', 'important');
                        child.style.color = '#333';
                        child.style.margin = '0';
                        child.style.padding = '0';
                        child.style.verticalAlign = 'baseline';
                        child.style.float = 'none';
                        child.style.clear = 'none';
                        child.style.width = 'auto';
                        child.style.height = 'auto';
                        child.style.lineHeight = 'inherit';
                    });
                    
                    // Handle icon separately - add if missing and ensure it's first
                    let icon = item.querySelector('.pac-icon, img[src*="loc.png"]');
                    if (!icon || icon.tagName !== 'IMG') {
                        // Remove any existing non-img icon
                        const existingIcon = item.querySelector('.pac-icon:not(img)');
                        if (existingIcon) {
                            existingIcon.remove();
                        }
                        
                        // Create icon if it doesn't exist
                        icon = document.createElement('img');
                        icon.src = window.MARKER_IMAGE_URL || '/loc.png';
                        icon.className = 'pac-icon';
                        icon.alt = 'Location';
                        icon.setAttribute('role', 'img');
                        icon.setAttribute('aria-label', 'Location icon');
                        
                        // Insert at the very beginning of the item
                        if (item.firstChild) {
                            item.insertBefore(icon, item.firstChild);
                        } else {
                            item.appendChild(icon);
                        }
                    } else {
                        // Ensure icon src is correct - only use loc.png
                        if (!icon.src || !icon.src.includes('loc.png')) {
                            icon.src = window.MARKER_IMAGE_URL || '/loc.png';
                        }
                        // Ensure icon is first child
                        if (icon !== item.firstChild) {
                            item.insertBefore(icon, item.firstChild);
                        }
                    }
                    icon.style.setProperty('display', 'inline-block', 'important');
                    icon.style.setProperty('visibility', 'visible', 'important');
                    icon.style.width = '24px';
                    icon.style.height = '24px';
                    icon.style.marginRight = '0';
                    icon.style.verticalAlign = 'top';
                    icon.style.opacity = '1';
                    icon.style.objectFit = 'contain';
                    icon.style.flexShrink = '0';
                    icon.style.position = 'absolute';
                    icon.style.left = '14px';
                    icon.style.top = '10px';
                    
                    // Remove all <br> tags completely
                    const brTags = item.querySelectorAll('br');
                    brTags.forEach(function(br) {
                        br.style.setProperty('display', 'none', 'important');
                        br.style.setProperty('visibility', 'hidden', 'important');
                        br.style.setProperty('height', '0', 'important');
                        br.style.setProperty('line-height', '0', 'important');
                        br.style.setProperty('margin', '0', 'important');
                        br.style.setProperty('padding', '0', 'important');
                    });
                    
                    // Convert all div elements to inline and add spacing
                    const divs = item.querySelectorAll('div:not(.pac-icon):not(.pac-attribution)');
                    divs.forEach(function(div) {
                        div.style.setProperty('display', 'inline', 'important');
                        div.style.setProperty('white-space', 'normal', 'important');
                        if (div.textContent.trim() && div.previousSibling && div.previousSibling.textContent.trim()) {
                            div.style.marginLeft = '4px';
                        }
                    });
                });
                
                console.log("Made", pacItems.length, "pac-items visible");
                
                // Show Google attribution elements when popup is open
                if (typeof showGoogleAttribution === 'function') {
                    showGoogleAttribution(pacContainer);
                }
                
        console.log("Pac-container visibility ensured (popup open), items:", pacItems.length, "position:", pacContainer.style.position, "display:", computedDisplay);
    });
}

// Function to show Google attribution when popup is open
function showGoogleAttribution(container) {
    if (!container) return;
    
    // Check if popup is open
    const popup = document.getElementById("addpopPopup");
    const isPopupOpen = popup && (popup.classList.contains('addpop-desktop-active') || popup.classList.contains('addpop-mobile-active'));
    
    if (!isPopupOpen) {
        // Popup not open, hide attribution
        hideGoogleAttribution(container);
        return;
    }
    
    // Show ALL attribution elements when popup is open - don't filter by text
    const selectors = [
        '.pac-logo',
        '.pac-attribution',
        'a[href*="google"]',
        'a[href*="maps"]',
        '[class*="attribution"]',
        '[class*="logo"]',
        'div[style*="powered"]',
        'span[style*="powered"]'
    ];
    
    selectors.forEach(function(selector) {
        try {
            const elements = container.querySelectorAll(selector);
            elements.forEach(function(el) {
                const text = el.textContent || el.innerText || '';
                const html = el.innerHTML || '';
                const lowerText = text.toLowerCase();
                const lowerHtml = html.toLowerCase();
                
                // Show if it contains "powered by" or "google" OR if it's an attribution element
                if (lowerText.includes('powered by') || 
                    lowerText.includes('google') || 
                    lowerHtml.includes('powered by') ||
                    lowerHtml.includes('google') ||
                    selector.includes('attribution') ||
                    selector.includes('logo')) {
                    
                    // Make sure it's visible and styled properly
                    el.style.setProperty('display', 'block', 'important');
                    el.style.setProperty('visibility', 'visible', 'important');
                    el.style.setProperty('opacity', '1', 'important');
                    el.style.setProperty('height', 'auto', 'important');
                    el.style.setProperty('width', 'auto', 'important');
                    el.style.setProperty('overflow', 'visible', 'important');
                    el.style.setProperty('font-size', '11px', 'important');
                    el.style.setProperty('line-height', 'normal', 'important');
                    el.style.setProperty('padding', '8px 12px', 'important');
                    el.style.setProperty('margin', '0', 'important');
                    el.style.setProperty('color', '#70757a', 'important');
                    el.style.setProperty('text-align', 'left', 'important');
                    el.style.setProperty('border-top', 'none', 'important');
                    el.removeAttribute('aria-hidden');
                    el.classList.add('pac-attribution-visible');
                    
                    console.log("Showing attribution element:", selector, text.substring(0, 50));
                }
            });
        } catch (e) {
            console.log("Error showing attribution:", e);
        }
    });
    
    // Also check for any text nodes containing "powered by Google"
    const walker = document.createTreeWalker(
        container,
        NodeFilter.SHOW_TEXT,
        null,
        false
    );
    
    let node;
    while (node = walker.nextNode()) {
        if (node.textContent && node.textContent.toLowerCase().includes('powered by')) {
            const parent = node.parentElement;
            if (parent && !parent.classList.contains('pac-item') && !parent.classList.contains('pac-item-query')) {
                parent.style.setProperty('display', 'block', 'important');
                parent.style.setProperty('visibility', 'visible', 'important');
                parent.style.setProperty('opacity', '1', 'important');
                parent.style.setProperty('font-size', '11px', 'important');
                parent.style.setProperty('color', '#70757a', 'important');
                parent.style.setProperty('padding', '8px 12px', 'important');
                parent.classList.add('pac-attribution-visible');
            }
        }
    }
    
    console.log("Google attribution shown (popup open)");
}

// Function to hide Google attribution when popup is closed
function hideGoogleAttribution(container) {
    if (!container) return;
    
    // Check if this is within location popup
    const isInPopup = container.closest('.addpop-popup') !== null;
    
    // Hide common Google attribution elements
    const selectors = [
        '.pac-logo',
        '.pac-attribution',
        'a[href*="google"]',
        'a[href*="maps"]',
        '[class*="attribution"]',
        '[class*="logo"]',
        '[id*="attribution"]',
        '[id*="logo"]',
        'hr',
        '.pac-separator',
        'div[style*="powered"]',
        'span[style*="powered"]'
    ];
    
    selectors.forEach(function(selector) {
        try {
            const elements = container.querySelectorAll(selector);
            elements.forEach(function(el) {
                // Check if element contains "powered by" or "google" text
                const text = el.textContent || el.innerText || '';
                const html = el.innerHTML || '';
                const lowerText = text.toLowerCase();
                const lowerHtml = html.toLowerCase();
                
                if (lowerText.includes('powered by') || 
                    lowerText.includes('google') || 
                    lowerHtml.includes('powered by') ||
                    lowerHtml.includes('google')) {
                    
                    // More aggressive hiding for location popup
                    if (isInPopup) {
                        el.style.display = 'none';
                        el.style.visibility = 'hidden';
                        el.style.opacity = '0';
                        el.style.height = '0';
                        el.style.width = '0';
                        el.style.overflow = 'hidden';
                        el.style.fontSize = '0';
                        el.style.lineHeight = '0';
                        el.style.padding = '0';
                        el.style.margin = '0';
                        el.style.border = 'none';
                        el.style.position = 'absolute';
                        el.style.left = '-9999px';
                        el.style.top = '-9999px';
                        el.style.zIndex = '-1';
                        el.setAttribute('aria-hidden', 'true');
                        el.remove();
                    } else {
                        el.style.display = 'none';
                        el.style.visibility = 'hidden';
                        el.style.opacity = '0';
                        el.style.height = '0';
                        el.style.width = '0';
                        el.style.overflow = 'hidden';
                        el.style.fontSize = '0';
                        el.style.lineHeight = '0';
                        el.style.padding = '0';
                        el.style.margin = '0';
                        el.style.border = 'none';
                    }
                }
            });
        } catch (e) {
            console.log("Error hiding attribution:", e);
        }
    });
    
    // Also hide any direct text nodes containing "powered by Google"
    const walker = document.createTreeWalker(
        container,
        NodeFilter.SHOW_TEXT,
        null,
        false
    );
    
    let node;
    while (node = walker.nextNode()) {
        if (node.textContent && node.textContent.toLowerCase().includes('powered by')) {
            const parent = node.parentElement;
            if (parent && !parent.classList.contains('pac-item') && !parent.classList.contains('pac-item-query')) {
                if (isInPopup) {
                    parent.remove();
                } else {
                    parent.style.display = 'none';
                    parent.style.visibility = 'hidden';
                }
            }
        }
    }
    
    // Additional check: Hide any element that might contain attribution text
    if (isInPopup) {
        const allElements = container.querySelectorAll('*');
        allElements.forEach(function(el) {
            const text = el.textContent || el.innerText || '';
            const lowerText = text.toLowerCase().trim();
            
            // Hide if it's just "powered by Google" or similar attribution text
            if ((lowerText === 'powered by google' || 
                 lowerText.startsWith('powered by') ||
                 lowerText.includes('powered by google')) &&
                !el.classList.contains('pac-item') &&
                !el.classList.contains('pac-item-query')) {
                el.style.display = 'none';
                el.style.visibility = 'hidden';
                el.style.opacity = '0';
                el.style.height = '0';
                el.style.width = '0';
                el.style.overflow = 'hidden';
                el.style.fontSize = '0';
                el.style.lineHeight = '0';
                el.style.padding = '0';
                el.style.margin = '0';
                el.remove();
            }
        });
    }
    
    console.log("Google attribution hidden", isInPopup ? "(in location popup)" : "");
}

// Set up MutationObserver to watch for pac-container changes
function setupPacContainerObserver() {
    // Watch for pac-container being added to DOM
    let observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        if (node.classList && node.classList.contains('pac-container')) {
                            console.log("Pac-container detected via MutationObserver");
                            // Check if popup is open before showing
                            const popup = document.getElementById("addpopPopup");
                            const isPopupOpen = popup && (popup.classList.contains('addpop-desktop-active') || popup.classList.contains('addpop-mobile-active'));
                            
                            if (isPopupOpen) {
                                setTimeout(function () {
                                    ensurePacContainerVisible();
                                    if (typeof showGoogleAttribution === 'function') {
                                        showGoogleAttribution(node);
                                    }
                                }, 0);
                                setTimeout(function () {
                                    ensurePacContainerVisible();
                                }, 50);
                                console.log("Pac-container detected; visibility synced via ensurePacContainerVisible");
                            } else {
                                node.classList.remove('pac-container-visible');
                                node.style.display = 'none';
                                node.style.visibility = 'hidden';
                                node.style.opacity = '0';
                            }
                        }
                        // Also check children
                        let pacContainers = node.querySelectorAll ? node.querySelectorAll('.pac-container') : [];
                        pacContainers.forEach(function(container) {
                            const popup = document.getElementById("addpopPopup");
                            const isPopupOpen = popup && (popup.classList.contains('addpop-desktop-active') || popup.classList.contains('addpop-mobile-active'));
                            
                            if (isPopupOpen) {
                                setTimeout(function () {
                                    ensurePacContainerVisible();
                                    if (typeof showGoogleAttribution === 'function') {
                                        showGoogleAttribution(container);
                                    }
                                }, 0);
                                setTimeout(function () {
                                    ensurePacContainerVisible();
                                }, 50);
                            } else {
                                container.classList.remove('pac-container-visible');
                                container.style.display = 'none';
                                container.style.visibility = 'hidden';
                                container.style.opacity = '0';
                            }
                        });
                    }
                    
                    // Also watch for pac-items being added directly
                    if (node.classList && node.classList.contains('pac-item')) {
                        let icon = node.querySelector('.pac-icon, img');
                        if (!icon) {
                            icon = document.createElement('img');
                            icon.src = window.MARKER_IMAGE_URL || '/loc.png';
                            icon.className = 'pac-icon';
                            icon.alt = 'Location';
                            node.insertBefore(icon, node.firstChild);
                        }
                        icon.style.setProperty('display', 'inline-block', 'important');
                        icon.style.width = '24px';
                        icon.style.height = '24px';
                        icon.style.marginRight = '12px';
                        icon.style.verticalAlign = 'middle';
                        icon.style.opacity = '1';
                        icon.style.objectFit = 'contain';
                        icon.style.flexShrink = '0';
                    }
                    
                    // Also check for pac-items inside added nodes
                    const pacItems = node.querySelectorAll ? node.querySelectorAll('.pac-item') : [];
                    pacItems.forEach(function(item) {
                        let icon = item.querySelector('.pac-icon, img');
                        if (!icon) {
                            icon = document.createElement('img');
                            icon.src = window.MARKER_IMAGE_URL || '/loc.png';
                            icon.className = 'pac-icon';
                            icon.alt = 'Location';
                            item.insertBefore(icon, item.firstChild);
                        }
                        icon.style.setProperty('display', 'inline-block', 'important');
                        icon.style.width = '24px';
                        icon.style.height = '24px';
                        icon.style.marginRight = '12px';
                        icon.style.verticalAlign = 'middle';
                        icon.style.opacity = '1';
                        icon.style.objectFit = 'contain';
                        icon.style.flexShrink = '0';
                    });
                });
            }
        });
    });
    
    // Observe document body for changes
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Store interval ID for cleanup - only run when user is actively searching
    let periodicCheckInterval = null;
    let lastInputTime = 0;
    
    // Function to start periodic check only when user is typing
    function startPeriodicCheck() {
        // Clear existing interval if any
        if (periodicCheckInterval) {
            clearInterval(periodicCheckInterval);
        }
        
        // Update last input time
        lastInputTime = Date.now();
        
        // Only run periodic check when popup is open and user is typing
        periodicCheckInterval = setInterval(function() {
            const popup = document.getElementById("addpopPopup");
            const isPopupOpen = popup && (popup.classList.contains('addpop-desktop-active') || popup.classList.contains('addpop-mobile-active'));
            
            // Stop checking if popup is closed
            if (!isPopupOpen) {
                clearInterval(periodicCheckInterval);
                periodicCheckInterval = null;
                return;
            }
            
            // Stop checking if user hasn't typed in last 3 seconds
            const timeSinceLastInput = Date.now() - lastInputTime;
            if (timeSinceLastInput > 3000) {
                clearInterval(periodicCheckInterval);
                periodicCheckInterval = null;
                return;
            }
            
            let pacContainers = document.querySelectorAll('.pac-container');
            
            if (pacContainers.length > 0) {
                pacContainers.forEach(function(container) {
                let computedStyle = window.getComputedStyle(container);
                
                if (isPopupOpen) {
                    // Popup is open - ensure container is visible
                    if (computedStyle.display === 'none' || computedStyle.visibility === 'hidden' || computedStyle.opacity === '0' || container.offsetHeight === 0) {
                        // Force show
                        container.classList.add('pac-container-visible');
                        container.style.setProperty('display', 'block', 'important');
                        container.style.setProperty('visibility', 'visible', 'important');
                        container.style.setProperty('opacity', '1', 'important');
                        container.style.setProperty('height', 'auto', 'important');
                        container.style.setProperty('max-height', '300px', 'important');
                        container.style.zIndex = '100000002';
                        container.style.backgroundColor = 'white';
                        container.style.overflowY = 'auto';
                        container.style.overflowX = 'hidden';
                        container.style.position = 'fixed';
                        
                        // Add icons to all pac-items before ensuring visibility
                        const items = container.querySelectorAll('.pac-item');
                        items.forEach(function(item) {
                            let icon = item.querySelector('.pac-icon, img');
                            if (!icon) {
                                icon = document.createElement('img');
                                icon.src = window.MARKER_IMAGE_URL || '/loc.png';
                                icon.className = 'pac-icon';
                                icon.alt = 'Location';
                                item.insertBefore(icon, item.firstChild);
                            }
                            icon.style.setProperty('display', 'inline-block', 'important');
                            icon.style.width = '24px';
                            icon.style.height = '24px';
                            icon.style.marginRight = '12px';
                            icon.style.verticalAlign = 'middle';
                            icon.style.opacity = '1';
                            icon.style.objectFit = 'contain';
                            icon.style.flexShrink = '0';
                        });
                        
                        ensurePacContainerVisible();
                        
                        // Show attribution
                        if (typeof showGoogleAttribution === 'function') {
                            showGoogleAttribution(container);
                        }
                    } else {
                        // Already visible, but ensure it stays visible
                        container.classList.add('pac-container-visible');
                        ensurePacContainerVisible();
                        
                        // Ensure items are visible and have icons
                        const items = container.querySelectorAll('.pac-item');
                        items.forEach(function(item) {
                            item.style.setProperty('display', 'flex', 'important');
                            item.style.setProperty('visibility', 'visible', 'important');
                            item.style.setProperty('opacity', '1', 'important');
                            item.style.setProperty('align-items', 'center', 'important');
                            item.style.color = '#333';
                            
                            // Add icon if missing
                            let icon = item.querySelector('.pac-icon, img');
                            if (!icon) {
                                icon = document.createElement('img');
                                icon.src = window.MARKER_IMAGE_URL || '/loc.png';
                                icon.className = 'pac-icon';
                                icon.alt = 'Location';
                                item.insertBefore(icon, item.firstChild);
                            }
                            icon.style.setProperty('display', 'inline-block', 'important');
                            icon.style.width = '24px';
                            icon.style.height = '24px';
                            icon.style.marginRight = '12px';
                            icon.style.verticalAlign = 'middle';
                            icon.style.opacity = '1';
                            icon.style.objectFit = 'contain';
                            icon.style.flexShrink = '0';
                        });
                        
                        if (typeof showGoogleAttribution === 'function') {
                            showGoogleAttribution(container);
                        }
                    }
                } else {
                    // Popup is closed - hide container
                    container.classList.remove('pac-container-visible');
                    container.style.setProperty('display', 'none', 'important');
                    container.style.setProperty('visibility', 'hidden', 'important');
                    container.style.setProperty('opacity', '0', 'important');
                    
                    if (typeof hideGoogleAttribution === 'function') {
                        hideGoogleAttribution(container);
                    }
                }
            });
        } else {
            // No pac-container found - check if we should trigger autocomplete
            if (isPopupOpen) {
                const isMobile = window.innerWidth <= 768;
                let activeInput = isMobile ? document.getElementById("autocomplete-mobile") : document.getElementById("autocomplete");
                if (activeInput && activeInput.value && activeInput.value.length > 0) {
                    // Trigger input event to force autocomplete
                    activeInput.dispatchEvent(new Event('input', { bubbles: true }));
                    activeInput.dispatchEvent(new Event('keydown', { bubbles: true }));
                    activeInput.dispatchEvent(new Event('keyup', { bubbles: true }));
                    
                    // Also try to focus the input
                    if (document.activeElement !== activeInput) {
                        activeInput.focus();
                    }
                }
            }
        }
    }, 500); // Reduced frequency to 500ms instead of 200ms
    }
    
    // Function to stop periodic check
    function stopPeriodicCheck() {
        if (periodicCheckInterval) {
            clearInterval(periodicCheckInterval);
            periodicCheckInterval = null;
        }
    }
    
    // Add event listeners to input fields to start/stop periodic check
    const inputDesktop = document.getElementById("autocomplete");
    const inputMobile = document.getElementById("autocomplete-mobile");
    
    if (inputDesktop) {
        inputDesktop.addEventListener('input', function() {
            startPeriodicCheck();
        });
        inputDesktop.addEventListener('blur', function() {
            setTimeout(stopPeriodicCheck, 2000); // Stop after 2 seconds of blur
        });
    }
    
    if (inputMobile) {
        inputMobile.addEventListener('input', function() {
            startPeriodicCheck();
        });
        inputMobile.addEventListener('blur', function() {
            setTimeout(stopPeriodicCheck, 2000); // Stop after 2 seconds of blur
        });
    }
    
    // Stop periodic check when popup closes
    const popupElement = document.getElementById("addpopPopup");
    if (popupElement) {
        // Use MutationObserver to detect when popup closes
        const popupObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    const isOpen = popupElement.classList.contains('addpop-desktop-active') || popupElement.classList.contains('addpop-mobile-active');
                    if (!isOpen) {
                        stopPeriodicCheck();
                    }
                }
            });
        });
        popupObserver.observe(popupElement, { attributes: true, attributeFilter: ['class'] });
    }
}

// Make functions globally accessible IMMEDIATELY (before Google Maps API loads)
window.initializeAutocompleteFields = initializeAutocompleteFields;
window.initAutocompleteFieldsNow = initAutocompleteFieldsNow;
window.ensurePacContainerVisible = ensurePacContainerVisible;
window.hideGoogleAttribution = hideGoogleAttribution;
window.showGoogleAttribution = showGoogleAttribution;

// Ensure initAutocomplete is available immediately
if (typeof window.initAutocomplete === 'undefined') {
    console.error("initAutocomplete not defined! Defining it now...");
    window.initAutocomplete = function() {
        console.log("Fallback initAutocomplete called");
        if (typeof initializeAutocompleteFields === 'function') {
            initializeAutocompleteFields();
        }
    };
} else {
    console.log("initAutocomplete is globally accessible");
}

// Ensure initAutocomplete is available immediately
if (typeof window.initAutocomplete === 'undefined') {
    console.error("initAutocomplete not defined!");
}
