@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title"> Product Images</h3>
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

    {{-- Add Product Form --}}
    @if (!$isDeletedView)
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Add Product</h3>
                </div>
                <div class="panel-body">
                    <div class="form">
                        <form class="cmxform form-horizontal tasi-form" method="post" action="{{ route('admin.product.images.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label class="control-label col-lg-2">Product Name *</label>
                                <div class="col-lg-5">
                                    <input class="form-control" name="product_name" type="text" value="{{ old('product_name') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-lg-2">Brand Name *</label>
                                <div class="col-lg-5">
                                    <input class="form-control" name="brand_name" type="text" value="{{ old('brand_name') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-lg-2">Description</label>
                                <div class="col-lg-5">
                                    <textarea class="form-control" name="description" rows="4">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-lg-2">Feature Image *</label>
                                <div class="col-lg-5">
                                    <input type="file" class="form-control" name="feature_image" accept="image/*" required>
                                    <small class="text-muted form_error">Only one feature image. Recommended size: min 120x120.</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-lg-2">Product Images</label>
                                <div class="col-lg-5">
                                    <input type="file" class="form-control" name="product_images[]" multiple accept="image/*">
                                    <small class="text-muted form_error">You can upload multiple images for product details.</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-lg-2">Active</label>
                                <div class="col-lg-5">
                                    <select class="form-control" name="is_active">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="is_deleted" value="0">

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
    @endif

    {{-- Deleted Product List --}}

    {{-- Product List --}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">

                <div class="panel-heading">
                    @if (!$isDeletedView)
                    <h3 class="panel-title">Product Image List</h3>
                    @else
                    <h3 class="panel-title">Deleted Product Image List</h3>
                    @endif

                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Product Name</th>
                                        <th>Brand</th>
                                        <th>Feature Image</th>
                                        <th>Product Images</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productImages as $key => $product)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $product->product_name }}</td>
                                            <td>{{ $product->brand_name }}</td>
                                            <td>
                                                @if($product->feature_image)
                                                    <img src="{{ asset('public/' . $product->feature_image) }}" style="width: 100px;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td> <button class="btn btn-info" data-toggle="modal" data-target="#viewModal{{ $product->id }}">View</button></td>
                                           <td>
                                                @if ($isDeletedView)
                                                    <form action="{{ route('admin.product.images.restore', $product->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        <button class="btn btn-warning btn-sm">Restore</button>
                                                    </form>

                                                    <form action="{{ route('admin.product.images.erase', $product->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure to permanently delete this product?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm">Erase</button>
                                                    </form>
                                                @else
                                                    {{-- Normal Edit/Delete buttons --}}
                                                    <button class="btn btn-success" data-toggle="modal" data-target="#editModal{{ $product->id }}">Edit</button>
                                                    <a href="{{ route('admin.product.images.delete', $product->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure to move trash product?');"><i class="fa fa-trash" aria-hidden="true"></i> Trash</a>
                                                @endif
                                            </td>

                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $product->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form method="POST" action="{{ route('admin.product.images.update', $product->id) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Product</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Product Name</label>
                                                                <input type="text" name="product_name" class="form-control" value="{{ $product->product_name }}" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Brand Name</label>
                                                                <input type="text" name="brand_name" class="form-control" value="{{ $product->brand_name }}" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Feature Image</label><br>
                                                                @if($product->feature_image)
                                                                    <img src="{{ asset('public/' . $product->feature_image) }}" style="width: 80px; margin-bottom: 10px;">
                                                                @endif
                                                                <input type="file" name="feature_image" class="form-control">
                                                                <small>Leave blank to keep current image</small>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Product Detail Images</label>
                                                                <input type="file" name="product_images[]" class="form-control" multiple>
                                                                <small>Optional: Upload more product detail images</small>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>
                                                                    <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                                                                    Active
                                                                </label>
                                                            </div>

                                                            <input type="hidden" name="is_deleted" value="0">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- View Modal -->
                                        <div class="modal fade" id="viewModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $product->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Product Detail Images</h5>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if(!empty($product->product_images) && is_array($product->product_images))
                                                            <div class="row">
                                                                @foreach ($product->product_images as $index => $image)
                                                                    <div class="col-md-3 mb-3 text-center">
                                                                        <img src="{{ asset('public/' . $image) }}" class="img-thumbnail mb-2" style="width: 100%; height: auto;">
                                                                        <form method="POST" action="{{ route('admin.product.image.delete.single') }}">
                                                                            @csrf
                                                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                                            <input type="hidden" name="image_index" value="{{ $index }}">
                                                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                                        </form>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <p class="text-muted">No additional images available.</p>
                                                        @endif
                                                    </div>
                                                </div>
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
