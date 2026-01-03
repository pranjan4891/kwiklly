<?php

namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\VendorOrder;
use App\Models\User;
use App\Models\Payment;
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
            $address = $order->address; // CustomerAddress relationship
            $deliverySlot = $vendorOrder->deliverySlot;

            // Format estimated delivery date and time from DeliverySlot
            $estimatedDeliveryDateTime = null;
            if ($deliverySlot) {
                // Use date and start_time from DeliverySlot model
                $date = $deliverySlot->date ? $deliverySlot->date->format('Y-m-d') : null;
                $startTime = $deliverySlot->start_time ?? null;
                
                if ($date && $startTime) {
                    // Format as datetime string: Y-m-d H:i:s
                    $estimatedDeliveryDateTime = $date . ' ' . $startTime;
                }
            }

            // Build customer address from CustomerAddress model
            $addressParts = [];
            if ($address) {
                if ($address->name) {
                    $addressParts[] = $address->name;
                }
                if ($address->flat) {
                    $addressParts[] = $address->flat;
                }
                if ($address->area) {
                    $addressParts[] = $address->area;
                }
                if ($address->landmark) {
                    $addressParts[] = $address->landmark;
                }
                // Use full_address if available, otherwise build from parts
                $fullAddress = $address->full_address ?? implode(', ', $addressParts);
            } else {
                $fullAddress = 'N/A';
            }

            return (object) [
                'fld_id' => $vendorOrder->id,
                'order_id' => $order->id,
                'vendor_order_id' => $vendorOrder->id,
                'fld_invno' => $order->order_number,
                'estimated_delivery_datetime' => $estimatedDeliveryDateTime,
                'fld_delivery_time' => $deliverySlot ? ($deliverySlot->start_time . '-' . $deliverySlot->end_time) : 'Standard',
                'delivery_type' => $deliverySlot ? 'Scheduled' : 'Standard',
                'fld_invdate' => $order->created_at->format('d/m/Y H:i'),
                'fld_grand_total' => '₹' . number_format($vendorOrder->final_amount, 2),
                'fld_name' => $order->user->name ?? ($address->name ?? 'N/A'),
                'fld_address1' => $fullAddress,
                'fld_city' => $address->area ?? '',
                'fld_pinocde' => $address->pincode ?? '',
                'fld_order_status' => $this->getStatusLabel($vendorOrder->delivery_status),
                'delivery_status' => $vendorOrder->delivery_status,
                'fld_payment_mode' => 'Online', // You can add payment info later
                'total_items' => $vendorOrder->orderItems->count(),
                'customer_phone' => $address->phone ?? ($order->user->phone ?? ''),
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
            'order.address', // CustomerAddress relationship
            'order.payments', // Payment relationship
            'orderItems',
            'orderItems.product',
            'orderItems.variant',
            'deliverySlot' // DeliverySlot relationship
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

        $vendorOrder = VendorOrder::with('order.vendorOrders')
            ->where('id', $orderId)
            ->where('vendor_id', $vendorId)
            ->firstOrFail();

        $vendorOrder->update(['delivery_status' => $request->status]);

        // Update main order status based on all vendor orders
        $order = $vendorOrder->order;
        if ($order) {
            $allVendorOrders = $order->vendorOrders;
            $totalVendors = $allVendorOrders->count();
            
            // Check if all vendor orders are cancelled
            $allCancelled = $allVendorOrders->where('delivery_status', 'cancelled')->count() === $totalVendors;
            
            // Check if all vendor orders are delivered
            $allDelivered = $allVendorOrders->where('delivery_status', 'delivered')->count() === $totalVendors;
            
            if ($allCancelled && $totalVendors > 0) {
                $order->status = 'cancelled';
                $order->save();
            } elseif ($allDelivered && $totalVendors > 0) {
                $order->status = 'delivered';
                $order->save();
            }
        }

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
            'orderItems.product.category',
            'orderItems.product.subcategory',
            'orderItems.variant',
            'deliverySlot'
        ])
        ->where('id', $orderId)
        ->where('vendor_id', $vendorId)
        ->firstOrFail();

        $filename = 'invoice_' . $vendorOrder->order->order_number . '.pdf';

        $pdf = Pdf::loadView('vendorpanel.order.invoice', compact('vendorOrder'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download($filename);
    }
}
