<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartItemRequest $request)
    {
        $cart = $this->resolveCart($request);

        if (! $cart) {
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
        $cart = $this->resolveCart($request);
        $this->ensureCartItemBelongsToCart($cartItem, $cart);

        $cartItem->update($request->validated());
        $cartItem->load('product');

        return new CartItemResource($cartItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, CartItem $cartItem)
    {
        $cart = $this->resolveCart($request);
        $this->ensureCartItemBelongsToCart($cartItem, $cart);

        $cartItem->delete();

        return response()->noContent();
    }

    /**
     * Resolve the cart from the request.
     * 
     */

    private function resolveCart(Request $request): ?Cart
    {
        $token = $request->header('X-Cart-Token');

        if (! $token) {
            return null;
        }

        return Cart::query()->where('token', $token)->first();
    }

    /**
     * Ensure the cart item belongs to the cart.
     */

    private function ensureCartItemBelongsToCart(CartItem $cartItem, ?Cart $cart): void
    {
        if (! $cart || $cartItem->cart_id !== $cart->id) {
            abort(404, 'Cart item not found');
        }
    }
}