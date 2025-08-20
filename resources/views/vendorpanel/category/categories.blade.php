@extends('vendorpanel.include.main')
@section('content')

<ul class="breadcrumb">
    <li><a href="#">Home</a></li>
    <li class="active">{{ isset($edit) ? 'Edit Category' : 'Add Category' }}</li>
</ul>

<div class="page-title">
    <h2>{{ isset($edit) ? 'Edit' : 'Add' }} Category</h2>
</div>

<div class="page-content-wrap">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">{{ isset($edit) ? 'Edit' : 'Add' }} Category</h3></div>
                <div class="panel-body">
                    <form method="post"
                          action="{{ isset($edit) ? route('vendor.categories.update', $edit->id) : route('vendor.categories.store') }}"
                          enctype="multipart/form-data" class="form-horizontal">
                        @csrf
                        @if(isset($edit)) @method('PUT') @endif

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Category Name *</label>
                            <div class="col-lg-5">
                                <input class="form-control" name="category_name" type="text"
                                       value="{{ old('category_name', $edit->category_name ?? '') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-2 control-label">Image *</label>
                            <div class="col-lg-5">
                                <input class="form-control" name="cat_img" type="file">
                                <span class="text-danger">Min dimension 120x120 or same ratio</span><br>
                                @if(isset($edit) && $edit->category_image)
                                    <img src="{{ asset('storage/'.$edit->category_image) }}" width="100" class="mt-2">
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button class="btn btn-success" type="submit">{{ isset($edit) ? 'Update' : 'Save' }}</button>
                                <a href="{{ route('vendor.categories') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Categories</h3></div>
                <div class="panel-body table-responsive">
                    <table class="table datatable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Category Name</th>
                            <th>Image</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($results as $index => $value)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $value->category_name }}</td>
                                <td>
                                    @if($value->category_image)
                                        <img src="{{ asset('public/'.$value->category_image) }}" width="100">
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d-m-Y') }}</td>
                                <td>
                                    <a href="{{ route('vendor.categories.edit', $value->id) }}" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></a>
                                    <form action="{{ route('vendor.categories.delete', $value->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5">No categories found</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
