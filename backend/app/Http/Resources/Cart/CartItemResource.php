<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'slug' => $this->product->slug,
                    'price' => $this->product->price,
                    'sale_price' => $this->product->sale_price,
                    'effective_price' => $this->product->effectivePrice(),
                    'on_sale' => $this->product->onSale(),
                    'image' => $this->product->image ? url('storage/' . $this->product->image) : null,
                ];
            }),
            'subtotal' => round($this->quantity * $this->product->effectivePrice(), 2),
        ];
    }
}
