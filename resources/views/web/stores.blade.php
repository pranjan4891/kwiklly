<?php //include("include/header2.php")?>
@extends('web.include.main')
@section('content')
<!-- first section start  -->
<section>
<div class="container py-3 px-4">
<h4 class=" headingclass">What you want to shop today ?</h4>
    <div class="row extraborder">     
     <!-- Sidebar -->
     <div class="col-md-2 col-3 p-0">
            <div class="sidebared">
                <ul class="list-unstyled mb-0 text-center">
                @if($categories)            
                    @foreach($categories as $cat)
                    <a href="{{ route('stores', [$cat->slug]) }}" style="text-decoration:none">
                        
                        <li class="py-3 sidebar-itemde {{($slug==$cat->slug)?'active':''}}">
                            <img src="{{ asset('public/'.$cat->image) }}" alt="{{ $cat['name'] }}" class="side-img mb-2" />
                            <div class="side-text">{{ $cat['name'] }}</div>
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
                    <div class="row d-none d-md-flex text-center">
                        <div class="col-md-6 bannerimg ">
                            <img src="{{ asset('public/assets/website/images/storebanner1.jpg')}}" alt="" class="img-fluid">
                        </div>
                        <div class="col-md-6 bannerimg ">
                            <img src="{{ asset('public/assets/website/images/storebanner2.jpeg')}}" alt="" class="img-fluid">
                        </div>
                    </div>   
                    <!-- Mobile Slider -->
                    <div id="mobileSlider" class="carousel slide d-md-none" data-bs-ride="carousel" data-bs-interval="2000">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="{{ asset('public/assets/website/images/storebanner1.jpg')}}" class="d-block w-100 img-fluid" alt="">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('public/assets/website/images/storebanner2.jpeg')}}" class="d-block w-100 img-fluid" alt="">
                            </div>
                        </div>
                    </div>
                    <!-- Card Template -->

<!-- Replace this entire section of hardcoded KFC cards -->
@forelse($stores as $store)
    <div class="col-md-4 col-12 storedetailanchor">
        <a href="{{ route('explorestore', [$store->id, $slug]) }}"> {{-- adjust route if needed --}}
            <div class="food-card">
                <div class="position-relative">
                    <img src="{{ asset('public/' . $store->business_banner) }}" class="w-100 food-img" alt="{{ $store->store_name }}">
                    <span class="badge bg-white text-dark position-absolute top-10 end-0 m-3">
                        {{ $store->offer_text ?? 'Best Seller' }}
                    </span>
                </div>
                <div class="p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">{{ $store->business_name }}</h5>
                        <span class="text-success fw-semibold">5.0 km</span>
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


                    {{-- <div class="col-md-4 col-12 storedetailanchor">
                        <a href="{{route('explorestore')}}"><div class="food-card ">
                            <div class="position-relative">
                            <img src="{{ asset('public/assets/website/images/storepimg1.png')}}" class="w-100 food-img" alt="KFC">
                            <span class="badge bg-white text-dark position-absolute top-10 end-0 m-3">50% off above ₹200</span>
                            </div>
                            <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">KFC</h5>
                                <span class="text-success fw-semibold">5.0 km</span>
                            </div>
                            <div class="mt-2 frequently-ordered text-black">
                                <i class="fa fa-thumbs-up me-1 text-warning"></i> Frequently Ordered
                            </div>
                            <p class="text-muted mt-2 small">Burger, Rolls, Fast Food, Cold Dri...</p>
                            </div>
                        </div></a>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="food-card ">
                            <div class="position-relative">
                            <img src="{{ asset('public/assets/website/images/storepimg2.png')}}" class="w-100 food-img" alt="KFC">
                            <span class="badge bg-white text-dark position-absolute top-10 end-0 m-3">50% off above ₹200</span>
                            </div>
                            <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">KFC</h5>
                                <span class="text-success fw-semibold">5.0 km</span>
                            </div>
                            <div class="mt-2 frequently-ordered text-black">
                                <i class="fa fa-thumbs-up me-1 text-warning"></i> Frequently Ordered
                            </div>
                            <p class="text-muted mt-2 small">Burger, Rolls, Fast Food, Cold Dri...</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="food-card ">
                            <div class="position-relative">
                            <img src="{{ asset('public/assets/website/images/storepimg3.png')}}" class="w-100 food-img" alt="KFC">
                            <span class="badge bg-white text-dark position-absolute top-10 end-0 m-3">50% off above ₹200</span>
                            </div>
                            <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">KFC</h5>
                                <span class="text-success fw-semibold">5.0 km</span>
                            </div>
                            <div class="mt-2 frequently-ordered text-black">
                                <i class="fa fa-thumbs-up me-1 text-warning"></i> Frequently Ordered
                            </div>
                            <p class="text-muted mt-2 small">Burger, Rolls, Fast Food, Cold Dri...</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="food-card ">
                            <div class="position-relative">
                            <img src="{{ asset('public/assets/website/images/storepimg4.png')}}" class="w-100 food-img" alt="KFC">
                            <span class="badge bg-white text-dark position-absolute top-10 end-0 m-3">50% off above ₹200</span>
                            </div>
                            <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">KFC</h5>
                                <span class="text-success fw-semibold">5.0 km</span>
                            </div>
                            <div class="mt-2 frequently-ordered text-black">
                                <i class="fa fa-thumbs-up me-1 text-warning"></i> Frequently Ordered
                            </div>
                            <p class="text-muted mt-2 small">Burger, Rolls, Fast Food, Cold Dri...</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="food-card ">
                            <div class="position-relative">
                            <img src="{{ asset('public/assets/website/images/storepimg5.png')}}" class="w-100 food-img" alt="KFC">
                            <span class="badge bg-white text-dark position-absolute top-10 end-0 m-3">50% off above ₹200</span>
                            </div>
                            <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">KFC</h5>
                                <span class="text-success fw-semibold">5.0 km</span>
                            </div>
                            <div class="mt-2 frequently-ordered text-black">
                                <i class="fa fa-thumbs-up me-1 text-warning"></i> Frequently Ordered
                            </div>
                            <p class="text-muted mt-2 small">Burger, Rolls, Fast Food, Cold Dri...</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="food-card ">
                            <div class="position-relative">
                            <img src="{{ asset('public/assets/website/images/storepimg1.png')}}" class="w-100 food-img" alt="KFC">
                            <span class="badge bg-white text-dark position-absolute top-10 end-0 m-3">50% off above ₹200</span>
                            </div>
                            <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">KFC</h5>
                                <span class="text-success fw-semibold">5.0 km</span>
                            </div>
                            <div class="mt-2 frequently-ordered text-black">
                                <i class="fa fa-thumbs-up me-1 text-warning"></i> Frequently Ordered
                            </div>
                            <p class="text-muted mt-2 small">Burger, Rolls, Fast Food, Cold Dri...</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="food-card ">
                            <div class="position-relative">
                            <img src="{{ asset('public/assets/website/images/storepimg2.png')}}" class="w-100 food-img" alt="KFC">
                            <span class="badge bg-white text-dark position-absolute top-10 end-0 m-3">50% off above ₹200</span>
                            </div>
                            <div class="p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">KFC</h5>
                                <span class="text-success fw-semibold">5.0 km</span>
                            </div>
                            <div class="mt-2 frequently-ordered text-black">
                                <i class="fa fa-thumbs-up me-1 text-warning"></i> Frequently Ordered
                            </div>
                            <p class="text-muted mt-2 small">Burger, Rolls, Fast Food, Cold Dri...</p>
                            </div>
                        </div>
                    </div> --}}

                </div>
    </div>
</div>
</section>
<!-- first section end  -->
<!-- second section start  -->
<section>
    <div class="container">
        <div class="row">
          <div class="col-md-12 col-12">
            <div class="cta-section">
                <div class="cta-text">
                    <h2>Start Selling Now</h2>
                    <p>For any questions or concerns, feel free to contact us.</p>
                    <button class="cta-btn">Contact Us</button>
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