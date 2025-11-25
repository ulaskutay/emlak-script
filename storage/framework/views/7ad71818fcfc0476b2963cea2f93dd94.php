<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'EstateFlow')); ?> - <?php echo $__env->yieldContent('title', 'Yönetim Paneli'); ?></title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-primary-700 to-primary-800 text-white flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-white border-opacity-10">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center backdrop-blur-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">EstateFlow</h1>
                        <p class="text-xs text-white text-opacity-70">Yönetim Paneli</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('dashboard') ? 'bg-white bg-opacity-20 text-white shadow-lg' : 'text-white text-opacity-80 hover:bg-white hover:bg-opacity-10'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Kontrol Paneli</span>
                </a>

                <a href="<?php echo e(route('listings.index')); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors <?php echo e((request()->routeIs('listings.index') || request()->routeIs('listings.show') || request()->routeIs('listings.edit')) && !request()->routeIs('listings.create') ? 'bg-white bg-opacity-20 text-white shadow-lg' : 'text-white text-opacity-80 hover:bg-white hover:bg-opacity-10'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span>İlanlar</span>
                </a>

                <a href="<?php echo e(route('listings.create')); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('listings.create') ? 'bg-white bg-opacity-20 text-white shadow-lg' : 'text-white text-opacity-80 hover:bg-white hover:bg-opacity-10'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Yeni İlan Ekle</span>
                </a>

                <a href="<?php echo e(route('inquiries.index')); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('inquiries.*') ? 'bg-white bg-opacity-20 text-white shadow-lg' : 'text-white text-opacity-80 hover:bg-white hover:bg-opacity-10'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Talepler</span>
                </a>

                <a href="<?php echo e(route('customers.index')); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('customers.*') ? 'bg-white bg-opacity-20 text-white shadow-lg' : 'text-white text-opacity-80 hover:bg-white hover:bg-opacity-10'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Müşteriler</span>
                </a>

                <a href="<?php echo e(route('calendar.index')); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('calendar.*') ? 'bg-white bg-opacity-20 text-white shadow-lg' : 'text-white text-opacity-80 hover:bg-white hover:bg-opacity-10'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Takvim</span>
                </a>

                <a href="<?php echo e(route('agents.index')); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('agents.*') ? 'bg-white bg-opacity-20 text-white shadow-lg' : 'text-white text-opacity-80 hover:bg-white hover:bg-opacity-10'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Emlakçılar</span>
                </a>
            </nav>

            <!-- Bottom Menu -->
                <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->isAdmin()): ?>
                <div class="p-4 border-t border-white border-opacity-10 space-y-2">
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('design.*') ? 'bg-white bg-opacity-20 text-white shadow-lg' : 'text-white text-opacity-80 hover:bg-white hover:bg-opacity-10'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                        <span>Tasarım</span>
                    </a>
                    
                    <a href="<?php echo e(route('settings.index')); ?>" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors <?php echo e(request()->routeIs('settings.*') ? 'bg-white bg-opacity-20 text-white shadow-lg' : 'text-white text-opacity-80 hover:bg-white hover:bg-opacity-10'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Ayarlar</span>
                    </a>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-semibold text-gray-800"><?php echo $__env->yieldContent('header-title', 'Kontrol Paneli'); ?></h2>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="relative">
                            <input type="text" placeholder="Emlak, müşteri, emlakçı ara..." 
                                   class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 w-64 transition-all bg-gray-50 hover:bg-white">
                            <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        <!-- Notifications -->
                        <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </button>

                        <!-- Language -->
                        <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                            </svg>
                        </button>

                        <!-- User Menu -->
                        <div class="flex items-center space-x-3" x-data="{ open: false }">
                            <div class="flex items-center space-x-2 cursor-pointer" @click="open = !open">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold shadow-md">
                                    <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900"><?php echo e(auth()->user()->name); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e(auth()->user()->isAdmin() ? 'Yönetici' : 'Emlakçı'); ?></p>
                                </div>
                            </div>

                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="absolute right-6 top-16 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Çıkış Yap
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <?php if(session('success')): ?>
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
</body>
</html>

<?php /**PATH /Users/eticajans/Desktop/Etic Ajans/Projeler/Mobil Uygulama/Emlak Script/resources/views/layouts/app.blade.php ENDPATH**/ ?>