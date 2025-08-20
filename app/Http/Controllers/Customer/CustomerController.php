<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Order;


class CustomerController extends Controller
{
    public function login()
    {
        return view('web.login');
    }
    // public function loginStore(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|string',
    //     ]);

    //     if (auth()->attempt($credentials)) {
    //         $request->session()->regenerate();
    //         return redirect()->route('customer.dashboard');
    //     }

    //     return back()->withErrors([
    //         'email' => 'Invalid email or password.',
    //     ]);
    // }

    public function loginStore(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (auth()->attempt($credentials)) {
        $request->session()->regenerate();

        // Migrate session cart
        $sessionCart = session('cart', []);

        foreach ($sessionCart as $item) {
            CartItem::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'product_id' => $item['product_id'],
                    'variant_id' => $item['variant_id'],
                ],
                [
                    'quantity' => \DB::raw("quantity + {$item['quantity']}"),
                    'price' => $item['price']
                ]
            );
        }

        session()->forget('cart');

        return redirect()->route('customer.dashboard');
    }

    return back()->withErrors([
        'email' => 'Invalid email or password.',
    ]);
}

    public function loginbyphone()
    {
        return view('web.loginphone');
    }
    public function forgot()
    {
        return view('web.forgot');
    }
    public function signup()
    {
        return view('web.signup');
    }
    
   public function signupStore(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:100',
        'email' => 'required|email|unique:users,email',
        'phone_number' => 'required|unique:users,phone_number',
        'password' => 'required|confirmed|min:6',
        'referral_code' => 'nullable|string|max:10',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'password' => Hash::make($request->password),
        'referral_code' => $request->referral_code,
    ]);

    auth()->login($user);

    return redirect()->route('customer.dashboard');
}

   
    public function myaccount()
{
    $user = auth()->user();

    $orders = \App\Models\Order::with(['items.variant.product.vendor'])
        ->where('user_id', $user->id)
        ->latest()
        ->get();

    // Group order items by vendor (business_name)
    $groupedOrders = [];

    foreach ($orders as $order) {
        $itemsByVendor = [];

        foreach ($order->items as $item) {
            $vendorName = $item->variant->product->vendor->business_name ?? 'Unknown Vendor';

            $itemsByVendor[$vendorName][] = [
                'product_title' => $item->variant->product->title,
                'variant' => $item->variant->variant_name ?? '',
                'quantity' => $item->quantity,
                'price' => $item->price,
                'image' => $item->variant->product->vendor->business_logo
                    ? url('public/' . $item->variant->product->vendor->business_logo)
                    : asset('public/assets/website/images/default.png')
            ];
        }
        //dd($itemsByVendor);

        $groupedOrders[] = [
            'order_id' => $order->order_number,
            'total' => $order->total_price,
            'status' => $order->status,
            'date' => $order->created_at->format('D j M Y, h:i A'),
            'vendors' => $itemsByVendor
        ];
    }

    return view('web.myaccount', compact('groupedOrders'));
}

    public function orderDetails($order_id)
    {
        $order = Order::with(['items.variant.product.vendor'])
            ->where('order_number', $order_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('web.orderdetails', compact('order'));
    }


    public function orderCancel($orderNumber)
    {
        $order = Order::with(['items.variant.product.vendor'])
                    ->where('order_number', $orderNumber)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        return view('web.ordercancel', compact('order'));
    }

    public function processCancel($orderNumber)
    {
        $order = Order::with(['items.variant.product.vendor'])
        ->where('order_number', $orderNumber)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        $order->status = 'cancelled';
        $order->save();

        return redirect()->route('customer.dashboard')->with('success', 'Order cancelled successfully.');
    }




   


    
}