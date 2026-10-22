<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ParkingLot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'address',
        'city',
        'latitude',
        'longitude',
        'total_spots',
        'hourly_rate',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'hourly_rate' => 'decimal:2',
            'total_spots' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the individual parking spots in this lot.
     */
    public function spots(): HasMany
    {
        return $this->hasMany(ParkingSpot::class);
    }

    /**
     * Get all bookings across all spots in this lot.
     */
    public function bookings(): HasManyThrough
    {
        return $this->hasManyThrough(Booking::class, ParkingSpot::class);
    }

    /**
     * Count currently available spots in this lot.
     */
    public function availableSpotsCount(): int
    {
        return $this->spots()->where('is_active', true)->where('is_occupied', false)->count();
    }
}
