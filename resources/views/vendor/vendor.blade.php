{{--
    Store page (route 'vendor', vendor-detail/{id}). Rebuilt — the original
    view was missing from the project. Store and menu are rendered server-side
    (App\Storefront\StorePage); the cart uses the same endpoints and payload as
    products/detail.blade.php.
--}}
@include('layouts.app')
@include('layouts.header')
@php
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
@endphp

<div class="offer-section py-3 resturant-banner">
    <div class="container position-relative">
        <div class="resturant-banner-inner row" id="vendor-pic">
            <div class="col-md-8 resturant-banner-left">
                <img class="restaurant-pic w-100 rounded" style="max-height:320px;object-fit:cover" src="{{ $photos[0] ?? '' }}" alt="{{ $vendor['title'] ?? '' }}" onerror="this.onerror=null;this.src=placeholderImageSrc">
            </div>
            @if(count($photos) > 1)
                <div class="col-md-4 resturant-banner-right">
                    <div class="row">
                        @foreach(array_slice($photos, 1, 4) as $ph)
                            <div class="col-6 mb-2 resturant-banner-right-block"><img class="w-100 rounded" style="height:150px;object-fit:cover" src="{{ $ph }}" alt=""></div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="container">
    <div class="pb-3 rounded position-relative text-dark rest-basic-detail">
        <div class="d-flex align-items-start flex-wrap">
            <div class="text-dark">
                <h2 class="font-weight-bold h6" id="vendor_title">{{ $vendor['title'] ?? '' }}</h2>
                <div class="d-flex flex-wrap">
                    <p class="text-dark m-0" id="vendor_address"><span class="fa fa-map-marker"></span> {{ $vendor['location'] ?? '' }}</p>
                    <div class="rest-time pl-3">
                        <span class="font-weight-bold" id="vendor_shop_status"></span>
                        <span class="text-muted small" id="vendor_open_time"></span>
                    </div>
                </div>
                <div class="rating-wrap d-flex align-items-center mt-2" id="vendor_ratings">
                    <ul class="rating" data-rating="{{ round($rating) }}">
                        @for($i = 0; $i < 5; $i++)<li class="rating__item"></li>@endfor
                    </ul>
                    <p class="label-rating ml-2 small mb-0">{{ $rating }} ({{ $reviewsCount }} {{ trans('lang.reviews') }})</p>
                </div>
                @if(!empty($vendor['description']))
                    <p class="text-muted small mt-2 mb-0">{{ $vendor['description'] }}</p>
                @endif
            </div>
            <div class="feather_icon ml-auto">
                <div class="row fu-review">
                    <a href="#reviews" class="text-decoration-none mx-1 p-2 rest-right-btn"><i class="font-weight-bold feather-star"></i></a>
                    @if(isset($vendor['latitude'], $vendor['longitude']))
                        <a class="text-decoration-none mx-1 p-2 rest-right-btn" target="_blank" rel="noopener" href="https://www.openstreetmap.org/?mlat={{ $vendor['latitude'] }}&mlon={{ $vendor['longitude'] }}#map=16/{{ $vendor['latitude'] }}/{{ $vendor['longitude'] }}"><i class="font-weight-bold feather-map-pin"></i></a>
                    @endif
                    @if(!empty($vendor['phonenumber']))
                        <a class="text-decoration-none mx-1 p-2 rest-right-btn" href="tel:{{ $vendor['phonenumber'] }}"><i class="font-weight-bold feather-phone"></i></a>
                    @endif
                </div>
            </div>
        </div>

        @if(count($coupons))
            <div class="d-flex flex-wrap mt-3" style="gap:8px">
                @foreach($coupons as $cp)
                    <span class="badge badge-light border p-2"><i class="feather-percent"></i> <strong>{{ $cp['code'] ?? '' }}</strong>
                        — {{ $cp['discount'] ?? '' }}{{ in_array($cp['discountType'] ?? '', ['Percentage', 'Percent', 'percentage'], true) ? '%' : '' }}
                    </span>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="container position-relative">
    <div class="row">
        <div class="col-md-8 pt-3 vendor-detail-left">
            @if(count($groups) > 1)
                <ul class="nav nav-pills mb-3 vendor-menu-nav" style="gap:6px">
                    @foreach($groups as $catId => $g)
                        <li class="nav-item"><a class="nav-link border" href="#cat-{{ \Illuminate\Support\Str::slug($catId ?: 'other') }}">{{ $g['title'] }}</a></li>
                    @endforeach
                </ul>
            @endif

            <p class="not-available alert alert-warning" style="display:none">{{ trans('lang.closed') }}</p>

            @forelse($groups as $catId => $g)
                <div class="mb-4" id="cat-{{ \Illuminate\Support\Str::slug($catId ?: 'other') }}">
                    <h4 class="font-weight-bold h6 mb-3">{{ $g['title'] }}</h4>
                    @foreach($g['items'] as $p)
                        @php($pid = $p['id'])
                        <div class="gold-members d-flex align-items-center justify-content-between bg-white border rounded p-2 mb-2 resitem-list-view">
                            <div class="media align-items-center">
                                <img class="mr-3 rounded" style="width:84px;height:66px;object-fit:cover" src="{{ $p['photo'] ?? '' }}" alt="" loading="lazy" onerror="this.onerror=null;this.src=placeholderImageSrc">
                                <div class="media-body">
                                    <h6 class="mb-1">
                                        @if(!empty($p['veg']))<span class="badge badge-success mr-1">{{ trans('lang.veg') }}</span>@elseif(!empty($p['nonveg']))<span class="badge badge-danger mr-1">{{ trans('lang.non_veg') }}</span>@endif
                                        <a class="text-dark" href="{{ route('productdetail', $pid) }}">{{ $p['name'] ?? '' }}</a>
                                    </h6>
                                    <p class="text-muted mb-0 small pro-price">
                                        <span class="currency-symbol-left"></span>{{ $money($p['_price']) }}<span class="currency-symbol-right"></span>
                                        @if($p['_original'] !== null)<s class="ml-1"><span class="currency-symbol-left"></span>{{ $money($p['_original']) }}<span class="currency-symbol-right"></span></s>@endif
                                    </p>
                                </div>
                            </div>
                            <div class="add-btn menu-itembtn addtocart">
                                @if($p['_customize'])
                                    <a class="btn btn-outline-secondary btn-sm" href="{{ route('productdetail', $pid) }}">{{ trans('lang.add_to_cart') }} <i class="feather-chevron-right"></i></a>
                                @else
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-toggle="modal" data-target="#item_{{ $pid }}"><i class="feather-plus"></i> {{ trans('lang.add_to_cart') }}</button>
                                @endif
                            </div>
                        </div>

                        @unless($p['_customize'])
                            <div class="modal fade" id="item_{{ $pid }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $p['name'] ?? '' }}</h5>
                                            <button type="button" class="close" id="close_{{ $pid }}" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body recepie-body">
                                            @if(!empty($p['description']))<div class="small text-muted mb-3">{!! strip_tags($p['description'], '<p><br><b><strong><i><em><ul><ol><li>') !!}</div>@endif

                                            @if(count($p['_addons']))
                                                <h6 class="font-weight-bold">{{ trans('lang.extra') }}</h6>
                                                @foreach($p['_addons'] as $n => $ad)
                                                    <div class="custom-control custom-checkbox border-bottom py-2">
                                                        <input data-price="{{ $ad['price'] }}" type="checkbox" id="{{ $pid }}_extra_{{ $n }}" name="extra_{{ $pid }}" value="{{ $ad['title'] }}" class="custom-control-input extra_{{ $pid }}">
                                                        <label class="custom-control-label d-flex justify-content-between" for="{{ $pid }}_extra_{{ $n }}">
                                                            <span>{{ $ad['title'] }}</span>
                                                            <span class="text-muted">+<span class="currency-symbol-left"></span>{{ $money($ad['price']) }}<span class="currency-symbol-right"></span></span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif

                                            <div class="d-flex align-items-center justify-content-between mt-3 quantity">
                                                <span>{{ trans('lang.qty') }}</span>
                                                <span class="count-number">
                                                    <button type="button" class="btn-sm left dec btn btn-outline-secondary food_count_decrese"><i class="feather-minus"></i></button>
                                                    <input class="count-number-input" name="quantity_{{ $pid }}" type="text" value="1" readonly>
                                                    <button type="button" class="btn-sm right inc btn btn-outline-secondary"><i class="feather-plus"></i></button>
                                                </span>
                                            </div>

                                            <input type="hidden" name="name_{{ $pid }}" value="{{ $p['name'] ?? '' }}">
                                            <input type="hidden" name="price_{{ $pid }}" value="{{ round($p['_price'], 2) }}">
                                            <input type="hidden" name="dis_price_{{ $pid }}" value="{{ $p['_dis'] !== null ? round($p['_dis'], 2) : '' }}">
                                            <input type="hidden" name="image_{{ $pid }}" value="{{ $p['photo'] ?? '' }}">
                                            <input type="hidden" name="veg_{{ $pid }}" value="{{ !empty($p['veg']) ? 'true' : 'false' }}">
                                            <input type="hidden" name="category_id_{{ $pid }}" value="{{ $p['categoryID'] ?? '' }}">
                                            <input type="hidden" id="quantity_{{ $pid }}" value="{{ $p['quantity'] ?? -1 }}">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" data-id="{{ $pid }}" class="add-to-cart btn btn-primary btn-block">{{ trans('lang.add_to_cart') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endunless
                    @endforeach
                </div>
            @empty
                <p class="text-muted py-4">{{ trans('lang.no_results') }}</p>
            @endforelse

            <div class="py-2 mb-3 vendor-detailed-ratings-and-reviews" id="reviews">
                <h4 class="font-weight-bold h6 w-100 mb-3">{{ trans('lang.ratings_and_reviews') }}</h4>
                @forelse($reviews as $rv)
                    <div class="border-bottom py-2">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $rv['uname'] ?? '' }}</strong>
                            <span class="badge badge-success"><i class="feather-star"></i> {{ $rv['rating'] ?? '' }}</span>
                        </div>
                        @if(!empty($rv['comment']))<p class="mb-0 small text-muted">{{ $rv['comment'] }}</p>@endif
                    </div>
                @empty
                    <p class="no_review_fount text-muted">{{ trans('lang.no_review_found') }}</p>
                @endforelse
            </div>
        </div>

        <div class="col-md-4 pt-3 vendor-detail-right">
            <div class="siddhi-cart-item rounded rounded shadow-sm overflow-hidden bg-white sticky_sidebar" id="cart_list">
                @include('vendor.cart_item')
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="vendor_id" id="vendor_id" value="{{ $vendor['id'] }}">
<input type="hidden" name="vendor_name" id="vendor_name" value="{{ $vendor['title'] ?? '' }}">
<input type="hidden" name="vendor_location" id="vendor_location" value="{{ $vendor['location'] ?? '' }}">
<input type="hidden" name="vendor_latitude" id="vendor_latitude" value="{{ $vendor['latitude'] ?? '' }}">
<input type="hidden" name="vendor_longitude" id="vendor_longitude" value="{{ $vendor['longitude'] ?? '' }}">
<input type="hidden" name="vendor_image" id="vendor_image" value="{{ $vendor['photo'] ?? '' }}">

@include('layouts.footer')
<script type="text/javascript">
    var vendorData = {!! json_encode($vendorJs, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!};
    var isMultivendor = @json($isMultivendor);
    var placeholderImageSrc = '';
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;
    var distanceType = 'km';
    var deliveryChargemain = [];
    var ecommerce_delivery_charge = @json((float) ($section['delivery_charge'] ?? 0));
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
    var cityToCountry = {!! json_encode($cityToCountry) !!};
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
        $('#vendor_shop_status').html(openNow ? '<span class="open text-success">{{ trans('lang.open') }}</span>' : '<span class="close text-danger">{{ trans('lang.closed') }}</span>');
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
        data._token = '{{ csrf_token() }}';
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
        cartPost("{{ route('order-tip-add') }}", {tip: $('#tip_amount').val(), type: type || 'plus'});
    }

    // Bound on ready so they run after siddhi.js's own ready-time .inc/.dec handlers
    // (those update the quantity box first; same order as the original pages).
    $(function () {
    $(document).on('click', '.add-to-cart', async function () {
        @guest
            window.location.href = "{{ route('login') }}";
            return false;
        @endguest
        var id = $(this).attr('data-id');
        var quantity = parseInt($('input[name="quantity_' + id + '"]').val() || '0', 10);
        if (!quantity) {
            Swal.fire({text: "{{ trans('lang.invalid_qty') }}", icon: 'error'});
            return false;
        }
        var stock_quantity = $('#quantity_' + id).val();
        if (stock_quantity !== undefined && stock_quantity != -1 && quantity > parseInt(stock_quantity, 10)) {
            Swal.fire({text: "{{ trans('lang.invalid_stock_qty') }}", icon: 'error'});
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

        await cartPost("{{ route('add-to-cart') }}", {
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
        Swal.fire({text: "{{ trans('lang.added_tocart') }}", icon: 'success'});
    });

    $(document).on('click', '.remove_item', function () {
        cartPost("{{ route('remove-from-cart') }}", {vendor_id: $(this).attr('data-vendor'), id: $(this).attr('data-id')});
    });

    $(document).on('click', '.count-number-input-cart', function () {
        var id = $(this).attr('data-id');
        cartPost("{{ route('change-quantity-cart') }}", {
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
                Swal.fire({text: "{{ trans('lang.coupon_not_valid') }}", icon: 'error'});
                $('#coupon_code').val('');
                return;
            }
            cartPost("{{ route('apply-coupon') }}", {
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
@include('layouts.nav')
