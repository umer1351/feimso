<?php $__env->startSection('title', ($id ? __('admin.edit') : __('admin.add_new')) . ' · ' . __("admin.resources.$name")); ?>

<?php
    // Long fields go full width; everything else in two columns.
    $wide = ['textarea', 'html', 'json', 'images', 'polygon', 'multiselect'];
?>

<?php $__env->startSection('content'); ?>
<form method="post" enctype="multipart/form-data" action="<?php echo e($id ? route('admin.resource.update', [$name, $id]) : route('admin.resource.store', $name)); ?>">
    <?php echo csrf_field(); ?>
    <?php if($id): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <div class="row">
        <div class="col-xl-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $r['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $def): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="<?php echo e(in_array($def['type'], $wide, true) ? 'col-12' : 'col-md-6'); ?>">
                                <?php echo $__env->make('admin.partials.field', [
                                    'input' => $field, 'field' => $field, 'def' => $def,
                                    'value' => \App\Admin\Fields::formValue($field, $def, $doc),
                                    'opts' => $options[$field] ?? [],
                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card mb-3">
                <div class="card-body">
                    <button class="btn btn-primary btn-block"><i class="feather-save"></i> <?php echo e(__('admin.save')); ?></button>
                    <a class="btn btn-light btn-block" href="<?php echo e(route('admin.resource.index', $name)); ?>"><?php echo e(__('admin.back')); ?></a>
                    <?php if($id): ?>
                        <hr>
                        <div class="small text-muted text-break">ID: <code><?php echo e($id); ?></code></div>
                        <a class="small" href="<?php echo e(route('admin.resource.show', [$name, $id])); ?>"><?php echo e(__('admin.view_raw')); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</form>

<?php if($name === 'users' && $id): ?>
    <div class="card" style="max-width:520px">
        <div class="card-header bg-white"><strong><?php echo e(__('admin.wallet')); ?></strong> — <?php echo e(__('admin.balance')); ?>: <?php echo e(number_format((float) ($doc['wallet_amount'] ?? 0), 2)); ?></div>
        <div class="card-body">
            <form method="post" action="<?php echo e(route('admin.wallet.adjust', $id)); ?>" class="form-row">
                <?php echo csrf_field(); ?>
                <div class="col-4"><input type="number" step="0.01" name="amount" class="form-control" placeholder="±0.00" required></div>
                <div class="col"><input type="text" name="note" class="form-control" placeholder="<?php echo e(__('admin.fields.note')); ?>"></div>
                <div class="col-auto"><button class="btn btn-outline-primary"><?php echo e(__('admin.apply')); ?></button></div>
            </form>
            <div class="help mt-2"><?php echo e(__('admin.help.wallet_adjust_help')); ?></div>
        </div>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\feimso\resources\views/admin/resource/form.blade.php ENDPATH**/ ?>