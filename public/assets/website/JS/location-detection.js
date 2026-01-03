/**
 * Location Detection Handler
 * Handles geolocation detection and reverse geocoding
 */

// --- Detect Current Location (with reverse geocoding) ---
function detectLocation(){
    console.log("detectLocation called");
    
    // Get the button element
    const detectBtn = document.querySelector('.addpop-detect-btn');
    const originalText = detectBtn ? detectBtn.innerHTML : '';
    
    // Show loader on button
    if (detectBtn) {
        detectBtn.disabled = true;
        detectBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Detecting location...';
        detectBtn.style.opacity = '0.7';
        detectBtn.style.cursor = 'not-allowed';
    }
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            let lat = position.coords.latitude;
            let lng = position.coords.longitude;
            console.log("Location detected:", lat, lng);

            // Update button text to show reverse geocoding in progress
            if (detectBtn) {
                detectBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Searching location...';
            }

            reverseGeocode(lat, lng, false).then(() => {
                // Reset button after successful location update
                if (detectBtn) {
                    detectBtn.disabled = false;
                    detectBtn.innerHTML = originalText || 'Detect Current Location';
                    detectBtn.style.opacity = '1';
                    detectBtn.style.cursor = 'pointer';
                }
            }).catch((error) => {
                // Reset button on error
                if (detectBtn) {
                    detectBtn.disabled = false;
                    detectBtn.innerHTML = originalText || 'Detect Current Location';
                    detectBtn.style.opacity = '1';
                    detectBtn.style.cursor = 'pointer';
                }
                console.warn("Reverse geocode error:", error);
            });
        }, (err) => {
            console.warn("Geolocation error:", err);
            // Reset button on error
            if (detectBtn) {
                detectBtn.disabled = false;
                detectBtn.innerHTML = originalText || 'Detect Current Location';
                detectBtn.style.opacity = '1';
                detectBtn.style.cursor = 'pointer';
            }
            alert('Unable to detect your location. Please try again or search manually.');
        });
    } else {
        console.warn("Geolocation not supported by this browser.");
        // Reset button if geolocation not supported
        if (detectBtn) {
            detectBtn.disabled = false;
            detectBtn.innerHTML = originalText || 'Detect Current Location';
            detectBtn.style.opacity = '1';
            detectBtn.style.cursor = 'pointer';
        }
        alert('Geolocation is not supported by this browser.');
    }
}

function reverseGeocode(lat, lng, isAutoDetect = true){
    // Get API key from window variable (set in footer.blade.php)
    let googlemapkey = window.GOOGLE_MAPS_API_KEY || '';
    if (!googlemapkey) {
        console.error("Google Maps API key not found");
        return Promise.reject("API key not found");
    }
    
    let geocodeUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${googlemapkey}`;
    
    return fetch(geocodeUrl)
        .then(response => response.json())
        .then(data => {
            if (data.status === "OK" && data.results.length) {
                let result = data.results[0];
                let address = result.formatted_address;
                console.log("Reverse geocode successful:", address);
                if (typeof updateLocation === 'function') {
                    updateLocation(address, result, lat, lng, isAutoDetect);
                }
                return Promise.resolve();
            } else {
                console.warn("Unable to detect location. Try searching manually.");
                return Promise.reject("No results");
            }
        })
        .catch(error => {
            console.error("Reverse geocode error:", error);
            return Promise.reject(error);
        });
}

// Make functions globally accessible
window.detectLocation = detectLocation;
window.reverseGeocode = reverseGeocode;
