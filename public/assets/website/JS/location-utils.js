/**
 * Location Utility Functions
 * Helper functions for location handling
 */
const GUEST_SESSION_LOCATION_TS_KEY = "guestSessionLocationAt";
const GUEST_SESSION_LOCATION_TTL_MS = 5 * 60 * 1000; // 5 minutes
/** ~28 m — Android / GPS / string coords often differ slightly; must match commit + read paths */
const GUEST_COORD_MATCH_EPS = 0.00025;

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

/**
 * Parse Indian-style formatted address when components are incomplete.
 * Example: "1, Basti Khas, Narayanpur, Basti, Uttar Pradesh 272001, India"
 * -> area: Basti Khas, landmark: Narayanpur, Basti, Uttar Pradesh, India
 */
function parseFormattedAddressIndian(formattedAddress) {
    const empty = { area: "", landmark: "", postalCode: "" };
    if (!formattedAddress || typeof formattedAddress !== "string") {
        return empty;
    }
    const pinMatch = formattedAddress.match(/\b(\d{6})\b/);
    const postalCode = pinMatch ? pinMatch[1] : "";
    let s = formattedAddress.replace(/\b\d{6}\b\s*,?/g, "").replace(/,\s*,/g, ",").replace(/\s+/g, " ").trim();
    const parts = s.split(",").map((p) => p.trim()).filter(Boolean);
    if (parts.length === 0) {
        return { area: "", landmark: "", postalCode };
    }
    let start = 0;
    if (parts.length >= 2) {
        const first = parts[0];
        if (/^\d+[A-Za-z\-]?\s*$/.test(first) || (/^\d+/.test(first) && first.length <= 6)) {
            start = 1;
        }
    }
    if (start >= parts.length) {
        return { area: "", landmark: "", postalCode };
    }
    const area = parts[start] || "";
    const landmark = parts.slice(start + 1).join(", ");
    return { area, landmark, postalCode };
}

/**
 * Area = sublocality / neighborhood first (e.g. Basti Khas), not the main locality.
 * Landmark = locality + district + state + country (read-only in forms); pincode separate.
 */
function buildAreaLandmarkFromPlace(place) {
    const empty = {
        area: "",
        landmark: "",
        locality: "",
        postalCode: "",
        streetNumber: "",
        route: "",
    };
    if (!place) {
        return empty;
    }
    const c = place.address_components || [];
    const pickFirst = (types) => {
        for (let i = 0; i < types.length; i++) {
            const comp = c.find((x) => x.types && x.types.includes(types[i]));
            if (comp) {
                return comp.long_name;
            }
        }
        return "";
    };

    const streetNumber = pickFirst(["street_number"]);
    const route = pickFirst(["route"]);
    const subloc = pickFirst(["sublocality_level_1", "sublocality", "neighborhood"]);
    const locality = pickFirst(["locality"]);
    const admin2 = pickFirst(["administrative_area_level_2"]);
    const admin1 = pickFirst(["administrative_area_level_1"]);
    const country = pickFirst(["country"]);
    let postalCode = pickFirst(["postal_code"]);

    let area = subloc || "";
    if (!area && locality) {
        area = locality;
    }
    if (!area) {
        area = admin2 || "";
    }

    const landmarkParts = [];
    const pushUnique = (v) => {
        if (!v || v === area) {
            return;
        }
        if (landmarkParts.indexOf(v) !== -1) {
            return;
        }
        landmarkParts.push(v);
    };

    if (subloc && area === subloc) {
        pushUnique(locality);
        pushUnique(admin2);
    } else if (!subloc && area === locality) {
        pushUnique(admin2);
    } else {
        pushUnique(locality);
        pushUnique(admin2);
    }
    pushUnique(admin1);
    pushUnique(country);

    let landmark = landmarkParts.join(", ");

    const formatted = place.formatted_address || "";
    if (formatted) {
        const fb = parseFormattedAddressIndian(formatted);
        if (fb.postalCode) {
            postalCode = fb.postalCode;
        }
        if (!area || !landmark) {
            if (!area && fb.area) {
                area = fb.area;
            }
            if (!landmark && fb.landmark) {
                landmark = fb.landmark;
            }
        }
    }

    return {
        area,
        landmark,
        locality: locality || "",
        postalCode,
        streetNumber,
        route,
    };
}

/** Wipe saved browser location (call on customer logout). */
function clearKwikllyLocationLocalStorage() {
    [
        "userLocation",
        "guestSessionLocation",
        "guestTempLocation",
        "guestTempLocationAt",
        "selectedSavedAddressId",
        GUEST_SESSION_LOCATION_TS_KEY,
    ].forEach(function (key) {
        try {
            localStorage.removeItem(key);
        } catch (e) {}
    });
    [
        "guestLocationPromptShown",
        "lastLocationErrorKey",
        "lastLocationErrorAt",
        "guestSessionLocation",
        "guestSessionLocationAt",
    ].forEach(function (key) {
        try {
            sessionStorage.removeItem(key);
        } catch (e) {}
    });
    try {
        window.__locationDeliverable = undefined;
    } catch (e) {}
}

function clearExpiredGuestLocationData() {
    [
        "userLocation",
        "guestSessionLocation",
        GUEST_SESSION_LOCATION_TS_KEY,
    ].forEach(function (key) {
        try {
            localStorage.removeItem(key);
        } catch (e) {}
        try {
            sessionStorage.removeItem(key);
        } catch (e) {}
    });
}

/**
 * Same short label on desktop + mobile headers (avoids different truncation per viewport).
 */
function applyUnifiedHeaderLocationText(fullAddress, place) {
    if (!fullAddress) {
        return;
    }
    const desk = document.querySelector(".location-text");
    const mob = document.querySelector(".locations-text");
    const sel = document.getElementById("selected-location");
    const display =
        typeof getShortAddressMobile === "function"
            ? getShortAddressMobile(fullAddress, place)
            : typeof getShortAddress === "function"
              ? getShortAddress(fullAddress, place)
              : fullAddress;
    if (desk) {
        desk.innerHTML = display;
    }
    if (mob) {
        mob.innerHTML = display;
    }
    if (sel) {
        sel.innerText = "\uD83D\uDCCD " + fullAddress;
    }
}

/**
 * Guest only: persist lat/lng after master+vendor check passes (5 min TTL).
 * Mirrors to sessionStorage so some Android / WebView cases still retain coords if localStorage fails.
 */
function commitGuestServiceableLocation(locationJsonString) {
    if (window.IS_LOGGED_IN) {
        return;
    }
    const now = String(Date.now());
    try {
        localStorage.setItem("guestSessionLocation", locationJsonString);
        localStorage.setItem(GUEST_SESSION_LOCATION_TS_KEY, now);
        localStorage.setItem("userLocation", locationJsonString);
    } catch (e) {}
    try {
        sessionStorage.setItem("guestSessionLocation", locationJsonString);
        sessionStorage.setItem(GUEST_SESSION_LOCATION_TS_KEY, now);
    } catch (e2) {}
}

/** After login, promote last guest serviceable blob to userLocation and drop guest keys. */
function promoteGuestLocationAfterLogin() {
    if (!window.IS_LOGGED_IN) {
        return;
    }
    try {
        const g = localStorage.getItem("guestSessionLocation");
        const ts = Number(localStorage.getItem(GUEST_SESSION_LOCATION_TS_KEY) || 0);
        const fresh = ts > 0 && Date.now() - ts <= GUEST_SESSION_LOCATION_TTL_MS;
        if (g && fresh) {
            localStorage.setItem("userLocation", g);
        }
        localStorage.removeItem("guestSessionLocation");
        localStorage.removeItem(GUEST_SESSION_LOCATION_TS_KEY);
        sessionStorage.removeItem("guestSessionLocation");
        sessionStorage.removeItem("guestSessionLocationAt");
    } catch (e) {}
}

/**
 * Guests: guestSessionLocation + TTL from localStorage, or sessionStorage mirror if local is empty.
 * Logged-in: userLocation.
 */
function getPreferredSavedLocationRaw() {
    try {
        if (!window.IS_LOGGED_IN) {
            let ts = 0;
            let guestRaw = null;
            try {
                guestRaw = localStorage.getItem("guestSessionLocation");
                ts = Number(localStorage.getItem(GUEST_SESSION_LOCATION_TS_KEY) || 0);
            } catch (e) {}
            if (!guestRaw || !ts) {
                try {
                    guestRaw = sessionStorage.getItem("guestSessionLocation");
                    ts = Number(sessionStorage.getItem(GUEST_SESSION_LOCATION_TS_KEY) || 0);
                } catch (e2) {}
            }
            const isFresh = ts > 0 && Date.now() - ts <= GUEST_SESSION_LOCATION_TTL_MS;
            if (!isFresh) {
                clearExpiredGuestLocationData();
                return null;
            }
            if (guestRaw) {
                return guestRaw;
            }
            return null;
        }
    } catch (e) {}
    try {
        return localStorage.getItem("userLocation");
    } catch (e) {
        return null;
    }
}

function getPreferredSavedLocation() {
    const raw = getPreferredSavedLocationRaw();
    if (!raw) return null;
    try {
        return JSON.parse(raw);
    } catch (e) {
        return null;
    }
}

// Make getShortAddress globally accessible
window.getShortAddress = getShortAddress;
window.getShortAddressMobile = getShortAddressMobile;
window.parseFormattedAddressIndian = parseFormattedAddressIndian;
window.buildAreaLandmarkFromPlace = buildAreaLandmarkFromPlace;
window.clearKwikllyLocationLocalStorage = clearKwikllyLocationLocalStorage;
window.getPreferredSavedLocationRaw = getPreferredSavedLocationRaw;
window.getPreferredSavedLocation = getPreferredSavedLocation;
window.applyUnifiedHeaderLocationText = applyUnifiedHeaderLocationText;
window.commitGuestServiceableLocation = commitGuestServiceableLocation;
window.promoteGuestLocationAfterLogin = promoteGuestLocationAfterLogin;
window.GUEST_COORD_MATCH_EPS = GUEST_COORD_MATCH_EPS;
