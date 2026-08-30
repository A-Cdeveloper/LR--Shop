<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResendVerificationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;


class EmailVerificationController extends Controller
{
    public function verify(Request $request, string $id, string $hash)
    {
        $frontendUrl = rtrim(config('app.frontend_url'), '/');

        if (! $request->hasValidSignature()) {
            return redirect()->away($frontendUrl . '/verify-email?status=error');
        }

        $user = User::find($id);

        if (! $user || ! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->away($frontendUrl . '/verify-email?status=error');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->away($frontendUrl . '/verify-email?status=already_verified');
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return redirect()->away($frontendUrl . '/verify-email?status=success');
    }


    public function resend(ResendVerificationRequest $request)
    {
        $user = User::where('email', $request->validated('email'))->first();

        if ($user && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return response()->json([
            'message' => __('api.auth.verification_resent'),
        ]);
    }
}
