/**
 * Location Detection Handler
 * Handles geolocation detection and reverse geocoding
 */

// --- Detect Current Location (with reverse geocoding) ---
function detectLocation(){
    console.log("detectLocation called");
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
            let lat = position.coords.latitude;
            let lng = position.coords.longitude;
            console.log("Location detected:", lat, lng);

            reverseGeocode(lat, lng, false); // false = user manually clicked button
        }, (err) => {
            console.warn("Geolocation error:", err);
        });
    } else {
        console.warn("Geolocation not supported by this browser.");
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
