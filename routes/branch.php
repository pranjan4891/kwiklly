<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Branch\BranchController;
use App\Http\Controllers\Branch\DashboardController;
use App\Http\Controllers\Branch\ProductImagesController;
use App\Http\Controllers\Branch\ProductController;
use App\Http\Controllers\Branch\CouponController;
use App\Http\Controllers\Branch\CartController;
use App\Models\City;
use App\Models\MasterLocation;
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
Route::group(['prefix' => 'branch'], function () {

    // Guest Routes (Login)

        Route::get('/', [BranchController::class, 'index'])->name('branch.login');
        Route::post('/login', [BranchController::class, 'loginCheck'])->name('branch.loginCheck');


    // Authenticated Routes (Admin Panel)
    Route::middleware('auth:branch')->group(function () {

        // Admin Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('branch.dashboard');



        // Product Images Management
        Route::get('product-images', [ProductImagesController::class, 'index'])->name('branch.product.images');
        Route::post('product-images-store', [ProductImagesController::class, 'store'])->name('branch.product.images.store');
        Route::post('product-images/update/{id}', [ProductImagesController::class, 'update'])->name('branch.product.images.update');
        Route::get('product-images/delete/{id}', [ProductImagesController::class, 'softDelete'])->name('branch.product.images.delete');
        Route::post('product-image/delete-single', [ProductImagesController::class, 'deleteSingleImage'])->name('branch.product.image.delete.single');

        Route::get('product-images/deleted', [ProductImagesController::class, 'showDeletedImages'])->name('branch.product.images.deleted');
        Route::post('product-images/{id}/restore', [ProductImagesController::class, 'restore'])->name('branch.product.images.restore');
        Route::delete('product-images/{id}/erase', [ProductImagesController::class, 'erase'])->name('branch.product.images.erase');


        // Product Management
        Route::get('products', [ProductController::class, 'index'])->name('branch.products');
        Route::get('product/create', [ProductController::class, 'create'])->name('branch.product.create');
        Route::post('product/store', [ProductController::class, 'store'])->name('branch.product.store');
        Route::get('product/edit/{id}', [ProductController::class, 'edit'])->name('branch.product.edit');
        Route::post('product/{id}/edit', [ProductController::class, 'update'])->name('branch.product.update');
        Route::post('product/{id}/delete', [ProductController::class, 'destroy'])->name('branch.product.destroy');

        // Product Inventory Management
        Route::prefix('product')->group(function () {
            Route::get('/variant/create/{productId}', [ProductController::class, 'createVariant'])->name('branch.product.variant.create');
            Route::post('/variant/store', [ProductController::class, 'storeVariant'])->name('branch.product.variant.store');
            Route::get('/variant/edit/{id}', [ProductController::class, 'editVariant'])->name('branch.product.variant.edit');
            Route::post('/variant/update/{id}', [ProductController::class, 'updateVariant'])->name('branch.product.variant.update');
            Route::delete('/variant/delete/{id}', [ProductController::class, 'deleteVariant'])->name('branch.product.variant.destroy');
        });

        // Get Subcategories by Category
        Route::get('/get-subcategories', [ProductController::class, 'getByCategory'])->name('branch.getSubcategories');

        // Product Import/Export
        Route::get('/products/export', [ProductController::class, 'exportProducts'])->name('branch.products.export');
        Route::get('/products/import', [ProductController::class, 'importProductsCsv'])->name('branch.products.importcsv');
        Route::post('/products/import', [ProductController::class, 'importProducts'])->name('branch.products.import');


        // Profile Management
        Route::get('profile', [BranchController::class, 'profile'])->name('branch.profile');
        Route::post('profile/update', [BranchController::class, 'updateProfile'])->name('branch.profile.update');


        // Change Password
        Route::get('change-password', [BranchController::class, 'showChangePasswordForm'])->name('branch.password.change');
        Route::post('change-password', [BranchController::class, 'updatePassword'])->name('branch.password.update');

        // Cart Management
        Route::get('cart', [CartController::class, 'index'])->name('branch.cart.index');
        //Route::put('cart/{id}', [CartController::class, 'update'])->name('branch.cart.update');
        Route::delete('cart/{id}', [CartController::class, 'destroy'])->name('branch.cart.destroy');
        Route::post('/cart/update', [CartController::class, 'update'])->name('branch.cart.update');
        Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('branch.cart.remove');


        // Coupon Management
        Route::get('coupons', [CouponController::class, 'index'])->name('branch.coupons.index');
        Route::get('coupons/create', [CouponController::class, 'create'])->name('branch.coupons.create');
        Route::post('coupons/store', [CouponController::class, 'store'])->name('branch.coupons.store');
        Route::get('coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('branch.coupons.edit');
        Route::post('coupons/{coupon}/update', [CouponController::class, 'update'])->name('branch.coupons.update');
        Route::post('coupons/{coupon}/delete', [CouponController::class, 'destroy'])->name('branch.coupons.destroy');
        Route::post('coupons/{coupon}/restore', [CouponController::class, 'restore'])->name('branch.coupons.restore');
        Route::get('coupons/deleted', [CouponController::class, 'deleted'])->name('branch.coupons.deleted');
        Route::delete('coupons/{coupon}/force-delete', [CouponController::class, 'forceDelete'])->name('branch.coupons.forceDelete');

        // Logout
        Route::get('logout', [BranchController::class, 'logout'])->name('branch.logout');


    });



});






