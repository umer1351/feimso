<?php
    // $kind: '', image, bool, datetime, status, ref:coll.label, path:a.b
    $v = str_starts_with($kind, 'path:') ? data_get($data, substr($kind, 5)) : ($data[$field] ?? null);
?>
<?php if($kind === 'image'): ?>
    <?php if(is_string($v) && $v !== ''): ?><img src="<?php echo e($v); ?>" class="thumb" alt="" loading="lazy"><?php else: ?><span class="thumb d-inline-block"></span><?php endif; ?>
<?php elseif($kind === 'bool'): ?>
    <?php if($v): ?><span class="badge badge-success"><?php echo e(__('admin.yes')); ?></span><?php else: ?><span class="badge badge-light"><?php echo e(__('admin.no')); ?></span><?php endif; ?>
<?php elseif($kind === 'datetime'): ?>
    <?php if(is_array($v) && isset($v['s'])): ?><?php echo e(\Carbon\Carbon::createFromTimestamp($v['s'])->format('Y-m-d H:i')); ?><?php else: ?><span class="text-muted">—</span><?php endif; ?>
<?php elseif($kind === 'status'): ?>
    <?php echo $__env->make('admin.partials.status', ['status' => $v], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php elseif(str_starts_with($kind, 'ref:')): ?>
    <?php echo e($refs[substr($kind, 4)][$v] ?? ($v ?: '—')); ?>

<?php else: ?>
    <bdi><?php echo e(is_scalar($v) ? \Illuminate\Support\Str::limit((string) $v, 60) : ($v === null ? '—' : \Illuminate\Support\Str::limit(json_encode($v, JSON_UNESCAPED_UNICODE), 60))); ?></bdi>
<?php endif; ?>
<?php /**PATH C:\wamp64\www\feimso\resources\views/admin/partials/cell.blade.php ENDPATH**/ ?>