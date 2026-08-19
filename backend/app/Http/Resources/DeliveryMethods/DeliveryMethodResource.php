<?php

namespace App\Http\Resources\DeliveryMethods;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryMethodResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'free_over' => $this->free_over,
            'eta_days_min' => $this->eta_days_min,
            'eta_days_max' => $this->eta_days_max,
            'is_active' => $this->is_active,
        ];
    }
}