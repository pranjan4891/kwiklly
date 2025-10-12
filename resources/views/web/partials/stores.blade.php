 <div class="container">
      <h4 class="pb-3 pt-4 headingclass">Stores</h4>
      <div class="row justify-content-center">
         <!-- Store Item -->
         @if ($stores)

         @foreach ($stores as $store)
         <div class="col-md-2 col-4 store-item">
            <a href="{{route('explorestore',['vendor_id'=>$store->id,'cat_id'=>0] )}}"  onclick="return redirectWithLocation(this.href)" style="text-decoration: none">
                <div class="store-image">
                @php
                    $imagePath = public_path($store->business_logo);
                    $imageUrl = file_exists($imagePath)
                    ? asset('public/' . $store->business_logo)
                    : asset('public/uploads/no-image.jpg');
                @endphp

                    <img src="{{ $imageUrl }}" alt="Store">

                </div>
            </a>
            <div class="store-name"><a href="{{route('explorestore',['vendor_id'=>$store->id,'cat_id'=>0] )}}"  onclick="return redirectWithLocation(this.href)">{{ $store->business_name }}</a></div>
            @foreach($store->categories as $cat)
                {{-- <a href="{{ route('stores', ['slug' => $cat->slug])}}"  onclick="return redirectWithLocation(this.href)"> --}}
                    {{ $cat->name }}
                {{-- </a> --}}
                <br>
            @endforeach
            {{-- <div class="store-discount">Upto 28% Off</div> --}}
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
