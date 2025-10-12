  <?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Customer\CustomerController;
    use App\Http\Controllers\Customer\GoogleController;
    use Illuminate\Support\Facades\Auth;
    use App\Http\Controllers\Website\AddressController;
    use App\Http\Controllers\Customer\FacebookController;

    /*StartCustomer Section-------------------------------*/
    Route::get("/login", [CustomerController::class, "login"])->name("login");
    Route::get("/loginbyphone", [CustomerController::class, "loginbyphone"])->name("loginbyphone");
    Route::get("/signup", [CustomerController::class, "signup"])->name("signup");
    Route::post("/otpsent", [CustomerController::class, "otpsent"])->name("otpsent");
    Route::post("/otpcheck", [CustomerController::class, "otpcheck"])->name("otpcheck");
    //resend otp
    Route::post("/resendotp", [CustomerController::class, "resendotp"])->name("resendotp");

    Route::get("/myaccount", [CustomerController::class, "myaccount"])->name("myaccount");
    /*EndCustomer Section-------------------------------*/

    Route::post("/signup", [CustomerController::class, "signupStore"])->name("signup.store");
    Route::post("/login", [CustomerController::class, "loginStore"])->name("login.store");

    // Google OAuth Routes
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google.redirect');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

    // Facebook OAuth Route
    Route::get('/auth/facebook', [FacebookController::class, 'redirectToFacebook'])->name('auth.facebook.login');
    Route::get('/auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);

    Route::get('/forgot-password', [CustomerController::class, 'showForgotPasswordForm'])->name('forgot.password.form');
    Route::post('/forgot-password', [CustomerController::class, 'sendResetLink'])->name('forgot.password.send');

    Route::get('/reset-password/{token}', [CustomerController::class, 'showResetPasswordForm'])->name('password.reset.form');
    Route::post('/reset-password', [CustomerController::class, 'resetPassword'])->name('password.reset');


    Route::post("/logout", function () {
        Auth::logout();
        request()
            ->session()
            ->invalidate();
        request()
            ->session()
            ->regenerateToken();
        return redirect()->route("login");
    })->name("logout");

    Route::middleware("auth")->group(function () {
        Route::get("/dashboard", [CustomerController::class, "myaccount"])->name("customer.dashboard");
        Route::get('/update-profile', [CustomerController::class, 'showUpdateProfile'])->name('update.profile');
        Route::post('user/update-profile', [CustomerController::class, 'saveUpdateProfile'])->name('update.profile.save');
        Route::get("/order-details/{order_id}", [CustomerController::class,"orderDetails"])->name("customer.orderDetails");
        Route::get("/order-cancel/{orderNumber}", [CustomerController::class,"orderCancel"])->name("order.cancel");
        Route::post("/order-cancel/{orderNumber}", [CustomerController::class,"processCancel"])->name("order.cancel.process");
        Route::get('address/{address}', [CustomerController::class, 'showAddress'])->name('address.show');
        Route::post('address/{address}', [CustomerController::class, 'updateAddress'])->name('address.update');
        Route::delete('address/{address}', [CustomerController::class, 'deleteAddress'])->name('address.delete');
        Route::post("/logout", function () {
            auth()->logout();
            return redirect()->route("login");
        })->name("logout");
    });
