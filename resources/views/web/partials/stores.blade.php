 <div class="container">
      <h4 class="pb-3 pt-4 headingclass">Stores</h4>
      <div class="row justify-content-center">
         <!-- Store Item -->
         @if ($stores)
         @foreach ($stores as $store)
         <div class="col-md-2 col-4 store-item">
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
            @foreach($store->categories as $cat)
                <a href="{{ route('stores', ['slug' => $cat->slug])}}"  onclick="return redirectWithLocation(this.href)">{{ $cat->name }}
                </a>
            @endforeach
            <div class="store-discount">Upto 28% Off</div>
         </div>
         @endforeach
         @endif
      </div>
      <!-- View All Button -->
      <div class="text-center">
         <a class="view-all-btn mt-3" href="{{ route('stores', ['slug' => 'all']) }}"  onclick="return redirectWithLocation(this.href)">
         View All <i class="fa fa-angles-down ms-2"></i>
         </a>
      </div>
   </div>
