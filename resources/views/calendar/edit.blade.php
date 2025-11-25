@extends('layouts.app')

@section('title', 'Randevu Düzenle')

@section('header-title', 'Randevu Düzenle')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Randevu Bilgileri</h3>
            <form method="POST" action="{{ route('calendar.destroy', $calendar) }}" onsubmit="return confirm('Bu randevuyu silmek istediğinize emin misiniz?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                    Sil
                </button>
            </form>
        </div>

        <form method="POST" action="{{ route('calendar.update', $calendar) }}" class="p-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Başlık <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $calendar->title) }}" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Agent (Admin only) -->
                @if(auth()->user()->isAdmin())
                <div>
                    <label for="agent_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Emlakçı <span class="text-red-500">*</span>
                    </label>
                    <select id="agent_id" 
                            name="agent_id" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Seçiniz</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ old('agent_id', $calendar->agent_id) == $agent->id ? 'selected' : '' }}>
                                {{ $agent->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('agent_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Listing -->
                <div>
                    <label for="listing_id" class="block text-sm font-medium text-gray-700 mb-2">
                        İlan (Opsiyonel)
                    </label>
                    <select id="listing_id" 
                            name="listing_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">İlan Seçiniz</option>
                        @foreach($listings as $listing)
                            <option value="{{ $listing->id }}" {{ old('listing_id', $calendar->listing_id) == $listing->id ? 'selected' : '' }}>
                                {{ $listing->title }} - {{ $listing->city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Customer -->
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Müşteri (Opsiyonel)
                    </label>
                    <select id="customer_id" 
                            name="customer_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="">Müşteri Seçiniz</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $calendar->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tarih <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ old('start_date', $calendar->start_at->format('Y-m-d')) }}" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Time -->
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Başlangıç Saati <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           id="start_time" 
                           name="start_time" 
                           value="{{ old('start_time', $calendar->start_at->format('H:i')) }}" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <!-- End Time -->
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Bitiş Saati <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           id="end_time" 
                           name="end_time" 
                           value="{{ old('end_time', $calendar->end_at->format('H:i')) }}" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    @error('end_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Note -->
                <div class="md:col-span-2">
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-2">
                        Notlar
                    </label>
                    <textarea id="note" 
                              name="note" 
                              rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('note', $calendar->note) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('calendar.index') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    İptal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    Güncelle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

