@extends('layouts.app')

@section('title', 'İlanlar')

@section('header-title', 'İlanlar')

@section('content')
<div class="space-y-6">
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
                    <option value="">Tüm Emlakçılar</option>
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
                            @php $coverPhoto = $listing->coverPhoto(); @endphp
                            @if($coverPhoto)
                                <img src="{{ asset('storage/' . $coverPhoto->path) }}" alt="{{ $listing->title }}" 
                                     class="w-16 h-16 object-cover rounded">
                            @else
                                <div class="w-16 h-16 bg-gray-200 rounded"></div>
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
                            <a href="{{ route('listings.edit', $listing) }}" class="text-blue-600 hover:text-blue-900">Düzenle</a>
                            <form action="{{ route('listings.destroy', $listing) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Bu ilanı silmek istediğinize emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Sil</button>
                            </form>
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
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $listings->links() }}
        </div>
    </div>
</div>
@endsection

