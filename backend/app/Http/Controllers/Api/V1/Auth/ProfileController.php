<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Auth\UserResource;
use App\Http\Requests\Auth\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return new UserResource($request->user());
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());

        return (new UserResource($user->fresh()))
            ->additional(['message' => 'Profile updated successfully.']);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return response()->json(['message' => 'Admin account cannot be deleted.'], 403);
        }

        $user->cart()?->delete(); // cart_items cascade sa cart-om
        $user->tokens()->delete();
        $user->delete(); // orders cascade

        return response()->noContent();
    }
}
