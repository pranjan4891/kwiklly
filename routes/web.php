<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Website\HomeController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Website\CartController;
use App\Http\Controllers\Website\CheckoutController;
use App\Http\Controllers\Website\AddressController;
use App\Http\Controllers\Website\OrderController;
use App\Http\Controllers\Website\SearchController;
use App\Models\Order;
use App\Models\VendorAdmin;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Search;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

    Route::get('/clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('clear-compiled');
        echo '<center> Cache cleared!!</center>';
        // return view('admin/dashboard.clear_cache');
    })->name('clear.all.cache');

    require __DIR__ . '/admin.php';
    require __DIR__ . '/branch.php';
    require __DIR__ . '/vendor.php';
    require __DIR__ . '/customer.php';
    // Route::get('/', function () {
    //     return view('welcome');
    // });

    // Route::get('delivery-address/', function () {
    //     return view('web.checkoutaddress');
    // });
    // Route::get('checkout-delivery-page/', function () {
    //     return view('web.checkoutdelivery');
    // });


    /*Website-------------------------------*/
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/department', [HomeController::class, 'department'])->name('department');
    Route::get('/department/products', [HomeController::class, 'getProducts'])->name('department.products');
    Route::get('/stores/{slug?}', [HomeController::class, 'stores'])->name('stores');
    Route::get('/categorywiseproduct/{category_id}/{subcategory_id}', [HomeController::class, 'CategoryProducts'])->name('categorywiseproduct');
    Route::get('/productdetails', [HomeController::class, 'productdetails'])->name('productdetails');
    Route::get('/explorestore/{vendor_id}/{category_id}', [HomeController::class, 'explorestore'])->name('explorestore');
    Route::get('/explorestore/{vendor_id}/{category_id}/{subcategory_id}', [HomeController::class, 'subcategoryProducts'])->name('subcategory.products');
    Route::get('/product/{id}/variants', function ($id) {
        $product = Product::with('variants')->findOrFail($id);
        return response()->json($product->variants);
    });

    Route::get('/s', [SearchController::class, 'index'])->name('searchresults');
    Route::get('/get-product-variants/{id}', [HomeController::class, 'getProductVariants'])->name('get.product.variants');

    // web.php
    Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/increment', [CartController::class, 'incrementQty'])->name('cart.increment');
    Route::post('/cart/decrement', [CartController::class, 'decrementQty'])->name('cart.decrement');
    Route::get('/cart-data', [CartController::class, 'getCartData'])->name('cart.data');


    Route::get('/auth-status', function () {
        return response()->json(['logged_in' => auth()->check()]);
    })->name('check.auth.status');


    Route::middleware('auth')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.page');
        Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
      //      Route::get('/order-success', fn () => view('web.order_success'))->name('order.success');

        // New Update
        Route::get('/cart/view', [CartController::class, 'viewCart'])->name('cart.view');


        // Coupon
        Route::post('/coupon/apply', [CartController::class, 'applyCoupon'])->name('coupon.apply');
        // Route::post('/coupon/clear', [CartController::class, 'clearCoupon'])->name('coupon.clear');
        Route::get('/coupon/vendorwise', [CartController::class, 'getVendorCoupons'])->name('coupon.vendorwise');
        Route::post('/coupon/clear', [CartController::class, 'clearCoupon'])->name('coupon.clear');

        // Wallet routes
        Route::post('/cart/apply-wallet', [CartController::class, 'applyWallet'])->name('cart.apply.wallet');
        Route::get('/cart/wallet-balance', [CartController::class, 'getWalletBalance'])->name('cart.wallet.balance');


        // minimum order amount
        Route::get('/minimum-order-amount', [CartController::class, 'getMinimumOrderAmount'])->name('minimum.order.amount');

        // Order
        Route::post('/checkout/process-order', [OrderController::class, 'storeOrder'])->name('checkout.process.order');
        Route::get('/delivery-address', [OrderController::class, 'deliveryAddress'])->name('delivery.address');


        // Update order address
        Route::post('/order/update-address', [OrderController::class, 'updateAddress'])->name('order.updateAddress');

        // Payment details page
        Route::get('/payment/details/{orderId}', [OrderController::class, 'paymentDetails'])->name('payment.details');
        Route::post('/process-cod', [OrderController::class, 'processCOD'])->name('order.process.cod');


        // Customer Address Routes
        Route::post('/address/store', [AddressController::class, 'store'])->name('address.store');
        Route::post('/address/update/{id}', [AddressController::class, 'update'])->name('address.update');
        Route::delete('/address/delete/{id}', [AddressController::class, 'delete'])->name('address.delete');
        Route::get('/customer/addresses', [AddressController::class, 'getAddresses'])->name('address.list');
        Route::get('/customer/address/{id}', [AddressController::class, 'getSingleAddress'])->name('address.single');

        // routes/web.php
        Route::get('/debug/orders', function() {
            $orders = App\Models\Order::with(['vendorOrders.orderItems'])->get();
            return response()->json($orders);
        });

        Route::get('/debug/cart', function() {
            $user = Auth::user();
            if (!$user) return response()->json(['error' => 'Not authenticated']);

            $cartItems = App\Models\CartItem::with('product.business')
                ->where('user_id', $user->id)
                ->get()
                ->groupBy('product.business_id');

            return response()->json($cartItems);
        });

    });

    /*End Website-------------------------------*/

    // Route::get('/pay', function (PhonePeService $phonePe) {
    //     $orderId = 'ORDER_' . uniqid();
    //     $amount = 100; // ₹100
    //     $callbackUrl = route('phonepe.callback', [], true); // FULL URL is important for PhonePe

    //     $response = $phonePe->initiatePayment($orderId, $amount, $callbackUrl);

    //     if (
    //         isset($response['data']['instrumentResponse']['redirectInfo']['url'])
    //     ) {
    //         return redirect($response['data']['instrumentResponse']['redirectInfo']['url']);
    //     }

    //     \Log::error('PhonePe Payment Initiation Failed', ['response' => $response]);
    //     return 'Failed to initiate payment. Please try again.';
    // })->name('phonepe.pay'); // optional route name for testing

    // Route::match(['POST', 'GET'], '/phonepe-callback', function (Request $request) {
    //     \Log::info('PhonePe Callback Received:', $request->all());

    //     // Optionally: validate response, update order status, etc.

    //     return response('Callback received', 200);
    // })->name('phonepe.callback');

