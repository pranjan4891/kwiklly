<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\VendorOrder;
use App\Models\WalletTransaction;
use App\Models\VendorAdmin;
use App\Models\OrderItem;
use App\Models\CustomerAddress;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function login(Request $request)
    {
        // Store the previous URL (except login/signup) so we can redirect back after login
        if ($request->method() === 'GET' &&
            ! in_array($request->path(), ['login', 'signup', 'loginphone']) &&
            ! $request->is('logout'))
        {
            session(['url.intended' => url()->previous()]);
        }

        return view('web.login');
    }

    public function loginbyphone(Request $request)
    {
        // Same logic for phone login page
        if ($request->method() === 'GET' &&
            ! in_array($request->path(), ['login', 'signup', 'loginphone']) &&
            ! $request->is('logout'))
        {
            session(['url.intended' => url()->previous()]);
        }

        return view('web.loginphone');
    }

    public function signup(Request $request)
    {
         // Same logic for phone login page
        if ($request->method() === 'GET' &&
            ! in_array($request->path(), ['login', 'signup', 'loginphone']) &&
            ! $request->is('logout'))
        {
            session(['url.intended' => url()->previous()]);
        }
        return view('web.signup');
    }
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
                        'quantity' => DB::raw("quantity + {$item['quantity']}"),
                        'price' => $item['price']
                    ]
                );
            }
            session()->forget('cart');

            if (!empty($sessionCart)) {
                return redirect()->route('cart.view');
            }

            // ✅ Proper redirect
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }
    public function otpcheck(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|digits:10',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        if ($user && $user->otp == $request->otp) {
            auth()->login($user);
            $request->session()->regenerate();

            // If guest (missing name/email), redirect to profile update
            if (empty($user->name) || empty($user->email)) {
                return redirect()->route('update.profile')->with('info', 'Please update your details');
            }

            // ✅ Migrate session cart if exists
            $sessionCart = session('cart', []);
            foreach ($sessionCart as $item) {
                CartItem::updateOrCreate(
                    [
                        'user_id' => auth()->id(),
                        'product_id' => $item['product_id'],
                        'variant_id' => $item['variant_id'],
                    ],
                    [
                        'quantity' => DB::raw("quantity + {$item['quantity']}"),
                        'price' => $item['price']
                    ]
                );
            }
            session()->forget('cart');

            if (!empty($sessionCart)) {
                return redirect()->route('cart.view');
            }

            // ✅ Proper redirect
            return redirect()->intended('/');
        }

        return back()->with('error', 'Invalid OTP');
    }

    //otp sent
    public function otpsent(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|digits:10',
        ]);

        $otp = rand(100000, 999999);

        // Try to find existing user
        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            // If not found, create a guest user
            $user = User::create([
                'phone_number' => $request->phone_number,
                'otp'          => $otp,

            ]);
        } else {
            $user->update(['otp' => $otp]);
        }

        return view('web.loginotp', compact('user'))->with('otp', strval($otp));
    }


    public function resendotp(Request $request)
    {
        $user = User::where('phone_number', $request->phone_number)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $otp = rand(100000, 999999);
        $user->update(['otp' => $otp]);

        return response()->json(['success' => true, 'otp' => $otp]);
    }




    //
    public function showUpdateProfile()
    {
        return view('web.updateprofile');
    }

    public function saveUpdateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        /** @var \App\Models\User $user */
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'status'=> 'active', // upgrade from guest
        ]);

        return redirect()->route('cart.view')->with('success', 'Profile updated successfully!');
    }


   // Show Forgot Password form
    public function showForgotPasswordForm()
    {
        return view('web.forgot-password');
    }

    // Send Reset Link
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = Str::random(64);

        // Store token in password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]
        );

        // Send reset link via email
        $link = url('/reset-password/' . $token . '?email=' . urlencode($request->email));

        Mail::raw("Click the link to reset your password: $link", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Password Reset Link');
        });

        return back()->with('success', 'We have emailed your password reset link!');
    }

    // Show Reset Password form
    public function showResetPasswordForm(Request $request, $token)
    {
        $email = $request->query('email');
        return view('web.reset-password', compact('token', 'email'));
    }

    // Handle Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required'
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Invalid reset token!']);
        }

        // Update password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', 'Your password has been reset!');
    }



    public function signupStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|unique:users,phone_number',
            'password' => 'required|confirmed|min:6',
            //'referral_code' => 'nullable|string|max:10',
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

        // Get wallet balance
        $walletBalance = WalletTransaction::getBalance($user->id);

        // Get orders with proper relationships
        $orders = Order::with(['vendorOrders.vendor', 'vendorOrders.orderItems.product', 'vendorOrders.orderItems.variant'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Group orders by vendor with vendor-specific totals
        $groupedOrders = [];

        foreach ($orders as $order) {
            $itemsByVendor = [];

            foreach ($order->vendorOrders as $vendorOrder) {
                $vendorName = $vendorOrder->vendor->business_name ?? 'Unknown Vendor';
                $vendorLogo = $vendorOrder->vendor->business_logo
                    ? url('public/' . $vendorOrder->vendor->business_logo)
                    : asset('public/assets/website/images/default.png');

                // Calculate vendor-specific total
                $vendorTotal = 0;
                $vendorItems = [];

                foreach ($vendorOrder->orderItems as $item) {
                    $itemTotal = $item->price * $item->quantity;
                    $vendorTotal += $itemTotal;

                    $vendorItems[] = [
                        'product_title' => $item->product->title ?? 'Unknown Product',
                        'variant' => $item->variant->variant_name ?? '',
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'item_total' => $itemTotal
                    ];
                }

                // Add delivery fee if applicable
                if (!empty($vendorOrder->delivery_fee)) {
                    $vendorTotal += $vendorOrder->delivery_fee;
                }

                $itemsByVendor[$vendorName] = [
                    'image' => $vendorLogo,
                    'items' => $vendorItems,
                    'vendor_total' => $vendorTotal
                ];
            }

            $groupedOrders[] = [
                'order_id' => $order->order_number,
                'order_total' => $order->total_price, // Keep the full order total if needed
                'status' => $order->status,
                'date' => $order->created_at->format('D j M Y, h:i A'),
                'vendors' => $itemsByVendor
            ];
        }

        // Get user addresses
        $addresses = CustomerAddress::where('user_id', $user->id)->get();

        // Get user coupons (assuming you have a coupon usage model)
        $coupons = Coupon::whereHas('usages', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('web.myaccount', compact('groupedOrders', 'walletBalance', 'addresses', 'coupons'));
    }

    public function orderDetails($order_id)
    {
        $order = Order::with(['vendorOrders.vendor', 'vendorOrders.orderItems.product', 'vendorOrders.orderItems.variant'])
            ->where('order_number', $order_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('web.orderdetails', compact('order'));
    }


    public function orderCancel($orderNumber)
    {
        $order = Order::with(['vendorOrders.vendor', 'vendorOrders.orderItems.product', 'vendorOrders.orderItems.variant'])
                    ->where('order_number', $orderNumber)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        return view('web.ordercancel', compact('order'));
    }

    public function processCancel($orderNumber)
    {
        $order = Order::with(['vendorOrders.vendor', 'vendorOrders.orderItems.product', 'vendorOrders.orderItems.variant'])
                    ->where('order_number', $orderNumber)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        $order->status = 'cancelled';
        $order->save();

        return redirect()->route('customer.dashboard')->with('success', 'Order cancelled successfully.');
    }

}
