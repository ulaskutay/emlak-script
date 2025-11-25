@php
    $listing = $listing ?? null;
@endphp
<!-- Genel Bilgiler -->
<div>
    <h3 class="text-lg font-semibold mb-4">Genel Bilgiler</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @if(auth()->user()->isAdmin() && isset($agents))
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Emlakçı</label>
            <select name="agent_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ ($listing && $listing->agent_id == $agent->id) ? 'selected' : '' }}>
                        {{ $agent->user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Başlık *</label>
            <input type="text" name="title" value="{{ old('title', $listing->title ?? '') }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Açıklama *</label>
            <textarea name="description" rows="4" required
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $listing->description ?? '') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tip *</label>
            <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="sale" {{ old('type', $listing->type ?? '') === 'sale' ? 'selected' : '' }}>Satılık</option>
                <option value="rent" {{ old('type', $listing->type ?? '') === 'rent' ? 'selected' : '' }}>Kiralık</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Durum</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="pending" {{ old('status', $listing->status ?? 'pending') === 'pending' ? 'selected' : '' }}>Beklemede</option>
                <option value="active" {{ old('status', $listing->status ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="passive" {{ old('status', $listing->status ?? '') === 'passive' ? 'selected' : '' }}>Pasif</option>
            </select>
        </div>
    </div>
</div>

<!-- Konum -->
<div>
    <h3 class="text-lg font-semibold mb-4">Konum</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Şehir *</label>
            <input type="text" name="city" value="{{ old('city', $listing->city ?? '') }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">İlçe</label>
            <input type="text" name="district" value="{{ old('district', $listing->district ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-1">Adres *</label>
            <input type="text" name="address" value="{{ old('address', $listing->address ?? '') }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
    </div>
</div>

<!-- Fiyat -->
<div>
    <h3 class="text-lg font-semibold mb-4">Fiyat</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fiyat *</label>
            <input type="number" name="price" step="0.01" value="{{ old('price', $listing->price ?? '') }}" required
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Para Birimi *</label>
            <select name="currency" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="TRY" {{ old('currency', $listing->currency ?? 'TRY') === 'TRY' ? 'selected' : '' }}>TRY</option>
                <option value="USD" {{ old('currency', $listing->currency ?? '') === 'USD' ? 'selected' : '' }}>USD</option>
                <option value="EUR" {{ old('currency', $listing->currency ?? '') === 'EUR' ? 'selected' : '' }}>EUR</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dönem</label>
            <select name="price_period" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Seçiniz</option>
                <option value="ay" {{ old('price_period', $listing->price_period ?? '') === 'ay' ? 'selected' : '' }}>Ay</option>
                <option value="yil" {{ old('price_period', $listing->price_period ?? '') === 'yil' ? 'selected' : '' }}>Yıl</option>
                <option value="tam" {{ old('price_period', $listing->price_period ?? '') === 'tam' ? 'selected' : '' }}>Tam</option>
            </select>
        </div>
    </div>
</div>

<!-- Özellikler -->
<div>
    <h3 class="text-lg font-semibold mb-4">Özellikler</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">m²</label>
            <input type="number" name="area_m2" value="{{ old('area_m2', $listing->area_m2 ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Yatak Odası</label>
            <input type="number" name="bedrooms" value="{{ old('bedrooms', $listing->bedrooms ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Banyo</label>
            <input type="number" name="bathrooms" value="{{ old('bathrooms', $listing->bathrooms ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kat</label>
            <input type="text" name="floor" value="{{ old('floor', $listing->floor ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Isıtma Tipi</label>
            <input type="text" name="heating_type" value="{{ old('heating_type', $listing->heating_type ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Etiketler (virgülle ayırın)</label>
            <input type="text" name="tags" value="{{ old('tags', $listing ? implode(',', $listing->tags ?? []) : '') }}" placeholder="Örn: Havuz, Balkon, Garaj"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="flex items-center space-x-2 mt-6">
                <input type="checkbox" name="furnished" value="1" {{ old('furnished', $listing->furnished ?? false) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="text-sm font-medium text-gray-700">Eşyalı</span>
            </label>
        </div>
    </div>
</div>

<!-- Medya -->
<div>
    <h3 class="text-lg font-semibold mb-4">Yeni Fotoğraflar Ekle</h3>
    <input type="file" name="photos[]" multiple accept="image/*"
           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
    <p class="mt-2 text-sm text-gray-500">Yeni fotoğraflar eklemek için seçin.</p>
</div>

