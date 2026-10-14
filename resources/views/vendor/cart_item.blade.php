{{--
    Store cart partial. Rendered inside #cart_list on the store page and on
    checkout, and re-rendered by ProductController's cart endpoints (the JSON
    `html` key). Rebuilt from scratch — the original file was missing.

    Checkout (checkout/checkout.blade.php finalCheckout()) reads the hidden
    inputs below by id, so their ids and value formats must not change.
    Some endpoints re-render without `is_checkout`, so checkout mode is also
    taken from the request.
--}}
@php
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
@endphp

@if(!empty($order_complete))
    <div class="d-flex siddhi-cart-item-profile bg-white p-3">
        <p class="m-0">{{ trans('lang.your_order_placed_successfully') }}</p>
    </div>
@else

@if($vendorId && !empty($cart['vendor']['name']))
    <div class="bg-white p-3 border-bottom" id="vendor_place">
        <div class="d-flex align-items-center">
            @if(!empty($cart['vendor']['image']))
                <img id="vendor_image_place" src="{{ $cart['vendor']['image'] }}" class="rounded mr-2" style="width:44px;height:44px;object-fit:cover" alt="">
            @endif
            <div>
                <h6 class="mb-0" id="vendor_name_place">{{ $cart['vendor']['name'] }}</h6>
                <p class="mb-0 small text-muted" id="vendor_location_place"><i class="feather-map-pin"></i> {{ $cart['vendor']['location'] ?? '' }}</p>
            </div>
        </div>
    </div>
@endif

<input type="hidden" id="main_vendor_id" value="{{ $vendorId }}">

@if(count($items))
    <div class="bg-white p-3 sidebar-item-list">
        <h6 class="pb-3 mb-0">{{ trans('lang.cart_details') }}</h6>
        @foreach($items as $key => $it)
            @php
                $qty = (int) ($it['quantity'] ?? 0);
                $line = ((float) ($it['item_price'] ?? 0) + (float) ($it['extra_price'] ?? 0)) * $qty;
                $variant = is_array($it['variant_info'] ?? null) ? $it['variant_info'] : null;
                $extras = is_array($it['extra'] ?? null) ? $it['extra'] : (empty($it['extra']) ? [] : explode(',', (string) $it['extra']));
                $vqty = $variant['variant_qty'] ?? ($it['stock_quantity'] ?? -1);
            @endphp
            <div class="product-item gold-members row align-items-center py-2 border mb-2 rounded-lg m-0" data-id="{{ $key }}">
                {{-- read by finalCheckout(); ids only, no names (the store page reads names) --}}
                <input type="hidden" id="price_{{ $key }}" value="{{ $it['price'] ?? 0 }}">
                <input type="hidden" id="dis_price_{{ $key }}" value="{{ $it['dis_price'] ?? '' }}">
                <input type="hidden" id="item_price_{{ $key }}" value="{{ $it['item_price'] ?? 0 }}">
                <input type="hidden" id="photo_{{ $key }}" value="{{ $it['image'] ?? '' }}">
                <input type="hidden" id="extras_price_{{ $key }}" value="{{ $it['extra_price'] ?? 0 }}">
                <input type="hidden" id="size_{{ $key }}" value="{{ $it['size'] ?? '' }}">
                <input type="hidden" id="name_{{ $key }}" value="{{ $it['name'] ?? '' }}">
                <input type="hidden" id="quantity_{{ $key }}" value="{{ $qty }}">
                <input type="hidden" id="category_id_{{ $key }}" value="{{ $it['category_id'] ?? '' }}">
                @if($variant)
                    <input type="hidden" id="variant_info_{{ $key }}" value="{{ base64_encode(json_encode($variant)) }}">
                @endif
                @foreach($extras as $ex)
                    <input type="hidden" class="extras_{{ $key }}" value="{{ $ex }}">
                @endforeach

                <div class="media align-items-center col-6 px-1">
                    <div class="media-body">
                        <p class="m-0">{{ $it['name'] ?? '' }}</p>
                        @if($variant && !empty($variant['variant_options']) && is_array($variant['variant_options']))
                            <div class="variant-info"><ul class="m-0 p-0">
                                @foreach($variant['variant_options'] as $label => $value)
                                    <li class="variant"><span class="label">{{ $label }}</span> <span class="value">{{ $value }}</span></li>
                                @endforeach
                            </ul></div>
                        @endif
                        @if(!empty($it['size']))<small class="text-muted d-block">{{ trans('lang.size') }}: {{ $it['size'] }}</small>@endif
                        @if(count($extras))<small class="text-muted d-block">{{ trans('lang.extra') }}: {{ implode(', ', $extras) }}</small>@endif
                    </div>
                </div>
                <div class="d-flex align-items-center count-number-box col-5 px-1">
                    <span class="count-number">
                        <button type="button" class="count-number-input-cart btn-sm left dec btn btn-outline-secondary" data-id="{{ $key }}" data-vendor="{{ $vendorId }}" data-vqty="{{ $vqty }}"><i class="feather-minus"></i></button>
                        <input class="count-number-input count_number_{{ $key }}" type="text" readonly value="{{ $qty }}">
                        <button type="button" class="count-number-input-cart btn-sm right inc btn btn-outline-secondary" data-id="{{ $key }}" data-vendor="{{ $vendorId }}" data-vqty="{{ $vqty }}" data-vqtymsg="{{ trans('lang.invalid_stock_qty') }}"><i class="feather-plus"></i></button>
                    </span>
                    <p class="text-gray mb-0 ml-2 text-muted small">
                        <span class="currency-symbol-left"></span><span class="cart_iteam_total_{{ $key }}">{{ $money($line) }}</span><span class="currency-symbol-right"></span>
                    </p>
                </div>
                <div class="close remove_item col-1 px-1 text-center" data-id="{{ $key }}" data-vendor="{{ $vendorId }}"><i class="fa fa-times"></i></div>
            </div>
        @endforeach
    </div>

    <div class="bg-white px-3 clearfix">
        <div class="border-bottom pb-3">
            <div class="input-group-sm mb-2 input-group">
                <input placeholder="{{ trans('lang.promo_help') }}" value="{{ $coupon['coupon_code'] ?? '' }}" id="coupon_code" data-vendor-id="{{ $vendorId }}" type="text" class="form-control">
                <div class="input-group-append">
                    <button type="button" class="btn btn-primary" id="apply-coupon-code"><i class="feather-percent"></i> {{ trans('lang.apply') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- One hidden delivery_option first: checkout reads the first matching input's value. --}}
    <input type="hidden" name="delivery_option" value="{{ $takeaway ? 'takeaway' : 'delivery' }}">

    @if(!$takeaway)
        <div class="bg-white clearfix delevery-partner">
            <div class="border-bottom p-3">
                <h3>{{ trans('lang.tip_your_delivery_partner') }}</h3>
                <div class="tip-box">
                    @foreach($tipOptions as $opt)
                        <div class="custom-control custom-radio border-bottom py-2">
                            <input type="radio" name="tip" id="tip_{{ $opt }}" value="{{ $opt }}" class="this_tip custom-control-input {{ (float) $tip === (float) $opt ? 'tip_checked' : '' }}" @checked((float) $tip === (float) $opt)>
                            <label class="custom-control-label" for="tip_{{ $opt }}"><span class="currency-symbol-left"></span>{{ $opt }}<span class="currency-symbol-right"></span></label>
                        </div>
                    @endforeach
                    <div class="custom-control custom-radio border-bottom py-2">
                        <input type="radio" name="tip" id="Other_tip" value="other" class="custom-control-input" @checked($tip > 0 && !in_array((float) $tip, array_map('floatval', $tipOptions), true))>
                        <label class="custom-control-label" for="Other_tip">{{ trans('lang.other') }}</label>
                    </div>
                    <div class="custom-control custom-radio border-bottom py-2" id="add_tip_box" style="display:none">
                        <input type="number" min="0" step="any" id="tip_amount" class="form-control" value="{{ $tip ?: '' }}" onchange="tipAmountChange('plus')">
                    </div>
                </div>
            </div>
        </div>
    @else
        <input type="hidden" id="tip_amount" value="0">
    @endif

    @if($isCheckout)
        <div class="bg-white px-3 clearfix schedule-order pt-3">
            <div class="border-bottom pb-3">
                <h3>{{ trans('lang.schedule_order') }}</h3>
                <span class="text-dark"><input type="datetime-local" id="scheduleTime" name="scheduleTime" class="form-control"></span>
            </div>
        </div>
    @endif

    <div class="bg-white p-3 clearfix btm-total">
        <p class="mb-2">{{ trans('lang.sub_total') }} <span class="float-right text-dark"><span class="currency-symbol-left"></span>{{ $money($subtotal) }}<span class="currency-symbol-right"></span></span></p>

        @if($couponAmount > 0)
            <p class="mb-2 text-success">{{ trans('lang.discount') }} ({{ $coupon['coupon_code'] }}) <span class="float-right text-success">- <span class="currency-symbol-left"></span>{{ $money($couponAmount) }}<span class="currency-symbol-right"></span></span></p>
        @endif
        @if($special > 0)
            <p class="mb-2 text-success">{{ trans('lang.special_discount') }} <span class="float-right text-success">- <span class="currency-symbol-left"></span>{{ $money($special) }}<span class="currency-symbol-right"></span></span></p>
        @endif

        @foreach($taxRows as $t)
            <p class="mb-2">{{ $t['title'] }} @if($t['label'])({{ $t['label'] }})@endif <span class="float-right text-dark"><span class="currency-symbol-left"></span>{{ $money($t['amount']) }}<span class="currency-symbol-right"></span></span></p>
        @endforeach

        @if($takeaway)
            <p class="mb-2">{{ trans('lang.take_away') }} <span class="float-right text-dark">{{ trans('lang.free') }}</span></p>
        @elseif($selfDelivery)
            <p class="mb-2">{{ trans('lang.deliveryCharge') }} <span class="float-right text-success">{{ trans('lang.free_delivery') }}</span></p>
        @else
            <p class="mb-2">{{ trans('lang.deliveryCharge') }} <span class="float-right text-dark"><span class="currency-symbol-left"></span>{{ $money($delivery) }}<span class="currency-symbol-right"></span></span></p>
        @endif

        @if($tip > 0)
            <p class="mb-2">{{ trans('lang.tip_amount') }} <span class="float-right text-dark"><span class="currency-symbol-left"></span>{{ $money($tip) }}<span class="currency-symbol-right"></span></span></p>
        @endif

        <hr>
        <h6 class="font-weight-bold mb-0">{{ trans('lang.total') }} <span class="float-right"><span class="currency-symbol-left"></span>{{ $money($total) }}<span class="currency-symbol-right"></span></span></h6>
    </div>

    {{-- read by finalCheckout() --}}
    <input type="hidden" id="total_pay" value="{{ round($total, 2) }}">
    <input type="hidden" id="coupon_code_main" value="{{ $coupon['coupon_code'] ?? '' }}">
    <input type="hidden" id="coupon_id" value="{{ $coupon['coupon_id'] ?? '' }}">
    <input type="hidden" id="discount_amount" value="{{ round($couponAmount, 2) }}">
    <input type="hidden" id="deliveryCharge" value="{{ $delivery }}">
    <input type="hidden" id="deliveryChargeMain" value="{{ $cart['deliverychargemain'] ?? 0 }}">
    <input type="hidden" id="adminCommission" value="0">
    <input type="hidden" id="adminCommissionType" value="fixed">
    <input type="hidden" id="tax_label" value="">
    <input type="hidden" id="tax" value="{{ round($tax, 2) }}">
    <input type="hidden" id="specialOfferDiscountAmount" value="{{ round($special, 2) }}">
    <input type="hidden" id="specialOfferType" value="{{ $specialType }}">
    <input type="hidden" id="specialOfferDiscountVal" value="{{ $specialVal }}">

    <div class="p-3">
        @if($isCheckout)
            <a class="btn btn-primary btn-block btn-lg" href="javascript:void(0)" onclick="finalCheckout()">
                {{ trans('lang.pay') }} <span class="currency-symbol-left"></span>{{ $money($total) }}<span class="currency-symbol-right"></span> <i class="feather-arrow-right"></i>
            </a>
        @else
            <a class="btn btn-primary btn-block btn-lg" href="{{ route('checkout') }}">{{ trans('lang.checkout') }} <i class="feather-arrow-right"></i></a>
        @endif
    </div>
@else
    <div class="bg-white py-2">
        <div class="gold-members d-flex align-items-center justify-content-between px-3 py-3">
            <span>{{ trans('lang.your_cart_is_empty') }}</span>
        </div>
    </div>
    <input type="hidden" id="total_pay" value="0">
    <input type="hidden" name="delivery_option" value="delivery">
@endif
@endif
