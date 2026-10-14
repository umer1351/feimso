<?php

namespace Database\Seeders;

use App\Firestore\Geo;
use Database\Seeders\Concerns\WritesDocs;
use Illuminate\Database\Seeder;

/**
 * Demo catalogue around Dubai (apparel, clothing and cosmetics) so every listing page has something to show.
 * Ids are readable on purpose (demo_*) so they are easy to find and remove.
 */
class CatalogSeeder extends Seeder
{
    use WritesDocs;

    public const ZONE = 'zone_uae';
    public const BEAUTY = 'section_beauty';
    public const SHOP = 'section_shop';

    public function run(): void
    {
        $img = fn ($f) => asset("img/$f");
        $ph = fn (int $n) => asset(sprintf('img/feimso/photos/fashion-%02d.jpg', $n));
        $ts = $this->now();

        // A zone covering the UAE; the views check the visitor's address is inside it.
        $this->doc('zone', self::ZONE, [
            'id' => self::ZONE, 'name' => 'UAE', 'publish' => true,
            'area' => array_map(fn ($p) => Geo::point($p[0], $p[1]), [
                [26.40, 51.40], [26.40, 56.60], [22.50, 56.60], [22.50, 51.40],
            ]),
        ]);

        $commission = ['enable' => false, 'commission' => 0, 'type' => 'percentage'];
        $this->doc('sections', self::SHOP, [
            'id' => self::SHOP, 'name' => 'Fashion', 'serviceType' => 'Ecommerce Service',
            'isActive' => true, 'color' => '#c9973a', 'sectionImage' => $ph(4),
            'dine_in_active' => false, 'nearByRadius' => '50', 'delivery_charge' => 10.0, 'is_product_details' => false,
            'adminCommision' => $commission,
        ]);
        $this->doc('sections', self::BEAUTY, [
            'id' => self::BEAUTY, 'name' => 'Beauty & Cosmetics', 'serviceType' => 'Ecommerce Service',
            'isActive' => true, 'color' => '#d1657f', 'sectionImage' => $ph(8),
            'dine_in_active' => false, 'nearByRadius' => '50', 'delivery_charge' => 10.0, 'is_product_details' => false,
            'adminCommision' => $commission,
        ]);

        $categories = [
            'cat_women' => [self::SHOP, 'Women', 6],
            'cat_men' => [self::SHOP, 'Men', 9],
            'cat_streetwear' => [self::SHOP, 'Streetwear', 1],
            'cat_footwear' => [self::SHOP, 'Footwear', 10],
            'cat_skincare' => [self::BEAUTY, 'Skincare', 8],
            'cat_makeup' => [self::BEAUTY, 'Makeup', 8],
        ];
        foreach ($categories as $id => [$section, $title, $n]) {
            $this->doc('vendor_categories', $id, [
                'id' => $id, 'title' => $title, 'photo' => $ph($n), 'description' => '',
                'section_id' => $section, 'publish' => true, 'show_in_homepage' => true, 'review_attributes' => [],
            ]);
        }

        $this->doc('brands', 'brand_feimso', ['id' => 'brand_feimso', 'title' => 'Feimso', 'photo' => $img('logo_web.png'), 'is_publish' => true, 'sectionId' => self::SHOP]);

        $hours = array_map(fn ($d) => ['day' => $d, 'timeslot' => [['from' => '00:00', 'to' => '23:59']]],
            ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);

        $vendors = [
            'demo_vendor_boutique' => ['Feimso Boutique', self::SHOP, ['cat_women', 'cat_men', 'cat_footwear'], 25.1972, 55.2744, 3],
            'demo_vendor_street' => ['Urban Edit', self::SHOP, ['cat_streetwear', 'cat_footwear'], 25.0800, 55.1400, 11],
            'demo_vendor_beauty' => ['Glow Studio', self::BEAUTY, ['cat_skincare', 'cat_makeup'], 25.2048, 55.2708, 8],
        ];
        foreach ($vendors as $id => [$title, $section, $cats, $lat, $lng, $n]) {
            $this->doc('vendors', $id, Geo::fields($lat, $lng) + [
                'id' => $id, 'title' => $title, 'description' => "$title — demo store",
                'photo' => $ph($n), 'photos' => [], 'location' => 'Dubai, United Arab Emirates',
                'phonenumber' => '+971500000000', 'latitude' => $lat, 'longitude' => $lng,
                'author' => AccountsSeeder::VENDOR_OWNER, 'section_id' => $section, 'zoneId' => self::ZONE,
                'categoryID' => $cats, 'categoryTitle' => array_map(fn ($c) => $categories[$c][1], $cats),
                'createdAt' => $ts, 'reviewsCount' => 0, 'reviewsSum' => 0, 'reststatus' => true,
                'workingHours' => $hours, 'specialDiscountEnable' => false, 'specialDiscount' => [],
                'isSelfDelivery' => false, 'enabledDiveInFuture' => false,
                'openDineTime' => '09:00 AM', 'closeDineTime' => '11:00 PM', 'restaurantCost' => 60,
                'restaurantMenuPhotos' => [], 'filters' => [],
            ]);
        }

        // [id, vendor, category, section, name, price, discounted price (0 = none), photo number]
        $products = [
            ['demo_p_dress', 'demo_vendor_boutique', 'cat_women', self::SHOP, 'Floral Wrap Dress', 320, 279, 6],
            ['demo_p_coat', 'demo_vendor_boutique', 'cat_women', self::SHOP, 'Powder Blue Trench Coat', 590, 0, 7],
            ['demo_p_shirt', 'demo_vendor_boutique', 'cat_men', self::SHOP, 'Essential V-Neck Tee', 95, 0, 9],
            ['demo_p_blouse', 'demo_vendor_boutique', 'cat_women', self::SHOP, 'Linen Summer Blouse', 150, 119, 2],
            ['demo_p_trousers', 'demo_vendor_street', 'cat_streetwear', self::SHOP, 'Striped Wide-Leg Trousers', 210, 0, 5],
            ['demo_p_set', 'demo_vendor_street', 'cat_streetwear', self::SHOP, 'Sunshine Hoodie Set', 380, 329, 1],
            ['demo_p_sneaker', 'demo_vendor_street', 'cat_footwear', self::SHOP, 'Red Runner Sneakers', 450, 399, 10],
            ['demo_p_serum', 'demo_vendor_beauty', 'cat_skincare', self::BEAUTY, 'Radiance Face Serum', 180, 149, 8],
            ['demo_p_lipstick', 'demo_vendor_beauty', 'cat_makeup', self::BEAUTY, 'Velvet Matte Lipstick', 85, 0, 4],
        ];
        foreach ($products as [$id, $vendor, $cat, $section, $name, $price, $dis, $n]) {
            $this->doc('vendor_products', $id, [
                'id' => $id, 'name' => $name, 'description' => "$name (demo product)",
                'price' => (string) $price, 'disPrice' => (string) $dis,
                'photo' => $ph($n), 'photos' => [$ph($n)],
                'vendorID' => $vendor, 'categoryID' => $cat, 'brandID' => 'brand_feimso',
                'section_id' => $section, 'publish' => true, 'createdAt' => $ts, 'quantity' => -1,
                'veg' => false, 'nonveg' => false, 'takeawayOption' => false,
                'calories' => 0, 'grams' => 0, 'proteins' => 0, 'fats' => 0,
                'addOnsTitle' => [], 'addOnsPrice' => [], 'size' => [], 'sizePrice' => [],
                'item_attribute' => null, 'product_specification' => [],
                'reviewsCount' => 0, 'reviewsSum' => 0, 'isDigitalProduct' => false,
            ]);
        }

        $this->doc('banner_items', 'demo_banner_1', [
            'title' => 'Welcome to Feimso', 'web_banner' => $img('web_banner_img.png'), 'is_publish' => true,
            'set_order' => 1, 'sectionId' => self::SHOP, 'position' => 'top', 'redirect_type' => '',
        ]);

        foreach (['about-us' => 'About us', 'help' => 'Help'] as $slug => $name) {
            $this->doc('cms_pages', "cms_$slug", ['slug' => $slug, 'name' => $name, 'description' => "<p>$name content.</p>", 'publish' => true]);
        }
    }
}
