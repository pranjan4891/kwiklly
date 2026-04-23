@php
    use Carbon\Carbon;

    $currentDay = $currentDay ?? now()->format('l');
    $tz = config('app.timezone'); // "Asia/Kolkata"
    $now = Carbon::now($tz);
    $currentTimeData = null;
    $isOpen = false;
    
    if ($selectedVendor && $selectedVendor->store_time) {
        $decoded = is_array($selectedVendor->store_time) 
            ? $selectedVendor->store_time 
            : json_decode($selectedVendor->store_time, true);
        
        if (is_array($decoded)) {
            foreach ($decoded as $time) {
                if (($time['day_name'] ?? '') === $currentDay) {
                    $currentTimeData = $time;
                    break;
                }
            }
        }
        
        // Check if store is currently open
        if ($currentTimeData && ($currentTimeData['status'] ?? '0') === '1') {
            $start = Carbon::parse($currentTimeData['startTime'], $tz)->setDate($now->year, $now->month, $now->day);
            $end   = Carbon::parse($currentTimeData['endTime'], $tz)->setDate($now->year, $now->month, $now->day);
            if ($end->lessThanOrEqualTo($start)) {
                $end->addDay(); // handle overnight
            }
            $isOpen = $now->between($start, $end);
        }
    }
@endphp

<div class="store-sectionde department-page-banner" style="background-image: url('{{ asset('public/uploads/departmentbanner.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="store-infode">
        <h2>Departments</h2>
        <p>Explore the best of the premium store in your locality. We provide you the access of awesome products under one roof from the store with exclusive coupons, deals, and discounts.</p>

        <div class="time-container">
            <div class="time-boxde">
                @if ($currentTimeData && ($currentTimeData['status'] ?? '0') === '1')
                    {{ $currentDay }} {{ $currentTimeData['startTime'] ?? '' }} - {{ $currentTimeData['endTime'] ?? '' }}
                @else
                    {{ $currentDay }} Closed
                @endif
            </div>
                  @if($branches && count($branches) > 0)
                <select name="branch" id="brnch" class="time-boxde" onchange="changeBranch(this.value)">
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ ($selectedVendor && $selectedVendor->id == $branch->id) ? 'selected' : '' }}>
                            {{ $branch->business_name }} ({{ ucfirst($branch->user_type) }})
                        </option>
                    @endforeach
                </select>
        @endif
    </div>
            </div>
      
    <div class="online-statusde {{ $isOpen ? 'status-online' : 'status-offline' }}">
        <span class="{{ $isOpen ? 'open' : 'closed' }}"></span>
        <span class="status-text">{{ $isOpen ? 'Online' : 'Offline' }}</span>
    </div>

    {{-- Coupon Box (same as before) --}}
    @if($selectedVendor && $selectedVendor->coupons && count($selectedVendor->coupons) > 0)
        @php $coupon = $selectedVendor->coupons->first(); @endphp
        <div class="coupon-boxde">
            <div class="coupon-headerde">
                <h4>{{ $coupon->discount_type == 'percentage' ? (int) round($coupon->discount_value) : $coupon->discount_value }}{{ $coupon->discount_type == 'percentage' ? '% OFF' : ' OFF' }}</h4>
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
        <!-- <div class="coupon-boxde">
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
        </div> -->
    @endif
</div>
