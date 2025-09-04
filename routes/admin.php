<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\ProductImagesController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\MasterLocationController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CartController;
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
Route::group(['prefix' => 'admin'], function () {

    // Guest Routes (Login)

        Route::get('/', [AdminController::class, 'index'])->name('admin.login');
        Route::post('login', [AdminController::class, 'loginCheck'])->name('admin.loginCheck');


    // Authenticated Routes (Admin Panel)
    Route::middleware('auth:admin')->group(function () {

        // Admin Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Admin Vendor Management
        Route::get('/vendor/approve', [VendorController::class, 'approve'])->name('admin.vendor.approve');
        Route::get('/vendor/pending', [VendorController::class, 'pending'])->name('admin.vendor.pending');
        Route::get('/vendor/rejected', [VendorController::class, 'rejected'])->name('admin.vendor.rejected');
        Route::get('/vendor/{uuid}', [VendorController::class, 'show'])->name('admin.vendor.show');
        Route::put('/vendor/{uuid}/update-status', [VendorController::class, 'updateStatus'])->name('admin.vendor.updateStatus');



        // Attribute Management
        Route::get('/attributes', [AttributeController::class, 'index'])->name('admin.attributes');
        Route::post('/attributes/store', [AttributeController::class, 'store'])->name('admin.attribute.store');
        Route::post('/attributes/update/{id}', [AttributeController::class, 'update'])->name('admin.attribute.update');
        Route::get('/attributes/delete/{id}', [AttributeController::class, 'delete'])->name('admin.attribute.delete');

        // Attribute Value Management
        Route::get('/attribute-values', [AttributeController::class, 'indexAttributeValues'])->name('admin.attribute.values');
        Route::post('/attribute-values/store', [AttributeController::class, 'storeAttributeValue'])->name('admin.attribute.values.store');
        Route::post('/attribute-values/update/{id}', [AttributeController::class, 'updateAttributeValue'])->name('admin.attribute.value.update');
        Route::get('/attribute-values/delete/{id}', [AttributeController::class, 'softDeleteAttributeValue'])->name('admin.attribute.value.delete');

        //Banner Management
        Route::get('banner', [DashboardController::class, 'banner'])->name('admin.banner');
        Route::post('bannerstore', [DashboardController::class, 'bannerstore'])->name('admin.banner.store');
        Route::get('banners/delete/{id}', [DashboardController::class, 'bannerdestroy'])->name('admin.banners.delete');
        Route::post('banners/update/{id}', [DashboardController::class, 'bannerupdate'])->name('admin.banners.update');
        Route::get('deleted/banners', [DashboardController::class, 'deletedbanner'])->name('admin.banner.deleted');
        Route::delete('banner/force-delete/{id}', [DashboardController::class, 'forceDelete'])->name('admin.banners.forceDelete');


        // Category Management
        Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories');
        Route::get('category/create', [CategoryController::class, 'create'])->name('admin.category.create');
        Route::post('category/create', [CategoryController::class, 'store'])->name('admin.category.store');
        Route::get('category/{id}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
        Route::post('category/{id}/edit', [CategoryController::class, 'update'])->name('admin.category.update');
        Route::get('category/{id}/delete', [CategoryController::class, 'destroy'])->name('admin.category.delete');

        // add checkbox update is_home
        Route::post('category/{id}/toggle-home', [CategoryController::class, 'toggleHome'])->name('admin.category.toggleHome');

        // Subcategory Management
        Route::get('subcategories', [CategoryController::class, 'indexSubcategory'])->name('admin.subcategories');
        Route::post('subcategory-store', [CategoryController::class, 'storeSubcategory'])->name('admin.subcategory.store');
        Route::post('subcategory/update/{id}', [CategoryController::class, 'updateSubcategory'])->name('admin.subcategory.update');
        Route::post('subcategory/delete/{id}', [CategoryController::class, 'softDeleteSubcategory'])->name('admin.subcategory.delete');
        Route::get('subcategory/restore/{id}', [CategoryController::class, 'restoreSubcategory'])->name('admin.subcategory.restore');
        Route::get('subcategory/force-delete/{id}', [CategoryController::class, 'forceDeleteSubcategory'])->name('admin.subcategory.forceDelete');

        //get attribute values
       // Route::get('get-attribute-values', [CategoryController::class, 'getAttributeValues'])->name('admin.get.attribute.values');


        // Product Images Management
        Route::get('product-images', [ProductImagesController::class, 'index'])->name('admin.product.images');
        Route::post('product-images-store', [ProductImagesController::class, 'store'])->name('admin.product.images.store');
        Route::post('product-images/update/{id}', [ProductImagesController::class, 'update'])->name('admin.product.images.update');
        Route::get('product-images/delete/{id}', [ProductImagesController::class, 'softDelete'])->name('admin.product.images.delete');
        Route::post('product-image/delete-single', [ProductImagesController::class, 'deleteSingleImage'])->name('admin.product.image.delete.single');

        Route::get('product-images/deleted', [ProductImagesController::class, 'showDeletedImages'])->name('admin.product.images.deleted');
        Route::post('product-images/{id}/restore', [ProductImagesController::class, 'restore'])->name('admin.product.images.restore');
        Route::delete('product-images/{id}/erase', [ProductImagesController::class, 'erase'])->name('admin.product.images.erase');


        // Product Management
        Route::get('products', [ProductController::class, 'index'])->name('admin.products');
        Route::get('product/create', [ProductController::class, 'create'])->name('admin.product.create');
        Route::post('product/store', [ProductController::class, 'store'])->name('admin.product.store');
        Route::get('product/edit/{id}', [ProductController::class, 'edit'])->name('admin.product.edit');
        Route::post('product/{id}/edit', [ProductController::class, 'update'])->name('admin.product.update');
        Route::post('product/{id}/delete', [ProductController::class, 'destroy'])->name('admin.product.destroy');

        // Product Inventory Management
        Route::prefix('product')->group(function () {
            Route::get('/variant/create/{productId}', [ProductController::class, 'createVariant'])->name('product.variant.create');
            Route::post('/variant/store', [ProductController::class, 'storeVariant'])->name('product.variant.store');
            Route::get('/variant/edit/{id}', [ProductController::class, 'editVariant'])->name('product.variant.edit');
            Route::post('/variant/update/{id}', [ProductController::class, 'updateVariant'])->name('product.variant.update');
            Route::delete('/variant/delete/{id}', [ProductController::class, 'deleteVariant'])->name('product.variant.destroy');
        });

        // Get Subcategories by Category
        Route::get('/get-subcategories', [ProductController::class, 'getByCategory'])->name('admin.getSubcategories');

        // Product Import/Export
        Route::get('/products/export', [ProductController::class, 'exportProducts'])->name('admin.products.export');
        Route::get('/products/import', [ProductController::class, 'importProductsCsv'])->name('admin.products.importcsv');
        Route::post('/products/import', [ProductController::class, 'importProducts'])->name('admin.products.import');


        // Profile Management
        Route::get('profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::post('profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

        // Branch Management
        Route::get('branches', [AdminController::class, 'showBranches'])->name('admin.branches');
        Route::get('branch/create', [AdminController::class, 'createBranch'])->name('admin.branch.create');
        Route::post('branch/store', [AdminController::class, 'storeBranch'])->name('admin.branch.store');
        // Route::get('branch/edit/{id}', [AdminController::class, 'editBranch'])->name('admin.branch.edit');
        // Route::post('branch/update/{id}', [AdminController::class, 'updateBranch'])->name('admin.branch.update');
        Route::post('branch/delete/{id}', [AdminController::class, 'deleteBranch'])->name('admin.branch.delete');
        Route::get('branch/deleted', [AdminController::class, 'deletedBranches'])->name('admin.branch.deleted');
        Route::post('branch/restore/{id}', [AdminController::class, 'restoreBranch'])->name('admin.branch.restore');
        Route::delete('branch/destroy/{id}', [AdminController::class, 'destroyBranch'])->name('admin.branch.destroy');


        // Change Password
        Route::get('change-password', [AdminController::class, 'showChangePasswordForm'])->name('admin.password.change');
        Route::post('change-password', [AdminController::class, 'updatePassword'])->name('admin.password.update');

        // Logout
        Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');
    });

    // Location Management
    Route::prefix('location')->group(function () {
        Route::get('/', [MasterLocationController::class, 'index'])->name('admin.location');
        Route::get('/add', [MasterLocationController::class, 'createLocation'])->name('admin.location.create');
        Route::post('/store', [MasterLocationController::class, 'storeLocation'])->name('admin.location.store');
        Route::get('/edit/{id}', [MasterLocationController::class, 'editLocation'])->name('admin.location.edit');
        Route::post('/update/{id}', [MasterLocationController::class, 'updateLocation'])->name('admin.location.update');
        Route::post('/delete/{id}', [MasterLocationController::class, 'deleteLocation'])->name('admin.location.delete');
        Route::get('/deleted', [MasterLocationController::class, 'deletedLocations'])->name('admin.location.deleted');
        Route::post('/restore/{id}', [MasterLocationController::class, 'restoreLocation'])->name('admin.location.restore');
        Route::delete('/destroy/{id}', [MasterLocationController::class, 'destroyLocation'])->name('admin.location.destroy');
    });

    // Cart Management
    Route::get('cart', [CartController::class, 'index'])->name('admin.cart.index');
   // Route::put('cart/{id}', [CartController::class, 'update'])->name('admin.cart.update');
    Route::delete('cart/{id}', [CartController::class, 'destroy'])->name('admin.cart.destroy');
    Route::post('/cart/update', [CartController::class, 'update'])->name('admin.cart.update');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('admin.cart.remove');


    // Coupon Management
    Route::get('coupons', [CouponController::class, 'index'])->name('admin.coupons.index');
    Route::get('coupons/create', [CouponController::class, 'create'])->name('admin.coupons.create');
    Route::post('coupons/store', [CouponController::class, 'store'])->name('admin.coupons.store');
    Route::get('coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('admin.coupons.edit');
    Route::post('coupons/{coupon}/update', [CouponController::class, 'update'])->name('admin.coupons.update');
    Route::post('coupons/{coupon}/delete', [CouponController::class, 'destroy'])->name('admin.coupons.destroy');
    Route::post('coupons/{coupon}/restore', [CouponController::class, 'restore'])->name('admin.coupons.restore');
    Route::get('coupons/deleted', [CouponController::class, 'deleted'])->name('admin.coupons.deleted');
    Route::delete('coupons/{coupon}/force-delete', [CouponController::class, 'forceDelete'])->name('admin.coupons.forceDelete');


    // Social Links Management
    Route::get('social-links', [AdminController::class, 'socialLinks'])->name('admin.social.links');


});

    Route::get('/admin/get-area', [AdminController::class, 'getArea'])->name('admin.get.area');
    // Get Cities by State
    Route::get('/get-cities/{state_id}', function ($state_id) {
        return City::where('state_id', $state_id)->get();
    })->name('admin.get.cities');
    //Export Category & Subcategory
    Route::get('/export/categories', [CategoryController::class, 'exportCategories'])->name('admin.export.categories');
    Route::get('/export/subcategories', [CategoryController::class, 'exportSubcategories'])->name('admin.export.subcategories');
    Route::get('download/sample-csv', function () {
        $file = public_path('sample.csv');
        return response()->download($file, 'sample.csv', [
            'Content-Type' => 'text/csv',
        ]);
    })->name('admin.sample.csv');





