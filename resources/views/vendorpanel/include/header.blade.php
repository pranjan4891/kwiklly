<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- META SECTION -->
        <title>Kwiklly</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="icon" href="{{ asset('public/web/images/logo.png')}}" type="image/x-icon" />
        <!-- END META SECTION -->
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:100,300,400,600,700,900,400italic' rel='stylesheet'>
        <link href="{{ asset('public/assets/admin/assets/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('public/assets/admin/assets/css/bootstrap-reset.css') }}" rel="stylesheet">
        <link href="{{ asset('public/assets/admin/assets/css/animate.css') }}" rel="stylesheet">
        <link href="{{ asset('public/assets/admin/assets/assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
        <link href="{{ asset('public/assets/admin/assets/assets/ionicon/css/ionicons.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('public/assets/admin/assets/assets/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ asset('public/assets/admin/assets/assets/morris/morris.css') }}">
        <link href="{{ asset('public/assets/admin/assets/assets/sweet-alert/sweet-alert.min.css') }}" rel="stylesheet">

        <!-- CSS INCLUDE -->
        <link rel="stylesheet" type="text/css" id="theme" href="{{ asset('public/assets/vendor/css/theme-default.css')}}"/>
        <!-- EOF CSS INCLUDE -->
        <!--Select 2--->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
         {{-- <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','../../../www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-62751496-1', 'auto');
            ga('send', 'pageview');

        </script> --}}
    </head>

<body>
@php
                                $vendor = auth()->user();
                            @endphp
        <!-- START PAGE CONTAINER -->
        <div class="page-container">

            <!-- START PAGE SIDEBAR -->
            <div class="page-sidebar">
                <!-- START X-NAVIGATION -->
                <ul class="x-navigation">
                    <li class="xn-logo">
                 <img src="{{ asset('public/assets/website/img/web-main/logo.png') }}" alt="logo" height="80px">

                        <a href="{{ route('vendor.dashboard')}}" class="x-navigation-control"></a>
                    </li>
                    <li class="xn-profile">
                        <a href="#" class="profile-mini">
                                        <img src="{{ asset('public/assets/website/images/logo.png')}}" alt="Kwiklly" />
                                        <!--<img src="../../upload_img/vendor_reg/static_img/store.jpg" alt="" style="width:200px" class="br-radius">-->
                                        <img src="{{ asset('public/'.$vendor->business_logo)}}" alt="Kwiklly" />
                        </a>
                        <div class="profile">
                            <div class="profile-image">
                                        <!-- <img src="{{ asset('web/images/logo.png')}}" /> -->
                                        <!--<img src="../../upload_img/vendor_reg/static_img/store.jpg" alt="" style="width:200px" class="br-radius">-->
                                        <img src="{{ asset('public/'.$vendor->business_logo)}}" alt="" >
                            </div>

                            <div class="profile-data">
                                <div class="profile-data-name">Welcome ! Vendor -  {{$vendor->business_name}}</div>
                                <div class="profile-data-title">Your Email  -  {{$vendor->email}}</div>
                            </div>
                            {{-- <div class="profile-controls">
                               <a href="{{ route('vendor.profile')}}" class="profile-control-left"><span class="fa fa-info"></span></a>
                                <a href="#" class="profile-control-right"><span class="fa fa-envelope"></span></a>
                            </div> --}}
                        </div>
                    </li>
                    <li class="xn-title"><a href="{{ route('vendor.dashboard')}}"><span class="fa fa-dashboard"></span> <span class="xn-text">Dashboards</span></a></li>
                    <li class="xn"><a href="{{ route('vendor.profile')}}"><span class="fa fa-user"></span> <span class="xn-text">Manage Profile</span></a></li>
                    <li class="xn-openable">
                        <a href="#"><span class="fa fa-pencil"></span> <span class="xn-text">Product Images</span></a>
                        <ul>
                            <li><a href="{{ route('vendor.product.images')}}"><span class="fa fa-list-ul"></span> Active Product Image</a></li>
                            <li><a href="{{route('admin.product.images.deleted')}}">InActive Product Image</a></li>
                        </ul>
                    </li>
                    <li class="xn-openable">
                        <a href="#"><span class="fa fa-files-o"></span> <span class="xn-text">Manage Product</span></a>
                        <ul>
                            <li><a href="{{ route('vendor.products')}}"><span class="fa fa-image"></span>Product List</a></li>
                            <li><a href="{{ route('vendor.product.create')}}"><span class="fa fa-image"></span>Create Product</a></li>
                            <li><a href="{{route('vendor.products.importcsv')}}"><span class="fa fa-image"></span>Import Products</a></li>
                            <li><a href="{{route('vendor.products.export')}}"><span class="fa fa-file-text-o"></span> Export Products</a></li>
                        </ul>
                    </li>
                    <!--Cart Management -->
                    <li class="xn-openable">
                        <a href="#"><span class="fa fa-shopping-cart"></span> <span class="xn-text">Manage Cart</span></a>
                        <ul>
                            <li>
                                <a href="{{ route('vendor.cart.index') }}">
                                    <span class="fa fa-list"></span> Cart List
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="xn-openable">
                        <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Manage Orders</span></a>
                        <ul>
                            <li><a href="{{ route('vendor.orderlist')}}"><span class="fa fa-heart"></span> Orders List</a></li>
                            <li><a href=""><span class="fa fa-search"></span> Orders Search</a></li>
                        </ul>
                    </li>
                    <li class="xn-openable">
                        <a href="#"><span class="fa fa-cogs"></span> <span class="xn-text">Manage Coupon</span></a>
                        <ul>
                            <li><a href="{{ route('vendor.coupons.index')}}"><span class="fa fa-heart"></span>Listing Coupon</a></li>
                            <li><a href="{{ route('vendor.coupons.create')}}"><span class="fa fa-heart"></span> Create Coupon</a></li>
                            <li><a href="{{ route('vendor.coupons.deleted')}}"><span class="fa fa-heart"></span> Deleted Coupons</a></li>

                            <!--li><a href="Home/orderreport');pan class="fa fa-search"></span> Orders Search</a></li-->
                        </ul>
                    </li>

                </ul>
                <!-- END X-NAVIGATION -->
            </div>
            <!-- END PAGE SIDEBAR -->

            <!-- PAGE CONTENT -->
            <div class="page-content">

                <!-- START X-NAVIGATION VERTICAL -->
                <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
                    <!-- TOGGLE NAVIGATION -->
                    <li class="xn-icon-button">
                        <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
                    </li>
                    <!-- END TOGGLE NAVIGATION -->
                    <!-- SEARCH -->
                    <li class="xn-search">
                        <form role="form">
                            <input type="text" name="search" placeholder="Search..."/>
                        </form>
                    </li>
                    <!-- END SEARCH -->
                    <!-- POWER OFF  -->
                    <li class="xn-icon-button pull-right last">
                        <a href="#"><span class="fa fa-power-off"></span></a>
                        <ul class="xn-drop-left animated zoomIn">
                            <li><a href="{{route('vendor.profile')}}"><span class="fa fa-user"></span> Profile</a></li>
                            <li><a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span> Sign Out</a></li>
                        </ul>
                    </li>
                    <!-- END POWER OFF -->
                    <!-- MESSAGES -->
                    <li class="xn-icon-button pull-right">
                        <a href="#"><span class="fa fa-comments"></span></a>

                    </li>
                    <!-- END MESSAGES -->
                    <!-- TASKS -->
                    <li class="xn-icon-button pull-right">
                        <a href="#"><span class="fa fa-tasks"></span></a>

                    </li>
                    <!-- END TASKS -->
                    <!-- LANG BAR -->
                    <li class="xn-icon-button pull-right">
                        <a href="#"><span class="flag flag-gb"></span></a>
                        <ul class="xn-drop-left xn-drop-white animated zoomIn">
                            <li><a href="#"><span class="flag flag-gb"></span> English</a></li>
<!--                            <li><a href="#"><span class="flag flag-de"></span> Deutsch</a></li>
                            <li><a href="#"><span class="flag flag-cn"></span> Chinese</a></li>-->
                        </ul>
                    </li>
                    <!-- END LANG BAR -->
                </ul>
                <!-- END X-NAVIGATION VERTICAL -->
