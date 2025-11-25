@extends('layouts.app')

@section('title', 'Ayarlar')

@section('header-title', 'Ayarlar')

@section('content')
<form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <div class="bg-white rounded-lg shadow p-6 space-y-6">
        <div>
            <h3 class="text-lg font-semibold mb-4">Genel Ayarlar</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Acente Adı</label>
                    <input type="text" name="agency_name" value="{{ $settings['agency_name'] }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Acente Logo</label>
                    <input type="file" name="agency_logo" accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefon</label>
                    <input type="text" name="agency_phone" value="{{ $settings['agency_phone'] }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                    <input type="text" name="agency_whatsapp" value="{{ $settings['agency_whatsapp'] }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Varsayılan Para Birimi</label>
                    <select name="default_currency" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="TRY" {{ $settings['default_currency'] === 'TRY' ? 'selected' : '' }}>TRY</option>
                        <option value="USD" {{ $settings['default_currency'] === 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ $settings['default_currency'] === 'EUR' ? 'selected' : '' }}>EUR</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Varsayılan Şehir</label>
                    <input type="text" name="default_city" value="{{ $settings['default_city'] }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="email_notifications" value="1" 
                               {{ $settings['email_notifications'] === 'true' ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Yeni talep geldiğinde e-posta gönder</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Kaydet
            </button>
        </div>
    </div>
</form>
@endsection

