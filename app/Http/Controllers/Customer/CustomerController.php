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
use App\Services\LocationServiceability;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $user = User::where('email', $credentials['email'])->first();

        if (! $user) {
            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->withInput($request->only('email'));
        }

        $storedHash = $user->getRawOriginal('password');
        if (! $this->storedPasswordIsServiceableBcrypt($storedHash)) {
            try {
                $this->sendPasswordSetupOrResetEmail(
                    $user->email,
                    'Set your password — Kwiklly',
                    "Please set your password using the link below:\n\n%s\n\nIf you did not request this, you can ignore this email."
                );
            } catch (\Throwable $e) {
                return back()
                    ->withErrors(['email' => 'We could not send email right now. Please use “Forgot Password?” shortly or contact support.'])
                    ->withInput($request->only('email'));
            }

            return back()
                ->with('success', 'Please set your password. A link has been sent to your email — open it to choose your password, then log in here.')
                ->withInput($request->only('email'));
        }

        if (! auth()->attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Invalid email or password.'])
                ->withInput($request->only('email'));
        }

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

        if (! empty($sessionCart)) {
            return redirect()->route('cart.view');
        }

        return redirect()->intended('/');
    }
    public function otpcheck(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|digits:10',
            'otp' => 'required|digits:6',
        ]);

        $pending = session('pending_otp_login');
        if (! $pending || (string) ($pending['phone_number'] ?? '') !== (string) $request->phone_number) {
            return back()->with('error', 'Session expired or location missing. Please request OTP again from the login page.');
        }

        $checker = app(LocationServiceability::class);
        if (! $checker->isServiceable((float) $pending['lat'], (float) $pending['lng'])) {
            session()->forget('pending_otp_login');

            return back()->with('error', 'Delivery is not available at your selected location. Please choose a serviceable area and request OTP again.');
        }

        $user = User::where('phone_number', $request->phone_number)->first();

        if ($user && $user->otp == $request->otp) {
            session()->forget('pending_otp_login');
            auth()->login($user);
            $request->session()->regenerate();

            // ✅ Always migrate session cart first (so cart is never lost for new or existing users)
            $sessionCart = session('cart', []);
            foreach ($sessionCart as $item) {
                if (empty($item['product_id']) || empty($item['variant_id'])) {
                    continue;
                }
                $qty = (int) ($item['quantity'] ?? 1);
                $price = (float) ($item['price'] ?? 0);
                CartItem::updateOrCreate(
                    [
                        'user_id' => auth()->id(),
                        'product_id' => $item['product_id'],
                        'variant_id' => $item['variant_id'],
                    ],
                    [
                        'quantity' => DB::raw("quantity + {$qty}"),
                        'price' => $price,
                    ]
                );
            }
            session()->forget('cart');

            // If guest (missing name/email), redirect to profile update; cart already merged
            if (empty($user->name) || empty($user->email)) {
                return redirect()->route('update.profile')->with('info', 'Please update your details');
            }

            // If cart had items (or user has items after merge), go to cart else intended/dashboard
            $hasCartItems = !empty($sessionCart) || CartItem::where('user_id', auth()->id())->exists();
            if ($hasCartItems) {
                return redirect()->route('cart.view');
            }

            return redirect()->intended('/');
        }

        return back()->with('error', 'Invalid OTP');
    }

    public function otpsent(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|digits:10',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;

        $checker = app(LocationServiceability::class);
        if (! $checker->isServiceable($lat, $lng)) {
            return back()
                ->with('error', 'Delivery is not available at your location. Please select an area where we serve, then try to log in.')
                ->withInput($request->only('phone_number'));
        }

        session([
            'pending_otp_login' => [
                'phone_number' => $request->phone_number,
                'lat' => $lat,
                'lng' => $lng,
            ],
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
        $request->validate([
            'phone_number' => 'required|digits:10',
        ]);

        $pending = session('pending_otp_login');
        if (! $pending || (string) ($pending['phone_number'] ?? '') !== (string) $request->phone_number) {
            return response()->json(['error' => 'Session expired. Go back to login and request OTP again.'], 403);
        }

        $checker = app(LocationServiceability::class);
        if (! $checker->isServiceable((float) $pending['lat'], (float) $pending['lng'])) {
            session()->forget('pending_otp_login');

            return response()->json(['error' => 'Location is no longer serviceable. Please start login again.'], 403);
        }

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
        // Check if user is authenticated
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'You must be logged in to update your profile.'], 401);
            }
            return redirect()->route('login')->with('error', 'You must be logged in to update your profile.');
        }

        // @var User $user
        $user = auth()->user();

        // Check if user is a guest user (if guest users have a specific type or flag)
        if ($user->user_type == 'guest' || (isset($user->is_guest) && $user->is_guest)) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Guest users cannot update their profile. Please create a full account.'], 403);
            }
            return back()->with('error', 'Guest users cannot update their profile. Please create a full account.');
        }

        // Build validation rules
        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Phone number validation - make it conditional or handle existing phone
        if ($request->has('phone_number') && !empty($request->phone_number)) {
            $rules['phone_number'] = 'required|digits:10|unique:users,phone_number,' . $user->id;
        } elseif (!$user->phone_number) {
            // Only require phone if user doesn't have one
            $rules['phone_number'] = 'required|digits:10|unique:users,phone_number';
        }

        $validator = validator($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $avatarPath = $user->profile_photo;

        // Handle avatar upload
        if ($request->hasFile('profile_photo')) {
            // Delete old file if exists
            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                unlink(public_path($user->profile_photo));
            }
            // Upload new avatar
            $avatarFile = $request->file('profile_photo');
            $avatarName = time() . '.' . $avatarFile->getClientOriginalExtension();

            // Ensure directory exists
            $uploadPath = public_path('uploads/avatars');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $avatarFile->move($uploadPath, $avatarName);
            $avatarPath = 'uploads/avatars/' . $avatarName;
        } elseif ($request->delete_avatar == '1') {
            // Delete avatar
            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                unlink(public_path($user->profile_photo));
            }
            $avatarPath = null;
        }

        // Prepare update data
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'profile_photo' => $avatarPath,
        ];

        // Only update phone_number if provided
        if ($request->has('phone_number') && !empty($request->phone_number)) {
            $updateData['phone_number'] = $request->phone_number;
        } elseif ($user->phone_number) {
            // Keep existing phone number if not provided
            $updateData['phone_number'] = $user->phone_number;
        }

        /** @var User $user */
        $user->update($updateData);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Profile updated successfully!']);
        }

        // If user has items in cart, take to cart page; else dashboard
        $hasCartItems = CartItem::where('user_id', $user->id)->exists();
        if ($hasCartItems) {
            return redirect()->route('cart.view')->with('success', 'Profile updated successfully!');
        }
        return redirect()->route('customer.dashboard')->with('success', 'Profile updated successfully!');
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

        try {
            $this->sendPasswordSetupOrResetEmail(
                $request->email,
                'Password Reset Link — Kwiklly',
                "Click the link to reset your password:\n\n%s"
            );
        } catch (\Throwable $e) {
            return back()->withErrors(['email' => 'Unable to send email. Please try again.']);
        }

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
    // Signup Store
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
    // Dashboard
    public function myaccount()
    {
        $user = auth()->user();

        // Get wallet balance
        $walletBalance = WalletTransaction::getBalance($user->id);

        // Get orders with proper relationships - only latest 6 orders (include payments for payment status)
        $orders = Order::with(['vendorOrders.vendor', 'vendorOrders.orderItems.product', 'vendorOrders.orderItems.variant', 'vendorOrders.deliverySlot', 'payments'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(6)
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

                $deliverySlot = $vendorOrder->deliverySlot;
                $deliveryDate = null;
                if ($deliverySlot) {
                    $deliveryDate = $deliverySlot->formatted_date . ' ' . $deliverySlot->time_range;
                }

                $itemsByVendor[$vendorName] = [
                    'image' => $vendorLogo,
                    'items' => $vendorItems,
                    'vendor_total' => $vendorTotal,
                    'delivery_date' => $deliveryDate,
                    'delivery_status' => $vendorOrder->delivery_status ?? 'pending', // Add delivery status from vendor_orders
                    'vendor_id' => $vendorOrder->vendor_id // Add vendor_id for filtering
                ];
            }

            $latestPayment = $order->payments->sortByDesc('created_at')->first();
            $groupedOrders[] = [
                'order_id' => $order->order_number,
                'order_total' => $order->total_price,
                'status' => $order->status,
                'date' => $order->created_at->format('D j M Y, h:i A'),
                'created_at' => $order->created_at->timestamp,
                'payment_status' => $latestPayment ? $latestPayment->payment_status : 'pending',
                'payment_method' => $latestPayment ? $latestPayment->payment_method : 'N/A',
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


    public function orderDetails($order_id, Request $request)
    {
        $order = Order::with([
            'vendorOrders.vendor', 
            'vendorOrders.orderItems.product.featureImage',
            'vendorOrders.orderItems.variant',
            'vendorOrders.deliverySlot',
            'payments'
        ])
            ->where('order_number', $order_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Get vendor_id from query parameter if provided
        $vendorId = $request->get('vendor_id');

        return view('web.orderdetails', compact('order', 'vendorId'));
    }

    public function orderCancel($orderNumber, Request $request)
    {
        $order = Order::with(['vendorOrders.vendor', 'vendorOrders.orderItems.product', 'vendorOrders.orderItems.variant'])
                    ->where('order_number', $orderNumber)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        // Get vendor_id from query parameter if provided
        $vendorId = $request->get('vendor_id');

        return view('web.ordercancel', compact('order', 'vendorId'));
    }

    public function processCancel($orderNumber, Request $request)
    {
        $order = Order::with(['vendorOrders.vendor', 'vendorOrders.orderItems.product', 'vendorOrders.orderItems.variant'])
                    ->where('order_number', $orderNumber)
                    ->where('user_id', auth()->id())
                    ->firstOrFail();

        // Get vendor_id from request (POST or GET) if provided (for vendor-specific cancellation)
        $vendorId = $request->input('vendor_id');

        if ($vendorId) {
            // Cancel specific vendor order
            $vendorOrder = $order->vendorOrders()->where('vendor_id', $vendorId)->first();
            
            if ($vendorOrder && $vendorOrder->delivery_status !== 'cancelled') {
                $vendorOrder->delivery_status = 'cancelled';
                $vendorOrder->save();
            }
        } else {
            // Cancel all vendor orders for this order
            $order->vendorOrders()->update(['delivery_status' => 'cancelled']);
        }

        // Refresh order to get latest vendor orders
        $order->refresh();
        $order->load('vendorOrders');

        // Check if all vendor orders are cancelled
        $allCancelled = $order->vendorOrders->where('delivery_status', '!=', 'cancelled')->count() === 0;
        if ($allCancelled && $order->vendorOrders->count() > 0) {
        $order->status = 'cancelled';
        $order->save();
        }

        return redirect()->route('customer.dashboard')->with('success', 'Order cancelled successfully.');
    }

    public function showAddress(CustomerAddress $address): JsonResponse
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }
        return response()->json($address);
    }

    public function updateAddress(Request $request, CustomerAddress $address): JsonResponse
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'flat' => 'required|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'pincode' => 'required|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'alt_phone' => 'nullable|string|max:15',
        ]);
        $address->update($validated);
        return response()->json(['success' => true, 'message' => 'Address updated successfully!']);
    }

    public function deleteAddress(CustomerAddress $address): JsonResponse
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }
        $address->delete();
        return response()->json(['success' => true, 'message' => 'Address deleted successfully!']);
    }

    public function downloadInvoice($order_id)
    {
        $order = Order::with([
            'vendorOrders.vendor',
            'vendorOrders.orderItems.product.category',
            'vendorOrders.orderItems.product.subcategory',
            'vendorOrders.orderItems.variant',
            'vendorOrders.deliverySlot',
            'user',
            'address'
        ])
        ->where('order_number', $order_id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

        // Check if order is delivered
        $isDelivered = $order->vendorOrders->contains(function($vendorOrder) {
            return $vendorOrder->delivery_status === 'delivered';
        });

        if (!$isDelivered) {
            return redirect()->back()->with('error', 'Invoice can only be downloaded for delivered orders.');
        }

        $filename = 'invoice_' . $order->order_number . '.pdf';

        $pdf = Pdf::loadView('web.order.invoice', compact('order'));
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download($filename);
    }

    /**
     * Non-empty bcrypt hash only — avoids RuntimeException "This password does not use the Bcrypt algorithm."
     * when config hashing.bcrypt.verify is true and the DB holds null, plain text, or another algorithm.
     */
    protected function storedPasswordIsServiceableBcrypt(mixed $stored): bool
    {
        if (! is_string($stored) || $stored === '') {
            return false;
        }

        return str_starts_with($stored, '$2y$')
            || str_starts_with($stored, '$2a$')
            || str_starts_with($stored, '$2b$');
    }

    /**
     * Store token and email the reset / set-password link.
     *
     * @param  string  $bodyWithPercentSForLink  Must contain one %s for the full URL.
     */
    protected function sendPasswordSetupOrResetEmail(string $email, string $subject, string $bodyWithPercentSForLink): void
    {
        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => $token,
                'created_at' => now(),
            ]
        );

        $link = url('/reset-password/' . $token . '?email=' . urlencode($email));
        $body = sprintf($bodyWithPercentSForLink, $link);

        Mail::raw($body, function ($message) use ($email, $subject) {
            $message->to($email)->subject($subject);
        });
    }

}
