<?php $__env->startSection('title', __('admin.settings') . ' · ' . __("admin.settings_tabs.$tab")); ?>

<?php $__env->startSection('content'); ?>
<ul class="nav nav-tabs mb-3">
    <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $def): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="nav-item"><a class="nav-link <?php echo e($key === $tab ? 'active' : ''); ?>" href="<?php echo e(route('admin.settings', $key)); ?>"><?php echo e(__("admin.settings_tabs.$key")); ?></a></li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>

<?php if($tab === 'payments'): ?>
    <div class="alert alert-warning small"><?php echo e(__('admin.help.payments_warning')); ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" action="<?php echo e(route('admin.settings.update', $tab)); ?>">
    <?php echo csrf_field(); ?>
    <div class="row">
        <?php $__currentLoopData = $tabs[$tab]['docs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $docId => $fields): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php ($wideDoc = collect($fields)->contains(fn ($d) => in_array($d['type'], ['html', 'json'], true))); ?>
            <div class="<?php echo e($wideDoc ? 'col-12' : 'col-lg-6'); ?> mb-3">
                <div class="card h-100">
                    <div class="card-header bg-white"><strong><?php echo e(__("admin.settings_docs.$docId") === "admin.settings_docs.$docId" ? \Illuminate\Support\Str::headline($docId) : __("admin.settings_docs.$docId")); ?></strong></div>
                    <div class="card-body">
                        <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $def): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('admin.partials.field', [
                                'input' => $docId . '__' . $field, 'field' => $field, 'def' => $def,
                                'value' => \App\Admin\Fields::formValue($field, $def, $docs[$docId]),
                                'opts' => $options[$docId][$field] ?? [],
                                'isSet' => ($docs[$docId][$field] ?? '') !== '',
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <button class="btn btn-primary"><i class="feather-save"></i> <?php echo e(__('admin.save')); ?></button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\feimso\resources\views/admin/settings.blade.php ENDPATH**/ ?>