/**
 * Checkout Handler
 * Handles proceed to checkout functionality
 */

(function() {
    'use strict';

    if (typeof $ === 'undefined') {
        console.warn('jQuery is required for checkout handler');
        return;
    }

    // Proceed to checkout
    $('.proceed-btn').on('click', function () {
        if (window.CHECK_AUTH_STATUS_URL) {
            $.ajax({
                url: window.CHECK_AUTH_STATUS_URL,
                method: "GET",
                success: function (res) {
                    if (res.logged_in) {
                        function goCart() {
                            if (window.CART_VIEW_URL) {
                                window.location.href = window.CART_VIEW_URL;
                            } else if (window.CHECKOUT_PAGE_URL) {
                                window.location.href = window.CHECKOUT_PAGE_URL;
                            }
                        }
                        if (!window.ADDRESS_LIST_URL || typeof fetch !== 'function') {
                            goCart();
                            return;
                        }
                        fetch(window.ADDRESS_LIST_URL, { headers: { Accept: 'application/json' } })
                            .then(function (r) { return r.json(); })
                            .then(function (addresses) {
                                if (Array.isArray(addresses) && addresses.length === 0) {
                                    var home = (window.HOME_URL || '/').split('?')[0];
                                    var ret = window.CART_VIEW_URL || window.CHECKOUT_PAGE_URL || '';
                                    var q = 'requireDeliveryAddress=1';
                                    if (ret) {
                                        q += '&return=' + encodeURIComponent(ret);
                                    }
                                    if (typeof Swal !== 'undefined') {
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'Save delivery address',
                                            text: 'Pehle apni location se delivery address save karein, phir checkout karein.',
                                            confirmButtonColor: '#E94412'
                                        }).then(function () {
                                            window.location.href = home + (home.indexOf('?') >= 0 ? '&' : '?') + q;
                                        });
                                    } else {
                                        window.location.href = home + (home.indexOf('?') >= 0 ? '&' : '?') + q;
                                    }
                                    return;
                                }
                                goCart();
                            })
                            .catch(function () {
                                goCart();
                            });
                    } else {
                        // Redirect to login page
                        if (window.LOGIN_URL) {
                            window.location.href = window.LOGIN_URL;
                        }
                    }
                }
            });
        }
    });

    // Auto open login popup on mobile
    document.addEventListener("DOMContentLoaded", function () {
        if (window.innerWidth <= 576) {
            setTimeout(() => {
                const loginBox = document.querySelector('.log-in-box');
                if (loginBox) {
                    loginBox.classList.add('show');
                }
            }, 200);
        }
    });
})();
