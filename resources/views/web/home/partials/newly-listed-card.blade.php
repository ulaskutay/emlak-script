@php
    $coverPhoto = $listing->coverPhoto();
    $fullAddress = trim(($listing->district ?? '') . ' ' . ($listing->city ?? '') . ' ' . ($listing->address ?? ''));
    $agentUser = $listing->agent?->user;
@endphp

<div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
    <!-- Image Section -->
    <div class="relative h-64 overflow-hidden">
        @if($coverPhoto)
            <img src="{{ asset('storage/' . $coverPhoto->path) }}" 
                 alt="{{ $listing->title }}" 
                 class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        @endif
    </div>
    
    <!-- Content Section -->
    <div class="p-6">
        <!-- Title -->
        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
            <a href="{{ route('listings.public.show', $listing->slug) }}" class="hover:text-primary-600 transition-colors">
                {{ $listing->title }}
            </a>
        </h3>
        
        <!-- Location -->
        <div class="flex items-start gap-2 mb-3">
            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <p class="text-sm text-gray-600 flex-1 line-clamp-2">{{ $fullAddress }}</p>
        </div>
        
        <!-- Added Date -->
        <p class="text-xs text-gray-500 mb-4">
            @php
                $date = $listing->published_at ?? $listing->created_at;
                $months = [
                    1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
                    5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
                    9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
                ];
                $monthName = $months[$date->month] ?? $date->format('F');
            @endphp
            {{ $date->format('d') }} {{ $monthName }} {{ $date->format('Y') }}
        </p>
        
        <!-- Features -->
        <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-200">
            @if($listing->bedrooms)
            <div class="flex items-center gap-1.5">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-sm text-gray-700 font-medium">{{ $listing->bedrooms }}</span>
            </div>
            @endif
            
            @if($listing->bathrooms)
            <div class="flex items-center gap-1.5">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-sm text-gray-700 font-medium">{{ $listing->bathrooms }}</span>
            </div>
            @endif
            
            @if($listing->area_m2)
            <div class="flex items-center gap-1.5">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                </svg>
                <span class="text-sm text-gray-700 font-medium">{{ number_format($listing->area_m2, 0, ',', '.') }} m²</span>
            </div>
            @endif
        </div>
        
        <!-- Agent & Price -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                @if($agentUser)
                    @if($agentUser->avatar)
                        <img src="{{ asset('storage/' . $agentUser->avatar) }}" 
                             alt="{{ $agentUser->name }}" 
                             class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center">
                            <span class="text-primary-600 font-semibold text-xs">{{ strtoupper(substr($agentUser->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <span class="text-sm text-gray-700 font-medium">{{ $agentUser->name }}</span>
                @else
                    <span class="text-sm text-gray-500">Ofis</span>
                @endif
            </div>
            
            <div class="text-right">
                <div class="text-xl font-bold text-gray-900">{{ $listing->formatted_price }}</div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="grid grid-cols-4 gap-2 pt-4 border-t border-gray-200">
            <button class="flex flex-col items-center gap-1 p-2 text-primary-600 hover:bg-primary-50 rounded-lg transition-colors group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <span class="text-xs font-medium">Favori</span>
            </button>
            
            <button class="flex flex-col items-center gap-1 p-2 text-primary-600 hover:bg-primary-50 rounded-lg transition-colors group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                </svg>
                <span class="text-xs font-medium">Karşılaştır</span>
            </button>
            
            <a href="{{ route('listings.public.show', $listing->slug) }}" 
               class="flex flex-col items-center gap-1 p-2 text-primary-600 hover:bg-primary-50 rounded-lg transition-colors group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-xs font-medium">Resimler</span>
            </a>
            
            @if($listing->video_url || $listing->video_path)
            <a href="{{ route('listings.public.show', $listing->slug) }}#video" 
               class="flex flex-col items-center gap-1 p-2 text-primary-600 hover:bg-primary-50 rounded-lg transition-colors group">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                <span class="text-xs font-medium">Videolar</span>
            </a>
            @else
            <button disabled class="flex flex-col items-center gap-1 p-2 text-gray-300 rounded-lg cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                <span class="text-xs font-medium">Videolar</span>
            </button>
            @endif
        </div>
    </div>
</div>

