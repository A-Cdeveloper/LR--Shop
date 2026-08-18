<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAdminUserRequest;
use App\Models\User;
use App\Http\Resources\Users\AdminUserResource;

class AdminUserController extends Controller
{
    public function index()
    {
        $perPage = (int) request()->query('per_page', 10);
        $perPage = max(1, min($perPage, 50));

        $sortBy = request()->query('sort');
        $direction = request()->query('order');


        $active = request()->query('active');

        $users = User::query()
            ->sortBy($sortBy, $direction)
            ->filterActive($active)
            ->paginate($perPage);

        return AdminUserResource::collection($users);
    }


    public function show(User $user)
    {
        return new AdminUserResource($user);
    }


    public function update(UpdateAdminUserRequest $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return response()->json([
                'message' => __('api.admin.cannot_change_own_active_status'),
            ], 403);
        }

        $user->update($request->validated());

        if (! $user->is_active) {
            $user->tokens()->delete();
        }

        return (new AdminUserResource($user->fresh()))
            ->additional(['message' => __('api.admin.user_updated')]);
    }
}
