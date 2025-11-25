<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $agency_name ?? 'EstateFlow') - @yield('subtitle', 'Emlak')</title>
    <meta name="description" content="@yield('description', 'Güvenilir emlak çözümleri')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ mobileMenuOpen: false, userMenuOpen: false }">
    <!-- Header -->
    <header class="bg-white sticky top-0 z-50 shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4">
            <div class="flex items-center h-20">
                <!-- Logo - Sol -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3 flex-shrink-0">
                    @if($header_logo ?? null)
                        <img src="{{ asset('storage/' . $header_logo) }}" 
                             alt="{{ $agency_name ?? 'EstateFlow' }}" 
                             class="h-10 w-auto object-contain">
                    @elseif($agency_logo ?? null)
                        <img src="{{ asset('storage/' . $agency_logo) }}" 
                             alt="{{ $agency_name ?? 'EstateFlow' }}" 
                             class="h-10 w-auto object-contain">
                    @else
                        <div class="h-10 w-10 bg-primary-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                    @endif
                    @if(!empty(trim($header_slogan ?? '')))
                        <div class="flex flex-col">
                            <span class="text-xl font-semibold text-gray-900 leading-tight tracking-tight">{{ $header_slogan }}</span>
                        </div>
                    @endif
                </a>

                <!-- Navigation Menu - Sağ (Right Aligned) -->
                <nav class="hidden lg:flex items-center space-x-8 flex-1 justify-end mx-8">
                    @foreach($header_menus ?? [] as $menu)
                        <a href="{{ route($menu['route']) }}" class="text-gray-900 hover:text-primary-600 transition-colors font-medium text-[15px] whitespace-nowrap {{ request()->routeIs($menu['route']) || request()->routeIs($menu['route'] . '.*') ? 'text-primary-600 font-semibold' : '' }}">
                            {{ $menu['label'] }}
                        </a>
                    @endforeach
                </nav>

                <!-- Buttons - Sağ -->
                <div class="hidden lg:flex items-center space-x-3 flex-shrink-0 ml-auto">
                    @if($agency_phone ?? null)
                    <a href="tel:{{ $agency_phone }}" class="flex items-center space-x-2 px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>Randevu Al</span>
                    </a>
                    @endif
                    @if($agency_whatsapp ?? null)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $agency_whatsapp) }}" target="_blank" class="px-5 py-2.5 border-2 border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors font-medium text-sm">
                        İletişim
                    </a>
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <div x-show="mobileMenuOpen" 
                 x-cloak
                 @click.away="mobileMenuOpen = false"
                 class="lg:hidden pb-4 border-t border-gray-200"
                 style="display: none;">
                <nav class="flex flex-col space-y-2 mt-4">
                    @foreach($header_menus ?? [] as $menu)
                        <a href="{{ route($menu['route']) }}" class="px-4 py-3 text-gray-900 hover:bg-gray-50 rounded-lg transition-colors font-medium text-[15px] {{ request()->routeIs($menu['route']) || request()->routeIs($menu['route'] . '.*') ? 'bg-primary-50 text-primary-600 font-semibold' : '' }}">
                            {{ $menu['label'] }}
                        </a>
                    @endforeach
                    @if($agency_phone ?? null)
                    <a href="tel:{{ $agency_phone }}" class="px-4 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center justify-center space-x-2 mt-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>Randevu Al</span>
                    </a>
                    @endif
                    @if($agency_whatsapp ?? null)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $agency_whatsapp) }}" target="_blank" class="px-4 py-3 border-2 border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors flex items-center justify-center space-x-2">
                        <span>WhatsApp İletişim</span>
                    </a>
                    @endif
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ $agency_name ?? 'EstateFlow' }}</h3>
                    <p class="text-gray-400 text-sm">Güvenilir emlak çözümleri için yanınızdayız.</p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider mb-4">Hızlı Linkler</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Ana Sayfa</a></li>
                        <li><a href="{{ route('listings.public.index') }}" class="text-gray-400 hover:text-white transition-colors text-sm">İlanlar</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Hakkımızda</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white transition-colors text-sm">İletişim</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider mb-4">İletişim</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        @if($agency_phone ?? null)
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>{{ $agency_phone }}</span>
                        </li>
                        @endif
                        @if($agency_whatsapp ?? null)
                        <li>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $agency_whatsapp) }}" target="_blank" class="hover:text-white transition-colors">
                                WhatsApp: {{ $agency_whatsapp }}
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>

                <!-- Social Media -->
                <div>
                    <h4 class="text-sm font-semibold uppercase tracking-wider mb-4">Takip Edin</h4>
                    <div class="flex space-x-4">
                        <!-- Social media links can be added here -->
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} {{ $agency_name ?? 'EstateFlow' }}. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>

