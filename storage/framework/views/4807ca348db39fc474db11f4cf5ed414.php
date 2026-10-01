<?php ($rtl = app()->getLocale() === 'ar'); ?>
<!doctype html>
<html lang="<?php echo e(app()->getLocale()); ?>" dir="<?php echo e($rtl ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(__('admin.login')); ?> · <?php echo e(config('app.name')); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('img/fav.png')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset($rtl ? 'vendor/bootstrap/css/bootstrap-rtl.min.css' : 'vendor/bootstrap/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin-assets/admin.css')); ?>">
</head>
<body class="admin d-flex align-items-center" style="min-height:100vh">
<div class="container" style="max-width:420px">
    <div class="text-center mb-4">
        <h3 class="mb-0"><?php echo e(config('app.name')); ?></h3>
        <div class="text-muted"><?php echo e(__('admin.panel')); ?></div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form method="post" action="<?php echo e(route('admin.login')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="email"><?php echo e(__('admin.fields.email')); ?></label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required autofocus>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label for="password"><?php echo e(__('admin.fields.password')); ?></label>
                    <input id="password" type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                    <label class="form-check-label" for="remember"><?php echo e(__('admin.remember_me')); ?></label>
                </div>
                <button class="btn btn-primary btn-block"><?php echo e(__('admin.login')); ?></button>
            </form>
        </div>
    </div>
    <div class="text-center mt-3">
        <a href="<?php echo e(route('admin.locale', $rtl ? 'en' : 'ar')); ?>"><?php echo e($rtl ? 'English' : 'العربية'); ?></a>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\wamp64\www\feimso\resources\views/admin/login.blade.php ENDPATH**/ ?>