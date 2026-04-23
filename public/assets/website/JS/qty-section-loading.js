/**
 * Shared qty row loading overlay (+/− during cart AJAX).
 * Loaded before cart-operations.js on the site; checkout loads this without cart-operations.
 */
(function () {
    'use strict';

    if (typeof $ === 'undefined') {
        return;
    }

    var qtySpinnerOverlayHtml =
        '<div class="qty-spinner-overlay">' +
        '<span class="spinner-border spinner-border-sm text-white" role="status" aria-hidden="true"></span>' +
        '</div>';
    var qtyAjaxInflight = {};

    function normalizeCartKey(key) {
        if (key === undefined || key === null || String(key).trim() === '') return null;
        return String(key).trim();
    }

    function qtyWrapForBtn($btn) {
        var $c = $btn.closest('.qty-container');
        if ($c.length) return $c;
        var $ig = $btn.closest('.input-group');
        if ($ig.length) return $ig;
        return $btn.closest('.qty-box');
    }

    function btnsForCartKey(key) {
        var want = normalizeCartKey(key);
        if (!want) return $();
        return $('.increment-btn, .decrement-btn').filter(function () {
            return String($(this).attr('data-key') || '').trim() === want;
        });
    }

    /** Clears overlay/class by marker (survives side-cart DOM refresh mid-request). */
    function clearMarkersForKey(nk) {
        if (!nk) return;
        $('.qty-section-loading').filter(function () {
            return $(this).attr('data-qty-loading-key') === nk;
        }).each(function () {
            var $el = $(this);
            $el.removeAttr('data-qty-loading-key');
            $el.removeClass('qty-section-loading');
            $el.children('.qty-spinner-overlay').remove();
        });
    }

    function uniqueQtyWraps(key) {
        var seen = {};
        var $wraps = $();
        btnsForCartKey(key).each(function () {
            var $w = qtyWrapForBtn($(this));
            if (!$w.length) return;
            var el = $w.get(0);
            if (!seen[el]) {
                seen[el] = true;
                $wraps = $wraps.add($w);
            }
        });
        return $wraps;
    }

    function applyQtySectionLoadingVisual(key, loading) {
        var nk = normalizeCartKey(key);
        if (!nk) return;

        if (loading) {
            var $wraps = uniqueQtyWraps(nk);
            $wraps.addClass('qty-section-loading');
            $wraps.attr('data-qty-loading-key', nk);
            $wraps.each(function () {
                var $el = $(this);
                $el.children('.qty-spinner-overlay').remove();
                $el.append(qtySpinnerOverlayHtml);
            });
            btnsForCartKey(nk).prop('disabled', true);
        } else {
            clearMarkersForKey(nk);

            var $wraps = uniqueQtyWraps(nk);
            $wraps.removeClass('qty-section-loading');
            $wraps.removeAttr('data-qty-loading-key');
            $wraps.children('.qty-spinner-overlay').remove();

            btnsForCartKey(nk).prop('disabled', false);
        }
    }

    function beginQtyAjax(key) {
        var nk = normalizeCartKey(key);
        if (!nk) return;
        qtyAjaxInflight[nk] = (qtyAjaxInflight[nk] || 0) + 1;
        applyQtySectionLoadingVisual(nk, true);
    }

    function endQtyAjax(key) {
        var nk = normalizeCartKey(key);
        if (!nk) return;
        qtyAjaxInflight[nk] = Math.max(0, (qtyAjaxInflight[nk] || 0) - 1);
        if (qtyAjaxInflight[nk] === 0) {
            applyQtySectionLoadingVisual(nk, false);
        }
    }

    window.kwikllyQtySectionLoading = function (key, loading) {
        if (loading) beginQtyAjax(key);
        else endQtyAjax(key);
    };

    /** Full .qty-box overlay while Add AJAX runs (same look as +/- overlay). */
    function qtyBoxByProductVariant(productId, variantId) {
        return $('.qty-box').filter(function () {
            return (
                String($(this).attr('data-product-id')) === String(productId) &&
                String($(this).attr('data-variant-id')) === String(variantId)
            );
        }).first();
    }

    window.kwikllyQtyBoxAddLoading = function (productId, variantId, loading) {
        var nk = normalizeCartKey(String(productId) + '_' + String(variantId));
        if (!nk) return;

        if (loading) {
            var $box = qtyBoxByProductVariant(productId, variantId);
            if (!$box.length) return;
            $box.addClass('qty-section-loading');
            $box.attr('data-qty-loading-key', nk);
            $box.children('.qty-spinner-overlay').remove();
            $box.append(qtySpinnerOverlayHtml);
            $box.find('.add-btn').prop('disabled', true);
        } else {
            clearMarkersForKey(nk);
            var $box = qtyBoxByProductVariant(productId, variantId);
            if ($box.length) {
                $box.removeClass('qty-section-loading');
                $box.removeAttr('data-qty-loading-key');
                $box.children('.qty-spinner-overlay').remove();
                $box.find('.add-btn').prop('disabled', false);
            }
        }
    };
})();
