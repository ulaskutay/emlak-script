@extends('layouts.app')

@section('title', 'Kontrol Paneli')

@section('header-title', 'Kontrol Paneli')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Toplam İlanlar -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Toplam İlanlar</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total_listings']['value']) }}</p>
                    <p class="text-sm text-green-600 mt-1">{{ $stats['total_listings']['change'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Aktif İlanlar -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Aktif İlanlar</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['active_listings']['value']) }}</p>
                    <p class="text-sm text-green-600 mt-1">{{ $stats['active_listings']['change'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Bu Ay Kiralanan / Satılan -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Bu Ay Kiralanan / Satılan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['this_month_sold_rented']['value']) }}</p>
                    <p class="text-sm text-green-600 mt-1">{{ $stats['this_month_sold_rented']['change'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <span class="text-2xl font-bold text-purple-600">₺</span>
                </div>
            </div>
        </div>

        <!-- Bugün Yeni Talepler -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Bugün Yeni Talepler</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['today_new_inquiries']['value']) }}</p>
                    <p class="text-sm text-green-600 mt-1">{{ $stats['today_new_inquiries']['change'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Listings -->
    <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Son Emlak İlanları</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fotoğraf</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Başlık & Adres</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tip</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fiyat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Şehir</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($latestListings as $listing)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $coverPhoto = $listing->coverPhoto();
                                @endphp
                                @if($coverPhoto)
                                    <img src="{{ asset('storage/' . $coverPhoto->path) }}" alt="{{ $listing->title }}" 
                                         class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $listing->title }}</div>
                                <div class="text-sm text-gray-500">{{ $listing->address }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $listing->type === 'sale' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $listing->type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if($listing->status === 'active') bg-green-100 text-green-800
                                    @elseif($listing->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($listing->status === 'sold') bg-blue-100 text-blue-800
                                    @elseif($listing->status === 'rented') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $listing->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $listing->formatted_price }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $listing->city }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('listings.edit', $listing) }}" class="text-blue-600 hover:text-blue-900">Düzenle</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Henüz ilan bulunmuyor.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    </div>

    <!-- Upcoming Calendar Events -->
    @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Yaklaşan Randevular</h3>
                <p class="text-sm text-gray-500 mt-1">{{ $upcomingEvents->count() }} randevu</p>
            </div>
            <a href="{{ route('calendar.index') }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                Tümünü Gör →
            </a>
        </div>
        
        <div class="divide-y divide-gray-200 max-h-[600px] overflow-y-auto">
            @foreach($upcomingEvents as $event)
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4 mb-3">
                            <!-- Date Badge -->
                            <div class="flex-shrink-0 bg-primary-100 rounded-lg px-4 py-2 text-center">
                                <div class="text-xs text-primary-600 font-medium uppercase">
                                    @php
                                        $monthNames = [
                                            1 => 'Oca', 2 => 'Şub', 3 => 'Mar', 4 => 'Nis',
                                            5 => 'May', 6 => 'Haz', 7 => 'Tem', 8 => 'Ağu',
                                            9 => 'Eyl', 10 => 'Eki', 11 => 'Kas', 12 => 'Ara'
                                        ];
                                        echo $monthNames[$event->start_at->month];
                                    @endphp
                                </div>
                                <div class="text-lg font-bold text-primary-700">{{ $event->start_at->day }}</div>
                            </div>
                            
                            <!-- Time Badge -->
                            <div class="flex-shrink-0 bg-blue-50 rounded-lg px-3 py-2">
                                <div class="text-sm font-semibold text-blue-700">{{ $event->start_at->format('H:i') }}</div>
                                <div class="text-xs text-blue-600">{{ $event->end_at->format('H:i') }}</div>
                            </div>
                            
                            <!-- Title -->
                            <div class="flex-1">
                                <h4 class="text-base font-semibold text-gray-900 mb-1">{{ $event->title }}</h4>
                                @if($event->start_at->isToday())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Bugün
                                    </span>
                                @elseif($event->start_at->isTomorrow())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Yarın
                                    </span>
                                @endif
                                @if(auth()->user()->isAdmin())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 ml-2">
                                        {{ $event->agent->user->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Event Details -->
                        <div class="ml-20 space-y-2">
                            @if($event->customer)
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-medium text-gray-700">Müşteri:</span>
                                <a href="{{ route('customers.show', $event->customer) }}" class="text-primary-600 hover:text-primary-800">
                                    {{ $event->customer->name }}
                                </a>
                            </div>
                            @endif
                            
                            @if($event->listing)
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span class="font-medium text-gray-700">İlan:</span>
                                <a href="{{ route('listings.edit', $event->listing) }}" class="text-primary-600 hover:text-primary-800">
                                    {{ \Illuminate\Support\Str::limit($event->listing->title, 50) }}
                                </a>
                                <span class="text-gray-400">•</span>
                                <span class="text-gray-500">{{ $event->listing->city }}</span>
                            </div>
                            @endif
                            
                            @if($event->note)
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg border-l-4 border-primary-500">
                                <p class="text-sm text-gray-700">{{ \Illuminate\Support\Str::limit($event->note, 100) }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="ml-4 flex items-center space-x-2">
                        <a href="{{ route('calendar.edit', $event) }}" 
                           class="px-3 py-1.5 text-sm bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 transition-colors">
                            Düzenle
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($upcomingEvents->count() >= 5)
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 text-center">
            <a href="{{ route('calendar.index') }}" class="text-sm text-primary-600 hover:text-primary-800 font-medium">
                Tüm randevuları görüntüle →
            </a>
        </div>
        @endif
    </div>
    @endif

    <!-- Top Agents -->
    @if(auth()->user()->isAdmin() && isset($topAgents) && $topAgents->count() > 0)
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Emlakçılar</h3>
        </div>
        <div class="p-6">
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @foreach($topAgents as $agent)
                <a href="{{ route('agents.show', $agent) }}" class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors group">
                    <div class="flex items-center space-x-4 flex-1 min-w-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                            {{ strtoupper(substr($agent->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate group-hover:text-primary-600 transition-colors">{{ $agent->user->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $agent->active_listings_count }} Aktif İlan</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

