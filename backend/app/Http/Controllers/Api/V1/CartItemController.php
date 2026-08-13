<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\Cart;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;

class CartItemController extends Controller
{


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartItemRequest $request)
    {
        $cart = Cart::query()->where('token', $request->header('X-Cart-Token'))->first();
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $productId = $request->validated()['product_id'];

        $item = $cart->items()
        ->where('product_id', $productId)
        ->first();

        if ($item) {
            $item->update([
                'quantity' => $item->quantity + $request->validated()['quantity'],
            ]);
        } else {
            $item = $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $request->validated()['quantity'],
            ]);
        }

        $item->load('product');

        return new CartItemResource($item);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartItemRequest $request, CartItem $cartItem)
    {
        $cart = Cart::query()->where('token', $request->header('X-Cart-Token'))->first();
        if (! $cart || $cartItem->cart_id !== $cart->id) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $cartItem->update($request->validated());

        $cartItem->load('product');

        return new CartItemResource($cartItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}