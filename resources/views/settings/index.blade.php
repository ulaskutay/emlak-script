@extends('layouts.app')

@section('title', 'Ayarlar')

@section('header-title', 'Ayarlar')

@section('content')
<div x-data="deleteLogoModal()">
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
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Acente Logo</label>
                    
                    <!-- Mevcut Logo -->
                    @if($settings['agency_logo'])
                    <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-medium text-gray-700">Mevcut Logo:</p>
                            <button type="button" 
                                    @click="openDeleteModal()" 
                                    class="text-xs px-3 py-1.5 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                                Logoyu Kaldır
                            </button>
                        </div>
                        <div class="inline-block">
                            <img src="{{ asset('storage/' . $settings['agency_logo']) }}" 
                                 alt="Acente Logo" 
                                 class="h-20 object-contain border border-gray-200 rounded-lg p-2 bg-white">
                        </div>
                    </div>
                    @endif
                    
                    <!-- Logo Yükleme -->
                    <div class="flex justify-center px-6 pt-4 pb-4 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="agency_logo" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                    <span>Logo seçin</span>
                                    <input id="agency_logo" name="agency_logo" type="file" accept="image/*" class="sr-only">
                                </label>
                                <p class="pl-1">veya sürükleyip bırakın</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP (max. 2MB)</p>
                        </div>
                    </div>
                    @error('agency_logo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <!-- Logo Önizleme -->
                    <div id="logoPreview" class="hidden mt-3">
                        <p class="text-xs text-gray-500 mb-2">Yeni Logo Önizleme:</p>
                        <img id="logoPreviewImg" src="" alt="Logo Önizleme" class="h-20 object-contain border border-gray-200 rounded-lg p-2 bg-white">
                    </div>
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

<!-- Delete Logo Confirmation Modal -->
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
                    Logoyu Kaldır
                </h3>
                
                <p class="text-center text-sm text-gray-500 mb-6">
                    Acente logosunu kaldırmak istediğinize emin misiniz? Bu işlem geri alınamaz.
                </p>
                
                <div class="flex space-x-3">
                    <button type="button"
                            @click="closeModal()"
                            class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        İptal
                    </button>
                    
                    <form action="{{ route('settings.logo.delete') }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                            Kaldır
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
    // Logo preview
    document.getElementById('agency_logo').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreviewImg').src = e.target.result;
                document.getElementById('logoPreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Drag and drop for logo
    const logoDropZone = document.querySelector('input[name="agency_logo"]').closest('div');
    if (logoDropZone) {
        logoDropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('border-primary-500', 'bg-primary-50');
        });
        
        logoDropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('border-primary-500', 'bg-primary-50');
        });
        
        logoDropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-primary-500', 'bg-primary-50');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('agency_logo').files = files;
                const event = new Event('change', { bubbles: true });
                document.getElementById('agency_logo').dispatchEvent(event);
            }
        });
    }
    
    // Delete Logo Modal
    function deleteLogoModal() {
        return {
            show: false,
            openDeleteModal() {
                this.show = true;
            },
            closeModal() {
                this.show = false;
            }
        }
    }
</script>
@endpush
@endsection

