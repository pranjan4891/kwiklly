<?php

namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\VendorOrder;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;


class OrderController extends Controller
{
    public function orderlist(Request $request)
    {
        $vendorId = auth('vendor')->id();

        // Get vendor orders for this vendor
        $vendorOrders = VendorOrder::with([
            'order',
            'order.user',
            'order.address',
            'orderItems',
            'orderItems.product',
            'orderItems.variant',
            'deliverySlot'
        ])
        ->where('vendor_id', $vendorId)
        ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($request->filled('status')) {
            $status = $request->status;
            // Map frontend status to backend delivery_status
            $statusMapping = [
                'Pending' => 'pending',
                'Packed' => 'packed',
                'Shipped' => 'shipped',
                'Delivered' => 'delivered',
            ];

            if (isset($statusMapping[$status])) {
                $vendorOrders->where('delivery_status', $statusMapping[$status]);
            }
        }

        // Filter by date range if provided
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $dateFrom = date('Y-m-d 00:00:00', strtotime($request->date_from));
            $dateTo = date('Y-m-d 23:59:59', strtotime($request->date_to));
            $vendorOrders->whereBetween('created_at', [$dateFrom, $dateTo]);
        } elseif ($request->filled('date_from')) {
            $dateFrom = date('Y-m-d 00:00:00', strtotime($request->date_from));
            $vendorOrders->where('created_at', '>=', $dateFrom);
        } elseif ($request->filled('date_to')) {
            $dateTo = date('Y-m-d 23:59:59', strtotime($request->date_to));
            $vendorOrders->where('created_at', '<=', $dateTo);
        }

        $vendorOrders = $vendorOrders->paginate(20);

        // Transform data for the view (maintaining compatibility with old structure)
        $orderDetails = $vendorOrders->map(function ($vendorOrder) {
            $order = $vendorOrder->order;
            $address = $order->address;

            // Format delivery date time
            $deliveryInfo = 'Normal-Delivery'; // Default
            if ($vendorOrder->deliverySlot) {
                $slotDateTime = $vendorOrder->deliverySlot->slot_date . ' ' . $vendorOrder->deliverySlot->start_time . '-' . $vendorOrder->deliverySlot->end_time;
                if (strpos($vendorOrder->deliverySlot->slot_name, 'Express') !== false) {
                    $deliveryInfo = 'Express-Delivery';
                }
            } else {
                $slotDateTime = date('d/m/Y H:i', strtotime($vendorOrder->created_at)) . '-' . date('H:i', strtotime($vendorOrder->created_at . ' +30 minutes'));
            }

            return (object) [
                'fld_id' => $vendorOrder->id,
                'order_id' => $order->id,
                'vendor_order_id' => $vendorOrder->id,
                'fld_invno' => $order->order_number,
                'estimated_delivery_datetime' => $vendorOrder->deliverySlot ? $vendorOrder->deliverySlot->slot_date . ' ' . $vendorOrder->deliverySlot->start_time : null,
                'fld_delivery_time' => $vendorOrder->deliverySlot ? $vendorOrder->deliverySlot->start_time . '-' . $vendorOrder->deliverySlot->end_time : 'Standard',
                'delivery_type' => $deliveryInfo,
                'fld_invdate' => $order->created_at->format('d/m/Y H:i'),
                'fld_grand_total' => '₹' . number_format($vendorOrder->final_amount, 2),
                'fld_name' => $order->user->name ?? 'N/A',
                'fld_address1' => $address->address_line_1 ?? '',
                'fld_city' => $address->city ?? '',
                'fld_pinocde' => $address->pincode ?? '',
                'fld_order_status' => $this->getStatusLabel($vendorOrder->delivery_status),
                'delivery_status' => $vendorOrder->delivery_status,
                'fld_payment_mode' => 'Online', // You can add payment info later
                'total_items' => $vendorOrder->orderItems->count(),
                'customer_phone' => $order->user->phone ?? '',
                'customer_email' => $order->user->email ?? '',
            ];
        });

        if ($request->ajax()) {
            return response()->json([
                'data' => $orderDetails,
                'current_page' => $vendorOrders->currentPage(),
                'last_page' => $vendorOrders->lastPage(),
                'total' => $vendorOrders->total(),
            ]);
        }

        return view('vendorpanel.order.list', compact('orderDetails', 'vendorOrders'));
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Pending',
            'packed' => 'Packed',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ];

        return $labels[$status] ?? ucfirst($status);
    }

    public function orderDetails($orderId)
    {
        $vendorId = auth('vendor')->id();

        $vendorOrder = VendorOrder::with([
            'order',
            'order.user',
            'order.address',
            'orderItems',
            'orderItems.product',
            'orderItems.variant'
        ])
        ->where('id', $orderId)
        ->where('vendor_id', $vendorId)
        ->firstOrFail();

        return view('vendorpanel.order.details', compact('vendorOrder'));
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:pending,packed,shipped,delivered,cancelled'
        ]);

        $vendorId = auth('vendor')->id();

        $vendorOrder = VendorOrder::where('id', $orderId)
            ->where('vendor_id', $vendorId)
            ->firstOrFail();

        $vendorOrder->update(['delivery_status' => $request->status]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Order status updated successfully']);
        }

        return redirect()->back()->with('success', 'Order status updated successfully');
    }

    public function downloadInvoice($orderId)
    {
        $vendorId = auth('vendor')->id();

        $vendorOrder = VendorOrder::with([
            'order',
            'order.user',
            'order.address',
            'orderItems',
            'orderItems.product',
            'orderItems.variant'
        ])
        ->where('id', $orderId)
        ->where('vendor_id', $vendorId)
        ->firstOrFail();

        $filename = 'invoice_' . $vendorOrder->order->order_number . '.pdf';

        $pdf = Pdf::loadView('vendorpanel.order.invoice', compact('vendorOrder'));
        return $pdf->download($filename);
    }
}
