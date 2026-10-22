<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    public const ADMIN = 'admin';
    public const CUSTOMER = 'customer';
    public const OPERATOR = 'operator';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Get users associated with this role.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
