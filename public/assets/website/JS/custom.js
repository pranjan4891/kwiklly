
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
      document.getElementById("openCart").addEventListener("click", function () {
        document.getElementById("cartSidebar").classList.add("show");
      });
      document.getElementById("openCart2").addEventListener("click", function () {
        document.getElementById("cartSidebar2").classList.add("show");
      });


    //   document.getElementById("deliveryToggle2").addEventListener("click", function () {
    //     const el = document.getElementById("deliveryOptions2");
    //     el.style.display = el.style.display === "none" ? "block" : "none";
    //   });

    //   document.getElementById("expressBtn").addEventListener("click", function () {
    //     const btn = this;
    //     const isExpress = btn.classList.contains("btn-success");
    //     if (isExpress) {
    //       btn.classList.remove("btn-success");
    //       btn.classList.add("btn-outline-success");
    //       btn.innerHTML = '<i class="fa fa-times me-1"></i> Remove Express Delivery';
    //     } else {
    //       btn.classList.remove("btn-outline-success");
    //       btn.classList.add("btn-success");
    //       btn.innerHTML = '<i class="fa fa-bolt me-1"></i> Express Delivery in 20 mins';
    //     }
    //   });
    //   document.getElementById("expressBtn2").addEventListener("click", function () {
    //     const btn = this;
    //     const isExpress = btn.classList.contains("btn-success");
    //     if (isExpress) {
    //       btn.classList.remove("btn-success");
    //       btn.classList.add("btn-outline-success");
    //       btn.innerHTML = '<i class="fa fa-times me-1"></i> Remove Express Delivery';
    //     } else {
    //       btn.classList.remove("btn-outline-success");
    //       btn.classList.add("btn-success");
    //       btn.innerHTML = '<i class="fa fa-bolt me-1"></i> Express Delivery in 20 mins';
    //     }
    //   });


    //   document.getElementById("walletBtn2").addEventListener("click", function () {
    //     const btn = this;
    //     const walletText = document.getElementById("walletText2");
    //     if (btn.textContent.includes("Use")) {
    //       btn.textContent = "Remove";
    //       walletText.innerHTML = '<i class="fa fa-wallet me-1"></i> Added ₹5 in your wallet';
    //     } else {
    //       btn.textContent = "Use ₹5";
    //       walletText.innerHTML = '<i class="fa fa-wallet me-1"></i> Save money by kwikily wallet';
    //     }
    //   });


    //   document.getElementById("toggleBillBtn2").addEventListener("click", function () {
    //     const bill = document.getElementById("billSummary2");
    //     const icon = this.querySelector("i");
    //     if (bill.style.display === "none") {
    //       bill.style.display = "block";
    //       this.innerHTML = "<i class='fa fa-chevron-up'></i>";
    //     } else {
    //       bill.style.display = "none";
    //       this.innerHTML = "<i class='fa fa-chevron-down'></i>";
    //     }
    //   });
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
            320: { items: 2.4 }, // 2 full products + 1/3 in mobile view
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
            320: { items: 2.2 }, // 2 full products + 1/3 in mobile view
            600:{ items:3, nav:false },
            1000:{ items:4, nav:true }
        }
    });
});

// button converter and pop up for product quantity
// function openPopup() {
//     var modal = new bootstrap.Modal(document.getElementById("productModal"));
//     modal.show();
// }

function convertToQty(button) {
    // Save the original button for restoring later
    let parent = button.parentElement;
    let originalBtn = button.cloneNode(true); // Save the original for restoring
    originalBtn.onclick = function () { convertToQty(this); }; // reassign the click handler

    // Store reference for later restoration
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
        // Restore original Add button
        let parent = qtyContainer.parentElement;
        parent.innerHTML = parent.dataset.originalButton;

        // Re-assign click handler to restored Add button
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

//     function updateForm() {
//         let container = document.getElementById("dynamic-container");
//         let confirmBtn = document.getElementById("confirm-btn");
//         let backBtn = document.getElementById("back-btn");
//         let heading = document.getElementById("form-heading");
//         let input = document.getElementById("form-input");

//         if (step === 0) {
//             heading.textContent = "Register Today";
//             input.placeholder = "Enter Landmark";
//             container.innerHTML = `
//             <input type="text" id="form-input" class="form-control mb-3" placeholder="Enter Landmark">
//                 <div id="map" class="map-container">
//                     <button class="use-location-btn" onclick="getLocation()">
//                         <i class="fas fa-map-marker-alt"></i> Use Current Location
//                     </button>
//                 </div>`;
//             confirmBtn.textContent = "Confirm and Proceed";
//             backBtn.style.display = "none";

//             setTimeout(() => {
//                 initMap();
//             }, 100);
//         } else {
//             backBtn.style.display = "inline-block";
//             if (step === 1) {
//                 heading.textContent = "Register Today";
//                 input.placeholder = "Enter Landmark";
//                 container.innerHTML = `
//                 <h4>Your Location</h4>
//                 <p>Cisf ground, gali no 2, near metro station gate no 3, <br>saket, Delhi </p>
//                 <h4>Personal Details</h4>
//                 <input type="text" id="form-input" class="form-control mb-3" placeholder="Email Id">
//                 <input type="text" id="form-input" class="form-control mb-3" placeholder="Phone Number">
//                 <input type="text" id="form-input" class="form-control mb-3" placeholder="Password">
//                 <input type="text" id="form-input" class="form-control mb-3" placeholder="Confirm Password">
//                 `;
//             } else if (step === 2) {
//                 heading.textContent = "Register Today";
//                 input.placeholder = "Enter Phone";
//                 container.innerHTML = `
//                 <h4>Company Details</h4>
//                 <input type="text" id="form-input" class="form-control mb-3" placeholder="Company Name">
//                 <input type="text" id="form-input" class="form-control mb-3" placeholder="GST Number">
//                 <input type="text" id="form-input" class="form-control mb-3" placeholder="Address">

//                 `;
//             } else if (step === 3) {
//                 heading.textContent = "Register Today";
//                 input.placeholder = "Enter Address";
//                 confirmBtn.textContent = "Submit";
//                 container.innerHTML = `
//                 <h4>Bank Details</h4>
//                 <input type="text" id="form-input" class="form-control mb-3" placeholder="Account Holder Name">
//                 <input type="text" id="form-input" class="form-control mb-3" placeholder="Account Name">
//                 <input type="text" id="form-input" class="form-control mb-3" placeholder="IFSC Code">
//                 <label class="pb-2">Cancelled Cheque</label>
//                 <input type="file" id="form-input" class="form-control mb-3" placeholder="Cancelled Cheque">
//                 `;
//             } else if (step === 4) {
//                 Swal.fire({
//                     text: "Thank you for your confirmation",
//                     icon: "success",
//                     confirmButtonText: "Continue"
//                 }).then(() => {
//                     step = 0;
//                     updateForm();
//                 });
//             }
//         }
//     }

//     function nextStep() {
//         if (step < 4) {
//             step++;
//             updateForm();
//         }
//     }

//     function prevStep() {
//         if (step > 0) {
//             step--;
//             updateForm();
//         }
//     }

//     function initMap() {
//         map = L.map('map').setView([20.5937, 78.9629], 5); // Default India location
//         L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//             attribution: '&copy; OpenStreetMap contributors'
//         }).addTo(map);
//         marker = L.marker([20.5937, 78.9629]).addTo(map);
//     }

//     function getLocation() {
//         if (navigator.geolocation) {
//             navigator.geolocation.getCurrentPosition(function(position) {
//                 var lat = position.coords.latitude;
//                 var lon = position.coords.longitude;
//                 map.setView([lat, lon], 13);
//                 marker.setLatLng([lat, lon]).bindPopup("You are here!").openPopup();
//             });
//         } else {
//             Swal.fire({
//                 title: "Error!",
//                 text: "Geolocation is not supported by this browser.",
//                 icon: "error",
//                 confirmButtonText: "OK"
//             });
//         }
//     }

//     document.addEventListener("DOMContentLoaded", updateForm);

// // department sidebar active
// document.querySelectorAll('.sidebar-itemde').forEach(item => {
//     item.addEventListener('click', function() {
//         // Remove active class from all
//         document.querySelectorAll('.sidebar-itemde').forEach(el => el.classList.remove('active'));

//         // Add active class to clicked item
//         this.classList.add('active');
//     });
// });

// // store page side bar active class by js
//   $(document).ready(function() {
//     let initialItems = 9;
//     const loadItems = 3;
//     const $items = $('.new-cate-item-wrap');
//     const totalItems = $items.length;
//     let loadedItems = initialItems;

//     function showItems(count) {
//       $items.slice(0, count).slideDown();
//     }

//     function resetItems() {
//       loadedItems = initialItems;
//       $items.hide();
//       showItems(loadedItems);
//       $('#loadMoreBtn').html('Load More <i class="fa fa-angles-down ms-2"></i>');
//     }

//     // Initial Display
//     resetItems();

//     $('#loadMoreBtn').click(function() {
//       if ($(this).text().includes('Load Back')) {
//         resetItems();
//       } else {
//         loadedItems += loadItems;
//         showItems(loadedItems);
//         if (loadedItems >= totalItems) {
//           $(this).html('Load Back <i class="fa fa-angles-up ms-2"></i>');
//         }
//       }
//     });
//   });
// //   department side fixed position at mobile screen
// $(document).ready(function() {
//     var sidebarOffset = $('.mobile-sidebar').offset().top;

//     $(window).on('scroll resize', function() {
//       var scrollTop = $(window).scrollTop();
//       var winWidth = $(window).width();

//       if (winWidth <= 768) { // Mobile screens only
//         if (scrollTop >= sidebarOffset) {
//           $('.mobile-sidebar').addClass('sticky');
//         } else {
//           $('.mobile-sidebar').removeClass('sticky');
//         }
//       } else {
//         $('.mobile-sidebar').removeClass('sticky'); // Remove sticky if window is not mobile
//       }
//     });
//   });
//   // Show the login box with smooth slide up on mobile
//   window.onload = function () {
//     if (window.innerWidth <= 576) {
//       setTimeout(() => {
//         document.querySelector('.log-in-box').classList.add('show');
//       }, 200);
//     }
//   }
//     // Auto focus move to next input
//     function moveNext(elem, event) {
//         if (elem.value.length === 1) {
//           let next = elem.nextElementSibling;
//           if (next) next.focus();
//         } else if (event.inputType === "deleteContentBackward") {
//           let prev = elem.previousElementSibling;
//           if (prev) prev.focus();
//         }
//       }

//       // detaile page  js start
//        // Thumbnail slider arrow scroll
//     const thumbContainer = document.querySelector('.thumb-det');
//     document.querySelector('.arrow-left-det').onclick = () => thumbContainer.scrollBy({ left: -100, behavior: 'smooth' });
//     document.querySelector('.arrow-right-det').onclick = () => thumbContainer.scrollBy({ left: 100, behavior: 'smooth' });

//     // Thumbnail image click
//     const thumbs = document.querySelectorAll('.thumb-det img');
//     const mainImg = document.querySelector('.main-product-det');
//     thumbs.forEach(thumb => {
//       thumb.addEventListener('click', () => {
//         thumbs.forEach(t => t.classList.remove('active'));
//         thumb.classList.add('active');
//         mainImg.src = thumb.src;
//       });
//     });

//     // Memory/RAM option select
//     document.querySelectorAll('.btn-option-det').forEach(btn => {
//       btn.addEventListener('click', () => {
//         btn.parentElement.querySelectorAll('.btn-option-det').forEach(b => b.classList.remove('active'));
//         btn.classList.add('active');
//       });
//     });

//     // Color selection active effect
//     document.querySelectorAll('.color-option-det').forEach(color => {
//       color.addEventListener('click', () => {
//         document.querySelectorAll('.color-option-det').forEach(c => c.classList.remove('active'));
//         color.classList.add('active');
//       });
//     });

//     // Show More toggle
//     document.querySelector('.show-more-det').addEventListener('click', function () {
//       const extra = document.querySelector('.extra-det');
//       if (extra.style.display === 'none' || extra.style.display === '') {
//         extra.style.display = 'inline';
//         this.textContent = 'Show Less -';
//       } else {
//         extra.style.display = 'none';
//         this.textContent = 'Show More +';
//       }
//     });
      // detaile page js end

      // myaccount js strat


       function toggleDropdown(el) {
    const dropdown = el.nextElementSibling;
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
      if (menu !== dropdown) menu.style.display = 'none';
    });
    dropdown.style.display = dropdown.style.display === 'flex' ? 'none' : 'flex';
  }

  function editAddress() {
    Swal.fire('Edit Clicked', 'You can add your edit logic here.', 'info');
  }

  function deleteAddress(el) {
    Swal.fire({
      title: 'Are you sure?',
      text: 'Do you want to delete this address?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#ff4d00',
      cancelButtonColor: '#aaa',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire('Deleted!', 'Your address has been deleted.', 'success');
        el.closest('.address-card').remove();
      }
    });
  }

      // myaccount js end

  function openPataSidebar() {
    document.getElementById("pataSidebar").classList.add("active");
    setTimeout(initMap, 100); // Delay to ensure sidebar is visible
  }

  function closePataSidebar() {
    document.getElementById("pataSidebar").classList.remove("active");
  }

  document.addEventListener("DOMContentLoaded", function () {
    const homeBtn = document.getElementById("pataHomeBtn");
    const workBtn = document.getElementById("pataWorkBtn");

    homeBtn.addEventListener("click", function () {
      homeBtn.classList.add("active");
      workBtn.classList.remove("active");
    });

    workBtn.addEventListener("click", function () {
      workBtn.classList.add("active");
      homeBtn.classList.remove("active");
    });
  });




