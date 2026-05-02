/**
 * Product variant details page – variant selection on page (no modal).
 * On variant click: updates price, stock, net quantity, attributes and qty-box for selected variant.
 */
(function () {
    'use strict';

    if (typeof $ === 'undefined') return;

    var variantData = null;
    var currentCart = {};

    function getVariantData() {
        var el = document.getElementById('product-detail-variants-data');
        if (!el || !el.textContent) return null;
        try {
            return JSON.parse(el.textContent.trim());
        } catch (e) {
            return null;
        }
    }

    function loadCart(callback) {
        var url = window.CART_DATA_URL || '/cart/data';
        $.get(url).done(function (res) {
            currentCart = res.cart || {};
            if (typeof callback === 'function') callback(currentCart);
        }).fail(function () {
            if (typeof callback === 'function') callback({});
        });
    }

    function getQtyForKey(key) {
        var qty = 0;
        $.each(currentCart, function (business, items) {
            if (items && items[key]) {
                qty = items[key].quantity;
                return false;
            }
        });
        return qty;
    }

    function formatNum(n) {
        return parseInt(n, 10).toLocaleString('en-IN');
    }

    function updatePriceStock(variant) {
        var selling = parseFloat(variant.variant_selling_price) || 0;
        var actual = parseFloat(variant.variant_actual_price) || 0;
        var stock = parseInt(variant.stock, 10) || 0;
        var disc = variant.discount_percent || 0;
        if (actual > selling && actual > 0) {
            disc = Math.round(((actual - selling) / actual) * 100);
        }

        $('.product-detail-selling-price').text(formatNum(selling));
        $('.product-detail-actual-price').text(formatNum(actual));
        $('.product-detail-discount-badge').text(disc + '% Off');

        var actualWrap = $('.product-detail-actual-wrap');
        if (actual > selling) {
            actualWrap.show();
        } else {
            actualWrap.hide();
        }

        var discountWrap = $('.product-detail-discount-wrap');
        if (disc > 0) {
            discountWrap.show();
        } else {
            discountWrap.hide();
        }

        // $('.product-detail-net-qty').text('Net Quantity : ' + (product.sub_title || '1 unit'));
        // $('.product-detail-availability').text('Availability : ' + (stock > 0 ? 'In Stock' : 'Out of Stock'));

        // Mobile bar
        $('.product-detail-selling-price-mobile').text(formatNum(selling));
        $('.product-detail-actual-price-mobile').text(formatNum(actual));
        $('.product-detail-discount-mobile').text(disc + '% Off');
        if (disc > 0) {
            $('.product-detail-discount-mobile').show();
            $('.product-detail-actual-wrap-mobile').show();
        } else {
            $('.product-detail-discount-mobile').hide();
            $('.product-detail-actual-wrap-mobile').hide();
        }

        // Update main image and thumbnails from variant images (color-specific gallery)
        var mainImg = document.querySelector('.main-img-det .main-product-det');
        var thumbDet = document.querySelector('.thumb-det');
        if (variant.images && variant.images.length > 0 && mainImg && thumbDet) {
            mainImg.setAttribute('src', variant.images[0]);
            var thumbHtml = '';
            variant.images.forEach(function (url, idx) {
                thumbHtml += '<img src="' + url + '" class="' + (idx === 0 ? 'active' : '') + '" alt="">';
            });
            thumbDet.innerHTML = thumbHtml;
        }
    }

    function renderQtyBox(productId, variantId, key, stock, inCart, quantity) {
        var storeOpen = $('.product-details-section .qty-box').attr('data-store-open') === '1';
        var cartIcon = '<img src="' + (window.CART_ICON_URL || '') + '" class="ms-2">';
        var stockNum = parseInt(stock, 10) || 0;
        var outOfStockHtml = '<span class="add-btn-detail btn disabled text-muted">Out of Stock</span>';
        var html = '';
        if (!storeOpen) {
            html = '<button class="add-btn-detail disabled" disabled>Add ' + cartIcon + '</button>';
        } else if (stockNum <= 0) {
            html = outOfStockHtml;
        } else if (inCart && quantity > 0) {
            html = '<div class="qty-container">' +
                '<button class="qty-btn minus decrement-btn" data-key="' + key + '">−</button>' +
                '<input type="text" class="qty-input quantity-input" value="' + quantity + '" readonly>' +
                '<button class="qty-btn plus increment-btn" data-key="' + key + '">+</button>' +
                '</div>';
        } else {
            html = '<button class="add-btn-detail add-btn" data-product-id="' + productId + '" data-variant-id="' + variantId + '">Add ' + cartIcon + '</button>';
        }
        $('.product-details-section .qty-box').attr('data-variant-id', variantId).attr('data-key', key).html(html);

        var mobileHtml = '';
        if (!storeOpen) {
            mobileHtml = '<button class="add-btn-mobile disabled" disabled>Add ' + cartIcon + '</button>';
        } else if (stockNum <= 0) {
            mobileHtml = '<span class="add-btn-mobile btn disabled text-muted">Out of Stock</span>';
        } else if (inCart && quantity > 0) {
            mobileHtml = '<div class="qty-container qty-container-mobile">' +
                '<button class="qty-btn qty-btn-mobile minus decrement-btn" data-key="' + key + '">−</button>' +
                '<input type="text" class="qty-input qty-input-mobile quantity-input" value="' + quantity + '" readonly>' +
                '<button class="qty-btn qty-btn-mobile plus increment-btn" data-key="' + key + '">+</button>' +
                '</div>';
        } else {
            mobileHtml = '<button class="add-btn-mobile add-btn" data-product-id="' + productId + '" data-variant-id="' + variantId + '">Add ' + cartIcon + '</button>';
        }
        $('.qty-box-mobile').attr('data-variant-id', variantId).attr('data-key', key).html(mobileHtml);
    }

    function selectVariant($el) {
        var variantId = $el.data('variant-id');
        var productId = $el.data('product-id');
        var key = $el.data('key');
        var selling = $el.data('selling');
        var actual = $el.data('actual');
        var stock = $el.data('stock');
        var netQty = $el.data('net-qty') || '1 unit';

        variantData = variantData || getVariantData();
        var variant = null;
        if (variantData && variantData.variants) {
            variant = variantData.variants.find(function (v) { return v.id == variantId; });
        }
        if (!variant) {
            variant = {
                id: variantId,
                variant_selling_price: selling,
                variant_actual_price: actual,
                stock: stock,
                net_qty_display: netQty,
                discount_percent: (actual > selling && actual > 0) ? Math.round(((actual - selling) / actual) * 100) : 0,
                images: []
            };
        }

        $('.variant-item-detail').removeClass('active');
        $('.color-option-variant').removeClass('active');
        if ($el.hasClass('color-option-card')) {
            $el.find('.color-option-variant').addClass('active');
            if (variant && variant.variant_base) {
                $('.variant-base-item[data-variant-base="' + variant.variant_base + '"]').addClass('active');
            }
        } else {
            $el.addClass('active');
        }

        updatePriceStock(variant);

        loadCart(function () {
            var qty = getQtyForKey(key);
            renderQtyBox(productId, variantId, key, stock, qty > 0, qty || 1);
        });
    }

    function selectVariantBase($baseEl) {
        var base = $baseEl.data('variant-base');
        if (!base) return;
        $('.variant-base-item').removeClass('active');
        $baseEl.addClass('active');
        $('.color-option-card').addClass('d-none').removeClass('color-first-active');
        $('.color-option-card[data-variant-base="' + base + '"]').removeClass('d-none');
        var $firstColor = $('.color-option-card[data-variant-base="' + base + '"]').first();
        if ($firstColor.length) {
            $firstColor.find('.color-option-variant').addClass('active');
            selectVariant($firstColor);
        } else {
            selectVariant($baseEl);
        }
    }

    $(document).on('click', '.variant-base-item', function () {
        selectVariantBase($(this));
    });

    $(document).on('click', '.variant-item-detail:not(.variant-base-item)', function () {
        selectVariant($(this));
    });

    $(document).on('click', '.color-option-variant', function () {
        var $card = $(this).closest('.color-option-card');
        selectVariant($card.length ? $card : $(this));
    });

    // After cart add/increment/decrement, refresh qty box for current variant
    $(document).ajaxSuccess(function (event, xhr, settings, response) {
        if (response && response.cart) {
            currentCart = response.cart;
        }
    });
})();
