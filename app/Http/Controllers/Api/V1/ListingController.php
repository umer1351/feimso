<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    private function table(): \Illuminate\Database\Query\Builder
    {
        return DB::table('listings');
    }

    public function index(Request $request)
    {
        $q = $this->table()->where('is_published', true)->where('status', 'active');

        if ($request->listing_type) {
            $q->where('listing_type', $request->listing_type);
        }
        if ($request->category) {
            $q->where('category', 'like', '%' . $request->category . '%');
        }
        if ($request->search) {
            $q->where(function ($sq) use ($request) {
                $sq->where('title', 'like', '%' . $request->search . '%')
                   ->orWhere('description', 'like', '%' . $request->search . '%')
                   ->orWhere('brand', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->min_price) {
            $q->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $q->where('price', '<=', $request->max_price);
        }

        $sort = $request->sort ?? 'newest';
        match ($sort) {
            'price_asc'  => $q->orderBy('price'),
            'price_desc' => $q->orderByDesc('price'),
            default      => $q->orderByDesc('_created'),
        };

        $results = $q->paginate((int) ($request->per_page ?? 20));

        return response()->json($results);
    }

    public function show(string $id)
    {
        $listing = $this->table()->where('id', $id)->first();

        if (! $listing) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        return response()->json(['listing' => $listing]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'listing_type'          => 'required|in:sale,rental,service,experience',
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'price'                 => 'nullable|numeric|min:0',
            'category'              => 'nullable|string|max:128',
            'brand'                 => 'nullable|string|max:128',
            'condition'             => 'nullable|in:new,like_new,good,fair',
            'delivery_option'       => 'nullable|in:pickup,courier,both',
            'return_policy'         => 'nullable|string',
            'cancellation_policy'   => 'nullable|in:flexible,moderate,strict',
            'rental_price_per_day'  => 'nullable|numeric|min:0',
            'deposit_amount'        => 'nullable|numeric|min:0',
            'booking_type'          => 'nullable|in:fixed,hourly',
            'available_from'        => 'nullable|date',
            'available_to'          => 'nullable|date',
            'location_type'         => 'nullable|in:in_person,remote,both',
            'location_address'      => 'nullable|string',
            'duration_minutes'      => 'nullable|integer|min:1',
            'scope_of_work'         => 'nullable|string',
            'max_participants'      => 'nullable|integer|min:1',
            'fulfilment_details'    => 'nullable|string',
            'authenticity_declared' => 'nullable|boolean',
            'liability_acknowledged' => 'nullable|boolean',
        ]);

        $now = now();
        $id  = (string) Str::uuid();

        DB::table('listings')->insert([
            'id'        => $id,
            'seller_id' => $request->user()->id,
            'status'    => 'draft',
            'is_published' => false,
            '_created'  => $now,
            '_updated'  => $now,
        ] + $data);

        return response()->json(['listing' => DB::table('listings')->where('id', $id)->first()], 201);
    }

    public function update(Request $request, string $id)
    {
        $listing = $this->table()->where('id', $id)->where('seller_id', $request->user()->id)->first();

        if (! $listing) {
            return response()->json(['message' => 'Not found or not yours.'], 404);
        }

        $data = $request->validate([
            'title'          => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'price'          => 'nullable|numeric|min:0',
            'condition'      => 'nullable|in:new,like_new,good,fair',
            'delivery_option' => 'nullable|in:pickup,courier,both',
            'return_policy'  => 'nullable|string',
            'status'         => 'nullable|in:draft,active,paused',
        ]);

        DB::table('listings')->where('id', $id)->update($data + ['_updated' => now()]);

        return response()->json(['listing' => DB::table('listings')->where('id', $id)->first()]);
    }

    public function myListings(Request $request)
    {
        $results = $this->table()
            ->where('seller_id', $request->user()->id)
            ->orderByDesc('_created')
            ->paginate(20);

        return response()->json($results);
    }
}
