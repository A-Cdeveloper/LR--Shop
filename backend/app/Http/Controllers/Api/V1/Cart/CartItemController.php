<?php

namespace App\Http\Controllers\Api\V1\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\StoreCartItemRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Http\Resources\Cart\CartItemResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartItemRequest $request)
    {
        $cart = $this->resolveCart($request);

        if (! $cart) {
            return response()->json(['message' => __('api.cart.not_found')], 404);
        }

        $productId = $request->validated()['product_id'];
        $quantity = $request->validated()['quantity'];
        $product = Product::findOrFail($productId);

        if (!$product->is_active) {
            return response()->json(['message' => __('api.cart.product_not_active')], 422);
        }

        $item = $cart->items()->where('product_id', $productId)->first();
        $alreadyInCart = $item?->quantity ?? 0;

        if ($alreadyInCart + $quantity > $product->stock) {
            return response()->json(['message' => __('api.cart.not_enough_stock')], 422);
        }

        if ($item) {
            $item->update(['quantity' => $alreadyInCart + $quantity]);
        } else {
            $item = $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
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

        $cartItem->load('product');

        $quantity = (int) $request->validated('quantity');

        if ($quantity > $cartItem->product->stock) {
            abort(422, __('api.cart.not_enough_stock_for_product'));
        }

        $cartItem->update(['quantity' => $quantity]);

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
        $user = auth('sanctum')->user();

        if ($user) {
            return Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['token' => (string) Str::uuid()]
            );
        }

        $token = $request->header('X-Cart-Token');

        if (! $token) {
            return null;
        }

        return Cart::where('token', $token)->first();
    }

    /**
     * Ensure the cart item belongs to the cart.
     */

    private function ensureCartItemBelongsToCart(CartItem $cartItem, ?Cart $cart): void
    {
        if (! $cart || $cartItem->cart_id !== $cart->id) {
            abort(404, __('api.cart.item_not_found'));
        }
    }
}
