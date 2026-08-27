<?php

namespace App\Http\Controllers\Api\V1\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Http\Resources\Orders\OrderResource;
use App\Http\Resources\Orders\OrderSummaryResource;
use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class OrderController extends Controller
{



    public function index(Request $request)
    {

        $perPage = (int) request()->query('per_page', 10);
        $perPage = max(1, min($perPage, 50));

        $orders = $request->user()
            ->orders()
            ->latest()
            ->withCount('items')
            ->paginate($perPage);

        return OrderSummaryResource::collection($orders);
    }


    public function show(Request $request, Order $order)
    {
        $user = $request->user();
        if ($order->user_id !== $user->id) {
            return response()->json(['message' => __('api.common.unauthorized')], 404);
        }
        return new OrderResource($order->load('items'));
    }


    public function store(StoreOrderRequest $request)
    {
        $order = app(CheckoutService::class)->place(
            $request->user(),
            $request->validated()
        );

        $additional = [
            'message' => __('api.orders.placed'),
        ];

        if (filled($order->stripe_client_secret)) {
            $additional['client_secret'] = $order->stripe_client_secret;
        }

        return (new OrderResource($order))
            ->additional($additional)
            ->response()
            ->setStatusCode(201);
    }
}
