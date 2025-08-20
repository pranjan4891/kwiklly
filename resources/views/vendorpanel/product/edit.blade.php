@extends('vendorpanel.include.main')

@section('content')
    <div class="wraper container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Edit Product</h2>
                    </div>

                    <div class="panel-body">
                        <form action="{{ route('vendor.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Product Title</label>
                                    <input type="text" name="title" class="form-control" value="{{ $product->title }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sub_title">Sub Title</label>
                                    <input type="text" name="sub_title" class="form-control" value="{{ $product->sub_title }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Category</label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sub_category_id">Sub Category</label>
                                    <select name="sub_category_id" id="sub_category_id" class="form-control">
                                        <option value="">-- Select Sub Category --</option>
                                        @foreach ($subcategories as $sub)
                                            <option value="{{ $sub->id }}" {{ $product->sub_category_id == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->sub_cat_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top: 20px; margin-bottom: 20px;">
                                <div class="form-check">
                                    <input type="checkbox" name="is_physical" value="1" class="form-check-input"
                                        id="is_physical" {{ $product->is_physical ? 'checked' : '' }} onchange="togglePhysicalFields()">
                                    <label class="form-check-label" for="is_physical">Physical Product</label>
                                </div>
                            </div>
                          <div class="col-md-12" id="physicalProductFields" style="display: none;">
                                <div class="form-group">
                                    <label for="description">Product Description</label>
                                    <textarea name="description" rows="3" class="form-control">{{ $product->description }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="disclaimer">Disclaimer</label>
                                    <textarea name="disclaimer" rows="2" class="form-control">{{ $product->disclaimer }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="information">Additional Information</label>
                                    <textarea name="information" rows="2" class="form-control">{{ $product->information }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="gst">GST (%)</label>
                                    <input type="number" name="gst" id="gst" class="form-control" step="0.01"
                                        value="{{ old('gst', $product->cgst + $product->sgst) }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cgst">CGST (%)</label>
                                    <input type="number" name="cgst" class="form-control" step="0.01"
                                        value="{{ old('cgst', $product->cgst) }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sgst">SGST (%)</label>
                                    <input type="number" name="sgst" class="form-control" step="0.01"
                                        value="{{ old('sgst', $product->sgst) }}" readonly>
                                </div>
                            </div>


                            <div class="col-md-12 mb-3">
                                <label>Feature Image</label><br>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#imageModal">Select Image</button>
                                <a class="btn btn-warning btn-sm ml-2" href="{{ route('admin.product.images') }}" target="_blank">Upload New Image</a>
                                <input type="hidden" name="feature_image_id" id="feature_image_id" value="{{ $product->feature_image_id }}">
                                <p class="mt-2">Selected Image:
                                    <strong id="selectedImageText" class="text-danger">
                                        {{ $product->featureImage->product_name ?? 'None' }}
                                    </strong>
                                </p>
                            </div>

                            <div class="col-md-4" style="margin-top: 20px; margin-bottom: 20px;">
                                <div class="form-check">
                                    <input type="checkbox" name="best_offers" value="1" class="form-check-input"
                                        id="bestOffers" {{ $product->best_offers ? 'checked' : '' }}>
                                    <label class="form-check-label" for="bestOffers">Best Offers</label>
                                </div>
                            </div>

                            <div class="col-md-4" style="margin-top: 20px; margin-bottom: 20px;">
                                <div class="form-check">
                                    <input type="checkbox" name="top_selling" value="1" class="form-check-input"
                                        id="topSelling" {{ $product->top_selling ? 'checked' : '' }}>
                                    <label class="form-check-label" for="topSelling">Top Selling</label>
                                </div>
                            </div>

                            <div class="col-md-4"   style="margin-top: 20px; margin-bottom: 20px;">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input"
                                        id="isActive" {{ $product->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">Active</label>
                                </div>
                            </div>

                            <input type="hidden" name="is_deleted" value="0">

                            <button type="submit" class="btn btn-primary mt-3">Update Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Feature Image Modal --}}
    @include('admin.product.partials.image-modal')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#category_id').on('change', function () {
            let categoryId = $(this).val();
            $('#sub_category_id').html('<option value="">Loading...</option>');

            if (categoryId) {
                $.ajax({
                    url: '{{ route("vendor.getSubcategories") }}',
                    method: 'GET',
                    data: { category_id: categoryId },
                    success: function (res) {
                        let options = '<option value="">-- Select Sub Category --</option>';
                        res.forEach(function (sub) {
                            options += `<option value="${sub.id}" ${sub.id == "{{ $product->sub_category_id }}" ? 'selected' : ''}>${sub.sub_cat_name}</option>`;
                        });
                        $('#sub_category_id').html(options);
                    }
                });
            } else {
                $('#sub_category_id').html('<option value="">-- Select Sub Category --</option>');
            }
        });

        $('#imageSearchInput').on('keyup', function () {
            const searchText = $(this).val().toLowerCase();
            $('.image-item').each(function () {
                const productName = $(this).data('product');
                const brandName = $(this).data('brand');
                const match = productName.includes(searchText) || brandName.includes(searchText);
                $(this).toggle(match);
            });
        });

        $(document).on('click', '.select-feature-image', function () {
            let imageId = $(this).data('id');
            let imageName = $(this).data('name');

            $('#feature_image_id').val(imageId);
            $('#selectedImageText').text(imageName);
            $('#imageModal').modal('hide');
        });
    </script>
    <script>
    $(document).ready(function () {
        function togglePhysicalFields() {
            if ($('#is_physical').is(':checked')) {
                $('#physicalProductFields').slideDown();
            } else {
                $('#physicalProductFields').slideUp();
            }
        }

        // Run on load
        togglePhysicalFields();

        // Run on change
        $('#is_physical').on('change', function () {
            togglePhysicalFields();
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const gstInput = document.getElementById('gst');
        const cgstInput = document.querySelector('input[name="cgst"]');
        const sgstInput = document.querySelector('input[name="sgst"]');

        gstInput.addEventListener('input', function () {
            let gst = parseFloat(this.value);
            if (!isNaN(gst)) {
                let half = (gst / 2).toFixed(2);
                cgstInput.value = half;
                sgstInput.value = half;
            } else {
                cgstInput.value = '';
                sgstInput.value = '';
            }
        });
    });
</script>

@endsection
