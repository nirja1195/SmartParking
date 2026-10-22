<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingSlot extends Model
{
    use HasFactory;

    public const STATUS_AVAILABLE = 'AVAILABLE';
    public const STATUS_RESERVED = 'RESERVED';
    public const STATUS_OCCUPIED = 'OCCUPIED';
    public const STATUS_MAINTENANCE = 'MAINTENANCE';
    public const STATUS_BLOCKED = 'BLOCKED';

    protected $fillable = [
        'parking_location_id',
        'zone_id',
        'slot_number',
        'slot_type',
        'price',
        'vehicle_type',
        'floor',
        'section',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->price !== null && $this->price > 0) {
            return (float) $this->price;
        }
        return (float) ($this->parkingLocation->parking_charges ?? 20.00);
    }

    public function parkingLocation(): BelongsTo
    {
        return $this->belongsTo(ParkingLocation::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ParkingZone::class, 'zone_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'slot_id');
    }

    public function isAvailable(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    public function isReserved(): bool
    {
        return $this->status === self::STATUS_RESERVED;
    }

    public function isOccupied(): bool
    {
        return $this->status === self::STATUS_OCCUPIED;
    }

    /**
     * Color indicator for visual slot grid per specification:
     * Green: Available
     * Blue: Reserved
     * Red: Occupied
     * Gray: Maintenance
     * Dark Gray: Blocked
     */
    public function getGridColorClass(): string
    {
        return match ($this->status) {
            self::STATUS_AVAILABLE => 'slot-available', // Green
            self::STATUS_RESERVED => 'slot-reserved',   // Blue
            self::STATUS_OCCUPIED => 'slot-occupied',   // Red
            self::STATUS_MAINTENANCE => 'slot-maintenance', // Gray
            self::STATUS_BLOCKED => 'slot-blocked',     // Dark Gray
            default => 'slot-available',
        };
    }
}
