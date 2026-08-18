<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function update(ChangePasswordRequest $request)
    {
        $request->validated();
        $user = $request->user();
        if(Hash::check($request->current_password, $user->password)) {
            $user->password = $request->new_password;
            $user->save();
            return response()->json(['message' => __('api.auth.password_changed')], 200);
        } else {
            return response()->json(['message' => __('api.auth.current_password_incorrect')], 422);
        }
    }
}