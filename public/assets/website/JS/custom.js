
// Prevent scroll to top on mobile view - PREVENT BEFORE IT HAPPENS
(function() {
    'use strict';
    
    function isMobileView() {
        return window.innerWidth < 768;
    }
    
    // Initialize only on mobile
    if (!isMobileView()) {
        // Re-check on resize
        window.addEventListener('resize', function() {
            if (isMobileView() && !window.mobileScrollFixInitialized) {
                initMobileScrollFix();
            }
        });
        return;
    }
    
    function initMobileScrollFix() {
        if (window.mobileScrollFixInitialized) return;
        window.mobileScrollFixInitialized = true;
        
        // Save scroll position
        let savedScrollPosition = 0;
        let isLockingScroll = false;
        
        // Prevent scroll restoration
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
        
        // Save scroll position continuously
        function saveScrollPosition() {
            if (!isLockingScroll) {
                savedScrollPosition = window.pageYOffset || document.documentElement.scrollTop || 0;
            }
        }
        
        // Update saved position on scroll (only if not locking)
        let scrollTimeout;
        window.addEventListener('scroll', function() {
            if (!isLockingScroll) {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(saveScrollPosition, 50);
            } else {
                // If scroll is locked, immediately restore position
                window.scrollTo(0, savedScrollPosition);
            }
        }, { passive: false }); // NOT passive - we need to prevent scroll
        
        // Save initial position
        saveScrollPosition();
        
        // PREVENT scroll BEFORE it happens - lock scroll during clicks
        document.addEventListener('click', function(e) {
            if (!isMobileView()) return;
            
            // Save scroll position BEFORE any action
            savedScrollPosition = window.pageYOffset || document.documentElement.scrollTop || 0;
            
            // Lock scroll position
            isLockingScroll = true;
            
            const target = e.target;
            const clickable = target.closest('button, .btn, .add-btn, .qty-btn, .increment-btn, .decrement-btn, a, [onclick], [role="button"]');
            
            // Handle anchor links with href="#"
            const link = target.closest('a');
            if (link) {
                const href = link.getAttribute('href');
                
                if (href === '#' || href === '#!') {
                    // Only prevent if it's not a modal/dropdown trigger
                    if (!link.hasAttribute('data-bs-toggle') && 
                        !link.hasAttribute('data-toggle') && 
                        !link.hasAttribute('data-bs-target') &&
                        !link.hasAttribute('data-target')) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Execute onclick if exists
                        if (link.onclick) {
                            link.onclick(e);
                        }
                        
                        // Unlock after a delay
                        setTimeout(function() {
                            isLockingScroll = false;
                        }, 100);
                        return false;
                    }
                }
            }
            
            // For all clickable elements, lock scroll for a short period
            if (clickable) {
                // Keep scroll locked for 200ms to prevent any scroll jumps
                setTimeout(function() {
                    const currentScroll = window.pageYOffset || document.documentElement.scrollTop || 0;
                    // If scroll changed, restore it immediately
                    if (Math.abs(currentScroll - savedScrollPosition) > 10) {
                        window.scrollTo(0, savedScrollPosition);
                    }
                    // Unlock after ensuring position is maintained
                    setTimeout(function() {
                        isLockingScroll = false;
                    }, 50);
                }, 200);
            } else {
                // Unlock immediately if not a clickable element
                setTimeout(function() {
                    isLockingScroll = false;
                }, 100);
            }
        }, true); // Use capture phase - intercept EARLIEST
        
        // Prevent scroll on form submissions
        document.addEventListener('submit', function(e) {
            if (!isMobileView()) return;
            
            saveScrollPosition();
            isLockingScroll = true;
            
            // Unlock after form submission
            setTimeout(function() {
                const currentScroll = window.pageYOffset || document.documentElement.scrollTop || 0;
                if (Math.abs(currentScroll - savedScrollPosition) > 10) {
                    window.scrollTo(0, savedScrollPosition);
                }
                isLockingScroll = false;
            }, 100);
        }, true);
        
        // Continuous monitoring - prevent any unwanted scroll to top
        let lastScrollCheck = 0;
        setInterval(function() {
            if (!isMobileView()) return;
            
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop || 0;
            
            // If scroll jumped to top unexpectedly, restore immediately
            if (currentScroll < 50 && savedScrollPosition > 100 && !isLockingScroll) {
                isLockingScroll = true;
                window.scrollTo(0, savedScrollPosition);
                setTimeout(function() {
                    isLockingScroll = false;
                }, 100);
            } else if (currentScroll > 0 && !isLockingScroll) {
                // Update saved position if scroll is valid
                savedScrollPosition = currentScroll;
            }
            
            lastScrollCheck = currentScroll;
        }, 20); // Check every 20ms for very fast response
    }
    
    // Initialize immediately
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileScrollFix);
    } else {
        initMobileScrollFix();
    }
})();

// side cart quantity increaser start
document.addEventListener("DOMContentLoaded", function () {
  const inputGroups = document.querySelectorAll(".input-group");

  inputGroups.forEach(function (group) {
    const decrementBtn = group.querySelector(".decrement-btn");
    const incrementBtn = group.querySelector(".increment-btn");
    const quantityInput = group.querySelector(".quantity-input");

    decrementBtn.addEventListener("click", function (e) {
      if (window.innerWidth < 768) {
        e.preventDefault();
      }
      let value = parseInt(quantityInput.value);
      if (value > 1) {
        quantityInput.value = value - 1;
      }
    });

    incrementBtn.addEventListener("click", function (e) {
      if (window.innerWidth < 768) {
        e.preventDefault();
      }
      let value = parseInt(quantityInput.value);
      quantityInput.value = value + 1;
    });
  });
});
// side cart quantity increaser end


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



/** Homepage category strip: keep visible slide count below total chunks so prev/next move the stage */
function getCategoryOwlCarouselOptions() {
    return {
        loop: true,
        margin: 12,
        nav: true,
        dots: false,
        navText: [
            '<span class="new-cate-nav-inner" aria-hidden="true"><i class="fa fa-chevron-left"></i></span>',
            '<span class="new-cate-nav-inner" aria-hidden="true"><i class="fa fa-chevron-right"></i></span>'
        ],
        responsive: {
            0: { items: 2.4 },
            600: { items: 3 },
            1000: { items: 2 }
        }
    };
}
window.getCategoryOwlCarouselOptions = getCategoryOwlCarouselOptions;

// category slider
$(document).ready(function () {

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


// button converter and pop up for product quantity
function convertToQty(button) {
    let parent = button.parentElement;
    let originalBtn = button.cloneNode(true);
    originalBtn.onclick = function () { convertToQty(this); };

    parent.dataset.originalButton = parent.innerHTML;

    let qtyContainer = document.createElement("div");
    qtyContainer.classList.add("qty-container");

    let minusBtn = document.createElement("button");
    minusBtn.innerHTML = "−";
    minusBtn.classList.add("qty-btn", "minus");
    minusBtn.onclick = function () { changeQty(this, -1); };

    let qtyInput = document.createElement("input");
    qtyInput.value = 1;
    qtyInput.classList.add("qty-input");
    qtyInput.setAttribute("readonly", "true");

    let plusBtn = document.createElement("button");
    plusBtn.innerHTML = "+";
    plusBtn.classList.add("qty-btn", "plus");
    plusBtn.onclick = function () { changeQty(this, 1); };

    qtyContainer.appendChild(minusBtn);
    qtyContainer.appendChild(qtyInput);
    qtyContainer.appendChild(plusBtn);

    parent.replaceChild(qtyContainer, button);
}

function changeQty(button, change) {
    let qtyContainer = button.parentElement;
    let qtyInput = qtyContainer.querySelector(".qty-input");
    let newValue = parseInt(qtyInput.value) + change;

    if (newValue < 1) {
        let parent = qtyContainer.parentElement;
        parent.innerHTML = parent.dataset.originalButton;

        let addBtn = parent.querySelector("button");
        addBtn.onclick = function () { convertToQty(this); };
    } else {
        qtyInput.value = newValue;
    }
}

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
  
  
  $(document).ready(function(){
    $(".owl-carousel").not(".new-cate-owl-carousel").owlCarousel({
        loop:true,
        margin:10,
        dots:false,
        nav:true,
        navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
        responsive:{
            0: { items: 2, margin: 8 },
            600:{ items:3, nav:false },
            1000:{ items:6, nav:true }
        }
    });
});
