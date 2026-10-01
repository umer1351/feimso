<?php
    // $input: form input name, $field: doc field, $def: field definition, $value: form value, $opts: select options
    $type = $def['type'];
    $id = 'f_' . preg_replace('/[^A-Za-z0-9_]/', '_', $input);
    $req = !empty($def['required']);
    $help = isset($def['help']) ? __('admin.help.' . $def['help']) : null;
    $help = $help === 'admin.help.' . ($def['help'] ?? '') ? $def['help'] : $help;
    $old = old($input, is_array($value) ? null : $value);
?>

<?php if($type === 'bool'): ?>
    <div class="form-group">
        <input type="hidden" name="<?php echo e($input); ?>" value="0">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="<?php echo e($id); ?>" name="<?php echo e($input); ?>" value="1" <?php if(old($input, $value ? '1' : '0') === '1' || (old($input) === null && $value)): echo 'checked'; endif; ?>>
            <label class="custom-control-label" for="<?php echo e($id); ?>"><?php echo e(\App\Admin\Fields::label($field)); ?></label>
        </div>
        <?php if($help): ?><div class="help"><?php echo e($help); ?></div><?php endif; ?>
    </div>
<?php else: ?>
<div class="form-group">
    <label for="<?php echo e($id); ?>"><?php echo e(\App\Admin\Fields::label($field)); ?> <?php if($req): ?><span class="text-danger">*</span><?php endif; ?></label>

    <?php switch($type):
        case ('textarea'): ?>
            <textarea id="<?php echo e($id); ?>" name="<?php echo e($input); ?>" rows="3" class="form-control"><?php echo e($old); ?></textarea>
            <?php break; ?>
        <?php case ('html'): ?>
            <textarea id="<?php echo e($id); ?>" name="<?php echo e($input); ?>" rows="10" class="form-control code"><?php echo e($old); ?></textarea>
            <?php break; ?>
        <?php case ('json'): ?>
            <textarea id="<?php echo e($id); ?>" name="<?php echo e($input); ?>" rows="8" class="form-control code" spellcheck="false"><?php echo e($old); ?></textarea>
            <?php break; ?>
        <?php case ('images'): ?>
            <textarea id="<?php echo e($id); ?>" name="<?php echo e($input); ?>" rows="3" class="form-control code" placeholder="<?php echo e(__('admin.one_url_per_line')); ?>"><?php echo e($old); ?></textarea>
            <input type="file" name="<?php echo e($input); ?>_files[]" accept="image/*" multiple class="form-control-file mt-2">
            <?php break; ?>
        <?php case ('polygon'): ?>
            <textarea id="<?php echo e($id); ?>" name="<?php echo e($input); ?>" rows="6" class="form-control code" placeholder="25.20,55.27"><?php echo e($old); ?></textarea>
            <?php break; ?>
        <?php case ('select'): ?>
            <select id="<?php echo e($id); ?>" name="<?php echo e($input); ?>" class="form-control" <?php if($req): echo 'required'; endif; ?>>
                <option value="">—</option>
                <?php $__currentLoopData = $opts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($val); ?>" <?php if((string) $old === (string) $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php break; ?>
        <?php case ('multiselect'): ?>
            <?php ($selected = old($input, $value ?: [])); ?>
            <select id="<?php echo e($id); ?>" name="<?php echo e($input); ?>[]" class="form-control" multiple size="<?php echo e(min(8, max(3, count($opts)))); ?>">
                <?php $__currentLoopData = $opts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($val); ?>" <?php if(in_array((string) $val, array_map('strval', (array) $selected), true)): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <div class="help"><?php echo e(__('admin.multiselect_help')); ?></div>
            <?php break; ?>
        <?php case ('image'): ?>
            <div class="input-group">
                <input id="<?php echo e($id); ?>" type="url" name="<?php echo e($input); ?>" value="<?php echo e($old); ?>" class="form-control code" placeholder="https://">
            </div>
            <input type="file" name="<?php echo e($input); ?>_file" accept="image/*" class="form-control-file mt-2">
            <?php if($value): ?><img src="<?php echo e($value); ?>" class="img-preview" alt=""><?php endif; ?>
            <?php break; ?>
        <?php case ('latlng'): ?>
            <div class="form-row">
                <div class="col"><input type="text" name="<?php echo e($input); ?>_lat" value="<?php echo e(old($input . '_lat', $value['lat'] ?? '')); ?>" class="form-control code" placeholder="<?php echo e(__('admin.latitude')); ?>" <?php if($req): echo 'required'; endif; ?>></div>
                <div class="col"><input type="text" name="<?php echo e($input); ?>_lng" value="<?php echo e(old($input . '_lng', $value['lng'] ?? '')); ?>" class="form-control code" placeholder="<?php echo e(__('admin.longitude')); ?>" <?php if($req): echo 'required'; endif; ?>></div>
            </div>
            <?php break; ?>
        <?php case ('datetime'): ?>
            <input id="<?php echo e($id); ?>" type="datetime-local" name="<?php echo e($input); ?>" value="<?php echo e($old); ?>" class="form-control" <?php if($req): echo 'required'; endif; ?>>
            <?php break; ?>
        <?php case ('password'): ?>
            <input id="<?php echo e($id); ?>" type="password" name="<?php echo e($input); ?>" class="form-control" autocomplete="new-password">
            <?php break; ?>
        <?php case ('secret'): ?>
            <input id="<?php echo e($id); ?>" type="password" name="<?php echo e($input); ?>" class="form-control code" autocomplete="off" placeholder="<?php echo e(!empty($isSet) ? '••••••••  (' . __('admin.secret_set') . ')' : __('admin.secret_not_set')); ?>">
            <?php if(!empty($isSet)): ?>
                <div class="custom-control custom-checkbox mt-1 secret-state">
                    <input type="checkbox" class="custom-control-input" id="<?php echo e($id); ?>_clear" name="<?php echo e($input); ?>_clear" value="1">
                    <label class="custom-control-label" for="<?php echo e($id); ?>_clear"><?php echo e(__('admin.secret_clear')); ?></label>
                </div>
            <?php endif; ?>
            <?php break; ?>
        <?php case ('color'): ?>
            <input id="<?php echo e($id); ?>" type="color" name="<?php echo e($input); ?>" value="<?php echo e($old ?: '#ff3b5c'); ?>" class="form-control" style="max-width:90px">
            <?php break; ?>
        <?php case ('number'): ?>
        <?php case ('money'): ?>
            <input id="<?php echo e($id); ?>" type="number" step="any" name="<?php echo e($input); ?>" value="<?php echo e($old); ?>" class="form-control" <?php if($req): echo 'required'; endif; ?>>
            <?php break; ?>
        <?php case ('integer'): ?>
            <input id="<?php echo e($id); ?>" type="number" step="1" name="<?php echo e($input); ?>" value="<?php echo e($old); ?>" class="form-control" <?php if($req): echo 'required'; endif; ?>>
            <?php break; ?>
        <?php default: ?>
            <input id="<?php echo e($id); ?>" type="<?php echo e($type === 'email' ? 'email' : 'text'); ?>" name="<?php echo e($input); ?>" value="<?php echo e($old); ?>" class="form-control" <?php if($req): echo 'required'; endif; ?>>
    <?php endswitch; ?>

    <?php if($help): ?><div class="help"><?php echo e($help); ?></div><?php endif; ?>
    <?php $__errorArgs = [$input];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php endif; ?>
<?php /**PATH C:\wamp64\www\feimso\resources\views/admin/partials/field.blade.php ENDPATH**/ ?>