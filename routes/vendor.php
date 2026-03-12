<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Vendor\VendorAuthController;
use App\Http\Controllers\Vendor\VendorDashboardController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Vendor\CategoryController;
use App\Http\Controllers\Vendor\SubCategoryController;
use App\Http\Controllers\Vendor\ProductController;
use App\Http\Controllers\Vendor\CouponController;
use App\Http\Controllers\Vendor\OrderController;
use App\Http\Controllers\Vendor\VendorCartController;
use App\Http\Controllers\Vendor\ProductImagesController;
use Illuminate\Http\Request;

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

// 👇 Place these outside vendor middleware (public)
Route::get('/vendor/email/verify', function () {
    $user = Auth::guard('vendor')->user();
    if (!is_null($user->email_verified_at)) {
        return redirect()->route('vendor.dashboard');
    }
    return view('vendorpanel.auth.verify-email');
})->middleware('auth:vendor')->name('verification.notice');

Route::get('/vendor/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    // Refresh the user in session to reflect the verified email
    Auth::guard('vendor')->login(Auth::guard('vendor')->user());

    return redirect()->route('vendor.dashboard')->with('success', 'Email verified successfully!');
})->middleware(['auth:vendor', 'signed'])->name('verification.verify');

Route::post('/vendor/email/verification-notification', function (Request $request) {
    $request->user('vendor')->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth:vendor', 'throttle:6,1'])->name('vendor.verification.send');


Route::get('/vendor-register', [VendorController::class, 'vendorRegistration'])->name('vendor.signup');
Route::post('/vendor-register', [VendorController::class, 'submit'])->name('registration.submit');

Route::get('/vendor-login', [VendorController::class, 'vendorSignIn'])->name('vendor.login');
Route::post('/login-submit', [VendorAuthController::class, 'login'])->name('vendor.login.submit');


/*Vendor Logout - accessible to all authenticated vendors regardless of verified or approval status*/
Route::prefix('vendor')->name('vendor.')->middleware(['auth:vendor'])->group(function () {
    Route::match(['get', 'post'], '/logout', [VendorAuthController::class, 'logout'])->name('logout');
    Route::get('/status', [VendorAuthController::class, 'checkStatus'])->name('status');
});


/*Vendor Dashboard-------------------------------*/
Route::prefix('vendor')->name('vendor.')->middleware(['auth:vendor', 'verified', 'vendor.approved'])->group(function () {
    // Route::get('logout', [VendorAuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [VendorDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('profile', [VendorDashboardController::class, 'profile'])->name('profile');
    Route::post('update-profile', [VendorDashboardController::class, 'updateProfile'])->name('update.profile');
    Route::post('update-image', [VendorController::class, 'updateImage'])->name('updateImage');
    Route::post('update-delivery-charges', [VendorDashboardController::class, 'updateDeliveryCharges'])->name('delivery-charges.update');
    Route::post('/store-time', [VendorDashboardController::class, 'storeTime'])->name('update.store.time');

    // Location Contacts
    Route::post('save-location-contact', [VendorDashboardController::class, 'saveLocationContact'])->name('save.location.contact');
    Route::get('get-location-contacts', [VendorDashboardController::class, 'getLocationContacts'])->name('get.location.contacts');
    Route::get('get-location', [VendorDashboardController::class, 'getLocation'])->name('get.location');
    Route::post('share-location/{contactId}', [VendorDashboardController::class, 'shareLocation'])->name('share.location');
    Route::delete('location-contact/{id}', [VendorDashboardController::class, 'deleteLocationContact']);

    // Delivery location (master location, pindrop, polygon) - like admin branches
    Route::get('get-area', [VendorDashboardController::class, 'getArea'])->name('get.area');
    Route::get('check-lat-lon', [VendorDashboardController::class, 'pointInPolygon'])->name('check.lat.lon');
    Route::post('delivery-location/save', [VendorDashboardController::class, 'saveDeliveryLocation'])->name('delivery.location.save');

    // Category
    Route::get('categories', [CategoryController::class, 'categories'])->name('categories');
    Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');

    // Subcategory
    Route::resource('subcategories', SubCategoryController::class)->except(['show']);

    // Product
    Route::get('product/list', [ProductController::class, 'list'])->name('prolist');
    Route::get('product/add', [ProductController::class, 'add'])->name('proadd');
    // Route::post('product/store', [ProductController::class, 'store'])->name('product.store');
    // Route::get('product/edit', [ProductController::class, 'edit'])->name('proedit');

    // Product Images Management
    Route::get('product-images', [ProductImagesController::class, 'index'])->name('product.images');
    Route::post('product-images-store', [ProductImagesController::class, 'store'])->name('product.images.store');
    Route::post('product-images/update/{id}', [ProductImagesController::class, 'update'])->name('product.images.update');
    Route::get('product-images/delete/{id}', [ProductImagesController::class, 'softDelete'])->name('product.images.delete');
    Route::post('product-image/delete-single', [ProductImagesController::class, 'deleteSingleImage'])->name('product.image.delete.single');

    Route::get('product-images/deleted', [ProductImagesController::class, 'showDeletedImages'])->name('product.images.deleted');
    Route::post('product-images/{id}/restore', [ProductImagesController::class, 'restore'])->name('product.images.restore');
    Route::delete('product-images/{id}/erase', [ProductImagesController::class, 'erase'])->name('product.images.erase');

    // Product Management
    Route::get('products', [ProductController::class, 'index'])->name('products');
    Route::get('product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('product/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::post('product/{id}/delete', [ProductController::class, 'destroy'])->name('product.destroy');

    Route::get('get-subcategories', [ProductController::class, 'getByCategory'])->name('getSubcategories');
    Route::get('/variant/create/{productId}', [ProductController::class, 'createVariant'])->name('product.variant.create');
    Route::post('/variant/store', [ProductController::class, 'storeVariant'])->name('product.variant.store');
    Route::get('/variant/edit/{id}', [ProductController::class, 'editVariant'])->name('product.variant.edit');
    Route::post('/variant/update/{id}', [ProductController::class, 'updateVariant'])->name('product.variant.update');
    Route::get('/variant/image/delete/{id}', [ProductController::class, 'deleteVariantImage'])->name('product.variant.image.delete');
    Route::delete('/variant/delete/{id}', [ProductController::class, 'deleteVariant'])->name('product.variant.destroy');


    // Product Import/Export
    Route::get('/products/export', [ProductController::class, 'exportProducts'])->name('products.export');
    Route::get('/products/import', [ProductController::class, 'importProductsCsv'])->name('products.importcsv');
    Route::post('/products/import', [ProductController::class, 'importProducts'])->name('products.import');

    // Coupon Management
    Route::get('coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::get('coupons/create', [CouponController::class, 'create'])->name('coupons.create');
    Route::post('coupons/store', [CouponController::class, 'store'])->name('coupons.store');
    Route::get('coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
    Route::post('coupons/{coupon}/update', [CouponController::class, 'update'])->name('coupons.update');
    Route::post('coupons/{coupon}/delete', [CouponController::class, 'destroy'])->name('coupons.destroy');
    Route::post('coupons/{coupon}/restore', [CouponController::class, 'restore'])->name('coupons.restore');
    Route::get('coupons/deleted', [CouponController::class, 'deleted'])->name('coupons.deleted');
    Route::delete('coupons/{coupon}/force-delete', [CouponController::class, 'forceDelete'])->name('coupons.forceDelete');

    //Cart Management
    Route::get('cart', [VendorCartController::class, 'index'])->name('cart.index');
    Route::put('cart/{id}', [VendorCartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{id}', [VendorCartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/update', [VendorCartController::class, 'update'])->name('cart.update');
    Route::get('/cart/remove/{id}', [VendorCartController::class, 'remove'])->name('cart.remove');

    // Orders
    Route::get('order/list', [OrderController::class, 'orderlist'])->name('orderlist');
    Route::get('order/{orderId}/details', [OrderController::class, 'orderDetails'])->name('order.details');
    Route::put('order/{orderId}/update-status', [OrderController::class, 'updateOrderStatus'])->name('order.update.status');
    Route::get('order/{orderId}/download-invoice', [OrderController::class, 'downloadInvoice'])->name('order.download.invoice');

});

/*End Vendor Dashboard-------------------------------*/
