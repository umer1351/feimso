<?php

use App\Http\Controllers\AllVendorsController;
use App\Http\Controllers\Auth\AjaxController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DiveinVendorController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OffersController;
use App\Http\Controllers\OnDemandCheckoutController;
use App\Http\Controllers\OnDemandOrderController;
use App\Http\Controllers\OnDemandController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ParcelController;
use App\Http\Controllers\PayExtraChargeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SendEmailController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TrendingController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PayLaterServiceChargeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('set-location', [HomeController::class, 'setLocation'])->name('set-location');
Route::get('login', [LoginController::class, 'login'])->name('login');
Route::get('signup', [LoginController::class, 'signup'])->name('signup');
Route::get('search', [SearchController::class, 'index'])->name('search');
Route::get('privacy', [CmsController::class, 'privacypolicy'])->name('privacy');
Route::get('terms', [CmsController::class, 'termsofuse'])->name('terms');
Route::get('deliveryofsupport', [CmsController::class, 'deliveryofsupport'])->name('deliveryofsupport');
Route::get('lang/change', [LangController::class, 'change'])->name('changeLang');
Route::post('takeaway', [PaymentController::class, 'takeawayOption'])->name('takeaway');

Route::get('parcel/{id}', [ParcelController::class, 'parcel'])->name('parcel');
Route::get('parcel_checkout', [ParcelController::class, 'parcelCheckout'])->name('parcel_checkout');
Route::post('parcel_cart', [ParcelController::class, 'parcelCart'])->name('parcel_cart');
Route::post('parcel_order_proccessing', [ParcelController::class, 'parcelOrderProccessing'])->name('parcel_order_proccessing');
Route::get('process_parcel_order_pay', [ParcelController::class, 'processParcelOrderPay'])->name('process_parcel_order_pay');
Route::post('parcel_razorpay_payment', [ParcelController::class, 'parcelRazorpayPayment'])->name('parcel_razorpay_payment');
Route::get('parcel_success', [ParcelController::class, 'parcelSuccess'])->name('parcel_success');
Route::post('process_parcel_stripe', [ParcelController::class, 'processParcelStripePayment'])->name('process_parcel_stripe');
Route::post('apply_parcel_coupon', [ParcelController::class, 'applyParcelCoupon'])->name('apply_parcel_coupon');
Route::post('process_parcel_paypal', [ParcelController::class, 'processParcelPaypalPayment'])->name('process_parcel_paypal');
Route::get('parcel_notify', [ParcelController::class, 'parcelNotify'])->name('parcel_notify');
Route::post('parcel_order_complete', [ParcelController::class, 'parcelOrderComplete'])->name('parcel_order_complete');
Route::get('parcel_orders', [ParcelController::class, 'parcelOrders'])->name('parcel_orders');

Route::post('find-rental-cars', [RentalController::class, 'findRentalCars'])->name('find_rental_cars');
Route::get('rental-success', [RentalController::class, 'rentalSuccess'])->name('rental_success');
Route::post('rental-order-proccessing', [RentalController::class, 'rentalOrderProccessing'])->name('rental_order_proccessing');
Route::get('process-rental-order-pay', [RentalController::class, 'processRentalOrderPay'])->name('process_rental_order_pay');
Route::post('rental-razorpay-payment', [RentalController::class, 'rentalRazorpayPayment'])->name('rental_razorpay_payment');
Route::post('rental-order-complete', [RentalController::class, 'rentalOrderComplete'])->name('rental_order_complete');
Route::get('rental-notify', [RentalController::class, 'rentalNotify'])->name('rental_notify');
Route::post('process-rental-stripe', [RentalController::class, 'processRentalStripePayment'])->name('process_rental_stripe');
Route::post('process-rental-paypal', [RentalController::class, 'processRentalPaypalPayment'])->name('process_rental_paypal');
Route::post('apply-rental-coupon', [RentalController::class, 'applyRentalCoupon'])->name('apply_rental_coupon');
Route::get('rental-cars-checkout', [RentalController::class, 'rentalCarsCheckout'])->name('rental_cars_checkout');
Route::get('rental-orders', [RentalController::class, 'RentalOrders'])->name('rental_orders');
Route::get('rental-orders-detail/{id}', [RentalController::class, 'RentalOrdersDetails'])->name('rental_orders_detail');

Route::get('my_order', [OrderController::class, 'index'])->name('my_order');
Route::get('completed_order', [OrderController::class, 'completedOrders'])->name('completed_order');
Route::get('pending_order', [OrderController::class, 'pendingOrder'])->name('pending_order');
Route::get('cancelled_order', [OrderController::class, 'cancelledOrder'])->name('cancelled_order');
Route::get('rejected_order', [OrderController::class, 'rejectedOrder'])->name('rejected_order');
Route::get('my_dinein', [OrderController::class, 'myDinein'])->name('my_dinein');
Route::get('dinein', [OrderController::class, 'dinein'])->name('dinein');
Route::get('contact-us', [ContactUsController::class, 'index'])->name('contact_us');
Route::get('trending', [TrendingController::class, 'index'])->name('trending');
Route::get('category/{id}', [VendorController::class, 'categoryDetail'])->name('category_detail');
Route::get('vendor-detail/{id}', [VendorController::class, 'index'])->name('vendor');
Route::get('cart', [ProductController::class, 'cart'])->name('cart');
Route::post('add-to-cart', [ProductController::class, 'addToCart'])->name('add-to-cart');
Route::post('reorder-add-to-cart', [ProductController::class, 'reorderaddToCart'])->name('reorder-add-to-cart');
Route::post('update-cart', [ProductController::class, 'update'])->name('update-cart');
Route::post('remove-from-cart', [ProductController::class, 'remove'])->name('remove-from-cart');
Route::post('change-quantity-cart', [ProductController::class, 'changeQuantityCart'])->name('change-quantity-cart');
Route::post('apply-coupon', [ProductController::class, 'applyCoupon'])->name('apply-coupon');
Route::get('checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('order-complete', [ProductController::class, 'orderComplete'])->name('order-complete');
Route::post('order-tip-add', [ProductController::class, 'orderTipAdd'])->name('order-tip-add');
Route::post('order-delivery-option', [ProductController::class, 'orderDeliveryOption'])->name('order-delivery-option');
Route::get('pay', [CheckoutController::class, 'proccesstopay'])->name('pay');
Route::post('order-proccessing', [CheckoutController::class, 'orderProccessing'])->name('order-proccessing');
Route::post('stripepaymentcallback', [PaymentController::class, 'stripePaymentcallback'])->name('stripepaymentcallback');
Route::post('process-stripe', [CheckoutController::class, 'processStripePayment'])->name('process-stripe');
Route::post('process-paypal', [CheckoutController::class, 'processPaypalPayment'])->name('process-paypal');
Route::post('razorpaypayment', [CheckoutController::class, 'razorpaypayment'])->name('razorpaypayment');
Route::get('success', [CheckoutController::class, 'success'])->name('success');
Route::get('failed', [CheckoutController::class, 'failed'])->name('failed');
Route::get('notify', [CheckoutController::class, 'notify'])->name('notify');
Route::get('transactions', [TransactionController::class, 'index'])->name('transactions');
Route::get('offers', [OffersController::class, 'index'])->name('offers');
Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::get('favorite-stores', [FavoritesController::class, 'index'])->name('favorites');
Route::get('favorite-products', [FavoritesController::class, 'favProduct'])->name('favorites.product');
Route::get('faq', [FaqController::class, 'index'])->name('faq');
Route::get('vendors', [AllVendorsController::class, 'index'])->name('vendors');
Route::get('vendors/category/{id}', [AllVendorsController::class, 'VendorsbyCategory'])->name('vendorsbycategory');
Route::get('brands', [BrandsController::class, 'index'])->name('brands');
Route::get('categories', [VendorController::class, 'categoryList'])->name('categorylist');
Route::get('products/{type}/{id}', [ProductController::class, 'productList'])->name('productlist');
Route::get('products', [ProductController::class, 'productListAll'])->name('productlist.all');
Route::get('product/{id}', [ProductController::class, 'productDetail'])->name('productdetail');
Route::get('dinein', [DiveinVendorController::class, 'index'])->name('dineinVendors');
Route::get('dyiningvendor', [DiveinVendorController::class, 'dyiningvendor'])->name('dyiningvendor');
Route::post('sendnotification', [VendorController::class, 'sendnotification'])->name('sendnotification');
Route::post('setToken', [AjaxController::class, 'setToken'])->name('setToken');
Route::post('logout', [AjaxController::class, 'logout'])->name('logout');
Route::post('newRegister', [AjaxController::class, 'newRegister'])->name('newRegister');
Route::post('checkEmail', [App\Http\Controllers\Auth\AjaxController::class, 'checkEmail'])->name('checkEmail');
Route::post('sendemail/send', [SendEmailController::class, 'send'])->name('sendContactUsMail');
Route::get('my_order/{id}', [OrderController::class, 'edit'])->name('orderDetails');
Route::post('add-cart-note', [OrderController::class, 'addCartNote'])->name('add-cart-note');
Route::get('page/{slug}', [CmsController::class, 'index'])->name('page');
Route::post('send-email', [App\Http\Controllers\SendEmailController::class, 'sendMail'])->middleware('throttle:10,1')->name('sendMail');
Route::get('forgot-password', [App\Http\Controllers\LoginController::class, 'forgotPassword'])->name('forgot-password');
Route::post('remove-cart-data', [PaymentController::class, 'removeCartData'])->name('remove-cart-data');
Route::get('check-cart-data', [PaymentController::class, 'checkCartData'])->name('check-cart-data');
Route::get('buy-gift-card', [App\Http\Controllers\GiftCardController::class, 'index'])->name('customize.giftcard');
Route::post('gift-card-processing', [App\Http\Controllers\GiftCardController::class, 'giftCardProcessing'])->name('giftcard.processing');
Route::get('pay-giftcard', [App\Http\Controllers\GiftCardController::class, 'proccesstopay'])->name('giftcard.pay');
Route::get('gift-card-success', [App\Http\Controllers\GiftCardController::class, 'success'])->name('giftcard.success');
Route::get('giftcards', [App\Http\Controllers\GiftCardController::class, 'giftcards'])->name('giftcards');
Route::post('giftcard-razorpaypayment', [App\Http\Controllers\GiftCardController::class, 'razorpaypayment'])->name('giftcard.razorpaypayment');
Route::post('giftcard-stripepayment', [App\Http\Controllers\GiftCardController::class, 'processStripePayment'])->name('giftcard.stripepayment');
Route::post('giftcard-paypalpayment', [App\Http\Controllers\GiftCardController::class, 'processPaypalPayment'])->name('giftcard.paypalpayment');
Route::get('delivery-address', [App\Http\Controllers\DeliveryAddressController::class, 'index'])->name('delivery-address.index');
Route::get('service/{id}', [OnDemandController::class, 'index'])->name('service');
Route::get('provider/{id}', [OnDemandController::class, 'index'])->name('provider');
Route::get('ondemand-categories', [OnDemandController::class, 'categoryList'])->name('ondemand.categorylist');
Route::get('ondemand-provider/{id}', [OnDemandController::class, 'providerDetail'])->name('ondemand-providerdetail');
Route::get('ondemand-services', [OnDemandController::class, 'servicesList'])->name('ondemand-services');
Route::get('services/category/{id}', [OnDemandController::class, 'servicesByCategory'])->name('ServicebyCategory');
Route::post('ondemand-cart', [OnDemandController::class, 'onDemandCart'])->name('ondemand-cart');
Route::get('ondemand-checkout', [OnDemandController::class, 'onDemandCheckout'])->name('ondemand-checkout');
Route::post('set-extra-charge', [OnDemandController::class, 'setExtraCharge'])->name('set-extra-charge');
Route::get('pay-extra-charge', [OnDemandController::class, 'payExtraCharge'])->name('pay-extra-charge');
Route::post('set-service-charge', [PayLaterServiceChargeController::class, 'setServiceCharge'])->name('set-service-charge');
Route::get('pay-service-charge', [PayLaterServiceChargeController::class, 'payServiceCharge'])->name('pay-service-charge');
Route::post('change-service-quantity-cart', [OnDemandController::class, 'changeQuantityCart'])->name('change-service-quantity-cart');
Route::post('apply-service-coupon', [OnDemandController::class, 'applyCoupon'])->name('apply-service-coupon');
Route::post('remove-service-from-cart', [OnDemandController::class, 'remove'])->name('remove-service-from-cart');
Route::post('service-order-proccessing', [OnDemandCheckoutController::class, 'orderProccessing'])->name('service-order-proccessing');
Route::get('omdemand-pay', [OnDemandCheckoutController::class, 'proccesstopay'])->name('ondemand-pay');
Route::post('ondemand-razorpay-payment', [OnDemandCheckoutController::class, 'razorpaypayment'])->name('ondemand-razorpay-payment');
Route::post('ondemand-process-stripe', [OnDemandCheckoutController::class, 'processStripePayment'])->name('ondemand-process-stripe');
Route::post('ondemand-process-paypal', [OnDemandCheckoutController::class, 'processPaypalPayment'])->name('ondemand-process-paypal');
Route::get('ondemand-success', [OnDemandCheckoutController::class, 'success'])->name('ondemand-success');
Route::get('ondemand-failed', [OnDemandCheckoutController::class, 'failed'])->name('ondemand-failed');
Route::post('ondemand-order-complete', [OnDemandCheckoutController::class, 'orderComplete'])->name('ondemand-order-complete');
Route::get('my-bookings', [OnDemandOrderController::class, 'index'])->name('my-bookings');
Route::get('completed-booking', [OnDemandOrderController::class, 'completedBookings'])->name('completed-booking');
Route::get('pending-booking', [OnDemandOrderController::class, 'pendingBookings'])->name('pending-booking');
Route::get('cancelled-booking', [OnDemandOrderController::class, 'cancelledBookings'])->name('cancelled-booking');
Route::get('accepted-booking', [OnDemandOrderController::class, 'acceptedBookings'])->name('accepted-booking');
Route::get('ongoing-booking', [OnDemandOrderController::class, 'ongoingBookings'])->name('ongoing-booking');
Route::post('extra-pay-proccessing', [PayExtraChargeController::class, 'orderProccessing'])->name('extra-pay-proccessing');
Route::get('extra-pay', [PayExtraChargeController::class, 'proccesstopay'])->name('extra-pay');
Route::get('extra-pay-success', [PayExtraChargeController::class, 'success'])->name('extra-pay-success');
Route::get('extra-pay-failed', [PayExtraChargeController::class, 'failed'])->name('extra-pay-failed');
Route::post('extra-pay-razorpay', [PayExtraChargeController::class, 'razorpaypayment'])->name('extra-pay-razorpay');
Route::post('extra-pay-stripe', [PayExtraChargeController::class, 'processStripePayment'])->name('extra-pay-stripe');
Route::post('extra-pay-paypal', [PayExtraChargeController::class, 'processPaypalPayment'])->name('extra-pay-paypal');
Route::get('ondemand-search', [SearchController::class, 'onDemandSearch'])->name('ondemand-search');
Route::get('favorite-providers', [FavoritesController::class, 'favProvider'])->name('favorites.provider');
Route::get('favorite-services', [FavoritesController::class, 'favService'])->name('favorites.service');
Route::post('apply-service-charge-coupon', [PayLaterServiceChargeController::class, 'applyCoupon'])->name('apply-service-charge-coupon');
Route::post('service-charge-proccessing', [PayLaterServiceChargeController::class, 'orderProccessing'])->name('service-charge-proccessing');
Route::get('service-charge-pay', [PayLaterServiceChargeController::class, 'proccesstopay'])->name('service-charge-pay');
Route::get('service-charge-success', [PayLaterServiceChargeController::class, 'success'])->name('service-charge-success');
Route::get('service-charge-failed', [PayLaterServiceChargeController::class, 'failed'])->name('service-charge-failed');
Route::post('service-charge-razorpay', [PayLaterServiceChargeController::class, 'razorpaypayment'])->name('service-charge-razorpay');
Route::post('service-charge-stripe', [PayLaterServiceChargeController::class, 'processStripePayment'])->name('service-charge-stripe');
Route::post('service-charge-paypal', [PayLaterServiceChargeController::class, 'processPaypalPayment'])->name('service-charge-paypal');
Route::post('store-firebase-service', [HomeController::class,'storeServiceFile'])->name('storeServiceFile');

Route::get('pay-wallet', [App\Http\Controllers\TransactionController::class, 'proccesstopaywallet'])->name('pay-wallet');
Route::post('wallet-proccessing', [App\Http\Controllers\TransactionController::class, 'walletProccessing'])->name('wallet-proccessing');
Route::post('wallet-process-stripe', [App\Http\Controllers\TransactionController::class, 'processStripePayment'])->name('wallet-process-stripe');
Route::post('wallet-process-paypal', [App\Http\Controllers\TransactionController::class, 'processPaypalPayment'])->name('wallet-process-paypal');
Route::post('razorpaywalletpayment', [App\Http\Controllers\TransactionController::class, 'razorpaypayment'])->name('razorpaywalletpayment');
Route::post('wallet-process-mercadopago', [App\Http\Controllers\TransactionController::class, 'processMercadoPagoPayment'])->name('wallet-process-mercadopago');
Route::get('wallet-success', [App\Http\Controllers\TransactionController::class, 'success'])->name('wallet-success');
Route::get('wallet-notify', [App\Http\Controllers\TransactionController::class, 'notify'])->name('wallet-notify');
/*
| MySQL backend for public/js/firebase-local.js (replaces Firebase Firestore/Auth/Storage).
*/
Route::prefix('fs')->group(function () {
    Route::post('batch', [App\Http\Controllers\FirestoreController::class, 'batch'])->middleware('throttle:600,1');

    Route::post('auth/me', [App\Http\Controllers\FsAuthController::class, 'me']);
    Route::post('auth/login', [App\Http\Controllers\FsAuthController::class, 'login']);
    Route::post('auth/register', [App\Http\Controllers\FsAuthController::class, 'register'])->middleware('throttle:10,1');
    Route::post('auth/logout', [App\Http\Controllers\FsAuthController::class, 'logout']);
    Route::post('auth/password-reset', [App\Http\Controllers\FsAuthController::class, 'passwordReset'])->middleware('throttle:5,1');
    Route::post('auth/phone/send', [App\Http\Controllers\FsAuthController::class, 'phoneSend'])->middleware('throttle:10,1');
    Route::post('auth/phone/verify', [App\Http\Controllers\FsAuthController::class, 'phoneVerify'])->middleware('throttle:20,1');

    Route::post('storage/upload', [App\Http\Controllers\FsStorageController::class, 'upload'])->middleware('throttle:60,1');
    Route::post('storage/upload-string', [App\Http\Controllers\FsStorageController::class, 'uploadString'])->middleware('throttle:60,1');
    Route::post('storage/url', [App\Http\Controllers\FsStorageController::class, 'url']);
    Route::post('storage/delete', [App\Http\Controllers\FsStorageController::class, 'delete']);
});

Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

/*
| Feimso 2.0 — fashion marketplace web routes.
*/
Route::prefix('feimso')->name('feimso.')->group(function () {
    // Auth (guest)
    Route::middleware('guest')->group(function () {
        Route::get('register',         [App\Http\Controllers\Feimso\PageController::class, 'showRegister'])->name('register');
        Route::post('register',        [App\Http\Controllers\Feimso\PageController::class, 'register'])->name('register.submit');
        Route::get('login',            [App\Http\Controllers\Feimso\PageController::class, 'showLogin'])->name('login');
        Route::post('login',           [App\Http\Controllers\Feimso\PageController::class, 'login'])->name('login.submit');
        Route::get('forgot',           [App\Http\Controllers\Feimso\PageController::class, 'showForgot'])->name('forgot');
        Route::post('forgot',          [App\Http\Controllers\Feimso\PageController::class, 'forgot'])->name('forgot.submit');
        Route::get('forgot/sent',      [App\Http\Controllers\Feimso\PageController::class, 'forgotSent'])->name('forgot.sent');
    });

    // Auth (any)
    Route::post('logout',              [App\Http\Controllers\Feimso\PageController::class, 'logout'])->name('logout');
    Route::get('verify-email',         [App\Http\Controllers\Feimso\PageController::class, 'verifyEmail'])->name('verify-email');

    // Onboarding (auth required)
    Route::middleware('auth')->group(function () {
        Route::get('onboarding/step1',  [App\Http\Controllers\Feimso\PageController::class, 'onboardStep1'])->name('onboarding.step1');
        Route::post('onboarding/step1', [App\Http\Controllers\Feimso\PageController::class, 'onboardStep1Submit'])->name('onboarding.step1.submit');
        Route::get('onboarding/step2',  [App\Http\Controllers\Feimso\PageController::class, 'onboardStep2'])->name('onboarding.step2');
        Route::post('onboarding/step2', [App\Http\Controllers\Feimso\PageController::class, 'onboardStep2Submit'])->name('onboarding.step2.submit');
        Route::get('check-username',    [App\Http\Controllers\Feimso\PageController::class, 'checkUsername'])->name('check-username');
    });

    // Public browsing
    Route::get('feed',              [App\Http\Controllers\Feimso\PageController::class, 'feed'])->name('feed');
    Route::get('marketplace',       [App\Http\Controllers\Feimso\PageController::class, 'marketplace'])->name('marketplace');
    Route::get('profile',           [App\Http\Controllers\Feimso\PageController::class, 'profile'])->name('profile');
    Route::get('profile/{id}',      [App\Http\Controllers\Feimso\PageController::class, 'profile'])->name('profile.show');

    // Auth-protected (specific paths before wildcards)
    Route::middleware('auth')->group(function () {
        Route::get('listings/create',   [App\Http\Controllers\Feimso\PageController::class, 'createListing'])->name('listing.create');
        Route::post('listings',         [App\Http\Controllers\Feimso\PageController::class, 'storeListing'])->name('listing.store');
        Route::get('profile/edit',      [App\Http\Controllers\Feimso\PageController::class, 'editProfile'])->name('profile.edit');
        Route::post('bookings',         [App\Http\Controllers\Feimso\PageController::class, 'storeBooking'])->name('booking.store');
    });

    // Wildcard listing show (after specific paths)
    Route::get('listings/{id}',     [App\Http\Controllers\Feimso\PageController::class, 'showListing'])->name('listing.show');
});

/*
| Admin panel (/admin). Accounts with users.role = 'admin'.
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'show'])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->middleware('throttle:20,1');
    Route::get('locale/{locale}', [App\Http\Controllers\Admin\AuthController::class, 'locale'])->name('locale');

    Route::middleware('admin')->group(function () {
        Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/', App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');

        Route::get('settings/{tab?}', [App\Http\Controllers\Admin\SettingsController::class, 'edit'])->name('settings');
        Route::post('settings/{tab}', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');

        Route::post('users/{user}/wallet', [App\Http\Controllers\Admin\WalletController::class, 'adjust'])->name('wallet.adjust');

        Route::controller(App\Http\Controllers\Admin\ResourceController::class)->prefix('r/{name}')->name('resource.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('{id}', 'show')->name('show');
            Route::get('{id}/edit', 'edit')->name('edit');
            Route::put('{id}', 'update')->name('update');
            Route::delete('{id}', 'destroy')->name('destroy');
            Route::post('{id}/status', 'status')->name('status');
        });
    });
});
