@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Product List</h3>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Product List</h3>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="datatable">
                            <thead>
                                <tr>
                                    <th>S No.</th>
                                    <th>Product Image</th>
                                    <th>Product Name</th>
                                    <th>Vendor Name</th>
                                    <th>Category</th>
                                    <th>Subcategory</th>
                                    <th>Add Inventory</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        <td>
                                            @if ($product->featureImage)
                                                <img src="{{ asset('public/' . $product->featureImage->feature_image) }}" alt="Image" style="width: 100px;">
                                            @else
                                                <em>No Image</em>
                                            @endif
                                        </td>

                                        <td>{{ $product->title }}</td>

                                        <td>{{ $product->vendor->name ?? 'N/A' }}</td>

                                        <td>{{ $product->category->name ?? 'N/A' }}</td>

                                        <td>{{ $product->subcategory->sub_cat_name ?? 'N/A' }}</td>

                                        <td><a href="{{ route('product.variant.create', $product->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                                <i class="fa fa-plus"></i> Add Inventory
                                            </a></td>

                                        <td>
                                            @if ($product->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>

                                        <td>
                                            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.product.destroy', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure to delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                                @if($products->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center">No products found.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        {{-- Add pagination if needed --}}
                        {{-- {{ $products->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
