<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'authToken' => $this->additional['token'] ?? '',
            'tripId' => $this->trip_id,
            'amount' => $this->amount,
            'prepayment' => $this->prepayment,
            'bonuses' => $this->bonuses,
            'touristsCount' => $this->tourists_count,
            'comment' => $this->comment,
        ];
    }
}
