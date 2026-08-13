<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $token = $request->header('X-Cart-Token');
    
        $cart = $token
            ? Cart::query()->where('token', $token)->first()
            : null;
    
        if (! $cart) {
            $cart = Cart::query()->create([
                'token' => (string) \Illuminate\Support\Str::uuid(),
            ]);
        }
    
        $cart->load('items.product');
    
        return (new CartResource($cart))
        ->response()
        ->header('X-Cart-Token', $cart->token);
    }




}