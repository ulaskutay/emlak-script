@extends('layouts.web')

@section('title', 'Ana Sayfa')
@section('subtitle', 'Emlak')
@section('description', 'Güvenilir emlak çözümleri, satılık ve kiralık ilanlar')

@section('content')
<!-- Intro Banner / Hero Section -->
<section class="relative {{ $intro_banner ?? null ? '' : 'bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800' }} text-white py-16 md:py-24 lg:py-32 overflow-hidden">
    @if($intro_banner ?? null)
    <!-- Background Banner Image -->
    <div class="absolute inset-0">
        <img src="{{ asset('storage/' . $intro_banner) }}" 
             alt="Giriş Banner" 
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    </div>
    @else
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml;utf8,<svg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"><g fill=\"none\" fill-rule=\"evenodd\"><g fill=\"%23ffffff\" fill-opacity=\"1\"><circle cx=\"30\" cy=\"30\" r=\"2\"/></g></svg>');"></div>
    </div>
    @endif
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            <!-- Left Side: Heading & Subtitle -->
            <div class="text-center lg:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 leading-tight">
                    @if(!empty($intro_title ?? ''))
                        {{ $intro_title }}
                    @else
                        Hayalinizdeki Evi
                        <span class="block text-primary-100">Bulun</span>
                    @endif
                </h1>
                <p class="text-lg md:text-xl text-primary-100 mb-6 leading-relaxed max-w-xl lg:max-w-none">
                    @php
                        // Get subtitle text (use default if empty)
                        $subtitleTemplate = !empty($intro_subtitle ?? '') 
                            ? $intro_subtitle 
                            : '{TOTAL_LISTINGS} aktif ilan ile size en uygun emlakı keşfedin. Profesyonel emlak danışmanlarımızla hayalinizdeki evi bulun.';
                        
                        // Replace {TOTAL_LISTINGS} placeholder with actual count
                        $subtitleText = str_replace('{TOTAL_LISTINGS}', $totalListings ?? 0, $subtitleTemplate);
                    @endphp
                    {{ $subtitleText }}
                </p>
            </div>
            
            <!-- Right Side: Search Form -->
            <div class="lg:pl-8">
                <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">İlan Ara</h2>
                    
                    <form action="{{ route('listings.public.index') }}" method="GET" class="space-y-4">
                        <!-- İlan Tipi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">İlan Tipi</label>
                            <select name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors bg-white">
                                <option value="">Tümü</option>
                                <option value="sale">Satılık</option>
                                <option value="rent">Kiralık</option>
                            </select>
                        </div>
                        
                        <!-- Şehir -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Şehir</label>
                            <select name="city" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors bg-white">
                                <option value="">Tüm Şehirler</option>
                                @foreach($citiesWithListings as $city)
                                    <option value="{{ $city->city }}">{{ $city->city }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Fiyat Aralığı -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Min Fiyat</label>
                                <input type="number" 
                                       name="min_price" 
                                       placeholder="Min" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Fiyat</label>
                                <input type="number" 
                                       name="max_price" 
                                       placeholder="Max" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            </div>
                        </div>
                        
                        <!-- Oda Sayısı -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Oda Sayısı</label>
                            <select name="rooms" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors bg-white">
                                <option value="">Tümü</option>
                                <option value="1">1+1</option>
                                <option value="2">2+1</option>
                                <option value="3">3+1</option>
                                <option value="4">4+1</option>
                                <option value="5">5+1</option>
                            </select>
                        </div>
                        
                        <!-- Ara Butonu -->
                        <button type="submit" class="w-full px-6 py-3.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                İlan Ara
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-12 bg-white border-b border-gray-200">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-3xl md:text-4xl font-bold text-primary-600 mb-2">{{ $totalListings }}</div>
                <div class="text-gray-600">Toplam İlan</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold text-primary-600 mb-2">{{ $saleCount }}</div>
                <div class="text-gray-600">Satılık</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold text-primary-600 mb-2">{{ $rentCount }}</div>
                <div class="text-gray-600">Kiralık</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold text-primary-600 mb-2">{{ $citiesWithListings->count() }}</div>
                <div class="text-gray-600">Şehir</div>
            </div>
        </div>
    </div>
</section>

<!-- Newly Listed Projects Section -->
@if($newlyListedProjects->count() > 0)
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8">Yeni Eklenen İlanlar</h2>
        
        <!-- Filter Buttons -->
        <div class="flex flex-wrap gap-3 mb-10">
            <a href="{{ route('home', ['filter_type' => 'all']) }}" 
               class="px-6 py-2.5 rounded-lg font-medium transition-colors {{ (!request('filter_type') || request('filter_type') === 'all') ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                Tümü
            </a>
            <a href="{{ route('home', ['filter_type' => 'rent']) }}" 
               class="px-6 py-2.5 rounded-lg font-medium transition-colors {{ request('filter_type') === 'rent' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                Kiralık
            </a>
            <a href="{{ route('home', ['filter_type' => 'sale']) }}" 
               class="px-6 py-2.5 rounded-lg font-medium transition-colors {{ request('filter_type') === 'sale' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                Satılık
            </a>
            <a href="{{ route('home', ['filter_type' => 'apartment']) }}" 
               class="px-6 py-2.5 rounded-lg font-medium transition-colors {{ request('filter_type') === 'apartment' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                Apartman
            </a>
            <a href="{{ route('home', ['filter_type' => 'office']) }}" 
               class="px-6 py-2.5 rounded-lg font-medium transition-colors {{ request('filter_type') === 'office' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                Ofis
            </a>
            <a href="{{ route('home', ['filter_type' => 'shop']) }}" 
               class="px-6 py-2.5 rounded-lg font-medium transition-colors {{ request('filter_type') === 'shop' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                Dükkan
            </a>
        </div>

        <!-- Listings Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($newlyListedProjects->take(3) as $listing)
                @include('web.home.partials.newly-listed-card', ['listing' => $listing])
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Listings Section -->
@if($featuredListings->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Öne Çıkan İlanlar</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">En popüler ve güncel emlak ilanlarını keşfedin</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredListings as $listing)
                <x-listing-card :listing="$listing" />
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('listings.public.index') }}" class="inline-block px-8 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-semibold">
                Tüm İlanları Görüntüle
            </a>
        </div>
    </div>
</section>
@endif

<!-- Latest Listings Section -->
@if($latestListings->count() > 0)
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Son Eklenen İlanlar</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">En yeni emlak ilanlarını kaçırmayın</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($latestListings as $listing)
                <x-listing-card :listing="$listing" />
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Cities Section -->
@if($citiesWithListings->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Şehirlere Göre İlanlar</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">İstediğiniz şehirdeki ilanları inceleyin</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach($citiesWithListings as $city)
                <a href="{{ route('listings.public.index', ['city' => $city->city]) }}" 
                   class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-all text-center group">
                    <div class="text-2xl font-bold text-gray-900 group-hover:text-primary-600 transition-colors mb-2">
                        {{ $city->city }}
                    </div>
                    <div class="text-sm text-gray-600">
                        {{ $city->count }} ilan
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-16 bg-primary-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Emlak İlanınızı Ekleyin</h2>
        <p class="text-xl text-primary-100 mb-8 max-w-2xl mx-auto">
            İlanınızı binlerce potansiyel alıcı ve kiracıyla buluşturun
        </p>
        @if($agency_phone ?? null)
        <a href="tel:{{ $agency_phone }}" class="inline-block px-8 py-3 bg-white text-primary-600 rounded-lg hover:bg-gray-100 transition-colors font-semibold">
            Bize Ulaşın
        </a>
        @endif
    </div>
</section>
@endsection

