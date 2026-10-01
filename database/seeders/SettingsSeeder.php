<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\WritesDocs;
use Illuminate\Database\Seeder;

/**
 * The `settings` documents the views read (one row per former Firestore doc).
 * Payment gateways are all disabled except cash on delivery and wallet; fill
 * in real keys from the admin panel.
 */
class SettingsSeeder extends Seeder
{
    use WritesDocs;

    public function run(): void
    {
        $img = fn ($f) => asset("img/$f");

        $docs = [
            'globalSettings' => [
                'applicationName' => 'Feimso',
                'appLogo' => $img('logo_web.png'),
                'defaultCountryCode' => '+971',
                'isSelfDelivery' => false,
                'isEnableAdsFeature' => false,
            ],
            'placeHolderImage' => ['image' => $img('pro1.jpg')],
            'DriverNearBy' => ['distanceType' => 'Km', 'driverRadios' => '50', 'selectedMapType' => 'osm', 'enableOTPTripStartForRental' => false],
            'VendorNearBy' => ['radios' => '50'],
            'DeliveryCharge' => [
                'amount' => 5.0,
                'vendor_can_modify' => false,
                'delivery_charges_per_km' => 1.0,
                'minimum_delivery_charges' => 5.0,
                'minimum_delivery_charges_within_km' => 3.0,
            ],
            'googleMapKey' => ['key' => ''],
            'vendor' => ['subscription_model' => false],
            'Version' => ['web_version' => '1.0.0'],
            'specialDiscountOffer' => ['isEnable' => false],
            'story' => ['isEnabled' => false],
            'languages' => ['list' => [
                ['title' => 'English', 'slug' => 'en', 'isActive' => true, 'is_rtl' => false],
                ['title' => 'العربية', 'slug' => 'ar', 'isActive' => true, 'is_rtl' => true],
            ]],
            'notification_setting' => ['serviceJson' => ''],
            // The /set-location page is just this HTML; layer.blade.php fills
            // #logo_web and #sections, and reads #user_locationnew + .btn-continue.
            'homepageTemplate' => ['homepageTemplate' => <<<'HTML'
<section class="py-5 bg-light" style="min-height:100vh">
  <div class="container">
    <div class="text-center mb-4"><div id="logo_web"></div></div>
    <div class="row justify-content-center">
      <div class="col-lg-7">
        <div class="bg-white shadow-sm rounded p-4">
          <h4 class="mb-3 text-center">Where should we deliver?</h4>
          <div class="input-group location-group mb-4">
            <input type="text" id="user_locationnew" class="form-control" placeholder="Search your address" autocomplete="off">
            <div class="input-group-append">
              <button type="button" class="btn btn-outline-secondary locate-me"><i class="feather-map-pin"></i> Locate me</button>
            </div>
          </div>
          <h6 class="mb-2">Choose a service</h6>
          <div class="cat-slider" id="sections"></div>
          <div class="text-center mt-4">
            <button type="button" class="btn btn-primary btn-lg btn-continue px-5">Continue</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
HTML],
            'footerTemplate' => ['footerTemplate' => ''],
            'privacyPolicy' => ['privacy_policy' => '<p>Privacy policy goes here.</p>'],
            'termsAndConditions' => ['terms_and_condition' => '<p>Terms and conditions go here.</p>'],
            'ContactUs' => ['Address' => 'Dubai, United Arab Emirates', 'Email' => 'support@feimso.local', 'Phone' => '+971 4 000 0000'],
            'maintenance_settings' => ['isMaintenanceModeForCustomer' => false],
            'CODSettings' => ['isEnabled' => true],
            'walletSettings' => ['isEnabled' => true],
            'razorpaySettings' => ['isEnabled' => false, 'isSandboxEnabled' => true, 'razorpayKey' => '', 'razorpaySecret' => ''],
            'stripeSettings' => ['isEnabled' => false, 'isSandboxEnabled' => true, 'stripeKey' => '', 'clientpublishableKey' => '', 'stripeSecret' => ''],
            'paypalSettings' => ['isEnabled' => false, 'isLive' => false, 'paypalClient' => '', 'paypalAppId' => '', 'paypalSecret' => ''],
            'payFastSettings' => ['isEnable' => false, 'isSandbox' => true, 'merchant_id' => '', 'merchant_key' => '', 'return_url' => '', 'cancel_url' => '', 'notify_url' => ''],
            'payStack' => ['isEnable' => false, 'isSandbox' => true, 'publicKey' => '', 'secretKey' => ''],
            'flutterWave' => ['isEnable' => false, 'isSandbox' => true, 'publicKey' => '', 'secretKey' => '', 'encryptionKey' => ''],
            'MercadoPago' => ['isEnabled' => false, 'isSandboxEnabled' => true, 'PublicKey' => '', 'AccessToken' => ''],
            'xendit_settings' => ['enable' => false, 'isSandbox' => true, 'apiKey' => '', 'image' => ''],
            'midtrans_settings' => ['enable' => false, 'isSandbox' => true, 'serverKey' => '', 'image' => ''],
            'orange_money_settings' => ['enable' => false, 'isSandbox' => true, 'auth' => '', 'clientId' => '', 'clientSecret' => '', 'merchantKey' => '', 'notifyUrl' => '', 'returnUrl' => '', 'cancelUrl' => '', 'image' => ''],
            'PaytmSettings' => ['isEnabled' => false],
            'AdminCommission' => ['isEnabled' => false],
            'taxSetting' => ['isEnabled' => false],
        ];

        foreach ($docs as $id => $data) {
            $this->doc('settings', $id, $data);
        }

        $this->doc('currencies', 'aed', [
            'code' => 'AED', 'symbol' => 'AED', 'name' => 'UAE Dirham',
            'decimal_degits' => 2, 'symbolAtRight' => false, 'isActive' => true,
        ]);
    }
}
