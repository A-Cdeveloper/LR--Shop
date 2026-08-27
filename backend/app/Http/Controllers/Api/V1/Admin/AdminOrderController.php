<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Http\Resources\Orders\OrderResource;
use App\Mail\OrderPlacedMail;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use App\Services\OrderStockService;
use App\Services\StripePaymentService;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = (int) request()->query('per_page', 10);
        $perPage = max(1, min($perPage, 50));

        $status = request()->query('status');
        $sortField = request()->query('sort');
        $sortDirection = request()->query('order');



        $orders = Order::query()
            ->withCount('items')
            ->filterStatus($status)
            ->sortBy($sortField, $sortDirection)
            ->paginate($perPage);

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return new OrderResource($order->load('items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderStatusRequest $request, Order $order)
    {
        $data = $request->validated();
        $oldStatus = $order->status;

        $statusChanged = array_key_exists('status', $data)
            && $data['status'] !== $order->status;

        $paymentStatusChanged = array_key_exists('payment_status', $data)
            && $data['payment_status'] !== $order->payment_status;

        if (! $statusChanged && ! $paymentStatusChanged) {
            return (new OrderResource($order->load('items')))
                ->additional(['message' => __('api.orders.status_updated')]);
        }

        $newStatus = $data['status'] ?? $order->status;
        $newPaymentStatus = $data['payment_status'] ?? $order->payment_status;

        app(OrderStockService::class)->transition($order, $newStatus, $newPaymentStatus);

        $order = $order->fresh()->load('items');

        if ($statusChanged) {
            Mail::to($order->user->email)->send(new OrderStatusMail($order, $oldStatus));
        }

        if ($paymentStatusChanged && $order->payment_status === 'paid') {
            Mail::to($order->user->email)->send(new OrderPlacedMail($order));
        }

        return (new OrderResource($order))
            ->additional(['message' => __('api.orders.status_updated')]);
    }

    /**
     * Refund the specified resource in storage.
     */
    public function refund(Order $order)
    {
        if (
            $order->payment_status !== 'paid'
            || blank($order->stripe_payment_intent_id)
        ) {
            return response()->json([
                'message' => __('api.orders.refund_not_allowed'),
            ], 422);
        }

        try {
            app(StripePaymentService::class)->refund($order);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => __('api.orders.refund_failed'),
            ], 502);
        }

        $oldStatus = $order->status;

        app(OrderStockService::class)->transition($order, 'refunded', 'refunded');

        $order = $order->fresh()->load('items');

        Mail::to($order->user->email)->send(new OrderStatusMail($order, $oldStatus));

        return (new OrderResource($order))
            ->additional(['message' => __('api.orders.refunded')]);
    }
}
