<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkingLotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'address' => $this->address,
            'city' => $this->city,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'total_spots' => (int) $this->total_spots,
            'available_spots' => $this->when(isset($this->available_spots_count), (int) $this->available_spots_count),
            'hourly_rate' => (float) $this->hourly_rate,
            'is_active' => (bool) $this->is_active,
            'spots' => ParkingSpotResource::collection($this->whenLoaded('spots')),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
