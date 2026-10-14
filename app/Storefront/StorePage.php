<?php

namespace App\Storefront;

use App\Firestore\Codec;
use App\Helpers\FirestoreHelper;

/**
 * Data for the store page (vendor/vendor.blade.php), loaded server-side from
 * MySQL in a handful of queries instead of the old page's chain of browser
 * Firestore calls.
 */
class StorePage
{
    public function load(string $vendorId): ?array
    {
        $vendor = FirestoreHelper::getDocument("vendors/$vendorId");
        if (!$vendor) {
            return null;
        }
        $vendor['id'] = $vendorId;

        $section = !empty($vendor['section_id']) ? FirestoreHelper::getDocument('sections/' . $vendor['section_id']) : null;
        $commission = $this->commission($section, $vendor);

        $categories = [];
        foreach (FirestoreHelper::query('vendor_categories', [['publish', '==', true]]) as $c) {
            $categories[$c['id']] = $c['data'];
        }

        $groups = [];
        foreach (FirestoreHelper::query('vendor_products', [['vendorID', '==', $vendorId], ['publish', '==', true]], [['createdAt', 'asc']]) as $p) {
            $d = $p['data'];
            $d['id'] = $p['id'];
            [$price, $dis] = $this->prices($d, $commission);
            $d['_price'] = $price;          // unit price shown/charged (discounted if any)
            $d['_original'] = $dis !== null ? $this->withCommission((float) $d['price'], $commission) : null;
            $d['_dis'] = $dis;
            $d['_addons'] = $this->addons($d, $commission);
            $d['_customize'] = !empty($d['item_attribute']['variants']) || !empty(array_filter((array) ($d['size'] ?? [])));
            $catId = (string) ($d['categoryID'] ?? '');
            $groups[$catId]['title'] = $categories[$catId]['title'] ?? trans('lang.other');
            $groups[$catId]['items'][] = $d;
        }

        $reviews = array_map(fn ($r) => $r['data'], FirestoreHelper::query('foods_review', [['VendorId', '==', $vendorId]], [], 20));
        usort($reviews, fn ($a, $b) => ($b['createdAt']['s'] ?? 0) <=> ($a['createdAt']['s'] ?? 0));

        $now = Codec::now();
        $coupons = array_values(array_filter(
            array_map(fn ($c) => $c['data'], FirestoreHelper::query('coupons', [['isEnabled', '==', true], ['vendorID', '==', $vendorId]])),
            fn ($c) => !empty($c['isPublic']) && (($c['expiresAt']['s'] ?? PHP_INT_MAX) >= $now['s'])
        ));

        return compact('vendor', 'section', 'groups', 'reviews', 'coupons');
    }

    /** Same rule as footer.blade.php processVendorData(): section switch, vendor override. */
    private function commission(?array $section, array $vendor): ?array
    {
        $sectionComm = $section['adminCommision'] ?? null;
        if (empty($sectionComm['enable'])) {
            return null;
        }
        $c = !empty($vendor['adminCommission']) && is_array($vendor['adminCommission']) ? $vendor['adminCommission'] : $sectionComm;
        return ['type' => $c['type'] ?? 'fixed', 'commission' => (float) ($c['commission'] ?? 0)];
    }

    private function withCommission(float $amount, ?array $commission): float
    {
        if (!$commission) {
            return $amount;
        }
        return $commission['type'] === 'percentage'
            ? $amount + $amount * $commission['commission'] / 100
            : $amount + $commission['commission'];
    }

    private function prices(array $p, ?array $commission): array
    {
        $price = $this->withCommission((float) ($p['price'] ?? 0), $commission);
        $disRaw = $p['disPrice'] ?? '';
        $hasDis = $disRaw !== '' && $disRaw !== null && (float) $disRaw > 0;
        $dis = $hasDis ? $this->withCommission((float) $disRaw, $commission) : null;
        return [$dis ?? $price, $dis];
    }

    private function addons(array $p, ?array $commission): array
    {
        $titles = array_values((array) ($p['addOnsTitle'] ?? []));
        $prices = array_values((array) ($p['addOnsPrice'] ?? []));
        $out = [];
        foreach ($titles as $i => $t) {
            if ($t === '' || $t === null) {
                continue;
            }
            $out[] = ['title' => (string) $t, 'price' => round($this->withCommission((float) ($prices[$i] ?? 0), $commission), 2)];
        }
        return $out;
    }
}
