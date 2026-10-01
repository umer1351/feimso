<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="siddhi-checkout siddhi-checkout-payment">
    <div class="container position-relative">
        <div class="py-5 row">
            <div class="pb-2 align-items-starrt sec-title col">
                <h2 class="m-0"><?php echo e(trans('lang.pay_with_razorpay')); ?></h2>
                <p class="sub-title"><?php echo e(trans('lang.payment_tagline_message')); ?></p>
            </div>
            <div class="col-md-12 mb-3">
                <div>
                    <div class="siddhi-cart-item mb-3 rounded shadow-sm bg-white overflow-hidden">
                        <div class="siddhi-cart-item-profile bg-white p-3">
                            <div class="card card-default payment-wrap">
                                <table class="payment-table">
                                    <thead>
                                    <tr>
                                        <th>
                                            <?php echo e(trans('lang.pay')); ?>

                                        </th>
                                        <th class="text-right">
                                            <?php echo e(trans('lang.total')); ?>

                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <?php echo e(trans('lang.razor_pay')); ?> <?php echo e(trans('lang.payment')); ?>

                                        </td>
                                        <td class="text-right payment-button">
                                            <form action="<?php echo e(route('ondemand-razorpay-payment')); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <script src="https://checkout.razorpay.com/v1/checkout.js"
                                                        data-key="<?php echo e($razorpayKey); ?>"
                                                        data-amount="<?php echo e($amount*100); ?>"
                                                        data-buttontext="Pay $<?php echo e($amount); ?>"
                                                        data-name="<?php echo e(env('APP_NAME', 'GroMart')); ?>"
                                                        data-description="Rozerpay"
                                                        data-image="https://www.itsolutionstuff.com/frontTheme/images/logo.png"
                                                        data-prefill.name="<?php echo e($authorName); ?>"
                                                        data-prefill.email="<?php echo e($email); ?>"
                                                        data-theme.color="#ff7529">
                                                </script>
                                            </form>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('layouts.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/bubuggggycarpool/web/resources/views/providersService/razorpay.blade.php ENDPATH**/ ?>