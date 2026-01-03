<div class="xyz-modal-overlay" id="couponModalxyz" style="display: none; justify-content: center; align-items: center;">
    <div class="xyz-modal">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" style="font-size: 18px;"><b>Coupons</b></h5>
            <button class="xyz-close" onclick="hideModal()" style="font-size: 28px; line-height: 1; padding: 0; background: none; border: none; cursor: pointer;">&times;</button>
        </div>

        @if(isset($coupons) && $coupons->isNotEmpty())
            @foreach($coupons as $coupon)
                <div class="xyz-coupon-row" style="background-color: #f5f5f5; border-radius: 12px; padding: 16px; margin-bottom: 15px; border: 1px solid #e0e0e0;">
                    <div class="row align-items-start">
                        <div class="col-7">
                            <h6 style="margin: 0 0 8px 0; font-size: 20px; font-weight: 700; color: #000;">
                                <strong>
                                    @if($coupon->discount_type == 'percentage')
                                        {{ (int) round($coupon->discount_value) }}% OFF
                                    @else
                                        ₹{{ number_format($coupon->discount_value, 2) }} OFF
                                    @endif
                                </strong>
                            </h6>
                            <div style="color: #3B6939; font-size: 13px; font-weight: 500; margin-bottom: 6px;">Min Order: ₹{{ number_format($coupon->min_order_amount ?? 0, 2) }}</div>
                            <small style="font-size: 12px; color: #666; display: block; margin-bottom: 10px;">Use Code: <b style="color: #000; font-weight: 600;">{{ $coupon->code }}</b></small>
                        </div>
                        <div class="col-5 text-end">
                            <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="logo" class="xyz-coupon-logo" style="max-width: 50px; height: auto; margin-bottom: 10px; object-fit: contain;">
                            <div>
                                <small style="font-size: 11px; color: #333; font-weight: 500; display: block; margin-bottom: 2px;">
                                    EXPIRES {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m') }}
                                </small>
                                <small style="color: #666; font-size: 11px;">
                                    {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    {{-- Applied On Section --}}
                    <div class="xyz-coupon-applied-on" style="margin-top: 12px; padding-top: 10px; border-top: 1px solid #e0e0e0;">
                        @php
                            $appliedItems = [];
                            
                            // Check if coupon applies to products
                            if ($coupon->products && $coupon->products->count() > 0) {
                                foreach ($coupon->products->take(3) as $product) {
                                    $appliedItems[] = $product->title;
                                }
                                if ($coupon->products->count() > 3) {
                                    $appliedItems[] = '... and ' . ($coupon->products->count() - 3) . ' more';
                                }
                            }
                            
                            // Check if coupon applies to categories
                            if ($coupon->categories && $coupon->categories->count() > 0) {
                                foreach ($coupon->categories as $category) {
                                    $appliedItems[] = $category->name;
                                }
                            }
                            
                            // Check if coupon applies to subcategories
                            if ($coupon->subcategories && $coupon->subcategories->count() > 0) {
                                foreach ($coupon->subcategories as $subcategory) {
                                    $appliedItems[] = $subcategory->sub_cat_name;
                                }
                            }
                            
                            // If no specific items, show "All Products"
                            if (empty($appliedItems)) {
                                $appliedItems[] = 'All Products';
                            }
                        @endphp
                        <small style="color: #333; font-size: 11px; line-height: 1.4;">
                            *Applied On: {{ implode(', ', $appliedItems) }}
                        </small>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center">No coupons available for this vendor.</p>
        @endif
    </div>
</div>
<script>
    function showModal() {
      document.getElementById('couponModalxyz').style.display = 'flex';
    }

    function hideModal() {
      document.getElementById('couponModalxyz').style.display = 'none';
    }

    // Dedicated function for side cart coupons
    function loadSideCartCoupons(vendorId) {
      console.log('Loading coupons for vendor:', vendorId);

      $.ajax({
        url: "{{ route('coupon.vendorwise') }}",
        method: 'GET',
        data: { vendor_id: vendorId },
        success: function(res) {
          console.log('Coupons loaded successfully for vendor:', vendorId);
          // Replace the entire modal with the response HTML
          $('#couponModalxyz').replaceWith(res.html);
          // Show the modal with flex display to center it properly (same as showModal)
          var modal = document.getElementById('couponModalxyz');
          if (modal) {
            modal.style.display = 'flex';
            modal.style.justifyContent = 'center';
            modal.style.alignItems = 'center';
          }

          // Re-bind the modal script functions if needed
          // (they should be included in the response HTML)
        },
        error: function(xhr) {
          console.error('Failed to load coupons:', xhr);
          alert('Could not load coupons for this vendor.');
        }
      });
    }
  </script>
