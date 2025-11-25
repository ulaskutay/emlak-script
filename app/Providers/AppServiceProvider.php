<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useTailwind();
        
        // Set Carbon locale to Turkish
        Carbon::setLocale('tr');
        
        // Share settings to all views
        View::composer('*', function ($view) {
            $view->with([
                'agency_name' => Setting::get('agency_name', 'EstateFlow'),
                'agency_logo' => Setting::get('agency_logo'),
            ]);
        });
    }
}

