@extends('admin.includes.main')

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
                    <form class="form-horizontal" method="POST" action="{{ route('product.variant.update', $variant->id) }}">
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
