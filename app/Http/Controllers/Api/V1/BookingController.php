<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function store(Request $request, string $listingId)
    {
        $listing = DB::table('listings')->where('id', $listingId)->where('is_published', true)->first();

        if (! $listing) {
            return response()->json(['message' => 'Listing not available.'], 404);
        }

        $data = $request->validate([
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'buyer_notes'  => 'nullable|string',
            'delivery_address' => 'nullable|string',
        ]);

        $now = now();
        $id  = (string) Str::uuid();

        DB::table('bookings')->insert([
            'id'           => $id,
            'listing_id'   => $listingId,
            'buyer_id'     => $request->user()->id,
            'seller_id'    => $listing->seller_id,
            'booking_type' => $listing->listing_type,
            'total'        => $listing->price,
            'currency'     => $listing->currency ?? 'AED',
            'status'       => 'pending',
            'payment_status' => 'pending',
            '_created'     => $now,
            '_updated'     => $now,
        ] + $data);

        return response()->json(['booking' => DB::table('bookings')->where('id', $id)->first()], 201);
    }

    public function myBookings(Request $request)
    {
        $results = DB::table('bookings')
            ->where('buyer_id', $request->user()->id)
            ->orderByDesc('_created')
            ->paginate(20);

        return response()->json($results);
    }

    public function mySales(Request $request)
    {
        $results = DB::table('bookings')
            ->where('seller_id', $request->user()->id)
            ->orderByDesc('_created')
            ->paginate(20);

        return response()->json($results);
    }

    public function show(Request $request, string $id)
    {
        $booking = DB::table('bookings')
            ->where('id', $id)
            ->where(function ($q) use ($request) {
                $q->where('buyer_id', $request->user()->id)
                  ->orWhere('seller_id', $request->user()->id);
            })
            ->first();

        if (! $booking) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        return response()->json(['booking' => $booking]);
    }

    public function cancel(Request $request, string $id)
    {
        $booking = DB::table('bookings')
            ->where('id', $id)
            ->where('buyer_id', $request->user()->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if (! $booking) {
            return response()->json(['message' => 'Cannot cancel this booking.'], 422);
        }

        DB::table('bookings')->where('id', $id)->update([
            'status'   => 'cancelled',
            '_updated' => now(),
        ]);

        return response()->json(['message' => 'Booking cancelled.']);
    }
}
