<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">
    <!-- <link rel="shortcut icon" href="{{ asset('public/assets/img/favicon_1.ico') }}"> -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('public/assets/website/img/web-main/logo.png') }}"/>
    <title>Kwiklly | {{ $title }}</title>
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:100,300,400,600,700,900,400italic' rel='stylesheet'>
    <link href="{{ asset('public/assets/admin/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/admin/assets/css/bootstrap-reset.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/admin/assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/admin/assets/assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/admin/assets/assets/ionicon/css/ionicons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/admin/assets/assets/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('public/assets/admin/assets/assets/morris/morris.css') }}">
    <link href="{{ asset('public/assets/admin/assets/assets/sweet-alert/sweet-alert.min.css') }}" rel="stylesheet">

    <!-- <link href="{{ asset('public/assets/assets/tagsinput/jquery.tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/assets/toggles/toggles.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/assets/timepicker/bootstrap-timepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/assets/timepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" /> -->

    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/assets/colorpicker/colorpicker.css') }}" /> -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/admin/assets/assets/jquery-multi-select/multi-select.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/admin/assets/assets/select2/select2.css') }}" />

    <link href="{{ asset('public/assets/admin/assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/admin/assets/css/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/admin/assets/css/style-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/admin/assets/css/temp.css') }}" rel="stylesheet">

    <!--<script src="{{ asset('public/assets/admin/assets/ckeditor/ckeditor.js') }}"></script>-->
    <!--<script src="{{ asset('public/assets/admin/assets/ckeditor/samples/js/sample.js') }}"></script>-->
    <!--<link rel="stylesheet" href="{{ asset('public/assets/admin/assets/ckeditor/samples/css/samples.css') }}">-->
    <!--<link rel="stylesheet" href="{{ asset('public/assets/admin/assets/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css') }}">-->

    <!--[if lt IE 9]>
      <script src="{{ asset('js/html5shiv.js') }}"></script>
      <script src="{{ asset('js/respond.min.js') }}"></script>
    <![endif]-->
        <style>
            table.dataTable thead .sorting:after {
                display: none;
            }
        </style>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-62751496-1', 'auto');
        ga('send', 'pageview');

    </script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>
<body>
    <aside class="left-panel" style="background-color: #284a00;">
        <div class="logo">
            <a href="#" class="logo-expanded">
                <img src="{{ asset('public/assets/website/img/web-main/logo.png') }}" alt="logo" height="80px">
            </a>
        </div>
        <nav class="navigation">
            <ul class="list-unstyled">
                <li class="has-submenu">
                    <a href="{{route('admin.dashboard')}}"><i class="ion-home"></i> <span class="nav-label">Dashboard</span></a>
                </li>
                <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i><span class="nav-label">Home Page Setting</span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('admin.banner')}}">Active/Inactive Banner</a></li>
                        <li><a href="{{route('admin.banner.deleted')}}">Deleted Banner</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i><span class="nav-label">All Vendor</span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('admin.vendor.pending')}}">Pending Vendor</a></li>
                        <li><a href="{{route('admin.vendor.approve')}}">Approve Vendor</a></li>
                        <li><a href="{{route('admin.vendor.rejected')}}">Rejected Vendor</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i><span class="nav-label">Master Attribute</span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('admin.attributes')}}">All Attribute</a></li>
                        <li><a href="{{route('admin.attribute.values')}}">All Variant Attribute</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="{{route('admin.categories')}}"><i class="ion-navicon-round"></i> <span class="nav-label">Category</span></a>
                </li>
                <li class="has-submenu">
                    <a href="{{route('admin.subcategories')}}"><i class="ion-navicon-round"></i> <span class="nav-label">Subcategory</span></a>
                </li>
                <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i> <span class="nav-label">Product</span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('admin.product.create')}}">Add Product</a></li>
                        <li><a href="{{route('admin.products')}}">Display Product</a></li>
                        <li><a href="{{route('admin.products.importcsv')}}">Import Products</a></li>
                        <li><a href="{{route('admin.products.export')}}">Export Products</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i> <span class="nav-label">Product Images</span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('admin.product.images')}}">Active Product Image</a></li>
                        <li><a href="{{route('admin.product.images.deleted')}}">Display Product Image</a></li>
                    </ul>
                </li>

                <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i> <span class="nav-label">Master Location</span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('admin.location')}}">All Location</a></li>
                        <li><a href="{{route('admin.location.create')}}">Add Location</a></li>
                        <li><a href="{{route('admin.location.deleted')}}">Deleted Location</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i> <span class="nav-label">Branch</span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('admin.branches')}}">All Branch</a></li>
                        <li><a href="{{route('admin.branch.create')}}">Add Branch</a></li>
                        <li><a href="{{route('admin.branch.deleted')}}">Deleted Branch</a></li>
                    </ul>
                </li>
                <li class="has-submenu">
                    <a href="{{route('admin.cart.index')}}"><i class="ion-navicon-round"></i> <span class="nav-label">Carts</span></a>
                </li>
                <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i> <span class="nav-label">Orders</span></a>
                    <ul class="list-unstyled">
                        <li><a href="#">Store Orders</a></li>
                        <li><a href="#">Department Orders</a></li>
                        <li><a href="#">Orders Report</a></li>
                    </ul>
                </li>
                 <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i> <span class="nav-label">Coupon Management</span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.coupons.index') }}">All Coupons</a></li>
                        <li><a href="{{ route('admin.coupons.create') }}">Add Coupon</a></li>
                        <li><a href="{{ route('admin.coupons.deleted') }}">Deleted Coupons</a></li>
                    </ul>
                </li>
                {{-- <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i> <span class="nav-label">Exclusive Deals</span></a>
                </li>
                <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i> <span class="nav-label">Deals of the Week</span></a>
                </li>
                <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i> <span class="nav-label">Popular Deals</span></a>
                </li> --}}
                {{-- <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i> <span class="nav-label">Customer Service</span></a>
                </li>
                <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i> <span class="nav-label">Subscribe</span></a>
                </li>
                <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i> <span class="nav-label">Social Links</span></a>
                </li>

                <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i> <span class="nav-label">Delivery Charge</span></a>
                    <ul class="list-unstyled">
                        <li><a href="#">Express Delivery</a></li>
                        <li><a href="#">Normal Delivery</a></li>
                        <li><a href="#">Charge By Vendor</a></li>
                    </ul>
                </li> --}}
                <li class="has-submenu">
                    <a href="#"><i class="ion-navicon-round"></i> <span class="nav-label">Setting</span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('admin.profile')}}">Profile</a></li>
                        <li><a href="{{route('admin.password.change')}}">Change Password</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </aside>
    <section class="content">
        <header class="top-head container-fluid">
            <button type="button" class="navbar-toggle pull-left">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <ul class="list-inline navbar-right top-menu top-right-menu">
                <li class="dropdown text-center">
                    {{-- <span class="vendor_dash"><a href="#">Vendor Dashboard</a></span> --}}
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <img alt src="{{ asset('public/' . $admin->business_logo) }}" class="img-circle profile-img thumb-sm">
                        <span class="username">{{$admin->name}}</span> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu extended pro-menu fadeInUp animated" tabindex="5003" style="overflow:hidden;outline:0">
                        <li><a href="{{route('admin.logout')}}"><i class="fa fa-sign-out"></i> Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </header>

