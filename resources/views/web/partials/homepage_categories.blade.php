{{-- Sirf wahi categories jinke products vendor delivery location ke andar hain (controller se filtered) --}}
@php
$uniqueHomepageCategories = $homepageCategories->unique('id')->values();
@endphp
@if ($uniqueHomepageCategories->count() > 0)

        <!-- Repeat this block for all your categories -->
        @foreach ($uniqueHomepageCategories as $cat)
        @php
           $categoryName = strtolower($cat['name']);
           // Replace & with & (with spaces) if not already spaced
           $categoryName = preg_replace('/\s*&\s*/', ' & ', $categoryName);
           // Convert to title case
           $categoryName = ucwords($categoryName);
        @endphp
        <div class="col-md-4 col-4 new-cate-item-wrap">
        <div class="new-cate-item">
            <a href="{{route('stores', ['slug' => $cat->slug])}}" class="text-decoration-none text-dark"  onclick="return redirectWithLocation(this.href)">
                <div class="product-carded">
                    <img src="{{ asset('public/' . $cat->image) }}" alt="{{ $categoryName }}">
                </div>
                <div class="py-2 text-center catename"><b>{{ $categoryName }}</b></div>
            </a>
        </div>
        </div>
        @endforeach

    {{-- <div class="text-center">
        <button class="view-all-btn mt-3" id="loadMoreBtn">
        Load More <i class="fa fa-angles-down ms-2"></i>
        </button>
    </div> --}}
@else
    <div class="text-center py-5">
        <p>No categories available in your area</p>
    </div>
@endif
