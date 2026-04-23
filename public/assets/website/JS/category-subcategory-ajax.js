(function () {
    'use strict';

    function getLatLng() {
        var latEl = document.getElementById('latitude');
        var lngEl = document.getElementById('longitude');
        return {
            latitude: latEl ? latEl.value : '',
            longitude: lngEl ? lngEl.value : '',
        };
    }

    function csrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function setActiveSidebar(container, activeSubId) {
        if (!container) return;
        var items = container.querySelectorAll('[data-subcategory-id]');
        items.forEach(function (el) {
            var sid = el.getAttribute('data-subcategory-id');
            var target = el.closest('.sidebar-itemde') || el.closest('li') || el;
            if (sid === String(activeSubId)) {
                target.classList.add('active');
            } else {
                target.classList.remove('active');
            }
        });
    }

    function postJson(url, body, onSuccess, onError) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken());
        xhr.onload = function () {
            try {
                var data = JSON.parse(xhr.responseText || '{}');
                if (xhr.status >= 200 && xhr.status < 300 && data.success && data.html !== undefined) {
                    onSuccess(data);
                } else {
                    onError(data.message || 'Could not load products.');
                }
            } catch (e) {
                onError('Invalid response.');
            }
        };
        xhr.onerror = function () {
            onError('Network error.');
        };
        xhr.send(JSON.stringify(body));
    }

    document.addEventListener('click', function (e) {
        var explore = e.target.closest('.js-explorestore-subcat-ajax');
        if (explore) {
            e.preventDefault();
            var vendorId = explore.getAttribute('data-vendor-id');
            var catId = explore.getAttribute('data-category-id');
            var subId = explore.getAttribute('data-subcategory-id');
            var exploreUrl =
                typeof window.ajaxExplorestoreProductsUrl !== 'undefined'
                    ? window.ajaxExplorestoreProductsUrl
                    : '';
            if (!exploreUrl || !vendorId || !catId || !subId) return;
            var loc = getLatLng();
            var wrap = explore.closest('.subcategory-list-wrap, .subcategory-mobile-list .js-subcat-list, .sidebar, .explore-sidebar') || document;
            var sec = document.getElementById('products-section');
            if (sec) sec.style.opacity = '0.6';
            postJson(
                exploreUrl,
                {
                    vendor_id: parseInt(vendorId, 10),
                    category_id: parseInt(catId, 10),
                    subcategory_id: parseInt(subId, 10),
                    latitude: loc.latitude,
                    longitude: loc.longitude,
                },
                function (data) {
                    if (sec) {
                        sec.innerHTML = data.html;
                        sec.style.opacity = '1';
                    }
                    setActiveSidebar(wrap, data.active_subcategory_id);
                    if (typeof window.updateProgress === 'function') {
                        window.updateProgress(parseInt(vendorId, 10));
                    }
                },
                function (msg) {
                    if (sec) sec.style.opacity = '1';
                    if (typeof Toastr !== 'undefined') {
                        Toastr.warning(msg);
                    }
                }
            );
            return;
        }

        var catwise = e.target.closest('.js-categorywise-subcat-ajax');
        if (catwise) {
            e.preventDefault();
            var cId = catwise.getAttribute('data-category-id');
            var sId = catwise.getAttribute('data-subcategory-id');
            var cwUrl =
                typeof window.ajaxCategorywiseProductsUrl !== 'undefined'
                    ? window.ajaxCategorywiseProductsUrl
                    : '';
            if (!cwUrl || !cId || !sId) return;
            var loc2 = getLatLng();
            if (!loc2.latitude || !loc2.longitude) {
                if (typeof Toastr !== 'undefined') {
                    Toastr.warning('Please enable location.');
                }
                return;
            }
            var wrap2 =
                catwise.closest('.subcategory-list-wrap, .subcategory-mobile-list .js-subcat-list, .sidebar') ||
                document;
            var sec2 = document.getElementById('products-section');
            if (sec2) sec2.style.opacity = '0.6';
            postJson(
                cwUrl,
                {
                    category_id: parseInt(cId, 10),
                    subcategory_id: parseInt(sId, 10),
                    latitude: loc2.latitude,
                    longitude: loc2.longitude,
                },
                function (data) {
                    if (sec2) {
                        sec2.innerHTML = data.html;
                        sec2.style.opacity = '1';
                    }
                    setActiveSidebar(wrap2, data.active_subcategory_id);
                },
                function (msg) {
                    if (sec2) sec2.style.opacity = '1';
                    if (typeof Toastr !== 'undefined') {
                        Toastr.warning(msg);
                    }
                }
            );
        }
    });
})();
