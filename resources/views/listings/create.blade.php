@extends('layouts.app')

@section('title', 'Yeni İlan Ekle')

@section('header-title', 'Yeni İlan Ekle')

@section('content')
<div class="max-w-6xl mx-auto">
    <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-lg shadow-lg">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-primary-50 to-primary-100">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Yeni İlan Oluştur</h2>
                        <p class="text-sm text-gray-600">Tüm bilgileri eksiksiz doldurun</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6 space-y-8">
                @include('listings.form-fields', ['listing' => null, 'agents' => $agents ?? collect()])
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
                        <span>İlanı Kaydet</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

