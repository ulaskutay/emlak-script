@php
    $listing = $listing ?? null;
@endphp
<!-- Genel Bilgiler -->
<div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
    <div class="flex items-center space-x-2 mb-6">
        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900">Genel Bilgiler</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @if(auth()->user()->isAdmin() && isset($agents))
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Emlakçı / Ofis</label>
            <select name="agent_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                <option value="" {{ (!$listing || !$listing->agent_id) ? 'selected' : (old('agent_id') === '' ? 'selected' : '') }}>Ofis</option>
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ ($listing && $listing->agent_id == $agent->id) ? 'selected' : (old('agent_id') == $agent->id ? 'selected' : '') }}>
                        {{ $agent->user->name }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500">Ofis olarak kaydetmek için "Ofis" seçeneğini seçin</p>
        </div>
        @endif
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Başlık <span class="text-red-500">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title', $listing->title ?? '') }}" required
                   placeholder="Örn: Deniz Manzaralı Lüks Daire"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Açıklama <span class="text-red-500">*</span>
            </label>
            <textarea name="description" rows="5" required
                      placeholder="İlan açıklamasını detaylı bir şekilde yazın..."
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">{{ old('description', $listing->description ?? '') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Tip <span class="text-red-500">*</span>
            </label>
            <select name="type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                <option value="sale" {{ old('type', $listing->type ?? '') === 'sale' ? 'selected' : '' }}>Satılık</option>
                <option value="rent" {{ old('type', $listing->type ?? '') === 'rent' ? 'selected' : '' }}>Kiralık</option>
            </select>
            @error('type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
            <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                <option value="pending" {{ old('status', $listing->status ?? 'pending') === 'pending' ? 'selected' : '' }}>Beklemede</option>
                <option value="active" {{ old('status', $listing->status ?? '') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="passive" {{ old('status', $listing->status ?? '') === 'passive' ? 'selected' : '' }}>Pasif</option>
            </select>
        </div>
    </div>
</div>

<!-- Konum -->
<div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
    <div class="flex items-center space-x-2 mb-6">
        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900">Konum Bilgileri</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Şehir <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="city" 
                   name="city" 
                   value="{{ old('city', $listing->city ?? '') }}" 
                   required
                   placeholder="Örn: İstanbul"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
            @error('city')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">İlçe</label>
            <input type="text" 
                   id="district" 
                   name="district" 
                   value="{{ old('district', $listing->district ?? '') }}"
                   placeholder="Örn: Kadıköy"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
        </div>
        <div class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Adres <span class="text-red-500">*</span>
            </label>
            <div class="space-y-3">
                <div class="relative">
                    <input type="text" 
                           id="address" 
                           name="address" 
                           value="{{ old('address', $listing->address ?? '') }}" 
                           required
                           autocomplete="off"
                           placeholder="Adres yazmaya başlayın..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                    <!-- Address Suggestions Dropdown -->
                    <div id="addressSuggestions" class="hidden absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        <!-- Suggestions will be populated here -->
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <button type="button" 
                            id="showMapBtn"
                            onclick="toggleMap()"
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors text-sm font-medium flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        <span id="mapBtnText">Haritada Göster</span>
                    </button>
                    <label class="flex items-center space-x-3 p-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                        <input type="checkbox" 
                               name="show_on_web" 
                               value="1" 
                               {{ old('show_on_web', $listing->show_on_web ?? false) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <div>
                            <span class="block text-sm font-medium text-gray-900">Haritayı İlanda Göster</span>
                            <span class="text-xs text-gray-500">Haritayı ilan detay sayfasında göster</span>
                        </div>
                    </label>
                </div>
                <div id="mapContainer" class="hidden">
                    <div id="map" class="w-full h-96 rounded-lg border border-gray-300"></div>
                    <div class="mt-2 flex items-center space-x-2 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Haritada konumu seçmek için tıklayın veya adresi girin</span>
                    </div>
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $listing->latitude ?? '') }}">
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $listing->longitude ?? '') }}">
                </div>
            </div>
            @error('address')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map;
    let marker;
    let mapVisible = false;
    let addressTimeout;
    let selectedSuggestion = false;
    let initialLat = {{ old('latitude', $listing->latitude ?? '41.0082') }};
    let initialLng = {{ old('longitude', $listing->longitude ?? '28.9784') }};
    
    // Türkiye'nin büyükşehir listesi (şehir tespiti için)
    const majorCities = ['İstanbul', 'Ankara', 'İzmir', 'Bursa', 'Antalya', 'Adana', 'Konya', 'Gaziantep', 'Mersin', 'Diyarbakır', 'Kayseri', 'Eskişehir', 'Urfa', 'Malatya', 'Trabzon', 'Samsun', 'Kahramanmaraş', 'Van', 'Denizli', 'Batman', 'Elazığ', 'Erzurum', 'Kocaeli', 'Şanlıurfa', 'Manisa', 'Sakarya', 'Muğla', 'Balıkesir', 'Aydın', 'Tekirdağ'];

    function toggleMap() {
        const container = document.getElementById('mapContainer');
        const btnText = document.getElementById('mapBtnText');
        
        if (!mapVisible) {
            container.classList.remove('hidden');
            btnText.textContent = 'Haritayı Gizle';
            mapVisible = true;
            
            if (!map) {
                initMap();
            }
        } else {
            container.classList.add('hidden');
            btnText.textContent = 'Haritada Göster';
            mapVisible = false;
        }
    }

    function initMap() {
        const addressInput = document.getElementById('address');
        
        // Initialize Leaflet map
        if (!map) {
            map = L.map('map').setView([initialLat, initialLng], 13);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);
            
            // Add initial marker if coordinates exist
            if (initialLat && initialLng && initialLat !== 41.0082 && initialLng !== 28.9784) {
                marker = L.marker([initialLat, initialLng]).addTo(map);
                map.setView([initialLat, initialLng], 15);
            }
            
            // Handle map click
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }
                
                // Reverse geocode
                reverseGeocode(lat, lng);
            });
        }
    }
    
    // Address Autocomplete with Nominatim
    document.addEventListener('DOMContentLoaded', function() {
        const addressInput = document.getElementById('address');
        const suggestionsDropdown = document.getElementById('addressSuggestions');
        
        if (!addressInput) return;
        
        addressInput.addEventListener('input', function() {
            const address = this.value.trim();
            
            if (address.length < 3) {
                if (suggestionsDropdown) {
                    suggestionsDropdown.classList.add('hidden');
                }
                return;
            }
            
            if (selectedSuggestion) {
                selectedSuggestion = false;
                return;
            }
            
            clearTimeout(addressTimeout);
            addressTimeout = setTimeout(() => {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=5&countrycodes=tr&addressdetails=1&accept-language=tr`, {
                    headers: {
                        'User-Agent': 'EstateFlow App'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        displaySuggestions(data);
                    } else {
                        if (suggestionsDropdown) {
                            suggestionsDropdown.classList.add('hidden');
                        }
                    }
                })
                .catch(error => {
                    console.error('Address search error:', error);
                    if (suggestionsDropdown) {
                        suggestionsDropdown.classList.add('hidden');
                    }
                });
            }, 300);
        });
        
        function displaySuggestions(suggestions) {
            if (!suggestionsDropdown) return;
            
            suggestionsDropdown.innerHTML = '';
            suggestions.forEach((item) => {
                const div = document.createElement('div');
                div.className = 'px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0';
                div.innerHTML = `
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">${item.display_name}</p>
                        </div>
                    </div>
                `;
                div.addEventListener('click', () => {
                    selectAddress(item);
                });
                suggestionsDropdown.appendChild(div);
            });
            suggestionsDropdown.classList.remove('hidden');
        }
        
        function selectAddress(item) {
            selectedSuggestion = true;
            
            addressInput.value = item.display_name;
            
            // Parse address components
            if (item.address) {
                parseNominatimAddress(item.address, item.display_name);
            }
            
            // Fill coordinates
            const lat = parseFloat(item.lat);
            const lng = parseFloat(item.lon);
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            initialLat = lat;
            initialLng = lng;
            
            // Update map
            if (map) {
                if (marker) {
                    marker.setLatLng([lat, lng]);
                } else {
                    marker = L.marker([lat, lng]).addTo(map);
                }
                map.setView([lat, lng], 15);
            }
            
            if (suggestionsDropdown) {
                suggestionsDropdown.classList.add('hidden');
            }
        }
        
        // Close suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (suggestionsDropdown && !addressInput.contains(e.target) && !suggestionsDropdown.contains(e.target)) {
                suggestionsDropdown.classList.add('hidden');
            }
        });
    });
    
    function parseNominatimAddress(address, displayName) {
        const cityInput = document.getElementById('city');
        const districtInput = document.getElementById('district');
        
        let city = '';
        let district = '';
        
        // Nominatim address structure for Turkey:
        // state: "Türkiye"
        // city: Büyükşehir belediyesi (İstanbul, Ankara, etc.) VEYA ilçe (yanlış olabilir)
        // county: İlçe (Pendik, Kadıköy, etc.)
        // town: Bazen ilçe, bazen şehir
        // municipality: Belediye
        
        // Strateji: Büyükşehir listesini kullanarak city'nin gerçekten şehir olup olmadığını kontrol et
        
        // Önce county'yi ilçe olarak al
        if (address.county) {
            district = address.county;
        }
        
        // Şehir tespiti - öncelik sırası
        // 1. Eğer city varsa ve büyükşehir listesindeyse -> şehir
        if (address.city && majorCities.includes(address.city)) {
            city = address.city;
        }
        // 2. Eğer city büyükşehir değilse ama county varsa -> city muhtemelen yanlış, display_name'den parse et
        else if (address.city && address.county && address.city !== address.county) {
            // City ile county farklıysa, city şehir olabilir (ama büyükşehir listesinde yoksa kontrol et)
            // Display name'den parse etmeye çalış
            const parts = displayName.split(',');
            for (let part of parts) {
                const trimmed = part.trim();
                if (majorCities.includes(trimmed)) {
                    city = trimmed;
                    break;
                }
            }
            // Eğer display_name'den bulamadıysak, city'yi kullan ama şüpheli
            if (!city && address.city.length > 3) {
                // İlçe isimleri genellikle daha kısa veya farklı yapıda olur
                // Büyük şehir isimleri genellikle daha uzun veya belirli desenleri takip eder
                city = address.city;
            }
        }
        // 3. Eğer state_district varsa
        else if (address.state_district) {
            city = address.state_district;
        }
        // 4. Eğer town varsa ve county yoksa
        else if (address.town && !address.county) {
            if (majorCities.includes(address.town)) {
                city = address.town;
            }
        }
        
        // Display name'den şehir bulmaya çalış (son çare)
        if (!city || !majorCities.includes(city)) {
            const parts = displayName.split(',');
            for (let part of parts) {
                const trimmed = part.trim();
                if (majorCities.includes(trimmed)) {
                    city = trimmed;
                    break;
                }
            }
        }
        
        // Eğer county city ile aynıysa veya city büyükşehir değilse, düzelt
        if (city && district && city === district) {
            // City ile district aynıysa, muhtemelen city yanlış
            // Display name'den şehir bulmaya çalış
            const parts = displayName.split(',');
            for (let part of parts) {
                const trimmed = part.trim();
                if (majorCities.includes(trimmed) && trimmed !== district) {
                    city = trimmed;
                    break;
                }
            }
        }
        
        // Eğer hala city yoksa ama district varsa, district muhtemelen ilçe, şehir başka bir yerde
        if (!city && district) {
            // Display name'den şehir bul
            const parts = displayName.split(',');
            for (let part of parts) {
                const trimmed = part.trim();
                if (majorCities.includes(trimmed)) {
                    city = trimmed;
                    break;
                }
            }
        }
        
        // Update inputs
        if (cityInput && city) {
            cityInput.value = city;
        }
        if (districtInput && district) {
            districtInput.value = district;
        }
    }
    
    function reverseGeocode(lat, lng) {
        const addressInput = document.getElementById('address');
        
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&accept-language=tr`, {
            headers: {
                'User-Agent': 'EstateFlow App'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                addressInput.value = data.display_name;
                if (data.address) {
                    parseNominatimAddress(data.address, data.display_name);
                }
            }
        })
        .catch(error => {
            console.error('Reverse geocoding error:', error);
        });
    }
</script>
@endpush

<!-- Fiyat -->
<div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
    <div class="flex items-center space-x-2 mb-6">
        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900">Fiyat Bilgileri</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Fiyat <span class="text-red-500">*</span>
            </label>
            <input type="number" name="price" step="0.01" value="{{ old('price', $listing->price ?? '') }}" required
                   placeholder="0.00"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
            @error('price')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Para Birimi <span class="text-red-500">*</span>
            </label>
            <select name="currency" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                <option value="TRY" {{ old('currency', $listing->currency ?? 'TRY') === 'TRY' ? 'selected' : '' }}>₺ TRY</option>
                <option value="USD" {{ old('currency', $listing->currency ?? '') === 'USD' ? 'selected' : '' }}>$ USD</option>
                <option value="EUR" {{ old('currency', $listing->currency ?? '') === 'EUR' ? 'selected' : '' }}>€ EUR</option>
            </select>
            @error('currency')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Dönem</label>
            <select name="price_period" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                <option value="">Seçiniz</option>
                <option value="ay" {{ old('price_period', $listing->price_period ?? '') === 'ay' ? 'selected' : '' }}>Ay</option>
                <option value="yil" {{ old('price_period', $listing->price_period ?? '') === 'yil' ? 'selected' : '' }}>Yıl</option>
                <option value="tam" {{ old('price_period', $listing->price_period ?? '') === 'tam' ? 'selected' : '' }}>Tam</option>
            </select>
        </div>
    </div>
</div>

<!-- Özellikler -->
<div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
    <div class="flex items-center space-x-2 mb-6">
        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900">Emlak Özellikleri</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <!-- Alan ve Oda Bilgileri -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Alan (m²)</label>
            <input type="number" name="area_m2" value="{{ old('area_m2', $listing->area_m2 ?? '') }}"
                   placeholder="0"
                   min="0"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Yatak Odası</label>
            <input type="number" name="bedrooms" value="{{ old('bedrooms', $listing->bedrooms ?? '') }}"
                   placeholder="0"
                   min="0"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Salon Sayısı</label>
            <input type="number" name="living_rooms" value="{{ old('living_rooms', $listing->living_rooms ?? '') }}"
                   placeholder="0"
                   min="0"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Toplam Oda</label>
            <input type="number" name="total_rooms" value="{{ old('total_rooms', $listing->total_rooms ?? '') }}"
                   placeholder="0"
                   min="0"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Banyo</label>
            <input type="number" name="bathrooms" value="{{ old('bathrooms', $listing->bathrooms ?? '') }}"
                   placeholder="0"
                   min="0"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Balkon Sayısı</label>
            <input type="number" name="balconies" value="{{ old('balconies', $listing->balconies ?? '') }}"
                   placeholder="0"
                   min="0"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
        </div>
        
        <!-- Bina Bilgileri -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kat</label>
            <input type="text" name="floor" value="{{ old('floor', $listing->floor ?? '') }}"
                   placeholder="Örn: 3. Kat"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Toplam Kat Sayısı</label>
            <input type="number" name="total_floors" value="{{ old('total_floors', $listing->total_floors ?? '') }}"
                   placeholder="0"
                   min="0"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Bina Yaşı</label>
            <input type="number" name="building_age" value="{{ old('building_age', $listing->building_age ?? '') }}"
                   placeholder="0"
                   min="0"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Bina Tipi</label>
            <select name="building_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                <option value="">Seçiniz</option>
                <option value="apartman" {{ old('building_type', $listing->building_type ?? '') === 'apartman' ? 'selected' : '' }}>Apartman</option>
                <option value="villa" {{ old('building_type', $listing->building_type ?? '') === 'villa' ? 'selected' : '' }}>Villa</option>
                <option value="müstakil" {{ old('building_type', $listing->building_type ?? '') === 'müstakil' ? 'selected' : '' }}>Müstakil</option>
                <option value="rezidans" {{ old('building_type', $listing->building_type ?? '') === 'rezidans' ? 'selected' : '' }}>Rezidans</option>
                <option value="site içi" {{ old('building_type', $listing->building_type ?? '') === 'site içi' ? 'selected' : '' }}>Site İçi</option>
                <option value="dubleks" {{ old('building_type', $listing->building_type ?? '') === 'dubleks' ? 'selected' : '' }}>Dubleks</option>
                <option value="tripleks" {{ old('building_type', $listing->building_type ?? '') === 'tripleks' ? 'selected' : '' }}>Tripleks</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Isıtma Tipi</label>
            <select name="heating_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                <option value="">Seçiniz</option>
                <option value="doğalgaz" {{ old('heating_type', $listing->heating_type ?? '') === 'doğalgaz' ? 'selected' : '' }}>Doğalgaz</option>
                <option value="kombi" {{ old('heating_type', $listing->heating_type ?? '') === 'kombi' ? 'selected' : '' }}>Kombi</option>
                <option value="soba" {{ old('heating_type', $listing->heating_type ?? '') === 'soba' ? 'selected' : '' }}>Soba</option>
                <option value="elektrik" {{ old('heating_type', $listing->heating_type ?? '') === 'elektrik' ? 'selected' : '' }}>Elektrik</option>
                <option value="güneş enerjisi" {{ old('heating_type', $listing->heating_type ?? '') === 'güneş enerjisi' ? 'selected' : '' }}>Güneş Enerjisi</option>
            </select>
        </div>
        
        <!-- Eşya Durumu -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Eşya Durumu</label>
            <select name="furnished_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                <option value="">Seçiniz</option>
                <option value="furnished" {{ old('furnished_type', $listing->furnished_type ?? '') === 'furnished' ? 'selected' : '' }}>Eşyalı</option>
                <option value="semi_furnished" {{ old('furnished_type', $listing->furnished_type ?? '') === 'semi_furnished' ? 'selected' : '' }}>Yarı Eşyalı</option>
                <option value="unfurnished" {{ old('furnished_type', $listing->furnished_type ?? '') === 'unfurnished' ? 'selected' : '' }}>Eşyasız</option>
            </select>
        </div>
        
        <!-- Etiketler -->
        <div class="md:col-span-2 lg:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-2">Etiketler (virgülle ayırın)</label>
            <input type="text" name="tags" value="{{ old('tags', $listing ? implode(',', $listing->tags ?? []) : '') }}" 
                   placeholder="Örn: Havuz, Balkon, Garaj, Asansör"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
            <p class="mt-1 text-xs text-gray-500">Birden fazla etiket eklemek için virgül kullanın</p>
        </div>
    </div>
    
    <!-- Eski Eşyalı checkbox (geriye uyumluluk için, artık furnished_type kullanılıyor) -->
    <div class="mt-4 hidden">
        <label class="flex items-center space-x-2">
            <input type="checkbox" name="furnished" value="1" {{ old('furnished', $listing->furnished ?? false) ? 'checked' : '' }}
                   class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
            <span class="text-sm font-medium text-gray-700">Eşyalı (Eski)</span>
        </label>
    </div>
</div>

<!-- Medya -->
<div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
    <div class="flex items-center space-x-2 mb-6">
        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900">Fotoğraflar</h3>
    </div>
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Yeni Fotoğraflar Ekle</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary-400 transition-colors">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="photos" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                            <span>Fotoğraf seçin</span>
                            <input id="photos" name="photos[]" type="file" multiple accept="image/*" class="sr-only">
                        </label>
                        <p class="pl-1">veya sürükleyip bırakın</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF (max. 5MB)</p>
                </div>
            </div>
        </div>
        @error('photos.*')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
        @error('photos')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<!-- Video -->
<div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
    <div class="flex items-center space-x-2 mb-6">
        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900">Video (Opsiyonel)</h3>
    </div>
    <div class="space-y-3">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Video URL</label>
            <input type="url" name="video_url" value="{{ old('video_url', $listing->video_url ?? '') }}"
                   placeholder="https://www.youtube.com/watch?v=... veya https://youtu.be/..."
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
            <p class="mt-1 text-xs text-gray-500">YouTube veya diğer video platformlarından URL ekleyebilirsiniz</p>
            @error('video_url')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="text-center text-xs text-gray-400">veya</div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Video Dosyası Yükle</label>
            <div class="mt-1 flex justify-center px-6 pt-4 pb-4 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary-400 transition-colors">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="video_file" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                            <span>Video seçin</span>
                            <input id="video_file" name="video_file" type="file" accept="video/*" class="sr-only">
                        </label>
                        <p class="pl-1">veya sürükleyip bırakın</p>
                    </div>
                    <p class="text-xs text-gray-500">MP4, AVI, MOV (max. 100MB)</p>
                    @if($listing && $listing->video_path)
                    <p class="text-xs text-green-600 mt-2">Mevcut video dosyası: {{ basename($listing->video_path) }}</p>
                    @endif
                </div>
            </div>
            @error('video_file')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <p class="text-xs text-gray-500 mt-2">Not: URL veya dosya yükleyebilirsiniz. İkisi birden seçilirse dosya öncelikli olur.</p>
    </div>
</div>

