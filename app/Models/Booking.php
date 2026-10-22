<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'PENDING';
    public const STATUS_CONFIRMED = 'CONFIRMED';
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_COMPLETED = 'COMPLETED';
    public const STATUS_CANCELLED = 'CANCELLED';

    public const PAYMENT_PENDING = 'PENDING';
    public const PAYMENT_PAID = 'PAID';
    public const PAYMENT_FAILED = 'FAILED';
    public const PAYMENT_CANCELLED = 'CANCELLED';
    public const PAYMENT_REFUNDED = 'REFUNDED';

    protected $fillable = [
        'booking_id',
        'user_id',
        'vehicle_id',
        'parking_location_id',
        'zone_id',
        'slot_id',
        'booking_date',
        'entry_time',
        'expected_exit_time',
        'actual_exit_time',
        'duration',
        'amount',
        'booking_status',
        'payment_status',
        'qr_code',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'entry_time' => 'datetime',
            'expected_exit_time' => 'datetime',
            'actual_exit_time' => 'datetime',
            'amount' => 'decimal:2',
            'duration' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function parkingLocation(): BelongsTo
    {
        return $this->belongsTo(ParkingLocation::class, 'parking_location_id');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ParkingZone::class, 'zone_id');
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(ParkingSlot::class, 'slot_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function parkingEntry(): HasOne
    {
        return $this->hasOne(ParkingEntry::class);
    }

    public function parkingExit(): HasOne
    {
        return $this->hasOne(ParkingExit::class);
    }

    public function isConfirmed(): bool
    {
        return $this->booking_status === self::STATUS_CONFIRMED;
    }

    public function isActive(): bool
    {
        return $this->booking_status === self::STATUS_ACTIVE;
    }

    public function isCompleted(): bool
    {
        return $this->booking_status === self::STATUS_COMPLETED;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    public function getTotalPriceAttribute(): float
    {
        return (float) ($this->amount ?? 0);
    }

    public function getTotalHoursAttribute(): int
    {
        return (int) ($this->duration ?? 1);
    }

    public function getStartTimeAttribute(): ?string
    {
        return $this->entry_time ? $this->entry_time->format('H:i') : null;
    }

    public function getEndTimeAttribute(): ?string
    {
        return $this->expected_exit_time ? $this->expected_exit_time->format('H:i') : null;
    }

    public function getParkingFeeAttribute(): float
    {
        return max(0, (float) ($this->amount ?? 0) - 2.00);
    }

    public function getConvenienceFeeAttribute(): float
    {
        return 2.00;
    }
}
