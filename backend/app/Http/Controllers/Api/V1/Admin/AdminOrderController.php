<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Http\Resources\Orders\OrderResource;
use App\Mail\OrderPlacedMail;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use App\Models\Product;
use App\Services\StripePaymentService;
use Illuminate\Support\Facades\DB;
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

        DB::transaction(function () use ($order, $data, $statusChanged, $oldStatus) {
            if ($statusChanged) {
                $newStatus = $data['status'];

                if ($this->shouldRestoreStock($oldStatus, $newStatus)) {
                    $order->load('items');

                    foreach ($order->items as $item) {
                        $product = Product::query()
                            ->whereKey($item->product_id)
                            ->lockForUpdate()
                            ->first();

                        if ($product) {
                            $product->increment('stock', $item->quantity);
                        }
                    }
                }
            }

            $order->update(collect($data)->only(['status', 'payment_status'])->all());
        });

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

        DB::transaction(function () use ($order, $oldStatus) {
            if ($this->shouldRestoreStock($oldStatus, 'refunded')) {
                $order->load('items');

                foreach ($order->items as $item) {
                    $product = Product::query()
                        ->whereKey($item->product_id)
                        ->lockForUpdate()
                        ->first();

                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
            }

            $order->update([
                'status' => 'refunded',
                'payment_status' => 'refunded',
            ]);
        });

        $order = $order->fresh()->load('items');

        Mail::to($order->user->email)->send(new OrderStatusMail($order, $oldStatus));

        return (new OrderResource($order))
            ->additional(['message' => __('api.orders.refunded')]);
    }

    // Private methods
    private function shouldRestoreStock(string $from, string $to): bool
    {
        return in_array($from, Order::STOCK_HELD_STATUSES, true)
            && in_array($to, Order::STOCK_RELEASED_STATUSES, true);
    }
}
