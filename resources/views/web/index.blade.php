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

<!--<section id="trending-products-section">-->
<!--  @if(isset($trending_products) && count($trending_products) > 0)-->
<!--      @include('web.partials.trending', ['trending_products' => $trending_products])-->
<!--   @endif-->
<!--</section>-->

<section id="top-selling-products-section">
   @if(isset($top_selling_products) && count($top_selling_products) > 0)
      @include('web.partials.trending', [
          'trending_products' => $top_selling_products,
          'sectionTitle' => 'Top Selling Products',
      ])
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
               <a href="{{$categoryBanner['banner_url']}}" onclick="return redirectWithLocation(this.href)">
    <div class="new-cate-promo-box" 
         style="background-image: url('{{ asset('public/' . $categoryBanner['desktop_image']) }}');">
    </div>
</a>     
            @endif               
         </div>
         
         <!-- Right Category Slider -->
         <div class="col-md-8 new-cate">
            <h4 class="py-3 m-0 new-cate-category-title headingclass">Categories</h4>
            <div class="new-cate-owl-carousel owl-carousel owl-theme">
               @include('web.partials.category_odd_even_grid', ['categories' => $categories, 'layout' => 'desktop'])
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
        <a href="{{$categoryBanner['banner_url']}}" onclick="return redirectWithLocation(this.href)">
    <div class="new-cate-promo-box" 
         style="background-image: url('{{ asset('public/' . $categoryBanner['desktop_image']) }}');">
    </div>
</a>
         @endif
      </div>
      <!-- Right Category Slider -->
      <div class="col-md-8 new-cate">
         <h4 class="pb-3 new-cate-category-title headingclass">Categories</h4>
         @php
            $mobileCategoryCount = $categories->unique('id')->count();
         @endphp
         <div class="row" id="category-container">
            @include('web.partials.category_odd_even_grid', ['categories' => $categories, 'layout' => 'mobile'])
         </div>
         <div class="text-center w-100 category-load-more-wrap" id="categoryLoadMoreWrap" style="{{ $mobileCategoryCount > 8 ? '' : 'display:none;' }}">
            <button type="button" class="view-all-btn mt-3" id="categoryLoadMoreBtn" aria-expanded="false">
               Load More <i class="fa fa-angles-down ms-2"></i>
            </button>
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
