<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'parking_location_id',
        'zone_name',
        'zone_code',
        'vehicle_type',
        'floor',
        'section',
        'total_slots',
        'description',
        'status',
    ];

    public function parkingLocation(): BelongsTo
    {
        return $this->belongsTo(ParkingLocation::class);
    }

    public function slots(): HasMany
    {
        return $this->hasMany(ParkingSlot::class, 'zone_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'zone_id');
    }

    public function availableSlotsCount(): int
    {
        return $this->slots()->where('status', 'AVAILABLE')->count();
    }

    public function reservedSlotsCount(): int
    {
        return $this->slots()->where('status', 'RESERVED')->count();
    }

    public function occupiedSlotsCount(): int
    {
        return $this->slots()->where('status', 'OCCUPIED')->count();
    }
}
