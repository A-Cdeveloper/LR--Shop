<?php

namespace App\Http\Controllers\Api\V1\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Http\Resources\Orders\OrderResource;
use App\Http\Resources\Orders\OrderSummaryResource;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class OrderController extends Controller
{



    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->withCount('items')
            ->latest()
            ->get();

        return OrderSummaryResource::collection($orders)->additional([
            'total' => $orders->count(),
        ]);
    }


    public function show(Request $request, Order $order)
    {
        $user = $request->user();
        if ($order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 404);
        }
        return new OrderResource($order->load('items'));
    }


    public function store(StoreOrderRequest $request)
    {
        $orderData = $request->validated();
        $user = $request->user();
        $cart = $user->cart()->with('items.product')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 422);
        }

        $order = DB::transaction(function () use ($orderData, $user, $cart) {
            $total = $cart->items->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => round($total, 2),
                ...$orderData,
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => round($item->quantity * $item->product->price, 2),
                ]);
            }

            $cart->items()->delete();

            return $order;
        });

        return (new OrderResource($order->load('items')))
            ->additional([
                'message' => 'Order placed successfully.',
            ])
            ->response()
            ->setStatusCode(201);
    }
}