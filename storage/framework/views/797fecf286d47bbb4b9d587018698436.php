<?php $__env->startSection('title', 'Yeni İlan Ekle'); ?>

<?php $__env->startSection('header-title', 'Yeni İlan Ekle'); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('listings.store')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <div class="bg-white rounded-lg shadow p-6 space-y-6">
        <?php echo $__env->make('listings.form-fields', ['listing' => null, 'agents' => $agents ?? collect()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Buttons -->
        <div class="flex justify-end space-x-4 pt-4 border-t">
            <a href="<?php echo e(route('listings.index')); ?>" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                İptal
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Kaydet
            </button>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/eticajans/Desktop/Etic Ajans/Projeler/Mobil Uygulama/Emlak Script/resources/views/listings/create.blade.php ENDPATH**/ ?>