@extends('vendorpanel.include.main')
@section('content')

<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="#">Home</a></li>                    
    <li><a href="#">Pages</a></li>
    <li class="active">Add Subcategory</li>
</ul>
<!-- END BREADCRUMB -->

<!-- PAGE TITLE -->
<div class="page-title">                    
    <h2><span class="fa fa-arrow-circle-o-left"></span> Add Subcategory</h2>
</div>
<!-- END PAGE TITLE -->                

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">

    <!-- Subcategory Form -->
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Add Subcategory</h3></div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('vendor.subcategories.store') }}">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-lg-2">Category Name *</label>
                            <div class="col-lg-5">
                                <select class="form-control" name="cat_id" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2">Subcategory Name *</label>
                            <div class="col-lg-5">
                                <input class="form-control" name="subcategory_name" type="text" required>
                                <input type="hidden" name="v_id" value="{{ $v_id }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button class="btn btn-success" type="submit">Save</button>
                                <a href="{{ route('vendor.subcategories.index') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div> 
            </div> 
        </div> 
    </div> 

    <!-- Subcategory Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Subcategories</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Category Name</th>
                                    <th>Subcategory Name</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subcategories as $index => $subcat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $subcat->category->category_name ?? 'N/A' }}</td>
                                    <td>{{ $subcat->subcategory_name }}</td>
                                    <td>{{ $subcat->created_at }}</td>
                                    <td>
                                        <a href="{{ route('vendor.subcategories.edit', $subcat->id) }}" class="btn btn-default btn-sm"><span class="fa fa-pencil"></span></a>
                                        <form action="{{ route('vendor.subcategories.destroy', $subcat->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure to delete permanently?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm"><span class="fa fa-times"></span></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div>
    </div> 

</div> 
@endsection
