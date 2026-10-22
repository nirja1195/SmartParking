<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'staff';

    protected $fillable = [
        'parking_location_id',
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function parkingLocation(): BelongsTo
    {
        return $this->belongsTo(ParkingLocation::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(ParkingEntry::class);
    }

    public function exits(): HasMany
    {
        return $this->hasMany(ParkingExit::class);
    }

    public function isManager(): bool
    {
        return $this->role === 'Parking Manager';
    }

    public function canConfirmEntry(): bool
    {
        return in_array($this->role, ['Parking Manager', 'Entry Staff']);
    }

    public function canConfirmExit(): bool
    {
        return in_array($this->role, ['Parking Manager', 'Exit Staff']);
    }
}
