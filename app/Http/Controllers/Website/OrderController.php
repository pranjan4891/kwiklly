<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\VendorOrder;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\DeliverySlot;
use App\Models\CouponUsage;
use App\Models\Product;
use App\Models\VendorAdmin;
use App\Models\WalletTransaction;
use App\Models\CustomerAddress;
use App\Models\Payment;
use Carbon\Carbon;


class OrderController extends Controller
{
    public function storeOrder(Request $request)
    {
        Log::info('OrderController: storeOrder method started', ['user_id' => Auth::id(), 'request_data' => $request->all()]);

        // Validate the request
        $request->validate([
            'vendors' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            // Get current user and cart items with product and vendor relationships
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $cartItems = CartItem::with(['product.vendor', 'variant'])
                ->where('user_id', $user->id)
                ->get();

            Log::info('Cart items retrieved', ['count' => $cartItems->count()]);

            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty.'
                ], 400);
            }

            // Debug: Check cart item prices
            foreach ($cartItems as $cartItem) {
                Log::info('Cart item details', [
                    'id' => $cartItem->id,
                    'product_id' => $cartItem->product_id,
                    'variant_id' => $cartItem->variant_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'product_price' => $cartItem->product->price ?? 'N/A',
                    'variant_price' => $cartItem->variant->price ?? 'N/A'
                ]);
            }

            // Group cart items by vendor ID from the product relationship
            $groupedCart = $cartItems->groupBy(function($item) {
                return $item->product->vendor_id ?? 0;
            });

            Log::info('Cart items grouped by vendor', [
                'vendor_count' => $groupedCart->count(),
                'vendor_ids' => $groupedCart->keys()->toArray()
            ]);

            // Generate unique order number
            $orderNumber = 'ORD' . date('YmdHis') . rand(100, 999);

            // Calculate totals - USE THE PRICE FROM CART ITEM, not from product/variant
            $totals = $this->calculateOrderTotals($groupedCart, $user);

            Log::info('Order totals calculated', $totals);

            // Check wallet balance if used
            $walletUsed = $totals['wallet_used'] ?? 0;
            if ($walletUsed > 0) {
                $walletBalance = WalletTransaction::getBalance($user->id);
                if ($walletBalance < $walletUsed) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient wallet balance.'
                    ], 400);
                }
            }

            // Create main order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'total_price' => $totals['subtotal'],
                'coupon_id' => $totals['coupon_id'],
                'coupon_discount' => $totals['coupon_discount'],
                'wallet_used' => $walletUsed,
                'final_amount' => $totals['final_amount'],
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Main order created', ['order_id' => $order->id]);

            // Process wallet transaction if used
            if ($walletUsed > 0) {
                $this->processWalletTransaction($user->id, $walletUsed, $order->id);
                Log::info('Wallet transaction processed', ['amount' => $walletUsed]);
            }

            // Track used coupons to update usage counts
            $usedCoupons = [];

            // Process each vendor's items
            foreach ($groupedCart as $vendorId => $items) {
                Log::info('Processing vendor order', ['vendor_id' => $vendorId, 'item_count' => $items->count()]);

                // Check if vendor exists
                $vendor = VendorAdmin::find($vendorId);
                if (!$vendor) {
                    Log::warning('Vendor not found', ['vendor_id' => $vendorId]);
                    continue;
                }

                $vendorTotals = $this->calculateVendorTotals($items, $vendorId, $user);
                Log::info('Vendor totals calculated', ['vendor_id' => $vendorId, 'totals' => $vendorTotals]);

                // Handle delivery slot
                $deliverySlotId = null;
                if (isset($request->vendors[$vendorId])) {
                    $vendorData = $request->vendors[$vendorId];

                    if ($vendorData['delivery_type'] === 'custom') {
                        // Create a new delivery slot for custom delivery
                        $deliverySlot = DeliverySlot::create([
                            'user_id' => $user->id,
                            'vendor_id' => $vendorId,
                            'date' => $vendorData['custom_delivery']['date'],
                            'start_time' => $vendorData['custom_delivery']['start_time'],
                            'end_time' => $vendorData['custom_delivery']['end_time'],
                            'is_available' => 1, // Mark as booked
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $deliverySlotId = $deliverySlot->id;
                        Log::info('Custom delivery slot created', ['slot_id' => $deliverySlotId]);

                    } elseif ($vendorData['delivery_type'] === 'express') {
                        // For express delivery, find or create a slot for the next 20 minutes
                        $expressTime = now()->addMinutes(20);
                        $expressEndTime = now()->addMinutes(40);

                        $deliverySlot = DeliverySlot::firstOrCreate([
                            'user_id' => $user->id,
                            'vendor_id' => $vendorId,
                            'date' => $expressTime->format('Y-m-d'),
                            'start_time' => $expressTime->format('H:i:s'),
                            'end_time' => $expressEndTime->format('H:i:s'),
                        ], [
                            'is_available' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $deliverySlotId = $deliverySlot->id;
                        Log::info('Express delivery slot created/used', ['slot_id' => $deliverySlotId]);

                    } else {
                        // Standard delivery - find or create a slot for the next 30 minutes
                        $standardTime = now()->addMinutes(30);
                        $standardEndTime = now()->addMinutes(60);

                        $deliverySlot = DeliverySlot::firstOrCreate([
                            'user_id' => $user->id,
                            'vendor_id' => $vendorId,
                            'date' => $standardTime->format('Y-m-d'),
                            'start_time' => $standardTime->format('H:i:s'),
                            'end_time' => $standardEndTime->format('H:i:s'),
                        ], [
                            'is_available' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $deliverySlotId = $deliverySlot->id;
                        Log::info('Standard delivery slot created/used', ['slot_id' => $deliverySlotId]);
                    }
                }

                // Create vendor order with delivery_slot_id
                $vendorOrder = VendorOrder::create([
                    'order_id' => $order->id,
                    'vendor_id' => $vendorId,
                    'coupon_id' => $vendorTotals['coupon_id'],
                    'coupon_discount' => $vendorTotals['coupon_discount'],
                    'delivery_slot_id' => $deliverySlotId,
                    'sub_total' => $vendorTotals['subtotal'],
                    'delivery_fee' => $vendorTotals['delivery_fee'],
                    'final_amount' => $vendorTotals['final_amount'],
                    'delivery_status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::info('Vendor order created', [
                    'vendor_order_id' => $vendorOrder->id,
                    'vendor_id' => $vendorId,
                    'delivery_slot_id' => $deliverySlotId
                ]);

                // Track vendor coupon usage
                if ($vendorTotals['coupon_id']) {
                    $usedCoupons[] = [
                        'coupon_id' => $vendorTotals['coupon_id'],
                        'vendor_id' => $vendorId
                    ];

                    Log::info('Vendor coupon tracked', [
                        'coupon_id' => $vendorTotals['coupon_id'],
                        'vendor_id' => $vendorId
                    ]);
                }

                // Create order items - USE THE PRICE FROM CART ITEM
                foreach ($items as $cartItem) {
                    $price = $cartItem->price; // Use the price stored in cart item

                    $orderItemData = [
                        'vendor_order_id' => $vendorOrder->id,
                        'product_id' => $cartItem->product_id,
                        'variant_id' => $cartItem->variant_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $price,
                        'total_price' => $cartItem->quantity * $price,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $orderItem = OrderItem::create($orderItemData);

                    Log::info('Order item created', [
                        'order_item_id' => $orderItem->id,
                        'product_id' => $cartItem->product_id,
                        'variant_id' => $cartItem->variant_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $price,
                        'total_price' => $cartItem->quantity * $price
                    ]);
                }
            }

            // Track global coupon usage
            if ($totals['coupon_id']) {
                $usedCoupons[] = [
                    'coupon_id' => $totals['coupon_id'],
                    'vendor_id' => null // Global coupon
                ];

                Log::info('Global coupon tracked', ['coupon_id' => $totals['coupon_id']]);
            }

            // Update coupon usage counts
            if (!empty($usedCoupons)) {
                $this->updateCouponUsages($usedCoupons, $user->id);
                Log::info('Coupon usages updated', ['count' => count($usedCoupons)]);
            }

            // Clear the cart
            // $deletedCartItems = CartItem::where('user_id', $user->id)->delete();
            // Log::info('Cart cleared', ['deleted_items' => $deletedCartItems]);

            // Clear session coupons and wallet settings
            session()->forget('global_coupon');
            session()->forget('use_wallet');
            session()->forget('wallet_amount');
            foreach ($groupedCart as $vendorId => $items) {
                session()->forget("vendor_{$vendorId}_coupon");
            }

            Log::info('Session coupons cleared');

            DB::commit();

            Log::info('Order processed successfully', ['order_id' => $order->id]);

            return response()->json([
                'success' => true,
                'message' => 'Order processed successfully',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Order processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
    }

     /**
     * Process wallet transaction for order payment
     */
    private function processWalletTransaction($userId, $amount, $orderId)
    {
        $currentBalance = WalletTransaction::getBalance($userId);
        $newBalance = $currentBalance - $amount;

        // Create debit transaction
        WalletTransaction::createTransaction(
            $userId,
            WalletTransaction::TYPE_DEBIT,
            $amount,
            WalletTransaction::REASON_ORDER_PAYMENT,
            $newBalance
        );

        // Update user's wallet balance
        $user = \App\Models\User::find($userId);
        if ($user) {
            $user->wallet_balance = $newBalance;
            $user->save();
        }

        Log::info('Wallet transaction processed', [
            'user_id' => $userId,
            'amount' => $amount,
            'order_id' => $orderId,
            'previous_balance' => $currentBalance,
            'new_balance' => $newBalance
        ]);
    }


    /**
     * Update coupon usage counts for all used coupons
     */
    private function updateCouponUsages(array $usedCoupons, $userId)
    {
        foreach ($usedCoupons as $couponUsage) {
            $couponId = $couponUsage['coupon_id'];

            // Check if coupon usage already exists for this user
            $usage = CouponUsage::where('coupon_id', $couponId)
                ->where('user_id', $userId)
                ->first();

            if ($usage) {
                // Increment existing usage count
                $usage->update([
                    'usage_count' => $usage->usage_count + 1,
                    'updated_at' => now()
                ]);

                Log::info('Coupon usage incremented', [
                    'coupon_id' => $couponId,
                    'user_id' => $userId,
                    'new_count' => $usage->usage_count
                ]);
            } else {
                // Create new usage record
                $newUsage = CouponUsage::create([
                    'coupon_id' => $couponId,
                    'user_id' => $userId,
                    'usage_count' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                Log::info('New coupon usage created', [
                    'coupon_usage_id' => $newUsage->id,
                    'coupon_id' => $couponId,
                    'user_id' => $userId
                ]);
            }
        }
    }

    private function calculateOrderTotals($groupedCart, $user)
    {
        $subtotal = 0;
        $couponDiscount = 0;
        $walletUsed = 0;
        $deliveryFee = 0;

        // Calculate subtotal from all items - USE THE PRICE FROM CART ITEM
        foreach ($groupedCart as $vendorItems) {
            foreach ($vendorItems as $item) {
                $price = $item->price; // Use the price stored in cart item
                $subtotal += $item->quantity * $price;

                Log::info('Price calculation', [
                    'item_id' => $item->id,
                    'cart_price' => $item->price,
                    'quantity' => $item->quantity,
                    'item_total' => $item->quantity * $price
                ]);
            }
        }

        Log::info('Total subtotal', ['subtotal' => $subtotal]);

        // Apply global coupon if any
        $globalCoupon = session()->get('global_coupon');
        if ($globalCoupon && $this->isCouponValid($globalCoupon, $user, $groupedCart)) {
            $couponDiscount = $this->calculateCouponDiscount($globalCoupon, $subtotal, $groupedCart);
            Log::info('Global coupon applied', ['discount' => $couponDiscount]);
        }

        // Apply wallet balance if used
        $useWallet = session()->get('use_wallet', false);
        $walletAmount = session()->get('wallet_amount', 0);
        $walletBalance = WalletTransaction::getBalance($user->id);

        if ($useWallet && $walletBalance > 0) {
            $walletUsed = min($walletBalance, $walletAmount, $subtotal - $couponDiscount);
            Log::info('Wallet used', ['amount' => $walletUsed]);
        }

        // Calculate delivery fees
        $deliveryFee = $this->calculateDeliveryFee($groupedCart);

        $finalAmount = $subtotal + $deliveryFee - $couponDiscount - $walletUsed;

        Log::info('Final amount calculation', [
            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'coupon_discount' => $couponDiscount,
            'wallet_used' => $walletUsed,
            'final_amount' => $finalAmount
        ]);

        return [
            'subtotal' => $subtotal,
            'coupon_id' => $globalCoupon->id ?? null,
            'coupon_discount' => $couponDiscount,
            'wallet_used' => $walletUsed,
            'delivery_fee' => $deliveryFee,
            'final_amount' => max(0, $finalAmount),
        ];
    }

    private function calculateVendorTotals($items, $vendorId, $user)
    {
        $subtotal = 0;

        foreach ($items as $item) {
            $price = $item->price; // Use the price stored in cart item
            $subtotal += $item->quantity * $price;
        }

        Log::info('Vendor subtotal', ['vendor_id' => $vendorId, 'subtotal' => $subtotal]);

        // Apply vendor-specific coupon if any
        $vendorCoupon = session()->get("vendor_{$vendorId}_coupon");
        $couponDiscount = 0;
        $couponId = null;

        if ($vendorCoupon && $this->isCouponValid($vendorCoupon, $user, [$vendorId => $items])) {
            $couponDiscount = $this->calculateCouponDiscount($vendorCoupon, $subtotal, [$vendorId => $items]);
            $couponId = $vendorCoupon->id;
            Log::info('Vendor coupon applied', ['vendor_id' => $vendorId, 'discount' => $couponDiscount]);
        }

        // Calculate vendor delivery fee
        $deliveryFee = $this->calculateVendorDeliveryFee($vendorId, $items);

        $finalAmount = $subtotal + $deliveryFee - $couponDiscount;

        return [
            'subtotal' => $subtotal,
            'coupon_id' => $couponId,
            'coupon_discount' => $couponDiscount,
            'delivery_fee' => $deliveryFee,
            'final_amount' => max(0, $finalAmount),
        ];
    }

    /**
     * Check if a coupon is valid for use based on your coupon model
     */
    private function isCouponValid($coupon, $user, $cartItems)
    {
        // Use the coupon's built-in validation
        if (!$coupon->isValidForUser($user)) {
            Log::info('Coupon invalid for user', ['coupon_id' => $coupon->id]);
            return false;
        }

        // Check minimum order amount
        $subtotal = $this->calculateCartSubtotal($cartItems);
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            Log::info('Coupon minimum order not met', [
                'coupon_id' => $coupon->id,
                'min_order' => $coupon->min_order_amount,
                'subtotal' => $subtotal
            ]);
            return false;
        }

        // Check if coupon applies to specific products/categories
        if ($coupon->applies_to !== 'all') {
            if (!$this->isCouponApplicableToCart($coupon, $cartItems)) {
                Log::info('Coupon not applicable to cart items', ['coupon_id' => $coupon->id]);
                return false;
            }
        }

        // Check vendor restriction based on created_by_type and created_by_id
        if ($coupon->created_by_type === 'vendor') {
            $vendorIds = array_keys($cartItems);
            if (count($vendorIds) > 1 || $vendorIds[0] != $coupon->created_by_id) {
                Log::info('Coupon vendor restriction failed', [
                    'coupon_id' => $coupon->id,
                    'coupon_vendor' => $coupon->created_by_id,
                    'cart_vendors' => $vendorIds
                ]);
                return false;
            }
        }

        Log::info('Coupon is valid', ['coupon_id' => $coupon->id]);
        return true;
    }

    /**
     * Check if coupon applies to items in cart based on applies_to field
     */
    private function isCouponApplicableToCart($coupon, $cartItems)
    {
        $applicableItems = [];

        foreach ($cartItems as $vendorItems) {
            foreach ($vendorItems as $item) {
                switch ($coupon->applies_to) {
                    case 'product':
                        if ($coupon->products->contains('id', $item->product_id)) {
                            $applicableItems[] = $item;
                        }
                        break;

                    case 'category':
                        if ($item->product && $item->product->category_id &&
                            $coupon->categories->contains('id', $item->product->category_id)) {
                            $applicableItems[] = $item;
                        }
                        break;

                    case 'subcategory':
                        if ($item->product && $item->product->sub_category_id &&
                            $coupon->subcategories->contains('id', $item->product->sub_category_id)) {
                            $applicableItems[] = $item;
                        }
                        break;

                    default:
                        // For other cases, assume all items are applicable
                        $applicableItems[] = $item;
                        break;
                }
            }
        }

        $isApplicable = count($applicableItems) > 0;
        Log::info('Coupon applicability check', [
            'coupon_id' => $coupon->id,
            'applies_to' => $coupon->applies_to,
            'applicable_items' => count($applicableItems),
            'is_applicable' => $isApplicable
        ]);

        return $isApplicable;
    }

    /**
     * Calculate coupon discount based on applicable items
     */
    private function calculateCouponDiscount($coupon, $subtotal, $cartItems)
    {
        // If coupon applies to all items, use the full subtotal
        if ($coupon->applies_to === 'all') {
            $applicableAmount = $subtotal;
        } else {
            // Calculate subtotal only for applicable items
            $applicableAmount = 0;
            foreach ($cartItems as $vendorItems) {
                foreach ($vendorItems as $item) {
                    if ($this->isItemApplicableForCoupon($coupon, $item)) {
                        $price = $item->price; // Use the price stored in cart item
                        $applicableAmount += $item->quantity * $price;
                    }
                }
            }
        }

        Log::info('Coupon discount calculation', [
            'coupon_id' => $coupon->id,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'applicable_amount' => $applicableAmount
        ]);

        if ($coupon->discount_type === 'percentage') {
            $discount = ($coupon->discount_value / 100) * $applicableAmount;
            return $discount;
        } else {
            // Fixed discount - cannot exceed applicable amount
            return min($coupon->discount_value, $applicableAmount);
        }
    }

    /**
     * Check if a specific cart item is applicable for the coupon
     */
    private function isItemApplicableForCoupon($coupon, $item)
    {
        switch ($coupon->applies_to) {
            case 'product':
                return $coupon->products->contains('id', $item->product_id);

            case 'category':
                return $item->product && $item->product->category_id &&
                       $coupon->categories->contains('id', $item->product->category_id);

            case 'subcategory':
                return $item->product && $item->product->sub_category_id &&
                       $coupon->subcategories->contains('id', $item->product->sub_category_id);

            default:
                return true;
        }
    }

    /**
     * Calculate cart subtotal
     */
    private function calculateCartSubtotal($cartItems)
    {
        $subtotal = 0;
        foreach ($cartItems as $vendorItems) {
            foreach ($vendorItems as $item) {
                $price = $item->price; // Use the price stored in cart item
                $subtotal += $item->quantity * $price;
            }
        }
        return $subtotal;
    }

    private function calculateDeliveryFee($cartItems)
    {
        // Implement your delivery fee calculation logic
        return 0; // Free delivery as shown in your screenshot
    }

    private function calculateVendorDeliveryFee($vendorId, $items)
    {
        // Implement vendor-specific delivery fee calculation
        return 0; // Free delivery as shown in your screenshot
    }

    public function deliveryAddress()
    {
        // Check if user has a pending order
        $order = Order::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$order) {
            return redirect()->route('cart.view')->with('error', 'Please create an order first.');
        }

        return view('web.checkoutaddress', compact('order'));
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'address_id' => 'required|exists:customer_addresses,id'
        ]);

        // Get the order
        $order = Order::where('id', $request->order_id)
                    ->where('user_id', Auth::id())
                    ->where('status', 'pending')
                    ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found or already processed');
        }

        // Update the address
        $order->cust_address_id = $request->address_id;
        $order->save();

        // Redirect to payment page
        return redirect()->route('payment.details', $order->id);
    }

    public function paymentDetails($orderId)
    {
        // Get the order with address
        $order = Order::with('address')
                    ->where('id', $orderId)
                    ->where('user_id', Auth::id())
                    ->where('status', 'pending')
                    ->first();

        if (!$order) {
            return redirect()->route('cart.view')->with('error', 'Order not found or already processed');
        }

        return view('web.paymentdetails', compact('order'));
    }

    public function processCOD(Request $request)
    {
        $order = Order::where('id', $request->order_id)
                    ->where('user_id', Auth::id())
                    ->where('status', 'pending')
                    ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or already processed'
            ]);
        }

        // Update order status
        $order->status = 'confirmed';
        $order->save();

        // Create payment record
        Payment::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'amount' => $order->final_amount,
            'currency' => 'INR'
        ]);

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'message' => 'Order confirmed successfully'
        ]);
    }

}
