<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryMethods\DeliveryMethodResource;
use App\Models\DeliveryMethod;

class DeliveryMethodController extends Controller
{
    public function index()
    {
        $methods = DeliveryMethod::query()->active()->get();

        return DeliveryMethodResource::collection($methods);
    }
}