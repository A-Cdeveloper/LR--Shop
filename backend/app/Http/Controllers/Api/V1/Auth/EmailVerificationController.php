<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class EmailVerificationController extends Controller
{
    public function verify(Request $request, string $id, string $hash)
    {
        $user = \App\Models\User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, __('api.auth.invalid_verification_link'));
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        return response()->json([
            'message' => __('api.auth.email_verified'),
        ]);
    }
}
