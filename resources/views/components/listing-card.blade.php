@props(['listing'])

<a href="{{ route('listings.public.show', $listing->slug) }}" class="group block bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
    <!-- Image -->
    <div class="relative h-48 bg-gray-200 overflow-hidden">
        @if($listing->coverPhoto())
            <img src="{{ asset('storage/' . $listing->coverPhoto()->path) }}" 
                 alt="{{ $listing->title }}" 
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        @endif
        
        <!-- Badge -->
        <div class="absolute top-3 left-3">
            <span class="px-3 py-1 bg-primary-600 text-white text-xs font-semibold rounded-full">
                {{ $listing->type === 'sale' ? 'Satılık' : 'Kiralık' }}
            </span>
        </div>

        <!-- Photo Count -->
        @if($listing->photos->count() > 0)
        <div class="absolute top-3 right-3">
            <span class="px-2 py-1 bg-black bg-opacity-50 text-white text-xs rounded flex items-center space-x-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ $listing->photos->count() }}</span>
            </span>
        </div>
        @endif
    </div>

    <!-- Content -->
    <div class="p-4">
        <!-- Price -->
        <div class="mb-2">
            <span class="text-2xl font-bold text-gray-900">
                {{ number_format($listing->price, 0, ',', '.') }} 
                <span class="text-sm font-normal">{{ $listing->currency }}</span>
            </span>
            @if($listing->price_period)
                <span class="text-sm text-gray-500">/{{ $listing->price_period === 'ay' ? 'ay' : ($listing->price_period === 'yil' ? 'yıl' : 'tam') }}</span>
            @endif
        </div>

        <!-- Title -->
        <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors line-clamp-2">
            {{ $listing->title }}
        </h3>

        <!-- Location -->
        <div class="flex items-center text-gray-600 text-sm mb-3">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>{{ $listing->district ? $listing->district . ', ' : '' }}{{ $listing->city }}</span>
        </div>

        <!-- Features -->
        <div class="flex flex-wrap gap-3 text-sm text-gray-600 border-t border-gray-200 pt-3">
            @if($listing->area_m2)
            <div class="flex items-center space-x-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                </svg>
                <span>{{ $listing->area_m2 }} m²</span>
            </div>
            @endif
            
            @if($listing->bedrooms)
            <div class="flex items-center space-x-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>{{ $listing->bedrooms }} Yatak</span>
            </div>
            @endif

            @if($listing->bathrooms)
            <div class="flex items-center space-x-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                </svg>
                <span>{{ $listing->bathrooms }} Banyo</span>
            </div>
            @endif
        </div>
    </div>
</a>

