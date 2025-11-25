@extends('layouts.app')

@section('title', 'Yeni Talep Ekle')

@section('header-title', 'Yeni Talep Ekle')

@section('content')
<div class="max-w-4xl mx-auto">
    <form method="POST" action="{{ route('inquiries.store') }}" class="space-y-6">
        @csrf

        <!-- Header -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-lg p-6 text-white">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Yeni Talep Ekle</h2>
                    <p class="text-primary-100 text-sm mt-1">Müşteri talebini manuel olarak ekleyin</p>
                </div>
            </div>
        </div>

        <!-- Müşteri Bilgileri -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center space-x-2 mb-6">
                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Müşteri Bilgileri</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Müşteri Seçimi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mevcut Müşteri (Opsiyonel)</label>
                    <select name="customer_id" id="customer_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                        <option value="">Yeni Müşteri</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" data-name="{{ $customer->name }}" data-phone="{{ $customer->phone }}" data-email="{{ $customer->email }}">
                                {{ $customer->name }} - {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Mevcut bir müşteriyi seçerseniz, bilgiler otomatik doldurulacak</p>
                </div>

                <!-- İlan Seçimi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">İlan (Opsiyonel)</label>
                    <select name="listing_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                        <option value="">İlan Seçilmedi</option>
                        @foreach($listings as $listing)
                            <option value="{{ $listing->id }}">
                                {{ $listing->title }} - {{ $listing->city }}@if($listing->district), {{ $listing->district }}@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- İsim -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        İsim <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telefon -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Telefon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">E-posta</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Talep Detayları -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center space-x-2 mb-6">
                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Talep Detayları</h3>
            </div>

            <div class="space-y-6">
                <!-- Mesaj -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mesaj <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message" rows="5" required
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Durum ve Emlakçı -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Durum -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                            <option value="new" {{ old('status', 'new') === 'new' ? 'selected' : '' }}>Yeni</option>
                            <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>İşlemde</option>
                            <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Kapalı</option>
                        </select>
                    </div>

                    <!-- Atanan Emlakçı -->
                    @if(auth()->user()->isAdmin())
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Atanan Emlakçı</label>
                        <select name="assigned_agent_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white">
                            <option value="">Atanmadı</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('assigned_agent_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="flex items-center justify-end space-x-4 bg-white rounded-lg shadow p-6">
            <a href="{{ route('inquiries.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                İptal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium">
                Talep Ekle
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Auto-fill customer information when customer is selected
    document.getElementById('customer_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            document.getElementById('name').value = selectedOption.dataset.name || '';
            document.getElementById('phone').value = selectedOption.dataset.phone || '';
            document.getElementById('email').value = selectedOption.dataset.email || '';
        }
    });
</script>
@endpush
@endsection

