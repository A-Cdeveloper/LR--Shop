<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
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
            return response()->json(['message' => 'Password changed successfully'], 200);
        } else {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }
    }
}