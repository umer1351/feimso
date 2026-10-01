<?php session_start(); ?>
<?php if(@$order_complete): ?>
    <div class="d-flex siddhi-cart-item-profile bg-white p-3">
        <p><?php echo e(trans('lang.your_order_placed_successfully')); ?></p>
    </div>
<?php endif; ?>
<?php if(@$extra_charge_cart['extra_charge']!=''): ?>
    <input type="hidden" id="extraCharge" value="<?php echo e(@$extra_charge_cart['extra_charge']); ?>">
    <input type="hidden" id="extraChargeId" value="<?php echo e(@$extra_charge_cart['order_id']); ?>">
    <div class="bg-white p-3 clearfix btm-total">
        <h6 class="font-weight-bold mb-0"><?php echo e(trans('lang.total')); ?>

            <p class="float-right">
                <span class="currency-symbol-left"></span>
                <span>
                <?php echo e(number_format(floatval(@$extra_charge_cart['extra_charge']), 2)); ?>

            </span>
                <span class="currency-symbol-right"></span>
            </p>
        </h6>
        <div class="p-3">
            <?php if(@$extra_charge_cart['extra_charge']>0): ?>
                <a class="btn btn-primary btn-block btn-lg" href="javascript:void(0)"
                   onclick="payExtraCharge()"><?php echo e(trans('lang.pay')); ?>

                    <span class="currency-symbol-left"></span>
                    <?php echo e(number_format(floatval(@$extra_charge_cart['extra_charge']), 2)); ?>

                    <span class="currency-symbol-right"></span><i class="feather-arrow-right"></i></a>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <?php
        $digit_decimal = 0;
        $total_price = 0;
        $total_item_price = 0;
    ?>
    <?php if(@$ondemand_cart['id']): ?>
        <div class="bg-white p-3 sidebar-item-list">
            <h6 class="pb-3"><?php echo e(trans('lang.cart_details')); ?></h6>
            <div class="product-item gold-members row align-items-center py-2 border mb-2 rounded-lg m-0" id="item">
                <input type="hidden" id="price_<?php echo e(@$ondemand_cart['id']); ?>"
                       value="<?php echo e(floatval(@$ondemand_cart['price'])); ?>">
                <input type="hidden" id="price_unit" value="<?php echo e(@$ondemand_cart['price_unit']); ?>">
                <input type="hidden" id="dis_price_<?php echo e(@$ondemand_cart['id']); ?>"
                       value="<?php echo e(floatval(@$ondemand_cart['dis_price'])); ?>">
                <input type="hidden" id="photo_<?php echo e(@$ondemand_cart['id']); ?>" value="<?php echo e(@$ondemand_cart['image']); ?>">
                <input type="hidden" id="name_<?php echo e(@$ondemand_cart['id']); ?>" value="<?php echo e(@$ondemand_cart['name']); ?>">
                <input type="hidden" id="quantity_<?php echo e(@$ondemand_cart['id']); ?>" value="<?php echo e(@$ondemand_cart['quantity']); ?>">
                <input type="hidden" id="total_price_<?php echo e(@$ondemand_cart['id']); ?>"
                       value="<?php echo e(@$ondemand_cart['total_price']); ?>">
                <input type="hidden" id="provider_id_<?php echo e(@$ondemand_cart['id']); ?>"
                       value="<?php echo e(@$ondemand_cart['providerId']); ?>">
                <input type="hidden" id="image_<?php echo e(@$ondemand_cart['id']); ?>" value="<?php echo e(@$ondemand_cart['image']); ?>">
                <input type="hidden" id="category_id_<?php echo e(@$ondemand_cart['id']); ?>"
                       value="<?php echo e(@$ondemand_cart['serviceCategoryId']); ?>">
                <input type="hidden" id="photo_<?php echo e(@$ondemand_cart['id']); ?>" value="<?php echo e(@$ondemand_cart['image']); ?>">
                <input type="hidden" id="provider_id" value="<?php echo e(@$ondemand_cart['providerId']); ?>">
                <input type="hidden" id="service_id" value="<?php echo e(@$ondemand_cart['id']); ?>">
                <div class="media align-items-center col-md-6">
                    <div class="media-body">
                        <p class="m-0">
                            <img src="<?php echo e(@$ondemand_cart['image']); ?>" class="img-responsive img-rounded"
                                 style="max-height: 40px; max-width: 25px;">
                            <?php echo e(@$ondemand_cart['name']); ?>

                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center count-number-box col-md-5">
                    <?php if(@$ondemand_cart['price_unit']!='Hourly'): ?>
                        <span class="count-number float-right">
                <button type="button" data-id="<?php echo e(@$ondemand_cart['id']); ?>"
                        class="count-number-input-cart btn-sm left dec btn btn-outline-secondary">
                    <i class="feather-minus"></i>
                </button>
                <input class="count-number-input count_number_<?php echo e(@$ondemand_cart['id']); ?>" type="text" readonly
                       value="<?php echo e(@$ondemand_cart['quantity']); ?>">
                <button type="button" data-id="<?php echo e(@$ondemand_cart['id']); ?>"
                        class="count-number-input-cart btn-sm right inc btn btn-outline-secondary count_number_right">
                    <i class="feather-plus"></i>
                </button>
            </span>
                    <?php endif; ?>
                    <?php
                        $totalItemPrice = floatval(@$ondemand_cart['total_price']);
                    ?>
                    <?php if(@$ondemand_cart['decimal_degits']): ?>
                        <?php $digit_decimal = $ondemand_cart['decimal_degits']; ?>
                    <?php endif; ?>
                    <p class="text-gray mb-0 float-right ml-3 text-muted small">
                        <span class="currency-symbol-left"></span>
                        <span class="cart_iteam_total_<?php echo e(@$ondemand_cart['id']); ?>">
                    <?php echo e(number_format($totalItemPrice, $digit_decimal)); ?>

                            <?php if(@$ondemand_cart['price_unit']=='Hourly'): ?>
                                <?php echo e(' / hr'); ?>

                            <?php endif; ?>
                </span>
                        <span class="currency-symbol-right"></span>
                    </p>
                </div>
                <div class="close remove_item col-md-1" data-id="<?php echo e($ondemand_cart['id']); ?>"><i class="fa fa-times"></i>
                </div>
            </div>
            <?php $total_item_price = $total_item_price + $totalItemPrice; ?>
        </div>
    <?php endif; ?>
    <?php if(@$ondemand_cart['id'] == ''): ?>
        <div class="bg-white py-2">
            <div class="gold-members d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                <span><?php echo e(trans('lang.your_cart_is_empty')); ?></span>
            </div>
        </div>
    <?php endif; ?>
    <?php if(@$ondemand_cart['price_unit']!='Hourly'): ?>
        <div class="bg-white px-3 clearfix">
            <div class="border-bottom pb-3">
                <div class="input-group-sm mb-2 input-group">
                    <input placeholder="<?php echo e(trans('lang.promo_help')); ?>"
                           value="<?php echo e(@$ondemand_cart['coupon']['coupon_code']); ?>"
                           id="coupon_code" type="text" class="form-control">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" data-id="<?php echo e(@$ondemand_cart['id']); ?>"
                                data-provider="<?php echo e(@$ondemand_cart['providerId']); ?>" id="apply-coupon-code">
                            <i class="feather-percent"></i> <?php echo e(trans('lang.apply')); ?>

                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="bg-white px-3 clearfix schedule-order pt-3">
        <div class="border-bottom pb-3">
            <h3><?php echo e(trans('lang.booking_date_slot')); ?></h3>
            <span class="text-dark">
            <input type="datetime-local" id="scheduleTime" name="scheduleTime"
                   value="<?php echo e((@$ondemand_cart['scheduleTime']) ? $ondemand_cart['scheduleTime'] : ""); ?>">
        </span>
        </div>
    </div>
    <div class="bg-white p-3 clearfix btm-total">
        <?php if(@$ondemand_cart['price_unit']!='Hourly'): ?>
            <p class="mb-2">
                <?php echo e(trans('lang.sub_total')); ?>

                <span class="float-right text-dark">
            <span class="currency-symbol-left"></span>
            <?php echo e(number_format($total_item_price, $digit_decimal)); ?>

            <span class="currency-symbol-right"></span>
        </span>
            </p>
        <?php endif; ?>
        <?php $discount_amount = 0;
    $coupon_id = '';
    $coupon_code = '';
    $discount = '';
    $discountType = '';
        ?>
        <?php if(@$ondemand_cart['coupon'] && $ondemand_cart['coupon']['discountType']): ?>
            <?php if(@$ondemand_cart['price_unit']!='Hourly'): ?>
                <hr>
                <p class="mb-1 text-success">
                    <?php
                        $discountType = $ondemand_cart['coupon']['discountType'];
                        $coupon_code = $ondemand_cart['coupon']['coupon_code'];
                        $coupon_id = @$ondemand_cart['coupon']['coupon_id'];
                        $discount = $ondemand_cart['coupon']['discount'];
                    ?>
                    <?php if($discountType == "Fix Price"): ?>
                        <?php
                            $discount_amount = floatval($ondemand_cart['coupon']['discount']);
                        ?>
                        <?php if($discount_amount > $total_item_price): ?>
                            <?php $discount_amount = $total_item_price; ?>
                        <?php endif; ?>
                        <?php if($total_item_price < 0): ?>
                            <?php $total_item_price=0; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php
                            $discount_amount=floatval($ondemand_cart['coupon']['discount']);
                            $discount_amount=($total_item_price *$discount_amount) / 100;
                        ?>
                        <?php if($discount_amount> $total_item_price): ?>
                            <?php $discount_amount = $total_item_price;?>
                        <?php endif; ?>
                        <?php if($total_item_price < 0): ?>
                            <?php $total_item_price=0; ?>
                        <?php endif; ?>
                    <?php endif; ?> <?php echo e(trans('lang.total')); ?>

                    <?php echo e(trans('lang.discount')); ?> <span class="float-right text-success">
                <span class="currency-symbol-left"></span>
                <?php echo e(number_format($discount_amount, $digit_decimal)); ?>

                <span class="currency-symbol-right"></span></span>
                </p>
            <?php endif; ?>
        <?php else: ?>
        <?php endif; ?>
        <input type="hidden" id="discount_amount" value="<?php echo e($discount_amount); ?>">
        <input type="hidden" id="coupon_id" value="<?php echo e($coupon_id); ?>">
        <input type="hidden" id="coupon_code_main" value="<?php echo e($coupon_code); ?>">
        <input type="hidden" id="discount" value="<?php echo e($discount); ?>">
        <input type="hidden" id="discountType" value="<?php echo e($discountType); ?>">
        <?php
            $total_item_price = $total_item_price - $discount_amount;
        ?>
        <?php if($total_item_price && @$ondemand_cart['taxValue']): ?>
            <?php if(@$ondemand_cart['price_unit']!='Hourly'): ?>
                <hr>
                <?php $total_tax_amount=0; ?>
                <?php $__currentLoopData = $ondemand_cart['taxValue']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p class="mb-2"><?php echo e($val['title']); ?>

                        <?php if($val['type'] == 'fix'): ?>
                            ( <span class="currency-symbol-left"></span>
                            <?php echo e(number_format(floatval($val['tax']), $digit_decimal)); ?>

                            <?php $tax = $val['tax']; ?>
                            <span class="currency-symbol-right"></span> )
                        <?php else: ?>
                            <?php $tax = ($val['tax'] * $total_item_price) / 100; ?>
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
                <?php $total_item_price = $total_item_price + $total_tax_amount; ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if(@$ondemand_cart['price_unit']!='Hourly'): ?>
            <hr>
            <h6 class="font-weight-bold mb-0"><?php echo e(trans('lang.total')); ?>

                <p class="float-right">
                    <span class="currency-symbol-left"></span>
                    <span>
                <?php echo e(number_format($total_item_price, $digit_decimal)); ?>

            </span>
                    <span class="currency-symbol-right"></span>
                </p>
            </h6>
    </div>
<?php endif; ?>
<input type="hidden" id="adminCommission" value="0">
<input type="hidden" id="adminCommissionType" value="Fix Price">
<input type="hidden" id="total_pay" value="<?php echo e(round($total_item_price, 2)); ?>">
<?php if(@$ondemand_cart['price_unit']!='Hourly'): ?>
    <div class="p-3">
        <?php if($total_item_price>0): ?>
            <a class="btn btn-primary btn-block btn-lg" href="javascript:void(0)"
               onclick="finalCheckout()"><?php echo e(trans('lang.pay')); ?> <span
                        class="currency-symbol-left"></span><?php echo e(number_format($total_item_price, $digit_decimal)); ?>

                <span class="currency-symbol-right"></span><i class="feather-arrow-right"></i></a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <a class="btn btn-primary btn-block btn-lg" href="javascript:void(0)"
       onclick="finalCheckout()"><?php echo e(trans('lang.book')); ?><i class="feather-arrow-right"></i></a>
<?php endif; ?>
<?php endif; ?><?php /**PATH /home/bubuggggycarpool/web/resources/views/providersService/cart_item.blade.php ENDPATH**/ ?>