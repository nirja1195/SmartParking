<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParkingExit extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'slot_id',
        'staff_id',
        'exit_time',
        'total_duration',
        'penalty_charge',
        'total_charge',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'exit_time' => 'datetime',
            'penalty_charge' => 'decimal:2',
            'total_charge' => 'decimal:2',
            'total_duration' => 'integer',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(ParkingSlot::class, 'slot_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}
