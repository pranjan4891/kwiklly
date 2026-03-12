/**
 * Location Initialization
 * Handles location restoration on page load
 */

// --- On Page Load ---
window.addEventListener("DOMContentLoaded", () => {
    // First check if URL has location parameters (from redirects)
    const urlParams = new URLSearchParams(window.location.search);
    const urlLat = urlParams.get('latitude');
    const urlLng = urlParams.get('longitude');
    
    // If URL has coordinates, use them and save to localStorage
    if (urlLat && urlLng) {
        console.log("Found location in URL params, using them:", urlLat, urlLng);
        
        // Keep params in URL - don't remove them
        // const newUrl = window.location.pathname;
        // window.history.replaceState({}, '', newUrl);
        
        // Use coordinates from URL - reverse geocode to get address
        if (typeof reverseGeocode === 'function') {
            reverseGeocode(parseFloat(urlLat), parseFloat(urlLng), true).then(() => {
                console.log("Location from URL saved successfully");
                // Products will be reloaded by updateLocation function
            }).catch(() => {
                console.log("Reverse geocode failed, saving coordinates directly");
                // If reverse geocode fails, still save coordinates
                let locData = {
                    fullAddress: `Lat: ${urlLat}, Lng: ${urlLng}`,
                    shortAddress: `Lat: ${urlLat}, Lng: ${urlLng}`,
                    lat: urlLat,
                    lng: urlLng
                };
                localStorage.setItem("userLocation", JSON.stringify(locData));
                if (typeof updateLocation === 'function') {
                    updateLocation(locData.fullAddress, null, urlLat, urlLng, true);
                }
            });
        }
        return; // Don't proceed with normal flow
    }
    
    let savedLocation = localStorage.getItem("userLocation");

    if (savedLocation) {
        // ✅ Only restore UI, no redirect, no cart clear
        try {
            let loc = JSON.parse(savedLocation);

            if (document.getElementById("latitude")) document.getElementById("latitude").value = loc.lat || "";
            if (document.getElementById("longitude")) document.getElementById("longitude").value = loc.lng || "";
            // Set search form hidden fields
            if (document.getElementById("search-latitude")) document.getElementById("search-latitude").value = loc.lat || "";
            if (document.getElementById("search-longitude")) document.getElementById("search-longitude").value = loc.lng || "";
            if (document.getElementById("mobile-search-latitude")) document.getElementById("mobile-search-latitude").value = loc.lat || "";
            if (document.getElementById("mobile-search-longitude")) document.getElementById("mobile-search-longitude").value = loc.lng || "";

            let headerLocationDesktop = document.querySelector(".location-text");
            let headerLocationMobile = document.querySelector(".locations-text");
            let selectedLocationEl = document.getElementById("selected-location");

            if (headerLocationDesktop && typeof getShortAddress === 'function') {
                headerLocationDesktop.innerHTML = getShortAddress(loc.fullAddress);
            }
            if (headerLocationMobile) {
                let mobileText = typeof getShortAddressMobile === 'function' ? getShortAddressMobile(loc.fullAddress) : (loc.shortAddress && loc.shortAddress.length > 32 ? loc.shortAddress.substring(0, 32) + ".." : (loc.shortAddress || loc.fullAddress || ""));
                headerLocationMobile.innerHTML = mobileText;
            }
            if (selectedLocationEl) selectedLocationEl.innerText = "📍 " + loc.fullAddress;

            // ✅ Load products for saved location
            if (loc.lat && loc.lng && typeof $ !== 'undefined') {
                console.log("Loading products for saved location:", loc.lat, loc.lng);
                $.ajax({
                    url: window.LOCATION_PRODUCTS_URL || '/location/products',
                    type: "POST",
                    data: {
                        _token: document.querySelector('meta[name="csrf-token"]')?.content || '',
                        latitude: loc.lat,
                        longitude: loc.lng
                    },
                    success: function(res){
                        console.log("Products loaded successfully", res);
                        
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
                            console.log("Updating footer categories");
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
                        if (typeof checkLocationInMasterArea === 'function') {
                            checkLocationInMasterArea(loc.lat, loc.lng);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading products:", error);
                        console.error("Response:", xhr.responseText);
                    }
                });
            }
        } catch (e) {
            console.error("Error parsing saved location:", e);
        }
    } else {
        // Auto-detect location if not saved
        console.log("No saved location found, attempting auto-detect...");
        if (typeof detectLocation === 'function') {
            // Use reverseGeocode directly for auto-detect
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    let lat = position.coords.latitude;
                    let lng = position.coords.longitude;
                    console.log("Auto-detected location:", lat, lng);
                    if (typeof reverseGeocode === 'function') {
                        reverseGeocode(lat, lng, true); // true = auto-detect
                    }
                }, (err) => {
                    console.log("Auto-location detection failed:", err.code);
                    // Silently fail for auto-detect
                });
            }
        }
    }
});
