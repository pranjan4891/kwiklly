{{--
  Sequential order by id (row1: 1–4, row2: 5–8).
  Desktop Owl: har slide item = ek column — upar + neeche (indices col aur col+rowSize).
  Mobile: same order flat grid (4×2) — DOM order AJAX / JS ke liye row-major (1…8).
--}}
@php
    $layout = $layout ?? 'mobile';
    $orderedCategories = $categories->unique('id')->sortBy('id')->values();
@endphp

@if ($layout === 'desktop')
    @php
        $slides = $orderedCategories->chunk(8);
    @endphp
    @foreach ($slides as $slideCats)
        @php
            $slideCats = $slideCats->values();
            $n = $slideCats->count();
            $rowSize = (int) ceil($n / 2);
        @endphp
        @for ($col = 0; $col < $rowSize; $col++)
            @php
                $topCat = $slideCats[$col] ?? null;
                $bottomIdx = $col + $rowSize;
                $bottomCat = ($bottomIdx < $n) ? $slideCats[$bottomIdx] : null;
            @endphp
            <div class="new-cate-item p-0">
                @if ($topCat)
                    @php
                        $categoryName = strtolower($topCat['name']);
                        $categoryName = preg_replace('/\s*&\s*/', ' & ', $categoryName);
                        $categoryName = ucwords($categoryName);
                    @endphp
                    <a href="{{ route('stores', ['slug' => $topCat->slug]) }}" class="text-decoration-none text-dark" onclick="return redirectWithLocation(this.href)">
                        <div class="product-carded">
                            <img src="{{ asset('public/' . $topCat->image) }}" alt="{{ $categoryName }}">
                        </div>
                        <div class="py-2 text-center catename"><b>{{ $categoryName }}</b></div>
                    </a>
                @endif
                @if ($bottomCat)
                    @php
                        $categoryName = strtolower($bottomCat['name']);
                        $categoryName = preg_replace('/\s*&\s*/', ' & ', $categoryName);
                        $categoryName = ucwords($categoryName);
                    @endphp
                    <a href="{{ route('stores', ['slug' => $bottomCat->slug]) }}" class="text-decoration-none text-dark" onclick="return redirectWithLocation(this.href)">
                        <div class="product-carded">
                            <img src="{{ asset('public/' . $bottomCat->image) }}" alt="{{ $categoryName }}">
                        </div>
                        <div class="py-2 text-center catename"><b>{{ $categoryName }}</b></div>
                    </a>
                @endif
            </div>
        @endfor
    @endforeach
@else
    {{-- Mobile + AJAX: Bootstrap row — col-md-4 col-4; pehli 8 visible, baaki Load More --}}
    @foreach ($orderedCategories as $idx => $cat)
        @php
            $categoryName = strtolower($cat['name']);
            $categoryName = preg_replace('/\s*&\s*/', ' & ', $categoryName);
            $categoryName = ucwords($categoryName);
            $isExtra = $idx >= 8;
        @endphp
        <div class="col-md-4 col-4 new-cate-item-wrap {{ $isExtra ? 'new-cate-item-wrap--extra' : '' }}">
            <div class="new-cate-item">
                <a href="{{ route('stores', ['slug' => $cat->slug]) }}" class="text-decoration-none text-dark d-block" onclick="return redirectWithLocation(this.href)">
                    <div class="product-carded">
                        <img src="{{ asset('public/' . $cat->image) }}" alt="{{ $categoryName }}">
                    </div>
                    <div class="py-2 text-center catename"><b>{{ $categoryName }}</b></div>
                </a>
            </div>
        </div>
    @endforeach
@endif
