<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
    $cityToCountry = file_get_contents(asset('tz-cities-to-countries.json'));
    $cityToCountry = json_decode($cityToCountry, true);
    $countriesJs = array();
    foreach ($cityToCountry as $key => $value) {
        $countriesJs[$key] = $value;
    }
?>
<div class="siddhi-checkout">
    <div class="container position-relative">
        <div class="py-5 row">
            <div class="col-md-12 mb-3">
                <div>
                    <div class="siddhi-cart-item mb-3 rounded shadow-sm bg-white overflow-hidden">
                        <div class="siddhi-cart-item-profile bg-white p-3">
                            <div class="card card-default">
                                <?php $authorName = @$cart['cart_order']['authorName']; ?>
                                <?php if($message = Session::get('success')): ?>
                                    <div class="py-5 linus-coming-soon d-flex justify-content-center align-items-center">
                                        <div class="col-md-6">
                                            <div class="text-center pb-3">
                                                <h1 class="font-weight-bold payment-status"><?php if (@$authorName) {
                                                        echo @strtoupper($authorName) . ",";
                                                    } ?> <?php echo e(trans('lang.payment_successfull')); ?></h1>
                                                <p>Check your booking <a href="<?php echo e(route('my-bookings')); ?>"
                                                                         class="font-weight-bold text-decoration-none text-primary"><?php echo e(trans('lang.my_orders')); ?></a>
                                            </div>
                                            <div class="bg-white rounded text-center p-4 shadow-sm">
                                                <h1 class="display-1 mb-4"><?php echo e(trans('lang.emoji')); ?></h1>
                                                <a href="<?php echo e(route('my-bookings')); ?>"
                                                   class="btn rounded btn-primary btn-lg btn-block"><?php echo e(trans('lang.view_booking')); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="data-table_processing_order" class="dataTables_processing panel panel-default" style="display: none;">
    <?php echo e(trans('lang.processing_success')); ?>

</div>
<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('layouts.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php if($message = Session::get('success')): ?>
    <script src="<?php echo e(asset('js/geofirestore.js')); ?>"></script>
    <script type="text/javascript">
        var fcmToken = '';
        var userId = "<?php echo $id; ?>";
        var userDetailsRef = database.collection('users').where('id', "==", userId);
        var firestore = firebase.firestore();
        var geoFirestore = new GeoFirestore(firestore);
            <?php if (@$cart['paymentStatus'] == true && !empty(@$cart['cart_order']['total_pay']) && !empty(@$cart['cart_order']['orderId'])) { ?>
        var id_order = "<?php echo e(@$cart['cart_order']['orderId']); ?>";
        var extraCharge = "<?php echo e(@$cart['cart_order']['total_pay']); ?>";
        var providerId = '';
        database.collection('provider_orders').where('id', '==', id_order).get().then(async function (snapshots) {
            orderDetail = snapshots.docs[0].data();
            providerId = orderDetail.provider.author;
            finalCheckout();
        })

        async function finalCheckout() {
            var wId = database.collection('tmp').doc().id;
            await database.collection('wallet').doc(wId).set({
                "amount": parseFloat(extraCharge),
                "date": firebase.firestore.FieldValue.serverTimestamp(),
                "id": wId,
                "isTopUp": true,
                "order_id": id_order,
                "payment_method": 'Wallet',
                "payment_status": "success",
                "serviceType": "ondemand-service",
                "user_id": providerId,
                'transactionUser': 'provider',
                'note': 'Extra Charge Amount Credited'
            }).then(async function (result) {
                await database.collection('provider_orders').doc(id_order).update({extraPaymentStatus: true}).then(function (result) {
                    window.location.href = "<?php echo e(route('my-bookings')); ?>";
                });
            })
        }
        <?php } else { ?>
            $('.payment-status').html('<?php echo e(trans("lang.payment_failed")); ?>');
        <?php } ?>
    </script>
<?php endif; ?><?php /**PATH /home/bubuggggycarpool/web/resources/views/providersService/extra_charge/success.blade.php ENDPATH**/ ?>