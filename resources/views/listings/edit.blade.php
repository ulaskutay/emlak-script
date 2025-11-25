@extends('layouts.app')

@section('title', 'İlan Düzenle')

@section('header-title', 'İlan Düzenle')

@section('content')
<div class="max-w-6xl mx-auto">
    <form action="{{ route('listings.update', $listing) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="bg-white rounded-lg shadow-lg">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-primary-50 to-primary-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">İlan Düzenle</h2>
                        <p class="text-sm text-gray-600">{{ $listing->title }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6 space-y-8">
                @include('listings.form-fields', ['listing' => $listing, 'agents' => $agents])
                
                <!-- Existing Video -->
                @if($listing->video_url || $listing->video_path)
                <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Mevcut Video</h3>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        @if($listing->video_path)
                            <video controls class="w-full max-w-md rounded-lg">
                                <source src="{{ asset('storage/' . $listing->video_path) }}" type="video/mp4">
                                Tarayıcınız video oynatmayı desteklemiyor.
                            </video>
                            <p class="text-xs text-gray-500 mt-2">{{ basename($listing->video_path) }}</p>
                        @elseif($listing->video_url)
                            <a href="{{ $listing->video_url }}" target="_blank" class="text-primary-600 hover:text-primary-800 font-medium flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                <span>{{ $listing->video_url }}</span>
                            </a>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Existing Photos -->
                @if($listing->photos->count() > 0)
                <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Mevcut Fotoğraflar</h3>
                        </div>
                        <span class="text-sm text-gray-500">{{ $listing->photos->count() }} fotoğraf</span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($listing->photos as $photo)
                        <div class="relative group">
                            @if($photo->exists())
                                <img src="{{ $photo->url }}" 
                                     alt="Photo" 
                                     class="w-full h-32 object-cover rounded-lg border-2 {{ $photo->is_cover ? 'border-primary-500' : 'border-gray-200' }}">
                            @else
                                <div class="w-full h-32 bg-gray-200 rounded-lg border-2 border-gray-200 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            @if($photo->is_cover)
                                <span class="absolute top-2 left-2 px-2 py-1 bg-primary-600 text-white text-xs font-medium rounded-lg shadow-sm">Kapak</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                <a href="{{ route('listings.index') }}" 
                   class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-white transition-colors font-medium text-gray-700">
                    İptal
                </a>
                <div class="flex items-center space-x-3">
                    <button type="submit" 
                            class="px-8 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium shadow-sm hover:shadow-md flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Değişiklikleri Kaydet</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

