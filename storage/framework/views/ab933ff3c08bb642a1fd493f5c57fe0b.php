<?php $__env->startSection('title', 'Takvim'); ?>

<?php $__env->startSection('header-title', 'Takvim'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-lg shadow p-6">
    <div class="mb-4">
        <form method="GET" action="<?php echo e(route('calendar.index')); ?>" class="flex items-center space-x-4">
            <input type="date" name="date" value="<?php echo e($date); ?>" class="px-4 py-2 border border-gray-300 rounded-lg">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Filtrele</button>
        </form>
    </div>
    
    <div class="space-y-4">
        <h3 class="text-lg font-semibold"><?php echo e(\Carbon\Carbon::parse($date)->format('d.m.Y')); ?> Tarihindeki Etkinlikler</h3>
        
        <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="border rounded-lg p-4">
            <div class="flex justify-between items-start">
                <div>
                    <h4 class="font-semibold"><?php echo e($event->title); ?></h4>
                    <p class="text-sm text-gray-600"><?php echo e($event->start_at->format('H:i')); ?> - <?php echo e($event->end_at->format('H:i')); ?></p>
                    <?php if($event->listing): ?>
                        <p class="text-sm text-gray-500">İlan: <?php echo e($event->listing->title); ?></p>
                    <?php endif; ?>
                    <?php if($event->customer): ?>
                        <p class="text-sm text-gray-500">Müşteri: <?php echo e($event->customer->name); ?></p>
                    <?php endif; ?>
                    <?php if($event->note): ?>
                        <p class="text-sm text-gray-500 mt-2"><?php echo e($event->note); ?></p>
                    <?php endif; ?>
                </div>
                <div class="text-sm text-gray-500">
                    <?php echo e($event->agent->user->name); ?>

                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-gray-500">Bu tarihte etkinlik bulunmuyor.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/eticajans/Desktop/Etic Ajans/Projeler/Mobil Uygulama/Emlak Script/resources/views/calendar/index.blade.php ENDPATH**/ ?>