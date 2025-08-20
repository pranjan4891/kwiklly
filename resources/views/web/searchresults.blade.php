@extends('web.include.main')
@section('content')
<?php //include("include/header2.php")?>
<!-- first section start  -->
<section class="extrapadding">
<div class="container mt-4">
<div class="row align-items-center mb-3">
            <div class="col-md-6 col-12 searchtext">
                <h4 class="mb-0 ">Search Result "Milk"</h4>
                <p class="text-muted">16 stores near you</p>
            </div>
            <div class="col-md-6 col-12">
                <!-- Desktop Grid View -->
                <div class="row g-2 d-none d-md-flex">
                    <div class="col-4 col-sm-3">
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-dark dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                Filters
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><a class="dropdown-item" href="#">Option 1</a></li>
                                <li><a class="dropdown-item" href="#">Option 2</a></li>
                                <li><a class="dropdown-item" href="#">Option 3</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-4 col-sm-3">
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-dark dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                Brand
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><a class="dropdown-item" href="#">Brand 1</a></li>
                                <li><a class="dropdown-item" href="#">Brand 2</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-4 col-sm-3">
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-dark dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                Quantity
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><a class="dropdown-item" href="#">Small</a></li>
                                <li><a class="dropdown-item" href="#">Medium</a></li>
                                <li><a class="dropdown-item" href="#">Large</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-4 col-sm-3">
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-dark dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                Sort by
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><a class="dropdown-item" href="#">Price: Low to High</a></li>
                                <li><a class="dropdown-item" href="#">Price: High to Low</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Mobile Scrollable View -->
                <div class="d-md-none filter-scroll mt-2 pb-1">
                    <div class="filter-item">
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-dark dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                Filters
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><a class="dropdown-item" href="#">Option 1</a></li>
                                <li><a class="dropdown-item" href="#">Option 2</a></li>
                                <li><a class="dropdown-item" href="#">Option 3</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="filter-item">
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-dark dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                Brand
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><a class="dropdown-item" href="#">Brand 1</a></li>
                                <li><a class="dropdown-item" href="#">Brand 2</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="filter-item">
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-dark dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                Quantity
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><a class="dropdown-item" href="#">Small</a></li>
                                <li><a class="dropdown-item" href="#">Medium</a></li>
                                <li><a class="dropdown-item" href="#">Large</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="filter-item">
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-dark dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                Sort by
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><a class="dropdown-item" href="#">Price: Low to High</a></li>
                                <li><a class="dropdown-item" href="#">Price: High to Low</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>          
        <div class="row">
            <div class="col-md-3 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk"></a>
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
            <div class="col-md-3 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product12.png')}}" class="product-image" alt="Milk"></a>
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
            <div class="col-md-3 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk"></a>
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
            <div class="col-md-3 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product12.png')}}" class="product-image" alt="Milk"></a>
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
            <div class="col-md-3 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk"></a>
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
            <div class="col-md-3 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product12.png')}}" class="product-image" alt="Milk"></a>
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
            <div class="col-md-3 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product11.png')}}" class="product-image" alt="Milk"></a>
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
            <div class="col-md-3 pb-4 col-6">
                <div class="item">
                    <div class="product-card2 p-0">
                        <span class="discount-label">20% Off</span>
                        <a href="{{route('productdetails')}}"><img src="{{ asset('public/assets/website/images/product12.png')}}" class="product-image" alt="Milk"></a>
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
<!-- first section end  -->
@endsection