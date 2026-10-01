<?php $__env->startSection('title', __('admin.dashboard')); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <?php $__currentLoopData = ['customers' => 'users', 'vendors' => 'vendors', 'products' => 'vendor_products', 'orders' => 'vendor_orders']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-sm-6 col-xl-3 mb-3">
            <a href="<?php echo e(route('admin.resource.index', $res)); ?>" class="card stat text-reset text-decoration-none">
                <div class="n"><?php echo e(number_format($stats[$key])); ?></div>
                <div class="l"><?php echo e(__("admin.stats.$key")); ?></div>
            </a>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="row">
    <div class="col-lg-8 mb-3">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between">
                <strong><?php echo e(__('admin.recent_orders')); ?></strong>
                <a href="<?php echo e(route('admin.resource.index', 'vendor_orders')); ?>"><?php echo e(__('admin.see_all')); ?></a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>#</th><th><?php echo e(__('admin.fields.author')); ?></th><th><?php echo e(__('admin.fields.vendor')); ?></th><th><?php echo e(__('admin.fields.status')); ?></th></tr></thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><a href="<?php echo e(route('admin.resource.show', ['vendor_orders', $o['id']])); ?>"><?php echo e(\Illuminate\Support\Str::limit($o['id'], 14)); ?></a></td>
                            <td><?php echo e(data_get($o, 'data.author.firstName')); ?> <?php echo e(data_get($o, 'data.author.lastName')); ?></td>
                            <td><?php echo e(data_get($o, 'data.vendor.title')); ?></td>
                            <td><?php echo $__env->make('admin.partials.status', ['status' => data_get($o, 'data.status')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="4" class="text-muted text-center py-4"><?php echo e(__('admin.no_records')); ?></td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-header bg-white"><strong><?php echo e(__('admin.orders_by_status')); ?></strong></div>
            <ul class="list-group list-group-flush">
                <?php $__empty_1 = true; $__currentLoopData = $byStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="list-group-item d-flex justify-content-between"><?php echo $__env->make('admin.partials.status', ['status' => $status], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> <span><?php echo e($n); ?></span></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="list-group-item text-muted"><?php echo e(__('admin.no_records')); ?></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\feimso\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>