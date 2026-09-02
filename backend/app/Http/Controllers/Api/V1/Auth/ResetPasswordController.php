<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function update(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->password = $password;
                $user->save();
                $user->tokens()->delete();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json([
                'message' => __('api.auth.reset_token_invalid'),
                'code' => 'reset_token_invalid',
            ], 422);
        }

        return response()->json([
            'message' => __('api.auth.password_reset'),
        ], 200);
    }
}
