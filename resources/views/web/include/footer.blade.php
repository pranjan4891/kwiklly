@include('web.include.variant_modal')
   @include('web.include.vendor-coupon-modal')
   @include('web.partials.location_error_modal')

    <!-- Footer Section -->
    <footer class="footer-section extramarginfooter" >
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="row">
                      <!-- Logo & Social Links -->
                        <div class="col-md-12">
                            <div class="footer-logo">
                                <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="Kwikly Logo">
                            </div>
                            <div class="footer-social">
                                <a href="javascript:void(0)"><i class="fab fa-linkedin"></i></a>
                                <a href="javascript:void(0)"><i class="fab fa-facebook"></i></a>
                                <a href="javascript:void(0)"><i class="fab fa-instagram"></i></a>
                                <a href="javascript:void(0)"><img src="{{ asset('public/assets/website/images/logotwiter.png')}}" alt="" style="height: 22px;
                                margin-bottom: 5px;"></a>
                            </div>
                        </div>
                    </div>
              </div>
              <div class="col-md-8">
                <div class="row categorypadding">
                    <div class="col-md-4 col-6 footer-links">
                        <h5>Resources</h5>
                        <ul>
                            <li><a href="{{route('vendor.login')}}">Vendor Login</a></li>
                            <li><a href="{{route('vendor.signup')}}">Vendor Registration</a></li>
                        </ul>
                    </div>

                    <!-- Company -->
                    <div class="col-md-4 col-6 footer-links">
                        <h5>Company</h5>
                        <ul>
                            <li><a href="{{ route('policy.show', 'privacy-policy') }}" onclick="return redirectWithLocation(this.href)">Privacy Policy</a></li>
                            <li><a href="{{ route('policy.show', 'terms-condition') }}" onclick="return redirectWithLocation(this.href)">Terms & Condition</a></li>
                            <li><a href="{{ route('policy.show', 'return-policy') }}" onclick="return redirectWithLocation(this.href)">Return Policy</a></li>
                        </ul>
                    </div>

                    <!-- About -->
                    <div class="col-md-4 col-12 footer-links">
                        <h5>About</h5>
                        <ul>
                            <li><a href="{{route('aboutus')}}" onclick="return redirectWithLocation(this.href)">About Us</a></li>
                           <li><a href="#" id="newconOpen">Contact Us</a></li>
                        </ul>
                    </div>
                    </div>
                    <hr class="breakdown">

                    @php
                        // Point in polygon function (same as HomeController)
                        function pointInPolygon($lat, $lng, $polygon) {
                            if (is_array($polygon)) {
                                $polygon = json_encode($polygon);
                            }
                            $inside = false;
                            $x = $lng;
                            $y = $lat;
                            $points = collect(json_decode($polygon, true));
                            $n = $points->count();

                            for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
                                $xi = $points[$i]['lng'];
                                $yi = $points[$i]['lat'];
                                $xj = $points[$j]['lng'];
                                $yj = $points[$j]['lat'];

                                $intersect = (($yi > $y) != ($yj > $y))
                                    && ($x < ($xj - $xi) * ($y - $yi) / (($yj - $yi) ?: 1e-10) + $xi);
                                if ($intersect) $inside = !$inside;
                            }
                            return $inside;
                        }

                        // Location-based category filtering like index.blade.php
                        // Get user's current location
                        $lat = request()->input('latitude') ?? (isset($_GET['latitude']) ? $_GET['latitude'] : null);
                        $lng = request()->input('longitude') ?? (isset($_GET['longitude']) ? $_GET['longitude'] : null);

                        // Filter footer categories based on location-vendors with products
                        $filteredFooterCategories = $footerCategories;

                        if ($lat && $lng) {
                            // 1) Delivery location: vendors/branches whose delivery area contains user's point
                            $deliveryLocations = \App\Models\DeliveryLocation::where('is_active', 1)->where('is_deleted', 0)->whereNotNull('delivery_lat_long')->get();
                            $vendorIds = collect();
                            foreach ($deliveryLocations as $loc) {
                                $polygon = $loc->delivery_lat_long;
                                if (empty($polygon)) continue;
                                $points = is_array($polygon) ? $polygon : (json_decode($polygon, true) ?: []);
                                if (count($points) < 3) continue;
                                if (pointInPolygon($lat, $lng, $polygon)) {
                                    $vendorIds->push($loc->vendor_id);
                                }
                            }
                            $vendorIds = $vendorIds->unique()->values();
                            // Master location fallback commented - ab sirf delivery location se dikhaye
                            // if ($vendorIds->isEmpty()) {
                            //     $masterLocations = \App\Models\MasterLocation::where('is_active', 1)->where('is_deleted', 0)->get();
                            //     $insideLocation = $masterLocations->first(function($loc) use ($lat, $lng) {
                            //         return pointInPolygon($lat, $lng, $loc->lat_long);
                            //     });
                            //     if ($insideLocation) {
                            //         $vendorIds = \App\Models\VendorAdmin::where('status', '1')->where('is_active', '1')->whereNull('deleted_at')->whereNotNull('latitude')->whereNotNull('longitude')->get()->filter(function ($v) use ($insideLocation) {
                            //             return pointInPolygon($v->latitude, $v->longitude, $insideLocation->lat_long);
                            //         })->pluck('id')->values();
                            //     }
                            // }
                            if ($vendorIds->count() > 0) {
                                $categoryIdsWithProducts = \App\Models\Product::whereIn('vendor_id', $vendorIds)
                                    ->where('is_active', 1)
                                    ->where('is_deleted', 0)
                                    ->pluck('category_id')
                                    ->unique()
                                    ->filter();
                                $filteredFooterCategories = $footerCategories->filter(function($category) use ($categoryIdsWithProducts) {
                                    return $categoryIdsWithProducts->contains($category->id);
                                });
                            }
                        }

                        $totalCategories = $filteredFooterCategories->count();

                        // Footer layout: same as index.blade.php with 3-row design
                        // First 2 rows: 3 categories each (total 6)
                        // Third row: 3 categories, remaining in dropdown
                        $maxVisibleFirstTwoRows = 6; // 2 rows × 3 categories
                        $maxVisibleThirdRow = 3;    // 3 more categories
                        $maxVisible = $maxVisibleFirstTwoRows + $maxVisibleThirdRow; // 9 total

                        // If more than 9 categories:
                        $visibleCategories = $totalCategories > $maxVisible
                            ? $filteredFooterCategories->take($maxVisible - 1)   // show first 8
                            : $filteredFooterCategories;           // else show all

                        // From 9th onward goes into dropdown
                        $moreCategories = $totalCategories > $maxVisible
                            ? $filteredFooterCategories->slice($maxVisible - 1)
                            : collect();
                    @endphp

                    @php
    function formatCategoryName($name) {
        $name = strtolower($name);
        $name = preg_replace('/\s*&\s*/', ' & ', $name);
        return ucwords($name);
    }
@endphp

                    <div class="row mt-4 footer-links hidecat">
                        <h5>Category</h5>

                        <div class="row w-100" id="footer-categories-container">
                            {{-- 8 visible categories (or fewer if total < 9) --}}
                            @foreach($visibleCategories as $category)
                                <div class="col-md-4 col-6 footer-links">
                                    <ul>
                                        <li>
                                            <a href="{{ route('allcategorywiseproduct', $category->id) }}" onclick="return redirectWithLocation(this.href)">
                                                {{ formatCategoryName($category->name) }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endforeach

                            {{-- Dropdown in 9th slot if needed --}}
                            @if($moreCategories->isNotEmpty())
                                <div class="col-md-4 col-6 footer-links">
                                    <ul>
                                        <li>
                                            <select class="shopmore" onchange="if(this.value) window.location.href=this.value">
                                                <option>Show More</option>
                                                @foreach($moreCategories as $category)
                                                    <option value="{{ route('allcategorywiseproduct', $category->id) }}">
                                                        <a onclick="return redirectWithLocation(this.href)">{{ formatCategoryName($category->name) }}</a>
                                                    </option>
                                                @endforeach
                                            </select>
                                        </li>
                                    </ul>
                                </div>
                            @endif

                            {{-- Mobile Show More (visible on small screens) --}}
                            @if($moreCategories->isNotEmpty())
                                <div class="col-12 d-md-none text-center py-3 shopmore2">
                                    <select class="shopmore-mobile" onchange="if(this.value) window.location.href=this.value">
                                        <option>Show More</option>
                                        @foreach($moreCategories as $category)
                                            <option value="{{ route('allcategorywiseproduct', $category->id) }}">
                                                <a onclick="return redirectWithLocation(this.href)">{{ formatCategoryName($category->name) }}</a>
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>




              </div>
        </div>
        <section>
            <div class="container p-0">
            <div class="row paymentspacing">
                        <!-- Payment Options -->
                        <div class="col-md-8 footer-links ">
                            <h5>We accept payment by</h5>
                            <div class="footer-payments mt-3">
                                <img src="{{ asset('public/assets/website/images/payment1.png')}}" alt="Paytm">
                                <img src="{{ asset('public/assets/website/images/payment2.png')}}" alt="RuPay">
                                <img src="{{ asset('public/assets/website/images/payment3.png')}}" alt="Visa">
                                <img src="{{ asset('public/assets/website/images/payment4.png')}}" alt="Mastercard">
                                <img src="{{ asset('public/assets/website/images/payment5.png')}}" alt="American Express">
                            </div>
                            <div class="footer-payments ">
                                <img src="{{ asset('public/assets/website/images/payment6.png')}}" alt="Paytm">
                                <img src="{{ asset('public/assets/website/images/payment7.png')}}" alt="RuPay">
                                <img src="{{ asset('public/assets/website/images/payment8.png')}}" alt="Visa">
                                <img src="{{ asset('public/assets/website/images/payment9.png')}}" alt="Mastercard">
                                <img src="{{ asset('public/assets/website/images/payment10.png')}}" alt="American Express">
                            </div>
                        </div>

                        <!-- Subscription Section -->
                        <div class="col-md-4 extrafootermargin">
                            <h5 class="py-2 textnewsletter">Get offers, discount codes and deals from Kwikly</h5>
                            <div class="footer-subscribe newformcontrol position-relative">
                                <input type="email" class="form-control" placeholder="Your Email">
                                <button class="btn btn-subscribe">Subscribe</button>
                            </div>
                        </div>
                    </div>
                <hr>
            <div class="footer-bottom ">
                Copyright &copy; 2000 - 2025 Kwikly. All rights reserved.
            </div>
            </div>
        </section>
    </footer>
     <!-- Trigger link -->

    <!-- Popup START-->

    <div class="newcon-overlay" id="newconPopup">
        <div class="newcon-popup">
            <span class="newcon-close" id="newconClose">&times;</span>
            <h2>Contact Us</h2>
            <hr>
            <form id="newconForm" method="POST" action="{{ route('send.enquiry') }}">
                @csrf
                <input type="text" id="newconName" name="name" placeholder="Name" required>
                <input type="email" id="newconEmail" name="email" placeholder="Email id" required>
                <input type="text" id="newconSubject" name="subject" placeholder="Subject" required>
                <textarea id="newconMessage" name="message" placeholder="Message" required></textarea>
                <button type="submit" class="newcon-submit">Submit</button>
                <button type="button" class="newcon-cancel" id="newconCancel">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Popup END-->


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Set global configuration variables -->
    <script>
        // Laravel routes and configuration
        window.GOOGLE_MAPS_API_KEY = "{{ env('GOOGLE_MAPS_API_KEY') }}";
        window.APP_URL = "{{ env('APP_URL') }}";
        window.CART_DATA_URL = "{{ route('cart.data') }}";
        window.CART_ADD_URL = "{{ route('cart.add') }}";
        window.CART_INCREMENT_URL = "{{ route('cart.increment') }}";
        window.CART_DECREMENT_URL = "{{ route('cart.decrement') }}";
        window.CART_CLEAR_URL = "{{ route('cart.clear') }}";
        window.GET_VARIANT_URL = "{{ url('/get-product-variants') }}";
        window.CHECK_AUTH_STATUS_URL = "{{ route('check.auth.status') }}";
        window.CHECKOUT_PAGE_URL = "{{ route('cart.view') }}";
        window.CART_VIEW_URL = "{{ route('cart.view') }}";
        window.LOGIN_URL = "{{ route('loginbyphone') }}";
        window.LOCATION_PRODUCTS_URL = "{{ route('location.products') }}";
        window.CHECK_LOCATION_IN_MASTER_URL = "{{ route('check.location.in.master') }}";
        window.SEARCH_SUGGESTIONS_URL = "{{ route('search.suggestions') }}";
        window.EXPLORE_STORE_URL = "{{ route('explorestore', ['vendor_id' => ':vendor_id', 'cat_id' => 0]) }}";
        window.CART_ICON_URL = "{{ asset('public/assets/website/images/cart.svg') }}";
        window.MARKER_IMAGE_URL = "{{ asset('public/loc.png') }}";
        window.HOME_URL = "{{ url('/') }}";
        window.IS_LOGGED_IN = {{ auth()->check() ? 'true' : 'false' }};
        window.ADDRESS_STORE_URL = "{{ route('address.store') }}";
        window.ADDRESS_LIST_URL = "{{ route('address.list') }}";
        window.ADDRESS_SELECT_URL_TEMPLATE = "{{ url('/address/select/:id') }}";
        window.LOGIN_PAGE_URL = "{{ route('loginbyphone') }}";
    </script>

    <!--------------- CUSTOM JAVASCRIPT START ----------------->
    <!-- Location related scripts - MUST load before Google Maps API -->
    <script src="{{ versioned_asset('public/assets/website/JS/location-popup.js') }}"></script>
    <script src="{{ versioned_asset('public/assets/website/JS/location-utils.js') }}"></script>
    <script>
        document.addEventListener("submit", function (e) {
            var form = e.target;
            if (!form || form.tagName !== "FORM" || !form.action) return;
            try {
                var u = new URL(form.action, window.location.href);
                var path = (u.pathname || "").replace(/\/+$/, "") || "/";
                if (path.endsWith("/logout") || path === "/logout") {
                    if (typeof window.clearKwikllyLocationLocalStorage === "function") {
                        window.clearKwikllyLocationLocalStorage();
                    }
                }
            } catch (err) {}
        }, true);
        if (window.IS_LOGGED_IN && typeof window.promoteGuestLocationAfterLogin === "function") {
            window.promoteGuestLocationAfterLogin();
        }
    </script>
    <script src="{{ versioned_asset('public/assets/website/JS/location-detection.js') }}"></script>
    <script src="{{ versioned_asset('public/assets/website/JS/location-products.js') }}"></script>
    <script src="{{ versioned_asset('public/assets/website/JS/location-autocomplete.js') }}"></script>
    <script src="{{ versioned_asset('public/assets/website/JS/location-init.js') }}"></script>
    <script src="{{ versioned_asset('public/assets/website/JS/location-saved-flow.js') }}"></script>
    <script src="{{ versioned_asset('public/assets/website/JS/redirect-location.js') }}"></script>
    <script src="{{ versioned_asset('public/assets/website/JS/search-suggestions.js') }}"></script>
    <script src="{{ versioned_asset('public/assets/website/JS/auto-redirect.js') }}"></script>
    <script>
        window.ajaxExplorestoreProductsUrl = @json(route('ajax.explorestore.products'));
        window.ajaxCategorywiseProductsUrl = @json(route('ajax.categorywise.products'));
    </script>
    <script src="{{ versioned_asset('public/assets/website/JS/category-subcategory-ajax.js') }}"></script>

    <!-- Cart and product scripts -->
    <script src="{{ versioned_asset('public/assets/website/JS/qty-section-loading.js') }}"></script>
    <script src="{{ versioned_asset('public/assets/website/JS/cart-operations.js') }}"></script>
    <script src="{{ versioned_asset('public/assets/website/JS/product-variant-modal.js') }}"></script>
    <script src="{{ versioned_asset('public/assets/website/JS/checkout-handler.js') }}"></script>

    <!-- Other scripts -->
    <script src="{{ versioned_asset('public/assets/website/JS/contact-popup.js') }}"></script>
    <script src="{{ versioned_asset('public/assets/website/JS/footer-categories.js') }}"></script>
    <script src="{{ versioned_asset('public/assets/website/JS/hash-cleanup.js') }}"></script>
    <script src="{{ versioned_asset('public/assets/website/JS/custom.js') }}"></script>

    <!-- Google Maps API (for location autocomplete) - MUST load after location-autocomplete.js -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initAutocomplete" async defer></script>
    <!--------------- CUSTOM JAVASCRIPT END ----------------->

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    confirmButtonColor: '#d33',
                });
            });
        </script>
    @endif
    <!-- Hidden inputs for location -->
    <input type="hidden" id="latitude" name="latitude">
    <input type="hidden" id="longitude" name="longitude">
    @stack('scripts')
</body>
</html>
