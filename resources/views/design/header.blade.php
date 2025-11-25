@extends('layouts.app')

@section('title', 'Header Tasarımı')
@section('header-title', 'Header Tasarımı')

@push('styles')
<style>
    .sortable-ghost {
        opacity: 0.5;
        background-color: #f9fafb;
    }
    .sortable-chosen {
        border-color: #214CC4;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .sortable-drag {
        border-color: #1e40af;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div x-data="deleteHeaderLogoModal()">
<form action="{{ route('design.header.update') }}" method="POST" enctype="multipart/form-data" id="headerForm">
    @csrf
    @method('PATCH')
    
    <div class="space-y-6">
        <!-- Logo Bölümü -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Header Logo</h3>
            
            <!-- Header Sloganı -->
            <div class="mb-6 pb-6 border-b border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-2">Header Sloganı</label>
                <input type="text" 
                       name="header_slogan" 
                       value="{{ $headerSlogan ?? '' }}"
                       placeholder="Header sloganı (boş bırakılabilir)"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <p class="mt-1 text-xs text-gray-500">Header'da logo yanında görünecek slogan metni</p>
            </div>
            
            <!-- Mevcut Logo -->
            @if($headerLogo || $agencyLogo)
            <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-700">Mevcut Logo:</p>
                    @if($headerLogo)
                    <button type="button" 
                            @click="openDeleteModal()" 
                            class="text-xs px-3 py-1.5 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors">
                        Logoyu Kaldır
                    </button>
                    @endif
                </div>
                <div class="inline-block">
                    <img src="{{ asset('storage/' . ($headerLogo ?? $agencyLogo)) }}" 
                         alt="Header Logo" 
                         class="h-16 object-contain border border-gray-200 rounded-lg p-2 bg-white">
                </div>
                @if(!$headerLogo && $agencyLogo)
                <p class="mt-2 text-xs text-gray-500">Şu anda genel logo kullanılıyor. Header için özel logo yükleyebilirsiniz.</p>
                @endif
            </div>
            @endif
            
            <!-- Logo Yükleme -->
            <div class="flex justify-center px-6 pt-4 pb-4 border-2 border-gray-300 border-dashed rounded-lg hover:border-primary-400 transition-colors">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="header_logo" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                            <span>Logo seçin</span>
                            <input id="header_logo" name="header_logo" type="file" accept="image/*" class="sr-only">
                        </label>
                        <p class="pl-1">veya sürükleyip bırakın</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP (max. 2MB)</p>
                </div>
            </div>
            @error('header_logo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            
            <!-- Logo Önizleme -->
            <div id="logoPreview" class="hidden mt-3">
                <p class="text-xs text-gray-500 mb-2">Yeni Logo Önizleme:</p>
                <img id="logoPreviewImg" src="" alt="Logo Önizleme" class="h-16 object-contain border border-gray-200 rounded-lg p-2 bg-white">
            </div>
        </div>

        <!-- Menü Düzenleme -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Menü Ayarları</h3>
            <p class="text-sm text-gray-600 mb-6">Menü öğelerini açıp kapatabilir ve sıralayabilirsiniz. Sıralamak için sol taraftaki tutamacı kullanın.</p>
            
            <div class="space-y-3" id="menusContainer">
                @foreach($menuSettings as $index => $menu)
                <div class="menu-item border border-gray-200 rounded-lg p-4 hover:border-primary-300 transition-all bg-white" 
                     data-menu-key="{{ $menu['key'] ?? '' }}"
                     data-original-index="{{ $index }}">
                    <div class="flex items-start gap-4">
                        <!-- Drag Handle -->
                        <div class="drag-handle flex-shrink-0 pt-7 cursor-move">
                            <svg class="w-6 h-6 text-gray-400 hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </div>
                        
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Menü Adı -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Menü Adı</label>
                                <input type="text" 
                                       name="menus[{{ $index }}][label]"
                                       class="menu-label-input w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                       value="{{ $menu['label'] }}"
                                       required>
                                <input type="hidden" name="menus[{{ $index }}][key]" class="menu-key-input" value="{{ $menu['key'] }}">
                                <input type="hidden" name="menus[{{ $index }}][route]" class="menu-route-input" value="{{ $menu['route'] }}">
                                <input type="hidden" name="menus[{{ $index }}][order]" class="menu-order-input" value="{{ $menu['order'] ?? ($index + 1) }}">
                            </div>
                            
                            <!-- Durum Switch -->
                            <div class="flex items-center space-x-3">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Durum</label>
                                    <div class="flex items-center space-x-3">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" 
                                                   name="menus[{{ $index }}][enabled]"
                                                   value="1"
                                                   class="menu-enabled-checkbox sr-only peer"
                                                   {{ ($menu['enabled'] ?? true) ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                                        </label>
                                        <span class="menu-status-text text-sm font-medium text-gray-700">{{ ($menu['enabled'] ?? true) ? 'Aktif' : 'Pasif' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
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

<!-- Delete Header Logo Confirmation Modal -->
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
                    Header Logoyu Kaldır
                </h3>
                
                <p class="text-center text-sm text-gray-500 mb-6">
                    Header logosunu kaldırmak istediğinize emin misiniz? Bu işlem geri alınamaz. Genel logo kullanılacaktır.
                </p>
                
                <div class="flex space-x-3">
                    <button type="button"
                            @click="closeModal()"
                            class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        İptal
                    </button>
                    
                    <form action="{{ route('design.header.logo.delete') }}" method="POST" class="flex-1">
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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    let sortableInstance = null;
    
    // Logo preview
    const logoInput = document.getElementById('header_logo');
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
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
    }
    
    // Delete Header Logo Modal
    window.deleteHeaderLogoModal = function() {
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
    
    function initMenuSortable() {
        const menusContainer = document.getElementById('menusContainer');
        if (!menusContainer) {
            setTimeout(initMenuSortable, 200);
            return;
        }
        
        // Wait for SortableJS
        if (typeof Sortable === 'undefined') {
            setTimeout(initMenuSortable, 200);
            return;
        }
        
        // Destroy existing instance if any
        if (sortableInstance) {
            sortableInstance.destroy();
            sortableInstance = null;
        }
        
        // Create Sortable instance
        try {
            sortableInstance = new Sortable(menusContainer, {
                animation: 150,
                handle: '.drag-handle',
                forceFallback: false,
                onEnd: function(evt) {
                    // Update order values
                    updateMenuOrders();
                }
            });
            console.log('Sortable initialized successfully');
        } catch (error) {
            console.error('Error initializing Sortable:', error);
        }
    }
    
    // Update menu order values after drag
    function updateMenuOrders() {
        const menuItems = document.querySelectorAll('#menusContainer .menu-item');
        menuItems.forEach((item, index) => {
            const orderInput = item.querySelector('.menu-order-input');
            if (orderInput) {
                orderInput.value = index + 1;
            }
        });
    }
    
    // Handle checkbox status updates
    function setupMenuCheckboxes() {
        const checkboxes = document.querySelectorAll('.menu-enabled-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const menuItem = this.closest('.menu-item');
                const statusText = menuItem.querySelector('.menu-status-text');
                if (statusText) {
                    statusText.textContent = this.checked ? 'Aktif' : 'Pasif';
                }
            });
        });
    }
    
    // Prepare form data before submission
    function prepareFormData() {
        const headerForm = document.getElementById('headerForm');
        if (!headerForm) return;
        
        headerForm.addEventListener('submit', function(e) {
            const menuItems = Array.from(document.querySelectorAll('#menusContainer .menu-item'));
            
            menuItems.forEach((item, newIndex) => {
                const labelInput = item.querySelector('.menu-label-input');
                const keyInput = item.querySelector('.menu-key-input');
                const routeInput = item.querySelector('.menu-route-input');
                const orderInput = item.querySelector('.menu-order-input');
                const enabledCheckbox = item.querySelector('.menu-enabled-checkbox');
                
                if (!labelInput || !keyInput || !routeInput) return;
                
                // Update order value
                if (orderInput) {
                    orderInput.value = newIndex + 1;
                }
                
                // Update input names
                labelInput.name = `menus[${newIndex}][label]`;
                keyInput.name = `menus[${newIndex}][key]`;
                routeInput.name = `menus[${newIndex}][route]`;
                if (orderInput) {
                    orderInput.name = `menus[${newIndex}][order]`;
                }
                
                // Handle enabled checkbox - update name attribute
                if (enabledCheckbox) {
                    // Remove old hidden inputs for enabled
                    item.querySelectorAll('input[type="hidden"][name*="[enabled]"]').forEach(h => h.remove());
                    
                    if (enabledCheckbox.checked) {
                        enabledCheckbox.name = `menus[${newIndex}][enabled]`;
                        enabledCheckbox.value = '1';
                    } else {
                        // Checkbox unchecked - remove name from checkbox and add hidden input
                        enabledCheckbox.removeAttribute('name');
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = `menus[${newIndex}][enabled]`;
                        hiddenInput.value = '0';
                        item.appendChild(hiddenInput);
                    }
                }
            });
        });
    }
    
    // Initialize everything when DOM is ready
    function init() {
        setupMenuCheckboxes();
        prepareFormData();
        // Initialize Sortable - wait a bit for script to load
        setTimeout(function() {
            initMenuSortable();
        }, 300);
    }
    
    // Also try to initialize when SortableJS loads
    window.addEventListener('load', function() {
        setTimeout(initMenuSortable, 500);
    });
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
</script>
@endpush
@endsection

