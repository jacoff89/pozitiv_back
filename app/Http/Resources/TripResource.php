<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'cost' => $this->cost,
            'min_cost' => $this->min_cost,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'tourist_limit' => $this->tourist_limit,
            'additional_services' => $this->additionalServices,
            'bonuses' => $this->bonuses,
            'orders' => $this->orders,
        ];
    }
}
