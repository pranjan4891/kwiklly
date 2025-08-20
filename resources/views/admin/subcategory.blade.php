@extends('admin.includes.main')

@section('title', 'Add Subcategory')

@section('main')
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Add Subcategory</h3>
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

    {{-- Add Subcategory Form --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Add Subcategory</h3></div>
                <div class="panel-body">
                    <div class="form">
                        <form class="cmxform form-horizontal tasi-form" method="post" id="subCatgory" action="{{route('admin.subcategory.store')}}" enctype="multipart/form-data">

                            @csrf

                            {{-- Category Selection --}}
                            <div class="form-group">
                                <label for="cname" class="control-label col-lg-2">Category Name *</label>
                                <div class="col-lg-5">
                                    <select class="form-control" name="cat_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('cat_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Subcategory Name --}}
                            <div class="form-group">
                                <label for="subcategory_name" class="control-label col-lg-2">Subcategory Name *</label>
                                <div class="col-lg-5">
                                    <input type="hidden" name="id" value="123">
                                    <input class="form-control" id="subcategory_name" name="subcategory_name" type="text" value="{{old('subcategory_name')}}" required>
                                    {{-- <p class="form_error">Static error message if any</p> --}}
                                </div>
                            </div>


                            {{-- Attribute --}}
                           <div class="form-group">
                            <label for="attribute" class="control-label col-lg-2">Attributes</label>
                            <div class="col-lg-5">
                                <select class="form-control attribute-select" name="attribute[]" id="attribute" multiple required>
                                    @foreach ($attributes as $attribute)
                                        <option value="{{ $attribute->id }}" {{ (collect(old('attribute'))->contains($attribute->id)) ? 'selected' : '' }}>
                                            {{ $attribute->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Search and select multiple attributes</small>
                            </div>
                        </div>



                            {{-- Attribute Value --}}
                            {{-- <div class="form-group">
                                <label for="attribute_value" class="control-label col-lg-2">Attribute Value</label>
                                <div class="col-lg-5">
                                    <select class="form-control" name="attribute_value" id="attribute_value" required >
                                        <option value="">Select Attribute Value</option>
                                    </select>
                                </div>
                            </div> --}}

                            {{-- Subcategory Image --}}
                            <div class="form-group">
                                <label for="cat_img" class="control-label col-lg-2">Sub-category Image *</label>
                                <div class="col-lg-5">
                                    <input class="form-control" id="subcat_img" name="subcat_img" type="file" required>
                                    <span style="color: red">Min dimension of images should be 120 * 120 or in the same ratio</span>
                                </div>
                            </div>
                            {{-- Status --}}
                            <div class="form-group">
                                <label for="status" class="control-label col-lg-2">Status</label>
                                <div class="col-lg-5">
                                    <select name="status" id="" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <button class="btn btn-success" type="submit">Save</button>
                                    <button class="btn btn-default" type="button" onclick="clearForm('subCatgory')">Clear</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Subcategory List --}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Subcategory
                        {{-- <span class="form_error">Static message if any</span> --}}
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S No.</th>
                                        <th>Subcategory Image</th>
                                        <th>Category Name</th>
                                        <th>Subcategory Name</th>
                                        <th>Attribute</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subcategories as $key => $subcategory)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td><img src="{{ asset('public/'.$subcategory->image) }}" alt="" width="50px" height="50px"></td>
                                            <td>{{ $subcategory->category->name }}</td>
                                            <td>{{ $subcategory->sub_cat_name }}</td>
                                            <td>
                                                @foreach ($subcategory->attributes as $attr)
                                                    <span class="label label-info">{{ $attr->name }}</span>
                                                @endforeach
                                            </td>

                                            <td>
                                                @if($subcategory->is_active == 1)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-success" data-toggle="modal" data-target="#editModal{{ $subcategory->id }}">Edit</button>
                                                <a href="{{ url('admin/delete-subcategory/'.$subcategory->id) }}" class="btn btn-danger" onclick="return doconfirm();">Delete</a>
                                            </td>
                                        </tr>
                                        <!-- Edit Modal --><!-- Edit Modal -->
                                        <div class="modal fade" id="editModal{{ $subcategory->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $subcategory->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('admin.subcategory.update', $subcategory->id) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Subcategory</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <div class="modal-body">
                                                            {{-- Category --}}
                                                            <div class="form-group">
                                                                <label>Category Name *</label>
                                                                <select class="form-control" name="cat_id" required>
                                                                    <option value="">Select Category</option>
                                                                    @foreach($categories as $category)
                                                                        <option value="{{ $category->id }}" {{ $subcategory->category_id == $category->id ? 'selected' : '' }}>
                                                                            {{ $category->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            {{-- Subcategory Name --}}
                                                            <div class="form-group">
                                                                <label>Subcategory Name *</label>
                                                                <input class="form-control" name="subcategory_name" type="text" value="{{ $subcategory->sub_cat_name }}" required>
                                                            </div>

                                                           <div class="form-group">
                                                            <div>
                                                                <label>Attributes *</label>
                                                            </div>

                                                            <div>

                                                                <select class="form-control attribute-select" name="attribute[]" multiple required>
                                                                    @foreach ($attributes as $attribute)
                                                                        <option value="{{ $attribute->id }}"
                                                                            {{ in_array($attribute->id, $subcategory->attributes->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                                            {{ $attribute->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                                <small class="text-muted">Search and select multiple attributes</small>
                                                            </div>


                                                                {{-- Image --}}
                                                                <div class="form-group">
                                                                <label>Sub-category Image</label>
                                                                <input class="form-control" name="subcat_img" type="file">
                                                                <small style="color: red;">Min dimension: 120x120 or same ratio</small>
                                                                @if($subcategory->image)
                                                                    <div class="mt-2">
                                                                        <img src="{{ asset('public/'.$subcategory->image) }}" width="50" height="50" alt="Subcategory Image">
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            {{-- Status --}}
                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select class="form-control" name="status" required>
                                                                    <option value="1" {{ $subcategory->is_active == 1 ? 'selected' : '' }}>Active</option>
                                                                    <option value="0" {{ $subcategory->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </form>
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

@push('scripts')
<script>
    function doconfirm() {
        return confirm("Are you sure to delete permanently?");
    }
</script>
<script>
    $(document).ready(function () {
        $('.attribute-select').select2({
            placeholder: "Select Attributes",
            closeOnSelect: false,
            templateResult: formatCheckbox,
            templateSelection: formatSelection
        });

        function formatCheckbox(option) {
            if (!option.id) return option.text;

            const isSelected = $(option.element).prop('selected');
            return $(
                `<span><input type="checkbox" ${isSelected ? 'checked' : ''} style="margin-right: 10px;" />${option.text}</span>`
            );
        }

        function formatSelection(option) {
            return option.text;
        }
    });
</script>



@endpush
