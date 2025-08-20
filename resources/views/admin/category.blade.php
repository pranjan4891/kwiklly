@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Add Category</h3>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Add Category Form --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Add Category</h3>
                </div>
                <div class="panel-body">
                    <div class="form">
                        <form class="cmxform form-horizontal tasi-form" method="post" action="{{ route('admin.category.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-2">Category Name *</label>
                                <div class="col-lg-5">
                                    <input class="form-control" id="cname" name="category_name" type="text" value="{{ old('category_name') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cat_img" class="control-label col-lg-2">Image *</label>
                                <div class="col-lg-5">
                                    <input class="form-control" id="cat_img" name="cat_img" type="file" required>
                                    <span style="color: red">Min dimension of images should be 120 * 120 or in the same ratio</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <button class="btn btn-success" type="submit">Save</button>
                                    <button class="btn btn-default" type="reset">Clear</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Category List --}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Category List</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Category Name</th>
                                        <th>Category Image</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $key => $category)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <img src="{{ asset('public/'.$category->image) }}" style="width: 100px;">
                                            </td>
                                            <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <button class="btn btn-success" data-toggle="modal" data-target="#editModal{{ $category->id }}">Edit</button>
                                                <a href="{{ route('admin.category.delete', $category->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure to delete permanently?')">Delete</a>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $category->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form method="POST" action="{{ route('admin.category.update', $category->id) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Category</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Category Name</label>
                                                                <input type="text" name="category_name" class="form-control" value="{{ $category->name }}" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Image</label><br>
                                                                <img src="{{ asset('public/'.$category->image) }}" style="width: 80px; margin-bottom: 10px;">
                                                                <input type="file" name="cat_img" class="form-control">
                                                                <small>Leave blank to keep current image</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
