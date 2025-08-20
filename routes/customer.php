  <?php
  use Illuminate\Support\Facades\Route;
  use App\Http\Controllers\Customer\CustomerController;
  use Illuminate\Support\Facades\Auth;
  use App\Http\Controllers\Website\AddressController;



  /*Customer Section-------------------------------*/
  // Route::get('/login', [CustomerController::class, 'login'])->name('login');
    Route::get("/loginbyphone", [
        CustomerController::class,
        "loginbyphone",
    ])->name("loginbyphone");
    // Route::get('/forgot', [CustomerController::class, 'forgot'])->name('forgot');
    // Route::get('/signup', [CustomerController::class, 'signup'])->name('signup');
    Route::get("/myaccount", [CustomerController::class, "myaccount"])->name(
        "myaccount"
    );
    /*EndCustomer Section-------------------------------*/

    Route::get("/signup", [CustomerController::class, "signup"])->name("signup");
    Route::post("/signup", [CustomerController::class, "signupStore"])->name(
        "signup.store"
    );
    Route::get("/login", [CustomerController::class, "login"])->name("login");
    Route::post("/login", [CustomerController::class, "loginStore"])->name(
        "login.store"
    );
    Route::get("/forgot", [CustomerController::class, "forgot"])->name("forgot");

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
        Route::get("/dashboard", [CustomerController::class, "myaccount"])->name(
            "customer.dashboard"
        );
        Route::post("/logout", function () {
            auth()->logout();
            return redirect()->route("login");
        })->name("logout");

        Route::get("/order-details/{order_id}", [
            CustomerController::class,
            "orderDetails",
        ])->name("customer.orderDetails");

        Route::get("/order-cancel/{orderNumber}", [
            CustomerController::class,
            "orderCancel",
        ])->name("order.cancel");

        Route::post("/order-cancel/{orderNumber}", [
            CustomerController::class,
            "processCancel",
        ])->name("order.cancel.process");


  });

