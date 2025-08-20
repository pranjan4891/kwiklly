<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Feature Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                {{-- Search Field --}}
                <div class="form-group mb-3">
                    <input type="text" id="imageSearchInput" class="form-control" placeholder="Search by product name or brand name" oninput="filterImages()">
                </div>

                {{-- Image List --}}
                <div class="row" id="imageList">
                    @foreach ($images as $img)
                        <div class="col-md-3 mb-3 image-item" data-product="{{ strtolower($img->product_name) }}" data-brand="{{ strtolower($img->brand_name) }}">
                            <img src="{{ asset('public/' . $img->feature_image) }}"
                                 alt=""
                                 class="img-thumbnail select-feature-image"
                                 style="cursor: pointer;"
                                 data-id="{{ $img->id }}"
                                 data-name="{{ basename($img->product_name) }}">
                            <p class="text-center mt-2">{{ $img->product_name }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
