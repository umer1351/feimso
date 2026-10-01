<?php

namespace App\Http\Controllers\Admin;

use App\Admin\DocRepository;
use App\Firestore\Codec;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/** Server-side wallet credit/debit with a ledger row, in one transaction. */
class WalletController extends Controller
{
    public function adjust(Request $request, DocRepository $repo, string $userId)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|not_in:0|between:-1000000,1000000',
            'note' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($repo, $userId, $data) {
            $user = User::lockForUpdate()->findOrFail($userId);
            $amount = round((float) $data['amount'], 2);
            $user->wallet_amount = round((float) $user->wallet_amount + $amount, 2);
            $user->save();

            $repo->save('wallet', null, [
                'user_id' => $userId,
                'amount' => abs($amount),
                'isTopUp' => $amount > 0,
                'date' => Codec::now(),
                'payment_method' => 'admin',
                'payment_status' => 'success',
                'note' => ($data['note'] ?? '') ?: ($amount > 0 ? 'Admin credit' : 'Admin debit'),
                'transactionUser' => 'admin:' . Auth::id(),
                'order_id' => '',
            ]);
        });

        return back()->with('ok', __('admin.wallet_updated'));
    }
}
