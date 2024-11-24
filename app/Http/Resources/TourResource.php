<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TourResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        foreach ($this->images as $img) {
            $images[] = Storage::disk('public')->url($img);
        }
        return [
            'id' =>$this->id,
            'name' => $this->name,
            'description' => $this->description,
            'duration' => $this->duration,
            'place' => $this->place,
            'planPicture' => Storage::disk('public')->url($this->plan_picture),
            'plan' => $this->plan,
            'season' => $this->season,
            'images' => $images,
            'trips' => TripResource::collection($this->trips),
        ];
    }
}
