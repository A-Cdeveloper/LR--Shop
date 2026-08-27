<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            [
                'name' => 'Administrator',
                'password' => env('ADMIN_PASSWORD', 'password'),
            ]
        );

        if ($user->wasRecentlyCreated) {
            $user->forceFill([
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ])->save();
        }
    }
}
