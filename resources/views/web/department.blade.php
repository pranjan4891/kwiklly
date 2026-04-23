@extends('web.include.main')

@section('content')
<style>
/* Department hero — mobile: same card treatment as explore store (max 400px wide, centered, rounded) */
@media (max-width: 767.98px) {
    section.department-hero-wrap .department-page-banner {
        width: min(400px, 100%);
        max-width: 100%;
        margin-left: auto;
        margin-right: auto;
        border-radius: 12px;
        min-height: 0 !important;
        height: auto;
        max-height: none;
        overflow: visible;
        background-size: cover !important;
        background-position: center center !important;
        padding: calc(10px + env(safe-area-inset-top, 0px)) 12px 14px !important;
        display: block !important;
    }

    section.department-hero-wrap .department-page-banner::before {
        border-radius: 12px;
    }

    section.department-hero-wrap .department-page-banner .store-infode {
        margin-top: 40px;
        max-width: 100%;
        position: relative;
        top: 0 !important;
        left: 0 !important;
        padding-right: 0 !important;
        z-index: 2;
    }

    section.department-hero-wrap .department-page-banner .store-infode h2 {
        font-size: 0.95rem !important;
        line-height: 1.2 !important;
        margin-bottom: 0.35rem !important;
    }

    section.department-hero-wrap .department-page-banner .store-infode p {
        font-size: 10px !important;
        line-height: 1.25 !important;
        margin-bottom: 0.5rem !important;
    }

    section.department-hero-wrap .department-page-banner .time-container {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
        margin-top: 8px;
        width: 100%;
    }

    section.department-hero-wrap .department-page-banner .time-boxde {
        font-size: 9px !important;
        padding: 2px 8px !important;
        margin-top: 0 !important;
        border-radius: 12px;
        line-height: 1.25;
        width: auto;
        max-width: 100%;
    }

    section.department-hero-wrap .department-page-banner select.time-boxde {
        min-width: 0 !important;
        width: 100%;
    }

    section.department-hero-wrap .department-page-banner .online-statusde {
        margin-top: 8px !important;
        margin-bottom: 8px !important;
        font-size: 11px !important;
        padding: 6px 12px !important;
    }

    section.department-hero-wrap .department-page-banner .coupon-boxde {
        margin-top: 10px !important;
        padding: 10px 12px !important;
        border-radius: 10px;
    }

    section.department-hero-wrap .department-page-banner .coupon-headerde h4 {
        font-size: 18px !important;
    }

    section.department-hero-wrap .department-page-banner .coupon-headerde img {
        height: 26px !important;
        width: auto !important;
    }

    section.department-hero-wrap .department-page-banner .coupon-leftde p,
    section.department-hero-wrap .department-page-banner .coupon-leftde span,
    section.department-hero-wrap .department-page-banner .coupon-rightde p {
        font-size: 10px !important;
        line-height: 1.25 !important;
    }
}
</style>
<!-- first section start  -->
<section class="extrapadding department-hero-wrap">
    <div id="vendor-header">
        {{-- Initial render of the header via partial --}}
        @include('web.partials.vendor_header', [
            'branches' => $branches,
            'selectedVendor' => $selectedVendor,
            'currentDay' => $currentDay ?? now()->format('l'),
            'currentTime' => $currentTime ?? null,
            'isOpen' => $isOpen ?? false,
        ])
    </div>
</section>
<!-- first section end  -->

<!-- second section start  -->
<section>
<div class="container mt-1 headingde">
    <h3>Inspiration for your order</h3>
    <div class="row">
        <div class="col-md-3 d-none d-md-block">
            <div class="sidebarde">
                <ul>
                    <!-- <li class="sidebar-itemde active" data-subcategory="all" onclick="filterBySubcategory('all')">All</li> -->
                    @foreach($subcategories as $subcategory)
                    <li class="sidebar-itemde" data-subcategory="{{ $subcategory->id }}">
                        <img src="{{ asset('public/uploads/subcategories/'.$subcategory->image) }}">
                        <a href="javascript:void(0)" onclick="filterBySubcategory({{ $subcategory->id }})" class="text-decoration-none text-dark">
                            {{ $subcategory->sub_cat_name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Mobile Sidebar as Horizontal Slider -->
        <div class="mobile-sidebar d-block d-md-none">
            <!-- <div class="sidebar-itemde active" data-subcategory="all" onclick="filterBySubcategory('all')">
                <div style="font-weight: 700; font-size: 14px;">All </div>
            </div> -->
            @foreach($subcategories as $subcategory)
            <div class="sidebar-itemde" data-subcategory="{{ $subcategory->id }}" onclick="filterBySubcategory({{ $subcategory->id }})">
                <img src="{{ asset('public/uploads/subcategories/'.$subcategory->image) }}" alt="{{ $subcategory->sub_cat_name }}">
                <div>{{ $subcategory->sub_cat_name }}</div>
            </div>
            @endforeach
        </div>

        <div class="col-md-9 fixedheight" id="products-section">
            <div class="row pt-1 px-md-3 px-1" id="products-container">
                {{-- Initial render of products via partial --}}
                @include('web.partials.products', [
                    'products' => $products,
                    'selectedVendor' => $selectedVendor
                ])
            </div>
        </div>
    </div>
</div>

</section>
<!-- second section end  -->

<script>
    // Change branch: refresh header + products; keep current subcategory filter
    function changeBranch(branchId) {
        const productsContainer = document.getElementById('products-container');
        const vendorHeader = document.getElementById('vendor-header');

        // remember active subcategory
        const activeEl = document.querySelector('.sidebar-itemde.active');
        const activeSubcategory = activeEl ? activeEl.getAttribute('data-subcategory') : 'all';

        // loader
        productsContainer.innerHTML =
            '<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

        fetch(`{{ route('department.products') }}?branch=${encodeURIComponent(branchId)}`)
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    productsContainer.innerHTML = '<div class="col-12"><div class="alert alert-danger">Error loading products</div></div>';
                    return;
                }

                // Swap header and products
                vendorHeader.innerHTML = data.header;
                productsContainer.innerHTML = data.html;

                // Reapply the current subcategory filter to new DOM
                if (activeSubcategory) {
                    filterBySubcategory(activeSubcategory);
                }
            })
            .catch(() => {
                productsContainer.innerHTML = '<div class="col-12"><div class="alert alert-danger">Error loading products</div></div>';
            });
    }

    // Filter by subcategory (client-side show/hide)
    function filterBySubcategory(subcategoryId) {
        // Update active on both desktop + mobile lists
        document.querySelectorAll('.sidebar-itemde').forEach(i => i.classList.remove('active'));
        document.querySelectorAll(`.sidebar-itemde[data-subcategory="${subcategoryId}"]`).forEach(i => i.classList.add('active'));

        document.querySelectorAll('.product-item').forEach(product => {
            if (subcategoryId === 'all' || String(product.getAttribute('data-subcategory')) === String(subcategoryId)) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });

        // Scroll to products section on mobile after filtering
        if (window.innerWidth < 768) {
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
            }, 150);
        }
    }

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

            // Check if we came from a subcategory click (check sessionStorage)
            const cameFromSubcategory = sessionStorage.getItem('subcategory_clicked') === 'true';
            
            if (cameFromSubcategory) {
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

    // Mark subcategory click before navigation (for mobile sidebar)
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile sidebar items (they use onclick, so we need to wrap it)
        const mobileSidebarItems = document.querySelectorAll('.mobile-sidebar .sidebar-itemde');
        mobileSidebarItems.forEach(function(item) {
            const originalOnClick = item.getAttribute('onclick');
            if (originalOnClick) {
                item.addEventListener('click', function(e) {
                    // Set flag before navigation (only for mobile)
                    if (window.innerWidth < 768) {
                        sessionStorage.setItem('subcategory_clicked', 'true');
                    }
                });
            }
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

