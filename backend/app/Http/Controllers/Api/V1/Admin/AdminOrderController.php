<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Http\Resources\Orders\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

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
        $newStatus = $request->validated('status');
        $oldStatus = $order->status;

        if ($newStatus === $oldStatus) {
            return (new OrderResource($order->load('items')))
                ->additional(['message' => __('api.orders.status_updated')]);
        }

        DB::transaction(function () use ($order, $newStatus, $oldStatus) {
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

            $order->update(['status' => $newStatus]);
        });

        return (new OrderResource($order->fresh()->load('items')))
            ->additional(['message' => __('api.orders.status_updated')]);
    }

    private function shouldRestoreStock(string $from, string $to): bool
    {
        return in_array($from, Order::STOCK_HELD_STATUSES, true)
            && in_array($to, Order::STOCK_RELEASED_STATUSES, true);
    }
}
