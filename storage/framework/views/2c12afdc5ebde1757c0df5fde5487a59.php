<?php $__env->startSection('title', 'Talepler'); ?>

<?php $__env->startSection('header-title', 'Talepler'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Talep Listesi</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İsim</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telefon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İlan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Durum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tarih</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İşlem</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $inquiries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inquiry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-6 py-4"><?php echo e($inquiry->name); ?></td>
                    <td class="px-6 py-4"><?php echo e($inquiry->phone); ?></td>
                    <td class="px-6 py-4"><?php echo e($inquiry->listing ? $inquiry->listing->title : 'Genel Talep'); ?></td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            <?php if($inquiry->status === 'new'): ?> bg-blue-100 text-blue-800
                            <?php elseif($inquiry->status === 'in_progress'): ?> bg-yellow-100 text-yellow-800
                            <?php else: ?> bg-green-100 text-green-800
                            <?php endif; ?>">
                            <?php echo e($inquiry->status_label); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4"><?php echo e($inquiry->created_at->format('d.m.Y')); ?></td>
                    <td class="px-6 py-4">
                        <a href="<?php echo e(route('inquiries.show', $inquiry)); ?>" class="text-blue-600 hover:text-blue-900">Görüntüle</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Talep bulunamadı.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php echo e($inquiries->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/eticajans/Desktop/Etic Ajans/Projeler/Mobil Uygulama/Emlak Script/resources/views/inquiries/index.blade.php ENDPATH**/ ?>