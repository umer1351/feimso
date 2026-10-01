<?php

namespace Database\Seeders;

use App\Firestore\Geo;
use Database\Seeders\Concerns\WritesDocs;
use Illuminate\Database\Seeder;

/**
 * Demo catalogue around Dubai so every listing page has something to show.
 * Ids are readable on purpose (demo_*) so they are easy to find and remove.
 */
class CatalogSeeder extends Seeder
{
    use WritesDocs;

    public const ZONE = 'zone_uae';
    public const FOOD = 'section_food';
    public const SHOP = 'section_shop';

    public function run(): void
    {
        $img = fn ($f) => asset("img/$f");
        $ts = $this->now();

        // A zone covering the UAE; the views check the visitor's address is inside it.
        $this->doc('zone', self::ZONE, [
            'id' => self::ZONE, 'name' => 'UAE', 'publish' => true,
            'area' => array_map(fn ($p) => Geo::point($p[0], $p[1]), [
                [26.40, 51.40], [26.40, 56.60], [22.50, 56.60], [22.50, 51.40],
            ]),
        ]);

        $commission = ['enable' => false, 'commission' => 0, 'type' => 'percentage'];
        $this->doc('sections', self::FOOD, [
            'id' => self::FOOD, 'name' => 'Food', 'serviceType' => 'Multivendor Delivery Service',
            'isActive' => true, 'color' => '#ff3b5c', 'sectionImage' => $img('food_delivery.png'),
            'dine_in_active' => true, 'nearByRadius' => '50', 'is_product_details' => true,
            'adminCommision' => $commission,
        ]);
        $this->doc('sections', self::SHOP, [
            'id' => self::SHOP, 'name' => 'Shop', 'serviceType' => 'Ecommerce Service',
            'isActive' => true, 'color' => '#2a6f97', 'sectionImage' => $img('electronics.png'),
            'dine_in_active' => false, 'nearByRadius' => '50', 'delivery_charge' => 10.0, 'is_product_details' => false,
            'adminCommision' => $commission,
        ]);

        $categories = [
            'cat_burgers' => [self::FOOD, 'Burgers', 'popular1.png'],
            'cat_pizza' => [self::FOOD, 'Pizza', 'popular2.png'],
            'cat_fashion' => [self::SHOP, 'Fashion', 'women-img.png'],
            'cat_gadgets' => [self::SHOP, 'Gadgets', 'watch-img.png'],
        ];
        foreach ($categories as $id => [$section, $title, $photo]) {
            $this->doc('vendor_categories', $id, [
                'id' => $id, 'title' => $title, 'photo' => $img($photo), 'description' => '',
                'section_id' => $section, 'publish' => true, 'show_in_homepage' => true, 'review_attributes' => [],
            ]);
        }

        $this->doc('brands', 'brand_feimso', ['id' => 'brand_feimso', 'title' => 'Feimso', 'photo' => $img('logo_web.png'), 'is_publish' => true, 'sectionId' => self::SHOP]);

        $hours = array_map(fn ($d) => ['day' => $d, 'timeslot' => [['from' => '00:00', 'to' => '23:59']]],
            ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);

        $vendors = [
            'demo_vendor_burger' => ['Burger House', self::FOOD, ['cat_burgers'], 25.2048, 55.2708, 'starter1.jpg'],
            'demo_vendor_pizza' => ['Pizza Corner', self::FOOD, ['cat_pizza'], 25.0800, 55.1400, 'starter2.jpg'],
            'demo_vendor_boutique' => ['Feimso Boutique', self::SHOP, ['cat_fashion', 'cat_gadgets'], 25.1972, 55.2744, 'starter3.jpg'],
        ];
        foreach ($vendors as $id => [$title, $section, $cats, $lat, $lng, $photo]) {
            $this->doc('vendors', $id, Geo::fields($lat, $lng) + [
                'id' => $id, 'title' => $title, 'description' => "$title — demo store",
                'photo' => $img($photo), 'photos' => [], 'location' => 'Dubai, United Arab Emirates',
                'phonenumber' => '+971500000000', 'latitude' => $lat, 'longitude' => $lng,
                'author' => AccountsSeeder::VENDOR_OWNER, 'section_id' => $section, 'zoneId' => self::ZONE,
                'categoryID' => $cats, 'categoryTitle' => array_map(fn ($c) => $categories[$c][1], $cats),
                'createdAt' => $ts, 'reviewsCount' => 0, 'reviewsSum' => 0, 'reststatus' => true,
                'workingHours' => $hours, 'specialDiscountEnable' => false, 'specialDiscount' => [],
                'isSelfDelivery' => false, 'enabledDiveInFuture' => $section === self::FOOD,
                'openDineTime' => '09:00 AM', 'closeDineTime' => '11:00 PM', 'restaurantCost' => 60,
                'restaurantMenuPhotos' => [], 'filters' => [],
            ]);
        }

        $products = [
            ['demo_p_classic', 'demo_vendor_burger', 'cat_burgers', self::FOOD, 'Classic Burger', 28, 24, 'pro1.jpg'],
            ['demo_p_cheese', 'demo_vendor_burger', 'cat_burgers', self::FOOD, 'Double Cheese Burger', 36, 0, 'pro2.jpg'],
            ['demo_p_margherita', 'demo_vendor_pizza', 'cat_pizza', self::FOOD, 'Margherita Pizza', 42, 35, 'pro3.jpg'],
            ['demo_p_pepperoni', 'demo_vendor_pizza', 'cat_pizza', self::FOOD, 'Pepperoni Pizza', 48, 0, 'pro4.jpg'],
            ['demo_p_abaya', 'demo_vendor_boutique', 'cat_fashion', self::SHOP, 'Evening Abaya', 350, 299, 'women-img.png'],
            ['demo_p_shirt', 'demo_vendor_boutique', 'cat_fashion', self::SHOP, 'Linen Shirt', 120, 0, 'men-img.png'],
            ['demo_p_watch', 'demo_vendor_boutique', 'cat_gadgets', self::SHOP, 'Smart Watch', 499, 449, 'watch-img.png'],
        ];
        foreach ($products as [$id, $vendor, $cat, $section, $name, $price, $dis, $photo]) {
            $this->doc('vendor_products', $id, [
                'id' => $id, 'name' => $name, 'description' => "$name (demo product)",
                'price' => (string) $price, 'disPrice' => (string) $dis,
                'photo' => $img($photo), 'photos' => [$img($photo)],
                'vendorID' => $vendor, 'categoryID' => $cat, 'brandID' => $section === self::SHOP ? 'brand_feimso' : '',
                'section_id' => $section, 'publish' => true, 'createdAt' => $ts, 'quantity' => -1,
                'veg' => false, 'nonveg' => $section === self::FOOD, 'takeawayOption' => false,
                'calories' => 0, 'grams' => 0, 'proteins' => 0, 'fats' => 0,
                'addOnsTitle' => [], 'addOnsPrice' => [], 'size' => [], 'sizePrice' => [],
                'item_attribute' => null, 'product_specification' => [],
                'reviewsCount' => 0, 'reviewsSum' => 0, 'isDigitalProduct' => false,
            ]);
        }

        $this->doc('banner_items', 'demo_banner_1', [
            'title' => 'Welcome to Feimso', 'web_banner' => $img('web_banner_img.png'), 'is_publish' => true,
            'set_order' => 1, 'sectionId' => self::FOOD, 'position' => 'top', 'redirect_type' => '',
        ]);

        foreach (['about-us' => 'About us', 'help' => 'Help'] as $slug => $name) {
            $this->doc('cms_pages', "cms_$slug", ['slug' => $slug, 'name' => $name, 'description' => "<p>$name content.</p>", 'publish' => true]);
        }
    }
}
