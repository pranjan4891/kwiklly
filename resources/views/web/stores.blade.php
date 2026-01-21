
@extends('web.include.main')

@section('content')

<style>
    
</style>
<!-- first section start  -->
<section class="store-banner-section">
<div class="container py-2 px-3 store-page-container">
    <div class="row d-none d-md-flex text-center">
        @if ($banners)
            @foreach ($banners as $banner)
                <div class="col-md-6 bannerimg ">
                    <img src="{{ asset('public/' . $banner->desktop_image) }}" alt="Banner" class="img-fluid">
                </div>
            @endforeach
        @else
            <div class="col-md-6 bannerimg ">
                <img src="{{ asset('public/assets/website/images/storebanner1.jpg')}}" alt="" class="img-fluid">
            </div>
            <div class="col-md-6 bannerimg ">
                <img src="{{ asset('public/assets/website/images/storebanner2.jpeg')}}" alt="" class="img-fluid">
            </div>
        @endif
    </div>
    <!-- Mobile Slider -->
    @if($banners && $banners->count() > 0)
    <div id="mobileSlider" class="carousel slide d-md-none store-mobile-slider" data-bs-ride="carousel" data-bs-interval="2000">
        <div class="carousel-inner">
            @foreach ($banners as $index => $banner)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    @if($banner->banner_url)
                        <a href="{{ $banner->banner_url }}">
                            <img src="{{ asset('public/' . $banner->desktop_image) }}" class="d-block w-100 img-fluid" alt="Banner">
                        </a>
                    @else
                        <img src="{{ asset('public/' . $banner->desktop_image) }}" class="d-block w-100 img-fluid" alt="Banner">
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @else
    <!-- Fallback if no banners -->
    <div id="mobileSlider" class="carousel slide d-md-none store-mobile-slider" data-bs-ride="carousel" data-bs-interval="2000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('public/assets/website/images/storebanner1.jpg')}}" class="d-block w-100 img-fluid" alt="">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('public/assets/website/images/storebanner2.jpeg')}}" class="d-block w-100 img-fluid" alt="">
            </div>
        </div>
    </div>
    @endif
    <h4 class="headingclass mt-3 mb-3">What you want to shop today ?</h4>
    @if($stores && count($stores) > 0)
    <div class="row extraborder">
                
     <!-- Sidebar -->
     <div class="col-md-2 col-3 p-0">
            <div class="sidebared">
                <ul class="list-unstyled mb-0 text-center">
                    @if($categories)
                        @foreach($categories as $cat)
                            @php
                                $categoryName = strtolower($cat['name']);
                                // Replace & with & (with spaces) if not already spaced
                                $categoryName = preg_replace('/\s*&\s*/', ' & ', $categoryName);
                                // Convert to title case
                                $categoryName = ucwords($categoryName);
                            @endphp
                            <a href="{{ route('stores', [$cat->slug]) }}" style="text-decoration:none" onclick="return redirectWithLocation(this.href)">
                                <li class="py-3 sidebar-itemde {{($slug==$cat->slug)?'active':''}}">
                                    <img src="{{ asset('public/'.$cat->image) }}" alt="{{ $cat['name'] }}" class="side-img mb-2" />
                                    <div class="side-text" title="{{ $cat['name'] }}">{{ $categoryName }}</div>
                                </li>
                            </a>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
        <!-- Content -->
        <div class="col-md-10 col-9">
                <p class="store16">{{ count($stores)}} stores near you</p>
            <div class="row g-3 fixedheight2">
                
                <!-- Card Template -->

                <!-- Replace this entire section of hardcoded KFC cards -->
                @forelse($stores as $store)
                    @php
                        $firstProduct = $store->products->first();
                        $firstProductCategoryId = $firstProduct && $firstProduct->fcategory
                            ? $firstProduct->fcategory->id
                            : 0; // fallback if no category found
                    @endphp
                    <div class="col-md-4 col-12 storedetailanchor">
                        <a href="{{ route('explorestore', [$store->id, $firstProductCategoryId]) }}" onclick="return redirectWithLocation(this.href)">
                            <div class="food-card">
                                <div class="position-relative">
                                    <img src="{{ $store->business_banner
                                            ? asset('public/' . $store->business_banner)
                                            : asset('images/default-banner.jpg') }}"
                                        class="w-100 food-img"
                                        alt="{{ $store->store_name }}">

                                    <span class="badge bg-white text-dark position-absolute top-10 end-0 m-3">
                                        {{ $store->offer_text ?? 'Best Seller' }}
                                    </span>
                                </div>
                                <div class="p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-bold">{{ $store->business_name }}</h5>
                                        {{-- <span class="text-success fw-semibold">5.0 km</span> --}}
                                    </div>
                                    <div class="mt-2 frequently-ordered text-black">
                                        <i class="fa fa-thumbs-up me-1 text-warning"></i> {{ $store->tagline ?? ' Frequently Ordered' }}
                                    </div>
                                    <p class="text-muted mt-2 small">{{ Str::limit($store->store_description, 50) }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>No stores found in this category.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</section>
<!-- first section end  -->
<!-- second section start  -->
<section>
    <div class="container">
        <div class="row">
          <div class="col-md-12 col-12">
            <div class="cta-section">
                <div class="cta-text">
                    <h2>Start Selling with us </h2>
                    <!--<p>For any questions or concerns, feel free to contact us.</p>-->
                    <button class="cta-btn" id="newconOpen">Contact Us</button>
                </div>
                <div class="cta-image">
                    <img src="{{ asset('public/assets/website/images/ctaimage.png')}}" alt="Fruits Basket">
                </div>
            </div>
          </div>
        </div>
    </div>
</section>
<!-- second section end  -->
@endsection
