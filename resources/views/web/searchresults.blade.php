@extends('web.include.main')
@section('content')

<section class="extrapadding">
    <div class="container mt-4">
        <div class="row align-items-center mb-3">
            <div class="col-md-6 col-12 searchtext">
                <h4 class="mb-0">Search Result "{{ $query }}"</h4>
                <p class="text-muted">{{ $storeCount }} stores near you</p>
            </div>
        </div>

        <div class="row">
            @forelse($products as $product)
                @php
                    $variant = $product->variants->first();
                    $featureImage = $product->featureImage
                        ? asset('public/'.$product->featureImage->feature_image)
                        : asset('public/assets/website/images/default.png');
                @endphp

                <div class="col-md-3 pb-4 col-6">
                    <div class="item">
                        <div class="product-card2 p-0">

                            {{-- Discount Label --}}
                            @if($variant && $variant->variant_save_price_in_percent > 0)
                                <span class="discount-label">{{ $variant->variant_save_price_in_percent }}% Off</span>
                            @endif

                            {{-- Product Image --}}
                            <a href="{{ route('productdetails', $product->slug) }}">
                                <img src="{{ $featureImage }}" class="product-image" alt="{{ $product->title }}">
                            </a>

                            {{-- Product Title --}}
                            <div class="product-title cardpadding">{{ $product->title }}</div>

                            {{-- Variant Name (like 500 ml) --}}
                            <div class="product-info cardpadding">
                                {{ $variant->variant_name ?? '' }}
                            </div>

                            {{-- Price Section --}}
                            <div class="price-container cardpadding">
                                @if($variant)
                                    <span class="price">
                                        <span class="rupee-symbol">₹</span> {{ $variant->variant_selling_price }}
                                    </span>
                                    @if($variant->variant_actual_price > $variant->variant_selling_price)
                                        <span class="original-price">
                                            <span class="rupee-symbol2">₹</span> {{ $variant->variant_actual_price }}
                                        </span>
                                    @endif
                                @endif

                                {{-- Add Button with Popup if multiple variants --}}
                                @if($product->variants->count() > 1)
                                    <button class="add-btn d-flex flex-column align-items-center position-relative"
                                            data-bs-toggle="modal"
                                            data-bs-target="#productModal-{{ $product->id }}">
                                        <div class="d-flex align-items-center">
                                            Add
                                            <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                        </div>
                                        <div class="cart-options text-black">
                                            {{ $product->variants->count() }} Options
                                        </div>
                                    </button>
                                @else
                                    <button class="add-btn" onclick="convertToQty(this)">
                                        Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-2">
                                    </button>
                                @endif
                            </div>

                            {{-- Vendor Info --}}
                            <div class="store-info">
                                <span>{{ $product->vendor->business_name ?? 'Vendor' }}</span>
                                <span> • </span>
                                <span>Near You</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Popup Modal for Variants --}}
                @if($product->variants->count() > 1)
                    <div class="modal fade" id="productModal-{{ $product->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">{{ $product->title }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <h6>Select Unit</h6>
                                    <div class="unit-list">
                                        @foreach($product->variants as $v)
                                            <div class="unit-item">
                                                <img src="{{ $featureImage }}" class="unit-image" alt="{{ $product->title }}">
                                                <span>{{ $v->variant_name }}</span>
                                                <span class="pricepopup">
                                                    <span class="rupee-symbol">₹</span> {{ $v->variant_selling_price }}
                                                </span>
                                                @if($v->variant_actual_price > $v->variant_selling_price)
                                                    <span class="original-price">
                                                        <span class="rupee-symbol2">₹</span> {{ $v->variant_actual_price }}
                                                    </span>
                                                @endif
                                                <button class="add-btn" onclick="convertToQty(this)">
                                                    Add <img src="{{ asset('public/assets/website/images/cart.svg') }}" class="ms-1">
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <p class="text-muted">No products found for "{{ $query }}"</p>
            @endforelse
        </div>
    </div>
</section>

@endsection
