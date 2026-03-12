@extends('branch.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Edit Product Variant</h3>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">{{ $variant->variant_name }}</h3></div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('branch.product.variant.update', $variant->id) }}" enctype="multipart/form-data">
                        @csrf


                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="form-group">
                            <label class="col-md-2 control-label">Variant Name</label>
                            <div class="col-md-6">
                                <input type="text" name="variant_name" class="form-control" value="{{ $variant->variant_name }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Actual Price (Rs)</label>
                            <div class="col-md-6">
                                <input type="number" step="0.01" name="variant_actual_price" class="form-control" value="{{ $variant->variant_actual_price }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Selling Price (Rs)</label>
                            <div class="col-md-6">
                                <input type="number" step="0.01" name="variant_selling_price" class="form-control" value="{{ $variant->variant_selling_price }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Stock</label>
                            <div class="col-md-6">
                                <input type="number" name="stock" class="form-control" value="{{ $variant->stock }}" required>
                            </div>
                        </div>

                        @php
                            $selectedAttributes = json_decode($variant->attributes, true) ?? [];
                        @endphp

                        @foreach($attributes as $attribute)
                        <div class="form-group">
                            <label class="col-md-2 control-label">{{ $attribute->name }}</label>
                            <div class="col-md-6">
                                <select name="attributes[{{ $attribute->name }}]" class="form-control" required>
                                    <option value="">Select {{ $attribute->name }}</option>
                                    @foreach($attribute->values as $value)
                                        <option value="{{ $value->value }}"
                                            {{ (isset($selectedAttributes[$attribute->name]) && $selectedAttributes[$attribute->name] === $value->value) ? 'selected' : '' }}>
                                            {{ $value->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endforeach

                        <div class="form-group">
                            <label class="col-md-2 control-label">Variant Images</label>
                            <div class="col-md-6">
                                @if($variant->images->isNotEmpty())
                                    <div class="mb-3">
                                        @foreach($variant->images as $img)
                                            <span class="d-inline-block mr-2 mb-2" style="position:relative;">
                                                <img src="{{ asset('public/' . $img->image_path) }}" alt="" style="max-height:60px; max-width:80px; object-fit:contain; border:1px solid #ddd;">
                                                <a href="{{ route('branch.product.variant.image.delete', $img->id) }}" class="btn btn-xs btn-danger" style="position:absolute; top:-8px; right:-8px;" onclick="return confirm('Remove this image?')">×</a>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                                <small class="text-muted">Add more images. Existing images above can be removed with ×.</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-6">
                                <button type="submit" class="btn btn-success">Update Variant</button>
                                <a href="{{ url()->previous() }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
