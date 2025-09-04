@extends('branch.includes.main')

@section('main')
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Add Product Variant</h3>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Add Variant</h3></div>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <li>{{ $errors->first() }}</li>
                        </ul>
                    </div>
                @endif
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('branch.product.variant.store') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="form-group">
                            <label class="col-md-2 control-label">Variant Name</label>
                            <div class="col-md-6">
                                <input type="text" name="variant_name" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Actual Price (Rs)</label>
                            <div class="col-md-6">
                                <input type="number" step="0.01" name="variant_actual_price" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Selling Price (Rs)</label>
                            <div class="col-md-6">
                                <input type="number" step="0.01" name="variant_selling_price" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Stock</label>
                            <div class="col-md-6">
                                <input type="number" name="stock" class="form-control" required>
                            </div>
                        </div>

                        @foreach($attributes as $attribute)
                        <div class="form-group">
                            <label class="col-md-2 control-label">{{ $attribute->name }}</label>
                            <div class="col-md-6">
                                <select name="attributes[{{ $attribute->name }}]" class="form-control" required>
                                    <option value="">Select {{ $attribute->name }}</option>
                                    @foreach($attribute->values as $value)
                                        <option value="{{ $value->value }}">{{ $value->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endforeach

                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-6">
                                <button type="submit" class="btn btn-success">Save Variant</button>
                                <a href="{{ url()->previous() }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Variant Listing --}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Product Variants</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Variant Name</th>
                                <th>Actual Price</th>
                                <th>Selling Price</th>
                                <th>Save (Rs)</th>
                                <th>Save (%)</th>
                                <th>Stock</th>
                                <th>Attributes</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->variants as $index => $variant)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $variant->variant_name }}</td>
                                    <td>₹{{ number_format($variant->variant_actual_price, 2) }}</td>
                                    <td>₹{{ number_format($variant->variant_selling_price, 2) }}</td>
                                    <td>₹{{ number_format($variant->variant_save_price_in_rs, 2) }}</td>
                                    <td>{{ $variant->variant_save_price_in_percent }}%</td>
                                    <td>{{ $variant->stock }}</td>
                                    <td>
                                        @php
                                            $attrs = json_decode($variant->attributes, true);
                                        @endphp

                                        @if(!empty($attrs) && is_array($attrs))
                                            @foreach($attrs as $attrName => $attrValue)
                                                <strong>{{ $attrName }}:</strong> {{ $attrValue }}<br>
                                            @endforeach
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('branch.product.variant.edit', $variant->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('branch.product.variant.destroy', $variant->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this variant?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="10" class="text-center">No variants available.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
