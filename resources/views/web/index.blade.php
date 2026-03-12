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
      @if($secondBanners->count() > 0)
      <div id="mobileSlider" class="carousel slide d-md-none pointer-event" data-bs-ride="carousel" data-bs-interval="2000">
         <div class="carousel-inner">
            @foreach ($secondBanners as $index => $banner)
                <div class="carousel-item {{ $index === ($secondBanners->count() - 1) ? 'active' : '' }}">
                    <a href="{{ $banner['banner_url'] ?? '#' }}">
                        <img src="{{ asset('public/'. $banner['desktop_image']) }}" class="d-block w-100 img-fluid" alt="">
                    </a>
                </div>
            @endforeach
         </div>
      </div>
      @endif
   </div>
</section>

<section id="trending-products-section">
  @if(isset($trending_products) && count($trending_products) > 0)
      @include('web.partials.trending', ['trending_products' => $trending_products])
   
   @endif
</section>


{{-- Category slider: sirf wahi categories jinke products vendor delivery location ke andar hain --}}
@if(isset($categories) && $categories->count() > 0)
<section class="categoryfordesktop">
   <div class="container new-cate-grocery-section">
      <div class="row align-items-center m-0">
         <!-- Left Promo Section -->
         <div class="col-md-4">
             @php
               $categoryBanner = collect($banners)->firstWhere('banner_cat_id', 3);
            @endphp
            @if ($categoryBanner)
               <div class="new-cate-promo-box" style="background-image: url('{{ asset('public/' . $categoryBanner['desktop_image']) }}');">
                  <a href="{{$categoryBanner['banner_url']}}" class="btn btn-light" onclick="return redirectWithLocation(this.href)">Know More</a>
               </div>      
            @endif               
         </div>
         
         <!-- Right Category Slider -->
         <div class="col-md-8 new-cate">
            <h4 class="py-3 m-0 new-cate-category-title headingclass">Categories</h4>
            <div class="new-cate-owl-carousel owl-carousel owl-theme">
               @php
               $uniqueCategories = $categories->unique('id')->values();
               @endphp
               @foreach ($uniqueCategories->chunk(2) as $chunk)
               <div class="new-cate-item p-0">
                  @foreach ($chunk as $cat)
                  @php
                     $categoryName = strtolower($cat['name']);
                     // Replace & with & (with spaces) if not already spaced
                     $categoryName = preg_replace('/\s*&\s*/', ' & ', $categoryName);
                     // Convert to title case
                     $categoryName = ucwords($categoryName);
                  @endphp
                  <a href="{{route('stores', ['slug' => $cat->slug])}}" class="text-decoration-none text-dark"  onclick="return redirectWithLocation(this.href)">
                     <div class="product-carded">
                        <img src="{{ asset('public/' . $cat->image) }}" alt="{{ $categoryName }}">
                         <div class="py-2 text-center catename "><b>{{ $categoryName }}</b></div>
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
@endif

{{-- Mobile category slider: sirf delivery location wali categories --}}
@if(isset($categories) && $categories->count() > 0)
@php $categoryBanner = $categoryBanner ?? collect($banners)->firstWhere('banner_cat_id', 3); @endphp
<section class="categoryformobile">
   <div class="container new-cate-grocery-section">
   <div class="row align-items-center m-0">
      <!-- Left Promo Section -->
      <div class="col-md-4">
         @if ($categoryBanner)
         <div class="new-cate-promo-box" style="background-image: url('{{ asset('public/' . $categoryBanner['desktop_image']) }}');">
            <a href="{{$categoryBanner['banner_url']}}" class="btn btn-light" onclick="return redirectWithLocation(this.href)">Know More</a>
         </div>
         @endif
      </div>
      <!-- Right Category Slider -->
      <div class="col-md-8 new-cate">
         <h4 class="pb-3 new-cate-category-title headingclass">Categories</h4>
         <div class="row" id="category-container">
            @php
            $uniqueCategoriesMobile = $categories->unique('id')->values();
            @endphp
            @foreach ($uniqueCategoriesMobile as $cat)
                @php
                   $categoryName = strtolower($cat['name']);
                   // Replace & with & (with spaces) if not already spaced
                   $categoryName = preg_replace('/\s*&\s*/', ' & ', $categoryName);
                   // Convert to title case
                   $categoryName = ucwords($categoryName);
                @endphp
                <div class="col-md-4 col-4 new-cate-item-wrap">
                <div class="new-cate-item">
                    <a href="{{route('stores', ['slug' => $cat->slug])}}" class="text-decoration-none text-dark"  onclick="return redirectWithLocation(this.href)">
                        <div class="product-carded">
                            <img src="{{ asset('public/' . $cat->image) }}" alt="{{ $categoryName }}">
                        </div>
                        <div class="py-2 text-center catename"><b>{{ $categoryName }}</b></div>
                    </a>
                </div>
                </div>
                @endforeach
         </div>
      </div>
   </div>
</section>
@endif

<section id="stores-section">
   @if(isset($stores) && count($stores) > 0)
      @include('web.partials.stores', ['stores' => $stores])  
   @endif
</section>

<section id="best-offers-products-section">
   @include('web.partials.best-offers', ['best_offers_products' => $best_offers_products])
</section>

<section id="sponsors-products-section">
   @include('web.partials.sponsors', ['sponsors_products' => $sponsors_products])
</section>

{{-- Category-wise products: sirf tab dikhe jab koi vendor ne delivery location (polygon) set kiya ho --}}
<section id="categories-section">
  @if(isset($categorywiseproducts) && count($categorywiseproducts) > 0)
      @include('web.partials.categories', ['categorywiseproducts' => $categorywiseproducts])
  @endif
</section>

@endsection
