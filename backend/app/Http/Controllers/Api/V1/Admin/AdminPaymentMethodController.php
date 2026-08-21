<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePaymentMethodRequest;
use App\Http\Requests\Admin\UpdatePaymentMethodRequest;
use App\Http\Resources\PaymentMethods\PaymentMethodResource;
use App\Models\PaymentMethod;

class AdminPaymentMethodController extends Controller
{
    public function index()
    {
        return PaymentMethodResource::collection(PaymentMethod::all());
    }

    public function store(StorePaymentMethodRequest $request)
    {
        $method = PaymentMethod::create($request->validated());

        return (new PaymentMethodResource($method))
            ->additional(['message' => __('api.admin.payment_method_created')])
            ->response()
            ->setStatusCode(201);
    }

    public function show(PaymentMethod $paymentMethod)
    {
        return new PaymentMethodResource($paymentMethod);
    }

    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->update($request->validated());

        return (new PaymentMethodResource($paymentMethod->fresh()))
            ->additional(['message' => __('api.admin.payment_method_updated')]);
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return response()->noContent();
    }
}
