<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePageFeature extends Model
{
    protected $fillable = [
        'icon',
        'title',
        'description',
        'sort_order',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'Active')->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }
}
