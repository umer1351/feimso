
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php
    $cityToCountry = json_decode(file_get_contents(public_path('tz-cities-to-countries.json')), true) ?: [];
    $photos = array_values(array_filter(array_merge([$vendor['photo'] ?? ''], (array) ($vendor['photos'] ?? []))));
    $reviewsCount = (int) ($vendor['reviewsCount'] ?? 0);
    $rating = $reviewsCount > 0 ? round(((float) ($vendor['reviewsSum'] ?? 0)) / $reviewsCount, 1) : 0;
    $isMultivendor = ($section['serviceType'] ?? '') === 'Multivendor Delivery Service';
    $money = fn ($v) => number_format((float) $v, 2);
    $vendorJs = [
        'workingHours' => $vendor['workingHours'] ?? [],
        'reststatus' => $vendor['reststatus'] ?? true,
        'isSelfDelivery' => !empty($vendor['isSelfDelivery']),
        'deliveryCharge' => $vendor['deliveryCharge'] ?? null,
        'specialDiscount' => $vendor['specialDiscount'] ?? [],
        'specialDiscountEnable' => !empty($vendor['specialDiscountEnable']),
    ];
?>

<div class="offer-section py-3 resturant-banner">
    <div class="container position-relative">
        <div class="resturant-banner-inner row" id="vendor-pic">
            <div class="col-md-8 resturant-banner-left">
                <img class="restaurant-pic w-100 rounded" style="max-height:320px;object-fit:cover" src="<?php echo e($photos[0] ?? ''); ?>" alt="<?php echo e($vendor['title'] ?? ''); ?>" onerror="this.onerror=null;this.src=placeholderImageSrc">
            </div>
            <?php if(count($photos) > 1): ?>
                <div class="col-md-4 resturant-banner-right">
                    <div class="row">
                        <?php $__currentLoopData = array_slice($photos, 1, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-6 mb-2 resturant-banner-right-block"><img class="w-100 rounded" style="height:150px;object-fit:cover" src="<?php echo e($ph); ?>" alt=""></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container">
    <div class="pb-3 rounded position-relative text-dark rest-basic-detail">
        <div class="d-flex align-items-start flex-wrap">
            <div class="text-dark">
                <h2 class="font-weight-bold h6" id="vendor_title"><?php echo e($vendor['title'] ?? ''); ?></h2>
                <div class="d-flex flex-wrap">
                    <p class="text-dark m-0" id="vendor_address"><span class="fa fa-map-marker"></span> <?php echo e($vendor['location'] ?? ''); ?></p>
                    <div class="rest-time pl-3">
                        <span class="font-weight-bold" id="vendor_shop_status"></span>
                        <span class="text-muted small" id="vendor_open_time"></span>
                    </div>
                </div>
                <div class="rating-wrap d-flex align-items-center mt-2" id="vendor_ratings">
                    <ul class="rating" data-rating="<?php echo e(round($rating)); ?>">
                        <?php for($i = 0; $i < 5; $i++): ?><li class="rating__item"></li><?php endfor; ?>
                    </ul>
                    <p class="label-rating ml-2 small mb-0"><?php echo e($rating); ?> (<?php echo e($reviewsCount); ?> <?php echo e(trans('lang.reviews')); ?>)</p>
                </div>
                <?php if(!empty($vendor['description'])): ?>
                    <p class="text-muted small mt-2 mb-0"><?php echo e($vendor['description']); ?></p>
                <?php endif; ?>
            </div>
            <div class="feather_icon ml-auto">
                <div class="row fu-review">
                    <a href="#reviews" class="text-decoration-none mx-1 p-2 rest-right-btn"><i class="font-weight-bold feather-star"></i></a>
                    <?php if(isset($vendor['latitude'], $vendor['longitude'])): ?>
                        <a class="text-decoration-none mx-1 p-2 rest-right-btn" target="_blank" rel="noopener" href="https://www.openstreetmap.org/?mlat=<?php echo e($vendor['latitude']); ?>&mlon=<?php echo e($vendor['longitude']); ?>#map=16/<?php echo e($vendor['latitude']); ?>/<?php echo e($vendor['longitude']); ?>"><i class="font-weight-bold feather-map-pin"></i></a>
                    <?php endif; ?>
                    <?php if(!empty($vendor['phonenumber'])): ?>
                        <a class="text-decoration-none mx-1 p-2 rest-right-btn" href="tel:<?php echo e($vendor['phonenumber']); ?>"><i class="font-weight-bold feather-phone"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if(count($coupons)): ?>
            <div class="d-flex flex-wrap mt-3" style="gap:8px">
                <?php $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="badge badge-light border p-2"><i class="feather-percent"></i> <strong><?php echo e($cp['code'] ?? ''); ?></strong>
                        — <?php echo e($cp['discount'] ?? ''); ?><?php echo e(in_array($cp['discountType'] ?? '', ['Percentage', 'Percent', 'percentage'], true) ? '%' : ''); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="container position-relative">
    <div class="row">
        <div class="col-md-8 pt-3 vendor-detail-left">
            <?php if(count($groups) > 1): ?>
                <ul class="nav nav-pills mb-3 vendor-menu-nav" style="gap:6px">
                    <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catId => $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="nav-item"><a class="nav-link border" href="#cat-<?php echo e(\Illuminate\Support\Str::slug($catId ?: 'other')); ?>"><?php echo e($g['title']); ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>

            <p class="not-available alert alert-warning" style="display:none"><?php echo e(trans('lang.closed')); ?></p>

            <?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catId => $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="mb-4" id="cat-<?php echo e(\Illuminate\Support\Str::slug($catId ?: 'other')); ?>">
                    <h4 class="font-weight-bold h6 mb-3"><?php echo e($g['title']); ?></h4>
                    <?php $__currentLoopData = $g['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php ($pid = $p['id']); ?>
                        <div class="gold-members d-flex align-items-center justify-content-between bg-white border rounded p-2 mb-2 resitem-list-view">
                            <div class="media align-items-center">
                                <img class="mr-3 rounded" style="width:84px;height:66px;object-fit:cover" src="<?php echo e($p['photo'] ?? ''); ?>" alt="" loading="lazy" onerror="this.onerror=null;this.src=placeholderImageSrc">
                                <div class="media-body">
                                    <h6 class="mb-1">
                                        <?php if(!empty($p['veg'])): ?><span class="badge badge-success mr-1"><?php echo e(trans('lang.veg')); ?></span><?php elseif(!empty($p['nonveg'])): ?><span class="badge badge-danger mr-1"><?php echo e(trans('lang.non_veg')); ?></span><?php endif; ?>
                                        <a class="text-dark" href="<?php echo e(route('productdetail', $pid)); ?>"><?php echo e($p['name'] ?? ''); ?></a>
                                    </h6>
                                    <p class="text-muted mb-0 small pro-price">
                                        <span class="currency-symbol-left"></span><?php echo e($money($p['_price'])); ?><span class="currency-symbol-right"></span>
                                        <?php if($p['_original'] !== null): ?><s class="ml-1"><span class="currency-symbol-left"></span><?php echo e($money($p['_original'])); ?><span class="currency-symbol-right"></span></s><?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="add-btn menu-itembtn addtocart">
                                <?php if($p['_customize']): ?>
                                    <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route('productdetail', $pid)); ?>"><?php echo e(trans('lang.add_to_cart')); ?> <i class="feather-chevron-right"></i></a>
                                <?php else: ?>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#item_<?php echo e($pid); ?>"><i class="feather-plus"></i> <?php echo e(trans('lang.add_to_cart')); ?></button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (! ($p['_customize'])): ?>
                            <div class="modal fade" id="item_<?php echo e($pid); ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><?php echo e($p['name'] ?? ''); ?></h5>
                                            <button type="button" class="close" id="close_<?php echo e($pid); ?>" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body recepie-body">
                                            <?php if(!empty($p['description'])): ?><div class="small text-muted mb-3"><?php echo strip_tags($p['description'], '<p><br><b><strong><i><em><ul><ol><li>'); ?></div><?php endif; ?>

                                            <?php if(count($p['_addons'])): ?>
                                                <h6 class="font-weight-bold"><?php echo e(trans('lang.extra')); ?></h6>
                                                <?php $__currentLoopData = $p['_addons']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n => $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="custom-control custom-checkbox border-bottom py-2">
                                                        <input data-price="<?php echo e($ad['price']); ?>" type="checkbox" id="<?php echo e($pid); ?>_extra_<?php echo e($n); ?>" name="extra_<?php echo e($pid); ?>" value="<?php echo e($ad['title']); ?>" class="custom-control-input extra_<?php echo e($pid); ?>">
                                                        <label class="custom-control-label d-flex justify-content-between" for="<?php echo e($pid); ?>_extra_<?php echo e($n); ?>">
                                                            <span><?php echo e($ad['title']); ?></span>
                                                            <span class="text-muted">+<span class="currency-symbol-left"></span><?php echo e($money($ad['price'])); ?><span class="currency-symbol-right"></span></span>
                                                        </label>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>

                                            <div class="d-flex align-items-center justify-content-between mt-3 quantity">
                                                <span><?php echo e(trans('lang.qty')); ?></span>
                                                <span class="count-number">
                                                    <button type="button" class="btn-sm left dec btn btn-outline-secondary food_count_decrese"><i class="feather-minus"></i></button>
                                                    <input class="count-number-input" name="quantity_<?php echo e($pid); ?>" type="text" value="1" readonly>
                                                    <button type="button" class="btn-sm right inc btn btn-outline-secondary"><i class="feather-plus"></i></button>
                                                </span>
                                            </div>

                                            <input type="hidden" name="name_<?php echo e($pid); ?>" value="<?php echo e($p['name'] ?? ''); ?>">
                                            <input type="hidden" name="price_<?php echo e($pid); ?>" value="<?php echo e(round($p['_price'], 2)); ?>">
                                            <input type="hidden" name="dis_price_<?php echo e($pid); ?>" value="<?php echo e($p['_dis'] !== null ? round($p['_dis'], 2) : ''); ?>">
                                            <input type="hidden" name="image_<?php echo e($pid); ?>" value="<?php echo e($p['photo'] ?? ''); ?>">
                                            <input type="hidden" name="veg_<?php echo e($pid); ?>" value="<?php echo e(!empty($p['veg']) ? 'true' : 'false'); ?>">
                                            <input type="hidden" name="category_id_<?php echo e($pid); ?>" value="<?php echo e($p['categoryID'] ?? ''); ?>">
                                            <input type="hidden" id="quantity_<?php echo e($pid); ?>" value="<?php echo e($p['quantity'] ?? -1); ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" data-id="<?php echo e($pid); ?>" class="add-to-cart btn btn-primary btn-block"><?php echo e(trans('lang.add_to_cart')); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted py-4"><?php echo e(trans('lang.no_results')); ?></p>
            <?php endif; ?>

            <div class="py-2 mb-3 vendor-detailed-ratings-and-reviews" id="reviews">
                <h4 class="font-weight-bold h6 w-100 mb-3"><?php echo e(trans('lang.ratings_and_reviews')); ?></h4>
                <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border-bottom py-2">
                        <div class="d-flex justify-content-between">
                            <strong><?php echo e($rv['uname'] ?? ''); ?></strong>
                            <span class="badge badge-success"><i class="feather-star"></i> <?php echo e($rv['rating'] ?? ''); ?></span>
                        </div>
                        <?php if(!empty($rv['comment'])): ?><p class="mb-0 small text-muted"><?php echo e($rv['comment']); ?></p><?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="no_review_fount text-muted"><?php echo e(trans('lang.no_review_found')); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-4 pt-3 vendor-detail-right">
            <div class="siddhi-cart-item rounded rounded shadow-sm overflow-hidden bg-white sticky_sidebar" id="cart_list">
                <?php echo $__env->make('vendor.cart_item', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="vendor_id" id="vendor_id" value="<?php echo e($vendor['id']); ?>">
<input type="hidden" name="vendor_name" id="vendor_name" value="<?php echo e($vendor['title'] ?? ''); ?>">
<input type="hidden" name="vendor_location" id="vendor_location" value="<?php echo e($vendor['location'] ?? ''); ?>">
<input type="hidden" name="vendor_latitude" id="vendor_latitude" value="<?php echo e($vendor['latitude'] ?? ''); ?>">
<input type="hidden" name="vendor_longitude" id="vendor_longitude" value="<?php echo e($vendor['longitude'] ?? ''); ?>">
<input type="hidden" name="vendor_image" id="vendor_image" value="<?php echo e($vendor['photo'] ?? ''); ?>">

<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script type="text/javascript">
    var vendorData = <?php echo json_encode($vendorJs, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG); ?>;
    var isMultivendor = <?php echo json_encode($isMultivendor, 15, 512) ?>;
    var placeholderImageSrc = '';
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;
    var distanceType = 'km';
    var deliveryChargemain = [];
    var ecommerce_delivery_charge = <?php echo json_encode((float) ($section['delivery_charge'] ?? 0), 15, 512) ?>;
    var taxSetting = [];
    var specialOfferForHour = [];
    var isSelfDeliveryGlobally = false;
    var isSelfDeliveryByVendor = vendorData.isSelfDelivery;

    database.collection('settings').doc('placeHolderImage').get().then(function (s) { placeholderImageSrc = (s.data() || {}).image || ''; });
    database.collection('settings').doc('globalSettings').get().then(function (s) { isSelfDeliveryGlobally = !!(s.data() || {}).isSelfDelivery; });
    database.collection('settings').doc('DriverNearBy').get().then(function (s) { distanceType = (s.data() || {}).distanceType || 'km'; });
    database.collection('currencies').where('isActive', '==', true).get().then(function (snap) {
        if (!snap.docs.length) return;
        var c = snap.docs[0].data();
        currentCurrency = c.symbol;
        currencyAtRight = c.symbolAtRight;
        if (c.decimal_degits) decimal_degits = c.decimal_degits;
        loadcurrency();
    });

    // Delivery charge: global settings, overridden by the store's own when allowed.
    database.collection('settings').doc('DeliveryCharge').get().then(function (s) {
        deliveryChargemain = s.data() || {};
        var v = vendorData.deliveryCharge;
        if (deliveryChargemain.vendor_can_modify && v && v.delivery_charges_per_km && v.minimum_delivery_charges && v.minimum_delivery_charges_within_km) {
            deliveryChargemain = v;
        }
    });

    // Tax rows for the visitor's country (same lookup as the product page).
    var cityToCountry = <?php echo json_encode($cityToCountry); ?>;
    var userCountry = cityToCountry[(Intl.DateTimeFormat().resolvedOptions().timeZone || '').split('/')[1]];
    if (userCountry) {
        database.collection('tax').where('country', '==', userCountry).where('enable', '==', true).where('sectionId', '==', section_id).get().then(function (snap) {
            snap.docs.forEach(function (d) {
                var t = d.data();
                taxSetting.push({country: t.country, enable: t.enable, id: t.id, tax: t.tax, title: t.title, type: t.type});
            });
        });
    }

    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var now = new Date();
    var today = days[now.getDay()];
    var nowHM = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2);

    // Special discount for the current hour (needs both admin and store switches).
    database.collection('settings').doc('specialDiscountOffer').get().then(function (s) {
        var adminOn = !!(s.data() || {}).isEnable;
        if (!adminOn || !vendorData.specialDiscountEnable) return;
        (vendorData.specialDiscount || []).forEach(function (d) {
            if (d.day !== today) return;
            (d.timeslot || []).forEach(function (t) {
                if (nowHM >= t.from && nowHM <= t.to && t.discount_type === 'delivery') specialOfferForHour = [t];
            });
        });
        setCookie('specialOfferForHourMain', JSON.stringify(specialOfferForHour), 365);
    });

    // Open / closed from working hours (browser time, like the rest of the site).
    (function () {
        var todays = (vendorData.workingHours || []).filter(function (d) { return d.day === today; })[0];
        var slots = todays ? (todays.timeslot || []) : [];
        var openNow = vendorData.reststatus !== false && slots.some(function (t) { return nowHM >= t.from && nowHM <= t.to; });
        $('#vendor_shop_status').html(openNow ? '<span class="open text-success"><?php echo e(trans('lang.open')); ?></span>' : '<span class="close text-danger"><?php echo e(trans('lang.closed')); ?></span>');
        $('#vendor_open_time').text(slots.map(function (t) { return t.from + ' - ' + t.to; }).join(', '));
        if (isMultivendor && !slots.length) {
            $('.addtocart, .quantity').hide();
            $('.not-available').show();
        }
    })();

    function loadcurrency() {
        if (currencyAtRight) {
            jQuery('.currency-symbol-left').hide();
            jQuery('.currency-symbol-right').show().text(currentCurrency);
        } else {
            jQuery('.currency-symbol-left').show().text(currentCurrency);
            jQuery('.currency-symbol-right').hide();
        }
    }

    function cartPost(url, data) {
        data._token = '<?php echo e(csrf_token()); ?>';
        return $.ajax({type: 'POST', url: url, data: data}).then(function (res) {
            res = typeof res === 'string' ? JSON.parse(res) : res;
            if (res && res.html !== undefined) {
                $('#cart_list').html(res.html);
                loadcurrency();
            }
            return res;
        });
    }

    function tipAmountChange(type) {
        cartPost("<?php echo e(route('order-tip-add')); ?>", {tip: $('#tip_amount').val(), type: type || 'plus'});
    }

    // Bound on ready so they run after siddhi.js's own ready-time .inc/.dec handlers
    // (those update the quantity box first; same order as the original pages).
    $(function () {
    $(document).on('click', '.add-to-cart', async function () {
        <?php if(auth()->guard()->guest()): ?>
            window.location.href = "<?php echo e(route('login')); ?>";
            return false;
        <?php endif; ?>
        var id = $(this).attr('data-id');
        var quantity = parseInt($('input[name="quantity_' + id + '"]').val() || '0', 10);
        if (!quantity) {
            Swal.fire({text: "<?php echo e(trans('lang.invalid_qty')); ?>", icon: 'error'});
            return false;
        }
        var stock_quantity = $('#quantity_' + id).val();
        if (stock_quantity !== undefined && stock_quantity != -1 && quantity > parseInt(stock_quantity, 10)) {
            Swal.fire({text: "<?php echo e(trans('lang.invalid_stock_qty')); ?>", icon: 'error'});
            return false;
        }
        var unit = parseFloat($('input[name="price_' + id + '"]').val());
        var extra = [];
        var extra_price = 0;
        $('input:checkbox.extra_' + id + ':checked').each(function () {
            extra.push($(this).val());
            extra_price += parseFloat($(this).attr('data-price')) || 0;
        });

        setCookie('vendor_latitude', $('#vendor_latitude').val(), 365);
        setCookie('vendor_longitude', $('#vendor_longitude').val(), 365);
        if (getCookie('service_type') == 'Ecommerce Service') {
            setCookie('ecommerce_delivery_charge', ecommerce_delivery_charge, 356);
        }
        setCookie('deliveryChargemain', JSON.stringify(deliveryChargemain), 356);

        await cartPost("<?php echo e(route('add-to-cart')); ?>", {
            vendor_id: $('#vendor_id').val(),
            extra: extra,
            size: '',
            id: id,
            quantity: quantity,
            stock_quantity: stock_quantity,
            name: $('input[name="name_' + id + '"]').val(),
            price: unit * quantity,
            dis_price: $('input[name="dis_price_' + id + '"]').val(),
            image: $('input[name="image_' + id + '"]').val() || placeholderImageSrc,
            extra_price: extra_price,
            item_price: unit,
            vendor_location: $('#vendor_location').val(),
            vendor_name: $('#vendor_name').val(),
            vendor_image: $('#vendor_image').val(),
            veg: $('input[name="veg_' + id + '"]').val(),
            taxValue: taxSetting,
            vendor_latitude: $('#vendor_latitude').val(),
            vendor_longitude: $('#vendor_longitude').val(),
            variant_info: {},
            category_id: $('input[name="category_id_' + id + '"]').val(),
            specialOfferForHour: specialOfferForHour,
            decimal_degits: decimal_degits,
            distanceType: distanceType,
            isSelfDelivery: (isSelfDeliveryByVendor && isSelfDeliveryGlobally && getCookie('service_type') == 'Multivendor Delivery Service')
        });
        $('#close_' + id).trigger('click');
        Swal.fire({text: "<?php echo e(trans('lang.added_tocart')); ?>", icon: 'success'});
    });

    $(document).on('click', '.remove_item', function () {
        cartPost("<?php echo e(route('remove-from-cart')); ?>", {vendor_id: $(this).attr('data-vendor'), id: $(this).attr('data-id')});
    });

    $(document).on('click', '.count-number-input-cart', function () {
        var id = $(this).attr('data-id');
        cartPost("<?php echo e(route('change-quantity-cart')); ?>", {
            vendor_id: $(this).attr('data-vendor'), id: id, quantity: $('.count_number_' + id).val(), specialOfferForHour: specialOfferForHour
        });
    });

    $(document).on('click', '#apply-coupon-code', function () {
        var code = $.trim($('#coupon_code').val());
        if (!code) return;
        var vendorId = $('#vendor_id').val();
        database.collection('coupons').where('code', '==', code).where('isEnabled', '==', true).where('expiresAt', '>=', new Date()).get().then(function (snap) {
            var c = snap.docs.length ? snap.docs[0].data() : null;
            if (!c || (c.vendorID && c.vendorID != vendorId)) {
                Swal.fire({text: "<?php echo e(trans('lang.coupon_not_valid')); ?>", icon: 'error'});
                $('#coupon_code').val('');
                return;
            }
            cartPost("<?php echo e(route('apply-coupon')); ?>", {
                coupon_code: code, discount: c.discount, discountType: c.discountType, coupon_id: snap.docs[0].id, specialOfferForHour: specialOfferForHour
            });
        });
    });

    $(document).on('click', '#Other_tip', function () {
        $('#tip_amount').val('');
        $('#add_tip_box').show();
    });
    $(document).on('click', '.this_tip', function () {
        $('#tip_amount').val($(this).val());
        $('#add_tip_box').hide();
        if ($(this).hasClass('tip_checked')) {
            $(this).removeClass('tip_checked').prop('checked', false);
            tipAmountChange('minus');
        } else {
            $(this).addClass('tip_checked');
            tipAmountChange('plus');
        }
    });
    });
</script>
<?php echo $__env->make('layouts.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php /**PATH C:\wamp64\www\feimso\resources\views/vendor/vendor.blade.php ENDPATH**/ ?>