@extends('vendorpanel.include.main')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Delivery Slots</h3>
        <a href="{{ route('vendor.delivery-slots.create') }}" class="btn btn-success">New Slot</a>
    </div>

    @if(session('success')) 
        <div class="alert alert-success">{{ session('success') }}</div> 
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Time Range</th>
                <th>Type</th>
                <th>Express Charge</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($slots as $slot)
                <tr>
                    <td>{{ $slot->id }}</td>
                    <td>{{ $slot->date ? \Carbon\Carbon::parse($slot->date)->format('Y-m-d') : '-' }}</td>
                    <td>{{ $slot->time_range ?? '-' }}</td>
                    <td>
                        @if($slot->is_express === 0)
                            <span class="badge bg-secondary">Normal</span>
                        @elseif($slot->is_express === 1)
                            <span class="badge bg-info text-dark">Express ({{ $slot->default_minutes ?? '' }} min)</span>
                        @elseif($slot->is_express === 3)
                            <span class="badge bg-warning text-dark">
                                Default Express ({{ $slot->default_minutes ?? '' }} min)
                            </span>
                        @endif
                    </td>
                    <td>{{ $slot->express_charge ? '₹'.$slot->express_charge : '-' }}</td>
                    <td>
                        <a href="{{ route('vendor.delivery-slots.edit', $slot->id) }}" class="btn btn-sm btn-primary">Edit</a>

                        <form action="{{ route('vendor.delivery-slots.destroy', $slot->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete slot?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No slots found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $slots->links() }}
</div>
@endsection
