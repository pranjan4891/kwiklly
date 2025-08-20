@extends('branch.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Create New Coupon</h3>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Coupon Form</h3>
                </div>

                <div class="col-sm-12 mt-2">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                </div>

                <div class="panel-body">
                    <form action="{{ route('branch.coupons.store') }}" method="POST">
                        @csrf

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Coupon Code <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Discount Type <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="discount_type" class="form-control" required>
                                    <option value="" disabled selected>Select Discount Type</option>
                                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Discount Value <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <input type="number" step="0.01" name="discount_value" class="form-control" value="{{ old('discount_value') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Minimum Order Amount</label>
                            <div class="col-lg-8">
                                <input type="number" step="0.01" name="min_order_amount" class="form-control" value="{{ old('min_order_amount') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Max Uses</label>
                            <div class="col-lg-8">
                                <input type="number" name="max_uses" class="form-control" value="{{ old('max_uses') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Max Uses per User</label>
                            <div class="col-lg-8">
                                <input type="number" name="max_uses_per_user" class="form-control" value="{{ old('max_uses_per_user') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Valid From</label>
                            <div class="col-lg-8">
                                <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Expires At</label>
                            <div class="col-lg-8">
                                <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Applies To <span class="text-danger">*</span></label>
                            <div class="col-lg-8">
                                <select name="applies_to" class="form-control" required>
                                    <option value="all" {{ old('applies_to') == 'all' ? 'selected' : '' }}>All Products</option>
                                    <option value="product" {{ old('applies_to') == 'product' ? 'selected' : '' }}>Specific Products</option>
                                    <option value="category" {{ old('applies_to') == 'category' ? 'selected' : '' }}>Categories</option>
                                    <option value="subcategory" {{ old('applies_to') == 'subcategory' ? 'selected' : '' }}>Subcategories</option>
                                </select>
                            </div>
                        </div>

                        {{-- Dynamic multi-selects --}}
                       <div id="product-select" class="form-group row">
                            <label class="col-lg-2 col-form-label">Select Products</label>
                            <div class="col-lg-8">
                                <select name="product_ids[]" class="form-control select2" multiple="multiple" data-placeholder="Select Products">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->title }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div id="category-select" class="form-group row">
                            <label class="col-lg-2 col-form-label">Select Categories</label>
                            <div class="col-lg-8">
                               <select name="category_ids[]" class="form-control select2" multiple="multiple" data-placeholder="Select Categories">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="subcategory-select" class="form-group row">
                            <label class="col-lg-2 col-form-label">Select Subcategories</label>
                            <div class="col-lg-8">
                                <select name="subcategory_ids[]" class="form-control select2" multiple="multiple" data-placeholder="Select Subcategories">
                                    @foreach($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}">{{ $subcategory->sub_cat_name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Status</label>
                            <div class="col-lg-8">
                                <label>
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}> Active
                                </label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-save"></i> Save Coupon
                                </button>
                                <a href="{{ route('branch.coupons.index') }}" class="btn btn-default">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </form>
                </div> <!-- end panel-body -->
            </div> <!-- end panel -->
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {

        function formatResult(data) {
            if (!data.id) return data.text;

            // Hide already-selected items from the dropdown
            const selectedValues = $(data.element).parent().val() || [];
            if (selectedValues.includes(data.id)) {
                return null;
            }

            return $('<span><input type="checkbox" style="margin-right:6px;"  />' + data.text + '</span>');
        }

        $('.select2').select2({
            closeOnSelect: false,
            width: '100%',
            templateResult: formatResult,
            templateSelection: function (data) {
                return data.text;
            },
            escapeMarkup: function (markup) {
                return markup;
            }
        });

        // Show/hide specific selectors based on applies_to value
        function toggleItemSelectors() {
            const appliesTo = $('select[name="applies_to"]').val();
            $('#product-select, #category-select, #subcategory-select').hide();

            if (appliesTo === 'product') {
                $('#product-select').show();
            } else if (appliesTo === 'category') {
                $('#category-select').show();
            } else if (appliesTo === 'subcategory') {
                $('#subcategory-select').show();
            }
        }

        toggleItemSelectors(); // initial check
        $('select[name="applies_to"]').on('change', toggleItemSelectors);
    });
</script>

@endpush

