<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Http\Resources\Orders\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

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
        $order->update($request->validated());
        return (new OrderResource($order->load('items')))
            ->additional(['message' => 'Order status updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     //
    // }
}