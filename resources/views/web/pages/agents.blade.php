@extends('layouts.web')

@section('title', 'Emlakçılar')
@section('subtitle', 'Emlak')

@section('content')
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Emlakçılarımız</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Deneyimli ve güvenilir emlak danışmanlarımızla tanışın</p>
        </div>

        @if($agents->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($agents as $agent)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow border border-gray-200 overflow-hidden">
                <!-- Agent Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-md">
                            {{ strtoupper(substr($agent->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $agent->user->name }}</h3>
                            <p class="text-sm text-gray-500 truncate">{{ $agent->user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Agent Info -->
                <div class="p-6 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Toplam İlan</span>
                        <span class="font-semibold text-gray-900">{{ $agent->listings_count ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Aktif İlan</span>
                        <span class="font-semibold text-primary-600">{{ $agent->active_listings_count ?? 0 }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($agents->hasPages())
        <div class="mt-12">
            {{ $agents->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Henüz emlakçı bulunmuyor</h3>
            <p class="text-gray-500">Sistemde kayıtlı emlakçı bulunmamaktadır.</p>
        </div>
        @endif
    </div>
</section>
@endsection

