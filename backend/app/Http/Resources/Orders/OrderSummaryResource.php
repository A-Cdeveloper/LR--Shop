<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderSummaryResource extends JsonResource
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
            'currency' => $this->currency,
            'items_count' => $this->items_count,
            'delivery_method_name' => $this->delivery_method_name,
            'payment_method_name' => $this->payment_method_name,
            'payment_status' => $this->payment_status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
