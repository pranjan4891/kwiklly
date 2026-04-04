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
use App\Models\PendingCheckout;
use App\Services\PhonePeService;
use Carbon\Carbon;


class OrderController extends Controller
{
    public function storeOrder(Request $request)
    {
        $request->validate([
            'vendors' => 'required|array',
            'grand_total' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        $cartItems = CartItem::with(['product.vendor', 'variant'])
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 400);
        }

        foreach ($cartItems as $cartItem) {
            $variant = $cartItem->variant;
            if (!$variant || $variant->stock < $cartItem->quantity) {
                $available = $variant?->stock ?? 0;
                return response()->json([
                    'success' => false,
                    'message' => "Only {$available} qty available for {$cartItem->product->title}."
                ], 422);
            }
        }

        $groupedCart = $cartItems->groupBy(fn($item) => $item->product->vendor_id ?? 0);
        $orderNumber = 'ORD' . date('YmdHis') . rand(100, 999);
        $totals = $this->calculateOrderTotals($groupedCart, $user);
        $finalAmount = (float) $request->grand_total;
        $walletUsed = $totals['wallet_used'] ?? 0;

        if ($walletUsed > 0) {
            $walletBalance = WalletTransaction::getBalance($user->id);
            if ($walletBalance < $walletUsed) {
                return response()->json(['success' => false, 'message' => 'Insufficient wallet balance.'], 400);
            }
        }

        // Build checkout data for session (no DB order until payment complete)
        $usedCoupons = [];
        $vendorsData = [];

        foreach ($groupedCart as $vendorId => $items) {
            $vendor = VendorAdmin::find($vendorId);
            if (!$vendor) {
                continue;
            }
            $vendorTotals = $this->calculateVendorTotals($items, $vendorId, $user);
            $vendorData = $request->vendors[$vendorId] ?? [];
            $vendorsData[$vendorId] = [
                'delivery_type' => $vendorData['delivery_type'] ?? 'standard',
                'custom_delivery' => $vendorData['custom_delivery'] ?? null,
                'vendor_totals' => $vendorTotals,
                'items' => $items->map(fn($i) => [
                    'product_id' => $i->product_id,
                    'variant_id' => $i->variant_id,
                    'quantity' => $i->quantity,
                    'price' => (float) $i->price,
                ])->values()->toArray(),
            ];
            if ($vendorTotals['coupon_id']) {
                $usedCoupons[] = ['coupon_id' => $vendorTotals['coupon_id'], 'vendor_id' => $vendorId];
            }
        }
        if ($totals['coupon_id']) {
            $usedCoupons[] = ['coupon_id' => $totals['coupon_id'], 'vendor_id' => null];
        }

        $checkoutData = [
            'order_number' => $orderNumber,
            'totals' => [
                'subtotal' => $totals['subtotal'],
                'coupon_id' => $totals['coupon_id'],
                'coupon_discount' => $totals['coupon_discount'],
                'wallet_used' => $walletUsed,
                'final_amount' => $finalAmount,
            ],
            'vendors' => $vendorsData,
            'used_coupons' => $usedCoupons,
        ];

        session()->put('checkout_data', $checkoutData);

        return response()->json([
            'success' => true,
            'message' => 'Proceed to delivery address',
        ]);
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

                // Log::info('Coupon usage incremented', [
                //     'coupon_id' => $couponId,
                //     'user_id' => $userId,
                //     'new_count' => $usage->usage_count
                // ]);
            } else {
                // Create new usage record
                $newUsage = CouponUsage::create([
                    'coupon_id' => $couponId,
                    'user_id' => $userId,
                    'usage_count' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Log::info('New coupon usage created', [
                //     'coupon_usage_id' => $newUsage->id,
                //     'coupon_id' => $couponId,
                //     'user_id' => $userId
                // ]);
            }
        }
    }

    /**
     * Create Order, VendorOrder, OrderItem from session/checkout data. Call only when payment is being completed.
     */
    public function createOrderFromCheckoutData(array $checkoutData, int $userId, ?int $custAddressId): Order
    {
        $totals = $checkoutData['totals'];
        $orderNumber = $checkoutData['order_number'];

        $order = Order::create([
            'user_id' => $userId,
            'cust_address_id' => $custAddressId,
            'order_number' => $orderNumber,
            'total_price' => $totals['subtotal'],
            'coupon_id' => $totals['coupon_id'],
            'coupon_discount' => $totals['coupon_discount'],
            'wallet_used' => $totals['wallet_used'],
            'final_amount' => $totals['final_amount'],
            'status' => 'confirmed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (($totals['wallet_used'] ?? 0) > 0) {
            $this->processWalletTransaction($userId, $totals['wallet_used'], $order->id);
        }

        foreach ($checkoutData['vendors'] as $vendorId => $vd) {
            $vendor = VendorAdmin::find($vendorId);
            if (!$vendor) {
                continue;
            }
            $vendorTotals = $vd['vendor_totals'];
            $deliverySlotId = $this->createDeliverySlotFromCheckout($userId, (int) $vendorId, $vd);

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

            foreach ($vd['items'] as $item) {
                OrderItem::create([
                    'vendor_order_id' => $vendorOrder->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total_price' => $item['quantity'] * $item['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if (!empty($checkoutData['used_coupons'])) {
            $this->updateCouponUsages($checkoutData['used_coupons'], $userId);
        }

        session()->forget('checkout_data');
        session()->forget('checkout_address_id');
        session()->forget('global_coupon');
        session()->forget('use_wallet');
        session()->forget('wallet_amount');
        foreach (array_keys($checkoutData['vendors'] ?? []) as $vid) {
            session()->forget("vendor_{$vid}_coupon");
        }

        return $order;
    }

    private function createDeliverySlotFromCheckout(int $userId, int $vendorId, array $vd): ?int
    {
        $type = $vd['delivery_type'] ?? 'standard';
        $custom = $vd['custom_delivery'] ?? null;

        if ($type === 'custom' && $custom) {
            $slot = DeliverySlot::create([
                'user_id' => $userId,
                'vendor_id' => $vendorId,
                'date' => $custom['date'],
                'start_time' => date('H:i', strtotime($custom['start_time'])),
                'end_time' => date('H:i', strtotime($custom['end_time'])),
                'is_available' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return $slot->id;
        }
        if ($type === 'express') {
            $start = now()->addMinutes(20);
            $end = now()->addMinutes(40);
        } else {
            $start = now()->addMinutes(30);
            $end = now()->addMinutes(60);
        }
        $slot = DeliverySlot::firstOrCreate([
            'user_id' => $userId,
            'vendor_id' => $vendorId,
            'date' => $start->format('Y-m-d'),
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
        ], [
            'is_available' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return $slot->id;
    }

    /**
     * Decrement product stock for all items of an order. Call only when payment is complete.
     * Uses order.stock_deducted flag to avoid double decrement (e.g. PhonePe redirect + callback).
     */
    private function decrementStockForOrder(int $orderId): void
    {
        $order = Order::find($orderId);
        if (!$order || $order->stock_deducted) {
            return;
        }
        $orderItems = OrderItem::whereHas('vendorOrder', fn ($q) => $q->where('order_id', $orderId))
            ->with('variant')
            ->get();
        foreach ($orderItems as $orderItem) {
            if ($orderItem->variant) {
                $orderItem->variant->decrement('stock', $orderItem->quantity);
            }
        }
        $order->update(['stock_deducted' => true]);
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

                // Log::info('Price calculation', [
                //     'item_id' => $item->id,
                //     'cart_price' => $item->price,
                //     'quantity' => $item->quantity,
                //     'item_total' => $item->quantity * $price
                // ]);
            }
        }

     //   Log::info('Total subtotal', ['subtotal' => $subtotal]);

        // Apply global coupon if any
        $globalCoupon = session()->get('global_coupon');
        if ($globalCoupon && $this->isCouponValid($globalCoupon, $user, $groupedCart)) {
            $couponDiscount = $this->calculateCouponDiscount($globalCoupon, $subtotal, $groupedCart);
          //  Log::info('Global coupon applied', ['discount' => $couponDiscount]);
        }

        // Apply wallet balance if used
        $useWallet = session()->get('use_wallet', false);
        $walletAmount = session()->get('wallet_amount', 0);
        $walletBalance = WalletTransaction::getBalance($user->id);

        if ($useWallet && $walletBalance > 0) {
            $walletUsed = min($walletBalance, $walletAmount, $subtotal - $couponDiscount);
          //  Log::info('Wallet used', ['amount' => $walletUsed]);
        }

        // Calculate delivery fees
        $deliveryFee = $this->calculateDeliveryFee($groupedCart);

        $finalAmount = $subtotal + $deliveryFee - $couponDiscount - $walletUsed;

        // Log::info('Final amount calculation', [
        //     'subtotal' => $subtotal,
        //     'delivery_fee' => $deliveryFee,
        //     'coupon_discount' => $couponDiscount,
        //     'wallet_used' => $walletUsed,
        //     'final_amount' => $finalAmount
        // ]);

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

       // Log::info('Vendor subtotal', ['vendor_id' => $vendorId, 'subtotal' => $subtotal]);

        // Apply vendor-specific coupon if any
        $vendorCoupon = session()->get("vendor_{$vendorId}_coupon");
        $couponDiscount = 0;
        $couponId = null;

        if ($vendorCoupon && $this->isCouponValid($vendorCoupon, $user, [$vendorId => $items])) {
            $couponDiscount = $this->calculateCouponDiscount($vendorCoupon, $subtotal, [$vendorId => $items]);
            $couponId = $vendorCoupon->id;
           // Log::info('Vendor coupon applied', ['vendor_id' => $vendorId, 'discount' => $couponDiscount]);
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
           // Log::info('Coupon invalid for user', ['coupon_id' => $coupon->id]);
            return false;
        }

        // Check minimum order amount
        $subtotal = $this->calculateCartSubtotal($cartItems);
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            // Log::info('Coupon minimum order not met', [
            //     'coupon_id' => $coupon->id,
            //     'min_order' => $coupon->min_order_amount,
            //     'subtotal' => $subtotal
            // ]);
            return false;
        }

        // Check if coupon applies to specific products/categories
        if ($coupon->applies_to !== 'all') {
            if (!$this->isCouponApplicableToCart($coupon, $cartItems)) {
             //   Log::info('Coupon not applicable to cart items', ['coupon_id' => $coupon->id]);
                return false;
            }
        }

        // Check vendor restriction based on created_by_type and created_by_id
        if ($coupon->created_by_type === 'vendor') {
            $vendorIds = array_keys($cartItems);
            if (count($vendorIds) > 1 || $vendorIds[0] != $coupon->created_by_id) {
                // Log::info('Coupon vendor restriction failed', [
                //     'coupon_id' => $coupon->id,
                //     'coupon_vendor' => $coupon->created_by_id,
                //     'cart_vendors' => $vendorIds
                // ]);
                return false;
            }
        }

      //  Log::info('Coupon is valid', ['coupon_id' => $coupon->id]);
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
        // Log::info('Coupon applicability check', [
        //     'coupon_id' => $coupon->id,
        //     'applies_to' => $coupon->applies_to,
        //     'applicable_items' => count($applicableItems),
        //     'is_applicable' => $isApplicable
        // ]);

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

        // Log::info('Coupon discount calculation', [
        //     'coupon_id' => $coupon->id,
        //     'discount_type' => $coupon->discount_type,
        //     'discount_value' => $coupon->discount_value,
        //     'applicable_amount' => $applicableAmount
        // ]);

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
        $checkoutData = session('checkout_data');
        if (!$checkoutData) {
            return redirect()->route('cart.view')->with('error', 'Please proceed from cart first.');
        }

        // View expects an object with order_number, final_amount; id used only for form/API
        $order = (object) [
            'id' => 0,
            'order_number' => $checkoutData['order_number'],
            'final_amount' => $checkoutData['totals']['final_amount'],
        ];

        return view('web.checkoutaddress', compact('order'));
    }

    /**
     * Check if given lat/lng is deliverable by all vendors in the order (intersects each vendor's delivery area).
     * Used when user clicks "Use my current location" on checkout address page.
     */
    public function checkDeliveryLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $checkoutData = session('checkout_data');
        if (!$checkoutData || empty($checkoutData['vendors'])) {
            return response()->json(['deliverable' => false, 'message' => 'Checkout session expired. Please try again from cart.']);
        }

        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;
        $vendorIds = array_keys($checkoutData['vendors']);

        $tempAddress = new CustomerAddress();
        $tempAddress->latitude = $lat;
        $tempAddress->longitude = $lng;

        foreach ($vendorIds as $vendorId) {
            if (!$tempAddress->isDeliverableByVendor((int) $vendorId)) {
                return response()->json([
                    'deliverable' => false,
                    'message' => 'Sorry we could not deliver on this address.',
                ]);
            }
        }

        return response()->json(['deliverable' => true]);
    }

    /** Max distance (km) to allow delivery address from current location */
    const DELIVERY_LOCATION_RADIUS_KM = 50;

    public function updateAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:customer_addresses,id',
            'current_latitude' => 'required|numeric',
            'current_longitude' => 'required|numeric',
        ]);

        if (!session('checkout_data')) {
            return redirect()->route('cart.view')->with('error', 'Checkout session expired. Please try again from cart.');
        }

        $address = CustomerAddress::where('id', $request->address_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$address) {
            return redirect()->back()->with('error', 'Invalid address');
        }

        if (!$address->latitude || !$address->longitude) {
            return redirect()->back()->with('error', 'This address cannot be used for delivery at current location. Please add an address in your current area using "Use my current location".');
        }
        $km = CustomerAddress::distanceInKm(
            (float) $request->current_latitude,
            (float) $request->current_longitude,
            (float) $address->latitude,
            (float) $address->longitude
        );
        if ($km === null || $km > self::DELIVERY_LOCATION_RADIUS_KM) {
            return redirect()->back()->with('error', 'Selected address is not in your current delivery location. Please select an address in your current area or update your location.');
        }

        CustomerAddress::where('user_id', Auth::id())->update(['is_selected' => false]);
        $address->is_selected = true;
        $address->save();

        session()->put('checkout_address_id', $request->address_id);

        return redirect()->route('payment.checkout');
    }

    /**
     * Initiate PhonePe for session checkout (no order in DB yet). Creates PendingCheckout and returns redirect URL.
     */
    public function initiatePhonePe(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1', // in paise
        ]);

        $checkoutData = session('checkout_data');
        $addressId = session('checkout_address_id');
        if (!$checkoutData || !$addressId) {
            return response()->json([
                'success' => false,
                'message' => 'Checkout session expired. Please try again from cart.'
            ], 400);
        }

        $user = Auth::user();
        $amount = (float) $request->amount;
        $amountPaise = (int) round($amount);
        if ($amountPaise < 100) {
            return response()->json(['success' => false, 'message' => 'Invalid amount.'], 400);
        }

        $pending = PendingCheckout::create([
            'user_id' => $user->id,
            'cust_address_id' => $addressId,
            'amount' => $amount / 100,
            'currency' => 'INR',
            'order_data' => $checkoutData,
            'status' => 'pending',
        ]);

        $transactionId = 'PC_' . $pending->id;
        $payment = Payment::create([
            'order_id' => null,
            'pending_checkout_id' => $pending->id,
            'user_id' => $user->id,
            'payment_method' => 'phonepe',
            'payment_status' => 'initiated',
            'amount' => $amount / 100,
            'currency' => 'INR',
            'transaction_id' => $transactionId,
        ]);

        $phonePe = app(PhonePeService::class);
        $result = $phonePe->createOrder($amountPaise, $transactionId);

        if (isset($result['orderId']) && isset($result['redirectUrl'])) {
            $payment->update([
                'gateway_reference' => $result['orderId'] ?? null,
                'payment_status' => 'pending',
            ]);
            return response()->json([
                'success' => true,
                'redirectUrl' => $result['redirectUrl'],
            ]);
        }

        $errorMessage = $result['message'] ?? ($result['error'] ?? 'Unknown error from PhonePe');
        return response()->json([
            'success' => false,
            'message' => $errorMessage,
            'debug' => $result,
        ], 500);
    }

    public function paymentCheckout()
    {
        $checkoutData = session('checkout_data');
        $addressId = session('checkout_address_id');
        if (!$checkoutData) {
            return redirect()->route('cart.view')->with('error', 'Checkout session expired. Please try again from cart.');
        }
        if (!$addressId) {
            return redirect()->route('delivery.address')->with('error', 'Please select a delivery address first.');
        }

        $address = CustomerAddress::where('id', $addressId)->where('user_id', Auth::id())->first();
        if (!$address) {
            return redirect()->route('delivery.address')->with('error', 'Invalid address.');
        }

        $totals = $checkoutData['totals'];
        $order = (object) [
            'id' => 0,
            'order_number' => $checkoutData['order_number'],
            'total_price' => $totals['subtotal'],
            'coupon_discount' => $totals['coupon_discount'],
            'wallet_used' => $totals['wallet_used'],
            'wallet_discount' => $totals['wallet_used'],
            'final_amount' => $totals['final_amount'],
            'address' => $address,
        ];

        return view('web.paymentdetails', compact('order'));
    }

    public function paymentDetails($orderId)
    {
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
        $checkoutData = session('checkout_data');
        $addressId = session('checkout_address_id');
        if (!$checkoutData || !$addressId) {
            return response()->json([
                'success' => false,
                'message' => 'Checkout session expired. Please try again from cart.'
            ], 400);
        }

        $user = Auth::user();
        $address = CustomerAddress::where('id', $addressId)->where('user_id', $user->id)->first();
        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Invalid address.'], 400);
        }

        // Validate stock from cart
        $cartItems = CartItem::with('variant')->where('user_id', $user->id)->get();
        foreach ($cartItems as $item) {
            if ($item->variant && $item->variant->stock < $item->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$item->variant->stock} qty available for an item. Please update cart."
                ], 422);
            }
        }

        DB::beginTransaction();
        try {
            $order = $this->createOrderFromCheckoutData($checkoutData, $user->id, (int) $addressId);
            Payment::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'amount' => $order->final_amount,
                'currency' => 'INR'
            ]);
            $this->decrementStockForOrder($order->id);
            CartItem::where('user_id', $user->id)->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Order confirmed successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage()
            ], 500);
        }
    }

}
