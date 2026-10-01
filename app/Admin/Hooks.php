<?php

namespace App\Admin;

use App\Firestore\Codec;
use App\Firestore\CollectionSchema;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Keeps the denormalised fields the website relies on in sync when the admin
 * saves something (the old mobile/admin apps used to do this client-side).
 */
class Hooks
{
    public function __construct(private DocRepository $repo)
    {
    }

    public function beforeSave(string $collection, string $id, array $changes, ?array $existing): array
    {
        $isNew = $existing === null;
        $c = CollectionSchema::find($collection);

        if ($isNew) {
            if ($collection !== 'users') {
                $changes['id'] = $id; // most eMart docs repeat their id in an `id` field
            }
            if ($c->hasColumn('createdAt') && !isset($changes['createdAt'])) {
                $changes['createdAt'] = Codec::now();
            }
        }

        switch ($collection) {
            case 'vendors':
                $titles = [];
                foreach ($changes['categoryID'] ?? [] as $catId) {
                    $cat = $this->repo->find('vendor_categories', $catId);
                    if ($cat) {
                        $titles[] = $cat['title'] ?? '';
                    }
                }
                $changes['categoryTitle'] = $titles;
                if ($isNew) {
                    $changes += ['reviewsCount' => 0, 'reviewsSum' => 0, 'specialDiscountEnable' => false, 'specialDiscount' => [], 'photos' => [], 'restaurantMenuPhotos' => [], 'filters' => []];
                }
                break;

            case 'vendor_products':
                $vendor = isset($changes['vendorID']) ? $this->repo->find('vendors', $changes['vendorID']) : null;
                if ($vendor) {
                    $changes['section_id'] = $vendor['section_id'] ?? null;
                }
                if (count($changes['addOnsTitle'] ?? []) !== count($changes['addOnsPrice'] ?? [])) {
                    throw ValidationException::withMessages(['addOnsPrice' => __('admin.addons_mismatch')]);
                }
                if ($isNew) {
                    $changes += ['reviewsCount' => 0, 'reviewsSum' => 0, 'size' => [], 'sizePrice' => []];
                }
                break;

            case 'users':
                if (isset($changes['email'])) {
                    $taken = DB::table('users')->where('email', $changes['email'])->where('id', '!=', $id)->exists();
                    if ($taken) {
                        throw ValidationException::withMessages(['email' => __('admin.email_taken')]);
                    }
                }
                if ($isNew) {
                    $changes += ['wallet_amount' => 0, 'shippingAddress' => [], 'createdAt' => Codec::now()];
                }
                break;

            case 'currencies':
                // Only one active currency: the views take the first isActive row.
                if (!empty($changes['isActive'])) {
                    DB::table('currencies')->where('id', '!=', $id)->update(['isActive' => 0]);
                }
                break;

            case 'providers_services':
                if ($isNew) {
                    $changes += ['reviewsCount' => 0, 'reviewsSum' => 0];
                }
                break;
        }

        return $changes;
    }
}
