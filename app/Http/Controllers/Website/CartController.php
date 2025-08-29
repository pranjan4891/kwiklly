<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\TimeSlot;
use App\Models\VendorAdmin;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            "product_id" => "required|exists:products,id",
            "variant_id" => "required|exists:product_variants,id",
            "quantity" => "required|integer|min:1",
        ]);

        $variant = ProductVariant::with(
            "product.vendor",
            "product.featureImage"
        )->findOrFail($request->variant_id);

        if (auth()->check()) {
            $userId = auth()->id();

            $cartItem = CartItem::where("user_id", $userId)
                ->where("product_id", $request->product_id)
                ->where("variant_id", $request->variant_id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $request->quantity;
                $cartItem->price = $variant->variant_selling_price;
                $cartItem->save();
            } else {
                CartItem::create([
                    "user_id" => $userId,
                    "product_id" => $request->product_id,
                    "variant_id" => $request->variant_id,
                    "quantity" => $request->quantity,
                    "price" => $variant->variant_selling_price,
                ]);
            }

            return $this->getCartData();
        } else {
            $cart = session()->get("cart", []);
            $key = $request->product_id . "_" . $request->variant_id;

            if (isset($cart[$key])) {
                $cart[$key]["quantity"] += $request->quantity;
            } else {
                $cart[$key] = [
                    "product_id" => $request->product_id,
                    "variant_id" => $request->variant_id,
                    "title" => $variant->product->title,
                    "business_name" => $variant->product->vendor->business_name,
                    "image" => $variant->product->feature_image_id
                        ? url(
                            "public/" .
                                $variant->product->featureImage->feature_image
                        )
                        : "public/assets/website/images/default.png",
                    "price" => $variant->variant_selling_price,
                    "original_price" => $variant->variant_actual_price,
                    "quantity" => $request->quantity,
                ];
            }

            session()->put("cart", $cart);

            return response()->json([
                "status" => "success",
                "count" => count($cart),
                "cart" => $this->getGuestGroupedCart($cart),
            ]);
        }
    }

    public function incrementQty(Request $request)
    {
        $key = $request->key;

        if (auth()->check()) {
            [$productId, $variantId] = explode("_", $key);
            $item = CartItem::where("user_id", auth()->id())
                ->where("product_id", $productId)
                ->where("variant_id", $variantId)
                ->first();

            if ($item) {
                $item->quantity++;
                $item->save();
            }

            return $this->getCartData();
        } else {
            $cart = session()->get("cart", []);
            if (isset($cart[$key])) {
                $cart[$key]["quantity"]++;
                session()->put("cart", $cart);
            }

            return response()->json([
                "cart" => $this->getGuestGroupedCart($cart),
                "count" => count($cart),
            ]);
        }
    }

    public function decrementQty(Request $request)
    {
        $key = $request->key;
        $confirm = $request->confirm ?? false;

        if (!auth()->check()) {
            // Handle guest case if needed
            return response()->json(["error" => "Login required."]);
        }

        [$productId, $variantId] = explode("_", $key);
        $item = CartItem::with("variant.product.vendor")
            ->where("user_id", auth()->id())
            ->where("product_id", $productId)
            ->where("variant_id", $variantId)
            ->first();

        if (!$item) {
            return $this->getCartData();
        }

        $vendorId = $item->variant->product->vendor_id;

        // Get all cart items from same vendor
        $vendorItems = CartItem::with("variant")
            ->where("user_id", auth()->id())
            ->get()
            ->filter(fn($ci) => $ci->variant->product->vendor_id == $vendorId);

        // Calculate subtotal properly
        $vendorSubtotal = $vendorItems->sum(
            fn($ci) => $ci->quantity *
                ($ci->price ?? $ci->variant->variant_selling_price)
        );

        // Next subtotal after decrement
        $nextSubtotal =
            $vendorSubtotal -
            ($item->price ?? $item->variant->variant_selling_price);

        // Get applied coupon
        $vendorCoupons = session("vendor_coupons", []);
        $couponData = $vendorCoupons[$vendorId] ?? null;
        $coupon = $couponData
            ? Coupon::where("code", $couponData["code"])
                ->where("created_by_id", $vendorId)
                ->first()
            : null;

        // Coupon warning condition
        if ($coupon && $nextSubtotal < $coupon->min_order_amount && !$confirm) {
            return response()->json([
                "coupon_removal_warning" => true,
                "vendor_id" => $vendorId,
                "vendor_name" => $item->variant->product->vendor->business_name,
                "coupon_code" => $coupon->code,
                "min_order_amount" => $coupon->min_order_amount,
                "next_subtotal" => $nextSubtotal,
                "product_name" => $item->variant->product->title,
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
            session()->put("vendor_coupons", $vendorCoupons);
        }

        return $this->getCartData();
    }

    public function viewCart()
    {
        if (auth()->check()) {
            $cartData = $this->getCartData()->getData(true);
        } else {
            $cart = session()->get("cart", []);
            $cartData = [
                "cart" => $this->getGuestGroupedCart($cart),
                "count" => count($cart),
            ];
        }

        $user = auth()->user();
        $coupons = Coupon::where("is_active", 1)
            ->where("is_deleted", 0)
            ->get()
            ->filter(fn($c) => $c->isValidForUser($user));

        // ✅ Pass applied vendor-wise coupons from session
        $vendorCoupons = session("vendor_coupons", []);

        // Get wallet balance
        $walletBalance = 0;
        if ($user) {
            $walletBalance = WalletTransaction::getBalance($user->id);
        }

        //time slots
        $timeSlots = TimeSlot::all();

        // FIX: Ensure groupedCart has proper structure
        $groupedCart = $cartData["cart"] ?? [];

        // Add business_id to each vendor group for JavaScript access
        $enhancedGroupedCart = [];
        foreach ($groupedCart as $businessName => $items) {
            $firstItem = reset($items);
            $businessId = $firstItem['business_id'] ?? null;

            $enhancedGroupedCart[$businessName] = [
                'items' => $items,
                'business_id' => $businessId
            ];
        }

        return view("web.checkoutdelivery", [
            "groupedCart" => $enhancedGroupedCart,
            "cartCount" => $cartData["count"],
            "coupons" => $coupons,
            "vendorCoupons" => $vendorCoupons,
            "timeSlots" => $timeSlots,
            "walletBalance" => $walletBalance, // Pass wallet balance to view
        ]);
    }

    public function getCartData()
    {
        if (auth()->check()) {
            $items = CartItem::with(['variant.product.vendor', 'variant.product.featureImage'])
                ->where("user_id", auth()->id())
                ->get();

            $groupedCart = [];

            foreach ($items as $item) {
                if (!$item->variant || !$item->variant->product || !$item->variant->product->vendor) {
                    continue;
                }

                $businessId = $item->variant->product->vendor->id;
                $businessName = $item->variant->product->vendor->business_name;
                $key = $item->product_id . "_" . $item->variant_id;

                $groupedCart[$businessName][$key] = [
                    "product_id" => $item->product_id,
                    "variant_id" => $item->variant_id,
                    "title" => $item->variant->product->title,
                    "business_id" => $businessId,
                    "business_name" => $businessName,
                    "image" => $item->variant->product->feature_image_id && $item->variant->product->featureImage
                        ? url("public/" . $item->variant->product->featureImage->feature_image)
                        : asset("public/assets/website/images/default.png"),
                    "price" => $item->price ?? $item->variant->variant_selling_price,
                    "original_price" => $item->variant->variant_actual_price,
                    "quantity" => $item->quantity,
                ];
            }

            // Get wallet balance
            $walletBalance = WalletTransaction::getBalance(auth()->id());

            return response()->json([
                "cart" => $groupedCart,
                "count" => $items->count(),
                "vendor_coupons" => session("vendor_coupons", []),
                "wallet_balance" => $walletBalance, // Include wallet balance in response
            ]);
        } else {
            $cart = session()->get("cart", []);
            return response()->json([
                "cart" => $this->getGuestGroupedCart($cart),
                "count" => count($cart),
                "wallet_balance" => 0, // Guests have 0 wallet balance
            ]);
        }
    }

    /**
     * Apply wallet balance to order
     */
    public function applyWallet(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                "success" => false,
                "message" => "Please login to use wallet balance.",
            ]);
        }

        $user = auth()->user();
        $walletBalance = WalletTransaction::getBalance($user->id);
        $useWallet = $request->input('use_wallet', false);
        $walletAmount = $request->input('wallet_amount', 0);

        if ($useWallet && $walletAmount > 0) {
            // Validate wallet amount
            if ($walletAmount > $walletBalance) {
                return response()->json([
                    "success" => false,
                    "message" => "Insufficient wallet balance.",
                ]);
            }

            session()->put('use_wallet', true);
            session()->put('wallet_amount', $walletAmount);

            return response()->json([
                "success" => true,
                "message" => "Wallet balance applied successfully.",
                "wallet_amount" => $walletAmount,
                "updated_cart" => $this->getCartData()->getData(true),
            ]);
        } else {
            session()->forget('use_wallet');
            session()->forget('wallet_amount');

            return response()->json([
                "success" => true,
                "message" => "Wallet balance removed.",
                "updated_cart" => $this->getCartData()->getData(true),
            ]);
        }
    }

    /**
     * Get wallet balance
     */
    public function getWalletBalance()
    {
        if (!auth()->check()) {
            return response()->json([
                "success" => false,
                "balance" => 0,
            ]);
        }

        $user = auth()->user();
        $balance = WalletTransaction::getBalance($user->id);

        return response()->json([
            "success" => true,
            "balance" => $balance,
        ]);
    }

    private function getGuestGroupedCart($cart)
    {
        $grouped = [];

        foreach ($cart as $key => $item) {
            // FIX: Ensure guest cart items have business_id
            if (!isset($item['business_id'])) {
                // For guest users, we need to get business_id from product/variant
                // This is a temporary fix - you might need to modify your guest cart structure
                $item['business_id'] = 0; // Default value or fetch from DB if needed
            }
            $grouped[$item["business_name"]][$key] = $item;
        }

        return $grouped;
    }

    public function getMinimumOrderAmount(Request $request)
    {
          $vendorId = $request->vendor_id;
        $cartTotal = $request->cart_total ?? 0; // pass current vendor cart total from frontend

        // Get vendor details
        $vendor = VendorAdmin::where("id", $vendorId)
                    ->where("is_active", 1)
                    ->where("status", 1)
                    ->first();

        if (!$vendor) {
            return response()->json([
                "success" => false,
                "message" => "Vendor not found",
            ]);
        }

        $minOrderValue = $vendor->minimum_order_value ?? 0;
        $deliveryCharge = $vendor->delivery_charge ?? 0;

        // Check if free delivery condition applies
        if ($cartTotal >= $minOrderValue && $minOrderValue > 0) {
            $deliveryCharge = 0;
        }

        return response()->json([
            "success" => true,
            "min_order_amount" => $minOrderValue,
            "delivery_charge"  => $deliveryCharge,
        ]);
    }


    public function getVendorCoupons(Request $request)
    {
        $vendorId = $request->vendor_id;

        $coupons = Coupon::where("created_by_id", $vendorId)
            ->where("is_active", 1)
            ->where("is_deleted", 0)
            ->with(['products', 'categories', 'subcategories']) // Load relationships
            ->get();

        // Render partial blade view
        $html = view(
            "web.include.vendor-coupon-modal",
            compact("coupons")
        )->render();

        return response()->json(["html" => $html]);
    }

    public function applyCoupon(Request $request)
    {
        $code = $request->input("code");
        $vendorId = $request->input("vendor_id");
        $user = auth()->user();

        // Get the coupon for this vendor with relationships
        $coupon = Coupon::where("code", $code)
            ->where("created_by_id", $vendorId)
            ->with(['products', 'categories', 'subcategories'])
            ->first();

        // Validate coupon existence and validity
        if (!$coupon || !$coupon->isValidForUser($user)) {
            return response()->json([
                "success" => false,
                "message" => "Invalid or expired coupon.",
            ]);
        }

        // Get cart items for this vendor only with relationships
        $items = CartItem::where("user_id", $user->id)
            ->whereHas(
                "variant.product.vendor",
                fn($q) => $q->where("id", $vendorId)
            )
            ->with(['variant.product.category', 'variant.product.subcategory']) // Updated to match your model
            ->get();

        if ($items->isEmpty()) {
            return response()->json([
                "success" => false,
                "message" => "No items from this vendor.",
            ]);
        }

        // Filter items based on coupon applicability
        $applicableItems = $this->getApplicableItems($coupon, $items);

        if (empty($applicableItems)) {
            return response()->json([
                "success" => false,
                "message" => $this->getCouponNotApplicableMessage($coupon),
            ]);
        }

        // Calculate subtotal for applicable items only
        $applicableSubtotal = 0;
        foreach ($applicableItems as $item) {
            $price = $item->price ?? ($item->variant->selling_price ?? 0);
            $applicableSubtotal += $item->quantity * $price;
        }

        // Check min order condition for applicable items
        if ($applicableSubtotal < ($coupon->min_order_amount ?? 0)) {
            return response()->json([
                "success" => false,
                "message" => "Add items worth ₹" . $coupon->min_order_amount . " to use this coupon.",
            ]);
        }

        // Calculate discount based on applicable items
        $discount = $this->calculateDiscount($coupon, $applicableSubtotal);

        // Store coupon per vendor in session with additional info
        $appliedCoupons = session("vendor_coupons", []);
        $appliedCoupons[$vendorId] = [
            "code" => $coupon->code,
            "discount" => round($discount, 2),
            "coupon_type" => $coupon->applies_to,
            "applicable_items" => count($applicableItems),
        ];
        session(["vendor_coupons" => $appliedCoupons]);

        return response()->json([
            "success" => true,
            "message" => "Coupon applied successfully!",
            "code" => $coupon->code,
            "discount" => round($discount, 2),
            "discount_text" => $this->getDiscountText($coupon, $discount),
            "vendor_id" => $vendorId,
            "updated_cart" => $this->getCartData()->getData(true),
            "vendor_coupons" => session("vendor_coupons", []),
        ]);
    }

    /**
     * Get items that are applicable for the coupon
     */
    private function getApplicableItems($coupon, $items)
    {
        $applicableItems = [];

        foreach ($items as $item) {
            if ($this->isItemApplicable($coupon, $item)) {
                $applicableItems[] = $item;
            }
        }

        return $applicableItems;
    }

    /**
     * Check if an item is applicable for the coupon
     */
    private function isItemApplicable($coupon, $item)
    {
        // If coupon applies to all items, return true
        if ($coupon->applies_to === 'all') {
            return true;
        }

        // Check if item has necessary relationships
        if (!$item->variant || !$item->variant->product) {
            return false;
        }

        $product = $item->variant->product;

        // Check based on coupon type
        switch ($coupon->applies_to) {
            case 'product':
                // Check if item's product is in coupon's product list
                return $coupon->products->contains('id', $product->id);

            case 'category':
                // Check if item's category is in coupon's category list
                return $product->category_id &&
                    $coupon->categories->contains('id', $product->category_id);

            case 'subcategory':
                // Check if item's subcategory is in coupon's subcategory list
                return $product->sub_category_id &&
                    $coupon->subcategories->contains('id', $product->sub_category_id);

            default:
                return false;
        }
    }

    /**
     * Get appropriate message when coupon is not applicable
     */
    private function getCouponNotApplicableMessage($coupon)
    {
        switch ($coupon->applies_to) {
            case 'product':
                return "This coupon is only valid for specific products.";

            case 'category':
                return "This coupon is only valid for specific categories.";

            case 'subcategory':
                return "This coupon is only valid for specific subcategories.";

            default:
                return "This coupon is not applicable to any items in your cart.";
        }
    }

    /**
     * Calculate discount based on coupon type and applicable amount
     */
    private function calculateDiscount($coupon, $applicableAmount)
    {
        if ($coupon->discount_type === "fixed") {
            return min($coupon->discount_value, $applicableAmount);
        }

        if ($coupon->discount_type === "percentage") {
            $discount = ($applicableAmount * $coupon->discount_value) / 100;

            // Apply max discount cap if set
            if ($coupon->max_discount_amount) {
                return min($discount, $coupon->max_discount_amount);
            }

            return $discount;
        }

        return 0;
    }

    /**
     * Get formatted discount text
     */
    private function getDiscountText($coupon, $discount)
    {
        if ($coupon->discount_type === "fixed") {
            return "Flat ₹" . round($discount, 2) . " OFF";
        }

        if ($coupon->discount_type === "percentage") {
            return $coupon->discount_value . "% OFF (₹" . round($discount, 2) . ")";
        }

        return "Discount applied";
    }

    public function clearCoupon(Request $request)
    {
        $vendorId = $request->input("vendor_id");

        $appliedCoupons = session("vendor_coupons", []);

        if (isset($appliedCoupons[$vendorId])) {
            unset($appliedCoupons[$vendorId]);
            session(["vendor_coupons" => $appliedCoupons]);
        }

        return response()->json([
            "success" => true,
            "message" => "Coupon removed successfully.",
            "vendor_id" => $vendorId,
            "updated_cart" => $this->getCartData()->getData(true),
            "vendor_coupons" => session("vendor_coupons"),
        ]);
    }

}
