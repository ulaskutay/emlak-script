<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
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
            // Get header menus
            $menuSettings = json_decode(Setting::get('header_menus', '[]'), true);
            if (empty($menuSettings)) {
                // Default menus (without 'home' as it's always added separately)
                $menuSettings = [
                    ['key' => 'listings', 'label' => 'İlanlar', 'route' => 'listings.public.index', 'enabled' => true, 'order' => 2],
                    ['key' => 'agents', 'label' => 'Emlakçılar', 'route' => 'agents.public', 'enabled' => true, 'order' => 3],
                    ['key' => 'about', 'label' => 'Hakkımızda', 'route' => 'about', 'enabled' => true, 'order' => 4],
                    ['key' => 'contact', 'label' => 'İletişim', 'route' => 'contact', 'enabled' => true, 'order' => 5],
                ];
            }
            // Sort by order and filter enabled
            usort($menuSettings, fn($a, $b) => ($a['order'] ?? 999) <=> ($b['order'] ?? 999));
            $finalMenus = array_filter($menuSettings, fn($menu) => ($menu['enabled'] ?? true));

            // Get logos (check existence but don't block if file check fails)
            $headerLogoPath = Setting::get('header_logo');
            $agencyLogoPath = Setting::get('agency_logo');
            
            // Set logo paths (we'll let the browser handle missing files)
            $headerLogo = !empty($headerLogoPath) ? $headerLogoPath : null;
            $agencyLogo = !empty($agencyLogoPath) ? $agencyLogoPath : null;

            // Get header slogan (empty if not set)
            $headerSlogan = Setting::get('header_slogan');
            $headerSloganText = !empty($headerSlogan) ? trim($headerSlogan) : '';
            $agencyName = Setting::get('agency_name', 'EstateFlow');

            // Get intro settings
            $introBanner = Setting::get('intro_banner');
            $introTitle = Setting::get('intro_title', 'Hayalinizdeki Evi Bulun');
            $introSubtitle = Setting::get('intro_subtitle', '');

            $view->with([
                'agency_name' => $agencyName,
                'agency_logo' => $agencyLogo,
                'header_logo' => $headerLogo,
                'header_slogan' => $headerSloganText,
                'agency_phone' => Setting::get('agency_phone'),
                'agency_whatsapp' => Setting::get('agency_whatsapp'),
                'header_menus' => $finalMenus,
                'intro_banner' => $introBanner,
                'intro_title' => $introTitle,
                'intro_subtitle' => $introSubtitle,
            ]);
        });
    }
}

