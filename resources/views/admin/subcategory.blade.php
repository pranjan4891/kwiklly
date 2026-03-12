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
                                            <td><img src="{{ asset('public/uploads/subcategories/'.$subcategory->image) }}" alt="" width="50px" height="50px"></td>
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
                                                {{-- <a href="{{ url('admin/subcategory/delete//'.$subcategory->id) }}" class="btn btn-danger" onclick="return doconfirm();">Delete</a> --}}
                                                <form action="{{ route('admin.subcategory.delete', ['id'=>$subcategory->id]) }}" method="POST" style="display:inline;" onsubmit="return doconfirm();">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <!-- Edit Subcategory Modal -->
                                        <div class="modal fade" id="editModal{{ $subcategory->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $subcategory->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('admin.subcategory.update', $subcategory->id) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-header" style="border-bottom: 1px solid #eee; padding: 15px 20px;">
                                                            <h5 class="modal-title" id="editModalLabel{{ $subcategory->id }}" style="font-weight: 600; font-size: 18px;">Edit Subcategory</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -10px -15px -10px 0; padding: 10px; opacity: 0.6;">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body" style="padding: 20px 24px;">
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <div class="form-group" style="margin-bottom: 18px;">
                                                                        <label class="control-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Category Name <span class="text-danger">*</span></label>
                                                                        <select class="form-control" name="cat_id" required style="border-radius: 4px;">
                                                                            <option value="">Select Category</option>
                                                                            @foreach($categories as $category)
                                                                                <option value="{{ $category->id }}" {{ $subcategory->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <div class="form-group" style="margin-bottom: 18px;">
                                                                        <label class="control-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Subcategory Name <span class="text-danger">*</span></label>
                                                                        <input class="form-control" name="subcategory_name" type="text" value="{{ $subcategory->sub_cat_name }}" required style="border-radius: 4px;" placeholder="e.g. Mobile Phone">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group" style="margin-bottom: 18px;">
                                                                <label class="control-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Attributes <span class="text-danger">*</span></label>
                                                                <select class="form-control attribute-select edit-attribute-select" name="attribute[]" multiple required style="width: 100%; border-radius: 4px;">
                                                                    @foreach ($attributes as $attribute)
                                                                        <option value="{{ $attribute->id }}" {{ in_array($attribute->id, $subcategory->attributes->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $attribute->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <small class="text-muted" style="margin-top: 4px; display: block;">Search and select multiple attributes</small>
                                                            </div>

                                                            <div class="form-group" style="margin-bottom: 18px;">
                                                                <label class="control-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Sub-category Image</label>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <input class="form-control" name="subcat_img" type="file" accept="image/*" style="border-radius: 4px; padding: 6px;">
                                                                        <small class="text-muted" style="margin-top: 6px; display: block;">Min dimension: 120×120 or same ratio. Leave empty to keep current image.</small>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        @if($subcategory->image)
                                                                            <div class="subcat-edit-preview" style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; background: #fafafa; text-align: center; min-height: 100px;">
                                                                                <p style="font-size: 11px; color: #888; margin-bottom: 8px;">Current image</p>
                                                                                <img src="{{ asset('public/'.$subcategory->image) }}" alt="Subcategory" style="max-width: 80px; max-height: 80px; object-fit: contain;">
                                                                            </div>
                                                                        @else
                                                                            <div class="subcat-edit-preview" style="border: 1px dashed #ddd; border-radius: 6px; padding: 20px; background: #f9f9f9; text-align: center; min-height: 100px; color: #999;">
                                                                                <span style="font-size: 12px;">No image uploaded</span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group" style="margin-bottom: 0;">
                                                                <label class="control-label" style="font-weight: 600; margin-bottom: 6px; display: block;">Status</label>
                                                                <select class="form-control" name="status" required style="max-width: 200px; border-radius: 4px;">
                                                                    <option value="1" {{ $subcategory->is_active == 1 ? 'selected' : '' }}>Active</option>
                                                                    <option value="0" {{ $subcategory->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer" style="border-top: 1px solid #eee; padding: 14px 24px; background: #fafafa;">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
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
