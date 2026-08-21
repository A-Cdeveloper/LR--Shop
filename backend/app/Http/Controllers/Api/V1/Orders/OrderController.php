<?php

namespace App\Http\Controllers\Api\V1\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\StoreOrderRequest;
use App\Http\Resources\Orders\OrderResource;
use App\Http\Resources\Orders\OrderSummaryResource;
use App\Mail\OrderPlacedMail;
use App\Models\DeliveryMethod;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
        $orderData = $request->validated();
        $user = $request->user();
        $cart = $user->cart()->with('items.product')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return response()->json(['message' => __('api.orders.cart_empty')], 422);
        }



        $deliveryMethod = DeliveryMethod::query()
            ->whereKey($orderData['delivery_method_id'])
            ->where('is_active', true)
            ->first();

        if (! $deliveryMethod) {
            return response()->json([
                'message' => __('api.orders.invalid_delivery_method'),
            ], 422);
        }


        $paymentMethod = PaymentMethod::query()
            ->whereKey($orderData['payment_method_id'])
            ->where('is_active', true)
            ->first();

        if (! $paymentMethod) {
            return response()->json([
                'message' => __('api.orders.invalid_payment_method'),
            ], 422);
        }



        $order = DB::transaction(function () use ($orderData, $user, $cart, $deliveryMethod, $paymentMethod) {


            foreach ($cart->items as $item) {
                $product = $item->product()->lockForUpdate()->first();
                if ($item->quantity > $product->stock) {
                    abort(response()->json([
                        'message' => __('api.cart.not_enough_stock'),
                    ], 422));
                }
                $product->decrement('stock', $item->quantity);
            }

            $total = $cart->items->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });


            $deliveryPrice = (float) $deliveryMethod->price;

            if (
                $deliveryMethod->free_over !== null
                && $total >= (float) $deliveryMethod->free_over
            ) {
                $deliveryPrice = 0;
            }

            $total = round($total + $deliveryPrice, 2);



            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => round($total, 2),
                'currency' => Setting::get('shop.currency', 'EUR'),
                'delivery_method_id' => $deliveryMethod->id,
                'delivery_method_name' => $deliveryMethod->name,
                'delivery_price' => $deliveryPrice,
                'payment_method_id' => $paymentMethod->id,
                'payment_method_name' => $paymentMethod->name,
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


        Mail::to($user->email)->send(new OrderPlacedMail($order->load('items')));

        return (new OrderResource($order->load('items')))
            ->additional([
                'message' => __('api.orders.placed'),
            ])
            ->response()
            ->setStatusCode(201);
    }
}
