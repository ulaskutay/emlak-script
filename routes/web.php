<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\Web\HomePageController;
use App\Http\Controllers\Web\PublicListingController;
use App\Http\Controllers\Web\PageController;
use Illuminate\Support\Facades\Route;

// Public Web Routes (must be before auth routes)
Route::get('/', [HomePageController::class, 'index'])->name('home');
Route::get('/ilanlar', [PublicListingController::class, 'index'])->name('listings.public.index');
Route::get('/ilanlar/{slug}', [PublicListingController::class, 'show'])->name('listings.public.show');
Route::get('/emlakçılar', [PageController::class, 'agents'])->name('agents.public');
Route::get('/hakkımızda', [PageController::class, 'about'])->name('about');
Route::get('/iletisim', [PageController::class, 'contact'])->name('contact');

// Authentication Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    Route::get('/panel', [DashboardController::class, 'index'])->name('panel');
    
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    
    Route::resource('listings', ListingController::class);
    
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/create', [InquiryController::class, 'create'])->name('inquiries.create');
    Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');
    Route::get('/inquiries/{inquiry}', [InquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('/inquiries/{inquiry}', [InquiryController::class, 'update'])->name('inquiries.update');
    
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    
    Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
    Route::get('/agents/{agent}', [AgentController::class, 'show'])->name('agents.show');
    
    Route::resource('calendar', CalendarController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::get('/calendar/day-events', [CalendarController::class, 'dayEvents'])->name('calendar.day-events');
    
    Route::middleware('admin')->group(function () {
        Route::delete('/settings/logo', [SettingsController::class, 'deleteLogo'])->name('settings.logo.delete');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
        
        // Design Routes
        Route::prefix('design')->name('design.')->group(function () {
            Route::get('/header', [DesignController::class, 'header'])->name('header');
            Route::patch('/header', [DesignController::class, 'updateHeader'])->name('header.update');
            Route::delete('/header/logo', [DesignController::class, 'deleteHeaderLogo'])->name('header.logo.delete');
            
            Route::get('/intro', [DesignController::class, 'intro'])->name('intro');
            Route::patch('/intro', [DesignController::class, 'updateIntro'])->name('intro.update');
            Route::delete('/intro/banner', [DesignController::class, 'deleteIntroBanner'])->name('intro.banner.delete');
            
            Route::get('/listings', [DesignController::class, 'listings'])->name('listings');
        });
    });
});


