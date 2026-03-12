
@extends('web.include.main')
@section('content')

<style>
    /* ===============================
   Global Font Feel
=================================*/
.product-details-section {
    font-family: 'Inter', 'Poppins', sans-serif;
    color: #1c1c1c;
}

/* ===============================
   Breadcrumb
=================================*/
.breadcrumb-product {
    font-size: 13px;
    font-weight: 400;
    color: #7a7a7a;
    letter-spacing: 0.2px;
}

/* ===============================
   Product Title
=================================*/
.prodetailheading h4 {
    font-size: 26px;
    font-weight: 700;
    letter-spacing: -0.3px;
    margin-bottom: 4px;
    color: #1c1c1c;
}

/* Vendor */
.prodetailheading p {
    font-size: 14px;
    font-weight: 400;
    color: #686b78;
    margin-bottom: 0;
}

.prodetailheading p span {
    font-weight: 600;
}

/* ===============================
   Small Info Text (Stock / Quantity)
=================================*/
.prodetailheading h6 {
    font-size: 14px;
    font-weight: 500;
    color: #3d4152;
}

.d-flex p {
    font-size: 14px;
    font-weight: 500;
    color: #3d4152;
}

/* ===============================
   Section Headings
=================================*/
.detailheading {
    font-size: 16px;
    font-weight: 600;
    margin-top: 28px;
    margin-bottom: 12px;
    color: #1c1c1c;
    letter-spacing: 0.2px;
}

/* ===============================
   Variant Options
=================================*/
.color-option-det,
.btn-option-det {
    font-size: 14px;
    font-weight: 500;
}

/* Color as image thumbnail (Select Color) */
.color-option-variant-wrap .color-option-variant {
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    margin-bottom: 8px;
    padding: 3px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.color-option-variant-wrap .color-option-variant:hover {
    border-color: #ff6a00;
}
.color-option-variant-wrap .color-option-variant.active {
    border-color: #22c55e;
    box-shadow: 0 0 0 1px #22c55e;
}
.color-option-variant-wrap .color-option-img {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 4px;
    display: block;
}
.color-option-card { margin-right: 16px; margin-bottom: 12px; text-align: center; }
.color-option-card .color-stock-below { font-size: 12px; color: #333; margin-top: 4px; }
.color-option-card .color-stock-below .color-name-below { display: block; font-weight: 500; }
.color-option-card .color-stock-below .stock-below { color: #666; }
.variant-base-list .variant-base-item .variant-item-name { font-weight: 500; }
.variant-base-item .variant-item-stock { display: none !important; }
.color-option-text { display: inline-block; padding: 8px 12px; font-size: 13px; }

/* ===============================
   Add Button
=================================*/
.add-btn-detail {
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

/* ===============================
   Quantity Buttons
=================================*/
.qty-btn {
    font-size: 18px;
    font-weight: 600;
}

.qty-input {
    font-size: 15px;
    font-weight: 600;
}

/* ===============================
   Coupon Text
=================================*/
.coupon-det {
    font-size: 14px;
    font-weight: 500;
}

/* ===============================
   Description
=================================*/
.desc-det {
    font-size: 14px;
    font-weight: 400;
    line-height: 1.7;
    color: #3d4152;
}

.extra-det {
    font-size: 13px;
    font-weight: 400;
    color: #7a7a7a;
}

.show-more-det {
    font-size: 14px;
    font-weight: 600;
}

/* Show more / Show less (300+ chars) - inline at line end */
.product-detail-expandable-wrap {
    position: relative;
}
.product-detail-expandable-content {
    position: relative;
    margin-bottom: 0;
}
.product-detail-expandable-content.product-detail-collapsed {
    max-height: 8.5em;
    overflow: hidden;
}
.product-detail-expandable-content.product-detail-collapsed::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2.5em;
    background: linear-gradient(transparent, #fff);
    pointer-events: none;
}
.product-detail-expandable-content > *:last-child {
    margin-bottom: 0;
}
/* When collapsed: "... + Show more" just below truncated content (no overlap) */
.product-detail-expandable-wrap:has(.product-detail-collapsed) {
    max-height: 8.5em;
    overflow: visible;
}
.product-detail-expandable-wrap:has(.product-detail-collapsed) .show-more-inline {
    position: absolute;
    bottom: -1.35em;
    left: 0;
    padding-right: 4px;
    background: #fff;
}
/* When expanded: " - Show less" in flow at end of content (last line) */
.product-detail-expandable-wrap .show-more-inline,
.product-detail-expandable-wrap .show-less-inline {
    display: inline;
    margin-top: 0;
    margin-bottom: 0;
    vertical-align: baseline;
}
.product-detail-expandable-wrap .show-more-less-btn {
    font-size: 14px;
    font-weight: 600;
    color: #ff6a00;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0 0 0 2px;
    margin: 0;
    display: inline;
    vertical-align: baseline;
}
.product-detail-expandable-wrap .show-more-less-btn:hover {
    text-decoration: underline;
}
.product-detail-expandable-wrap .show-more-ellipsis {
    color: #3d4152;
}
/* ===============================
   Mobile Responsive Styling
=================================*/

@media (max-width: 768px) {

    /* Section Padding */
    .product-details-section {
        padding: 10px 0;
    }

    /* Breadcrumb */
    .breadcrumb-product {
        font-size: 12px;
        line-height: 1.4;
        margin-bottom: 10px;
    }

    /* Product Title */
    .prodetailheading h4 {
        font-size: 20px;
        font-weight: 700;
        line-height: 1.3;
    }

    /* Vendor */
    .prodetailheading p {
        font-size: 13px;
    }

    /* Quantity & Stock Row */
    .prodetailheading h6,
    .d-flex p {
        font-size: 13px;
    }

    /* Section Headings */
    .detailheading {
        font-size: 15px;
        font-weight: 600;
        margin-top: 5px;
        margin-bottom: 8px;
    }

    /* Variant Buttons */
    .color-option-det,
    .btn-option-det {
        font-size: 13px;
        padding: 6px 14px;
        margin-bottom: 8px;
    }

    /* Add Button */
    .add-btn-detail {
        font-size: 14px;
        padding: 10px 18px;
    }

    /* Quantity Controls */
    .qty-btn {
        font-size: 16px;
    }

    .qty-input {
        font-size: 14px;
    }

    /* Coupon */
    .coupon-det {
        font-size: 13px;
        padding: 8px 10px;
    }

    /* Description */
    .desc-det {
        font-size: 13px;
        line-height: 1.6;
    }

    .extra-det {
        font-size: 12px;
    }

    .show-more-det {
        font-size: 13px;
    }

    /* Improve spacing between sections */
    .product-details-section .col-md-7 {
        margin-top: 10px;
    }
}


/* Extra Small Devices */
@media (max-width: 480px) {

    .prodetailheading h4 {
        font-size: 25px;
    }

    .detailheading {
        font-size: 14px;
    }

    .color-option-det,
    .btn-option-det {
        font-size: 12px;
        padding: 6px 12px;
    }

    .add-btn-detail {
        font-size: 13px;
    }
}

/* ===============================
   Product variant list on details page (small buttons: name + stock only)
=================================*/
.product-detail-variants {
    margin-top: 16px;
    margin-bottom: 16px;
}

.product-detail-variants .detailheading {
    margin-top: 0;
    margin-bottom: 10px;
}

.variant-list-detail {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.variant-item-detail {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #eaeaea;
    background: #fff;
    cursor: pointer;
    transition: all 0.25s ease;
    min-width: 0;
}

.variant-item-detail:hover {
    border-color: #ff6a00;
    background: #fff7ed;
}

.variant-item-detail.active {
    border-color: #ff6a00;
    background: #fff3e8;
}

.variant-item-detail .variant-item-name {
    font-size: 13px;
    font-weight: 500;
    color: #222;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}

.variant-item-detail .variant-item-stock {
    font-size: 11px;
    color: #666;
    margin-top: 2px;
}

@media (max-width: 576px) {
    .variant-list-detail {
        gap: 6px;
    }
    .variant-item-detail {
        padding: 6px 10px;
    }
    .variant-item-detail .variant-item-name {
        font-size: 12px;
    }
    .variant-item-detail .variant-item-stock {
        font-size: 10px;
    }
}

/* Mobile: show price + quantity only in sticky bar, hide from main content */
@media (max-width: 768px) {
    .product-details-section .product-detail-price-wrap {
        display: none !important;
    }
    .product-details-section .qty-box:not(.qty-box-mobile) {
        display: none !important;
    }
    .newspace{
        padding-top: 25px;
    }
}

</style>
<!-- first section start  -->
<section class="extrapadding product-details-section">
<div class="container my-4">
    <div class="row">
    @php
        $breadCat = $product->category->name ?? 'Category';
        $breadSub = $product->subcategory->sub_cat_name ?? 'Subcategory';
        $breadTitle = $product->title ?? '';
        $titleCase = function($s) { return ucwords(strtolower($s), " \t\r\n\f\v-"); };
    @endphp
    <p class="text-black breadcrumb-product">Home > {{ $titleCase($breadCat) }} > {{ $titleCase($breadSub) }} > {{ $titleCase($breadTitle) }}</p>
      <!-- Product Image and Thumbnails -->
      <div class="col-md-5 py-3">
        <div class="main-img-det">
          <img src="{{ $productImages && $productImages->feature_image ? asset('public/' . $productImages->feature_image) : asset('public/assets/website/images/default.png') }}" class="img-fluid main-product-det" alt="{{ $product->title }}">
        </div>

        <!-- Thumbnail slider with arrows -->
        <div class="thumb-container-det position-relative">
          <div class="arrow-det arrow-left-det"><i class="fa fa-chevron-left"></i></div>
          <div class="thumb-det">
            @if($productImages && $productImages->product_images && is_array($productImages->product_images))
              @foreach($productImages->product_images as $index => $image)
                <img src="{{ asset('public/' . $image) }}" class="{{ $index === 0 ? 'active' : '' }}" alt="{{ $product->title }}">
              @endforeach
            @else
              <img src="{{ $productImages && $productImages->feature_image ? asset('public/' . $productImages->feature_image) : asset('public/assets/website/images/default.png') }}" class="active" alt="{{ $product->title }}">
            @endif
          </div>
          <div class="arrow-det arrow-right-det"><i class="fa fa-chevron-right"></i></div>
        </div>
      </div>

      <!-- Product Details -->
        <div class="col-md-7 py-3">
            <div class="d-flex justify-content-between">
            <div class="prodetailheading">
                <h4>{{ $product->title }}</h4>
            </div>
           <div class="detailicon d-none"><img src="{{ asset('public/assets/website/images/share.png')}}" alt=""></div>
            </div>
            <div class="prodetailheading">
             <p>by <span class="text-danger">{{ $product->vendor->business_name ?? 'Store' }}</span></p>
            </div>

            @php
                $firstVariantForDisplay = $product->variants->first();
                $attrsFirst = $firstVariantForDisplay ? (is_array($firstVariantForDisplay->attributes ?? null) ? $firstVariantForDisplay->attributes : json_decode($firstVariantForDisplay->attributes ?? '{}', true)) : [];
                $netQtyFirst = !empty($attrsFirst) ? implode(', ', $attrsFirst) : ($firstVariantForDisplay->variant_name ?? '1 unit');
            @endphp
            <!-- Share and Stock (dynamic per variant) -->
            <div class="d-flex justify-content-between mt-4 mb-2">
             <div class="prodetailheading"><h6 class="mt-1 product-detail-net-qty">Net Quantity : {{ $netQtyFirst }}</h6></div>
             <div><p class="product-detail-availability">Availability : {{ $firstVariantForDisplay && $firstVariantForDisplay->stock > 0 ? 'In Stock' : 'Out of Stock' }}</p></div>
            </div>

            @php
                $selectedVariant = $product->variants->first();
                $sellingPrice = $selectedVariant ? $selectedVariant->variant_selling_price : 0;
                $actualPrice = $selectedVariant ? $selectedVariant->variant_actual_price : 0;
                $discountPercent = ($actualPrice > $sellingPrice && $actualPrice > 0) ? (int) round((($actualPrice - $sellingPrice) / $actualPrice) * 100) : 0;
            @endphp

            <div class="d-flex justify-content-between align-items-center product-detail-price-wrap">
                <div class="">
                    <span class="pricedetail">
                        <span class="rupee-symbol">₹</span> <span class="product-detail-selling-price">{{ number_format($sellingPrice, 0) }}</span>
                    </span>
                    <span class="original-price ms-1 product-detail-actual-wrap" @if($actualPrice <= $sellingPrice) style="display:none;" @endif>
                        <span class="rupee-symbol2">₹</span> <span class="product-detail-actual-price">{{ number_format($actualPrice, 0) }}</span>
                    </span>
                    <span class="product-detail-discount-wrap" @if($discountPercent <= 0) style="display:none;" @endif><span class="badge bg-successs ms-2 product-detail-discount-badge">{{ $discountPercent }}% Off</span></span>
                    <p>(incl. of all tax)</p>
                </div>
            </div>

            @php
                $memoryVariants = collect();
                $ramVariants = collect();
                $variantBases = [];
                $defaultSelectedVariant = null;
                foreach($product->variants as $variant) {
                    $attributes = json_decode($variant->attributes ?? '{}', true);
                    $baseName = $variant->variant_name;
                    if (isset($attributes['Color'])) {
                        $pos = strrpos($variant->variant_name, ' - ');
                        $baseName = $pos !== false ? trim(substr($variant->variant_name, 0, $pos)) : $variant->variant_name;
                    }
                    if (!isset($variantBases[$baseName])) {
                        $variantBases[$baseName] = [];
                    }
                    $variantBases[$baseName][] = $variant;
                    if(isset($attributes['Memory Size'])) $memoryVariants->push($attributes['Memory Size']);
                    if(isset($attributes['RAM'])) $ramVariants->push($attributes['RAM']);
                }
                $uniqueBases = array_keys($variantBases);
                $firstBase = $uniqueBases[0] ?? null;
                $firstVariantsOfBase = $firstBase ? $variantBases[$firstBase] : [];
                $defaultSelectedVariant = !empty($firstVariantsOfBase) ? $firstVariantsOfBase[0] : null;
                $uniqueMemories = $memoryVariants->unique()->values();
                $uniqueRams = $ramVariants->unique()->values();
            @endphp

            @if(!empty($uniqueBases) && count($uniqueBases) > 0)
                @if(count($uniqueBases) === 1)
                @php
                    $singleBaseName = $uniqueBases[0];
                    $singleVariant = $variantBases[$singleBaseName][0] ?? null;
                    $singleAttrs = $singleVariant ? (is_array($singleVariant->attributes ?? null) ? $singleVariant->attributes : json_decode($singleVariant->attributes ?? '{}', true)) : [];
                    $singleAttrValues = is_array($singleAttrs) && !empty($singleAttrs) ? implode(', ', array_values($singleAttrs)) : '';
                @endphp
            @if($singleAttrValues)
            <div class="single-variant-text">
                <span class="variant-item-name text-dark">{{ $singleAttrValues }}</span>
            </div>
            @endif
                @else
            <h5 class="detailheading">Select variant</h5>
            <div class="variant-list-detail variant-base-list my-3" id="variantBaseList">
                @foreach($uniqueBases as $baseName)
                @php
                    $baseVariants = $variantBases[$baseName];
                    $firstOfBase = $baseVariants[0];
                    $vAttrs = is_array($firstOfBase->attributes ?? null) ? $firstOfBase->attributes : json_decode($firstOfBase->attributes ?? '{}', true);
                    $vKey = $product->id . '_' . $firstOfBase->id;
                @endphp
                <div class="variant-item-detail variant-base-item {{ $loop->first ? 'active' : '' }}"
                     data-variant-base="{{ e($baseName) }}"
                     data-variant-ids="{{ e(json_encode(array_map(fn($v) => $v->id, $baseVariants))) }}"
                     data-variant-id="{{ $firstOfBase->id }}"
                     data-product-id="{{ $product->id }}"
                     data-key="{{ $vKey }}"
                     data-selling="{{ $firstOfBase->variant_selling_price ?? 0 }}"
                     data-actual="{{ $firstOfBase->variant_actual_price ?? 0 }}"
                     data-stock="{{ (int) $firstOfBase->stock }}"
                     data-net-qty="{{ e(is_array($vAttrs) && !empty($vAttrs) ? implode(', ', $vAttrs) : $firstOfBase->variant_name) }}">
                    <span class="variant-item-name">{{ $baseName }}</span>
                </div>
                @endforeach
            </div>
                @endif
            @endif

            @php
                $hasColorVariants = false;
                foreach($product->variants as $v) {
                    $a = json_decode($v->attributes ?? '{}', true);
                    if (!empty($a['Color'])) { $hasColorVariants = true; break; }
                }
            @endphp
            @if($hasColorVariants)
            <h5 class="detailheading">Select Color</h5>
            <div class="d-flex flex-wrap align-items-start my-3 color-option-variant-wrap" id="colorOptionsWrap">
                @foreach($product->variants as $v)
                @php
                    $vAttrs = is_array($v->attributes ?? null) ? $v->attributes : json_decode($v->attributes ?? '{}', true);
                    $colorName = $vAttrs['Color'] ?? null;
                    if (!$colorName) continue;
                    $pos = strrpos($v->variant_name, ' - ');
                    $baseName = $pos !== false ? trim(substr($v->variant_name, 0, $pos)) : $v->variant_name;
                    $imgUrl = $v->images->isNotEmpty() ? asset('public/'.$v->images->first()->image_path) : null;
                    $vKey = $product->id . '_' . $v->id;
                    $isFirstBase = ($firstBase === $baseName);
                    $isFirstOfBase = $isFirstBase && ($v->id === ($firstVariantsOfBase[0]->id ?? null));
                @endphp
                <div class="color-option-card {{ $isFirstBase ? '' : 'd-none' }} {{ $isFirstOfBase ? 'color-first-active' : '' }}"
                     data-variant-base="{{ e($baseName) }}"
                     data-variant-id="{{ $v->id }}"
                     data-product-id="{{ $product->id }}"
                     data-key="{{ $vKey }}"
                     data-selling="{{ $v->variant_selling_price ?? 0 }}"
                     data-actual="{{ $v->variant_actual_price ?? 0 }}"
                     data-stock="{{ (int) $v->stock }}"
                     data-net-qty="{{ e(is_array($vAttrs) && !empty($vAttrs) ? implode(', ', $vAttrs) : $v->variant_name) }}">
                    <span class="color-option-det color-option-variant {{ $isFirstOfBase ? 'active' : '' }}" title="{{ $colorName }}">
                        @if($imgUrl)
                        <img src="{{ $imgUrl }}" alt="{{ $colorName }}" class="color-option-img">
                        @else
                        <span class="color-option-text">{{ $colorName }}</span>
                        @endif
                    </span>
                    <div class="color-stock-below">
                        <span class="color-name-below">{{ $colorName }}</span>
                        <span class="stock-below">Stock: {{ (int) $v->stock }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if($uniqueMemories->isNotEmpty())
            <h5 class="detailheading">Select Memory Size</h5>
            <div class="my-3">
                @foreach($uniqueMemories as $memory)
                <span class="btn-option-det {{ $loop->first ? 'active' : '' }}">{{ $memory }}</span>
                @endforeach
            </div>
            @endif

            @if($uniqueRams->isNotEmpty())
            <h5 class="detailheading">Select RAM</h5>
            <div class="my-3">
                @foreach($uniqueRams as $ram)
                <span class="btn-option-det {{ $loop->first ? 'active' : '' }}">{{ $ram }}</span>
                @endforeach
            </div>
            @endif

            @php
                $defaultVariant = $defaultSelectedVariant ?? $product->variants->first();
                $hasMultipleVariants = $product->variants->count() > 1;
                $firstVariant = $defaultVariant;
                $key = $product->id . '_' . ($firstVariant->id ?? 0);

                if (auth()->check()) {
                    $cartItem = \App\Models\CartItem::where([
                        'user_id' => auth()->id(),
                        'product_id' => $product->id,
                        'variant_id' => $firstVariant->id ?? null,
                    ])->first();
                    $inCart = $cartItem !== null;
                    $quantity = $inCart ? $cartItem->quantity : 1;
                } else {
                    $cart = session('cart', []);
                    $inCart = isset($cart[$key]);
                    $quantity = $inCart ? $cart[$key]['quantity'] : 1;
                }

                $isOpen = \App\Helpers\StoreHelper::isStoreOpen($product->vendor->store_time);
            @endphp

            @php
                $hasMultipleVariants = $product->variants->count() > 1;
                $productImageUrl = $productImages && $productImages->feature_image ? asset('public/' . $productImages->feature_image) : asset('public/assets/website/images/default.png');
                $variantBasesForJson = [];
                foreach($variantBases ?? [] as $base => $vars) {
                    $variantBasesForJson[] = [ 'base' => $base, 'variant_ids' => array_map(fn($x) => $x->id, $vars) ];
                }
                $variantDataForJson = [
                    'product_id' => $product->id,
                    'product_image' => $productImageUrl,
                    'variant_bases' => $variantBasesForJson,
                    'variants' => $product->variants->map(function($v) {
                        $a = is_array($v->attributes ?? null) ? $v->attributes : json_decode($v->attributes ?? '{}', true);
                        $attrText = is_array($a) && !empty($a) ? implode(', ', $a) : ($v->variant_name ?? '1 unit');
                        $actual = $v->variant_actual_price ?? 0;
                        $selling = $v->variant_selling_price ?? 0;
                        $disc = ($actual > $selling && $actual > 0) ? (int) round((($actual - $selling) / $actual) * 100) : 0;
                        $imgUrls = $v->images && $v->images->isNotEmpty()
                            ? $v->images->map(fn($img) => asset('public/'.$img->image_path))->values()->all()
                            : [];
                        $pos = strrpos($v->variant_name ?? '', ' - ');
                        $baseName = ($pos !== false && isset($a['Color'])) ? trim(substr($v->variant_name, 0, $pos)) : ($v->variant_name ?? '');
                        return [
                            'id' => $v->id,
                            'variant_name' => $v->variant_name ?? '',
                            'variant_base' => $baseName,
                            'attributes' => $v->attributes,
                            'variant_actual_price' => $actual,
                            'variant_selling_price' => $selling,
                            'stock' => (int) $v->stock,
                            'net_qty_display' => $attrText,
                            'discount_percent' => $disc,
                            'images' => $imgUrls,
                        ];
                    })->values()->all(),
                ];
            @endphp
            @if($product->variants->count() > 0)
            <script type="application/json" id="product-detail-variants-data">{!! json_encode($variantDataForJson) !!}</script>
            @endif

            <div class="qty-box"
                data-product-id="{{ $product->id }}"
                data-variant-id="{{ $firstVariant->id ?? '' }}"
                data-key="{{ $key }}"
                data-store-open="{{ $isOpen ? '1' : '0' }}">

                @if ($isOpen)
                    {{-- ✅ Store is open --}}
                    @if ($hasMultipleVariants)
                        {{-- Variant chosen from list above; qty-box binds to selected variant via JS --}}
                        @if (!$inCart)
                        <button class="add-btn-detail add-btn"
                                data-product-id="{{ $product->id }}"
                                data-variant-id="{{ $firstVariant->id }}">
                            Add
                            <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                        </button>
                        @else
                        <div class="qty-container">
                            <button class="qty-btn minus decrement-btn" data-key="{{ $key }}">−</button>
                            <input type="text" class="qty-input quantity-input" value="{{ $quantity }}" readonly>
                            <button class="qty-btn plus increment-btn" data-key="{{ $key }}">+</button>
                        </div>
                        @endif
                    @else
                        @if (!$defaultVariant)
                            <button class="add-btn-detail" disabled>Unavailable</button>
                        @else
                            @if (!$inCart)
                                <button class="add-btn-detail add-btn"
                                        data-product-id="{{ $product->id }}"
                                        data-variant-id="{{ $firstVariant->id }}">
                                    Add
                                    <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                </button>
                            @else
                                <div class="qty-container">
                                    <button class="qty-btn minus decrement-btn" data-key="{{ $key }}">−</button>
                                    <input type="text" class="qty-input quantity-input" value="{{ $quantity }}" readonly>
                                    <button class="qty-btn plus increment-btn" data-key="{{ $key }}">+</button>
                                </div>
                            @endif
                        @endif
                    @endif
                @else
                    {{-- ❌ Store is closed --}}
                    <button class="add-btn-detail disabled" disabled>
                        Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                    </button>
                @endif
            </div>

            <!-- Coupon and Offers (only if applicable to this product) -->
            @php
                $productApplicableCoupons = \App\Models\Coupon::where('created_by_id', $product->vendor_id)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->where(function ($q) {
                        $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                    })
                    ->where(function ($q) use ($product) {
                        $q->whereIn('applies_to', ['all', null])
                            ->orWhere(function ($q2) use ($product) {
                                $q2->where('applies_to', 'product')
                                    ->whereHas('products', function ($q3) use ($product) {
                                        $q3->where('products.id', $product->id);
                                    });
                            })
                            ->orWhere(function ($q2) use ($product) {
                                $q2->where('applies_to', 'category')
                                    ->whereHas('categories', function ($q3) use ($product) {
                                        $q3->where('categories.id', $product->category_id);
                                    });
                            })
                            ->orWhere(function ($q2) use ($product) {
                                $q2->where('applies_to', 'subcategory')
                                    ->whereHas('subcategories', function ($q3) use ($product) {
                                        $q3->where('subcategories.id', $product->sub_category_id);
                                    });
                            });
                    })
                    ->limit(3)
                    ->get();
            @endphp

            @if($productApplicableCoupons->count() > 0)
            <h5 class="detailheading">Coupon & Offers</h5>
                @foreach($productApplicableCoupons as $coupon)
                <div class="coupon-det">
                    <i class="fa-solid fa-tags text-danger"></i>
                    {{ $coupon->discount_type === 'percentage' ? $coupon->discount_value . '% off' : '₹' . $coupon->discount_value . ' off' }}
                    above ₹{{ $coupon->min_order_amount }}
                </div>
                @endforeach
            @endif

            @if($product->information && trim($product->information) !== '')
            @php
                $infoHtml = \App\Helpers\StoreHelper::safeHtml($product->information);
                $infoLen = strlen(strip_tags(html_entity_decode($product->information)));
                $infoLong = $infoLen > 300;
            @endphp
            <h5 class="detailheading">Product Information</h5>
            <div class="product-detail-expandable-wrap">
                <div class="desc-det product-detail-expandable-content {{ $infoLong ? 'product-detail-collapsed' : '' }}">{!! $infoHtml !!}@if($infoLong)<span class="show-less-inline" style="display:none"> <button type="button" class="show-more-less-btn" data-state="less">- Show less</button></span>@endif</div>@if($infoLong)<span class="show-more-inline"><span class="show-more-ellipsis">... </span><button type="button" class="show-more-less-btn" data-state="more">+ Show more</button></span>@endif
            </div>
            @endif

            @php
                $descText = $product->description ?? '';
                $descHtml = \App\Helpers\StoreHelper::safeHtml($descText) ?: 'No description available.';
                $descLen = strlen(strip_tags(html_entity_decode($descText)));
                $descLong = $descLen > 300;
            @endphp
            <h5 class="detailheading newspace">Products Description</h5>
            <div class="product-detail-expandable-wrap">
                <div class="desc-det product-detail-expandable-content {{ $descLong ? 'product-detail-collapsed' : '' }}">{!! $descHtml !!}@if($descLong)<span class="show-less-inline" style="display:none"> <button type="button" class="show-more-less-btn" data-state="less">- Show less</button></span>@endif</div>@if($descLong)<span class="show-more-inline"><span class="show-more-ellipsis">... </span><button type="button" class="show-more-less-btn" data-state="more">+ Show more</button></span>@endif
            </div>

            @php
                $disclaimerText = $product->disclaimer && trim($product->disclaimer) !== '' ? $product->disclaimer : '';
            @endphp
            @if($disclaimerText)
            @php
                $disclaimerHtml = \App\Helpers\StoreHelper::safeHtml($disclaimerText);
                $disclaimerLen = strlen(strip_tags(html_entity_decode($disclaimerText)));
                $disclaimerLong = $disclaimerLen > 300;
            @endphp
            <h5 class="detailheading">Disclaimer</h5>
            <div class="product-detail-expandable-wrap">
                <div class="desc-det product-detail-expandable-content {{ $disclaimerLong ? 'product-detail-collapsed' : '' }}">{!! $disclaimerHtml !!}@if($disclaimerLong)<span class="show-less-inline" style="display:none"> <button type="button" class="show-more-less-btn" data-state="less">- Show less</button></span>@endif</div>@if($disclaimerLong)<span class="show-more-inline"><span class="show-more-ellipsis">... </span><button type="button" class="show-more-less-btn" data-state="more">+ Show more</button></span>@endif
            </div>
            @endif
        </div>
     </div>
  </div>
</section>
<!-- first section end  -->
<!-- Add-to-cart bar outside section so position:fixed works on scroll (Chrome/Safari) -->
<div class="fixed-bottom-mobile footerpricemobile">
    <div class="d-flex align-items-center justify-content-between w-100 price-container-mobile">
        <div class="product-detail-price-wrap-mobile">
             <span class="pricedetail"> 
                <span class="rupee-symbol">₹</span> <span class="product-detail-selling-price-mobile">{{ number_format($sellingPrice, 0) }}</span>
            </span>
            <span class="original-price ms-1 product-detail-actual-wrap-mobile" @if($actualPrice <= $sellingPrice) style="display:none;" @endif>
                <span class="rupee-symbol2">₹</span> <span class="product-detail-actual-price-mobile">{{ number_format($actualPrice, 0) }}</span>
            </span>
            <span class="discount-badge-mobile product-detail-discount-mobile" @if($discountPercent <= 0) style="display:none;" @endif>{{ $discountPercent }}% Off</span>
            <p class="tax-info-mobile">(incl. of all tax)</p>
        </div>
        <div class="qty-box qty-box-mobile"
             data-product-id="{{ $product->id }}"
             data-variant-id="{{ $firstVariant->id ?? '' }}"
             data-key="{{ $key }}">
        @if ($isOpen)
            @if ($hasMultipleVariants)
                @if (!$inCart)
                <button class="add-btn-mobile add-btn"
                        data-product-id="{{ $product->id }}"
                        data-variant-id="{{ $firstVariant->id }}">
                    Add
                    <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                </button>
                @else
                <div class="qty-container qty-container-mobile">
                    <button class="qty-btn qty-btn-mobile minus decrement-btn" data-key="{{ $key }}">−</button>
                    <input type="text" class="qty-input qty-input-mobile quantity-input" value="{{ $quantity }}" readonly>
                    <button class="qty-btn qty-btn-mobile plus increment-btn" data-key="{{ $key }}">+</button>
                </div>
                @endif
            @else
                    @if (!$defaultVariant)
                        <button class="add-btn-mobile" disabled>Unavailable</button>
                    @else
                        @if (!$inCart)
                        <button class="add-btn-mobile add-btn"
                                data-product-id="{{ $product->id }}"
                                data-variant-id="{{ $firstVariant->id }}">
                            Add
                            <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                        </button>
                    @else
                        <div class="qty-container qty-container-mobile">
                            <button class="qty-btn qty-btn-mobile minus decrement-btn" data-key="{{ $key }}">−</button>
                            <input type="text" class="qty-input qty-input-mobile quantity-input" value="{{ $quantity }}" readonly>
                            <button class="qty-btn qty-btn-mobile plus increment-btn" data-key="{{ $key }}">+</button>
                        </div>
                    @endif
                @endif
            @endif
        @else
            <button class="add-btn-mobile disabled" disabled>
                Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
            </button>
        @endif
        </div>
    </div>
</div>
   <!-- for mobile  -->
<!-- second section start  -->
@if($similarProducts->count() > 0)
<section>
<div class="container mt-4">
    <h4 class="pb-3 pt-1 headingclass">Similar Products</h4>
        <div class="owl-carousel owl-theme">
        @foreach($similarProducts as $similarProduct)
        @php
            $variant = $similarProduct->variants->first();
            $sellingPrice = $variant ? $variant->variant_selling_price : 0;
            $actualPrice = $variant ? $variant->variant_actual_price : 0;
            $discountPercent = ($actualPrice > $sellingPrice && $actualPrice > 0) ? round((($actualPrice - $sellingPrice) / $actualPrice) * 100) : 0;

            $attributes = json_decode($variant->attributes ?? '{}', true);
            $variantInfo = array_values($attributes)[0] ?? '';
        @endphp
        <div class="item">
                <div class="product-card">
                    @if($discountPercent > 0)
                    <span class="discount-label">{{ $discountPercent }}% Off</span>
                    @endif
                    <a href="{{ route('productdetails', ['slug' => $similarProduct->slug]) }}">
                        <img src="{{ $similarProduct->featureImage ? asset('public/' . $similarProduct->featureImage->feature_image) : asset('public/assets/website/images/default.png') }}" class="product-image" alt="{{ $similarProduct->title }}">
                    </a>
                    <div class="product-title">{{ $similarProduct->title }}</div>
                    <div class="product-info">{{ $variantInfo }}</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> {{ number_format($sellingPrice, 0) }}
                        </span>
                        @if($actualPrice > $sellingPrice)
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> {{ number_format($actualPrice, 0) }}
                        </span>
                        @endif

                        @php
                            $defaultVariantSimilar = $similarProduct->variants->first();
                            $hasMultipleVariantsSimilar = $similarProduct->variants->count() > 1;
                            $firstVariantSimilar = $defaultVariantSimilar;
                            $keySimilar = $similarProduct->id . '_' . ($firstVariantSimilar->id ?? 0);

                            if (auth()->check()) {
                                $cartItemSimilar = \App\Models\CartItem::where([
                                    'user_id' => auth()->id(),
                                    'product_id' => $similarProduct->id,
                                    'variant_id' => $firstVariantSimilar->id ?? null,
                                ])->first();
                                $inCartSimilar = $cartItemSimilar !== null;
                                $quantitySimilar = $inCartSimilar ? $cartItemSimilar->quantity : 1;
                            } else {
                                $cartSimilar = session('cart', []);
                                $inCartSimilar = isset($cartSimilar[$keySimilar]);
                                $quantitySimilar = $inCartSimilar ? $cartSimilar[$keySimilar]['quantity'] : 1;
                            }

                            $isOpenSimilar = \App\Helpers\StoreHelper::isStoreOpen($similarProduct->vendor->store_time);
                        @endphp

                        <!-- <div class="qty-box"
                            data-product-id="{{ $similarProduct->id }}"
                            data-variant-id="{{ $firstVariantSimilar->id ?? '' }}"
                            data-key="{{ $keySimilar }}">

                            @if ($isOpenSimilar)
                                {{-- ✅ Store is open --}}
                                @if ($hasMultipleVariantsSimilar)
                                    <button class="add-btn d-flex flex-column align-items-center position-relative"
                                            onclick="openPopup({{ $similarProduct->id }})">
                                        <div class="d-flex align-items-center">
                                            Add
                                            <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                        </div>
                                        <div class="cart-options text-black">{{ $similarProduct->variants->count() }} Options</div>
                                    </button>
                                @else
                                    @if (!$defaultVariantSimilar)
                                        <button class="add-btn" disabled>Unavailable</button>
                                    @else
                                        @if (!$inCartSimilar)
                                            <button class="add-btn"
                                                    data-product-id="{{ $similarProduct->id }}"
                                                    data-variant-id="{{ $firstVariantSimilar->id }}"
                                                    onclick="addToCart(this)">
                                                Add
                                                <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                            </button>
                                        @else
                                            <div class="qty-container">
                                                <button class="qty-btn minus decrement-btn" data-key="{{ $keySimilar }}">−</button>
                                                <input type="text" class="qty-input quantity-input" value="{{ $quantitySimilar }}" readonly>
                                                <button class="qty-btn plus increment-btn" data-key="{{ $keySimilar }}">+</button>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            @else
                                {{-- ❌ Store is closed --}}
                                <button class="add-btn disabled" disabled>
                                    Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                </button>
                            @endif
                        </div> -->
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
</section>
@endif
<!-- second section end  -->
 <!-- third section start  -->
@if($otherVendorProducts->count() > 0)
<section>
<div class="container mt-4 ">
    <h4 class="pb-3 pt-4 headingclass">{{ $product->vendor->business_name ?? 'Store' }}</h4>
        <div class="owl-carousel owl-theme">
        @foreach($otherVendorProducts as $vendorProduct)
        @php
            $variant = $vendorProduct->variants->first();
            $sellingPrice = $variant ? $variant->variant_selling_price : 0;
            $actualPrice = $variant ? $variant->variant_actual_price : 0;
            $discountPercent = ($actualPrice > $sellingPrice && $actualPrice > 0) ? round((($actualPrice - $sellingPrice) / $actualPrice) * 100) : 0;

            $attributes = json_decode($variant->attributes ?? '{}', true);
            $variantInfo = array_values($attributes)[0] ?? '';
        @endphp
        <div class="item">
                <div class="product-card">
                    @if($discountPercent > 0)
                    <span class="discount-label">{{ $discountPercent }}% Off</span>
                    @endif
                    <a href="{{ route('productdetails', ['slug' => $vendorProduct->slug]) }}">
                        <img src="{{ $vendorProduct->featureImage ? asset('public/' . $vendorProduct->featureImage->feature_image) : asset('public/assets/website/images/default.png') }}" class="product-image" alt="{{ $vendorProduct->title }}">
                    </a>
                    <div class="product-title">{{ $vendorProduct->title }}</div>
                    <div class="product-info">{{ $variantInfo }}</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> {{ number_format($sellingPrice, 0) }}
                        </span>
                        @if($actualPrice > $sellingPrice)
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> {{ number_format($actualPrice, 0) }}
                        </span>
                        @endif

                        @php
                            $defaultVariantVendor = $vendorProduct->variants->first();
                            $hasMultipleVariantsVendor = $vendorProduct->variants->count() > 1;
                            $firstVariantVendor = $defaultVariantVendor;
                            $keyVendor = $vendorProduct->id . '_' . ($firstVariantVendor->id ?? 0);

                            if (auth()->check()) {
                                $cartItemVendor = \App\Models\CartItem::where([
                                    'user_id' => auth()->id(),
                                    'product_id' => $vendorProduct->id,
                                    'variant_id' => $firstVariantVendor->id ?? null,
                                ])->first();
                                $inCartVendor = $cartItemVendor !== null;
                                $quantityVendor = $inCartVendor ? $cartItemVendor->quantity : 1;
                            } else {
                                $cartVendor = session('cart', []);
                                $inCartVendor = isset($cartVendor[$keyVendor]);
                                $quantityVendor = $inCartVendor ? $cartVendor[$keyVendor]['quantity'] : 1;
                            }

                            $isOpenVendor = \App\Helpers\StoreHelper::isStoreOpen($vendorProduct->vendor->store_time);
                        @endphp

                        <!-- <div class="qty-box"
                            data-product-id="{{ $vendorProduct->id }}"
                            data-variant-id="{{ $firstVariantVendor->id ?? '' }}"
                            data-key="{{ $keyVendor }}">

                            @if ($isOpenVendor)
                                {{-- ✅ Store is open --}}
                                @if ($hasMultipleVariantsVendor)
                                    <button class="add-btn d-flex flex-column align-items-center position-relative"
                                            onclick="openPopup({{ $vendorProduct->id }})">
                                        <div class="d-flex align-items-center">
                                            Add
                                            <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                        </div>
                                        <div class="cart-options text-black">{{ $vendorProduct->variants->count() }} Options</div>
                                    </button>
                                @else
                                    @if (!$defaultVariantVendor)
                                        <button class="add-btn" disabled>Unavailable</button>
                                    @else
                                        @if (!$inCartVendor)
                                            <button class="add-btn"
                                                    data-product-id="{{ $vendorProduct->id }}"
                                                    data-variant-id="{{ $firstVariantVendor->id }}"
                                                    onclick="addToCart(this)">
                                                Add
                                                <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                            </button>
                                        @else
                                            <div class="qty-container">
                                                <button class="qty-btn minus decrement-btn" data-key="{{ $keyVendor }}">−</button>
                                                <input type="text" class="qty-input quantity-input" value="{{ $quantityVendor }}" readonly>
                                                <button class="qty-btn plus increment-btn" data-key="{{ $keyVendor }}">+</button>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            @else
                                {{-- ❌ Store is closed --}}
                                <button class="add-btn disabled" disabled>
                                    Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                </button>
                            @endif
                        </div> -->
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    </div>
</section>
@endif
<!-- third section end  -->
<?php //include("include/footer2.php")?>
@endsection

@push('scripts')
<script src="{{ asset('public/assets/website/JS/product-varient-details.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var mainImg = document.querySelector('.main-img-det .main-product-det');
  var thumbContainer = document.querySelector('.thumb-det');
  var arrowLeft = document.querySelector('.thumb-container-det .arrow-left-det');
  var arrowRight = document.querySelector('.thumb-container-det .arrow-right-det');

  if (!mainImg || !thumbContainer) return;

  // Thumbnail click: update main preview image
  thumbContainer.addEventListener('click', function (e) {
    var thumb = e.target.closest('.thumb-det img');
    if (!thumb) return;
    var src = thumb.getAttribute('src');
    if (src) {
      mainImg.setAttribute('src', src);
      thumbContainer.querySelectorAll('img').forEach(function (img) { img.classList.remove('active'); });
      thumb.classList.add('active');
    }
  });

  // Arrow left: scroll thumb strip
  if (arrowLeft) {
    arrowLeft.addEventListener('click', function () {
      if (thumbContainer.scrollLeft > 0) {
        thumbContainer.scrollLeft -= (thumbContainer.offsetWidth * 0.8);
      }
    });
  }
  if (arrowRight) {
    arrowRight.addEventListener('click', function () {
      var maxScroll = thumbContainer.scrollWidth - thumbContainer.clientWidth;
      if (thumbContainer.scrollLeft < maxScroll) {
        thumbContainer.scrollLeft += (thumbContainer.offsetWidth * 0.8);
      }
    });
  }

  // Show more / Show less - "... + Show more" at line end, " - Show less" at content end
  document.querySelectorAll('.show-more-less-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var wrap = this.closest('.product-detail-expandable-wrap');
      var content = wrap && wrap.querySelector('.product-detail-expandable-content');
      var showMoreSpan = wrap && wrap.querySelector('.show-more-inline');
      var showLessSpan = wrap && wrap.querySelector('.show-less-inline');
      if (!content) return;
      var isMore = this.getAttribute('data-state') === 'more';
      if (isMore) {
        content.classList.remove('product-detail-collapsed');
        if (showMoreSpan) showMoreSpan.style.display = 'none';
        if (showLessSpan) showLessSpan.style.display = 'inline';
      } else {
        content.classList.add('product-detail-collapsed');
        if (showMoreSpan) showMoreSpan.style.display = 'inline';
        if (showLessSpan) showLessSpan.style.display = 'none';
      }
    });
  });
});
</script>
@endpush
