<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    
    public function store(ForgotPasswordRequest $request)
    {
        $request->validated();

        Password::sendResetLink($request->only('email'));

        return response()->json([
            'message' => 'If that email exists, we sent a reset link.',
        ], 200);
    
    }
}