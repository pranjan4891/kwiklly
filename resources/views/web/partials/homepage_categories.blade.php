{{-- Sirf wahi categories jinke products vendor delivery location ke andar hain (controller se filtered) --}}
@php
$uniqueHomepageCategories = $homepageCategories->unique('id')->values();
@endphp
@if ($uniqueHomepageCategories->count() > 0)
        @include('web.partials.category_odd_even_grid', ['categories' => $homepageCategories, 'layout' => 'mobile'])
@else
    <div class="text-center py-5">
        <p>No categories available in your area</p>
    </div>
@endif
