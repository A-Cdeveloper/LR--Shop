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
}