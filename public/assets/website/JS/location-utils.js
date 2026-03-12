/**
 * Location Utility Functions
 * Helper functions for location handling
 */

// --- Helper: shorten address for header ---
function getShortAddress(fullAddress, place = null) {
    if (place && place.address_components) {
        let components = place.address_components;
        let sublocality = components.find(c =>
            c.types.includes("sublocality") || c.types.includes("sublocality_level_1")
        );
        let neighborhood = components.find(c => c.types.includes("neighborhood"));
        let city = components.find(c => c.types.includes("locality"));
        let state = components.find(c => c.types.includes("administrative_area_level_1"));

        let area = sublocality ? sublocality.long_name : (neighborhood ? neighborhood.long_name : "");

        if (area || city || state) {
            return `${area ? area + ", " : ""}${city ? city.long_name + ", " : ""}${state ? state.long_name : ""}`;
        }
    }
    return fullAddress.length > 40 ? fullAddress.substring(0, 40) + "..." : fullAddress;
}

/** Mobile header: max 32 chars then "..", single line display */
function getShortAddressMobile(fullAddress, place = null) {
    let short = getShortAddress(fullAddress, place);
    if (short.length > 32) {
        return short.substring(0, 32) + "..";
    }
    return short;
}

// Make getShortAddress globally accessible
window.getShortAddress = getShortAddress;
window.getShortAddressMobile = getShortAddressMobile;
