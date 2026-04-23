/**
 * Mobile: only disable automatic history scroll restoration.
 * Older code used capture-phase click locks + scroll listeners that called scrollTo() repeatedly;
 * that fought cart-operations.js (Add/qty AJAX) and overflow:hidden during add-to-cart → jump/jitter.
 */
(function () {
    'use strict';
    function arm() {
        if (window.innerWidth >= 768) return;
        if ('scrollRestoration' in history) history.scrollRestoration = 'manual';
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', arm);
    else arm();
    window.addEventListener('resize', arm);
})();

/* Qty +/- is handled only by cart-operations.js (delegated). Old duplicate listeners here
   fired on mobile with preventDefault() and fought the server-backed cart → tap/scroll quirks. */

// desktop side cart functions start
      document.getElementById("openCart").addEventListener("click", function (e) {
        // Check if cart count is 0, if so prevent opening sidebar
        const cartCount = document.querySelector('#openCart .cart-count').textContent;
        if (parseInt(cartCount) === 0) {
          e.preventDefault();
          return;
        }
        document.getElementById("cartSidebar").classList.add("show");
      });

      document.getElementById("openCart2").addEventListener("click", function (e) {
        // Check if cart count is 0, if so prevent opening sidebar
        const cartCount = document.querySelector('#openCart2 .cart-count').textContent;
        if (parseInt(cartCount) === 0) {
          e.preventDefault();
          return;
        }
        document.getElementById("cartSidebar2").classList.add("show");
        const overlay = document.getElementById("cartOverlay2");
        if (overlay) {
          overlay.classList.add("show");
          overlay.style.display = "block";
        }
      });
      
      // Function to close mobile cart
      window.closeMobileCart = function() {
        const cartSidebar = document.getElementById("cartSidebar2");
        const overlay = document.getElementById("cartOverlay2");
        if (cartSidebar) {
          cartSidebar.classList.remove("show");
        }
        if (overlay) {
          overlay.classList.remove("show");
          overlay.style.display = "none";
        }
      };

// Function to update cart button state and badge visibility (hide count when 0)
function updateCartButtonState() {
    const desktopCartCount = document.querySelector('#openCart .cart-count');
    const desktopCartButton = document.getElementById('openCart');
    const mobileCartCount = document.querySelector('#openCart2 .cart-count');
    const mobileCartButton = document.getElementById('openCart2');

    function syncCartBadge(countEl, buttonEl) {
        if (!countEl || !buttonEl) return;
        const count = parseInt(countEl.textContent, 10) || 0;
        if (count > 0) {
            countEl.classList.remove('d-none');
            countEl.setAttribute('aria-hidden', 'false');
            buttonEl.removeAttribute('disabled');
        } else {
            countEl.classList.add('d-none');
            countEl.setAttribute('aria-hidden', 'true');
            buttonEl.setAttribute('disabled', 'disabled');
        }
    }

    syncCartBadge(desktopCartCount, desktopCartButton);
    syncCartBadge(mobileCartCount, mobileCartButton);
}

window.updateCartButtonState = updateCartButtonState;

// Update cart button state on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCartButtonState();
});

// Also update when cart count changes (for AJAX updates)
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
        if (mutation.type === 'childList' || mutation.type === 'characterData') {
            updateCartButtonState();
        }
    });
});

// Observe cart count elements for changes
const desktopCartCount = document.querySelector('#openCart .cart-count');
const mobileCartCount = document.querySelector('#openCart2 .cart-count');

if (desktopCartCount) {
    observer.observe(desktopCartCount, {
        childList: true,
        characterData: true,
        subtree: true
    });
}

if (mobileCartCount) {
    observer.observe(mobileCartCount, {
        childList: true,
        characterData: true,
        subtree: true
    });
}
      // Desktop side cart functions end



/** Homepage category carousel: har Owl item = ek column (upar+neeche pair); ~4 columns visible. */
function getCategoryOwlCarouselOptions() {
    return {
        loop: true,
        margin: 10,
        nav: true,
        dots: false,
        navText: [
            '<span class="new-cate-nav-inner" aria-hidden="true"><i class="fa fa-chevron-left"></i></span>',
            '<span class="new-cate-nav-inner" aria-hidden="true"><i class="fa fa-chevron-right"></i></span>'
        ],
        responsive: {
            0: { items: 2 },
            600: { items: 3 },
            1000: { items: 4 }
        }
    };
}
window.getCategoryOwlCarouselOptions = getCategoryOwlCarouselOptions;

/**
 * Flat mobile wraps (id order, row-major 1–8) → desktop column slides (pairs col + col+rowSize).
 */
function buildDesktopCategoryOwlSlidesHtml(wrapElements) {
    function escAttr(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }
    function anchorFromWrap(wrapEl) {
        var $a = $(wrapEl).find('a').first();
        if (!$a.length) {
            return '';
        }
        var href = $a.attr('href') || '#';
        var onclick = $a.attr('onclick');
        var onclickAttr = onclick ? ' onclick="' + escAttr(onclick) + '"' : '';
        var $img = $a.find('.product-carded img').length ? $a.find('.product-carded img').first() : $a.find('img').first();
        var imgSrc = $img.attr('src') || '';
        var alt = $img.attr('alt') || '';
        var nameText = $.trim($a.find('.catename').first().text()) || alt;
        return (
            '<a href="' + escAttr(href) + '" class="text-decoration-none text-dark"' + onclickAttr + '>' +
            '<div class="product-carded">' +
            '<img src="' + escAttr(imgSrc) + '" alt="' + escAttr(alt) + '">' +
            '</div>' +
            '<div class="py-2 text-center catename"><b>' + escAttr(nameText) + '</b></div>' +
            '</a>'
        );
    }
    var html = '';
    var list = wrapElements;
    for (var c = 0; c < list.length; c += 8) {
        var chunk = [];
        var chunkLen = Math.min(8, list.length - c);
        for (var i = 0; i < chunkLen; i++) {
            chunk.push(list[c + i]);
        }
        var n = chunk.length;
        var rowSize = Math.ceil(n / 2);
        for (var col = 0; col < rowSize; col++) {
            var topEl = chunk[col];
            var bottomIdx = col + rowSize;
            var bottomEl = bottomIdx < n ? chunk[bottomIdx] : null;
            html += '<div class="new-cate-item p-0">';
            html += anchorFromWrap(topEl);
            if (bottomEl) {
                html += anchorFromWrap(bottomEl);
            }
            html += '</div>';
        }
    }
    return html;
}
window.buildDesktopCategoryOwlSlidesHtml = buildDesktopCategoryOwlSlidesHtml;

/** Mobile category grid: pehli 8 categories, baaki Load More se */
function initCategoryLoadMore() {
    var $container = $('#category-container');
    var $wrap = $('#categoryLoadMoreWrap');
    var $btn = $('#categoryLoadMoreBtn');
    if (!$container.length) {
        return;
    }
    var $items = $container.find('.new-cate-item-wrap');
    var total = $items.length;
    var hiddenCount = $items.filter('.new-cate-item-wrap--extra').length;

    $container.removeClass('is-expanded');

    if ($wrap.length && $btn.length) {
        if (hiddenCount > 0) {
            $wrap.show();
            $btn.show().attr('aria-expanded', 'false').html('Load More <i class="fa fa-angles-down ms-2"></i>');
        } else {
            $wrap.hide();
        }
    }

    $btn.off('click.categoryLoadMore').on('click.categoryLoadMore', function () {
        $container.addClass('is-expanded');
        $(this).attr('aria-expanded', 'true').hide();
        $wrap.hide();
    });
}
window.initCategoryLoadMore = initCategoryLoadMore;

// category slider
$(document).ready(function () {

    initCategoryLoadMore();

    /* ===============================
       CATEGORY SLIDER
    ================================ */
    $(".new-cate-owl-carousel").owlCarousel(getCategoryOwlCarouselOptions());

    $(".new-cate-owl-carousel").addClass("cate-slider");

    /* ===============================
       PRODUCT / COMMON SLIDERS
    ================================ */
    $(".owl-carousel").not(".new-cate-owl-carousel").each(function () {

        const $carousel = $(this);
        const itemCount = $carousel.find(".item").length;

        $carousel.toggleClass("single-item-carousel", itemCount === 1);

        $carousel.owlCarousel({
            loop: false,
            dots: false,
            nav: itemCount > 1,
            navText: ["", ""],
            mouseDrag: itemCount > 1,
            touchDrag: itemCount > 1,
            pullDrag: itemCount > 1,
            responsive: {
                0: { items: 2 },
                600: { items: 3 },
                1000: { items: 6 }
            }
        });

        $carousel.addClass("beauty-slider");
    });

});


// js for vendor registration
    let step = 0;
    let map, marker;
    let mapInitialized = false;

  window.onload = function () {
    if (window.innerWidth <= 576) {
      setTimeout(() => {
        const logInBox = document.querySelector('.log-in-box');
        if (logInBox) {
          logInBox.classList.add('show');
        }
      }, 200);
    }
  }

    function moveNext(elem, event) {
        if (elem.value.length === 1) {
          let next = elem.nextElementSibling;
          if (next) next.focus();
        } else if (event.inputType === "deleteContentBackward") {
          let prev = elem.previousElementSibling;
          if (prev) prev.focus();
        }
      }

  function openPataSidebar() {
    document.getElementById("pataSidebar").classList.add("active");
    setTimeout(initMap, 100);
  }

  function closePataSidebar() {
    document.getElementById("pataSidebar").classList.remove("active");
  }

  document.addEventListener("DOMContentLoaded", function () {
    const homeBtn = document.getElementById("pataHomeBtn");
    const workBtn = document.getElementById("pataWorkBtn");

    if (homeBtn && workBtn) {
    homeBtn.addEventListener("click", function () {
      homeBtn.classList.add("active");
      workBtn.classList.remove("active");
    });

    workBtn.addEventListener("click", function () {
      workBtn.classList.add("active");
      homeBtn.classList.remove("active");
    });
    }
  });
  /* Product owl carousels: init only in the block above — a second .owlCarousel() here re-inited the same
     nodes with different options (loop:true) and caused mobile scroll/jump on taps inside product cards. */
