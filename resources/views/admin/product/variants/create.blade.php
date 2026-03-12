@extends('admin.includes.main')

@section('main')
<style>
.variant-create-info { background: #f0f7ff; border: 1px solid #b8d4f0; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; font-size: 13px; color: #1a4d7a; }
.variant-create-info strong { color: #0d3d6b; }
.variant-section-divider { border: 0; height: 1px; background: #e0e0e0; margin: 24px 0 20px; }
.variant-colors-heading { font-size: 16px; font-weight: 600; color: #333; margin: 0 0 4px 0; }
.variant-colors-sub { font-size: 12px; color: #666; margin-bottom: 16px; }
.color-block { border: 1px solid #e5e5e5; border-radius: 4px; background: #fafafa; padding: 12px 0; margin-bottom: 12px; }
.color-block .form-group { margin-bottom: 0; }
.color-block .color-row-inline .form-control { display: inline-block; }
.btn-color-icon { padding: 6px 10px; }
.variant-form-actions { margin-top: 24px; padding-top: 16px; border-top: 1px solid #eee; }
.variant-form-actions .btn { margin-right: 8px; }
#addColorBlock { margin-bottom: 8px; }
</style>
<div class="wraper container-fluid">
    <div class="page-title">
        <h3 class="title">Add Product Variant</h3>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Add Variant</h3></div>
                <div class="panel-body">
                    
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
                    @php
                        $colorAttr = $attributes->firstWhere('type', 'color');
                        $nonColorAttrs = $attributes->filter(fn($a) => ($a->type ?? 'text') !== 'color');
                    @endphp

                    <form class="form-horizontal" method="POST" action="{{ route('product.variant.store') }}" id="variantForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="form-group">
                            <label class="col-md-2 control-label">Variant Base Name</label>
                            <div class="col-md-6">
                                <input type="text" name="variant_base_name" id="variant_base_name" class="form-control" placeholder="e.g. 8/256 ya 32" required>
                                <small class="text-muted">Sirf variant part (e.g. 8/256). Color name auto add hoga.</small>
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

                        @if(!$colorAttr)
                        <div class="form-group">
                            <label class="col-md-2 control-label">Stock</label>
                            <div class="col-md-6">
                                <input type="number" name="stock" class="form-control" value="0" required>
                            </div>
                        </div>
                        @endif

                        @foreach($nonColorAttrs as $attribute)
                        <div class="form-group">
                            <label class="col-md-2 control-label">{{ $attribute->name }}</label>
                            <div class="col-md-6">
                                <select name="attributes[{{ $attribute->name }}]" class="form-control non-color-attr" data-name="{{ $attribute->name }}" required>
                                    <option value="">Select {{ $attribute->name }}</option>
                                    @foreach($attribute->values as $value)
                                        <option value="{{ $value->value }}">{{ $value->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endforeach

                        @if($colorAttr)
                        <hr class="variant-section-divider">
                        <div id="colorBlocks" class="col-md-12">
                            <div class="color-block">
                                <div class="form-group color-row-inline">
                                    <label class="col-md-2 control-label">Color</label>
                                    <div class="col-md-3">
                                        <select name="colors[0][color]" class="form-control color-select" required>
                                            <option value="">Select Color</option>
                                            @foreach($colorAttr->values as $value)
                                                <option value="{{ $value->value }}">{{ $value->value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="file" name="colors[0][images][]" class="form-control" accept="image/*" multiple>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="colors[0][stock]" class="form-control color-stock" value="0" min="0" placeholder="Stock">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-color btn-color-icon" title="Remove"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-6">
                                <button type="button" id="addColorBlock" class="btn btn-info btn-color-icon" title="Add another color"><i class="fa fa-plus"></i> Add color</button>
                            </div>
                        </div>
                        <input type="hidden" name="has_color_blocks" value="1">
                        @else
                        {{-- No Color attribute: old single-variant form --}}
                        <div class="form-group">
                            <label class="col-md-2 control-label">Variant Name</label>
                            <div class="col-md-6">
                                <input type="text" name="variant_name" class="form-control" placeholder="e.g. 8/256 ya Default" required>
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
                            <label class="col-md-2 control-label">Variant Images</label>
                            <div class="col-md-6">
                                <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                            </div>
                        </div>
                        <input type="hidden" name="has_color_blocks" value="0">
                        @endif

                        <div class="form-group variant-form-actions">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">Save {{ $colorAttr ? 'Variants (one per color)' : 'Variant' }}</button>
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
                                <th>Images</th>
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
                                        @php $imgs = $variant->images ?? collect(); @endphp
                                        @if($imgs->isNotEmpty())
                                            {{ $imgs->count() }} image(s)
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('product.variant.edit', $variant->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('product.variant.destroy', $variant->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this variant?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="11" class="text-center">No variants available.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if($colorAttr)
<script>
document.addEventListener('DOMContentLoaded', function() {
    var colorBlocks = document.getElementById('colorBlocks');
    var addBtn = document.getElementById('addColorBlock');
    var colorOptions = @json($colorAttr->values->map(fn($v) => ['value' => $v->value, 'label' => $v->value])->values()->all());
    var index = 1;

    function getColorSelectHtml() {
        var h = '<option value="">Select Color</option>';
        colorOptions.forEach(function(o) { h += '<option value="' + (o.value || '') + '">' + (o.label || '') + '</option>'; });
        return h;
    }

    addBtn.addEventListener('click', function() {
        var block = document.querySelector('.color-block').cloneNode(true);
        block.querySelector('.color-select').name = 'colors[' + index + '][color]';
        block.querySelector('.color-select').value = '';
        block.querySelector('.color-select').innerHTML = getColorSelectHtml();
        block.querySelector('input[type="file"]').name = 'colors[' + index + '][images][]';
        block.querySelector('input[type="file"]').value = '';
        var stockInput = block.querySelector('.color-stock');
        if (stockInput) {
            stockInput.name = 'colors[' + index + '][stock]';
            stockInput.value = '0';
        }
        colorBlocks.appendChild(block);
        index++;
    });

    colorBlocks.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-color')) {
            var blocks = colorBlocks.querySelectorAll('.color-block');
            if (blocks.length > 1) {
                e.target.closest('.color-block').remove();
            }
        }
    });

    document.querySelectorAll('.non-color-attr').forEach(function(sel) {
        sel.addEventListener('change', function() {
            var parts = [];
            document.querySelectorAll('.non-color-attr').forEach(function(s) {
                if (s.value) parts.push(s.value);
            });
            if (parts.length) document.getElementById('variant_base_name').value = parts.join('/');
        });
    });
});
</script>
@endif
@endsection
