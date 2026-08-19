<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDeliveryMethodRequest;
use App\Http\Requests\Admin\UpdateDeliveryMethodRequest;
use App\Http\Resources\DeliveryMethods\DeliveryMethodResource;
use App\Models\DeliveryMethod;

class AdminDeliveryMethodController extends Controller
{
    public function index()
    {
        return DeliveryMethodResource::collection(DeliveryMethod::all());
    }

    public function store(StoreDeliveryMethodRequest $request)
    {
        $method = DeliveryMethod::create($request->validated());

        return (new DeliveryMethodResource($method))
            ->additional(['message' => __('api.admin.delivery_method_created')])
            ->response()
            ->setStatusCode(201);
    }

    public function show(DeliveryMethod $deliveryMethod)
    {
        return new DeliveryMethodResource($deliveryMethod);
    }

    public function update(UpdateDeliveryMethodRequest $request, DeliveryMethod $deliveryMethod)
    {
        $deliveryMethod->update($request->validated());

        return (new DeliveryMethodResource($deliveryMethod->fresh()))
            ->additional(['message' => __('api.admin.delivery_method_updated')]);
    }

    public function destroy(DeliveryMethod $deliveryMethod)
    {
        $deliveryMethod->delete();

        return response()->noContent();
    }
}
