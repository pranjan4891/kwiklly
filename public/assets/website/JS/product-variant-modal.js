/**
 * Product Variant Modal Handler
 * Handles product variant modal and cart operations within modal
 */

(function() {
    'use strict';

    if (typeof $ === 'undefined') {
        console.warn('jQuery is required for product variant modal');
        return;
    }

    function escapeHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Keep global cart data
    let currentCart = {};

    // Load cart initially
    if (window.CART_DATA_URL) {
        $.get(window.CART_DATA_URL, function (res) {
            $('.cart-count').text(res.count);
            if (typeof window.updateCartButtonState === 'function') window.updateCartButtonState();
            if (typeof loadSideCartItems === "function") {
                loadSideCartItems(res.cart);
            }
            currentCart = res.cart; // store globally
        });
    }

    // Open product popup (mobile: avoid scroll-to-top before modal — no focus jump, preserve scroll)
    function openPopup(productId, evt) {
        var e = evt || (typeof window.event !== 'undefined' ? window.event : null);
        if (e && typeof e.preventDefault === 'function') {
            e.preventDefault();
            e.stopPropagation();
        }

        var modalEl = document.getElementById('productModal');
        if (!modalEl) {
            return;
        }

        var scrollTop = window.pageYOffset || document.documentElement.scrollTop || 0;

        var modal = bootstrap.Modal.getInstance(modalEl);
        if (!modal) {
            modal = new bootstrap.Modal(modalEl, {
                focus: false,
                backdrop: true,
                keyboard: true
            });
        }

        var getVariantUrl = window.GET_VARIANT_URL || '/get-product-variants';
        var finalUrl = `${getVariantUrl}/${productId}`;

        $('#productModalLabel').text("Loading...");
        $('#variantList').html('<p>Loading...</p>');

        fetch(finalUrl)
            .then(res => res.json())
            .then(data => {
                $('#productModalLabel').text(data.product_name);

                var cartIconSrc =
                    typeof window.CART_ICON_URL === 'string' && window.CART_ICON_URL
                        ? window.CART_ICON_URL
                        : '/public/assets/website/images/cart.svg';

                let html = '';
                data.variants.forEach(variant => {
                    const attr = typeof variant.attributes === 'string'
                        ? JSON.parse(variant.attributes || '{}')
                        : (variant.attributes || {});
                    const volume = attr.Volume || attr['Memory Size'] || attr.Color || attr.RAM || '';
                    const colorName = (attr.Color || attr.Colour || '').toString().trim();
                    const actual = variant.variant_actual_price;
                    const selling = variant.variant_selling_price;
                    const stock = parseInt(variant.stock, 10) || 0;

                    const key = `${productId}_${variant.id}`;
                    let qtyBoxHtml = '';

                    // Variant-wise quantity in cart
                    let qty = findQtyInCart(key);

                    if (qty > 0) {
                        const canIncrement = qty < stock;
                        qtyBoxHtml = `
                            <div class="variant-actions-row variant-actions-row--qty variant-actions-row--qty-only">
                                <div class="qty-container">
                                    <button type="button" class="qty-btn minus decrement-btn" data-key="${key}">−</button>
                                    <input type="text" class="qty-input quantity-input" value="${qty}" readonly title="In cart: ${qty}">
                                    <button type="button" class="qty-btn plus increment-btn" data-key="${key}" ${!canIncrement ? 'disabled' : ''}>+</button>
                                </div>
                            </div>`;
                    } else {
                        const outOfStock = stock <= 0;
                        qtyBoxHtml = outOfStock
                            ? `<span class="text-muted small">Out of stock</span>`
                            : `
                            <div class="variant-actions-row variant-actions-row--add">
                                <button type="button" class="add-btn"
                                    data-product-id="${productId}"
                                    data-variant-id="${variant.id}">
                                    Add <img src="${cartIconSrc}" class="variant-add-cart-icon" alt="">
                                </button>
                            </div>`;
                    }

                    const variantName = (variant.variant_name || '').trim();
                    const displayTitle = variantName || volume || 'Variant';
                    const showColor =
                        colorName &&
                        !String(displayTitle).toLowerCase().includes(colorName.toLowerCase());
                    const displayTitleHtml = `${escapeHtml(displayTitle)}${showColor ? ` <span class="small text-primary fw-semibold">(${escapeHtml(colorName)})</span>` : ''}`;
                    const showMeta =
                        volume &&
                        (variantName || String(displayTitle) !== String(volume));

                    const rowImage = variant.image || data.image;

                    html += `
                        <div class="unit-item variant-option-card">
                            <div class="variant-option-main">
                                <img src="${rowImage}" class="variant-option-thumb" alt="${data.product_name}">
                                <div class="variant-option-details">
                                    <div class="variant-option-title">${displayTitleHtml}</div>

                                    <div class="variant-option-prices">
                                        <span class="price fw-bold">₹ ${selling}</span>
                                        <span class="original-price text-decoration-line-through">₹ ${actual}</span>

                                    </div>
                                </div>
                            </div>
                            <div class="qty-box variant-option-qty" data-product-id="${productId}" data-variant-id="${variant.id}" data-key="${key}">
                                ${qtyBoxHtml}
                            </div>
                        </div>
                    `;
                });

                $('#variantList').html(html);

                function restoreScroll() {
                    window.scrollTo(0, scrollTop);
                }

                modalEl.addEventListener('shown.bs.modal', function onShown() {
                    restoreScroll();
                    modalEl.removeEventListener('shown.bs.modal', onShown);
                });

                modal.show();

                requestAnimationFrame(restoreScroll);
                setTimeout(restoreScroll, 0);
                setTimeout(restoreScroll, 50);
            })
            .catch(() => {
                $('#variantList').html('<p class="text-danger">Failed to load variants.</p>');
            });
    }

    // Helper: find qty in currentCart
    function findQtyInCart(key) {
        let qty = 0;
        $.each(currentCart, function (business, items) {
            if (items[key]) {
                qty = items[key].quantity;
            }
        });
        return qty;
    }

    // Update global cart on every AJAX success
    $(document).ajaxSuccess(function (event, xhr, settings, response) {
        if (response && response.cart) {
            currentCart = response.cart;
        }
    });

    /* +/- inside #variantList: handled globally by cart-operations.js (increment-btn / decrement-btn).
       Decrement rebuild for modal Add button is in cart-operations.js when qty hits 0. */

    // Make openPopup globally accessible
    window.openPopup = openPopup;
})();
