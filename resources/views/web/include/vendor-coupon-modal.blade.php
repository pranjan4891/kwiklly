<div class="xyz-modal-overlay" id="couponModalxyz" style="display: none; justify-content: center; align-items: center;">
    <div class="xyz-modal">
        <div class="d-flex justify-content-between align-items-center xyz-modal-header">
            <h5 class="mb-0"><b>Coupons</b></h5>
            <button type="button" class="xyz-close" onclick="hideModal()" aria-label="Close">&times;</button>
        </div>

        @if(isset($coupons) && $coupons->isNotEmpty())
            @foreach($coupons as $coupon)
                <div class="xyz-coupon-row">
                    <div class="row align-items-start g-2 xyz-coupon-row-inner">
                        <div class="col-7">
                            <h6 class="xyz-coupon-discount mb-0">
                                <strong>
                                    @if($coupon->discount_type == 'percentage')
                                        {{ (int) round($coupon->discount_value) }}% OFF
                                    @else
                                        ₹{{ number_format($coupon->discount_value, 2) }} OFF
                                    @endif
                                </strong>
                            </h6>
                            <div class="xyz-coupon-min-order">Min Order: ₹{{ number_format($coupon->min_order_amount ?? 0, 2) }}</div>
                            <small class="xyz-coupon-code">Use Code: <b>{{ $coupon->code }}</b></small>
                        </div>
                        <div class="col-5 text-end">
                            <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="logo" class="xyz-coupon-logo">
                            <div>
                                <small class="xyz-coupon-expiry d-block">
                                    EXPIRES {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m') }}
                                </small>
                                <small class="xyz-coupon-expiry-date d-block">
                                    {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="xyz-coupon-applied-on">
                        @php
                            $appliedItems = [];

                            if ($coupon->products && $coupon->products->count() > 0) {
                                foreach ($coupon->products->take(3) as $product) {
                                    $appliedItems[] = $product->title;
                                }
                                if ($coupon->products->count() > 3) {
                                    $appliedItems[] = '... and ' . ($coupon->products->count() - 3) . ' more';
                                }
                            }

                            if ($coupon->categories && $coupon->categories->count() > 0) {
                                foreach ($coupon->categories as $category) {
                                    $appliedItems[] = $category->name;
                                }
                            }

                            if ($coupon->subcategories && $coupon->subcategories->count() > 0) {
                                foreach ($coupon->subcategories as $subcategory) {
                                    $appliedItems[] = $subcategory->sub_cat_name;
                                }
                            }

                            if (empty($appliedItems)) {
                                $appliedItems[] = 'All Products';
                            }
                        @endphp
                        <small class="xyz-coupon-applied-line">*Applied On: {{ implode(', ', $appliedItems) }}</small>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center xyz-modal-empty mb-0">No coupons available for this vendor.</p>
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
          $('#couponModalxyz').replaceWith(res.html);
          var modal = document.getElementById('couponModalxyz');
          if (modal) {
            modal.style.display = 'flex';
            modal.style.justifyContent = 'center';
            modal.style.alignItems = 'center';
          }
        },
        error: function(xhr) {
          console.error('Failed to load coupons:', xhr);
          alert('Could not load coupons for this vendor.');
        }
      });
    }
  </script>
