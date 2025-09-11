<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeliverySlotRequest;
use App\Models\DeliverySlot;
use Illuminate\Http\Request;

class DeliverySlotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vendorId = auth()->id();
        $slots = DeliverySlot::where('vendor_id', $vendorId)
            ->orderBy('date', 'asc')
            ->orderBy('is_express', 'desc')
            ->paginate(20);

        return view('vendorpanel.delivery_slots.index', compact('slots'));
    }

    public function create()
    {
        return view('vendorpanel.delivery_slots.create');
    }

    public function store(DeliverySlotRequest $request)
    {
        $vendorId = auth()->id();
        $data = $request->validated();
        $data['is_express'] = (int) $data['is_express'];

        // Build common time_range
        if ($request->filled(['start_time', 'end_time'])) {
            $data['time_range'] = $request->start_time . ' - ' . $request->end_time;
        }

        if ($data['is_express'] === 3) {
            // Only one Default Express allowed
            $exists = DeliverySlot::where('vendor_id', $vendorId)
                ->where('is_express', 3)
                ->exists();
            if ($exists) {
                return back()->withErrors(['is_express' => 'Only one Default Express slot allowed.'])->withInput();
            }

            $data['date'] = null;
            $data['time_range'] = null;
            $data['express_minutes'] = null;
            // default_minutes stays as provided
        } elseif ($data['is_express'] === 1) {
            // Express slot: move default_minutes into express_minutes
             $exists = DeliverySlot::where('vendor_id', $vendorId)
                ->where('is_express', 1)
                ->exists();
            if ($exists) {
                return back()->withErrors(['is_express' => 'Only one Express slot allowed.'])->withInput();
            }
            $data['default_minutes'] = $data['default_minutes'];
        } else {
            // Normal slot
            $data['express_charge'] = null;
            $data['express_minutes'] = null;
            $data['default_minutes'] = null;
        }

        $data['vendor_id'] = $vendorId;
        DeliverySlot::create($data);

        return redirect()->route('vendor.delivery-slots.index')
            ->with('success', 'Delivery slot created successfully.');
    }

    public function edit(DeliverySlot $deliverySlot)
    {
        $this->authorizeAction($deliverySlot);
        return view('vendorpanel.delivery_slots.edit', ['slot' => $deliverySlot]);
    }

    public function update(DeliverySlotRequest $request, DeliverySlot $deliverySlot)
    {
        $this->authorizeAction($deliverySlot);
        $data = $request->validated();
        $data['is_express'] = (int) $data['is_express'];

        if ($request->filled(['start_time', 'end_time'])) {
            $data['time_range'] = $request->start_time . ' - ' . $request->end_time;
        }

        if ($data['is_express'] === 3) {
            // Only one Default Express allowed (excluding current)
            $exists = DeliverySlot::where('vendor_id', $deliverySlot->vendor_id)
                ->where('is_express', 3)
                ->where('id', '!=', $deliverySlot->id)
                ->exists();
            if ($exists) {
                return back()->withErrors(['is_express' => 'Only one Default Express slot allowed.'])->withInput();
            }

            $data['date'] = null;
            $data['time_range'] = null;
            $data['express_minutes'] = null;
        } elseif ($data['is_express'] === 1) {
            // Express slot: move default_minutes into express_minutes
            $data['default_minutes'] = $data['default_minutes'];
        } else {
            // Normal slot
            $data['express_charge'] = null;
            $data['express_minutes'] = null;
            $data['default_minutes'] = null;
        }

        $deliverySlot->update($data);

        return redirect()->route('vendor.delivery-slots.index')
            ->with('success', 'Delivery slot updated successfully.');
    }

    public function destroy(DeliverySlot $deliverySlot)
    {
        $this->authorizeAction($deliverySlot);
        $deliverySlot->delete();

        return redirect()->route('vendor.delivery-slots.index')
            ->with('success', 'Delivery slot deleted successfully.');
    }

    public function publicSlots($vendorId)
    {
        $slots = DeliverySlot::where('vendor_id', $vendorId)
            ->where(function ($q) {
                $q->where('date', '>=', now()->toDateString())
                  ->orWhereNull('date');
            })
            ->orderBy('date', 'asc')
            ->orderBy('is_express', 'desc')
            ->get()
            ->map(function ($slot) {
                return [
                    'id'              => $slot->id,
                    'date'            => $slot->date ? $slot->date->format('Y-m-d') : null,
                    'time_range'      => $slot->time_range,
                    'is_express'      => (int) $slot->is_express,
                    'express_charge'  => $slot->express_charge,
                    'express_minutes' => $slot->express_minutes,
                    'default_minutes' => $slot->default_minutes,
                ];
            })->groupBy('date');

        return response()->json($slots);
    }

    protected function authorizeAction(DeliverySlot $slot)
    {
        $vendorId = auth()->id();
        if ($slot->vendor_id != $vendorId) {
            abort(403);
        }
    }
}
