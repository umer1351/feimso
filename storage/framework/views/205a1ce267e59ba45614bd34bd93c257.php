<?php $__env->startSection('title', __("admin.resources.$name") . ' · ' . \Illuminate\Support\Str::limit((string) ($doc[$r['title']] ?? $id), 40)); ?>

<?php
    $fmt = function ($v) {
        if (is_array($v) && ($v['__fs'] ?? null) === 'ts') {
            return \Carbon\Carbon::createFromTimestamp($v['s'])->format('Y-m-d H:i');
        }
        if (is_array($v) && ($v['__fs'] ?? null) === 'geo') {
            return $v['lat'] . ', ' . $v['lng'];
        }
        if (is_bool($v)) {
            return $v ? __('admin.yes') : __('admin.no');
        }
        return is_scalar($v) ? (string) $v : null;
    };
    $scalars = array_filter($doc, fn ($v) => $fmt($v) !== null);
    $nested = array_diff_key($doc, $scalars);
?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-xl-8">
        <?php if(!empty($doc['products']) && is_array($doc['products'])): ?>
            <div class="card mb-3">
                <div class="card-header bg-white"><strong><?php echo e(__('admin.items')); ?></strong></div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th><?php echo e(__('admin.fields.name')); ?></th><th><?php echo e(__('admin.fields.quantity')); ?></th><th><?php echo e(__('admin.fields.price')); ?></th><th><?php echo e(__('admin.fields.extras')); ?></th></tr></thead>
                        <tbody>
                        <?php $__currentLoopData = $doc['products']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($p['name'] ?? ''); ?></td>
                                <td><?php echo e($p['quantity'] ?? ''); ?></td>
                                <td><?php echo e($p['discountPrice'] ?? $p['price'] ?? ''); ?></td>
                                <td class="small text-muted"><?php echo e(is_array($p['extras'] ?? null) ? implode(', ', $p['extras']) : ($p['extras'] ?? '')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <div class="card mb-3">
            <div class="card-header bg-white"><strong><?php echo e(__('admin.details')); ?></strong></div>
            <table class="table table-sm mb-0">
                <tr><th style="width:35%">ID</th><td><code><?php echo e($id); ?></code></td></tr>
                <?php $__currentLoopData = $scalars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr><th><?php echo e(\App\Admin\Fields::label($k)); ?></th><td class="text-break">
                        <?php if(is_string($v) && preg_match('#^https?://.+\.(png|jpe?g|gif|webp|svg)(\?.*)?$#i', $v)): ?>
                            <img src="<?php echo e($v); ?>" class="thumb" alt=""> <a href="<?php echo e($v); ?>" target="_blank" class="small"><?php echo e(\Illuminate\Support\Str::limit($v, 60)); ?></a>
                        <?php elseif($k === 'status'): ?>
                            <?php echo $__env->make('admin.partials.status', ['status' => $v], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php else: ?>
                            <?php echo e(\Illuminate\Support\Str::limit($fmt($v), 300)); ?>

                        <?php endif; ?>
                    </td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        </div>

        <?php $__currentLoopData = $nested; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card mb-3">
                <div class="card-header bg-white"><strong><?php echo e(\App\Admin\Fields::label($k)); ?></strong></div>
                <div class="card-body p-2"><pre class="json-view mb-0"><?php echo e(json_encode($v, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)); ?></pre></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="col-xl-4">
        <?php if($r['orders']): ?>
            <div class="card mb-3">
                <div class="card-header bg-white"><strong><?php echo e(__('admin.change_status')); ?></strong></div>
                <div class="card-body">
                    <p><?php echo $__env->make('admin.partials.status', ['status' => $doc['status'] ?? ''], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></p>
                    <form method="post" action="<?php echo e(route('admin.resource.status', [$name, $id])); ?>">
                        <?php echo csrf_field(); ?>
                        <select name="status" class="form-control mb-2">
                            <?php $__currentLoopData = $r['fields']['status']['options'] ?? config('admin.order_statuses'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($s); ?>" <?php if(($doc['status'] ?? '') === $s): echo 'selected'; endif; ?>><?php echo e($s); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button class="btn btn-primary btn-block"><?php echo e(__('admin.update')); ?></button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
        <div class="card">
            <div class="card-body">
                <?php if($r['edit'] && $r['fields']): ?>
                    <a class="btn btn-outline-primary btn-block" href="<?php echo e(route('admin.resource.edit', [$name, $id])); ?>"><i class="feather-edit-2"></i> <?php echo e(__('admin.edit')); ?></a>
                <?php endif; ?>
                <a class="btn btn-light btn-block" href="<?php echo e(route('admin.resource.index', $name)); ?>"><?php echo e(__('admin.back')); ?></a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\feimso\resources\views/admin/resource/show.blade.php ENDPATH**/ ?>