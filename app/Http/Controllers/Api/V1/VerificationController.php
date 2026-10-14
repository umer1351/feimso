<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function submit(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'credential_type'          => 'required|string|max:128',
            'licence_number'           => 'nullable|string|max:191',
            'issuing_authority'        => 'nullable|string|max:255',
            'activity'                 => 'nullable|string|max:255',
            'licence_expiry'           => 'nullable|date',
            'vat_trn'                  => 'nullable|string|max:64',
            'advertiser_permit_number' => 'nullable|string|max:191',
            'document_urls'            => 'nullable|array',
            'document_urls.*'          => 'url',
        ]);

        $now = now();
        $id  = (string) Str::uuid();

        DB::table('creator_verifications')->insert([
            'id'       => $id,
            'user_id'  => $user->id,
            'role'     => $user->feimso_role,
            'status'   => 'pending',
            '_created' => $now,
            '_updated' => $now,
        ] + array_merge($data, [
            'document_urls' => isset($data['document_urls']) ? json_encode($data['document_urls']) : null,
        ]));

        // bump user status to pending
        DB::table('users')->where('id', $user->id)->update([
            'verification_status' => 'pending',
            '_updated'            => $now,
        ]);

        return response()->json([
            'message'         => 'Verification submitted. You will be notified once reviewed.',
            'verification_id' => $id,
        ], 201);
    }

    public function status(Request $request)
    {
        $user = $request->user();

        $verif = DB::table('creator_verifications')
            ->where('user_id', $user->id)
            ->orderByDesc('_created')
            ->first();

        return response()->json([
            'verification_status' => $user->verification_status,
            'latest_submission'   => $verif,
        ]);
    }
}
