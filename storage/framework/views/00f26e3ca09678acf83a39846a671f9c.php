<?php session_start(); ?>
<?php if(@$order_complete): ?>
    <div class="d-flex siddhi-cart-item-profile bg-white p-3">
        <p><?php echo e(trans('lang.your_order_placed_successfully')); ?></p>
    </div>
<?php endif; ?>
<?php if(@$service_charge_cart): ?>
    <?php
        $digit_decimal = 0;
        $total_price = 0;
    ?>
    <?php if(@$service_charge_cart['decimal_degits']): ?>
        <?php $digit_decimal = $service_charge_cart['decimal_degits']; ?>
    <?php endif; ?>
    <input type="hidden" id="extraCharge" value="<?php echo e(@$service_charge_cart['extra_charge']); ?>">
    <input type="hidden" id="orderId" value="<?php echo e(@$service_charge_cart['order_id']); ?>">
    <input type="hidden" id="serviceTotal" value="<?php echo e(@$service_charge_cart['service_total']); ?>">
    <input type="hidden" id="provider_id" value="<?php echo e(@$service_charge_cart['provider_id']); ?>">
    <div class="bg-white p-3 sidebar-item-list">
        <h6 class="pb-3"><?php echo e(trans('lang.price_details')); ?></h6>
    </div>
    <?php
        $total_price = floatval(@$service_charge_cart['sub_total']);
    ?>
    <div class="bg-white px-3 clearfix">
        <div class="border-bottom pb-3">
            <div class="input-group-sm mb-2 input-group">
                <input placeholder="<?php echo e(trans('lang.promo_help')); ?>"
                       value="<?php echo e(@$service_charge_cart['coupon']['coupon_code']); ?>"
                       id="coupon_code" type="text" class="form-control">
                <div class="input-group-append">
                    <button type="button" class="btn btn-primary" data-id="<?php echo e(@$service_charge_cart['id']); ?>"
                            data-provider="<?php echo e(@$service_charge_cart['provider_id']); ?>" id="apply-coupon-code">
                        <i class="feather-percent"></i> <?php echo e(trans('lang.apply')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white p-3 clearfix btm-total">
        <p class="mb-2">
            <?php echo e(trans('lang.sub_total')); ?>

            <span class="float-right text-dark">
            <span class="currency-symbol-left"></span>
            <?php echo e(number_format($total_price, $digit_decimal)); ?>

            <span class="currency-symbol-right"></span>
        </span>
        </p>
        <?php $discount_amount = 0;
    $coupon_id = '';
    $coupon_code = '';
    $discount = '';
    $discountType = '';
        ?>
        <?php if(@$service_charge_cart['coupon'] && $service_charge_cart['coupon']['discountType']): ?>
            <hr>
            <p class="mb-1 text-success">
                <?php
                    $discountType = @$service_charge_cart['coupon']['discountType'];
                    $coupon_code = @$service_charge_cart['coupon']['coupon_code'];
                    $coupon_id = @$service_charge_cart['coupon']['coupon_id'];
                    $discount = @$service_charge_cart['coupon']['discount'];
                ?>
                <?php if($discountType == "Fix Price"): ?>
                    <?php
                        $discount_amount = floatval($service_charge_cart['coupon']['discount']);
                    ?>
                    <?php if($discount_amount > $total_price): ?>
                        <?php $discount_amount = $total_price; ?>
                    <?php endif; ?>
                    <?php if($total_price < 0): ?>
                        <?php $total_price=0; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <?php
                        $discount_amount=$service_charge_cart['coupon']['discount'];
                        $discount_amount=($total_price *$discount_amount) / 100;
                    ?>
                    <?php if($discount_amount> $total_price): ?>
                        <?php $discount_amount = $total_price;?>
                    <?php endif; ?>
                    <?php if($total_price < 0): ?>
                        <?php $total_price=0; ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?php echo e(trans('lang.total')); ?>

                <?php echo e(trans('lang.discount')); ?> <span class="float-right text-success">
                <span class="currency-symbol-left"></span>
                <?php echo e(number_format($discount_amount, $digit_decimal)); ?>

                <span class="currency-symbol-right"></span></span>
            </p>
        <?php else: ?>
        <?php endif; ?>
        <input type="hidden" id="discount_amount" value="<?php echo e($discount_amount); ?>">
        <input type="hidden" id="coupon_id" value="<?php echo e($coupon_id); ?>">
        <input type="hidden" id="coupon_code_main" value="<?php echo e($coupon_code); ?>">
        <input type="hidden" id="discount" value="<?php echo e($discount); ?>">
        <input type="hidden" id="discountType" value="<?php echo e($discountType); ?>">
        <?php
            $total_price = $total_price - $discount_amount;
        ?>
        <?php if($total_price && @$service_charge_cart['taxValue']): ?>
            <hr>
            <?php $total_tax_amount=0; ?>
            <?php $__currentLoopData = $service_charge_cart['taxValue']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <p class="mb-2"><?php echo e($val['title']); ?>

                    <?php if($val['type'] == 'fix'): ?>
                        ( <span class="currency-symbol-left"></span>
                        <?php echo e(number_format(floatval($val['tax']), $digit_decimal)); ?>

                        <?php $tax = $val['tax']; ?>
                        <span class="currency-symbol-right"></span> )
                    <?php else: ?>
                        <?php $tax = (floatval($val['tax']) * $total_price) / 100; ?>
        (<?php echo e($val['tax']); ?>%)
                    <?php endif; ?>
                    <span class="float-right text-dark">
            <span class="currency-symbol-left"></span>
            <?php echo e(number_format(floatval($tax), $digit_decimal)); ?>

            <span class="currency-symbol-right"></span>
        </span>
                </p>
                <?php $total_tax_amount = $total_tax_amount + $tax; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php $total_price=$total_price+$total_tax_amount ?>
        <?php endif; ?>
        <?php if($total_price && @$service_charge_cart['extra_charge'] && @$service_charge_cart['extra_charge']!=0 ): ?>
            <hr>
            <p class="mb-1 ">
                <?php echo e(trans('lang.extra_charge')); ?>

                <span class="float-right ">
                <span class="currency-symbol-left"></span>
                <?php echo e(number_format(floatval(@$service_charge_cart['extra_charge']), $digit_decimal)); ?>

                <span class="currency-symbol-right"></span></span>
            </p>
            <?php $total_price=$total_price+floatval(@$service_charge_cart['extra_charge']) ?>
        <?php endif; ?>
        <hr>
        <h6 class="font-weight-bold mb-0"><?php echo e(trans('lang.total')); ?>

            <p class="float-right">
                <span class="currency-symbol-left"></span>
                <span>
                <?php echo e(number_format(floatval($total_price), $digit_decimal)); ?>

            </span>
                <span class="currency-symbol-right"></span>
            </p>
        </h6>
    </div>
    <input type="hidden" id="total_pay" value="<?php echo e(round($total_price, $digit_decimal)); ?>">
    <div class="p-3">
        <?php if($total_price>0): ?>
            <a class="btn btn-primary btn-block btn-lg" href="javascript:void(0)"
               onclick="payServiceCharge()"><?php echo e(trans('lang.pay')); ?> <span
                        class="currency-symbol-left"></span><?php echo e(number_format(floatval($total_price), $digit_decimal)); ?>

                <span class="currency-symbol-right"></span><i class="feather-arrow-right"></i></a>
        <?php endif; ?>
    </div>
<?php endif; ?><?php /**PATH /home/bubuggggycarpool/web/resources/views/providersService/service_charge/cart_item.blade.php ENDPATH**/ ?>