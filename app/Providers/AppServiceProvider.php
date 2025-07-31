<?php

namespace App\Providers;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('pages.frontend.components.navbar', function ($view) {
            $view->with('latestCategories', Category::latest()->take(4)->get());
        });
        Paginator::useBootstrap();
    }
}
