/**
 * Cart Operations Handler
 * Handles cart add, increment, decrement operations
 */
 
 

(function() {
    'use strict';

    if (typeof $ === 'undefined') {
        console.warn('jQuery is required for cart operations');
        return;
    }

    function isMobileViewport() {
        return typeof window.matchMedia !== 'undefined'
            ? window.matchMedia('(max-width: 767.98px)').matches
            : window.innerWidth < 768;
    }

    window.kwikllyCartAjaxActive = false;

    var addBtnSpinnerHtml = '<span class="spinner-border spinner-border-sm add-btn-spinner" role="status" aria-hidden="true"></span>';

    /** Qty overlay: window.kwikllyQtySectionLoading — see qty-section-loading.js */
    function qtyLoading(key, on) {
        if (typeof window.kwikllyQtySectionLoading === 'function') {
            window.kwikllyQtySectionLoading(key, on);
        }
    }

    /** Re-enable +/- even if overlay cleanup missed (prevents stuck disabled = no repeat spinner). */
    function enableQtyButtonsForKey(key) {
        var nk = String(key == null ? '' : key).trim();
        if (!nk) return;
        $('.increment-btn, .decrement-btn').each(function () {
            if (String($(this).attr('data-key') || '').trim() === nk) {
                $(this).prop('disabled', false);
            }
        });
    }

    $(document).ready(function () {
        // Load cart initially
        if (window.CART_DATA_URL) {
            $.get(window.CART_DATA_URL, function (res) {
                $('.cart-count').text(res.count);
                if (typeof window.updateCartButtonState === 'function') window.updateCartButtonState();
                if (typeof loadSideCartItems === "function") {
                    loadSideCartItems(res.cart);
                }
            });
        }

        // ADD
        $(document).on('click', '.add-btn', function (e) {
            e.preventDefault();
            var $btn = $(this);
            let productId = $btn.data('product-id');
            let variantId = $btn.data('variant-id');

            if (!variantId) return;
            if ($btn.hasClass('disabled') || $btn.prop('disabled')) return;

            let parent = $btn.closest('.qty-box');
            let key = productId + '_' + variantId;
            var originalBtnHtml = $btn.html();

            if (typeof window.kwikllyQtyBoxAddLoading === 'function') {
                window.kwikllyQtyBoxAddLoading(productId, variantId, true);
            } else {
                $btn.prop('disabled', true).addClass('add-btn-loading').html(addBtnSpinnerHtml);
            }

            $.ajax({
                url: window.CART_ADD_URL || '/cart/add',
                type: "POST",
                data: {
                    _token: document.querySelector('meta[name="csrf-token"]')?.content || '',
                    product_id: productId,
                    variant_id: variantId,
                    quantity: 1
                },
                success: function (res) {
                    $('.cart-count').text(res.count);
                    if (typeof window.updateCartButtonState === 'function') window.updateCartButtonState();
                    if (typeof loadSideCartItems === "function") {
                        loadSideCartItems(res.cart);
                    }
                    var isMobileBar = parent.hasClass('qty-box-mobile') || parent.closest('.fixed-bottom-mobile').length > 0;
                    var inVariantModalQty =
                        parent.closest('#variantList').length > 0 || parent.hasClass('variant-option-qty');
                    var qtyContainerClass = isMobileBar ? 'qty-container qty-container-mobile' : 'qty-container';
                    var qtyBtnClass = isMobileBar ? 'qty-btn qty-btn-mobile' : 'qty-btn';
                    var qtyInputClass = isMobileBar ? 'qty-input qty-input-mobile quantity-input' : 'qty-input quantity-input';
                    let qtyContainer = `
                        <div class="${qtyContainerClass}">
                            <button class="${qtyBtnClass} minus decrement-btn" type="button" data-key="${key}">−</button>
                            <input type="text" class="${qtyInputClass}" value="1" readonly tabindex="-1">
                            <button class="${qtyBtnClass} plus increment-btn" type="button" data-key="${key}">+</button>
                        </div>
                    `;
                    if (inVariantModalQty) {
                        qtyContainer =
                            '<div class="variant-actions-row variant-actions-row--qty variant-actions-row--qty-only">' +
                            qtyContainer.trim() +
                            '</div>';
                    }
                    parent.html(qtyContainer);

                    if (!isMobileViewport() && typeof openCart === "function") {
                        openCart();
                    }

                    if (window.updateProgress) window.updateProgress();
                },
                error: function (xhr) {
                    let msg = xhr?.responseJSON?.message || 'Unable to add item. Please try again.';
                    if (typeof window.kwikllyQtyBoxAddLoading === 'function') {
                        window.kwikllyQtyBoxAddLoading(productId, variantId, false);
                    }
                    $btn.prop('disabled', false).removeClass('add-btn-loading').html(originalBtnHtml);
                    alert(msg);
                },
                complete: function () {
                    if (typeof window.kwikllyQtyBoxAddLoading === 'function') {
                        window.kwikllyQtyBoxAddLoading(productId, variantId, false);
                    }
                }
            });
        });

        // INCREMENT
        $(document).on('click', '.increment-btn', function (e) {
            e.preventDefault();
            var key = String($(this).attr('data-key') || '').trim();
            if (key === '') return;
            if ($(this).prop('disabled')) return;
            window.kwikllyCartAjaxActive = true;
            qtyLoading(key, true);
            $.ajax({
                url: window.CART_INCREMENT_URL || '/cart/increment',
                type: 'POST',
                data: {
                    _token: document.querySelector('meta[name="csrf-token"]')?.content || '',
                    key: key
                },
                complete: function () {
                    window.kwikllyCartAjaxActive = false;
                    qtyLoading(key, false);
                    enableQtyButtonsForKey(key);
                },
                success: function (res) {
                    $('.cart-count').text(res.count);
                    if (typeof window.updateCartButtonState === 'function') window.updateCartButtonState();
                    if (typeof loadSideCartItems === "function") {
                        loadSideCartItems(res.cart);
                    }

                    // Find quantity from grouped cart
                    let updatedQty = null;
                    $.each(res.cart, function (businessName, items) {
                        if (items[key]) {
                            updatedQty = items[key].quantity;
                        }
                    });

                    if (updatedQty !== null) {
                        // Update qty everywhere for this key (product details, cards, side cart, etc.)
                        $('[data-key="' + key + '"]')
                            .closest('.qty-box, .qty-container')
                            .find('.quantity-input')
                            .val(updatedQty);
                        if (window.updateProgress) window.updateProgress();
                    }
                },
                error: function (xhr) {
                    let msg = xhr?.responseJSON?.message || 'Quantity cannot be increased.';
                    alert(msg);
                }
            });
        });

        // DECREMENT
        $(document).on('click', '.decrement-btn', function (e) {
            e.preventDefault();
            var key = String($(this).attr('data-key') || '').trim();
            if (key === '') return;
            if ($(this).prop('disabled')) return;
            window.kwikllyCartAjaxActive = true;
            qtyLoading(key, true);
            var decrementXhr = $.post(window.CART_DECREMENT_URL || '/cart/decrement', {
                _token: document.querySelector('meta[name="csrf-token"]')?.content || '',
                key: key
            }, function (res) {
                $('.cart-count').text(res.count);
                if (typeof window.updateCartButtonState === 'function') window.updateCartButtonState();
                if (typeof loadSideCartItems === "function") {
                    loadSideCartItems(res.cart);
                }

                let [productId, variantId] = key.split('_');

                // Find product's qty box
                let productQtyBox = $(`.qty-box[data-product-id="${productId}"][data-variant-id="${variantId}"]`);

                // If item removed completely, reset to "Add" button
                let stillExists = false;
                $.each(res.cart, function (business, items) {
                    if (items[key]) stillExists = true;
                });

                if (!stillExists) {
                    var inVariantModal = productQtyBox.closest('#variantList').length > 0;
                    var cartIconUrl = window.CART_ICON_URL || '/public/assets/website/images/cart.svg';
                    if (inVariantModal) {
                        productQtyBox.html(
                            '<div class="variant-actions-row variant-actions-row--add">' +
                            '<button type="button" class="add-btn" data-product-id="' + productId + '" data-variant-id="' + variantId + '">' +
                            'Add <img src="' + cartIconUrl + '" class="variant-add-cart-icon" alt="">' +
                            '</button></div>'
                        );
                    } else {
                        productQtyBox.html(
                            '<button class="add-btn" type="button" data-product-id="' + productId + '" data-variant-id="' + variantId + '">' +
                            'Add <img src="' + cartIconUrl + '" class="ms-2">' +
                            '</button>'
                        );
                    }
                } else {
                    // Update qty in UI (support .qty-box and .qty-container)
                    $.each(res.cart, function (business, items) {
                        if (items[key]) {
                            $('[data-key="' + key + '"]').closest('.qty-box, .qty-container').find('.quantity-input').val(items[key].quantity);
                        }
                    });
                }

                if (window.updateProgress) window.updateProgress();
            }).always(function () {
                window.kwikllyCartAjaxActive = false;
                qtyLoading(key, false);
                enableQtyButtonsForKey(key);
            });
        });
    });

    // Render cart items in sidebar
    function loadSideCartItems(cartGroups) {
        let html = '';
        let total = 0;

        if (cartGroups && Object.keys(cartGroups).length > 0) {
           // e.preventDefault();
            $.each(cartGroups, function (businessName, items) {
                // Pick vendor_id from the first item of the group
                let firstItemKey = Object.keys(items)[0];
                let vendorId = items[firstItemKey].business_id || '#';

                // Build explore store URL
                let exploreStoreUrl = (window.EXPLORE_STORE_URL || '/explore-store/:vendor_id/0').replace(':vendor_id', vendorId);

                // Start business group
                html += `
                        <div class="cart-business-group mb-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-1">
                                <a href="${exploreStoreUrl}"
                                class="small text-primary my-2 d-block"
                                onclick="return redirectWithLocation(this.href)">
                                    ${businessName}
                                </a>
                            </h6>
                            <h6 class="mb-1"><a class="small text-danger my-2 d-block" onclick="if(typeof loadSideCartCoupons==='function')loadSideCartCoupons(${vendorId})">Coupons</a></h6>
                           </div>
                        `;

                // Loop through each item under this business
                $.each(items, function (key, item) {
                    let price = parseFloat(item.price);
                    let quantity = parseInt(item.quantity);
                    let originalPrice = parseFloat(item.original_price || price);
                    let subtotal = price * quantity;
                    total += subtotal;

                    // Format price to remove .00
                    let formatPrice = function(amount) {
                        let num = parseFloat(amount);
                        // If whole number, return without decimals, else return with decimals but remove .00
                        let formatted = num % 1 === 0 ? num.toString() : num.toFixed(2);
                        // Remove .00 if present
                        return formatted.replace(/\.00$/, '');
                    };

                    let formattedPrice = formatPrice(price);
                    let formattedOriginalPrice = formatPrice(originalPrice);

                    // Escape HTML to prevent XSS and prepare for title attribute
                    let escapedTitle = $('<div>').text(item.title).html();

                    html += `
                        <div class="cart-item d-flex align-items-center justify-content-between border-bottom py-2">
                            <div class="d-flex align-items-center totalimg">
                                <img src="${item.image}" alt="${escapedTitle}" style="width:50px;">
                                <div class="mx-3">
                                    <p class="mb-0" title="${escapedTitle}">${escapedTitle}</p>
                                    <small class="text-success">
                                        ₹${formattedPrice} ${price < originalPrice ? `<s class="text-muted">₹${formattedOriginalPrice}</s>` : ''}
                                    </small>
                                </div>
                            </div>
                            <div class="input-group input-group-sm sidecartbutton sidecart-qty-wrap">
                                <button class="btn btn-danger decrement-btn" data-key="${key}">-</button>
                                <input type="text" class="form-control text-center quantity-input" value="${quantity}" disabled>
                                <button class="btn btn-danger increment-btn" data-key="${key}">+</button>
                            </div>
                        </div>
                    `;
                });

                html += `</div>`; // close business group
            });
        } else {
            html = `<p class="text-center">Your cart is empty.</p>`;
        }

        // Update both desktop and mobile cart wrappers
        $('.cartItemsWrapper').html(html);
        $('#cartItemsWrapperMobile').html(html);

        // Update grand total
        if (typeof updateBillSummary === "function") {
            updateBillSummary(total);
        }
    }

    function updateBillSummary(total) {
        // Static values for demonstration
        let deliveryCharge = 0;
        let couponDiscount = 0;
        let walletDiscount = 0;

        let grandTotal = total + deliveryCharge - couponDiscount - walletDiscount;

        $('.billSummary').html(`
            <ul class="list-unstyled small">
            <li class="d-flex justify-content-between">
                <span>Item charge</span><span>₹${total}</span>
            </li>
            <li class="d-flex justify-content-between">
                <span>Delivery Charges</span><span>₹${deliveryCharge}</span>
            </li>
            <li class="d-flex justify-content-between">
                <span>Coupon Discount</span><span class="text-success">- ₹${couponDiscount}</span>
            </li>
            <li class="d-flex justify-content-between">
                <span>Wallet Discount</span><span class="text-success">- ₹${walletDiscount}</span>
            </li>
            </ul>
        `);

        // Format grand total with proper formatting
        let formattedTotal = grandTotal.toFixed(0);

        // Check if user is logged in (set via window.IS_LOGGED_IN)
        if (window.IS_LOGGED_IN) {
            $('.proceed-btn').html(`Proceed to Checkout <span class="rupee-symbol-sidecart ms-2">(₹ ${formattedTotal}/-)</span>`);
        } else {
            $('.proceed-btn').html(`Login to Proceed <span class="rupee-symbol-sidecart ms-2">(₹ ${formattedTotal}/-)</span>`);
        }

        if ($('.grand-total-box strong').length) {
            $('.grand-total-box strong').html(`₹${grandTotal}`);
        }
    }

    // Sidebar Controls
    function openCart() {
        $('#cartSidebar').css('right', '0');
    }
    function closeCart() {
        $('#cartSidebar').css('right', '-100%');
    }

    // Make functions globally accessible
    window.loadSideCartItems = loadSideCartItems;
    window.updateBillSummary = updateBillSummary;
    window.openCart = openCart;
    window.closeCart = closeCart;

    /**
     * Legacy UI (storedetail / order summaries): swap Add for local qty controls only — no server cart.
     * Inline onclick="convertToQty(this)" pages rely on these globals.
     */
    window.convertToQty = function (button) {
        var parent = button.parentElement;
        var originalBtn = button.cloneNode(true);
        originalBtn.onclick = function () {
            window.convertToQty(this);
        };

        parent.dataset.originalButton = parent.innerHTML;

        var qtyContainer = document.createElement('div');
        qtyContainer.classList.add('qty-container');

        var minusBtn = document.createElement('button');
        minusBtn.innerHTML = '−';
        minusBtn.classList.add('qty-btn', 'minus');
        minusBtn.onclick = function () {
            window.changeQty(this, -1);
        };

        var qtyInput = document.createElement('input');
        qtyInput.value = 1;
        qtyInput.classList.add('qty-input');
        qtyInput.setAttribute('readonly', 'true');

        var plusBtn = document.createElement('button');
        plusBtn.innerHTML = '+';
        plusBtn.classList.add('qty-btn', 'plus');
        plusBtn.onclick = function () {
            window.changeQty(this, 1);
        };

        qtyContainer.appendChild(minusBtn);
        qtyContainer.appendChild(qtyInput);
        qtyContainer.appendChild(plusBtn);

        parent.replaceChild(qtyContainer, button);
    };

    window.changeQty = function (button, change) {
        var qtyContainer = button.parentElement;
        var qtyInput = qtyContainer.querySelector('.qty-input');
        var newValue = parseInt(qtyInput.value, 10) + change;

        if (newValue < 1) {
            var wrap = qtyContainer.parentElement;
            wrap.innerHTML = wrap.dataset.originalButton;

            var addBtn = wrap.querySelector('button');
            if (addBtn) {
                addBtn.onclick = function () {
                    window.convertToQty(this);
                };
            }
        } else {
            qtyInput.value = newValue;
        }
    };
})();
