<?php //include("include/headerproductdetail.php")?>
@extends('web.include.main')
@section('content')
<!-- first section start  -->
<section class="extrapadding">
<div class="container my-4">
    <div class="row">
    <p class="text-black">Home > Electronic > iPhone 16 - Teal 256 GB</p>
      <!-- Product Image and Thumbnails -->
      <div class="col-md-5 py-3">
        <div class="main-img-det">
          <img src="{{ asset('public/assets/website/images/detailimg.png')}}" class="img-fluid main-product-det" alt="iPhone 16">
        </div>

        <!-- Thumbnail slider with arrows -->
        <div class="thumb-container-det position-relative">
          <div class="arrow-det arrow-left-det"><i class="fa fa-chevron-left"></i></div>
          <div class="thumb-det">
            <img src="{{ asset('public/assets/website/images/detailsmallimg1.png')}}" class="active" alt="">
            <img src="{{ asset('public/assets/website/images/detailsmallimg2.png')}}" alt="">
            <img src="{{ asset('public/assets/website/images/detailsmallimg3.png')}}" alt="">
            <img src="{{ asset('public/assets/website/images/detailsmallimg1.png')}}" alt="">
            <img src="{{ asset('public/assets/website/images/detailsmallimg2.png')}}" alt="">
            <img src="{{ asset('public/assets/website/images/detailsmallimg3.png')}}" alt="">
            <img src="{{ asset('public/assets/website/images/detailsmallimg1.png')}}" alt="">
            <img src="{{ asset('public/assets/website/images/detailsmallimg2.png')}}" alt="">
            <img src="{{ asset('public/assets/website/images/detailsmallimg3.png')}}" alt="">
          </div>
          <div class="arrow-det arrow-right-det"><i class="fa fa-chevron-right"></i></div>
        </div>
      </div>

      <!-- Product Details -->
        <div class="col-md-7 py-3">
            <div class="d-flex justify-content-between">
            <div class="prodetailheading">
                <h4>iPhone 16 - Teal 256 GB</h4>
            </div>
            <div class="detailicon"><img src="{{ asset('public/assets/website/images/share.png')}}" alt=""></div>
            </div>
            <div class="prodetailheading">
             <p>by <span class="text-danger">Store Name</span></p>
            </div>

            <!-- Share and Stock -->
            <div class="d-flex justify-content-between mt-4 mb-2">
             <div class="prodetailheading"><h6 class="mt-1">Net Quantity : 1 unit</h6></div>
             <div><p>Availability : In Stock</p></div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div class="">
                    <span class="pricedetail">
                        <span class="rupee-symbol">₹</span> 3056500
                    </span>
                    <span class="original-price ms-1">
                        <span class="rupee-symbol2">₹</span> 3856500
                    </span>    
                    <span class="badge bg-successs ms-2">20%
                    Off</span>     
                    <p>(incl. of all tax)</p>                     
                </div>           
            </div>

            <h5 class="detailheading">Select Color</h5>
            <div class="d-flex my-3">
                <img src="{{ asset('public/assets/website/images/detailimg.png')}}" class="color-option-det active">
                <img src="{{ asset('public/assets/website/images/detailimg.png')}}" class="color-option-det">
                <img src="{{ asset('public/assets/website/images/detailimg.png')}}" class="color-option-det">
            </div>

            <h5 class="detailheading">Select Memory Size</h5>
            <div class="my-3">
                <span class="btn-option-det">128 GB</span>
                <span class="btn-option-det active">256 GB</span>
                <span class="btn-option-det">500 GB</span>
            </div>

            <h5 class="detailheading">Select RAM</h5>
            <div class="my-3">
                <span class="btn-option-det">4 GB</span>
                <span class="btn-option-det">6 GB</span>
                <span class="btn-option-det active">8 GB</span>
            </div>
            <button class="add-btn-detail" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2"></button>        

            <!-- Coupon and Offers -->
            <h5 class="detailheading">Coupon & Offers</h5>
            <div class="coupon-det"><i class="fa-solid fa-tags text-danger"></i> 30% off above ₹5000</div>
            <div class="coupon-det"><i class="fa-solid fa-tags text-danger"></i> 50% off above ₹2600</div>
            <div class="coupon-det"><i class="fa-solid fa-tags text-danger"></i> 10% off above ₹5500</div>

            <!-- Description -->
            <h5 class="detailheading">Products Description</h5>
            <div class="desc-det">
                Introducing Flousers 2.0 - the ultimate fusion of flexibility and style...
                <span class="extra-det">Crafted with a meticulous blend of 50s thread triblend fabric, including viscose, polyamide, and spandex, these pants are designed to elevate your comfort guaranteed!</span>
                <span class="show-more-det">Show More +</span>
            </div>
        </div>
     </div>
  </div>
  <!-- for mobile  -->
  <div class="fixed-bottom-mobile footerpricemobile">
    <div class="d-flex align-items-center justify-content-between w-100 price-container-mobile">
        <div>
             <span class="pricedetail">
                <span class="rupee-symbol">₹</span> 3056500
            </span>
            <span class="original-price ms-1">
                <span class="rupee-symbol2">₹</span> 3856500
            </span> 
            <span class="discount-badge-mobile">20% Off</span>
            <p class="tax-info-mobile">(incl. of all tax)</p>
        </div>
        <button class="add-btn-mobile" onclick="convertToQty(this)">
            Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2">
        </button>
        
    </div>
</div>
   <!-- for mobile  -->
</section>
<!-- first section end  -->
<!-- second section start  -->
<section>
<div class="container mt-4">
    <h4 class="pb-3 pt-4 headingclass">Similar Products</h4>
        <div class="owl-carousel owl-theme">
        <div class="item">
                <div class="product-card">
                    <span class="discount-label">20% Off</span>
                    <img src="{{ asset('public/assets/website/images/product9.png')}}" class="product-image" alt="Milk">
                    <div class="product-title">Mother Dairy Toned Milk</div>
                    <div class="product-info">500 ml</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> 3056500
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 3856500
                        </span>                              
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="product-card">
                    <span class="discount-label">20% Off</span>
                    <img src="{{ asset('public/assets/website/images/product10.png')}}" class="product-image" alt="Milk">
                    <div class="product-title">Mother Dairy Toned Milk</div>
                    <div class="product-info">500 ml</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> 3565000
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 5650038
                        </span>                              
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="product-card">
                    <span class="discount-label">20% Off</span>
                    <img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk">
                    <div class="product-title">Mother Dairy Toned Milk</div>
                    <div class="product-info">500 ml</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> 3565000
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 3565008
                        </span>                              
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="product-card">
                    <span class="discount-label">20% Off</span>
                    <img src="{{ asset('public/assets/website/images/product12.png')}}" class="product-image" alt="Milk">
                    <div class="product-title">Mother Dairy Toned Milk</div>
                    <div class="product-info">500 ml</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> 3056500
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 5650038
                        </span>                              
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="product-card">
                    <span class="discount-label">20% Off</span>
                    <img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk">
                    <div class="product-title">Mother Dairy Toned Milk</div>
                    <div class="product-info">500 ml</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> 3056500
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 3856500
                        </span>                              
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="product-card">
                    <span class="discount-label">20% Off</span>
                    <img src="{{ asset('public/assets/website/images/product9.png')}}" class="product-image" alt="Milk">
                    <div class="product-title">Mother Dairy Toned Milk</div>
                    <div class="product-info">500 ml</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> 3565000
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 3565008
                        </span>                              
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- second section end  -->
 <!-- third section start  -->
<section>
<div class="container mt-4 otherproductmargin">
    <h4 class="pb-3 pt-4 headingclass">Other Products by Store Name</h4>
        <div class="owl-carousel owl-theme">
        <div class="item">
                <div class="product-card">
                    <span class="discount-label">20% Off</span>
                    <img src="{{ asset('public/assets/website/images/product9.png')}}" class="product-image" alt="Milk">
                    <div class="product-title">Mother Dairy Toned Milk</div>
                    <div class="product-info">500 ml</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> 56500
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 38500
                        </span>                              
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="product-card">
                    <span class="discount-label">20% Off</span>
                    <img src="{{ asset('public/assets/website/images/product10.png')}}" class="product-image" alt="Milk">
                    <div class="product-title">Mother Dairy Toned Milk</div>
                    <div class="product-info">500 ml</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> 56500
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 35650
                        </span>                              
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="product-card">
                    <span class="discount-label">20% Off</span>
                    <img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk">
                    <div class="product-title">Mother Dairy Toned Milk</div>
                    <div class="product-info">500 ml</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> 56530
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 38500
                        </span>                              
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="product-card">
                    <span class="discount-label">20% Off</span>
                    <img src="{{ asset('public/assets/website/images/product12.png')}}" class="product-image" alt="Milk">
                    <div class="product-title">Mother Dairy Toned Milk</div>
                    <div class="product-info">500 ml</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> 356500
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 50038
                        </span>                              
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="product-card">
                    <span class="discount-label">20% Off</span>
                    <img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk">
                    <div class="product-title">Mother Dairy Toned Milk</div>
                    <div class="product-info">500 ml</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> 36500
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 386500
                        </span>                              
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="product-card">
                    <span class="discount-label">20% Off</span>
                    <img src="{{ asset('public/assets/website/images/product9.png')}}" class="product-image" alt="Milk">
                    <div class="product-title">Mother Dairy Toned Milk</div>
                    <div class="product-info">500 ml</div>
                    <div class="">
                        <span class="pricedetail">
                            <span class="rupee-symbol">₹</span> 30650
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 35650
                        </span>                              
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- third section end  -->
<?php //include("include/footer2.php")?>
@endsection