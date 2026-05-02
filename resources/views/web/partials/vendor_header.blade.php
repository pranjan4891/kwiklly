@php
    use Carbon\Carbon;

    $currentDay = $currentDay ?? now()->format('l');
    $tz = config('app.timezone');
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

        if ($currentTimeData && ($currentTimeData['status'] ?? '0') === '1') {
            $start = Carbon::parse($currentTimeData['startTime'], $tz)->setDate($now->year, $now->month, $now->day);
            $end = Carbon::parse($currentTimeData['endTime'], $tz)->setDate($now->year, $now->month, $now->day);
            if ($end->lessThanOrEqualTo($start)) {
                $end->addDay();
            }
            $isOpen = $now->between($start, $end);
        }
    }

    $cookValue1 = $selectedVendor->minimum_order_value_for_cook ?? null;
    $cookValue2 = $selectedVendor->minimum_order_for_cook ?? null;
    $deliveryValue = $selectedVendor->minimum_order_value ?? null;
    $minimumForCook = null;

    if ($cookValue1 !== null && $cookValue1 !== '') {
        $minimumForCook = $cookValue1;
    } elseif ($cookValue2 !== null && $cookValue2 !== '') {
        $minimumForCook = $cookValue2;
    }

    $minimumForCook = ($minimumForCook !== null && is_numeric($minimumForCook)) ? (float) $minimumForCook : 0;
    $minimumForDelivery = ($deliveryValue !== null && is_numeric($deliveryValue)) ? (float) $deliveryValue : 0;
    $showProgressSection = ($minimumForCook > 0) || ($minimumForDelivery > 0);
    $dyText = $selectedVendor->dy_text ?? 'Free Dietitian Visit';
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

    <div class="vendor-header-actions">
        <div class="department-status-coupon-row">
            <div class="online-statusde {{ $isOpen ? 'status-online' : 'status-offline' }}">
                <span class="{{ $isOpen ? 'open' : 'closed' }}"></span>
                <span class="status-text">{{ $isOpen ? 'Online' : 'Offline' }}</span>
            </div>

            @if($selectedVendor)
                <button type="button" class="btn btn-light text-danger border12" onclick="loadSideCartCoupons({{ $selectedVendor->id }})">
                    <b>Coupons</b>
                </button>
            @endif
        </div>

        @if($selectedVendor && $showProgressSection)
            <div class="department-progress-box" data-vendor-id="{{ $selectedVendor->id }}">
                @if($minimumForCook > 0)
                    <div class="d-flex align-items-center mb-3" id="department-cook-section">
                        <img src="{{ asset('public/assets/website/images/demo.png') }}" alt="Icon">
                        <div class="ms-3 w-100">
                            <div class="coupontext cook-text">Add item worth &#8377;<b id="department-cook-amount-needed">{{ round($minimumForCook) }}</b> to get {{ $dyText }}</div>
                            <div class="xyz-progress mt-1">
                                <div class="xyz-progress-bar cook-progress" id="department-cook-progress" style="width: 0%; min-width: 0%;"></div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($minimumForDelivery > 0)
                    <div class="xyz-clickable-div" id="department-delivery-section">
                        <div class="coupontext delivery-text">Add item worth &#8377;<b id="department-delivery-amount-needed">{{ round($minimumForDelivery) }}</b> more to get free delivery</div>
                        <div class="xyz-progress mt-1">
                            <div class="xyz-progress-bar delivery-progress" id="department-delivery-progress" style="width: 0%; min-width: 0%;"></div>
                        </div>
                    </div>
                @endif

                <div class="xyz-right-text">*Progress Bar will reset in next order</div>
            </div>
        @endif
    </div>
</div>
