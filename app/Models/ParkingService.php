<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ParkingService extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'image',
        'icon',
        'button_text',
        'button_url',
        'status',
        'sort_order',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($service) {
            if (empty($service->slug) && !empty($service->name)) {
                $service->slug = Str::slug($service->name);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'Active')->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }
}
