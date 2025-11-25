@extends('layouts.app')

@section('title', 'İlanlar')

@section('header-title', 'İlanlar')

@section('content')
<div class="space-y-6" x-data="deleteModal()">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('listings.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Başlık, şehir, adres ara..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tüm Durumlar</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Beklemede</option>
                    <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Satıldı</option>
                    <option value="rented" {{ request('status') === 'rented' ? 'selected' : '' }}>Kiralandı</option>
                    <option value="passive" {{ request('status') === 'passive' ? 'selected' : '' }}>Pasif</option>
                </select>
            </div>
            <div>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tüm Tipler</option>
                    <option value="sale" {{ request('type') === 'sale' ? 'selected' : '' }}>Satılık</option>
                    <option value="rent" {{ request('type') === 'rent' ? 'selected' : '' }}>Kiralık</option>
                </select>
            </div>
            @if(auth()->user()->isAdmin())
            <div>
                <select name="agent_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tümü (Emlakçılar & Ofis)</option>
                    <option value="office" {{ request('agent_id') === 'office' ? 'selected' : '' }}>Ofis</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                            {{ $agent->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="md:col-span-4 flex space-x-2">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Filtrele
                </button>
                <a href="{{ route('listings.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Temizle
                </a>
            </div>
        </form>
    </div>

    <!-- Listings Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">İlan Listesi</h3>
            <a href="{{ route('listings.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Yeni İlan Ekle
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fotoğraf</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Başlık & Adres</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tip</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fiyat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Şehir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($listings as $listing)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php 
                                $coverPhoto = $listing->photos->where('is_cover', true)->first() ?? $listing->photos->first();
                            @endphp
                            @if($coverPhoto && $coverPhoto->exists())
                                <img src="{{ $coverPhoto->url }}" alt="{{ $listing->title }}" 
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('listings.show', $listing) }}" class="text-primary-600 hover:text-primary-900">Görüntüle</a>
                            <a href="{{ route('listings.edit', $listing) }}" class="text-blue-600 hover:text-blue-900">Düzenle</a>
                            <button type="button" 
                                    @click="openDeleteModal({{ $listing->id }}, '{{ addslashes($listing->title) }}')"
                                    class="text-red-600 hover:text-red-900">
                                Sil
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">İlan bulunamadı.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($listings->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-center">
                {{ $listings->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

    <!-- Delete Confirmation Modal -->
    <div x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    @keydown.escape.window="closeModal()"
    style="display: none;">
    <!-- Backdrop -->
    <div x-show="show" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
         @click="closeModal()"></div>

    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-xl bg-white shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            
            <!-- Modal Content -->
            <div class="bg-white px-6 pb-4 pt-6" @click.away.stop>
                <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full bg-red-100">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                
                <h3 class="text-center text-lg font-semibold text-gray-900 mb-2">
                    İlanı Sil
                </h3>
                
                <p class="text-center text-sm text-gray-500 mb-6">
                    "<span x-text="listingTitle" class="font-medium text-gray-900"></span>" adlı ilanı silmek istediğinize emin misiniz? Bu işlem geri alınamaz.
                </p>
                
                <div class="flex space-x-3" x-show="deleteUrl">
                    <button type="button"
                            @click="closeModal()"
                            class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        İptal
                    </button>
                    
                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                            Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteModal() {
    return {
        show: false,
        listingId: null,
        listingTitle: '',
        deleteUrl: '',
        openDeleteModal(id, title) {
            this.listingId = id;
            this.listingTitle = title;
            this.deleteUrl = '{{ url('/listings') }}/' + id;
            this.show = true;
        },
        closeModal() {
            this.show = false;
            this.listingId = null;
            this.listingTitle = '';
            this.deleteUrl = '';
        }
    }
}
</script>
@endsection

