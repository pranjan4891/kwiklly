@php
    $currentDay = $currentDay ?? now()->format('l');
    $currentTime = $currentTime ?? null;
    $isOpen = $isOpen ?? false;
@endphp

<div class="store-sectionde">
    <div class="store-infode">
        <h2>Departments</h2>
        <p>Explore the best of the premium store in your locality. We provide you the access of awesome products under one roof from the store with exclusive coupons, deals, and discounts.</p>

        @if($currentTime)
        <div class="time-container" style="width:50%">
            <div class="time-boxde">{{ $currentDay }} {{ $currentTime }}</div>
        </div>
        @endif

        @if($branches && count($branches) > 0)
        <div class="time-container" style="width:50%">
            <label for="brnch" class="me-2">Select Branch:</label>
            <select name="branch" id="brnch" class="time-boxde" onchange="changeBranch(this.value)">
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ ($selectedVendor && $selectedVendor->id == $branch->id) ? 'selected' : '' }}>
                        {{ $branch->business_name }} ({{ ucfirst($branch->user_type) }})
                    </option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    <div class="online-statusde">
        <span class="{{ $isOpen ? 'open' : 'closed' }}"></span>
        <span class="status-text">{{ $isOpen ? 'Online' : 'Offline' }}</span>
    </div>

    @if($selectedVendor && $selectedVendor->coupons && count($selectedVendor->coupons) > 0)
        @php $coupon = $selectedVendor->coupons->first(); @endphp
        <div class="coupon-boxde">
            <div class="coupon-headerde">
                <h4>{{ $coupon->discount_value }}{{ $coupon->discount_type == 'percentage' ? '% OFF' : ' OFF' }}</h4>
                <img src="{{ $selectedVendor->business_logo ? Storage::url($selectedVendor->business_logo) : asset('public/assets/website/images/klogo.png') }}" alt="{{ $selectedVendor->business_name }} Logo">
            </div>
            <div class="coupon-contentde">
                <div class="coupon-leftde">
                    <p>MAX ₹ {{ $coupon->max_discount ?? '200' }}</p>
                    <span class="mt-2">{{ $coupon->name }}</span>
                </div>
                <div class="coupon-rightde">
                    <p class="p-0">COUPON EXPIRES {{ $coupon->expiry_date ? date('d/m', strtotime($coupon->expiry_date)) : '--/--' }}</p>
                </div>
            </div>
        </div>
    @else
        <!-- Static coupon as fallback -->
        <div class="coupon-boxde">
            <div class="coupon-headerde">
                <h4>20% OFF</h4>
                <img src="{{ asset('public/assets/website/images/klogo.png')}}" alt="Company Logo">
            </div>
            <div class="coupon-contentde">
                <div class="coupon-leftde">
                    <p>MAX ₹ 200</p>
                    <span class="mt-2">Holi Week Discount</span>
                </div>
                <div class="coupon-rightde">
                    <p class="p-0">COUPON EXPIRES 23/05</p>
                </div>
            </div>
        </div>
    @endif
</div>
