@extends('layouts.app')

@section('title', 'Yeni İlan Ekle')

@section('header-title', 'Yeni İlan Ekle')

@section('content')
<form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="bg-white rounded-lg shadow p-6 space-y-6">
        @include('listings.form-fields', ['listing' => null, 'agents' => $agents ?? collect()])

        <!-- Buttons -->
        <div class="flex justify-end space-x-4 pt-4 border-t">
            <a href="{{ route('listings.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                İptal
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Kaydet
            </button>
        </div>
    </div>
</form>
@endsection

