@extends('vendorpanel.include.main')

@section('content')

<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li><a href="#">Pages</a></li>
    <li class="active">Add Product</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">

                <div class="panel-heading">
                    <h3 class="panel-title">Add Product</h3>
                    <div class="text-right">
                        <a href="{{ route('vendor.prolist')}}" class="btn btn-m btn-primary">Product List</a>
                    </div>
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" action="{{ route('vendor.product.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Product Name -->
                        <div class="form-group">
                            <label class="col-md-2 control-label">Product Name</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" autocomplete="off" name="product_name" required="">
                                <input type="hidden" class="form-control" name="v_id" value="{{ auth()->user()->id }}">
                            </div>
                        </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label class="col-md-2 control-label">Category</label>
                        <div class="col-md-8">
                            <select class="form-control" name="cat_id" id="category_select" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Subcategory -->
                    <div class="form-group">
                        <label class="col-md-2 control-label">Subcategory</label>
                        <div class="col-md-8">
                            <select class="form-control" name="subcat_id" id="subcategory_select" required>
                                <option value="">Select Subcategory</option>
                                @foreach($subcategories as $sub)
                                    <option value="{{ $sub->id }}" data-category="{{ $sub->cat_id }}">
                                        {{ $sub->subcategory_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                        <!-- Product Prices -->
                        <div class="form-group">
                            <label class="col-md-2 control-label">Product Actual Price <span style="color: red">In Rs.</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="prod_act_p" id="prod_act_p" placeholder="Eg: 200" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Product Selling Price <span style="color: red">In Rs.</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="prod_sell_p" id="prod_sell_p" placeholder="Eg: 180" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Price Save <span style="color: red">In Rs.</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="price_rs" id="price_rs" readonly="" placeholder="In Rs.">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Price Save <span style="color: red">In %</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="price_percent" id="price_percent" readonly="" placeholder="In %">
                            </div>
                        </div>

                        <!-- Product Quantity and Price -->
                        <div class="form-group fieldGroup">
                            <label class="col-md-2 control-label">Product Quantity <br><span style="color: red"> Eg: 500g/1L/1Kg/1piece </span></label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="prod_qty[]" required="">
                                <span style="color: red">Eg: In gram/kg/litre/ml/per piece </span>
                            </div>
                            <label class="col-md-1 control-label">Price</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="prod_prc[]" required="">
                            </div>
                            <label class="col-md-1 control-label">Product Weight</label>
                            <div class="col-md-2">
                                <select name="prod_weight[]" class="form-control" required="">
                                    <option value="">Select*</option>
                                    <option value="gram">gram</option>
                                    <option value="kg">kg</option>
                                    <option value="liter">litre</option>
                                    <option value="ml">ml</option>
                                    <option value="per piece">per piece</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <a href="javascript:void(0);" class="addMore" title="Add field" style="color: red;">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Product Description -->
                        <div class="form-group">
                            <label class="col-md-2 control-label">Product Description</label>
                            <div class="col-md-8">
                                <textarea class="summernote form-control" rows="5" name="prod_desc"></textarea>
                            </div>
                        </div>

                        <!-- Product Disclaimer -->
                        <div class="form-group">
                            <label class="col-md-2 control-label">Product Disclaimer</label>
                            <div class="col-md-8">
                                <textarea class="summernote form-control" rows="5" name="prod_disclaimer"></textarea>
                            </div>
                        </div>

                        <!-- Product Information -->
                        <div class="form-group">
                            <label class="col-md-2 control-label">Product Information</label>
                            <div class="col-md-8">
                                <textarea class="summernote form-control" rows="5" name="prod_info"></textarea>
                            </div>
                        </div>

                        <!-- CGST & SGST -->
                        <div class="form-group">
                            <label class="col-md-2 control-label">CGST</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="cgst" id="cgst">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">SGST</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="sgst" id="sgst">
                            </div>
                        </div>

                        <!-- Product Image -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Product Image</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" name="prod_img1">
                                <span style="color: red">Min dimension of images should be 500 * 500 or in the same ratio</span>
                            </div>
                        </div>

                        <!-- Add More Quantity/Price Fields -->
                        <div class="form-group fieldGroupCopy" style="display: none;">
                            <label class="col-md-2 control-label">Product Quantity <br><span style="color: red"> Eg: 500g/1L/1Kg/1piece </span></label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="prod_qty[]">
                            </div>
                            <label class="col-md-1 control-label">Price</label>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="prod_prc[]">
                            </div>
                            <label class="col-md-1 control-label">Product Weight</label>
                            <div class="col-md-2">
                                <select name="prod_weight[]" class="form-control">
                                    <option value="">Select*</option>
                                    <option value="gram">gram</option>
                                    <option value="kg">kg</option>
                                    <option value="liter">litre</option>
                                    <option value="ml">ml</option>
                                    <option value="per piece">per piece</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <a href="javascript:void(0);" class="remove">
                                    <img src="assets/vendor/img/remove-icon.png" />
                                </a>
                            </div>
                        </div>

                        <!-- Submit and Cancel Buttons -->
                        <div class="col-lg-offset-2 col-lg-8">
                            <button class="btn btn-success" type="submit">Save</button>
                            <button class="btn btn-default" type="button">Cancel</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- PAGE CONTENT WRAPPER -->
</div>

<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 150,
        });
    });

    $(function() {
        $("#prod_act_p, #prod_sell_p").on("keydown keyup", sumprice);
    });

    function sumprice() {
        var p1 = $("#prod_act_p").val();
        var p2 = $("#prod_sell_p").val();
        var p3 = (p1 - p2);
        var p4 = (p3 / p1) * 100;

        $("#price_rs").val(p3);
        $("#price_percent").val(p4);
    }
</script>




@endsection
