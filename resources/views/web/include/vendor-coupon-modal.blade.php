<div class="xyz-modal-overlay" id="couponModalxyz" style="display: flex;">
    <div class="xyz-modal">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><b>Coupons</b></h5>
            <button class="xyz-close" onclick="hideModal()">&times;</button>
        </div>

        @forelse($coupons as $coupon)
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
                        <a href="#" class="apply-coupon-btn"
                            data-code="{{ $coupon->code }}"
                            data-vendor-id="{{ $coupon->created_by_id }}"
                            style="text-decoration: none; color:red; font-size:11px;">
                            Apply
                        </a>

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
        @empty
            <p class="text-center">No coupons available for this vendor.</p>
        @endforelse
    </div>
</div>
