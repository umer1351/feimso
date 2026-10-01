<?php

/*
| Admin panel resources. Each resource is a Firestore-style collection
| (see config/firestore.php) with list columns and form fields.
|
| Field types:
|   text, email, color, textarea, html, number (float), integer, money (stored
|   as a numeric *string*, which is what the views expect for prices), bool,
|   select (options | source), multiselect (array of ids), image (URL, upload),
|   images (array of URLs), list (array of strings, comma separated),
|   datetime (timestamp), latlng (latitude/longitude + GeoFirestore g/coordinates),
|   polygon (array of GeoPoints, one "lat,lng" per line), json (raw JSON),
|   password (users only, hashed).
|
| Labels come from lang/{en,ar}/admin.php `fields.<name>`; missing keys fall
| back to a headline of the field name.
*/

$statuses = ['Order Placed', 'Order Accepted', 'Order Rejected', 'Driver Pending', 'Driver Accepted', 'Driver Rejected',
    'Order Shipped', 'In Transit', 'Order Assigned', 'Order Ongoing', 'Order Completed', 'Order Cancelled'];
$discountTypes = ['Percentage' => 'Percentage', 'Fix' => 'Fixed'];
$section = ['type' => 'select', 'source' => ['collection' => 'sections', 'label' => 'name']];

return [
    'groups' => [
        'catalog' => ['sections', 'vendor_categories', 'vendors', 'vendor_products', 'brands', 'vendor_attributes', 'review_attributes'],
        'orders' => ['vendor_orders', 'provider_orders', 'parcel_orders', 'rental_orders', 'booked_table'],
        'people' => ['users', 'wallet', 'items_review'],
        'marketing' => ['coupons', 'banner_items', 'gift_cards', 'gift_purchases', 'advertisements'],
        'services' => ['provider_categories', 'providers_services', 'providers_workers', 'providers_coupons'],
        'parcel_rental' => ['parcel_categories', 'parcel_weight', 'parcel_coupons', 'rental_vehicle_type', 'rental_packages', 'rental_coupons'],
        'config' => ['zone', 'currencies', 'tax', 'cms_pages', 'email_templates', 'dynamic_notification'],
    ],

    'order_statuses' => $statuses,

    'resources' => [
        'sections' => [
            'icon' => 'grid', 'title' => 'name', 'search' => ['name'],
            'columns' => ['sectionImage' => 'image', 'name', 'serviceType', 'isActive' => 'bool'],
            'fields' => [
                'name' => ['type' => 'text', 'required' => true],
                'serviceType' => ['type' => 'select', 'required' => true, 'options' => [
                    'Multivendor Delivery Service', 'Ecommerce Service', 'On Demand Service',
                    'Parcel Delivery Service', 'Rental Service', 'Cab Service']],
                'sectionImage' => ['type' => 'image'],
                'color' => ['type' => 'color', 'default' => '#ff3b5c'],
                'isActive' => ['type' => 'bool', 'default' => true],
                'dine_in_active' => ['type' => 'bool'],
                'nearByRadius' => ['type' => 'text', 'default' => '50', 'help' => 'km'],
                'delivery_charge' => ['type' => 'number', 'help' => 'ecommerce_delivery_help'],
                'is_product_details' => ['type' => 'bool'],
                'adminCommision' => ['type' => 'json', 'default' => ['enable' => false, 'commission' => 0, 'type' => 'percentage']],
            ],
        ],

        'vendor_categories' => [
            'icon' => 'tag', 'title' => 'title', 'search' => ['title'], 'filters' => ['section_id' => $section],
            'columns' => ['photo' => 'image', 'title', 'section_id' => 'ref:sections.name', 'publish' => 'bool', 'show_in_homepage' => 'bool'],
            'fields' => [
                'title' => ['type' => 'text', 'required' => true],
                'section_id' => $section + ['required' => true],
                'photo' => ['type' => 'image'],
                'description' => ['type' => 'textarea'],
                'publish' => ['type' => 'bool', 'default' => true],
                'show_in_homepage' => ['type' => 'bool', 'default' => true],
                'review_attributes' => ['type' => 'multiselect', 'source' => ['collection' => 'review_attributes', 'label' => 'title']],
            ],
        ],

        'vendors' => [
            'icon' => 'shopping-bag', 'title' => 'title', 'search' => ['title', 'location', 'phonenumber'],
            'filters' => ['section_id' => $section, 'zoneId' => ['type' => 'select', 'source' => ['collection' => 'zone', 'label' => 'name']]],
            'columns' => ['photo' => 'image', 'title', 'section_id' => 'ref:sections.name', 'location', 'reststatus' => 'bool'],
            'fields' => [
                'title' => ['type' => 'text', 'required' => true],
                'author' => ['type' => 'select', 'required' => true, 'source' => ['collection' => 'users', 'label' => 'email', 'where' => ['role' => 'vendor']], 'help' => 'vendor_owner_help'],
                'section_id' => $section + ['required' => true],
                'zoneId' => ['type' => 'select', 'required' => true, 'source' => ['collection' => 'zone', 'label' => 'name']],
                'categoryID' => ['type' => 'multiselect', 'source' => ['collection' => 'vendor_categories', 'label' => 'title']],
                'photo' => ['type' => 'image'],
                'photos' => ['type' => 'images'],
                'description' => ['type' => 'textarea'],
                'phonenumber' => ['type' => 'text'],
                'location' => ['type' => 'text', 'required' => true],
                'position' => ['type' => 'latlng', 'required' => true],
                'reststatus' => ['type' => 'bool', 'default' => true],
                'isSelfDelivery' => ['type' => 'bool'],
                'enabledDiveInFuture' => ['type' => 'bool'],
                'openDineTime' => ['type' => 'text', 'default' => '09:00 AM'],
                'closeDineTime' => ['type' => 'text', 'default' => '11:00 PM'],
                'restaurantCost' => ['type' => 'number'],
                'workingHours' => ['type' => 'json', 'default' => 'all_week'],
                'deliveryCharge' => ['type' => 'json', 'default' => ['delivery_charges_per_km' => 1, 'minimum_delivery_charges' => 5, 'minimum_delivery_charges_within_km' => 3]],
                'adminCommission' => ['type' => 'json'],
            ],
        ],

        'vendor_products' => [
            'icon' => 'package', 'title' => 'name', 'search' => ['name'],
            'filters' => ['vendorID' => ['type' => 'select', 'source' => ['collection' => 'vendors', 'label' => 'title']], 'publish' => ['type' => 'bool']],
            'columns' => ['photo' => 'image', 'name', 'vendorID' => 'ref:vendors.title', 'price', 'disPrice', 'quantity', 'publish' => 'bool'],
            'fields' => [
                'name' => ['type' => 'text', 'required' => true],
                'vendorID' => ['type' => 'select', 'required' => true, 'source' => ['collection' => 'vendors', 'label' => 'title']],
                'categoryID' => ['type' => 'select', 'required' => true, 'source' => ['collection' => 'vendor_categories', 'label' => 'title']],
                'brandID' => ['type' => 'select', 'source' => ['collection' => 'brands', 'label' => 'title']],
                'price' => ['type' => 'money', 'required' => true],
                'disPrice' => ['type' => 'money', 'default' => '0', 'help' => 'dis_price_help'],
                'quantity' => ['type' => 'integer', 'default' => -1, 'help' => 'quantity_help'],
                'photo' => ['type' => 'image'],
                'photos' => ['type' => 'images'],
                'description' => ['type' => 'html'],
                'publish' => ['type' => 'bool', 'default' => true],
                'veg' => ['type' => 'bool'],
                'nonveg' => ['type' => 'bool'],
                'takeawayOption' => ['type' => 'bool'],
                'addOnsTitle' => ['type' => 'list'],
                'addOnsPrice' => ['type' => 'list', 'help' => 'addons_help'],
                'calories' => ['type' => 'integer'], 'grams' => ['type' => 'integer'],
                'proteins' => ['type' => 'integer'], 'fats' => ['type' => 'integer'],
                'item_attribute' => ['type' => 'json', 'help' => 'item_attribute_help'],
                'product_specification' => ['type' => 'json'],
                'isDigitalProduct' => ['type' => 'bool'],
                'digitalProduct' => ['type' => 'text'],
            ],
        ],

        'brands' => [
            'icon' => 'award', 'title' => 'title', 'search' => ['title'],
            'columns' => ['photo' => 'image', 'title', 'is_publish' => 'bool'],
            'fields' => [
                'title' => ['type' => 'text', 'required' => true],
                'photo' => ['type' => 'image'],
                'sectionId' => $section,
                'is_publish' => ['type' => 'bool', 'default' => true],
            ],
        ],

        'vendor_attributes' => [
            'icon' => 'sliders', 'title' => 'title', 'search' => ['title'], 'columns' => ['title'],
            'fields' => ['title' => ['type' => 'text', 'required' => true]],
        ],

        'review_attributes' => [
            'icon' => 'star', 'title' => 'title', 'search' => ['title'], 'columns' => ['title'],
            'fields' => ['title' => ['type' => 'text', 'required' => true]],
        ],

        // ------------------------------------------------------------ orders
        'vendor_orders' => [
            'icon' => 'shopping-cart', 'orders' => true, 'create' => false, 'title' => 'id', 'search' => ['status', 'payment_method'],
            'filters' => ['status' => ['type' => 'select', 'options' => $statuses], 'section_id' => $section],
            'columns' => ['author' => 'path:author.firstName', 'vendor' => 'path:vendor.title', 'status' => 'status', 'payment_method', 'createdAt' => 'datetime'],
            'fields' => ['status' => ['type' => 'select', 'options' => $statuses], 'notes' => ['type' => 'textarea']],
        ],
        'provider_orders' => [
            'icon' => 'tool', 'orders' => true, 'create' => false, 'title' => 'id', 'search' => ['status'],
            'filters' => ['status' => ['type' => 'select', 'options' => $statuses]],
            'columns' => ['author' => 'path:author.firstName', 'provider' => 'path:provider.title', 'status' => 'status', 'payment_method', 'paymentStatus' => 'bool', 'createdAt' => 'datetime'],
            'fields' => ['status' => ['type' => 'select', 'options' => $statuses], 'paymentStatus' => ['type' => 'bool'], 'workerId' => ['type' => 'select', 'source' => ['collection' => 'providers_workers', 'label' => 'firstName']]],
        ],
        'parcel_orders' => [
            'icon' => 'box', 'orders' => true, 'create' => false, 'title' => 'id', 'search' => ['status'],
            'filters' => ['status' => ['type' => 'select', 'options' => $statuses]],
            'columns' => ['author' => 'path:author.firstName', 'parcelType', 'status' => 'status', 'payment_method', 'createdAt' => 'datetime'],
            'fields' => ['status' => ['type' => 'select', 'options' => $statuses], 'driverId' => ['type' => 'select', 'source' => ['collection' => 'users', 'label' => 'email', 'where' => ['role' => 'driver']]]],
        ],
        'rental_orders' => [
            'icon' => 'truck', 'orders' => true, 'create' => false, 'title' => 'id', 'search' => ['status'],
            'filters' => ['status' => ['type' => 'select', 'options' => $statuses]],
            'columns' => ['author' => 'path:author.firstName', 'rentalVehicleType' => 'path:rentalVehicleType.name', 'status' => 'status', 'paymentStatus' => 'bool', 'createdAt' => 'datetime'],
            'fields' => ['status' => ['type' => 'select', 'options' => $statuses], 'paymentStatus' => ['type' => 'bool'], 'driverId' => ['type' => 'select', 'source' => ['collection' => 'users', 'label' => 'email', 'where' => ['role' => 'driver']]]],
        ],
        'booked_table' => [
            'icon' => 'calendar', 'orders' => true, 'create' => false, 'title' => 'id', 'search' => ['guestFirstName', 'guestPhone', 'status'],
            'filters' => ['status' => ['type' => 'select', 'options' => ['Order Placed', 'Order Accepted', 'Order Rejected']]],
            'columns' => ['guestFirstName', 'vendor' => 'path:vendor.title', 'date' => 'datetime', 'totalGuest', 'status' => 'status'],
            'fields' => ['status' => ['type' => 'select', 'options' => ['Order Placed', 'Order Accepted', 'Order Rejected']]],
        ],

        // ------------------------------------------------------------ people
        'users' => [
            'icon' => 'users', 'title' => 'email', 'search' => ['email', 'firstName', 'lastName', 'phoneNumber'],
            'filters' => ['role' => ['type' => 'select', 'options' => ['customer', 'vendor', 'provider', 'driver', 'admin']], 'active' => ['type' => 'bool']],
            'columns' => ['profilePictureURL' => 'image', 'firstName', 'lastName', 'email', 'role', 'wallet_amount', 'active' => 'bool'],
            'fields' => [
                'firstName' => ['type' => 'text', 'required' => true],
                'lastName' => ['type' => 'text'],
                'email' => ['type' => 'email', 'required' => true, 'unique' => true],
                'countryCode' => ['type' => 'text', 'default' => '+971'],
                'phoneNumber' => ['type' => 'text'],
                'role' => ['type' => 'select', 'required' => true, 'options' => ['customer', 'vendor', 'provider', 'driver', 'admin'], 'default' => 'customer'],
                'active' => ['type' => 'bool', 'default' => true],
                'profilePictureURL' => ['type' => 'image'],
                'password' => ['type' => 'password', 'help' => 'password_help'],
                'vendorID' => ['type' => 'select', 'source' => ['collection' => 'vendors', 'label' => 'title'], 'help' => 'vendor_id_help'],
                'carName' => ['type' => 'text'], 'carNumber' => ['type' => 'text'],
            ],
        ],
        'wallet' => [
            'icon' => 'credit-card', 'create' => false, 'edit' => false, 'title' => 'id', 'search' => ['user_id', 'note', 'transactionUser'],
            'columns' => ['user_id' => 'ref:users.email', 'amount', 'isTopUp' => 'bool', 'payment_method', 'note', 'date' => 'datetime'],
            'fields' => [],
        ],
        'items_review' => [
            'icon' => 'message-square', 'create' => false, 'title' => 'id', 'search' => ['comment', 'uname'],
            'columns' => ['uname', 'rating', 'comment', 'productId' => 'ref:vendor_products.name', 'createdAt' => 'datetime'],
            'fields' => ['comment' => ['type' => 'textarea'], 'rating' => ['type' => 'number']],
        ],

        // --------------------------------------------------------- marketing
        'coupons' => [
            'icon' => 'percent', 'title' => 'code', 'search' => ['code', 'description'],
            'columns' => ['image' => 'image', 'code', 'discount', 'discountType', 'expiresAt' => 'datetime', 'isEnabled' => 'bool'],
            'fields' => [
                'code' => ['type' => 'text', 'required' => true],
                'discount' => ['type' => 'number', 'required' => true],
                'discountType' => ['type' => 'select', 'options' => $discountTypes, 'default' => 'Percentage'],
                'expiresAt' => ['type' => 'datetime', 'required' => true],
                'section_id' => $section,
                'vendorID' => ['type' => 'select', 'source' => ['collection' => 'vendors', 'label' => 'title'], 'help' => 'coupon_vendor_help'],
                'isEnabled' => ['type' => 'bool', 'default' => true],
                'isPublic' => ['type' => 'bool', 'default' => true],
                'image' => ['type' => 'image'],
                'description' => ['type' => 'textarea'],
            ],
        ],
        'banner_items' => [
            'icon' => 'image', 'title' => 'title', 'search' => ['title'],
            'columns' => ['web_banner' => 'image', 'title', 'position', 'set_order', 'is_publish' => 'bool'],
            'fields' => [
                'title' => ['type' => 'text', 'required' => true],
                'web_banner' => ['type' => 'image', 'required' => true],
                'sectionId' => $section + ['required' => true],
                'position' => ['type' => 'select', 'options' => ['top', 'middle'], 'default' => 'top'],
                'set_order' => ['type' => 'integer', 'default' => 1],
                'redirect_type' => ['type' => 'select', 'options' => ['' => '—', 'store' => 'Store', 'product' => 'Product', 'external_link' => 'External link']],
                'redirect_id' => ['type' => 'text', 'help' => 'redirect_id_help'],
                'is_publish' => ['type' => 'bool', 'default' => true],
            ],
        ],
        'gift_cards' => [
            'icon' => 'gift', 'title' => 'title', 'search' => ['title'],
            'columns' => ['image' => 'image', 'title', 'expiryDay', 'isEnable' => 'bool'],
            'fields' => [
                'title' => ['type' => 'text', 'required' => true],
                'image' => ['type' => 'image'],
                'message' => ['type' => 'textarea'],
                'expiryDay' => ['type' => 'integer', 'default' => 30],
                'isEnable' => ['type' => 'bool', 'default' => true],
            ],
        ],
        'gift_purchases' => [
            'icon' => 'gift', 'create' => false, 'edit' => false, 'title' => 'giftCode', 'search' => ['giftCode', 'giftTitle'],
            'columns' => ['giftTitle', 'giftCode', 'price', 'userid' => 'ref:users.email', 'redeem' => 'bool', 'expireDate' => 'datetime'],
            'fields' => [],
        ],
        'advertisements' => [
            'icon' => 'tv', 'title' => 'title', 'search' => ['title'],
            'columns' => ['coverImage' => 'image', 'title', 'vendorId' => 'ref:vendors.title', 'status', 'paymentStatus' => 'bool', 'endDate' => 'datetime'],
            'fields' => [
                'title' => ['type' => 'text', 'required' => true],
                'vendorId' => ['type' => 'select', 'source' => ['collection' => 'vendors', 'label' => 'title']],
                'type' => ['type' => 'select', 'options' => ['restaurant_promotion', 'video_promotion']],
                'status' => ['type' => 'select', 'options' => ['pending', 'approved', 'rejected'], 'default' => 'approved'],
                'paymentStatus' => ['type' => 'bool'], 'isPaused' => ['type' => 'bool'],
                'startDate' => ['type' => 'datetime'], 'endDate' => ['type' => 'datetime'],
                'priority' => ['type' => 'text'],
                'description' => ['type' => 'textarea'],
                'coverImage' => ['type' => 'image'], 'profileImage' => ['type' => 'image'],
                'video' => ['type' => 'text'],
                'showRating' => ['type' => 'bool'], 'showReview' => ['type' => 'bool'],
            ],
        ],

        // ---------------------------------------------------------- services
        'provider_categories' => [
            'icon' => 'layers', 'title' => 'title', 'search' => ['title'],
            'columns' => ['image' => 'image', 'title', 'level', 'publish' => 'bool'],
            'fields' => [
                'title' => ['type' => 'text', 'required' => true],
                'sectionId' => $section + ['required' => true],
                'level' => ['type' => 'integer', 'default' => 0, 'help' => 'level_help'],
                'parentCategoryId' => ['type' => 'select', 'source' => ['collection' => 'provider_categories', 'label' => 'title']],
                'image' => ['type' => 'image'],
                'publish' => ['type' => 'bool', 'default' => true],
            ],
        ],
        'providers_services' => [
            'icon' => 'briefcase', 'title' => 'title', 'search' => ['title', 'authorName', 'address'],
            'columns' => ['title', 'authorName', 'price', 'priceUnit', 'publish' => 'bool'],
            'fields' => [
                'title' => ['type' => 'text', 'required' => true],
                'author' => ['type' => 'select', 'required' => true, 'source' => ['collection' => 'users', 'label' => 'email', 'where' => ['role' => 'provider']]],
                'authorName' => ['type' => 'text'],
                'sectionId' => $section + ['required' => true],
                'categoryId' => ['type' => 'select', 'source' => ['collection' => 'provider_categories', 'label' => 'title']],
                'subCategoryId' => ['type' => 'select', 'source' => ['collection' => 'provider_categories', 'label' => 'title']],
                'price' => ['type' => 'money', 'required' => true],
                'disPrice' => ['type' => 'money', 'default' => '0'],
                'priceUnit' => ['type' => 'select', 'options' => ['Fixed', 'Hourly'], 'default' => 'Fixed'],
                'photos' => ['type' => 'images'],
                'description' => ['type' => 'html'],
                'address' => ['type' => 'text'],
                'position' => ['type' => 'latlng'],
                'days' => ['type' => 'list', 'default' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']],
                'startTime' => ['type' => 'text', 'default' => '09:00'],
                'endTime' => ['type' => 'text', 'default' => '18:00'],
                'publish' => ['type' => 'bool', 'default' => true],
            ],
        ],
        'providers_workers' => [
            'icon' => 'user-check', 'title' => 'firstName', 'search' => ['firstName', 'lastName', 'email', 'phoneNumber'],
            'columns' => ['profilePictureURL' => 'image', 'firstName', 'lastName', 'email', 'phoneNumber'],
            'fields' => [
                'firstName' => ['type' => 'text', 'required' => true], 'lastName' => ['type' => 'text'],
                'email' => ['type' => 'email'], 'phoneNumber' => ['type' => 'text'], 'address' => ['type' => 'text'],
                'providerId' => ['type' => 'select', 'source' => ['collection' => 'users', 'label' => 'email', 'where' => ['role' => 'provider']]],
                'profilePictureURL' => ['type' => 'image'],
            ],
        ],
        'providers_coupons' => [
            'icon' => 'percent', 'title' => 'code', 'search' => ['code'],
            'columns' => ['code', 'discount', 'discountType', 'expiresAt' => 'datetime', 'isEnabled' => 'bool'],
            'fields' => [
                'code' => ['type' => 'text', 'required' => true],
                'discount' => ['type' => 'money', 'required' => true],
                'discountType' => ['type' => 'select', 'options' => $discountTypes, 'default' => 'Percentage'],
                'expiresAt' => ['type' => 'datetime', 'required' => true],
                'sectionId' => $section,
                'providerId' => ['type' => 'select', 'source' => ['collection' => 'users', 'label' => 'email', 'where' => ['role' => 'provider']]],
                'isEnabled' => ['type' => 'bool', 'default' => true], 'isPublic' => ['type' => 'bool', 'default' => true],
                'image' => ['type' => 'image'], 'description' => ['type' => 'textarea'],
            ],
        ],

        // ---------------------------------------------------- parcel / rental
        'parcel_categories' => [
            'icon' => 'archive', 'title' => 'title', 'search' => ['title'],
            'columns' => ['image' => 'image', 'title', 'set_order', 'publish' => 'bool'],
            'fields' => [
                'title' => ['type' => 'text', 'required' => true], 'image' => ['type' => 'image'],
                'sectionId' => $section + ['required' => true],
                'set_order' => ['type' => 'integer', 'default' => 1], 'publish' => ['type' => 'bool', 'default' => true],
            ],
        ],
        'parcel_weight' => [
            'icon' => 'bar-chart', 'title' => 'title', 'search' => ['title'], 'columns' => ['title', 'delivery_charge'],
            'fields' => ['title' => ['type' => 'text', 'required' => true], 'delivery_charge' => ['type' => 'money', 'required' => true, 'help' => 'per_km_help']],
        ],
        'parcel_coupons' => [
            'icon' => 'percent', 'title' => 'code', 'search' => ['code'],
            'columns' => ['code', 'discount', 'discountType', 'expiresAt' => 'datetime', 'isEnabled' => 'bool'],
            'fields' => [
                'code' => ['type' => 'text', 'required' => true], 'discount' => ['type' => 'money', 'required' => true],
                'discountType' => ['type' => 'select', 'options' => $discountTypes, 'default' => 'Percentage'],
                'expiresAt' => ['type' => 'datetime', 'required' => true], 'sectionId' => $section,
                'isEnabled' => ['type' => 'bool', 'default' => true], 'isPublic' => ['type' => 'bool', 'default' => true],
                'image' => ['type' => 'image'], 'description' => ['type' => 'textarea'],
            ],
        ],
        'rental_vehicle_type' => [
            'icon' => 'truck', 'title' => 'name', 'search' => ['name'],
            'columns' => ['rental_vehicle_icon' => 'image', 'name', 'short_description', 'isActive' => 'bool'],
            'fields' => [
                'name' => ['type' => 'text', 'required' => true], 'short_description' => ['type' => 'text'],
                'description' => ['type' => 'textarea'], 'rental_vehicle_icon' => ['type' => 'image'],
                'sectionId' => $section + ['required' => true], 'isActive' => ['type' => 'bool', 'default' => true],
            ],
        ],
        'rental_packages' => [
            'icon' => 'clock', 'title' => 'name', 'search' => ['name'],
            'columns' => ['name', 'vehicleTypeId' => 'ref:rental_vehicle_type.name', 'baseFare', 'includedHours', 'includedDistance', 'published' => 'bool'],
            'fields' => [
                'name' => ['type' => 'text', 'required' => true], 'description' => ['type' => 'textarea'],
                'vehicleTypeId' => ['type' => 'select', 'required' => true, 'source' => ['collection' => 'rental_vehicle_type', 'label' => 'name']],
                'baseFare' => ['type' => 'number', 'required' => true], 'includedHours' => ['type' => 'number'],
                'includedDistance' => ['type' => 'number'], 'extraKmFare' => ['type' => 'number'],
                'extraMinuteFare' => ['type' => 'number'], 'published' => ['type' => 'bool', 'default' => true],
            ],
        ],
        'rental_coupons' => [
            'icon' => 'percent', 'title' => 'code', 'search' => ['code'],
            'columns' => ['code', 'discount', 'discountType', 'expiresAt' => 'datetime', 'isEnabled' => 'bool'],
            'fields' => [
                'code' => ['type' => 'text', 'required' => true], 'discount' => ['type' => 'money', 'required' => true],
                'discountType' => ['type' => 'select', 'options' => $discountTypes, 'default' => 'Percentage'],
                'expiresAt' => ['type' => 'datetime', 'required' => true], 'sectionId' => $section,
                'isEnabled' => ['type' => 'bool', 'default' => true], 'isPublic' => ['type' => 'bool', 'default' => true],
                'image' => ['type' => 'image'], 'description' => ['type' => 'textarea'],
            ],
        ],

        // ------------------------------------------------------------ config
        'zone' => [
            'icon' => 'map', 'title' => 'name', 'search' => ['name'],
            'columns' => ['name', 'publish' => 'bool'],
            'fields' => [
                'name' => ['type' => 'text', 'required' => true],
                'area' => ['type' => 'polygon', 'required' => true, 'help' => 'polygon_help'],
                'publish' => ['type' => 'bool', 'default' => true],
            ],
        ],
        'currencies' => [
            'icon' => 'dollar-sign', 'title' => 'code', 'search' => ['code', 'name'],
            'columns' => ['code', 'symbol', 'name', 'decimal_degits', 'isActive' => 'bool'],
            'fields' => [
                'name' => ['type' => 'text', 'required' => true], 'code' => ['type' => 'text', 'required' => true],
                'symbol' => ['type' => 'text', 'required' => true],
                'decimal_degits' => ['type' => 'integer', 'default' => 2],
                'symbolAtRight' => ['type' => 'bool'],
                'isActive' => ['type' => 'bool', 'help' => 'currency_active_help'],
            ],
        ],
        'tax' => [
            'icon' => 'file-text', 'title' => 'title', 'search' => ['title', 'country'],
            'columns' => ['title', 'country', 'tax', 'type', 'enable' => 'bool'],
            'fields' => [
                'title' => ['type' => 'text', 'required' => true], 'country' => ['type' => 'text', 'required' => true],
                'tax' => ['type' => 'number', 'required' => true],
                'type' => ['type' => 'select', 'options' => ['percentage' => 'Percentage', 'fix' => 'Fixed'], 'default' => 'percentage'],
                'sectionId' => $section, 'enable' => ['type' => 'bool', 'default' => true],
            ],
        ],
        'cms_pages' => [
            'icon' => 'file', 'title' => 'name', 'search' => ['name', 'slug'],
            'columns' => ['name', 'slug', 'publish' => 'bool'],
            'fields' => [
                'name' => ['type' => 'text', 'required' => true], 'slug' => ['type' => 'text', 'required' => true],
                'description' => ['type' => 'html'], 'publish' => ['type' => 'bool', 'default' => true],
            ],
        ],
        'email_templates' => [
            'icon' => 'mail', 'title' => 'type', 'search' => ['type', 'subject'],
            'columns' => ['type', 'subject'],
            'fields' => ['type' => ['type' => 'text', 'required' => true], 'subject' => ['type' => 'text', 'required' => true], 'message' => ['type' => 'html', 'required' => true]],
        ],
        'dynamic_notification' => [
            'icon' => 'bell', 'title' => 'type', 'search' => ['type', 'subject'],
            'columns' => ['type', 'subject'],
            'fields' => ['type' => ['type' => 'text', 'required' => true], 'subject' => ['type' => 'text', 'required' => true], 'message' => ['type' => 'textarea', 'required' => true]],
        ],
    ],
];
