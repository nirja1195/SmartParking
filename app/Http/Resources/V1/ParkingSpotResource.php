<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkingSpotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'parking_lot_id' => $this->parking_lot_id,
            'spot_number' => $this->spot_number,
            'floor' => $this->floor,
            'spot_type' => $this->spot_type,
            'is_active' => (bool) $this->is_active,
            'is_occupied' => (bool) $this->is_occupied,
            'effective_hourly_rate' => $this->getEffectiveRate(),
        ];
    }
}
