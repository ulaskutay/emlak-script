<?php $__env->startSection('title', 'Emlakçılar'); ?>

<?php $__env->startSection('header-title', 'Emlakçılar'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Agents Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow border border-gray-100 overflow-hidden">
            <!-- Agent Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-md">
                        <?php echo e(strtoupper(substr($agent->user->name, 0, 1))); ?>

                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-gray-900 truncate"><?php echo e($agent->user->name); ?></h3>
                        <p class="text-sm text-gray-500 truncate"><?php echo e($agent->user->email); ?></p>
                    </div>
                </div>
            </div>

            <!-- Agent Info -->
            <div class="p-6 space-y-4">
                <!-- Contact Info -->
                <?php if($agent->phone): ?>
                <div class="flex items-center space-x-3 text-sm">
                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span class="text-gray-700"><?php echo e($agent->phone); ?></span>
                </div>
                <?php endif; ?>

                <!-- Statistics -->
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600"><?php echo e($agent->listings_count); ?></p>
                        <p class="text-xs text-gray-600 mt-1">Toplam İlan</p>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600"><?php echo e($agent->active_listings_count); ?></p>
                        <p class="text-xs text-gray-600 mt-1">Aktif İlan</p>
                    </div>
                    <div class="text-center p-3 bg-yellow-50 rounded-lg">
                        <p class="text-2xl font-bold text-yellow-600"><?php echo e($agent->inquiries_count); ?></p>
                        <p class="text-xs text-gray-600 mt-1">Talepler</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <a href="<?php echo e(route('agents.show', $agent)); ?>" class="w-full block text-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-medium">
                    Detayları Görüntüle
                </a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Henüz emlakçı bulunmuyor</h3>
            <p class="text-gray-500">Sistemde kayıtlı emlakçı bulunmamaktadır.</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($agents->hasPages()): ?>
    <div class="bg-white rounded-lg shadow px-6 py-4">
        <?php echo e($agents->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/eticajans/Desktop/Etic Ajans/Projeler/Mobil Uygulama/Emlak Script/resources/views/agents/index.blade.php ENDPATH**/ ?>