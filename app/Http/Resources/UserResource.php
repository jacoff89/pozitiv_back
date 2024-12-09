<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'token' => $this->additional['token'] ?? '',
            'isAdmin' => $this->isAdmin(),
            'mainTourist' => new TouristResource($this->mainTourist),
            'orders' => OrderResource::collection($this->orders),
        ];
    }
}
