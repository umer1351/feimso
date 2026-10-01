<?php

/*
| Settings screens in the admin panel. Each tab edits one or more documents
| of the `settings` collection; field types are the same as config/admin.php.
*/

$gateway = fn (string $enableKey, string $sandboxKey, array $keys) => array_merge(
    [$enableKey => ['type' => 'bool'], $sandboxKey => ['type' => 'bool']],
    array_map(fn ($secret) => ['type' => $secret ? 'secret' : 'text'], $keys),
);

return [
    'general' => [
        'icon' => 'settings',
        'docs' => [
            'globalSettings' => [
                'applicationName' => ['type' => 'text', 'required' => true],
                'appLogo' => ['type' => 'image'],
                'defaultCountryCode' => ['type' => 'text'],
                'isSelfDelivery' => ['type' => 'bool'],
                'isEnableAdsFeature' => ['type' => 'bool'],
            ],
            'placeHolderImage' => ['image' => ['type' => 'image']],
            'ContactUs' => ['Address' => ['type' => 'text'], 'Email' => ['type' => 'email'], 'Phone' => ['type' => 'text']],
            'maintenance_settings' => ['isMaintenanceModeForCustomer' => ['type' => 'bool']],
            'vendor' => ['subscription_model' => ['type' => 'bool']],
            'specialDiscountOffer' => ['isEnable' => ['type' => 'bool']],
            'story' => ['isEnabled' => ['type' => 'bool']],
        ],
    ],
    'delivery' => [
        'icon' => 'navigation',
        'docs' => [
            'DeliveryCharge' => [
                'amount' => ['type' => 'number'],
                'delivery_charges_per_km' => ['type' => 'number'],
                'minimum_delivery_charges' => ['type' => 'number'],
                'minimum_delivery_charges_within_km' => ['type' => 'number'],
                'vendor_can_modify' => ['type' => 'bool'],
            ],
            'DriverNearBy' => [
                'distanceType' => ['type' => 'select', 'options' => ['Km', 'Miles']],
                'driverRadios' => ['type' => 'text', 'help' => 'km'],
                'selectedMapType' => ['type' => 'select', 'options' => ['osm' => 'OpenStreetMap (free)', 'google' => 'Google Maps']],
                'enableOTPTripStartForRental' => ['type' => 'bool'],
            ],
            'VendorNearBy' => ['radios' => ['type' => 'text', 'help' => 'km']],
            'googleMapKey' => ['key' => ['type' => 'secret', 'help' => 'google_key_help']],
        ],
    ],
    'languages' => [
        'icon' => 'globe',
        'docs' => [
            'languages' => ['list' => ['type' => 'json', 'help' => 'languages_help']],
        ],
    ],
    'content' => [
        'icon' => 'file-text',
        'docs' => [
            'homepageTemplate' => ['homepageTemplate' => ['type' => 'html', 'help' => 'homepage_template_help']],
            'footerTemplate' => ['footerTemplate' => ['type' => 'html']],
            'privacyPolicy' => ['privacy_policy' => ['type' => 'html']],
            'termsAndConditions' => ['terms_and_condition' => ['type' => 'html']],
        ],
    ],
    'payments' => [
        'icon' => 'credit-card',
        'docs' => [
            'CODSettings' => ['isEnabled' => ['type' => 'bool']],
            'walletSettings' => ['isEnabled' => ['type' => 'bool']],
            'stripeSettings' => $gateway('isEnabled', 'isSandboxEnabled', ['stripeKey' => false, 'clientpublishableKey' => false, 'stripeSecret' => true]),
            'paypalSettings' => ['isEnabled' => ['type' => 'bool'], 'isLive' => ['type' => 'bool'], 'paypalClient' => ['type' => 'text'], 'paypalAppId' => ['type' => 'text'], 'paypalSecret' => ['type' => 'secret']],
            'razorpaySettings' => $gateway('isEnabled', 'isSandboxEnabled', ['razorpayKey' => false, 'razorpaySecret' => true]),
            'payStack' => $gateway('isEnable', 'isSandbox', ['publicKey' => false, 'secretKey' => true]),
            'flutterWave' => $gateway('isEnable', 'isSandbox', ['publicKey' => false, 'secretKey' => true, 'encryptionKey' => true]),
            'payFastSettings' => $gateway('isEnable', 'isSandbox', ['merchant_id' => false, 'merchant_key' => true, 'return_url' => false, 'cancel_url' => false, 'notify_url' => false]),
            'MercadoPago' => $gateway('isEnabled', 'isSandboxEnabled', ['PublicKey' => false, 'AccessToken' => true]),
            'xendit_settings' => $gateway('enable', 'isSandbox', ['apiKey' => true]),
            'midtrans_settings' => $gateway('enable', 'isSandbox', ['serverKey' => true]),
            'orange_money_settings' => $gateway('enable', 'isSandbox', ['clientId' => false, 'clientSecret' => true, 'merchantKey' => true, 'auth' => true, 'notifyUrl' => false, 'returnUrl' => false, 'cancelUrl' => false]),
        ],
    ],
];
