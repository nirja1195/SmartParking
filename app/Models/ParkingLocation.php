<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location_type',
        'address',
        'area',
        'city',
        'state',
        'pincode',
        'latitude',
        'longitude',
        'contact_number',
        'opening_time',
        'closing_time',
        'total_capacity',
        'description',
        'facilities',
        'image',
        'parking_charges',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'parking_charges' => 'decimal:2',
            'total_capacity' => 'integer',
        ];
    }

    public function zones(): HasMany
    {
        return $this->hasMany(ParkingZone::class);
    }

    public function slots(): HasMany
    {
        return $this->hasMany(ParkingSlot::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }

    /**
     * Dynamic Available Slots: Total - (Reserved + Occupied + Maintenance + Blocked)
     */
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

    public function totalSlotsCount(): int
    {
        return $this->slots()->count();
    }

    /**
     * Helper for image URL with fallback to a default image asset.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && file_exists(public_path($this->image))) {
            return asset($this->image);
        }
        if ($this->image && str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return asset('images/locations/city-center.jpg');
    }

    /**
     * Soft pastel badge CSS class for Location Type per screenshot style.
     */
    public function getTypeBadgeClassAttribute(): string
    {
        return match (strtolower($this->location_type ?? 'commercial')) {
            'commercial' => 'badge-type-commercial',
            'airport' => 'badge-type-airport',
            'office' => 'badge-type-office',
            'transit' => 'badge-type-transit',
            'hospital' => 'badge-type-hospital',
            'educational' => 'badge-type-educational',
            'public' => 'badge-type-public',
            default => 'badge-type-commercial',
        };
    }
}
