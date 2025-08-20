<?php //include("include/header2.php")?>
@extends('web.include.main')
@section('content')
<!-- first section start  -->
<section>
<div class="store-sectionde">
        <div class="store-infode">
            <h2>Departments </h2>
            <p>Explore the best of the premium store in your locality. We provide you the access of awesome products under one roof from the store with exclusive coupons, deals, and discounts.</p>
            <div class="time-container">
              <div class="time-boxde">Wednesday 9:30 AM - 10:00 PM</div>
            </div>
        </div>
        <div class="online-statusde">
            <span></span>
            Online
        </div>
        <div class="coupon-boxde">
            <div class="coupon-headerde">
                <h4>20% OFF</h4>
                <img src="{{ asset('public/assets/website/images/klogo.png')}}" alt="Company Logo">
            </div>
            <div class="coupon-contentde">
                <div class="coupon-leftde">
                    <p>MAX ₹ 200</p>
                    <span class="mt-2">Holi Week Discount</span>
                </div>
                <div class="coupon-rightde">
                    <p class="p-0">COUPON EXPIRES 23/05</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- first section end  -->
<!-- second section start  -->
<section>
<div class="container mt-4 headingde">
    <h3>Inspiration for your order</h3>
    <div class="row">
        <div class="col-md-3">
            <div class="sidebarde">
                <ul>
                    <li class="sidebar-itemde active"><img src="{{ asset('public/assets/website/images/small1.png')}}">Snacks & Branded Foods</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small2.png')}}">Dry Fruits</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small3.png')}}">Meat & Fish</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small4.png')}}">Food Grains & Daily</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small5.png')}}">Bakery</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small6.png')}}">Masala</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small7.png')}}">Cereals & Breakfast</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small8.png')}}">Milk & Bread</li>
                    <li class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small9.png')}}">Oil & Ghee</li>
                </ul>
            </div>
        </div>
        <!-- Mobile Sidebar as Horizontal Slider -->
        <div class="mobile-sidebar">
            <div class="sidebar-itemde active"><img src="{{ asset('public/assets/website/images/small1.png')}}"> Snacks & Branded Food</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small2.png')}}"> Dry Fruits</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small3.png')}}"> Meat & Fish</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small4.png')}}"> Food Grains & Daily</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small5.png')}}"> Bakery</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small6.png')}}"> Masala</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small7.png')}}"> Cereals & Breakfast</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small8.png')}}"> Milk & Bread</div>
            <div class="sidebar-itemde"><img src="{{ asset('public/assets/website/images/small9.png')}}"> Oil & Ghee</div>
        </div>
        <div class="col-md-9 fixedheight ">
            <div class="row pt-3">
            <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{ route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                            <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn d-flex flex-column align-items-center position-relative" onclick="openPopup()">
                                <div class="d-flex align-items-center">
                                    Add
                                    <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2">
                                </div>
                                <div class="cart-options text-black">5 Options</div>
                            </button>
                        </div>
                        <div class="store-info ">
                            <span>Ad </span>
                            <span>Chandrash Grocery</span>
                            <span>5 min away</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{ route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product12.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                        <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2"></button>
                        </div>
                        <div class="store-info ">
                            <span>Ad </span>
                            <span>Chandrash Grocery</span>
                            <span>5 min away</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{ route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                            <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn d-flex flex-column align-items-center position-relative" onclick="openPopup()">
                                <div class="d-flex align-items-center">
                                    Add
                                    <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2">
                                </div>
                                <div class="cart-options text-black">5 Options</div>
                            </button>
                        </div>
                        <div class="store-info ">
                            <span>Ad </span>
                            <span>Chandrash Grocery</span>
                            <span>5 min away</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{ route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product12.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                        <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2"></button>
                        </div>
                        <div class="store-info ">
                            <span>Ad </span>
                            <span>Chandrash Grocery</span>
                            <span>5 min away</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{ route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                            <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn d-flex flex-column align-items-center position-relative" onclick="openPopup()">
                                <div class="d-flex align-items-center">
                                    Add
                                    <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2">
                                </div>
                                <div class="cart-options text-black">5 Options</div>
                            </button>
                        </div>
                        <div class="store-info ">
                            <span>Ad </span>
                            <span>Chandrash Grocery</span>
                            <span>5 min away</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{ route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product12.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                        <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2"></button>
                        </div>
                        <div class="store-info ">
                            <span>Ad </span>
                            <span>Chandrash Grocery</span>
                            <span>5 min away</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{ route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                            <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn d-flex flex-column align-items-center position-relative" onclick="openPopup()">
                                <div class="d-flex align-items-center">
                                    Add
                                    <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2">
                                </div>
                                <div class="cart-options text-black">5 Options</div>
                            </button>
                        </div>
                        <div class="store-info ">
                            <span>Ad </span>
                            <span>Chandrash Grocery</span>
                            <span>5 min away</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{ route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product12.png')}}" class="product-image" alt="Milk"></a>
                        <div class="product-title cardpadding">Mother Dairy Toned Milk</div>
                        <div class="product-info cardpadding">500 ml</div>
                        <div class="price-container cardpadding">
                        <span class="price">
                                <span class="rupee-symbol">₹</span> 30
                            </span>
                            <span class="original-price">
                                <span class="rupee-symbol2">₹</span> 38
                            </span>                        
                            <button class="add-btn" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-2"></button>
                        </div>
                        <div class="store-info ">
                            <span>Ad </span>
                            <span>Chandrash Grocery</span>
                            <span>5 min away</span>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
   <!-- pop up of add button  -->
   <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Mother Dairy Milk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Select Unit</h6>
                <div class="unit-list">
                    <div class="unit-item">
                        <img src="{{ asset('public/assets/website/images/category1.png')}}" class="unit-image" alt="Milk">
                        <span>100 ml</span>
                        <span class="pricepopup">
                            <span class="rupee-symbol">₹</span> 30
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 38
                        </span>
                       
                        <button class="add-btn" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-1"></button>
                    </div>
                    <div class="unit-item">
                        <img src="{{ asset('public/assets/website/images/category1.png')}}" class="unit-image" alt="Milk">
                        <span>500 ml</span>
                         <span class="pricepopup">
                            <span class="rupee-symbol">₹</span> 30
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 38
                        </span>
                        <button class="add-btn" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-1"></button>
                    </div>
                    <div class="unit-item">
                        <img src="{{ asset('public/assets/website/images/category1.png')}}" class="unit-image" alt="Milk">
                        <span>500 ml</span>
                         <span class="pricepopup">
                            <span class="rupee-symbol">₹</span> 30
                        </span>
                        <span class="original-price">
                            <span class="rupee-symbol2">₹</span> 38
                        </span>
                        <button class="add-btn" onclick="convertToQty(this)">Add <img src="{{ asset('public/assets/website/images/cart.svg')}}" alt="" class="ms-1"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<!-- second section end  -->
@endsection