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
            'minCost' => $this->min_cost,
            'dateStart' => $this->date_start->format('d.m.Y'),
            'dateEnd' => $this->date_end->format('d.m.Y'),
            'touristLimit' => $this->tourist_limit,
            'services' => AdditionalServiceResource::collection($this->additionalServices),
            'bonuses' => $this->bonuses,
            'orders' => $this->orders,
        ];
    }
}
