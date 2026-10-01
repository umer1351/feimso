<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    /**
     * Delete an account. Previously this was open to anyone who knew a uid;
     * now only an admin, or the account owner, may do it.
     */
    public function deleteUserFromDb(Request $request)
    {
        $uuid = (string) $request->input('uuid');
        $actor = Auth::guard('web')->user() ?? $request->user();
        if (!$actor || (!$actor->isAdmin() && (string) $actor->id !== $uuid)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $user = User::find($uuid);
        if (!$user) {
            return response()->json(['status' => 'okay', 'message' => 'User not found.'], 404);
        }
        $user->delete();
        return response()->json(['status' => 'okay', 'message' => 'User deleted successfully.'], 200);
    }
}
