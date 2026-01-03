
// side cart quantity increaser start
document.addEventListener("DOMContentLoaded", function () {
  const inputGroups = document.querySelectorAll(".input-group");

  inputGroups.forEach(function (group) {
    const decrementBtn = group.querySelector(".decrement-btn");
    const incrementBtn = group.querySelector(".increment-btn");
    const quantityInput = group.querySelector(".quantity-input");

    decrementBtn.addEventListener("click", function () {
      let value = parseInt(quantityInput.value);
      if (value > 1) {
        quantityInput.value = value - 1;
      }
    });

    incrementBtn.addEventListener("click", function () {
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

// Function to update cart button state based on cart count
function updateCartButtonState() {
    // Check desktop cart button
    const desktopCartCount = document.querySelector('#openCart .cart-count');
    const desktopCartButton = document.getElementById('openCart');

    // Check mobile cart button
    const mobileCartCount = document.querySelector('#openCart2 .cart-count');
    const mobileCartButton = document.getElementById('openCart2');

    if (desktopCartCount && desktopCartButton) {
        const count = parseInt(desktopCartCount.textContent);
        if (count > 0) {
            desktopCartButton.removeAttribute('disabled');
        } else {
            desktopCartButton.setAttribute('disabled', 'disabled');
        }
    }

    if (mobileCartCount && mobileCartButton) {
        const count = parseInt(mobileCartCount.textContent);
        if (count > 0) {
            mobileCartButton.removeAttribute('disabled');
        } else {
            mobileCartButton.setAttribute('disabled', 'disabled');
        }
    }
}

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



// category slider
$(document).ready(function () {
   $(".new-cate-owl-carousel").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        dots: false,
        navText: [
            "<span class='cate-custom-prev'><i class='fa fa-chevron-left'></i></span>",
            "<span class='cate-custom-next'><i class='fa fa-chevron-right'></i></span>"
        ],
        responsive: {
            320: { items: 2.4 },
            600: { items: 4 },
            1000: { items: 4 }
        }
    });

    // Move navigation buttons to the right
    $(".cate-owl-carousel .owl-nav").addClass("cate-owl-nav");
});
// slider for mobile screen
$(document).ready(function(){
    $(".owl-carousel").owlCarousel({
        loop:true,
        margin:10,
        dots:false,
        nav:true,
        navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
        responsive:{
            320: { items: 2.2 },
            600:{ items:3, nav:false },
            1000:{ items:4, nav:true }
        }
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
