<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/statistics', function() {
        return view('statistics.index');
    })->name('statistics.index');
    
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
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
});

Route::get('/', function () {
    return redirect()->route('dashboard');
});

