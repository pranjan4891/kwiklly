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
                        // Always redirect to cart/view page
                        if (window.CART_VIEW_URL) {
                            window.location.href = window.CART_VIEW_URL;
                        } else if (window.CHECKOUT_PAGE_URL) {
                            window.location.href = window.CHECKOUT_PAGE_URL;
                        }
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
