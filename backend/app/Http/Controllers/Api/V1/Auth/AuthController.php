<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Auth\UserResource;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        Cart::mergeGuest($request->header('X-Cart-Token'), $user);


        $user->sendEmailVerificationNotification();
        return (new UserResource($user))
            ->additional([
                'message' => 'Registered. Check your email to verify your account.',
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();

        // Check if the user exists and the password is correct
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Check if the user has verified their email
        if (! $user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Please verify your email first.'], 403);
        }

        // Check if the user is active
        if (! $user->is_active) {
            return response()->json(['message' => 'Your account is not active. Please contact support.'], 403);
        }

        $user->token = $user->createToken('auth_token')->plainTextToken;
        Cart::mergeGuest($request->header('X-Cart-Token'), $user);
        return new UserResource($user);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }
}
