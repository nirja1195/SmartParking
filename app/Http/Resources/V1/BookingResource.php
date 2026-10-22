<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_reference' => $this->booking_reference,
            'user' => new UserResource($this->whenLoaded('user')),
            'parking_spot' => new ParkingSpotResource($this->whenLoaded('spot')),
            'vehicle_plate' => $this->vehicle?->license_plate,
            'start_time' => $this->start_time?->toIso8601String(),
            'end_time' => $this->end_time?->toIso8601String(),
            'check_in_time' => $this->check_in_time?->toIso8601String(),
            'check_out_time' => $this->check_out_time?->toIso8601String(),
            'base_amount' => (float) $this->base_amount,
            'penalty_amount' => (float) $this->penalty_amount,
            'total_amount' => (float) $this->total_amount,
            'status' => $this->status,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
