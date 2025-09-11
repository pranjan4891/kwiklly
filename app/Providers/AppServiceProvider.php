<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     //
    // }
    public function boot(): void
    {
        // Share active categories globally
        view()->composer('*', function ($view) {
            // get ALL active, not-deleted categories (no "take" here)
            $categories = Category::where('is_active', 1)
                ->where('is_deleted', 0)
                ->orderBy('name') // or ->orderBy('id')
                ->get();

            $view->with('footerCategories', $categories);
        });
    }
}
