@isset($slot)
    @php $isEdit = true; @endphp
@else
    @php $isEdit = false; @endphp
@endisset

@php
    $startTime = '';
    $endTime = '';
    if (!empty($slot->time_range ?? '') && strpos($slot->time_range, '-') !== false) {
        [$startTime, $endTime] = array_map('trim', explode('-', $slot->time_range));
    }
@endphp

<form action="{{ $isEdit ? route('vendor.delivery-slots.update', $slot->id) : route('vendor.delivery-slots.store') }}" method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- Slot Type --}}
    <div class="mb-3">
        <label for="slot-type">Slot Type</label>
        @php
            $selectedType = (string) old('is_express', isset($slot) ? (string)$slot->is_express : '0');
        @endphp
        <select name="is_express" id="slot-type" class="form-control">
            <option value="0" {{ $selectedType === '0' ? 'selected' : '' }}>Normal</option>
            <option value="1" {{ $selectedType === '1' ? 'selected' : '' }}>Express</option>
            <option value="3" {{ $selectedType === '3' ? 'selected' : '' }}>Default Express</option>
        </select>
        @error('is_express') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    {{-- Date (Normal only) --}}
    <div class="mb-3 type-normal">
        <label>Date</label>
        <input type="text" id="date-picker" name="date" class="form-control"
               value="{{ old('date', isset($slot->date) ? $slot->date->format('Y-m-d') : '') }}">
        @error('date') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    {{-- Start Time (Normal only) --}}
    <div class="mb-3 type-normal">
        <label>Start Time</label>
        <input type="text" id="start-time" name="start_time" class="form-control"
               value="{{ old('start_time', $startTime) }}">
        @error('start_time') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    {{-- End Time (Normal only) --}}
    <div class="mb-3 type-normal">
        <label>End Time</label>
        <input type="text" id="end-time" name="end_time" class="form-control"
               value="{{ old('end_time', $endTime) }}">
        @error('end_time') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    {{-- Express Charge (Normal, Express & Default Express) --}}
    <div class="mb-3 type-charge">
        <label id="charge-label">Express Charge (₹)</label>
        <input type="number" name="express_charge" class="form-control" step="0.01"
               value="{{ old('express_charge', $slot->express_charge ?? '') }}">
        @error('express_charge') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    {{-- Default Minutes (Express & Default Express) --}}
    <div class="mb-3 type-minutes">
        <label id="minutes-label">Express Time (minutes)</label>
        <input type="number" name="default_minutes" class="form-control"
               value="{{ old('default_minutes', $slot->default_minutes ?? '') }}">
        @error('default_minutes') <div class="text-danger">{{ $message }}</div> @enderror
    </div>

    <button class="btn btn-primary">{{ $isEdit ? 'Update' : 'Create' }}</button>
    <a href="{{ route('vendor.delivery-slots.index') }}" class="btn btn-secondary">Cancel</a>
</form>

{{-- Flatpickr --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
(function () {
    function toggleFields() {
        const type = String(document.getElementById('slot-type').value);

        document.querySelectorAll('.type-normal, .type-charge, .type-minutes')
                .forEach(el => el.style.display = 'none');

        if (type === '0') { // Normal
            document.querySelectorAll('.type-normal, .type-charge').forEach(el => el.style.display = '');
        } 
        else if (type === '1') { // Express
            document.querySelectorAll('.type-charge, .type-minutes').forEach(el => el.style.display = '');
            document.getElementById('minutes-label').innerText = 'Express Time (minutes)';
        } 
        else if (type === '3') { // Default Express
            document.querySelectorAll('.type-charge, .type-minutes').forEach(el => el.style.display = '');
            document.getElementById('minutes-label').innerText = 'Default Time (minutes)';
        }
    }

    document.getElementById('slot-type').addEventListener('change', toggleFields);
    toggleFields();

    flatpickr("#date-picker", { dateFormat: "Y-m-d", allowInput: true });
    flatpickr("#start-time", { enableTime: true, noCalendar: true, dateFormat: "h:i K", allowInput: true });
    flatpickr("#end-time", { enableTime: true, noCalendar: true, dateFormat: "h:i K", allowInput: true });
})();
</script>
