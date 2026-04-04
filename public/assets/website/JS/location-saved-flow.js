/**
 * Saved location flow for header popup.
 * Keeps guest location temporary and asks to save after login.
 */
(function () {
    let transientLocation = null;
    let saveModalInstance = null;
    const GUEST_LOCATION_KEY = "guestTempLocation";
    const GUEST_LOCATION_TS_KEY = "guestTempLocationAt";
    const GUEST_LOCATION_TTL_MS = 5 * 60 * 1000;

    function qs(id) {
        return document.getElementById(id);
    }

    function isAuthenticated() {
        return !!window.IS_LOGGED_IN;
    }

    function parseAddressComponents(place) {
        if (typeof window.buildAreaLandmarkFromPlace === "function" && place) {
            const b = window.buildAreaLandmarkFromPlace(place);
            return {
                area: b.area || "",
                pincode: b.postalCode || "",
                locality: b.locality || "",
                landmark: b.landmark || "",
            };
        }
        return { area: "", pincode: "", locality: "", landmark: "" };
    }

    function rememberGuestLocation(loc) {
        localStorage.setItem(GUEST_LOCATION_KEY, JSON.stringify(loc));
        localStorage.setItem(GUEST_LOCATION_TS_KEY, String(Date.now()));
    }

    function getGuestLocation() {
        const raw = localStorage.getItem(GUEST_LOCATION_KEY);
        const ts = Number(localStorage.getItem(GUEST_LOCATION_TS_KEY) || 0);
        if (!raw || !ts || (Date.now() - ts) > GUEST_LOCATION_TTL_MS) {
            localStorage.removeItem(GUEST_LOCATION_KEY);
            localStorage.removeItem(GUEST_LOCATION_TS_KEY);
            return null;
        }
        try {
            return JSON.parse(raw);
        } catch (e) {
            return null;
        }
    }

    function bindUpdateLocationCapture() {
        if (typeof window.updateLocation !== "function") return;
        const originalUpdateLocation = window.updateLocation;
        window.updateLocation = function (fullAddress, place, lat, lng, isAutoDetect) {
            transientLocation = {
                fullAddress: fullAddress || "",
                lat: lat,
                lng: lng,
                components: parseAddressComponents(place),
            };
            rememberGuestLocation(transientLocation);
            const saveBox = qs("location-save-actions");
            if (saveBox) saveBox.style.display = (window.__locationDeliverable === false) ? "none" : "block";
            return originalUpdateLocation.apply(this, arguments);
        };
    }

    function getLocationFromStorageOrTransient() {
        if (transientLocation && transientLocation.lat && transientLocation.lng) return transientLocation;
        const guest = getGuestLocation();
        if (guest && guest.lat && guest.lng) return guest;
        const raw =
            typeof window.getPreferredSavedLocationRaw === "function"
                ? window.getPreferredSavedLocationRaw()
                : localStorage.getItem("userLocation");
        if (!raw) return null;
        try {
            const parsed = JSON.parse(raw);
            return { fullAddress: parsed.fullAddress || "", lat: parsed.lat, lng: parsed.lng, components: {} };
        } catch (e) {
            return null;
        }
    }

    function setSelectedSavedAddress(id) {
        localStorage.setItem("selectedSavedAddressId", String(id));
    }

    function selectAddressOnServer(id) {
        if (!window.ADDRESS_SELECT_URL_TEMPLATE) return Promise.resolve();
        const url = window.ADDRESS_SELECT_URL_TEMPLATE.replace(":id", encodeURIComponent(id));
        return fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
                Accept: "application/json"
            }
        }).then(() => {});
    }

    function renderSavedLocations(addresses) {
        const wrap = qs("saved-locations-list");
        if (!wrap) return;
        if (!addresses || !addresses.length) {
            wrap.innerHTML = '<div class="text-muted">No saved locations.</div>';
            return;
        }
        const selectedId = localStorage.getItem("selectedSavedAddressId");
        wrap.innerHTML = addresses.map((addr) => {
            const label = [addr.flat, addr.area, addr.landmark, addr.pincode].filter(Boolean).join(", ");
            const isChecked = !!addr.is_selected || (selectedId && String(addr.id) === String(selectedId));
            return `
                <label class="d-flex align-items-start gap-2 border rounded p-2 mb-2" style="cursor:pointer;">
                    <input type="radio" name="saved_location_radio" value="${addr.id}" ${isChecked ? "checked" : ""}>
                    <span>
                        <strong>${(addr.type || "home").toUpperCase()}</strong><br>
                        ${label}
                    </span>
                </label>
            `;
        }).join("");

        wrap.querySelectorAll('input[name="saved_location_radio"]').forEach((input) => {
            input.addEventListener("change", function () {
                const id = this.value;
                const selectedAddress = addresses.find((a) => String(a.id) === String(id));
                if (!selectedAddress) return;
                setSelectedSavedAddress(id);
                selectAddressOnServer(id);
                if (typeof window.updateLocation === "function") {
                    const fullAddress = selectedAddress.full_address || [selectedAddress.flat, selectedAddress.area, selectedAddress.landmark, selectedAddress.pincode].filter(Boolean).join(", ");
                    window.updateLocation(fullAddress, null, selectedAddress.latitude, selectedAddress.longitude, false);
                }
            });
        });
    }

    function autoSelectAddressForCurrentArea(addresses) {
        if (!isAuthenticated() || !addresses || !addresses.length) return;
        const loc = getLocationFromStorageOrTransient();
        if (!loc || !loc.lat || !loc.lng) return;

        const currentLat = Number(loc.lat);
        const currentLng = Number(loc.lng);
        const toRad = (v) => v * Math.PI / 180;
        const dist = (aLat, aLng, bLat, bLng) => {
            const R = 6371;
            const dLat = toRad(bLat - aLat);
            const dLng = toRad(bLng - aLng);
            const x = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(toRad(aLat)) * Math.cos(toRad(bLat)) * Math.sin(dLng / 2) * Math.sin(dLng / 2);
            return R * (2 * Math.atan2(Math.sqrt(x), Math.sqrt(1 - x)));
        };

        let nearest = null;
        addresses.forEach((addr) => {
            const aLat = Number(addr.latitude);
            const aLng = Number(addr.longitude);
            if (!aLat || !aLng) return;
            const km = dist(currentLat, currentLng, aLat, aLng);
            if (km <= 50 && (!nearest || km < nearest.km)) {
                nearest = { addr, km };
            }
        });

        if (nearest && !nearest.addr.is_selected) {
            setSelectedSavedAddress(nearest.addr.id);
            selectAddressOnServer(nearest.addr.id);
        }
    }

    function loadSavedLocations() {
        if (!isAuthenticated()) {
            const wrap = qs("saved-locations-list");
            if (wrap) wrap.innerHTML = '<div class="text-muted">Login to view saved locations.</div>';
            return;
        }
        fetch(window.ADDRESS_LIST_URL, { headers: { Accept: "application/json" } })
            .then((res) => res.json())
            .then((data) => {
                const addresses = Array.isArray(data) ? data : [];
                autoSelectAddressForCurrentArea(addresses);
                renderSavedLocations(addresses);
            })
            .catch(() => {
                const wrap = qs("saved-locations-list");
                if (wrap) wrap.innerHTML = '<div class="text-danger">Unable to load saved locations.</div>';
            });
    }

    function unlockPopupLocationDerivedFields() {
        ["popupArea", "popupLandmark", "popupPincode"].forEach(function (id) {
            const el = qs(id);
            if (!el) return;
            el.readOnly = false;
            el.classList.remove("address-locked");
        });
    }

    function populateSaveFormFromLocation() {
        const loc = getLocationFromStorageOrTransient();
        if (!loc) return;

        const area = qs("popupArea");
        const pincode = qs("popupPincode");
        const landmark = qs("popupLandmark");

        let fb = { area: "", landmark: "", postalCode: "" };
        const full = (loc.fullAddress || "").trim();
        if (full && typeof window.parseFormattedAddressIndian === "function") {
            fb = window.parseFormattedAddressIndian(full);
        }

        if (loc.components) {
            if (!fb.area && loc.components.area) fb.area = loc.components.area;
            if (!fb.landmark && loc.components.landmark) fb.landmark = loc.components.landmark;
            if (!fb.postalCode && loc.components.pincode) fb.postalCode = loc.components.pincode;
        }

        const selectedLocation = qs("selected-location");
        if ((!fb.area || !fb.landmark || !fb.postalCode) && selectedLocation) {
            const raw = selectedLocation.textContent.replace("📍", "").trim();
            if (raw && typeof window.parseFormattedAddressIndian === "function") {
                const fb2 = window.parseFormattedAddressIndian(raw);
                if (!fb.area && fb2.area) fb.area = fb2.area;
                if (!fb.landmark && fb2.landmark) fb.landmark = fb2.landmark;
                if (!fb.postalCode && fb2.postalCode) fb.postalCode = fb2.postalCode;
            }
        }

        if (area) area.value = fb.area || "";
        if (landmark) landmark.value = fb.landmark || "";
        if (pincode) pincode.value = fb.postalCode || "";

        if (area && fb.area) {
            area.readOnly = true;
            area.classList.add("address-locked");
            area.setAttribute("autocomplete", "off");
        }
        if (landmark && fb.landmark) {
            landmark.readOnly = true;
            landmark.classList.add("address-locked");
        }
        if (pincode && fb.postalCode) {
            pincode.readOnly = true;
            pincode.classList.add("address-locked");
        }
    }

    function maybePromptToSaveGuestLocation() {
        if (!isAuthenticated() || !window.Swal) return;
        if (sessionStorage.getItem("guestLocationPromptShown") === "1") return;
        const loc = getGuestLocation();
        if (!loc || !loc.lat || !loc.lng) return;

        sessionStorage.setItem("guestLocationPromptShown", "1");
        window.Swal.fire({
            icon: "question",
            title: "Save this location?",
            text: "Do you want to save address for your current area?",
            showCancelButton: true,
            confirmButtonText: "Save now",
            cancelButtonText: "Later"
        }).then((res) => {
            if (res.isConfirmed) {
                const openBtn = qs("openSaveLocationModalBtn");
                if (openBtn) openBtn.click();
            }
        });
    }

    function getReturnUrlFromQuery() {
        try {
            const p = new URLSearchParams(window.location.search);
            const ret = p.get("return");
            if (!ret) return null;
            const u = new URL(ret, window.location.origin);
            if (u.origin !== window.location.origin) return null;
            return u.pathname + u.search + u.hash;
        } catch (e) {
            return null;
        }
    }

    function handleRequireDeliveryAddressQuery() {
        try {
            const p = new URLSearchParams(window.location.search);
            if (p.get("requireDeliveryAddress") !== "1" || !isAuthenticated()) return;

            const openSave = function () {
                const loc = getLocationFromStorageOrTransient();
                if (!loc || !loc.lat || !loc.lng) {
                    if (typeof window.toggleAddpop === "function") {
                        window.toggleAddpop({
                            preventDefault: function () {},
                            stopPropagation: function () {},
                        });
                    }
                    if (window.Swal) {
                        window.Swal.fire({
                            icon: "info",
                            title: "Location required",
                            text: "Pehle location select ya detect karein, phir address save karein.",
                            confirmButtonColor: "#E94412",
                        });
                    }
                    return;
                }
                populateSaveFormFromLocation();
                const openBtn = qs("openSaveLocationModalBtn");
                if (openBtn) {
                    openBtn.click();
                } else if (typeof window.toggleAddpop === "function") {
                    window.toggleAddpop({
                        preventDefault: function () {},
                        stopPropagation: function () {},
                    });
                }
            };

            setTimeout(openSave, 500);
        } catch (e) {}
    }

    function bindSaveModalActions() {
        const openBtn = qs("openSaveLocationModalBtn");
        const form = qs("popupLocationAddressForm");
        const homeBtn = qs("popupHomeBtn");
        const workBtn = qs("popupWorkBtn");
        const typeInput = qs("popupAddressType");
        if (!openBtn || !form || !homeBtn || !workBtn || !typeInput) return;

        openBtn.addEventListener("click", function () {
            if (!isAuthenticated()) {
                window.location.href = window.LOGIN_PAGE_URL || "/login-by-phone";
                return;
            }
            populateSaveFormFromLocation();
            if (!saveModalInstance) {
                const el = qs("saveLocationModal");
                if (!el) return;
                saveModalInstance = new bootstrap.Modal(el);
            }
            saveModalInstance.show();
        });

        homeBtn.addEventListener("click", function () {
            typeInput.value = "home";
            homeBtn.classList.add("active");
            workBtn.classList.remove("active");
        });
        workBtn.addEventListener("click", function () {
            typeInput.value = "work";
            workBtn.classList.add("active");
            homeBtn.classList.remove("active");
        });

        form.addEventListener("submit", function (e) {
            e.preventDefault();
            const loc = getLocationFromStorageOrTransient();
            if (!loc || !loc.lat || !loc.lng) {
                alert("Please detect/select location first.");
                return;
            }

            const fd = new FormData(form);
            fd.set("latitude", loc.lat);
            fd.set("longitude", loc.lng);
            fd.set("is_selected", "1");

            fetch(window.ADDRESS_STORE_URL, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
                    Accept: "application/json",
                },
                body: fd,
            })
                .then((res) => res.json())
                .then((data) => {
                    if (!data || !data.success) {
                        alert((data && data.message) ? data.message : "Unable to save location.");
                        return;
                    }
                    if (data.address && data.address.id) {
                        setSelectedSavedAddress(data.address.id);
                    }
                    if (saveModalInstance) saveModalInstance.hide();
                    form.reset();
                    unlockPopupLocationDerivedFields();
                    localStorage.removeItem(GUEST_LOCATION_KEY);
                    localStorage.removeItem(GUEST_LOCATION_TS_KEY);
                    loadSavedLocations();

                    const back = getReturnUrlFromQuery();
                    if (back) {
                        window.location.href = back;
                    }
                })
                .catch(() => alert("Network error while saving location."));
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        bindUpdateLocationCapture();
        bindSaveModalActions();
        loadSavedLocations();
        handleRequireDeliveryAddressQuery();
        maybePromptToSaveGuestLocation();
        window.addEventListener('location-deliverability-updated', function (e) {
            const saveBox = qs("location-save-actions");
            if (!saveBox) return;
            saveBox.style.display = (e.detail && e.detail.deliverable === false) ? "none" : "block";
        });
        document.querySelectorAll(".location-box, .location-boxs").forEach((el) => {
            el.addEventListener("click", function () {
                setTimeout(loadSavedLocations, 150);
            });
        });
    });

    window.loadSavedLocationsInHeaderPopup = loadSavedLocations;
})();
