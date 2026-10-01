<?php $__env->startSection('title', __("admin.resources.$name")); ?>

<?php
    $cols = [];
    foreach ($r['columns'] as $k => $v) {
        is_int($k) ? $cols[$v] = '' : $cols[$k] = $v;
    }
    $sort = request('sort', '_created:desc');
?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-body pb-2">
        <form class="form-row align-items-end" method="get">
            <div class="col-md-4 mb-2">
                <input type="search" name="q" value="<?php echo e(request('q')); ?>" class="form-control" placeholder="<?php echo e(__('admin.search')); ?>">
            </div>
            <?php $__currentLoopData = $r['filters']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $def): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-2 mb-2">
                    <select name="f_<?php echo e($field); ?>" class="form-control" onchange="this.form.submit()">
                        <option value=""><?php echo e(\App\Admin\Fields::label($field)); ?>: <?php echo e(__('admin.all')); ?></option>
                        <?php $__currentLoopData = $filterOptions[$field]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val); ?>" <?php if((string) ($filters[$field] ?? '') === (string) $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="col-auto mb-2">
                <button class="btn btn-secondary"><i class="feather-search"></i></button>
                <?php if(request()->hasAny(['q', 'sort']) || collect(request()->query())->keys()->contains(fn ($k) => str_starts_with($k, 'f_'))): ?>
                    <a class="btn btn-link" href="<?php echo e(route('admin.resource.index', $name)); ?>"><?php echo e(__('admin.reset')); ?></a>
                <?php endif; ?>
            </div>
            <?php if($r['create']): ?>
                <div class="col text-right mb-2" style="text-align:end!important">
                    <a class="btn btn-primary" href="<?php echo e(route('admin.resource.create', $name)); ?>"><i class="feather-plus"></i> <?php echo e(__('admin.add_new')); ?></a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <?php $__currentLoopData = $cols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $kind): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php ($sortable = $kind !== 'image' && !str_starts_with($kind, 'path:')); ?>
                    <th>
                        <?php if($sortable): ?>
                            <?php ($next = $sort === "$field:asc" ? "$field:desc" : "$field:asc"); ?>
                            <a class="text-reset" href="<?php echo e(request()->fullUrlWithQuery(['sort' => $next, 'page' => null])); ?>"><?php echo e(\App\Admin\Fields::label($field)); ?>

                                <?php if(str_starts_with($sort, "$field:")): ?><i class="feather-chevron-<?php echo e(str_ends_with($sort, 'desc') ? 'down' : 'up'); ?>"></i><?php endif; ?>
                            </a>
                        <?php else: ?>
                            <?php echo e(\App\Admin\Fields::label($field)); ?>

                        <?php endif; ?>
                    </th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th class="text-right" style="text-align:end!important"><?php echo e(__('admin.actions')); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $docs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <?php $__currentLoopData = $cols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $kind): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td><?php echo $__env->make('admin.partials.cell', ['data' => $doc['data'], 'field' => $field, 'kind' => $kind], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <td class="text-nowrap" style="text-align:end">
                        <a class="btn btn-sm btn-light" href="<?php echo e(route('admin.resource.show', [$name, $doc['id']])); ?>" title="<?php echo e(__('admin.view')); ?>"><i class="feather-eye"></i></a>
                        <?php if($r['edit'] && $r['fields']): ?>
                            <a class="btn btn-sm btn-light" href="<?php echo e(route('admin.resource.edit', [$name, $doc['id']])); ?>" title="<?php echo e(__('admin.edit')); ?>"><i class="feather-edit-2"></i></a>
                        <?php endif; ?>
                        <?php if($r['delete']): ?>
                            <form method="post" action="<?php echo e(route('admin.resource.destroy', [$name, $doc['id']])); ?>" class="d-inline" onsubmit="return confirm(<?php echo json_encode(__('admin.confirm_delete'), 15, 512) ?>)">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-light text-danger" title="<?php echo e(__('admin.delete')); ?>"><i class="feather-trash-2"></i></button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="<?php echo e(count($cols) + 1); ?>" class="text-center text-muted py-5"><?php echo e(__('admin.no_records')); ?></td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($docs->hasPages()): ?>
        <div class="card-body pt-3 pb-0 d-flex justify-content-between align-items-center">
            <span class="text-muted"><?php echo e(__('admin.showing', ['from' => $docs->firstItem(), 'to' => $docs->lastItem(), 'total' => $docs->total()])); ?></span>
            <?php echo e($docs->onEachSide(1)->links('pagination::bootstrap-4')); ?>

        </div>
    <?php else: ?>
        <div class="card-footer bg-white text-muted"><?php echo e(__('admin.total', ['total' => $docs->total()])); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\feimso\resources\views/admin/resource/index.blade.php ENDPATH**/ ?>