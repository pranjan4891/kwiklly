 @if ($stores && count($stores) > 0)
    <div class="container">
      <h4 class="pb-3 pt-4 headingclass">Popular Stores</h4>
      <div class="row justify-content-center">
         <!-- Store Item -->        

         @foreach ($stores as $store)
         <div class="col-md-2 col-4 store-item">
            <a href="{{route('explorestore',['vendor_id'=>$store->id,'cat_id'=>0] )}}"  onclick="return redirectWithLocation(this.href)" class="store-card-link">
                <div class="store-image">
                @php
                    $imagePath = public_path($store->business_logo);
                    $imageUrl = file_exists($imagePath)
                    ? asset('public/' . $store->business_logo)
                    : asset('public/uploads/no-image.jpg');
                @endphp
                    <img src="{{ $imageUrl }}" alt="Store">
                </div>
                <div class="store-name">{{ $store->business_name }}</div>
                @if($store->categories->count() > 0)
                    @php
                        $firstCategory = $store->categories->first();
                    @endphp
                    <span class="store-category-link" onclick="event.stopPropagation(); redirectWithLocation('{{ route('stores', ['slug' => $firstCategory->slug]) }}'); return false;">{{ $firstCategory->name }}</span>
                @endif
                @php
                    // Calculate maximum discount percentage from products
                    $maxDiscount = 0;
                    try {
                        $products = \App\Models\Product::where('vendor_id', $store->id)
                            ->where('is_active', 1)
                            ->where('is_deleted', 0)
                            ->with('variants')
                            ->limit(50) // Limit for performance
                            ->get();
                        
                        foreach ($products as $product) {
                            foreach ($product->variants as $variant) {
                                if ($variant->variant_actual_price > 0 && $variant->variant_selling_price < $variant->variant_actual_price) {
                                    $discount = (($variant->variant_actual_price - $variant->variant_selling_price) / $variant->variant_actual_price) * 100;
                                    if ($discount > $maxDiscount) {
                                        $maxDiscount = $discount;
                                    }
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        $maxDiscount = 0;
                    }
                @endphp
                @if($maxDiscount > 0)
                    <div class="store-discount">Upto {{ (int) round($maxDiscount) }}% Off</div>
                @endif
            </a>
         </div>
         @endforeach
         
      </div>
      <!-- View All Button -->
      <!-- <div class="text-center">
         <a class="view-all-btn mt-3" href="{{ route('stores', ['slug' => 'all']) }}"  onclick="return redirectWithLocation(this.href)">
         View All <i class="fa fa-angles-down ms-2"></i>
         </a>
      </div> -->
   </div>
@endif