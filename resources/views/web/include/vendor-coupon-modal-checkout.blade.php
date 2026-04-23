@php
    $vendorId = $vendorId ?? request()->get('vendor_id', 0);
@endphp
<div class="xyz-modal-overlay" id="couponModalxyz-{{ $vendorId }}" data-vendor-id="{{ $vendorId }}" onclick="if(event.target === this) { if(typeof window.hideModal === 'function') { window.hideModal({{ $vendorId }}); } }" style="display: none; justify-content: center; align-items: center;">
    <div class="xyz-modal" onclick="event.stopPropagation()">
        <div class="d-flex justify-content-between align-items-center xyz-modal-header">
            <h5 class="mb-0"><b>Coupons</b></h5>
            <button type="button" class="xyz-close" onclick="(function() { const vid = {{ $vendorId }}; if(typeof window.hideModal === 'function') { window.hideModal(vid); } else { $('#couponModalxyz-' + vid).fadeOut(200, function() { $(this).css('display', 'none'); }); } })(); return false;" aria-label="Close">&times;</button>
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
                            <div class="mt-1">
                                <button type="button" class="apply-coupon-btn xyz-coupon-apply-btn"
                                    data-code="{{ $coupon->code }}"
                                    data-vendor-id="{{ $coupon->created_by_id }}">
                                    Apply
                                </button>
                            </div>
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
    // Modal functions are handled in checkoutdelivery.blade.php
    // Direct button binding will be done when modal loads
</script>
