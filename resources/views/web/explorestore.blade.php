@extends('web.include.main')
@section('content')
<style>
/* Explore store hero — mobile: readable height (no clip), top row pushed below search/safe area */
@media (max-width: 767.98px) {
  section.extrapadding .explorestore-page-banner {
    width: min(400px, 100%);
    max-width: 100%;
    margin-left: auto;
    margin-right: auto;
    border-radius: 12px;
    /* Let content define height — fixed square + overflow:hidden was clipping logo / Coupons / text */
    min-height: 0;
    height: auto;
    max-height: none;
    overflow: visible;
    background-size: cover !important;
    background-position: center center !important;
  }

  section.extrapadding .explorestore-page-banner .xyz-gradient {
    margin-top:40px;
    min-height: 0;
    height: auto;
    padding: calc(10px + env(safe-area-inset-top, 0px)) 10px 12px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    position: relative;
    z-index: 1;
  }

  section.extrapadding .explorestore-page-banner .xyz-gradient > .container {
    padding-left: var(--kw-page-gutter, 12px);
    padding-right: var(--kw-page-gutter, 12px);
    flex: 0 1 auto;
    min-height: auto;
    display: flex;
    flex-direction: column;
    position: relative;
    z-index: 2;
  }

  /* FSSAI + Coupons — extra top space so row sits clearly below navbar/search */
  section.extrapadding .explorestore-page-banner .xyz-gradient > .container > .d-flex.justify-content-between {
    margin-bottom: 0.5rem !important;
    padding-top: 8px !important;
    margin-top: 4px !important;
    flex-shrink: 0;
  }

  section.extrapadding .explorestore-page-banner .xyz-gradient > .container > .d-flex img[alt="Logo"] {
    height: 26px !important;
    width: auto !important;
  }

  section.extrapadding .explorestore-page-banner .btn.border12 {
    padding: 3px 9px !important;
    font-size: 10px !important;
    line-height: 1.2;
  }

  section.extrapadding .explorestore-page-banner .explorestore-banner-row {
    flex: 0 1 auto;
    min-height: auto;
    overflow: visible;
    margin-left: -6px;
    margin-right: -6px;
  }

  section.extrapadding .explorestore-page-banner .explorestore-banner-row > [class*="col-"] {
    padding-left: 6px;
    padding-right: 6px;
  }

  section.extrapadding .explorestore-page-banner .col7xyz,
  section.extrapadding .explorestore-page-banner .explorestore-col-main {
    margin-top: 0 !important;
    padding-bottom: 0.35rem !important;
  }

  section.extrapadding .explorestore-page-banner .col7xyz h3,
  section.extrapadding .explorestore-page-banner .explorestore-col-main h3 {
    font-size: 0.95rem !important;
    line-height: 1.2 !important;
    margin-bottom: 0.2rem !important;
  }

  section.extrapadding .explorestore-page-banner .xyz-location-text {
    font-size: 10px !important;
    gap: 5px;
    line-height: 1.25;
  }

  section.extrapadding .explorestore-page-banner .xyz-location-text i {
    font-size: 9px;
  }

  section.extrapadding .explorestore-page-banner .xyz-time-box {
    font-size: 9px !important;
    padding: 2px 8px !important;
    margin-top: 4px !important;
    border-radius: 12px;
    line-height: 1.25;
  }

  section.extrapadding .explorestore-page-banner .xyz-info-box {
    padding: 6px 8px !important;
    margin-top: 4px;
    border-radius: 8px;
  }

  section.extrapadding .explorestore-page-banner .xyz-info-box img {
    width: 28px !important;
    height: 28px !important;
  }

  section.extrapadding .explorestore-page-banner .xyz-info-box .ms-3 {
    margin-left: 0.5rem !important;
  }

  section.extrapadding .explorestore-page-banner .coupontext,
  section.extrapadding .explorestore-page-banner .coupontext b {
    font-size: 10px !important;
    line-height: 1.25 !important;
  }

  section.extrapadding .explorestore-page-banner .xyz-right-text {
    font-size: 9px !important;
    margin-top: 4px !important;
  }

  section.extrapadding .explorestore-page-banner .xyz-progress {
    height: 4px;
  }

  section.extrapadding .explorestore-page-banner #cook-section.mb-2 {
    margin-bottom: 0.35rem !important;
  }

  section.extrapadding .explorestore-page-banner .xyz-clickable-div {
    margin-top: 4px !important;
  }
}
</style>
<!-- first section start  -->
<script>
   @php
      // Get minimum values - check both fields for cook
      $cookValue1 = $vendor->minimum_order_value_for_cook ?? null;
      $cookValue2 = $vendor->minimum_order_for_cook ?? null;
      $deliveryValue = $vendor->minimum_order_value ?? null;
      
      // Use first non-null cook value, or 0
      $minimumForCook = null;
      if ($cookValue1 !== null && $cookValue1 !== '') {
          $minimumForCook = $cookValue1;
      } elseif ($cookValue2 !== null && $cookValue2 !== '') {
          $minimumForCook = $cookValue2;
      }
      
      // Convert to float and ensure numeric
      $minimumForCook = ($minimumForCook !== null && is_numeric($minimumForCook)) ? (float)$minimumForCook : 0;
      $minimumForDelivery = ($deliveryValue !== null && is_numeric($deliveryValue)) ? (float)$deliveryValue : 0;
      
      // Show section if either value is greater than 0
      $showProgressSection = ($minimumForCook > 0) || ($minimumForDelivery > 0);
   @endphp
   @if($showProgressSection)
   (function() {
       const venprourl = "{{ route('vendor.progress', ['vendor_id' => $vendor->id]) }}";
       
       function updateProgress() {
           // Check if progress section exists
           const cookSection = document.getElementById('cook-section');
           const deliverySection = document.getElementById('delivery-section');
           
           if (!cookSection && !deliverySection) {
               console.log('Progress sections not found');
               return; // Progress section not visible, skip update
           }
           
           fetch(venprourl, {
               method: 'GET',
               headers: {
                   'Accept': 'application/json',
                   'X-Requested-With': 'XMLHttpRequest'
               },
               credentials: 'same-origin'
           })
               .then(response => {
                   if (!response.ok) {
                       throw new Error('Network response was not ok: ' + response.status);
                   }
                   return response.json();
               })
               .then(data => {
                   console.log('Progress data received:', data);
                   
                   // Update cook section if it exists
                   if (cookSection) {
                       const cookAmountElement = document.getElementById('cook-amount-needed');
                       // Try multiple selectors to find progress bar
                       let cookProgressElement = cookSection.querySelector('.cook-progress');
                       if (!cookProgressElement) {
                           cookProgressElement = cookSection.querySelector('.xyz-progress-bar.cook-progress');
                       }
                       if (!cookProgressElement) {
                           cookProgressElement = document.querySelector('#cook-section .xyz-progress-bar');
                       }
                       
                       if (cookAmountElement) {
                           const amount = parseFloat(data.cook_amount_needed || 0);
                           cookAmountElement.textContent = Math.round(amount);
                           console.log('Cook amount updated:', Math.round(amount));
                       } else {
                           console.log('Cook amount element not found');
                       }
                       
                       if (cookProgressElement) {
                           const progress = parseFloat(data.cook_progress || 0);
                           const progressPercent = Math.min(Math.max(progress, 0), 100);
                           cookProgressElement.style.width = progressPercent + '%';
                           cookProgressElement.setAttribute('style', 'width: ' + progressPercent + '%');
                           console.log('Cook progress updated:', progressPercent + '%');
                       } else {
                           console.log('Cook progress element not found');
                       }
                   }

                   // Update delivery section if it exists
                   if (deliverySection) {
                       const deliveryAmountElement = document.getElementById('delivery-amount-needed');
                       // Try multiple selectors to find progress bar
                       let deliveryProgressElement = deliverySection.querySelector('.delivery-progress');
                       if (!deliveryProgressElement) {
                           deliveryProgressElement = deliverySection.querySelector('.xyz-progress-bar.delivery-progress');
                       }
                       if (!deliveryProgressElement) {
                           deliveryProgressElement = document.querySelector('#delivery-section .xyz-progress-bar');
                       }
                       
                       if (deliveryAmountElement) {
                           const amount = parseFloat(data.delivery_amount_needed || 0);
                           deliveryAmountElement.textContent = Math.round(amount);
                           console.log('Delivery amount updated:', Math.round(amount));
                       } else {
                           console.log('Delivery amount element not found');
                       }
                       
                       if (deliveryProgressElement) {
                           const progress = parseFloat(data.delivery_progress || 0);
                           const progressPercent = Math.min(Math.max(progress, 0), 100);
                           deliveryProgressElement.style.width = progressPercent + '%';
                           deliveryProgressElement.setAttribute('style', 'width: ' + progressPercent + '%');
                           console.log('Delivery progress updated:', progressPercent + '%');
                       } else {
                           console.log('Delivery progress element not found');
                       }
                   }
               })
               .catch(error => {
                   console.error('Error fetching progress:', error);
               });
       }

       // Make function globally accessible
       window.updateProgress = updateProgress;

       // Initial load - wait for DOM to be ready
       function initProgress() {
           // Wait a bit more to ensure elements are rendered
           setTimeout(function() {
               updateProgress();
               // Also update immediately if elements are already available
               if (document.getElementById('cook-section') || document.getElementById('delivery-section')) {
                   updateProgress();
               }
           }, 500);
           
           // Also try after a longer delay to ensure everything is loaded
           setTimeout(function() {
               updateProgress();
           }, 1000);
       }

       if (document.readyState === 'loading') {
           document.addEventListener('DOMContentLoaded', initProgress);
       } else {
           initProgress();
       }
       
       // Also update on window load
       window.addEventListener('load', function() {
           setTimeout(updateProgress, 200);
       });
   })();
   @endif
</script>
<section class="extrapadding">
   <div class="xyz-banner explorestore-page-banner"
      style="background: url('{{ $vendor && $vendor->business_banner
      ? asset('public/' . $vendor->business_banner)
      : asset('public/assets/website/images/default-banner.jpg') }}')
      no-repeat center center / cover;">
      <div class="xyz-gradient">
         <div class="container">
            <div class="d-flex justify-content-between align-items-start mb-2 mb-md-3 mt-0 mt-md-5 pt-2 pt-md-5">
               <img src="{{ asset('public/assets/website/images/fssai.png')}}" alt="Logo" height="40">
               <div class="text-end">
                  <button class="btn btn-light text-danger border12" onclick="showModal()"><b>Coupons</b></button>
               </div>
            </div>
            <div class="row explorestore-banner-row">
               <!-- Left column -->
               <div class="col-12 col-md-7 pb-2 pb-md-4 col7xyz explorestore-col-main">
                  <h3>{{ $vendor->business_name ?? 'No Store Found' }}</h3>
                  <div class="xyz-location-text">
                     <i class="fas fa-map-marker-alt"></i>
                     <span>{{ $vendor->business_address ?? 'Address not available' }}</span>
                  </div>
                  @php
                  $tz = config('app.timezone'); // now "Asia/Kolkata"
                  $now = \Carbon\Carbon::now($tz);
                  $currentDay = $now->format('l');
                  $currentTimeData = null;
                  if ($vendor && $vendor->store_time) {
                  $decoded = is_array($vendor->store_time) ? $vendor->store_time : json_decode($vendor->store_time, true);
                  if (is_array($decoded)) {
                  foreach ($decoded as $time) {
                  if (($time['day_name'] ?? '') === $currentDay) {
                  $currentTimeData = $time;
                  break;
                  }
                  }
                  }
                  }
                  @endphp
                  <div class="xyz-time-box">
                     @if ($currentTimeData && ($currentTimeData['status'] ?? '0') === '1')
                     @php
                     $start = \Carbon\Carbon::parse($currentTimeData['startTime'], $tz)->setDate($now->year, $now->month, $now->day);
                     $end   = \Carbon\Carbon::parse($currentTimeData['endTime'], $tz)->setDate($now->year, $now->month, $now->day);
                     if ($end->lessThanOrEqualTo($start)) {
                     $end->addDay(); // handle overnight
                     }
                     $isOpen = $now->between($start, $end);
                     @endphp
                     @if ($isOpen)
                     {{ $currentDay }} {{ $currentTimeData['startTime'] ?? '' }} - {{ $currentTimeData['endTime'] ?? '' }}
                     @else
                     {{ $currentDay }} Closed
                     @endif
                     @else
                     {{ $currentDay }} Closed
                     @endif
                  </div>
               </div>
               <!-- Right column - Show only if minimum_order_value or minimum_order_for_cook has data -->
               @php
                  // Get minimum values - check both fields for cook
                  $cookValue1 = $vendor->minimum_order_value_for_cook ?? null;
                  $cookValue2 = $vendor->minimum_order_for_cook ?? null;
                  $deliveryValue = $vendor->minimum_order_value ?? null;

                  $dy_text = $vendor->dy_text ?? null;
                  // Use first non-null cook value, or 0
                  $minimumForCook = null;
                  if ($cookValue1 !== null && $cookValue1 !== '') {
                      $minimumForCook = $cookValue1;
                  } elseif ($cookValue2 !== null && $cookValue2 !== '') {
                      $minimumForCook = $cookValue2;
                  }
                  
                  // Convert to float and ensure numeric
                  $minimumForCook = ($minimumForCook !== null && is_numeric($minimumForCook)) ? (float)$minimumForCook : 0;
                  $minimumForDelivery = ($deliveryValue !== null && is_numeric($deliveryValue)) ? (float)$deliveryValue : 0;
                  
                  // Show section if either value is greater than 0
                  $showProgressSection = ($minimumForCook > 0) || ($minimumForDelivery > 0);
               @endphp
               @if($showProgressSection)
               <div class="col-12 col-md-5">
                  <div class="xyz-info-box">
                     @if($minimumForCook > 0)
                     <div class="d-flex align-items-center mb-2" id="cook-section">
                        <img src="{{asset('public/assets/website/images/demo.png')}}" alt="Icon">
                        <div class="ms-3 w-100">
                           <div class="coupontext cook-text">Add item worth ₹<b id="cook-amount-needed">{{ round($cook_amount_needed ?? 0) }}</b> to get {{$dy_text}}</div>
                           <div class="xyz-progress mt-1">
                              <div class="xyz-progress-bar cook-progress" style="width: {{ min(max($cook_progress ?? 0, 0), 100) }}%; min-width: 0%;"></div>
                           </div>
                        </div>
                     </div>
                     @endif
                     @if($minimumForDelivery > 0)
                     <div class="xyz-clickable-div" data-state="default" onclick="changeText(this)" id="delivery-section">
                        <div class="coupontext delivery-text">Add item worth ₹<b id="delivery-amount-needed">{{ round($delivery_amount_needed ?? 0) }}</b> more to get free delivery</div>
                        <div class="xyz-progress mt-1">
                           <div class="xyz-progress-bar delivery-progress" style="width: {{ min(max($delivery_progress ?? 0, 0), 100) }}%; min-width: 0%;"></div>
                        </div>
                     </div>
                     @endif
                     <div class="xyz-right-text">*Progress Bar will reset in next order</div>
                  </div>
               </div>
               @endif
            </div>
         </div>
      </div>
   </div>
   </div>
</section>
<!-- first section end  -->
<!-- second section start  -->
<section>
   <div class="container mt-1 headingde">
      <h3>Inspiration for your order</h3>
      <div class="row">
         <div class="col-md-3">
            <div class="sidebarde">
               <ul>
                {{-- dd($subcategories) --}}
                  @forelse($subcategories as $subcategoryItem)
                  <li class="sidebar-itemde {{ isset($subcategory) && $subcategoryItem->id == $subcategory->id ? 'active' : '' }}">
                     <img src="{{ asset('public/uploads/subcategories/'.$subcategoryItem->image) }}" >
                     @if($vendor && ($subcategoryItem->category_id || isset($subcategoryItem->category)))
                     <a href="javascript:void(0)"
                        class="text-decoration-none text-dark js-explorestore-subcat-ajax"
                        role="button"
                        data-vendor-id="{{ $vendor->id }}"
                        data-category-id="{{ $subcategoryItem->category_id ?? $subcategoryItem->category->id }}"
                        data-subcategory-id="{{ $subcategoryItem->id }}">
                     {{ $subcategoryItem->sub_cat_name }}
                     </a>
                     @else
                     <span class="text-decoration-none text-dark" style="cursor: not-allowed;">
                     {{ $subcategoryItem->sub_cat_name }}
                     </span>
                     @endif
                  </li>
                  @empty
                  <li>No categories available</li>
                  @endforelse
               </ul>
            </div>
            <div class="mobile-sidebar d-block d-md-none">
                @forelse($subcategories as $subcategoryItem)
                <div class="sidebar-itemde {{ isset($subcategory) && $subcategoryItem->id == $subcategory->id ? 'active' : '' }}">
                    <img src="{{ asset('public/uploads/subcategories/'.$subcategoryItem->image) }}" alt="{{ $subcategoryItem->sub_cat_name }}">
                    @if($vendor && ($subcategoryItem->category_id || isset($subcategoryItem->category)))
                    <a href="javascript:void(0)"
                        class="text-decoration-none text-dark js-explorestore-subcat-ajax"
                        role="button"
                        data-vendor-id="{{ $vendor->id }}"
                        data-category-id="{{ $subcategoryItem->category_id ?? $subcategoryItem->category->id }}"
                        data-subcategory-id="{{ $subcategoryItem->id }}">
                        <div>{{ $subcategoryItem->sub_cat_name }}</div>
                    </a>
                    @else
                    <div style="cursor: not-allowed;">
                        {{ $subcategoryItem->sub_cat_name }}
                    </div>
                    @endif
                </div>
                @empty
                <div>No categories available</div>
                @endforelse
            </div>

         </div>
         <div class="col-md-9 fixedheight" id="products-section">
            @include('web.partials.explorestore_products_grid', ['products' => $products ?? collect()])
         </div>
      </div>
   </div>
</section>
<!-- second section end  -->
<script>
    // Make mobile sidebar sticky on scroll
    (function() {
        function initStickySidebar() {
            const mobileSidebar = document.querySelector('.mobile-sidebar');
            const productsContainer = document.querySelector('.fixedheight');
            
            if (!mobileSidebar) return;
            
            let sidebarOffsetTop = 0;
            let isSticky = false;
            
            function calculateOffset() {
                // Get the sidebar's position relative to the document
                const rect = mobileSidebar.getBoundingClientRect();
                sidebarOffsetTop = rect.top + window.pageYOffset;
            }
            
            function updateSidebarPosition() {
                // Only for mobile
                if (window.innerWidth >= 768) {
                    if (isSticky) {
                        mobileSidebar.classList.remove('sticky');
                        if (productsContainer) {
                            productsContainer.style.paddingTop = '';
                        }
                        isSticky = false;
                    }
                    return;
                }
                
                // Calculate offset on first run
                if (sidebarOffsetTop === 0) {
                    calculateOffset();
                }
                
                const scrollY = window.pageYOffset || document.documentElement.scrollTop;
                const headerHeight = 90; // Header height in pixels
                
                // Make sticky when scrolled past the sidebar's original position
                // Sidebar will stick below header at 90px from top
                if (scrollY >= sidebarOffsetTop) {
                    if (!isSticky) {
                        mobileSidebar.classList.add('sticky');
                        // Add padding to products container to prevent content from going under sticky sidebar
                        // Header (90px) + Sidebar height (approx 70px) = 160px
                        if (productsContainer) {
                            productsContainer.style.paddingTop = '160px';
                        }
                        isSticky = true;
                    }
                } else {
                    if (isSticky) {
                        mobileSidebar.classList.remove('sticky');
                        if (productsContainer) {
                            productsContainer.style.paddingTop = '';
                        }
                        isSticky = false;
                    }
                }
            }
            
            // Wait for page to be fully loaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(function() {
                        calculateOffset();
                        updateSidebarPosition();
                    }, 200);
                });
            } else {
                setTimeout(function() {
                    calculateOffset();
                    updateSidebarPosition();
                }, 200);
            }
            
            // Update on scroll
            window.addEventListener('scroll', updateSidebarPosition, { passive: true });
            
            // Recalculate on resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    sidebarOffsetTop = 0;
                    calculateOffset();
                    updateSidebarPosition();
                }, 150);
            });
        }
        
        // Initialize
        initStickySidebar();
    })();

    // Scroll to products section on mobile when subcategory is clicked
    (function() {
        function scrollToProducts() {
            // Only on mobile view
            if (window.innerWidth >= 768) {
                return;
            }

            // Check if URL has subcategory parameter (meaning user clicked on subcategory)
            // URL format: /subcategory/{vendor_id}/{category_id}/{subcategory_id}
            const pathParts = window.location.pathname.split('/').filter(part => part !== '');
            const hasSubcategory = pathParts.length >= 4 && pathParts[3] !== undefined && pathParts[3] !== '';

            // Also check if we came from a subcategory link (check sessionStorage)
            const cameFromSubcategory = sessionStorage.getItem('subcategory_clicked') === 'true';
            
            if (hasSubcategory || cameFromSubcategory) {
                // Clear the flag
                sessionStorage.removeItem('subcategory_clicked');
                
                // Wait for page to fully load and render
                setTimeout(function() {
                    const productsSection = document.getElementById('products-section');
                    const mobileSidebar = document.querySelector('.mobile-sidebar');
                    
                    if (productsSection) {
                        // Calculate scroll position: after sticky menu
                        const headerHeight = 90; // Header height
                        const sidebarHeight = mobileSidebar ? mobileSidebar.offsetHeight : 70;
                        const stickyMenuHeight = headerHeight + sidebarHeight;
                        
                        // Get the position of products section relative to document
                        const productsRect = productsSection.getBoundingClientRect();
                        const productsTop = productsRect.top + window.pageYOffset;
                        
                        // Calculate where sticky menu will be (below header)
                        const stickyMenuTop = headerHeight;
                        
                        // Scroll to show products section just below sticky menu with proper spacing
                        // We want products section to start after sticky menu + some padding
                        const scrollPosition = productsTop - stickyMenuHeight - 15; // 15px extra spacing for better visibility
                        
                        // Smooth scroll to products section
                        window.scrollTo({
                            top: Math.max(0, scrollPosition), // Ensure not negative
                            behavior: 'smooth'
                        });
                    }
                }, 600); // Wait a bit more for page to fully render
            }
        }

        // Run on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(scrollToProducts, 100);
            });
        } else {
            setTimeout(scrollToProducts, 100);
        }
    })();

    // Mark subcategory click before navigation (for both mobile and desktop sidebar links)
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile sidebar links
        const mobileSidebarLinks = document.querySelectorAll('.mobile-sidebar a');
        mobileSidebarLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                // Set flag before navigation (only for mobile)
                if (window.innerWidth < 768) {
                    sessionStorage.setItem('subcategory_clicked', 'true');
                }
            });
        });

        // Desktop sidebar links (optional - for consistency)
        const desktopSidebarLinks = document.querySelectorAll('.sidebarde a');
        desktopSidebarLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                // Set flag before navigation (only for mobile)
                if (window.innerWidth < 768) {
                    sessionStorage.setItem('subcategory_clicked', 'true');
                }
            });
        });
    });
</script>

@endsection
