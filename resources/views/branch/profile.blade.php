@extends('branch.includes.main')
@section('main')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3>Admin Profile</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>
        <div class="card-body my-3 py-2">
            <form action="{{ route('branch.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Profile Picture --}}
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center mb-3">
                            <img id="previewImage"
                                src="{{ $branch->business_logo ? asset('storage/app/public/' . $branch->business_logo) : asset('storage/app/public/uploads/avatar.webp') }}"
                                class="rounded-circle border"
                                width="150"
                                height="150"
                                alt="Profile Picture">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <h5>Update Business Logo</h5>
                        <input type="file" name="business_logo" class="form-control mt-2" id="imageUpload" accept="image/*" style="margin-top: 40px;">
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" style="margin-top: 10px;margin-bottom:10px; padding:10px;">
                            <label class="col-md-2 control-label">Name</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="name" value="{{ $branch->name }}" required>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 20px;padding:10px;">
                            <label class="col-md-2 control-label">Email</label>
                            <div class="col-md-10">
                                <input type="email" class="form-control" name="email" value="{{ $branch->email }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Business Information --}}
                <h5 class="text-primary">Business Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Business Name</label>
                        <input type="text" class="form-control" name="business_name" value="{{ $branch->business_name }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Business Category</label>
                        <input type="text" class="form-control" name="business_category" value="{{ $branch->business_category }}">
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Business Description</label>
                        <textarea class="form-control" name="business_description">{{ $branch->business_description }}</textarea>
                    </div>
                </div>

                {{-- Address Information --}}
                <h5 class="text-primary">Address Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">State</label>
                        <select name="state" id="state" class="form-control">
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" {{ $branch->state_id == $state->id ? 'selected' : '' }}>
                                    {{ $state->state_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">City</label>
                        <select name="city" id="city" class="form-control">
                            <option value="">Select City</option>
                            @if(!empty($cities))
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ $branch->city_id == $city->id ? 'selected' : '' }}>
                                        {{ $city->city_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-6 mt-3">
                        <label class="form-label">Postal Code</label>
                        <input type="text" class="form-control" name="postal_code" value="{{ $branch->postal_code }}">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Area</label>
                        <input type="text" class="form-control" name="area" value="{{ $branch->area }}">
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="form-label">Business Address</label>
                        <textarea class="form-control" name="business_address">{{ $branch->business_address }}</textarea>
                    </div>
                </div>

                {{-- Banking Information --}}
                <h5 class="text-primary">Banking Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Bank Name</label>
                        <input type="text" class="form-control" name="bank_name" value="{{ $branch->bank_name }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bank Branch</label>
                        <input type="text" class="form-control" name="bank_branch" value="{{ $branch->bank_branch }}">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Account Holder Name</label>
                        <input type="text" class="form-control" name="account_holder_name" value="{{ $branch->account_holder_name }}">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Account Number</label>
                        <input type="text" class="form-control" name="account_number" value="{{ $branch->account_number }}">
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">IFSC Code</label>
                        <input type="text" class="form-control" name="ifsc_code" value="{{ $branch->ifsc_code }}">
                    </div>
                </div>

                {{-- Store Time Management --}}
                <h5 class="text-primary">Store Time Management</h5>
                @php
                    $days = [];
                    for ($i = 0; $i < 7; $i++) {
                        $days[$i] = jddayofweek($i, 1); // Monday, Tuesday, etc.
                    }
                @endphp

                @foreach ($days as $index => $day)
                    @php
                        // Find saved data for this day by matching 'day_name'
                        $dayData = collect($storetime)->firstWhere('day_name', $day) ?? null;
                    @endphp

                    <div class="form-group border p-3 mb-2">
                        <label><strong>{{ $day }}</strong></label>

                        {{-- Hidden day name --}}
                        <input type="hidden" name="day_name_{{ $index + 1 }}" value="{{ $day }}">

                        {{-- Hidden day_id (if exists in DB) --}}
                        @if (!empty($dayData['day_id']))
                            <input type="hidden" name="day_id_{{ $index + 1 }}" value="{{ $dayData['day_id'] }}">
                        @endif

                        {{-- Open/Close Radio --}}
                        <div class="radio mb-2">
                            <label class="me-3">
                                <input type="radio" name="day_oc_{{ $index + 1 }}" value="1"
                                    {{ old("day_oc_" . ($index + 1), $dayData['status'] ?? '') == 1 ? 'checked' : '' }} required>
                                Open
                            </label>
                            <label>
                                <input type="radio" name="day_oc_{{ $index + 1 }}" value="0"
                                    {{ old("day_oc_" . ($index + 1), $dayData['status'] ?? '') == 0 ? 'checked' : '' }} required>
                                Close
                            </label>
                        </div>

                        {{-- Open Time --}}
                        <label for="open_time_{{ $index + 1 }}">Open Time</label>
                        <select name="open_time_{{ $index + 1 }}" class="form-control mb-2">
                            <option value="">Select Open Time Slot</option>
                            @foreach ($timeSlots as $slot)
                                <option value="{{ $slot->slot_time }}"
                                    {{ old("open_time_" . ($index + 1), $dayData['startTime'] ?? '') == $slot->slot_time ? 'selected' : '' }}>
                                    {{ $slot->slot_time }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Closed Time --}}
                        <label for="closed_time_{{ $index + 1 }}">Closed Time</label>
                        <select name="closed_time_{{ $index + 1 }}" class="form-control">
                            <option value="">Select Store Closed Time</option>
                            @foreach ($timeSlots as $slot)
                                <option value="{{ $slot->slot_time }}"
                                    {{ old("closed_time_" . ($index + 1), $dayData['endTime'] ?? '') == $slot->slot_time ? 'selected' : '' }}>
                                    {{ $slot->slot_time }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endforeach


                {{-- Save Button --}}
                <div class="text-center" style="margin: 10px 0; padding: 10px 0;">
                    <button type="submit" class="btn btn-success px-4">Update Profile</button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- Image Preview Script --}}
<script>
document.getElementById('imageUpload').addEventListener('change', function(event) {
    let reader = new FileReader();
    reader.onload = function() {
        document.getElementById('previewImage').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
});
</script>

@endsection
@push('scripts')
<script>
document.getElementById('state').addEventListener('change', function() {
    const stateId = this.value;
    const citySelect = document.getElementById("city");

    // Reset city dropdown
    citySelect.innerHTML = `<option value="">Loading...</option>`;

    if (!stateId) {
        citySelect.innerHTML = `<option value="">Select City</option>`;
        return;
    }

    // Build route URL
    const url = "{{ route('admin.get.cities', ':id') }}".replace(':id', stateId);

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            citySelect.innerHTML = `<option value="">Select City</option>`;
            if (data.length > 0) {
                data.forEach(city => {
                    citySelect.innerHTML += `<option value="${city.id}">${city.city_name}</option>`;
                });
            }
        })
        .catch(error => {
            console.error("Error fetching cities:", error);
            citySelect.innerHTML = `<option value="">Error loading cities</option>`;
        });
});
</script>

@endpush
