@extends('vendorpanel.include.main')

@section('content')

<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="#">Pages</a></li>
    <li class="active">Product List</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <div class="row">
        <div class="col-md-12">

            <!-- START DEFAULT DATATABLE -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Product List</h3>
                    <div class="text-right">
                        <a href="{{ route('vendor.proadd') }}" class="btn btn-m btn-primary">Add Product</a>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Category Name</th>
                                    <th>Subcategory Name</th>
                                    <th>Product Name</th>
                                    <th>Product Price</th>
                                    <th>Product Image</th>
                                    <th>Inventory</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $key => $value)
                                    @php
                                        $pid = base64_encode($value->v_product_id);
                                        $sts = ($value->v_product_status == 0) ? 'Active' : 'In Active';
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $value->v_cat_name }}</td>
                                        <td>{{ $value->v_subcat_name }}</td>
                                        <td>{{ $value->v_product_name }}</td>
                                        <td>
                                            Actual-Price: {{ $value->v_product_cost }}<br>
                                            Selling-Price: {{ $value->v_product_selling_price }}<br>
                                            Offer in Rs.: {{ $value->v_product_save_in_rs }}<br>
                                            Offer in %: {{ $value->v_product_save_percent }}
                                        </td>
                                        <td>
                                            <img src="{{ asset($value->v_product_image) }}" style="width: 100px;">
                                        </td>
                                        <td>
                                            <a href="{{ url('vendor_admin/Home/Inventory?id=' . $pid) }}" class="btn btn-primary tooltips" data-placement="top" data-toggle="tooltip" title="Add Inventory">
                                                Add Inventory
                                            </a>
                                        </td>
                                        <td>{{ $sts }}</td>
                                        <td>
                                            <a href="{{ url('vendor_admin/Home/ViewProduct?id=' . $pid) }}" class="btn btn-primary tooltips" data-placement="top" data-toggle="tooltip" title="View">
                                                <span class="fa fa-eye"></span>
                                            </a>
                                            <a href="{{ url('vendor_admin/Home/duplicate_product?id=' . $pid) }}" class="btn btn-primary tooltips" data-placement="top" data-toggle="tooltip" title="Duplicate Product">
                                                <i class="fa fa-list-ul"></i>
                                            </a>
                                            <a href="{{ url('vendor_admin/Home/EditProduct?id=' . $pid) }}" class="btn btn-default btn-rounded btn-condensed btn-sm" data-placement="top" data-toggle="tooltip" title="Edit">
                                                <span class="fa fa-pencil"></span>
                                            </a>
                                            <a href="{{ url('vendor_admin/Home/DeleteProduct?id=' . $pid) }}" class="btn btn-danger btn-rounded btn-condensed btn-sm" data-placement="top" data-toggle="tooltip" title="Delete" onclick="return doconfirm();">
                                                <span class="fa fa-times"></span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No products found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <script>
                            function doconfirm() {
                                return confirm("Are you sure to delete permanently?");
                            }
                        </script>
                    </div>
                </div>
            </div>
            <!-- END DEFAULT DATATABLE -->
        </div>
    </div>

</div>
<!-- PAGE CONTENT WRAPPER -->

@endsection
