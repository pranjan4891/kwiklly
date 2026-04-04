/**
 * Location Products Handler
 * Handles product reloading based on location
 */

// --- Update Location Everywhere + Reload Products ---
function updateLocation(fullAddress, place = null, lat = null, lng = null, isAutoDetect = false){
    let shortAddress = typeof getShortAddress === 'function' ? getShortAddress(fullAddress, place) : fullAddress;

    let oldLocation = typeof getPreferredSavedLocationRaw === "function"
        ? getPreferredSavedLocationRaw()
        : localStorage.getItem("userLocation");
    let newLocation = JSON.stringify({
        fullAddress: fullAddress,
        shortAddress: shortAddress,
        lat: lat,
        lng: lng
    });

    const prevCandidate = window.__lastLocationCandidate;
    window.__lastLocationCandidate = {
        fullAddress: fullAddress,
        shortAddress: shortAddress,
        lat: lat,
        lng: lng,
        place: place || null
    };

    if (window.IS_LOGGED_IN) {
        localStorage.setItem("userLocation", newLocation);
    } else {
        try {
            sessionStorage.removeItem("guestSessionLocation");
            sessionStorage.removeItem("guestSessionLocationAt");
        } catch (e) {}
    }

    // Hidden inputs
    if (document.getElementById("latitude")) document.getElementById("latitude").value = lat || "";
    if (document.getElementById("longitude")) document.getElementById("longitude").value = lng || "";
    // Set search form hidden fields
    if (document.getElementById("search-latitude")) document.getElementById("search-latitude").value = lat || "";
    if (document.getElementById("search-longitude")) document.getElementById("search-longitude").value = lng || "";
    if (document.getElementById("mobile-search-latitude")) document.getElementById("mobile-search-latitude").value = lat || "";
    if (document.getElementById("mobile-search-longitude")) document.getElementById("mobile-search-longitude").value = lng || "";

    if (typeof applyUnifiedHeaderLocationText === "function") {
        applyUnifiedHeaderLocationText(fullAddress, place);
    } else {
        let headerLocationDesktop = document.querySelector(".location-text");
        let headerLocationMobile = document.querySelector(".locations-text");
        let selectedLocationEl = document.getElementById("selected-location");
        if (selectedLocationEl) {
            selectedLocationEl.innerText = "\uD83D\uDCCD " + fullAddress;
        }
        if (headerLocationDesktop) headerLocationDesktop.innerHTML = shortAddress;
        if (headerLocationMobile) {
            let mobileText = typeof getShortAddressMobile === 'function' ? getShortAddressMobile(fullAddress, place) : (shortAddress.length > 32 ? shortAddress.substring(0, 32) + ".." : shortAddress);
            headerLocationMobile.innerHTML = mobileText;
        }
    }

    if (typeof closeAddpop === 'function') {
        closeAddpop();
    }

    let locationChangedForRedirect = false;
    if (!isAutoDetect) {
        if (window.IS_LOGGED_IN) {
            locationChangedForRedirect = oldLocation !== newLocation;
        } else {
            const eps = 1e-6;
            const nla = Number(lat);
            const nlng = Number(lng);
            if (!prevCandidate || !Number.isFinite(nla) || !Number.isFinite(nlng)) {
                locationChangedForRedirect = true;
            } else {
                const pla = Number(prevCandidate.lat);
                const plng = Number(prevCandidate.lng);
                locationChangedForRedirect =
                    !Number.isFinite(pla) ||
                    !Number.isFinite(plng) ||
                    Math.abs(pla - nla) > eps ||
                    Math.abs(plng - nlng) > eps;
            }
        }
    }

    // 👉 If location changed manually (not auto-detect/init), clear cart + redirect home with lat/lng
    if (locationChangedForRedirect) {
        console.log("Location changed, redirecting with new location:", lat, lng);
        if (typeof $ !== 'undefined') {
            $.post(window.CART_CLEAR_URL || '/cart/clear', {
                _token: document.querySelector('meta[name="csrf-token"]')?.content || ''
            }, function () {
                // Reset cart count/UI
                $('.cart-count').text(0);
                if (typeof loadSideCartItems === "function") loadSideCartItems({});
                if (typeof currentCart !== "undefined") currentCart = {};

                // Redirect to home WITH location parameters
                let homeUrl = window.HOME_URL || '/';
                if (lat && lng) {
                    // Remove existing query params and add new ones
                    homeUrl = homeUrl.split('?')[0];
                    homeUrl += `?latitude=${encodeURIComponent(lat)}&longitude=${encodeURIComponent(lng)}`;
                    console.log("Redirecting to:", homeUrl);
                }
                window.location.href = homeUrl;
            });
        } else {
            // Fallback if jQuery not available
            let homeUrl = window.HOME_URL || '/';
            if (lat && lng) {
                homeUrl = homeUrl.split('?')[0];
                homeUrl += `?latitude=${encodeURIComponent(lat)}&longitude=${encodeURIComponent(lng)}`;
            }
            window.location.href = homeUrl;
        }
        return; // prevent further execution (avoid AJAX reload here)
    }

    // --- Always reload products if coords exist (for first load, same location, or auto-detect) ---
    if (lat && lng && typeof $ !== 'undefined') {
        console.log("Reloading products for location:", lat, lng);
        $.ajax({
            url: window.LOCATION_PRODUCTS_URL || '/location/products',
            type: "POST",
            data: {
                _token: document.querySelector('meta[name="csrf-token"]')?.content || '',
                latitude: lat,
                longitude: lng
            },
            success: function(res){
                console.log("Products reloaded successfully", res);
                
                // Update each section if it exists and has content
                if ($('#trending-products-section').length && res.trending_html) {
                    $('#trending-products-section').html(res.trending_html);
                }
                if ($('#best-offers-products-section').length && res.best_offers_html) {
                    $('#best-offers-products-section').html(res.best_offers_html);
                }
                if ($('#sponsors-products-section').length && res.sponsors_html) {
                    $('#sponsors-products-section').html(res.sponsors_html);
                }
                if ($('#stores-section').length && res.stores_html) {
                    $('#stores-section').html(res.stores_html);
                }
                if ($('#categories-section').length && res.categories_html) {
                    $('#categories-section').html(res.categories_html);
                }
                
                // Update homepage categories slider (mobile view)
                if ($('#category-container').length && res.homepage_categories_html) {
                    $('#category-container').html(res.homepage_categories_html);
                }
                
                // Update desktop categories carousel
                if ($('.new-cate-owl-carousel').length && res.homepage_categories_html) {
                    // Destroy existing carousel first
                    $('.new-cate-owl-carousel').trigger('destroy.owl.carousel');
                    $('.new-cate-owl-carousel').removeClass('owl-loaded owl-hidden');
                    $('.new-cate-owl-carousel').find('.owl-stage-outer, .owl-stage, .owl-item').remove();
                    
                    // Parse categories HTML and rebuild carousel structure
                    let tempDiv = $('<div>').html(res.homepage_categories_html);
                    let categoryItems = tempDiv.find('.new-cate-item-wrap');
                    
                    if (categoryItems.length > 0) {
                        // Remove duplicates by checking category links/slugs
                        let seenCategories = new Set();
                        let uniqueCategoryItems = [];
                        
                        categoryItems.each(function() {
                            let categoryLink = $(this).find('a').attr('href');
                            if (categoryLink && !seenCategories.has(categoryLink)) {
                                seenCategories.add(categoryLink);
                                uniqueCategoryItems.push(this);
                            }
                        });
                        
                        let carouselHtml = '';
                        // Group unique categories in chunks of 2 for carousel
                        for (let i = 0; i < uniqueCategoryItems.length; i += 2) {
                            carouselHtml += '<div class="new-cate-item p-0">';
                            // Add first category
                            carouselHtml += uniqueCategoryItems[i].outerHTML;
                            if (i + 1 < uniqueCategoryItems.length) {
                                carouselHtml += uniqueCategoryItems[i + 1].outerHTML;
                            }
                            carouselHtml += '</div>';
                        }
                        $('.new-cate-owl-carousel').html(carouselHtml);
                        
                        // Reinitialize carousel after HTML update
                        $('.new-cate-owl-carousel').owlCarousel({
                            loop: true,
                            margin: 10,
                            nav: true,
                            dots: false,
                            navText: [
                                "<span class='cate-custom-prev'><i class='fa fa-chevron-left'></i></span>",
                                "<span class='cate-custom-next'><i class='fa fa-chevron-right'></i></span>"
                            ],
                            responsive: {
                                320: { items: 2.4 },
                                600: { items: 4 },
                                1000: { items: 4 }
                            }
                        });
                    }
                }
                
                // Update footer categories
                if ($('#footer-categories-container').length && res.footer_categories_html) {
                    console.log("Updating footer categories from location change");
                    $('#footer-categories-container').html(res.footer_categories_html);
                    
                    // Reinitialize shopmore dropdowns
                    $('.shopmore, .shopmore-mobile').off('change').on('change', function() {
                        if (this.value && typeof redirectWithLocation === 'function') {
                            redirectWithLocation(this.value);
                        } else if (this.value) {
                            window.location.href = this.value;
                        }
                    });
                } else {
                    console.warn("Footer categories container not found or no HTML provided");
                    console.log("Container exists:", $('#footer-categories-container').length);
                    console.log("HTML provided:", !!res.footer_categories_html);
                }

                if (typeof initializeOwlCarousels === "function") {
                    initializeOwlCarousels();
                }
                
                // Check if location is in master area and show modal if not
                checkLocationInMasterArea(lat, lng);
            },
            error: function(xhr, status, error) {
                console.error("Error reloading products:", error);
                console.error("Response:", xhr.responseText);
                if (lat && lng && typeof checkLocationInMasterArea === "function") {
                    checkLocationInMasterArea(lat, lng);
                }
            }
        });
    } else if (lat && lng && typeof checkLocationInMasterArea === "function") {
        checkLocationInMasterArea(lat, lng);
    }
}

function applyLocationMasterCheckResponse(response, lat, lng) {
    console.log("Location check response:", response);
    window.__locationDeliverable = !!(
        response &&
        response.is_valid_for_selection !== false &&
        response.is_in_master_area !== false &&
        response.is_in_vendor_area !== false
    );
    window.dispatchEvent(
        new CustomEvent("location-deliverability-updated", {
            detail: { deliverable: window.__locationDeliverable, lat: lat, lng: lng },
        })
    );

    const latN = Number(lat);
    const lngN = Number(lng);
    const eps =
        typeof window.GUEST_COORD_MATCH_EPS === "number" ? window.GUEST_COORD_MATCH_EPS : 0.00025;
    function coordsMatchCheck(a, b, c, d) {
        return (
            Math.abs(Number(a) - Number(c)) < eps && Math.abs(Number(b) - Number(d)) < eps
        );
    }

    let pertainsToGuest = false;
    const cand = window.__lastLocationCandidate;
    if (cand && coordsMatchCheck(cand.lat, cand.lng, latN, lngN)) {
        pertainsToGuest = true;
    }
    if (!pertainsToGuest) {
        try {
            let raw = null;
            try {
                raw = localStorage.getItem("guestSessionLocation");
            } catch (e) {}
            if (!raw) {
                try {
                    raw = sessionStorage.getItem("guestSessionLocation");
                } catch (e2) {}
            }
            if (raw) {
                const p = JSON.parse(raw);
                if (p && coordsMatchCheck(p.lat, p.lng, latN, lngN)) {
                    pertainsToGuest = true;
                }
            }
        } catch (e) {}
    }

    if (!window.__locationDeliverable && !window.IS_LOGGED_IN && pertainsToGuest) {
        if (typeof clearExpiredGuestLocationData === "function") {
            clearExpiredGuestLocationData();
        }
    }

    if (window.__locationDeliverable && !window.IS_LOGGED_IN) {
        let jsonToCommit = null;
        if (cand && coordsMatchCheck(cand.lat, cand.lng, latN, lngN)) {
            const sa =
                cand.shortAddress ||
                (typeof getShortAddress === "function"
                    ? getShortAddress(cand.fullAddress, cand.place)
                    : cand.fullAddress);
            jsonToCommit = JSON.stringify({
                fullAddress: cand.fullAddress,
                shortAddress: sa,
                lat: cand.lat,
                lng: cand.lng,
            });
        } else {
            try {
                let raw = null;
                try {
                    raw = localStorage.getItem("guestSessionLocation");
                } catch (e) {}
                if (!raw) {
                    try {
                        raw = sessionStorage.getItem("guestSessionLocation");
                    } catch (e2) {}
                }
                if (raw) {
                    const p = JSON.parse(raw);
                    if (p && coordsMatchCheck(p.lat, p.lng, latN, lngN)) {
                        jsonToCommit = raw;
                    }
                }
            } catch (e) {}
        }
        if (jsonToCommit && typeof window.commitGuestServiceableLocation === "function") {
            window.commitGuestServiceableLocation(jsonToCommit);
        }
    }

    if (!window.__locationDeliverable) {
        console.log("Location is outside master area, showing modal");
        if (typeof window.showLocationUnavailableModal === "function") {
            window.showLocationUnavailableModal(lat, lng);
        }
    } else {
        console.log("Location is in master area");
    }
}

function checkLocationInMasterArea(lat, lng) {
    if (!lat || !lng) {
        return;
    }
    const latN = Number(lat);
    const lngN = Number(lng);
    if (!Number.isFinite(latN) || !Number.isFinite(lngN)) {
        return;
    }

    const token =
        document.querySelector('meta[name="csrf-token"]')?.content ||
        (typeof $ !== "undefined" ? $('meta[name="csrf-token"]').attr("content") : "") ||
        "";
    const url = window.CHECK_LOCATION_IN_MASTER_URL || "/check-location-in-master";

    function runFetchCheck() {
        const body = new URLSearchParams({
            _token: token,
            latitude: String(latN),
            longitude: String(lngN),
        });
        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": token,
            },
            body: body,
            credentials: "same-origin",
        })
            .then(function (r) {
                return r.json();
            })
            .then(function (response) {
                applyLocationMasterCheckResponse(response, lat, lng);
            })
            .catch(function (err) {
                console.error("Error checking location in master area (fetch):", err);
            });
    }

    if (typeof $ !== "undefined" && $.ajax) {
        $.ajax({
            url: url,
            method: "POST",
            data: {
                _token: token,
                latitude: latN,
                longitude: lngN,
            },
            success: function (response) {
                applyLocationMasterCheckResponse(response, lat, lng);
            },
            error: function () {
                runFetchCheck();
            },
        });
    } else {
        runFetchCheck();
    }
}

// --- Function to initialize/reinitialize Owl Carousels ---
function initializeOwlCarousels() {
    if (typeof $ === 'undefined' || typeof $.fn.owlCarousel === 'undefined') {
        return;
    }
    
    // Reinitialize new-cate-owl-carousel specifically
    if ($('.new-cate-owl-carousel').length) {
        $('.new-cate-owl-carousel').trigger('destroy.owl.carousel');
        $('.new-cate-owl-carousel').removeClass('owl-loaded owl-hidden');
        $('.new-cate-owl-carousel').find('.owl-stage-outer, .owl-stage, .owl-item').remove();

        $('.new-cate-owl-carousel').owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            dots: false,
            navText: [
                "<span class='cate-custom-prev'><i class='fa fa-chevron-left'></i></span>",
                "<span class='cate-custom-next'><i class='fa fa-chevron-right'></i></span>"
            ],
            responsive: {
                320: { items: 2.4 },
                600: { items: 4 },
                1000: { items: 4 }
            }
        });
    }
    
    // Reinitialize other carousels
    $('.owl-carousel').not('.new-cate-owl-carousel').trigger('destroy.owl.carousel');
    $('.owl-carousel').not('.new-cate-owl-carousel').removeClass('owl-loaded owl-hidden');
    $('.owl-carousel').not('.new-cate-owl-carousel').find('.owl-stage-outer, .owl-stage, .owl-item').remove();

    $('.owl-carousel').not('.new-cate-owl-carousel').each(function() {
        var $carousel = $(this);
        var itemCount = $carousel.find('.item').length;
        
        // Determine responsive settings based on item count
        var responsiveSettings = {
            0: { items: 2, stagePadding: 15, margin: 10 },
            480: { items: 2, stagePadding: 15, margin: 10 },
            600: { items: 3, nav: false, margin: 12 },
            768: { items: 3, nav: true, margin: 15 },
            1000: { items: 4, nav: true, margin: 15 }
        };
        
        // If only one item, adjust settings to maintain proper width
        if (itemCount === 1) {
            responsiveSettings = {
                0: { items: 2, stagePadding: 0, margin: 10, nav: false, center: false },
                480: { items: 2, stagePadding: 0, margin: 10, nav: false, center: false },
                600: { items: 4, nav: false, margin: 12, center: false },
                768: { items: 4, nav: false, margin: 15, center: false },
                1000: { items: 4, nav: false, margin: 15, center: false }
            };
            // Add class to identify single-item carousel
            $carousel.addClass('single-item-carousel');
        }
        
        $carousel.owlCarousel({
            loop: false,
            margin: 10,
            dots: false,
            nav: itemCount > 1, // Only show nav if more than 1 item
            navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
            mouseDrag: itemCount > 1, // Disable drag if only 1 item
            touchDrag: itemCount > 1,
            pullDrag: itemCount > 1,
            freeDrag: false,
            responsive: responsiveSettings
        });
    });
}

// Make functions globally accessible
window.updateLocation = updateLocation;
window.initializeOwlCarousels = initializeOwlCarousels;
window.checkLocationInMasterArea = checkLocationInMasterArea;
