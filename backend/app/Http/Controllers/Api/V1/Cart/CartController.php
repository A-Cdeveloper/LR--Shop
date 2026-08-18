<?php

namespace App\Http\Controllers\Api\V1\Cart;

use App\Http\Controllers\Controller;
use App\Http\Resources\Cart\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{


    /**
     * Display the specified resource.
     */
    public function show(Request $request)

    {

        $user = auth('sanctum')->user();

        if ($user) {
            $cart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['token' => (string) \Illuminate\Support\Str::uuid()]
            );
        } else {
            $token = $request->header('X-Cart-Token');

            $cart = $token
                ? Cart::where('token', $token)->first()
                : null;

            if (! $cart) {
                $cart = Cart::create([
                    'token' => (string) \Illuminate\Support\Str::uuid(),
                ]);
            }
        }

        $cart->load('items.product');

        return (new CartResource($cart))
            ->response()
            ->header('X-Cart-Token', $cart->token);
    }




    public function destroy(Request $request)
    {
        $user = auth('sanctum')->user();

        if ($user) {
            $cart = Cart::where('user_id', $user->id)->first();
        } else {
            $token = $request->header('X-Cart-Token');
            $cart = $token ? Cart::where('token', $token)->first() : null;
        }

        if (! $cart) {
            return response()->json(['message' => __('api.cart.not_found')], 404);
        }

        $cart->items()->delete();

        return response()->noContent();
    }
}
