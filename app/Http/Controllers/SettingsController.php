<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $settings = [
            'agency_name' => Setting::get('agency_name', 'EstateFlow'),
            'agency_logo' => Setting::get('agency_logo'),
            'agency_phone' => Setting::get('agency_phone'),
            'agency_whatsapp' => Setting::get('agency_whatsapp'),
            'default_currency' => Setting::get('default_currency', 'TRY'),
            'default_city' => Setting::get('default_city', 'İstanbul'),
            'email_notifications' => Setting::get('email_notifications', 'true'),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'agency_name' => 'required|string|max:255',
            'agency_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'agency_phone' => 'nullable|string|max:255',
            'agency_whatsapp' => 'nullable|string|max:255',
            'default_currency' => 'required|in:TRY,USD,EUR',
            'default_city' => 'required|string|max:255',
            'email_notifications' => 'nullable|boolean',
        ]);

        // Handle logo upload separately
        if ($request->hasFile('agency_logo')) {
            $oldLogo = Setting::get('agency_logo');
            
            // Delete old logo if exists
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            // Store new logo
            $path = $request->file('agency_logo')->store('settings', 'public');
            Setting::set('agency_logo', $path);
        }

        // Handle other settings
        foreach ($validated as $key => $value) {
            if ($key !== 'agency_logo') {
                Setting::set($key, $value === true ? 'true' : ($value === false ? 'false' : $value));
            }
        }

        return back()->with('success', 'Ayarlar başarıyla güncellendi.');
    }

    public function deleteLogo()
    {
        $logo = Setting::get('agency_logo');
        
        if ($logo && Storage::disk('public')->exists($logo)) {
            Storage::disk('public')->delete($logo);
        }
        
        Setting::set('agency_logo', null);
        
        return back()->with('success', 'Logo başarıyla silindi.');
    }
}

