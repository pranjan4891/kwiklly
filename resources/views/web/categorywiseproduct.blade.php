@extends('web.include.main')
@section('content')

<!-- Add this script to handle the progress bar -->

<section class="extrapadding">
   <div class="xyz-banner mt-3 py-5" style="background-color: #3b6939;">
      <div class="xyz-gradient">
         <h3 class="text-center">{{ $category->name }}</h3>
      </div>
   </div>
</section>


<!-- second section start  -->
<section>
   <div class="container mt-4 headingde">
      <h3>Inspiration for your order</h3>
      <div class="row">
         <div class="col-md-3">
            <div class="sidebarde">
               <ul>
                  @foreach($subcategories as $subItem)
                  <li class="sidebar-itemde {{ (isset($subcategory) && (int) $subItem->id === (int) $subcategory->id) ? 'active' : '' }}">
                     <img src="{{ asset('public/uploads/subcategories/'.$subItem->image) }}">
                     <a href="javascript:void(0)" class="text-decoration-none text-dark js-categorywise-subcat-ajax" role="button"
                        data-category-id="{{ $category->id }}"
                        data-subcategory-id="{{ $subItem->id }}">
                     {{ $subItem->sub_cat_name }}
                     </a>
                  </li>
                  @endforeach
               </ul>
            </div>
         </div>
         <!-- Mobile Sidebar as Horizontal Slider -->
         <div class="mobile-sidebar d-block d-md-none">
            @foreach($subcategories as $subItem)
            <a href="javascript:void(0)" class="text-decoration-none text-dark js-categorywise-subcat-ajax" role="button"
               data-category-id="{{ $category->id }}"
               data-subcategory-id="{{ $subItem->id }}">
               <div class="sidebar-itemde {{ (isset($subcategory) && (int) $subItem->id === (int) $subcategory->id) ? 'active' : '' }}">
                  <img src="{{ asset('public/uploads/subcategories/'.$subItem->image) }}" alt="{{ $subItem->sub_cat_name }}">
                  <div>{{ $subItem->sub_cat_name }}</div>
               </div>
            </a>
            @endforeach
         </div>
        <div class="col-md-9 fixedheight" id="products-section">
            @include('web.partials.categorywise_products_grid', ['products' => $products ?? collect()])
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
            // URL format: /category/{category_id}/{subcategory_id}
            const pathParts = window.location.pathname.split('/').filter(part => part !== '');
            const hasSubcategory = pathParts.length >= 3 && pathParts[2] !== undefined && pathParts[2] !== '';

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
