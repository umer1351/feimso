<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="osahan-profile pt-5">
    <div class="container position-relative">
        <div class="col-md-12 mb-3">
            <div class="osahan-cart-item-profile">
                <div id="basics">
                    <div class="mb-2 mt-3">
                        <h5 class="font-weight-semi-bold mb-0"><?php echo e(trans('lang.basics')); ?></h5>
                    </div>
                    <div id="basicsAccordion">
                        <div class="box border-bottom bg-white mb-2 rounded shadow-sm overflow-hidden">
                            <div id="basicsHeadingOne">
                                <h5 class="mb-0">
                                    <button class="shadow-none btn btn-block d-flex justify-content-between card-btn p-3 collapsed"
                                            data-toggle="collapse" data-target="#basicsCollapseOne"
                                            aria-expanded="false" aria-controls="basicsCollapseOne">
                                        <?php echo e(trans('lang.do_you_have_any_built_in_caching')); ?>

                                        <span class="card-btn-arrow">
                                        <span class="feather-chevron-down"></span>
                                        </span>
                                    </button>
                                </h5>
                            </div>
                            <div id="basicsCollapseOne" class="collapse show" aria-labelledby="basicsHeadingOne"
                                 data-parent="#basicsAccordion" style="">
                                <div class="card-body border-top p-3 text-muted">
                                    <?php echo e(trans('lang.anim_pariatur_message')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="box border-bottom bg-white mb-2 rounded shadow-sm overflow-hidden">
                            <div id="basicsHeadingTwo">
                                <h5 class="mb-0">
                                    <button class="shadow-none btn btn-block d-flex justify-content-between card-btn p-3 collapsed"
                                            data-toggle="collapse" data-target="#basicsCollapseTwo"
                                            aria-expanded="false" aria-controls="basicsCollapseTwo">
                                        <?php echo e(trans('lang.add_upgrade_plan')); ?>

                                        <span class="card-btn-arrow">
                                        <span class="feather-chevron-down"></span>
                                        </span>
                                    </button>
                                </h5>
                            </div>
                            <div id="basicsCollapseTwo" class="collapse" aria-labelledby="basicsHeadingTwo"
                                 data-parent="#basicsAccordion" style="">
                                <div class="card-body border-top p-3 text-muted">
                                    <?php echo e(trans('lang.anim_pariatur_message')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="box border-bottom bg-white mb-2 rounded shadow-sm overflow-hidden">
                            <div id="basicsHeadingThree">
                                <h5 class="mb-0">
                                    <button class="shadow-none btn btn-block d-flex justify-content-between card-btn p-3 collapsed"
                                            data-toggle="collapse" data-target="#basicsCollapseThree"
                                            aria-expanded="false" aria-controls="basicsCollapseThree">
                                        <?php echo e(trans('lang.access_comes_plan')); ?>

                                        <span class="card-btn-arrow">
                                        <span class="feather-chevron-down"></span>
                                        </span>
                                    </button>
                                </h5>
                            </div>
                            <div id="basicsCollapseThree" class="collapse" aria-labelledby="basicsHeadingThree"
                                 data-parent="#basicsAccordion" style="">
                                <div class="card-body border-top p-3 text-muted">
                                    <?php echo e(trans('lang.anim_pariatur_message')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="box border-bottom bg-white mb-2 rounded shadow-sm overflow-hidden">
                            <div id="basicsHeadingFour">
                                <h5 class="mb-0">
                                    <button class="shadow-none btn btn-block d-flex justify-content-between card-btn collapsed p-3"
                                            data-toggle="collapse" data-target="#basicsCollapseFour"
                                            aria-expanded="false" aria-controls="basicsCollapseFour">
                                        <?php echo e(trans('lang.change_my_password')); ?>

                                        <span class="card-btn-arrow">
                                        <span class="feather-chevron-down"></span>
                                        </span>
                                    </button>
                                </h5>
                            </div>
                            <div id="basicsCollapseFour" class="collapse" aria-labelledby="basicsHeadingFour"
                                 data-parent="#basicsAccordion">
                                <div class="card-body border-top p-3 text-muted">
                                    <?php echo e(trans('lang.anim_pariatur_message')); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="account">
                    <div class="mb-2 mt-3">
                        <h5 class="font-weight-semi-bold mb-0"><?php echo e(trans('lang.account')); ?></h5>
                    </div>
                    <div id="accountAccordion">
                        <div class="box border-bottom bg-white mb-2 rounded shadow-sm overflow-hidden">
                            <div id="accountHeadingOne">
                                <h5 class="mb-0">
                                    <button class="shadow-none btn btn-block d-flex justify-content-between card-btn p-3"
                                            data-toggle="collapse" data-target="#accountCollapseOne"
                                            aria-expanded="false" aria-controls="accountCollapseOne">
                                        <?php echo e(trans('lang.change_my_password')); ?>

                                        <span class="card-btn-arrow">
                                        <span class="feather-chevron-down"></span>
                                        </span>
                                    </button>
                                </h5>
                            </div>
                            <div id="accountCollapseOne" class="collapse show" aria-labelledby="accountHeadingOne"
                                 data-parent="#accountAccordion">
                                <div class="card-body border-top p-3 text-muted">
                                    <?php echo e(trans('lang.anim_pariatur_message')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="box border-bottom bg-white mb-2 rounded shadow-sm overflow-hidden">
                            <div id="accountHeadingTwo">
                                <h5 class="mb-0">
                                    <button class="shadow-none btn btn-block d-flex justify-content-between card-btn collapsed p-3"
                                            data-toggle="collapse" data-target="#accountCollapseTwo"
                                            aria-expanded="false" aria-controls="accountCollapseTwo">
                                        <?php echo e(trans('lang.delete_account')); ?>

                                        <span class="card-btn-arrow">
<span class="feather-chevron-down"></span>
</span>
                                    </button>
                                </h5>
                            </div>
                            <div id="accountCollapseTwo" class="collapse" aria-labelledby="accountHeadingTwo"
                                 data-parent="#accountAccordion">
                                <div class="card-body border-top p-3 text-muted">
                                    <?php echo e(trans('lang.anim_pariatur_message')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="box border-bottom bg-white mb-2 rounded shadow-sm overflow-hidden">
                            <div id="accountHeadingThree">
                                <h5 class="mb-0">
                                    <button class="shadow-none btn btn-block d-flex justify-content-between card-btn collapsed p-3"
                                            data-toggle="collapse" data-target="#accountCollapseThree"
                                            aria-expanded="false" aria-controls="accountCollapseThree">
                                        <?php echo e(trans('lang.change_account_setting')); ?>

                                        <span class="card-btn-arrow">
<span class="feather-chevron-down"></span>
</span>
                                    </button>
                                </h5>
                            </div>
                            <div id="accountCollapseThree" class="collapse" aria-labelledby="accountHeadingThree"
                                 data-parent="#accountAccordion">
                                <div class="card-body border-top p-3 text-muted">
                                    <?php echo e(trans('lang.anim_pariatur_message')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="box border-bottom bg-white mb-2 rounded shadow-sm overflow-hidden">
                            <div id="accountHeadingFour">
                                <h5 class="mb-0">
                                    <button class="shadow-none btn btn-block d-flex justify-content-between card-btn collapsed p-3"
                                            data-toggle="collapse" data-target="#accountCollapseFour"
                                            aria-expanded="false" aria-controls="accountCollapseFour">
                                        <?php echo e(trans('lang.forgot_password_reset_it')); ?>

                                        <span class="card-btn-arrow">
<span class="feather-chevron-down"></span>
</span>
                                    </button>
                                </h5>
                            </div>
                            <div id="accountCollapseFour" class="collapse" aria-labelledby="accountHeadingFour"
                                 data-parent="#accountAccordion">
                                <div class="card-body border-top p-3 text-muted">
                                    <?php echo e(trans('lang.anim_pariatur_message')); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>;<?php /**PATH /home/bubuggggycarpool/web/resources/views/static/deliveryofsupport.blade.php ENDPATH**/ ?>