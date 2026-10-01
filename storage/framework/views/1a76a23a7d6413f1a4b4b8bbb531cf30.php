<?php ($rtl = app()->getLocale() === 'ar'); ?>
<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e($rtl ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', __('admin.dashboard')); ?> · <?php echo e(__('admin.panel')); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('img/fav.png')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset($rtl ? 'vendor/bootstrap/css/bootstrap-rtl.min.css' : 'vendor/bootstrap/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/icons/feather.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin-assets/admin.css')); ?>">
</head>
<body class="admin">
<div class="admin-shell">
    <nav class="admin-side">
        <a class="brand" href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(config('app.name')); ?> <small class="text-muted"><?php echo e(__('admin.panel')); ?></small></a>
        <a class="item <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>"><i class="feather-home"></i> <?php echo e(__('admin.dashboard')); ?></a>
        <?php $__currentLoopData = config('admin.groups'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $names): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group"><?php echo e(__("admin.groups.$group")); ?></div>
            <?php $__currentLoopData = $names; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a class="item <?php echo e(request()->route('name') === $n ? 'active' : ''); ?>" href="<?php echo e(route('admin.resource.index', $n)); ?>">
                    <i class="feather-<?php echo e(config("admin.resources.$n.icon", 'circle')); ?>"></i> <?php echo e(__("admin.resources.$n")); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="group"><?php echo e(__('admin.settings')); ?></div>
        <?php $__currentLoopData = config('admin_settings'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tabKey => $tabDef): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a class="item <?php echo e(request()->routeIs('admin.settings') && request()->route('tab', 'general') === $tabKey ? 'active' : ''); ?>" href="<?php echo e(route('admin.settings', $tabKey)); ?>">
                <i class="feather-<?php echo e($tabDef['icon']); ?>"></i> <?php echo e(__("admin.settings_tabs.$tabKey")); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="pb-4"></div>
    </nav>

    <div class="admin-main">
        <header class="admin-top">
            <div class="d-flex align-items-center" style="gap:10px">
                <button class="btn btn-sm btn-light side-toggle" type="button" onclick="document.body.classList.toggle('side-open')"><i class="feather-menu"></i></button>
                <h1><?php echo $__env->yieldContent('title', __('admin.dashboard')); ?></h1>
            </div>
            <div class="d-flex align-items-center" style="gap:8px">
                <a class="btn btn-sm btn-light" href="<?php echo e(url('/')); ?>" target="_blank"><i class="feather-external-link"></i> <?php echo e(__('admin.view_site')); ?></a>
                <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('admin.locale', $rtl ? 'en' : 'ar')); ?>"><?php echo e($rtl ? 'English' : 'العربية'); ?></a>
                <span class="text-muted d-none d-md-inline"><?php echo e(auth()->user()->email); ?></span>
                <form method="post" action="<?php echo e(route('admin.logout')); ?>" class="m-0"><?php echo csrf_field(); ?>
                    <button class="btn btn-sm btn-outline-danger"><i class="feather-log-out"></i> <?php echo e(__('admin.logout')); ?></button>
                </form>
            </div>
        </header>

        <main class="admin-content">
            <?php if(session('ok')): ?>
                <div class="alert alert-success"><?php echo e(session('ok')); ?></div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <strong><?php echo e(__('admin.fix_errors')); ?></strong>
                    <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
                </div>
            <?php endif; ?>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</div>
<script src="<?php echo e(asset('vendor/jquery/jquery.min.js')); ?>"></script>
<script src="<?php echo e(asset($rtl ? 'vendor/bootstrap/js/bootstrap-rtl.bundle.min.js' : 'vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\wamp64\www\feimso\resources\views/admin/layout.blade.php ENDPATH**/ ?>