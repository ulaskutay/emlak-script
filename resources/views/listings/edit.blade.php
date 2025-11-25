@extends('layouts.app')

@section('title', 'İlan Düzenle')

@section('header-title', 'İlan Düzenle')

@section('content')
<form action="{{ route('listings.update', $listing) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <div class="bg-white rounded-lg shadow p-6 space-y-6">
        <!-- Same form fields as create, but with values -->
        @include('listings.form-fields', ['listing' => $listing, 'agents' => $agents])
        
        <!-- Existing Photos -->
        @if($listing->photos->count() > 0)
        <div>
            <h3 class="text-lg font-semibold mb-4">Mevcut Fotoğraflar</h3>
            <div class="grid grid-cols-4 gap-4">
                @foreach($listing->photos as $photo)
                <div class="relative">
                    <img src="{{ asset('storage/' . $photo->path) }}" alt="Photo" class="w-full h-32 object-cover rounded">
                    @if($photo->is_cover)
                        <span class="absolute top-2 left-2 px-2 py-1 bg-blue-600 text-white text-xs rounded">Kapak</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Buttons -->
        <div class="flex justify-end space-x-4 pt-4 border-t">
            <a href="{{ route('listings.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                İptal
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Güncelle
            </button>
        </div>
    </div>
</form>
@endsection

