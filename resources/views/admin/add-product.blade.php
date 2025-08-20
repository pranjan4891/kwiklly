@extends('admin.includes.main')

@section('title', 'Add Banner')

@section('main')
<div class="wrapper container-fluid">
    <div class="page-title">
        <h3 class="title">Add Product</h3>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Enter Product Details</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" id="addProduct" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Product Name</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="product_name" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Category</label>
                            <div class="col-md-10">
                                <select class="form-control" name="cat_id" required>
                                    <option value="">Select Category</option>
                                    <option value="1">Category 1</option>
                                    <option value="2">Category 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Subcategory</label>
                            <div class="col-md-10">
                                <select class="form-control" name="subcat_id" required>
                                    <option value="">Select Subcategory</option>
                                    <option value="1">Subcategory 1</option>
                                    <option value="2">Subcategory 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Product Actual Price (Rs.)</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="prod_act_p" id="prod_act_p" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Product Selling Price (Rs.)</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="prod_sell_p" id="prod_sell_p" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Price Save (Rs.)</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="price_rs" id="price_rs" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Product Description</label>
                            <div class="col-md-10">
                                <textarea class="summernote" name="prod_desc"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Product Image</label>
                            <div class="col-md-10">
                                <input type="file" class="form-control" name="prod_img1">
                            </div>
                        </div>
                        <div class="col-lg-offset-2 col-lg-10">
                            <button class="btn btn-success" type="submit">Save</button>
                            <button class="btn btn-default" type="reset">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 150
        });
    });
</script>
@endsection
