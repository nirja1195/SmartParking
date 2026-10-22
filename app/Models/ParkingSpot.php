<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingSpot extends Model
{
    use HasFactory;

    protected $fillable = [
        'parking_lot_id',
        'spot_number',
        'floor',
        'spot_type',
        'is_active',
        'is_occupied',
        'hourly_rate_override',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_occupied' => 'boolean',
            'hourly_rate_override' => 'decimal:2',
        ];
    }

    /**
     * The parking lot this spot belongs to.
     */
    public function lot(): BelongsTo
    {
        return $this->belongsTo(ParkingLot::class, 'parking_lot_id');
    }

    /**
     * Bookings made for this parking spot.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Effective hourly rate considering lot rate and spot-level override.
     */
    public function getEffectiveRate(): float
    {
        if ($this->hourly_rate_override !== null) {
            return (float) $this->hourly_rate_override;
        }

        $base = (float) ($this->lot?->hourly_rate ?? config('parking.default_hourly_rate', 20.00));
        $multiplier = config("parking.spot_types.{$this->spot_type}.multiplier", 1.0);

        return round($base * $multiplier, 2);
    }
}
