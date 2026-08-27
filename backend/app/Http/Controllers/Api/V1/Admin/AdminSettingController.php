<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdminSettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = Setting::query()
            ->get(['key', 'value'])
            ->mapWithKeys(fn(Setting $setting) => [$setting->key => $setting->value]);

        return response()->json([
            'settings' => $settings,
        ]);
    }

    public function update(UpdateSettingsRequest $request): JsonResponse
    {
        $items = $request->validated('settings');

        DB::transaction(function () use ($items) {
            foreach ($items as $item) {
                Setting::query()->updateOrCreate(
                    ['key' => $item['key']],
                    ['value' => $item['value']],
                );
            }
        });

        Setting::flushCache();

        $settings = Setting::query()
            ->get(['key', 'value'])
            ->mapWithKeys(fn(Setting $setting) => [$setting->key => $setting->value]);

        return response()->json([
            'settings' => $settings,
            'message' => __('api.admin.settings_updated'),
        ]);
    }
}
