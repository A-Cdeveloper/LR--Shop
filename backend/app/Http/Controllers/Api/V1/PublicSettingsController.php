<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class PublicSettingsController extends Controller
{

    public function index(): JsonResponse
    {
        $settings = Setting::query()
            ->whereIn('key', Setting::PUBLIC_KEYS)
            ->get(['key', 'value'])
            ->mapWithKeys(fn(Setting $setting) => [$setting->key => $setting->value]);

        return response()->json([
            'settings' => [
                'name' => $settings->get('shop.name'),
                'email' => $settings->get('shop.email'),
                'phone' => $settings->get('shop.phone'),
                'address_line1' => $settings->get('shop.address_line1'),
                'address_line2' => $settings->get('shop.address_line2'),
                'city' => $settings->get('shop.city'),
            ],
        ]);
    }
}
