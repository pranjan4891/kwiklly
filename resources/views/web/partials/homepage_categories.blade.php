@if ($homepageCategories->count() > 0)

        <!-- Repeat this block for all your categories -->
        @foreach ($homepageCategories as $cat)
        <div class="col-md-4 col-4 new-cate-item-wrap">
        <div class="new-cate-item">
            <a href="{{route('stores', ['slug' => $cat->slug])}}" class="text-decoration-none text-dark"  onclick="return redirectWithLocation(this.href)">
                <div class="product-carded">
                    <img src="{{ asset('public/' . $cat->image) }}" alt="{{ $cat['name'] }}">
                </div>
                <div class="py-2 text-center catename"><b>{{ $cat['name'] }}</b></div>
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
