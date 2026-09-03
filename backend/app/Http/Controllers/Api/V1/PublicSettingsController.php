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
            'settings' => $settings,
        ]);
    }
}
