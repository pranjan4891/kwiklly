@extends('admin.includes.main', ['title' => 'Add Home Banner', 'admin'=>Auth::guard('admin')->user()] )

@section('main')
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">
            Welcome! Admin Panel
            <span class="vendor_dash">
                <a href="#">Vendor Dashboard</a>
            </span>
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-3 col-sm-6">
            <div class="widget-panel widget-style-2" style="background-color: #ff4d79; color: white;">
                <i class="fa fa-archive"></i>
                <h2 class="m-0 counter">469</h2>
                <div><strong>Total Product</strong></div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="widget-panel widget-style-2" style="background-color: #28a745; color: white;">
                <i class="ion-android-contacts"></i>
                <h2 class="m-0 counter">47</h2>
                <div><strong>Total Vendors</strong></div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="widget-panel widget-style-2" style="background-color: #17a2b8; color: white;">
                <i class="ion-ios7-pricetag"></i>
                <h2 class="m-0 counter">22</h2>
                <div><strong>Pending Order</strong></div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="widget-panel widget-style-2" style="background-color: #20c997; color: white;">
                <i class="ion-ios7-pricetag"></i>
                <h2 class="m-0 counter">0</h2>
                <div><strong>Onhold Order</strong></div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="widget-panel widget-style-2" style="background-color: #dc3545; color: white;">
                <i class="ion-ios7-pricetag"></i>
                <h2 class="m-0 counter">0</h2>
                <div><strong>Shipped Order</strong></div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="widget-panel widget-style-2" style="background-color: #218838; color: white;">
                <i class="ion-ios7-pricetag"></i>
                <h2 class="m-0 counter">1</h2>
                <div><strong>Delivered Order</strong></div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="widget-panel widget-style-2" style="background-color: #6c757d; color: white;">
                <i class="ion-ios7-pricetag"></i>
                <h2 class="m-0 counter">0</h2>
                <div><strong>Cancelled Order</strong></div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="widget-panel widget-style-2" style="background-color: #ffc107; color: black;">
                <i class="ion-ios7-pricetag"></i>
                <h2 class="m-0 counter">0</h2>
                <div><strong>Today's Dep. Sales</strong></div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6">
            <div class="widget-panel widget-style-2" style="background-color: #007bff; color: white;">
                <i class="ion-ios7-pricetag"></i>
                <h2 class="m-0 counter">0</h2>
                <div><strong>Today's Store Sales</strong></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card-body">
                <h3>Last 5 Latest Products</h3>
                <a href="#"> <span style="color:green;">View More</span></a>
            </div>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Category Name</th>
                        <th>Product Name</th>
                        <th>Product Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Electronics</td>
                        <td>Smartphone</td>
                        <td>
                            Actual-Price: 50000<br>
                            Selling-Price: 45000<br>
                            Offer in Rs.: 5000<br>
                            Offer in %: 10%
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Clothing</td>
                        <td>T-Shirt</td>
                        <td>
                            Actual-Price: 1000<br>
                            Selling-Price: 900<br>
                            Offer in Rs.: 100<br>
                            Offer in %: 10%
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
