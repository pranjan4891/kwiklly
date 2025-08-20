@extends('admin.includes.main')

@section('main')
<div class="wraper container-fluid">
    @if($isDeletedView)
    <div class="page-title">
        <h3 class="title">Deleted Banners</h3>
    </div>
    @else
    <div class="page-title">
        <h3 class="title">Add Banner</h3>
    </div>
    @endif
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
    @if(!$isDeletedView)
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Add Banner</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form">
                            <form class="cmxform form-horizontal tasi-form" method="post" action="{{ route('admin.banner.store') }}" enctype="multipart/form-data">

                                @csrf
                                <div class="form-group">
                                    <label for="cname" class="control-label col-lg-2">Banner Category *</label>
                                    <div class="col-lg-5">
                                        <select class="form-control" id="banner_cat_id" name="banner_cat_id" aria-required="true" required>
                                            <option value="">Select Category</option>
                                            <option value="1">Main Advertise</option>
                                            <option value="2">Advertise</option>
                                            <option value="3">Category</option>
                                            <option value="4">Store</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group desktop-upload">
                                    <label for="cname" class="control-label col-lg-2">Home Page Banner (DESKTOP) *</label>
                                    <div class="col-lg-5">
                                        <input class="form-control" id="cat_img" name="cat_img" type="file" aria-required="true">
                                        <span style="color: red">Dimension of images should be 1903 px * 600px</span>
                                    </div>
                                </div>
                                <div class="form-group mobile-upload">
                                    <label for="cname" class="control-label col-lg-2">Home Page Banner (MOBILE) *</label>
                                    <div class="col-lg-5">
                                        <input class="form-control" id="mob_img" name="mob_img" type="file" aria-required="true">
                                        <span style="color: red">Dimension of images should be 413 px * 413px</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="banner_url" class="control-label col-lg-2">Banner URL</label>
                                    <div class="col-lg-5">
                                        <input class="form-control" id="banner_url" name="banner_url" type="url" placeholder="https://example.com">
                                    </div>
                                </div>

                                <div class="form-group" id="store-category-wrapper" style="display: none;">
                                    <label for="store_category" class="control-label col-lg-2">Select Store Category *</label>
                                    <div class="col-lg-5">
                                        <select class="form-control" id="master_category" name="master_category">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="cname" class="control-label col-lg-2">Home Page Banner Position *</label>
                                    <div class="col-lg-5">
                                        <input class="form-control" id="banner_pos" name="banner_pos" type="text" aria-required="true" value="0">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-success" type="submit">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @foreach ([1 => 'Main Advertise', 2 => 'Advertise', 3 => 'Category', 4 => 'Store'] as $cat_id => $cat_label)
        @if(isset($banners[$cat_id]) && count($banners[$cat_id]) > 0)
            <div class="page-title">
                <h3 class="title">{{ $cat_label }}</h3>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Desktop Image</th>
                                        @if ($cat_id == 1)
                                            <th>Mobile Image</th>
                                        @endif
                                        <th>Banner URL</th>
                                        <th>Position</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($banners[$cat_id] as $banner)
                                        <tr>
                                            <td class="text-center">
                                                <img src="{{ asset('public/'.$banner->desktop_image) }}" style="max-width:100px;">
                                            </td>

                                            @if ($cat_id == 1)
                                                <td class="text-center">
                                                    @if($banner->mobile_image)
                                                        <img src="{{ asset('public/'.$banner->mobile_image) }}" style="max-width:80px;">
                                                    @else
                                                        <span>No Image</span>
                                                    @endif
                                                </td>
                                            @endif

                                            <td class="text-center">
                                                @if($banner->banner_url)
                                                    <a href="{{ $banner->banner_url }}" target="_blank">{{ $banner->banner_url }}</a>
                                                @else
                                                    <span>No URL</span>
                                                @endif
                                            </td>

                                            <td class="text-center">{{ $banner->position }}</td>

                                            <td class="text-center">
                                                <span class="label label-{{ $banner->is_active == 1 ? 'success' : 'danger' }}">
                                                    {{ $banner->is_active == 1 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>

                                            <td class="text-center">
                                                <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#editModal{{ $banner->id }}">Edit</button>
                                                 @if($isDeletedView)
                                                    <form method="POST" action="{{ route('admin.banners.forceDelete', $banner->id) }}" onsubmit="return confirm('Permanently delete this banner?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm">Erase</button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('admin.banners.delete', $banner->id) }}" onclick="return confirm('Soft delete this banner?')">
                                                        <button class="btn btn-warning btn-sm">Disable</button>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editModal{{ $banner->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $banner->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Banner</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" action="{{ route('admin.banners.update', $banner->id) }}" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label for="banner_cat_id">Banner Category *</label>
                                                                <select class="form-control" id="banner_cat_id" name="banner_cat_id" aria-required="true" required>

                                                                    <option value="1" {{ $banner->banner_cat_id == 1 ? 'selected' : '' }}>Main Advertise</option>
                                                                    <option value="2" {{ $banner->banner_cat_id == 2 ? 'selected' : '' }}>Advertise</option>
                                                                    <option value="3" {{ $banner->banner_cat_id == 3 ? 'selected' : '' }}>Category</option>
                                                                    <option value="4" {{ $banner->banner_cat_id == 4 ? 'selected' : '' }}>Store</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="cat_img">Home Page Banner (DESKTOP)</label>
                                                                <input class="form-control" id="cat_img" name="cat_img" type="file">
                                                                <span style="color: red">Dimension of images should be 1903 px * 600px</span>
                                                                <img src="{{ asset('public/'.$banner->desktop_image) }}" alt="" style="max-width:100px;">
                                                            </div>
                                                            @if ($cat_id == 1)
                                                                <div class="form-group">
                                                                    <label for="mob_img">Home Page Banner (MOBILE)</label>
                                                                    <input class="form-control" id="mob_img" name="mob_img" type="file">
                                                                    <span style="color: red">Dimension of images should be 413 px * 413px</span>
                                                                    @if($banner->mobile_image)
                                                                        <img src="{{ asset('public/'.$banner->mobile_image) }}" alt="" style="max-width:80px;">
                                                                    @endif
                                                                </div>
                                                            @endif
                                                            <div class="form-group">
                                                                <label for="banner_url">Banner URL</label>
                                                                <input class="form-control" id="banner_url" name="banner_url" type="url" value="{{ $banner->banner_url }}">
                                                            </div>
                                                            @if ($cat_id == 4)
                                                                <div class="form-group"></div>
                                                                    <label for="master_category">Select Store Category *</label>
                                                                    <select class="form-control" id="master_category" name="master_category">
                                                                        <option value="">Select Category</option>
                                                                        @foreach($categories as $category)
                                                                            <option value="{{ $category->id }}" {{ $banner->master_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @endif
                                                            <div class="form-group">
                                                                <label for="banner_pos">Home Page Banner Position</label>
                                                                <input class="form-control" id="banner_pos" name="banner_pos" type="text" value="{{ $banner->position }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="status">Status</label>
                                                                <select class="form-control" id="status" name="status">
                                                                    <option value="1" {{ $banner->is_active == 1 ? 'selected' : '' }}>Active</option>
                                                                    <option value="0" {{ $banner->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-primary">Update</button>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>
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
        @else
            <div class="panel panel-default">
                <div class="panel-body">
                    <p class="text-center">No {{ $cat_label }} banners found.</p>
                </div>
            </div>
        @endif

    @endforeach


</div>


@endsection
@push('scripts')
<script type="text/javascript">
    // Function to toggle visibility of fields based on selected category
    document.addEventListener('DOMContentLoaded', function () {
        const bannerCatSelect = document.getElementById('banner_cat_id');
        const mobileUpload = document.querySelector('.mobile-upload');
        const storeCategoryWrapper = document.getElementById('store-category-wrapper');

        function toggleFields() {
            // Toggle mobile upload visibility
            if (bannerCatSelect.value === '1') {
                mobileUpload.style.display = 'block';
            } else {
                mobileUpload.style.display = 'none';
            }

            // Toggle store category dropdown
            if (bannerCatSelect.value === '4') {
                storeCategoryWrapper.style.display = 'block';
            } else {
                storeCategoryWrapper.style.display = 'none';
            }
        }

        // Bind change event
        bannerCatSelect.addEventListener('change', toggleFields);

        // Initial toggle on page load
        toggleFields();
    });
</script>



@endpush
