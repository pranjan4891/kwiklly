/**
 * Search Suggestions Handler
 * Handles search box autocomplete suggestions for both desktop and mobile
 * Fetches products from vendors, branches, and admins based on user location
 */

document.addEventListener("DOMContentLoaded", function() {
    // Initialize desktop search
    initializeSearchSuggestions(
        "search-box",
        "suggestions-box",
        "search-form",
        "search-latitude",
        "search-longitude"
    );

    // Initialize mobile search
    initializeSearchSuggestions(
        "mobile-search-box",
        "mobile-suggestions-box",
        "mobile-search-form",
        "mobile-search-latitude",
        "mobile-search-longitude"
    );

    // Helper function to initialize search suggestions
    function initializeSearchSuggestions(searchBoxId, suggestionsBoxId, formId, latId, lngId) {
        let searchBox = document.getElementById(searchBoxId);
        let suggestionsBox = document.getElementById(suggestionsBoxId);
        let form = document.getElementById(formId);

        if (!searchBox || !suggestionsBox || !form) {
            return;
        }

        // Helper to get location params
        function getSuggestionLocationParams() {
            let params = '';
            let lat = document.getElementById(latId)?.value;
            let lng = document.getElementById(lngId)?.value;
            if (lat) params += `&latitude=${encodeURIComponent(lat)}`;
            if (lng) params += `&longitude=${encodeURIComponent(lng)}`;
            return params;
        }

        // Prevent body scroll when suggestions are shown (mobile only)
        let savedScrollPosition = 0;
        /** Only true while mobile suggestions overlay has locked body scroll — avoids scrollTo(0,0) on every tap. */
        let mobileSuggestionsScrollLocked = false;
        function preventBodyScroll(prevent) {
            if (window.innerWidth < 768 && searchBoxId === 'mobile-search-box') { // Mobile only
                if (prevent) {
                    // Save current scroll position
                    savedScrollPosition = window.pageYOffset || document.documentElement.scrollTop || 0;
                    mobileSuggestionsScrollLocked = true;
                    document.body.classList.add('suggestions-open');
                    document.body.style.top = `-${savedScrollPosition}px`;
                    document.body.style.position = 'fixed';
                    document.body.style.width = '100%';
                    const navbar = document.querySelector('.navbar');
                    if (navbar) {
                        navbar.style.position = 'fixed';
                        navbar.style.top = '0';
                        navbar.style.left = '0';
                        navbar.style.right = '0';
                        navbar.style.zIndex = '1050';
                    }
                } else {
                    if (!mobileSuggestionsScrollLocked) {
                        return;
                    }
                    mobileSuggestionsScrollLocked = false;
                    document.body.classList.remove('suggestions-open');
                    document.body.style.top = '';
                    document.body.style.position = '';
                    document.body.style.width = '';
                    const navbar = document.querySelector('.navbar');
                    if (navbar) {
                        navbar.style.position = '';
                        navbar.style.top = '';
                        navbar.style.left = '';
                        navbar.style.right = '';
                        navbar.style.zIndex = '';
                    }
                    window.scrollTo(0, savedScrollPosition);
                    savedScrollPosition = 0;
                }
            }
        }
        
        // Position mobile suggestions box below search form
        function positionMobileSuggestions() {
            if (searchBoxId === 'mobile-search-box' && window.innerWidth < 768) {
                const form = document.getElementById(formId);
                const searchBox = document.getElementById(searchBoxId);
                if (form && searchBox && suggestionsBox && !suggestionsBox.classList.contains('d-none')) {
                    const formRect = form.getBoundingClientRect();
                    // Position suggestions below search form using fixed positioning
                    suggestionsBox.style.position = 'fixed';
                    suggestionsBox.style.top = `${formRect.bottom + 5}px`;
                    suggestionsBox.style.left = `${formRect.left}px`;
                    suggestionsBox.style.width = `${formRect.width}px`;
                    suggestionsBox.style.maxWidth = `${formRect.width}px`;
                    suggestionsBox.style.right = 'auto';
                    suggestionsBox.style.zIndex = '10001';
                }
            }
        }
        
        // Reposition on window resize
        if (searchBoxId === 'mobile-search-box') {
            window.addEventListener('resize', function() {
                if (!suggestionsBox.classList.contains('d-none')) {
                    positionMobileSuggestions();
                }
            });
        }

        // Load last searches from localStorage
        function showLastSearches() {
            let lastSearches = JSON.parse(localStorage.getItem("lastSearches")) || [];
            suggestionsBox.innerHTML = "";
            if (lastSearches.length > 0) {
                lastSearches.forEach(item => {
                    let li = document.createElement("li");
                    li.classList.add("list-group-item", "d-flex", "align-items-center");
                    li.innerHTML = `<img src="${window.MARKER_IMAGE_URL || '/public/marker.png'}"
                                        class="me-2" style="width:30px; height:30px; object-fit:cover; border-radius:5px;">
                                    <span>${item}</span>`;
                    li.addEventListener("click", () => {
                        searchBox.value = item;
                        suggestionsBox.classList.add("d-none");
                        preventBodyScroll(false);
                    });
                    suggestionsBox.appendChild(li);
                });
                suggestionsBox.classList.remove("d-none");
                preventBodyScroll(true);
                // Position suggestions box below search form (mobile only)
                if (searchBoxId === 'mobile-search-box') {
                    setTimeout(() => positionMobileSuggestions(), 10);
                }
            } else {
                suggestionsBox.classList.add("d-none");
                preventBodyScroll(false);
            }
        }

        // On focus show last searches
        searchBox.addEventListener("focus", showLastSearches);

        // On keyup fetch suggestions
        searchBox.addEventListener("keyup", function() {
            let query = this.value.trim();
            if (query.length < 2) {
                showLastSearches();
                return;
            }

            // Fetch suggestions from backend API
            // Backend returns products from vendors, branches, and admins filtered by location
            let searchSuggestionsUrl = window.SEARCH_SUGGESTIONS_URL || '/search/suggestions';
            fetch(`${searchSuggestionsUrl}?q=${encodeURIComponent(query)}${getSuggestionLocationParams()}`)
                .then(res => res.json())
                .then(data => {
                    suggestionsBox.innerHTML = "";
                    if (data.length > 0) {
                       data.forEach(item => {
                            let li = document.createElement("li");
                            li.classList.add("list-group-item", "d-flex", "align-items-center");
                            li.style.cursor = "pointer";
                            li.innerHTML = `
                                <img src="${item.image}"
                                    class="me-2" style="width:40px; height:40px; object-fit:cover; border-radius:5px;">
                                <span>${item.title}</span>
                            `;
                            li.addEventListener("click", () => {
                                searchBox.value = item.title;
                                form.submit(); // ✅ redirect to searchresults with ?q=item.title
                                suggestionsBox.classList.add("d-none");
                            });
                            suggestionsBox.appendChild(li);
                        });

                        suggestionsBox.classList.remove("d-none");
                        preventBodyScroll(true);
                        // Position suggestions box below search form (mobile only)
                        if (searchBoxId === 'mobile-search-box') {
                            setTimeout(() => positionMobileSuggestions(), 10);
                        }
                    } else {
                        suggestionsBox.classList.add("d-none");
                        preventBodyScroll(false);
                    }
                })
                .catch(err => {
                    console.error("Search error:", err);
                    suggestionsBox.classList.add("d-none");
                });
        });

        // Save searches on form submit
        form.addEventListener("submit", function() {
            let query = searchBox.value.trim();
            if (query) {
                let lastSearches = JSON.parse(localStorage.getItem("lastSearches")) || [];
                if (!lastSearches.includes(query)) {
                    lastSearches.unshift(query); // add to top
                    if (lastSearches.length > 5) lastSearches.pop(); // keep max 5
                    localStorage.setItem("lastSearches", JSON.stringify(lastSearches));
                }
            }
        });

        // Hide suggestions when clicking outside
        document.addEventListener("click", function(e) {
            if (!form.contains(e.target)) {
                suggestionsBox.classList.add("d-none");
                preventBodyScroll(false);
            }
        });
        
        // Also hide suggestions and restore scroll on blur (mobile)
        if (searchBoxId === 'mobile-search-box') {
            searchBox.addEventListener("blur", function() {
                // Delay to allow click events to fire first
                setTimeout(() => {
                    if (!form.contains(document.activeElement)) {
                        suggestionsBox.classList.add("d-none");
                        preventBodyScroll(false);
                    }
                }, 200);
            });
        }
    }
});
