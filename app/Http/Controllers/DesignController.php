<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DesignController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Convert PHP size string to bytes
     */
    private function convertToBytes($size)
    {
        $size = trim($size);
        $last = strtolower($size[strlen($size) - 1]);
        $size = (int)$size;
        
        switch ($last) {
            case 'g':
                $size *= 1024;
                // no break - fall through
            case 'm':
                $size *= 1024;
                // no break - fall through
            case 'k':
                $size *= 1024;
        }
        
        return $size;
    }

    public function header()
    {
        // Get header settings
        $headerLogo = Setting::get('header_logo');
        $agencyLogo = Setting::get('agency_logo'); // Fallback to main logo
        $headerSlogan = Setting::get('header_slogan');
        $agencyName = Setting::get('agency_name', 'EstateFlow');
        
        // Get menu settings (JSON)
        $menuSettings = json_decode(Setting::get('header_menus', '[]'), true);
        
        // Default menus if not set
        if (empty($menuSettings)) {
            $menuSettings = [
                [
                    'key' => 'home',
                    'label' => 'Ana Sayfa',
                    'route' => 'home',
                    'enabled' => true,
                    'order' => 1,
                ],
                [
                    'key' => 'listings',
                    'label' => 'İlanlar',
                    'route' => 'listings.public.index',
                    'enabled' => true,
                    'order' => 2,
                ],
                [
                    'key' => 'agents',
                    'label' => 'Emlakçılar',
                    'route' => 'agents.public',
                    'enabled' => true,
                    'order' => 3,
                ],
                [
                    'key' => 'about',
                    'label' => 'Hakkımızda',
                    'route' => 'about',
                    'enabled' => true,
                    'order' => 4,
                ],
                [
                    'key' => 'contact',
                    'label' => 'İletişim',
                    'route' => 'contact',
                    'enabled' => true,
                    'order' => 5,
                ],
            ];
        } else {
            // Check if 'home' and 'agents' menus exist, if not add them
            $hasHomeMenu = false;
            $hasAgentsMenu = false;
            
            foreach ($menuSettings as $menu) {
                if (isset($menu['key'])) {
                    if ($menu['key'] === 'home') {
                        $hasHomeMenu = true;
                    }
                    if ($menu['key'] === 'agents') {
                        $hasAgentsMenu = true;
                    }
                }
            }
            
            $needsUpdate = false;
            
            // Add home menu if missing
            if (!$hasHomeMenu) {
                array_unshift($menuSettings, [
                    'key' => 'home',
                    'label' => 'Ana Sayfa',
                    'route' => 'home',
                    'enabled' => true,
                    'order' => 1,
                ]);
                $needsUpdate = true;
            }
            
            // Add agents menu if missing
            if (!$hasAgentsMenu) {
                $agentsMenu = [
                    'key' => 'agents',
                    'label' => 'Emlakçılar',
                    'route' => 'agents.public',
                    'enabled' => true,
                    'order' => 3,
                ];
                
                // Find listings position and insert after it
                $inserted = false;
                $newMenuSettings = [];
                foreach ($menuSettings as $menu) {
                    $newMenuSettings[] = $menu;
                    if (isset($menu['key']) && $menu['key'] === 'listings' && !$inserted) {
                        $newMenuSettings[] = $agentsMenu;
                        $inserted = true;
                    }
                }
                
                if (!$inserted) {
                    $newMenuSettings[] = $agentsMenu;
                }
                
                $menuSettings = $newMenuSettings;
                $needsUpdate = true;
            }
            
            // Save updated menus to database automatically
            if ($needsUpdate) {
                Setting::set('header_menus', json_encode($menuSettings));
            }
        }

        // Sort by order
        usort($menuSettings, fn($a, $b) => ($a['order'] ?? 999) <=> ($b['order'] ?? 999));

        return view('design.header', compact('headerLogo', 'agencyLogo', 'menuSettings', 'headerSlogan', 'agencyName'));
    }

    public function updateHeader(Request $request)
    {
        $validated = $request->validate([
            'header_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'header_slogan' => 'nullable|string|max:255',
            'menus' => 'nullable|array',
            'menus.*.key' => 'required|string',
            'menus.*.label' => 'required|string|max:255',
            'menus.*.route' => 'required|string',
            'menus.*.enabled' => 'nullable|boolean',
            'menus.*.order' => 'nullable|integer',
        ]);

        // Handle header logo upload
        if ($request->hasFile('header_logo')) {
            $oldLogo = Setting::get('header_logo');
            
            // Delete old logo if exists
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            // Store new logo
            $path = $request->file('header_logo')->store('settings/header', 'public');
            Setting::set('header_logo', $path);
        }

        // Update header slogan
        if ($request->has('header_slogan')) {
            Setting::set('header_slogan', trim($request->input('header_slogan', '')));
        }

        // Update menu settings
        if ($request->has('menus')) {
            $menus = [];
            
            // Track if agents menu exists
            $hasAgents = false;
            
            // Add menus in the order they are submitted (after drag & drop)
            foreach ($request->menus as $index => $menu) {
                if (isset($menu['key']) && $menu['key'] === 'agents') {
                    $hasAgents = true;
                }
                $menus[] = [
                    'key' => $menu['key'],
                    'label' => $menu['label'],
                    'route' => $menu['route'],
                    'enabled' => isset($menu['enabled']) ? (bool)$menu['enabled'] : true,
                    'order' => $index + 1, // Order based on position
                ];
            }
            
            // If agents menu doesn't exist, add it
            if (!$hasAgents) {
                $agentsMenu = [
                    'key' => 'agents',
                    'label' => 'Emlakçılar',
                    'route' => 'agents.public',
                    'enabled' => true,
                    'order' => count($menus) + 1,
                ];
                $menus[] = $agentsMenu;
            }
            
            Setting::set('header_menus', json_encode($menus));
        }

        return back()->with('success', 'Header ayarları başarıyla güncellendi.');
    }

    public function deleteHeaderLogo()
    {
        $logo = Setting::get('header_logo');
        
        if ($logo && Storage::disk('public')->exists($logo)) {
            Storage::disk('public')->delete($logo);
        }
        
        Setting::set('header_logo', null);
        
        return back()->with('success', 'Header logosu başarıyla silindi.');
    }

    public function intro()
    {
        $introBanner = Setting::get('intro_banner');
        $introTitle = Setting::get('intro_title', 'Hayalinizdeki Evi Bulun');
        $introSubtitle = Setting::get('intro_subtitle', '');
        
        // Get PHP upload limits for display
        $uploadMaxSize = ini_get('upload_max_filesize');
        $postMaxSize = ini_get('post_max_size');
        $uploadMaxBytes = $this->convertToBytes($uploadMaxSize);
        $postMaxBytes = $this->convertToBytes($postMaxSize);
        $allowedMaxBytes = min($uploadMaxBytes, $postMaxBytes);
        $maxUploadMB = min(10, max(round($allowedMaxBytes / 1024 / 1024, 0), 1));

        return view('design.intro', compact(
            'introBanner', 
            'introTitle', 
            'introSubtitle',
            'maxUploadMB',
            'uploadMaxSize',
            'postMaxSize'
        ));
    }

    public function updateIntro(Request $request)
    {
        try {
            // Get current PHP upload limits for better error messages
            $uploadMaxSize = ini_get('upload_max_filesize');
            $postMaxSize = ini_get('post_max_size');
            
            // Convert to bytes for comparison
            $uploadMaxBytes = $this->convertToBytes($uploadMaxSize);
            $postMaxBytes = $this->convertToBytes($postMaxSize);
            $allowedMaxBytes = min($uploadMaxBytes, $postMaxBytes);
            $allowedMaxMB = max(round($allowedMaxBytes / 1024 / 1024, 0), 1); // Minimum 1MB
            
            // Set validation limit (10MB max, but respect PHP limits)
            $validationMaxKB = min(10240, (int)($allowedMaxBytes / 1024)); // Max 10MB or PHP limit, in KB
            
            // Validate request
            $validated = $request->validate([
                'intro_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:' . $validationMaxKB,
                'intro_title' => 'nullable|string|max:255',
                'intro_subtitle' => 'nullable|string|max:500',
            ], [
                'intro_banner.image' => 'Yüklenen dosya bir görsel olmalıdır. Maksimum dosya boyutu: ' . min(10, $allowedMaxMB) . 'MB',
                'intro_banner.mimes' => 'Yüklenen dosya şu formatlardan biri olmalıdır: jpeg, png, jpg, gif, webp. Maksimum dosya boyutu: ' . min(10, $allowedMaxMB) . 'MB',
                'intro_banner.max' => 'Yüklenen dosya maksimum ' . min(10, $allowedMaxMB) . 'MB boyutunda olmalıdır. (Mevcut PHP ayarları: upload_max_filesize=' . $uploadMaxSize . ', post_max_size=' . $postMaxSize . ')',
            ]);

            // Handle banner upload
            if ($request->hasFile('intro_banner')) {
                $file = $request->file('intro_banner');
                
                // Check if file is valid
                if (!$file->isValid()) {
                    return back()->with('error', 'Dosya geçersiz. Lütfen farklı bir dosya seçin.')->withInput();
                }
                
                try {
                    $oldBanner = Setting::get('intro_banner');
                    
                    // Delete old banner if exists
                    if ($oldBanner && Storage::disk('public')->exists($oldBanner)) {
                        Storage::disk('public')->delete($oldBanner);
                    }
                    
                    // Ensure directory exists
                    $directory = 'settings/intro';
                    $fullPath = storage_path('app/public/' . $directory);
                    if (!File::exists($fullPath)) {
                        File::makeDirectory($fullPath, 0755, true);
                    }
                    
                    // Store new banner
                    $path = $file->store($directory, 'public');
                    
                    if (!$path) {
                        \Log::error('Banner upload failed: path is empty');
                        return back()->with('error', 'Banner kaydedilemedi. Lütfen tekrar deneyin.')->withInput();
                    }
                    
                    // Verify file was saved
                    if (!Storage::disk('public')->exists($path)) {
                        \Log::error('Banner file not found after upload: ' . $path);
                        return back()->with('error', 'Banner dosyası kaydedilemedi. Lütfen tekrar deneyin.')->withInput();
                    }
                    
                    Setting::set('intro_banner', $path);
                    
                } catch (\Illuminate\Http\Exceptions\PostTooLargeException $e) {
                    $uploadMaxSize = ini_get('upload_max_filesize');
                    $postMaxSize = ini_get('post_max_size');
                    return back()->with('error', 'Dosya boyutu çok büyük. Maksimum yüklenebilir boyut: upload_max_filesize=' . $uploadMaxSize . ', post_max_size=' . $postMaxSize)->withInput();
                } catch (\Exception $e) {
                    \Log::error('Banner upload error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
                    return back()->with('error', 'Banner yüklenirken hata oluştu: ' . $e->getMessage())->withInput();
                }
            }

            // Update title and subtitle
            if ($request->has('intro_title')) {
                Setting::set('intro_title', trim($request->input('intro_title', '')));
            }

            if ($request->has('intro_subtitle')) {
                Setting::set('intro_subtitle', trim($request->input('intro_subtitle', '')));
            }

            return back()->with('success', 'Giriş ayarları başarıyla güncellendi.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Intro update error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Bir hata oluştu: ' . $e->getMessage())->withInput();
        }
    }

    public function deleteIntroBanner()
    {
        $banner = Setting::get('intro_banner');
        
        if ($banner && Storage::disk('public')->exists($banner)) {
            Storage::disk('public')->delete($banner);
        }
        
        Setting::set('intro_banner', null);
        
        return back()->with('success', 'Giriş banner\'ı başarıyla silindi.');
    }

    public function listings()
    {
        return view('design.listings');
    }
}

