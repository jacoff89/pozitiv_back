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
            'minCost' => $this->minCost,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd,
            'touristLimit' => $this->touristLimit,
            'tour_id' => $this->tour_id,
        ];
    }
}
