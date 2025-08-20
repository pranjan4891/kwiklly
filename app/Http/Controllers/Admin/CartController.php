<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CartItemUpdatedNotification;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // List all cart items related to this vendor
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
        }
        $title = 'Admin | Cart';
        $cartItems = CartItem::where('vendor_id', $admin->id)->with('user','featureImage')->get();
        return view('admin.cart', compact('cartItems','admin','title'));
    }

    // Update quantity or remove cart item
   public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'quantities' => 'required|array',
        ]);

        foreach ($request->quantities as $cartItemId => $quantity) {
            if ($quantity < 1) continue;

            $cartItem = CartItem::where('vendor_id', $admin->id)->find($cartItemId);
            if ($cartItem) {
                $cartItem->quantity = $quantity;
                $cartItem->save();

                // Notify user
                $user = $cartItem->user;
                if ($user) {
                    Notification::send($user, new CartItemUpdatedNotification($cartItem));
                }
            }
        }

        return back()->with('success', 'Cart updated and users notified.');
    }

    // Delete cart item
    public function destroy($id)
    {
        $cartItem = CartItem::where('vendor_id', auth()->id())->findOrFail($id);
        $user = $cartItem->user;

        $cartItem->delete();

        // Notify user
        Notification::send($user, new CartItemUpdatedNotification(null, true));

        return back()->with('success', 'Cart item removed and user notified.');
    }
}
