@extends('admin.includes.main')

@section('title', 'Add Banner')

@section('main')
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Product List</h3>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Product List
                        <span class="form_error">Sample Message</span>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Product Id</th>
                                        <th>Category Name</th>
                                        <th>Subcategory Name</th>
                                        <th>Product Name</th>
                                        <th>Product_Price</th>
                                        <th>Product Image</th>
                                        <th>Inventory</th>
                                        <th>Status</th>
                                        <th style="width:10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>1001</td>
                                        <td>Electronics</td>
                                        <td>Mobile Phones</td>
                                        <td>iPhone 13</td>
                                        <td>
                                            Actual-Price :  999.99<br>
                                            Selling-Price :  899.99 <br>
                                            Offer in Rs. :  100.00 <br>
                                            Offer in % :  10% <br>
                                        </td>
                                        <td><img src="../../sample.jpg" style="width:100px"></td>
                                        <td>
                                            <a href="#" class="btn btn-primary tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Add Inventory">
                                                Add Inventory
                                            </a>
                                        </td>
                                        <td>
                                            <a class='badge badge-success' href="#">Active</a>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-primary tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger tooltips" data-placement="top" data-toggle="tooltip" data-original-title="Delete" onClick='return doconfirm();'>
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function doconfirm() {
        return confirm("Are you sure to delete permanently?");
    }
</script>

@endsection
