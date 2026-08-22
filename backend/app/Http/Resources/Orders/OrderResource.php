<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'total' => $this->total,
            'tax_amount' => $this->tax_amount,
            'currency' => $this->currency,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'shipping_address' => $this->shipping_address,
            'city' => $this->city,
            'state' => $this->state,
            'zip' => $this->zip,
            'country' => $this->country,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'delivery_method_id' => $this->delivery_method_id,
            'delivery_method_name' => $this->delivery_method_name,
            'delivery_price' => $this->delivery_price,
            'payment_method_id' => $this->payment_method_id,
            'payment_method_name' => $this->payment_method_name,
            'payment_status' => $this->payment_status,
        ];
    }
}
