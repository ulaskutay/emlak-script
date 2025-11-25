<?php

namespace App\Providers;

use App\Models\Listing;
use App\Models\Inquiry;
use App\Policies\ListingPolicy;
use App\Policies\InquiryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Listing::class => ListingPolicy::class,
        Inquiry::class => InquiryPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}

