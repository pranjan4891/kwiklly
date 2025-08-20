<?php
namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with(['product', 'variant'])
            ->where('user_id', auth()->id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('web.checkout', compact('cartItems', 'total'));
    }

   public function placeOrder(Request $request)
{
    $userId = auth()->id();
    $cartItems = CartItem::with('variant.product.vendor')->where('user_id', $userId)->get();

    if ($cartItems->isEmpty()) {
        return redirect()->back()->with('error', 'Your cart is empty.');
    }

    // Group items by vendor ID
    $groupedItems = $cartItems->groupBy(function ($item) {
        return $item->variant->product->vendor->id;
    });

    DB::beginTransaction();

    try {
        foreach ($groupedItems as $vendorId => $items) {
            // Calculate total for this vendor
            $total = $items->sum(fn($item) => $item->price * $item->quantity);

            // Create a separate order for each vendor
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => strtoupper(Str::random(10)),
                'total_price' => $total,
                'status' => 'pending',
                 'vendor_id' => $vendorId
            ]);

            // Add items under this order
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ]);
            }
        }

        // Clear cart after all orders placed
        CartItem::where('user_id', $userId)->delete();

        DB::commit();

        return redirect()->route('order.success')->with('success', 'Order placed successfully!');
    } catch (\Exception $e) {
        DB::rollback();
        return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    }
}

    // public function placeOrder(Request $request)
    // {
    //     $cartItems = CartItem::where('user_id', auth()->id())->get();

    //     if ($cartItems->isEmpty()) {
    //         return redirect()->back()->with('error', 'Your cart is empty.');
    //     }

    //     DB::beginTransaction();

    //     try {
    //         $order = Order::create([
    //             'user_id' => auth()->id(),
    //             'order_number' => strtoupper(Str::random(10)),
    //             'total_price' => $cartItems->sum(fn ($item) => $item->price * $item->quantity),
    //             'status' => 'pending'
    //         ]);

    //         foreach ($cartItems as $item) {
    //             OrderItem::create([
    //                 'order_id' => $order->id,
    //                 'product_id' => $item->product_id,
    //                 'variant_id' => $item->variant_id,
    //                 'quantity' => $item->quantity,
    //                 'price' => $item->price
    //             ]);
    //         }

    //         CartItem::where('user_id', auth()->id())->delete();
    //         DB::commit();

    //         return redirect()->route('order.success')->with('success', 'Order placed successfully!');
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         return redirect()->back()->with('error', 'Something went wrong. Please try again.');
    //     }
    // }
}
