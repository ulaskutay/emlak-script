@extends('layouts.app')

@section('title', 'Giriş Ayarları')
@section('header-title', 'Giriş Ayarları')


@section('content')
<div x-data="deleteIntroBannerModal()">
<form action="{{ route('design.intro.update') }}" method="POST" enctype="multipart/form-data" id="introForm">
    @csrf
    @method('PATCH')
    
    <div class="space-y-6">
        <!-- Banner Bölümü -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Giriş Banner'ı</h3>
            <p class="text-sm text-gray-600 mb-4">Ana sayfanın üst kısmında görünecek banner görseli. (Opsiyonel - Banner yüklenmezse gradient arka plan kullanılır)</p>
            
            <!-- Mevcut Banner -->
            @if($introBanner)
            <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-700">Mevcut Banner:</p>
                    <button type="button" 
                            @click="openDeleteModal()" 
                            class="text-xs px-3 py-1.5 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                        Banner'ı Kaldır
                    </button>
                </div>
                <div class="inline-block">
                    <img src="{{ asset('storage/' . $introBanner) }}" 
                         alt="Giriş Banner" 
                         class="max-w-full h-auto max-h-64 object-contain border border-gray-200 rounded-lg p-2 bg-white">
                </div>
            </div>
            @endif
            
            <!-- Banner Yükleme -->
            <div class="flex justify-center px-6 pt-4 pb-4 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary-400 transition-colors">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="intro_banner" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                            <span>Banner seçin</span>
                            <input id="intro_banner" name="intro_banner" type="file" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" class="sr-only">
                        </label>
                        <p class="pl-1">veya sürükleyip bırakın</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP (max. {{ $maxUploadMB ?? 2 }}MB)</p>
                    @if(isset($uploadMaxSize) && $uploadMaxSize)
                    <p class="text-xs text-gray-400 mt-1">
                        PHP limit: upload_max_filesize={{ $uploadMaxSize }}, post_max_size={{ $postMaxSize ?? '8M' }}
                    </p>
                    @endif
                </div>
            </div>
            @error('intro_banner')
                <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                </div>
            @enderror
            
            <!-- Banner Önizleme -->
            <div id="bannerPreview" class="hidden mt-3">
                <p class="text-xs text-gray-500 mb-2">Yeni Banner Önizleme:</p>
                <img id="bannerPreviewImg" src="" alt="Banner Önizleme" class="max-w-full h-auto max-h-64 object-contain border border-gray-200 rounded-lg p-2 bg-white">
            </div>
        </div>

        <!-- Slogan Bölümü -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Slogan Ayarları</h3>
            
            <!-- Ana Başlık (Slogan) -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ana Başlık (Slogan)</label>
                <input type="text" 
                       name="intro_title" 
                       value="{{ old('intro_title', $introTitle) }}"
                       placeholder="Örn: Hayalinizdeki Evi Bulun"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <p class="mt-1 text-xs text-gray-500">Ana sayfa hero bölümünde görünecek ana başlık</p>
            </div>
            
            <!-- Alt Başlık -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alt Başlık (Açıklama)</label>
                <textarea name="intro_subtitle" 
                          rows="3"
                          placeholder="Örn: {TOTAL_LISTINGS} aktif ilan ile size en uygun emlakı keşfedin. Profesyonel emlak danışmanlarımızla hayalinizdeki evi bulun."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none">{{ old('intro_subtitle', $introSubtitle) }}</textarea>
                <p class="mt-1 text-xs text-gray-500">
                    Ana başlığın altında görünecek açıklama metni. 
                    <span class="font-semibold text-primary-600">{TOTAL_LISTINGS}</span> yazarsanız aktif ilan sayısı otomatik olarak gösterilir.
                </p>
                <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs text-blue-800">
                        <strong>Örnek:</strong> "{TOTAL_LISTINGS} aktif ilan ile size en uygun emlakı keşfedin."
                        <br>
                        <span class="text-blue-600">→ Otomatik olarak "{totalListings} aktif ilan ile size en uygun emlakı keşfedin." şeklinde görünecektir.</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-semibold">
                Kaydet
            </button>
        </div>
    </div>
</form>

<!-- Delete Banner Confirmation Modal -->
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
                    Giriş Banner'ını Kaldır
                </h3>
                
                <p class="text-center text-sm text-gray-500 mb-6">
                    Giriş banner'ını kaldırmak istediğinize emin misiniz? Bu işlem geri alınamaz. Gradient arka plan kullanılacaktır.
                </p>
                
                <div class="flex space-x-3">
                    <button type="button"
                            @click="closeModal()"
                            class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        İptal
                    </button>
                    
                    <form action="{{ route('design.intro.banner.delete') }}" method="POST" class="flex-1">
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
    // Banner preview
    const bannerInput = document.getElementById('intro_banner');
    if (bannerInput) {
        bannerInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('bannerPreviewImg').src = e.target.result;
                    document.getElementById('bannerPreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Delete Banner Modal
    window.deleteIntroBannerModal = function() {
        return {
            show: false,
            openDeleteModal() {
                this.show = true;
            },
            closeModal() {
                this.show = false;
            }
        }
    };
</script>
@endpush
@endsection

