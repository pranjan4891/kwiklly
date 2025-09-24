@extends('web.include.main')
@section('content')
<style>
   .top-banner {
   padding: 15px;
   }
   .bannerimg .img-fluid {
   border-radius: 10px;
   width: 100% !important;
   height: auto;
   }
</style>

<section class="extrapadding2">
   <div class="container">
      <div class="row">
         <div class="col-md-12 bannerimg p-0">
            <!-- Show top banner (banner_cat_id = 1)  -->
            @php
            $topBanner = collect($banners)->firstWhere('banner_cat_id', 1);
            @endphp
            @if ($topBanner)
            <div class="top-banner">
                 <a href="{{ $topBanner['banner_url']}}">
                    <img src="{{ asset('public/' . $topBanner['desktop_image']) }}" alt="" class="img-fluid">
                 </a>
            </div>
            @endif
         </div>
      </div>
   </div>
</section>

<section>
   <div class="container">
      <div class="row d-none d-md-flex text-center">
         <!-- Show other banners in loop (banner_cat_id = 2) -->
         @php
         $secondBanners = collect($banners)->where('banner_cat_id', 2);
         @endphp
         @foreach ($secondBanners as $banner)
         <div class="col-md-4 bannerimg">
             <a href="{{ $banner['banner_url']}}">
                <img src="{{ asset('public/' . $banner['desktop_image']) }}" alt="" class="img-fluid">
             </a>
         </div>
         @endforeach
      </div>
      <!-- Mobile Slider -->
      <div id="mobileSlider" class="carousel slide d-md-none" data-bs-ride="carousel" data-bs-interval="2000">
         <div class="carousel-inner">
             @foreach ($secondBanners as $banner)
                <div class="carousel-item active">
                    <a href="{{ $banner['banner_url']}}">
                        <img src="{{ asset('public/'. $banner['desktop_image']) }}" class="d-block w-100 img-fluid"  alt="...">
                    </a>
                </div>
            @endforeach

         </div>
      </div>
   </div>
</section>

<section id="trending-products-section">
  @if(isset($trending_products) && count($trending_products) > 0)
      @include('web.partials.trending', ['trending_products' => $trending_products])
   @else
      <div class="text-center py-5">
         <p>Allow location access to see trending products in your area</p>
      </div>
   @endif
</section>

<section class="categoryfordesktop">
   <div class="container new-cate-grocery-section">
      <div class="row align-items-center m-0">
         <!-- Left Promo Section -->
         <div class="col-md-4">
            <div class="new-cate-promo-box">
               <h2>Grocery at your doorstep</h2>
               <p>Your favorite vegetables, fruits & more</p>
               <button class="btn btn-light">Order Now</button>
               @php
               $categoryBanner = collect($banners)->firstWhere('banner_cat_id', 3);
               @endphp
               @if ($categoryBanner)
               <img src="{{ asset('public/' . $categoryBanner['desktop_image']) }}" alt="Vegetables">
               @endif
            </div>
         </div>
         <!-- Right Category Slider -->
         <div class="col-md-8 new-cate">
            <h4 class="py-3 m-0 new-cate-category-title headingclass">Categories</h4>
            <div class="new-cate-owl-carousel owl-carousel owl-theme">
               @foreach ($categories->chunk(2) as $chunk)
               <div class="new-cate-item p-0">
                  @foreach ($chunk as $cat)
                  <a href="{{route('stores', ['slug' => $cat->slug])}}" class="text-decoration-none text-dark"  onclick="return redirectWithLocation(this.href)">
                     <div class="product-carded">
                        <img src="{{ asset('public/' . $cat->image) }}" alt="{{ $cat['name'] }}">
                        
                         <div class="py-2 text-center catename "><b>{{ $cat['name'] }}</b></div>
                     </div>
                    
                  </a>
                  @endforeach
               </div>
               @endforeach
            </div>
         </div>
      </div>
   </div>
</section>

<section class="categoryformobile">
   <div class="container new-cate-grocery-section">
   <div class="row align-items-center m-0">
      <!-- Left Promo Section -->
      <div class="col-md-4">
         <div class="new-cate-promo-box">
            <h2>Grocery at your doorstep</h2>
            <p>Your favorite vegetables, fruits & more</p>
            <button class="btn btn-light">Order Now</button>
            @if ($categoryBanner)
            <img src="{{ asset('public/' . $categoryBanner['desktop_image']) }}" alt="Vegetables">
            @endif
         </div>
      </div>
      <!-- Right Category Slider -->
      <div class="col-md-8 new-cate">
         <h4 class="pb-3 new-cate-category-title headingclass">Categories</h4>
         <div class="row" id="category-container">
            <!-- Repeat this block for all your categories (add as many as you want, for demo 18) -->
            @if ($categories->count() > 0)
            @foreach ($categories as $cat)
            <div class="col-md-4 col-4 new-cate-item-wrap">
               <div class="new-cate-item">
                  <a href="{{route('stores', ['slug' => $cat->slug])}}" class="text-decoration-none text-dark"  onclick="return redirectWithLocation(this.href)">
                     <div class="product-carded">
                        <img src="{{ asset('public/' . $cat->image) }}" alt="{{ $cat['name'] }}">
                     </div>
                     <div class="py-2 text-center catename"><b>{{ $cat['name'] }}</b></div>
                  </a>
               </div>
            </div>
            @endforeach
            @else
            <p>No categories found.</p>
            @endif
         </div>
      </div>
      <div class="text-center">
         <button class="view-all-btn mt-3" id="loadMoreBtn">
         Load More <i class="fa fa-angles-down ms-2"></i>
         </button>
      </div>
   </div>
</section>

<section id="stores-section">
    @if(isset($stores) && count($stores) > 0)
      @include('web.partials.stores', ['stores' => $stores])
   @else
      <div class="text-center py-5">
         <p>Allow location access to see stores in your area</p>
      </div>
   @endif
</section>



<section id="categories-section">
  @if(isset($categorywiseproducts) && count($categorywiseproducts) > 0)
      @include('web.partials.categories', ['categorywiseproducts' => $categorywiseproducts])
   @else
      <div class="text-center py-5">
         <p>Allow location access to see categories in your area</p>
      </div>
   @endif
</section>




@endsection
@push('scripts')


@endpush
