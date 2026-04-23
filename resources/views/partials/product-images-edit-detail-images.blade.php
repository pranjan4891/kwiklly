{{-- $product: App\Models\ProductImages; optional $extraHelp: string --}}
@php
    $paths = $product->product_images;
@endphp
<div class="form-group">
    <label>Product Detail Images</label><br>
    <div class="product-detail-images-preview" style="min-height: 92px; max-height: 240px; overflow-y: auto; margin-bottom: 10px; padding: 12px; border: 1px solid #e2e2e2; background: #fafafa; border-radius: 4px;">
        @forelse ($paths as $img)
            <img src="{{ \App\Models\ProductImages::publicAssetUrl($img) }}" alt="" style="width: 80px; height: 80px; object-fit: contain; margin: 0 10px 10px 0; display: inline-block; vertical-align: top; border: 1px solid #ddd; background: #fff; border-radius: 3px; padding: 4px;">
        @empty
            <span class="text-muted" style="line-height: 80px; display: inline-block;">No product detail images yet.</span>
        @endforelse
    </div>
    <input type="file" name="product_images[]" class="form-control" multiple accept="image/*">
    <small class="text-muted" style="display: block; margin-top: 6px;">Optional: choose files to add more images. Leave unchanged to keep the images above.</small>
    @if (! empty($extraHelp ?? null))
        <small class="text-muted" style="display: block; margin-top: 4px;">{{ $extraHelp }}</small>
    @endif
</div>
