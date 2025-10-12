<div class="xyz-modal-overlay" id="couponModalxyz" style="display: none; justify-content: center; align-items: center;">
    <div class="xyz-modal">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><b>Coupons</b></h5>
            <button class="xyz-close" onclick="hideModal()">&times;</button>
        </div>

        @if(isset($coupons) && $coupons->isNotEmpty())
            @foreach($coupons as $coupon)
                <div class="xyz-coupon-row row m-2">
                    <div class="col-6">
                        <h6>
                            <strong>
                                @if($coupon->discount_type == 'percentage')
                                    {{ $coupon->discount_value }}% OFF
                                @else
                                    ₹{{ $coupon->discount_value }} OFF
                                @endif
                            </strong>
                        </h6>
                        <div style="color: green;">Min Order: ₹{{ $coupon->min_order_amount ?? 0 }}</div>
                        <small>Use Code: <b>{{ $coupon->code }}</b></small>
                        <div>
                            {{-- <a href="#" class="apply-coupon-btn"
                                data-code="{{ $coupon->code }}"
                                data-vendor-id="{{ $coupon->created_by_id }}"
                                style="text-decoration: none; color:red; font-size:11px;">
                                Apply
                            </a> --}}
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="logo" class="xyz-coupon-logo">
                        <div>
                            <small>
                                EXPIRES {{ \Carbon\Carbon::parse($coupon->expires_at)->format('d/m') }}
                            </small>
                        </div>
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
          // Show the modal
          $('#couponModalxyz').fadeIn();

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
