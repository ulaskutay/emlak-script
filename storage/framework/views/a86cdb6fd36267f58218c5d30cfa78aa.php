<?php $__env->startSection('title', 'Müşteriler'); ?>

<?php $__env->startSection('header-title', 'Müşteriler'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Müşteri Listesi</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İsim</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Telefon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">E-posta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Talepler</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İşlem</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-6 py-4"><?php echo e($customer->name); ?></td>
                    <td class="px-6 py-4"><?php echo e($customer->phone); ?></td>
                    <td class="px-6 py-4"><?php echo e($customer->email ?? '-'); ?></td>
                    <td class="px-6 py-4"><?php echo e($customer->inquiries_count); ?></td>
                    <td class="px-6 py-4">
                        <a href="<?php echo e(route('customers.show', $customer)); ?>" class="text-blue-600 hover:text-blue-900">Görüntüle</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Müşteri bulunamadı.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php echo e($customers->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/eticajans/Desktop/Etic Ajans/Projeler/Mobil Uygulama/Emlak Script/resources/views/customers/index.blade.php ENDPATH**/ ?>