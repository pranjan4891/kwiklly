<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\CartItem;
use App\Models\Coupon;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::with('product.vendor', 'product.featureImage')->findOrFail($request->variant_id);

        if (auth()->check()) {
            $userId = auth()->id();

            $cartItem = CartItem::where('user_id', $userId)
                ->where('product_id', $request->product_id)
                ->where('variant_id', $request->variant_id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $request->quantity;
                $cartItem->price = $variant->variant_selling_price;
                $cartItem->save();
            } else {
                CartItem::create([
                    'user_id' => $userId,
                    'product_id' => $request->product_id,
                    'variant_id' => $request->variant_id,
                    'quantity' => $request->quantity,
                    'price' => $variant->variant_selling_price,
                ]);
            }

            return $this->getCartData();
        } else {
            $cart = session()->get('cart', []);
            $key = $request->product_id . '_' . $request->variant_id;

            if (isset($cart[$key])) {
                $cart[$key]['quantity'] += $request->quantity;
            } else {
                $cart[$key] = [
                    'product_id' => $request->product_id,
                    'variant_id' => $request->variant_id,
                    'title' => $variant->product->title,
                    'business_name' => $variant->product->vendor->business_name,
                    'image' => $variant->product->feature_image_id
                        ? url('public/' . $variant->product->featureImage->feature_image)
                        : 'public/assets/website/images/default.png',
                    'price' => $variant->variant_selling_price,
                    'original_price' => $variant->variant_actual_price,
                    'quantity' => $request->quantity,
                ];
            }

            session()->put('cart', $cart);

            return response()->json([
                'status' => 'success',
                'count' => count($cart),
                'cart' => $this->getGuestGroupedCart($cart)
            ]);
        }
    }

    public function incrementQty(Request $request)
    {
        $key = $request->key;

        if (auth()->check()) {
            [$productId, $variantId] = explode('_', $key);
            $item = CartItem::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->where('variant_id', $variantId)
                ->first();

            if ($item) {
                $item->quantity++;
                $item->save();
            }

            return $this->getCartData();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$key])) {
                $cart[$key]['quantity']++;
                session()->put('cart', $cart);
            }

            return response()->json([
                'cart' => $this->getGuestGroupedCart($cart),
                'count' => count($cart)
            ]);
        }
    }



public function decrementQty(Request $request)
{
    $key = $request->key;
    $confirm = $request->confirm ?? false;

    if (!auth()->check()) {
        // Handle guest case if needed
        return response()->json(['error' => 'Login required.']);
    }

    [$productId, $variantId] = explode('_', $key);
    $item = CartItem::with('variant.product.vendor')->where('user_id', auth()->id())
        ->where('product_id', $productId)
        ->where('variant_id', $variantId)
        ->first();

    if (!$item) return $this->getCartData();

    $vendorId = $item->variant->product->vendor_id;

    // Get all cart items from same vendor
    $vendorItems = CartItem::with('variant')->where('user_id', auth()->id())->get()
        ->filter(fn ($ci) => $ci->variant->product->vendor_id == $vendorId);

    // Calculate subtotal properly
    $vendorSubtotal = $vendorItems->sum(fn ($ci) => $ci->quantity * ($ci->price ?? $ci->variant->variant_selling_price));

    // Next subtotal after decrement
    $nextSubtotal = $vendorSubtotal - ($item->price ?? $item->variant->variant_selling_price);

    // Get applied coupon
    $vendorCoupons = session('vendor_coupons', []);
    $couponData = $vendorCoupons[$vendorId] ?? null;
    $coupon = $couponData
        ? Coupon::where('code', $couponData['code'])->where('created_by_id', $vendorId)->first()
        : null;

    // Coupon warning condition
    if ($coupon && $nextSubtotal < $coupon->min_order_amount && !$confirm) {
        return response()->json([
            'coupon_removal_warning' => true,
            'vendor_id' => $vendorId,
            'vendor_name' => $item->variant->product->vendor->business_name,
            'coupon_code' => $coupon->code,
            'min_order_amount' => $coupon->min_order_amount,
            'next_subtotal' => $nextSubtotal,
            'product_name' => $item->variant->product->title,
        ]);
    }

    // Proceed with decrement
    if ($item->quantity > 1) {
        $item->quantity--;
        $item->save();
    } else {
        $item->delete();
    }

    // Remove coupon only if subtotal is now below minimum
    if ($coupon && $nextSubtotal < $coupon->min_order_amount) {
        unset($vendorCoupons[$vendorId]);
        session()->put('vendor_coupons', $vendorCoupons);
    }

    return $this->getCartData();
}





    public function getCartData()
    {
        if (auth()->check()) {
            $items = CartItem::where('user_id', auth()->id())->get();

            $groupedCart = [];

            foreach ($items as $item) {
                $businessId=$item->variant->product->vendor->id;
                $businessName = $item->variant->product->vendor->business_name;
                $key = $item->product_id . '_' . $item->variant_id;

                $groupedCart[$businessName][$key] = [
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'title' => $item->variant->product->title,
                    'business_id' => $businessId,
                    'business_name' => $businessName,
                    'image' => $item->variant->product->feature_image_id
                        ? url('public/' . $item->variant->product->featureImage->feature_image)
                        : 'public/assets/website/images/default.png',
                    'price' => $item->price,
                    'original_price' => $item->variant->variant_actual_price,
                    'quantity' => $item->quantity,
                ];
            }

            return response()->json([
                'cart' => $groupedCart,
                'count' => $items->count(),
                'vendor_coupons' => session('vendor_coupons', []),
            ]);
        } else {
            $cart = session()->get('cart', []);
            return response()->json([
                'cart' => $this->getGuestGroupedCart($cart),
                'count' => count($cart)
            ]);
        }
    }

    private function getGuestGroupedCart($cart)
    {
        $grouped = [];

        foreach ($cart as $key => $item) {
            $grouped[$item['business_name']][$key] = $item;
        }

        return $grouped;
    }

public function viewCart()
{
    if (auth()->check()) {
        $cartData = $this->getCartData()->getData(true); // Get raw array from JsonResponse
    } else {
        $cart = session()->get('cart', []);
        $cartData = [
            'cart' => $this->getGuestGroupedCart($cart),
            'count' => count($cart)
        ];
    }

    $user = auth()->user();
    $coupons = Coupon::where('is_active', 1)
        ->where('is_deleted', 0)
        ->get()
        ->filter(fn ($c) => $c->isValidForUser($user));

    // ✅ Pass applied vendor-wise coupons from session
    $vendorCoupons = session('vendor_coupons', []);

    return view('web.checkoutdelivery', [
        'groupedCart' => $cartData['cart'],
        'cartCount' => $cartData['count'],
        'coupons' => $coupons,
        'vendorCoupons' => $vendorCoupons,
    ]);
}



public function getVendorCoupons(Request $request)
{
    $vendorId = $request->vendor_id;

    $coupons = Coupon::where('created_by_id', $vendorId)
                    ->where('is_active', 1)
                    ->where('is_deleted', 0)
                    ->get();

    // Render partial blade view
    $html = view('web.include.vendor-coupon-modal', compact('coupons'))->render();

    return response()->json(['html' => $html]);
}




public function applyCoupon(Request $request)
{
    $code = $request->input('code');
    $vendorId = $request->input('vendor_id');
    $user = auth()->user();

    // Get the coupon for this vendor
    $coupon = Coupon::where('code', $code)->where('created_by_id', $vendorId)->first();

    // Validate coupon existence and validity
    if (!$coupon || (method_exists($coupon, 'isValidForUser') && !$coupon->isValidForUser($user))) {
        return response()->json(['success' => false, 'message' => 'Invalid or expired coupon.']);
    }

    // Get cart items for this vendor only
    $items = CartItem::where('user_id', $user->id)
        ->whereHas('variant.product.vendor', fn($q) => $q->where('id', $vendorId))
        ->get();

    if ($items->isEmpty()) {
        return response()->json(['success' => false, 'message' => 'No items from this vendor.']);
    }

    // Calculate subtotal
    $subtotal = 0;
    foreach ($items as $item) {
        $price = $item->price ?? $item->variant->selling_price ?? 0;
        $subtotal += $item->quantity * $price;
    }

    // Check min order condition
    if ($subtotal < ($coupon->min_order_amount ?? 0)) {
        return response()->json([
            'success' => false,
            'message' => 'Add items worth ₹' . $coupon->min_order_amount . ' to use this coupon.'
        ]);
    }

    // Calculate discount
    $discount = 0;
    if ($coupon->discount_type === 'fixed') {
        $discount = $coupon->discount_value;
    } elseif ($coupon->discount_type === 'percentage') {
        $discount = ($subtotal * $coupon->discount_value) / 100;
        $discount = min($discount, $coupon->max_discount_amount ?? 200); // optional cap
    }

    // Store coupon per vendor in session
    $appliedCoupons = session('vendor_coupons', []);
    $appliedCoupons[$vendorId] = [
        'code' => $coupon->code,
        'discount' => round($discount, 2),
    ];
    session(['vendor_coupons' => $appliedCoupons]);

    return response()->json([
    'success' => true,
    'message' => 'Coupon applied successfully!',
    'code' => $coupon->code,
    'discount' => round($discount, 2),
    'discount_text' => $coupon->discount_type === 'fixed'
        ? 'Flat ₹' . round($discount, 2)
        : $coupon->discount_value . '% OFF',
    'vendor_id' => $vendorId,

    // ✅ Return updated cart
    'updated_cart' => $this->getCartData()->getData(true),

    // ✅ VERY IMPORTANT: Return session coupons so frontend can update
    'vendor_coupons' => session('vendor_coupons', []),
]);

}




public function clearCoupon(Request $request)
{
    $vendorId = $request->input('vendor_id');

    $appliedCoupons = session('vendor_coupons', []);

    if (isset($appliedCoupons[$vendorId])) {
        unset($appliedCoupons[$vendorId]);
        session(['vendor_coupons' => $appliedCoupons]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Coupon removed successfully.',
        'vendor_id' => $vendorId,
        'updated_cart' => $this->getCartData()->getData(true),
        'vendor_coupons' => session('vendor_coupons'),
    ]);
}


public function getSlots($vendorId)
{
    $slots = DeliverySlot::where('vendor_id', $vendorId)
        ->where('date', '>=', now()->toDateString())
        ->orderBy('date')
        ->get()
        ->groupBy('date');
    dd($slots);
    return response()->json($slots);
}

// CartController.php
public function setDeliverySlot(Request $request)
{
    $vendorId = $request->vendor_id;
    $slotId = $request->slot_id;

    $cartSlots = session()->get('delivery_slots', []);
    $cartSlots[$vendorId] = $slotId;
    session()->put('delivery_slots', $cartSlots);

    return response()->json(['success' => true]);
}





    public function deliveryAddress()
    {
        return view('web.checkoutaddress');
    }

}
