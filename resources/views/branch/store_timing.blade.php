@extends('branch.includes.main')
@section('main')
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h3>Store Timing</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('branch.update.store.time') }}" method="POST">
                @csrf
                @php
                    $days = [];
                    for ($i = 0; $i < 7; $i++) {
                        $days[$i] = jddayofweek($i, 1);
                    }
                @endphp

                @foreach ($days as $index => $day)
                    @php
                        $dayData = $storetime[$day] ?? null;
                        $dayId = $dayData['day_id'] ?? $dayData['id'] ?? null;
                    @endphp

                    <div class="form-group border p-3 mb-3">
                        <label class="d-block"><strong>{{ $day }}</strong></label>

                        <input type="hidden" name="day_name_{{ $index + 1 }}" value="{{ $day }}">

                        @if (!empty($dayId))
                            <input type="hidden" name="day_id_{{ $index + 1 }}" value="{{ $dayId }}">
                        @endif

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

                        <label for="open_time_{{ $index + 1 }}">Open time</label>
                        <select name="open_time_{{ $index + 1 }}" id="open_time_{{ $index + 1 }}" class="form-control mb-2">
                            <option value="">Select Open Time Slot</option>
                            @foreach ($timeSlots as $slot)
                                <option value="{{ $slot->slot_time }}"
                                    {{ old("open_time_" . ($index + 1), $dayData['startTime'] ?? '') == $slot->slot_time ? 'selected' : '' }}>
                                    {{ $slot->slot_time }}
                                </option>
                            @endforeach
                        </select>

                        <label for="closed_time_{{ $index + 1 }}">Closed time</label>
                        <select name="closed_time_{{ $index + 1 }}" id="closed_time_{{ $index + 1 }}" class="form-control">
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

                <div class="form-group text-center">
                    <button type="submit" class="btn btn-warning px-4">Update Time Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
