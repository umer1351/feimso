
<?php
    $cart = $cart ?? [];
    $isCheckout = !empty($is_checkout) || request()->boolean('is_checkout') || request()->routeIs('checkout');
    $digits = (int) ($cart['decimal_degits'] ?? 2);
    $money = fn ($v) => number_format((float) $v, $digits, '.', '');

    $vendorId = $cart['vendor']['id'] ?? (isset($cart['item']) && is_array($cart['item']) ? array_key_first($cart['item']) : null);
    $items = ($vendorId && !empty($cart['item'][$vendorId]) && is_array($cart['item'][$vendorId])) ? $cart['item'][$vendorId] : [];

    $subtotal = 0;
    foreach ($items as $it) {
        $qty = (float) ($it['quantity'] ?? 0);
        $subtotal += (float) ($it['item_price'] ?? 0) * $qty + (float) ($it['extra_price'] ?? 0) * $qty;
    }

    $coupon = $cart['coupon'] ?? [];
    $couponAmount = 0;
    if (!empty($coupon['coupon_code'])) {
        $d = (float) ($coupon['discount'] ?? 0);
        $couponAmount = ($coupon['discountType'] ?? '') === 'Fix Price' ? min($d, $subtotal) : min($subtotal * $d / 100, $subtotal);
    }

    $specialType = $cart['specialOfferType'] ?? '';
    $specialVal = (float) ($cart['specialOfferDiscountVal'] ?? 0);
    $special = $specialType === 'amount' ? $specialVal : ($specialType === 'percentage' ? $subtotal * $specialVal / 100 : 0);
    $special = min($special, max(0, $subtotal - $couponAmount));

    $taxBase = max(0, $subtotal - $couponAmount - $special);
    $taxRows = [];
    $tax = 0;
    foreach ((array) ($cart['taxValue'] ?? []) as $t) {
        if (!is_array($t) || !isset($t['tax'])) {
            continue;
        }
        $isPct = ($t['type'] ?? '') === 'percentage';
        $amount = $isPct ? (float) $t['tax'] * $taxBase / 100 : (float) $t['tax'];
        $tax += $amount;
        $taxRows[] = ['title' => $t['title'] ?? '', 'label' => $isPct ? ((float) $t['tax']) . '%' : null, 'amount' => $amount];
    }

    $takeaway = ($cart['delivery_option'] ?? '') === 'takeaway' || Session::get('takeawayOption') === 'true';
    $selfDelivery = in_array($cart['isSelfDelivery'] ?? false, [true, 'true', 1, '1'], true);
    $delivery = $takeaway ? 0 : (float) ($cart['deliverycharge'] ?? 0);
    $tip = $takeaway ? 0 : (float) ($cart['tip_amount'] ?? 0);
    $total = $taxBase + $tax + $delivery + $tip;
    $tipOptions = [10, 20, 30];
?>

<?php if(!empty($order_complete)): ?>
    <div class="d-flex siddhi-cart-item-profile bg-white p-3">
        <p class="m-0"><?php echo e(trans('lang.your_order_placed_successfully')); ?></p>
    </div>
<?php else: ?>

<?php if($vendorId && !empty($cart['vendor']['name'])): ?>
    <div class="bg-white p-3 border-bottom" id="vendor_place">
        <div class="d-flex align-items-center">
            <?php if(!empty($cart['vendor']['image'])): ?>
                <img id="vendor_image_place" src="<?php echo e($cart['vendor']['image']); ?>" class="rounded mr-2" style="width:44px;height:44px;object-fit:cover" alt="">
            <?php endif; ?>
            <div>
                <h6 class="mb-0" id="vendor_name_place"><?php echo e($cart['vendor']['name']); ?></h6>
                <p class="mb-0 small text-muted" id="vendor_location_place"><i class="feather-map-pin"></i> <?php echo e($cart['vendor']['location'] ?? ''); ?></p>
            </div>
        </div>
    </div>
<?php endif; ?>

<input type="hidden" id="main_vendor_id" value="<?php echo e($vendorId); ?>">

<?php if(count($items)): ?>
    <div class="bg-white p-3 sidebar-item-list">
        <h6 class="pb-3 mb-0"><?php echo e(trans('lang.cart_details')); ?></h6>
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $qty = (int) ($it['quantity'] ?? 0);
                $line = ((float) ($it['item_price'] ?? 0) + (float) ($it['extra_price'] ?? 0)) * $qty;
                $variant = is_array($it['variant_info'] ?? null) ? $it['variant_info'] : null;
                $extras = is_array($it['extra'] ?? null) ? $it['extra'] : (empty($it['extra']) ? [] : explode(',', (string) $it['extra']));
                $vqty = $variant['variant_qty'] ?? ($it['stock_quantity'] ?? -1);
            ?>
            <div class="product-item gold-members row align-items-center py-2 border mb-2 rounded-lg m-0" data-id="<?php echo e($key); ?>">
                
                <input type="hidden" id="price_<?php echo e($key); ?>" value="<?php echo e($it['price'] ?? 0); ?>">
                <input type="hidden" id="dis_price_<?php echo e($key); ?>" value="<?php echo e($it['dis_price'] ?? ''); ?>">
                <input type="hidden" id="item_price_<?php echo e($key); ?>" value="<?php echo e($it['item_price'] ?? 0); ?>">
                <input type="hidden" id="photo_<?php echo e($key); ?>" value="<?php echo e($it['image'] ?? ''); ?>">
                <input type="hidden" id="extras_price_<?php echo e($key); ?>" value="<?php echo e($it['extra_price'] ?? 0); ?>">
                <input type="hidden" id="size_<?php echo e($key); ?>" value="<?php echo e($it['size'] ?? ''); ?>">
                <input type="hidden" id="name_<?php echo e($key); ?>" value="<?php echo e($it['name'] ?? ''); ?>">
                <input type="hidden" id="quantity_<?php echo e($key); ?>" value="<?php echo e($qty); ?>">
                <input type="hidden" id="category_id_<?php echo e($key); ?>" value="<?php echo e($it['category_id'] ?? ''); ?>">
                <?php if($variant): ?>
                    <input type="hidden" id="variant_info_<?php echo e($key); ?>" value="<?php echo e(base64_encode(json_encode($variant))); ?>">
                <?php endif; ?>
                <?php $__currentLoopData = $extras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <input type="hidden" class="extras_<?php echo e($key); ?>" value="<?php echo e($ex); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <div class="media align-items-center col-6 px-1">
                    <div class="media-body">
                        <p class="m-0"><?php echo e($it['name'] ?? ''); ?></p>
                        <?php if($variant && !empty($variant['variant_options']) && is_array($variant['variant_options'])): ?>
                            <div class="variant-info"><ul class="m-0 p-0">
                                <?php $__currentLoopData = $variant['variant_options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="variant"><span class="label"><?php echo e($label); ?></span> <span class="value"><?php echo e($value); ?></span></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul></div>
                        <?php endif; ?>
                        <?php if(!empty($it['size'])): ?><small class="text-muted d-block"><?php echo e(trans('lang.size')); ?>: <?php echo e($it['size']); ?></small><?php endif; ?>
                        <?php if(count($extras)): ?><small class="text-muted d-block"><?php echo e(trans('lang.extra')); ?>: <?php echo e(implode(', ', $extras)); ?></small><?php endif; ?>
                    </div>
                </div>
                <div class="d-flex align-items-center count-number-box col-5 px-1">
                    <span class="count-number">
                        <button type="button" class="count-number-input-cart btn-sm left dec btn btn-outline-secondary" data-id="<?php echo e($key); ?>" data-vendor="<?php echo e($vendorId); ?>" data-vqty="<?php echo e($vqty); ?>"><i class="feather-minus"></i></button>
                        <input class="count-number-input count_number_<?php echo e($key); ?>" type="text" readonly value="<?php echo e($qty); ?>">
                        <button type="button" class="count-number-input-cart btn-sm right inc btn btn-outline-secondary" data-id="<?php echo e($key); ?>" data-vendor="<?php echo e($vendorId); ?>" data-vqty="<?php echo e($vqty); ?>" data-vqtymsg="<?php echo e(trans('lang.invalid_stock_qty')); ?>"><i class="feather-plus"></i></button>
                    </span>
                    <p class="text-gray mb-0 ml-2 text-muted small">
                        <span class="currency-symbol-left"></span><span class="cart_iteam_total_<?php echo e($key); ?>"><?php echo e($money($line)); ?></span><span class="currency-symbol-right"></span>
                    </p>
                </div>
                <div class="close remove_item col-1 px-1 text-center" data-id="<?php echo e($key); ?>" data-vendor="<?php echo e($vendorId); ?>"><i class="fa fa-times"></i></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="bg-white px-3 clearfix">
        <div class="border-bottom pb-3">
            <div class="input-group-sm mb-2 input-group">
                <input placeholder="<?php echo e(trans('lang.promo_help')); ?>" value="<?php echo e($coupon['coupon_code'] ?? ''); ?>" id="coupon_code" data-vendor-id="<?php echo e($vendorId); ?>" type="text" class="form-control">
                <div class="input-group-append">
                    <button type="button" class="btn btn-primary" id="apply-coupon-code"><i class="feather-percent"></i> <?php echo e(trans('lang.apply')); ?></button>
                </div>
            </div>
        </div>
    </div>

    
    <input type="hidden" name="delivery_option" value="<?php echo e($takeaway ? 'takeaway' : 'delivery'); ?>">

    <?php if(!$takeaway): ?>
        <div class="bg-white clearfix delevery-partner">
            <div class="border-bottom p-3">
                <h3><?php echo e(trans('lang.tip_your_delivery_partner')); ?></h3>
                <div class="tip-box">
                    <?php $__currentLoopData = $tipOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="custom-control custom-radio border-bottom py-2">
                            <input type="radio" name="tip" id="tip_<?php echo e($opt); ?>" value="<?php echo e($opt); ?>" class="this_tip custom-control-input <?php echo e((float) $tip === (float) $opt ? 'tip_checked' : ''); ?>" <?php if((float) $tip === (float) $opt): echo 'checked'; endif; ?>>
                            <label class="custom-control-label" for="tip_<?php echo e($opt); ?>"><span class="currency-symbol-left"></span><?php echo e($opt); ?><span class="currency-symbol-right"></span></label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="custom-control custom-radio border-bottom py-2">
                        <input type="radio" name="tip" id="Other_tip" value="other" class="custom-control-input" <?php if($tip > 0 && !in_array((float) $tip, array_map('floatval', $tipOptions), true)): echo 'checked'; endif; ?>>
                        <label class="custom-control-label" for="Other_tip"><?php echo e(trans('lang.other')); ?></label>
                    </div>
                    <div class="custom-control custom-radio border-bottom py-2" id="add_tip_box" style="display:none">
                        <input type="number" min="0" step="any" id="tip_amount" class="form-control" value="<?php echo e($tip ?: ''); ?>" onchange="tipAmountChange('plus')">
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <input type="hidden" id="tip_amount" value="0">
    <?php endif; ?>

    <?php if($isCheckout): ?>
        <div class="bg-white px-3 clearfix schedule-order pt-3">
            <div class="border-bottom pb-3">
                <h3><?php echo e(trans('lang.schedule_order')); ?></h3>
                <span class="text-dark"><input type="datetime-local" id="scheduleTime" name="scheduleTime" class="form-control"></span>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white p-3 clearfix btm-total">
        <p class="mb-2"><?php echo e(trans('lang.sub_total')); ?> <span class="float-right text-dark"><span class="currency-symbol-left"></span><?php echo e($money($subtotal)); ?><span class="currency-symbol-right"></span></span></p>

        <?php if($couponAmount > 0): ?>
            <p class="mb-2 text-success"><?php echo e(trans('lang.discount')); ?> (<?php echo e($coupon['coupon_code']); ?>) <span class="float-right text-success">- <span class="currency-symbol-left"></span><?php echo e($money($couponAmount)); ?><span class="currency-symbol-right"></span></span></p>
        <?php endif; ?>
        <?php if($special > 0): ?>
            <p class="mb-2 text-success"><?php echo e(trans('lang.special_discount')); ?> <span class="float-right text-success">- <span class="currency-symbol-left"></span><?php echo e($money($special)); ?><span class="currency-symbol-right"></span></span></p>
        <?php endif; ?>

        <?php $__currentLoopData = $taxRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <p class="mb-2"><?php echo e($t['title']); ?> <?php if($t['label']): ?>(<?php echo e($t['label']); ?>)<?php endif; ?> <span class="float-right text-dark"><span class="currency-symbol-left"></span><?php echo e($money($t['amount'])); ?><span class="currency-symbol-right"></span></span></p>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($takeaway): ?>
            <p class="mb-2"><?php echo e(trans('lang.take_away')); ?> <span class="float-right text-dark"><?php echo e(trans('lang.free')); ?></span></p>
        <?php elseif($selfDelivery): ?>
            <p class="mb-2"><?php echo e(trans('lang.deliveryCharge')); ?> <span class="float-right text-success"><?php echo e(trans('lang.free_delivery')); ?></span></p>
        <?php else: ?>
            <p class="mb-2"><?php echo e(trans('lang.deliveryCharge')); ?> <span class="float-right text-dark"><span class="currency-symbol-left"></span><?php echo e($money($delivery)); ?><span class="currency-symbol-right"></span></span></p>
        <?php endif; ?>

        <?php if($tip > 0): ?>
            <p class="mb-2"><?php echo e(trans('lang.tip_amount')); ?> <span class="float-right text-dark"><span class="currency-symbol-left"></span><?php echo e($money($tip)); ?><span class="currency-symbol-right"></span></span></p>
        <?php endif; ?>

        <hr>
        <h6 class="font-weight-bold mb-0"><?php echo e(trans('lang.total')); ?> <span class="float-right"><span class="currency-symbol-left"></span><?php echo e($money($total)); ?><span class="currency-symbol-right"></span></span></h6>
    </div>

    
    <input type="hidden" id="total_pay" value="<?php echo e(round($total, 2)); ?>">
    <input type="hidden" id="coupon_code_main" value="<?php echo e($coupon['coupon_code'] ?? ''); ?>">
    <input type="hidden" id="coupon_id" value="<?php echo e($coupon['coupon_id'] ?? ''); ?>">
    <input type="hidden" id="discount_amount" value="<?php echo e(round($couponAmount, 2)); ?>">
    <input type="hidden" id="deliveryCharge" value="<?php echo e($delivery); ?>">
    <input type="hidden" id="deliveryChargeMain" value="<?php echo e($cart['deliverychargemain'] ?? 0); ?>">
    <input type="hidden" id="adminCommission" value="0">
    <input type="hidden" id="adminCommissionType" value="fixed">
    <input type="hidden" id="tax_label" value="">
    <input type="hidden" id="tax" value="<?php echo e(round($tax, 2)); ?>">
    <input type="hidden" id="specialOfferDiscountAmount" value="<?php echo e(round($special, 2)); ?>">
    <input type="hidden" id="specialOfferType" value="<?php echo e($specialType); ?>">
    <input type="hidden" id="specialOfferDiscountVal" value="<?php echo e($specialVal); ?>">

    <div class="p-3">
        <?php if($isCheckout): ?>
            <a class="btn btn-primary btn-block btn-lg" href="javascript:void(0)" onclick="finalCheckout()">
                <?php echo e(trans('lang.pay')); ?> <span class="currency-symbol-left"></span><?php echo e($money($total)); ?><span class="currency-symbol-right"></span> <i class="feather-arrow-right"></i>
            </a>
        <?php else: ?>
            <a class="btn btn-primary btn-block btn-lg" href="<?php echo e(route('checkout')); ?>"><?php echo e(trans('lang.checkout')); ?> <i class="feather-arrow-right"></i></a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="bg-white py-2">
        <div class="gold-members d-flex align-items-center justify-content-between px-3 py-3">
            <span><?php echo e(trans('lang.your_cart_is_empty')); ?></span>
        </div>
    </div>
    <input type="hidden" id="total_pay" value="0">
    <input type="hidden" name="delivery_option" value="delivery">
<?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\wamp64\www\feimso\resources\views/vendor/cart_item.blade.php ENDPATH**/ ?>