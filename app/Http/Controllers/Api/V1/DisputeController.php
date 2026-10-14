<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DisputeController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'booking_id'   => 'nullable|string|max:191',
            'dispute_type' => 'required|in:item_not_received,item_not_as_described,damage,payment,other',
            'description'  => 'required|string|min:10',
            'evidence_urls' => 'nullable|array',
            'evidence_urls.*' => 'url',
        ]);

        $booking = null;
        if ($data['booking_id']) {
            $booking = DB::table('bookings')
                ->where('id', $data['booking_id'])
                ->where(function ($q) use ($request) {
                    $q->where('buyer_id', $request->user()->id)
                      ->orWhere('seller_id', $request->user()->id);
                })
                ->first();

            if (! $booking) {
                return response()->json(['message' => 'Booking not found or not yours.'], 404);
            }
        }

        $now = now();
        $id  = (string) Str::uuid();

        DB::table('disputes')->insert([
            'id'             => $id,
            'booking_id'     => $data['booking_id'] ?? null,
            'complainant_id' => $request->user()->id,
            'respondent_id'  => $booking ? ($booking->buyer_id === $request->user()->id ? $booking->seller_id : $booking->buyer_id) : null,
            'dispute_type'   => $data['dispute_type'],
            'description'    => $data['description'],
            'evidence_urls'  => isset($data['evidence_urls']) ? json_encode($data['evidence_urls']) : null,
            'status'         => 'open',
            '_created'       => $now,
            '_updated'       => $now,
        ]);

        if ($booking) {
            DB::table('bookings')->where('id', $booking->id)->update(['status' => 'disputed', '_updated' => $now]);
        }

        return response()->json(['message' => 'Dispute submitted.', 'dispute_id' => $id], 201);
    }

    public function myDisputes(Request $request)
    {
        $results = DB::table('disputes')
            ->where(function ($q) use ($request) {
                $q->where('complainant_id', $request->user()->id)
                  ->orWhere('respondent_id', $request->user()->id);
            })
            ->orderByDesc('_created')
            ->paginate(20);

        return response()->json($results);
    }
}
