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

    // Open product popup
    function openPopup(productId) {
        const modal = new bootstrap.Modal(document.getElementById('productModal'));
        const getVariantUrl = window.GET_VARIANT_URL || '/get-product-variants';
        const finalUrl = `${getVariantUrl}/${productId}`;

        $('#productModalLabel').text("Loading...");
        $('#variantList').html('<p>Loading...</p>');

        fetch(finalUrl)
            .then(res => res.json())
            .then(data => {
                $('#productModalLabel').text(data.product_name);

                let html = '';
                data.variants.forEach(variant => {
                    const attr = JSON.parse(variant.attributes || '{}');
                    const volume = attr.Volume || attr['Memory Size'] || attr.Color || attr.RAM || '';
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
                            <div class="qty-container">
                                <button class="qty-btn minus decrement-btn" data-key="${key}">−</button>
                                <input type="text" class="qty-input quantity-input" value="${qty}" readonly title="In cart: ${qty}">
                                <button class="qty-btn plus increment-btn" data-key="${key}" ${!canIncrement ? 'disabled' : ''}>+</button>
                            </div>
                            ${stock > 0 ? `<small class="text-muted d-block mt-1">Stock: ${stock}</small>` : ''}
                        `;
                    } else {
                        const outOfStock = stock <= 0;
                        qtyBoxHtml = outOfStock
                            ? `<span class="text-muted small">Out of stock</span>`
                            : `
                            <button class="add-btn btn btn-sm btn-outline-success"
                                data-product-id="${productId}"
                                data-variant-id="${variant.id}">
                                Add <i class="fas fa-shopping-cart ms-1"></i>
                            </button>
                            <small class="text-muted d-block mt-1">Stock: ${stock}</small>
                        `;
                    }

                    const variantName = variant.variant_name || '';
                    const displayTitle = variantName 
                        ? `${data.product_name} (${variantName})` 
                        : data.product_name;

                    html += `
                        <div class="unit-item d-flex align-items-center justify-content-between border-bottom py-2">
                            <div class="d-flex align-items-start">
                                <img src="${data.image}" class="unit-image me-3" style="width:60px;height:60px;" alt="${data.product_name}">
                                <div>
                                    <div class="fw-bold text-dark">${displayTitle}</div>
                                    <div class="text-muted small">${volume}</div>
                                    <div class="d-flex align-items-baseline gap-2">
                                        <span class="original-price text-decoration-line-through">₹ ${actual}</span>
                                        <span class="price fw-bold">₹ ${selling}</span>
                                    </div>
                                    ${qty > 0 ? `<small class="text-success">In cart: ${qty}</small>` : ''}
                                </div>
                            </div>
                            <div class="qty-box" data-product-id="${productId}" data-variant-id="${variant.id}" data-key="${key}">
                                ${qtyBoxHtml}
                            </div>
                        </div>
                    `;
                });

                $('#variantList').html(html);
                modal.show();
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

    // Increment inside popup
    $(document).on("click", "#variantList .increment-btn", function () {
        let key = $(this).data("key");
        let input = $(this).siblings(".quantity-input");

        $.ajax({
            url: window.CART_INCREMENT_URL || '/cart/increment',
            type: "POST",
            data: {
                _token: document.querySelector('meta[name="csrf-token"]')?.content || '',
                key: key
            },
            success: function (res) {
                $('.cart-count').text(res.count);
                if (typeof window.updateCartButtonState === 'function') window.updateCartButtonState();

                // Always update from server response
                let updatedQty = getQtyFromResponse(res.cart, key);

                if (updatedQty !== null) {
                    input.val(updatedQty);
                }

                currentCart = res.cart;
                if (window.updateProgress) window.updateProgress();
            }
        });
    });

    // Decrement inside popup
    $(document).on("click", "#variantList .decrement-btn", function () {
        let key = $(this).data("key");
        let input = $(this).siblings(".quantity-input");

        $.ajax({
            url: window.CART_DECREMENT_URL || '/cart/decrement',
            type: "POST",
            data: {
                _token: document.querySelector('meta[name="csrf-token"]')?.content || '',
                key: key
            },
            success: function (res) {
                $('.cart-count').text(res.count);
                if (typeof window.updateCartButtonState === 'function') window.updateCartButtonState();

                let updatedQty = getQtyFromResponse(res.cart, key);

                if (updatedQty !== null && updatedQty > 0) {
                    input.val(updatedQty);
                } else {
                    // If removed, show Add button again
                    let parent = input.closest(".qty-box");
                    parent.html(`
                        <button class="add-btn btn btn-sm btn-outline-success"
                            data-product-id="${parent.data("product-id")}"
                            data-variant-id="${parent.data("variant-id")}">
                            Add <i class="fas fa-shopping-cart ms-1"></i>
                        </button>
                    `);
                }

                currentCart = res.cart;
                if (window.updateProgress) window.updateProgress();
            }
        });
    });

    // Helper: safely extract qty from response
    function getQtyFromResponse(cart, key) {
        let qty = null;
        $.each(cart, function (business, items) {
            if (items[key]) {
                qty = items[key].quantity;
            }
        });
        return qty;
    }

    // Make openPopup globally accessible
    window.openPopup = openPopup;
})();
